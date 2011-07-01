<?php
$etag = md5($tpl->fetch($template));

if ($_SERVER['HTTP_IF_NONE_MATCH'] == $etag){
 header('HTTP/1.0 304 Not Modified');
}
else {
 header('ETag: '.$etag);
 $tpl->display($template);
}
?>
