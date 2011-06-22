<!DOCTYPE html>
<html>
<head>
{if $title_append == ''}
<title>{$site_name}</title>
{else}
<title>{$site_name} - {$title_append}</title>
{/if}
<link rel='shortcut icon' type='image/png' href='{$static_location}/css/img/cameron_icon.png' />
<link rel='alternate' type='application/rss+xml' title='RSS' href='/rss' />
<link rel='stylesheet' type='text/css' href='{$static_location}/css/default/grey.css' title='Default Grey' />
<link rel='stylesheet' type='text/css' href='{$static_location}/css/custom-theme/jquery-ui-1.8.5.custom.css' />
<link title='{$site_name}' type='application/opensearchdescription+xml' rel='search' href='{$static_location}/opensearch' />
<script type='text/javascript' src='http://code.jquery.com/jquery-1.5.min.js'></script>
<script type='text/javascript' src='{$static_location}/js/jquery-ui-1.8.5.custom.min.js'></script>
<script type='text/javascript' src='{$static_location}/js/jquery.ct.js'></script>
<script type='text/javascript' src='{$static_location}/js/autoresize.jquery.min.js'></script>
<script type='text/javascript' src='{$static_location}/js/raphael.js'></script>
<script type='text/javascript' src='{$static_location}/js/main.js'></script>
</head>
<body>
