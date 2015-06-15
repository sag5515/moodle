<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of aojtools
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_aojtools
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// (Replace aojtools with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // aojtools instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('aojtools', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $aojtools  = $DB->get_record('aojtools', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $aojtools  = $DB->get_record('aojtools', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $aojtools->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('aojtools', $aojtools->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

add_to_log($course->id, 'aojtools', 'view', "view.php?id={$cm->id}", $aojtools->name, $cm->id);

/// Print the page header
$PAGE->set_url('/mod/aojtools/view.php', array('id' => $cm->id));
require_login($course, true, $cm);
$context = context_module::instance($cm->id);
$PAGE->set_title(format_string($aojtools->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('aojtools-'.$somevar);

// Output starts here
echo $OUTPUT->header();

if ($aojtools->intro) { // Conditions to show the intro can change to look for own settings or whatever
    echo $OUTPUT->box(format_module_intro('aojtools', $aojtools, $cm->id), 'generalbox mod_introbox', 'aojtoolsintro');
}
//---------------------------------------------------------------------------------------------------------------------
//$cm->instanceはたぶんaojtoolsのid
//--------------------------------------プロキシ-----------------------------------------
$proxy_opts = array(
 'http' => array(
 'proxy' => 'http-p.srv.cc.suzuka-ct.ac.jp:8080',
 ),
);
$proxy_context=stream_context_create($proxy_opts);
//$xml_string=file_get_contents('http://judge.u-aizu.ac.jp/onlinejudge/webservice/user?id=h22i16', false,$proxy_context);
//$xml_obj=simplexml_load_string($xml_string);
$data=$DB->get_record('aojtools', array('id' => $cm->instance), '*');
$data2=$DB->get_record('aojproblemlist', array('userid' => $USER->username,'courseid'=>$cm->instance), '*');
$data3=$DB->get_record('aojlogin', array('userid' => $USER->username), '*');
//-------------------------------------データ更新or追加---------------------------------
$a='a';
$check=array("","","","","","","","","","");
var_dump($data->timemodified);
var_dump($data2->timemodified);
if(empty($data2) || $data2->timemodified < $data->timemodified){
for($i=0;$i<10;$i++){
$aa='problem_'.$a++;
$url ='http://judge.u-aizu.ac.jp/onlinejudge/webservice/status_log?user_id='.$data3->aojid.'&problem_id='.$data->$aa;
print_r($url);
$xml_string=file_get_contents($url, false,$proxy_context);
$xml_obj=simplexml_load_string($xml_string);
var_dump($xml_obj);
switch($data->period){
    case 0:
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
	break;
    case 1:
      for($j=0;$j<count($xml_obj->status);$j++){
		if($xml_obj->status[$j]->submission_date > ($data->starttime)*1000){
      	  		$str=(string)$xml_obj->status[$j]->status;
			$str = preg_replace('/(\s|　)/','',$str);
    	  		if($str == "Accepted"){
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
    	  		$str = preg_replace('/(\s|　)/','',$str);
    	  		if($str == "Accepted"){
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
    	  		$str = preg_replace('/(\s|　)/','',$str);
    	  		if($str == "Accepted"){
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
$sum=0;
for($i=0;$i<10;$i++){
if($check[$i]==="○"){
  $select="point_".(string)($i+1);
  $sum+=$data->$select;
}
}
print_r($check);
$id=$DB->get_record('aojproblemlist', array('userid' => $USER->username), '*');
if(empty($data2)){
$input=array(
'id'=>$id->id,
'courseid'=>$cm->instance,
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
'point_sum'=>$sum,
);
$DB->update_record('aojproblemlist', $input);
}else{
$input=array(
'id'=>$data2->id,
'courseid'=>$cm->instance,
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
'point_sum'=>$sum,
);
$DB->update_record('aojproblemlist', $input);
}
$data2=$DB->get_record('aojproblemlist', array('userid' => $USER->username,'courseid'=>$cm->instance), '*');
}
//-------------------------------------以下描画----------------------------------------------------
$data4 = $DB->get_records_sql("SELECT * FROM mdl_aojproblemlist WHERE courseid=".$cm->instance." AND timemodified >=".$data->timemodified." ORDER BY point_sum DESC LIMIT 10");
$key=array_keys($data4);
$num=$cm->instance;
$a='a';
$problemcount=0;
for($s=0;$s<10;$s++){
$aa='problem_'.$a++;
if(empty($data->$aa)){
break;
}
$problemcount+=1;
}
$width=$problemcount*80+200;
echo $OUTPUT->heading("<html><head><meta charset=\"utf-8\">
<link rel=\"stylesheet\" href=\"$CFG->wwwroot/mod/aojtools/style.css\" type=\"text/css\">
<link rel=\"stylesheet\" href=\"//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css\" type=\"text/css\"/>
<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js\" type=\"text/javascript\"></script>
<script src=\"//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js\"></script>
<script type=\"text/javascript\" language = \"javascript\">
$(function() {
    			$(\"#dbinput\").click(function(){
				var problemnum = document.update.problem_num.options[document.update.problem_num.selectedIndex].value;
				$.ajax({
            				type: \"POST\",
            				url: \"$CFG->wwwroot/mod/aojtools\"+\"/p_dbupdate.php\",
            				data: {
						\"renewal\": \"$num\",
						\"problemnum\": problemnum,
            				},
            				success: function(j_data){
						alert(problemnum+\"を更新しました\")
						location.reload()
					}
        			});
    			});

    			$(\"#dbaoj\").click(function(){
				var aojid = document.forms.dbupdata.indata.value;
				$.ajax({
            				type: \"POST\",
            				url: \"$CFG->wwwroot/mod/aojtools\"+\"/u_dbupdate.php\",
            				data: {
						\"aojid\": aojid,
            				},
            				success: function(j_data){
						alert(aojid+\"を登録しました\")
					}
        			});
    			});
		});</script></head><body>
");
//---------------------------AOJID確認-----------------------------------
echo $OUTPUT->heading("<form id=\"dbupdata\" name=\"dbupdata\">");
if(!isset($data3->aojid)){
echo $OUTPUT->heading("<div>使用するAOJのID　<INPUT type=\"text\" id=\"indata\"></div>");
echo $OUTPUT->heading("</form><div id=\"aaa\"><input type=\"submit\" id=\"dbaoj\" value=\"AOJIDを登録する\" name=\"dbaoj\"></div>");
}
else{
echo $OUTPUT->heading("使用するAOJのID\"$data3->aojid\"");
echo $OUTPUT->heading("<div>変更するAOJのID　<INPUT type=\"text\" id=\"indata\"></div>");
echo $OUTPUT->heading("</form><div id=\"aaa\"><input type=\"submit\" id=\"dbaoj\" value=\"AOJIDを変更する\" name=\"dbaoj\"></div>");
}
//-------------------------------------自分のデータ--------------------------------
echo $OUTPUT->heading("<div id=\"sample\"><h1>自分の結果</h1></div>");
echo $OUTPUT->heading("<div style=\"margin:0px;padding:0px;\" align=\"center\">
<table width=\"".$width."px\" style=\"border-collapse: collapse;border:1px solid #FFFFFF;background-color:#FFFFFF;color:#000000;text-align:left;\">
<tbody><tr><td style=\"border:1px solid #FFFFFF;text-align:left;\">"."name"."</td>");
$a='a';
for($s=0;$s<$problemcount;$s++){
$aa='problem_'.$a++;
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\"><a href=\"http://judge.u-aizu.ac.jp/onlinejudge/description.jsp?id=".$data->$aa."&lang=jp\" target=\"_blank\">".$data->$aa."</a></td>");
}
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">"."合計点数"."</td>");
echo $OUTPUT->heading('</tr>');

echo $OUTPUT->heading("<tr><td style=\"border:1px solid #FFFFFF;text-align:left;\">".$data2->userid."</td>");
$a='a';
for($s=0;$s<$problemcount;$s++){
$aa='problem_'.$a++;
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">".$data2->$aa."</td>");
}
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">".$data2->point_sum."</td>");
echo $OUTPUT->heading('</tr>');
//-------------------------配点------------------------------
echo $OUTPUT->heading("<tr><td style=\"border:1px solid #FFFFFF;text-align:left;\">"."配点"."</td>");
$psum=0;
for($s=0;$s<$problemcount;$s++){
$point="point_".(string)($s+1);
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">".$data->$point."</td>");
$psum+=$data->$point;
}
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">".$psum."</td></tr></tbody></table></div>");
//-------------------------成績上位者------------------------------------
echo $OUTPUT->heading("<h1>成績上位者</h1>");
echo $OUTPUT->heading("<div style=\"margin:0px;padding:0px;\" align=\"center\">
<table width=\"".$width."px\" style=\"border-collapse: collapse;border:1px solid #FFFFFF;background-color:#FFFFFF;color:#000000;text-align:left;\">
<tbody><tr><td style=\"border:1px solid #FFFFFF;text-align:left;\">"."name"."</td>");
$a='a';
for($s=0;$s<$problemcount;$s++){
$aa='problem_'.$a++;
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\"><a href=\"http://judge.u-aizu.ac.jp/onlinejudge/description.jsp?id=".$data->$aa."&lang=jp\" target=\"_blank\">".$data->$aa."</a></td>");
}
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">"."合計点数"."</td>");
echo $OUTPUT->heading('</tr>');
//-------------------------------↑テーブル一段目↓ランキング---------------
for($rank=0;$rank<count($key);$rank++){
//if($data4[$key[$rank]]->point_sum != 0){
echo $OUTPUT->heading("<tr><td style=\"border:1px solid #FFFFFF;text-align:left;\">".$data4[$key[$rank]]->userid."</td>");
$a='a';
for($s=0;$s<$problemcount;$s++){
$aa='problem_'.$a++;
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">".$data4[$key[$rank]]->$aa."</td>");
}
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">".$data4[$key[$rank]]->point_sum."</td>");
echo $OUTPUT->heading('</tr>');
}
if($rank>=9)
 break;
//}
//-------------------------配点------------------------------
echo $OUTPUT->heading("<tr><td style=\"border:1px solid #FFFFFF;text-align:left;\">"."配点"."</td>");
for($s=0;$s<$problemcount;$s++){
$point="point_".(string)($s+1);
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">".$data->$point."</td>");
}
echo $OUTPUT->heading("<td style=\"border:1px solid #FFFFFF;text-align:left;\">".$psum."</td></tr></tbody></table></div>");
//------------------------------更新ボタン---------------------------------------------------------
echo $OUTPUT->heading("<FORM NAME=\"update\" id=\"updata\"><SELECT name=\"problem_num\">");

$a='a';
for($i=0; $i<$problemcount; $i++){
$aa='problem_'.$a++;
echo $OUTPUT->heading("<OPTION value=".$data->$aa.">".$data->$aa."</OPTION>");
}
echo $OUTPUT->heading("</SELECT></FORM>");

echo $OUTPUT->heading("<input type=\"submit\" id=\"dbinput\" value=\"自分の結果を更新する\" name=\"renewal\"></body></html>");
//-------------------------------------------------------------------------------------------------
//time()でタイムスタンプ AOJはミリ秒で保存しているため*1000
//---------------------------------------------------------------------------------------------------------------------
// Finish the page
echo $OUTPUT->footer();
