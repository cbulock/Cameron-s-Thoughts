<?php
$template = 'month.tpl';
require_once('pages.inc');

$entries = call('getMonthlyEntries',$month,$year);

include('stats.inc');

$tpl->assign('entries',$entries);
$tpl->assign('query_count',$ct->getQueryCount());
$tpl->assign('cache_count',$ct->getCacheCount());

require_once('display.inc');
?>
