<?php

require_once(INCLUDE_DIR.'/libs/twitter/twitteroauth/twitteroauth.php');

class Status {

 protected $twitter;

public function postStatus($message) {
 return $this->twitter->post('statuses/update',array('status'=>$message));
}

public function getStatus($options) {
 if (!$options['count']) $options['count'] = 1;
 return $this->twitter->get('statuses/user_timeline',array('user_id'=>TWITTER_UID,'count'=>$options['count']));
}

public function __construct() {
 $this->twitter = new TwitterOAuth(TWITTER_CONSUMER_KEY,TWITTER_CONSUMER_SECRET,TWITTER_OAUTH_TOKEN,TWITTER_OAUTH_SECRET);
}

// End Status
}

