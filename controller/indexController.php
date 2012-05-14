<?php
require_once(Config::$base_path.'/common/view.php');
require_once(Config::$base_path.'/common/login.php');
require_once(Config::$base_path.'/common/util.php');
require_once(Config::$base_path.'/model/threadInfo.php');
require_once(Config::$base_path.'/model/comment.php');

/**
 * topページ用のコントローラクラス
 */
class indexController{

	public $view;
	private $threadInfo;
	private $comment;
	private $util;
	private $fb;

	//コンストラクタ
	function __construct(){
		$this->fb = new fbLogin;
		
		$this->view = new View;
		$this->util = new Util;
	
		//ログインエラー時はエラーページに遷移
		$error = $_GET['error_reason'];
		if($error){
			$this->error();
			exit;
		}

		$this->threadInfo = new threadInfo;
		$this->comment = new comment;
		
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
		//ログインしているかどうか
		$login = $this->fb->checkLogin();

		if(!$login){
			$url = $this->fb->getLoginUrl();
		}else{
			$url='';
		}

		$list = $this->threadInfo->selectThreadList();
		foreach($list as $key=>$value){
			$comment[] = $this->comment->select((int)$value['id']);
		}
		
		foreach($comment as $key=>$value){
			$commentList[$value['thread_id']] = unserialize($value['comment']);
		}
		foreach($commentList as $key=>$value){
			$flag[$key] = 0;
			if(count($value)>=3){
				$arr =  array_chunk($value,3,TRUE);
				$commentList[$key] = $arr[0];

				if(count($value) > Config::MAXCOUNT){
					$flag[$key] = 1;
				}
			}
		}
		$this->view->display('index',array('list'=>$list,'comment'=>$commentList,'login'=>$login,'login_url'=>$url,'flag'=>$flag));
	}

	/**
 	 * コメントの登録処理
	 */	
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
	
	public function error(){
		$this->view->display('error',array('home'=>Config::$home_url));
	}
}
