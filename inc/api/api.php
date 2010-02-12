<?php

class API {

private $db;

/**********************************
   Entry Methods
**********************************/

public function getEntry($value, $blogid='2', $callby='basename') {
 switch($callby) {
  case 'basename':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".$this->db->sqlClean($blogid)."' AND entry_basename='".$this->db->sqlClean($value)."'";
  break;
  case 'id':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".$this->db->sqlClean($blogid)."' AND entry_id='".$this->db->sqlClean($value)."'";
  break;
 }
 return $this->db->directProcessQuery($sql,'cbulock_mt2');
}

public function prevEntry($id, $blogid='2', $where='1') {
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_id < ".$this->db->sqlClean($id)." AND entry_blog_id =".$this->db->sqlClean($blogid)." AND ".$where.")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2');
 return $result[0];
}

public function nextEntry($id, $blogid='2', $where='1') {
 $sql = "select min(entry_id) FROM `mt_entry` WHERE (entry_id > ".$this->db->sqlClean($id)." AND entry_blog_id = ".$this->db->sqlClean($blogid)." AND ".$where.")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2');
 return $result[0];
}

public function lastEntry($blogid='2', $where='1') {
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_blog_id = ".$this->db->sqlClean($blogid)." AND ".$where.")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2');
 return $result[0];
}


/**********************************
   Debugging
**********************************/

public function getLastQuery() {
 return $this->db->getLastQuery();
}


/**********************************
   Information Methods
**********************************/

public function getQueryCount() {
 return $this->db->getQueryCount();
}

public function getDirectQueryCount() {
 return $this->getDirectQueryCount;
}

/**********************************
   Core Methods
**********************************/

public function __construct($settings) {
 $this->db = new DB($settings['db']['host'],$settings['db']['user'],$settings['db']['pass']);
}

public function __destruct() {
 unset($this->db); 
}

// End API
}
?>
