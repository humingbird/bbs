<?php
require_once("../config/bbsConf.php");
require_once("../common/util.php");
require_once("../model/threadInfo.php");
require_once("../model/comment.php");

/**
 * 新規スレッド作成
 */
	class threadController{
		
		private $threadInfo;
		private $comment;
		private $util;
		
		//コンストラクタ
		function __construct(){
			//modelクラスの呼び出し
			$this->util = new Util;
			$this->threadInfo= new threadInfo;
			$this->comment = new comment;
		}
		
		/**
		 * 処理の実行
		 */
		function exec(){
			$postData = $_POST;
			
			//バリデーションチェック
			$postData = $this->checkParams($postData);
			
			//modelクラスを呼び出して、thread_infoテーブルに基本情報をinsert
			//登録したthread_idを基にcommentテーブルにもinsert
						
			$flag = $this->threadInfo->insert($postData);
			
			//登録失敗したときはリダイレクト
			if(!$flag){
				header("Location:".Config::$home_url.'/thread.html?db=1');
				exit;
			}
			//登録したthreadIdを取得してくる
			$threadId = $this->threadInfo->selectThreadId($postData['name']);
			
			//commentテーブルにinsert
			$this->comment->insert($threadId,$postData);
			
			//リダイレクト
			header("Location:".Config::$home_url);

		}
		/**
		 * バリデーション処理
		 *
		 * @params array $postData  postされたスレッドデータ
		 * @return array 			チェックを通ったデータ
		 */
		public function checkParams($postData){
			
			$this->util->checkUndefined($postData);
			$this->util->countWords($postData);
			$this->util->checkEmail($postData['email']);
			
			foreach($postData as $key=>$value){
				$postData[$key] = htmlspecialchars($value);
			}
			return $postData;
		}
	}

//あんまり形よくないけどこれでインスタンス生成
$instance = new threadController;
$instance->exec();
?>