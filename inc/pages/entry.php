<?php

require_once('pages.inc');

$entry = $ct->getEntry($basename);
$prev_entry = $ct->getEntry($ct->prevEntry($entry['entry_id']),array('callby'=>'id'));
$next_entry = $ct->getEntry($ct->nextEntry($entry['entry_id']),array('callby'=>'id'));
$comments = $ct->getComments($entry['entry_id']);
$cat = $ct->getCat($ct->getCatID($entry['entry_id']));
$create_date = strtotime($entry['entry_created_on']);
$create['date'] = date('M j, Y g:ia',$create_date);
$create['link'] = '/'.date('Y',$create_date).'/'.date('m',$create_date).'/';

$query_count = $ct->getQueryCount();

$tpl->assign('entry',$entry);
if ($prev_entry) $tpl->assign('prev_entry',$prev_entry);
if ($next_entry) $tpl->assign('next_entry',$next_entry);
if ($comments) $tpl->assign('comments',$comments);
if ($cat) $tpl->assign('cat',$cat);
$tpl->assign('create',$create);
$tpl->assign('query_count',$query_count);

$tpl->display('entry.tpl');
?>
