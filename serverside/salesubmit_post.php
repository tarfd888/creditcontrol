<?php
include("../_incs/chksession.php");
include("../_incs/config.php"); 
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";
include("../_incs/rev_printform_func.php");

if (($_SERVER['REQUEST_METHOD']=='POST')) {
	if (!matchToken($csrf_key,$user_login)) {
		echo "System detect CSRF attack!!";
		exit;
	}
}
else {
	echo "Allow for POST Only";
	exit;
}  

set_time_limit(0);
date_default_timezone_set('Asia/Bangkok');
$curr_date = ymd(date("d/m/Y"));
$today = date("Y-m-d H:i:s");
$params = array();
$errorflag = false;
$errortxt = "";

$action = mssql_escape($_POST['action']);
$cus_step_code  = mssql_escape(decrypt($_REQUEST['step_code'], $key));	
$cus_app_nbr  = mssql_escape(decrypt($_POST['cus_app_nbr'], $key));
	if ($action == "cust_edit" && $cus_step_code !="51") {	

			if (isservonline($smtp)) { 
				$can_sendmail=true;
				$r="1";
				$errortxt="ส่ง Email ขออนุมัติเรียบร้อยแล้วค่ะ ";
				$nb=encrypt($cus_app_nbr, $key);		
			}
			else {
				$can_sendmail=false;
				$r="0";
				$nb="";
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}

			$params = array($cus_app_nbr);
			$sql = "SELECT * from cus_app_mstr WHERE cus_app_nbr = ? ";
			$result = sqlsrv_query($conn, $sql, $params, array("Scrollable" => 'keyset' ));
			$rowCounts = sqlsrv_num_rows($result);
			if($rowCounts > 0){
				while($r_row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					$cus_code = mssql_escape($r_row['cus_code']);
					$cus_cust_type = mssql_escape($r_row['cus_cust_type']);
					$cus_cond_cust = mssql_escape($r_row['cus_cond_cust']);
					$cus_reg_nme = mssql_escape($r_row['cus_reg_nme']);
					$cus_reg_addr = mssql_escape($r_row['cus_reg_addr']);
					$cus_district = mssql_escape($r_row['cus_district']);
					$cus_amphur = mssql_escape($r_row['cus_amphur']);
					$cus_prov = mssql_escape($r_row['cus_prov']);
					$address = $cus_reg_addr." ".$cus_district." ".$cus_amphur." ".$cus_prov;
					$cus_create_date = $r_row['cus_create_date'];
					$cus_curprocessor_check = mssql_escape($r_row['cus_curprocessor']);
					$cus_create_by = mssql_escape($r_row['cus_create_by']);
					$auc_user_name = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$cus_create_by,$conn);
					$cust_type_name = findsqlval("cus_type_mstr","cus_type_name","cus_type_code",$cus_cust_type,$conn);	
					$cus_code_name = findsqlval("cus_mstr","cus_name1","cus_nbr",$cus_code,$conn);
					$cus_address = findsqlval("cus_mstr","cus_street+' '+cus_street2+' '+cus_district+' '+cus_city+' '+cus_zipcode","cus_nbr",$cus_code,$conn);
				
					switch($cus_cond_cust){
						case "c1" :
							$cardtxt = "แต่งตั้งลูกค้าใหม่";
							$filename = "แต่งตั้งลูกค้าใหม่";
							$color = 'Back';
							break;
						case "c2" :
							$cardtxt = "แต่งตั้งร้านสาขา";
							$filename = "แต่งตั้งร้านสาขา";
							break;  
						case "c3" :
							$cardtxt = "เปลี่ยนแปลงชื่อ";
							$filename = "เปลี่ยนแปลงชื่อ";
							$color = 'DeepPink';
							break;   
						case "c4" :
							$cardtxt = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
							$filename = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
							$color = 'DarkOrange';
							break;  
						case "c5" :
							$cardtxt = "เปลี่ยนแปลงชื่อและที่อยู่";
							$filename = "เปลี่ยนแปลงชื่อและที่อยู่";
							$color = 'Purple';
							break; 
						case "c6" :
							$cardtxt = "ยกเลิกลูกค้า";
							$filename = "ยกเลิก Code ลูกค้า";
							$color = 'Red';
							break;        	          
						default :
							$color = 'Black';
					}

					if (inlist($cus_curprocessor_check,$user_login )) {
						$allow_post = true;
					}
					else
					{
						$allow_post = false;
					}
				}
			}	
			else 
			{
				$errorflag = true;	
			}
			
			if (!$errorflag) { 
				//$doc_url = "<a href='".$app_url."/index.php?doc=".encrypt($bill_nbr, strtoupper($apprvp_user_id))."&auth=".encrypt($apprvp_user_id, $dbkey)."' target='_blank'>คลิ๊กเพื่อเปิดเอกสารจากระบบ</a>";
				$doc_url = "<a href='".$app_url."/index.php' target='_blank'>คลิ๊กเพื่อเข้าสู่ระบบ</a>";

				if ($cus_step_code=="40") { // Wait Section Approved กรณีมี reviewer ให้ส่งข้อมูลผ่าน Mail ให้กับ reviewer1 ตรวจสอบและอนุมัติ
					$cus_approve_code = md5(gen_uuid());
					
					$params_apprv = array($cus_app_nbr);
					$sql_apprv = "select top 1 * FROM apprv_person Where apprv_cus_nbr = ? and apprv_status = '' order by apprv_seq";
					$result_apprv = sqlsrv_query($conn,$sql_apprv,$params_apprv); 
					if($result_apprv) {
						$top = "result Top";
						while($row_apprv = sqlsrv_fetch_array($result_apprv)) {
							$apprv_aplevel_code = $row_apprv['apprv_aplevel_code'];
							$apprv_type_code = $row_apprv['apprv_type_code'];
						}
						if(!empty($apprv_aplevel_code) and !empty($apprv_type_code)) {
							$params_apv = array($cus_app_nbr,$apprv_aplevel_code,$apprv_type_code);
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
										//$cr_output_filename = $cus_app_nbr.$filename.".pdf";
										$cr_output_filename = 'ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr.".pdf";
										if($cus_cond_cust == "c1" || $cus_cond_cust == "c2"){
											array_push($fileattach,$output_folder.print_formnewcust($cus_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
											array_push($fileattach_mailname,'ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr.".pdf");
										}
										else 
										{
											array_push($fileattach,$output_folder.print_formchgcust($cus_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
											array_push($fileattach_mailname,'ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr.".pdf");
										}
										$my_files = $fileattach;
										$my_filesname = $fileattach_mailname;
										$mail_from = $mail_from_text_app;
										$mail_from_email = $mail_credit_email;			
										$mail_to = $apprv_email;					
										$mail_topic = $cardtxt;
										$mail_subject = "[$mail_topic] - เอกสารเลขที่ $cus_app_nbr : $cus_reg_nme ค่ะ";
											
											$approve_url = "<a href='".$app_url."/sendmail/cr_send_mail_rev.php?auth=".$cus_approve_code."&nbr=".encrypt($cus_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_nextstep_cusstep_code, $dbkey)."&ch=Approve' target='_blank'><font color='green'>Approve</font></a>";
											$revise_url = "<a href='".$app_url."/sendmail/cr_send_mail_rev.php?auth=".$cus_approve_code."&nbr=".encrypt($cus_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_backstep_cusstep_code, $dbkey)."&ch=Revise' target='_blank'><font color='blue'>Revise</font></a>";
											$home = "<a href='".$app_url."/index.php' target='_blank'></a>";
											
											$message_action = "<tr><td>โปรดดำเนินการ ดังต่อไปนี้   </td><td>$home$home$approve_url &nbsp;|&nbsp; $home$revise_url </td></tr>";
											// ฟอร์ม Email แต่งตั้งลูกค้าใหม่ / แต่งตั้งร้านสาขา
											if($cus_cond_cust=="c1" || $cus_cond_cust=="c2"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :		 </td><td><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :			 </td><td><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
													<tr><td >ประเภทลูกค้าที่ขอแต่งตั้ง :   </td><td>$cust_type_name</td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td>$address</td></tr>
													<tr><td >ผู้ขออนุมัติ : 			 </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 	    </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	

											// ฟอร์ม Email เปลี่ยนแปลงชื่อ
											if($cus_cond_cust=="c3"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
													<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
													<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
													<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
													<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td>$address</td></tr>
													<tr><td >ผู้ขออนุมัติ : 			 </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	
			
											// ฟอร์ม Email เปลี่ยนแปลงที่อยู่จดทะเบียน
											if($cus_cond_cust=="c4"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</td></tr>
													<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
													<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
													<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
													<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td><span style='color:$color;'>$address</span></td></tr>
													<tr><td >ผู้ขออนุมัติ : 			 </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	

											// ฟอร์ม Email เปลี่ยนแปลงชื่อและที่อยู่
											if($cus_cond_cust=="c5"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>
													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
													<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
													<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
													<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
													<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td><span style='color:$color;'>$address</span></td></tr>
													<tr><td >ผู้ขออนุมัติ : 			 </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	

										if ($can_sendmail) {
											if ($mail_to !="") {
												$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
												if (!$sendstatus) {
													$r="0";
													$nb="";
													$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ<br>";
												}
												else 
												{
													$params = array($cus_app_nbr);
													$sql_updatestep = "UPDATE cus_app_mstr SET" .
													" cus_approve_code = '$cus_approve_code'" .
													" WHERE cus_app_nbr = ?";						
													$result_updatestep = sqlsrv_query($conn,$sql_updatestep, $params); 
												}
											} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ<br>";}
										}
										else {
											{$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ<br>";}
										}
									}
								}						
							}
						}
						else
						{
							$r="0";
							$errortxt="ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ";
							$nb="";	
						}
					} 

					if($user_email != ""){
						$mail_from = $mail_from_text;
						$mail_from_email = $mail_credit_email;
						$mail_to = $user_email;
						$mail_subject = "[$cardtxt] - เลขที่ $cus_app_nbr : $cus_reg_nme  ได้ส่งไปให้ผู้พิจารณาแล้วค่ะ ";
						$mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน คุณ$auc_user_name <br><br>
						ใบขอ$cardtxt เลขที่ $cus_app_nbr $cus_reg_nme ได้ส่งไปให้ผู้พิจารณาแล้วค่ะ <br><br>
						$doc_url <br><br>
						
						ขอบคุณค่ะ<br></font>";
						$mail_message .= $mail_no_reply;
						if($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
								$r="0";
								$nb="";
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
					}	
					//echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
				}

				if ($cus_step_code=="10") {  // กรณีไม่มี reviewer ให้ส่งข้อมูลผ่าน Mail ให้แผนกสินเชื่อ ตรวจสอบและอนุมัติ
					//ส่งหาผู้อนุมัติเลย
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
					$mail_from = $auc_user_name;
					$mail_from_email = $user_email;
					$mail_to = $cr_next_curprocessor_email;
					$mail_subject = "Credit 1 โปรดดำเนินการ: ใบขอ$cardtxt เลขที่ $cus_app_nbr : $cus_reg_nme";
					$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน แผนกสินเชื่อ (Credit 1)<br><br>
					ใบขอ$cardtxt เลขที่ $cus_app_nbr $cus_reg_nme<br>
					Credit 1 : โปรดดำเนินการในระบบ Credit Control ด้วยค่ะ  <br>
					$doc_url <br><br>
										
					ขอบคุณค่ะ<br></font>";	
					
					$mail_message .= $mail_no_reply;
					
					if ($mail_to!="") {
						$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
						if (!$sendstatus) {
							$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
							$r="0";
							$nb="";
						} 
						else 
						{
							$r="1";
							$errortxt="ส่งอีเมลเรียบร้อยแล้ว.";
							$nb=encrypt($cus_step_code, $key);
						}
						
					} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
					
					if($user_email != ""){
						$mail_from = $mail_from_text;
						$mail_from_email = $mail_credit_email;
						$mail_to = $user_email;
						$mail_subject = "ใบขอ$cardtxt เลขที่ $cus_app_nbr : $cus_reg_nme  ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ ";
						$mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน คุณ$auc_user_name <br><br>
						ใบขอ$cardtxt เลขที่ $cus_app_nbr $cus_reg_nme ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ <br>
						
						$doc_url <br><br>
						
						ขอบคุณค่ะ<br></font>";
						$mail_message .= $mail_no_reply;
						if($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
								$r="0";
								$nb="";
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
					}	
					
					//echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'"}';
				} 
				// กรณีเคส step 0 --> 10 ส่งข้อมูลให้แผนกสินเชื่ออนุมัติ

				if ($cus_step_code=="20") {  // กรณี revise ส่ง Mail ให้แผนกสินเชื่อ ตรวจสอบและอนุมัติ
					//ส่งหาผู้อนุมัติเลย
					$cr_next_curprocessor_email = "";
					$params = array('Action_View');
					$sql_aucadmin = "select role_user_login from role_mstr where SUBSTRING(role_code, 1, 11) = ? and role_receive_mail = 1";
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
					$mail_from = $auc_user_name;
					$mail_from_email = $user_email;
					$mail_to = $cr_next_curprocessor_email;
					$mail_subject = "ข้อมูล Revise Credit 1,2 โปรดดำเนินการ: ใบขอ$cardtxt เลขที่ $cus_app_nbr : $cus_reg_nme";
					$mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน แผนกสินเชื่อ (Credit 1,2)<br><br>
					ใบขอ$cardtxt เลขที่ $cus_app_nbr $cus_reg_nme ได้ทำการตรวจสอบและแก้ไขเรียบร้อยแล้ว <br>
					Credit : โปรดดำเนินการในระบบ Credit Control ด้วยค่ะ  <br>
					$doc_url <br><br>
										
					ขอบคุณค่ะ<br></font>";	
					
					$mail_message .= $mail_no_reply;
					
					if ($mail_to!="") {
						$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
						if (!$sendstatus) {
							$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
							$r="0";
							$nb="";
						} 
						else 
						{
							$r="1";
							$errortxt="ส่งอีเมลเรียบร้อยแล้ว.";
							$nb=encrypt($cus_step_code, $key);
						}
						
					} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
					
					if($user_email != ""){
						$mail_from = "แผนกสินเชื่อ ";
						$mail_from_email = $mail_credit_email;
						$mail_to = $user_email;
						$mail_subject = "ข้อมูล Revise ใบขอ$cardtxt เลขที่ $cus_app_nbr : $cus_reg_nme  แก้ไขเรียบร้อยแล้ว ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ ";
						$mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน คุณ$auc_user_name <br><br>
						ใบขอ$cardtxt เลขที่ $cus_app_nbr $cus_reg_nme แก้ไขเรียบร้อยแล้ว ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ <br>
						$doc_url <br><br>
						
						ขอบคุณค่ะ<br></font>";
						$mail_message .= $mail_no_reply;
						if($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
								$r="0";
								$nb="";
							}
							else 
							{
								if($sendstatus){
									// เช็คคนอนุมัติก่อนหน้านี้
									$params = array($cus_app_nbr);
									$query_apprv = "SELECT top 1 * FROM cus_approval where cus_ap_nbr = ?  and cus_ap_active = '1' order by cus_ap_id desc";
					
									$result_apprv_detail = sqlsrv_query($conn, $query_apprv,$params);
									$row_app = sqlsrv_fetch_array($result_apprv_detail, SQLSRV_FETCH_ASSOC);
									if ($row_app) {
										$prev_step = $row_app['cus_ap_t_step_code'];
									} 
									$cus_ap_f_step = $prev_step;  
									$cus_ap_t_step = $cus_step_code; 
									if($cus_step_code=="20"){
										$cus_ap_text = "ฉบับแก้ไข "."ส่งอนุมัติ ";
										$cus_ap_color = "text-warning";
										//$cus_ap_remark = $cr_rem_revise;
									} 
									//เก็บประวัติการดำเนินการ
										
									$cus_ap_id = getcusnewapp($cus_app_nbr,$conn);
										
									$sql = "INSERT INTO cus_approval(" . 
									" cus_ap_id,cus_ap_nbr,cus_ap_f_step_code,cus_ap_t_step_code,cus_ap_text,cus_ap_remark,cus_ap_color,cus_ap_active,cus_ap_create_by,cus_ap_create_date)" .		
									" VALUES('$cus_ap_id','$cus_app_nbr','$cus_ap_f_step','$cus_ap_t_step','$cus_ap_text','$cus_ap_remark','$cus_ap_color','1','$user_login','$today')";				
									$result = sqlsrv_query($conn, $sql);			
								}
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
					}	
					//echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'"}';
				} 
				// สิ้นสุดกรณี revise ส่ง Mail ให้แผนกสินเชื่อ ตรวจสอบและอนุมัติ

				if ($cus_step_code=="50") { // กรณี sale แก้ไขเอกสารเรียบร้อย ส่งกลับหาผู้พิจารณา Reviwer1
					$revise_txt = "[ฉบับแก้ไข] ";
					$cus_approve_code = md5(gen_uuid());
					$params_apprv = array($cus_app_nbr);
					$sql_apprv = "select top 1 * FROM apprv_person Where apprv_cus_nbr = ? and apprv_status = '' order by apprv_seq";
					$result_apprv = sqlsrv_query($conn,$sql_apprv,$params_apprv); 
					if($result_apprv) {
						$top = "result Top";
						while($row_apprv = sqlsrv_fetch_array($result_apprv)) {
							$apprv_aplevel_code = $row_apprv['apprv_aplevel_code'];
							$apprv_type_code = $row_apprv['apprv_type_code'];
						}
						if(!empty($apprv_aplevel_code) and !empty($apprv_type_code)) {
							$params_apv = array($cus_app_nbr,$apprv_aplevel_code,$apprv_type_code);
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
										//$cr_output_filename = $cus_app_nbr.$filename.".pdf";
										$cr_output_filename = 'ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr.".pdf";
										if($cus_cond_cust == "c1" || $cus_cond_cust == "c2"){
											array_push($fileattach,$output_folder.print_formnewcust($cus_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
											array_push($fileattach_mailname,'ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr.".pdf");
										}
										else 
										{
											array_push($fileattach,$output_folder.print_formchgcust($cus_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
											array_push($fileattach_mailname,'ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr.".pdf");
										}
										$my_files = $fileattach;
										$my_filesname = $fileattach_mailname;
										$mail_from = $mail_from_text_app;
										$mail_from_email = $mail_credit_email;			
										$mail_to = $apprv_email;					
										$mail_topic = $revise_txt.$cardtxt;
										$mail_subject = "$mail_topic - เอกสารเลขที่ $cus_app_nbr $cus_reg_nme ค่ะ";
											
											$approve_url = "<a href='".$app_url."/sendmail/cr_send_mail_rev.php?auth=".$cus_approve_code."&nbr=".encrypt($cus_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_nextstep_cusstep_code, $dbkey)."&ch=Approve' target='_blank'><font color='green'>Approve</font></a>";
											$revise_url = "<a href='".$app_url."/sendmail/cr_send_mail_rev.php?auth=".$cus_approve_code."&nbr=".encrypt($cus_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_backstep_cusstep_code, $dbkey)."&ch=Revise' target='_blank'><font color='blue'>Revise</font></a>";
											$home = "<a href='".$app_url."/index.php' target='_blank'></a>";
											
											$message_action = "<tr><td >โปรดดำเนินการ ดังต่อไปนี้   </td><td>$home$home$approve_url &nbsp;|&nbsp; $home$revise_url </td></tr>";
											// ฟอร์ม Email แต่งตั้งลูกค้าใหม่ / แต่งตั้งร้านสาขา
											if($cus_cond_cust=="c1" || $cus_cond_cust=="c2"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้ </b></td></tr>
													<tr><td >หมายเลขเอกสาร :		  </td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :			  </td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
													<tr><td >ประเภทลูกค้าที่ขอแต่งตั้ง :	</td><td>$cust_type_name</td></tr>
													<tr><td >ชื่อจดทะเบียน :		    </td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			  </td><td>$address</td></tr>
													<tr><td >ผู้ขออนุมัติ : 			  </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		 </td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
													<tr><td >เหตุผลประกอบการขออนุมัติ :  </td><td>$cusd_sale_reason</td></tr>	
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	

											// ฟอร์ม Email เปลี่ยนแปลงชื่อ
											if($cus_cond_cust=="c3"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
													<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
													<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
													<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
													<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
													<tr><td >ชื่อจดทะเบียน :           </td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td>$address</td></tr>
													<tr><td >ผู้ขออนุมัติ : 		     </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
													<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	
			
											// ฟอร์ม Email เปลี่ยนแปลงที่อยู่จดทะเบียน
											if($cus_cond_cust=="c4"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</td></tr>
													<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
													<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
													<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
													<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td><span style='color:$color;'>$address</span></td></tr>
													<tr><td >ผู้ขออนุมัติ : 			 </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
													<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	

											// ฟอร์ม Email เปลี่ยนแปลงชื่อและที่อยู่
											if($cus_cond_cust=="c5"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
													<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
													<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
													<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
													<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td><span style='color:$color;'>$address</span></td></tr>
													<tr><td >ผู้ขออนุมัติ :				 </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
													<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	

										if ($can_sendmail) {
											if ($mail_to !="") {
												$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
												//$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
												if (!$sendstatus) {
													$r="0";
													$nb="";
													$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ<br>";
												}
												else 
												{
													$params = array($cus_app_nbr);
													$sql_updatestep = "UPDATE cus_app_mstr SET" .
													" cus_approve_code = '$cus_approve_code'" .
													" WHERE cus_app_nbr = ?";						
													$result_updatestep = sqlsrv_query($conn,$sql_updatestep, $params); 
												}
											} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ<br>";}
										}
										else {
											{$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ<br>";}
										}
									}
								}						
							}
						}
						else
						{
							$r="0";
							$errortxt="ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ";
							$nb="";	
						}
					} 

					if($user_email != ""){
						$mail_from = $mail_from_text;
						$mail_from_email = $mail_credit_email;
						$mail_to = $user_email;
						$mail_topic = $revise_txt;
						$mail_subject = "$mail_topic ใบขอ$cardtxt เลขที่ $cus_app_nbr : $cus_reg_nme  ได้ส่งไปให้ผู้พิจารณาแล้วค่ะ ";
						$mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน คุณ$auc_user_name <br><br>
						ใบขอ$cardtxt เลขที่ $cus_app_nbr $cus_reg_nme ได้ส่งไปให้ผู้พิจารณาแล้วค่ะ <br>
						$doc_url <br><br>
						
						ขอบคุณค่ะ<br></font>";
						$mail_message .= $mail_no_reply;
						if($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
								$r="0";
								$nb="";
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
					}	
					//echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
				} // สิ้นสุดกรณี sale แก้ไขเอกสารเรียบร้อย ส่งกลับหาผู้พิจารณา Reviwer1

				if ($cus_step_code=="511") { // กรณี sele แก้ไขส่ง mail กลับให้ผู้พิจารณาอนุมัติรายการแก้ไข
					$revise_txt = "[ฉบับแก้ไข] ";
					$cus_approve_code = md5(gen_uuid());
					
					$params_apprv = array($cus_app_nbr);
					$sql_apprv = "select top 1 * FROM apprv_person Where apprv_cus_nbr = ? and apprv_status = '' order by apprv_seq";
					$result_apprv = sqlsrv_query($conn,$sql_apprv,$params_apprv); 
					if($result_apprv) {
						$top = "result Top";
						while($row_apprv = sqlsrv_fetch_array($result_apprv)) {
							$apprv_aplevel_code = $row_apprv['apprv_aplevel_code'];
							$apprv_type_code = $row_apprv['apprv_type_code'];
						}
						if(!empty($apprv_aplevel_code) and !empty($apprv_type_code)) {
							$params_apv = array($cus_app_nbr,$apprv_aplevel_code,$apprv_type_code);
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
										//$cr_output_filename = $cus_app_nbr.$filename.".pdf";
										$cr_output_filename = 'ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr.".pdf";
										if($cus_cond_cust == "c1" || $cus_cond_cust == "c2"){
											array_push($fileattach,$output_folder.print_formnewcust($cus_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
											array_push($fileattach_mailname,'ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr.".pdf");
										}
										else 
										{
											array_push($fileattach,$output_folder.print_formchgcust($cus_app_nbr,true,$output_folder,$cr_output_filename,$conn,$watermark_text));
											array_push($fileattach_mailname,'ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr.".pdf");
										}
										$my_files = $fileattach;
										$my_filesname = $fileattach_mailname;
										$mail_from = $mail_from_text_app;
										$mail_from_email = $mail_credit_email;			
										$mail_to = $apprv_email;					
										$mail_topic = $revise_txt.$cardtxt;
										$mail_subject = "$mail_topic - เอกสารเลขที่ $cus_app_nbr $cus_reg_nme ค่ะ";
											
											$approve_url = "<a href='".$app_url."/sendmail/cr_send_mail_rev.php?auth=".$cus_approve_code."&nbr=".encrypt($cus_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_nextstep_cusstep_code, $dbkey)."&ch=Approve' target='_blank'><font color='green'>Approve</font></a>";
											$revise_url = "<a href='".$app_url."/sendmail/cr_send_mail_rev.php?auth=".$cus_approve_code."&nbr=".encrypt($cus_app_nbr, $dbkey)."&id=".encrypt($apprv_user_id, $dbkey)."&act=".encrypt($apprv_backstep_cusstep_code, $dbkey)."&ch=Revise' target='_blank'><font color='blue'>Revise</font></a>";
											$home = "<a href='".$app_url."/index.php' target='_blank'></a>";
											
											$message_action = "<tr><td >โปรดดำเนินการ ดังต่อไปนี้   </td><td>$home$home$approve_url &nbsp;|&nbsp; $home$revise_url </td></tr>";
											// ฟอร์ม Email แต่งตั้งลูกค้าใหม่ / แต่งตั้งร้านสาขา
											if($cus_cond_cust=="c1" || $cus_cond_cust=="c2"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
													<tr><td >ประเภทลูกค้าที่ขอแต่งตั้ง :   </td><td>$cust_type_name</td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td>$address</td></tr>
													<tr><td >ผู้ขออนุมัติ : 		     </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
													<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	

											// ฟอร์ม Email เปลี่ยนแปลงชื่อ
											if($cus_cond_cust=="c3"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :</td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :			 </td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
													<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
													<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
													<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
													<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td>$address</td></tr>
													<tr><td >ผู้ขออนุมัติ : 			 </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
													<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	
			
											// ฟอร์ม Email เปลี่ยนแปลงที่อยู่จดทะเบียน
											if($cus_cond_cust=="c4"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :		 </td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :			 </td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</td></tr>
													<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
													<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
													<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
													<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td><span style='color:$color;'>$address</span></td></tr>
													<tr><td >ผู้ขออนุมัติ : 			 </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ :		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
													<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	

											// ฟอร์ม Email เปลี่ยนแปลงชื่อและที่อยู่
											if($cus_cond_cust=="c5"){
												//$mail_message = "<table cellpadding='2' cellspacing='0' width='900' border='0' style='text-align:left; font-size:16px; font-family:tahoma,verdana,san-serif;'>
												$mail_message = "<table cellpadding='2' cellspacing='0' width='600' border='0' style='text-align:left; font-size:14px; font-family:tahoma,verdana,san-serif;'>
													<tr><td colspan='3'><b>เรียน คุณ$apprv_name</b></td></tr>
													<tr><td colspan='3'><b>เรื่อง $mail_topic</b></td></tr>

													$message_action
													<tr><td colspan='3'><b>รายละเอียดดังนี้</b></td></tr>
													<tr><td >หมายเลขเอกสาร :		</td><td ><span style='color:blue; font-weight:bold;'>$cus_app_nbr</span></td></tr>
													<tr><td >ประเภทเอกสาร :			 </td><td ><span style='color:blue; font-weight:bold;'>$cardtxt</span></td></tr>
													<tr><td colspan='3'><b>ข้อมูลเดิม</b></td></tr>
													<tr><td >ชื่อลูกค้า :		  		</td><td>$cus_code_name</td></tr>
													<tr><td >ที่อยู่ :					</td><td>$cus_address</td></tr>
													<tr><td colspan='3'><b>ข้อมูลใหม่</b></td></tr>
													<tr><td >ชื่อจดทะเบียน :		   </td><td><span style='color:$color;'>$cus_reg_nme</span></td></tr>
													<tr><td >ที่อยู่จดทะเบียน :			 </td><td><span style='color:$color;'>$address</span></td></tr>
													<tr><td >ผู้ขออนุมัติ : 			 </td><td>$auc_user_name</td></tr>
													<tr><td >ขออนุมัติ เมื่อวันที่ : 		</td><td>".date_format($cus_create_date,"d/m/Y H:i:s น.")."</td></tr>											
													<tr><td >เหตุผลประกอบการขออนุมัติ : </td><td>$cusd_sale_reason</td></tr>	
																			
													<tr><td colspan='3'>$mail_no_reply</td></tr>
												</table>";	
											}	

										if ($can_sendmail) {
											if ($mail_to !="") {
												$sendstatus = mail_multiattachment($my_files, $my_filesname, $mail_to, $mail_from_email, $mail_from, $mail_subject, $mail_message);
												//$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
												if (!$sendstatus) {
													$r="0";
													$nb="";
													$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ<br>";
												}
												else 
												{
													$params = array($cus_app_nbr);
													$sql_updatestep = "UPDATE cus_app_mstr SET" .
													" cus_approve_code = '$cus_approve_code'" .
													" WHERE cus_app_nbr = ?";						
													$result_updatestep = sqlsrv_query($conn,$sql_updatestep, $params); 
												}
												if($result_updatestep)
												{
													// เช็คคนอนุมัติก่อนหน้านี้
													$params = array($cus_app_nbr);
													$query_apprv = "SELECT top 1 * FROM cus_approval where cus_ap_nbr = ?  and cus_ap_active = '1' order by cus_ap_id desc";
													$result_apprv_detail = sqlsrv_query($conn, $query_apprv,$params);
													$row_app = sqlsrv_fetch_array($result_apprv_detail, SQLSRV_FETCH_ASSOC);
														if ($row_app) {
															$prev_step = $row_app['cus_ap_t_step_code'];
														}
														//เก็บประวัติการดำเนินการ
														$cus_ap_f_step = $prev_step;  // step ก่อนหน้านี้
														$cus_ap_t_step = $cus_step_code; 
														
														$apprv_aplevel_name = "ฉบับแก้ไข";
														$cus_ap_remark = "แก้ไข";
														$cus_ap_text = $apprv_aplevel_name." "."ส่งอนุมัติ";
														$cus_ap_color = "text-warning";
															
														$cus_ap_id = getcusnewapp($cus_app_nbr,$conn);
															
														$sql = "INSERT INTO cus_approval(" . 
														" cus_ap_id,cus_ap_nbr,cus_ap_f_step_code,cus_ap_t_step_code,cus_ap_text,cus_ap_remark,cus_ap_color,cus_ap_active,cus_ap_create_by,cus_ap_create_date)" .		
														" VALUES('$cus_ap_id','$cus_app_nbr','$cus_ap_f_step','$cus_ap_t_step','$cus_ap_text','$cus_ap_remark','$cus_ap_color','1','$user_login','$today')";				
														$result = sqlsrv_query($conn, $sql);
												}
											} else {$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ<br>";}
										}
										else {
											{$errortxt .= "ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ<br>";}
										}
									}
								}						
							}
						}
						else
						{
							$r="0";
							$errortxt="ไม่สามารถส่ง Email แจ้งผู้อนุมัติได้ค่ะ";
							$nb="";	
						}
					} 

					if($user_email != ""){
						$mail_from = $mail_from_text;
						$mail_from_email = $mail_credit_email;
						$mail_to = $user_email;
						$mail_subject = $revise_txt."ใบขอ$cardtxt เลขที่ $cus_app_nbr : $cus_reg_nme  ได้ส่งไปให้ผู้พิจารณาแล้วค่ะ ";
						$mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน คุณ$auc_user_name <br><br>
						ใบขอ$cardtxt เลขที่ $cus_app_nbr $cus_reg_nme ได้ส่งไปให้ผู้พิจารณาแล้วค่ะ <br>
						$doc_url <br><br>
						
						ขอบคุณค่ะ<br></font>";
						$mail_message .= $mail_no_reply;
						if($mail_to!="") {
							$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
							if (!$sendstatus) {
								$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
								$r="0";
								$nb="";
							}
						} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
					}	
				}
			}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'"}';
	}
	else
	{
			$r="0";
	 		$nb="";
	 		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'"}';
	}
?>									