<?php
/**
 * 検索＋テンプレートへの変数セットを行うクラス
 */

// INCLUDES
require_once 'GoogleAPI.php';
require_once 'SiteGroup.php';
require_once 'SearchBase.php';
require_once 'MySmarty.php';
require_once 'Define.php';
require_once 'HTMLCreator.php';

class SearchQry {
	
	// constructor
	function SearchQry() {
		// DEFINES
		$this->COUNT_PER_REQUEST = 10;		// 1回のリクエストあたりでの取得件数（Max10）
		$this->RESULT_PER_REQUEST = 100;	// 1回のリクエストあたりでの最大取得件数
	}
	
	function search($type, $query, $start) {
		global $BASE_URI;
		
		$smarty = new MySmarty(".");
		
		$query = mb_convert_encoding($query, "UTF-8", "auto");
		$smarty->assign("query", $query);
		if ($start == null) {
			$start = 0;
		}
		$smarty->assign("start", $start);
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
		$smarty->assign("allGroup", $allGroup);
		$smarty->assign("site", $site);
		$smarty->assign("siteName", $site->siteName);
		$smarty->assign("scriptName", $site->scriptName);
		
		if ($query == "") {
			$smarty->assign("norequest", true);
		}
		else {
			$message = "";
			
			$response = $site->search($query, $start, $this->COUNT_PER_REQUEST);
			if (array_key_exists('faultcode', $response)) {
				if ($response["faultstring"] == "Exception from service object: Daily limit of 1000 queries exceeded for key " . $site->googleKey) {
					$message = "Google APIの検索制限回数を超えました。しばらく時間を置いてから再検索してください。";
		print "response<pre>"; print_r ($response); print "</pre>";
				}
				else {
					$message = "Google API error: [" . $response["faultcode"] . "] "; // . $response["faultstring"]; -> 自分のキーが見えてしまう
				}
			}
		//print "response<pre>"; print_r ($response); print "</pre>";
			
			if (is_array($response['resultElements'])) {
				$html = new HTMLCreator();
				
				// page list
				$curPage = $start / $this->COUNT_PER_REQUEST;
				$totalPage = floor($response['estimatedTotalResultsCount'] / $this->COUNT_PER_REQUEST);
				
				$count = 0;
				if ($curPage > 1) {
					$html->addPage($curPage - 1, $this->$COUNT_PER_REQUEST, "前へ", "120%");
				}
				for ($page = 0; $page <= $totalPage; $page++) {
					$html->addPage($page, $this->COUNT_PER_REQUEST, $page + 1, "100%", ($curPage == $page ? true : false));
					if (++$count > $this->COUNT_PER_REQUEST) {
						break;
					}
				}
				if ($curPage < $totalPage &&
				    $curPage < $this->RESULT_PER_REQUEST / $this->COUNT_PER_REQUEST) {
					$html->addPage($curPage + 1, $this->COUNT_PER_REQUEST, "次へ", "120%");
				}
				
				$smarty->assign("response", $response);
				$smarty->assign("pages", $html->pages);
				$smarty->assign("resultList", $site->dataList);
				$smarty->assign("totalPage", $totalPage);
			}
			// 結果がなければ終わり
			else {
				$smarty->assign("nodata", true);
			}
			$smarty->assign("message", $message);
		}
		
		return $smarty;
	}
}	
?>
