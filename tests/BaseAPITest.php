<?php
//Requires PHPUnit
require_once('../var.inc');
require_once(API_DIR.'base_api.php');
class BaseAPITest extends PHPUnit_Framework_TestCase {
 
 protected $api;
 protected function setUp(){
  $this->api = new BaseAPI();
 }
 protected function tearDown(){
  unset($this->api);
 }
 
 /**** postEntry ****/
 public function test_postEntry_success() {
  $admin = $this->api->login('testadmin',array('pass'=>'!test!'));
  $result = $this->api->postEntry(array(
   'title'	=>	'Test post: '.date('c'),
   'text'		=>	'This is a post from a unit test',
   'token'	=>	$admin
  ));
  $this->assertTrue($result);
 }
 public function test_postEntry_noadmin() {
  try {
   $result = $this->api->postEntry(array(
    'title'  =>  'Test post: '.date('c'),
    'text'   =>  'This is a post from a unit test'
   ));
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_postEntry_notext() {
  $admin = $this->api->login('testadmin',array('pass'=>'!test!'));
  try {
   $result = $this->api->postEntry(array(
    'title'	=>	'Test post: '.date('c'),
   'token'	=>	$admin
   ));
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 function test_postEntry_notitle() {
  $admin = $this->api->login('testadmin',array('pass'=>'!test!'));
  try {
   $result = $this->api->postEntry(array(
    'text'  =>  'This is a post from a unit test',
   'token'  =>  $admin
   ));
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /**** editEntry ****/

 /**** getEntry ****/
 public function test_getEntry_title() {
  $entry = $this->api->getEntry('1',array('callby'=>'id'));
  $this->assertArrayHasKey('entry_title',$entry);
 }
 public function test_getEntry_entrylink() {
  $entry = $this->api->getEntry('1',array('callby'=>'id'));
  $this->assertArrayHasKey('entry_link',$entry);
 }
 public function test_getEntry_badentry() {
  try {
   $this->api->getEntry('qqqWWW');//an entry that shouldn't exist
  }
  catch (UnexpectedValueException $e) {
   if ($e->getCode() != '1000') $this->fail('An exception was raised, but not the expected one. '.$e->getMessage());
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }

 /**** prevEntry ****/
 public function test_prevEntry() {
  $entry = $this->api->prevEntry('2');
  $this->assertEquals('1',$entry);
 }

 /**** nextEntry ****/
 public function test_nextEntry() {
  $entry = $this->api->nextEntry('1');
  $this->assertEquals('2',$entry);
 }

 /**** lastEntry ****/
 public function test_lastEntry() {
  $entry = (int)$this->api->lastEntry();
  if ($entry === 0) unset($entry);
  $this->assertInternalType('integer',$entry);
 }

 /**** commentCount ****/
 public function test_commentCount() {
  $count = (int)$this->api->commentCount('9');
  if ($count === 0) unset($count);
  $this->assertInternalType('integer',$count);
 }

 /**** getComment ****/
 public function test_getComment_text() {
  $comment = $this->api->getComment('1');
  $this->assertArrayHasKey('text',$comment);
 }
 public function test_getComment_email() {
  $comment = $this->api->getComment('1');
  $this->assertArrayNotHasKey('email',$comment);
 }
 public function test_getComment_badcomment() {
  try {
   $this->api->getComment('120');//this comment shouldn't exist
  }
  catch (UnexpectedValueException $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }

 /**** getComments ****/
 public function test_getComments_text() {
  $comments = $this->api->getComments('9');
  $this->assertArrayHasKey('text',$comments[1]);
 }
 public function test_getComments_email() {
  $comments = $this->api->getComments('9');
  $this->assertArrayNotHasKey('email',$comments[1]);
 }
 public function test_getComments_nocomments() {
  $comments = $this->api->getComments('1');//has no comments
  $this->assertNull($comments);
 }

 /**** postComment ****/
 public function test_postComment_success() {
  $token = $this->api->login('testuser',array('pass'=>'!test!'));
  $comment = $this->api->postComment('9',array('token'=>$token,'text'=>'Test comment: '.date('c')));
  $this->assertInternalType('integer',$comment['id']);
 }
 public function test_postComment_nouser() {
  try {
   $comment = $this->api->postComment('9',array('text'=>'Test comment: '.date('c')));
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_postComment_notext() {
  $token = $this->api->login('testuser',array('pass'=>'!test!'));
  try {
   $comment = $this->api->postComment('9',array('token'=>$token));
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }

 /**** editComment ****/
 public function test_editComment_admin() {
  $token = $this->api->login('testuser',array('pass'=>'!test!'));
  $comment = $this->api->postComment('9',array('token'=>$token,'text'=>'Test comment: '.date('c')));
  $admin = $this->api->login('testadmin',array('pass'=>'!test!'));
  $this->assertTrue($this->api->editComment($comment['id'],array('token'=>$admin,'text'=>'Edited comment')));
 }
 public function test_editComment_user() {
  $this->api->login('testuser',array('pass'=>'!test!'));
  $comment = $this->api->postComment('9',array('text'=>'Test comment: '.date('c')));
  $this->assertTrue($this->api->editComment($comment['id'],array('text'=>'Edited comment')));
 }
 public function test_editComment_nologin() {
  try {
   $this->api->login('testuser',array('pass'=>'!test!'));
   $comment = $this->api->postComment('9',array('text'=>'Test comment: '.date('c')));
   $this->api->logout();
   print_r($this->api->getAuthUser());
   $this->api->editComment($comment['id'],array('text'=>'Edited comment'));
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_editComment_wronguser() {
  try {
   $token = $this->api->login('testuser',array('pass'=>'!test!'));
   $comment = $this->api->postComment('9',array('token'=>$token,'text'=>'Test comment: '.date('c')));
   $token2 = $this->api->login('testuser2',array('pass'=>'!test!'));
   $this->api->editComment($comment['id'],array('token'=>$token2,'text'=>'Edited comment'));
  }
  catch (Exception $e) {
   return; 
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_editComment_notext() {
  try {
   $this->api->login('testuser',array('pass'=>'!test!'));
   $comment = $this->api->postComment('9',array('text'=>'Test comment: '.date('c')));
   $this->api->editComment($comment['id']);
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }

 /**** deleteComment ****/
 public function test_deleteComment_success() {
  $token = $this->api->login('testuser',array('pass'=>'!test!'));
  $comment = $this->api->postComment('9',array('token'=>$token,'text'=>'Test comment: '.date('c')));
  $admin = $this->api->login('testadmin',array('pass'=>'!test!'));
  $this->assertTrue($this->api->deleteComment($comment['id'],array('token'=>$admin)));
 }
 public function test_deleteComment_nonadmin() {
  try {
   $this->api->login('testuser',array('pass'=>'!test!'));
   $comment = $this->api->postComment('9',array('text'=>'Test comment: '.date('c')));
   $this->api->deleteComment($comment['id']);
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 } 

 /**** getCat ****/
 public function test_getCat_basename() {
  $cat = $this->api->getCat('1');
  $this->assertArrayHasKey('category_basename',$cat);
 }
 public function test_getCat_badcat() {
  try {
   $this->api->getCat('qqqWWW');//a category that shouldn't exist
  }
  catch (UnexpectedValueException $e) {
   if ($e->getCode() != '1000') $this->fail('An exception was raised, but not the expected one. '.$e->getMessage());
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /**** getCatList ****/
 public function test_getCatList() {
  $list = $this->api->getCatList();
  $this->assertArrayHasKey('category_basename',$list[1]);
 }

 /**** login ****/
 public function test_login_pass() {
  $token = $this->api->login('testuser',array('pass'=>'!test!'));
  $this->assertInternalType('string',$token);
 }
 public function test_login_fail() {
  try {
   $this->api->login('testuser',array('pass'=>'!test'));//bad password
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }

 /***logout***/
 public function test_logout() {
  $this->assertTrue($this->api->logout());
 }

 /***getLatestStatus***/
 public function test_getLatestStatus() {
  $status = $this->api->getLatestStatus();
  $this->assertObjectHasAttribute('text',$status[0]);
 }

 /**** createUser ****/
 public function test_createUser_success() {
  $user = $this->api->createUser(
   '_test_'.md5(date('U')),
   array(
    'pass'	=>	md5(date('c')),
    'email'	=>	'test@example.com',
    'name'	=>	'Unit Test User',
    'url'		=>	'http://www.cbulock.com'
   )
  );
  $this->assertInternalType('integer',$user) ;
 }
 public function test_createUser_passwordfail() {
  try {
   $user = $this->api->createUser(
    '_test_'.md5(date('U')),
    array(
     'email' =>  'test@example.com',
     'name'  =>  'Unit Test User',
     'url'   =>  'http://www.cbulock.com'
    )
   );
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_createUser_emailfail() {
  try {
   $user = $this->api->createUser(
    '_test_'.md5(date('U')),
    array(
     'pass'  =>  md5(date('c')),
     'name'  =>  'Unit Test User',
     'url'   =>  'http://www.cbulock.com'
    )
   );
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_createUser_loginfail() {
  try {
   $user = $this->api->createUser(
    '',
    array(
     'pass'		=>	md5(date('c')),
     'email'	=>	'test@example.com',
     'name'		=>	'Unit Test User',
     'url'		=>	'http://www.cbulock.com'
    )
   );
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_createUser_adminfail() {
  try {
   $user = $this->api->createUser(
    '_test_'.md5(date('U')),
    array(
     'pass'		=>	md5(date('c')),
     'email'	=>	'test@example.com',
     'name'		=>	'Unit Test User',
     'url'		=>	'http://www.cbulock.com',
     'type'		=>	'admin'
    )
   );
  }
  catch (Exception $e) {
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /***nameFree***/
 public function test_nameFree_notfree() {
  $result = $this->api->nameFree('testuser');
  $this->assertFalse($result);
 }
 public function test_nameFree_isfree() {
  $result = $this->api->nameFree('qqqWWW');//a user that likely shouldn't exist
  $this->assertTrue($result);
 }

 /***getUser***/
 public function test_getUser_login() {
  $user = $this->api->getUser('cbulock');
  $this->assertArrayHasKey('login',$user);
 }
 public function test_getUser_email() {
  $user = $this->api->getUser('cbulock');
  $this->assertArrayNotHasKey('email',$user);
 }
 public function test_getUser_pass() {
  $user = $this->api->getUser('cbulock');
  $this->assertArrayNotHasKey('pass',$user);
 }
 public function test_getUser_notexist() {
  $user = $this->api->getUser('qqqWWW');//a user that likely shouldn't exist
  $this->assertFalse($user);
 }
 public function test_getUser_emailadmin() {
  $admin = $this->api->login('testadmin',array('pass'=>'!test!'));
  $user = $this->api->getUser('cbulock',array('token'=>$admin));
  $this->assertArrayHasKey('email',$user);
 }
 public function test_getUser_emailsameuser() {
  $token = $this->api->login('testuser',array('pass'=>'!test!'));
  $user = $this->api->getUser('testuser',array('token'=>$token));
  $this->assertArrayHasKey('email',$user);
 }
 public function test_getUser_emailotheruser() {
  $token = $this->api->login('testuser',array('pass'=>'!test!'));
  $user = $this->api->getUser('cbulock',array('token'=>$token));
  $this->assertArrayNotHasKey('email',$user);
 }

 /***getUserList***/

 /***getAuthUser***/
 public function test_getAuthUser_loggedin() {
  $login = 'testuser';
  $token = $this->api->login($login,array('pass'=>'!test!'));
  $user = $this->api->getAuthUser(array('token'=>$token));
  $this->assertEquals($login,$user['login']);
 }
 public function test_getAuthUser_none() {
  $user = $this->api->getAuthUser();
  $this->assertFalse($user);
 }

 /**** sendMessage ****/
 public function test_sendMessage_success() {
  $result = $this->api->sendMessage(array('name'=>'Test Sender','message'=>'This is sent from unit tester.'));
  $this->assertTrue($result);
 }
 public function test_sendMessage_blank() {
  try {
   $result = $this->api->sendMessage(array('name'=>'Test Sender','message'=>''));
  }
  catch (Exception $e){
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }  
 public function test_sendMessage_fail() {
  try {
   $result = $this->api->sendMessage(array('email'=>'test@','name'=>'Test Sender','message'=>'This is sent from unit tester.'));//bad email
  }
  catch (Exception $e){
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /**** addStat ****/
 public function test_addStat() {
  $result = $this->api->addStat(array('type'=>'test'));
  $this->assertInternalType('integer',$result);
 }

 /***getSetting***/
 public function test_getSetting_exists() {
  $setting = $this->api->getSetting('site_name');
  $this->assertEquals("Cameron's Thoughts",$setting['value']);
 }
 public function test_getSetting_fail() {
  try {
   $this->api->getSetting('qqqWWW');//a setting that doesn't exist
  }
  catch (Exception $e){
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_getSetting_admin() {
  $admin = $this->api->login('testadmin',array('pass'=>'!test!'));  
  $setting = $this->api->getSetting('internal_test');
  $this->assertEquals('1',$setting['value']);
 }
 public function test_getSetting_internal() {
  try {
   $this->api->getSetting('internal_test');
  }
  catch (Exception $e){
   if ($e->getCode() != '403') $this->fail('An exception was raised, but it was not the correct one.');
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 
 /***getImageDetails***/
 public function test_getImageDetails() {
  $image = $this->api->getImageDetails('Test');
  $this->assertArrayHasKey('type',$image);
 }

 /***search***/
 public function test_search_results() {
  $result = $this->api->search('cameron');
  $this->assertArrayHasKey('results',$result);
 }
 public function test_search_noresults() {
  $result = $this->api->search('qqqWWW');//this should return 0 results
  $this->assertEquals(0,$result['count']);
 }

 /***clearCache***/
 public function test_clearCache_fail() {
  try {
   $this->api->clearCache();
  }
  catch (Exception $e) {
   if ($e->getCode() != '403') $this->fail('An exception was raised, but it was not the correct one.');
   return;
  }
  $this->fail('An expected exception has not been raised.');
 }
 public function test_clearCache_admin() {
  $admin = $this->api->login('testadmin',array('pass'=>'!test!'));
  $result = $this->api->clearCache(array('token'=>$admin));
  $this->assertContains(TRUE,$result);
 }
 
}
?>
