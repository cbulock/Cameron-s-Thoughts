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
  //print_r($e);
  switch($e->getCode()) {
   case 1000:
    header("HTTP/1.0 404 Not Found");
   break;
  }
 }
?>
