<?php
$template = 'comment.tpl';
require_once('pages.inc');

$tpl->assign('comment',$ct->getComment($option));

require_once('display.inc');
?>
