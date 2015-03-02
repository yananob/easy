<?php
/**
 * 
 */
error_reporting(E_ERROR);
//error_reporting(E_ALL);

// DEFINES
$TEMPLATE_NAME = "HotWords.tpl";

// INCLUDES
require_once 'RSSList.php';
require_once 'MySmarty.php';
require_once 'SiteGroup.php';
require_once 'SearchBase.php';

$type  = $_GET['type'];			// 動作タイプ
//print "_GET<pre>"; print_r ($_GET); print "</pre>";

#$logger = &Log::singleton('file', "/virtual/glorydays/public_html/easy.nicher.jp/log/vc_" . date('Ymd') . ".log", 'ident', array());

$smarty = new MySmarty(".");

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

$rssParser = $site->group->getRandomRSS();
$smarty->assign("keywordRss", $rssParser);

$smarty->display($TEMPLATE_NAME);

exit;
?>
