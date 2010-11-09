<?php


class BaseAPI {

protected $db;		//database connection
protected $guid;	//user token
protected $token;	//internal token
protected $log;		//log file
protected $user;	//authenticated user
protected $status;      //external status object
protected $cache;	//cache

/*API Method Template
public function nameName($options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'setting' => 'value',
 );
 extract($setup_result = $this->api_call_setup($setup));
 <code goes here>
 return $this->api_call_finish($result);
}
*/

/**********************************
   Entry Methods
**********************************/

public function postEntry($options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'convert_breaks' => '__default__',
  'excerpt' => '',
  'keywords' => ''
 );
 extract($setup_result = $this->api_call_setup($setup));
 $user = $this->getAuthUser();
 if ($user['type'] != 'admin') throw new Exception('Must be Admin to do this',403);
 if (!$options['title']) throw new Exception('Missing Title');
 if (!$options['text']) throw new Exception('Missing Text');
 $basename = strtolower(trim($options['title']));
 $basename = ereg_replace("[^ A-Za-z0-9_]", "", $basename);
 $basename = str_replace(" ", "_", $basename);
 if ($this->getEntry($basename,array('blogid'=>$options['blogid'],'cache'=>FALSE))) throw new Exception('Basename conflict'); //need to figure out how to better handle basename conflicts
 
 $thisentry = $this->db->insertItem('mt_entry',array());
 if (!$thisentry) throw new Exception('Entry failed to save');
 
 $atomid = 'tag:www.cbulock.com,'.date('Y').'://'.$options['blogid'].'.'.$thisentry;
 if ($options['category']) {//I think this still posts when not existing
  $catoptions = array(
   'placement_entry_id' => $thisentry,
   'placement_blog_id' => $options['blogid'],
   'placement_category_id' => $options['category'],
   'placement_is_primary' => '1' 
  );
  $this->db->insertItem('mt_placement',$catoptions);
 };

 $entrydata = array(
  'entry_blog_id' => $options['blogid'],
  'entry_status' => '2',
  'entry_author_id' => '2',
  'entry_allow_comments' => '1',
  'entry_allow_pings' => '0',
  'entry_convert_breaks' => $options['convert_breaks'],
  'entry_title' => $options['title'],
  'entry_excerpt' => $options['excerpt'],
  'entry_text' => $options['text'],
  'entry_keywords' => $options['keywords'],
  'entry_created_on' => date('Y-m-d H:i:s'),
  'entry_basename' => $basename,
  'entry_atom_id' => $atomid,
  'entry_week_number' => date('YW'), 
 );
 if (!$this->db->updateItem('mt_entry',$thisentry,$entrydata,array('field'=>'entry_id'))) throw new Exception('Entry save did not complete, in bad state');
 
 //Create Status Posting
 $url = 'http://ct3.cbulock.com/'.date('Y').'/'.date('m').'/'.$basename.'.html';//the url needs to be dynamic
 $shorturl = $this->getShortURL($url);
 if (strlen($options['title']) > 100) {
  $statustitle = substr($options['title'],0,100).'…';
 }
 else {
  $statustitle = $options['title'];
 }
 $this->postStatus('New Blog Post: '.$statustitle.' '.$shorturl);
 
 return $this->api_call_finish(TRUE); //i'd like to return an array of useful data, like entryid for instance
}

public function getEntry($value, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'callby' => 'basename',
  'cache' => TRUE 
 );
 extract($setup_result = $this->api_call_setup($setup));
 switch($options['callby']) {//seems like these sql calls could use getTable for the time being
  case 'basename':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".$this->db->sqlClean($options['blogid'])."' AND entry_basename='".$this->db->sqlClean($value)."'";
  break;
  case 'id':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".$this->db->sqlClean($options['blogid'])."' AND entry_id='".$this->db->sqlClean($value)."'";
  break;
 }
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2',array('cache'=>$options['cache']));
 if ($result) {
  $result['entry_raw'] = $result['entry_text'];
  $result['entry_text'] = html_entity_decode($result['entry_text']);
  //this restores my <code> tags to a working state, but is really ugly
  $result['entry_text'] = preg_replace('/<code>/','<code><xmp>',$result['entry_text']);
  $result['entry_text'] = preg_replace('/<\/code>/','</xmp></code>',$result['entry_text']);
  if ($result['entry_convert_breaks']) {
   $result['entry_text'] = nl2br($result['entry_text']);
  }
  if ($options['blogid'] == '2') {
   $year = date('Y',strtotime($result['entry_created_on']));
   $month = date('m',strtotime($result['entry_created_on']));
   $result['entry_link'] = "/".$year."/".$month."/".$result['entry_basename'].".html";
  }
  $result['comment_count'] = $this->commentCount($result['entry_id'],array('blogid'=>$options['blogid']));
  return $this->api_call_finish($result);
 }
 return $this->api_call_finish(FALSE);
}

public function prevEntry($id, $options = array()) {//where is very open
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'where' => '1'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_id < ".$this->db->sqlClean($id)." AND entry_blog_id =".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2',array('return'=>'single','cache'=>FALSE));//going to disable caching as I believe this can be off when new entries are created
 return $this->api_call_finish($result);
}

public function nextEntry($id, $options = array()) {//where is very open
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'where' => '1'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = "select min(entry_id) FROM `mt_entry` WHERE (entry_id > ".$this->db->sqlClean($id)." AND entry_blog_id = ".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2',array('return'=>'single','cache'=>FALSE));//going to disable caching as I believe this can be off when new entries are created
 return $this->api_call_finish($result);
}

public function lastEntry($options = array()) {//where is very open
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'where' => '1'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_blog_id = ".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'cbulock_mt2',array('return'=>'single','cache'=>FALSE));//going to disable caching as I believe this can be off when new entries are created
 return $this->api_call_finish($result);
}

/**********************************
   Comment Methods
**********************************/

function commentCount($postid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
 'blogid' => '2',
 'cache' => TRUE,
 'expires' => '0.2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = "SELECT COUNT(*) FROM `comments` WHERE blogid = '".$this->db->sqlClean($options['blogid'])."' AND postid = '".$this->db->sqlClean($postid)."'";
 $result = $this->db->directProcessQuery($sql,'cbulock_cbulock',array('return'=>'single','cache'=>$options['cache'],'expires'=>$options['expires']));
 return $this->api_call_finish($result);
}

public function getComments($postid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'expires' => '0.2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $tableoptions = array(
  'where' => 'postid = "'.$this->db->sqlClean($postid).'" AND blogid= "'.$this->db->sqlClean($options['blogid']).'"',
  'orderBy' => '`created`',
  'expires' => $options['expires']
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
 return $this->api_call_finish($results);
}

public function postComment($postid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $user = $this->getAuthUser();
 if (!$user) throw new Exception('Must be logged in to post comment',401);
 if (!$options['text']) throw new Exception('Must enter text into comment box',1001);
 $data = array(
  'blogid' => $options['blogid'],
  'postid' => $postid,//this needs to be sanitized better
  'user' => $user['id'],
  'ip' => $remote_ip,
  'text' => $options['text']
 );
 $comment = $this->db->insertItem('comments',$data);
 if (!$comment) throw new Exception('Error saving comment');
 
 //Admin Email
 $mail = $this->useMail();
 $mail->AddAddress(ADMIN_EMAIL,'Cameron');
 $mail->Subject = "New Comment Posted on Cameron's Thoughts";
 $data['username'] = $user['login'];
 $data['fullname'] = $user['name'];
 $mail->Body = $this->getMailTemplate('post_comment',$data);
 $mail->Send();

 //User Emails
 //Somehow email users of all previous comments here
 
 $result = array(
  'id' => $comment,
  'count' => $this->commentCount($postid,array('cache'=>FALSE))
 ); 
 return $this->api_call_finish($result);
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
 return $this->api_call_finish($item['placement_category_id']);
}

public function getCat($catid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'field' => 'category_id'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $dboptions = array(
  'field' => $options['field']
 );
 return $this->api_call_finish($this->db->getItem('mt_category',$catid,$dboptions));
}

public function getCatList($options = array()) {
 $setup['options'] = $options;
 extract($setup_result = $this->api_call_setup($setup));
 $dboptions = array(
  'key' => 'category_id',
  'orderBy' => '`category_id`'
 );
 return $this->api_call_finish($this->db->getTable('mt_category',$dboptions));
}

/**********************************
   Authentication Methods
**********************************/

public function login($user, $options = array()) {
 $setup['options'] = $options;
 extract($setup_result = $this->api_call_setup($setup));

 $id = $this->checkPass($user,$options['pass']);
 if (!$id) throw new Exception('Authentication Failure',401);
 $data = array(
  'user'=>$id,
  'guid'=>$this->getUserToken()
 );
 $this->db->insertItem('sessions',$data);
 $this->user = $this->getUser($user,array('token'=>$this->getAPIToken()));
 return $this->api_call_finish($this->getUserToken());
}

protected function tokenLogin($token) {
 $this->setUserToken($token);
 $session = $this->db->getItem('sessions',$token,array('field'=>'guid','cache'=>FALSE));
 if ($session) {
  $this->user = $this->getUser($session['user'],array('token'=>$this->getAPIToken(),'callby'=>'id','cache'=>FALSE));
  return $this->api_call_finish($token);
 }
 return FALSE;
}

protected function checkPass($user,$pass) {
 $acct = $this->getUser($user,array('token'=>$this->getAPIToken()));
 if ($acct['pass'] == md5($pass)) return $this->api_call_finish($acct['id']);
 return FALSE;
}

protected function methodAuth($token=NULL) {
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
 return $this->api_call_finish(FALSE);
}

public function logout() {
 return $this->api_call_finish($this->db->deleteItem('sessions',$this->getUserToken(),array('field'=>'guid')));
}

/**********************************
   Status Methods
**********************************/

protected function useStatus() {
 if (!isset($this->status)) {
  require_once('status.php');
  $this->status = new Status;
 }
}

public function getLatestStatus() {
 $this->useStatus();
 return $this->api_call_finish($this->status->getStatus(array('count'=>'1')));
}

protected function postStatus($message) {
 $this->useStatus();
 return $this->status->postStatus($message);
}

protected function getShortURL($url) {
 $apiurl = 'http://api.bit.ly/v3/shorten?login=cbulock&apiKey='.BITLY_API_KEY.'&longUrl='.urlencode($url);
 $result = json_decode($this->call($apiurl));
 return $result->data->url;
}

/**********************************
   Mail Methods
**********************************/

protected function useMail() {
 require_once('email.php');
 $mail = new PHPMailer;
 $mail->SetFrom(SITE_EMAIL,"Cameron's Thoughts");
 return $mail;
}

protected function getMailTemplate($name,$data = array()) {
 $filename = TPL_DIR.TYPE.'/email/'.$name.'.tpl';
 $file = fopen($filename,'r'); 
 $template = fread($file,filesize($filename));
 foreach ($data as $v => $i) {
  $template = preg_replace('/\$'.$v.'\$/',$i,$template);
 }
 return $template;
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
 return $this->api_call_finish($user);
}

protected function getAvatarPath($hash, $service) {
 if ($service == '0' || $service == '1') {
  return 'http://www.gravatar.com/avatar.php?gravatar_id='.$hash.'&r=r';
 }
}

protected function setCookie($name, $value, $expire=1893456000) {
 return setcookie($name, $value, $expire, "/");
}

protected function writeLog($text) {
 if (!isset($this->log)) {
  $logfile = LOG_DIR.'api.log';
  $this->log = fopen($logfile,'a');
 }
 $timestamp = date('c');
 $log = $timestamp.' '.$_SERVER['REMOTE_ADDR'].' '.$text."\n";
 return fwrite($this->log,$log);
}

protected function call($url,$post=NULL) {
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Cameron\'s Thoughts API Caller)');
 curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
 curl_setopt($ch, CURLOPT_TIMEOUT, 20);
 $response = curl_exec($ch);
 curl_close($ch);
 return $response;
}

public function addStat($options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'type' => 'page'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $data = array(
  'ip' => $options['ip'],
  'host' => $options['host'],
  'page' => $options['page'],
  'file' => $options['file'],
  'referer' => $options['referer'],
  'agent' => $options['agent'],
  'guid' => $this->getUserToken(),
  'response' => $options['response'],
  'lang' => $options['lang'],
  'request' => $options['request'],
  'type' => $options['type'],
  'method' => $options['method']
 );
 $user = $this->getAuthUser();
 if ($user) $data['user_id'] = $user['id'];
 $result = $this->db->insertItem('referers',$data);
 return $this->api_call_finish($result);
}

/**********************************
   Token Methods
**********************************/

protected function createGUID() {
 return md5(uniqid(rand(), true));
}

protected function getUserToken() {
 return $this->guid;
}

protected function setUserToken($guid) {
 $this->guid = $guid;
}

protected function getAPIToken() {
 return $this->token;
}

protected function setAPIToken($token) {
 $this->token = $token;
}

/**********************************
   Debugging
**********************************/

public function getCacheCount() {
 return $this->api_call_finish($this->cache->count());
}

public function getLastQuery($options = array()) {
 return $this->api_call_finish($this->db->getLastQuery());
}

public function getQueryLog($options = array()) {
 return $this->api_call_finish($this->db->getQueryLog());
}

public function getAPIMethods($options = array()) {
 return $this->api_call_finish($this->db->getTable('api_methods'));
}

public function getMethodParameters($methodid, $options = array()) {
 $options = array(
  'where' => 'method = '.$this->db->sqlClean($methodid)
 );
 return $this->api_call_finish($this->db->getTable('api_parameters',$options));
}

public function getQueryCount() {
 return $this->api_call_finish($this->db->getQueryCount());
}

public function getDirectQueryCount() {
 return $this->api_call_finish($this->db->getDirectQueryCount);
}


/**********************************
   Helper Methods
**********************************/

protected function api_call_setup($setup) {
 if ($setup['defaults']) $result['options'] = $this->setOptions($setup['options'],$setup['defaults']);
 $result['auth'] = $this->methodAuth($setup['options']['token']);
 $result['remote_ip'] = $_SERVER['REMOTE_ADDR'];
 return $result;
}

protected function api_call_finish($data) {
 if (!is_array($data)) return $data;
 return $data;
}

protected function setOptions($options, $defaults) { 
 foreach($defaults as $option => $value)
 {
  if (!isset($options[$option])) $options[$option] = $value;
 }
 return $options;
}

/**********************************
   Core Methods
**********************************/

public function __construct($settings) {
 //create internal token
 $this->setAPIToken($this->createGUID());
 //setup caching
 require_once('cache.php');
 $this->cache = new Cache;
 //connect to database
 require_once('db.php');
 $this->db = new DB($settings['db']['host'],$settings['db']['user'],$settings['db']['pass'],array('prefix'=>DB_PREFIX,'cache'=>$this->cache));
 //setup user token/login
 if ($_COOKIE['guid']) {
  $this->tokenLogin($_COOKIE['guid']);
 }
 else {
  $this->setUserToken($this->createGUID());
  $this->setCookie('guid',$this->getUserToken());
 }
}

public function __destruct() {
 //for debugging
 $this->writeLog(print_r($this->getQueryLog(),1));
 //close database
 if (isset($this->db)) {
  unset($this->db);
 }
 //close logfile
 if (isset($this->log)) {
  fclose($this->log);
 }
}

// End BaseAPI
}
?>
