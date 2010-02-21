<?php

class API {

private $db;

/**********************************
   Entry Methods
**********************************/

public function getEntry($value, $options = array()) {
 $defaults = array(
  'blogid' => '2',
  'callby' => 'basename'
 );
 $options = $this->setOptions($options,$defaults);
 switch($options['callby']) {
  case 'basename':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".$this->db->sqlClean($options['blogid'])."' AND entry_basename='".$this->db->sqlClean($value)."'";
  break;
  case 'id':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".$this->db->sqlClean($options['blogid'])."' AND entry_id='".$this->db->sqlClean($value)."'";
  break;
 }
 return $this->db->directProcessQuery($sql,'cbulock_mt2');
}

public function prevEntry($id, $options = array()) {//where is very open
 $defaults = array(
  'blogid' => '2',
  'where' => '1'
 );
 $options = $this->setOptions($options,$defaults);
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_id < ".$this->db->sqlClean($id)." AND entry_blog_id =".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2');
 return $result[0];
}

public function nextEntry($id, $options = array()) {//where is very open
 $defaults = array(
  'blogid' => '2',
  'where' => '1'
 );
 $options = $this->setOptions($options,$defaults);
 $sql = "select min(entry_id) FROM `mt_entry` WHERE (entry_id > ".$this->db->sqlClean($id)." AND entry_blog_id = ".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2');
 return $result[0];
}

public function lastEntry($options = array()) {//where is very open
 $defaults = array(
  'blogid' => '2',
  'where' => '1'
 );
 $options = $this->setOptions($options,$defaults);
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_blog_id = ".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2');
 return $result[0];
}

function commentCount($postid, $options = array()) {
 $defaults = array(
 'blogid' => '2'
 );
 $options = $this->setOptions($options,$defaults);
 $sql = "SELECT COUNT(*) FROM `comments` WHERE blogid = '".$this->db->sqlClean($options['blogid'])."' AND postid = '".$this->db->sqlClean($postid)."'";
 $result = $this->db->directProcessQuery($sql,'cbulock_cbulock');
 return $result[0];
}

public function getComments($postid, $options = array()) {
 $defaults = array(
  'blogid' => '2'
 );
 $options = $this->setOptions($options,$defaults);
 $tableoptions = array(
  'where' => 'postid = "'.$this->db->sqlClean($postid).'" AND blogid= "'.$this->db->sqlClean($options['blogid']).'"',
  'orderBy' => '`created`'
 );
 return $this->db->getTable('comments', $tableoptions);
}

/**********************************
   Category Methods
**********************************/

public function getCatID($entryid, $options = array()) {
 $options = array(
  'field' => 'placement_entry_id'
 ); 
 $item = $this->db->getItem('mt_placement',$entryid);
 return $item['placement_category_id'];
}



/**********************************
   Debugging
**********************************/

public function getLastQuery($options = array()) {
 return $this->db->getLastQuery();
}

public function getAPIMethods($options = array()) {
 return $this->db->getTable('api_methods');
}

public function getMethodParameters($methodid, $options = array()) {
 $options = array(
  'where' => 'method = '.$this->db->sqlClean($methodid)
 );
 return $this->db->getTable('api_parameters',$options);
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
   Helper Methods
**********************************/

private function setOptions($options, $defaults) {
 foreach($defaults as $option => $value)
 {
  if (!$options[$option]) $options[$option] = $value;
 }
 return $options;
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
