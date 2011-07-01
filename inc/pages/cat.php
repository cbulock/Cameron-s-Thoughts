<?php
$template = 'cat.tpl';
require_once('pages.inc');

$cat = call('getCat',$basename,array('field'=>'category_basename'));
$entries = call('getCatEntries',$cat['category_id']);

include('stats.inc');

$tpl->assign('entries',$entries);
$tpl->assign('cat',$cat);
$tpl->assign('query_count',$ct->getQueryCount());
$tpl->assign('cache_count',$ct->getCacheCount());

require_once('display.inc');
?>
