{* Smarty *}

<img src='{$baseUri}/images/tag_orange.gif'> <b>関連サイトから検索</b>:<br />
{foreach from=$allGroup->groups item=group}
	<div class="groupName">{$group->groupName}</div>
	<ul>
	{foreach from=$group->getRelatedSite() item=site}
		<li><a href="{$site->scriptName}{$query}">{$site->siteName}</a></li>
	{/foreach}
	</ul>
{/foreach}
