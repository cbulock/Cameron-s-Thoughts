<?php

require_once('pages.inc');

$cat = $ct->getCat($basename,array('field'=>'category_basename'));
$entries = $ct->getCatEntries($cat['category_id']);

include('stats.inc');

$tpl->assign('entries',$entries);
$tpl->assign('cat',$cat);
$tpl->assign('query_count',$ct->getQueryCount());
$tpl->assign('cache_count',$ct->getCacheCount());

$tpl->display('cat.tpl');
?>
