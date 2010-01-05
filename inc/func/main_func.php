<?php

function include_remote($address)
{
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $address);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
 curl_setopt($ch, CURLOPT_USERAGENT,'CBulock.com Include Retrieval');
 $data = curl_exec($ch);
 curl_close($ch);
 return $data;
}

function fix_urls($text)
{
 preg_match_all('/href=[\'"](.*?)[\'"]/',$text,$matches);
 foreach($matches[1] as $dirty_url)
 {
  $clean_url = htmlspecialchars($dirty_url);
  $text = str_replace($dirty_url,$clean_url,$text);
 }
 return $text;
}

function html_parse($str)
{
 return preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($str));
}

function convert_datetime($str) 
{
 list($date, $time) = explode(' ', $str);
 list($year, $month, $day) = explode('-', $date);
 list($hour, $minute, $second) = explode(':', $time);
 $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
 return $timestamp;
}

function get_catid($basename)
{
 $item = db_get_item('mt_category',$basename,'category_basename','mt2');
 return $item['category_id'];
}

function get_entry_by_cat($catid)
{
 global $querycount;
 mysql_select_db("cbulock_mt2");
 $sql = "SELECT entry_id  FROM mt_entry e INNER JOIN mt_placement p ON e.entry_id = p.placement_entry_id JOIN mt_category c ON c.category_id = p.placement_category_id WHERE c.category_id = ".$catid;
 $querycount++;
 $result = mysql_query($sql);
 while ($row = mysql_fetch_array($result))
 {
  $entries[] = $row[0];
 }
 return array_reverse($entries);
}

function get_entry_by_date($month,$year,$blogid='2')
{
 global $querycount;
 mysql_select_db("cbulock_mt2");
 $sql = "SELECT entry_id FROM `mt_entry` WHERE MONTH(entry_created_on) = ".$month." AND YEAR(entry_created_on) = ".$year." AND entry_blog_id =".$blogid;
 $querycount++;
 $result = mysql_query($sql);
while ($row = mysql_fetch_array($result))
 {
  $entries[] = $row[0];
 }
 return array_reverse($entries);
}
?>
