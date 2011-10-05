<?php
$template = 'newentry.tpl';
require_once('pages.inc');

$tpl->assign('cats',$ct->getCatList());

require_once('display.inc');
?>
