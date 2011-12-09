<!DOCTYPE html>
<html>
<head>
{if $title_append == ''}
<title>{$site_name} - Admin Panel</title>
{else}
<title>{$site_name} - Admin Panel - {$title_append}</title>
{/if}
<link rel='shortcut icon' type='image/png' href='{$static_location}/css/img/cameron_icon.png' />
<link rel='stylesheet' type='text/css' href='{$static_location}/css/admin/dark_grey.css' title='Dark Grey' />
<link rel='stylesheet' type='text/css' href='{$static_location}/css/custom-theme/jquery-ui-1.8.5.custom.css' />
</head>
<body>
<div id='fb-root' appid='{$facebook_app_id}'></div>
<h1>Administration Panel</h1>
{include file='menu.tpl'}
<article>
