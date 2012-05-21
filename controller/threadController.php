<?php
require_once(Config::$base_path."/common/view.php");
require_once(Config::$base_path."/common/util.php");
require_once(Config::$base_path."/model/threadInfo.php");
require_once(Config::$base_path."/model/comment.php");
if(!Config::$debug){
	require_once(Config::$base_path.'/common/login.php');
}

/**
 * 新規スレッド作成
 */
	class threadController{
		
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
			
			$error = $_GET['error_reason'];
			if($error){
				$this->error();
				exit;
			}
			//modelクラスの呼び出し
			$this->threadInfo= new threadInfo;
			$this->comment = new comment;
			
			$path = $_GET['regist'];
			if($path ==1){
				$this->regist();
			}else{
				$this->exec();
			}
		}
		
		function exec(){
			//ログインしてるかどうか
			if(!Config::$debug){
				$login = $this->fb->checkLogin();
				if(!$login){
					$url = $this->fb->getLoginUrl();
					$profile ='';
				}else{
					$url='';
					$profile = $this->fb->getUserInfo();
				}
			}else{
				$login = 1; //ローカル上では常にログイン設定
				$profile = '';
			}
			
			//UAの取得
			$ua = $_SERVER['HTTP_USER_AGENT'];
			//PC/Android/iPhoneのパターンわけ
			$deviceType = $this->util->setDeviceType($ua);
		
			//スマートフォンの場合はfbログインページをモバイル用ページにする
			if($deviceType != 'pc' && $url !=''){
				$url = str_replace('www','m',$url);
			}	
			$this->view->display('thread',array('home'=>Config::$home_url,'login'=>$login,'fb_url'=>$url,'profile'=>$profile,'device'=>$deviceType));
		}
		/**
		 * 処理の実行
		 */
		function regist(){
			$postData = $_POST;
			
			//バリデーションチェック
			$postData = $this->util->checkParams($postData,'thread');
			
			//modelクラスを呼び出して、thread_infoテーブルに基本情報をinsert
			//登録したthread_idを基にcommentテーブルにもinsert
						
			$flag = $this->threadInfo->insert($postData);
			
			//登録失敗したときはリダイレクト
			if(!$flag){
				header("Location:".Config::$home_url.'/?page=thread&db=1');
				exit;
			}
			//リダイレクト
			header("Location:".Config::$home_url);

		}
	}

?>
