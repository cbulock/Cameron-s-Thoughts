<?php


class BaseAPI {

protected $db;		//database connection
protected $guid;	//user token
protected $token;	//internal token
protected $log;		//log file
protected $errorlog;	//error log file
protected $user;	//authenticated user
protected $status;      //external status object
protected $cache;	//cache

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
 $setup['perms'] = array(
  'admin'
 );
 extract($setup_result = $this->api_call_setup($setup));
 if (!$options['title']) {
  $this->writeLog('Missing title when posting entry','errorlog');
  throw new Exception('Missing Title');
 }
 if (!$options['text']) {
  $this->writeLog('Missing text when posting entry','errorlog');
  throw new Exception('Missing Text');
 }
 $basename = strtolower(trim($options['title']));
 $basename = ereg_replace("[^ A-Za-z0-9_]", "", $basename);
 $basename = str_replace(" ", "_", $basename);
 try {//need to verify basename doesn't already exist
  $this->getEntry($basename,array('blogid'=>$options['blogid'],'cache'=>FALSE));
 }
 catch (UnexpectedValueException $e) {
  switch ($e->getCode()) {
   case 1000:
    $thisentry = $this->db->insertItem('mt_entry',array());
    if (!$thisentry) {
     $this->writeLog('Entry failed to save: '.$options['title'],'errorlog');
     throw new Exception('Entry failed to save');
    }
    $atomid = 'tag:www.cbulock.com,'.date('Y').'://'.$options['blogid'].'.'.$thisentry;
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
     'entry_category_id' => $options['category'],
     'entry_created_on' => date('Y-m-d H:i:s'),
     'entry_basename' => $basename,
     'entry_atom_id' => $atomid,
     'entry_week_number' => date('YW'),
    );
    if (!$this->db->updateItem('mt_entry',$thisentry,$entrydata,array('field'=>'entry_id'))) {
     $this->writeLog('[WARNING] Entry save did not complete and was left in bad state ID:'.$thisentry,'errorlog');
     throw new Exception('Entry save did not complete, in bad state');
    }
    $this->clearCache(array('token'=>$this->getAPIToken()));//there are random issues if cache isn't cleared
    $this->newEntryStatus($thisentry);
    $this->writeLog('New entry posted. ID:'.$thisentry.' Title: '.$options['title']);
    return $this->api_call_finish(TRUE); //i'd like to return an array of useful data, like entryid for instance
   default:
    throw new Exception($e);
  }
 }
 $this->writeLog('Basename conflict: '.$basename,'errorlog');
 throw new Exception('Basename conflict');
}

public function editEntry($value, $options = array()) {
 $setup['options'] = $options;
 $setup['perms'] = array(
  'admin'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $allowedoptions = array(
  'entry_title',
  'entry_category_id',
  'entry_text',
  'entry_excerpt',
  'entry_keywords',
  'convert_breaks'
 );
 foreach($allowedoptions as $o) {
  if (isset($options[$o])) {
   $updatedata[$o] = $options[$o];
  }
 }
 if (!$this->db->updateItem('mt_entry',$value,$updatedata,array('field'=>'entry_id'))) {
  $this->writeLog('Edit Entry failed to update. ID:'.$value,'errorlog');
  throw new Exception('Entry edit failed');
 }
 $this->clearCache(array('token'=>$this->getAPIToken()));
 return $this->api_call_finish(TRUE);
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
 $result = $this->db->directProcessQuery($sql,'mt_entry',array('cache'=>$options['cache'],'htmlParse'=>FALSE));
 if ($result) {
  $result['entry_raw'] = $result['entry_text'];
  //hack to use current image tags to display images
   if (preg_match_all("/\<\?php echo image\(\"(.*?)\"\)\;\?\>/",$result['entry_text'],$images)) {
   foreach($images[1] as $i => $image) {
    $images[2][$i] = $this->getImageDetails($image);
   }
   foreach($images[0] as $i => $image) {
    $result['entry_text'] = preg_replace(
     '/'.preg_quote($image).'/',
     '<img src="http://www.cbulock.com/images/view/'.$images[2][$i]['filename'].'" width="'.$images[2][$i]['twidth'].'" height="'.$images[2][$i]['theight'].'" />',
     $result['entry_text']
    );
   }
  }
  if (preg_match_all("/\<\?php echo imagethumb\(\"(.*?)\"\)\;\?\>/",$result['entry_text'],$images)) {
   foreach($images[1] as $i => $image) {
    $images[2][$i] = $this->getImageDetails($image);
   }
   foreach($images[0] as $i => $image) {
    $result['entry_text'] = preg_replace(
     '/'.preg_quote($image).'/', 
     '<a href="http://www.cbulock.com/images/fit/'.$images[2][$i]['filename'].'"><img src="http://www.cbulock.com/images/thumb/'.$images[2][$i]['filename'].'" width="'.$images[2][$i]['twidth'].'" height="'.$images[2][$i]['theight'].'" /></a>',
     $result['entry_text']
    );
   }
  }
  if ($result['entry_convert_breaks']) {
   $result['entry_text'] = nl2br($result['entry_text']);
  } 
  if ($options['blogid'] == '2') {
   $year = date('Y',strtotime($result['entry_created_on']));
   $month = date('m',strtotime($result['entry_created_on']));
   $result['entry_link'] = "/".$year."/".$month."/".$result['entry_basename'].".html";
  }
  $result['comment_count'] = $this->commentCount($result['entry_id'],array('blogid'=>$options['blogid']));
  $result['prev_entry'] = $this->prevEntry($result['entry_id']);
  $result['next_entry'] = $this->nextEntry($result['entry_id']);
  return $this->api_call_finish($result);
 }
 $this->writeLog('Entry not found: '.$value,'errorlog');
 throw new UnexpectedValueException('Entry not found',1000);
}

public function prevEntry($id, $options = array()) {//where is very open
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2',
  'where' => '1'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_id < ".$this->db->sqlClean($id)." AND entry_blog_id =".$this->db->sqlClean($options['blogid'])." AND ".$options['where'].")";
 $result = $this->db->directProcessQuery($sql,'mt_entry',array('return'=>'single','cache'=>TRUE));
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
 $result = $this->db->directProcessQuery($sql,'mt_entry',array('return'=>'single','cache'=>TRUE));
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
 $result = $this->db->directProcessQuery($sql,'mt_entry',array('return'=>'single','cache'=>TRUE));
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
 $result = $this->db->directProcessQuery($sql,'comments',array('return'=>'single','cache'=>$options['cache'],'expires'=>$options['expires']));
 return $this->api_call_finish($result);
}

public function getComment($id, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'expires' => '0.2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $dboptions = array(
  'expires' => $options['expires']
 );
 $result = $this->db->getItem('comments', $id, $dboptions);
 if ($result) {
  $result['service'] = 0;
  if ($result['user']) {
   $user = $this->getUser($result['user'],array('callby'=>'id','token'=>$this->getAPIToken()));
   $result['author'] = $user['name'];
   $result['url'] = $user['url'];
   $result['email'] = $user['email'];
   $result['service'] = $user['service'];
   $result['email_hash'] = $user['email_hash'];
   $result['avatar'] = $user['avatar'];
  }
  if (!$result['email_hash']) $result['email_hash'] = md5($result['email']);
  if (!$result['avatar']) $result['avatar'] = $this->getAvatarPath(array('email_hash' => $result['email_hash'],'service' => $result['service']));
  if (!in_array('internal',$permassets)) {
   unset($result['email']);
  }
 return $this->api_call_finish($result);
 }
 $this->writeLog('Comment not found: '.$id,'errorlog');
 throw new UnexpectedValueException('Comment not found');
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
   $comments[$key] = $this->getComment($result['id']);
  }
 }
 return $this->api_call_finish($comments);
}

public function postComment($postid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2'
 );
 $setup['perms'] = array(
  'user'
 );
 extract($setup_result = $this->api_call_setup($setup));
 if (!$options['text']) {
  $this->writeLog('Text missing from comment','errorlog');
  throw new Exception('Must enter text into comment box',1001);
 }
 $user = $this->getAuthUser();
 $data = array(
  'blogid' => $options['blogid'],
  'postid' => $postid,//this needs to be sanitized better
  'user' => $user['id'],
  'ip' => $remote_ip,
  'text' => $options['text']
 );
 $comment = $this->db->insertItem('comments',$data);
 if (!$comment) {
  $this->writeLog('Error saving comment','errorlog');
  throw new Exception('Error saving comment');
 }

 $this->writeLog('New comment on entry '.$postid);
 
 //Admin Email
 $data['username'] = $user['login'];
 $data['fullname'] = $user['name'];
 $data['userid'] = $user['id'];
 $data['postid'] = $postid;
 $data['id'] = $comment;
 $data['location'] = 'http:'.LOCATION;
 $site_name = $this->getSetting('site_name');
 $mailoptions = array(
  'data' => $data,
  'subject' => "New Comment Posted on ".$site_name['value'],
  'template' => 'post_comment'
 );
 $this->sendMail($mailoptions);

 //User Emails
 //Somehow email users of all previous comments here
 
 $result = array(
  'id' => $comment,
  'count' => $this->commentCount($postid,array('cache'=>FALSE))
 ); 
 return $this->api_call_finish($result);
}

public function editComment($id, $options = array()) {
 $setup['options'] = $options;
 $setup['perms'] = array(
  'user'
 );
 extract($setup_result = $this->api_call_setup($setup));
 if (!$options['text']) {
  $this->writeLog('Text missing from comment','errorlog');
  throw new Exception('Must enter text into comment box',1001);
 }
 $comment = $this->getComment($id);
 $user = $this->getAuthUser();
 if (!in_array('admin',$permassets) && ($user['id'] != $comment['user'])) {
  $this->writeLog('Non permitted user attempting to edit comment','errorlog');
  throw new Exception('Insufficent permissions to edit comment',403);   
 }
 if ($this->db->updateItem('comments',$id,array('text'=>$options['text']))) {
  return $this->api_call_finish(TRUE);
 }
 $this->writeLog('Comment edit failed for comment '.$id,'errorlog');
 throw new Exception('There was an error saving the comment');
}

public function deleteComment($id, $options = array()) {
 $setup['options'] = $options;
 $setup['perms'] = array(
  'admin'
 );
 extract($setup_result = $this->api_call_setup($setup));
 return $this->api_call_finish($this->db->deleteItem('comments',$id));
}

/**********************************
   Category Methods
**********************************/

public function getCat($catid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'field' => 'category_id'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $dboptions = array(
  'field' => $options['field']
 );
 $cat = $this->db->getItem('mt_category',$catid,$dboptions);
 if ($cat) return $this->api_call_finish($cat);
 $this->writeLog('Category not found: '.$catid,'errorlog');
 throw new UnexpectedValueException('Category not found',1000);
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
 if (!$id) {
  $this->writeLog('[AUTH] Login failure: '.$user,'errorlog');
  throw new Exception('Authentication Failure',401);
 }
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

public function logout() {
 unset($this->user);
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

public function getLatestStatus($options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'cache' => TRUE,
  'expires' => 180
 );
 extract($setup_result = $this->api_call_setup($setup));
 if($this->cache->exists('latestStatus',$options['expires']) && $options['cache']) {
  $status = $this->cache->read('latestStatus');
 }
 else { 
  $this->useStatus();
  $status = $this->api_call_finish($this->status->getStatus(array('count'=>'1')));
  $this->cache->add('latestStatus',$status);
 }
 return $status;
}

protected function postStatus($message) {
 $this->useStatus();
 return $this->status->postStatus($message);
}

protected function newEntryStatus($id,$options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'message' => 'New Blog Post: ',
  'message_max_length' => 119 //bit.ly URL is 20chars plus there is a space, 140-21=119
 );
 extract($setup_result = $this->api_call_setup($setup));
 $entry = $this->getEntry($id,array('callby'=>'id'));
 $url = 'http:'.LOCATION.$entry['entry_link'];
 $shorturl = $this->getShortURL($url);
 $message = $options['message'].$entry['entry_title'];
 if ((strlen($message) > $options['message_max_length'])) {
  $message = substr($message,0,$options['message_max_length']-3).'...';
 }
 $message = $message.' '.$shorturl;
 return $this->postStatus($message);
}

protected function getShortURL($url) {
 $apiurl = 'http://api.bit.ly/v3/shorten?login=cbulock&apiKey='.BITLY_API_KEY.'&longUrl='.urlencode($url);
 $result = json_decode($this->callURL($apiurl));
 $this->writeLog('Bit.ly URL recieved. LongURL: '.$url.' ShortURL: '.$result->data->url);
 return $result->data->url;
}

/**********************************
   Mail Methods
**********************************/

protected function getMailTemplate($name,$data = array()) {
 $filename = TPL_DIR.TYPE.'/email/'.$name.'.tpl';
 $file = fopen($filename,'r'); 
 $template = fread($file,filesize($filename));
 foreach ($data as $v => $i) {
  $template = preg_replace('/\$'.$v.'\$/',$i,$template);
 }
 return $template;
}

protected function sendMail($options = array()) {
 $setup['options'] = $options;
 $site_email = $this->getSetting('site_email',array('token'=>$this->getAPIToken()));
 $admin_email = $this->getSetting('admin_email',array('token'=>$this->getAPIToken()));
 $site_name = $this->getSetting('site_name');
 $setup['defaults'] = array(
  'from_email' => $site_email['value'],
  'from_name' => $site_name['value'],
  'to_email' => $admin_email['value'],
  'to_name' => 'Cameron'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $options['data']['ip'] = $remote_ip;
 require_once('email.php');
 $mail = new PHPMailer(TRUE);
 $mail->SetFrom($options['from_email'],$options['from_name']);
 $mail->AddAddress($options['to_email'],$options['to_name']);
 if ($options['subject']) {
  $mail->Subject = $options['subject'];
 }
 $mail->Body = $this->getMailTemplate($options['template'],$options['data']);
 try {
  $result = $mail->Send();
 } 
 catch (phpmailerException $e) {
  $this->writeLog('PHPMailer encountered an error','errorlog');
  throw new Exception($e);
 }
 return $result;
}

/**********************************
   User Methods
**********************************/

public function createUser($login, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'name' => '',
  'url' => '',
  'type' => 'user',
  'service' => 1,
  'service_id' => NULL
 );
 extract($setup_result = $this->api_call_setup($setup));
 $this->checkRBL($remote_ip);
 if ($options['type']!='user' && !in_array('admin',$permassets)) {
  $this->writeLog('Must be admin to create admin users','errorlog');
  throw new Exception('Must be admin to setup non-standard users');
 }
 if (!$options['pass']) {
  $this->writeLog('Tried to create user without password','errorlog');
  throw new Exception('Password required');
 }
 if (!$options['email']) {
  $this->writeLog('Tried to create user without email address','errorlog');
  throw new Exception('Email required');
 }
 if (!$this->nameFree($login)) {
  $this->writeLog('Tried to create user, name was already taken. Name: '.$login,'errorlog');
  throw new Exception('Username already taken');
 }
 $this->clearCache(array('token'=>$this->getAPIToken()));
 $useroptions = array(
  'login' => $login,
  'pass' => md5($options['pass']),
  'type' => $options['type'],
  'name' => $options['name'],
  'email' => $options['email'],
  'url' => $options['url'],
  'service' => $options['service'],
  'service_id' => $options['service_id']
 );
 $userid = $this->db->insertItem('users',$useroptions);
 if ($userid) {
  $this->writeLog('Created new user, '.$login.' ID:'.$userid);
  return $userid;
 }
 else {
  $this->writeLog('Error creating user '.$login,'errorlog');
  throw new Exception('Error creating user '.$login);
 }
}

public function nameFree($login, $options = array()) {
 if ($this->getUser($login)) return $this->api_call_finish(FALSE);//exception?
 return $this->api_call_finish(TRUE);
}

public function getUser($value, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'callby' => 'login'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $user = $this->db->getItem('users',$value,array('field'=>$options['callby']));
 if (!$user) return FALSE;//throw an exception here?
 $user['email_hash'] = md5($user['email']);
 $authuser = $this->getAuthUser();
 if (!in_array('internal',$permassets) && !in_array('admin',$permassets) && ($authuser['id'] != $user['id'])) {
  unset($user['pass']);
  unset($user['email']);
 }
 $user['avatar'] = $this->getAvatarPath($user);
 return $this->api_call_finish($user);
}

public function getUserList($options = array()) {
 $setup['options'] = $options;
 $setup['perms'] = array(
  'admin'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $results = $this->db->getTable('users');
 if ($results) {
  foreach ($results as $key=>$result) {
   $users[$key] = $this->getUser($result['id'],array('callby'=>'id'));
  }
 }
 return $this->api_call_finish($users);
}

public function getAuthUser($options = array()) {
 $setup['options'] = $options;
 extract($setup_result = $this->api_call_setup($setup));
 $user = $this->user;
 if (!in_array('internal',$permassets)) {
  unset($user['pass']);
  unset($user['email']);
 }
 if ($user) return $this->api_call_finish($user);
 return $this->api_call_finish(FALSE);
}

/**********************************
   Misc Methods
**********************************/

protected function checkRBL($ip, $options = array()) {
 require_once('rbl.php');
 $rbl = new http_bl(HTTP_BL_KEY);
 $result = $rbl->query($ip);
 if ($result == 2) {
  $this->writeLog('RBL failure: '.$ip,'errorlog');
  throw new Exception('IP listed on RBL, spam account rejected',1003);
 }
 return FALSE;
}

public function sendMessage($options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'email' => 'no-email-given@example.com',
  'name' => 'Contact Form User'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $this->checkRBL($remote_ip);
 $data['message'] = $options['message'];
 $mailoptions = array(
  'data' => $data,
  'from_email' => $options['email'],
  'from_name' => $options['name'],
  'subject' => 'New Contact Form Message',
  'template' => 'contact_form'
 );
 if ($this->sendMail($mailoptions)) return $this->api_call_finish(TRUE);
 $this->writeLog('Message sending failure','errorlog');
 throw new Exception('Message failed to send',1002); 
}

protected function getAvatarPath($user) {
 if ($user['service'] == '0' || $user['service'] == '1') {
  return 'http://www.gravatar.com/avatar.php?gravatar_id='.$user['email_hash'].'&r=r';
 }
 if ($user['service'] == '2') {
  return 'http://graph.facebook.com/'.$user['service_id'].'/picture?type=normal';
 }
}

protected function setCookie($name, $value, $expire=1893456000) {
 if (!headers_sent()) {
  return setcookie($name, $value, $expire, "/");
 }
 return FALSE;
}

protected function writeLog($text, $type='log') {
 if (!isset($this->{$type})) {
  $logfile = LOG_DIR.$type.'.log';
  $this->{$type} = fopen($logfile,'a');
 }
 $timestamp = date('c');
 $log = $timestamp."\t".$_SERVER['REMOTE_ADDR']."\t".$text."\n";
 return fwrite($this->{$type},$log);
}

protected function callURL($url,$post=NULL) {
 $site_name = $this->getSetting('site_name');
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 ('.$site_name['value'].' API Caller)');
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

public function getSetting($setting, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'expires' => 360
 );
 extract($setup_result = $this->api_call_setup($setup));
 $dboptions = array(
  'field' => 'name',
  'expires' => $options['expires']
 );
 $setting = $this->db->getItem('settings',$setting, $dboptions);
 if (!$setting) {
  $this->writeLog('Setting not found: '.$setting,'errorlog');
  throw new Exception('Setting not found');
 }
 if ((!in_array('internal',$permassets) && !in_array('admin',$permassets)) && !$setting['public']) {
  $this->writeLog('Insufficent permissions for setting: '.$setting,'errorlog');
  throw new Exception('Unauthorized to access setting', 403);
 }
 return $this->api_call_finish($setting);
}

public function getSettingList($options = array()) {
 $setup['options'] = $options;
 $setup['perms'] = array(
  'admin'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $results = $this->db->getTable('settings');
 if ($results) {
  foreach ($results as $key=>$result) {
   $settings[$key] = $this->getSetting($result['name']);
  }
 }
 return $this->api_call_finish($settings);
}

public function editSetting($name, $options = array()) {
 $setup['options'] = $options;
 $setup['perms'] = array(
  'admin'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $setting = $this->getSetting($name);

 $settingdata = array();
 if (isset($options['value'])) $settingdata['value'] = $options['value'];
 if (isset($options['public'])) $settingdata['public'] = $options['public']; 
 
 if ($this->db->updateItem('settings',$setting[id],$settingdata)) {
  $this->clearCache(array('token'=>$this->getAPIToken()));
  return $this->api_call_finish(TRUE);
 }
 $this->writeLog('Setting edit failed for setting '.$name,'errorlog');
 throw new Exception('There was an error saving the setting');
}

public function getImageDetails($name, $options = array()) {
 $setup['options'] = $options;
 extract($setup_result = $this->api_call_setup($setup));
 $image = $this->db->getItem('images',$name,array('field'=>'name'));
 return $this->api_call_finish($image);
}

public function search($term, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'blogid' => '2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $sql = 'SELECT entry_id FROM `mt_entry` WHERE entry_blog_id = "'.$this->db->sqlClean($options['blogid']).'" AND MATCH (entry_keywords,entry_title,entry_excerpt) AGAINST ("'.$this->db->sqlClean($term).'");';
 $sqlresults = $this->db->directProcessMultiQuery($sql,'mt_entry',array('sortkey'=>'entry_id'));
 $count = 0;
 if ($sqlresults) {
  foreach($sqlresults as $sqlresult) {
   $count++;
   $results[$count] = $this->getEntry($sqlresult['entry_id'],array('callby'=>'id'));
  }
 }
 $output = array(
  'count'=>$count,
  'results'=>$results
 );
 return $this->api_call_finish($output);
}

public function clearCache($options = array()) {
 $setup['options'] = $options;
 $setup['perms'] = array(
  'admin',
  'internal'
 );
 extract($setup_result = $this->api_call_setup($setup));
 return $this->api_call_finish($this->cache->clear());
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
 return $this->api_call_finish($this->db->getTable('api_methods',array('orderBy'=>'value','key'=>'value')));
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
 $permassets = array();
 $user = $this->user;
 if ($setup['options']['token'] == $this->getAPIToken()) array_push($permassets,'internal');
 if ($user['id']) array_push($permassets,'user');
 if ($user['type'] == 'admin') array_push($permassets,'admin');
 if ($setup['perms']) {
  if (!array_intersect($permassets,$setup['perms'])) {
   $this->writeLog('Insufficient permissions for method','errorlog');
   throw new Exception('Insufficient permissions', 403);
  };
 }
 if ($setup['defaults']) $result['options'] = $this->setOptions($setup['options'],$setup['defaults']);
 $result['permassets'] = $permassets;
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

public function __construct() {
 //create internal token
 $this->setAPIToken($this->createGUID());
 //setup caching
 require_once('cache.php');
 $this->cache = new Cache;
 //connect to database
 require_once('db.php');
 $this->db = new DB(DB_HOST,DB_USER,DB_PASS,array('prefix'=>DB_PREFIX,'cache'=>$this->cache));
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
 //close database
 if (isset($this->db)) {
  unset($this->db);
 }
 //close logfiles
 if (isset($this->log)) {
  fclose($this->log);
 }
 if (isset($this->errorlog)) {
  fclose($this->errorlog);
 }
}

// End BaseAPI
}
?>
