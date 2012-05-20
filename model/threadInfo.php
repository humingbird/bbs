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
			':email'=>$postData['email'],':description'=>$postData['comment'],
			':fb_url'=>($postData['fb_url'])?$postData['fb_url'] : null
			);
		
		$sql = 'insert into `thread_info`(`title`,`name`,`email`,`fb_url`,`description`,`created`,`updated`)
			values(:title,:name,:email,:fb_url,:description,NOW(),NOW())';
			
		$state = $this->pdoExecute($sql,$params);

		//新規登録後はリストが変わるのでキャッシュを削除する
		if($pastData = $this->memcache->get(self::NEW_THREAD)){
			$this->memcache->delete(self::NEW_THREAD);
			$this->memcache->delete(self::TITLE_LIST);
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
	public function selectThreadList($limit = 10){
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
			$this->memcache->set(self::NEW_THREAD,$row);
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
	 * 更新時刻の更新
	 *
	 * @params int $threadId  スレッドid
	 */
	public function updateTime($threadId){
		$params =  array(':thread_id'=>$threadId);
		$sql = 'update `thread_info` set `updated`= NOW() where `id`=:thread_id';
		
		$state = $this->pdoExecute($sql,$params);
		
		//新規登録後はリストが変わるのでキャッシュを削除する
		if($pastData = $this->memcache->get(self::TITLE_LIST)){
			$this->memcache->delete(self::TITLE_LIST);
		}
		
	}
	
	/**
	 * スレッド一覧用に更新がかかったもの順でリストを取得する
	 *
	 * @return array  スレッドタイトル
	 */
	public function selectTitleList(){
		if(!$rows = $this->memcache->get(self::TITLE_LIST)){
			$sql = 'select `id`,`title` from `thread_info` order by `updated` desc';
		
			//DBの接続
			$db = new DbConnection;
			$conn = $db->connect();

			$stmt = $conn->prepare($sql);
    		$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$this->memcache->set(self::TITLE_LIST,$rows);
		}
		return $rows;
	}
	
	/**
	 * 指定数*10件目までのスレッド一覧を１０件取得する
	 *
	 * @params int $next  指定数
	 * @return array スレッド一覧
	 */
	public function selectNextList($next){
		$start = 10*$next-10;
		$end = 10*$next;
		$sql = sprintf('select * from `thread_info` order by `created` desc limit %d,%d',$start,$end);
		
		//DBの接続
		$db = new DbConnection;
		$conn = $db->connect();

		$stmt = $conn->prepare($sql);
    	$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return $rows;
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
	
