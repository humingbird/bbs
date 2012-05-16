<?php
require(Config::$base_path.'/common/dbConnection.php');
require(Config::$base_path.'/common/cache.php');

/**
 * thread_infoテーブルのdaoクラス
 */
class threadInfo{

	const NEW_THREAD = 'new_thread';
	const THREAD  = 'thread_';
	const TITLE_LIST = 'title_list';
	
	private $memcache;

	//コンストラクタ
	public function __construct(){
		$cache = new cache;
		$this->memcache = $cache->connect();
	}

	
	/**
	 * postされたスレッドデータを新規登録する
	 *
	 * @params array $postData  postされたスレッド情報
	 * @return boolean 			登録成功したかどうか
	 */
	public function insert($postData){
		$params = array(':title'=>$postData['title'],':name'=>$postData['name'],
			':email'=>$postData['email'],':description'=>$postData['comment']);
		
		$sql = 'insert into `thread_info`(`title`,`name`,`email`,`description`,`created`,`updated`)
			values(:title,:name,:email,:description,NOW(),NOW())';
			
		$state = $this->pdoExecute($sql,$params);

		//新規登録後はリストが変わるのでキャッシュを削除する
		if($pastData = $this->memcache->get(self::NEW_THREAD)){
			$this->memcache->delete(self::NEW_THREAD);
		}
		return $state;
	}
	
	/**
	 * 登録したthreadIDを取得する
	 * 
	 * @params string $name  スレッドを登録したユーザーの名前
	 * @return int           そのユーザ名の最新登録時のid
	 */
	public function selectThreadId($name){
		$params = array(':name'=>$name);
		$sql = 'select `id`,`name` from thread_info where `name`=:name order by id desc';
				
		//DBの接続
		$db = new DbConnection;
		$conn = $db->connect();

		$stmt = $conn->prepare($sql);
    		$stmt->execute($params);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $row['id'];
	}
	
	/**
	 * 指定件数分の最新のスレッド情報を取得してくる
	 * 
	 * @params int $limit    何件分取得するか
	 * @return array         スレッド情報
	 */
	public function selectThreadList($limit = 3){
		
		//memcacheにデータが無いか探しに行く
		
		if(!$row = $this->memcache->get(self::NEW_THREAD)){
			$sql = sprintf('select * from thread_info order by id desc limit %d',$limit);	
			
			//DBの接続
			$db = new DbConnection;
			$conn = $db->connect();

			$stmt = $conn->prepare($sql);
    			$stmt->execute();

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

			//取得したデータをmemcacheにセットする
			if($limit == 10){
				$this->memcache->set(self::TITLE_LIST,$row);
			}else{
				$this->memcache->set(self::NEW_THREAD,$row);
			}
		}

		return $row;
	}
	
	/**
	 * idから情報を取得する
	 * @params int $id スレッドid
	 * @return array   スレッド情報
	 */
	public function selectForId($id){
		if(!$row = $this->memcache->get(self::THREAD.$id)){
			$params = array(':id'=>$id);
			$sql = 'select * from thread_info where id=:id';
		
			//DBの接続
			$db = new DbConnection;
			$conn = $db->connect();

			$stmt = $conn->prepare($sql);
    			$stmt->execute($params);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
			$this->memcache->set(self::THREAD.$id,$row);
		}
		return $row;
	}
	
	/**
	 * PDOの実行
	 *
	 * @params string $sql    SQL文
	 * @params array  $values パラメータ
	 * @return boolean 処理が成功したかどうか
	 */
	private function pdoExecute($sql,$values){
		//DBの接続
		$db = new DbConnection;
		$conn = $db->connect();
		
		$stmt = $conn->prepare($sql);
		$state = $stmt->execute($values);
		
		return $state;
	}
		
}
	
