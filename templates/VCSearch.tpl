{* Smarty *}

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="keywords" content="{$query},{$siteName},簡単,検索,ワンクリック,サーチ,Search">
<meta name="description" content="キーワードとサービスをワンクリックで結ぶ、検索ツール。各サイトが提供する検索よりも、多くの内容を対象として検索できます。">
{if $nodata}
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW">
{else}
	<meta name="ROBOTS" content="INDEX,FOLLOW">
{/if}
<link rel="stylesheet" href="{$baseUri}/style.css">
<link rel="stylesheet" href="{$baseUri}/tab2.css">
<script src="{$baseUri}/prototype.js"></script>
<link href="http://nicher.jp/favicon.ico" rel="SHORTCUT ICON" />
<title>{if !$norequest}{$query} - {/if}{$siteName} - ニッチャー.JP</title>
{literal}
	<!-- Google Analytics start --->
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	var pageTracker = _gat._getTracker("UA-177420-3");
	pageTracker._initData();
	pageTracker._trackPageview();
	</script>
	<!-- Google Analytics end --->
	
	<script type="text/javascript">
	<!--
	function focus() {
		if (document.search.query.value == "") {
			document.search.query.focus();
		}
	}
	function send() {
		// location.href = "{/literal}{$scriptName}{literal}" + document.search.query.value;
		doSearch(document.search.query.value, 0);
	}
	
	// bpref = tab body prefix, hpref = tab header prefix
	function seltab_old(selected) {
	
		var bpref = "box";
		var hpref = "head";
		var id_max = 10;
		
		if (! document.getElementById)
			return;
		
		for (i = 0; i <= id_max; i++) {
			if (! document.getElementById(bpref + i))
				continue;
			
			if (i == selected) {
				document.getElementById(bpref + i).style.visibility = "visible";
				document.getElementById(bpref + i).style.position = "";
				document.getElementById(hpref + i).className = "open";
			}
			else {
				document.getElementById(bpref + i).style.visibility = "hidden";
				document.getElementById(bpref + i).style.position = "absolute";
				document.getElementById(hpref + i).className = "close";
			}
		}
	}
	
	function seltab(selected) {
		
		var hpref = "head";
		var bpref = "box";
		var id_max = 10;
		
		if (! document.getElementById)
			return;
		
		var heads = document.getElementsByClassName("head", "ul");
		
		for (i = 1; i <= id_max; i++) {
			if (! document.getElementById(bpref + i))
				continue;
			
			if (i == selected) {
				document.getElementsByName(hpref + i).id = "current";
				heads[i - 1].id = "current";
				// document.getElementById(hpref + i).className = "open";
				document.getElementById(bpref + i).style.visibility = "visible";
				document.getElementById(bpref + i).style.position = "";
			}
			else {
				document.getElementsByName(hpref + i).id = "";
				heads[i - 1].id = "";
				// document.getElementById(hpref + i).className = "close";
				document.getElementById(bpref + i).style.visibility = "hidden";
				document.getElementById(bpref + i).style.position = "absolute";
			}
		}
	}
	
	function init() {
		// 最初に読み込んだときに 1 番のタブを選択するようにしています
		seltab(1);
		
		{/literal}{if $query != ""}{literal}
			// リンク、RSSの表示
		//	refreshPermalink("{/literal}{$query}", {$start}{literal});
		{/literal}{/if}{literal}
		// 注目ワードの表示
		// refreshHotWords();
		// 関連サイトの表示
		// refreshRelatedSites();
		
		focus();
	}
	
	function doSearch(query, start) {
		
		document.search.query.value = query;
		
/*
		var d = $('result');
		d.innerHTML = "<img src='{/literal}{$baseUri}{literal}/images/hourglass.gif'> <b>" + query + "</b>&nbsp;を検索中です...";
		
		var url = '{/literal}{$baseUri}{literal}/Result.php';
		var params = 'type={/literal}{$site->type}{literal}&query=' + query + '&start=' + start;
		
		var myAjax = new Ajax.Updater('result', url, {
										method: 'get',
										parameters: params,
										evalScripts: false,
										onSuccess: function() {
											refreshPermalink(query, start);
											refreshRelatedSites();
										}.bind(this)});
*/
		location.href = '{/literal}{$scriptName}{literal}' + query + '/' + start;
	}
	function refreshPermalink(query, start) {
		var id_name = "permalink";
		
		var d = $(id_name);
		d.style.visibility = "visible";
		
		/*
		var urlPerma = "{/literal}{$scriptName}{literal}" + query + (start != 0 ? ("/" + start) : "");
		var urlRss = "{/literal}{$baseUri}{literal}/rss_{/literal}{$site->type}{literal}.xml/" + query + (start != 0 ? ("/" + start) : "");
		d.innerHTML = "<img src='{/literal}{$baseUri}{literal}/images/add.gif'> <b>このページをチェック</b>:&nbsp;"
					+ "<a href='" + urlPerma + "'><img src='{/literal}{$baseUri}{literal}/images/link.gif' border='0' align='adsmiddle'> Permalink</a>&nbsp;&nbsp;&nbsp;"
					+ "<a href='http://rssicon20.com/rss.php?u=" + escape(urlRss) + "&s=1' target='_blank'><img src='{/literal}{$baseUri}{literal}/images/feed.gif' border='0' align='adsmiddle'> RSS</a>";
		*/
		var url = '{/literal}{$baseUri}{literal}/Permalink.php';
		var params = 'type={/literal}{$site->type}{literal}&query=' + query + '&start=' + start;
		
		var myAjax = new Ajax.Updater(id_name, url, {method: 'get', parameters: params});
	}
	function refreshHotWords() {
		var id_name = "box1";
		
		var d = $(id_name);
		// d.style.visibility = "visible";
		d.innerHTML = "<img src='{/literal}{$baseUri}{literal}/images/hourglass.gif'> 読み込み中です...";
		
		var url = '{/literal}{$baseUri}{literal}/HotWords.php';
		var params = 'type={/literal}{$site->type}{literal}&cache=' + (new Date()).getTime();
		
		var myAjax = new Ajax.Updater(id_name, url, {method: 'get', parameters: params});
	}
	function refreshRelatedSites(query) {
		var id_name = "box3";
		
		var d = $(id_name);
		d.innerHTML = "<img src='{/literal}{$baseUri}{literal}/images/hourglass.gif'> 読み込み中です...";
		
		var url = '{/literal}{$baseUri}{literal}/RelatedSites.php';
		var params = 'query=' + document.search.query.value;
		
		var myAjax = new Ajax.Updater(id_name, url, {method: 'get', parameters: params});
	}
	//-->
	</script>
{/literal}
<META name="verify-v1" content="REq7JMg5n05F2X7grAJpVYfvsvkCAXBQsM7gPxb0lD4=" />
</head>
<body onload="init()">

<form name="search" method="GET" onSubmit="send(); return false">

<table cellpadding="2" border="0">
	<tr>
		<td width="40%" nowrap>
			<a href="{$scriptName}" style="color: black"><b>{$siteName}</b></a>: <span style="font-size: 90%">ワンクリックでどんどん検索。</span>
		</td>
		<td width="50%" nowrap>
			<input type="text" name="query" size="30" value="{$query}" style="height: 1.5em">
			<input type="submit" value="Google 検索">
		</td>
		<td width="10%" nowrap align="right">
			<!--
			<a href="http://note.nicher.jp/EasySearch.html" target="_blank"><img src="{$baseUri}/images/help.gif" border="0"> ヘルプ</a>&nbsp;&nbsp;&nbsp;
			-->
		</td>
	</tr>
</table>

</form>

<hr />

<div class="list">

	<div align="center">
		<script type="text/javascript"><!--
		google_ad_client = "pub-6988464373673184";
		google_ad_width = 728;
		google_ad_height = 90;
		google_ad_format = "728x90_as";
		google_ad_type = "text_image";
		//2006-10-23: easy
		google_ad_channel = "5920700218";
		google_color_border = "FFFFFF";
		google_color_bg = "FFFFFF";
		google_color_link = "0033CC";
		google_color_text = "000000";
		google_color_url = "0033CC";
		//--></script>
		<script type="text/javascript"
		  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
	</div>
	<br>
	<div id="result">
		{if $norequest}
			キーワードとサービスをワンクリックで結ぶ検索ツール。<br />
			<br />
			各サイトが提供する検索よりも、多くの内容を対象として検索できます。<br />
		{else}
			{include file="Result.tpl"}
		{/if}
	</div>
	<div class="listRight">
		<div id="tabsC">
			<ul>
				<li class="head"><a href="javascript:seltab(1)"><span>注目語</span></a></li>
				<li class="head"><a href="javascript:seltab(2)"><span>人気語</span></a></li>
				<li class="head"><a href="javascript:seltab(3)"><span>関連Web</span></a></li>
			</ul>
		</div>
		
		<div class="tabbody">
			<div id="box1" style="visibility: visible">
				<!-- <div id="hotwords" style="visibility: hidden">
				</div -->
				{include file="HotWords.tpl"}
			</div>
			
			<div id="box2" style="visibility: hidden">
				<img src='{$baseUri}/images/tag_blue.gif'> <b>人気の検索ワードで検索</b>:<br />
				<ol>
				{foreach from=$rssHotwords->getKeywords(10) item=keyword}
					<li>
						<!-- <a href='javascript:doSearch("{$keyword}", 0)'>{$keyword}</a> -->
						<a href='{$scriptName}{$keyword}'>{$keyword}</a>
					</li>
				{/foreach}
				</ol>
			</div>
			
			<div id="box3" style="visibility: hidden">
				{include file='RelatedSites.tpl'}
			</div>
			
		</div>

		<div id="permalink">
			{include file='Permalink.tpl'}
		</div>

	</div>
</div>
<br clear="all" />
<hr width="80%" />

<div class="footer">
	{$siteName} 
	<a href="http://www.google.com/apis/reference.html" target="_blank"><img src="http://www.google.co.jp/logos/powered_by_google_135x35.gif" alt="Powered by Google" width="135" height="35" border="0" align="top" /></a>,
	<a href="http://www.amazon.com/webservices" target="_blank">Amazon Web Services</a>,
	<a href="http://smarty.php.net/" target="_blank"><img src="{$baseUri}/images/smarty_icon.gif" border="0" height="31" width="88" alt="Smarty" align="top" /></a>, 
	<a href="http://www.thumbshots.org" target="_blank"><img src="http://open.thumbshots.org/attribution.png" alt="Free thumbnail preview by Thumbshots.org" width="88" height="31" border="0" align="top" /></a> / Icons by <a href="http://www.famfamfam.com/lab/icons/silk/" target="_blank">famfamfam.com</a><br />
	<br />
	<a href="http://nicher.jp/"><img src="http://nicher.s310.xrea.com/images/nicherjp.png" alt="nicher.jp" border="0"></a>
</div>

</body>
</html>
