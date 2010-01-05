<?php
$html->title($site['title']);
$html->stylesheet('/css/main.css','Main');

//Login box
if ($loggedin)
{
 $html->text('Welcome, '.$user['name'],array('id'=>'login'));
}

$html->heading($html->linktag('/',$site['title']),1);
$html->text($site['tag'],array('id'=>'headtag'));
?>
