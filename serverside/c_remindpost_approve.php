<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
include("../_incs/cus_printform_func.php");
if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key,$user_login)) {
		echo "System detect CSRF attack!!";
		exit;
	}
} else {
	echo "System detect CSRF attack!!";
	exit;
}   
clearstatcache();	
$params = array();
set_time_limit(0);
date_default_timezone_set('Asia/Bangkok');
$today = date("Y-m-d H:i:s");
$curr_date = ymd(date("d/m/Y"));
$errortxt = "";
$errorflag = false;

$action = mssql_escape($_POST['action']);
$cr_app_nbr  = mssql_escape(decrypt($_POST['cus_app_nbr'], $key));
$cr_step_code  = mssql_escape(decrypt($_POST['cr_step_code'], $key));

		//Send Mail
		if (isservonline($smtp)) { 
			$can_sendmail=true;
			$r="1";
			$errortxt="ส่ง Email ขออนุมัติเรียบร้อยแล้วค่ะ ";
			$nb=encrypt($cr_app_nbr, $key);		
		}
		else {
			$can_sendmail=false;
			$r="0";
			$nb="";
			$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
		}
		$params = array($cr_app_nbr);
		$query = "SELECT * FROM  cus_app_mstr WHERE cus_app_nbr = ?";
		$result = sqlsrv_query($conn, $query, $params, array("Scrollable" => 'keyset' ));
		$rowCounts = sqlsrv_num_rows($result);
		if($rowCounts > 0){
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			{
				$cus_cust_type = mssql_escape($row['cus_cust_type']);
				$cus_cond_cust = mssql_escape($row['cus_cond_cust']);
				$cus_reg_nme = mssql_escape($row['cus_reg_nme']);
				$cus_reg_addr = mssql_escape($row['cus_reg_addr']);
				$cus_district = mssql_escape($row['cus_district']);
				$cus_amphur = mssql_escape($row['cus_amphur']);
				$cus_prov = mssql_escape($row['cus_prov']);
				$address = $cus_reg_addr." ".$cus_district." ".$cus_amphur." ".$cus_prov;
				$cus_create_date = $row['cus_create_date'];
				$cus_create_by = $row['cus_create_by'];
				$cus_step_code = mssql_escape($row['cus_step_code']);

				/* switch($cus_step_code){
					case "30" :
						$nextstep_code = "61";
						break;
					case "30" :
						$nextstep_code = "61";
						break;  
				}		 */
				
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
			$cus_approve_code = md5(gen_uuid());
			$params = array($cr_app_nbr);
			$sql_updatestep = "UPDATE cus_app_mstr SET" .
			" cus_approve_code = '$cus_approve_code'" .
			" WHERE cus_app_nbr = ?";						
			$result_updatestep = sqlsrv_query($conn,$sql_updatestep, $params); 

			// เช็คหาคนอนุมัติล่าสุด
			$params = array($cr_app_nbr);
			$sql_last = "select top 1 * FROM apprv_person Where apprv_cus_nbr = ? and apprv_status = 'AP' order by apprv_seq desc";
			$result_last = sqlsrv_query($conn,$sql_last,$params); 
			if($result_last) {
				while($row_last = sqlsrv_fetch_array($result_last)) {
					$apprv_user_id = $row_last['apprv_user_id'];
					$apprv_date = $row_last['apprv_date'];
					$apprv_date = date_format($apprv_date,"d/m/Y H:i:s");

				}
				$apprv_last_name = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$apprv_user_id,$conn);

			}

			// เช็คหาข้อความจากผู้ดำเนินการล่าสุด
			$params = array($cr_app_nbr,$apprv_user_id);
			$sql_last = "SELECT top 1 * FROM cus_approval where cus_ap_nbr= ? and cus_ap_create_by= ? order by cus_ap_id desc";
			$result_last = sqlsrv_query($conn,$sql_last,$params); 
			if($result_last) {
				while($row_last = sqlsrv_fetch_array($result_last)) {
					$cus_ap_remark = $row_last['cus_ap_remark'];

				}
			}
			
			$params_apprv = array($cr_app_nbr);
			$sql_apprv = "select top 1 * FROM apprv_person Where apprv_cus_nbr = ? and apprv_status = '' order by apprv_seq";
			$result_apprv = sqlsrv_query($conn,$sql_apprv,$params_apprv); 
			if($result_apprv) {
				$remind_txt = "[Remind Email] ";
				while($row_apprv = sqlsrv_fetch_array($result_apprv)) {
					$apprv_aplevel_code = $row_apprv['apprv_aplevel_code'];
					$apprv_type_code = $row_apprv['apprv_type_code'];
					//$apprvp_role_approve = $row_apprv['apprvp_role_approve'];
				}
				if(!empty($apprv_aplevel_code) and !empty($apprv_type_code)) {
					$params_apv = array($cr_app_nbr,$apprv_aplevel_code,$apprv_type_code);
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
							if($apprv_lasted=="Y"){
								$nextstep_cusstep_code = "60"; // MD Approver
								//$nextstep_cusstep_code = "50"; //Wait Approver
							} else {
								$nextstep_cusstep_code = $apprv_nextstep_cusstep_code;
							}
								
							if ($apprv_email != "") {	
								$fileattach = array();
								$fileattach_mailname = array();
								$output_folder = $downloadpath."SALES/";
								//$cr_output_filename = $cr_app_nbr.$filename.".pdf";
								$cr_output_filename = 'ใบขออนุมัติ'.$filename.'-'.$cr_app_nbr.".pdf";
								if($cus_cond_cust == "c1" || $cus_cond_cust == "c2"){
									array_push($fileattach,$output_folder.print_formnewcust($cr_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
									array_push($fileattach_mailname,'ใบขออนุมัติ'.$filename.'-'.$cr_app_nbr.".pdf");
								}
								else 
								{
									array_push($fileattach,$output_folder.print_formchgcust($cr_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
									array_push($fileattach_mailname,'ใบขออนุมัติ'.$filename.'-'.$cr_app_nbr.".pdf");
								}
								$my_files = $fileattach;
								$my_filesname = $fileattach_mailname;
								$mail_from = $mail_from_text_app;
								$mail_from_email = $mail_credit_email;			
								$mail_to = $apprv_email;					
								$mail_topic = $cardtxt;
								$mail_subject = "[$mail_topic] - เอกสารเลขที่ $cr_app_nbr : $cus_reg_nme ค่ะ";
									
									$approve_url = "<a href='".$app_url."/sendmail/cr_send_mail.php?auth=".$cus_approve_code."&nbr=".encrypt($cr_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_nextstep_cusstep_code, $dbkey)."&ch=Approve' target='_blank'><font color='green'>Approve</font></a>";
									$re_url = "<a href='".$app_url."/sendmail/cr_send_mail.php?auth=".$cus_approve_code."&nbr=".encrypt($cr_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_delstep_cusstep_code, $dbkey)."&ch=Reject' target='_blank'><font color='red'>Reject</font></a>";
									$home = "<a href='".$app_url."/index.php' target='_blank'></a>";
									
									$message_action = "<tr><td >โปรดดำเนินการ ดังต่อไปนี้   </td><td>$home$home$approve_url &nbsp;|&nbsp; $home$re_url </td></tr>";
									// ฟอร์ม Email แต่งตั้งลูกค้าใหม่ / แต่งตั้งร้านสาขา
									if($cus_cond_cust=="c1" || $cus_cond_cust=="c2"){
										// $mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
										$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
											<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
											<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>
											$message_action
											<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
											<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cr_app_nbr</span></td></tr>
											<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
											<tr><td >ประเภทลูกค้าที่ขอแต่งตั้ง :</td><td>$cust_type_name</td></tr>
											<tr><td >ชื่อจดทะเบียน :</td><td>$cus_reg_nme</td></tr>
											<tr><td >ที่อยู่จดทะเบียน :</td><td>$address</td></tr>
											<tr><td >ผู้ขออนุมัติ : </td><td>$owner_name</td></tr>
											<tr><td >ขออนุมัติ เมื่อวันที่ : </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
											<tr><td colspan='2'><b>ข้อมูลผู้ดำเนินการคนล่าสุด</b></td></tr>		
											<tr><td>ผู้ดำเนินการ : </td><td>คุณ$apprv_last_name</td></tr>
											<tr><td>ดำเนินการ เมื่อวันที่ : </td><td>$apprv_date</td></tr>
											<tr><td>ข้อความจากผู้ดำเนินการ  (ถ้ามี) : </td><td>$cus_ap_remark</td></tr>										
											<tr><td colspan='3'>$mail_no_reply</td></tr>
										</table>";	
									}	

									// ฟอร์ม Email เปลี่ยนแปลงชื่อ
									if($cus_cond_cust=="c3"){
										// $mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
										$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
											<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
											<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>
											$message_action
											<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
											<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cr_app_nbr</span></td></tr>
											<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
											<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
											<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
											<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
											<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
											<tr><td >ชื่อจดทะเบียน :</td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
											<tr><td >ที่อยู่จดทะเบียน :</td><td>$address</td></tr>
											<tr><td >ผู้ขออนุมัติ : </td><td>$owner_name</td></tr>
											<tr><td >ขออนุมัติ เมื่อวันที่ : </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
											<tr><td colspan='2'><b>ข้อมูลผู้ดำเนินการคนล่าสุด</b></td></tr>		
											<tr><td>ผู้ดำเนินการ : </td><td>คุณ$apprv_last_name</td></tr>
											<tr><td>ดำเนินการ เมื่อวันที่ : </td><td>$apprv_date</td></tr>
											<tr><td>ข้อความจากผู้ดำเนินการ  (ถ้ามี) : </td><td>$cus_ap_remark</td></tr>												<tr><td colspan='3'>$mail_no_reply</td></tr>
										</table>";	
									}	
	
									// ฟอร์ม Email เปลี่ยนแปลงที่อยู่จดทะเบียน
									if($cus_cond_cust=="c4"){
										// $mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
										$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
											<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
											<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>
											$message_action
											<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
											<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cr_app_nbr</span></td></tr>
											<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</td></tr>
											<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
											<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
											<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
											<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
											<tr><td >ชื่อจดทะเบียน :</td><td>$cus_reg_nme</span></td></tr>
											<tr><td >ที่อยู่จดทะเบียน :</td><td><span style='color:$color;'>$address</span></td></tr>
											<tr><td >ผู้ขออนุมัติ : </td><td>$owner_name</td></tr>
											<tr><td >ขออนุมัติ เมื่อวันที่ : </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
											<tr><td colspan='2'><b>ข้อมูลผู้ดำเนินการคนล่าสุด</b></td></tr>		
											<tr><td>ผู้ดำเนินการ : </td><td>คุณ$apprv_last_name</td></tr>
											<tr><td>ดำเนินการ เมื่อวันที่ : </td><td>$apprv_date</td></tr>
											<tr><td>ข้อความจากผู้ดำเนินการ  (ถ้ามี) : </td><td>$cus_ap_remark</td></tr>												<tr><td colspan='3'>$mail_no_reply</td></tr>
										</table>";	
									}	

									// ฟอร์ม Email เปลี่ยนแปลงชื่อและที่อยู่
									if($cus_cond_cust=="c5"){
										// $mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
										$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
											<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
											<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>
											$message_action
											<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
											<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cr_app_nbr</span></td></tr>
											<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
											<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
											<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
											<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
											<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
											<tr><td >ชื่อจดทะเบียน :</td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
											<tr><td >ที่อยู่จดทะเบียน :</td><td><span style='color:$color;'>$address</span></td></tr>
											<tr><td >ผู้ขออนุมัติ : </td><td>$owner_name</td></tr>
											<tr><td >ขออนุมัติ เมื่อวันที่ : </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
											<tr><td colspan='2'><b>ข้อมูลผู้ดำเนินการคนล่าสุด</b></td></tr>		
											<tr><td>ผู้ดำเนินการ : </td><td>คุณ$apprv_last_name</td></tr>
											<tr><td>ดำเนินการ เมื่อวันที่ : </td><td>$apprv_date</td></tr>
											<tr><td>ข้อความจากผู้ดำเนินการ  (ถ้ามี) : </td><td>$cus_ap_remark</td></tr>												<tr><td colspan='3'>$mail_no_reply</td></tr>
										</table>";	
									}	

								if ($can_sendmail) {
									if ($mail_to !="") {
										$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
										//$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
										if (!$sendstatus) {
											$r="0";
											$nb="";
											$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ 1<br>";
										}
									} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ 2<br>";}
								}
								else {
									
								}
							}
						}						
					}
				}
				else
				{
					$r="0";
					$errortxt="ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ ";
					$nb="";	
				}

				$params = array($cr_app_nbr);
				$sql = "UPDATE cus_app_mstr SET ".
				"cus_step_code = '$cr_step_code', ". 
				"cus_curprocessor = '$apprv_user_id' ".
				"WHERE cus_app_nbr = ? ";
				$result = sqlsrv_query($conn,$sql,$params);
				
			}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';

?>