{include file='head.tpl' title_append='Edit Comment'}

<h2>Edit Comment</h2>

<form>

<p>
Text:<br />
<textarea id='commentText' class='smallSpace'>{$comment.text}</textarea>
</p>

<p>
Posted by: <a href='/admin/user/{$comment.user}'>{$comment.author}</a><br/><!--This should link to the user-->
Posted: {$comment.created|date_format:"F d, Y h:i A"}
</p>

<button type='submit' id='editComment'>Save Comment</button>
<button type='submit' id='deleteComment'>Delete Comment</button>
<input type='hidden' id='commentId' value='{$comment.id}' />
</form>

{include file='footer.tpl'}
