<?php

// INCLUDES
require_once("RSS.php");

/*
class RSSList {
	
	function RSSList() {
		
		srand((double)microtime() * 1000000);
		
		$this->list = array();
		
		// ★BookGroupのコンストラクタへ移動済み★
		array_push($this->list, new HatenaHotKeywordRSS);
		array_push($this->list, new HatenaHotAsinRSS);
		array_push($this->list, new GooAllRSS);
		array_push($this->list, new GooTvRSS);
		array_push($this->list, new GooHotRSS);
		array_push($this->list, new GooDeepRSS);
		array_push($this->list, new GooClipRSS);
		array_push($this->list, new GooEntameRSS);
		array_push($this->list, new GooMaleRSS);
		array_push($this->list, new GooFemaleRSS);
		// array_push($this->list, new WikipediaRSS);
		// Wikipedia: 重すぎるので、キャッシュするまでやめ
	}
	
	// ランダムなRSSを取得
	function getRandomRSS() {
		
		$rssIndex = rand(0, count($this->list) - 1);
		
		return $this->list[$rssIndex];
	}
}
*/

class RSSParser {
	
	function RSSParser($rdf) {
		
		$this->rdf = $rdf;
	}
	
	function getKeywords($maxCount) {
		
		$r =& new XML_RSS($this->rdf);
		$parseRes = $r->parse();
		if (is_object($parseRes)) {
		//	print "<pre>"; print_r($parseRes); print "</pre>";
		}
		if (!$ch = $r->getChannelInfo()) {
			return FALSE;
		}
		
		$site_link = $ch['link'];
		
		$keywords = array();
		$items = $r->getItems();
		
		for ($i = 0; $i < min($maxCount, count($items)); $i++) {
			$title = mb_convert_encoding(strip_tags($items[$i]['title']), "UTF-8", "auto");
			// for Hatena
			$title = ereg_replace("リスト::", "", $title);
			// for goo All
			$title = preg_replace("/^[0-9]{1,2}位 /", "", $title);
			// for goo TV
			$title = preg_replace("/.+\[.+\] /", "", $title);
			if ($title == "") {
				continue;
			}
			/*
			オブジェクトでなく、キーワード文字列を直接配列に入れている
			$group["desc"] = mb_convert_encoding(strip_tags($val[$i]['description']), "UTF-8", "auto");
			$group["link"] = htmlspecialchars($val[$i]['link']);
			$group["date"] = $val[$i]['dc:date'];
			*/
			
			array_push($keywords, $title);
		}
		
		return $keywords;
	}
}

class HatenaHotKeywordRSS extends RSSParser {
	
	function HatenaHotKeywordRSS() {
		parent::RSSParser("http://d.hatena.ne.jp/hotkeyword?mode=rss");
		$this->sourceName = "はてな注目キーワード";
		$this->sourceUrl = "http://www.hatena.ne.jp/info/webservices#d-rss";
	}
}

class HatenaHotAsinRSS extends RSSParser {
	
	function HatenaHotAsinRSS() {
		parent::RSSParser("http://d.hatena.ne.jp/hotasin?mode=rss");
		$this->sourceName = "はてな注目ASIN/ISBN";
		$this->sourceUrl = "http://www.hatena.ne.jp/info/webservices#d-rss";
	}
}

class GooAllRSS extends RSSParser {
	
	function GooAllRSS() {
		parent::RSSParser("http://ranking.goo.ne.jp/rss/keyword/keyrank_all1/index.rdf");
		$this->sourceName = "gooウェブ検索 急上昇キーワードランキング";
		$this->sourceUrl = "http://ranking.goo.ne.jp/keyword/";
	}
}

class GooTvRSS extends RSSParser {
	
	function GooTvRSS() {
		parent::RSSParser("http://guide.search.goo.ne.jp/ranking/tv.rdf");
		$this->sourceName = "goo 人気テレビ番組";
		$this->sourceUrl = "http://ranking.goo.ne.jp/keyword/";
	}
}

class GooHotRSS extends RSSParser {
	
	function GooHotRSS() {
		parent::RSSParser("http://ranking.goo.ne.jp/rss/keyword/main/keyword/index.rdf");
		$this->sourceName = "goo 注目ワード";
		$this->sourceUrl = "http://ranking.goo.ne.jp/keyword/";
	}
}

class GooDeepRSS extends RSSParser {
	
	function GooDeepRSS() {
		parent::RSSParser("http://ranking.goo.ne.jp/rss/keyword/main/deep/index.rdf");
		$this->sourceName = "goo ディープワード";
		$this->sourceUrl = "http://ranking.goo.ne.jp/keyword/";
	}
}

class GooClipRSS extends RSSParser {
	
	function GooClipRSS() {
		parent::RSSParser("http://ranking.goo.ne.jp/rss/keyword/main/clip/index.rdf");
		$this->sourceName = "goo 検索で知るコトバ";
		$this->sourceUrl = "http://ranking.goo.ne.jp/keyword/";
	}
}

class GooEntameRSS extends RSSParser {
	
	function GooEntameRSS() {
		parent::RSSParser("http://ranking.goo.ne.jp/rss/keyword/keyrank_entame/index.rdf");
		$this->sourceName = "goo 急上昇エンタメ";
		$this->sourceUrl = "http://ranking.goo.ne.jp/keyword/";
	}
}

class GooMaleRSS extends RSSParser {
	
	function GooMaleRSS() {
		parent::RSSParser("http://ranking.goo.ne.jp/rss/keyword/keyrank_male/index.rdf");
		$this->sourceName = "goo 急上昇男性有名人";
		$this->sourceUrl = "http://ranking.goo.ne.jp/keyword/";
	}
}

class GooFemaleRSS extends RSSParser {
	
	function GooFemaleRSS() {
		parent::RSSParser("http://ranking.goo.ne.jp/rss/keyword/keyrank_female/index.rdf");
		$this->sourceName = "goo 急上昇女性有名人";
		$this->sourceUrl = "http://ranking.goo.ne.jp/keyword/";
	}
}

class WikipediaRSS extends RSSParser {
	
	function WikipediaRSS() {
		parent::RSSParser("http://en.wikipedia.org/w/index.php?title=Special:Recentchanges&feed=rss");
		$this->sourceName = "Wikipedia";
		$this->sourceUrl = "http://en.wikipedia.org/wiki/";
	}
}

class EasyHotwordsRSS extends RSSParser {
	
	function EasyHotwordsRSS($type) {
		parent::RSSParser("http://easy.nicher.jp/RSSHotWords.php?type=" . $type);
		$this->sourceName = "RSSHotWords";
		$this->sourceUrl = "http://easy.nicher.jp/";
	}
}

?>
