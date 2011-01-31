<!DOCTYPE html>
<html>
<head>
{if $title_append == ''}
<title>{$site_name}</title>
{else}
<title>{$site_name} - {$title_append}</title>
{/if}
<link rel='shortcut icon' type='image/png' href='/css/img/cameron_icon.png' />
<link rel='stylesheet' type='text/css' href='/css/grey.css' title='Main' />
<link rel='stylesheet' type='text/css' href='/css/custom-theme/jquery-ui-1.8.5.custom.css' />
<link rel='alternate' type='application/rss+xml' title='RSS' href='/rss' />
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js'></script>
<script type='text/javascript' src='/js/jquery-ui-1.8.5.custom.min.js'></script>
<script type='text/javascript' src='/js/jquery.ct.js'></script>
<script type='text/javascript' src='/js/autoresize.jquery.min.js'></script>
<script type='text/javascript' src="/js/raphael.js"></script>
<script type='text/javascript' src='/js/main.js'></script>
</head>
<body>
