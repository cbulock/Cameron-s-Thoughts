<?php

class DB {

private $queryCount;
private $directQueryCount;
private $lastQuery;
private $link;

/**********************************
   Database Access
**********************************/

public function getTable($table, $options = array()) {
 $defaults = array(
  'where' => '1',
  'orderBy' => '`id` ASC',
  'limit' => '5000',
  'key' => 'id'
 );
 $options = $this->setOptions($options,$defaults);
 $this->selectDatabase($table, $options['database']);
 $sql = 'SELECT * FROM `'.$table.'` WHERE '.$options['where'].' ORDER BY '.$options['orderBy'].' LIMIT '.$options['limit'];
 return $this->sqlProcessMulti($sql,$options['key']);
}

public function getItem($table, $value, $options = array()) {
 $defaults = array(
  'field' => 'id'
 );
 $options = $this->setOptions($options,$defaults);
 $this->selectDatabase($table, $options['database']);
 $sql = 'SELECT * FROM `'.$table.'` WHERE `'.$this->sqlClean($options['field']).'` = \''.$this->sqlClean($value).'\'';
 return $this->sqlProcess($sql);
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

public function deleteItem($table, $value,  $options = array()) { 
 $defaults = array(
  'field' => 'id'
 );
 $options = setOptions($options,$defaults);
 $this->selectDatabase($table, $options['database']);
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

private function selectDatabase($table, $db=NULL) {
 mysql_select_db($this->determineDatabase($table, $db),$this->link);
}

private function determineDatabase($table, $db=NULL) { //$table can be overloaded with $db value
 if ($db) return $db;
 $databases['cbulock_accesslog'] = array('cbulock_accesslog','referers','sessions');
 $databases['cbulock_cbulock'] = array('cbulock_cbulock','ads','ads_cat','blockedips','guid_admins','images','quotes','styles','users','comments','api_methods','api_parameters');
 $databases['cbulock_mt2'] = array('cbulock_mt2','mt_blog','mt_entry','mt_category','mt_placement');
 $databases['cbulock_ct3'] = array('cbulock_ct3','settings');
 foreach ($databases as $dbname => $database) {
  foreach ($database as $tablename) {
   if ($tablename == $table) return $dbname;
  }
 }
}

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
 if ($row) {
  foreach($row as $key => $value) {
   $result[$key] = $this->htmlParse(stripslashes($value));
  }
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

public function __construct($host,$user,$pass) {
 $this->link = mysql_connect($host,$user,$pass);
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
 return $this->lastQuery;
}

public function directQuery($sql,$db) { //this should rarely be used
 $this->directQueryCount++; 
 $this->selectDatabase($db);
 return $this->sqlQuery($sql);
}

public function directProcessQuery($sql,$db) {
 $this->directQueryCount++;
 $this->selectDatabase($db);
 return $this->sqlProcess($sql);
}

public function directProcessMultiQuery($sql,$db,$sortkey='id') {
 $this->directQueryCount++;
 $this->selectDatabase($db);
 return $this->sqlProcessMulti($sql,$sortkey);
}


//End DB
}
