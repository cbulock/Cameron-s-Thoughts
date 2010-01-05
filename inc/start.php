<?php
//Pull in data
$entry = get_entry(last_entry(),2,'id');

include(T_DIR.'header.php');

for($i = 0; $i < MAINPAGE_COUNT; $i++)
{
 include(T_DIR.'entry.php');
 //comment count and link
 $count = comment_count($entry['entry_id']);
 switch($count)
 {
  case 0:
   $html->text($html->linktag(entry_link($entry).'#comments','No comments yet'));
  break;
  case 1:
   $html->text($html->linktag(entry_link($entry).'#comments','1 comment'));
  break;
  default:
   $html->text($html->linktag(entry_link($entry).'#comments',$count. ' comments'));
  break;
 }
 //increase the count if not the last page
 if ($i < MAINPAGE_COUNT-1)
 {
  $entry = get_entry(prev_entry($entry['entry_id']),2,'id');
 }
}

$html->comment("Total Queries: ".$querycount);//AHHH querycount is bad

$html->outputPage();
?>
