<h4 id='comments'>
{if $comment_count == 0}
No comments yet
{elseif $comment_count == 1}
1 comment
{else}
{$comment_count} comments
{/if}
</h4>
{foreach from=$comments item=comment}
<p>{$comment.text}</p>
<div>Comment by: <a href='{$comment.url}'>{$comment.author}</a> on {$comment.created|date_format:"F d, Y h:i A"}</div>
{/foreach}
