<?php
/**
 * 
 */

// DEFINES

// INCLUDES
require_once 'Define.php';
require_once 'DB.php';

class SearchBase {
	
	function SearchBase($group) {
		$this->group = $group;
	}
	
	function search($query, $start, $countPerRequest) {
		
		#global $logger;
		global $MYSQL_DSN;
		
		$db = DB::connect($MYSQL_DSN);
		#if (DB::isError($db)) $logger->log(DB::errorMessage($db));
		#$logger->log("SearchBase.search: db connected");
		
		$sql =	"select count "
			.	"  from easy_search_stats "
			.	" where type = '" . $this->type . "' "
			.	"   and keyword = '" . $this->convertToDB($query) . "'"
			. ";";
		$stats_count = $db->getOne($sql);
		
		$sql =	"select title, url, snippet, registered "
			.	"  from easy_search_cache "
			.	" where type = '" . $this->type . "' "
			.	"   and keyword = '" . $this->convertToDB($query) . "'"
			.	"   and position between " . ($start + 1) . " and " . ($start + $countPerRequest)
			. ";";
//print "sql<pre>"; print_r ($sql); print "</pre>";
		$datas = $db->getAll($sql);
		#if (!is_array($datas)) $logger->log(DB::errorMessage($db));
//print "datas<pre>"; print_r ($datas); print "</pre>";
		
		// 統計未取得か、統計は取ってるが該当範囲を未取得であれば
		if ($stats_count == "" ||
		    ($stats_count != "0" && count($datas) == 0)) {
			$googled = true;
			
			$google = new GoogleAPI($this->googleKey);
			#$logger->log("SearchBase.search: google created");
			$response = $google->search($query . $this->keyword, $this->site, $start, $countPerRequest);
			#$logger->log("SearchBase.search: google searched");
			
			$sql = "insert into easy_search_stats values ("
					. "'" . $this->type . "'"
					. ",'" . $this->convertToDB($query) . "'"
					. "," . $response['estimatedTotalResultsCount']
					. ",1"
					. ",current_timestamp"
				. ")";
			$res = $db->query($sql);
			#if ($res != DB_OK) $logger->log(DB::errorMessage($db));
			
			// 結果がなければ終わり
			if (!is_array($response['resultElements'])) {
				return $response;
			}
		}
		else {			// 検索済み：DBデータからセット
			// 結果がなければ終わり
			if ($stats_count == 0) {
				return array();
			}
			
			$googled = false;
			$response = array();
			$response['resultElements'] = $datas;
			$response['estimatedTotalResultsCount'] = $stats_count;
			$response['startIndex'] = $start + 1;
			$response['endIndex'] = $start + $countPerRequest;
			
			if ($start == 0) {
				$sql = "update easy_search_stats set "
					 . "  hit = hit + 1"
					 . " where type = '" . $this->type . "'"
					 . "   and keyword = '" . $this->convertToDB($query) . "'";
				$res = $db->query($sql);
				#if ($res != DB_OK) $logger->log(DB::errorMessage($db));
			}
		}
		
		// Googleからの結果を加工して格納
		$this->dataList = array();
		$position = $start + 1;
		foreach ($response['resultElements'] as $result) {
			if ($googled) {
				$data->title = $result['title'];
				$data->url = $result['URL'];
				$data->snippet = $result['snippet'];
				$data->registered = getdate();
				
				$sql = "insert into easy_search_cache values ("
						. "'" . $this->type . "'"
						. ",'" . $this->convertToDB($query) . "'"
						. "," . $position++
						. ",'" . $this->convertToDB($data->title) . "'"
						. ",'" . $data->url . "'"
						. ",'" . $this->convertToDB($data->snippet) . "'"
						. ",current_timestamp"
					. ")";
				$res = $db->query($sql);
				#if ($res != DB_OK) $logger->log(DB::errorMessage($db));
			}
			else {
				$data->title = $this->convertFromDB($result[0]);
				$data->url = $this->convertFromDB($result[1]);
				$data->snippet = $this->convertFromDB($result[2]);
				$data->registered = $this->convertFromDB($result[3]);
			}
			
			$data = $this->adjustResult($data);
			
			array_push($this->dataList, $data);
		}
		
		$this->addExtraData();
//print "resultList<pre>"; print_r ($resultList); print "</pre>";
		
		$db->disconnect();
		#$logger->log("SearchBase.search: db disconnected");
		
		return $response;
	}
	
	function addExtraData() {
	}
	
	function adjustResult($data) {
		// Snippet
		$data->snippet = ereg_replace("<br>", "", $data->snippet);
		
		return $data;
	}
	function setLinkUrl($data) {
		if ($data->detailPageUrl == "") {
			$data->linkUrl = $data->url;
		}
		else {
			$data->linkUrl = $data->detailPageUrl;
		}
		return $data;
	}
	function convertToDB($str) {
		return mb_convert_encoding($str, "EUC-JP", "auto");
	}

	function convertFromDB($str) {
		return mb_convert_encoding($str, "UTF-8", "auto");
	}
	
	function setThumbshotsImage($data) {
		/*
		$data->image->url = 'http://open.thumbshots.org/image.pxf?url=' . rawurlencode($data->url);
		$data->image->height = 82;
		$data->image->width = 111;
		*/
		
		return $data;
	}
}

class SearchAmazon extends SearchBase {
	
	function SearchAmazon($group) {
		global $BASE_URI;
		
		parent::SearchBase($group);
		
		require_once 'AmazonEcs.php';
		
		$this->type = "amazon";
		$this->siteName = "Amazon簡単検索2";
		$this->scriptName = "$BASE_URI/AmazonSearch/";
		$this->site = "amazon.co.jp";
		$this->googleKey = "LOxw9bZQFHJs5RC59RngZZTX8ujVmCgz";
	}
	
	function addExtraData() {
		#global $logger;
		
		parent::addExtraData();
		#$logger->log("AmazonSearch.addExtraData: parent done");
		
		$asins = array();
		for ($i = 0; $i < count($this->dataList); $i++) {
			$data = $this->dataList[$i];
			// ASIN
			preg_match("/\/([A-Z0-9]{5,})/", $data->url, $matches);
			if (count($matches) == 2) {
				$data->asin = $matches[1];
				array_push($asins, $data->asin);
			}
			else {
				$data->asin = "";
			}
			
			$this->dataList[$i] = $data;
//print "data<pre>"; print_r ($data); print "</pre>";
		}
		#$logger->log("AmazonSearch.addExtraData: set asin done");
		
		// Amazonから情報を取得して格納（ASINをまとめて指定するため、ループを分離）
		$ecs = new AmazonEcs();
		#$logger->log("AmazonSearch.addExtraData ECS created");
		if (!$ecs->itemLookup(join(",", $asins))) {
			$message = "AmazonからのXMLデータ取得に失敗<br />";
		}
		#$logger->log("AmazonSearch.addExtraData: ECS lookuped");
		$itemArray = $ecs->xml->get_elements_by_tagname("Item");
		$dummy = $ecs->xml->get_elements_by_tagname("RequestProcessingTime");
		$requestProcessingTime = $dummy[0];
		
		foreach ($itemArray as $item) {
			// Amazon情報をセット
			$itemInfo = new ItemInfo($item);
			for ($i = 0; $i < count($this->dataList); $i++) {
				$data = $this->dataList[$i];
				
				if ($data->asin == $itemInfo->asin) {
					$data->detailPageUrl = $itemInfo->detailPageUrl;
					$data->image->url = $itemInfo->smallImage->url;
					$data->image->height = $itemInfo->smallImage->height;
					$data->image->width = $itemInfo->smallImage->width;
					$data->price = $itemInfo->price;
					$data->rating->value = $itemInfo->averageRating;
					$data->rating->image->url = $this->getRatingImageUrl($data->rating->value);
					
					$this->dataList[$i] = $data;
					break;
				}
			}
		}
		
		for ($i = 0; $i < count($this->dataList); $i++) {
			$data = $this->dataList[$i];
			
			$data = parent::setLinkUrl($data);
			
			$this->dataList[$i] = $data;
		}
		#$logger->log("AmazonSearch.addExtraData: done");
	}
	
	function adjustResult($data) {
		$data = parent::adjustResult($data);
		
		// Title
		$data->title = ereg_replace("Amazon.co.jp：", "", $data->title);
		$data->title = ereg_replace("Amazon.co.jp:", "", $data->title);
		
		// URL
		$data->url = rawurldecode($data->url);

		return $data;
	}
	
	// private
	function getRatingImageUrl($rating) {
		
		if ($rating == 0.0) {
			return "";
		}
		
		$val = (ceil(($rating * 10) / 5) * 5) / 10;
		$rate = sprintf("%.1f", $val);
		
		$url = "http://images-jp.amazon.com/images/G/09/other/stars-" . str_replace(".", "-", $rate) . ".gif";
		
		return $url;
	}
}

class SearchVCBase extends SearchBase {
	
	var $sid;
	
	function SearchVCBase($group, $pid) {
		parent::SearchBase($group);
		
		$this->sid = "2254783";
		$this->pid = $pid;
	}
	
	function getVCLinkUrl($url) {
		return "http://ck.jp.ap.valuecommerce.com/servlet/referral?sid=" . $this->sid . "&pid=" . $this->pid . "&vc_url=" . rawurlencode($url);
	}
	
	function getVCCounterUrl() {
		return "http://ad.jp.ap.valuecommerce.com/servlet/gifbanner?sid=" . $this->sid . "&pid=" . $this->pid;
	}

	function addExtraData() {
		parent::addExtraData();
		
		for ($i = 0; $i < count($this->dataList); $i++) {
			$data = $this->dataList[$i];
			
			$data->detailPageUrl = $this->getVCLinkUrl($data->url);
			$data = parent::setLinkUrl($data);
			$data->counter = $this->getVCCounterUrl();
			
			$this->dataList[$i] = $data;
		}
//print "this->dataList<pre>"; print_r ($this->dataList); print "</pre>";
	}
}

class SearchJBook extends SearchVCBase {
	
	function SearchJBook($group) {
		global $BASE_URI;

		parent::SearchVCBase($group, "873622575");
		
		$this->type = "jbook";
		$this->siteName = "JBook簡単検索2";
		$this->scriptName = "$BASE_URI/JBookSearch/";
		$this->site = "jbook.co.jp";
		$this->googleKey = "ITV8bZFQFHJcZ/8Bsdqt6LKptN+CvKfG";
	}
	
	function addExtraData() {
		parent::addExtraData();
		
		for ($i = 0; $i < count($this->dataList); $i++) {
			$data = $this->dataList[$i];
			
			// URL: http://www.jbook.co.jp/p/p.aspx/[id]/s/
			// IMG: http://www.jbook.co.jp/member/img/product/0[id.1-4]/M0[id]-01.jpg
			preg_match("/\/([0-9]{5,})/", $data->url, $matches);
			if (count($matches) == 2) {
				$data->image->url = "http://www.jbook.co.jp/member/img/product/0" . substr($matches[1], 0, 4) . "/M0" . $matches[1] . "-01.jpg";
			}
			else {
				$data->image->url = "";
			}
			$data->image->height = 85;
			// $data->image->width = $itemInfo->smallImage->width;
			
			$this->dataList[$i] = $data;
//print "data<pre>"; print_r ($data); print "</pre>";
		}
	}

	function adjustResult($data) {
		$data = parent::adjustResult($data);
		
		// Title
		$data->title = ereg_replace("JBOOK：", "", $data->title);
		
		return $data;
	}
	
}

class SearchKinokuniya extends SearchVCBase {
	
	function SearchKinokuniya($group) {
		global $BASE_URI;
		
		parent::SearchVCBase($group, "873622601");
		
		$this->type = "kinokuniya";
		$this->siteName = "紀伊国屋簡単検索2";
		$this->scriptName = "$BASE_URI/KinokuniyaSearch/";
		$this->site = "bookweb.kinokuniya.co.jp";
		$this->googleKey = "ELrLJcZQFHIpRjh8IJivL7nbuapZiPHa";
	}
	
	function addExtraData() {
		parent::addExtraData();
		
		for ($i = 0; $i < count($this->dataList); $i++) {
			$data = $this->dataList[$i];
			
			/* URLと全然関連性のなさそうな画像URLになってる
			// ★★★
			// URL: http://www.jbook.co.jp/p/p.aspx/[id]/s/
			// IMG: http://www.jbook.co.jp/member/img/product/0[id.1-4]/M0[id]-01.jpg
			preg_match("/\/([0-9]{5,})/", $data->url, $matches);
			if (count($matches) == 2) {
				$data->image->url = "http://www.jbook.co.jp/member/img/product/0" . substr($matches[1], 0, 4) . "/M0" . $matches[1] . "-01.jpg";
			}
			else {
				$data->image->url = "";
			}
			$data->image->height = 85;
			// $data->image->width = $itemInfo->smallImage->width;
			*/
			
			$data = $this->setThumbshotsImage($data);
			
			$this->dataList[$i] = $data;
//print "data<pre>"; print_r ($data); print "</pre>";
		}
	}
}

class SearchTanomail extends SearchBase {
	
	function SearchTanomail($group) {
		global $BASE_URI;
		
		parent::SearchBase($group);
		
		$this->type = "tanomail";
		$this->siteName = "カテナビ簡単検索2";
		$this->scriptName = "$BASE_URI/TanomailSearch/";
		$this->site = "tanonavi.nicher.jp/modules/mylinks/";
		$this->googleKey = "ELrLJcZQFHIpRjh8IJivL7nbuapZiPHa";	// とりあえず紀伊国屋と同じで
	}
	
	function addExtraData() {
		parent::addExtraData();
		
		for ($i = 0; $i < count($this->dataList); $i++) {
			$data = $this->dataList[$i];
			
			$data = $this->setThumbshotsImage($data);
			
			$this->dataList[$i] = $data;
//print "data<pre>"; print_r ($data); print "</pre>";
		}
	}
}

class SearchValuMore extends SearchVCBase {
	
	function SearchValuMore($group) {
		global $BASE_URI;

		parent::SearchVCBase($group, "873622594");
		
		$this->type = "valumore";
		$this->siteName = "ValuMore簡単検索2";
		$this->scriptName = "$BASE_URI/ValuMoreSearch/";
		$this->site = "valumore.jp";
		$this->googleKey = "ITV8bZFQFHJcZ/8Bsdqt6LKptN+CvKfG";
	}
	
	function addExtraData() {
		parent::addExtraData();
		
		for ($i = 0; $i < count($this->dataList); $i++) {
			$data = $this->dataList[$i];
			
			// ToDo: とりあえず
			
			$this->dataList[$i] = $data;
//print "data<pre>"; print_r ($data); print "</pre>";
		}
	}

}

class SearchPlanex extends SearchVCBase {
	
	function SearchPlanex($group) {
		global $BASE_URI;

		parent::SearchVCBase($group, "873622609");
		
		$this->type = "planex";
		$this->siteName = "Planex簡単検索2";
		$this->scriptName = "$BASE_URI/PlanexSearch/";
		$this->site = "direct.planex.co.jp";
		$this->googleKey = "ITV8bZFQFHJcZ/8Bsdqt6LKptN+CvKfG";
	}
	
	function addExtraData() {
		parent::addExtraData();
		
		for ($i = 0; $i < count($this->dataList); $i++) {
			$data = $this->dataList[$i];
			
			// ToDo: とりあえず
			
			$this->dataList[$i] = $data;
//print "data<pre>"; print_r ($data); print "</pre>";
		}
	}

}

class SearchMag2 extends SearchBase {
	
	function SearchMag2($group) {
		global $BASE_URI;
		
		parent::SearchBase($group);
		
		$this->type = "mag2";
		$this->siteName = "まぐまぐ簡単検索2";
		$this->scriptName = "$BASE_URI/Mag2Search/";
		$this->site = "www.mag2.com/m";
		$this->googleKey = "ITV8bZFQFHJcZ/8Bsdqt6LKptN+CvKfG";
	}
	
	function addExtraData() {
		parent::addExtraData();
		
		for ($i = 0; $i < count($this->dataList); $i++) {
			$data = $this->dataList[$i];
			$data = parent::setLinkUrl($data);
			
			// ToDo: とりあえず
			
			$this->dataList[$i] = $data;
//print "data<pre>"; print_r ($data); print "</pre>";
		}
	}
	
	function adjustResult($data) {
		$data = parent::adjustResult($data);
		
		// Title
		// $data->title = ereg_replace("Amazon.co.jp： ", "", $data->title);
		
		return $data;
	}
}

?>
