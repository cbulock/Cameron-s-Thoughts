<?php
$template = 'user.tpl';
require_once('pages.inc');

$tpl->assign('user',$ct->getUser($option,array('callby'=>'id')));

require_once('display.inc');
?>
