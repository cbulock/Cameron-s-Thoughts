#! /usr/bin/php-cli
<?php
//Stylesheet builder, requires LessCSS

//basic steps
$dirs = get_dirs();
$configs = get_configs($dirs);
build_css($configs);


function get_dirs() {
 $result = array();
 $dirh = dir('.');
 while (FALSE !== $entry = $dirh->read()) {
  if ((is_dir($entry)) && ($entry!='.' && $entry!='..')) $result[] = $entry;
 }
 $dirh->close();
 return $result;
}

function get_configs($dirs) {
 $result = array();
 foreach($dirs as $dir) {
  $dirh = dir($dir);
   while (FALSE !== $entry = $dirh->read()) {
    if (substr($entry,-7)=='.config') $result[] = $dir.'/'.substr($entry,0,strlen($entry)-7);
   }
 }
 return $result;
}

function build_css($configs) {
 foreach($configs as $config) {
  $x = explode('/',$config);
  $dir = $x[0];
  $name = $x[1];
  echo "Building less file for ".$name." theme of the ".$dir." style\n";
  system('cat '.$dir.'/'.$name.'.config '.$dir.'/base.less > '.$dir.'/'.$name.'.less');
  echo "Compiling CSS from less for ".$name." theme of the ".$dir." style\n";
  system('lessc '.$dir.'/'.$name.'.less');
 }
}
?>
