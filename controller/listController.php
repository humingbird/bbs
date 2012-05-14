<?php
require_once(Config::$base_path.'/common/view.php');
require_once(Config::$base_path.'/common/login.php');
require_once(Config::$base_path.'/common/util.php');
require_once(Config::$base_path.'/model/threadInfo.php');
require_once(Config::$base_path.'/model/comment.php');

/**
 * 一覧表示
 */
class listController{
	
	public $view;
	private $util;
	private $threadInfo;
	private $comment;
	private $fb;
	
	public function __construct(){
		$this->fb = new fbLogin;

		$this->util = new Util;
		$this->view = new View;

		$this->threadInfo = new threadInfo;
		$this->comment = new comment;
	
		$path = $_GET['regist'];
		if($path == 1){
			$this->regist();
		}else{	
			$this->exec();
		}
	}
	
	public function exec(){
		//ログインしているかどうか
		$login = $this->fb->checkLogin();
		if(!$login){
			$url = $this->fb->getLoginUrl();
		}else{
			$url='';
		}
		//threadIdをもとにthread_infoとcommentテーブルの情報を取得する
		$threadId = $_GET['id'];
		
		//とりあえず数字かどうかだけ確認する
		if(is_numeric($threadId)){		
			$info = $this->threadInfo->selectForId($threadId);
		}
		$commentData = $this->comment->select($threadId);
		$comment = unserialize($commentData['comment']);

		$flag = 0;
		if(count($comment)>Config::MAXCOUNT){
			$flag = 1;
		}

		$this->view->display('list',array('info'=>$info,'comment'=>$comment,'login'=>$login,'login_url'=>$url,'flag'=>$flag));
	}

	/**
 	 * コメントの登録処理
	 */	
	public function regist(){
		//postされた要素を取得
		$postData = $_POST;
	
		//バリデーション処理
			$postData = $this->util->checkParams($postData,'list');
		//update
			$this->comment->insert($postData['threadId'],$postData);
		
		//topページにリダイレクト
			header("Location:".Config::$home_url.'?page=list&id='.$postData['threadId']);
	}
	
	public function error(){
		$this->view->display('error',array('home'=>Config::$home_url));
	}

}
