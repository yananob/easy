<?php

require_once('Define.php');
require_once("$INCLUDE_PATH/php_include/nusoap.php");
require_once('UTF8Encoder.php');

class GoogleAPI {
	
	function GoogleAPI($soapKey) {
		$this->soapclient = new soapclient('http://api.google.com/GoogleSearch.wsdl', 'wsdl');
		$this->soapclient->decodeUTF8(false);
		$this->soapKey = $soapKey;
	}
	
	function search($query, $site, $start, $countPerRequest) {
		$resultList = "";
		
		$enc = new UTF8Encoder;
		$query = $enc->_decode_entities($query);
		$query = $enc->_utf8_to_entities($query);
		
		// パラメータ作成
		$parameters = array(
			'key' => $this->soapKey,
			'q' => "$query site:$site",
			'start' => $start,
			'maxResults' => $countPerRequest,
			'filter' => 'false',
			'restrict' => '',
			'safeSearch' => 'false',
			'lr' => 'lang_ja',
			'ie' => 'UTF-8',
			'oe' => 'UTF-8'
		);
// print "<pre>"; print_r ($parameters); print "</pre>";
		// ぐぐる
		$response = $this->soapclient->call('doGoogleSearch', $parameters);
		
		/*
		if ($response["faultcode"] != "") {
			return FALSE;
		}
		*/
		
		return $response;
	}
}
?>
