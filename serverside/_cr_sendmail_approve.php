<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/cus_printform_func.php");

session_start();

date_default_timezone_set('Asia/Bangkok');
$today = date("Y-m-d H:i:s"); 
$curr_date = ymd(date("d/m/Y"));
$allow_post = false;	
$action_post = mssql_escape($_POST['action']);

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
	else { //post มาจาก email
		//Use Double Cookie for recheck CSRF
		$sessionid = session_id();
		$cus_verify_csrf_mail =  mssql_escape($_COOKIE['cus_verify_csrf_mail']);
		$sessionid_dec = decrypt($cus_verify_csrf_mail, $key);
				
		if ($sessionid != $sessionid_dec) {
				setcookie ("cus_verify_csrf_mail", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
				$r="0";
				$errortxt="<span style='color:red'><h4 style='text-align:center'>** ไม่สามารถอนุมัติได้  ** <h4></span>";
				echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
				exit;
			}
		else {
			setcookie ("cus_verify_csrf_mail", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			$cus_auth_code = mssql_escape($_POST['cus_auth_code']);
			$cus_approved_by = decrypt(mssql_escape($_POST['cus_approved_by']), $dbkey);
			$cus_approve_nbr = decrypt(mssql_escape($_POST['cus_approve_nbr']), $dbkey);
			$cus_approve_step = decrypt(mssql_escape($_POST['cus_approve_step']), $dbkey);
			//$auth_appr =  strtoupper(explode("@",$cus_approved_by)[0]);
			
			if ($cus_auth_code!="" && $cus_approve_nbr!="") {
			
				$params = array($cus_approve_nbr);
				$query_detail = "SELECT * FROM cus_app_mstr where cus_app_nbr = ?";
				$result_detail = sqlsrv_query($conn, $query_detail,$params);
				$rec = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
				if ($rec) {
					$cus_app_nbr = $rec['cus_app_nbr'];
					$cus_approve_code = $rec['cus_approve_code'];
					$cus_create_by = $rec['cus_create_by'];
					$iscus_curprocessor = $rec['cus_curprocessor'];
					$owner_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$cus_create_by,$conn);
					$owner_name = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$cus_create_by,$conn);

					//$allow_post = true;
					
					if ($cus_approve_code == $cus_auth_code) {	
						
						$params = array($cus_app_nbr,$cus_approved_by);
						$query_apprv = "SELECT * FROM apprv_person where apprv_cus_nbr = ? and apprv_user_id = ?";
						$result_apprv_detail = sqlsrv_query($conn, $query_apprv,$params);
						$row_app = sqlsrv_fetch_array($result_apprv_detail, SQLSRV_FETCH_ASSOC);
						if ($row_app) {
							$apprv_user_id = strtolower($row_app['apprv_user_id']);
							$apprv_aplevel_code = $row_app['apprv_aplevel_code'];
							$apprv_name = $row_app['apprv_name'];
							$apprv_email = $row_app['apprv_email'];
							$apprv_status = $row_app['apprv_status'];
							$apprv_by = $row_app['apprv_by'];
							$apprv_date = $row_app['apprv_date'];
							if($apprv_date!=""){
								$today_show = date_format($apprv_date,"d/m/Y H:i:s");
							}
							$allow_post = true;
						}				
						
					}
					else {
						$allow_post = false;
						$r="0";
						$errortxt="**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ **";
					}
				} 

					if(($apprv_by == $cus_approved_by) || ($apprv_date !="")) { // เช็คเอกสารว่ามีการ approve หรือยัง
						$allow_post = false;
						$r="0";
						$errortxt="<span style='color:red'><h4 style='text-align:center'>*** เอกสารฉบับนี้ ได้ถูกอนุมัติไปแล้ว ***<br><br>โดย คุณ$apprv_name เวลา $today_show   <h4></span>";
						echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
						exit;
					}

					if (inlist($iscus_curprocessor,$cus_approved_by)) {
						$allow_post = true;
						$user_login = $cus_approved_by;
									
						$params_login = array($user_login);
						$sql_emp = "SELECT * from emp_mstr where emp_user_id = ? ";
						$result_emp = sqlsrv_query($conn, $sql_emp,$params_login);	
						$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
						if ($r_emp) {
							$user_fullname = html_escape(trim($r_emp["emp_th_firstname"])) . " " . html_escape(trim($r_emp["emp_th_lastname"]));
							//$user_fullname = rep_prefix_name($user_fullname,'');
							$user_email = html_escape($r_emp['emp_email_bus']);
						}
						else {						
							$allow_post = false;
							$r="0";
							$errortxt="**ไม่พบข้อมูลพนักงานผู้อนุมัติ**";
						}
					}
					else {
						$allow_post = false;
						$r="0";
						$errortxt="**คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ หรือ อาจเพราะเอกสารไม่ได้อยู่ในขั้นตอนที่รอคุณอนุมัติแล้ว";
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
		if (inlist("50,60,61",$cus_approve_step)) {  //All Step ผผ, ผส, CMO, CFO, MD อนุมัติ	
	   
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			
			//เก็บประวัติการดำเนินการ
			$cus_ap_f_step = "40";  // Draft
			$cus_ap_t_step = $cus_approve_step; // ผผ, ผส
			$cus_ap_text = "Submit for ".$apprv_aplevel_code;
			$cus_ap_remark = "";		
				
			$cus_ap_id = getcusnewapp($cus_approve_nbr,$conn);
				
			$sql = "INSERT INTO cus_approval(" . 
			" cus_ap_id,cus_ap_nbr,cus_ap_f_step_code,cus_ap_t_step_code,cus_ap_text,cus_ap_remark,cus_ap_active,cus_ap_create_by,cus_ap_create_date)" .		
			" VALUES('$cus_ap_id','$cus_approve_nbr','$cus_ap_f_step','$cus_ap_t_step','$cus_ap_text','$cus_ap_remark','1','$cus_approved_by','$today')";				
			$result = sqlsrv_query($conn, $sql);

			if($can_sendmail) {
				//$all_email = $owner_email.",".$mail_credit_email.",".$mail_mgr_credit;
				$all_email = $owner_email;

				$mail_from = $mail_from_text;
				$mail_from_email = $mail_credit_email ;
				$mail_to = $all_email;
				$mail_subject = $mail_subject_text."- เอกสารเลขที่ $cus_approve_nbr ได้รับการอนุมัติแล้ว ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน คุณ$owner_name <br><br>
				เอกสารเลขที่ $cus_approve_nbr  <br><br>
				<span style='color:green'><strong>*** ได้รับการอนุมัติแล้วจาก คุณ$apprv_name ***</strong> </span><br><br>
				ขอบคุณค่ะ</font>";
				$mail_message .= "<br>" .$mail_no_reply;
				if($mail_to!="") {
					$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
				
				$params = array($cus_approve_nbr,$apprv_user_id);
				$sql = "UPDATE apprv_person	SET ".
				"apprv_by = '$cus_approved_by', ".
				"apprv_status = 'AP', ".
				"apprv_date = '$today' ".
				" WHERE apprv_cus_nbr = ? and apprv_user_id = ? ";
				$result = sqlsrv_query($conn,$sql,$params);

				$params = array($cus_approve_nbr);
				$sql = "UPDATE cus_app_mstr SET ".
				"cus_step_code = '$cus_approve_step', ".
				"cus_curprocessor = '$cus_approved_by' ".
				"WHERE cus_app_nbr = ? ";
				$result = sqlsrv_query($conn,$sql,$params);
			}	
			
			if($result) {
				$r="1";
				$errortxt="approve success.";
				$nb=encrypt($cus_approve_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="approve fail.";
			}
			// $r="1";	
			// echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		} // ผผ, ผส อนุมัติ

	/////////////////
		//ส่งเมลหาผู้อนุมัติคนถัดไป
		if (isservonline($smtp)) { $can_sendmail=true;}
		else {
			$can_sendmail=false;
			$r="0";
			$nb="";
			$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
		}
		$params = array($cus_approve_nbr);
		$query = "SELECT * FROM  cus_app_mstr WHERE cus_app_nbr = ?";
		$result = sqlsrv_query($conn, $query, $params, array("Scrollable" => 'keyset' ));
		$rowCounts = sqlsrv_num_rows($result);
		if($rowCounts > 0){
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			{
				$cr_app_nbr = $row['cus_app_nbr'];
				$cus_cust_type = $row['cus_cust_type'];
				$cus_cond_cust = $row['cus_cond_cust'];
				$cus_reg_nme = $row['cus_reg_nme'];
				$cus_reg_addr = $row['cus_reg_addr'];
				$cus_district = $row['cus_district'];
				$cus_amphur = $row['cus_amphur'];
				$cus_prov = $row['cus_prov'];
				$cus_approve_code = $row['cus_approve_code'];
				$address = $cus_reg_addr." ".$cus_district." ".$cus_amphur." ".$cus_prov;
				$cus_create_by = $row['cus_create_by'];
				$cus_create_date = $row['cus_create_date'];
			}
		}	
		$cust_type_name = findsqlval("cus_type_mstr","cus_type_name","cus_type_code",$cus_cust_type,$conn);	
		$cusd_sale_reason = findsqlval("cus_app_det","cusd_sale_reason","cusd_app_nbr",$cr_app_nbr,$conn);	
		$owner_name = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$cus_create_by,$conn);


		switch($cus_cond_cust){
			case "c1" :
				$cardtxt = "แต่งตั้งลูกค้าใหม่";
				$filename = "แต่งตั้งลูกค้าใหม่";
				break;
			case "c2" :
				$cardtxt = "แต่งตั้งร้านสาขา";
				$filename = "แต่งตั้งร้านสาขา";
				break;  
			case "c3" :
				$cardtxt = "เปลี่ยนแปลงชื่อ";
				$filename = "เปลี่ยนแปลงชื่อ";
				break;  
			case "c4" :
				$cardtxt = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
				$filename = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
				break;  
			case "c5" :
				$cardtxt = "เปลี่ยนแปลงชื่อและที่อยู่";
				$filename = "เปลี่ยนแปลงชื่อและที่อยู่";
				break;  
			case "c6" :
				$cardtxt = "ยกเลิก Code ลูกค้า";
				$filename = "ยกเลิก Code ลูกค้า";
				break; 			
		}
		
		$params_apprv = array($cus_approve_nbr);
		$sql_apprv = "select top 1 * FROM apprv_person Where apprv_cus_nbr = ? and apprv_status = '' order by apprv_seq";
		$result_apprv = sqlsrv_query($conn,$sql_apprv,$params_apprv); 
		if($result_apprv) {
			$top = "result Top";
			while($row_apprv = sqlsrv_fetch_array($result_apprv)) {
				$apprv_aplevel_code = $row_apprv['apprv_aplevel_code'];
				$apprv_type_code = $row_apprv['apprv_type_code'];
				//$apprvp_role_approve = $row_apprv['apprvp_role_approve'];
			}
			if(!empty($apprv_aplevel_code) and !empty($apprv_type_code)) {
				$params_apv = array($cus_approve_nbr,$apprv_aplevel_code,$apprv_type_code);
				$sql_apv = "select *  FROM  apprv_person Where apprv_cus_nbr = ? and apprv_aplevel_code = ? and apprv_type_code = ? and apprv_status = '' order by apprv_seq";
				$result_apv = sqlsrv_query($conn,$sql_apv,$params_apv); 
				if($result_apv) {
					while($row_apv = sqlsrv_fetch_array($result_apv)) {
						$apprv_user_id = $row_apv['apprv_user_id'];
						$apprv_emp_id  = $row_apv['apprv_emp_id'];
						$apprv_name = $row_apv['apprv_name'];
						$apprv_position = $row_apv['apprv_position'];
						$apprv_email = $row_apv['apprv_email'];
						$apprv_step = $row_apv['apprv_step'];
						$apprv_lasted = $row_apv['apprv_lasted'];
						$apprv_tobestep_cusstep_code = $row_apv['apprv_tobestep_cusstep_code'];
						$apprv_nextstep_cusstep_code = $row_apv['apprv_nextstep_cusstep_code'];
						$apprv_backstep_cusstep_code = $row_apv['apprv_backstep_cusstep_code'];
						$apprv_delstep_cusstep_code = $row_apv['apprv_delstep_cusstep_code'];
						//$apprvp_role_approve = $row_apv['apprvp_role_approve'];
							
						if ($apprv_email != "") {	
							$fileattach = array();
							$fileattach_mailname = array();
							$output_folder = $downloadpath."SALES/";
							$cr_output_filename = $cr_app_nbr.$filename.".pdf";
							if($cus_cond_cust == "c1" || $cus_cond_cust == "c2"){
								array_push($fileattach,$output_folder.print_formnewcust($cr_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
								array_push($fileattach_mailname,$cr_app_nbr.$filename.".pdf");
							}
							else 
							{
								array_push($fileattach,$output_folder.print_formchgcust($cr_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
								array_push($fileattach_mailname,$cr_app_nbr.$filename.".pdf");
							}
							$my_files = $fileattach;
							$my_filesname = $fileattach_mailname;
							$mail_from = $mail_from_text_app;
							$mail_from_email = $mail_credit_email;			
							$mail_to = $apprv_email;					
									
							$mail_topic = $cardtxt;
							$mail_subject = "$mail_topic - เอกสารเลขที่ $cr_app_nbr ค่ะ";
								
								$approve_url = "<a href='".$app_url."/crcust/cr_send_mail.php?auth=".$cus_approve_code."&nbr=".encrypt($cr_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_nextstep_cusstep_code, $dbkey)."&ch=Approve' target='_blank'><font color='green'>Approve</font></a>";
								//$revise_url = "<a href='".$app_url."/crcust/cr_send_mail.php?auth=".$cus_approve_code."&nbr=".encrypt($cr_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_backstep_cusstep_code, $dbkey)."&ch=Revise' target='_blank'><font color='blue'>Revise</font></a>";
								$reject_url = "<a href='".$app_url."/crcust/cr_send_mail.php?auth=".$cus_approve_code."&nbr=".encrypt($cr_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_delstep_cusstep_code, $dbkey)."&ch=Reject' target='_blank'><font color='red'>Reject</font></a>";
								$home = "<a href='".$app_url."/index.php' target='_blank'></a>";
								
								$message_action = "<tr><td >โปรดดำเนินการ ดังต่อไปนี้   </td><td>$home$home$approve_url &nbsp;|&nbsp; $home$reject_url </td></tr>";
								// ฟอร์ม Email แต่งตั้งลูกค้าใหม่ / แต่งตั้งร้านสาขา
								if($cus_cond_cust=="c1" || $cus_cond_cust=="c2"){
									$mail_message = "<table cellpadding='2' cellspacing='0' width='700' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
										<tr><td colspan='2'><b>เรียน คุณ$apprv_name</b></td></tr>
										<tr><td colspan='2'><b>เรื่อง $mail_topic</b></td></tr>
										$message_action
										<tr><td colspan='2'><b>รายละเอียดดังนี้</b></td></tr>
										<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:red; font-weight:bold;'>$cr_app_nbr</span></td></tr>
										<tr><td >ประเภทเอกสาร :</td><td ><span style='color:red; font-weight:bold;'>$cardtxt</span></td></tr>
										<tr><td >ประเภทลูกค้าที่ขอแต่งตั้ง :</td><td>$cust_type_name</td></tr>
										<tr><td >ชื่อจดทะเบียน :</td><td>$cus_reg_nme</td></tr>
										<tr><td >ที่อยู่จดทะเบียน :</td><td>$address</td></tr>
										<tr><td >ผู้ขออนุมัติ : </td><td>$owner_name</td></tr>
										<tr><td >ขออนุมัติ เมื่อวันที่ : </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
										<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																
										<tr><td colspan='2'>$mail_no_reply</td></tr>
									</table>";	
								}	

								// ฟอร์ม Email เปลี่ยนแปลงชื่อ
								if($cus_cond_cust=="c3"){
									$mail_message = "<table cellpadding='2' cellspacing='0' width='700' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
										<tr><td colspan='2'><b>เรียน คุณ$apprv_name</b></td></tr>
										<tr><td colspan='2'><b>เรื่อง $mail_topic</b></td></tr>
										$message_action
										<tr><td colspan='2'><b>รายละเอียดดังนี้</b></td></tr>
										<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:red; font-weight:bold;'>$cr_app_nbr</span></td></tr>
										<tr><td >ประเภทเอกสาร :</td><td ><span style='color:red; font-weight:bold;'>$cardtxt</span></td></tr>
										<tr><td >ชื่อจดทะเบียน :</td><td><span style='color:red; font-weight:bold;'>$cus_reg_nme</span></td></tr>
										<tr><td >ที่อยู่จดทะเบียน :</td><td>$address</td></tr>
										<tr><td >ผู้ขออนุมัติ : </td><td>$owner_name</td></tr>
										<tr><td >ขออนุมัติ เมื่อวันที่ : </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
										<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																
										<tr><td colspan='2'>$mail_no_reply</td></tr>
									</table>";	
								}	

								// ฟอร์ม Email เปลี่ยนแปลงที่อยู่จดทะเบียน
								if($cus_cond_cust=="c4"){
									$mail_message = "<table cellpadding='2' cellspacing='0' width='700' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
										<tr><td colspan='2'><b>เรียน คุณ$apprv_name</b></td></tr>
										<tr><td colspan='2'><b>เรื่อง $mail_topic</b></td></tr>
										$message_action
										<tr><td colspan='2'><b>รายละเอียดดังนี้</b></td></tr>
										<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:red; font-weight:bold;'>$cr_app_nbr</span></td></tr>
										<tr><td >ประเภทเอกสาร :</td><td ><span style='color:red; font-weight:bold;'>$cardtxt</td></tr>
										<tr><td >ชื่อจดทะเบียน :</td><td>$cus_reg_nme</span></td></tr>
										<tr><td >ที่อยู่จดทะเบียน :</td><td><span style='color:red; font-weight:bold;'>$address</span></td></tr>
										<tr><td >ผู้ขออนุมัติ : </td><td>$owner_name</td></tr>
										<tr><td >ขออนุมัติ เมื่อวันที่ : </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
										<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																
										<tr><td colspan='2'>$mail_no_reply</td></tr>
									</table>";	
								}	

								// ฟอร์ม Email เปลี่ยนแปลงชื่อและที่อยู่
								if($cus_cond_cust=="c5"){
									$mail_message = "<table cellpadding='2' cellspacing='0' width='700' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
										<tr><td colspan='2'><b>เรียน คุณ$apprv_name</b></td></tr>
										<tr><td colspan='2'><b>เรื่อง $mail_topic</b></td></tr>
										$message_action
										<tr><td colspan='2'><b>รายละเอียดดังนี้</b></td></tr>
										<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:red; font-weight:bold;'>$cr_app_nbr</span></td></tr>
										<tr><td >ประเภทเอกสาร :</td><td ><span style='color:red; font-weight:bold;'>$cardtxt</span></td></tr>
										<tr><td >ชื่อจดทะเบียน :</td><td><span style='color:red; font-weight:bold;'>$cus_reg_nme</span></td></tr>
										<tr><td >ที่อยู่จดทะเบียน :</td><td><span style='color:red; font-weight:bold;'>$address</span></td></tr>
										<tr><td >ผู้ขออนุมัติ : </td><td>$owner_name</td></tr>
										<tr><td >ขออนุมัติ เมื่อวันที่ : </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
										<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																
										<tr><td colspan='2'>$mail_no_reply</td></tr>
									</table>";	
								}	

							if ($can_sendmail) {
								if ($mail_to !="") {
									$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
									//$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
									if (!$sendstatus) {
										$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ <br>";
									}
								} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ <br>";}
							}
							else {
								
							}
							$params = array($cr_app_nbr);
							$sql = "UPDATE cus_app_mstr SET ".
							"cus_curprocessor = '$apprv_user_id' ".
							"WHERE cus_app_nbr = ? ";
							$result = sqlsrv_query($conn,$sql,$params);
						}
					}						
				}
				$r="1";
				$errortxt="ส่ง Email ขออนุมัติเรียบร้อยแล้วค่ะ";
				$nb=encrypt($cus_approve_nbr, $key);		
			}
			/* else
			{
				$r="0";
				$errortxt="ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ 3";
				$nb=encrypt($cus_approve_nbr, $key);	
			} */
		} 
		////
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	////////////////
	
	
	} 
	
	else {
		$r="0";
		$nb="";
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?>