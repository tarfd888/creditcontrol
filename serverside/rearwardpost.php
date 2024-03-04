<?php
	//Update can_editing
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	include "../_libs/SimpleImage/simpleimage.php";
	
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
	
	$params = array();
	
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");
	$curr_date = ymd(date("d/m/Y"));
	$errortxt = "";
	$allow_post = false;
	
	$pg = html_escape($_POST['pg']);
	$action = html_escape($_POST['action']);
	$crstm_nbr = html_escape($_POST['crstm_nbr']);
	$crstm_cus_name = html_escape($_POST['crstm_cus_name']);
	$crstm_rem_rearward = html_escape($_POST['crstm_rem_rearward']);
	
	if (inlist("remark_add",$action)) {	
		
		if ($crstm_rem_rearward=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ เหตุผล ]";
		}
	}
	$crstm_reviewer_date = findsqlval("crstm_mstr", "crstm_reviewer_date", "crstm_nbr", $crstm_nbr ,$conn);
	$crstm_step_code = html_escape($_POST['crstm_step_code']);	
	if ($crstm_step_code=="30") {
		$rearward_code = "21";
		$crstm_rem_rearward = $crstm_rem_rearward." ส่งกลับไปให้ Credit 2 จาก ".$user_fullname;
		$crstm_reviewer_date = $crstm_reviewer_date;
	}else if ($crstm_step_code=="21") {	
	    $rearward_code = "20";
		$crstm_rem_rearward = $crstm_rem_rearward." ส่งกลับไปให้ Credit 1 จาก ".$user_fullname;	
		$crstm_reviewer_date = $crstm_reviewer_date;
	}else if ($crstm_step_code=="20") {	
	    $rearward_code = "11";
		$crstm_rem_rearward = $crstm_rem_rearward." ส่งกลับไปให้ Credit 1 จาก ".$user_fullname;
		$crstm_reviewer_date = $crstm_reviewer_date;
	}else if ($crstm_step_code=="11") {	
	    $rearward_code = "10";
		$crstm_rem_rearward = $crstm_rem_rearward." ส่งกลับไปให้ Credit 1 จาก ".$user_fullname;
		$crstm_reviewer_date = $crstm_reviewer_date;
	}else if ($crstm_step_code=="10") {	
	    $rearward_code = "01";
		$crstm_rem_rearward = $crstm_rem_rearward." ส่งกลับไปให้ Sale จาก ".$user_fullname;	
		$crstm_reviewer_date = "";
	}	
		$rearward_name = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", $rearward_code ,$conn);

	
	if (inlist("remark_add",$action)) {	
		
		if (!$errorflag) { 
		
		$params_edit = array($crstm_nbr);
			
			$sql_edit = " UPDATE crstm_mstr SET ".
			"crstm_step_code = '$rearward_code', ".
			"crstm_step_name = '$rearward_name', ".
			"crstm_rem_rearward = '$crstm_rem_rearward', ".
			"crstm_reviewer_date = '$crstm_reviewer_date', ".
			"crstm_chk_rearward = '1' ".
			" WHERE crstm_nbr = ? ";
		
		$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if($result_edit) {
				$r="1";
				$errortxt="Edit success .";
				$nb=encrypt($crstm_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="Edit fail.";
			}
		
		///// Send email Manager ---> Cr2
		if ($crstm_step_code=="30") {
			//ดึงรายชื่อ email ของคนที่มี role Cr2 ทุกคน
			$cr_next_curprocessor_email = "";
			$params = array('Action_View2');
			$sql_aucadmin = "select role_user_login from role_mstr where role_code = ? and role_receive_mail = 1";
			$result_aucadmin = sqlsrv_query( $conn, $sql_aucadmin,$params);											
				while($r_aucadmin = sqlsrv_fetch_array($result_aucadmin, SQLSRV_FETCH_ASSOC)) {
					$aucadmin_user_login = $r_aucadmin['role_user_login'];
					$aucadmin_user_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$aucadmin_user_login,$conn);
					if ($aucadmin_user_email!="") {
						if ($cr_next_curprocessor_email != "") {$cr_next_curprocessor_email = $cr_next_curprocessor_email . ",";}
						$cr_next_curprocessor_email = $cr_next_curprocessor_email . $aucadmin_user_email;
					}
				}
			$params = array($user_login);	
			$sql_emp = "SELECT * from emp_mstr where emp_user_id = ?";
				$result_emp = sqlsrv_query($conn, $sql_emp,$params);	
				$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
					if ($r_emp) {
						$user_fullname = trim($r_emp['emp_prefix_th_name']) . trim($r_emp["emp_th_firstname"]) . " " . trim($r_emp["emp_th_lastname"]);
						$user_email = strtolower($r_emp['emp_email_bus']);
						$user_inform_last_action = $r_emp['emp_inform_last_action'];
						if ($r_emp['emp_inform_last_action'] == "1") {$user_inform_last_action = true;}
							else {$user_inform_last_action = false;} 
						}
							/* else {
								$allow_post = false;
								$r="0";
								$errortxt="1**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ **";
							} */
			
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
				$mail_from = $user_fullname;
				$mail_from_email = $user_email;
				$mail_to = $cr_next_curprocessor_email;
				$mail_subject = "Credit 2 แก้ไข : ใบขออนุมัติวงเงินเลขที่ : $crstm_nbr ลูกค้า  : $crstm_cus_name ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน แผนกสินเชื่อ (Credit 2)<br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_nbr  ลูกค้า $crstm_cus_name <br>
				Credit 2 : โปรดดำเนินการแก้ไข ในระบบ Credit Control ด้วยค่ะ  <br><br>
				$user_fullname  อีเมล $user_email<br><br>
				
				ขอบคุณค่ะ</font>";	
				$mail_message .= "<br>" .$mail_no_reply ;
		
			if ($mail_to!="") {
				$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";
					}
			
				} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";}
		}	
		///// Send email Manager ---> Cr2
		
		///// Send email Cr2 ---> Cr1
		if ($crstm_step_code=="20")  {
			//ดึงรายชื่อ email ของคนที่มี role Action_View1 ทุกคน
			$cr_next_curprocessor_email = "";
			$params = array('Action_View1');
			$sql_aucadmin = "select role_user_login from role_mstr where role_code = ? and role_receive_mail = 1";
			$result_aucadmin = sqlsrv_query( $conn, $sql_aucadmin,$params);											
				while($r_aucadmin = sqlsrv_fetch_array($result_aucadmin, SQLSRV_FETCH_ASSOC)) {
					$aucadmin_user_login = $r_aucadmin['role_user_login'];
					$aucadmin_user_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$aucadmin_user_login,$conn);
					if ($aucadmin_user_email!="") {
						if ($cr_next_curprocessor_email != "") {$cr_next_curprocessor_email = $cr_next_curprocessor_email . ",";}
						$cr_next_curprocessor_email = $cr_next_curprocessor_email . $aucadmin_user_email;
					}
				}
			$params = array($user_login);
			$sql_emp = "SELECT * from emp_mstr where emp_user_id = ?";
				$result_emp = sqlsrv_query($conn, $sql_emp,$params);	
				$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
					if ($r_emp) {
						$user_fullname = trim($r_emp['emp_prefix_th_name']) . trim($r_emp["emp_th_firstname"]) . " " . trim($r_emp["emp_th_lastname"]);
						$user_email = strtolower($r_emp['emp_email_bus']);
						$user_inform_last_action = $r_emp['emp_inform_last_action'];
						if ($r_emp['emp_inform_last_action'] == "1") {$user_inform_last_action = true;}
							else {$user_inform_last_action = false;} 
						}
							/* else {
								$allow_post = false;
								$r="0";
								$errortxt="1**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ **";
							} */
			
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
				$mail_from = $user_fullname;
				$mail_from_email = $user_email;
				$mail_to = $cr_next_curprocessor_email;
				$mail_subject = "Credit 1 โปรดแก้ไข : ใบขออนุมัติวงเงิน : $crstm_nbr ลูกค้า  : $crstm_cus_name ";
				//$mail_message = $detail;
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน แผนกสินเชื่อ (Credit 1)<br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_nbr  ลูกค้า $crstm_cus_name <br><br>
				Credit 1 : โปรดดำเนินการแก้ไข ในระบบ Credit Control ด้วยค่ะ  <br><br>
				$user_fullname  อีเมล $user_email<br><br>
				
				ขอบคุณค่ะ</font>";	
				$mail_message .= "<br>" .$mail_no_reply ;
		
			if ($mail_to!="") {
				$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";
					}
			
				} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";}
		}	
		///// Send email Cr2 ---> Cr1
		
		///// Send email Cr1 ---> Sales
		if ($crstm_step_code=="10")  {
			//ดึงรายชื่อ email ของคนที่มี curprocessor ทุกคน
			$params = array($crstm_nbr);
			$cr_next_curprocessor_email = "";
			$sql_aucadmin = "SELECT crstm_curprocessor From crstm_mstr WHERE (crstm_nbr = ?)";
			$result_aucadmin = sqlsrv_query( $conn, $sql_aucadmin,$params);										
				while($r_aucadmin = sqlsrv_fetch_array($result_aucadmin, SQLSRV_FETCH_ASSOC)) {
					$aucadmin_user_login = $r_aucadmin['crstm_curprocessor'];
					$aucadmin_user_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$aucadmin_user_login,$conn);
					$cr_next_curprocessor_email = $aucadmin_user_email;
					// if ($aucadmin_user_email!="") {
						// if ($cr_next_curprocessor_email != "") {$cr_next_curprocessor_email = $cr_next_curprocessor_email . ",";}
						// $cr_next_curprocessor_email = $cr_next_curprocessor_email . $aucadmin_user_email;
					// }
				}
			$params = array($aucadmin_user_login);
			$sql_emp = "SELECT * from emp_mstr where emp_user_id = ?";
				$result_emp = sqlsrv_query($conn, $sql_emp,$params);	
				$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
					if ($r_emp) {
						$user_fullname = trim($r_emp['emp_prefix_th_name']) . trim($r_emp["emp_th_firstname"]) . " " . trim($r_emp["emp_th_lastname"]);
						$user_email = strtolower($r_emp['emp_email_bus']);
						$user_inform_last_action = $r_emp['emp_inform_last_action'];
						if ($r_emp['emp_inform_last_action'] == "1") {$user_inform_last_action = true;}
							else {$user_inform_last_action = false;} 
						}
			
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			//if ($action =="send_mail") {
				$mail_from = $mail_from_text;
				$mail_from_email = $mail_credit_email;
				$mail_to = $cr_next_curprocessor_email;
				$mail_subject = "Sale โปรดแก้ไข : ใบขออนุมัติวงเงิน : $crstm_nbr ลูกค้า  : $crstm_cus_name ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $user_fullname<br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_nbr  ลูกค้า $crstm_cus_name <br>
				<span style='color:red'> หมายหตุ $crstm_rem_rearward</span><br><br>
				<span style='color:Blue'>Sale : โปรดดำเนินการแก้ไข ในระบบ Credit Control ด้วยค่ะ </span><br><br>
				
				 ขอบคุณค่ะ<br></font>";	
				
				$mail_message .= $mail_no_reply;
		
			if ($mail_to!="") {
				$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";
					}
			
				} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งเจ้าของเอกสารได้<br>";}
			//}
		}	
		///// Send email Cr1 ---> Sales
			
			
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	
	}
?>