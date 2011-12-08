{include file='head.tpl' title_append='API'}

<h2>API</h2>

{foreach from=$methods item=method}
<a href='/admin/api_method/{$method['value']}'>{$method['value']}</a><br />
{/foreach}

{include file='footer.tpl'}
