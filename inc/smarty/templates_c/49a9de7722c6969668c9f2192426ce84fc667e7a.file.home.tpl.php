<?php /* Smarty version Smarty3-b8, created on 2010-03-08 00:18:02
         compiled from "/home/ct3/public_html/inc/templates/home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20836534054b94888a4105c1-18166749%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '49a9de7722c6969668c9f2192426ce84fc667e7a' => 
    array (
      0 => '/home/ct3/public_html/inc/templates/home.tpl',
      1 => 1268024615,
    ),
  ),
  'nocache_hash' => '20836534054b94888a4105c1-18166749',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<html>

<!--This is a very base design and will be expanded on greatly-->

<title>Cameron's Thoughts<title>

<?php  $_smarty_tpl->tpl_vars['entry'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('entries')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['entry']->key => $_smarty_tpl->tpl_vars['entry']->value){
?>
<h3><?php echo $_smarty_tpl->getVariable('entry')->value['entry_title'];?>
</h3>
<div><?php echo html_entity_decode(nl2br($_smarty_tpl->getVariable('entry')->value['entry_text']));?>
</div>
<div><?php echo $_smarty_tpl->getVariable('comment_counts')->value[$_smarty_tpl->getVariable('entry')->value['entry_id']];?>
 comment(s)</div>
<?php }} ?>

</html>
