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
	
	$action = mssql_escape($_POST['action']);
	$country_code = mssql_escape($_POST['country_code']);	
	$country_desc = mssql_escape(trim($_POST['country_desc']));
	
	$create_by=$user_login;
	$errorflag = false;
	$errortxt = "";
	if (inlist("country_add,country_edit",$action)) {	
		
		if ($country_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Code ]";
		}
		if ($country_desc=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Country Name ]";
		}
	}
	
	if ($action == "country_add") {
		if (!$errorflag) {
			$params = array($country_code,$country_desc,$create_by,$today);	
			$sql_add = " INSERT INTO country_mstr (" . 
				" country_code,country_desc,".
				" country_create_by,country_create_date)" .					
				" VALUES(?,?,?,?)";			
				$result_add = sqlsrv_query($conn,$sql_add,$params);
			if ($result_add) {
				$r="1";
				$nb=encrypt($country_code, $key);
				$errortxt="บันทึกข้อมูลเรียบร้อย";
			}
			else {
				$r="0";
				$nb="";
				$errortxt="ไม่สามารถบันทึกข้อมูลได้";
			}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	}

	if ($action == "country_edit") {
		if (!$errorflag) {
			
			$params=array($country_code);
			$sql_edit = "UPDATE country_mstr SET " .
			" country_code = '$country_code'," .
			" country_desc  = '$country_desc',".		
			" country_update_by = '$role_user_login'," .
			" country_update_date = '$today'" .
			" WHERE country_code = ?";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			
			if ($result_edit) {
				$r="1";
				$nb=encrypt($country_code, $key);
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

	if ($action == "country_del") {
		$rowCounts = 0;	
		$params = array($country_code);
			$sql_del = "delete from country_mstr WHERE country_code = ?";
			$result_del = sqlsrv_query($conn,$sql_del,$params, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if ($result_del) {
				$r="1";
				$errortxt="ลบข้อมูลเรียบร้อยแล้ว";
				$nb=encrypt($country_code, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="ไม่สามารถลบข้อมูลได้";
			}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	
?>