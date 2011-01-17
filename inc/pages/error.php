<?php

require_once('pages.inc');

header("HTTP/1.0 ".$errornum);

$message = array(
 '404' => 'Page not found'
);

$tpl->assign('errornum',$errornum);
$tpl->assign('message',$message[$errornum]);

$tpl->display('error.tpl');
?>
