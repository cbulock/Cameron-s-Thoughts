<?php
require_once(SMARTY_DIR.'Smarty.class.php');
if (!$tpl) $tpl = new Smarty;

$tpl->setTemplateDir(TPL_DIR.TYPE.'/admin/');
$tpl->setCacheDir(SMARTY_DIR.'../cache/');
$tpl->setCompileDir(SMARTY_DIR.'../templates_c/');
$tpl->setConfigDir(SMARTY_DIR.'../configs');

//$settings
$site_name = $ct->getSetting('site_name');
$tpl->assign('site_name',$site_name['value']);
$site_tagline = $ct->getSetting('site_tagline');
$tpl->assign('site_tagline',$site_tagline['value']);
$tpl->assign('site_location',LOCATION);
$tpl->assign('static_location',STATIC_LOCATION);
$tpl->assign('facebook_app_id',FACEBOOK_APP_ID);

//$tpl->assign('cats',$ct->getCatList());

$authUser = $ct->getAuthUser();
if (!$authUser) {
 $tpl->display('mustlogin.tpl');
 exit();
}

if ($authUser['type'] != 'admin') {
 $tpl->display('adminrequired.tpl');
 exit();
}
?>
