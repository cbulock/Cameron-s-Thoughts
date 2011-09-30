{include file='head.tpl' title_append='New Post'}

<h2>New Post</h2>

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
<textarea id='postText'></textarea>
</p>

<p>
Excerpt:<br />
<textarea id='postExcerpt'></textarea>
</p>

<p>
Keywords:<br />
<input id='postKeywords' />
</p>

<input type='submit' id='postEntry' value='Submit Post' />

{include file='footer.tpl'}
