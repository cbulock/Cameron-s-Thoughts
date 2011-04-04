<?php
 function call($method,$req = array(), $opt = array()) {
  global $ct;
  try {
   $response = $ct->call($method, $req, $opt);
  }
  catch (exception $e) {
   exception_handler($e);
  }
 return $response;
 }

 function exception_handler($e) {
  global $ct;
  global $tpl;
  switch($e->getCode()) {
   case 1000:
    $errornum = 404;
    include(INCLUDE_DIR.'pages/error.php');
    die();
   break;
   default://may want to change this behavior as it may lead to uncaught exceptions
    throw new Exception($e->getMessage(),$e->getCode());
   break;
  }
 }
?>
