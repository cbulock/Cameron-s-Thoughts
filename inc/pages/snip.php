<?php
$template = 'snips/'.$snip.'.tpl';
require_once('pages.inc');

switch($snip) {
 case 'comment_footer':
  $comment = $ct->getComment($option);
  if ($comment) $tpl->assign('comment',$comment);  
 break;
}

require_once('display.inc');
?>
