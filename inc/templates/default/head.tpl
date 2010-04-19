<html>
{if $title_append == ''}
<title>{$settings.site.title}</title>
{else}
<title>{$settings.site.title} - {$title_append}<title>
{/if}
<link rel='stylesheet' type='text/css' href='/css/test.css' title='Test' />
