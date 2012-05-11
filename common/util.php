<?php

/**
 * 複数のクラスで利用できるメソッドを置いておくクラス
 */
 
class Util{
	
	/**
	 * 必須項目の空欄チェック
	 *
	 * @params array $postData  postされたスレッドデータ
	 */
	public function checkUndefined($postData){
		$check = false;

		if($postData['title'] === ""){
			$check = true;
			$errorComment['title'] = 1;
		}
		if($postData['comment'] === ""){
			$check = true;
			$errorComment['comment'] =1;
		}
		//引っかかった場合は警告コメントの種類判別のためにパラメータを付与してリダイレクト
		if($check){
			foreach($errorComment as $key=>$value){
				$error = $error.$key.'='.$value.'&';
			}
			$error = rtrim($error,'&');
		
			header("Location:".Config::$home_url.'thread.html?'.$error);
			exit;
		}
	}
		
	/**
	 * 文字数制限チェック
	 *
	 * @params array $postData  postされたスレッドデータ
	 */
	public function countWords($postData){
		$check = false;
			
		if(array_key_exists('title',$postData)){
			if(mb_strlen($postData['title'])>100){
				$check = true;
				$errorComment['title']=2;
			}
		}
		if(mb_strlen($postData['name'])>30){
			$check = true;
			$errorComment['name']=2;
		}
		if(mb_strlen($postData['email'])>30){
			$check = true;
			$errorComment['email']=2;
		}
		if(mb_strlen($postData['comment'])>300){
			$check = true;
			$errorComment['comment']=2;
		}
		//引っかかった場合は警告コメントの種類判別のためにパラメータを付与してリダイレクト
		if($check){
			foreach($errorComment as $key=>$value){
				$error = $error.$key.'='.$value.'&';
			}
			$error = rtrim($error,'&');
			
			header("Location:".Config::$home_url.'thread.html?'.$error);
			exit;
		}
	}
	
	/**
	 * メールアドレスかどうか（簡易的に）チェックする
	 *
	 * @params string $email  postされたメールアドレス
	 */
	public function checkEmail($email){
		$check = false;
		
		if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
			$check = true;
			$errorComment['email'] = 3;
		}
		//引っかかった場合は警告コメントの種類判別のためにパラメータを付与してリダイレクト
		if($check){
			foreach($errorComment as $key=>$value){
				$error = $error.$key.'='.$value.'&';
			}
			$error = rtrim($error,'&');
				
			header("Location:".Config::$home_url.'thread.html?'.$error);
			exit;
		}
	}
}
 