<?php
require_once(Config::$base_path.'/common/view.php');
require_once(Config::$base_path.'/common/util.php');
require_once(Config::$base_path.'/model/threadInfo.php');
require_once(Config::$base_path.'/model/comment.php');
if(!Config::$debug){
	require_once(Config::$base_path.'/common/login.php');
}

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
		if(!Config::$debug){
			$this->fb = new fbLogin;
		}
		$this->util = new Util;
		$this->view = new View;

		$this->threadInfo = new threadInfo;
		$this->comment = new comment;
	
		$path = $_GET['regist'];
		if($path == 1){
			$this->regist();
		}else{ 
			$limit = $_GET['limit'];
			$this->exec($limit);
		}
	}
	
	/**
	 * スレッド記事の表示
	 *
	 * @params string $limit  表示件数
	 */
	public function exec($limit = null){
		//ログインしているかどうか
		if(!Config::$debug){
			$login = $this->fb->checkLogin();
			if(!$login){
				$url = $this->fb->getLoginUrl();
			}else{
				$url='';
			}
		}else{
			$login =1;
		}
		//threadIdをもとにthread_infoとcommentテーブルの情報を取得する
		$threadId = $_GET['id'];
		
		//とりあえず数字かどうかだけ確認する
		if(is_numeric($threadId)){		
			$info = $this->threadInfo->selectForId($threadId);
		}
		$commentData = $this->comment->select($threadId);
		$comment = unserialize($commentData['comment']);

		$comment = $this->util->checkCommentLink($comment);
		$comment = $this->util->checkComment($comment);
		$flag = 0;
		if(count($comment)>Config::MAXCOUNT){
			$flag = 1;
		}
		//表示件数を制限する（nullの場合は何もしないで返す）
		$comment = $this->setDisplayComment($comment,$limit);

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
	
	/**
	 * コメントの表示件数を制限する
	 * 
	 * @params arrya  $comment コメントデータ
	 * @params stirng $limit   表示件数
	 * @return array           表示件数分のコメント
	 */
	 public function setDisplayComment($comment,$limit){
		if($limit == 50){
			foreach($comment as $key=>$value){
				$date[$key]= $value['created'];
			}
			arsort($date);
			foreach($date as $key=>$value){
				$sorted[] = $comment[$key];
			}
			$comment = $sorted;
	 		$result = array_chunk($comment,(int)$limit,TRUE);
	 		$comment = $result[0];
		}else if($limit){
		 	$result = array_chunk($comment,(int)$limit,TRUE);
	 		$comment = $result[0];
		}

	 	return $comment;
	 }
	public function error(){
		$this->view->display('error',array('home'=>Config::$home_url));
	}

}
