<?php
require_once(Config::$base_path.'/common/view.php');
require_once(Config::$base_path.'/common/login.php');
require_once(Config::$base_path.'/common/util.php');
require_once(Config::$base_path.'/model/threadInfo.php');
require_once(Config::$base_path.'/model/comment.php');

class indexController{
	public $view;
	private $threadInfo;
	private $comment;
	private $util;
	private $fb;

	//コンストラクタ
	function __construct(){
		$this->view = new View;
		$this->util = new Util;
		
		$this->threadInfo = new threadInfo;
		$this->comment = new comment;

		$this->fb = new fbLogin;
		
		$path = $_GET['regist'];
		if($path == 1){
			$this->regist();
		}else{
			$this->exec();
		}
	}
	
	/**
	 * とりあえず表示する
	 */
	function exec(){
		$login = $this->fb->checkLogin();

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
		$this->view->display('index',array('list'=>$list,'comment'=>$commentList,'login'=>$login));
	}
	
	public function regist(){
		//postされた要素を取得
		$postData = $_POST;
		
		//バリデーション処理
			$postData = $this->util->checkParams($postData);
		//update
			$this->comment->insert($postData['threadId'],$postData);
		
		//topページにリダイレクト
			header("Location:".Config::$home_url);
	}
}
