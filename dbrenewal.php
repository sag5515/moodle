<?php
require('../../config.php');
 global $USER, $DB;

$renewal = $_POST["renewal"];

$proxy_opts = array(
 'http' => array(
 'proxy' => 'http-p.srv.cc.suzuka-ct.ac.jp:8080',
 ),
);
$proxy_context=stream_context_create($proxy_opts);
$data=$DB->get_record('aojtools', array('id' => $renewal), '*');
$data3=$DB->get_record('block_aojlogin', array('userid' => $USER->username), '*');

$a='a';
$check=array("","","","","","","","","","");
for($i=0;$i<10;$i++){
$aa='problem_'.$a++;
$url ='http://judge.u-aizu.ac.jp/onlinejudge/webservice/status_log?user_id='.$data3->aojid.'&problem_id='.$data->$aa;
$xml_string=file_get_contents($url, false,$proxy_context);
$xml_obj=simplexml_load_string($xml_string);
switch($data->period){
    case 0:
      for($j=0;$j<count($xml_obj->status);$j++){
      	 	$str=(string)$xml_obj->status[$j]->status;
    	  	if(strncasecmp(substr($str,1),"Accepted",1)==0){
			$check[$i]="○";
	  	break;
    	  	}
       	}
	break;
    case 1:
      for($j=0;$j<count($xml_obj->status);$j++){
		if($xml_obj->status[$j]->submission_date > ($data->starttime)*1000){
      	  		$str=(string)$xml_obj->status[$j]->status;
    	  		if(strncasecmp(substr($str,1),"Accepted",1)==0){
				$check[$i]="○";
	  		break;
    	  		}
		}
       }
	break;
    case 2:
      for($j=0;$j<count($xml_obj->status);$j++){
		if($xml_obj->status[$j]->submission_date < ($data->finishtime)*1000){
      	  		$str=(string)$xml_obj->status[$j]->status;
    	  		if(strncasecmp(substr($str,1),"Accepted",1)==0){
				$check[$i]="○";
	  		break;
    	  		}
		}
       }
	break;
    case 3:
      for($j=0;$j<count($xml_obj->status);$j++){
		if($xml_obj->status[$j]->submission_date > ($data->starttime)*1000 && $xml_obj->status[$j]->submission_date < ($data->finishtime)*1000){
      	  		$str=(string)$xml_obj->status[$j]->status;
    	  		if(strncasecmp(substr($str,1),"Accepted",1)==0){
				$check[$i]="○";
	  		break;
    	  		}
		}
       }
	break;
    default:
	break;
}
}

$input=array(
'id'=>$data2->id,
'courseid'=>$renewal,
'userid'=>$USER->username,
'timemodified'=>$data->timemodified,
'problem_a'=>$check[0],
'problem_b'=>$check[1],
'problem_c'=>$check[2],
'problem_d'=>$check[3],
'problem_e'=>$check[4],
'problem_f'=>$check[5],
'problem_g'=>$check[6],
'problem_h'=>$check[7],
'problem_i'=>$check[8],
'problem_j'=>$check[9],
);
$DB->update_record('aojproblemlist', $input);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($renewal);

}
?>
