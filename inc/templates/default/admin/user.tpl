{include file='head.tpl' title_append='Edit User'}

<h2>Edit User</h2>

<form>

<p>
Login:<br />
<input id='userLogin' value='{$user.login}' />
</p>

<p>
Name:<br />
<input id='userName' value='{$user.name}' />
</p>

<p>
URL:<br />
<input id='userURL' type='url' value='{$user.url}' />
</p>

<p>
Email:<br />
<input id='userEmail' type='email' value='{$user.email}' />
</p>

<p>
Type:<br />
<select id='userType'>
 <option value='admin'{if $user.type == 'admin'} selected='selected'{/if}>Admin</option>
 <option value='user'{if $user.type == 'user'} selected='selected'{/if}>User</option>
</select>
</p>

<p>
Service: {$user.service}
{if $user.service == 2}
<br />
Service ID: {$user.service_id}
{/if}

</p>

<input type='submit' id='editUser' value='Save User' />
</form>

{include file='footer.tpl'}
