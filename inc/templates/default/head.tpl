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
<link rel='stylesheet' type='text/css' href='{$static_location}/css/default/dark_grey.css' title='Dark Grey' />
<link rel='stylesheet' type='text/css' href='{$static_location}/css/custom-theme/jquery-ui-1.8.5.custom.css' />
<link title="{$site_name}" type='application/opensearchdescription+xml' rel='search' href='{$location}/opensearch' />

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-126218-1']);
  _gaq.push(['_trackPageview']);`
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</head>
<body>
