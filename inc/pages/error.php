<?php
$template = 'error.tpl';
require_once('pages.inc');

header("HTTP/1.0 ".$errornum);
$_SERVER['REDIRECT_STATUS'] = $errornum;

$message = array(
 '404' => 'Page not found'
);

include('stats.inc');

$tpl->assign('errornum',$errornum);
$tpl->assign('message',$message[$errornum]);

require_once('display.inc');
?>
