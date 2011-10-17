{include file='head.tpl' title_append='Settings'}

<h2>Settings</h2>

{foreach from=$settings item=setting}
<a href='/admin/setting/{$setting['name']}'>{$setting['name']}</a><br />
{/foreach}

{include file='footer.tpl'}
