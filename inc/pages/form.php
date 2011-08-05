<?php
//Handles all forms that are not submitted via ajax
$template = 'form.tpl';
require_once('pages.inc');

switch($name) {
 case 'search'://Search Form
  header('Location: '.LOCATION.'/search/'.$_POST['search']);  
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
 case 'fbsignup'://Facebook Signup Form
  //Process signed_request from Facebook
  function parse_signed_request($signed_request, $secret) {
   list($encoded_sig, $payload) = explode('.',$signed_request, 2);
   $sig = base64_url_decode($encoded_sig);
   $data = json_decode(base64_url_decode($payload), true);
   if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
   }
   $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
   if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
   }
   return $data;
  }
  function base64_url_decode($input) {
   return base64_decode(strtr($input, '-_', '+/'));
  }
  $request = parse_signed_request($_POST['signed_request'], FACEBOOK_SECRET);

  try {
   $response = call('createUser','fb_'.$request['user_id'],array(
    'pass' => $request['registration']['email'],
    'email' => $request['registration']['email'],
    'name' => $request['registration']['name'],
    'service' => 2,
    'service_id' => $request['user_id']
   ));
  }
  catch (exception $e){
   $tpl->assign('error',$e->getMessage());
  }
  if ($response) {
   call('login','fb_'.$request['user_id'],array('pass'=>$request['registration']['email']));
   if ($_POST['referer']) {
    header('Location: '.$_POST['referer']);
   }
   else {
    header('Location: '.LOCATION);
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
      'pass' => $_POST['pass'],
      'email' => $_POST['email'],
      'name' => $_POST['fullname'],
      'url' => $_POST['url']
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
