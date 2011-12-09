<?php
$template = 'static.tpl';
require_once('pages.inc');

$entry = $ct->getEntry($basename,array('blogid'=>3));
$comments = $ct->getComments($entry['entry_id'],array('blogid'=>3));
$comment_count_text = $ct->commentCountText($entry['comment_count']);

include('stats.inc');

$tpl->assign('entry',$entry);
if ($comments) $tpl->assign('comments',$comments);
$tpl->assign('comment_count_text',$comment_count_text);
$tpl->assign('query_count',$ct->getQueryCount());
$tpl->assign('cache_count',$ct->getCacheCount());

require_once('display.inc');
?>

