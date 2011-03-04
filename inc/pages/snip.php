<?php

require_once('pages.inc');

switch($snip) {
 case 'comment_footer':
  $comment = $ct->getComment($option);
  if ($comment) $tpl->assign('comment',$comment);  
 break;
}

$tpl->display('snips/'.$snip.'.tpl');
?>
