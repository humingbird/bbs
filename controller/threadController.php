<?php
require_once(Config::$base_path."/common/view.php");
require_once(Config::$base_path."/common/util.php");
require_once(Config::$base_path."/model/threadInfo.php");
require_once(Config::$base_path."/model/comment.php");

/**
 * 新規スレッド作成
 */
	class threadController{
		
		private $threadInfo;
		private $comment;
		private $util;
		
		//コンストラクタ
		function __construct(){
			$this->view = new View;
			$this->util = new Util;
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
			$this->view->display('thread',array('home'=>Config::$home_url));
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
			//登録したthreadIdを取得してくる
			$threadId = $this->threadInfo->selectThreadId($postData['name']);
			
			//commentテーブルにinsert
			$this->comment->insert($threadId,$postData);
			
			//リダイレクト
			header("Location:".Config::$home_url);

		}
	}

?>
