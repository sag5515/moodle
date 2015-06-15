<?php
require('../../config.php');
 global $USER, $DB;

$aojid = $_POST["aojid"];

$data=$DB->get_record('aojlogin', array('userid' => $USER->username), '*');

$input=array(
'id'=>$data->id,
'userid'=>$USER->username,
'aojid'=>$aojid,
);
$DB->update_record('aojlogin', $input);
?>
