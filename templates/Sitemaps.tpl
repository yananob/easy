{insert name=header content="Content-Type: text/xml; charset=utf-8"}<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
	{foreach from=$itemList item=list}
		<url>
			<loc>{$scriptName}{$list->keyword}</loc>
			<lastmod>{$list->registered}</lastmod>
		</url>
	{/foreach}
</urlset>
