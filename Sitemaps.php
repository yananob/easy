<?php
/**
 * 
 */
error_reporting(E_ERROR);
//error_reporting(E_ALL);

// DEFINES
$TEMPLATE_NAME = "Sitemaps.tpl";

// INCLUDES
require_once 'Define.php';
require_once 'DB.php';
require_once 'SearchBase.php';
require_once 'MySmarty.php';
require_once 'SiteGroup.php';

$type  = $_GET['type'];			// 動作タイプ

$smarty = new MySmarty(".");

if ($type == "") {
	// てきとうなやつで
	$site = new SearchAmazon();
}
else {
	$allGroup = new AllGroup();
	foreach ($allGroup->groups as $group) {
		foreach ($group->getRelatedSite() as $relatedSite) {
			if ($relatedSite->type == $type) {
				$site = $relatedSite;
				break;
			}
		}
	}
}

$smarty->assign("siteName", $site->siteName);
$smarty->assign("scriptName", $site->scriptName);

global $MYSQL_DSN;

$db = DB::connect($MYSQL_DSN);

$sql =	"select keyword, sum(hit) sum_hit, sum(count) sum_count, registered "
	.	"  from easy_search_stats ";
if ($type != "") {
	$sql .= " where type = '" . $type . "' ";
}
$sql .= " group by keyword "
	.	" having sum_count > 0 "
	.	" order by sum_hit desc "
	.	" limit 0, 50 "
	. ";";
$hotwords = $db->getAll($sql);
#print "<pre>"; print_r ($hotwords); print "</pre>";

$itemList = array();
foreach ($hotwords as $row) {
	$data->keyword = $site->convertFromDB($row[0]);
	$data->hit = $site->convertFromDB($row[1]);
	$data->count = $site->convertFromDB($row[2]);
#	$data->registered = $site->convertFromDB($row[3]);
	$registered = $site->convertFromDB($row[3]);
	$data->registered = substr($registered, 0, 10) . "T" . substr($registered, 11, 8) . "+09:00";
#	2006-09-20T09:51:23+09:00
	
	array_push($itemList, $data);
}
$smarty->assign("itemList", $itemList);

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
