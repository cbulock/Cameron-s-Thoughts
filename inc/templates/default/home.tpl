{include file='head.tpl'}

{include file='header.tpl'}

{foreach from=$entries item=entry}
{include file='entry_body_excerpt.tpl'}
{include file='comment_link.tpl'}
{/foreach}
{include file='footer.tpl'}
