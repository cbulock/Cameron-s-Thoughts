<?php
////////////////////////////////////////////////////////////////////
// This finds any entries in the mt_placement table that don't have
// corresponding entries in mt_entries and prints the entry id's 
////////////////////////////////////////////////////////////////////

echo "The following entry ID's are found in mt_placement but are not in mt_entry:\n\n";

require_once('../var.inc');
require_once('../inc/api/mysql.php');
require_once('../inc/api/cache.php');
$cache = new Cache;
$db = new DB(DB_HOST,DB_USER,DB_PASS,array('prefix'=>DB_PREFIX,'cache'=>$cache));

$placements = $db->getTable('mt_placement',array('orderBy'=>"`placement_id`",'key'=>'placement_id','cache'=>FALSE));

foreach($placements as $p) {
 if(!$db->getItem('mt_entry',$p['placement_entry_id'],array('field'=>'entry_id','cache'=>FALSE))) {
  echo $p['placement_entry_id']."\n";
 }
} 
