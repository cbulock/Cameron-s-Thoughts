<?php
$template = 'entry.tpl';
require_once('pages.inc');

$tpl->assign('cats',$ct->getCatList());
$tpl->assign('entry',$ct->getEntry($option,array('callby'=>'id')));

require_once('display.inc');
?>
