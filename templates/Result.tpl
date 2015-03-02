{* Smarty *}

{if !$nodata}
	<div class="searchStat">
		約 <b>{$response.estimatedTotalResultsCount}</b> 件中 <b>{$response.startIndex}</b> - <b>{$response.endIndex}</b> 件目 (<b>{$response.searchTime|string_format:"%.5f"}</b> 秒)
	</div>
	<div class="searchComment">
		{$response.searchComments}&nbsp;&nbsp;{$response.searchTips}
	</div>
{/if}
<div class="listLeft">
	{if $nodata}
		<br />
		<b>{$query}</b> に該当するページが見つかりませんでした。<br />
		<br />
		{$message}
	{else}
		{foreach from=$resultList item=result}
			{if $result->image->url != ""}
				<span class='listImage'><a href='{$result->linkUrl}' target='_blank'><img src='{$result->image->url}' border='0' height='{$result->image->height}' {if $result->image->width != ""}width='{$result->image->width}'{/if} align='right' alt='{$result->title}' /></a></span>
			{/if}
			
			{* タイトル行の作成 *}
			<span class='listTitle'><a href='{$result->linkUrl}' target='_blank'>{$result->title}</a>
			{if $result->rating->image->url != ""}
				<img src='{$result->rating->image->url}' alt='レーティング: {$result->rating->value}' />
			{/if}
			{$result->price}
			{if $result->counter != ""}
				<img src='{$result->counter}' width='0' height='0' alt='' />
			{/if}
			</span><br />
			
			{* snippetの作成 *}
			<span class='listSnippet'>{$result->snippet}</span><br />
			
			{* URLの作成 *}
			<span class='listUrl'>{$result->url|truncate:100}</span><br />
			<br clear='all' />
		{/foreach}

		<br clear="all" />
		<div class="jumpMenu" id="page">
			{foreach from=$pages item=page}
				{if $page->isCurrent}
					<font color="red"><b>&nbsp;{$page->linkName}&nbsp;</b></font>&nbsp;
				{else}
					<!-- <a href='javascript:doSearch("{$query}", {$page->start})'><span style='font-size: {$page->fontsize}'>&nbsp;{$page->linkName}&nbsp;</span></a>&nbsp; -->
					<a href='{$scriptName}{$query}/{$page->start}'><span style='font-size: {$page->fontsize}'>&nbsp;{$page->linkName}&nbsp;</span></a>&nbsp;
				{/if}
			{/foreach}
		</div>
	{/if}
</div>
