<div id='welcome'>
{if isset($authUser)}
Welcome, {$authUser.name} | <a href='' id='logout'>Logout</a>
{else}
<a href='' id='login'>Login</a>
{/if}
</div>
<h1><a href='/'>{$settings.site.title}</a></h1>
<p id='sitetag'>{$settings.site.tag}</p>
