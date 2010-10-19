{include file='head.tpl' title_append=$entry.entry_title}

{include file='header.tpl'}

<ul id='entry_nav'>
{if isset($prev_entry)}
<li id='prev_entry'><a href='{$prev_entry['entry_link']}'>{$prev_entry['entry_title']}</a></li>
{/if}
{if isset($next_entry)}
<li id='next_entry'><a href='{$next_entry['entry_link']}'>{$next_entry['entry_title']}</a></li>
{/if}
</ul>

{include file='entry_body.tpl'}
{include file='entry_foot.tpl'}
{include file='comments.tpl'}
{include file='footer.tpl'}
