<?php

$format = substr(strrchr($request, '.'), 1);
if (strpos($request,'/')) {
 $url_parameters = explode('.',substr(strstr($request, '/'), 1));
 $url_parameters = explode('/',$url_parameters[0]);
}
$method = explode('.',$request);
$method = explode('/',$method[0]);
$method = $method[0];
//print_r($url_parameters[0]);
switch(count($url_parameters)) {//I truely hate this and need to find a better way
 case 1:
  $result = $ct->$method($url_parameters[0],$_REQUEST);
//$result = $ct->$method($url_parameters[0]);
// echo $ct->getLastQuery();
 break;
 case 2:
  $result = $ct->$method($url_parameters[0],$url_parameters[1],$_REQUEST);
 break;
 case 3:
  $result = $ct->$method($url_parameters[0],$url_parameters[1],$url_parameters[2],$_REQUEST);
 break;
 case 4:
  $result = $ct->$method($url_parameters[0],$url_parameters[1],$url_parameters[2],$url_parameters[3],$_REQUEST);
 break;
 default:
  $result = $ct->$method($_REQUEST);
 break;
}

echo json_encode($result);
?>
