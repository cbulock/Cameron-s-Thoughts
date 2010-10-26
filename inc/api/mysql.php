<?php

class DB {

private $queryCount;
private $directQueryCount;
private $queryLog = array();
private $link;
private $dbprefix;
private $cache;

/**********************************
   Database Access
**********************************/

public function getTable($table, $options = array()) {
 $defaults = array(
  'where' => '1',
  'orderBy' => '`id` ASC',
  'limit' => '5000',
  'key' => 'id',
  'cache' => TRUE,
  'expires' => DEFAULT_CACHE_EXPIRES 
 );
 $options = $this->setOptions($options,$defaults);
 $this->selectDatabase($table, $options['database']);
 $sql = 'SELECT * FROM `'.$table.'` WHERE '.$options['where'].' ORDER BY '.$options['orderBy'].' LIMIT '.$options['limit'];
 return $this->sqlProcessMulti($sql,array('sortkey'=>$options['key'],'cache'=>$options['cache'],'expires'=>$options['expires']));
}

public function getItem($table, $value, $options = array()) {
 $defaults = array(
  'field' => 'id',
  'cache' => TRUE,
  'expires' => DEFAULT_CACHE_EXPIRES
 );
 $options = $this->setOptions($options,$defaults);
 $this->selectDatabase($table, $options['database']);
 $sql = 'SELECT * FROM `'.$table.'` WHERE `'.$this->sqlClean($options['field']).'` = \''.$this->sqlClean($value).'\'';
 return $this->sqlProcess($sql,$options);
}

public function insertItem($table, $data, $options = array()) {
 $defaults = array();
 $options = $this->setOptions($options,$defaults);
 $this->selectDatabase($table, $options['database']);
 foreach($data as $key => $val)
 {
  $keys .= '`'.$key.'`,';
  $vals .= '\''.$this->sqlclean($val).'\', ';
 }
 $keys = rtrim($keys, ', ');
 $vals = rtrim($vals, ', ');
 $sql = 'INSERT INTO `'.$table.'` (';
 $sql .= $keys.') VALUES ('.$vals.')';
 if ($this->sqlQuery($sql)) {
  return mysql_insert_id($this->link);
 } 
 else {
  return FALSE;
 }
}

public function updateItem($table, $value, $data, $options = array()) {
 $defaults = array(
  'field' => 'id'
 );
 $options = $this->setOptions($options,$defaults);
 $this->selectDatabase($table, $options['database']);
 $sql = 'UPDATE `'.$table.'` SET ';
 foreach($data as $key => $val) {
  $sql .= '`'.$key.'`=\''.$this->sqlclean($val).'\', ';
 }
 $sql = rtrim($sql, ', ');
 $sql .= ' WHERE `'.$options['field'].'`=\''.$value.'\'';
 return $this->sqlQuery($sql); //investigate if more should be returned
}

public function deleteItem($table, $value, $options = array()) { 
 $defaults = array(
  'field' => 'id'
 );
 $options = $this->setOptions($options,$defaults);
 $this->selectDatabase($table, $options['database']);
 $sql = 'DELETE FROM `'.$table.'` WHERE `'.$this->sqlClean($options['field']).'` = \''.$this->sqlclean($value) . '\'';
 return $this->sqlQuery($sql); //investigate if more should be returned
}

private function sqlQuery($sql, $options = array()) {
 $this->queryCount++;
 $this->queryLog[] = $sql;
 return mysql_query($sql);
}

/**********************************
   Helper Functions
**********************************/

private function selectDatabase($table, $db=NULL) {
 mysql_select_db($this->determineDatabase($table, $db),$this->link);
}

private function determineDatabase($table, $db=NULL) { //$table can be overloaded with $db value
 if ($db) return $db;
 $databases[$this->dbprefix.'accesslog'] = array($this->dbprefix.'accesslog','referers','sessions');
 $databases[$this->dbprefix.'cbulock'] = array($this->dbprefix.'cbulock','ads','ads_cat','blockedips','guid_admins','images','quotes','styles','users','comments','api_methods','api_parameters');
 $databases[$this->dbprefix.'mt2'] = array($this->dbprefix.'mt2','mt_blog','mt_entry','mt_category','mt_placement');
 $databases[$this->dbprefix.'ct3'] = array($this->dbprefix.'ct3','settings');
 foreach ($databases as $dbname => $database) {
  foreach ($database as $tablename) {
   if ($tablename == $table) return $dbname;
  }
 }
}

private function setOptions($options, $defaults) {
 foreach($defaults as $option => $value)
 {
  if (!isset($options[$option])) $options[$option] = $value;
 }
 return $options;
}

private function sqlProcess($sql,$options = array()) {//It may be more consistant to just use sqlProcessMulti for everything
 $defaults = array(
  'return' => 'all',
  'cache' => TRUE,
  'expires' => DEFAULT_CACHE_EXPIRES
 );
 $options = $this->setOptions($options, $defaults);
 if($this->cache->exists($sql,$options['expires'])) {
  $result = $this->cache->read($sql);
 }
 else {
  $sqlReturn = $this->sqlQuery($sql);
  if (!$sqlReturn) return false;
  $row = mysql_fetch_array($sqlReturn,MYSQL_ASSOC);
  if ($row) {
   if ($options['return'] == 'all') {
    foreach($row as $key => $value) {
     $result[$key] = $this->htmlParse(stripslashes($value));
    }
   }
   else {
    $result = $this->htmlParse(stripslashes(reset($row)));
   }
  }
  else {
   $result = false;
  }
  if ($options['cache']) {
   $this->cache->add($sql,$result);
  }
 }
 return $result;
}

private function sqlProcessMulti($sql,$options = array()) {
 $defaults = array(
  'sortkey' => 'id',
  'cache' => TRUE,
  'expires' => DEFAULT_CACHE_EXPIRES
 );
 $options = $this->setOptions($options, $defaults);
 if($this->cache->exists($sql,$options['expires'])) {
  $result = $this->cache->read($sql);
 }
 else {
  $sqlReturn = $this->sqlQuery($sql);
  if (!$sqlReturn) return false;
  while ($row = mysql_fetch_array($sqlReturn,MYSQL_ASSOC)) {
   $id = $row[$options['sortkey']];
   foreach($row as $key => $value) {
    $result[$id][$key] = $this->htmlParse(stripslashes($value));
   }
  }
  if ($options['cache']) {
   $this->cache->add($sql,$result);
  }
 }
 return $result;
}

private function htmlParse($html) {
 return preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($html));
}

public function sqlClean($str) {
 if(!get_magic_quotes_gpc())
 {
  $str = mysql_real_escape_string($str);
 }
 else
 {
  $str = addslashes($str);
 }
 return $str;
}

/**********************************
   Core Functions
**********************************/

public function __construct($host,$user,$pass,$options = array()) {
 $defaults = array(
  'prefix' => 'cbulock_',
  'cache' => NULL
 );
 $options = $this->setOptions($options, $defaults);
 if ($options['cache']) $this->cache = $options['cache'];
 $this->link = mysql_connect($host,$user,$pass);
 $this->dbprefix = $options['prefix'];
}

public function __destruct() {
 mysql_close($this->link);
}

/**********************************
   Class Management
**********************************/
public function getQueryCount() {
 return $this->queryCount;
}

public function getDirectQueryCount() {
 return $this->directQueryCount;
}

public function getLastQuery() {
 return end($this->queryLog);
}

public function getQueryLog() {
 return $this->queryLog;
}

public function directQuery($sql,$db) { //this should rarely be used
 $this->directQueryCount++; 
 $this->selectDatabase($db);
 return $this->sqlQuery($sql);
}

public function directProcessQuery($sql,$db,$options = array()) {
 $this->directQueryCount++;
 $this->selectDatabase($db);
 return $this->sqlProcess($sql,$options);
}

public function directProcessMultiQuery($sql,$db,$options = array()) {
 $this->directQueryCount++;
 $this->selectDatabase($db);
 return $this->sqlProcessMulti($sql,$options);
}


//End DB
}
