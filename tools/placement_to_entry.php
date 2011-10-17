<?php
////////////////////////////////////////////////////////////////////
//This copies placement_category_id from mt_placement and places it
//into mt_entry under entry_category_id
////////////////////////////////////////////////////////////////////

require_once('../var.inc');
require_once('../inc/api/mysql.php');
require_once('../inc/api/cache.php');
$cache = new Cache;
$db = new DB(DB_HOST,DB_USER,DB_PASS,array('prefix'=>DB_PREFIX,'cache'=>$cache));

$placements = $db->getTable('mt_placement',array('orderBy'=>"`placement_id`",'key'=>'placement_id','cache'=>FALSE));

foreach($placements as $p) {
 $db->updateItem('mt_entry',$p['placement_entry_id'],array('entry_category_id'=>$p['placement_category_id']),array('field'=>'entry_id','cache'=>FALSE));
}

?> 
