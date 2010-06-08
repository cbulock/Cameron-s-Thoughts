<h4 id='comments'>
{if $entry.comment_count == 0}
No comments yet
{elseif $entry.comment_count == 1}
1 comment
{else}
{$entry.comment_count} comments
{/if}
</h4>
{foreach from=$comments item=comment}
<div id='c{$comment.id}' class='comment'>
<img src='{$comment.avatar}' alt='Avatar for {$comment.author}' />
<p class='comment_body'>{$comment.text}</p>
<p class='comment_footer'>Comment by: <a href='{$comment.url}'>{$comment.author}</a> on {$comment.created|date_format:"F d, Y h:i A"}</p>
</div>
{/foreach}
{if isset($authUser)}
<div id='new_comment' class='comment'>
<img src='{$authUser.avatar}' alt='Avatar for {$authUser.name}' />
<p class='comment_body'><textarea></textarea></p>
{else}
<p>You will need to be <a href='#' id='comment_login'>logged in</a> to add any comments.</p>
{/if}
