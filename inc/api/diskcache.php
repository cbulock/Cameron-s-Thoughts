<?php

class Cache {

private $count;  //cache usage counter

public function exists($name, $expires = DEFAULT_CACHE_EXPIRES) {//NULL expires causes expiration to be ignored
 $name = md5($name);
 if ($expires) {
  $mtime = @filemtime(CACHE_DIR.$name.'.cache');
  if ((date('U') - $mtime) < $expires*60 && file_exists(CACHE_DIR.$name.'.cache')) return TRUE;
 }
 else {
  if (file_exists(CACHE_DIR.$name.'.cache')) return TRUE;
 }
 return FALSE;
}

public function add($name, $data) {
 $name = md5($name);
 $file = fopen(CACHE_DIR.$name.'.cache','w'); 
 fwrite($file,serialize($data));
 fclose($file);
 return TRUE;
}

public function read($name) {
 if (!$this->exists($name,NULL)) return FALSE;
 $name = md5($name);
 $filename = CACHE_DIR.$name.'.cache';
 $file = fopen($filename,'r');
 $this->count++;
 return unserialize(fread($file,filesize($filename)));
}

public function clear() {
 $mask = CACHE_DIR."*.cache";
 return array_map("unlink", glob($mask));
}

public function count() {
 return $this->count;
}

//End Cache

}
