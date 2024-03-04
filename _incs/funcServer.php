<?php
function dowloadfile($path,$file) {
	$result_row = array();
	if(preg_match('/^[^.][-a-z0-9_.]+[a-z]$/i', $file)){
		$filepath = $path."/".$file;
		if(file_exists($filepath)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			$rf = readfile($filepath);
		}
	}
	die();
}
function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
, $_SERVER["HTTP_USER_AGENT"]);
}
function uploadfileimagefixtargetwidth($srcfile,$folder,$prefix,$targetwidth,$thumnail_flag,$filename_flag) {
	$ext = strtolower(pathinfo($_FILES[$srcfile]['name'], PATHINFO_EXTENSION));
	if($ext != "") {
		switch ($ext) {
			case "png":
				$type = "png";
				break;			
			case "jpg";
				$type = "jpg";
				break;	
			case "jpeg";
				$type = "jpg";
				break;
			default:
				$type = $ext;				
				break;
		}
	} else {
		$type = "jpg";	
	}	
	
	if ($filename_flag == "USE_IMAGE_FILENAME") {
		//$name = $prefix."_".trim($ext[0]);
		$name = $prefix;
	}
	else { //RANDOM FILE NAME
		$firstpicname =  $prefix."_".date("ymd_his");
		$middlename = rand_str();	
		$name = $firstpicname.'_'.$middlename;
	}
	$name = trim($name);
	$fullname = $name.".".$type;
	$thumname = "thum_".$name.".".$type;

	$destupload = $folder."/".$fullname; 
	$destthum = $folder."/".$thumname;
	
	if (move_uploaded_file($_FILES[$srcfile]['tmp_name'], $destupload)) { 
		$getImageInfo = getimagesize($destupload);
		$actual_image_width = $getImageInfo[0];
		$actual_image_height = $getImageInfo[1];
		
		if ($type=="png" || $type=="jpg" || $type=="jpeg" || $type=="bmp") {
			if (strtolower($thumnail_flag)=="thumnail") {
				resizess($destupload,$destthum,150,150); 
				if (isMobileDevice()) {
					if ($actual_image_width > $actual_image_height) {
						$filename = $destthum;
						$source = imagecreatefromjpeg($filename);
						$degrees = -90;
						// Rotate
						$rotate = imagerotate($source, $degrees, 0);
						//and save it on your server...
						imagejpeg($rotate, $filename,100);	
					}
				}
			}
			if ($actual_image_width > $targetwidth) {
				$percent = (100 * $targetwidth / $actual_image_width);
				$h = $actual_image_height * $percent / 100;
				resizess($destupload,$destupload,$targetwidth,$h);
				if (isMobileDevice()) {
					if ($actual_image_width > $actual_image_height) {
						$filename = $destupload;
						$source = imagecreatefromjpeg($filename);
						$degrees = -90;
						// Rotate
						$rotate = imagerotate($source, $degrees, 0);
						//and save it on your server...
						imagejpeg($rotate, $filename,100);	
					}
				}
			}
		}
		return $fullname;
	} else {
		parseError("File upload failed");
	}
}
function getnext_parent_id($plant,$parent_tb,$conn) {
	//plant = 3digit
	//123456789012345678
	//NE1-JRB-2110-00001
	//NK1-JRB-2110-00001
	//NK2-JRB-2110-00001
	//HK1-JRB-2110-00001
	$engym = date('ym');
	
	switch (strtolower($parent_tb)) {
		case "jrb_hist":
			$ym = strtoupper($plant)."-JRB-".$engym;
			$sql = "select max(substring(jrb_id,14,5)) as nbr from jrb_hist where substring(jrb_id,1,12) = '$ym' and jrb_plant = '$plant'";
			break;
		case "jrp_hist":
			$ym = strtoupper($plant)."-JRP-".$engym;
			$sql = "select max(substring(jrp_id,14,5)) as nbr from jrp_hist where substring(jrp_id,1,12) = '$ym' and jrp_plant = '$plant'";
			break;
		case "tag_hist":
			$ym = strtoupper($plant)."-TAG-".$engym;
			$sql = "select max(substring(tag_id,14,5)) as nbr from tag_hist where substring(tag_id,1,12) = '$ym' and tag_plant = '$plant'";
			break;
		case "pmh_hist":
			$ym = strtoupper($plant)."-PMH-".$engym;
			$sql = "select max(substring(pmh_id,14,5)) as nbr from pmh_hist where substring(pmh_id,1,12) = '$ym' and pmh_plant = '$plant'";
			break;
	}
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$parent_id = $ym."-".substr("00000{$next_numner}", -5);
	return $parent_id;
}

function getnext_det_id($plant,$parent_id,$det_tb,$conn) {
	//plant = 3digit (NE1,NK1,NK2,HK1)
	//parent_id = jrx_id
	//detail_name = jrp,jrb,tag
	//12345678901234567890123
	//NE1-JRB-2110-00001-0001
	//NK1-JRB-2110-00001-0001
	//NK2-JRB-2110-00001-0001
	//HK1-JRB-2110-00001-0001
	
	switch (strtolower($det_tb)) {
		//JRB
		case "jrb_eng_det":
			$sql = "select max(substring(jrb_eng_id,20,4)) as nbr from jrb_eng_det where jrb_eng_jrb_id = '$parent_id' and jrb_eng_plant = '$plant'";
			break;
		case "jrb_exp_det":
			$sql = "select max(substring(jrb_exp_id,20,4)) as nbr from jrb_exp_det where jrb_exp_jrb_id = '$parent_id' and jrb_exp_plant = '$plant'";
			break;
		case "jrb_wkd_det":
			$sql = "select max(substring(jrb_wkd_id,20,4)) as nbr from jrb_wkd_det where jrb_wkd_jrb_id = '$parent_id' and jrb_wkd_plant = '$plant'";
			break;
		//JRP
		case "jrp_eng_det":
			$sql = "select max(substring(jrp_eng_id,20,4)) as nbr from jrp_eng_det where jrp_eng_jrp_id = '$parent_id' and jrp_eng_plant = '$plant'";
			break;
		case "jrp_exp_det":
			$sql = "select max(substring(jrp_exp_id,20,4)) as nbr from jrp_exp_det where jrp_exp_jrp_id = '$parent_id' and jrp_exp_plant = '$plant'";
			break;
		case "jrp_wkd_det":
			$sql = "select max(substring(jrp_wkd_id,20,4)) as nbr from jrp_wkd_det where jrp_wkd_jrp_id = '$parent_id' and jrp_wkd_plant = '$plant'";
			break;
		//TAG
		case "tag_eng_det":
			$sql = "select max(substring(tag_eng_id,20,4)) as nbr from tag_eng_det where tag_eng_tag_id = '$parent_id' and tag_eng_plant = '$plant'";
			break;
		case "tag_exp_det":
			$sql = "select max(substring(tag_exp_id,20,4)) as nbr from tag_exp_det where tag_exp_tag_id = '$parent_id' and tag_exp_plant = '$plant'";
			break;
		case "tag_wkd_det":
			$sql = "select max(substring(tag_wkd_id,20,4)) as nbr from tag_wkd_det where tag_wkd_tag_id = '$parent_id' and tag_wkd_plant = '$plant'";
			break;
	}
	
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$det_id = $parent_id."-".substr("0000{$next_numner}", -4);
	return $det_id;
}

function jrb_engmember($jrb_id,$conn) {
	$eng_member = "";
	$sql_eng = "select jrb_eng_emp_code from jrb_eng_det where jrb_eng_jrb_id = '$jrb_id' order by jrb_eng_emp_code";																																																								
	$result_eng = sqlsrv_query( $conn, $sql_eng,$params);
	while($rec_eng = sqlsrv_fetch_array($result_eng, SQLSRV_FETCH_ASSOC)) {
		$eng_code = trim($rec_eng['jrb_eng_emp_code']);
		if (!empty($eng_code)) {
			if (!inlist($eng_member,$eng_code)) {
				if ($eng_member != "") {$eng_member .= ",";}
				$eng_member .= $eng_code;
			}
		}
	}
	return $eng_member;
}
function jrp_engmember($jrp_id,$conn) {
	$eng_member = "";
	$sql_eng = "select jrp_eng_emp_code from jrp_eng_det where jrp_eng_jrp_id = '$jrp_id' order by jrp_eng_emp_code";																																																								
	$result_eng = sqlsrv_query( $conn, $sql_eng,$params);
	while($rec_eng = sqlsrv_fetch_array($result_eng, SQLSRV_FETCH_ASSOC)) {
		$eng_code = trim($rec_eng['jrp_eng_emp_code']);
		if (!empty($eng_code)) {
			if (!inlist($eng_member,$eng_code)) {
				if ($eng_member != "") {$eng_member .= ",";}
				$eng_member .= $eng_code;
			}
		}
	}
	return $eng_member;
}
function tag_engmember($tag_id,$conn) {
	$eng_member = "";
	$sql_eng = "select tag_eng_emp_code from tag_eng_det where tag_eng_tag_id = '$tag_id' order by tag_eng_emp_code";																																																								
	$result_eng = sqlsrv_query( $conn, $sql_eng,$params);
	while($rec_eng = sqlsrv_fetch_array($result_eng, SQLSRV_FETCH_ASSOC)) {
		$eng_code = trim($rec_eng['tag_eng_emp_code']);
		if (!empty($eng_code)) {
			if (!inlist($eng_member,$eng_code)) {
				if ($eng_member != "") {$eng_member .= ",";}
				$eng_member .= $eng_code;
			}
		}
	}
	return $eng_member;
}
function cal_dt_diff($start_dt,$end_dt,$fmt0) {
	//$start_dt = '2012-07-10 00:00:00'
	//$end_dt = '2012-07-12 00:00:00'
	//fmt0 DHM,D,H,M,HM,HH,MM,H.M,TM
	$start_date = new DateTime($start_dt);
	$since_start = $start_date->diff(new DateTime($end_dt));
	$mins = $since_start->days * 24 * 60;
	$mins += $since_start->h * 60;
	$mins += $since_start->i;
	
	$fmt = strtoupper($fmt0);
    if (is_numeric($mins)) {
		switch ($fmt) {
			case "TM":
				$r_value = $mins;
			case "DHM":
			case "D":
			case "H":
			case "M":
				$mpday = 1440;
				$rday = (int)($mins/$mpday); 		//$rday = ($mins \ $mpday)
				$rhor = (int)(($mins % $mpday)/60); //($mins % $mpday) \ 60
				$rmin = ($mins % 60);				//($mins mod 60)
				$rhor = substr("00{$rhor}", -2);
				$rmin = substr("00{$rmin}", -2);
				switch ($fmt) {	
					case "D": $r_value = $rday; break;
					case "H": $r_value = $rhor; break;
					case "M": $r_value = $rmin; break;
					case "DHM": $r_value = "$rday:$rhor:$rmin"; break;
				}
				break;
			case "HM":
			case "HH":
			case "MM":
			case "H.M":
				$rhh = (int)($mins/60); //(mins \ 60)
				$rmm = ($mins % 60); 	//(mins Mod 60)
				$rhh = substr("00{$rhh}", -2);
				$rmm = substr("00{$rmm}", -2);
				switch ($fmt) {	
					case "HM": $r_value = "$rhh$rmm"; break;
					case "HH": $r_value = $rhh; break;
					case "MM": $r_value = $rmm; break;
					case "H.M" : $r_value = "$rhh.$rmm"; break;
				}
				break;
		}
	}
	return $r_value;
}
function get_jrb_sys_nbr($plant,$conn) {
	//HK-BYYMM-0001
	//N1-BYYMM-0001
	//N2-BYYMM-0001
	//IE-BYYMM-0001
	//IE-B2108-0002
	switch ($plant) {
		case "HK": $jrb_plant = "HK"; break;
		case "NKIE": $jrb_plant = "IE"; break;
		case "NK1": $jrb_plant = "N1"; break;
		case "NK2": $jrb_plant = "N2"; break;
	}
	$pym = $jrb_plant."-B".date('ym');
	$sql = "select max(substring(jrb_sys_nbr,10,4)) as nbr from jrb_hist where substring(jrb_sys_nbr,1,8) = '$pym' and jrb_plant = '$plant'";
	
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$jrb_sys_nbr = $pym."-".substr("0000{$next_numner}", -4);
	return $jrb_sys_nbr;
}
function get_pmj_nbr($conn) {
	//YYMM0001
	$ym = date('ym');
	$sql = "select max(substring(id,4,4)) as nbr from pmj_breakdown_hist where substring(id,0,4) = '$ym'";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$pmj_id = $ym.substr("0000{$next_numner}", -4);
	return $pmj_id;
}
function caldate($dt,$interval,$fmt) {
    //$dt = dd-mm-yyyy
    //$dt = yyyymmdd
    //$dt = dd/mm/yyyy
    $it = strtoupper($interval);
    if (strlen($dt) == 8) {
        $dt01 = substr($dt,6,2)."-".substr($dt,4,2)."-".substr($dt,0,4);
    }
    if (strlen($dt) == 10) {
        $dt01 = str_replace("/","-",$dt);
    }
    $it = str_replace("D"," day",$it);
    $it = str_replace("W"," week",$it);
    $it = str_replace("M"," month",$it);
    $it = str_replace("Y"," year", $it);
    return date($fmt, strtotime($it, strtotime($dt01)));
}
function calpmperiod($start_date,$fq_value,$fq_unit,$cal_type,$skip_sunday,$conn) {
	//$start_date = dd-mm-yyyy
    //$start_date = yyyymmdd
    //$start_date = dd/mm/yyyy
	//----  skip_sunday - หมายถึง ย้อนหลัง, 0 หมายถึง เอาวันอาทิตย์ด้วย, + หมายถึง เดินไปข้างหน้า
	if ($cal_type=="NEXT") { 
		$goto_date = caldate($start_date,$fq_value.$fq_unit,"Y-m-d");				
	}
	if ($cal_type=="PREV") {
		$goto_date = caldate($start_date,($fq_value*-1).$fq_unit,"Y-m-d");			
	}

	if ($goto_date != "") {
		$weekday = date('N', strtotime($goto_date));
		if ($weekday == 7) { //Sunday
			if ($fq_value == "1" and $fq_unit == "D") {
				//-- กรณีที่กำหนดความถี่เป็น 1 วันและวันถัดไปเป็นวันอาทิตย์
				//-- ถ้าไม่ +1 ระบบก็จะไม่สามารถเลื่อนวันให้ได้เนื่องจากจะได้วันเดิม
				$goto_date = caldate(str_replace("-","",$goto_date),"1D","Y-m-d");
			} else	{
				$goto_date = caldate(str_replace("-","",$goto_date),$skip_sunday."D","Y-m-d");
			}
		}
		return str_replace("-","",$goto_date);	
	} else {
		return "";
	}	
}

function getlast($pmh_mc_code,$pmh_plant,$pmh_type,$pmh_line,$conn) {
	$sql = "SELECT max(pmh_date) 'lastdate' from pmh_hist".
		" WHERE pmh_mc_code = '$pmh_mc_code' and ".
		" pmh_plant = '$pmh_plant' and " .
		" pmh_type = '$pmh_type' and " .
		" pmh_line = '$pmh_line'";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
	if ($row) {
		$last_date = $row['lastdate'];
		if (!is_null($last_date)) {
			return $last_date;
		} else {
			return "";	
		}
	} else {
		return "";
	}
	//return yyyymmdd
}
function getprev($pmh_mc_code,$pmh_plant,$pmh_type,$pmh_line,$pmh_last_date,$conn) {
	$sql = "SELECT max(pmh_date) 'prevdate' FROM pmh_hist".
		" WHERE pmh_mc_code = '$pmh_mc_code' and ".
		" pmh_plant = '$pmh_plant' and ".
		" pmh_type = '$pmh_type' and ".
		" pmh_line = '$pmh_line' and ".
		" pmh_date < '$pmh_last_date'";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
	if ($row) {
		$prev_date = $row['prevdate'];
		if (!is_null($prev_date)) {
			return $prev_date;
		} else {
			return "";	
		}
	} else {
		return "";
	}
	//return yyyymmdd
}

function caltime($begtime0,$endtime0) {
	if (istime($begtime0) && istime($endtime0)) {
		$begtime = substr($begtime0,0,2).substr($begtime0,3,2);
		$endtime = substr($endtime0,0,2).substr($endtime0,3,2);
		return substr(timediff($begtime,$endtime),0,2).".".substr(timediff($begtime,$endtime),2,2);
	} else {
		return "";
	}
}
function istimefmt($stime) {						
	if (strlen($stime) != 5) {
		return false;	
	}		
	$h  = substr($stime,0,2);
	$d = substr($stime,2,1);
	$m  = substr($stime,3,2);
	
	if(!is_numeric($h) || !is_numeric($m)) {
		return false;
	}
	$h = (int)($h);
	$m = (int)($m);
	if ($h<0) {
		return false;
	}
	if ($d!=".") {
		return false;
	}
	if ($m<0 || $m>59) {
		return false;
	}
	return true;
}
function istime($stime) {						
	if (strlen($stime) != 5) {
		return false;	
	}		
	$h  = substr($stime,0,2);
	$d = substr($stime,2,1);
	$m  = substr($stime,3,2);
	
	if(!is_numeric($h) || !is_numeric($m)) {
		return false;
	}
	$h = (int)($h);
	$m = (int)($m);
		
	if ($h<0 || $h>24) {
		return false;
	}
	if ($d!=".") {
		return false;
	}
	if ($h<24) {
		if ($m<0 || $m>59) {
			return false;
		}
		return true;
	}
	else {
		if ($m>0) {
			return false;
		}
	}
}
function subtimes($begtime,$endtime) {
	$beg_ning = $begtime;
	$end_ding = $endtime;
	$beg_hour = substr($beg_ning,0, 2);
	$beg_mins = substr($beg_ning,2, 2);
	$end_hour = substr($end_ding,0, 2);
	$end_mins = substr($end_ding,2, 2);
	
	$cntmins = 0;
	do {
		$beg_mins = (int)($beg_mins) + 1;
		$cntmins++;        
		if ((int)($beg_mins) == 60) {
		   $beg_hour = (int)($beg_hour) + 1;          
		   $beg_mins = 0;
		}
		$beg_hour = substr("00{$beg_hour}", -2);
		$beg_mins = substr("00{$beg_mins}", -2);
		$beg_ning = $beg_hour . $beg_mins;
	}
	while ($beg_ning < $end_ding);
	
	$rhh = (int)($cntmins/60);
	$rmm = $cntmins % 60;
	$rhh = substr("00{$rhh}", -2);
	$rmm = substr("00{$rmm}", -2);
	return $rhh.$rmm;
}
function inctimes($beg_hour_mins,$incvalue) {
	$beg_hour = (int)substr($beg_hour_mins,0,2);
	$beg_mins = (int)substr($beg_hour_mins,2,2);
	$incmins = (int)(substr($incvalue,0,2)) * 60 + (int)(substr($incvalue,2,2));

	$mins_all=0;   
	do {
		$beg_mins++;        
		$mins_all++;        
		if ($beg_mins == 60) {
		   $beg_hour++;           
		   $beg_mins = 0;
		}
	}
	while ($mins_all < $incmins);
	         
	$beg_hour = substr("00{$beg_hour}", -2);
	$beg_mins = substr("00{$beg_mins}", -2);
	return $beg_hour.$beg_mins;
}
function timediff($begt,$endt) {
	if ($begt > $endt) {		
		return inctimes(subtimes($begt,"2400"),subtimes("0000",$endt));
	} else {
		if ($begt == $endt) {
			return "2400";
		} else {
			return subtimes($begt,$endt);
		}
	}
}
function cmins2days($mins, $fmt0) {
    //fmt0 DHM,D,H,M,HM,HH,MM,H.M
	$r_value = "error";
	$fmt = strtoupper($fmt0);
    if (is_numeric($mins)) {
		switch ($fmt) {
			case "DHM":
			case "D":
			case "H":
			case "M":
				$mpday = 480;
				$rday = (int)($mins/$mpday); 		//$rday = ($mins \ $mpday)
				$rhor = (int)(($mins % $mpday)/60); //($mins % $mpday) \ 60
				$rmin = ($mins % 60);				//($mins mod 60)
				$rhor = substr("00{$rhor}", -2);
				$rmin = substr("00{$rmin}", -2);
				switch ($fmt) {	
					case "D": $r_value = $rday; break;
					case "H": $r_value = $rhor; break;
					case "M": $r_value = $rmin; break;
					case "DHM": $r_value = "$rday:$rhor:$rmin"; break;
				}
				break;
			case "HM":
			case "HH":
			case "MM":
			case "H.M":
				$rhh = (int)($mins/60); //(mins \ 60)
				$rmm = ($mins % 60); 	//(mins Mod 60)
				$rhh = substr("00{$rhh}", -2);
				$rmm = substr("00{$rmm}", -2);
				switch ($fmt) {	
					case "HM": $r_value = "$rhh$rmm"; break;
					case "HH": $r_value = $rhh; break;
					case "MM": $r_value = $rmm; break;
					case "H.M" : $r_value = "$rhh.$rmm"; break;
				}
				break;
		}
	}
	return $r_value;
}
function space($n) {
	$spacetext = "";
	for ($i = 0; $i < $n ; $i++) {
		$spacetext .= "&nbsp;";
	}	
	return $spacetext;
}

//NEW For XXS Prevention
function mssql_escape($s) {
	$v = trim($s);
	$v = htmlspecialchars($v,ENT_QUOTES,'UTF-8'); //จะแปลงทั้ง double และ single quote
	$v = htmlspecialchars_decode($v); //จะแปลงเฉพาะ double quote
	$v = str_replace("&#039;","''",$v);
	$v = str_replace("--","",$v);
	$v = filter_var($v, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	return $v;
}
function html_escape($value) {
	$v = (string)$value;
	if (is_null($v) || trim($v) == "") {
		return "";
	}
	else {
		$v = htmlspecialchars($v,ENT_QUOTES,'UTF-8'); //จะแปลงทั้ง double และ single quote
		return trim($v);
	}
}
function html_clear($value) {
	$v = (string)$value;
	if (is_null($v) || trim($v) == "") {
		return "";
	}
	else {
		$v = htmlspecialchars($v,ENT_QUOTES,'UTF-8'); //จะแปลงทั้ง double และ single quote
		return trim($v);
	}
}
function html_quot($q) {
	if (is_null($q)) {$o="";}
	else {
		if (is_numeric($q)) {$o=$q;}
		else {$o = str_replace('"',"&quot;",trim($q));}
	}
	return $o;
}

function number_fmt($num,$decimals = 2,$thousands_sep = ",",$fixdecimal = false) {
	if (!empty($num)) {
		if (((double)abs($num) - (int)abs($num))> 0.00) {
			return number_format($num,$decimals,".",$thousands_sep);
		} else {
			if ($fixdecimal) {
				return number_format($num,$decimals,".",$thousands_sep);
			}else {
				return number_format($num,0,".",$thousands_sep);
			}
		}
	}
	else {
		return "0";
	}
}
function number_fmtb($num,$decimals = 2,$thousands_sep = ",",$fixdecimal = false) {
	if (!empty($num)) {
		if (((double)$num - (int)$num) > 0.00) {
			return number_format($num,$decimals,".",$thousands_sep);
		} else {
			if ((double)$num == 0.00) {
				return "";
			} else {
				if ($fixdecimal) {
					return number_format($num,$decimals,".",$thousands_sep);
				}else {
					return number_format($num,0,".",$thousands_sep);
				}
			}
		}
	}
	else {
		return "";
	}
}
function number_fmtc($num) {
	if (!empty($num)) {
		return number_format($num,2);
	}
	else {
		return "";
	}
}
function CheckandShowNumber($Val,$Digit){
		$Num =$Val;	
		if(trim($Num !="") or $Num !== NULL)
		{
			if($Num==0 or $Num=='0')
			$CheckandShowNumber = "";	
			else
			$CheckandShowNumber = number_format($Num,$Digit);	
		}
		else{
			$CheckandShowNumber = "";
		}
		return $CheckandShowNumber;
	}
function CheckandShowDate($date){
		$excel_date =$date;	
		if(trim($excel_date !="") or $excel_date !== NULL)
		{
			$CheckandShowDate = date_format($excel_date,"d/m/Y");	
			if($CheckandShowDate == "01/01/1900" or $CheckandShowDate == "00/01/1900")
			{
				$CheckandShowDate = "";
			}		
		}
		else{
			$CheckandShowDate = "";
		}
		return $CheckandShowDate;
	}
function ymdsql($strdate) {
	//get from format dd/mm/yyyy
	$d = substr($strdate,0,2);
	$m = substr($strdate,3,2);
	$y = substr($strdate,6,4);
	return $y."-".$m."-".$d;
}

function getnewid($id, $table, $conn) {
	$sql = "select max($id) as id from " . $table;		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {
		return 1;
	}
	else {
		return $row["id"] + 1;
	}
}

function getnewseq($seq, $table, $conn) {
	$sql = "select max($seq) as seq from " . $table;		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {
		return 1;
	}
	else {
		return $row["seq"] + 1;
	}
}
function getnewseqbycon($seq,$table,$condition,$conn) {
	$sql = "select max($seq) as seq from " . $table . " where " . $condition;
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {
		return 1;
	}
	else {
		return $row["seq"] + 1;
	}
}
function getmaxseqbycon($seq,$table,$condition,$conn) {
	$sql = "select max($seq) as seq from " . $table . " where " . $condition;
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {
		return 0;
	}
	else {
		if (!is_null($row["seq"])) {
			return $row["seq"];
		}
		else {
			return 0;	
		}
	}
}

function cntdetailbyheader($detail_table,$condition,$conn) {
	$sql = "SELECT count(*) 'cnt_record' FROM $detail_table WHERE $condition";
	$result = sqlsrv_query($conn, $sql); 
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		$total_record = (int)$row['cnt_record'];
	}
	else {
		$total_record = 0;
	}	
	return $total_record;
}
function sumdetailbyheader($detail_table,$detail_field,$condition,$conn) {
	$sql = "SELECT sum($detail_field) 'sum_record' FROM $detail_table WHERE $condition";
	$result = sqlsrv_query($conn, $sql); 
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		$sumdata = (double)$row['sum_record'];
	}
	else {
		$sumdata = 0;
	}	
	return $sumdata;
}
function inlist($pattern,$astr) {
    $xpattern = $pattern;
	if (strpos($xpattern,",")) {	
	   while (strpos($xpattern,",")) {		   
	      $pos = strpos($xpattern,",",0);		  
		  $stmt = substr($xpattern,0,$pos);		  
		  if ($stmt == $astr) {			 
			 return true;			 
		  }
		  $xpattern = substr($xpattern,$pos + 1,strlen($xpattern));		  
	   }
	}	
	if ($xpattern == $astr) {		
		return true;
	}	
	else {		
		return false;
	}	
}
function genzero($id,$digit,$lr) {
	if ($lr == "l" or $lr == "L") {
		return str_pad($id,$digit,"0",STR_PAD_LEFT);
	}
	else {
		return str_pad($id,$digit,"0",STR_PAD_RIGHT);
	}	
}
function findsqlval($table, $selectfield, $wfield, $vfield,$conn) {
	if (isset($vfield) && trim($vfield) != "" && !is_null($vfield) ) {
	//if (isset($vfield)) {
		$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wfield . "=" . "'" . $vfield . "'";		
		$result = sqlsrv_query($conn, $sql);	
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if (!$row) {
			return "";
		}
		else {
			return $row["fvalue"];
		}
	}
	else {return "";}	
}

function findsqlval_aut($table, $selectfield, $wfield, $vfield,$conn) {
	if (isset($vfield) && trim($vfield) != "" && !is_null($vfield) ) {
		$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wfield . "=" . "'" . $vfield . "' and author_active='1' ORDER BY author_id ASC";		
		$result = sqlsrv_query($conn, $sql);	
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if (!$row) {
			return "";
		}
		else {
			return $row["fvalue"];
		}
	}
	else {return "";}	
}


function findsqlval_1($table, $selectfield, $wfield, $vfield,$conn) {
	if (isset($vfield) && trim($vfield) != "" && !is_null($vfield) ) {
	//if (isset($vfield)) {
		$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wfield . "=" . "'" . $vfield . "' and author_email_status='1' ORDER BY author_id ASC";		
		$result = sqlsrv_query($conn, $sql);	
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if (!$row) {
			return "";
		}
		else {
			return $row["fvalue"];
		}
	}
	else {return "";}	
}

function findsqlval_zc($table, $selectfield, $wfield, $vfield, $wfield1, $vfield1,$conn) {
	if (isset($vfield) && trim($vfield) != "" && !is_null($vfield) ) {
	//if (isset($vfield)) {
		$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wfield . "=" . "'" . $vfield . "' and " . $wfield1 . "=" . "'" . $vfield1 . "' and author_email_status='1' and account_group='ZC01' ORDER BY author_id desc";		

		$result = sqlsrv_query($conn, $sql);	
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if (!$row) {
			return "";
		}
		else {
			return $row["fvalue"];
		}
	}
	else {return "";}	
}
function findsqlval_dr($table, $selectfield, $wfield, $vfield, $wfield1, $vfield1,$conn) {
	if (isset($vfield) && trim($vfield) != "" && !is_null($vfield) ) {
	//if (isset($vfield)) {
		$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wfield . "=" . "'" . $vfield . "' and " . $wfield1 . "=" . "'" . $vfield1 . "' and author_email_status='1' and account_group<>'ZC01' ORDER BY author_id desc";		

		$result = sqlsrv_query($conn, $sql);	
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if (!$row) {
			return "";
		}
		else {
			return $row["fvalue"];
		}
	}
	else {return "";}	
}
function findsqlvalfirst($table, $selectfield, $wfield, $vfield, $conn) {
		if (isset($vfield)) {
			$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wfield . "=" . "'" . $vfield . "' and author_email_status='1' and account_group='ZC01' ORDER BY author_id ASC";		
			$result = sqlsrv_query($conn, $sql);	
			$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
			if (!$row) {
				return "";
			}
			else {
				return $row["fvalue"];
			}
		}
		else {return "";}	
	}
function findsqlvallast($table, $selectfield, $wfield, $vfield,$conn) {
		if (isset($vfield)) {
			$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wfield . "=" . "'" . $vfield . "' and author_email_status='1' and account_group='ZC01' ORDER BY author_id DESC";		
			$result = sqlsrv_query($conn, $sql);	
			$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
			if (!$row) {
				return "";
			}
			else {
				return $row["fvalue"];
			}
		}
		else {return "";}	
	}
Function findsqlvalbycon($table, $selectfield, $wcondition,$conn) {
	if (isset($wcondition)) {		
		$sql = "SELECT " . $selectfield . " AS fvalue FROM " . $table . " Where " . $wcondition;		
		$result = sqlsrv_query($conn, $sql);
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if (!$row) {
			return "";
		}
		else {
			return $row["fvalue"];
		}		
	}
}
function day($date) {return date("d",strtotime($date));}
function month($date) {return date("m",strtotime($date));}
function year($date) {return date("Y",strtotime($date));}
function today() {return date("d/m/Y");}
function dmydb($dbdate,$y) {
	if (!is_null($dbdate)) {
		//get from format in db
		if ($y=='Y') {
			return date_format($dbdate,'d/m/Y');
		} else {
			return date_format($dbdate,'d/m/y');
		}
	} else {return "";}
}
function dmyhmsdb($dbdate,$y) {
	if (!is_null($dbdate)) {
		//get from format in db
		if ($y=='Y') {
			return date_format($dbdate,'d/m/Y H:i:s');
		} else {
			return date_format($dbdate,'d/m/y H:i:s');
		}
	} else {return "";}
}
function cnvtodmyhmsdb($dbdate,$y) {
	if (!is_null($dbdate)) {
		//get from format in db
		if ($y=='Y') {
			return date_format($dbdate,'Y-m-d H:i:s');
		} else {
			return date_format($dbdate,'y-m-d H:i:s');
		}
	} else {return null;}
}

function dmytx($txdate) {
	if ($txdate!="") {
		//get from format yyyymmdd
		$d = substr($txdate,6,2);
		$m = substr($txdate,4,2);
		$y = substr($txdate,0,4);	
		return $d . "/" . $m . "/" . $y;
	} else {
		return "";
	}
}
function dmyty($txdate) {
	if ($txdate!="") {
		//get from format yyyymmdd
		$d = substr($txdate,6,2);
		$m = substr($txdate,4,2);
		$y = substr($txdate,2,2);	
		return $d . "/" . $m . "/" . $y;
	} else {
		return "";
	}
}
function ymd($strdate) {
	//get from format dd/mm/yyyy
	$d = substr($strdate,0,2);
	$m = substr($strdate,3,2);
	$y = substr($strdate,6,4);
	return $y . $m . $d;
}

function rand_str($length = 4, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') {
	$chars_length = (strlen($chars) - 1);
	$string = $chars{rand(0, $chars_length)};
	for ($i = 1; $i < $length; $i = strlen($string))
	{
		$r = $chars{rand(0, $chars_length)};
		if ($r != $string{$i - 1}) $string .=  $r;
	}
	return $string;
}

function getvalue($v) {
	if ($v == "" || $v == null) {
		return 0;
	}
	else {
		return $v;
	}
}

function base64_url_encode($input) {
	//return strtr(base64_encode($input), '+/=', '-_,');
	return base64_encode($input);
}
function base64_url_decode($input) {
	//return base64_decode(strtr($input, '-_,', '+/='));
	return base64_decode($input);
}

function encrypt($string,$txtkey) {
	$encrypt_method = "AES-256-CBC";
    $secret_key = $txtkey;
    $secret_iv = $txtkey;
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	//$output = strtr(base64_encode($output), '+/=', '-_,');
	$output = base64_encode($output);
	return $output;
}
function decrypt($encrypted,$txtkey) {
    $encrypt_method = "AES-256-CBC";
    $secret_key = $txtkey;
    $secret_iv = $txtkey;
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	//$output = strtr(openssl_decrypt(base64_decode($encrypted), $encrypt_method, $key, 0, $iv),'-_,', '+/=');
	$output = openssl_decrypt(base64_decode($encrypted), $encrypt_method, $key, 0, $iv);
	return $output;
}

function mail_attachment($filename_attach, $filename_in_mail,$path, $mail_to, $from_mail, $from_name, $subject, $message) {
	$file = $path.$filename_attach;
	$file_size = filesize($file);
	$handle = fopen($file, "r");
	$content = fread($handle, $file_size);
	fclose($handle);

	$content = chunk_split(base64_encode($content));
	$uid = md5(uniqid(time()));
	$name = basename($file);

	$eol = PHP_EOL;

	$header = "From: ".$from_name." <".$from_mail.">\n";
    $header .= "MIME-Version: 1.0\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\n\n";
    
	$emessage  = "--".$uid."\n";
	$emessage .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $emessage .= "Content-Transfer-Encoding: 7bit\n\n";
    $emessage .= $message."\n\n";
    $emessage .= "--".$uid."\n";
	
	
    $emessage .= "Content-Type: application/octet-stream; name=\"".$filename_in_mail."\"\n"; // use different content types here
    $emessage .= "Content-Transfer-Encoding: base64\n";
    $emessage .= "Content-Disposition: attachment; filename=\"".$filename_in_mail."\"\n\n";
    $emessage .= $content."\n\n";
    $emessage .= "--".$uid."--";
	
	
	
	$subject1 = "=?UTF-8?B?".base64_encode($subject)."?=";
	
    $result = mail($mail_to,$subject1,$emessage,$header);
	if($result) {
		return true;
	} else {
		return false;
	}
	
	//return true;
	/*
	
	$my_file = "2562_07_09-10.pdf";
	$my_path = "d:/appserv/www/testmail/f/";
	$my_name = "Komsun";
	$my_mail = "komsunyu@scg.com";
	$my_replyto = "komsunyu@scg.com";
	$my_subject = "This is a mail with attachment.";
	$my_message = "Hallo,rndo you like this script? I hope it will help.rnrngr. Olaf";
	mail_attachment($my_file, "S19000000011.pdf",$my_path, "komsunyu@scg.com", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);
	*/
}

function getfilename($f) {
	//Return Filename
	if (strrpos($f,"/")) {
		$filename = substr($f,strrpos($f,"/")+1);
	} else {
		$filename = substr($f,strrpos($f,"/"));
	}
	return $filename;
}
function mail_multiattachment($filename_attach,$filename_mail,$mail_to, $from_mail, $from_name, $subject, $message) {
	$to = $mail_to;
    $from = $from_mail; 
	$uid = md5(uniqid(time()));
	$header = "From: ".$from_name." <".$from_mail.">\n";
    $header .= "MIME-Version: 1.0\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\n\n";
	//Mail Message
	$emessage  = "--".$uid."\n";
	$emessage .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$emessage .= "Content-Transfer-Encoding: 7bit\n\n";
	$emessage .= $message."\n\n";
	//Mail Attachment files
	$files = $filename_attach; //FILES ARRAY
    for($x=0;$x<count($files);$x++){	
		if (file_exists($files[$x])) {
			//$f = getfilename($files[$x]);
			//Read Binary File
			$file = fopen($files[$x],"rb");
			$content = fread($file,filesize($files[$x]));
			fclose($file);
			$content = chunk_split(base64_encode($content));
			//Start Attach a File
			$emessage .= "--".$uid."\n";
			$emessage .= "Content-Type: application/octet-stream; name=\"".$filename_mail[$x]."\"\n";
			$emessage .= "Content-Transfer-Encoding: base64\n";
			$emessage .= "Content-Disposition: attachment; filename=\"".$filename_mail[$x]."\"\n\n";
			$emessage .= $content."\n\n";
			///End Attach
		}
    }
	$subject1 = "=?UTF-8?B?".base64_encode($subject)."?=";
    $result = mail($mail_to,$subject1,$emessage,$header);
	if($result) {
		return true;
	} else {
		return false;
	}
}

function mail_normal($from_name,$from_mail,$mail_to,$email_subject,$message) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8"."\r\n";
	$headers .= "From: ".$from_name."<".$from_mail.">"."\r\n";
	$subject1 = "=?UTF-8?B?".base64_encode($email_subject)."?=";
	
	$result = mail($mail_to,$subject1,$message,$headers);
	if($result) {
		return true;
	} else {
		return false;
	}
	
	//return true;
}

function gen_uuid() {
	$uuid = array(
	 'time_low'  => 0,
	 'time_mid'  => 0,
	 'time_hi'  => 0,
	 'clock_seq_hi' => 0,
	 'clock_seq_low' => 0,
	 'node'   => array()
	);

	$uuid['time_low'] = mt_rand(0, 0xffff) + (mt_rand(0, 0xffff) << 16);
	$uuid['time_mid'] = mt_rand(0, 0xffff);
	$uuid['time_hi'] = (4 << 12) | (mt_rand(0, 0x1000));
	$uuid['clock_seq_hi'] = (1 << 7) | (mt_rand(0, 128));
	$uuid['clock_seq_low'] = mt_rand(0, 255);

	for ($i = 0; $i < 6; $i++) {
	  $uuid['node'][$i] = mt_rand(0, 255);
	}

	$uuid = sprintf('%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
	 $uuid['time_low'],
	 $uuid['time_mid'],
	 $uuid['time_hi'],
	 $uuid['clock_seq_hi'],
	 $uuid['clock_seq_low'],
	 $uuid['node'][0],
	 $uuid['node'][1],
	 $uuid['node'][2],
	 $uuid['node'][3],
	 $uuid['node'][4],
	 $uuid['node'][5]
	);
	return $uuid;
}
function day_diff($fromdate,$todate) {
	//para1 yyyymmdd, para2 = yyyymmdd
	$date1 = date_create(substr($fromdate,0,4).'-'.substr($fromdate,4,2).'-'.substr($fromdate,6,2));
	$date2 = date_create(substr($todate,0,4).'-'.substr($todate,4,2).'-'.substr($todate,6,2));
	$interval = $date2->diff($date1);
	return $interval->format('%a');
}
function isdate($date) {
	if (preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/",$date)) {
		return true;
	} else {
		return false;
	}
}
function getFileType($file) {
    $ex = explode(".",$file);
    return $ex[1];
}

function resizess($images,$new_images,$w,$h) 
{
	// *** 1) Initialise / load image
	$resizeObj = new resize($images);
	// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
	$resizeObj -> resizeImage($w, $h, 'auto');
	// *** 3) Save image
	$resizeObj -> saveImage($new_images, 100); 
}
function uploadfileimage($srcfile,$folder,$prefix,$resizeflag) {
	$ext = explode(".",$_FILES[ $srcfile]['name']);		
	if(count($ext)>1) {
		switch (strtolower($ext[1])) {
			case "png":
				$type = "png";
				break;			
			case "jpg";
				$type = "jpg";
				break;	
			case "jpeg";
				$type = "jpg";
				break;
			default:
				$type = strtolower($ext[1]);				
				//parseError("Mime Type must be image/jpeg or image/png");
				break;
		}
	} else {
		$type = "jpg";	
	}	
	$firstpicname =  $prefix."_".date("ymd_his");
	$middlename = rand_str();	
	$name = $firstpicname.'_'.$middlename;
	$name = trim($name);
	
	$fullname = $name.".".$type;
	$thumname = $name."_thum".".".$type;
		
	//$destupload = $uploadPath.$folder."/".$fullname; 
	//$destthum = $uploadPath.$folder."/".$thumname;
	$destupload = $folder."/".$fullname; 
	$destthum = $folder."/".$thumname;

	if (move_uploaded_file($_FILES[$srcfile][ 'tmp_name' ], $destupload)) { 
		if ($type=="png" || $type=="jpg") {
			if (strtolower($resizeflag)=="resize") {
				resizess($destupload,$destthum,200,200);     
			}
		}
		return $fullname;
	} else {
		parseError("File upload failed");
	}
}
function uploadfiledata ($srcfile,$folder,$prefix) {
	//global $uploadPath;
	//$uploadPath = "../_fileupload/"; 	
	$ext = explode(".",$_FILES[$srcfile]['name']);
	//$type = strtolower($ext[1]);
	$sizeof_ext = count($ext);
	$type = "";
	if ($sizeof_ext >= 2) {
		$type = strtolower($ext[$sizeof_ext-1]);
	}
	$firstpicname =  $prefix."_".date("ymd_his");
	$middlename = rand_str();	
	$name = $firstpicname.'_'.$middlename;
	$name = trim($name);
	if ($type != "") {
		$fullname = $name.".".$type;
	} else {
		$fullname = $name;
	}

	$destupload = $folder."/".$fullname; 

	if (move_uploaded_file($_FILES[$srcfile]['tmp_name'], $destupload)) { 		
		return $fullname;
	} else {
		parseError("File upload failed");
	}
}

function uploadfileimage_fixwidth($srcfile,$folder,$prefix,$targetwidth,$thumnail_flag,$filename_flag) {
	$ext = explode(".",$_FILES[ $srcfile]['name']);		
	if(count($ext)>1) {
		switch (strtolower($ext[1])) {
			case "png":
				$type = "png";
				break;			
			case "jpg";
				$type = "jpg";
				break;	
			case "jpeg";
				$type = "jpg";
				break;
			default:
				$type = strtolower($ext[1]);				
				//parseError("Mime Type must be image/jpeg or image/png");
				break;
		}
	} else {
		$type = "jpg";	
	}	
	
	
	if ($filename_flag == "USE_PREFIX_FILENAME") {
		$name = $prefix;
	}
	elseif ($filename_flag == "USE_IMAGE_FILENAME") {
		$name = $prefix."_".trim($ext[0]);
	}
	else { //RANDOM FILE NAME
		$firstpicname =  $prefix."_".date("ymd_his");
		$middlename = rand_str();	
		$name = $firstpicname.'_'.$middlename;
	}
	$name = trim($name);
	$fullname = $name.".".$type;
	$thumname = $name."_thum".".".$type;

	$destupload = $folder."/".$fullname; 
	$destthum = $folder."/".$thumname;
	
	if (move_uploaded_file($_FILES[$srcfile]['tmp_name'], $destupload)) { 
		$getImageInfo = getimagesize($destupload);
		$actual_image_width = $getImageInfo[0];
		$actual_image_height = $getImageInfo[1];
		
		if ($type=="png" || $type=="jpg") {
			if (strtolower($thumnail_flag)=="thumnail") {
				resizess($destupload,$destthum,200,200); 
			}
			if ($actual_image_width > $targetwidth) {
				$percent = (100 * $targetwidth / $actual_image_width);
				$h = $actual_image_height * $percent / 100;
				resizess($destupload,$destupload,$targetwidth,$h);
			}
		}
		return $fullname;
	} else {
		parseError("File upload failed");
	}
}
function uploadfileimagesamename($srcfile,$folder,$prefix,$resizeflag) {
	$ext = explode(".",$_FILES[ $srcfile]['name']);		
	if(count($ext)>1) {
		switch (strtolower($ext[1])) {
			case "png":
				$type = "png";
				break;			
			case "jpg";
				$type = "jpg";
				break;	
			case "jpeg";
				$type = "jpg";
				break;
			default:
				$type = strtolower($ext[1]);				
				//parseError("Mime Type must be image/jpeg or image/png");
				break;
		}
	} else {
		$type = "jpg";	
	}	
	$firstpicname =  $prefix."_".date("ymd_his");
	$middlename = rand_str();	
	$name = $prefix;
	$name = trim($name);
	
	$fullname = $name.".".$type;
	$thumname = $name."_thum".".".$type;
		
	//$destupload = $uploadPath.$folder."/".$fullname; 
	//$destthum = $uploadPath.$folder."/".$thumname;
	$destupload = $folder."/".$fullname; 
	$destthum = $folder."/".$thumname;

	if (move_uploaded_file($_FILES[$srcfile][ 'tmp_name' ], $destupload)) { 
		if ($type=="png" || $type=="jpg") {
			if (strtolower($resizeflag)=="resize") {
				resizess($destupload,$destthum,200,200);     
			}
		}
		return $fullname;
	} else {
		parseError("File upload failed");
	}
}

function matchToken($key,$user) {
	$securecode_post = decrypt($_POST['csrf_securecode'], $key);
	$token_post = md5(encrypt($securecode_post,$key).$user);
	if(!isset($_POST['csrf_token']))
		return false;
	if($_POST['csrf_token'] === $token_post) {	
		return true;
	}
	return false;
}

function getnewrecid($prefix,$field,$conn) {
	//HR-REC
	$sql = "select max(substring(matcat_code,4,3)) as seq from matcat_mstr";		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return $matcat_code."-".substr("000{$id}", -3);	
}
function getrcnbr($type,$conn) {
	//XXX-D2102-0001
	$engym = date('ym');
	$ym = strtoupper($type)."-D".$engym;
	
	$sql = "select max(substring(rc_nbr,11,4)) as nbr from rc_mstr where substring(rc_nbr,1,9) = '$ym'";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$rc_nbr = $ym."-".substr("0000{$next_numner}", -4);
	return $rc_nbr;
}
function getrcdocnbr($type,$conn) {
	//RCDOC-XXX-D2102-0001
	$engym = date('ym');
	$ym = "RCDOC-".strtoupper($type)."-D".$engym;
	
	$sql = "select max(substring(rcdoc_nbr,17,4)) as nbr from rcdoc_mstr where substring(rcdoc_nbr,1,15) = '$ym'";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$rcdoc_nbr = $ym."-".substr("0000{$next_numner}", -4);
	return $rcdoc_nbr;
}
function getrcapnbr($rc_nbr,$conn) {
	//RC_NBR-001
	
	$sql = "select max(substring(rcap_id,16,4)) as nbr from rcap_approval where substring(rcap_id,1,14) = '$rc_nbr'";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$rcapnbr = $rc_nbr."-".substr("0000{$next_numner}", -4);
	return $rcapnbr;
}
function Ymd_fr_Txt_Date($strdate) {
	//get from format dd/mm/yyyy to yyyy - mm -dd
	$Y = substr($strdate,0,4);
	$m = substr($strdate,4,2);
	$d = substr($strdate,6,2);
	return $Y."-".$m."-".$d;
}

function dmY_fr_Txt_Date($strdate) {
	//get from format dd/mm/yyyy to yyyy - mm -dd
	$Y = substr($strdate,0,4);
	$m = substr($strdate,4,2);
	$d = substr($strdate,6,2);
	return $d."-".$m."-".$Y;
}
function day_diff_sign($fromdate,$todate) {
	//para1 yyyymmdd, para2 = yyyymmdd
	$date1 = date_create(substr($fromdate,0,4).'-'.substr($fromdate,4,2).'-'.substr($fromdate,6,2));
	$date2 = date_create(substr($todate,0,4).'-'.substr($todate,4,2).'-'.substr($todate,6,2));
	$interval = date_diff($date1,$date2);
	return $interval->format('%R%a');
}
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function rep_prefix_name($str,$repby) {
	$o = $str;
	$regex = "/นาย|น\.ส\.|นาง|นางสาว|วท\.รต|Mr\.|Miss|Mrs/i";
	$o = preg_replace($regex, $repby, $o);
	return trim($o);
}
function is_scgemail($email) {
	$u_email = strtoupper($email);
	$scg_email = "@SCG.COM";
	$pos = strpos($u_email,$scg_email);
	if ($pos) {
		return true;
	}
	else {
		return false;
	}
}
function isservonline($cfgServer) {
	$cfgPort    = "25";
	$cfgTimeOut = "5";
	
	$f=@fsockopen("$cfgServer",$cfgPort,$cfgTimeOut);
	if ($f) {
		return true;
	}
	else {
		return false;
	}
}
function mail_message($from_name,$from_mail,$mail_to,$email_subject,$message) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8"."\r\n";
	$headers .= "From: ".$from_name."<".$from_mail.">"."\r\n";
	$subject1 = "=?UTF-8?B?".base64_encode($email_subject)."?=";
	
	$result = mail($mail_to,$subject1,$message,$headers);
	if($result) {
		return true;
	} else {
		return false;
	}
	
	//return true;
}
function get_attach_sizemb($rc_nbr,$conn) {
	$attach_size = 0;
	$params = array($rc_nbr);	
	$sql = "select sum(rcat_size) as attach_size from rcat_attach where rc_nbr = ?";		
	$result = sqlsrv_query($conn, $sql,$params);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if ($row) {		
		$attach_size = number_format($row["attach_size"]/1024/1024,2);
	}
	return (double)$attach_size;	
}

function getnewfileid($rc_nbr,$conn) {
	//RC_NBR-001
	
	$sql = "select max(substring(rcat_id,16,4)) as nbr from rcat_attach where substring(rcat_id,1,14) = '$rc_nbr'";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
	if ($row) {
		if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$rcapnbr = $rc_nbr."-".substr("0000{$next_numner}", -4);
	return $rcapnbr;
}
function getcrstmnbr($type,$conn) {
	//CR-YYMM-0001
	$tym = strtoupper($type).date('ym');
	$sql = "select max(substring(crstm_nbr,9,4)) as nbr from crstm_mstr where substring(crstm_nbr,1,7) = '$tym'";	
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if ($row) {
			if (is_null($row['nbr'])) {
				$next_numner = 1;
			}
			else {
				$next_numner = $row['nbr'] + 1;
			}
		}
		else {
			$next_numner = 1;
		}
		$crstm_nbr = $tym."-".substr("0000{$next_numner}", -4);
		return $crstm_nbr;
}
	//new customber
	//1900000  crstm_cus_active=0 new customer
	function getnewcusid($id, $conn) {
		$sql = "select max(substring(crstm_cus_nbr,3,5)) as nbr from crstm_mstr where crstm_cus_active='0'";
		$result = sqlsrv_query($conn, $sql);		
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		if ($row) {
			if (is_null($row['nbr'])) {
			$next_numner = 1;
		}
		else {
			$next_numner = $row['nbr'] + 1;
		}
	}
	else {
		$next_numner = 1;
	}
	$id = $id.substr("00000{$next_numner}", -5);
	
	return $id;
	}
	
//crstd_det
function getnewcrstdid($crstm_nbr,$conn) {
//CR-2004-0002-00X
$sql = "select max(substring(crstm_id,14,3)) as seq from crstm_mstr where crstm_nbr = '$crstm_nbr'";		
$result = sqlsrv_query($conn, $sql);		
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if (!$row) {		
	$id = 1;
}
else {		
	$id = $row["seq"] + 1;
}
return $crstm_nbr."-".substr("000{$id}", -3);	
}

function getnewappnewid($type_nbr,$conn) {
	//CR-2106-0003-001
	$sql = "select max(substring(cr_ap_id,14,3)) as seq from crctrl_approval where cr_ap_crctrl_nbr = '$type_nbr'";		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return $type_nbr."-".substr("000{$id}", -3);	
}

// newcust_add 
function getcusnewmnbr($type,$conn) {
	//NC-YYMM-0001
	$tym = strtoupper($type).date('ym');
	$sql = "select max(substring(cus_app_nbr,9,4)) as nbr from cus_app_mstr where substring(cus_app_nbr,1,7) = '$tym'";	
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if ($row) {
			if (is_null($row['nbr'])) {
				$next_numner = 1;
			}
			else {
				$next_numner = $row['nbr'] + 1;
			}
		}
		else {
			$next_numner = 1;
		}
		$cus_app_nbr = $tym."-".substr("0000{$next_numner}", -4);
		return $cus_app_nbr;
}

// running number cus_approval
function getcusnewapp($type_nbr,$conn) {
	//CR-2106-0003-001
	$sql = "select max(substring(cus_ap_id,14,3)) as seq from cus_approval where cus_ap_nbr = '$type_nbr'";		
	$result = sqlsrv_query($conn, $sql);		
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if (!$row) {		
		$id = 1;
	}
	else {		
		$id = $row["seq"] + 1;
	}
	return $type_nbr."-".substr("000{$id}", -3);	
}

// images_mstr 
function getimagesnbr($type,$conn) {
	//IM-YYMM-00001

	$tym = strtoupper($type).date('ym');
	$sql = "select max(substring(image_tem_nbr,9,5)) as nbr from images_mstr where substring(image_tem_nbr,1,7) = '$tym'";	
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
		if ($row) {
			if (is_null($row['nbr'])) {
				$next_numner = 1;
			}
			else {
				$next_numner = $row['nbr'] + 1;
			}
		}
		else {
			$next_numner = 1;
		}
		$image_tem_nbr = $tym."-".substr("00000{$next_numner}", -5);
		return $image_tem_nbr;
}
?>
