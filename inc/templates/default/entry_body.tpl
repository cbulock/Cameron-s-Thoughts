<h2><a href='{$entry.entry_link}'>{$entry.entry_title}</a></h2>
{if $entry.entry_convert_breaks == '0'} 
<div id='entry'>{$entry.entry_text|html_entity_decode}</div>
{else}
<div id='entry'>{$entry.entry_text|nl2br|html_entity_decode}</div>
{/if}
