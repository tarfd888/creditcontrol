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
	
	$role_id = html_escape($_POST['role_id']);	
	$role_code = html_escape(trim($_POST['role_code']));
	$role_user_login = html_escape($_POST['role_user_login']);	
	$role_active	= html_escape($_POST['role_active']);	
	$role_desc	= html_escape($_POST['role_desc']);	
	$role_receive_mail = html_escape($_POST['role_receive_mail']);	
	
	$role_user_login=strtoupper($role_user_login);
	
	$role_create_by=$user_login;
	$errorflag = false;
	$errortxt = "";
	 if (inlist("role_add",$action)) {	
	
	 	if ($role_user_login == "") {
	 		if ($errortxt!="") {$errortxt .= "<br>";}
	 		$errorflag = true;					
	 		$errortxt .= "กรุณาระบุ - [ User ]";
	 	}
		
	 	if ($role_code=="") {
	 		if ($errortxt!="") {$errortxt .= "<br>";}
	 		$errorflag = true;					
	 		$errortxt .= "กรุณาระบุ - [ Role type ]";
		 }
		 
		 if ($role_active=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Status ]";
		}
			
	}

	if ($action == "role_add") {
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

if (inlist("role_edit",$action)) {	
	
	if ($role_user_login == "") {
		if ($errortxt!="") {$errortxt .= "<br>";}
		$errorflag = true;					
		$errortxt .= "กรุณาระบุ - [ User ]";
	}
   
	if ($role_code=="") {
		if ($errortxt!="") {$errortxt .= "<br>";}
		$errorflag = true;					
		$errortxt .= "กรุณาระบุ - [ Role type ]";
	}
	
	if ($role_active=="") {
	   if ($errortxt!="") {$errortxt .= "<br>";}
	   $errorflag = true;					
	   $errortxt .= "กรุณาระบุ - [ Status ]";
   }
	   
}

if ($action == "role_edit") {
if (!$errorflag) {

	$params=array($role_id);
		$sql_edit = "UPDATE role_mstr SET " .
			" role_user_login = '$role_user_login'," .
			" role_code = '$role_code'," .
			" role_active  = '$role_active',".		
			" role_desc  = '$role_desc',".		
			" role_receive_mail = '$role_receive_mail',".
			" role_update_by = '$role_user_login'," .
			" role_update_date = '$today'" .
			" WHERE role_id = ?";
		$result_edit = sqlsrv_query($conn, $sql_edit, $params);

   if ($result_edit) {
	   $r="1";
	   $nb=encrypt($role_id, $key);
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