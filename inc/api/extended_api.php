<?php

class ExtendedAPI extends BaseAPI {

public function getNewEntries($options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  //'offset' => '0', //don't think offset will be implemented for this method
  'count' => '10'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $entries = array();
 $current_entry = $this->lastEntry();
 for($i = 0; $i < $options['count']; $i++) {
  $entries[$current_entry] = $this->getEntry($current_entry,array('callby'=>'id'));
  if ($i < $options['count']-1) {
   $current_entry = $this->prevEntry($current_entry);
  }
 }
 return $entries;
}


public function getCatEntries($catid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'offset' => '0',//offset and count are not implemented in the sql query yet
  'count' => '10'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $dboptions = array(
  'where' => 'placement_category_id = '.$this->db->sqlClean($catid),
  'orderBy' => 'placement_entry_id DESC',
  'key' => 'placement_entry_id'
 );
 $entry_list = $this->db->getTable('mt_placement',$dboptions);
 foreach ($entry_list as $entry) {
  $entries[$entry['placement_entry_id']] = $this->getEntry($entry['placement_entry_id'],array('callby'=>'id'));
 }
 return $entries;
}





}
//End ExtendedAPI
?>
