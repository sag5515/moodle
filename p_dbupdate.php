<?php
require('../../config.php');
 global $USER, $DB;

$renewal = $_POST["renewal"];
$problemnum = $_POST["problemnum"];

$proxy_opts = array(
 'http' => array(
 'proxy' => 'http-p.srv.cc.suzuka-ct.ac.jp:8080',
 ),
);
$proxy_context=stream_context_create($proxy_opts);
$data=$DB->get_record('aojtools', array('id' => $renewal), '*');
$data2=$DB->get_record('aojproblemlist', array('userid' => $USER->username,'courseid'=>$renewal), '*');
$data3=$DB->get_record('aojlogin', array('userid' => $USER->username), '*');

$a="a";
for($i=0; $i<10; $i++){
$aa='problem_'.$a++;
if($data->$aa === $problemnum && $data2->$aa != "○"){
$a='a';
$check="";

$url ='http://judge.u-aizu.ac.jp/onlinejudge/webservice/status_log?user_id='.$data3->aojid.'&problem_id='.$problemnum;
$xml_string=file_get_contents($url, false,$proxy_context);
$xml_obj=simplexml_load_string($xml_string);

switch($data->period){
    case 0:
      for($j=0;$j<count($xml_obj->status);$j++){
      	 	$str=(string)$xml_obj->status[$j]->status;
    	  	$str = preg_replace('/(\s|　)/','',$str);
    	  	if($str == "Accepted"){
			$check="○";
	  	break;
    	  	}
       	}
	break;
    case 1:
      for($j=0;$j<count($xml_obj->status);$j++){
		if($xml_obj->status[$j]->submission_date > ($data->starttime)*1000){
      	  		$str=(string)$xml_obj->status[$j]->status;
    	  		$str = preg_replace('/(\s|　)/','',$str);
    	  		if($str == "Accepted"){
				$check="○";
	  		break;
    	  		}
		}
       }
	break;
    case 2:
      for($j=0;$j<count($xml_obj->status);$j++){
		if($xml_obj->status[$j]->submission_date < ($data->finishtime)*1000){
      	  		$str=(string)$xml_obj->status[$j]->status;
    	  		$str = preg_replace('/(\s|　)/','',$str);
    	  		if($str == "Accepted"){
				$check="○";
	  		break;
    	  		}
		}
       }
	break;
    case 3:
      for($j=0;$j<count($xml_obj->status);$j++){
		if($xml_obj->status[$j]->submission_date > ($data->starttime)*1000 && $xml_obj->status[$j]->submission_date < ($data->finishtime)*1000){
      	  		$str=(string)$xml_obj->status[$j]->status;
    	  		$str = preg_replace('/(\s|　)/','',$str);
    	  		if($str == "Accepted"){
				$check="○";
	  		break;
    	  		}
		}
       }
	break;
    default:
	break;
}
$updatescore = array("","","","","","","","","","");
$a="a";
for($i=0; $i<10; $i++){
$aa='problem_'.$a++;
if($data->$aa === $problemnum){
$updatescore[$i]=$check;
}else{
$updatescore[$i]=$data2->$aa;
}
}

$sum=0;
for($i=0;$i<10;$i++){
if($updatescore[$i]==="○"){
  $select="point_".(string)($i+1);
  $sum+=$data->$select;
}
}

$input=array(
'id'=>$data2->id,
'courseid'=>$renewal,
'userid'=>$USER->username,
'timemodified'=>$data->timemodified,
'problem_a'=>$updatescore[0],
'problem_b'=>$updatescore[1],
'problem_c'=>$updatescore[2],
'problem_d'=>$updatescore[3],
'problem_e'=>$updatescore[4],
'problem_f'=>$updatescore[5],
'problem_g'=>$updatescore[6],
'problem_h'=>$updatescore[7],
'problem_i'=>$updatescore[8],
'problem_j'=>$updatescore[9],
'point_sum'=>$sum,
);
$DB->update_record('aojproblemlist', $input);
}
}

?>
