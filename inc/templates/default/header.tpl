<div id='fb-root' appid='{$facebook_app_id}'></div>

<div id='welcome'>
{if isset($authUser)}
 Welcome, <a href='/profile/{$authUser.login}'>{$authUser.name}</a> 
 | <a href='/form/logout' id='logout'>Logout</a>
 {if $authUser.service == 2} 
  <span id='fb_login'><fb:login-button id='fb_logout' autologoutlink="true">Logout</fb:login-button></span>
 {/if}
{else}
 <a href='/form/login' id='login'>Login</a> | <a href='/form/signup' id='signup'>Sign Up</a>
 <span id='fb_login'><fb:login-button on-login="show.facebookSignup()">Register with Facebook</fb:login-button></span>
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
