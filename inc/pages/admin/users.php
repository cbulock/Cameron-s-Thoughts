<?php
$template = 'users.tpl';
require_once('pages.inc');

$tpl->assign('users',$ct->getUserList());

require_once('display.inc');
?>
