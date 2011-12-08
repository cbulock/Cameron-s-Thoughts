<?php
$template = 'api_param.tpl';
require_once('pages.inc');

$param = $ct->getMethodParameter($option);

$tpl->assign('param',$param);
$tpl->assign('method',$ct->getAPIMethod($param['method'],array('callby'=>'id')));

require_once('display.inc');
?>
