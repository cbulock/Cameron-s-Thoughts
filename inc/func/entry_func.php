<?php
function show_entry($text)
{
 global $html;
 global $entry;
 $text=process_images($text);
 $text=fix_urls($text);
 if ($entry['entry_convert_breaks'])
 {
  $html->text($text);
 }
 else
 {
  $html->addToBody($text);
 }
}


function process_images($text)
{
 global $html;
 $output = '';
 $pieces=explode('<?php ',$text);
 foreach ($pieces as $piece)
 {
  if (substr($piece,0,4)=='echo')
  {
   $piece = str_replace('echo',"return",$piece);
   $piece = str_replace("?>","",$piece);
   //in the future, this should be something like $html->image() or something, once the image system is in plac
   $subpieces = explode(';',$piece);
   foreach ($subpieces as $i => $subpiece)
   {
    if (!$i)
    {
     $output .= (eval($subpiece.";"));
     //$html->img(eval($piece));
    }
    else
    {
     $output .= ($subpiece);
    }
   }
  }
  else
  {
   $output .= ($piece);
  }
 }
 return $output;
}

function get_entry($value, $blogid='2',$callby='basename')
{
 global $querycount;
 mysql_select_db("cbulock_mt2");
 switch($callby)
 {
  case 'basename':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".sqlclean($blogid)."' AND entry_basename='".sqlclean($value)."'";
  break;
  case 'id':
   $sql = "SELECT * FROM `mt_entry` WHERE entry_blog_id='".sqlclean($blogid)."' AND entry_id='".sqlclean($value)."'";
  break;
 }
 $result = mysql_query($sql);$querycount++;
 if (!$result) return false;
 $row = mysql_fetch_array($result);

 if (!$row) return false;
 foreach($row as $key => $val)
 {
   $resultArray[$key] = stripslashes($val);
 }
 return $resultArray;
}

function comment_count($postid, $blogid='2')
{
 global $querycount;
 mysql_select_db("cbulock_cbulock");
 $sql = "SELECT COUNT(*) FROM `comments` WHERE blogid = '".sqlclean($blogid)."' AND postid = '".sqlclean($postid)."'";
 $result = mysql_query($sql);$querycount++;
 $row = mysql_fetch_row($result);
 return $row[0];
}

function get_comments($postid, $blogid='2')
{
 $comments = db_get_table('comments', "postid = '".$postid."' AND blogid= '".$blogid."'", "`created`",'cbulock');
 return $comments;
}

function prev_entry($id,$blogid='2',$where='1')
{
 global $querycount;
 mysql_select_db("cbulock_mt2");
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_id < ".sqlclean($id)." AND entry_blog_id =".sqlclean($blogid)." AND ".$where.")";
 $result = mysql_query($sql);$querycount++;
 $row = mysql_fetch_row($result);
 return $row[0];
}

function next_entry($id,$blogid='2',$where='1')
{
 global $querycount;
 mysql_select_db("cbulock_mt2");
 $sql = "select min(entry_id) FROM `mt_entry` WHERE (entry_id > ".sqlclean($id)." AND entry_blog_id = ".sqlclean($blogid)." AND ".$where.")"; 
 $result = mysql_query($sql);$querycount++;
 $row = mysql_fetch_row($result);
 return $row[0];
}

function last_entry($blogid='2',$where='1')
{
 global $querycount;
 mysql_select_db("cbulock_mt2");
 $sql = "select max(entry_id) FROM `mt_entry` WHERE (entry_blog_id = ".sqlclean($blogid)." AND ".$where.")";
 $result = mysql_query($sql);$querycount++;
 $row = mysql_fetch_row($result);
 return $row[0];
}

function entry_link($entry)
{
 $year = date('Y',strtotime($entry['entry_created_on']));
 $month = date('m',strtotime($entry['entry_created_on']));
 return "/".$year."/".$month."/".$entry['entry_basename'].".html";
}

function get_cat_id($entry_id)
{
 $item = db_get_item('mt_placement',$entry_id,'placement_entry_id','mt2');
 return $item['placement_category_id'];
}

function get_cat($cat_id)
{
 return db_get_item('mt_category',$cat_id,'category_id','mt2');
}


?>
