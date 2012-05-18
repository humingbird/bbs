<?php
/**
 * commentテーブルのdaoクラス
 */
class comment{
	
	const COMMENT = 'comment_';
	private $memcache;

	//コンストラクタ
	public function __construct(){
		*$cache = new cache;
		$this->memcache = $cache->connect();
	}
	
	/**
	 * postされたコメントを登録する
	 *
	 * @params int   $threadId  そのポストが属するスレッドのid
	 * @params array $postData  postされたコメント（またはスレッド）情報
	 * @return boolean 			登録成功したかどうか
	 */
	public function insert($threadId,$postData = null){
		//既に登録されているスレッドかどうかを調べる
		$pastData = $this->select($threadId);

		if($pastData){
			$comment = unserialize($pastData['comment']);
			$key = count($comment)+1;
			
			if(count($comment) == Config::MAXCOUNT){
				echo '書き込み件数が1000を越えたので書き込みできません';
				header('Location:'.Config::$home_url.'?thread_max=1');
				exit;
			}
			
		}else{
			$key=1;
		}
		//名前.email.コメント.投稿時刻をひとつの配列にしてシリアライズ
		$comment[]=array('id'=>$key,'name'=>$postData['name'],'email'=>$postData['email'],
				'description'=>$postData['comment'],'fb_url'=>($postData['fb_url'])?$postData['fb_url'] : null,
				'created'=>date('Y-m-d H:i:s')
			);
		$data = serialize($comment);
			
		$params = array(':thread_id'=>$threadId,':comment'=>$data);
		
		if($pastData){
			$sql = 'update `comment` set `comment`=:comment,`updated`= NOW() where `thread_id`=:thread_id';
		}else{
			$sql = 'insert into `comment`(`thread_id`,`comment`,`created`,`updated`)
				values(:thread_id,:comment,NOW(),NOW())';
		}
		$state = $this->pdoExecute($sql,$params);
	
		//新規登録後は現在のキャッシュを削除
		if($cache = $this->memcache->get(self::COMMENT.$threadId)){
			$this->memcache->delete(self::COMMENT.$threadId);
		}
		return $state;
	}
	
	/**
	 * スレッドIDからコメント情報を取得する
	 * 
	 * @params int $threadId  スレッドId
	 * @return array          そのスレッドidで登録されているコメント情報
	 */
	public function select($threadId){

		if(!$row = $this->memcache->get(self::COMMENT.$threadId)){
			$params = array(':thread_id'=>$threadId);
			$sql = 'select * from comment where `thread_id`=:thread_id';
				
			//DBの接続
			$db = new DbConnection;
			$conn = $db->connect();

			$stmt = $conn->prepare($sql);
    			$stmt->execute($params);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$this->memcache->set(self::COMMENT.$threadId,$row);
		}
		return $row;
	}
	
	/**
	 * 更新がかかった順にスレッドidを取得する
	 *
	 public function getThreadListByUpdate(){
	 	$sql = 'select thread_id from `comment` order by `updated` desc';
	 	
	 	//DBの接続
		$db = new DbConnection;
		$conn = $db->connect();

		$stmt = $conn->prepare($sql);
    	$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return $rows;
	}*/
	
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
	
