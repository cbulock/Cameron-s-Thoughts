<?php
$template = 'entries.tpl';
require_once('pages.inc');

$tpl->assign('entries',$ct->getNewEntries(array('count'=>50)));

require_once('display.inc');
?>
