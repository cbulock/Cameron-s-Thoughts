<?php
$template = 'entry.tpl';
require_once('pages.inc');

$entry = call('getEntry',$basename);
if ($entry['prev_entry']) $prev_entry = call('getEntry',$entry['prev_entry'],array('callby'=>'id'));
if ($entry['next_entry']) $next_entry = call('getEntry',$entry['next_entry'],array('callby'=>'id'));
$comments = $ct->getComments($entry['entry_id']);
$comment_count_text = $ct->commentCountText($entry['comment_count']);
if ($entry['entry_category_id']) $cat = $ct->getCat($entry['entry_category_id']);
$create_date = strtotime($entry['entry_created_on']);
$create['dateiso'] = date('c',$create_date);
$create['date'] = date('M j, Y g:ia',$create_date);
$create['link'] = '/'.date('Y',$create_date).'/'.date('m',$create_date).'/';

include('stats.inc');

$tpl->assign('entry',$entry);
if ($prev_entry) $tpl->assign('prev_entry',$prev_entry);
if ($next_entry) $tpl->assign('next_entry',$next_entry);
if ($comments) $tpl->assign('comments',$comments);
if ($cat) $tpl->assign('cat',$cat);
$tpl->assign('create',$create);
$tpl->assign('comment_count_text',$comment_count_text);
$tpl->assign('query_count',$ct->getQueryCount());
$tpl->assign('cache_count',$ct->getCacheCount());

require_once('display.inc');
?>
