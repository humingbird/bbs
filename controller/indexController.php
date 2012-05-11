<?php
require_once('common/view.php');
require_once('model/threadInfo.php');
require_once('model/comment.php');

class indexController{
	public $view;
	private $threadInfo;
	private $comment;
	
	//コンストラクタ
	function __construct(){
		$this->view = new View;
		$this->threadInfo = new threadInfo;
		$this->comment = new comment;
	}
	
	/**
	 * とりあえず表示する
	 */
	function exec(){
	
		$list = $this->threadInfo->selectThreadList();
		foreach($list as $key=>$value){
			$comment[] = $this->comment->select((int)$value['id']);
		}
		
		foreach($comment as $key=>$value){
			$commentList[$value['thread_id']] = unserialize($value['comment']);
		}
		foreach($commentList as $key=>$value){
			if(count($value)>=3){
				$arr =  array_chunk($value,3,TRUE);
				$commentList[$key] = $arr[0];
			}
		}
		$this->view->display('index',array('list'=>$list,'comment'=>$commentList));
	}
	
	//modelクラスから最新スレッドの情報を取得
	//取得したスレッドidから記事情報を取得してくる
	//よしなに形を作ったら、smartyにassign,index.tplを呼ぶ
}