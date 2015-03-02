<?php
/**
 * 
 */
error_reporting(E_ERROR);
//error_reporting(E_ALL);

// DEFINES
$TEMPLATE_NAME = "VCSearch.tpl";

// INCLUDES
require_once 'RSSList.php';
require_once 'SearchQry.php';
require_once 'SiteGroup.php';

$query = $_GET['query'];		// キーワード
$start = $_GET['start'];		// 表示開始位置
$type  = $_GET['type'];			// 動作タイプ
//print "_GET<pre>"; print_r ($_GET); print "</pre>";

#$logger = &Log::singleton('file', "/virtual/glorydays/public_html/easy.nicher.jp/log/vc_" . date('Ymd') . ".log", 'ident', array());

$searchQry = new SearchQry();
$smarty = $searchQry->search($type, $query, $start);

$smarty->assign("queryEnc", urlencode($query));
$smarty->assign("type", $type);

$rssHotwords = new EasyHotwordsRSS($type);
$smarty->assign("rssHotwords", $rssHotwords);

$allGroup = new AllGroup();
foreach ($allGroup->groups as $group) {
	foreach ($group->getRelatedSite() as $relatedSite) {
		if ($relatedSite->type == $type) {
			$site = $relatedSite;
			break;
		}
	}
}
$smarty->assign("allGroup", $allGroup);

$rssParser = $site->group->getRandomRSS();
$smarty->assign("keywordRss", $rssParser);


$smarty->display($TEMPLATE_NAME);

exit;
?>
