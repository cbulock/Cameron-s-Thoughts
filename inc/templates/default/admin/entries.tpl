{include file='head.tpl' title_append='Entries'}

<h2>Entries</h2>

{foreach from=$entries item=entry}
<a href='/admin/entry/{$entry['entry_id']}'>{$entry['entry_title']}</a><br />
{/foreach}


{include file='footer.tpl'}
