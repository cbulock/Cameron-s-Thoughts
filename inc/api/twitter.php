<?php

class Status {

protected function postStatus($message) {
 
}

public function getStatus($options) {
 if (!$options['count']) $options['count'] = 1;
 $url = 'statuses/user_timeline.json?user_id='.TWITTER_UID.'&count='.$options['count'];
 return $this->call($url);
}

private function call($url,$post=NULL) {
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, 'https://api.twitter.com/1/'.$url);
 //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Cameron\'s Thoughts Twitter API Caller)');
 curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
 curl_setopt($ch, CURLOPT_TIMEOUT, 20);

 $json = curl_exec($ch);
 curl_close($ch);

 return json_decode($json);
}


// End Status
}

