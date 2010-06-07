<div><a href='{$entry.entry_link}#comments'>
{if $entry.comment_count == 0}
No comments yet
{elseif $entry.comment_count == 1}
1 comment
{else}
{$entry.comment_count} comments
{/if}
</a></div>
