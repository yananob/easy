{insert name=header content="Content-Type: text/xml; charset=utf-8"}
<rdf:RDF
	xmlns="http://purl.org/rss/1.0/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
	xml:lang="ja">
	<channel rdf:about="{$scriptName}">
		<title>人気のキーワード - {$siteName} - ニッチャー.JP</title>
		<link>{$scriptName}</link>
		<description>人気のキーワード - {$siteName} - ニッチャー.JP</description>
		<items>
			<rdf:Seq>
			{foreach from=$itemList item=list}
				<rdf:li rdf:resource="{$scriptName}{$list->keyword}"/>
			{/foreach}
			</rdf:Seq>
		</items>
	</channel>
	{foreach from=$itemList item=list}
		<item rdf:about="{$scriptName}{$list->keyword}">
			<title>{$list->keyword}</title>
			<link>{$scriptName}{$list->keyword}</link>
			<description>
				{$list->hit} pt/{$list->count} 件
			</description>
			<dc:date>{$list->registered}</dc:date>
		</item>
	{/foreach}
</rdf:RDF>
