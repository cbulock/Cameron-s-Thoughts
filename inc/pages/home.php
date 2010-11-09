<?php

require_once('pages.inc');

$entries = $ct->getNewEntries(array('count'=>MAINPAGE_COUNT));
$cats = $ct->getCatList();

$query_count = $ct->getQueryCount();
$cache_count = $ct->getCacheCount();

$tpl->assign('entries',$entries);
$tpl->assign('cats',$cats);
$tpl->assign('query_count',$query_count);
$tpl->assign('cache_count',$cache_count);

$tpl->display('home.tpl');
?>
