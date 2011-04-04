<div id='welcome'>
{if isset($authUser)}
Welcome, {$authUser.name} | <a href='/form/logout' id='logout'>Logout</a>
{else}
<a href='/form/login' id='login'>Login</a> | <a href='/form/signup' id='signup'>Sign Up</a>
{/if}
</div>
<div id='heading'>
<h1 id='title'><a href='/'>{$site_name}</a></h1>
<p id='sitetag'>{$site_tagline}</p>
</div>
{include file='menu.tpl'}
