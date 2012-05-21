<?php
require_once('../config/bbsConf.php');
require_once(Config::$base_path.'/model/threadInfo.php');
require_once(Config::$base_path.'/model/comment.php');
require_once(Config::$base_path.'/common/util.php');
class threadList{
	
	private $threadInfo;
	private $comment;
	private $util;
	
	public function __construct(){
		$params = $_GET;
		if($params['next']){
			//DBから取得してくる
			$this->threadInfo = new threadInfo;
			$this->comment = new comment;
			$this->util = new util;
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
			
			foreach($commentList as $key=>$value){
				$flag[$key] = 0;
				if(count($value) > Config::MAXCOUNT){
					$flag[$key] = 1;
				}
				$value = $this->util->sortComment($value,10);
			
				//コメントの内容チェック（urlリンク）
				$value = $this->util->checkCommentLink($value);
				$value = $this->util->checkComment($value);
				$commentList[$key] = $value;
			}
			
			foreach($threadList as $key=>$value){
				$threadList[$key]['flag'] = $flag[$value['id']];
				$threadList[$key]['responce'] = $commentList[$value['id']];
			}
			
			//取ってきたデータをそのままjson_encode
			header("Content-Type: text/javascript; charset=utf-8");
			echo json_encode($threadList);
			//そのまま返す
		}
	}
}

$instance = new threadList;
