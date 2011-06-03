<!DOCTYPE html>
<html>
<head>
{if $title_append == ''}
<title>{$site_name}</title>
{else}
<title>{$site_name} - {$title_append}</title>
{/if}
<link rel='shortcut icon' type='image/png' href='/css/img/cameron_icon.png' />
<link rel='alternate' type='application/rss+xml' title='RSS' href='/rss' />
<link rel='stylesheet' type='text/css' href='/css/default/grey.css' title='Default Grey' />
<link rel='stylesheet' type='text/css' href='/css/custom-theme/jquery-ui-1.8.5.custom.css' />
<link rel='alternate' type='application/rss+xml' title='RSS' href='/rss' />
<link title='{$site_name}' type='application/opensearchdescription+xml' rel='search' href='/opensearch' />
<script type='text/javascript' src='http://code.jquery.com/jquery-1.5.min.js'></script>
<script type='text/javascript' src='/js/jquery-ui-1.8.5.custom.min.js'></script>
<script type='text/javascript' src='/js/jquery.ct.js'></script>
<script type='text/javascript' src='/js/autoresize.jquery.min.js'></script>
<script type='text/javascript' src='/js/raphael.js'></script>
<script type='text/javascript' src='/js/main.js'></script>
</head>
<body>
