<?php

$format = substr(strrchr($request, '.'), 1);
if (strpos($request,'/')) {
 $url_parameters = explode('.',substr(strstr($request, '/'), 1));
 $url_parameters = explode('/',$url_parameters[0]);
}
$method = explode('.',$request);
$method = explode('/',$method[0]);
$method = $method[0];

$url_parameters[] = $_REQUEST;

$statdata = array(
 'ip' => $_SERVER['REMOTE_ADDR'],
 'host' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
 'page' => $_SERVER['REQUEST_URI'],
 'file' => $_SERVER['SCRIPT_FILENAME'],
 'referer' => $_SERVER['HTTP_REFERER'],
 'agent' => $_SERVER['HTTP_USER_AGENT'],
 'response' => $_SERVER['REDIRECT_STATUS'],
 'lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
 'request' => print_r(apache_request_headers(),TRUE),
 'type' => 'api',
 'method' => $method
);
$ct->addStat($statdata);

try {
 $result = call_user_func_array(array($ct,$method),$url_parameters);
}
catch(Exception $e) {
 $result = array(
  'error' => $e->getMessage(),
  'error_number' => $e->getCode()
 );
}

header ('Content-type: application/json');
echo json_encode($result);
?>
