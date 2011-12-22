{include file='head.tpl' title_append='Edit API Method Parameter'}

<h2>Edit API Method Parameter</h2>

<form>

<p>
Value:<br />
{$param.value}
</p>

<p>
Method:<br />
<a href='/admin/api_method/{$method.value}'>{$method.value}</a>
</p>

<p>
Required:<br />
<select id='paramReq'>
 <option value='0'{if $param.required == '0'} selected='selected'{/if}>No</option>
 <option value='1'{if $param.required == '1'} selected='selected'{/if}>Yes</option>
</select>
</p>

<p>
Is in URL:<br />
<select id='paramURL'>
 <option value='0'{if $param.url_param == '0'} selected='selected'{/if}>No</option>
 <option value='1'{if $param.url_param == '1'} selected='selected'{/if}>Yes</option>
</select>
</p>

<p>
Default:<br />
<input id='paramDefault' value='{$param.default}' />
</p>

Info:<br />
<textarea id='paramInfo' class='smallSpace'>{$param.info}</textarea>
</p>

<input type='submit' id='editParam' value='Save Parameter' />
</form>

{include file='footer.tpl'}
