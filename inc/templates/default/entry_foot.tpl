{if isset($cat)}
<div>Category: <a href='/cat/{$cat.category_basename}/'>{$cat.category_label}</a></div>
{/if}
<div>Posted: <a href='{$create.link}'>{$create.date}</a></div>
<g:plusone></g:plusone>
<fb:like></fb:like>
