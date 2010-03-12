<html>
<!--This is a very base design and will be expanded on greatly-->
<title>{$settings.site.title} - {$entry.entry_title}<title>

{include file='header.tpl'}

{include file='entry_body.tpl'}
{include file='entry_foot.tpl'}
<p id='comments'>
{if $comment_count == 0}
No comments yet
{elseif $comment_count == 1}
1 comment
{else}
{$comment_count} comments
{/if}
</p>
{foreach from=$comments item=comment}
<p>{$comment.text}</p>
{/foreach}
{include file='footer.tpl'}
