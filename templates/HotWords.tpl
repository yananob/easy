{* Smarty *}

<img src="{$baseUri}/images/asterisk_orange.gif" border="0"> <b>注目キーワードで検索</b>:
<ol>
{foreach from=$keywordRss->getKeywords(10) item=keyword}
	<li>
		<!-- <a href='javascript:doSearch("{$keyword}", 0)'>{$keyword}</a> -->
		<a href='{$scriptName}{$keyword}'>{$keyword}</a>
	</li>
{/foreach}
</ol>
powered by<br>
&nbsp;&nbsp;<a href='{$keywordRss->sourceUrl}' target="_blank">{$keywordRss->sourceName}</a><br>
<a href="javascript:refreshHotWords()"><img src="{$baseUri}/images/arrow_refresh.gif" border="0"> 別のキーワードを見る</a>
