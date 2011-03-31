{include file='head.tpl'}

{include file='header.tpl'}

<h2>{$title}</h2>

{if isset($error)}
Error! {$error}
{/if}

<form method='post' action='/process'>
{include file="/snips/$name.tpl"}
<input type='hidden' name='referer' value='{$referer}'>
<input type='hidden' name='name' value='{$name}'>
<input type='submit' name='type' value='{$button}' />
</form>

{include file='footer.tpl'}

