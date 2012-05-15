<?php
class Config{
	
	const MAXCOUNT = 1000;
	public static $base_path ="";
	public static $home_url = "";
	//ローカルとvpsの設定の切り替え（FBのsdkがローカルだと動かないのでそこらへんの調整用）
	//TODO:FBログイン関係の処理をutilがどっかでまとめないといちいち面倒
	public static $debug = false;
	
	//smarty
	public static $smarty_dir = '';
	public static $s_template_dir = '';
	public static $s_compile_dir = '';
	
	//fbLogin
	public static $fb_dir = '';
	public static $fb_appId = ''; 
	public static $fb_secret = '';

	//db
	public static $dsn = '';
	public static $user='';
	public static $password=;

	//memcache
	public static $mem_host = '';
	public static $mem_port =;
}
	
