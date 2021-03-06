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
  'expires' => DEFAULT_CACHE_EXPIRES,
  'htmlParse' => TRUE
 );
 $options = $this->setOptions($options,$defaults);
 $sql = 'SELECT * FROM `'.$table.'` WHERE '.$options['where'].' ORDER BY '.$options['orderBy'].' LIMIT '.$options['limit'];
 return $this->sqlProcessMulti($sql,array('sortkey'=>$options['key'],'cache'=>$options['cache'],'expires'=>$options['expires'],'htmlParse'=>$options['htmlParse']));
}

public function getItem($table, $value, $options = array()) {
 $defaults = array(
  'field' => 'id',
  'cache' => TRUE,
  'expires' => DEFAULT_CACHE_EXPIRES
 );
 $options = $this->setOptions($options,$defaults);
 $sql = 'SELECT * FROM `'.$table.'` WHERE `'.$this->sqlClean($options['field']).'` = \''.$this->sqlClean($value).'\'';
 return $this->sqlProcess($sql,$options);
}

public function insertItem($table, $data, $options = array()) {
 $defaults = array();
 $options = $this->setOptions($options,$defaults);
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
 $sql = 'DELETE FROM `'.$table.'` WHERE `'.$this->sqlClean($options['field']).'` = \''.$this->sqlclean($value) . '\'';
 return $this->sqlQuery($sql); //investigate if more should be returned
}

private function sqlQuery($sql, $options = array()) {
 $this->queryCount++;
 $this->queryLog[] = $sql;
 $result = mysql_query($sql);
 if ($result) return $result;
 throw new Exception('MySQL Error occured: '. mysql_error().' Query: '.$sql);
}

/**********************************
   Helper Functions
**********************************/

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
  'expires' => DEFAULT_CACHE_EXPIRES,
  'htmlParse' => TRUE
 );
 $options = $this->setOptions($options, $defaults);
 if($this->cache->exists($sql,$options['expires']) && $options['cache']) {
  $result = $this->cache->read($sql);
 }
 else {
  $sqlReturn = $this->sqlQuery($sql);
  if (!$sqlReturn) return false;
  $row = mysql_fetch_array($sqlReturn,MYSQL_ASSOC);
  if ($row) {
   if ($options['return'] == 'all') {
    foreach($row as $key => $value) {
     if ($options['htmlParse']) {
      $result[$key] = $this->htmlParse(stripslashes($value));
     }
     else {
      $result[$key] = stripslashes($value);
     }
    }
   }
   else {
    if ($options['htmlParse']) {
     $result = $this->htmlParse(stripslashes(reset($row)));
    }
    else {
     $result = stripslashes(reset($row));
    }
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
  'expires' => DEFAULT_CACHE_EXPIRES,
  'htmlParse' => TRUE
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
    if ($options['htmlParse']) {
     $result[$id][$key] = $this->htmlParse(stripslashes($value));
    }
    else {
     $result[$id][$key] = $value;
    }
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

public function __construct($host,$user,$pass,$name,$options = array()) {
 $defaults = array(
  'cache' => NULL
 );
 $options = $this->setOptions($options, $defaults);
 if ($options['cache']) $this->cache = $options['cache'];
 $this->link = mysql_connect($host,$user,$pass);
 mysql_select_db($name,$this->link);
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

public function directQuery($sql) { //this should rarely be used
 $this->directQueryCount++; 
 return $this->sqlQuery($sql);
}

public function directProcessQuery($sql,$options = array()) {
 $this->directQueryCount++;
 return $this->sqlProcess($sql,$options);
}

public function directProcessMultiQuery($sql,$options = array()) {
 $this->directQueryCount++;
 return $this->sqlProcessMulti($sql,$options);
}


//End DB
}
