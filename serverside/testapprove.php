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
//$crstm_nbr = html_escape($_POST['crstm_nbr']);

if ($action == 'crctrlapp_edit') { //post มาจาก form approve 
			$crstm_nbr = html_escape($_POST['crstm_nbr']);
	
			//ยืนยัน current processor อีกครั้ง กรณีที่มีคนที่ไม่ใช่ current processor login เข้ามาอีก page
			$params = array($crstm_nbr);
			$sql_cr = "SELECT * from crstm_mstr where crstm_nbr = ? and crstm_step_code = '30'"; // FinCR Mgr approve
			$result_cr = sqlsrv_query($conn, $sql_cr,$params);	
			$r_cr = sqlsrv_fetch_array($result_cr, SQLSRV_FETCH_ASSOC);
			if ($r_cr) {
				$crstm_user = html_clear($r_cr['crstm_user']);
				//$name_from = trim($rec_cus['emp_prefix_th_name']) . trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
				$name_from = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_scg_emp_id",$crstm_user,$conn);	
				$emp_th_pos_name = findsqlval("emp_mstr","emp_th_pos_name","emp_scg_emp_id",$crstm_user,$conn);	
				
				$crstm_cus_name = html_clear($r_cr['crstm_cus_name']);
				$crstm_sd_reson = html_clear($r_cr['crstm_sd_reson']);
				$crstm_chk_rdo2 = html_clear($r_cr['crstm_chk_rdo2']);
				$crstm_approve = html_clear($r_cr['crstm_approve']);
				$crstm_cc1_reson = html_clear($r_cr['crstm_cc1_reson']);
				$crstm_cc2_reson = html_clear($r_cr['crstm_cc2_reson']);
				$crstm_mgr_reson = html_clear($r_cr['crstm_mgr_reson']);
				$crstm_cr_mgr = html_clear(number_format($r_cr['crstm_cr_mgr']));
				$crstm_cus_active = html_clear($r_cr['crstm_cus_active']);
				$crstm_chk_term = html_clear($r_cr['crstm_chk_term']);
				
				$terms_paymnt = html_clear($r_cr['cus_terms_paymnt']);
				$old_term = findsqlval("term_mstr", "term_desc", "term_code", $terms_paymnt ,$conn);

				$crstm_cc_amt = html_clear($r_cr['crstm_cc_amt']);
				$crstm_cc_date_beg = dmytx(html_clear($r_cr['crstm_cc_date_beg']));
				$crstm_cc_date_end = dmytx(html_clear($r_cr['crstm_cc_date_end']));
		
				$crstm_ch_term =  html_clear($r_cr['crstm_ch_term']);
				$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);		

				$crstm_reviewer = strtolower(html_clear($r_cr['crstm_reviewer']));
				$crstm_noreviewer = html_clear($r_cr['crstm_noreviewer']);
				$crstm_scgc = html_clear($r_cr['crstm_scgc']);
				$crstm_email_app1 = html_clear($r_cr['crstm_email_app1']);
				$crstm_email_app2 = html_clear($r_cr['crstm_email_app2']);
				$email_app_to1 =  $crstm_email_app1;
				$email_app_to2 =  $crstm_email_app2;
				
				$crstm_approve_code = html_clear($r_cr['crstm_approve_code']);
				
				$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", '50' ,$conn);
				
				$params = array($crstm_nbr);
				$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? ";
				$result_cc = sqlsrv_query($conn, $sql_cc,$params);
				
				while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
				{
					$amt = html_clear($row_cc['tbl3_amt_loc_curr']);
					$txt_ref = html_clear($row_cc['tbl3_txt_ref']);
					
					$gr_tot +=  $amt ;
					if ($txt_ref == "C1") {
						$tot_c1 += $amt;
						$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
					} else if ($txt_ref == "C3"){
						$tot_cc += $amt;
						$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
					}else if ($txt_ref == "CC"){
						$tot_cc += $amt;
						$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
					}	
				}
	
				if($crstm_chk_term == "old") {  /// เคสเปลี่ยนเงื่อนไขการชำระเงิน
					$txt_term = "";		
				}else if($crstm_chk_term == "change"){
					$txt_term = "และขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก :  $old_term  เป็น  $change_term <br>" ;
				}
				if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
					if($crstm_chk_rdo2=="C1"){ // ขอเพิ่มวงเงิน
						$subject = "เพื่อพิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name";
						if($crstm_approve != "คณะกรรมการบริหารอนุมัติ"){
							$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท <br></span> ";																															
						}else{
							$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดพิจารณาให้ความเห็นชอบก่อนนำเสนอ คบ.  พิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท  หลังจาก คก.สช. ให้ความเห็นชอบ ทางสินเชื่อจะเสนอ memo ให้ทาง คบ. อนุมัติ อีกครั้ง<br></span> ";																															
						}						
					}else {  //ขอต่ออายุวงเงิน
						$subject ="เพื่อพิจารณาอนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name "; 	
						if($crstm_approve != "คณะกรรมการบริหารอนุมัติ"){
							$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาอนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name ".number_format($tot_cc)."  บาท 	จนถึงวันที่  $due_date <br></span> ";
						}else{
							$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดพิจารณาให้ความเห็นชอบก่อนนำเสนอ คบ.  พิจารณาอนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name วงเงิน ".number_format($tot_cc )." บาท   จนถึงวันที่ $due_date หลังจาก คก.สช. ให้ความเห็นชอบ ทางสินเชื่อจะเสนอ memo ให้ทาง คบ. อนุมัติ อีกครั้ง<br></span> ";																															
						}
					}
				}else {
						// ขอเพิ่มวงเงินลูกค้าใหม่
						$subject = "เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name";	
						$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";	
				}
	
				switch($crstm_approve) {
					case "ผส. อนุมัติ":
						$author_to = findsqlval("emp_mstr", "emp_th_pos_name", "emp_email_bus", $crstm_email_app1 ,$conn);
						$step_app = "60";
						break;
					case "ผฝ. อนุมัติ":
						$author_to = findsqlval("emp_mstr", "emp_th_pos_name", "emp_email_bus", $crstm_email_app1 ,$conn);
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
	
				//// ผู้จัดการสินเชื่อมีสิทธิ์อนุมัติ
				if (inlist($user_role,'FinCR Mgr')) {
					$allow_post = true;
				}
				else {
					//คุณไม่มีสิทธิ์อนุมัติเอกสารฉบัยนี้ค่ะ
					$allow_post = false;
					$r="0";
					$errortxt="<span style='color:red'>**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบัยนี้ค่ะ**</span>";
					echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.encrypt($crstm_nbr, $key).'","pg":"'.$pg.'"}';
					exit();
				}
			}
			else {
				//เอกสารไม่อยู่ในสถานะรออนุมัติแล้วค่ะ
				$allow_post = false;
				$r="0";
				$errortxt="<span style='color:red'>**เอกสารไม่อยู่ในสถานะรออนุมัติแล้วค่ะ**</span>";
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.encrypt($rc_nbr, $key).'","pg":"'.$pg.'"}';
				exit();
			}
		}
		else {
			$allow_post = false;
			echo "System not allow direct access!!";
			exit;
		}	

if (!$allow_post) {
		$r="0";
		$errortxt ="Error Mail";
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.encrypt($rc_nbr, $key).'","pg":"'.$pg.'"}';
	}
	else {
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
			
		//////////////////////////////
		$last_app_array = array();
		array_push($last_app_array,$crstm_email_app1);
		if($crstm_email_app2!="") {array_push($last_app_array,$crstm_email_app2);}
		
		$last_app_count = count($last_app_array);
		if($last_app_count >0) {
			foreach ($last_app_array as $value) {
				$app_next_curprocessor_email = $value;
				if ($app_next_curprocessor_email != "") {
					$approve_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?auth=".$crstm_approve_code."&nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($app_next_curprocessor_email, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color=green> Approve</font></a>";
					$reject_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?auth=".$crstm_approve_code."&nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($app_next_curprocessor_email, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color=red> Reject </font></a>";
					
					// The email send to the approve
					$detail_app = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $author_to <br><br>
					$txt_cc
					$txt_term
					ตามอำนาจดำเนินการ :  $crstm_approve <br><br>
					รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br>
					$crstm_detail_mail <br><br>
					
					คลิ๊กเพื่อ   $approve_url  $reject_url<br><br>

					จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br>
					$name_from <br>
					$emp_th_pos_name <br></font>"; 
			
				//// ส่งอีเมลไปหาผู้อนุมัติคนที่ 1 crstm_email_app1
					$fileattach = array();
					$fileattach_mailname = array();
					$fileattach_del_on_end = array();
					$output_folder = $downloadpath."/SALES/";
					$strpm_output_filename = $crstm_nbr.".pdf";

					if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
						array_push($fileattach,$output_folder.printMailapp($crstm_nbr,"WORK_PDF",false,true,$conn));
						array_push($fileattach_mailname,$crstm_nbr."-ความเห็นสินเชื่อ.pdf");
						array_push($fileattach,$output_folder.printpageform($crstm_nbr,"WORK_FORM",false,true,$conn));
						array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
					}else{
						array_push($fileattach,$output_folder.printMailapp($crstm_nbr,"WORK_PDF",false,true,$conn));
						array_push($fileattach_mailname,$crstm_nbr."-ความเห็นสินเชื่อ.pdf");
						array_push($fileattach,$output_folder.printpageform_new($crstm_nbr,"WORK_CR_NUMBER",false,true,$conn));
						array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
					}
					
					$my_files = $fileattach;
					$my_filesname = $fileattach_mailname;
					$mail_from = $mail_from_text_app; //$user_fullname;
					$mail_from_email = $mail_credit_email; //$email_mgr;
					$mail_to = $email_app_to1;
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
			}
		}	
		
		/* if($email_bus!="") {
				////ดึงรายชื่อ email ของคนที่มี role Action_View1 ทุกคน
				$params = array('Action_View1');	
				$cr_Mgr_email = "";
				$sql = "select role_user_login from role_mstr where role_code = ? and role_receive_mail = 1";
				$result_mgr = sqlsrv_query( $conn, $sql,$params);	
			
				while($r_mgr = sqlsrv_fetch_array($result_mgr, SQLSRV_FETCH_ASSOC)) {
					$cr1_user_login = $r_mgr['role_user_login'];
					$cr_Mgr_email = findsqlval("emp_mstr","emp_manager_email","emp_user_id",$cr1_user_login,$conn);
					$cr_Mgr_email = strtolower($cr_Mgr_email);
					// if ($aucadmin_user_email!="") {
						// if ($cr_next_curprocessor_email != "") {$cr_next_curprocessor_email = $cr_next_curprocessor_email . ",";}
						// $cr_next_curprocessor_email = $cr_next_curprocessor_email . $aucadmin_user_email;
					// }
				}
				// ส่งอีเมลแจ้งเจ้าของเอกสาร  The email send to the owner of the document.
				$detail_sale = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $name_from <br>
				<span style='color: Blue'><br>** ใบขออนุมัติวงเงิน เลขที่ $crstm_nbr  ลูกค้า $crstm_cus_name ได้ถูกส่งไปขออนุมัติแล้ว รอผลการอนุมัติ โดยมีรายละเอียดตามเอกสารแนบ **<br><br></span>
				
				ขอบคุณค่ะ</font>"; 

				$fileattach = array();
				$fileattach_mailname = array();
				$fileattach_del_on_end = array();
				$output_folder = $downloadpath."/SALES/";
				$strpm_output_filename = $crstm_nbr.".pdf";

				if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
					array_push($fileattach,$output_folder.printMailapp($crstm_nbr,"WORK_PDF",false,true,$conn));
					array_push($fileattach_mailname,$crstm_nbr."-ความเห็นสินเชื่อ.pdf");
					array_push($fileattach,$output_folder.printpageform($crstm_nbr,"WORK_FORM",false,true,$conn));
					array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
				}else{
					array_push($fileattach,$output_folder.printMailapp($crstm_nbr,"WORK_PDF",false,true,$conn));
					array_push($fileattach_mailname,$crstm_nbr."-ความเห็นสินเชื่อ.pdf");
					array_push($fileattach,$output_folder.printpageform_new($crstm_nbr,"WORK_CR_NUMBER",false,true,$conn));
					array_push($fileattach_mailname,$crstm_nbr."-ใบขออนุมัติ.pdf");
				}
				//$cr_all_email = $email_bus.","."credit@scg.com,".$cr_mgr_email;
				$cr_all_email = $email_bus.",".$mail_credit_email;
				
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
			}  */
		//////////////////////////////
		}	
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?>