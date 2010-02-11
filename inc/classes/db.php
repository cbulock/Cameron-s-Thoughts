<?php

class DB {

private $queryCount;
private $lastQuery;
private $link;

/**********************************
   Database Access
**********************************/

public function getTable($table, $options = array()) {
 $defaults = array(
  'where' => '1',
  'orderBy' => '`id` DESC',
  'limit' => '5000',
  'key' => 'id'
 );
 $options = $this->setOptions($options,$defaults);
 $sql = 'SELECT * FROM `'.$table.'` WHERE '.$options['where'].' ORDER BY '.$options['orderBy'].' LIMIT '.$options['limit'];
 return $this->sqlProcessMulti($sql,$options['key']);
}

public function getItem($table, $value, $options = array()) {
 $defaults = array(
  'field' => 'id'
 );
 $options = $this->setOptions($options,$defaults);
 $sql = 'SELECT * FROM `'.$table.'` WHERE `'.$this->sqlClean($options['field']).'` = \''.$this->sqlClean($value).'\'';
 return $this->sqlProcess($sql);
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

public function deleteItem($table, $value,  $options = array()) { 
 $defaults = array(
  'field' => 'id'
 );
 $options = setOptions($options,$defaults);
 $sql = 'DELETE FROM `'.$table.'` WHERE `'.sqlClean($options['field']).'` = \''.sqlclean($value) . '\'';
 return sqlQuery($sql); //investigate if more should be returned
}

private function sqlQuery($sql) {
 $this->queryCount++;
 $this->lastQuery = $sql;
 return mysql_query($sql);
}

/**********************************
   Helper Functions
**********************************/

private function setOptions($options, $defaults) {
 foreach($defaults as $option => $value)
 {
  if (!$options[$option]) $options[$option] = $value;
 }
 return $options;
}

private function sqlProcess($sql) {//It may be more consistant to just use sqlProcessMulti for everything
 $sqlReturn = $this->sqlQuery($sql);
 if (!$sqlReturn) return false;
 $row = mysql_fetch_array($sqlReturn);
 foreach($row as $key => $value) {
  $result[$key] = $this->htmlParse(stripslashes($value));
 }
 return $result;
}

private function sqlProcessMulti($sql,$sortkey = 'id') {
 $sqlReturn = $this->sqlQuery($sql);
 if (!$sqlReturn) return false;
 while ($row = mysql_fetch_array($sqlReturn)) {
  $id = $row[$sortkey];
  foreach($row as $key => $value) {
   $result[$id][$key] = $this->htmlParse(stripslashes($value));
  }
 }
 return $result;
}

private function htmlParse($html) {
 return preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($html));
}

private function sqlClean($str) {
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

public function __construct($host,$user,$pass,$db) {
 $this->link = mysql_connect($host,$user,$pass);
 mysql_select_db($db,$this->link);
}


/**********************************
   Class Management
**********************************/
public function getQueryCount() {
 return $this->queryCount;
}

public function getLastQuery() {
 return $this->lastQuery;
}

//End DB
}
