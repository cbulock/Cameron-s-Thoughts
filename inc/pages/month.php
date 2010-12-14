<?php

require_once('pages.inc');

$entries = $ct->getMonthlyEntries($month,$year);

$tpl->assign('entries',$entries);
$tpl->assign('query_count',$ct->getQueryCount());
$tpl->assign('cache_count',$ct->getCacheCount());

$tpl->display('month.tpl');
?>
