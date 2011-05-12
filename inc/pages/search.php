<?php

require_once('pages.inc');

$search = $ct->search($term);

include('stats.inc');

$tpl->assign('term',$term);
$tpl->assign('count',$search['count']);
if ($search['count']) $tpl->assign('results',$search['results']);
$tpl->assign('query_count',$ct->getQueryCount());
$tpl->assign('cache_count',$ct->getCacheCount());

$tpl->display('search.tpl');
?>

