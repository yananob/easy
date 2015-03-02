<?php
/**
 * 
 */
error_reporting(E_ERROR);
//error_reporting(E_ALL);

// DEFINES
$TEMPLATE_NAME = "ResultRSS.tpl";

// INCLUDES
require_once 'SearchQry.php';

$query = $_GET['query'];		// キーワード
$start = $_GET['start'];		// 表示開始位置
$type  = $_GET['type'];			// 動作タイプ
//print "_GET<pre>"; print_r ($_GET); print "</pre>";

#$logger = &Log::singleton('file', "/virtual/glorydays/public_html/easy.nicher.jp/log/vc_" . date('Ymd') . ".log", 'ident', array());

$searchQry = new SearchQry();
$smarty = $searchQry->search($type, $query, $start);

$smarty->display($TEMPLATE_NAME);

exit;

function insert_header($params)
{
   // この関数は、パラメータ$contentを期待します
   if (empty($params['content'])) {
       return;
   }
   header($params['content']);
   return;
}

?>
