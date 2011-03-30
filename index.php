<?php
//This file will direct to what is required

require_once('var.inc');
require_once(INCLUDE_DIR.'main.inc');

switch($_GET['type']) {
 case 'entry':
  $basename =  $_GET['basename'];
  include(INCLUDE_DIR.'pages/entry.php');
 break;
 case 'month':
  $year = "20".sprintf("%02d",($_GET['year']));
  $month = $_GET['month'];
  include(INCLUDE_DIR.'pages/month.php');
 break;
 case 'cat':
  $basename = $_GET['basename'];
  include(INCLUDE_DIR.'pages/cat.php');
 break;
 case 'static':
  $basename = $_GET['basename'];
  include(INCLUDE_DIR.'pages/static.php');
 break;
 case 'snip':
  if($_GET['option']) $option = $_GET['option'];
  $snip = $_GET['snip'];
  include(INCLUDE_DIR.'pages/snip.php');
 break;
 case 'api':
  $request = $_GET['request'];
  include(API_DIR.'public.php');
 break;
 case 'error':
  $errornum = $_GET['errornum'];
  include(INCLUDE_DIR.'pages/error.php');
 break;
 case 'form':
  $name = $_GET['name'];
  include(INCLUDE_DIR.'pages/form.php');
 break;
 default:
  include(INCLUDE_DIR.'pages/home.php');
}
?>
