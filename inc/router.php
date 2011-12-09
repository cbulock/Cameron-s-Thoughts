<?php

$raw_request = explode('?',$_SERVER['REQUEST_URI']);
$full_request = $raw_request[0];
$query = $raw_request[1];
$request = explode('/',trim($full_request,'/'));

switch($full_request) {
 case '/about_me.html':
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: http:'.LOCATION.'/about/me/');
  exit();
 break;
 case '/about_this_site.html':
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: http:'.LOCATION.'/about/ct/');
  exit();
 break;
 case '/xml/full.xml':
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: http:'.LOCATION.'/rss');
  exit();
 break;
 case '/about/me/':
  $basename = 'about_me';
  include(INCLUDE_DIR.'pages/static.php');
 break;
 case '/about/ct/':
  $basename = 'about_this_site';
  include(INCLUDE_DIR.'pages/static.php');
 break;
 case '/rss':
  $pagetype = 'rss';
  include(INCLUDE_DIR.'pages/home.php');
 break;
 case '/opensearch':
  $pagetype = 'opensearch';
  include(INCLUDE_DIR.'pages/home.php');
 break;
}

switch(substr($request[0],0,2)) {//general pages
 case ''://homepage
  include(INCLUDE_DIR.'pages/home.php');
 break;
 case '20'://indivdual and monthly
  if(isset($request[2])) {//if a third value, it's an indivdual entry
   $basename = substr($request[2],0,-5);
   include(INCLUDE_DIR.'pages/entry.php');
  }
  else {//two values is monthly page
   $year = $request[0];
   $month = $request[1];
   include(INCLUDE_DIR.'pages/month.php');
  }
 break;
 case 'ca'://category
  $basename = $request[1];
  include(INCLUDE_DIR.'pages/cat.php');
 break;
 case 'pa'://page
  $basename = $request[1];
  include(INCLUDE_DIR.'pages/static.php');
 break;
 case 'sn'://snip
  $snip = $request[1];
  include(INCLUDE_DIR.'pages/snip.php');
 break;
 case 'ap'://api
  $request = substr($full_request,5);
  include(API_DIR.'public.php');
 break;
 case 'se'://search
  $term = $request[1];
  include(INCLUDE_DIR.'pages/search.php');
 break;
 case 'pr'://profile and process
  switch($request[0]) {
   case 'profile':
    $user = $request[1];
    include(INCLUDE_DIR.'pages/profile.php');
   break;
   case 'process':
    $name = $_POST['name'];
    include(INCLUDE_DIR.'pages/form.php');
   break;
  }
 break;
 case 'er'://error
  $errornum = $request[1];
  include(INCLUDE_DIR.'pages/error.php');
 break;
 case 'fo'://form
  $name = $request[1];
  include(INCLUDE_DIR.'pages/form.php');
 break;
 case 'ad'://admin
  if (isset($request[1])) {
   $page = $request[1];
  }
  else {
   $page = 'home';
  }
  if (isset($request[2])) $option = $request[2];
  include(INCLUDE_DIR.'pages/admin/'.$page.'.php');
 break;
 case 'rs'://rss
  $pagetype = 'rss';
  include(INCLUDE_DIR.'pages/home.php');
 break;
 case 'op'://opensearch
  $pagetype = 'opensearch';
  include(INCLUDE_DIR.'pages/home.php');
 break;
}

?>
