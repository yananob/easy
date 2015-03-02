{insert name=header content="Content-Type: text/xml; charset=utf-8"}<?xml version="1.0" encoding="utf-8" ?>
<rdf:RDF
	xmlns="http://purl.org/rss/1.0/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
	xml:lang="ja">
	<channel rdf:about="http://easy.nicher.jp/rss_amazon.xml/%E5%9D%82%E4%BA%95%E6%B3%89%E6%B0%B4/0">
		<title>{$query} - {$siteName} - ニッチャー.JP</title>
		<link>{$scriptName}{$query|escape:"url"}</link>
		<description>{$query} - {$siteName} - ニッチャー.JP</description>
		<items>
			<rdf:Seq>
			{foreach from=$resultList item=result}
				<rdf:li rdf:resource="{$result->title|escape:"html"}"/>
			{/foreach}
			</rdf:Seq>
		</items>
	</channel>
	{foreach from=$resultList item=result}
		<item rdf:about="{$result->url|escape:"html"}">
			<title>{$result->title|escape:"html"}</title>
			<link>{$result->linkUrl|escape:"html"}</link>
			<description>
				{$result->snippet|escape:"html"}
			</description>
			<dc:date>{$result->registered}</dc:date>
		</item>
	{/foreach}
</rdf:RDF>
