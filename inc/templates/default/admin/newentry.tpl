{include file='head.tpl' title_append='New Entry'}

<h2>New Entry</h2>

<form>

<p>
Title:<br />
<input id='postTitle' />
</p>

<p>
Category:<br />
<select id='postCategory'>
 <option value=''>(None)</option>
 {foreach from=$cats item=cat}
  <option value='{$cat.category_id}'>{$cat.category_label}</option>
 {/foreach}
</select>
</p>

<p>
Text:<br />
<textarea id='postText' class='largeSpace'></textarea>
</p>

<p>
Excerpt:<br />
<textarea id='postExcerpt' class='smallSpace'></textarea>
</p>

<p>
Keywords:<br />
<input id='postKeywords' />
</p>

<button type='submit' id='postEntry'>Submit Post</button>
</form>

{include file='footer.tpl'}
