{include file='head.tpl' title_append='Edit Entry'}

<h2>Edit Entry</h2>

<form>

<p>
Title:<br />
<input id='postTitle' value='{$entry.entry_title}' />
</p>

<p>
Category:<br />
<select id='postCategory'><!-- magic with the cat still needs to be done-->
 <option value=''>(None)</option>
 {foreach from=$cats item=cat}
  <option value='{$cat.category_id}'
  {if $cat.category_id == $entry.entry_category_id} selected='selected'{/if}
  >{$cat.category_label}</option>
 {/foreach}
</select>
</p>

<p>
Text:<br />
<textarea id='postText'>{$entry.entry_text}</textarea>
</p>

<p>
Excerpt:<br />
<textarea id='postExcerpt'>{$entry.entry_excerpt}</textarea>
</p>

<p>
Keywords:<br />
<input id='postKeywords' value='{$entry.entry_title}' />
</p>

<input type='submit' id='postEntry' value='Save Post' />

{include file='footer.tpl'}
