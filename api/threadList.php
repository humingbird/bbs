<?php
require_once('../config/bbsConf.php');
require_once(Config::$base_path.'/model/threadInfo.php');
require_once(Config::$base_path.'/model/comment.php');
class threadList{
	
	private $threadInfo;
	private $comment;
	
	public function __construct(){
		$params = $_GET;
		if($params['next']){
			//DBから取得してくる
			$this->threadInfo = new threadInfo;
			$this->comment = new comment;
			$threadList = $this->threadInfo->selectNextList((int)$params['next']);
			if($threadList){
				foreach($threadList as $key=>$value){
					$comment[] = $this->comment->select($value['id']);
				}
			}
			foreach($comment as $key=>$value){
				if($value['comment'] !=''){
					$commentList[$value['thread_id']] = unserialize($value['comment']);
				}
			}
			
			foreach($threadList as $key=>$value){
				$threadList[$key]['responce'] = $commentList[$value['id']];
			}
			//取ってきたデータをそのままjson_encode
			header("Content-Type: text/javascript; charset=utf-8");
			echo json_encode($threadList);
			//そのまま返す
		}
	}
}

$construct = new threadList;
