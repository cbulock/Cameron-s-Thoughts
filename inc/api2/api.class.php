<?php

require_once('util/log.class.php');
require_once('util/cache.class.php');
require_once('db/db.class.php');

class API {
 protected $log;
 protected $cache;
 protected $db;
 
 public function __construct() {
  $this->log = new Log;
  $this->cache = new Cache;
  $this->db = new DB;
 }

 public function __destruct() {
  unset($this->db);
  unset($this->cache);
  unset($this->log);
 }

}
