{include file='head.tpl' title_append='Edit Setting'}

<h2>Edit Setting</h2>

<form>

<p>
Name:<br />
{$setting.name}
</p>

<p>
Value:<br />
<input id='settingValue' value='{$setting.value}' />
</p>

<p>
Visablity:<br />
<select id='settingPublic'>
 <option value='1'{if $setting.public == '1'} selected='selected'{/if}>Public</option>
 <option value='0'{if $setting.public == '0'} selected='selected'{/if}>Internal</option>
</select>
</p>

<input type='submit' id='editSetting' value='Save Setting' />
</form>

{include file='footer.tpl'}
