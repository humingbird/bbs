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
		$mode = $_GET['mode'];
		if($path == 1){
			$this->regist();
		}else if($mode === 'logout'){
			$this->logout();
			$this->exec();
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
				$url= '?mode=logout';
				$profile = $this->fb->getUserInfo();
			}
		}else{
			$login = 1; //ローカル上では常にログイン状態にする
			$profile='';
		}
		
		//UAの取得
		$ua = $_SERVER['HTTP_USER_AGENT'];
		//PC/Android/iPhoneのパターンわけ
		$deviceType = $this->util->setDeviceType($ua);
		
		//スマートフォンの場合はfbログインページをモバイル用ページにする
		if($deviceType != 'pc' && $url !=''){
			$url = str_replace('www','m',$url);
		}
		
		//スレッド情報の取得(PCは最新10件,spは５件ずつ表示
		//if($deviceType === 'pc'){
			//echo 'pc mode';
			//$list = $this->threadInfo->selectUpdateList();
		//}else{
			$list = $this->threadInfo->selectUpdateList(5);
		//}
		//スレッドごとのコメント情報の取得
		foreach($list as $key=>$value){
			$comment[] = $this->comment->select((int)$value['id']);
		}
		foreach($comment as $key=>$value){
			if($value['comment'] !='' || $comment[$key] != false){
				$commentList[$value['thread_id']] = unserialize($value['comment']);
			}
		}
		if($commentList){
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
		//getパラメータの確認
		$mode = $_GET['mode'];
		//postされた要素を取得
		$postData = $_POST;

		//バリデーション処理
			$postData = $this->util->checkParams($postData,null,$mode);
		//update
			$this->comment->insert($postData['threadId'],$postData);
		//スレッドの更新時刻もupdate
			$this->threadInfo->updateTime((int)$postData['threadId']);
		
		//topページにリダイレクト
			header("Location:".Config::$home_url);
	}
	
	/**
	 * fbログアウト処理
	 */
	public function logout(){
		$this->fb->fbLogout();
	}
	
	public function error(){
		$this->view->display('error',array('home'=>Config::$home_url));
	}
}
