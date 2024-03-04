<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/funcCrform.php");
include("../_incs/funcAppform.php");

session_start();

date_default_timezone_set('Asia/Bangkok');
$today = date("Y-m-d H:i:s"); 
$curr_date = ymd(date("d/m/Y"));
$allow_post = false;	
$action_post = html_escape($_POST['action']);

if ($action_post != "") { //post มาจาก form
		include("../_incs/chksession.php");
		include "../_incs/acunx_csrf_var.php";
		if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
			if (!matchToken($csrf_key,$user_login)) {
				echo "System detect CSRF attack!!";
				exit;
			}
		}
		else {
			echo "Allow for POST Only";
			exit;
		}
	
		$step_code = html_escape($_GET['step_code']);
		$crstm_step_code = html_escape(decrypt($step_code, $key));
		$crstm_nbr = mssql_escape($_POST['crstm_nbr']);	
		$crstm_reviewer = mssql_escape($_POST['crstm_reviewer']);
		$crstm_noreviewer = mssql_escape($_POST['crstm_noreviewer']); // กรณีไม่ได้ระบุผู้ตรวจสอบคนที่ 1 ค่าจะเป็น true
		$crstm_step_name = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_step_code ,$conn);
		
	}
	else { //post มาจาก email
		//Use Double Cookie for recheck CSRF
		$sessionid = session_id();
		$rev_verify_csrf_mail =  html_escape($_COOKIE['rev_verify_csrf_mail']);
		$sessionid_dec = decrypt($rev_verify_csrf_mail, $key);
				
		if ($sessionid != $sessionid_dec) {
				setcookie ("rev_verify_csrf_mail", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
				$r="0";
				$errortxt="<span style='color:red'><h4 style='text-align:center'>** ไม่สามารถอนุมัติได้  ** <h4></span>";
				echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
				exit;
			}
		else {
			setcookie ("rev_verify_csrf_mail", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			$crstm_auth_code = mssql_escape($_POST['crstm_auth_code']);
			$crstm_approved_by = decrypt(mssql_escape($_POST['crstm_approved_by']), $dbkey);
			$crstm_approve_nbr = decrypt(mssql_escape($_POST['crstm_approve_nbr']), $dbkey);
			$crstm_approve_select = decrypt(mssql_escape($_POST['crstm_approve_select']), $dbkey);
			$auth_appr =  strtoupper(explode("@",$crstm_approved_by)[0]);
			
			if ($crstm_auth_code!="" && $crstm_approve_nbr!="" && $crstm_approve_select!="") {
			
				$params = array($crstm_approve_nbr);
				$query_detail = "SELECT crstm_nbr,crstm_user,crstm_tel,crstm_cus_name,crstm_reviewer,crstm_reviewer_date,crstm_approve_code,crstm_step_code FROM crstm_mstr where crstm_nbr = ?";
				$result_detail = sqlsrv_query($conn, $query_detail,$params);
				$rec_crstm = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
				if ($rec_crstm) {
					$crstm_nbr = html_clear($rec_crstm['crstm_nbr']);
					$crstm_user = html_clear(strtolower($rec_crstm['crstm_user']));
					$crstm_tel = html_clear($rec_crstm['crstm_tel']);
					$crstm_cus_name = html_clear($rec_crstm['crstm_cus_name']);
					$crstm_reviewer = html_clear($rec_crstm['crstm_reviewer']);
					$crstm_reviewer_date = html_clear($rec_crstm['crstm_reviewer_date']);
					$crstm_approve_code = html_clear($rec_crstm['crstm_approve_code']);
					$allow_post = true;
					
					if ($crstm_approve_code == $crstm_auth_code && inlist($crstm_reviewer,$crstm_approved_by)) {	
						$allow_post = true;
						
						$params = array($crstm_user);
						$query_emp_detail = "SELECT emp_email_bus,emp_prefix_th_name,emp_th_firstname,emp_th_lastname FROM emp_mstr where emp_scg_emp_id = ?";
						$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
						$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
						if ($rec_emp) {
							$sale_email = html_clear(strtolower($rec_emp['emp_email_bus']));
							$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
							$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
							$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
							$sale_fullname = $emp_prefix_th_name . $emp_th_firstname ." ". $emp_th_lastname;
						}				
						
					}
					else {
						$allow_post = false;
						$r="0";
						$errortxt="**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ   **";
					}
				} 
				
					if(($crstm_reviewer_date != "" || is_null($crstm_reviewer_date)) && ($crstm_reviewer == $crstm_approved_by)) { // เช็คเอกสารว่ามีการ approve หรือยัง
						$allow_post = false;
						$r="0";
						$errortxt="<span style='color:red'><h4 style='text-align:center'>*** เอกสารฉบับนี้ ได้ถูกอนุมัติไปแล้ว  *** <h4></span>";
						echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
						exit;
					}
			
			}
			else {
				$allow_post = false;
				$r="0";
				$errortxt="**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ  **";
			}
		}
	}
	
	if ($allow_post) {
	   if ($crstm_approve_select=="111") {   // reviewer approve 
	   
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			
			//เก็บประวัติการดำเนินการ
			$cr_ap_f_step = "0";  // Draft
			$cr_ap_t_step = "20"; // ผู้พิจารณาอนุมัติ
			$cr_ap_text = "Submit for Reviewer";
			$cr_ap_remark = "";		
				
			$cr_ap_id = getnewappnewid($crstm_approve_nbr,$conn);
				
			$sql = "INSERT INTO  crctrl_approval (" . 
			" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
			" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$auth_appr','$today')";				

			$result = sqlsrv_query($conn, $sql);

			//ดึงรายชื่อ email ของคนที่มี role Action_View1 ทุกคน
			$cr_next_curprocessor_email = "";
			$params = array('Action_View1');
			$sql_aucadmin = "select role_user_login from role_mstr where role_code = ? and role_receive_mail = 1";
			$result_aucadmin = sqlsrv_query($conn, $sql_aucadmin,$params);											
			while($r_aucadmin = sqlsrv_fetch_array($result_aucadmin, SQLSRV_FETCH_ASSOC)) {
				$aucadmin_user_login = $r_aucadmin['role_user_login'];
				$aucadmin_user_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$aucadmin_user_login,$conn);
				if ($aucadmin_user_email!="") {
					if ($cr_next_curprocessor_email != "") {$cr_next_curprocessor_email = $cr_next_curprocessor_email . ",";}
					$cr_next_curprocessor_email = $cr_next_curprocessor_email . $aucadmin_user_email;
				}
			}
				
			$mail_from = $sale_fullname;
			$mail_from_email = $sale_email;
			$mail_to = $cr_next_curprocessor_email;
			$mail_subject = "Credit 1 โปรดดำเนินการ: ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name ";
			
			$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน แผนกสินเชื่อ (Credit 1)<br><br>
			ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name <br>
			Credit 1 : โปรดดำเนินการในระบบ Credit Control ด้วยค่ะ  <br><br>
			$sale_fullname เบอร์โทร  $crstm_tel และอีเมล $sale_email<br><br>
			
			ขอบคุณค่ะ</font>";	
			
			$mail_message .= "<br>" .$mail_no_reply ;
			//$mail_message .= $mail_no_reply;
			
			if ($mail_to!="") {
				$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
				}
				
			} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			
			//  Cr1 แผนกสินเชือ  --- -> Sale
			if($sale_email!="") {
				$mail_from = $mail_from_text;
				$mail_from_email = $mail_credit_email;
				$mail_to = $sale_email;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr ลูกค้า $crstm_cus_name  ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $sale_fullname <br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ <br><br>
				
				ขอบคุณค่ะ</font>";
				$mail_message .= "<br>" .$mail_no_reply;
				if($mail_to!="") {
					$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			}
			
			$step_name_cr1 = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", "10" ,$conn);
			$params_edit = array($crstm_approve_nbr);
			$sql_edit = "UPDATE crstm_mstr SET ".
			" crstm_reviewer_date = '$curr_date ' ,".
			" crstm_step_code = '10' ,".
			" crstm_status = 0 ,".
			" crstm_step_name = '$step_name_cr1' ".
			" WHERE crstm_nbr = ? ";
			$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
			if($result_edit) {
				$r="1";
				$errortxt="Reviewer approve success.";
				$nb=encrypt($crstm_approve_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="Reviewer approve fail.";
			}
			$r="1";	
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} // reviewer approve 
		
		if ($crstm_approve_select=="112") {   // Revise
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			
			//เก็บประวัติการดำเนินการ
			$cr_ap_f_step = "0";  // Draft
			$cr_ap_t_step = "21"; //  แก้ไขเอกสาร
			$cr_ap_text = "Reviewer to Revise";
			$cr_ap_remark = "";		
				
			$cr_ap_id = getnewappnewid($crstm_approve_nbr,$conn);
				
			$sql = "INSERT INTO  crctrl_approval (" . 
			" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
			" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$auth_appr','$today')";				

			$result = sqlsrv_query($conn, $sql);
			
			// ค้นหาเมล Reviewer
			$params = array($crstm_approved_by);
			$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
			$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
			$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_emp) {
				$reviewer_email = html_clear(strtolower($rec_emp['emp_email_bus']));
				$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
				$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
				$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
				$reviewer_fullname = $emp_prefix_th_name . $emp_th_firstname ." " . $emp_th_lastname;
			}

			$params = array($crstm_approve_nbr);
			$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_user, emp_mstr.emp_prefix_th_name, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_email_bus ".
							"FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id WHERE (crstm_mstr.crstm_nbr = ?)";
			
			$result_detail = sqlsrv_query($conn, $query_detail,$params);
			$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_cus) {
				$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
				$sale_name = trim($rec_cus['emp_prefix_th_name']) . trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
				$email_sale = strtolower($rec_cus['emp_email_bus']);
			} 
			// Reviewer ส่งอีเมล  --->Sale กลับไปแก้ไข
			if($email_sale!="") {
				$mail_from = $reviewer_fullname;
				$mail_from_email = $reviewer_email;
				$mail_to = $email_sale;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr ลูกค้า $crstm_cus_name  กรุณาตรวจสอบแก้ไขใหม่ ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $sale_name <br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name กรุณาตรวจสอบแก้ไขใหม่ <br><br>
				
				ขอบคุณค่ะ</font>";
				$mail_message .= "<br>" .$mail_no_reply;
				if($mail_to!="") {
					$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			}
				$step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_approve_select ,$conn);
				$params_edit = array($crstm_approve_nbr);
				$sql_edit = "UPDATE crstm_mstr SET ".
				//" crstm_reviewer_date = '$curr_date' ,".
				" crstm_step_code = '$crstm_approve_select' ,".
				" crstm_step_name = '$step_name' ".
				" WHERE crstm_nbr = ? ";
				$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				if($result_edit) {
					$r="1";
					$errortxt="Reviese success.";
					$nb=encrypt($crstm_approve_nbr, $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="Reviese fail.";
				}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} ///// Revise --- > Sale
		
		if ($crstm_approve_select=="113") {   // Reject
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			
			//เก็บประวัติการดำเนินการ
			$cr_ap_f_step = "0";  // Draft
			$cr_ap_t_step = "22"; //  Reject
			$cr_ap_text = "Reviewer to Reject";
			$cr_ap_remark = "";		
				
			$cr_ap_id = getnewappnewid($crstm_approve_nbr,$conn);
				
			$sql = "INSERT INTO  crctrl_approval (" . 
			" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
			" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$auth_appr','$today')";				

			$result = sqlsrv_query($conn, $sql);
			
			// ค้นหาเมล Reviewer
			$params = array($crstm_approved_by);
			$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
			$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
			$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_emp) {
				$reviewer_email = html_clear(strtolower($rec_emp['emp_email_bus']));
				$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
				$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
				$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
				$reviewer_fullname = $emp_prefix_th_name . $emp_th_firstname ." " . $emp_th_lastname;
			}

			$params = array($crstm_approve_nbr);
			$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_user, emp_mstr.emp_prefix_th_name, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_email_bus ".
							"FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id WHERE (crstm_mstr.crstm_nbr = ?)";
			
			$result_detail = sqlsrv_query($conn, $query_detail,$params);
			$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_cus) {
				$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
				$sale_name = trim($rec_cus['emp_prefix_th_name']) . trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
				$email_sale = strtolower($rec_cus['emp_email_bus']);
			} 
			// Reviewer ส่งอีเมล  --->Sale reject
			if($email_sale!="") {
				$mail_from = $reviewer_fullname;
				$mail_from_email = $reviewer_email;
				$mail_to = $email_sale;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr ลูกค้า $crstm_cus_name ไม่ผ่านการพิจารณาจาก Reviewer 1 ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $sale_name <br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name ไม่ผ่านการพิจารณาจาก Reviewer 1 <br><br>
				
				ขอบคุณค่ะ</font>";
				$mail_message .= "<br>" .$mail_no_reply;
				if($mail_to!="") {
					$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			}
				$step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_approve_select ,$conn);
				$params_edit = array($crstm_approve_nbr);
				$sql_edit = "UPDATE crstm_mstr SET ".
				" crstm_reviewer_date = '$curr_date' ,".
				" crstm_step_code = '$crstm_approve_select' ,".
				" crstm_step_name = '$step_name' ".
				" WHERE crstm_nbr = ? ";
				$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				if($result_edit) {
					$r="1";
					$errortxt="Reject success.";
					$nb=encrypt($crstm_approve_nbr, $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="Reject fail.";
				}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} ///// Reject --- > Sale
	
	} 
	else {
		$r="0";
		$nb="";
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?>