<?php
/**
 * Google Ranking Checker
 * 
 * 指定したキーワードでGoogleを検索したときに、そのURLが
 * 何番目に位置しているのかを判定する。
 * 
 * 
 * nusoapを使用します。（LGPL）
 * http://dietrich.ganx4.com/nusoap/
 * ※ソースコードの一部を変更しています。
 * 
 * PHP4 テンプレートクラスライブラリ改を使用します。（LGPL）
 * http://www.daiwakantei.co.jp/pc/tmpl2sa.html
 * 
 * 
 * http://php.helloprg.com/
 * 
 * 
 * @package php_include.PgManager
 * @access  public
 * @author  Hello PHP Programming <info@helloprg.com>
 * @version 0.01
 */
error_reporting(E_ERROR);

// DEFINES

// INCLUDES
require_once('Define.php');
require_once("$INCLUDE_PATH/php_include/tmpl2.class.inc");
require_once("$INCLUDE_PATH/php_include/nusoap.php");
include_once("GoogleAPI.php");

$tmpl = new Tmpl2("ServicesSearch.html");

$KEY_GOOGLE = "/DpPpPdQFHJPa0HtYp0BALhJhpVYAW9q";	// キー
$COUNT_PER_REQUEST = 2;		// 1回のリクエストあたりでの取得件数（Max10）
$maxCheckNum = $COUNT_PER_REQUEST;		// 上位何件までチェックするのかの値。$COUNT_PER_REQUEST の整数倍でお願いします

define("TYPE_SERVICES",		"services");
define("TYPE_BLOG",			"blog");

// サイトごとの定義
$searchSite = array(TYPE_SERVICES => "tanonavi.nicher.jp/modules/mylinks", TYPE_BLOG => "blog.nicher.jp");
$siteTitle = array(TYPE_SERVICES => "サービスの架け橋", TYPE_BLOG => "ニッチャー.jp");
$splitter = array(TYPE_SERVICES => " ", TYPE_BLOG => ",");

// 入力パラメータ
$type = $_GET['type'];				// 呼び出し元種別
$query = $_GET['query'];			// キーワード
$excludeUrl = $_GET['excludeurl'];	// 除外URL

//
$query = mb_convert_encoding($query, "UTF-8", "auto");
$query = ereg_replace($siteTitle[$type], "", $query);
$query_ary = mb_split(" - ", $query);
$query = $query_ary[0];
$words = mb_split($splitter[$type], $query);
$tmpl->assign("query", join(",", $words));

$query = join(" OR ", $words);

if($query == "") {
	$tmpl->assign_def("norequest");
}
else {
	$loopCnt = $maxCheckNum / $COUNT_PER_REQUEST;
	
	$google = new GoogleAPI($KEY_GOOGLE);
	$results = $google->search($query, $searchSite[$type], '0', $COUNT_PER_REQUEST);
	if ($results["faultcode"] != "") {
		if ($results["faultstring"] == "Exception from service object: Daily limit of 1000 queries exceeded for key $KEY_GOOGLE") {
			$message = "Google APIの検索制限回数を超えました。しばらく時間を置いてから再検索してください。";
		}
		else {
			$message = "Google API error: [" . $results["faultcode"] . "] "; // . $results["faultstring"]; -> 自分のキーが見えてしまう
		}
	}
//print "<pre>"; print_r ($results); print "</pre>";
	
	// 結果を調査する
	if (is_array($results['resultElements'])) {
		$totalCount = $results['estimatedTotalResultsCount'];
		
		$arr = array(count($results['resultElements']));
		
		for ( $i = 0; $i < count($results['resultElements']); $i++) {
			$result = $results['resultElements'][$i];
			
			$url = $result['URL'];
			if ($url == $excludeUrl) {
				continue;
			}
			$title = mb_substr($result['title'], 0, 100) ;
			$resultList .= "<span class='listTitle'><a href='$url' target='_top'>" . $title . "</a></span><br />";
			// <br>を除去
			$snippet = mb_substr(ereg_replace("<br>", "", $result['snippet']), 0, 180) . "...";
			$resultList .= "<span class='listSnippet'>" . $snippet . "</span><br />";
			// $resultList .= "<span class='listUrl'>" . $url . "</span><br />";
			$resultList .= "<br clear='all' />";
		}
	}
	// 結果がなければ終わり
	else {
		$tmpl->assign_def("nodata");
	}
	
	$tmpl->assign("resultList", $resultList);
}

$tmpl->flush();

?>
