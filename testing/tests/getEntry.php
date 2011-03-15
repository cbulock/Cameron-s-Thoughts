<?php class getEntry {
 protected $api;

 function __construct($api) {
  $this->api = $api;
 }

 function run() {
  $results = array();
  //Test 1
  $result1 = $this->api->getEntry('1',array('callby'=>'id'));
  $results[] = $result1['entry_title'];
  $results[] = $result1['entry_created_on'];
  $results[] = $result1['entry_link'];
  return $results;
 }
 function answers() {
  return array(
   'First Entry',
   '2003-06-22 15:15:36',
   '/2003/06/first_entry.html'
  );
 }
}
?>
