<?php
//This file will contain the primary include info for all files on this site

//Content type, might want to do more to enable XML types
header("Content-type: text/html; charset='UTF-8'");

//Initialize APIs
require_once(FUNC_DIR.'main_func.php');
require_once(FUNC_DIR.'entry_func.php');
require_once(API_DIR.'db.php');
require_once(API_DIR.'api.php');
//$db = new DB($settings['db']['host'],$settings['db']['user'],$settings['db']['pass']);
//I'm thinking $api should initiate opening $db
$api = new API($settings);
//$api = new CT_API;

//Settings stored in database
//require(INCLUDE_DIR.'/backend/read_settings.php');

//Authentication system
//require(INCLUDE_DIR.'/backend/auth.php');

//Statistics / GUID
//include(INCLUDE_DIR.'/backend/stat.php');

//Image System
//include(INCLUDE_DIR.'images/func.php');

//HTML Template
/*
require(FUNC_DIR.'htmltemp.class.php');
$html = new HTMLTemp;
$html->ml = $site['ml'];
$html->mlver = $site['mlver'];
$html->htmlattrib = array("lang"=>$site['lang']);
*/


?>
