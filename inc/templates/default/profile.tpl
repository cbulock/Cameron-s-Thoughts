{include file='head.tpl'}

{include file='header.tpl'}

<h2>{$user.name}</h2>

<img src='{$user.avatar}' />
<p>Login: {$user.login}</p>
<p>Website: <a href='{$user.url}'>{$user.url}</a></p>

{include file='footer.tpl'}
