<?php
require_once(SMARTY_DIR.'Smarty.class.php');
$tpl = new Smarty;

$tpl->setTemplateDir(TPL_DIR.TYPE.'/');
$tpl->setCacheDir(SMARTY_DIR.'../cache/');
$tpl->setCompileDir(SMARTY_DIR.'../templates_c/');
$tpl->setConfigDir(SMARTY_DIR.'../configs');

$tpl->assign('settings',$settings);

$cats = $ct->getCatList();
$tpl->assign('cats',$cats);

$authUser = $ct->getAuthUser();
if ($authUser) {
 $tpl->assign('authUser',$authUser);
}

?>
