{include file='head.tpl'}

{include file='header.tpl'}

<h3>Search results for {$term}</h3>

{foreach from=$results item=entry}
{include file='entry_body_excerpt.tpl'}
{include file='comment_link.tpl'}
{/foreach}
{include file='footer.tpl'}
