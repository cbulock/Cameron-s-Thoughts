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
