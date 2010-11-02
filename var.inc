<?php
include('dbpass.inc');
define('ROOT_DIR','/home/ct3/public_html/');
define('INCLUDE_DIR',ROOT_DIR.'inc/');
define('TPL_DIR',INCLUDE_DIR.'templates/');
define('SMARTY_DIR',INCLUDE_DIR.'libs/smarty/libs/');
define('LOG_DIR',INCLUDE_DIR.'logs/');
define('API_DIR',INCLUDE_DIR.'api/');
define('CACHE_DIR',INCLUDE_DIR.'cache/');

$settings['site'] = array(
 'title' => "Cameron's Thoughts",
 'tag'   => "Why would you want to know what I'm thinking? Weirdos.",
 'ml'    => 'xhtml',
 'mlver' => '1.0',
 'lang'  => 'en'
);

$settings['db'] = array(
 'host' => 'cbulock.com',
 'user' => 'cbulock_cbulock',
 'pass' => DB_PASS
);

define('MAINPAGE_COUNT',20);
define('DEFAULT_CACHE_EXPIRES',2);
define('SITE_EMAIL','cbulock@cbulock.com');
define('ADMIN_EMAIL','cameron@bulock.com');
?>
