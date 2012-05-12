<?php
require_once("config/bbsConf.php");

//autoload
spl_autoload_register(array('router','autoload'));

class router{
	
	public function __construct(){
		$path = $_GET;
		if($path['page']){
			$className = $path['page'].'Controller';
			$instance = new $className;
		}else{
			$instance = new indexController;
		}
	}
	
	public static function autoload($class){
		if(preg_match('/.*Controller$/', $class)){
			$file = Config::$base_path.'/controller/'.$class.'.php';
			if(file_exists($file)){
				require_once $file;
			}else{
				echo "error";
				die();
			}
		}
	}
}
