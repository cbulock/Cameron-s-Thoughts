<?php

require_once('pages.inc');

switch($snip) {
 case 'comment_footer':
  $comments = $ct->getComments($entry['entry_id']);
  if ($comments) $tpl->assign('comments',$comments);  
 break;
}

$tpl->display('snips/'.$snip.'.tpl');
?>
