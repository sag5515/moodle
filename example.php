<?php
$proxy_opts = array(
 'http' => array(
 'proxy' => 'http-p.srv.cc.suzuka-ct.ac.jp:8080',
 ),
);
$proxy_context=stream_context_create($proxy_opts);
$a = array("0033","0161","0206","0184");
for($i=0;$i<3;$i++){
$url ='http://judge.u-aizu.ac.jp/onlinejudge/webservice/status_log?user_id=h23i21&problem_id='.$a[$i];
$xml_string=file_get_contents($url, false,$proxy_context);
$xml_obj=simplexml_load_string($xml_string);
var_dump($xml_obj);
for($j=0;$j<count($xml_obj->status);$j++){
      	 $str=(string)$xml_obj->status[$j]->status;
	 $str = preg_replace('/(\s|　)/','',$str);
    	 if($str == "Accepted"){
	 	print $i."\n";
		print "ssssssssssssssssssssssssssssss\n";
		$check[$i]="○";
	  	break;
    	  }
}
}

