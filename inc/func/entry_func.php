<?php
function show_entry($text) {
 global $html;
 global $entry;
 $text=process_images($text);
 $text=fix_urls($text);
 if ($entry['entry_convert_breaks']) {
  $html->text($text);
 }
 else {
  $html->addToBody($text);
 }
}

function process_images($text) {
 global $html;
 $output = '';
 $pieces=explode('<?php ',$text);
 foreach ($pieces as $piece) {
  if (substr($piece,0,4)=='echo') {
   $piece = str_replace('echo',"return",$piece);
   $piece = str_replace("?>","",$piece);
   //in the future, this should be something like $html->image() or something, once the image system is in plac
   $subpieces = explode(';',$piece);
   foreach ($subpieces as $i => $subpiece) {
    if (!$i) {
     $output .= (eval($subpiece.";"));
     //$html->img(eval($piece));
    }
    else {
     $output .= ($subpiece);
    }
   }
  }
  else {
   $output .= ($piece);
  }
 }
 return $output;
}

function get_entry($value, $blogid='2',$callby='basename') {
 global $db;
 switch($callby) {
  case 'basename':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".DB::sqlClean($blogid)."' AND entry_basename='".DB::sqlClean($value)."'";
  break;
  case 'id':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".DB::sqlClean($blogid)."' AND entry_id='".DB::sqlClean($value)."'";
  break;
 }
 return $db->directQuery($sql,'cbulock_mt2');
}

function comment_count($postid, $blogid='2') {
 global $db;
 $sql = "SELECT COUNT(*) FROM `comments` WHERE blogid = '".DB::sqlClean($blogid)."' AND postid = '".DB::sqlClean($postid)."'";
 $result = $db->directQuery($sql,'cbulock_cbulock');
 //return $row[0]; //need to figure out what exactly to return
}

function get_comments($postid, $blogid='2') {
 global $db;
 $comments = $db->getTable('comments', "postid = '".$postid."' AND blogid= '".$blogid."'", "`created`",'cbulock');
 return $comments;
}

function prev_entry($id,$blogid='2',$where='1') {
 global $db;
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_id < ".DB::sqlClean($id)." AND entry_blog_id =".DB::sqlClean($blogid)." AND ".$where.")";
 $result = $db->directQuery($sql,'cbulock_mt2');
// return $row[0];//need to figure out the correct return
}

function next_entry($id,$blogid='2',$where='1') {
 global $db;
 $sql = "select min(entry_id) FROM `mt_entry` WHERE (entry_id > ".DB::sqlClean($id)." AND entry_blog_id = ".DB::sqlClean($blogid)." AND ".$where.")"; 
 $result = $db->directQuery($sql,'cbulock_mt2');
// return $row[0];//need to figure out the correct return
}

function last_entry($blogid='2',$where='1') {
 global $db;
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_blog_id = ".DB::sqlClean($blogid)." AND ".$where.")";
 $result = $db->directQuery($sql,'cbulock_mt2');
// return $row[0];//need to figure out the correct return
}

function entry_link($entry) {
 $year = date('Y',strtotime($entry['entry_created_on']));
 $month = date('m',strtotime($entry['entry_created_on']));
 return "/".$year."/".$month."/".$entry['entry_basename'].".html";
}

function get_cat_id($entry_id) {
 global $db;
 $item = $db->getItem('mt_placement',$entry_id,'placement_entry_id','mt2');
 return $item['placement_category_id'];
}

function get_cat($cat_id) {
 global $db;
 return $db->getItem('mt_category',$cat_id,'category_id','mt2');
}

?>
