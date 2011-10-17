<?php
$template = 'settings.tpl';
require_once('pages.inc');

$tpl->assign('settings',$ct->getSettingList());

require_once('display.inc');
?>
