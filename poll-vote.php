<?php
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {return;}

if (empty($_POST['id'])) {exit();}

$nameFile = 'poll-results.txt';

$id = $_POST['id'];
$answer = $_POST['poll'];
$count = $_POST['count'];

if (isset($_COOKIE['polls'])) {
  $arrayPolls = explode(',',$_COOKIE['polls']);
  if (in_array($id, $arrayPolls)) {
    exit();
  }
}

$result = array();

if (!file_exists($nameFile)) {
  $output = array();
  $answers = array();
  for ($i=0; $i<=$count-1; $i++) {
    $answers[$i] = 0;
  }
  $answers[$answer-1] = $answers[$answer-1] + 1;
  $output[$id] = $answers;
  $output = json_encode($output);
  file_put_contents(dirname(__FILE__).'/'.$nameFile, $output, LOCK_EX);
} else {
  $output = file_get_contents(dirname(__FILE__).'/'.$nameFile);
  $output = json_decode($output, true);
  if (array_key_exists($id, $output)) {
    $answers = $output[$id];
    $answers[$answer-1] = $answers[$answer-1] + 1;
    $output[$id] = $answers;
  } else {
    $answers = array();
    for ($i=0; $i<=$count-1; $i++) {
      $answers[$i] = 0;
    }
    $answers[$answer-1] = $answers[$answer-1] + 1;
    $output[$id] = $answers;
  }
  $output = json_encode($output);
  file_put_contents(dirname(__FILE__).'/'.$nameFile, $output, LOCK_EX);
}

if (isset($_COOKIE['polls'])) {
  $arrayPolls = explode(',',$_COOKIE['polls']);
} else {
  $arrayPolls = array();
}
array_push($arrayPolls,$id);
setcookie('polls', implode(',',$arrayPolls),time() + (86400 * 365),'/');   

$result[$id] = $answers;
$result = json_encode($result);  
echo $result;
exit();
?>