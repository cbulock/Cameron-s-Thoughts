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

 /***tokenLogin***/
 //can't directly test tokenLogin as it's protected,
 //need to come up with some indirect tests

 /***logout***/
 public function test_logout() {
  $this->assertTrue($this->api->logout());
 }

 /***getLatestStatus***/
 public function test_getLatestStatus() {
  $status = $this->api->getLatestStatus();
  $this->assertObjectHasAttribute('text',$status[0]);
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



 
}
?>
