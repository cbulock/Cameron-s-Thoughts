<h4 id='comments'>
{$comment_count_text}
</h4>
{foreach from=$comments item=comment}
<div id='c{$comment.id}' class='comment'>
<img src='{$comment.avatar}' alt='Avatar for {$comment.author}' class='avatar' />
<p class='comment_body'>{$comment.text}</p>
{include file='snips/comment_footer.tpl'}
</div>
{/foreach}
{if isset($authUser)}
<h4 id='leave_comment'>Leave a Comment</h4>
<div id='new_comment' class='comment'>
<img src='{$authUser.avatar}' alt='Avatar for {$authUser.name}' class='avatar' />
<form id='comment_form' action='/process' method='post'>
<input type='hidden' name='postid' id='postid' value='{$entry.entry_id}'>
<input type='hidden' name='name' value='comment'>
<p class='comment_body'><textarea id='comment_text' name='comment_text'></textarea></p>
<input type='submit' id='comment_submit' value='Save Comment' />
</form>
</div>
{else}
<p>You will need to be <a href='/form/login' id='comment_login'>logged in</a> to add any comments.</p>
{/if}
