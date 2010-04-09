<?php

//this needs to not use $ct->auth() to authenticate, and also needs to block that method from being allowed publically
$format = substr(strrchr($request, '.'), 1);
if (strpos($request,'/')) {
 $url_parameters = explode('.',substr(strstr($request, '/'), 1));
 $url_parameters = explode('/',$url_parameters[0]);
}
$method = explode('.',$request);
$method = explode('/',$method[0]);
$method = $method[0];

$url_parameters[] = $_REQUEST;

$result = call_user_func_array(array($ct,$method),$url_parameters);

echo json_encode($result);
?>
