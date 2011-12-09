<?php
//This file will contain the primary include info for all files on this site

//Initialize APIs
require_once(API_DIR.'base_api.php');
require_once(API_DIR.'extended_api.php');
if (!$ct) $ct = new ExtendedAPI();

//Exception Handling
require_once(INCLUDE_DIR.'/backend/exceptions.php');
?>
