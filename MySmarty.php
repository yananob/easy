<?php
/**
 * 
 */

// INCLUDES
require_once('Define.php');
require_once("$INCLUDE_PATH/smarty/Smarty.class.php");

class MySmarty extends Smarty {
	
	function MySmarty($currentPath) {
		
		// クラスのコンストラクタ。これらは新しいインスタンスで自動的にセットされる
		$this->Smarty();
		
		$this->template_dir	= "$currentPath/templates/";
		$this->compile_dir	= "$currentPath/templates_c/";
		$this->config_dir	= "$currentPath/configs/";
		$this->cache_dir	= "$currentPath/cache/";
		
		$this->caching = false;
	}
}
?>
