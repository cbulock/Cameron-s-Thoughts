<?php

class Cache {

protected $count;  //cache usage counter

public function exists($name, $expires = DEFAULT_CACHE_EXPIRES) {//NULL expires causes expiration to be ignored
 $name = md5($name);
 //does not check for expiration yet
 return file_exists(CACHE_DIR.$name);
}

public function add($name, $data) {
 $name = md5($name);
 $file = fopen(CACHE_DIR.$name,'w'); 
 //print serialize($data);
 fwrite($file,serialize($data));
 fclose($file);
 return TRUE;
}

public function read($name) {
 if (!$this->exists($name,NULL)) return FALSE;
 $name = md5($name);
 $filename = CACHE_DIR.$name;
 $file = fopen($filename,'r');
 $this->count++;
 return unserialize(fread($file,filesize($filename)));
}

public function count() {
 return $this->count;
}

//End Cache

}
