<?php
require_once(SMARTY_DIR.'Smarty.class.php');
$tpl = new Smarty;
$tpl->setTemplateDir(T_DIR);
$tpl->setCacheDir(SMARTY_DIR.'../cache/');
$tpl->setCompileDir(SMARTY_DIR.'../templates_c/');
$tpl->setConfigDir(SMARTY_DIR.'../configs');
?>
