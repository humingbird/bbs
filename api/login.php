<?php
require_once('../config/bbsConf.php');
if(!Config::$debug){
	require_once(Config::$base_path.'/common/login.php');
}
class login{
	
	public function __construct(){
		//ログインしているかどうか
		if(!Config::$debug){
			$fb = new fbLogin;
			$login = $fb->checkLogin();
			if(!$login){
				$url = $fb->getLoginUrl();
				$profile='';
			}else{
				$url='';
				$profile = $fb->getUserInfo();
			}
		}else{
			$login = 1; //ローカル上では常にログイン状態にする
			$url = '';
			$profile='';
		}
		if($url !=''){
			$url = str_replace('www','m',$url);
		}
		$result = array('login'=>$login,'url'=>$url,'profile'=>$profile);
		
		//取ってきたデータをそのままjson_encode
		header("Content-Type: text/javascript; charset=utf-8");
		echo json_encode($result);
	}
}

$instance = new login;		
