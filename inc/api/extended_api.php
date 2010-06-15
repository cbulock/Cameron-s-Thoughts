<?php

class ExtendedAPI extends BaseAPI {

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
