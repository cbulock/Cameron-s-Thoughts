<?php
require_once(SMARTY_DIR.'Smarty.class.php');
if (!$tpl) $tpl = new Smarty;

$tpl->setTemplateDir(TPL_DIR.TYPE.'/');
$tpl->setCacheDir(SMARTY_DIR.'../cache/');
$tpl->setCompileDir(SMARTY_DIR.'../templates_c/');
$tpl->setConfigDir(SMARTY_DIR.'../configs');

//$settings
$site_name = $ct->getSetting('site_name');
$tpl->assign('site_name',$site_name['value']);
$site_tagline = $ct->getSetting('site_tagline');
$tpl->assign('site_tagline',$site_tagline['value']);

$tpl->assign('cats',$ct->getCatList());

$authUser = $ct->getAuthUser();
if ($authUser) {
 $tpl->assign('authUser',$authUser);
}
?>
