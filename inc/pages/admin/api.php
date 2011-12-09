<?php
$template = 'api.tpl';
require_once('pages.inc');

$tpl->assign('methods',$ct->getAPIMethods());

require_once('display.inc');
?>
