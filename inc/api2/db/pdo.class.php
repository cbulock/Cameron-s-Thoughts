<?php

require_once(API2_DIR.'util/log/log.class.php');
require_once(API2_DIR.'util/cache/cache.class.php');

class DB extends PDO {
 protected $log;
 protected $cache;
 protected $db;

 public function __construct($dsn, $user, $pass, $options=array()) {
  $this->log = new Log;
  $this->cache = new Cache;
  parent::__construct($dsn, $user, $pass, $options);   
  $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
 }

 public function __destruct() {
  unset($this->cache);
  unset($this->log);
 }

 public function fetch($query, $values=NULL) {
  $s = $this->prepare($query);
  $s->execute($values);
  return $s->fetchAll();
 }

}
