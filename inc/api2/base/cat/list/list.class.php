<?php

class CatList {
 protected $api;

 public function __construct(&$api) {
  $this->api = &$api;
 }

 public function get() {
  $query = 'SELECT * from category WHERE 1';
  return $this->api->db->fetch($query);
 }

}
