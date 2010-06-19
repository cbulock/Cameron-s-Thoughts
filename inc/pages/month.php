<?php

require_once('pages.inc');

$entries = $ct->getMonthlyEntries($month,$year);

$query_count = $ct->getQueryCount();

$tpl->assign('entries',$entries);
$tpl->assign('query_count',$query_count);

$tpl->display('month.tpl');
?>
