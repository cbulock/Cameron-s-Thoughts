<html>
<!--This is a very base design and will be expanded on greatly-->
<title>Cameron's Thoughts<title>

{foreach from=$entries item=entry}
{include file='entry_body.tpl'}
<div><a href='{$entry.entry_link}#comments'>
{if $comment_counts[$entry.entry_id] == 0}
No comments yet
{elseif $comment_counts[$entry.entry_id] == 1}
1 comment
{else}
{$comment_counts[$entry.entry_id]} comments
{/if}
</div>
{/foreach}
{include file='footer.tpl'}
