<?php
/**
 * memcacheの接続設定クラス
 */
class cache{

	public function connect(){
		$memcache = new Memcache;
		$memcache->connect(Config::$mem_host,Config::$mem_port)or die('memcache:接続できませんでした');
		return $memcache;
	}

}
