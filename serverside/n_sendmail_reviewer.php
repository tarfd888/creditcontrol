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
$cus_ap_remark = mssql_escape($_POST['cmmt']);


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
	else 
	{ //post มาจาก email
		//Use Double Cookie for recheck CSRF
		$sessionid = session_id();
		$rev_verify_csrf_mail =  mssql_escape($_COOKIE['rev_verify_csrf_mail']);
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
			$cus_auth_code = mssql_escape($_POST['cus_auth_code']);
			$cus_approved_by = decrypt(mssql_escape($_POST['cus_approved_by']), $dbkey);
			$cus_approve_nbr = decrypt(mssql_escape($_POST['cus_approve_nbr']), $dbkey);
			$cus_approve_step = decrypt(mssql_escape($_POST['cus_approve_step']), $dbkey);
			//$auth_appr =  strtoupper(explode("@",$cus_approved_by)[0]);
			
			if ($cus_auth_code!="" && $cus_approve_nbr!="") {
				if (inlist("10,51,52",$cus_approve_step)) {  // กรณีไม่มี 10 reviewer ให้ส่งข้อมูลผ่าน Mail ให้แผนกสินเชื่อ ตรวจสอบและอนุมัติ	|  กรณีไม่มี 51 revise
					$params = array($cus_approve_nbr);
					$query = "SELECT * FROM  cus_app_mstr WHERE cus_app_nbr = ?";
					$result = sqlsrv_query($conn, $query, $params, array("Scrollable" => 'keyset' ));
					$rowCounts = sqlsrv_num_rows($result);
					if($rowCounts > 0){
						while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
						{
							$cus_app_nbr = mssql_escape($row['cus_app_nbr']);
							$cus_reg_nme = mssql_escape($row['cus_reg_nme']);
							$cus_cond_cust = mssql_escape($row['cus_cond_cust']);
							$cus_create_by = mssql_escape($row['cus_create_by']);
							$cus_step_code = mssql_escape($row['cus_step_code']);
							$auc_user_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$cus_create_by,$conn);
							$auc_user_name = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$cus_create_by,$conn);
							switch($cus_cond_cust){
								case "c1" :
									$cardtxt = "แต่งตั้งลูกค้าใหม่";
									break;
								case "c2" :
									$cardtxt = "แต่งตั้งร้านสาขา";
									break;  
								case "c3" :
									$cardtxt = "เปลี่ยนแปลงชื่อ";
									break; 
								case "c4" :
									$cardtxt = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
									break; 	   
								case "c5" :
									$cardtxt = "เปลี่ยนแปลงชื่อและที่อยู่";
									break;           
								default :
									$cardtxt = "ยกเลิกลูกค้า";
							}
							$allow_post = true;
						}
					}	

					// เช็คว่ามีการอนุมัติซ้ำหรือไม่
					$params = array($cus_approve_nbr,$cus_approved_by);
					$query_apprv = "SELECT * FROM apprv_person where apprv_cus_nbr = ? and apprv_user_id = ?  and apprv_lasted = '' order by apprv_seq";

					$result_apprv_detail = sqlsrv_query($conn, $query_apprv,$params);
					$row_app = sqlsrv_fetch_array($result_apprv_detail, SQLSRV_FETCH_ASSOC);
					if ($row_app) {
						$apprv_user_id = strtolower($row_app['apprv_user_id']);
						$apprv_name = $row_app['apprv_name'];
						$apprv_status = $row_app['apprv_status'];
						$apprv_by = $row_app['apprv_by'];
						$apprv_date = $row_app['apprv_date'];
						if($apprv_date!=""){
							$today_show = date_format($apprv_date,"d/m/Y H:i:s");
						}
					}			
					if(($apprv_by == $cus_approved_by) || ($apprv_status == "AP")) { // เช็คเอกสารว่ามีการ approve หรือยัง
						$allow_post = false;
						$r="0";
						$errortxt="<span style='color:red'><h4 style='text-align:center'>*** เอกสารฉบับนี้ ได้ถูกอนุมัติไปแล้ว ***<br><br>โดย คุณ$apprv_name เวลา $today_show   <h4></span>";
						echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
						exit;
					}
					if(($cus_step_code == $cus_approve_step)) { // เช็คเอกสารว่ามีการส่งไป revise หรือยัง
						$allow_post = false;
						$r="0";
						$errortxt="<span style='color:red'><h4 style='text-align:center'>*** เอกสารฉบับนี้ ได้ถูกส่งกลับไปแก้ไขแล้ว *** <h4></span>";
						echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
						exit;
					}
					if(($cus_step_code == "51") && ($cus_approve_step=="10")) { // เอกสารมีการส่งกลับไปแก้ไข ไม่สามารถกด approve ได้
						$allow_post = false;
						$r="0";
						$errortxt="<span style='color:red'><h4 style='text-align:center'>*** เอกสารฉบับนี้ ได้ถูกส่งกลับไปแก้ไขแล้ว ไม่สามารถอนุมัติในขั้นตอนนี้ได้ *** <h4></span>";
						echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
						exit;
					}
				} 	
			}
			else {
				$allow_post = false;
				$r="0";
				$errortxt="** คุณไม่มีสิทธิ์อนุมัติเอกสารฉบับนี้ค่ะ **";
			}
		}
	}
	
	if ($allow_post) {
		$doc_url = "<a href='".$app_url."/index.php' target='_blank'>คลิ๊กเพื่อเข้าสู่ระบบ</a>";

		// เช็คคนอนุมัติก่อนหน้านี้
		$params = array($cus_app_nbr);
		$query_apprv = "SELECT top 1 * FROM cus_approval where cus_ap_nbr = ?  and cus_ap_active = '1' order by cus_ap_id desc";

		$result_apprv_detail = sqlsrv_query($conn, $query_apprv,$params);
		$row_app = sqlsrv_fetch_array($result_apprv_detail, SQLSRV_FETCH_ASSOC);
		if ($row_app) {
			$prev_step = $row_app['cus_ap_t_step_code'];
		} else {
			$prev_step = "0";
		}	
		$cus_ap_f_step = $prev_step;  
		$cus_ap_t_step = $cus_approve_step; // ผผ, ผส

		if($cus_approve_step!="51"){
			$cus_ap_text = "ผู้พิจารณา 1 "."อนุมัติ ";
			$cus_ap_color = "text-info";
		} else {
			$cus_ap_text = "ผู้พิจารณา 1 "."Revise";
			$cus_ap_color = "text-warning";
		}
		//เก็บประวัติการดำเนินการ
			
		$cus_ap_id = getcusnewapp($cus_approve_nbr,$conn);
			
		$sql = "INSERT INTO cus_approval(" . 
		" cus_ap_id,cus_ap_nbr,cus_ap_f_step_code,cus_ap_t_step_code,cus_ap_text,cus_ap_remark,cus_ap_color,cus_ap_active,cus_ap_create_by,cus_ap_create_date)" .		
		" VALUES('$cus_ap_id','$cus_approve_nbr','$cus_ap_f_step','$cus_ap_t_step','$cus_ap_text','$cus_ap_remark','$cus_ap_color','1','$cus_approved_by','$today')";				
		$result = sqlsrv_query($conn, $sql);

		$user_login = $cus_approved_by;
										
		$params_login = array($user_login);
		$sql_emp = "SELECT * from emp_mstr where emp_user_id = ? ";
		$result_emp = sqlsrv_query($conn, $sql_emp,$params_login);	
		$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
		if ($r_emp) {
			$user_fullname = html_escape(trim($r_emp["emp_th_firstname"])) . " " . html_escape(trim($r_emp["emp_th_lastname"]));
			$user_fullname = rep_prefix_name($user_fullname,'');
			$user_email = html_escape($r_emp['emp_email_bus']);
		}
		else {						
			$allow_post = false;
			$r="0";
			$errortxt="**ไม่พบข้อมูลพนักงานผู้อนุมัติ**";
		}
		////
		if (inlist("10",$cus_approve_step)) {  // ส่งอีเมลไปยังแผนกสินเชื่อ credit1	
	   	  //ส่งอีเมลหาแผนกสินเชื่อ credit1 
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

		   $mail_from = "คุณ".$auc_user_name;
		   $mail_from_email = $auc_user_email;
		   $mail_to = $cr_next_curprocessor_email;
		   $mail_subject = "Credit 1 โปรดดำเนินการ: ใบขอ$cardtxt เลขที่ $cus_app_nbr : $cus_reg_nme";
		   $mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน แผนกสินเชื่อ (Credit 1)<br><br>
		   ใบขอ$cardtxt เลขที่ $cus_app_nbr $cus_reg_nme<br>
		   Credit 1 : โปรดดำเนินการในระบบ Credit Control ด้วยค่ะ  <br><br>

		   <b>ข้อมูลผู้ดำเนินการคนล่าสุด</b><br>		
		   ผู้ดำเนินการ : $user_fullname<br>
		   ดำเนินการ เมื่อวันที่ : $today<br>
		   ข้อความจากผู้ดำเนินการ  (ถ้ามี) : $cus_ap_remark<br><br>	

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
				   $errortxt="ส่งอีเมลไปยังแผนกสินเชื่อเรียบร้อยแล้ว.";
				   $nb=encrypt($cus_step_code, $key);
			   }
			   
		   } else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
		   
		   if($auc_user_email != ""){
			   $mail_from = $mail_from_text;
			   $mail_from_email = $mail_credit_email;
			   $mail_to = $auc_user_email;
			   $mail_subject = "[$cardtxt] เลขที่ $cus_app_nbr : $cus_reg_nme  ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ ";
			   $mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน คุณ$auc_user_name <br><br>
			   ใบขอ$cardtxt เลขที่ $cus_app_nbr $cus_reg_nme ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ <br><br>

			   <b>ข้อมูลผู้ดำเนินการคนล่าสุด</b><br>		
			   ผู้ดำเนินการ : $user_fullname<br>
			   ดำเนินการ เมื่อวันที่ : $today<br>
			   ข้อความจากผู้ดำเนินการ  (ถ้ามี) : $cus_ap_remark<br><br>	
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
						$params = array($cus_approve_nbr);
						$sql = "UPDATE cus_app_mstr SET ".
						"cus_step_code = '$cus_approve_step', ".
						"cus_curprocessor = '$cus_approved_by' ".
						"WHERE cus_app_nbr = ? ";
						$result = sqlsrv_query($conn,$sql,$params);

						if($result){
							$params = array($cus_approve_nbr,$cus_approved_by);
							$sql = "UPDATE apprv_person	SET ".
							"apprv_by = '$cus_approved_by', ".
							"apprv_status = 'AP', ".
							"apprv_date = '$today' ".
							" WHERE apprv_cus_nbr = ? and apprv_user_id = ? ";
							$result = sqlsrv_query($conn,$sql,$params);
						}
						
				   }
			   } else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
					$r="1";
					$errortxt="ส่งอีเมลเรียบร้อยแล้ว.";
					$nb=encrypt($cus_step_code, $key);
		   }	
		} 
		// สิ้นสุด 10 credit1	

		// revise ส่งอีเมลไปยัง SEC
		if (inlist("51,52",$cus_approve_step)) { 
			//ส่งเมลหาผู้อนุมัติคนถัดไป
			$revise_txt = "[ฉบับแก้ไข] ";
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
					$cus_app_nbr = $row['cus_app_nbr'];
					$cus_cust_type = $row['cus_cust_type'];
					$cus_cond_cust = $row['cus_cond_cust'];
					$cus_step_code = $row['cus_step_code'];
					$cus_create_by = $row['cus_create_by'];
					$auc_user_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$cus_create_by,$conn);
					$auc_user_name = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$cus_create_by,$conn);
				}
			}	
			$cust_type_name = findsqlval("cus_type_mstr","cus_type_name","cus_type_code",$cus_cust_type,$conn);	
			$cusd_sale_reason = findsqlval("cus_app_det","cusd_sale_reason","cusd_app_nbr",$cus_app_nbr,$conn);	
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

			if(($cus_approve_step == $cus_step_code)) { // เช็คเอกสารว่ามีการ approve หรือยัง
				$allow_post = false;
				$r="0";
				$errortxt="<span style='color:red'><h4 style='text-align:center'>*** เอกสารฉบับนี้ ได้ถูกส่งกลับไปแก้ไขแล้ว *** <h4></span>";
				echo '{"r":"'.$r.'","e":"'.$errortxt.'"}';
				exit;
			}

			$params = array($cus_approve_nbr,$cus_approved_by);
			$query_apprv = "SELECT * FROM apprv_person where apprv_cus_nbr = ? and apprv_user_id = ?  and apprv_status = '' order by apprv_seq";
			$result_apprv_detail = sqlsrv_query($conn, $query_apprv,$params);
			$row_app = sqlsrv_fetch_array($result_apprv_detail, SQLSRV_FETCH_ASSOC);
			if ($row_app) {
				$apprv_user_id = strtolower($row_app['apprv_user_id']);
				$apprv_name = $row_app['apprv_name'];
				$apprv_status = $row_app['apprv_status'];
				$apprv_by = $row_app['apprv_by'];
				$apprv_backstep_cusstep_code = $row_app['apprv_backstep_cusstep_code'];
			}			

			$mail_from = $mail_from_text;
			$mail_from_email = $mail_credit_email;
			$mail_to = $auc_user_email;
			$mail_subject = $revise_txt."- เอกสารเลขที่ $cus_app_nbr : $cus_reg_nme ส่งกลับมาแก้ไข ";
			$mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน คุณ$owner_name <br><br>
			เอกสารเลขที่ $cus_app_nbr $cus_reg_nme ได้ส่งเอกสารฉบับนี้กลับมาแก้ไข<br>
			$doc_url <br><br>
			<span style='color:green'><strong>*** กรุณาตรวจสอบเอกสารใหม่อีกครั้ง ***</strong> </span><br><br>
			ขอบคุณค่ะ</font>";
			$mail_message .= "<br>" .$mail_no_reply;
			
			if ($mail_to!="") {
				$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					$r="0";
					$nb="";
				} 
				else 
				{
					$params = array($cus_approve_nbr);
					$sql_updatestep = "UPDATE cus_app_mstr SET" .
					" cus_step_code = '$apprv_backstep_cusstep_code'" .
					" WHERE cus_app_nbr = ?";						
					$result_updatestep = sqlsrv_query($conn,$sql_updatestep, $params); 

					$r="1";
					$errortxt="ส่งอีเมลเรียบร้อยแล้ว.";
					$nb=encrypt($cus_step_code, $key);
				}
				
			} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
		}	 	
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	} 
	else {
		$r="0";
		$nb="";
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
?>