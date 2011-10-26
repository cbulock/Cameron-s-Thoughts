<?php

require_once('../util/log.class.php');
require_once('../util/cache.class.php');

class DB {
 protected $log;
 protected $cache;
 protected $db;

 public function __construct($host=DB_HOST, $user=DB_USER, $pass=DB_PASS) {
  $this->log = new Log;
  $this->cache = new Cache;

  $dsn = 'mysql:host='.$host;
  $db = new PDO($dsn, $user, $pass);
 }

 public function __destruct() {
  $db = NULL;
  unset($this->cache);
  unset($this->log);
 }

}
