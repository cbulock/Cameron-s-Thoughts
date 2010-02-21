<?php
//This file will direct to what is required

require_once('var.inc');
require_once(INCLUDE_DIR.'main.inc');

switch($_GET['type']) {
 case 'entry':
  $basename =  $_GET['basename'];
  $id = $_GET['id'];
  include(INCLUDE_DIR.'entry/display.php');
 break;
 case 'month':
  $year = "20".sprintf("%02d",($_GET['year']));
  $month = $_GET['month'];
  include(INCLUDE_DIR.'month/display.php');
 break;
 case 'cat':
  $basename = $_GET['basename'];
  include(INCLUDE_DIR.'cat/display.php');
 break;
 case 'api':
  $request = $_GET['request'];
  include(API_DIR.'public.php');
 break;
 default:
  include(INCLUDE_DIR.'home.php');
}
?>
