<?php
$template = 'entry.tpl';
require_once('pages.inc');

$entry = $ct->getEntry($option,array('callby'=>'id'));

$tpl->assign('entry',$entry);
$tpl->assign('cats',$ct->getCatList());
$tpl->assign('comments',$ct->getComments($entry['entry_id']));

require_once('display.inc');
?>
