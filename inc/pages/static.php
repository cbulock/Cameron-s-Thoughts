<?php

require_once('pages.inc');

$entry = $ct->getEntry($basename,array('blogid'=>3));
$comments = $ct->getComments($entry['entry_id'],array('blogid'=>3));
$comment_count_text = $ct->commentCountText($entry['comment_count']);

$query_count = $ct->getQueryCount();
$cache_count = $ct->getCacheCount();

$tpl->assign('entry',$entry);
if ($comments) $tpl->assign('comments',$comments);
$tpl->assign('comment_count_text',$comment_count_text);
$tpl->assign('query_count',$query_count);
$tpl->assign('cache_count',$cache_count);

$tpl->display('static.tpl');
?>

