<?php

class API {

private $db;	//database connection
private $guid;	//user token
private $token;	//internal token
private $log;	//log file
private $user;	//authenticated user

/*API Method Template
public function nameName($options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'setting' => 'value',
 );
 extract($setup_result = $this->api_call_setup($setup));
 <code goes here>
 return $result;
}
*/

/**********************************
   Entry Methods
**********************************/

public function getEntry($value, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'callby' => 'basename'
 );
 extract($setup_result = $this->api_call_setup($setup));
 switch($options['callby']) {
  case 'basename':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".$this->db->sqlClean($options['blogid'])."' AND entry_basename='".$this->db->sqlClean($value)."'";
  break;
  case 'id':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".$this->db->sqlClean($options['blogid'])."' AND entry_id='".$this->db->sqlClean($value)."'";
  break;
 }
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2');
 if ($result) {
  $year = date('Y',strtotime($result['entry_created_on']));
  $month = date('m',strtotime($result['entry_created_on']));
  $result['entry_link'] = "/".$year."/".$month."/".$result['entry_basename'].".html";
  return $result;
 }
 return FALSE;
}

public function prevEntry($id, $options = array()) {//where is very open
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'where' => '1'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_id < ".$this->db->sqlClean($id)." AND entry_blog_id =".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2',array('return'=>'single'));
 return $result;
}

public function nextEntry($id, $options = array()) {//where is very open
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'where' => '1'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = "select min(entry_id) FROM `mt_entry` WHERE (entry_id > ".$this->db->sqlClean($id)." AND entry_blog_id = ".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2',array('return'=>'single'));
 return $result;
}

public function lastEntry($options = array()) {//where is very open
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'where' => '1'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_blog_id = ".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2',array('return'=>'single'));
 return $result;
}

/**********************************
   Comment Methods
**********************************/

function commentCount($postid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
 'blogid' => '2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = "SELECT COUNT(*) FROM `comments` WHERE blogid = '".$this->db->sqlClean($options['blogid'])."' AND postid = '".$this->db->sqlClean($postid)."'";
 $result = $this->db->directProcessQuery($sql,'cbulock_cbulock',array('return'=>'single'));
 return $result;
}

public function getComments($postid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $tableoptions = array(
  'where' => 'postid = "'.$this->db->sqlClean($postid).'" AND blogid= "'.$this->db->sqlClean($options['blogid']).'"',
  'orderBy' => '`created`'
 );
 $results = $this->db->getTable('comments', $tableoptions);
 if ($results) {
  foreach ($results as $key=>$result) {
   $results[$key]['service'] = 0;
   if ($result['user']) {
    $user = $this->getUser($result['user'],array('callby'=>'id','token'=>$this->token));
    $results[$key]['author'] = $user['name'];
    $results[$key]['url'] = $user['url'];
    $results[$key]['email'] = $user['email'];
    $results[$key]['service'] = $user['service'];
   }
   $results[$key]['email_hash'] = md5($results[$key]['email']);
   if ($auth['class']!='internal') {
    unset($results[$key]['email']);
   }
   $results[$key]['avatar'] = $this->getAvatarPath($results[$key]['email_hash'],$results[$key]['service']);
  }
 }
 return $results;
}

public function postComment($postid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $user = $this->getAuthUser();
 if (!$user) return FALSE;
 if (!$options['text']) return FALSE;
 $data = array(
  'blogid' => $options['blogid'],
  'postid' => $postid,//this needs to be sanitized better
  'user' => $user['id'],
  'ip' => $remote_ip,
  'text' => $options['text']
 );
 return $this->db->insertItem('comments',$data);
}

/**********************************
   Category Methods
**********************************/

public function getCatID($entryid, $options = array()) {
 $setup['options'] = $options;
 $options = array(
  'field' => 'placement_entry_id'
 ); 
 $item = $this->db->getItem('mt_placement',$entryid,$options);
 return $item['placement_category_id'];
}

public function getCat($catid, $options = array()) {
 $setup['options'] = $options;
 $options = array(
  'field' => 'category_id'
 );
 return $this->db->getItem('mt_category',$catid,$options);
}

/**********************************
   Authentication Methods
**********************************/

public function login($user, $options = array()) {
 $setup['options'] = $options;
 extract($setup_result = $this->api_call_setup($setup));

 $id = $this->checkPass($user,$options['pass']);
 if (!$id) return FALSE;
 $data = array(
  'user'=>$id,
  'guid'=>$this->getUserToken()
 );
 $this->db->insertItem('sessions',$data);
 $this->user = $this->getUser($user,array('token'=>$this->getAPIToken()));
 return $this->getUserToken();
}

private function tokenLogin($token) {
 $this->setUserToken($token);
 $session = $this->db->getItem('sessions',$token,array('field'=>'guid'));
 if ($session) {
  $this->user = $this->getUser($session['user'],array('token'=>$this->getAPIToken(),'callby'=>'id'));
  return $token;
 }
 return FALSE;
}

private function checkPass($user,$pass) {
 $acct = $this->getUser($user,array('token'=>$this->getAPIToken()));
 if ($acct['pass'] == md5($pass)) return $acct['id'];
 return FALSE;
}

private function methodAuth($token=NULL) {
 if ($token) {
  switch($token) {
   case $this->token:
    return array('class'=>'internal');
   break;
   default:
    if (!$this->tokenLogin($token)) {
     return FALSE;
    } 
  }
 }
 if (!$this->user) {
  return FALSE;
 }
 return array('class'=>'user');
}

public function getAuthUser($options = array()) {
 $setup['options'] = $options;
 extract($setup_result = $this->api_call_setup($setup));
 $user = $this->user;
 if ($auth['class']!='internal') {
  unset($user['pass']);
  unset($user['email']);
 }
 if ($user) return $user;
 return FALSE;
}

public function logout() {
 return $this->db->deleteItem('sessions',$this->getUserToken(),array('field'=>'guid'));
}

/**********************************
   Misc Methods
**********************************/

public function getUser($value, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'callby' => 'login'
 );
 extract($setup_result = $this->api_call_setup($setup)); 
 $user = $this->db->getItem('users',$value,array('field'=>$options['callby']));
 if (!$user) return FALSE;
 $user['email_hash'] = md5($user['email']);
 $user['avatar'] = $this->getAvatarPath($user['email_hash'],$user['service']);
 if ($auth['class']!='internal') {
  unset($user['pass']);
  unset($user['email']);
 }
 return $user;
}

private function getAvatarPath($hash, $service) {
 if ($service == '0' || $service == '1') {
  return 'http://www.gravatar.com/avatar.php?gravatar_id='.$hash.'&r=r';
 }
}

private function setCookie($name, $value, $expire=1893456000) {
 return setcookie($name, $value, $expire, "/");
}

private function writeLog($text) {
 $timestamp = date('c');
 $log = $timestamp.' '.$_SERVER['REMOTE_ADDR'].' '.$text."\n";
 return fwrite($this->log,$log);
}

/**********************************
   Token Methods
**********************************/

private function createGUID() {
 return md5(uniqid(rand(), true));
}

private function getUserToken() {
 return $this->guid;
}

private function setUserToken($guid) {
 $this->guid = $guid;
}

private function getAPIToken() {
 return $this->token;
}

private function setAPIToken($token) {
 $this->token = $token;
}

/**********************************
   Debugging
**********************************/

public function getLastQuery($options = array()) {
 return $this->db->getLastQuery();
}

public function getQueryLog($options = array()) {
 return $this->db->getQueryLog();
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

public function getQueryCount() {
 return $this->db->getQueryCount();
}

public function getDirectQueryCount() {
 return $this->getDirectQueryCount;
}


/**********************************
   Helper Methods
**********************************/

private function api_call_setup($setup) {
 if ($setup['defaults']) $result['options'] = $this->setOptions($setup['options'],$setup['defaults']);
 $result['auth'] = $this->methodAuth($setup['options']['token']);
 $result['remote_ip'] = $_SERVER['REMOTE_ADDR'];
 return $result;
}

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
 //open logfile
 $logfile = LOG_DIR.'api.log';
 $this->log = fopen($logfile,'a');
 //create internal token
 $this->setAPIToken($this->createGUID());
 //connect to database
 $this->db = new DB($settings['db']['host'],$settings['db']['user'],$settings['db']['pass'],DB_PREFIX);
 //setup user token/login
 if ($_COOKIE['guid']) {
  $this->tokenLogin($_COOKIE['guid']);
 }
 else {
  $this->setUserToken($this->createGUID());
  $this->setCookie('guid',$guid);
 }
}

public function __destruct() {
 //close database
 unset($this->db);
 //close logfile
 fclose($this->log);
}

// End API
}
?>
