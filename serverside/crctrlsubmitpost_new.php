<?php
	//Update can_editing
	include("../_incs/acunx_metaheader.php");
	//include("../_incs/chksession.php");  
	//include "../_incs/acunx_csrf_var.php";
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_libs/SimpleImage/simpleimage.php";
	
	//session_start();
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
		$action_post = html_escape($_POST['action']);
		$step_code = html_escape($_GET['step_code']);
		$crstm_step_code = html_escape(decrypt($step_code, $key));
		$crstm_nbr = mssql_escape($_POST['crstm_nbr']);	
		$crstm_reviewer = mssql_escape($_POST['crstm_reviewer']);
		$crstm_noreviewer = mssql_escape($_POST['crstm_noreviewer']); // กรณีไม่ได้ระบุผู้ตรวจสอบคนที่ 1 ค่าจะเป็น true
		//$crstm_scgc = mssql_escape($_POST['crstm_scgc']);
		//$crstm_email_app1 = mssql_escape($_POST['crstm_email_app1']);
		$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_step_code ,$conn);

		if ($crstm_reviewer == "" && $crstm_noreviewer == False) { //ระบบต้องกำหนดผู้อนุมัติคนที่ 1 ไว้เสมอ
			$r="0";
			$nbr="";
			$errortxt="ระบบไม่ได้กำหนดผู้อนุมัติ";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
			exit;
		}
		
		$params = array($crstm_nbr);
		$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, emp_mstr.emp_th_firstname, ".
		"emp_mstr.emp_th_lastname, emp_mstr.emp_email_bus, emp_mstr.emp_th_pos_name, crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add,  ".
		"crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active,  ".
		"crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt,crstm_mstr.crstm_detail_mail, crstm_mstr.crstm_mgr_reson  ".
		"FROM crstm_mstr INNER JOIN  ".
		"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id  ".
		"WHERE (crstm_mstr.crstm_nbr = ?)";
		
		$result_detail = sqlsrv_query($conn, $query_detail,$params);
		$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
		if ($rec_cus) {
			$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
			$name_from = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
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
			$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
			$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
			
			$crstm_ch_term =  html_clear($rec_cus['crstm_ch_term']);
			$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);
			
			$crstm_cus_nbr =  html_clear($rec_cus['crstm_cus_nbr']);
			$cus_term = findsqlval("cus_mstr", "cus_terms_paymnt", "cus_nbr", $crstm_cus_nbr ,$conn);
			$old_term = findsqlval("term_mstr", "term_desc", "term_code", $cus_term,$conn);
		} 
			$params = array($crstm_reviewer);
			$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
				$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
				$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
					if ($rec_emp) {
						$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
						$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
						$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
						$reviewer_name = html_clear($rec_emp['emp_th_pos_name']);
						$reviewName = $emp_th_firstname." ".$emp_th_lastname;
					} 
				//// จบการ query emp_mstr 
			$params = array($crstm_nbr);
			$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? ";
				$result_cc = sqlsrv_query($conn, $sql_cc,$params);
				while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
				{
					$amt = html_clear($row_cc['tbl3_amt_loc_curr']);
					$txt_ref = html_clear($row_cc['tbl3_txt_ref']);
					
					$gr_tot +=  $amt ;
					if ($txt_ref == "C1") {  // เสนอขอปรับเพิ่มวงเงิน
						$tot_c1 += $amt;
						$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
					} else if ($txt_ref == "C3"){  // เสนอขอต่ออายุวงเงิน
						$tot_cc += $amt;
						$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
					}else if ($txt_ref == "CC"){  // วงเงินปัจจุบัน
						$tot_cc += $amt;
						$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
					}	
				}
				if($crstm_chk_term = "old") {  /// เคสเปลี่ยนเงื่อนไขการชำระเงิน
					$txt_term = "";		
				}else if($crstm_chk_term = "change"){
					$txt_term = "และขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก :  $old_term  เป็น  $change_term";
				}
				if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
					if($crstm_chk_rdo2=="C1"){ // ขอเพิ่มวงเงิน
						$subject = "เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name";		
						$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ ". $crstm_cus_name . "จาก ".number_format($tot_cc )." บาท" ."  เป็น ".number_format($gr_tot)."  บาท "."<br></span> ";																															
						//$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาขออนุมัติปรับเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท <br></span> "." <br> "."$txt_term";																															
										
					}else {  //ขอต่ออายุวงเงิน
						$subject ="เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name "; 		
						$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ ". $crstm_cus_name  .number_format($tot_cc). "  บาท "." 	จนถึงวันที่ "  .$due_date. "<br></span> ";
						//$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name ".number_format($tot_cc)."  บาท 	จนถึงวันที่  $due_date <br></span> "." <br> "."$txt_term";
					}
				}else{
						// ขอเพิ่มวงเงินลูกค้าใหม่
						$subject = "เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name";	
						$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";	
				}	
				
				///// Send email Sales ---> Reviewer
				if ($crstm_step_code=="110") {  // ส่ง Mail ให้ Reviewer ตรวจสอบและอนุมัติ
					$approver1_user_id = $crstm_reviewer;
					//ดึงรายชื่อ email ของคนที่มี role Action_View1 ทุกคน
					$approve_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('111', $dbkey)."' target='_blank'><font color=DarkGreen> ... Approve ... | </font></a>";
					$revise_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('112', $dbkey)."' target='_blank'><font color=Blue>... Revise ... |</font></a>";
					$reject_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('113', $dbkey)."' target='_blank'><font color=Red>... Reject</font></a>";

					if (isservonline($smtp)) { $can_sendmail=true;}
					else {
						$can_sendmail=false;
						$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
					}

					$mail_from = $user_fullname;
					$mail_from_email = $user_email;
					$mail_to =  $crstm_reviewer;
					$mail_subject = $subject;
					$mail_message =	"<font style='font-family:Cordia New;font-size:19px'>เรียน คุณ$reviewName <br>
									$txt_cc
									$txt_term <br>
								
									ตามอำนาจดำเนินการ : $crstm_approve <br><br>
									เหตุผลที่เสนอขอวงเงิน<br>
									&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; $crstm_sd_reson <br><br>
									คลิ๊กเพื่อ   $approve_url  $revise_url  $reject_url</font>";
					$mail_message .= "<br>" .$mail_no_reply;
					
					if ($mail_to!="") {
						$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
						if (!$sendstatus) {
							$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
						}
						
					} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
				//}	
				/////  แจ้งเมลกลับเจ้าของเอกสาร
				if($sendstatus){
					$mail_message = "";
					if (isservonline($smtp)) { $can_sendmail=true;}
					else {
						$can_sendmail=false;
						$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
					}
					$mail_from = "แผนกสินเชื่อ ";
					$mail_from_email = $mail_credit_email;
					$mail_to =  $user_email;
					$mail_subject = "ใบขออนุมัติวงเงิน :" ." $crstm_nbr ". "ถูกส่งให้ผู้ตรวจสอบเรียบร้อยแล้ว รอผลการตรวจสอบ";

					$mail_message =	"<font style='font-family:Cordia New;font-size:19px'>เรียน คุณ$user_fullname <br>
					<br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; ใบขออนุมัติวงเงิน : $crstm_nbr  ถูกส่งให้ผู้ตรวจสอบเรียบร้อยแล้ว รอผลการตรวจสอบ</font>";
				
					$mail_message .= "<br>" .$mail_no_reply ;
					if ($mail_to!="") {
						$sendstatus1 = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
						if (!$sendstatus1) {
							$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
						}
						
					} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
				//}
				
				$params_edit = array($crstm_nbr);
				$sql_edit = "UPDATE crstm_mstr SET ".
				" crstm_step_code = '$crstm_step_code' ,".
				" crstm_step_name = '$crstm_step_name' ".
				" WHERE crstm_nbr = ? ";
				$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if($result_edit) {
						$r="1";
						$errortxt="Upldate success.";
						$nb=encrypt($crstm_nbr, $key);
					}
					else {
						$r="0";
						$nb="";
						$errortxt="Upldate fail.";
						}
				}			
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
			}
			else {
				$r="0";
				$nb="";
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
			}
			///// Send email Sales ---> Reviewer
	}
	else {
		/// ไม่ได้ใช้งานลูปนี้
		$crstm_approved_by = html_escape($_POST['crstm_approved_by']);
		$crstm_approve_nbr = html_escape($_POST['crstm_approve_nbr']);
		$crstm_approve_select = html_escape($_POST['crstm_approve_select']);
		$crstm_cus_name = html_escape($_POST['crstm_cus_name']);
		$params = array($crstm_approve_nbr);
			$query_detail = "SELECT * FROM crstm_mstr where crstm_nbr = ? ";
			$result_detail = sqlsrv_query($conn, $query_detail,$params);
			$rec_crstm = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_crstm) {
				$crstm_user = html_clear(strtolower($rec_crstm['crstm_user']));
				$crstm_tel = html_clear(strtolower($rec_crstm['crstm_tel']));
				$crstm_email_app1 = html_clear($rec_crstm['crstm_email_app1']);
				$crstm_email_app2 = html_clear($rec_crstm['crstm_email_app2']);
			}
		
			$params = array($crstm_user);
			$query_emp_detail = "SELECT * FROM emp_mstr where emp_scg_emp_id = ? ";
			$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
			$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_emp) {
				$sale_email = html_clear(strtolower($rec_emp['emp_email_bus']));
				$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
				$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
				$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
				$sale_fullname = $emp_th_firstname ." ". $emp_th_lastname;
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
				" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$user_login','$today')";				

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
			//}	
			
			//  Cr1 แผนกสินเชือ  --- -> Sale
			if($sale_email!="") {
				$mail_from = "แผนกสินเชื่อ ";
				$mail_from_email = $mail_credit_email;
				$mail_to = $sale_email;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : $crstm_cus_name  ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน  คุณ$sale_fullname <br><br>
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
				" crstm_step_code = '10' ,".
				" crstm_step_name = '$step_name_cr1' ".
				" WHERE crstm_nbr = ? ";
				$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
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
					$reviewer_fullname = $emp_prefix_th_name . $emp_th_firstname." ".$emp_th_lastname;
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
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน คุณ$sale_fullname <br><br>
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
				$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
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
				" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$user_login','$today')";				

				$result = sqlsrv_query($conn, $sql);
				//$all_email = $sale_email.",".$mail_credit_email;
				$all_email = $sale_email;	
				
					if (isservonline($smtp)) { $can_sendmail=true;}
					else {
						$can_sendmail=false;
						$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
					}
			
			if($crstm_approved_by!="") {
				$mail_from = $mail_from_text;
				$mail_from_email = $mail_credit_email ;
				$mail_to = $all_email;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : $crstm_cus_name  ได้รับการอนุมัติแล้ว ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน คุณ $sale_fullname <br><br>
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
				" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$user_login','$today')";				

				$result = sqlsrv_query($conn, $sql);
				//$all_email = $sale_email.",".$mail_credit_email;
				$all_email = $sale_email;	
				
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
				$crstm_stamp_app1 = findsqlval("crstm_mstr", "crstm_stamp_app1", "crstm_nbr", $crstm_approve_nbr ,$conn);
				$crstm_stamp_app2 = findsqlval("crstm_mstr", "crstm_stamp_app2", "crstm_nbr", $crstm_approve_nbr ,$conn);

				if ($crstm_stamp_app1 !="" && $crstm_stamp_app2 != ""){
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
						$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : $crstm_cus_name  ได้รับการอนุมัติแล้ว ";
						$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน คุณ $sale_fullname <br><br>
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
				" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$user_login','$today')";				

				$result = sqlsrv_query($conn, $sql);
				//$all_email = $sale_email.",".$mail_credit_email;
				$all_email = $sale_email;	
				
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
				$crstm_stamp_app1 = findsqlval("crstm_mstr", "crstm_stamp_app1", "crstm_nbr", $crstm_approve_nbr ,$conn);
				$crstm_stamp_app2 = findsqlval("crstm_mstr", "crstm_stamp_app2", "crstm_nbr", $crstm_approve_nbr ,$conn);

				if ($crstm_stamp_app1 !="" && $crstm_stamp_app2 != ""){
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
						$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr : $crstm_cus_name  ได้รับการอนุมัติแล้ว ";
						$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน คุณ $sale_fullname <br><br>
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
	}
?>