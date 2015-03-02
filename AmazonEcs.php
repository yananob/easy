<?php

class AmazonEcs
{
	// ECSから取得した商品情報XMLを格納
	var $xml;
	var $xmltree;
	// var $xmlctx;
	
	// ECS 4.0を利用するのに必要な情報
	var $host = "webservices.amazon.co.jp";
	var $base_path = "/onca/xml?Service=AWSECommerceService";
	
	var $sub_id = "073QE8XTN2V2E9RTA6R2";
	var $aid = "glory-22";
	var $version = "2005-07-26";
	var $contentType= "text/xml";
	
	// RESTパラメータを指定するPath部分
	var $path = "";

	// キーワードと商品種別、ページを指定して検索
	function setCommonPath()
	{
		$this->path = $this->base_path
					. "&SubscriptionId=" . $this->sub_id
					. "&AssociateTag=" . $this->aid
					. "&Version=" . $this->version
					. "&ContentType=" . $this->contentType;
		
		return TRUE;
	}
	
	function itemSearch($keyword, $index, $page) {
		
		$this->setCommonPath();
		
		$this->path .= "&Operation=ItemSearch"
					.  "&ResponseGroup=Small,Offers,Images,BrowseNodes,SalesRank"
					.  "&Keywords=" . $keyword
					.  "&SearchIndex=" . $index
					.  "&Page=" . $page;

		return $this->loadXML();
	}
	
	// asin: 複数指定する場合は、","で繋ぐ
	function itemLookup($asins) {
		
		$this->setCommonPath();
		
		$this->path .= "&Operation=ItemLookup"
					.  "&ResponseGroup=Medium,Reviews"
					.  "&ItemId=" . $asins;
		
		return $this->loadXML();
	}
	
	// ECS 4.0にRESTリクエストを発行して、XMLを取得
	function loadXML()
	{
		$doc = $this->connect();
		
		if (!$doc) return FALSE;
		if (substr($doc, 0, 5) != "<?xml") return FALSE;
		
		// Simple XMLに商品情報XMLを格納
		// $this->xml = @simplexml_load_string($doc);
		if (!$this->xml = domxml_open_mem($doc)) {
			return FALSE;
		}
		// $this->xmlctx = xpath_new_context($this->xml);
		$this->xmltree = domxml_xmltree($doc);
		
		// クエリチェック
		$isValids = $this->xml->get_elements_by_tagname("IsValid");
		$isValid = $isValids[0];
		if ($isValid->get_content() != TRUE) {
			return FALSE;
		}
		
		return TRUE;
	}
	
	// PHPのSocket機能を利用して、ECS 4.0サーバに接続
	function connect ()
	{
		$sockPointer = @fsockopen($this->host, 80, $errno, $errstr, 6);
		
		if( !$sockPointer ) {
			return FALSE;
		} else {
			stream_set_timeout($sockPointer, 6, 0);
			
			fputs ($sockPointer, "GET $this->path HTTP/1.0\r\nHost: $this->host\r\n\r\n");
			fputs ($sockPointer, "User-Agent: AmazonSearch/1.0\n\n");
			fputs ($sockPointer, "Keep-Alive: 300\n\n");
			fputs ($sockPointer, "Connection: Keep-Alive\n\n");
			fputs ($sockPointer, "Referer: http://easy.nicher.jp/AmazonSearch/\n\n");
			
			$buf = "";
			$response = fgets($sockPointer);
			if (substr_count($response, "200 OK") > 0)
			{
				while (!feof($sockPointer)) {
					$buf = $buf . fread($sockPointer,4096);
				}
			} else {
				$result = FALSE;
			}
			$result = TRUE;
		}
		fclose($sockPointer);
		
		if ($result) {
			$doc = substr($buf, strpos($buf,"\r\n\r\n")+4);
			return $doc;
		} else {
			return FALSE;
		}
	}
}

class ItemInfo {
	
	function ItemInfo($item) {
		
		$this->asin = $this->getElementValue($item, "ASIN");
		$this->detailPageUrl = $this->getElementValue($item, "DetailPageURL");
		
		$smallImage = $this->getFirstElement($item, "SmallImage");
		if ($smallImage != NULL) {
			$this->smallImage->url = $this->getElementValue($smallImage, "URL");
			$this->smallImage->height = $this->getElementValue($smallImage, "Height");
			$this->smallImage->width = $this->getElementValue($smallImage, "Width");
		}
		
		$mediumImage = $this->getFirstElement($item, "MediumImage");
		if ($mediumImage != NULL) {
			$this->mediumImage->url = $this->getElementValue($mediumImage, "URL");
			$this->mediumImage->height = $this->getElementValue($mediumImage, "Height");
			$this->mediumImage->width = $this->getElementValue($mediumImage, "Width");
		}
		
		$customerReviews = $this->getFirstElement($item, "CustomerReviews");
		if ($customerReviews != NULL) {
			$this->averageRating = $this->getElementValue($customerReviews, "AverageRating");
		}
		$itemAttributes = $this->getFirstElement($item, "ItemAttributes");
		if ($itemAttributes != NULL) {
			$listPrice = $this->getFirstElement($itemAttributes, "ListPrice");
			if ($listPrice != NULL) {
				$this->price = $this->getElementValue($listPrice, "FormattedPrice");
			}
		}
		
	}
	
	function getFirstElement($element, $tagname) {
		
		$dummy = $element->get_elements_by_tagname($tagname);
		
		if (count($dummy) < 1) {
			return NULL;
		}
		return $dummy[0];
	}
	
	function getElementValue($element, $tagname) {
		
		$first = $this->getFirstElement($element, $tagname);
		
		if ($first != NULL) {
			return $first->get_content();
		}
		else {
			return "";
		}
	}
}
?>
