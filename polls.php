<?php
$nameFile = 'poll-results.txt';
$current = 'pool-1';

$votes = array();

$key = 'pool-1';
$value = new stdClass();
$value->id = $key;
$value->question = 'Кто вы?';
$value->answers = array('Интроверт','Экстраверт','Не знаю');
$votes[$key] = $value;

// $output = file_get_contents(dirname(__FILE__).'/'.$nameFile);
// $output = json_decode($output, true);
$votes[$current]->result = $output[$current];

// if (isset($_COOKIE['polls'])) {
//   $arrayPolls = explode(',',$_COOKIE['polls']);
//   if (in_array($current, $arrayPolls)) {
//     $output = file_get_contents(dirname(__FILE__).'/'.$nameFile);
//     $output = json_decode($output, true);
//     if (array_key_exists($current, $output)) {
//       $votes[$current]->result = $output[$current];
//     }
//   }
// }

echo json_encode($votes[$current]);

exit();