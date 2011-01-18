<?php

require_once('pages.inc');

$mainpage_count = $ct->getSetting('mainpage_count');
$entries = $ct->getNewEntries(array('count'=>$mainpage_count['value']));

include('stats.inc');

$tpl->assign('entries',$entries);
$tpl->assign('query_count',$ct->getQueryCount());
$tpl->assign('cache_count',$ct->getCacheCount());

$tpl->display('home.tpl');
?>
