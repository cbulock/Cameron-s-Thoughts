<?php

class ExtendedAPI extends BaseAPI {

public function call($method, $req = array(), $opt = array()) {
 return call_user_func_array(array($this,$method),array($req,$opt)); 
}

public function getNewEntries($options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  //'offset' => '0', //don't think offset will be implemented for this method
  'count' => '10',
  'blogid' => '2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $entries = array();
 $current_entry = $this->lastEntry(array('blogid'=>$options['blogid']));
 for($i = 0; $i < $options['count']; $i++) {
  $entries[$current_entry] = $this->getEntry($current_entry,array('callby'=>'id'));
  if ($i < $options['count']-1) {
   $current_entry = $this->prevEntry($current_entry,array('blogid'=>$options['blogid']));//need to catch the expection for prevEntry in case it hits the first one
  }
 }
 return $this->api_call_finish($entries);
}

public function getCatEntries($catid, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'offset' => '0',//offset and count are not implemented in the sql query yet
  'count' => '10',
  'blogid' => '2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $dboptions = array(
  'where' => 'placement_category_id = "'.$this->db->sqlClean($catid).'" AND placement_blog_id= "'.$this->db->sqlClean($options['blogid']).'"',
  'orderBy' => 'placement_entry_id DESC',
  'key' => 'placement_entry_id'
 );
 $entry_list = $this->db->getTable('mt_placement',$dboptions);
 foreach ($entry_list as $entry) {
  $entries[$entry['placement_entry_id']] = $this->getEntry($entry['placement_entry_id'],array('callby'=>'id'));
 }
 return $this->api_call_finish($entries);
}

public function getMonthlyEntries($month, $year, $options = array()) {
 $setup['options'] = $options;
 $setup['defaults'] = array(
  'offset' => '0',//offset and count are not implemented in the sql query yet
  'count' => '10',
  'blogid' => '2'
 );
 extract($setup_result = $this->api_call_setup($setup));
 $dboptions = array(
  'where' => 'MONTH(entry_created_on) = "'.$month.'" AND YEAR(entry_created_on) = "'.$year.'" AND entry_blog_id= "'.$this->db->sqlClean($options['blogid']).'"',
  'orderBy' => 'entry_id DESC',
  'key' => 'entry_id'
 );
 $entry_list = $this->db->getTable('entry',$dboptions);
 if (!$entry_list) throw new Exception('Month not found',1000);
 foreach ($entry_list as $entry) {
  $entries[$entry['entry_id']] = $this->getEntry($entry['entry_id'],array('callby'=>'id'));
 }
 return $this->api_call_finish($entries);
}

public function commentCountText($count) {
 switch($count) {
  case '0':
   return $this->api_call_finish('No comments yet');
  break;
  case '1':
   return $this->api_call_finish('1 comment');
  break;
  default:
   return $this->api_call_finish($count.' comments');
  break;
 }
}


}
//End ExtendedAPI
?>
