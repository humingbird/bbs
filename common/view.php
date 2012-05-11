<?php
require_once('config/bbsConf.php');
require_once( Config::$smarty_dir. '/Smarty.class.php' );

/**
 * smartyを利用するための設定クラス
 */
class View{
	
	public $smarty;
	
	//コンストラクタ
	public function __construct(){
		$this->smarty = new Smarty;
		$this->smarty->template_dir = Config::$s_template_dir;
		$this->smarty->compile_dir = Config::$s_compile_dir;
	}
	
	/**
	 * パラメータをassignして、指定されたテンプレートをdisplayする
	 *
	 * @params string $template   テンプレート名
	 * @params array  $params     渡すパラメータ。連想配列
	 */
	function display($template,$params){
	
		if(is_array($params)){
			foreach($params as $key=>$value){
				$this->smarty->assign($key,$value);
			}
		}
		
		$this->smarty->display($template.'.tpl');
	}
}
