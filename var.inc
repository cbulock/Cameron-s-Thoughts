<?php
include('dbpass.inc');
define('ROOT_DIR','/home/ct3/public_html/');
define('INCLUDE_DIR',ROOT_DIR.'inc/');
define('TPL_DIR',INCLUDE_DIR.'templates/');
define('SMARTY_DIR',INCLUDE_DIR.'smarty/libs/');
define('LOG_DIR',INCLUDE_DIR.'logs/');
define('API_DIR',INCLUDE_DIR.'api/');
//define('FUNC_DIR',INCLUDE_DIR.'func/');
//define('CLASS_DIR',INCLUDE_DIR.'classes/');

$settings['site'] = array(
 'title' => "Cameron's Thoughts",
 'tag'   => "Why would you want to know what I'm thinking? Weirdos.",
 'ml'    => 'xhtml',
 'mlver' => '1.0',
 'lang'  => 'en'
);

$settings['db'] = array(
 'host' => 'localhost',
 'user' => 'cbulock_cbulock',
 'pass' => DB_PASS
);

define('MAINPAGE_COUNT',20);
?>
