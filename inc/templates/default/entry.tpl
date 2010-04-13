{include file='head.tpl' title_append=$entry.entry_title}

{include file='header.tpl'}

{if isset($prev_entry)}
<div id='prev_entry'><a href='{$prev_entry['entry_link']}'>{$prev_entry['entry_title']}</a></div>
{/if}
{if isset($next_entry)}
<div id='next_entry'><a href='{$next_entry['entry_link']}'>{$next_entry['entry_title']}</a></div>
{/if}

{include file='entry_body.tpl'}
{include file='entry_foot.tpl'}
{include file='comments.tpl'}
{include file='footer.tpl'}
