<?php
/**
 * 
 */

// DEFINES

// INCLUDES
require_once 'Define.php';
require_once 'RSSList.php';

class AllGroup {
	
	function AllGroup() {
		$this->groups = array();
		
		array_push($this->groups, new BookGroup());
//		array_push($this->groups, new ElectronicsGroup());
		array_push($this->groups, new MailMagGroup());
	}
	
	function getGroup($type) {
		foreach ($this->groups as $group) {
			if ($type == $group->type) {
				return $group;
			}
		}
	}
}

class SiteGroup {
	
	function SiteGroup() {
		$this->relatedSite = array();
		
		$this->rssList = array();
	}
	
	function addRelatedSite($relatedSite) {
		array_push($this->relatedSite, $relatedSite);
	}
	
	function getRelatedSite() {
		
		return $this->relatedSite;
	}
	
	function getSite($type) {
		foreach ($this->getRelatedSite() as $site) {
			if ($type == $site->type) {
				return $site;
			}
		}
	}
	
	function addRSS($rss) {
		array_push($this->rssList, $rss);
	}
	
	// ランダムなRSSを取得
	function getRandomRSS() {
		
		$rssIndex = rand(0, count($this->rssList) - 1);
		
		return $this->rssList[$rssIndex];
	}
}

class BookGroup extends SiteGroup {
	
	function BookGroup() {
		parent::SiteGroup();
		
		$this->type = "book";
		$this->groupName = "書籍";
		
		parent::addRSS(new HatenaHotKeywordRSS);
		parent::addRSS(new HatenaHotAsinRSS);
		parent::addRSS(new GooAllRSS);
		parent::addRSS(new GooTvRSS);
		parent::addRSS(new GooHotRSS);
		parent::addRSS(new GooDeepRSS);
		parent::addRSS(new GooClipRSS);
		parent::addRSS(new GooMaleRSS);
		parent::addRSS(new GooFemaleRSS);
		
		// ここで$thisは、自身のオブジェクトのコピーを渡しているみたい:
		// なので、$this->group->getRelatedSites()では完全なリストが取れない可能性あり
		parent::addRelatedSite(new SearchAmazon($this));
		parent::addRelatedSite(new SearchJBook($this));
		parent::addRelatedSite(new SearchKinokuniya($this));
		
	}
}

class ElectronicsGroup extends SiteGroup {
	
	function ElectronicsGroup() {
		parent::SiteGroup();
		
		$this->type = "electronics";
		$this->groupName = "電化製品";
		
		parent::addRSS(new HatenaHotKeywordRSS);
		parent::addRSS(new GooAllRSS);
		parent::addRSS(new GooHotRSS);
		
//		parent::addRelatedSite(new SearchTanomail($this));
		parent::addRelatedSite(new SearchValuMore($this));
		parent::addRelatedSite(new SearchPlanex($this));
	}
}

class MailMagGroup extends SiteGroup {
	
	function MailMagGroup() {
		parent::SiteGroup();
		
		$this->type = "mailmag";
		$this->groupName = "メルマガ";
		
		parent::addRSS(new HatenaHotKeywordRSS);
		parent::addRSS(new GooAllRSS);
		parent::addRSS(new GooHotRSS);
		
		parent::addRelatedSite(new SearchMag2($this));
	}
}

?>
