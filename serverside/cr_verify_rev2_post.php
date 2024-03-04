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
		$rev2_verify_csrf_mail =  html_escape($_COOKIE['rev2_verify_csrf_mail']);
		$sessionid_dec = decrypt($rev2_verify_csrf_mail, $key);
		
		if ($sessionid != $sessionid_dec) {
				setcookie ("rev2_verify_csrf_mail", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
				$r="0";
				$errortxt="<span style='color:red'><h4 style='text-align:center'>** ไม่สามารถอนุมัติได้  ** <h4></span>";
				echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
				exit;
			}
		else {
			setcookie ("rev2_verify_csrf_mail", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			$crstm_approved_by = decrypt(mssql_escape($_POST['crstm_approved_by']), $dbkey);
			$crstm_approve_nbr = decrypt(mssql_escape($_POST['crstm_approve_nbr']), $dbkey);
			$crstm_approve_select = decrypt(mssql_escape($_POST['crstm_approve_select']), $dbkey);
			$auth_appr =  strtoupper(explode("@",$crstm_approved_by)[0]);
			
			if ($crstm_approved_by!="" && $crstm_approve_nbr!="" && $crstm_approve_select!="") {
			
				$params = array($crstm_approve_nbr);
				$query_detail = "SELECT crstm_nbr,crstm_user,crstm_tel,crstm_cus_name,crstm_reviewer2,crstm_reviewer2_date,crstm_status,crstm_step_code FROM crstm_mstr where crstm_nbr = ?";
				$result_detail = sqlsrv_query($conn, $query_detail,$params);
				$rec_crstm = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
				if ($rec_crstm) {
					$crstm_nbr = html_clear($rec_crstm['crstm_nbr']);
					$crstm_user = html_clear(strtolower($rec_crstm['crstm_user']));
					$crstm_tel = html_clear($rec_crstm['crstm_tel']);
					$crstm_cus_name = html_clear($rec_crstm['crstm_cus_name']);
					$crstm_reviewer2 = html_clear($rec_crstm['crstm_reviewer2']);
					$crstm_reviewer2_date = html_clear($rec_crstm['crstm_reviewer2_date']);
					$crstm_status = html_clear($rec_crstm['crstm_status']);
					$allow_post = true;
					
					if ($crstm_reviewer2 == $crstm_approved_by)  {
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
						$errortxt="*** คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ  !!  **";
					}
				} 
				
					if(($crstm_reviewer2_date != "") && ($crstm_reviewer2 == $crstm_approved_by)) { // เช็คเอกสารว่ามีการ approve หรือยัง
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
				$errortxt="*** คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ  !!!**";
			}
		}
	}
	
	if ($allow_post) {
	   if ($crstm_approve_select=="221") {   // reviewer2 approve 
	   
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			
			//เก็บประวัติการดำเนินการ
			$cr_ap_f_step = "220";  // Wait Reviewer 2
			$cr_ap_t_step = "221"; // ผู้พิจารณาอนุมัติ
			$cr_ap_text = "Submit for Reviewer2";
			$cr_ap_remark = "";		
			$cr_ap_id = getnewappnewid($crstm_approve_nbr,$conn);
				
			$sql = "INSERT INTO  crctrl_approval (" . 
			" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
			" VALUES('$cr_ap_id','$crstm_approve_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$auth_appr','$today')";				
			$result = sqlsrv_query($conn, $sql);

			$params_edit = array($crstm_approve_nbr);
			$sql_edit = "UPDATE crstm_mstr SET crstm_reviewer2_date = '$curr_date'  WHERE crstm_nbr = ? ";
			$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);

			$params = array($crstm_approve_nbr);
					$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, emp_mstr.emp_th_firstname, ".
					"emp_mstr.emp_th_lastname, emp_mstr.emp_email_bus, emp_mstr.emp_th_pos_name, crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add,  ".
					"crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active,  ".
					"crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt,crstm_mstr.crstm_detail_mail, crstm_mstr.crstm_mgr_reson,  ".
					"crstm_mstr.crstm_mail_status, crstm_mstr.crstm_reviewer, crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc,crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, crstm_mstr.crstm_email_app3 ".
					"FROM crstm_mstr INNER JOIN  ".
					"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id  ".
					"WHERE (crstm_mstr.crstm_nbr = ?)";
					
			$result_detail = sqlsrv_query($conn, $query_detail,$params);
			$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
				if ($rec_cus) {
					$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
					$name_from = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
					$email_sale = strtolower($rec_cus['emp_email_bus']);
					$emp_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
					$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
					$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
					$crstm_chk_rdo2 = html_clear($rec_cus['crstm_chk_rdo2']);
					$crstm_approve = html_clear($rec_cus['crstm_approve']);
					$crstm_cus_active = html_clear($rec_cus['crstm_cus_active']);
					$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
					
					$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);
					$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
					$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
					
					$crstm_detail_mail =  html_clear($rec_cus['crstm_detail_mail']);
					$crstm_ch_term =  html_clear($rec_cus['crstm_ch_term']);
					$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);
					
					$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
					$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
					$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
					$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
					$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
					$crstm_email_app3 = html_clear($rec_cus['crstm_email_app3']);
					//$email_mgr = $user_email;
					
					/////////////$email_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg.","."credit@scg.com";
					//$email_app_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg;
					$email_app_to1 =  $crstm_email_app1;
					$email_app_to2 =  $crstm_email_app2;
					$email_app_to3 =  $crstm_email_app3;
					
					$crstm_cus_nbr =  html_clear($rec_cus['crstm_cus_nbr']);
					$cus_term = findsqlval("cus_mstr", "cus_terms_paymnt", "cus_nbr", $crstm_cus_nbr ,$conn);
					$old_term = findsqlval("term_mstr", "term_desc", "term_code", $cus_term,$conn);
					$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", '50' ,$conn);
				} 
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
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
					$step_app = "60";
					break;
				case "กจก. อนุมัติ":
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
					$step_app = "60";
					break;	
				case "คณะกรรมการสินเชื่ออนุมัติ":
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
					$step_app = "600";
					break;		
				case "คณะกรรมการบริหารอนุมัติ":	
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
					$step_app = "61";
					break;		
				}
				
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
					$txt_term = "<br>";		
				}else if($crstm_chk_term == "change"){
					$txt_term = "และขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก :  $old_term  เป็น  $change_term<br>";
				}
				
				if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
					if($crstm_chk_rdo2=="C1"){ // ขอเพิ่มวงเงิน
						$subject = "เพื่อพิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name";
						if($crstm_approve != "คณะกรรมการบริหารอนุมัติ"){
							$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท <br></span> ";																															
						}else{
							$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดพิจารณาให้ความเห็นชอบก่อนนำเสนอ คบ.  พิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท  หลังจาก คก.สช. ให้ความเห็นชอบ ทางสินเชื่อจะเสนอ memo ให้ทาง คบ. อนุมัติ อีกครั้ง<br></span> ";																															
						}						
					}else {  //ขอต่ออายุวงเงิน
						$subject ="เพื่อพิจารณาอนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name "; 	
						if($crstm_approve != "คณะกรรมการบริหารอนุมัติ"){
							$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาอนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name ".number_format($tot_cc)."  บาท 	จนถึงวันที่  $due_date <br><br></span> ";
						}else{
							$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดพิจารณาให้ความเห็นชอบก่อนนำเสนอ คบ.  พิจารณาอนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name วงเงิน ".number_format($tot_cc )." บาท   จนถึงวันที่ $due_date หลังจาก คก.สช. ให้ความเห็นชอบ ทางสินเชื่อจะเสนอ memo ให้ทาง คบ. อนุมัติ อีกครั้ง<br></span> ";																															
						}
					}
				}else {
						// ขอเพิ่มวงเงินลูกค้าใหม่
						$subject = "เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name";	
						//$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";	
						if($crstm_approve != "คณะกรรมการบริหารอนุมัติ"){
							$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name  เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";																															
						}else{
							$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดพิจารณาให้ความเห็นชอบก่อนนำเสนอ คบ.  พิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name "."  เป็น ".number_format($crstm_cc_amt)."  บาท  หลังจาก คก.สช. ให้ความเห็นชอบ ทางสินเชื่อจะเสนอ memo ให้ทาง คบ. อนุมัติ อีกครั้ง<br></span> ";																															
						}		
				}
			// ส่งอีเมลไปหาผู้อนุมัติคนที่ 1 crstm_email_app1
			if ($crstm_email_app1 != "") {
				$approver1_user_id = $crstm_email_app1;
				
				$approve_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?id=".encrypt($approver1_user_id, $dbkey)."&nbr=".encrypt($crstm_nbr, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color='green'> Approve</font></a>";
				$reject_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?id=".encrypt($approver1_user_id, $dbkey)."&nbr=".encrypt($crstm_nbr, $dbkey)."&act=".encrypt('690', $dbkey)."' target='_blank'><font color='Red'>Reject</font></a>";
				$doc_url  = "<a href='".$app_url."/index.php><img src='_images/spacer.gif'></a>";
				$doc_bot =" <a href='javascript:void(0)'></a>";			
				// The email send to the approve
				
				$detail_app = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $author_to <br><br>
				$doc_bot
				$txt_cc
				$txt_term 
				ตามอำนาจดำเนินการ :  $crstm_approve <br><br>
				รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br>
				$crstm_detail_mail <br><br>
				คลิ๊กเพื่อ   &nbsp;&nbsp;$doc_url $approve_url &nbsp;&nbsp; $reject_url<br><br>

				<font style='font-family:Cordia New;font-size:19px'>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br>
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
				
				if (is_scgemail($mail_to)) {
					$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
						$r="0";	
					} else {$r="1";}
					
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			}
			
			// ส่งอีเมลไปหาผู้อนุมัติคนที่ 2 crstm_email_app2
			if ($crstm_email_app2 != "") {
				$approver1_user_id = $crstm_email_app2;
				$approve_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?id=".encrypt($approver1_user_id, $dbkey)."&nbr=".encrypt($crstm_nbr, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color='green'> Approve</font></a>";
				$reject_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?id=".encrypt($approver1_user_id, $dbkey)."&nbr=".encrypt($crstm_nbr, $dbkey)."&act=".encrypt('690', $dbkey)."' target='_blank'><font color='Red'>Reject</font></a>";
				$doc_url  = "<a href='".$app_url."/index.php><img src='_images/spacer.gif'></a>";
				$doc_bot =" <a href='javascript:void(0)'></a>";							
				// The email send to the approve
				
				$detail_app = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $author_to <br><br>
				$doc_bot
				$txt_cc
				$txt_term 
				ตามอำนาจดำเนินการ :  $crstm_approve <br><br>
				รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br>
				$crstm_detail_mail <br><br>
				คลิ๊กเพื่อ   $doc_url  $approve_url &nbsp;&nbsp; $reject_url<br><br>

				<font style='font-family:Cordia New;font-size:19px'>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br>
				$name_from <br>
				$emp_th_pos_name <br></font>"; 
			
				// ส่งอีเมลไปหาผู้อนุมัติคนที่ 2 crstm_email_app2
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
				
				if (is_scgemail($mail_to)) {
					$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
						$r="0";	
					} else {$r="1";}
					
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			}
			// ส่งอีเมลไปหาผู้อนุมัติคนที่ 3 crstm_email_app3
			if ($crstm_email_app3 != "") {
				$approver1_user_id = $crstm_email_app3;
				$approve_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?id=".encrypt($approver1_user_id, $dbkey)."&nbr=".encrypt($crstm_nbr, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color='green'> Approve</font></a>";
				$reject_url = "<a href='".$app_url."/crctrlbof/crctrlapprovemail.php?id=".encrypt($approver1_user_id, $dbkey)."&nbr=".encrypt($crstm_nbr, $dbkey)."&act=".encrypt('690', $dbkey)."' target='_blank'><font color='Red'>Reject</font></a>";
				$doc_url  = "<a href='".$app_url."/index.php><img src='_images/spacer.gif'></a>";
				$doc_bot =" <a href='javascript:void(0)'></a>";							
				// The email send to the approve
				
				$detail_app = "<font style='font-family:Cordia New;font-size:19px'>เรียน  $author_to <br><br>
				$doc_bot
				$txt_cc
				$txt_term 
				ตามอำนาจดำเนินการ :  $crstm_approve <br><br>
				รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br>
				$crstm_detail_mail <br><br>
				คลิ๊กเพื่อ   $doc_url  $approve_url &nbsp;&nbsp; $reject_url<br><br>

				<font style='font-family:Cordia New;font-size:19px'>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br>
				$name_from <br>
				$emp_th_pos_name <br></font>"; 
			
				// ส่งอีเมลไปหาผู้อนุมัติคนที่ 3 crstm_email_app3
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
				
				if (is_scgemail($mail_to)) {
					$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
						$r="0";	
					} else {$r="1";}
					
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			}
			////////////////////////////////
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
				
				////$cr_all_email = $email_sale.",".$mail_credit_email;
				$cr_all_email = $email_sale.",".$mail_credit_email.",".$mail_mgr_credit; // 04/08/2022
			
				$my_files = $fileattach;
				$my_filesname = $fileattach_mailname;
				$mail_from = $mail_from_text; //$user_fullname;
				$mail_from_email = $mail_credit_email; //$email_mgr;
				$mail_to = $cr_all_email;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_nbr : $crstm_cus_name  ได้ถูกส่งไปขออนุมัติแล้วค่ะ ";
				$mail_message = $detail_sale;
				$mail_message .= $mail_no_reply;
				
				if (is_scgemail($mail_to)) {
					$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
						$r="0";	
					} else {$r="1";}
					
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
				
			////////////////////////////////
			$step_name_cr1 = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", "50" ,$conn);
			$params_edit = array($crstm_approve_nbr);
			$sql_edit = "UPDATE crstm_mstr SET ".
			" crstm_reviewer_date = '$curr_date' ,".
			" crstm_step_code = '50' ,".
			" crstm_status = 0 ,".
			" crstm_step_name = '$step_name_cr1' ".
			" WHERE crstm_nbr = ? ";
			$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
			if($result_edit) {
				$r="1";
				$errortxt="Reviewer2 approve success.";
				$nb=encrypt($crstm_approve_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="Reviewer2 approve fail.";
			}
			$r="1";	
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} // reviewer2 approve 
		
		if ($crstm_approve_select=="222") {   // Revise
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			
			//เก็บประวัติการดำเนินการ
			$cr_ap_f_step = "220";  // Draft
			$cr_ap_t_step = "222"; //  แก้ไขเอกสาร
			$cr_ap_text = "Reviewer2 to Revise";
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
		
		if ($crstm_approve_select=="223") {   // Reject
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			
			//เก็บประวัติการดำเนินการ
			$cr_ap_f_step = "220";  // Draft
			$cr_ap_t_step = "223"; //  Reject
			$cr_ap_text = "Reviewer2 to Reject";
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
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_approve_nbr ลูกค้า $crstm_cus_name ไม่ผ่านการพิจารณาจาก Reviewer 2 ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $sale_name <br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_approve_nbr  ลูกค้า $crstm_cus_name ไม่ผ่านการพิจารณาจาก Reviewer 2 <br><br>
				
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
				" crstm_reviewer2_date = '$curr_date' ,".
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
		echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
	}
?>