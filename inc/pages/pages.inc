<?php
$statdata = array(
 'ip' => $_SERVER['REMOTE_ADDR'],
 'host' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
 'page' => $_SERVER['REQUEST_URI'],
 'file' => $_SERVER['SCRIPT_FILENAME'],
 'referer' => $_SERVER['HTTP_REFERER'],
 'agent' => $_SERVER['HTTP_USER_AGENT'],
 'response' => $_SERVER['REDIRECT_STATUS'],
 'lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
 'request' => print_r(apache_request_headers(),TRUE),
 'type' => 'page'
);
$ct->addStat($statdata);

require_once(SMARTY_DIR.'Smarty.class.php');
$tpl = new Smarty;

$tpl->setTemplateDir(TPL_DIR.TYPE.'/');
$tpl->setCacheDir(SMARTY_DIR.'../cache/');
$tpl->setCompileDir(SMARTY_DIR.'../templates_c/');
$tpl->setConfigDir(SMARTY_DIR.'../configs');

$tpl->assign('settings',$settings);

$authUser = $ct->getAuthUser();
if ($authUser) {
 $tpl->assign('authUser',$authUser);
}

?>
