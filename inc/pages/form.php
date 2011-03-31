<?php
//Handles all forms that are not submitted via ajax
require_once('pages.inc');

switch($name) {
 case 'login'://Login Form
  $tpl->assign('title','Login');
  $tpl->assign('button','Login');
  if ($_POST['username']) {
   try {
    $response = call('login',$_POST['username'],array('pass'=>$_POST['password']));
   }
   catch (exception $e){
    $tpl->assign('error',$e->getMessage());
   }
   if ($response) {
    if ($_POST['referer']) {
     header('Location: '.$_POST['referer']);
    }
    else {
     header('Location: '.LOCATION);
    }
   }
  }
 break;
 case 'signup'://Signup Form
  $tpl->assign('title','Sign Up');
  $tpl->assign('button','Sign Up');
  if ($_POST['username']) {
   try {
    $response = call('createUser',$_POST['username'],array(
     'pass'=>$_POST['password'],
     'email'=>$_POST['email'],
     'name'=>$_POST['name'],
     'url'=>$_POST['url']
    ));
   }
   catch (exception $e){
    $tpl->assign('error',$e->getMessage());
   }
   if ($response) {
    if ($_POST['referer']) {
     header('Location: '.$_POST['referer']);
    }
    else {
     header('Location: '.LOCATION);
    }
   }
  }
 break;
 case 'contact'://Contact Form
  $tpl->assign('title','Send Message');
  $tpl->assign('button','Send');
  if ($_POST['contact_message']) {
   try {
    $response = call('sendMessage',array('name'=>$_POST['contact_name'],'email',$_POST['contact_email'],'message',$_POST['contact_message']));
   }
   catch (exception $e){
    $tpl->assign('error',$e->getMessage());
   }
   if ($response) {
    if ($_POST['referer']) {
     header('Location: '.$_POST['referer']);
    }
    else {
     header('Location: '.LOCATION);
    }
   }
  }
 break;
 case 'logout'://Logout's only
  try {
   $response = call('logout');
  }
  catch (exception $e){
   $tpl->assign('error',$e->getMessage());
  }
  if ($response) {
   if ($_SERVER['HTTP_REFERER']) {
    header('Location: '.$_SERVER['HTTP_REFERER']);
   }
   else {
    header('Location: '.LOCATION);
   }
  }
 break;
 case 'comment'://Posting comments only
  //how's this work?
 break;
}

if ($_POST['referer']) {
 $tpl->assign('referer',$_POST['referer']);
} 
else {
 $tpl->assign('referer',$_SERVER['HTTP_REFERER']);
}
$tpl->assign('name',$name);
$tpl->display('form.tpl');
?>
