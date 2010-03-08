<html>

<!--This is a very base design and will be expanded on greatly-->

<title>Cameron's Thoughts<title>

{foreach from=$entries item=entry}
<h3>{$entry.entry_title}</h3>
<div>{$entry.entry_text|nl2br|html_entity_decode}</div>
<div>{$comment_counts[$entry.entry_id]} comment(s)</div>
{/foreach}

</html>
