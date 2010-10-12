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
