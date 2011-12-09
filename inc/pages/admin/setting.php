<?php
$template = 'setting.tpl';
require_once('pages.inc');

$tpl->assign('setting',$ct->getSetting($option));

require_once('display.inc');
?>
