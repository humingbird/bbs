<?php
/**
 * DBの接続設定をするクラス
 */

class DbConnection{

	public function connect(){
		$dsn = Config::$dsn;
		$user= Config::$user;
		$password= Config::$password;
		
		try{
			$dbh = new PDO($dsn,$user,$password);
		}catch(PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
		return $dbh;
	}
}

	