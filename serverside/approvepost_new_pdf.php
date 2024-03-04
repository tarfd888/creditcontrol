<?php
//Update can_editing
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/funcCrform.php");
include("../_incs/funcAppform.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

clearstatcache();
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
	$errorflag = false;

	$action = html_escape($_POST['action']);
		
	$step_code = html_escape($_GET['step_code']);
	$crstm_step_code = html_escape(decrypt($step_code, $key));
	$formid = html_escape($_GET['formid']);
	
	//--1. Parameter From approve.php
	$crstm_nbr = html_escape($_POST['crstm_nbr']);
	$params = array($crstm_nbr);
	
	$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_chk_rdo1,  ".
                    "crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson,  ".
                    "crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg,  ".
                    "crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_detail_mail, crstm_mstr.crstm_mgr_reson, crstm_mstr.crstm_mail_status, crstm_mstr.crstm_reviewer,  ".
                    "crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc, crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, crstm_mstr.crstm_email_app3, emp_mstr.emp_prefix_th_name, emp_mstr.emp_th_firstname,  ".
                    "emp_mstr.emp_th_lastname, emp_mstr.emp_th_pos_name, emp_mstr.emp_email_bus ".
					"FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					"WHERE (crstm_mstr.crstm_nbr = ?)";
		
	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
		$name_from = trim($rec_cus['emp_prefix_th_name']) . trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
		$email_bus = strtolower($rec_cus['emp_email_bus']);
		$emp_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		$crstm_chk_rdo2 = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_cr_mgr = html_clear(number_format($rec_cus['crstm_cr_mgr']));
		$crstm_cus_active = html_clear($rec_cus['crstm_cus_active']);
		$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);

		$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);
		$gr_tot = $crstm_cc_amt;
		$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
		$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
		
		$crstm_ch_term =  html_clear($rec_cus['crstm_ch_term']);
		
		$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
		$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$crstm_email_app3 = html_clear($rec_cus['crstm_email_app3']);
		$email_mgr = $user_email;
		
		//$email_app_to1 =  $crstm_email_app1;
		//$email_app_to2 =  $crstm_email_app2;
		
		$crstm_cus_nbr =  html_clear($rec_cus['crstm_cus_nbr']);

		$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);		
	} 	

		$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", '50' ,$conn);
		switch($crstm_approve) {
			case "ผส. อนุมัติ":
				$author_to = findsqlval(" emp_mstr", "emp_th_pos_name", "emp_email_bus", $crstm_email_app1 ,$conn);
				$step_app = "60";
				break;
			case "ผฝ. อนุมัติ":
				$author_to = findsqlval(" emp_mstr", "emp_th_pos_name", "emp_email_bus", $crstm_email_app1 ,$conn);
				$step_app = "60";
				break;	
			case "CO. อนุมัติ":
				if($crstm_scgc == true) {
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "60";
				break;
			case "กจก. อนุมัติ":
				if($crstm_scgc == true) {
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "60";
				break;	
			case "คณะกรรมการสินเชื่ออนุมัติ":
				if($crstm_scgc == true) {
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "600";
				break;		
			case "คณะกรรมการบริหารอนุมัติ":	
				if($crstm_scgc == true) {
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "61";
				break;	
			}
	
	// ขอเพิ่มวงเงินลูกค้าใหม่
	$subject = "เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name";	
	//$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br><br></span> ";	
	if($crstm_approve != "คณะกรรมการบริหารอนุมัติ"){
		$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name    เป็น ".number_format($gr_tot)."  บาท <br></span> ";																															
	}else{
		$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดพิจารณาให้ความเห็นชอบก่อนนำเสนอ คบ.  พิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($gr_tot)."  บาท  หลังจาก คก.สช. ให้ความเห็นชอบ ทางสินเชื่อจะเสนอ memo ให้ทาง คบ. อนุมัติ อีกครั้ง<br></span> ";																															
	}	
	
	if (($crstm_step_code=="40")  && ($formid == "frm_send_approve")) {

		if (isservonline($smtp)) { $can_sendmail=true;}
		else {
			$r="0";
			$can_sendmail=false;
			$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
		}
		//เก็บประวัติการดำเนินการ
		$cr_ap_f_step = $crstm_step_code;  
		$cr_ap_t_step = "50"; // FinCR Mgr to submit
		$cr_ap_text = "Submit for FinCR";
		$cr_ap_remark = "";		
			
		$cr_ap_id = getnewappnewid($crstm_nbr,$conn);
			
		$sql = "INSERT INTO  crctrl_approval (" . 
		" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
		" VALUES('$cr_ap_id','$crstm_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$user_login','$today')";				

		$result = sqlsrv_query($conn, $sql);

		if ($crstm_email_app1 != "") {
			$approver1_user_id = $crstm_email_app1;
			$approve_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail_new.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color='DarkGreen'>... Approve ...|</font></a>";
			$reject_url  = "<a href='".$app_url."/crctrlbof/crctrlapprovemail_new.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('690', $dbkey)."' target='_blank'><font color='Red'>... Reject </font></a>";
			$doc_url  = "<a href='".$app_url."/index.php><img src='_images/spacer.gif'></a>";
			$doc_bot =" <a href='javascript:void(0)'></a>";
			
			// The email send to the approve
			$detail_app = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $author_to <br>
			$doc_bot
			$txt_cc
			ตามอำนาจดำเนินการ :  $crstm_approve <br><br>
			รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br>
			$crstm_detail_mail <br><br>
			
			คลิ๊กเพื่อ  &nbsp;&nbsp;$doc_url $approve_url  $reject_url<br><br>

			จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br>
			$name_from <br>
			$emp_th_pos_name <br></font>"; 
		
			// ส่งอีเมลไปหาผู้อนุมัติคนที่ 1 crstm_email_app1
			$fileattach = array();
			$fileattach_mailname = array();
			$output_folder = $downloadpath."SALES/";
			$cr_output_filename1 = $crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf";
			$cr_output_filename = $crstm_nbr."-ใบขออนุมัติ.pdf";
			if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
				array_push($fileattach,$output_folder.printMailapp($crstm_nbr,true,$output_folder,$cr_output_filename1,$conn));
				array_push($fileattach_mailname,$crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf");
				array_push($fileattach,$output_folder.printpageform($crstm_nbr,true,$output_folder,$cr_output_filename,$conn));
				array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
			}else{
				array_push($fileattach,$output_folder.printMailapp_new($crstm_nbr,true,$output_folder,$cr_output_filename1,$conn));
				array_push($fileattach_mailname,$crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf");
				array_push($fileattach,$output_folder.printpageform_new($crstm_nbr,true,$output_folder,$cr_output_filename,$conn));
				array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
			}
			
			$my_files = $fileattach;
			$my_filesname = $fileattach_mailname;
			$mail_from = $mail_from_text_app; //$user_fullname;
			$mail_from_email = $mail_credit_email; //$email_mgr;
			$mail_to = $crstm_email_app1;
			$mail_subject = $subject;
			$mail_message = $detail_app;
			
			if ($mail_to!="") {
				$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					$r="0";	
				} else {$r="1";}
				
			} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
		}
		
		// ส่งอีเมลไปหาผู้อนุมัติคนที่ 2 crstm_email_app2
		if ($crstm_email_app2 != "") {
			// The email send to the approve
			$approver1_user_id = $crstm_email_app2;
			$approve_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail_new.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color='DarkGreen'>... Approve ...|</font></a>";
			$reject_url  = "<a href='".$app_url."/crctrlbof/crctrlapprovemail_new.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color='Red'>... Reject </font></a>";
			$doc_url  = "<a href='".$app_url."/index.php><img src='_images/spacer.gif'></a>";
			$doc_bot =" <a href='javascript:void(0)'></a>";
			
			$detail_app = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $author_to <br>
			$doc_bot
			$txt_cc
			$txt_term
			ตามอำนาจดำเนินการ :  $crstm_approve <br><br>
			รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br>
			$crstm_detail_mail <br><br>
			
			คลิ๊กเพื่อ  &nbsp;&nbsp;$doc_url $approve_url  $reject_url<br><br>

			จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br>
			$name_from <br>
			$emp_th_pos_name <br></font>"; 
			
			$fileattach = array();
			$fileattach_mailname = array();
			$output_folder = $downloadpath."SALES/";
			$cr_output_filename1 = $crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf";
			$cr_output_filename = $crstm_nbr."-ใบขออนุมัติ.pdf";
			
			if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
				array_push($fileattach,$output_folder.$cr_output_filename1);
				array_push($fileattach_mailname,$crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf");
				
				array_push($fileattach,$output_folder.$cr_output_filename);
				array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
			}else{
				array_push($fileattach,$output_folder.$cr_output_filename1);
				array_push($fileattach_mailname,$crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf");
				
				array_push($fileattach,$output_folder.$cr_output_filename);
				array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
			}
			
			$my_files = $fileattach;
			$my_filesname = $fileattach_mailname;
			$mail_from = $mail_from_text_app; //$user_fullname;
			$mail_from_email = $mail_credit_email; //$email_mgr;
			$mail_to = $crstm_email_app2;
			$mail_subject = $subject;
			$mail_message = $detail_app;
			
			if ($mail_to!="") {
				$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					$r="0";	
				} else {$r="1";}
				
			} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
		}
		
		// ส่งอีเมลไปหาผู้อนุมัติคนที่ 3 crstm_email_app3
		if ($crstm_email_app3 != "") {
			// The email send to the approve
			$approver1_user_id = $crstm_email_app3;
			$approve_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail_new.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color='DarkGreen'>... Approve ...|</font></a>";
			$reject_url  = "<a href='".$app_url."/crctrlbof/crctrlapprovemail_new.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color='Red'>... Reject </font></a>";
			$doc_url  = "<a href='".$app_url."/index.php><img src='_images/spacer.gif'></a>";
			$doc_bot =" <a href='javascript:void(0)'></a>";
			
			$detail_app = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $author_to <br>
			$doc_bot
			$txt_cc
			$txt_term
			ตามอำนาจดำเนินการ :  $crstm_approve <br><br>
			รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br>
			$crstm_detail_mail <br><br>
			
			คลิ๊กเพื่อ  &nbsp;&nbsp;$doc_url $approve_url  $reject_url<br><br>

			จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br>
			$name_from <br>
			$emp_th_pos_name <br></font>"; 
			
			$fileattach = array();
			$fileattach_mailname = array();
			$output_folder = $downloadpath."SALES/";
			$cr_output_filename1 = $crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf";
			$cr_output_filename = $crstm_nbr."-ใบขออนุมัติ.pdf";
			
			if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
				array_push($fileattach,$output_folder.$cr_output_filename1);
				array_push($fileattach_mailname,$crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf");
				
				array_push($fileattach,$output_folder.$cr_output_filename);
				array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
			}else{
				array_push($fileattach,$output_folder.$cr_output_filename1);
				array_push($fileattach_mailname,$crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf");
				
				array_push($fileattach,$output_folder.$cr_output_filename);
				array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
			}
			
			$my_files = $fileattach;
			$my_filesname = $fileattach_mailname;
			$mail_from = $mail_from_text_app; //$user_fullname;
			$mail_from_email = $mail_credit_email; //$email_mgr;
			$mail_to = $crstm_email_app3;
			$mail_subject = $subject;
			$mail_message = $detail_app;
			
			if ($mail_to!="") {
				$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					$r="0";	
				} else {$r="1";}
				
			} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
		}

		if($email_bus!="") {
			//--- ดึงรายชื่อ email ของคนที่มี role Action_View1 ทุกคน
			/* $params = array('Action_View1');	
			$cr_Mgr_email = "";
			$sql = "select role_user_login from role_mstr where role_code = ? and role_receive_mail = 1";
			$result_mgr = sqlsrv_query( $conn, $sql,$params);	
			
				while($r_mgr = sqlsrv_fetch_array($result_mgr, SQLSRV_FETCH_ASSOC)) {
					$cr1_user_login = $r_mgr['role_user_login'];
					$cr_Mgr_email = findsqlval("emp_mstr","emp_manager_email","emp_user_id",$cr1_user_login,$conn);
					$cr_Mgr_email = strtolower($cr_Mgr_email);				
				} */
		
			// ส่งอีเมลแจ้งเจ้าของเอกสาร  The email send to the owner of the document.
			$detail_sale = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $name_from <br>
			<span style='color: Blue'><br>** ใบขออนุมัติวงเงิน เลขที่ $crstm_nbr  ลูกค้า $crstm_cus_name ได้ถูกส่งไปขออนุมัติแล้ว รอผลการอนุมัติ โดยมีรายละเอียดตามเอกสารแนบ **<br><br></span>
			
			ขอบคุณค่ะ</font>"; 

			$fileattach = array();
			$fileattach_mailname = array();
			$output_folder = $downloadpath."SALES/";
			$cr_output_filename1 = $crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf";
			$cr_output_filename = $crstm_nbr."-ใบขออนุมัติ.pdf";
			
			if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
				array_push($fileattach,$output_folder.$cr_output_filename1);
				array_push($fileattach_mailname,$crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf");
				
				array_push($fileattach,$output_folder.$cr_output_filename);
				array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
			}else{
				array_push($fileattach,$output_folder.$cr_output_filename1);
				array_push($fileattach_mailname,$crstm_nbr."-เหตุผลที่เสนอขอวงเงิน.pdf");
				
				array_push($fileattach,$output_folder.$cr_output_filename);
				array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
			}
			
			//$cr_all_email = $email_bus.","."credit@scg.com,".$cr_mgr_email;
			//$cr_all_email = $email_bus.",".$mail_credit_email;
			$cr_all_email = $email_bus.",".$mail_credit_email.",".$mail_mgr_credit; // 04/08/2022
			
			$my_files = $fileattach;
			$my_filesname = $fileattach_mailname;
			$mail_from = $mail_from_text; 
			$mail_from_email = $mail_credit_email; 
			$mail_to = $cr_all_email;  //$email_bus;
			$mail_subject = "ใบขออนุมัติวงเงิน $crstm_nbr : $crstm_cus_name  ได้ถูกส่งไปขออนุมัติแล้วค่ะ ";
			$mail_message = $detail_sale;
			$mail_message .= $mail_no_reply;
				
				if($mail_to!="") {
					$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
						$r="0";	
					} else {
						$params = array($crstm_nbr);	
						$sql_add = "UPDATE crstm_mstr SET ".
						" crstm_step_code = '50', ".
						" crstm_step_name = '$crstm_step_name' ".
						" WHERE crstm_nbr = ? ";
						
						$result_add = sqlsrv_query($conn, $sql_add,$params);
						if ($result_add) {
							$r="1";
							$nb=encrypt($crstm_nbr, $key);
							$errortxt="success.";
						}
						else {
							$r="0";
							$nb="";
							$errortxt="fail.";
						} 
					}
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			}
		}	
	echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
?>