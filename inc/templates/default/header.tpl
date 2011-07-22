<div id='welcome'>
{if isset($authUser)}
Welcome, <a href='/profile/{$authUser.login}'>{$authUser.name}</a> | <a href='/form/logout' id='logout'>Logout</a>
{else}
<a href='/form/login' id='login'>Login</a> | <a href='/form/signup' id='signup'>Sign Up</a>
{/if}
</div>
<header>
<h1 id='title'><a href='/'>{$site_name}</a></h1>
<p id='sitetag'>{$site_tagline}</p>
</header>
{include file='menu.tpl'}
