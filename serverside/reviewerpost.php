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

	$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	$emp_person_id = html_escape($_POST['emp_person_id']);	
	$emp_user_id = html_escape($_POST['emp_user_id']);	
	$emp_th_firstname = html_escape(trim($_POST['emp_th_firstname']));
	$emp_th_lastname = html_escape($_POST['emp_th_lastname']);	
	$emp_th_pos_name = html_escape($_POST['emp_th_pos_name']);	
	$emp_email_bus	= html_escape($_POST['emp_email_bus']);	
	$emp_remark = html_escape($_POST['emp_remark']);	
	$emp_flag = html_escape($_POST['emp_flag']);	
	$user_id =  strtoupper(explode("@",$emp_email_bus)[0]);
	
	$rev_create_by=$user_login;
	$errorflag = false;
	$errortxt = "";
	 if (inlist("rev_edit",$action)) {	
	
	 	if ($emp_th_firstname == "") {
	 		if ($errortxt!="") {$errortxt .= "<br>";}
	 		$errorflag = true;					
	 		$errortxt .= "กรุณาระบุ - [ ชื่อ]";
	 	}
		
	 	if ($emp_th_lastname=="") {
	 		if ($errortxt!="") {$errortxt .= "<br>";}
	 		$errorflag = true;					
	 		$errortxt .= "กรุณาระบุ - [ นามสกุล ]";
		 }
		 
		 if ($emp_th_pos_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ตำแหน่ง ]";
		}
		
		if ($emp_email_bus=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ อีเมล ]";
		}	
		
		if ($emp_flag=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Tiles หรือ Geoluxe ]";
		}	
	}

	if ($action == "rev_add") {
	if (!$errorflag) {
		$params = array($role_user_login);
		$role_id = getnewseq("role_id","role_mstr",$conn);
		
		$params = array($role_id,$role_code,$role_user_login,$role_desc,$role_create_by,$today,$role_active,$role_receive_mail);	
		$sql_add = " INSERT INTO role_mstr (" . 
			" role_id,role_code,role_user_login,role_desc,".
			" role_create_by,role_create_date,role_active,role_receive_mail)" .					
			" VALUES(?,?,?,?,?,?,?,?)";			
			$result_add = sqlsrv_query($conn,$sql_add,$params);
		if ($result_add) {
			$r="1";
			$nb=encrypt($role_user_login, $key);
			$errortxt="Insert success.";
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Insert fail.";
		}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	else {
		$r="0";
		$nb="";
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
}


if ($action == "rev_edit") {
	if (!$errorflag) {

		$params=array($emp_person_id);
			$sql_edit = "UPDATE reviewer_mstr SET " .
				" emp_th_firstname = '$emp_th_firstname'," .
				" emp_th_lastname  = '$emp_th_lastname',".		
				" emp_th_pos_name = '$emp_th_pos_name',".
				" emp_email_bus = '$emp_email_bus'," .
				" emp_flag = '$emp_flag'" .
				" WHERE emp_person_id = ?";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);

	   if ($result_edit) {
		   $r="1";
		   $nb=encrypt($emp_person_id, $key);
		   $errortxt="Update success.";
	   }
	   else {
		   $r="0";
		   $nb="";
		   $errortxt="Update fail.";
	   }
	   echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	else {
	   $r="0";
	   $nb="";
	   echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
}

if ($action == "add-remark") {
	if (!$errorflag) {

			$sql_edit = "UPDATE reviewer_mstr SET emp_remark = '$emp_remark'";
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

if ($action == "roledel") {
	$rowCounts = 0;	
	$params_check_del = array($role_id);
		$sql_del = "delete from role_mstr WHERE role_id = ?";
		$result_del = sqlsrv_query($conn,$sql_del,$params_check_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if ($result_del) {
			$r="1";
			$errortxt="Delete success.";
			$nb=encrypt($role_id, $key);
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Delete fail.";
		}
	echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
}
?>