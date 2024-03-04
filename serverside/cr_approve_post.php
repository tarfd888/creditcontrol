<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");

session_start();

date_default_timezone_set('Asia/Bangkok');
$today = date("Y-m-d H:i:s"); 
$curr_date = ymd(date("d/m/Y"));

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
	}
	else {
		//Use Double Cookie for recheck CSRF
		$sessionid = session_id();
		$cr_approve_mail =  html_escape($_COOKIE['cr_approve_mail']);
		$sessionid_dec = decrypt($cr_approve_mail, $key);
		if ($sessionid != $sessionid_dec) {
			setcookie ("cr_approve_mail", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			$r="0";
			$errortxt="<span style='color:red'>** ไม่สามารถอนุมัติได้ **</span>";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
			exit;
		}
		else {
		setcookie ("cr_approve_mail", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		
		$crstm_approved_by = decrypt(mssql_escape($_POST['crstm_approved_by']), $dbkey);
		$crstm_approve_nbr = decrypt(mssql_escape($_POST['crstm_approve_nbr']), $dbkey);
		$crstm_approve_select = decrypt(mssql_escape($_POST['crstm_approve_select']), $dbkey);
		$auth_appr =  strtoupper(explode("@",$crstm_approved_by)[0]);
			
		$params = array($crstm_approve_nbr);
			$query_detail = "SELECT * FROM crstm_mstr where crstm_nbr = ? ";
			$result_detail = sqlsrv_query($conn, $query_detail,$params);
			$rec_crstm = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_crstm) {
				$crstm_user = html_clear(strtolower($rec_crstm['crstm_user']));
				$crstm_tel = html_clear($rec_crstm['crstm_tel']);
				$crstm_email_app1 = html_clear($rec_crstm['crstm_email_app1']);
				$crstm_email_app2 = html_clear($rec_crstm['crstm_email_app2']);
				$crstm_email_app3 = html_clear($rec_crstm['crstm_email_app3']);
				$crstm_cus_name = html_clear($rec_crstm['crstm_cus_name']);
				$crstm_stamp_app1 = html_clear($rec_crstm['crstm_stamp_app1']);
				$crstm_stamp_app2 = html_clear($rec_crstm['crstm_stamp_app2']);
				$crstm_stamp_app3 = html_clear($rec_crstm['crstm_stamp_app3']);
			}
			if(($crstm_stamp_app1 == $crstm_approved_by) || ($crstm_stamp_app2 == $crstm_approved_by) || ($crstm_stamp_app3 == $crstm_approved_by)) { // เช็คเอกสารว่ามีการ approve หรือยัง
				$r="0";
				$errortxt="<span style='color:red'><h4 style='text-align:center'>*** เอกสารฉบับนี้ ได้ถูกอนุมัติไปแล้ว *** <h4></span>";
				echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
				exit;
			}	
			$params = array($crstm_user);
			$query_emp_detail = "SELECT emp_email_bus,emp_prefix_th_name,emp_th_firstname,emp_th_lastname FROM emp_mstr where emp_scg_emp_id = ? ";
			$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
			$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_emp) {
				$sale_email = html_clear(strtolower($rec_emp['emp_email_bus']));
				$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
				$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
				$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
				$sale_fullname = $emp_prefix_th_name . $emp_th_firstname ." ". $emp_th_lastname;
			}
		///// Reviewer approve ระบบส่งเมลจาก sale ไปถึง cr1 Send email Sales ---> Cr1 เ
		if ($crstm_approve_select=="111") {   // reviewer approve 

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
				
				if (isservonline($smtp)) { $can_sendmail=true;}
				else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
				}
				$mail_from = $sale_fullname;
				$mail_from_email = $sale_email;
				$mail_to = $cr_next_curprocessor_email;
				$mail_subject = "Credit 1 โปรดดำเนินการ: ใบขออนุมัติวงเงิน เลขที่ " ." $crstm_nbr ". " ลูกค้า $crstm_cus_name ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน แผนกสินเชื่อ (Credit 1)<br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name <br>
				Credit 1 : โปรดดำเนินการในระบบ Credit Control ด้วยค่ะ  <br><br>
				$sale_fullname เบอร์โทร  $crstm_tel และอีเมล $sale_email<br><br>
				
				 ขอบคุณค่ะ </font>";	
				
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
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : $crstm_cus_name  ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ ";
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
				$step_name_cr1 = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", "10" ,$conn);
				$params_edit = array($crstm_approve_nbr);
				$sql_edit = "UPDATE crstm_mstr SET ".
				" crstm_reviewer_date = '$curr_date' ,".
				" crstm_step_code = '10' ,".
				" crstm_step_name = '$step_name_cr1' ".
				" WHERE crstm_nbr = ? ";
				$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				if($result_edit) {
					$r="1";
					$errortxt="Upldate success.";
					$nb=encrypt($crstm_approve_nbr, $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="Upldate fail.";
				}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} // reviewer approve 
		
		///// Revise --- > Sale
		if ($crstm_approve_select=="112") {   // Revise
				//ดึงรายชื่อ email ของคนที่มี role Action_View1 ทุกคน
				$params = array($crstm_approved_by);
				$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
				$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
				$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
				if ($rec_emp) {
					$reviewer_email = html_clear(strtolower($rec_emp['emp_email_bus']));
					$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
					$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
					$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
					$reviewer_fullname = $emp_th_firstname ." ".$emp_th_lastname;
				}
					
					if (isservonline($smtp)) { $can_sendmail=true;}
					else {
						$can_sendmail=false;
						$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
					}
				
			// Reviewer ส่งอีเมล  --->Sale กลับไปแก้ไข
			if($sale_email!="") {
				$mail_from = $reviewer_fullname;
				$mail_from_email = $reviewer_email;
				$mail_to = $sale_email;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : $crstm_cus_name  กรุณาตรวจสอบแก้ไขใหม่ ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $sale_fullname <br><br>
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
				" crstm_step_code = '$crstm_approve_select' ,".
				" crstm_step_name = '$step_name' ".
				" WHERE crstm_nbr = ? ";
				$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				if($result_edit) {
					$r="1";
					$errortxt="Upldate success.";
					$nb=encrypt($crstm_approve_nbr, $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="Upldate fail.";
				}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} ///// Revise --- > Sale
		
		///// อนก. อนุมัติ email 
		if ($crstm_approve_select=="60") {   // เคส อนก. อนุมัติผ่านเมล
				$crstm_approved_by = $crstm_approved_by;
					//เก็บประวัติการดำเนินการ
				$cr_ap_f_step = "50";  
				$cr_ap_t_step = $crstm_approve_select; // FinCR Mgr to submit
				if ($crstm_approve_select=="60") {
					$cr_ap_text = "Submited for final approval";
				}else {
					$cr_ap_text = "Submited for initial approved";
				}
				$cr_ap_remark = "";		
					
				$cr_ap_id = getnewappnewid($crstm_approve_nbr,$conn);
					
				$sql = "INSERT INTO  crctrl_approval (" . 
				" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
				" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$auth_appr','$today')";				

				$result = sqlsrv_query($conn, $sql);
				$all_email = $sale_email.",".$mail_credit_email.",".$mail_mgr_credit;
								
					if (isservonline($smtp)) { $can_sendmail=true;}
					else {
						$can_sendmail=false;
						$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
					}
			
			if($crstm_approved_by!="") {
				$mail_from = $mail_from_text;
				$mail_from_email = $mail_credit_email ;
				$mail_to = $all_email;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : ".$crstm_cus_name. " ได้รับการอนุมัติแล้ว ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $sale_fullname <br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name <br><br>
				<span style='color:green'><strong>*** ได้รับการอนุมัติแล้วค่ะ ***</strong> </span><br><br>
				ขอบคุณค่ะ</font>";
				$mail_message .= "<br>" .$mail_no_reply;
				if($mail_to!="") {
					$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
				
				$step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_approve_select ,$conn);
				$params_edit = array($crstm_approve_nbr);
				$sql_edit = "UPDATE crstm_mstr SET ".
				" crstm_step_code = '$crstm_approve_select' ,".
				" crstm_step_name = '$step_name' ".
				" WHERE crstm_nbr = ? ";
				$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				
				if ($crstm_approved_by == $crstm_email_app1) {
					$params_edit = array($crstm_approve_nbr);
					$sql_edit = "UPDATE crstm_mstr SET ".
					" crstm_stamp_app1_date = '$curr_date', ".
					" crstm_stamp_app1 = '$crstm_approved_by' ".
					" WHERE crstm_nbr = ? ";
					$result_edit1 = sqlsrv_query($conn,$sql_edit,$params_edit);
					$crstm_stamp_app1 = findsqlval("crstm_mstr", "crstm_stamp_app1", "crstm_nbr", $crstm_approve_nbr ,$conn);
				} 
			}	
				if($result_edit) {
					$r="1";
					$errortxt="Upldate success.";
					$nb=encrypt($crstm_approve_nbr, $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="Upldate fail.";
				}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} ///// อนก. อนุมัติ email 

		///// อนก. อนุมัติ email 
		if ($crstm_approve_select=="600" ) {   // เคส อนก. คก.สช อนุมัติผ่านเมล
			$crstm_approved_by = $crstm_approved_by;
				//เก็บประวัติการดำเนินการ
			$cr_ap_f_step = "50";  
			$cr_ap_t_step = $crstm_approve_select; // FinCR Mgr to submit
			$cr_ap_text = "Submited for final approval";
			$cr_ap_remark = "";		
				
			$cr_ap_id = getnewappnewid($crstm_approve_nbr,$conn);
				
			$sql = "INSERT INTO  crctrl_approval (" . 
			" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
			" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$auth_appr','$today')";				

			$result = sqlsrv_query($conn, $sql);
			//$all_email = $sale_email.",".$mail_credit_email;
			$all_email = $sale_email.",".$mail_credit_email.",".$mail_mgr_credit;
			
				if (isservonline($smtp)) { $can_sendmail=true;}
				else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
				}
				if ($crstm_approved_by == $crstm_email_app1) {
					$params_edit = array($crstm_approve_nbr);
					$sql_edit = "UPDATE crstm_mstr SET ".
					" crstm_stamp_app1_date = '$curr_date', ".
					" crstm_stamp_app1 = '$crstm_approved_by' ".
					" WHERE crstm_nbr = ? ";
					$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				} 
				if ($crstm_approved_by == $crstm_email_app2) {
					$params_edit = array($crstm_approve_nbr);
					$sql_edit = "UPDATE crstm_mstr SET ".
					" crstm_stamp_app2_date = '$curr_date', ".
					" crstm_stamp_app2 = '$crstm_approved_by' ".
					" WHERE crstm_nbr = ? ";
					$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				}
				if ($crstm_approved_by == $crstm_email_app3) {
					$params_edit = array($crstm_approve_nbr);
					$sql_edit = "UPDATE crstm_mstr SET ".
					" crstm_stamp_app3_date = '$curr_date', ".
					" crstm_stamp_app3 = '$crstm_approved_by' ".
					" WHERE crstm_nbr = ? ";
					$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				}
				$crstm_stamp_app1 = findsqlval("crstm_mstr", "crstm_stamp_app1", "crstm_nbr", $crstm_approve_nbr ,$conn);
				$crstm_stamp_app2 = findsqlval("crstm_mstr", "crstm_stamp_app2", "crstm_nbr", $crstm_approve_nbr ,$conn);
				$crstm_stamp_app3 = findsqlval("crstm_mstr", "crstm_stamp_app3", "crstm_nbr", $crstm_approve_nbr ,$conn);

				if ($crstm_stamp_app1 !="" && $crstm_stamp_app2 != "" && $crstm_stamp_app3 != ""){
					$crsta_step_name = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", '60' ,$conn);
					$params_edit = array($crstm_approve_nbr);
					$sql_edit = "UPDATE crstm_mstr SET ".
					" crstm_step_code = '60' ,".
					" crstm_step_name = '$crsta_step_name' ".
					" WHERE crstm_nbr = ? ";
					 $result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
					 
					// ส่งเมลแจ้ง sale ได้รับการอนุมัติแล้ว
					 if($crstm_approved_by!="") {
						$mail_from = $mail_from_text;
						$mail_from_email = $mail_credit_email ;
						$mail_to = $all_email;
						$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : ".$crstm_cus_name. "ได้รับการอนุมัติแล้ว ";
						$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $sale_fullname <br><br>
						ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name <br><br>
						<span style='color:green'><strong>*** ได้รับการอนุมัติแล้วค่ะ ***</strong> </span><br><br>
						ขอบคุณค่ะ</font>";
						$mail_message .= "<br>" .$mail_no_reply;
						if($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
					} 
				}	
				if($result_edit) {
					$r="1";
					$errortxt="Upldate success.";
					$nb=encrypt($crstm_approve_nbr, $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="Upldate fail.";
				}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} ///// อนก. อนุมัติ email 

		if ($crstm_approve_select=="61" ) {   // เคส อนก. คบ อนุมัติผ่านเมล
			$crstm_approved_by = $crstm_approved_by;
				//เก็บประวัติการดำเนินการ
			$cr_ap_f_step = "50";  
			$cr_ap_t_step = $crstm_approve_select; // FinCR Mgr to submit
			if ($crstm_approve_select=="60") {
				$cr_ap_text = "Submited for final approval";
			}else {
				$cr_ap_text = "Submited for initial approved";
			}
			$cr_ap_remark = "";		
				
			$cr_ap_id = getnewappnewid($crstm_approve_nbr,$conn);
				
			$sql = "INSERT INTO  crctrl_approval (" . 
			" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
			" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$auth_appr','$today')";				

			$result = sqlsrv_query($conn, $sql);
			//$all_email = $sale_email.",".$mail_credit_email;
			$all_email = $sale_email.",".$mail_credit_email.",".$mail_mgr_credit;
			
				if (isservonline($smtp)) { $can_sendmail=true;}
				else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
				}
				if ($crstm_approved_by == $crstm_email_app1) {
					$params_edit = array($crstm_approve_nbr);
					$sql_edit = "UPDATE crstm_mstr SET ".
					" crstm_stamp_app1_date = '$curr_date', ".
					" crstm_stamp_app1 = '$crstm_approved_by' ".
					" WHERE crstm_nbr = ? ";
					$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				} 
				if ($crstm_approved_by == $crstm_email_app2) {
					$params_edit = array($crstm_approve_nbr);
					$sql_edit = "UPDATE crstm_mstr SET ".
					" crstm_stamp_app2_date = '$curr_date', ".
					" crstm_stamp_app2 = '$crstm_approved_by' ".
					" WHERE crstm_nbr = ? ";
					$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				}
				if ($crstm_approved_by == $crstm_email_app3) {
					$params_edit = array($crstm_approve_nbr);
					$sql_edit = "UPDATE crstm_mstr SET ".
					" crstm_stamp_app3_date = '$curr_date', ".
					" crstm_stamp_app3 = '$crstm_approved_by' ".
					" WHERE crstm_nbr = ? ";
					$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				}
				$crstm_stamp_app1 = findsqlval("crstm_mstr", "crstm_stamp_app1", "crstm_nbr", $crstm_approve_nbr ,$conn);
				$crstm_stamp_app2 = findsqlval("crstm_mstr", "crstm_stamp_app2", "crstm_nbr", $crstm_approve_nbr ,$conn);
				$crstm_stamp_app3 = findsqlval("crstm_mstr", "crstm_stamp_app3", "crstm_nbr", $crstm_approve_nbr ,$conn);

				if ($crstm_stamp_app1 !="" && $crstm_stamp_app2 != "" && $crstm_stamp_app3 != ""){
					$crsta_step_name = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", '61' ,$conn);
					$params_edit = array($crstm_approve_nbr);
					$sql_edit = "UPDATE crstm_mstr SET ".
					" crstm_step_code = '61' ,".
					" crstm_step_name = '$crsta_step_name' ".
					" WHERE crstm_nbr = ? ";
					 $result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
					 
					// ส่งเมลแจ้ง sale ได้รับการอนุมัติแล้ว
					 if($crstm_approved_by!="") {
						$mail_from = $mail_from_text;
						$mail_from_email = $mail_credit_email ;
						$mail_to = $all_email;
						$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : ".$crstm_cus_name. "ได้รับการอนุมัติแล้ว ";
						$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $sale_fullname <br><br>
						ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name <br><br>
						<span style='color:green'><strong>*** ได้รับการอนุมัติแล้วค่ะ ***</strong> </span><br><br>
						ขอบคุณค่ะ</font>";
						$mail_message .= "<br>" .$mail_no_reply;
						if($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
				} 
			}	
				if($result_edit) {
					$r="1";
					$errortxt="Upldate success.";
					$nb=encrypt($crstm_approve_nbr, $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="Upldate fail.";
				}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} ///// อนก. อนุมัติ email 
		
		///// ผู้อนุมัติ --- > Reject
		if ($crstm_approve_select=="690") {   // Reject
			//ดึงรายชื่อ email ของคนที่มี role Action_View1 ทุกคน
			$params = array($crstm_approved_by);
			$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
			$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
			$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_emp) {
				$reviewer_email = html_clear(strtolower($rec_emp['emp_email_bus']));
				$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
				$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
				$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
				$reviewer_fullname = $emp_th_firstname ." ".$emp_th_lastname;
			}
			
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
				
			//  ส่งอีเมล  --->Sale 
			if($sale_email!="") {
				$mail_from = $reviewer_fullname;
				$mail_from_email = $reviewer_email;
				$mail_to = $sale_email;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : $crstm_cus_name  *** ไม่อนุมัติ *** ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $sale_fullname <br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name  *** ไม่อนุมัติ *** <br><br>
				
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
				" crstm_step_code = '$crstm_approve_select' ,".
				" crstm_step_name = '$step_name' ".
				" WHERE crstm_nbr = ? ";
				$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				if($result_edit) {
					$r="1";
					$errortxt="Upldate success.";
					$nb=encrypt($crstm_approve_nbr, $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="Upldate fail.";
				}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} ///// ผู้อนุมัติ --- > Sale
	}
}
?>