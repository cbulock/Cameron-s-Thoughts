<?php

require_once('pages.inc');

$cat = $ct->getCat($basename,array('field'=>'category_basename'));
$entries = $ct->getCatEntries($cat['category_id']);
$cats = $ct->getCatList();

$query_count = $ct->getQueryCount();

$tpl->assign('entries',$entries);
$tpl->assign('cat',$cat);
$tpl->assign('cats',$cats);
$tpl->assign('query_count',$query_count);

$tpl->display('cat.tpl');
?>
