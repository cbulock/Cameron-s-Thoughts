<?php
//Handles all forms that are not submitted via ajax
$template = 'form.tpl';
require_once('pages.inc');

switch($name) {
 case 'search'://Search Form
  
 break;
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
   if ($_POST['pass'] != $_POST['pass2']) {
    $tpl->assign('error',"Passwords don't match");
   }
   else {
    try {
     $response = call('createUser',$_POST['username'],array(
      'pass'=>$_POST['pass'],
      'email'=>$_POST['email'],
      'name'=>$_POST['fullname'],
      'url'=>$_POST['url']
     ));
    }
    catch (exception $e){
     $tpl->assign('error',$e->getMessage());
    }
    if ($response) {
     call('login',$_POST['username'],array('pass'=>$_POST['pass']));
     if ($_POST['referer']) {
      header('Location: '.$_POST['referer']);
     }
     else {
      header('Location: '.LOCATION);
     }
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
  catch (exception $e){//nothing can really be done currently on error
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
  try {
   $response = call('postComment',$_POST['postid'],array('text'=>$_POST['comment_text']));
   call('clearCache');//this is lame, should do this better in the future
  }
  catch (exception $e){//nothing can really be done currently on error
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
}

include('stats.inc');

if ($_POST['referer']) {
 $tpl->assign('referer',$_POST['referer']);
} 
else {
 $tpl->assign('referer',$_SERVER['HTTP_REFERER']);
}
$tpl->assign('name',$name);
require_once('display.inc');
?>
