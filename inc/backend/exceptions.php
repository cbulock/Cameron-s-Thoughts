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
  switch($e->getCode()) {
   case 1000:
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /error/404');
   break;
  }
 }
?>
