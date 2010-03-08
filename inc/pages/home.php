<?php

require_once('pages.inc');

$current_entry = $ct->lastEntry();
$entries = array();
$comments = array();
for($i = 0; $i < MAINPAGE_COUNT; $i++) {
 $entries[$current_entry] = $ct->getEntry($current_entry,array('callby'=>'basename'));
 $comment_counts[$current_entry] = $ct->commentCount($current_entry);
 if ($i < MAINPAGE_COUNT-1) {
  $current_entry = $ct->prevEntry($current_entry);
 }
}
$query_count = $ct->getQueryCount();


$tpl->assign('entries',$entries);
$tpl->assign('comment_counts',$comment_counts);

$tpl->display('home.tpl');
?>
