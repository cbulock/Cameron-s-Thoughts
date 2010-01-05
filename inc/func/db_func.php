<?php

function db_get_table($table, $where="1", $orderBy="`id` DESC", $database=NULL, $extraSql="")
{
 global $querycount; 
 if (!$database)
 {
  $database = which_db($table);
 }
 mysql_select_db("cbulock_".$database);
 $resultArray = array();
 $sql = "SELECT * FROM `" . $table . "` WHERE " . $where . " ORDER BY " . $orderBy . " " . $extraSql;
 $result = mysql_query($sql);$querycount++;
 if (!$result)
 {
  return false;
 }
 else
 {
  while ($row = mysql_fetch_array($result))
  {
   $index = $row['id'];
   foreach($row as $key => $val)
   {
    $resultArray[$index][$key] = html_parse(stripslashes($val));
   }
  }
 }
 return $resultArray;
}

function db_get_item($table, $value, $field = "id", $database=NULL)
{
 global $querycount;	
 if (!$database)
 {
   $database = which_db($table);
 }
 mysql_select_db("cbulock_".$database);
 $resultArray = array();
 $sql = "SELECT * FROM `" . $table . "` WHERE `" . $field . "` = '" . sqlclean($value) . "'";
 $result = mysql_query($sql);$querycount++;
  
 if (!$result) return false;
 $row = mysql_fetch_array($result);
 
 if (!$row) return false;
 foreach($row as $key => $val)
 {
  $resultArray[$key] = html_parse(stripslashes($val));
 }
 return $resultArray;	
}

function db_insert($table, $data, $database=NULL)
{
 global $querycount; 
 if (!$database)
 {
  $database = which_db($table);
 }
 mysql_select_db("cbulock_".$database);
 foreach($data as $key => $val)
 {
  $keys .= "`" . $key . "`, ";
  $vals .= "'" . sqlclean($val) . "', ";
 }
 $keys = rtrim($keys, ", ");
 $vals = rtrim($vals, ", ");
  
 $sql = "INSERT INTO `" . $table . "` (";
 $sql .= $keys . ") VALUES (" . $vals . ")";
 $querycount++;
 if (mysql_query($sql))
 {
  return mysql_insert_id();
 } 
 else
 {
  return FALSE;
 }
}

function db_update($table, $value, $data, $field='id', $database=NULL)
{
 global $querycount;
 if (!$database)
 {
  $database = which_db($table);
 }
 mysql_select_db("cbulock_".$database);
 $sql = "UPDATE `" . $table . "` SET ";
 foreach($data as $key => $val)
 {
  $sql .= "`" . $key . "`='" . sqlclean($val) . "', ";
 }
 $sql = rtrim($sql, ", ");
 $sql .= " WHERE `" . $field . "`='" . $value . "'";
 $querycount++;
 return mysql_query($sql);
}

function db_delete($table, $value, $field='id', $database=NULL)
{
 global $querycount; 
 if (!$database)
 {
  $database = which_db($table);
 }
 mysql_select_db("cbulock_".$database);
 $sql = "DELETE FROM `" . $table . "` WHERE `" . $field . "` = '" . sqlclean($value) . "'";
 $querycount++; 
 return mysql_query($sql);
}

function sqlclean($string)
{
 if(!get_magic_quotes_gpc())
 {
  $string = mysql_real_escape_string($string);
 }
 else
 {
  $string = addslashes($string);
 }
 return $string;
}

function which_db($table) //smart function to determine which database holds which table
{
 $cbulockdb = array("ads","ads_cat","blockedips","guid_admins","images","quotes","styles","users","comments");
 $accesslogdb = array("referers","sessions");
 $mtdb = array('mt_blog','mt_entry');
 $ct3db = array('settings');
 foreach ($accesslogdb as $i)
 {
  if ($i == $table) return "accesslog";
 }
 foreach ($cbulockdb as $i)
 {
  if ($i == $table) return "cbulock";
 }
 foreach ($mtdb as $i)
 {
  if ($i == $table) return "mt2";
 }
 foreach ($ct3db as $i)
 {
  if ($i == $table) return "ct3";
 }

 return NULL;
}

?>
