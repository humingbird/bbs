<?php
/**
 * commentテーブルのdaoクラス
 */
class comment{
	
	/**
	 * postされたコメントを登録する
	 *
	 * @params int   $threadId  そのポストが属するスレッドのid
	 * @params array $postData  postされたコメント（またはスレッド）情報
	 * @return boolean 			登録成功したかどうか
	 */
	public function insert($threadId,$postData){
		//既に登録されているスレッドかどうかを調べる
		$pastData = $this->select($threadId);
		
		if($pastData){
			$comment = unserialize($pastData['comment']);
		}
		
		//名前.email.コメント.投稿時刻をひとつの配列にしてシリアライズ
		$comment[]=array('name'=>$postdata['name'],'email'=>$postData['email'],
				'description'=>$postData['comment'],'created'=>date('Y-m-d H:i:s')
			);
		$data = serialize($comment);
			
		$params = array(':thread_id'=>$threadId,':comment'=>$data);
		
		if($pastData){
			$sql = 'update `comment` set `comment`=:comment where `thread_id`=:thread_id';
		}else{
			$sql = 'insert into `comment`(`thread_id`,`comment`,`created`,`updated`)
				values(:thread_id,:comment,NOW(),NOW())';
		}
		$state = $this->pdoExecute($sql,$params);
		
		return $state;
	}
	
	
	/**
	 * スレッドIDからコメント情報を取得する
	 * 
	 * @params int $threadId  スレッドId
	 * @return array          そのスレッドidで登録されているコメント情報
	 */
	public function select($threadId){
		$params = array(':thread_id'=>$threadId);
		$sql = 'select * from comment where `thread_id`=:thread_id';
				
		//DBの接続
		$db = new DbConnection;
		$conn = $db->connect();

		$stmt = $conn->prepare($sql);
    	$stmt->execute($params);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
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
	