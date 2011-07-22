<footer>
{if isset($cat)}
<div>Category: <a href='/cat/{$cat.category_basename}/'>{$cat.category_label}</a></div>
{/if}
<div>Posted: <a href='{$create.link}'><time datetime='{$create.dateiso}' pubdate>{$create.date}</time></a></div>
</footer>
</article>
<div class="g-plusone" data-size="standard" data-count="true"></div>
<fb:like></fb:like>
