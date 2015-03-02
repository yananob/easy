<?php
/**
 * 
 */
error_reporting(E_ERROR);
//error_reporting(E_ALL);

// DEFINES
$TEMPLATE_NAME = "Permalink.tpl";

// INCLUDES
require_once 'Define.php';
require_once 'MySmarty.php';
require_once 'SiteGroup.php';
require_once 'SearchBase.php';

$query = $_GET['query'];		// キーワード
$start = $_GET['start'];		// 表示開始位置
$type  = $_GET['type'];			// 動作タイプ
//print "_GET<pre>"; print_r ($_GET); print "</pre>";

#$logger = &Log::singleton('file', "/virtual/glorydays/public_html/easy.nicher.jp/log/vc_" . date('Ymd') . ".log", 'ident', array());

$smarty = new MySmarty(".");

$query = mb_convert_encoding($query, "UTF-8", "auto");
$smarty->assign("query", $query);
$smarty->assign("start", $start);
$smarty->assign("type", $type);

$allGroup = new AllGroup();
foreach ($allGroup->groups as $group) {
	foreach ($group->getRelatedSite() as $relatedSite) {
		if ($relatedSite->type == $type) {
			$site = $relatedSite;
			break;
		}
	}
}
$smarty->assign("baseUri", $BASE_URI);
$smarty->assign("scriptName", $site->scriptName);

$smarty->assign("encodedUrl", urlencode($BASE_URI . "/rss_" . $type . ".xml/" . urlencode($query) . "/" . $start));

$smarty->display($TEMPLATE_NAME);

exit;
?>
