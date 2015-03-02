<?php
/**
 * 
 */
error_reporting(E_ERROR);
//error_reporting(E_ALL);

// DEFINES
$TEMPLATE_NAME = "RelatedSites.tpl";

// INCLUDES
require_once 'RSSList.php';
require_once 'GoogleAPI.php';
require_once 'MySmarty.php';
require_once 'HTMLCreator.php';
require_once 'SiteGroup.php';
require_once 'SearchBase.php';

$query = $_GET['query'];		// キーワード
//print "_GET<pre>"; print_r ($_GET); print "</pre>";

#$logger = &Log::singleton('file', "/virtual/glorydays/public_html/easy.nicher.jp/log/vc_" . date('Ymd') . ".log", 'ident', array());

$smarty = new MySmarty(".");

$smarty->assign("baseUri", $BASE_URI);

$query = mb_convert_encoding($query, "UTF-8", "auto");
$smarty->assign("query", $query);

$allGroup = new AllGroup();

$smarty->assign("allGroup", $allGroup);

$smarty->display($TEMPLATE_NAME);

exit;
?>
