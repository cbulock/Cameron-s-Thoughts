<?php
include('settings.inc');
define('INCLUDE_DIR',ROOT_DIR.'inc/');
define('TPL_DIR',INCLUDE_DIR.'templates/');
define('SMARTY_DIR',INCLUDE_DIR.'libs/smarty/libs/');
define('LOG_DIR',INCLUDE_DIR.'logs/');
define('API_DIR',INCLUDE_DIR.'api/');
define('CACHE_DIR',INCLUDE_DIR.'cache/');

define('DEFAULT_CACHE_EXPIRES',2); //can't pull from settings db as the db calls need a cache time
?>
