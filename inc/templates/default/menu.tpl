<ul class='main_nav'>
 <li><p>Posts</p>
  <ul class='sub_nav'>
   <li><a href=''>Archives</a></li>
{foreach from=$cats item=cat}
   <li><a href='/cat/{$cat.category_basename}/'>{$cat.category_label}</a></li>
{/foreach}
  </ul>
 </li>
 <li><p>About</p>
  <ul class='sub_nav'>
   <li><a href=''>Me</a></li>
   <li><a href=''>Cameron's Thoughts</a></li>
  </ul>
 </li>
 <li><p>Connect</p>
  <ul class='sub_nav'>
   <li><a href=''>Contact Form</a></li>
   <li><a href=''>Facebook</a></li>
  </ul>
 </li>
</ul>