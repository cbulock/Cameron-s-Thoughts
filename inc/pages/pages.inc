<?php
require_once(SMARTY_DIR.'Smarty.class.php');
$tpl = new Smarty;
$tpl->loadPlugin('smarty_compiler_switch');
$tpl->setTemplateDir(TPL_DIR);
$tpl->setCacheDir(SMARTY_DIR.'../cache/');
$tpl->setCompileDir(SMARTY_DIR.'../templates_c/');
$tpl->setConfigDir(SMARTY_DIR.'../configs');
?>
