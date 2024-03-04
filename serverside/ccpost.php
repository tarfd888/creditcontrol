<?php
	//Update can_editing
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack8!!";
			exit;
		}
	}
	else {
		echo "Allow for POST Only";
		exit;
	}
	$params = array();
	
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");
	$curr_date = ymd(date("d/m/Y"));
	$errortxt = "";
	$allow_post = false;
	
	$pg = html_escape($_POST['pg']);
	$action = html_escape($_POST['action']);
	
	//--1. Parameter From crctrladd.php

	$crstm_nbr = mssql_escape($_POST['crstm_nbr']);
	$crstm_cus_nbr = mssql_escape($_POST['cr_cust_code']);
	$phone_mask = html_escape($_POST['phone_mask']);
	$txt_cc = mssql_escape($_POST['txt_cc']);
	$cc_amt = mssql_escape(str_replace(",","",$_POST['cc_amt']));
	$beg_date = mssql_escape(ymd($_POST['beg_date']));
	$end_date = mssql_escape(ymd($_POST['end_date']));
	$txt_ref = mssql_escape($_POST['txt_ref']);
	$row_seq =  mssql_escape($_POST['row_seq']);
	$edit_beg_date = mssql_escape(ymd($_POST['edit_beg_date']));
	$edit_end_date = mssql_escape(ymd($_POST['edit_end_date']));
	$cus_conf_yes = mssql_escape($_POST['cus_conf_yes']);
	$cusold_conf_yes = mssql_escape($_POST['cusold_conf_yes']);
	$term_conf_yes = mssql_escape($_POST['term_conf_yes']);
	$crstm_sd_per_mm = mssql_escape($_POST['crstm_sd_per_mm']);
	$cc_amt_new = mssql_escape(str_replace(",","",$_POST['cc_amt_new']));
	$beg_date_new = mssql_escape(ymd($_POST['beg_date_new']));
	$end_date_new = mssql_escape(ymd($_POST['end_date_new']));
	
	//Parameter From crctrledit.php
	$edit1_beg_date = mssql_escape(ymd($_POST['edit1_beg_date']));
	$edit1_end_date = mssql_escape(ymd($_POST['edit1_end_date']));
	//$crlimit_ref = "CC";
	
	if ($txt_cc == "เสนอขอปรับเพิ่มวงเงิน") { 
		$crlimit_txt_ref = "C1";
		$crlimit_ref = "C1";
		} else if ($txt_cc == "เสนอขอปรับลดวงเงิน") { 
		$crlimit_txt_ref = "C2";
		$crlimit_ref = "C2";
		} else if ($txt_cc == "เสนอขอต่ออายุวงเงิน") { 
		$crlimit_txt_ref = "C3";
		$crlimit_ref = "C3";
	} else if ($txt_cc == "วงเงินปัจจุบัน") { 
		$crlimit_txt_ref = "CC";
		$crlimit_ref = "CC";
	}
	
	$errorflag = false;
	$errortxt = "";
	
	if (inlist("cc_add",$action)) {	
		// Section I VALIDATION
		if ($beg_date=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วันที่เริ่ม ]";
		}
		if ($end_date=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วันที่สิ้นสุด ]";
		}
		if ($cc_amt=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วงเงิน ]";
		}
		
	}
	if (inlist("cc_add_new",$action)) {	
		// Section I VALIDATION
		if ($beg_date_new=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วันที่เริ่ม ]";
		}
		if ($end_date_new=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วันที่สิ้นสุด ]";
		}
		if ($cc_amt_new=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วงเงิน ]";
		}
		
	}
	// form crctrladd.php
	if ($action == "cc_add" && $txt_cc != "เสนอขอต่ออายุวงเงิน") {
		
		if (!$errorflag) {
			$crlimit_id = getnewid("crlimit_id", "crlimit_mstr", $conn);
			$params = array($crstm_cus_nbr,$crlimit_ref,$beg_date,$end_date,$txt_cc,$cc_amt,$crlimit_txt_ref,$crlimit_id,$crlimit_id,$user_login,$today);	
			$sql_add = " INSERT INTO  crlimit_mstr (" . 
			" crlimit_acc,crlimit_doc_head_txt,crlimit_doc_date,crlimit_due_date,crlimit_txt,".
			//" crlimit_acc,crlimit_ref,crlimit_doc_date,crlimit_due_date,crlimit_txt,".
			" crlimit_amt_loc_curr,crlimit_txt_ref,crlimit_seq,crlimit_id,crlimit_create_by,crlimit_create_date)" .					
			" VALUES(?,?,?,?,?,?,?,?,?,?,?)";			
			$result_add = sqlsrv_query($conn,$sql_add,$params);
			if ($result_add) {
				$r="1";
				$nb=encrypt($crstm_cus_nbr,$key);
				$nb1=encrypt($cus_conf_yes,$key);
				$nb2=encrypt($cusold_conf_yes,$key);
				$nb3=encrypt($phone_mask,$key);
				$nb4=encrypt($term_conf_yes, $key); 
				$errortxt="Insert success.";
			}
			else {
				$r="0";
				$nb="";
				$nb1="";
				$nb2="";
				$errortxt="Insert fail.";
			}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","nb1":"'.$nb1.'","nb2":"'.$nb2.'","nb3":"'.$nb3.'","nb4":"'.$nb4.'"}';
		}
		else {
			$r="0";
			$nb="";
			$nb1="";
			$nb2="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","nb1":"'.$nb1.'","nb2":"'.$nb2.'"}';
		}
	}
	
	// form crctrladd.php
	if ($action == "cc_edit") {
		if (!$errorflag) {
			$params = array($crstm_cus_nbr,$row_seq);	
			
			$sql_edit = "UPDATE crlimit_mstr SET " .
			" crlimit_acc = '$crstm_cus_nbr'," .
			//" crlimit_ref  = '$txt_ref'," .
			" crlimit_doc_head_txt = '$txt_ref'," .
			//" crlimit_doc_date  = '$edit_beg_date',".		
			" crlimit_due_date = '$edit_end_date'," .
			//" crlimit_txt = '$txt_cc'," .
			//" crlimit_amt_loc_curr = '$cc_amt'," .
			" crlimit_update_by = '$user_login'," .
			" crlimit_update_date = '$today'" .
			" WHERE crlimit_acc = ?  and crlimit_seq = ? ";
			//" WHERE crlimit_acc = ? and crlimit_txt_ref = ? and crlimit_seq = ? ";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			
			if ($result_edit) {
				$r="1";
				$nb=encrypt($crstm_cus_nbr,$key);
				$nb1=encrypt($cus_conf_yes,$key);
				$nb2=encrypt($cusold_conf_yes,$key);
				$nb3=encrypt($phone_mask,$key);
				$nb4=encrypt($term_conf_yes, $key); 
				$nb5=encrypt($crstm_sd_per_mm, $key); 
				$errortxt="Update success.";
			}
			else {
				$r="0";
				$nb="";
				$nb1="";
				$nb2="";
				$nb3="";
				$errortxt="Update fail.";
			}
			//echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","nb1":"'.$nb1.'","nb2":"'.$nb2.'","nb3":"'.$nb3.'","nb4":"'.$nb4.'","nb5":"'.$nb5.'"}';
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","nb1":"'.$nb1.'","nb2":"'.$nb2.'","nb3":"'.$nb3.'","nb4":"'.$nb4.'","nb5":"'.$nb5.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","nb1":"'.$nb1.'","nb2":"'.$nb2.'"}';
		}
	}
	// form crctrladd.php
	if ($action == "del_cc") {
		$params_check_del = array($row_seq);	
		$sql_del = "delete from crlimit_mstr WHERE  crlimit_seq = ?";
		
		$result_del = sqlsrv_query($conn,$sql_del,$params_check_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if ($result_del) {
			$r="1";
			$errortxt="Delete success.";
			$nb=encrypt($crstm_nbr, $key);
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Delete fail.";
		}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	// form crctrledit.php
	if ($action == "del_edit_cc") {
		$params_check_del = array($row_seq);	
		$sql_del = "delete from tbl3_mstr WHERE  tbl3_id = ?";
		
		$result_del = sqlsrv_query($conn,$sql_del,$params_check_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if ($result_del) {
			$r="1";
			$errortxt="Delete success.";
			$nb=encrypt($crstm_nbr, $key);
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Delete fail.";
		}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	
	// form crctrledit.php
	if ($action == "editcc_edit") {
		if (!$errorflag) {
	
			$params = array($crstm_nbr,$row_seq);	
			$sql_edit = "UPDATE tbl3_mstr SET " .
			" tbl3_nbr = '$crstm_nbr'," .
			" tbl3_cus_nbr  = '$crstm_cus_nbr',".		
			" tbl3_amt_loc_curr  = '$cc_amt',".		
			" tbl3_doc_date = '$edit1_beg_date'," .
			" tbl3_due_date = '$edit1_end_date'," .
			" tbl3_txt_ref = '$txt_ref'," .
			" tbl3_update_by = '$user_login'," .
			" tbl3_update_date = '$today'" .
			" WHERE tbl3_nbr = ? and tbl3_id = ?";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			
			$params = array($crstm_nbr);
			$sql_edit = "UPDATE crstm_mstr SET ".
			"crstm_chk_rdo2 = '$txt_ref' ,".
			"crstm_cc_date_beg = '$edit1_beg_date'," .
			"crstm_cc_date_end =  '$edit1_end_date'," .
			"crstm_cc_amt = '$cc_amt'".		
			"WHERE crstm_nbr= ?";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			
			if ($result_edit) {
				$r="1";
				$nb=encrypt($crstm_nbr, $key);
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
			$nb1="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	}
	
?>