{include file='head.tpl' title_append='Users'}

<h2>Users</h2>

{foreach from=$users item=user}
<a href='/admin/user/{$user['id']}'>{$user['name']}</a><br />
{/foreach}

{include file='footer.tpl'}
