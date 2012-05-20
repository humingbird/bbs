<?php
require_once(Config::$base_path.'/common/view.php');
require_once(Config::$base_path.'/common/util.php');
require_once(Config::$base_path.'/model/threadInfo.php');
require_once(Config::$base_path.'/model/comment.php');
if(!Config::$debug){
	require_once(Config::$base_path.'/common/login.php');
}

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
		if(!Config::$debug){
			$this->fb = new fbLogin;
		}
		
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
		if(!Config::$debug){
			$login = $this->fb->checkLogin();
			if(!$login){
				$url = $this->fb->getLoginUrl();
				$profile='';
			}else{
				$url='';
				$profile = $this->fb->getUserInfo();
			}
		}else{
			$login = 1; //ローカル上では常にログイン状態にする
			$profile='';
		}
		
		//UAの取得
		$ua = $_SERVER['HTTP_USER_AGENT'];
		//PC/Android/iPhoneかが分かるように正規表現分岐とか作る
		$deviceType = $this->util->setDeviceType($ua);
		
		//スレッド情報の取得(PCは最新10件,spは５件ずつ表示（CSS側で表示制御）
		$list = $this->threadInfo->selectThreadList();
		//スレッドごとのコメント情報の取得
		foreach($list as $key=>$value){
			$comment[] = $this->comment->select((int)$value['id']);
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
		//スレッド一覧用
		$titleList = $this->threadInfo->selectTitleList();
		
		$this->view->display('index',array('list'=>$list,'comment'=>$commentList,'login'=>$login,
			'login_url'=>$url,'flag'=>$flag,'title'=>$titleList,'profile'=>$profile,'device'=>$deviceType));
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
		//スレッドの更新時刻もupdate
			$this->threadInfo->updateTime((int)$postData['threadId']);
		
		//topページにリダイレクト
			header("Location:".Config::$home_url);
	}
	
	public function error(){
		$this->view->display('error',array('home'=>Config::$home_url));
	}
}
