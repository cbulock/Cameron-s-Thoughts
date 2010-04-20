<div id='welcome'>
{if isset($authUser)}
Welcome, {$authUser.name}
{else}
Login
{/if}
</div>
<h1><a href='/'>{$settings.site.title}</a></h1>
<p id='sitetag'>{$settings.site.tag}</p>
