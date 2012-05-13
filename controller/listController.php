<?php
require_once(Config::$base_path.'/common/view.php');
require_once(Config::$base_path.'/common/util.php');
require_once(Config::$base_path.'/model/threadInfo.php');
require_once(Config::$base_path.'/model/comment.php');

/**
 * 一覧表示
 */
class listController{
	public $view;
	private $threadInfo;
	private $comment;
	
	public function __construct(){
		$this->view = new View;

		$this->threadInfo = new threadInfo;
		$this->comment = new comment;
		
		$this->exec();
	}
	
	public function exec(){
		//threadIdをもとにthread_infoとcommentテーブルの情報を取得する
		$threadId = $_GET['id'];
		
		//とりあえず数字かどうかだけ確認する
		if(is_numeric($threadId)){		
			$info = $this->threadInfo->selectForId($threadId);
		}
		$commentData = $this->comment->select($threadId);
		$comment = unserialize($commentData['comment']);

		$this->view->display('list',array('info'=>$info,'comment'=>$comment));
	}
}