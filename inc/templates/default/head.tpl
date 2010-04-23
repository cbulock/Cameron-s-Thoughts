<html>
{if $title_append == ''}
<title>{$settings.site.title}</title>
{else}
<title>{$settings.site.title} - {$title_append}</title>
{/if}
<link rel='stylesheet' type='text/css' href='/css/test.css' title='Test' />
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
<script type='text/javascript' src='/js/jquery.ct.js'></script>
<script type='text/javascript' src='/js/main.js'></script>
