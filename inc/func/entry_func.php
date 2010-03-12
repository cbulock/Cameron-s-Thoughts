<?php

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



?>
