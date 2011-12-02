{include file='head.tpl' title_append='Edit API Method'}

<h2>Edit API Method</h2>

<form>

<p>
Name:<br />
{$method.value}
</p>

<p>
Authentication Required:<br />
<select id='methodAuth'>
 <option value='0'{if $method.auth == '0'} selected='selected'{/if}>No</option>
 <option value='1'{if $method.auth == '1'} selected='selected'{/if}>Yes</option>
</select>
</p>

<p>
Category:<br />
<select id='methodCategory'>
 <option value=''>(None)</option>
 {foreach from=$cats item=cat}
  <option value='{$cat.id}'{if $cat.id == $method.cat} selected='selected'{/if}>{$cat.name}</option>
 {/foreach}
</select>
</p>

Info:<br />
<textarea id='methodInfo' class='smallSpace'>{$method.info}</textarea>
</p>

<input type='submit' id='editMethod' value='Save Method' />
</form>

<h3>Parameters</h3>

<table>
<tr>
<th>Value</th><th>Required</th><th>Default</th>
</tr>
{foreach from=$method.params item=param}
<tr id='p{$param.id}'>
<td><a href='/admin/api_param/{$param.id}'>{$param.value}</a></td><td>{$param.required}</td><td>{$param.default}</td>
</tr>
{/foreach}

</table>

{include file='footer.tpl'}
