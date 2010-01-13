<?php

class DB {

private $queryCount;
private $link;

/**********************************
   Database Access
**********************************/

public function getTable($table, $options = array()) {
 $defaults = array(
  'where' => '1',
  'orderBy' => '`id` DESC'
 );
 $options = setOptions($options,$defaults);
 selectDatabase($table, $options['database']);
 $sql = 'SELECT * FROM `'.$table.'` WHERE '.sqlClean($options['where']).' ORDER BY '.$options['orderBy'];
 return sqlProcessMulti($sql);
}

public function getItem($table, $value, $options = array()) {
 $defaults = array(
  $field => 'id'
 );
 $options = setOptions($options,$defaults);
 selectDatabase($table, $options['database']);
 $sql = 'SELECT * FROM `'.$table.'` WHERE `'.sqlClean($options['field']).'` = \''.sqlClean($value).'\'';
 return sqlProcess($sql);
}

public function insertItem($table, $data, $options = array()) {
 $defaults = array();
 $options = setOptions($options,$defaults);
 selectDatabase($table, $options['database']);
 foreach($data as $key => $val)
 {
  $keys .= '`'.$key.'`,';
  $vals .= '\''.sqlclean($val).'\', ';
 }
 $keys = rtrim($keys, ', ');
 $vals = rtrim($vals, ', ');
 $sql = 'INSERT INTO `'.$table.'` (';
 $sql .= $keys.') VALUES ('.$vals.')';
 if (sqlQuery($sql)) {
  return mysql_insert_id();
 } 
 else {
  return FALSE;
 }
}

public function updateItem($table, $value, $data, $options = array()) {
 $defaults = array(
  $field => 'id'
 );
 $options = setOptions($options,$defaults);
 selectDatabase($table, $options['database']);
 foreach($data as $key => $val) {
  $sql .= '`'.$key.'`=\''.sqlclean($val).'\', ';
 }
 $sql = rtrim($sql, ', ');
 $sql .= ' WHERE `'.$field.'`=\''.$value.'\'';
 return sqlQuery($sql); //investigate if more should be returned
}

public function deleteItem($table, $value,  $options = array()) { 
 $defaults = array(
  $field => 'id'
 );
 $options = setOptions($options,$defaults);
 selectDatabase($table, $options['database']);
 $sql = 'DELETE FROM `'.$table.'` WHERE `'.sqlClean($options['field']).'` = \''.sqlclean($value) . '\'';
 return sqlQuery($sql); //investigate if more should be returned
}

private function sqlQuery($sql) {
 $queryCount++;
 return mysql_query($sql);
}

/**********************************
   Helper Functions
**********************************/

private function selectDatabase($table, $db=NULL) {
 mysql_select_db(determineDatabase($table, $db),$this->link);
}

private function determineDatabase($table, $db=NULL) { //$table can be overloaded with $db value
 if ($db) return $db;
 $databases['cbulock_accesslog'] = array('cbulock_accesslog','referers','sessions');
 $databases['cbulock_cbulock'] = array('cbulock_cbulock','ads','ads_cat','blockedips','guid_admins','images','quotes','styles','users','comments');
 $databases['cbulock_mt2'] = array('cbulock_mt2','mt_blog','mt_entry');
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
 $sqlReturn = sqlQuery($sql);
 if (!$sqlReturn) return false;
 $row = mysql_fetch_array($sqlReturn);
 foreach($row as $key => $value) {
  $result[$key] = htmlParse(stripslashes($value));
 }
 return $result;
}

private function sqlProcessMulti($sql) {
 $sqlReturn = sqlQuery($sql);
 if (!$sqlReturn) return false;
 while ($row = mysql_fetch_array($sqlReturn)) {
  $id = $row['id'];//I don't like this so much, depends on tables having an id column
  foreach($row as $key => $value) {
   $result[$id][$key] = htmlParse(stripslashes($value));
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

public function __construct($host,$user,$pass) {
 $this->link = mysql_connect($host,$user,$pass);
}


/**********************************
   Class Management
**********************************/
public function getQueryCount() {
 return $queryCount;
}














//End DB
}
