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
	$bank_code = mssql_escape($_POST['bank_code']);	
	$bank_th_name = mssql_escape(trim($_POST['bank_th_name']));
	$bank_status = mssql_escape($_POST['bank_status']);	
	$create_by=$user_login;
	$errorflag = false;
	$errortxt = "";
	if (inlist("bank_add,bank_edit",$action)) {	
		
		if ($bank_code=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Code ]";
		}
		if ($bank_th_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ Bank Name ]";
		}
	}
	
	if ($action == "bank_add") {
		if (!$errorflag) {
			$params = array($bank_code,$bank_th_name,$bank_status,$create_by,$today);	
			$sql_add = " INSERT INTO bank_mstr (" . 
				" bank_code,bank_th_name,bank_status,".
				" bank_create_by,bank_create_date)" .					
				" VALUES(?,?,?,?,?)";			
				$result_add = sqlsrv_query($conn,$sql_add,$params);
			if ($result_add) {
				$r="1";
				$nb=encrypt($bank_code, $key);
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

	if ($action == "bank_edit") {
		if (!$errorflag) {
			
			$params=array($bank_code);
			$sql_edit = "UPDATE bank_mstr SET " .
			" bank_code = '$bank_code'," .
			" bank_th_name  = '$bank_th_name',".
			" bank_status  = '$bank_status',".			
			" bank_update_by = '$create_by'," .
			" bank_update_date = '$today'" .
			" WHERE bank_code = ?";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			
			if ($result_edit) {
				$r="1";
				$nb=encrypt($bank_code, $key);
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

	if ($action == "bank_del") {
		$rowCounts = 0;	
		$params = array($bank_code);
			$sql_del = "delete from bank_mstr WHERE bank_code = ?";
			$result_del = sqlsrv_query($conn,$sql_del,$params, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if ($result_del) {
				$r="1";
				$errortxt="ลบข้อมูลเรียบร้อยแล้ว";
				$nb=encrypt($bank_code, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="ไม่สามารถลบข้อมูลได้";
			}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	
?>