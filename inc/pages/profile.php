<?php

require_once('pages.inc');

$user = call('getUser',$user);

include('stats.inc');

$tpl->assign('user',$user);
$tpl->assign('query_count',$ct->getQueryCount());
$tpl->assign('cache_count',$ct->getCacheCount());

$tpl->display('profile.tpl');
?>
