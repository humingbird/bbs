<?php
require_once(Config::$fb_dir.'/src/facebook.php');
/**
 * facebookアカウントを利用したログイン処理
 */
class fbLogin {
	private $fb;

	public function __construct(){
		$this->fb = new Facebook(array(
			'appId'=>Config::$fb_appId,
			'secret'=>Config::$fb_secret,
		));
	}

	/**
	 * ログイン状態をチェックする
	 * 
	 * @return bool ログインしてればtrue
	 */	
	public function checkLogin(){
		$userId = $this->fb->getUser();
		if($userId){
			return true;
		}else{
			return false;
		}
	}

	/**
 	 * ログイン用のurlを生成する
	 *
	 * @return string fbのログイン（アプリ認証）ページ
	 */
	public function getLoginUrl(){
		$url = $this->fb->getLoginUrl();
		if($url){
			return $url;
		}else{
			return false;
		}
	}

}
