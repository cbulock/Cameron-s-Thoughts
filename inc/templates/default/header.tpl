<div id='welcome'>
{if isset($authUser)}
Welcome, <a href='/profile/{$authUser.login}'>{$authUser.name}</a> | <a href='/form/logout' id='logout'>Logout</a>
{else}
<a href='/form/login' id='login'>Login</a> | <a href='/form/signup' id='signup'>Sign Up</a> 
<fb:login-button registration-url="http://developers.facebook.com/docs/plugins/registration" />
{/if}
</div>
<form id='searchbox' method='post' action='/form/search'>
<input type='text' name='search' id='search' />
<button type='submit'>Search</button>
</form>
<header>
<h1 id='title'><a href='/'>{$site_name}</a></h1>
<p id='sitetag'>{$site_tagline}</p>
</header>
{include file='menu.tpl'}
