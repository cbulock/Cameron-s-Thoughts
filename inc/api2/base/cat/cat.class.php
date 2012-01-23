<?php

require_once('list/list.class.php');

class Cat {
 protected $api;
 public $list;

 public function __construct(&$api) {
  $this->api = &$api;
  $this->list = new CatList(&$api);
 }

 public function test() {
  return $this->api->test();
 }

}
?>
