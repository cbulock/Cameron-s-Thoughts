<?php
$template = 'api_method.tpl';
require_once('pages.inc');

$tpl->assign('method',$ct->getAPIMethod($option));
$tpl->assign('cats',$ct->getMethodCategories());

require_once('display.inc');
?>
