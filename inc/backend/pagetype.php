<?php
if ($_REQUEST['pagetype']) {
 define(TYPE,$_REQUEST['pagetype']);
}
else {
 define(TYPE,'default');
}

switch(TYPE) {
 case 'rss':
  header("Content-type: application/rss+xml; charset=UTF-8");
  break;
 default:
  header("Content-type: text/html; charset=UTF-8");
  break;
}
?>
