<?php
//Requires PHPUnit
require_once('../var.inc');
require_once(API_DIR.'base_api.php');
class BaseAPITest extends PHPUnit_Framework_TestCase {
 
 protected $api;
 protected $user;
 protected $admin;
 protected function setUp(){
  $this->api = new BaseAPI();
 }
 protected function tearDown(){
  unset($this->api);
 }
 
 /**** postEntry ****/

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

 /**** postComment ****/

 /**** getCatID ****/
 public function test_getCatID_success() {
  $cat = (int)$this->api->getCatID('2');
  if ($cat === 0) unset($cat);
  $this->assertInternalType('integer',$cat);
 }
 public function test_getCatID_fail() {
  $cat = $this->api->getCatID('1');
  $this->assertFalse($cat);
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
 /* This should thrown an exception, currently a known bug
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
 }*/
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
 public function test_getUser_notexist() {
  $user = $this->api->getUser('qqqWWW');//a user that likely shouldn't exist
  $this->assertFalse($user);
 }

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

 /**** addStat ****/

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
 public function test_getSetting_internal() {
  try {
   $this->api->getSetting('admin_email');
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
