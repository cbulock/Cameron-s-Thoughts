#! /usr/bin/php-cli
<?php
//This is the testing suite for Cameron's Thoughts

//Basic tests
$tests = array(
 'getEntry',
 'getUser'
);
//These tests should only be ran in development environments as they write data
$writetests = array(
 'postComment',
 'postEntry'
);


//Beginning of test logic
foreach ($tests as $testname) {
 run_test($testname);
}

function run_test($testname) {
 require_once('tests/'.$testname.'.php');
 $api = 1;
 $test = new $testname($api);
 $result = array();
 $result['name'] = $testname;
 $result['test'] = $test->run();
 $result['answers'] = $test->answers();
 return display(process_results($result));
}

function process_results($results) {
 $counter = 0;
 foreach($results['answers'] as $i=>$r) {
   if ($r == $results['test'][$i]) $counter++;
 }
 return array(
  'passed'=>$counter,
  'total'=>count($results['answers']),
  'name'=>$results['name']
 );
}

function display($data) {
 $data['result'] = "FAILED";
 $data['color'] = "0;31";
 if ($data['passed']==$data['total']) {
  $data['result'] = "PASSED";
  $data['color'] = "0;32";
 }
 echo $data['passed'].'/'.$data['total']."\t".chr(27).'['.$data['color'].'m'.$data['result'].chr(27)."[0m\t".$data['name']."\n";
 return TRUE;
}
