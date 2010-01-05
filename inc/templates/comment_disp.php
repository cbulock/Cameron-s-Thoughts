<?php
$count = comment_count($id);

$attrib = array('id'=>'comments');
switch($count)
{
 case 0:
  $html->heading('No comments yet',4,$attrib);
 break;
 case 1:
  $html->heading('1 comment',4,$attrib);
 break;
 default:
  $html->heading($count. ' comments',4,$attrib);
 break;
}

foreach ($comments as $comment)
{
 if ($comment['user'])
 {
  $comment_user = db_get_item('users',$comment['user']);
  $comment['email'] = $comment_user['email'];
  $comment['author'] = $comment_user['name'];
  $comment['url'] = $comment_user['url'];
 }
 if ($comment['url'])
 {
  $author = $html->linktag($comment['url'],htmlentities($comment['author'],ENT_QUOTES));
 }
 else
 {
  $author = htmlentities($comment['author'],ENT_QUOTES);
 }
 $html->text($comment['text'],array('id'=>'c'.$comment['id']));
 $html->text("Comment by: ".$author." on ".date("F d, Y h:i A",convert_datetime($comment['created'])));
}
?>
