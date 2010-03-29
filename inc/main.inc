<?php
//This file will contain the primary include info for all files on this site

//Content type, might want to do more to enable XML types
//header("Content-type: text/html; charset='UTF-8'");

//Initialize APIs
require_once(API_DIR.'mysql.php');
require_once(API_DIR.'api.php');
$ct = new API($settings);

//Settings stored in database
//require(INCLUDE_DIR.'/backend/read_settings.php');

//Authentication system
//require(INCLUDE_DIR.'/backend/auth.php');

//Statistics / GUID
//include(INCLUDE_DIR.'/backend/stat.php');

//Image System
//include(INCLUDE_DIR.'images/func.php');

?>
