<?php

require_once('util/log/log.class.php');
require_once('util/cache/cache.class.php');
require_once('db/db.class.php');
require_once('base/cat/cat.class.php');

class API {
 protected $log;
 protected $cache;
 public $db;
 public $cat;
 
 public function __construct() {
  $this->log = new Log;
  $this->cache = new Cache;
  $this->db = new DB('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
  $this->cat = new Cat(&$this);
 }

 public function __destruct() {
  unset($this->db);
  unset($this->cache);
  unset($this->log);
 }

 public function test() {
  return "hello world";
 }

}
