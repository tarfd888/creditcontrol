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
	
	$term_code = html_escape($_POST['term_code']);	
	$term_desc = html_escape(trim($_POST['term_desc']));
	$term_active	= html_escape($_POST['term_active']);	
	$term_group	= html_escape($_POST['term_group']);	
	if($term_group=="Domestic"){
		$term_group = "1";
	} else {
		$term_group = "2";
	}	
	$role_create_by=$user_login;
	$errorflag = false;
	$errortxt = "";
	if (inlist("term_edit",$action)) {	
		
		if ($term_active=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Status ]";
		}
		if ($term_group=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Domestic / Export ]";
		}
	}
	
	if ($action == "term_edit") {
		if (!$errorflag) {
			
			$params=array($term_code);
			$sql_edit = "UPDATE term_mstr SET " .
			" term_code = '$term_code'," .
			" term_active  = '$term_active',".		
			" term_group  = '$term_group',".	
			" term_desc  = '$term_desc',".		
			" term_update_by = '$role_user_login'," .
			" term_update_date = '$today'" .
			" WHERE term_code = ?";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			
			if ($result_edit) {
				$r="1";
				$nb=encrypt($term_code, $key);
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
	
?>