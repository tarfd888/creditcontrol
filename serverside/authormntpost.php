<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	} else {
		echo "System detect CSRF attack!!";
		exit;
	}
	$params = array();
	
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");
	$errortxt = "";
	$action_tiles = decrypt(mssql_escape($_REQUEST['action_tiles']), $key);

	$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	$author_id = html_escape($_POST['author_id']);	
	$author_sign_nme = html_escape(trim($_POST['author_sign_nme']));
	$author_email = strtolower(html_escape($_POST['author_email']));	
	$author_email_status	= html_escape($_POST['author_email_status']);	
	$author_text	= html_escape($_POST['author_text']);	
	$author_salutation = html_escape($_POST['author_salutation']);	
	$author_active = html_escape($_POST['author_active']);	
	$author_group = html_escape($_POST['author_group']);
	$author_code = html_escape($_POST['author_code']);
	$author_sign = html_escape($_POST['author_sign']);
	$account_group = html_escape($_POST['account_group']);
	$author_remark = html_escape($_POST['author_remark']);
	$financial_amt_beg = html_escape(str_replace(",","",$_POST['financial_amt_beg']));	
	$financial_amt_end = html_escape(str_replace(",","",$_POST['financial_amt_end']));
	$author_position = $author_sign_nme . " (" . $author_code . ")";
	
	//$reviewer_name = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ."/". $emp_th_div ."/". $emp_th_dept ."/". $emp_th_sec ;

	$gr = encrypt($action_tiles, $key);
	
	switch ($author_text) {
		case "ผผ. อนุมัติ":
			$author_seq = 1;
			$author_position = $author_position;
			break;
		case "ผส. อนุมัติ":
			$author_seq = 2;
			$author_position = $author_position;
			break;
		case "ผฝ. อนุมัติ":
			$author_seq = 3;
			$author_position = $author_position;
			break;
		case "CO. อนุมัติ":
			$author_seq = 4;
			$co = "CMO";
			if($action_tiles == "1"){
				$author_position = $author_sign_nme . " (" . $co . ")";
			}else{
				$author_position = $author_position;
			}	
			break;	
		case "กจก. อนุมัติ":
			$author_seq = 5;
			$author_position = $author_position;
			break;
		case "คณะกรรมการสินเชื่ออนุมัติ":
			$author_seq = 6;
			$author_position = $author_position;
			break;
		case "คณะกรรมการบริหารอนุมัติ":
			$author_seq = 7;
			$author_position = $author_position;
			break;	
		}

		if($action_tiles == "1"){
			$fle = "author_mstr";
			$author_group = "Tiles";
			// if($financial_amt_beg <= 700001){ // ตำแหน่งตั้งแต่ ผฝ. ลง $author_position ค่าว่าง
				// $author_position = "";
				$author_position = $author_position;
			// }
		}else{
			$fle = "author_g_mstr";
			$author_group = "Geoluxe";
				//if($financial_amt_beg < 5000001){ // ตำแหน่งตั้งแต่ กจก. ลง $author_position ค่าว่าง
					// $co = "Head Of Ceramics Business";
					// $author_position = $author_sign_nme . " (" . $co . ")";
					$author_position = $author_position;
				//}
		}

	$errorflag = false;
	$errortxt = "";
	 if (inlist("autho_add,autho_edit",$action)) {	
		if($financial_amt_beg >= 2000001) {
			if ($author_sign_nme == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ชื่อ - ตำแหน่ง ]";
			}
			if ($author_email=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ อีเมล ]";
		   }
		   if ($financial_amt_beg=="") {
			   if ($errortxt!="") {$errortxt .= "<br>";}
			   $errorflag = true;					
			   $errortxt .= "กรุณาระบุ - [ วงเงินเริ่มต้น ]";
		   }
		   if ($financial_amt_end=="") {
			   if ($errortxt!="") {$errortxt .= "<br>";}
			   $errorflag = true;					
			   $errortxt .= "กรุณาระบุ - [ วงเงินถึง ]";
		   } 
		}

		if ($author_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ อำนาจดำเนินการ ]";
		}
		if ($author_text=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ อำนาจดำเนินการอนุมัติ ]";
		}
		if ($author_salutation=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ เรียน ]";
		}
	}

	if ($action == "autho_add") {
	if (!$errorflag) {
		$params = array($author_sign_nme, 
						$author_email, 
						$author_salutation, 
						$author_position,
						$author_text, 
						$author_code, 
						$author_sign, 
						$author_group, 
						$account_group,
						$author_seq, 
						$author_email_status, 
						$author_active, 
						$financial_amt_beg, 
						$financial_amt_end);	

		$sql_add = " INSERT INTO $fle (" . 
			" author_sign_nme,author_email,author_salutation,author_position,author_text, ".
			" author_code,author_sign,author_group,account_group,author_seq, ".
			" author_email_status,author_active,financial_amt_beg,financial_amt_end)" .					
			" VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";			
			$result_add = sqlsrv_query($conn,$sql_add,$params);
		if ($result_add) {
			$r="1";
			$nb=encrypt($author_sign_nme, $key);
			$errortxt="Insert success.";
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Insert fail.";
		}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'","gr":"'.$gr.'"}';
	}
	else {
		$r="0";
		$nb="";
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'","gr":"'.$gr.'"}';
	}
}

if ($action == "autho_edit") {
	if (!$errorflag) {

		$params=array($author_id);
			$sql_edit = "UPDATE $fle SET " .
				" author_sign_nme = '$author_sign_nme'," .
				" author_email  = '$author_email',".		
				" author_text  = '$author_text',".		
				" author_code  = '$author_code',".		
				" author_sign  = '$author_sign',".	
				" author_group  = '$author_group',".		
				" author_seq  = '$author_seq',".	
				" author_position  = '$author_position',".	
				" author_salutation = '$author_salutation',".
				" author_email_status = '$author_email_status',".
				" author_active = '$author_active',".
				" financial_amt_beg = '$financial_amt_beg'," .
				" financial_amt_end = '$financial_amt_end'," .
				" author_update_by = '$user_login'," .
				" author_update_date = '$today'" .
				" WHERE author_id = ?";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);

	   if ($result_edit) {
		   $r="1";
		   $nb=encrypt($author_id, $key);
		   $errortxt="Update success.";
	   }
	   else {
		   $r="0";
		   $nb="";
		   $errortxt="Update fail.";
	   }
	   echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'","gr":"'.$gr.'"}';
	}
	else {
	   $r="0";
	   $nb="";
	   echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'","gr":"'.$gr.'"}';
	}
}

if ($action == "add-remark") {
	if (!$errorflag) {

			$sql_edit = "UPDATE author_mstr SET " .
				" author_remark = '$author_remark'," .
				" author_update_by = '$user_login'," .
				" author_update_date = '$today'";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);

	   if ($result_edit) {
		   $r="1";
		   $nb=encrypt($author_remark, $key);
		   $errortxt="Update success.";
	   }
	   else {
		   $r="0";
		   $nb="";
		   $errortxt="Update fail.";
	   }
	   echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'","gr":"'.$gr.'"}';
	}
	else {
	   $r="0";
	   $nb="";
	   echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'","gr":"'.$gr.'"}';
	}
}
?>