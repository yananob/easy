<?php
/**
 * 
 */

// DEFINES

// INCLUDES

class HTMLCreator {
	
	function HTMLCreator() {
		$this->pages = array();
	}
	
	function addPage($page, $countPerRequest, $linkName, $fontSize, $isCurrent = false) {
		
		$obj->start = $page * $countPerRequest;
		$obj->fontsize = $fontSize;
		$obj->linkName = $linkName;
		$obj->isCurrent = $isCurrent;
		
		array_push($this->pages, $obj);
	}
}
?>
