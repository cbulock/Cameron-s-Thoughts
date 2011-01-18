<?php
//This file will contain the primary include info for all files on this site

//Content type, might want to do more to enable XML types
//header("Content-type: text/html; charset='UTF-8'");

//Initialize APIs
require_once(API_DIR.'base_api.php');
require_once(API_DIR.'extended_api.php');
if (!$ct) $ct = new ExtendedAPI();

//Select page type
require_once(INCLUDE_DIR.'/backend/pagetype.php');

//Exception Handling
require_once(INCLUDE_DIR.'/backend/exceptions.php');
?>
