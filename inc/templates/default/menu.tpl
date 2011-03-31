<ul class='main_nav'>
 <li><p>Posts</p>
  <ul class='sub_nav'>
   <!-- This menu item isn't ready yet
   <li><a href=''>Archives</a></li> -->
{foreach from=$cats item=cat}
   <li><a href='/cat/{$cat.category_basename}/'>{$cat.category_label}</a></li>
{/foreach}
  </ul>
 </li>
 <li><p>About</p>
  <ul class='sub_nav'>
   <li><a href='/about/me/'>Me</a></li>
   <li><a href='/about/ct/'>{$site_name}</a></li>
  </ul>
 </li>
 <li><p>Connect</p>
  <ul class='sub_nav'>
   <li><a href='/form/contact' id='contact'>Contact Form</a></li>
   <li><a href='http://www.facebook.com/cbulock'>Facebook</a></li>
  </ul>
 </li>
</ul>
