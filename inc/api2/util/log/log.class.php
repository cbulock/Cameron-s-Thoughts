<?php

class Log {

 protected $standard;
 protected $error;

 public function write($text, $type='standard') {
  if (!isset($this->{$type})) {
   $logfile = LOG_DIR.$type.'.log';
   $this->{$type} = fopen($logfile,'a');
  }
  $timestamp = date('c');
  $log = $timestamp."\t".$_SERVER['REMOTE_ADDR']."\t".$text."\n";
  return fwrite($this->{$type},$log);
 }


 public function __destruct() {
  if (isset($this->standard)) {
   fclose($this->standard);
  }
  if (isset($this->error)) {
   fclose($this->error);
  }
 }

}
?>
