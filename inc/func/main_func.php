<?php

function fix_urls($text) {
 preg_match_all('/href=[\'"](.*?)[\'"]/',$text,$matches);
 foreach($matches[1] as $dirty_url)
 {
  $clean_url = htmlspecialchars($dirty_url);
  $text = str_replace($dirty_url,$clean_url,$text);
 }
 return $text;
}

function convert_datetime($str) {
 list($date, $time) = explode(' ', $str);
 list($year, $month, $day) = explode('-', $date);
 list($hour, $minute, $second) = explode(':', $time);
 $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
 return $timestamp;
}

?>
