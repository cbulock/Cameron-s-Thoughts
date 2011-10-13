{include file='head.tpl' title_append='Edit Entry'}

<h2>Edit Entry</h2>

<form>

<p>
Title:<br />
<input id='postTitle' value='{$entry.entry_title}' />
</p>

<p>
Category:<br />
<select id='postCategory'>
 <option value=''>(None)</option>
 {foreach from=$cats item=cat}
  <option value='{$cat.category_id}'{if $cat.category_id == $entry.entry_category_id} selected='selected'{/if}>{$cat.category_label}</option>
 {/foreach}
</select>
</p>

<p>
Text:<br />
<textarea id='postText' class='largeSpace'>{$entry.entry_text}</textarea>
</p>

<p>
Excerpt:<br />
<textarea id='postExcerpt' class='smallSpace'>{$entry.entry_excerpt}</textarea>
</p>

<p>
Keywords:<br />
<input id='postKeywords' value='{$entry.entry_title}' />
</p>

<input type='submit' id='editEntry' value='Save Post' />
</form>

<h3>Comments</h3>

{foreach from=$comments item=comment}
<div id='c{$comment.id}' class='comment'>
<p class='comment_body'>{$comment.text}</p>
<p class='comment_footer'>Comment by: <a href='/admin/user/{$comment.user}'>{$comment.author}</a> on {$comment.created|date_format:"F d, Y h:i A"} [<a href='/admin/comment/{$comment.id}'>Edit Comment</a>]</p>
</div>
{/foreach}

{include file='footer.tpl'}
