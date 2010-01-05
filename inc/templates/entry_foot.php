<?php
$html->text('Category: '. $html->linktag('/cat/'.$cat['category_basename'].'.html',$cat['category_label']));
$cdate = strtotime($entry['entry_created_on']);
$html->text('Posted: '. $html->linktag('/'.date('Y',$cdate).'/'.date('m',$cdate).'/index.html',date('M j, Y g:ia',$cdate)));
?>
