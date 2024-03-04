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
$uploadpath = "../_fileuploads/sale/project/";
if(!is_dir($uploadpath)){
	 mkdir($uploadpath,0,true);
}
chmod($uploadpath ,0777);

$uploadpath_cr = "../_fileuploads/ac/cr/";
if(!is_dir($uploadpath_cr)){
	 mkdir($uploadpath_cr,0,true);
}
chmod($uploadpath_cr ,0777);

$params = array();

set_time_limit(0);
date_default_timezone_set('Asia/Bangkok');
$today = date("Y-m-d H:i:s");
$curr_date = ymd(date("d/m/Y"));
$errortxt = "";
$allow_post = false;
$crstm_reviewer_date = ""; $crstm_reviewer2_date = ""; $crstm_stamp_app1_date = ""; $crstm_stamp_app2_date = "";

$step_code = html_escape($_GET['step_code']);
$crstm_step_code = html_escape(decrypt($step_code, $key));
//$crstm_step_code = html_escape($_REQUEST['step_code']);

/// rearward 
// $rem1 = html_escape($_REQUEST['rem1']);
// $crstm_cc1_reson_rem1 = html_escape(decrypt($rem1, $key));
// $rem2 = html_escape($_GET['rem2']);
// $crstm_cc1_reson_rem2 = html_escape(decrypt($rem2, $key));

$formid = html_escape($_GET['formid']);

$pg = html_escape($_POST['pg']);
$action = html_escape($_POST['action']);

//--1. Parameter From crctrladd_new.php
//-- Section I : ผู้ขอเสนออนุมัติ
$crstm_nbr = html_escape($_POST['crstm_nbr']);	
$crstm_date = html_escape(ymd($_POST['curr_date']));
$crstm_tel = html_escape($_POST['phone_mask']);
$crstm_cus_nbr = html_escape($_POST['crstm_cus_nbr']);

$crstm_cus_name = html_escape($_POST['crstm_cus_name']);

$crstm_tax_nbr3 = html_escape($_POST['crstm_tax_nbr3']);
$crstm_address = html_escape($_POST['crstm_address']);
$crstm_district = html_escape($_POST['crstm_district']);

$crstm_amphur = html_escape($_POST['crstm_amphur']);
$crstm_province = html_escape($_POST['crstm_province']);
$crstm_zip = html_escape($_POST['crstm_zip']);
$crstm_country = html_escape($_POST['crstm_country']);
// Check bok 
$crstm_chk_rdo1 = html_escape($_POST['cus_conf']);
$crstm_chk_rdo2 = html_escape($_POST['chk_rdo']);
$crstm_term_add = html_escape($_POST['term_desc_add']);

$crstm_approve = html_escape($_POST['crstm_approve']);
$crstm_sd_reson = html_escape($_POST['crstm_sd_reson']);
$crstm_cc1_reson = html_escape($_POST['crstm_cc1_reson']);

// Section I Project Information
$crstm_pj_name = html_escape($_POST['crstm_pj_name']);
$crstm_pj_prv = html_escape($_POST['crstm_pj_prv']);
$crstm_pj_term = html_escape($_POST['crstm_pj_term']);
$crstm_pj_amt = mssql_escape(str_replace(",","",$_POST['crstm_pj_amt']));

$crstm_pj_dura = html_escape($_POST['crstm_pj_dura']);
$crstm_pj_beg = html_escape(ymd($_POST['crstm_pj_beg']));

$crstm_pj1_name = html_escape($_POST['crstm_pj1_name']);
$crstm_pj1_prv = html_escape($_POST['crstm_pj1_prv']);
$crstm_pj1_term = html_escape($_POST['crstm_pj1_term']);
$crstm_pj1_amt = mssql_escape(str_replace(",","",$_POST['crstm_pj1_amt']));
$crstm_pj1_dura = html_escape($_POST['crstm_pj1_dura']);
$crstm_pj1_beg = html_escape(ymd($_POST['crstm_pj1_beg']));

$crstm_dbd_rdo = html_escape($_POST['web_conf']);
$crstm_dbd_yy = html_escape($_POST['crstm_dbd_yy']);
$crstm_dbd1_yy = html_escape($_POST['crstm_dbd1_yy']);
$crstm_cc2_reson = html_escape($_POST['crstm_cc2_reson']);

$crstm_mgr_reson = html_escape($_POST['crstm_mgr_reson']);
$crstm_mgr_rdo = html_escape($_POST['mgr_conf']);
$crstm_cr_mgr = html_escape(str_replace(",","",$_POST['crstm_cr_mgr']));

$crstm_cc_date_beg = html_escape(ymd($_POST['beg_date_new']));
$crstm_cc_date_end = html_escape(ymd($_POST['end_date_new']));
$crstm_cc_amt = html_escape(str_replace(",","",$_POST['cc_amt1']));

$crstm_sd_per_mm = mssql_escape(str_replace(",","",$_POST['crstm_sd_per_mm']));
$crstm_rem_rearward = html_escape($_POST['crstm_rem_rearward']);

$crstm_reviewer = mssql_escape($_POST['crstm_reviewer']);
$crstm_reviewer2 = mssql_escape($_POST['crstm_reviewer2']);
$crstm_noreviewer = mssql_escape($_POST['crstm_noreviewer']);
$crstm_scgc = mssql_escape($_POST['crstm_scgc']);
$crstm_email_app1 = mssql_escape($_POST['crstm_email_app1']);
$crstm_email_app2 = mssql_escape($_POST['crstm_email_app2']);
$crstm_email_app3 = mssql_escape($_POST['crstm_email_app3']);

$crstm_whocanread = html_escape($_POST['crstm_whocanread']);

$stamp_date = html_escape($_POST['stamp_date']);
$stamp1_date = html_escape($_POST['stamp1_date']);

if ($crstm_mgr_rdo == "1") {
	$crstm_step_code = $crstm_step_code;	
//}else if ($crstm_mgr_rdo == "2" && $crstm_step_code=="40"){
}else if ($crstm_mgr_rdo == "2"){	
	$crstm_step_code = "41";  // status non approve
}

$del_reson = html_escape($_POST['del_reson']);
$del_pj = html_escape($_POST['del_pj']);
$del_pj1 = html_escape($_POST['del_pj1']);
$del_cr1 = html_escape($_POST['del_cr1']);
$del_dbd = html_escape($_POST['del_dbd']);
$del_dbd1 = html_escape($_POST['del_dbd1']);
$del_cr2 = html_escape($_POST['del_cr2']);

$crstm_step_name = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_step_code ,$conn);

//--2. INPUT VALIDATION
$errorflag = false;
$errortxt = "";
if (inlist("add_new,edit_new",$action)) {	
		// Section I VALIDATION
		if ($crstm_cus_name=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ชื่อลูกค้า ]";
		}
		if ($crstm_tel=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ เบอร์โทรศัพท์  ]";
		}
		if ($crstm_tax_nbr3=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ เลขประจำตัวผู้เสียภาษี  ]";
		}
		if ($crstm_address=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ที่อยู่  ]";
		}
		if ($crstm_district=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ตำบล / แขวง  ]";
		}
		if ($crstm_amphur=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ อำเภอ / เขต  ]";
		}
		if ($crstm_province=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ จังหวัด  ]";
		}
		if ($crstm_zip=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ รหัสไปรษณีย์  ]";
		}
		if ($crstm_country=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ประเทศ  ]";
		}
		if ($crstm_sd_per_mm=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ประมาณการณ์ขายเฉลี่ยต่อเดือน ]";
		}
		if ($crstm_chk_rdo2=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [--- เสนอขออนุมัติวงเงิน --- ]";
		}
		if ($crstm_term_add=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [--- เงื่อนไขการชำระเงิน --- ]";
		}
		
		if ($crstm_cc_date_beg=="" || $crstm_cc_date_beg=="yyyymmdd") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [--- วันเริ่มอายุวงเงิน --- ]";
		}
		if ($crstm_cc_date_end=="" || $crstm_cc_date_end=="yyyymmdd") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [--- วันที่สิ้นสุดอายุวงเงิน --- ]";
		}
		if ($crstm_cc_amt=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [--- วงเงิน --- ]";
		}
		
		if ($crstm_cc_date_beg > $crstm_cc_date_end) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [--- วันที่เริ่มอายุวงเงินใหม่ ควรน้อยกว่าวันที่สิ้นสุดอายุวงเงิน --- ]";
		}
		
		if ($crstm_sd_reson=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [--- ความเห็น / เหตุผลที่เสนอขอวงเงิน  --- ]";
		}
		if ($crstm_reviewer =="" && $crstm_noreviewer == false) {
		 	if ($errortxt!="") {$errortxt .= "<br>";}
		 		$errorflag = true;					
		 		$errortxt .= "[--- กรุณาระบุผู้พิจารณาด้วยค่ะ --- ]";
		}
		if ($crstm_reviewer !="" && $crstm_noreviewer == true) {
		 	if ($errortxt!="") {$errortxt .= "<br>";}
		 		$errorflag = true;					
		 		$errortxt .= "[--- กรุณาระบุผู้พิจารณาด้วยค่ะ หรือ ติ๊กไม่ระบุผู้ตรวจสอบ  อย่างใดอย่างหนึ่ง --- ]";
		}
	   if ($crstm_email_app1=="") {
		   if ($errortxt!="") {$errortxt .= "<br>";}
		   $errorflag = true;					
		   $errortxt .= "กรุณาระบุ - [ อีเมลผู้อนุมัติวงเงิน ]";
	   }
	   if ($crstm_scgc=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ลูกค้า Tiles หรือ Geoluxe ]";
	   }	
		if ((($crstm_cc1_reson=="") && inlist($user_role,"Action_View1")) && inlist('10',$crstm_step_code)) {

				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;
				$errortxt .= "กรุณาระบุ - [--- ความเห็นสินเชื่อ #1 --]";
			}
	if ($formid != "frm_send_cr1") {	
		if ((($crstm_cc2_reson=="") && inlist($user_role,"Action_View2")) && inlist('20',$crstm_step_code)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;
				$errortxt .= "กรุณาระบุ - [--- ความเห็นสินเชื่อ #2 --]";
			}
		
		/* if ((($crstm_dbd_rdo=="")&& inlist($user_role,"Action_View2")) && inlist('20',$crstm_step_code)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [--- งบการเงินจากเว็บไซต์กรมพัฒนาธุรกิจ  / งบการเงินจากแหล่งอื่นๆ --- ]";
		} */	
		
		/* if ((($crstm_dbd_yy=="")&& inlist($user_role,"Action_View2")) && inlist('20',$crstm_step_code)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [---ปีงบการเงินล่าสุด --- ]";
		}	 */
	}	
		
		if ((($crstm_mgr_reson=="") && inlist($user_role,"FinCR Mgr")) && inlist('30,41',$crstm_step_code)) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;
			$errortxt .= "กรุณาระบุ - [--- ความเห็น Manager --]";
		}
		if ((($crstm_mgr_rdo=="") && inlist($user_role,"FinCR Mgr")) && inlist('30,41',$crstm_step_code)) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;
			$errortxt .= "กรุณาระบุ - [--- เห็นควรอนุมัติวงเงิน / ไม่เห็นควรอนุมัติ --]";
		}	
}
	if ($action == "add_new") {
		$crstm_whocanread = "ADMIN";
		//$crstm_step_code = "0" ; // Step Draft
		//ADD ผู้สร้างเป็น ADMIN แก้ไขโปรเจคได้
		if ($user_login!="") {
			if(!inlist($crstm_whocanread,$user_login)) {
				if ($crstm_whocanread != "") { $crstm_whocanread = $crstm_whocanread .","; }
				$crstm_whocanread = $crstm_whocanread . $user_login;
			}
		}
		
		$crstm_nbr = getcrstmnbr("CR-",$conn);
		$crstm_cus_nbr = getnewcusid("19",$conn);
		$crstm_cus_active = "0";
		
		$project_id = $crstm_nbr;	
		$random = (rand()%9);
		////file name ให้ขึ้นต้นด้วย "SDV-" เช่น  SDV-CR-2002-0001-00X.JPG  เอาไว้ที่ folder _fileuploads/sale/sale
		//Image Upload Section
		
		if( isset($_FILES['load_reson_img']['name'])) {
			// Check if file is selected
			if(isset($_FILES['load_reson_img']['name']) && $_FILES['load_reson_img']['size']> 0) {
				// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_reson_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_reson_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_reson_img']['size']< $maxFileSize){
							$new_filename0 = "SDV-".$project_id."_001"."_".$random.".".$ext; 
							
							$serverPath = $uploadpath; 
							$directoryFile =$serverPath.basename($new_filename0);
							move_uploaded_file($_FILES["load_reson_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_reson_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
		}
		if( isset($_FILES['load_pj_img']['name'])) {
			// Check if file is selected
			if(isset($_FILES['load_pj_img']['name']) && $_FILES['load_pj_img']['size']> 0) {
				// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_pj_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_pj_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_pj_img']['size']< $maxFileSize){
							$new_filename = "SDV-".$project_id."_002"."_".$random.".".$ext; 
							
							$serverPath = $uploadpath; 
							$directoryFile =$serverPath.basename($new_filename);
							move_uploaded_file($_FILES["load_pj_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_pj_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
		}
		
		if( isset($_FILES['load_pj1_img']['name'])) {
			// Check if file is selected
			if(isset($_FILES['load_pj1_img']['name']) && $_FILES['load_pj1_img']['size']> 0) {
				// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_pj1_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_pj1_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_pj1_img']['size']< $maxFileSize){
							$new_filename1 = "SDV-".$project_id."_003"."_".$random.".".$ext; 
							
							$serverPath = $uploadpath; 
							$directoryFile =$serverPath.basename($new_filename1);
							move_uploaded_file($_FILES["load_pj1_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_pj1_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
		}
		if (!$errorflag) {
		 	$sql_add = " INSERT INTO crstm_mstr (crstm_nbr,crstm_date,crstm_user,crstm_tel,crstm_cus_nbr,crstm_cus_name, ".
			" crstm_tax_nbr3,crstm_address,crstm_district,crstm_amphur,crstm_province,crstm_zip,crstm_country, ".
			" crstm_sd_reson,crstm_whocanread,crstm_curprocessor,crstm_chk_rdo1,crstm_chk_rdo2,crstm_term_add, ".
			" crstm_approve,crstm_reson_img, crstm_pj_img,crstm_pj1_img, ".
			" crstm_pj_name,crstm_pj_prv,crstm_pj_term,crstm_pj_dura,crstm_pj_beg,crstm_pj_amt, ".
			" crstm_pj1_name,crstm_pj1_prv,crstm_pj1_term,crstm_pj1_dura,crstm_pj1_beg,crstm_pj1_amt, ".
			" crstm_cc_date_beg,crstm_cc_date_end,crstm_cc_amt,crstm_sd_per_mm,crstm_reviewer,crstm_noreviewer,crstm_scgc, ".
			" crstm_email_app1,crstm_reviewer2,crstm_reviewer_date,crstm_reviewer2_date,crstm_stamp_app1_date,crstm_stamp_app2_date, ".
			" crstm_email_app2,crstm_email_app3,crstm_create_by,crstm_create_date,crstm_step_code,crstm_cus_active,crstm_step_name)".
			
			" VALUES ('$crstm_nbr','$curr_date','$user_code','$crstm_tel','$crstm_cus_nbr','$crstm_cus_name',
			'$crstm_tax_nbr3','$crstm_address','$crstm_district','$crstm_amphur','$crstm_province','$crstm_zip','$crstm_country',
			'$crstm_sd_reson','$crstm_whocanread','$user_login','$crstm_chk_rdo1','$crstm_chk_rdo2','$crstm_term_add',
			'$crstm_approve','$new_filename0','$new_filename','$new_filename1',
			'$crstm_pj_name','$crstm_pj_prv','$crstm_pj_term','$crstm_pj_dura','$crstm_pj_beg','$crstm_pj_amt',
			'$crstm_pj1_name','$crstm_pj1_prv','$crstm_pj1_term','$crstm_pj1_dura','$crstm_pj1_beg','$crstm_pj1_amt',
			'$crstm_cc_date_beg','$crstm_cc_date_end','$crstm_cc_amt','$crstm_sd_per_mm','$crstm_reviewer','$crstm_noreviewer','$crstm_scgc',   
			'$crstm_email_app1','$crstm_reviewer2','$crstm_reviewer_date','$crstm_reviewer2_date','$crstm_stamp_app1_date','$crstm_stamp_app2_date' , 
			'$crstm_email_app2','$crstm_email_app3','$user_login','$today','$crstm_step_code','$crstm_cus_active','$crstm_step_name')"; 
		
			$result_add = sqlsrv_query($conn, $sql_add);
			
			$params = array($crstm_nbr,$crstm_cc_date_beg,$crstm_cc_date_end,$crstm_cus_nbr,$crstm_cc_amt,'C3',$user_login,$today);
			$sql_tbl3 = "insert into tbl3_mstr (tbl3_nbr,tbl3_doc_date,tbl3_due_date,tbl3_cus_nbr,tbl3_amt_loc_curr,tbl3_txt_ref,tbl3_create_by,tbl3_create_date) ".
			"values (?,?,?,?,?,?,?,?)";
			$result_add = sqlsrv_query($conn,$sql_tbl3,$params, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	
			
			
			if ($result_add) {
				$r="1";
				$nb=encrypt($crstm_nbr, $key);
				$errortxt="Insert success.";
			}
			else {
				$r="0";
				$nb="";
				$errortxt="Insert fail.";
			}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	}
	
	//if ($action=="edit_new") {
	if (inlist("edit_new",$action)) {	
		$params = array($crstm_nbr);
		$sql = "SELECT crstm_curprocessor from  crstm_mstr WHERE crstm_nbr = ? ";
		$result = sqlsrv_query($conn, $sql, $params);	
		$r_row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
		if ($r_row) {
			$crstm_curprocessor_check = $r_row['crstm_curprocessor'];
			
			if (inlist($crstm_curprocessor_check,$user_login )) {
				$allow_post = true;
				}else{
				$allow_post = false;
			}
		}
		// if (!$allow_post) {
			// if ($errortxt!="") {$errortxt .= "<br>";}
			// $errorflag = true;					
			// $errortxt .= "คุณไม่มีสิทธิ์แก้ไขเอกสารเลขที่   ".$crstm_nbr." | User : ".$crstm_curprocessor_check;
		// } 
		
		//For Edit
		//ยืนยัน current processor อีกครั้ง กรณีที่มีคนที่ไม่ใช่ current processor login เข้ามาอีก page
		$crstm_nbr = html_escape($_POST['crstm_nbr']);	
		$crstm_reson_img = html_escape($_POST['crstm_reson_img']);
		$crstm_pj_img = html_escape($_POST['crstm_pj_img']);	
		$crstm_pj1_img = html_escape($_POST['crstm_pj1_img']);
		$random = (rand()%9);
		
		if( isset($_FILES['load_reson_img']['name'])) {
			$project_id = $crstm_nbr;	
			$new_filename0 = $crstm_reson_img;
			// Check if file is selected
			if(isset($_FILES['load_reson_img']['name']) && $_FILES['load_reson_img']['size']> 0) {
				// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_reson_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_reson_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_reson_img']['size']< $maxFileSize){
							$new_filename0 = "SDV-".$project_id."_001"."_".$random.".".$ext; 
							
							$serverPath = $uploadpath; 
							$pictureOriginal = findsqlval("crstm_mstr", "crstm_reson_img", "crstm_nbr", $project_id ,$conn);
							
							//Delete Existing Picture
							if($pictureOriginal != ""){ 
								$directoryFile = $serverPath.$pictureOriginal;
								if(file_exists($directoryFile)){
									unlink($directoryFile);
								}
							}
							//Upload Image
							$directoryFile =$serverPath.basename($new_filename0);
							move_uploaded_file($_FILES["load_reson_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_reson_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
		}
		
		if( isset($_FILES['load_pj_img']['name'])) {
			$project_id = $crstm_nbr;	
			$new_filename = $crstm_pj_img;
			// Check if file is selected
			if(isset($_FILES['load_pj_img']['name']) && $_FILES['load_pj_img']['size']> 0) {
				// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_pj_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_pj_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_pj_img']['size']< $maxFileSize){
							$new_filename = "SDV-".$project_id."_002"."_".$random.".".$ext; 
							
							$serverPath = $uploadpath; 
							$pictureOriginal = findsqlval("crstm_mstr", "crstm_pj_img", "crstm_nbr", $project_id ,$conn);
							
							//Delete Existing Picture
							if($pictureOriginal != ""){ 
								$directoryFile = $serverPath.$pictureOriginal;
								if(file_exists($directoryFile)){
									unlink($directoryFile);
								}
							}
							//Upload Image
							$directoryFile =$serverPath.basename($new_filename);
							move_uploaded_file($_FILES["load_pj_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_pj_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
		}
		
		if( isset($_FILES['load_pj1_img']['name'])) {
			$project_id = $crstm_nbr;
			$new_filename1 = $crstm_pj1_img;
			
			// Check if file is selected
			if(isset($_FILES['load_pj1_img']['name']) && $_FILES['load_pj1_img']['size']> 0) {
				// Get the extension
				
				$ext = strtolower(pathinfo($_FILES["load_pj1_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_pj1_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$indexPic = $pickey+1;
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
					}
					else {
						
						if($_FILES['load_pj1_img']['size']< $maxFileSize){
							
							$new_filename1 = "SDV-".$project_id."_003"."_".$random.".".$ext; 
							$serverPath = $uploadpath; 
							//Delete Existing Picture
							$pictureOriginal = findsqlval("crstm_mstr", "crstm_pj1_img", "crstm_nbr", $project_id ,$conn);
							if($pictureOriginal != ""){ 
								$directoryFile = $serverPath.$pictureOriginal;
								if(file_exists($directoryFile)){
									unlink($directoryFile);
								}
							}
							//Upload Image
							$directoryFile =$serverPath.basename($new_filename1);
							move_uploaded_file($_FILES["load_pj1_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}	
					}						
				}
				else {
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_pj1_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
					
				}
			}    
		} 
		
		// picture cr1
		$crstm_cr1_img = html_escape($_POST['crstm_cr1_img']);	
		// picture cr2
		$crstm_dbd_img = html_escape($_POST['crstm_dbd_img']);	
		$crstm_dbd1_img = html_escape($_POST['crstm_dbd1_img']);
		$crstm_cr2_img = html_escape($_POST['crstm_cr2_img']);
		// picture mgr
		$crstm_mgr_img = html_escape($_POST['crstm_mgr_img']);
		
		$cr1_id = $crstm_nbr;	
		$random = (rand()%9);
		/// Upload image from cr1
		if( isset($_FILES['load_cr1_img']['name'])) {
		$new_filename_cr1 = $crstm_cr1_img;
			// Check if file is selected
			if(isset($_FILES['load_cr1_img']['name']) && $_FILES['load_cr1_img']['size']> 0) {
				// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_cr1_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_cr1_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_cr1_img']['size']< $maxFileSize){
							$new_filename_cr1 = "CR1-".$cr1_id."_001"."_".$random.".".$ext; 
							$serverPath = $uploadpath_cr; 
							//Delete Existing Picture
							$pictureOriginal = findsqlval("crstm_mstr", "crstm_cr1_img", "crstm_nbr", $cr1_id ,$conn);

							if($pictureOriginal != ""){ 
								$directoryFile = $serverPath.$pictureOriginal;
								if(file_exists($directoryFile)){
									unlink($directoryFile);
								}
							}
							$serverPath = $uploadpath_cr; 
							$directoryFile =$serverPath.basename($new_filename_cr1);
							move_uploaded_file($_FILES["load_cr1_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_cr1_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
		}
		
		/// Upload image from cr2
		if( isset($_FILES['load_dbd_img']['name'])) {
		$new_filename_cr21 = $crstm_dbd_img;
		// Check if file is selected
			if(isset($_FILES['load_dbd_img']['name']) && $_FILES['load_dbd_img']['size']> 0) {
				// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_dbd_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_dbd_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_dbd_img']['size']< $maxFileSize){
							$new_filename_cr21 = "CR2-".$cr1_id."_001"."_".$random.".".$ext; 
							$serverPath = $uploadpath_cr; 
							//Delete Existing Picture
							$pictureOriginal = findsqlval("crstm_mstr", "crstm_dbd_img", "crstm_nbr", $cr1_id ,$conn);

							if($pictureOriginal != ""){ 
								$directoryFile = $serverPath.$pictureOriginal;
								if(file_exists($directoryFile)){
									unlink($directoryFile);
								}
							}
							$serverPath = $uploadpath_cr; 
							$directoryFile =$serverPath.basename($new_filename_cr21);
							move_uploaded_file($_FILES["load_dbd_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_dbd_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
		}
		
		if( isset($_FILES['load_dbd1_img']['name'])) {
		$new_filename_cr22 = $crstm_dbd1_img;
		// Check if file is selected
			if(isset($_FILES['load_dbd1_img']['name']) && $_FILES['load_dbd1_img']['size']> 0) {
				// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_dbd1_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_dbd1_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_dbd1_img']['size']< $maxFileSize){
							$new_filename_cr22 = "CR2-".$cr1_id."_002"."_".$random.".".$ext; 
							$serverPath = $uploadpath_cr; 
							//Delete Existing Picture
							$pictureOriginal = findsqlval("crstm_mstr", "crstm_dbd1_img", "crstm_nbr", $cr1_id ,$conn);

							if($pictureOriginal != ""){ 
								$directoryFile = $serverPath.$pictureOriginal;
								if(file_exists($directoryFile)){
									unlink($directoryFile);
								}
							}
							$serverPath = $uploadpath_cr; 
							$directoryFile =$serverPath.basename($new_filename_cr22);
							move_uploaded_file($_FILES["load_dbd1_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_dbd1_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
		}
		
		if( isset($_FILES['load_cr2_img']['name'])) {
			$new_filename_cr23 = $crstm_cr2_img;
		// Check if file is selected
			if(isset($_FILES['load_cr2_img']['name']) && $_FILES['load_cr2_img']['size']> 0) {
				// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_cr2_img"]["name"], PATHINFO_EXTENSION));
				// check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_cr2_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_cr2_img']['size']< $maxFileSize){
							$new_filename_cr23 = "CR2-".$cr1_id."_003"."_".$random.".".$ext;  
							$serverPath = $uploadpath_cr; 
							//Delete Existing Picture
							$pictureOriginal = findsqlval("crstm_mstr", "crstm_cr2_img", "crstm_nbr", $cr1_id ,$conn);

							if($pictureOriginal != ""){ 
								$directoryFile = $serverPath.$pictureOriginal;
								if(file_exists($directoryFile)){
									unlink($directoryFile);
								}
							}
							$serverPath = $uploadpath_cr; 
							$directoryFile =$serverPath.basename($new_filename_cr23);
							move_uploaded_file($_FILES["load_cr2_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_cr2_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
		}
		
		/// Upload image from mgr
		if( isset($_FILES['load_mgr_img']['name'])) {
		$new_filename_cr3 = $crstm_mgr_img;
				 //// Check if file is selected
				if(isset($_FILES['load_mgr_img']['name']) && $_FILES['load_mgr_img']['size']> 0) {
					// Get the extension
					$ext = strtolower(pathinfo($_FILES["load_mgr_img"]["name"], PATHINFO_EXTENSION));
					//// check extension and upload
						if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
						//// check size of file
						$maxFileSize = 5 * 1024 * 1024; //5MB
						
						if($_FILES['load_mgr_img']['size'] > $maxFileSize){
							if ($errortxt!="") {$errortxt .= "<br>";}
							$errorflag = true;	
							$indexPic = $pickey+1;
							$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}
						else {
						
						if($_FILES['load_mgr_img']['size']< $maxFileSize){
						
							$new_filename_cr3 = "CR3-".$cr1_id."_001"."_".$random.".".$ext; 
							
							$serverPath = $uploadpath_cr; 
							//Delete Existing Picture
							$pictureOriginal = findsqlval("crstm_mstr", "crstm_mgr_img", "crstm_nbr", $cr1_id ,$conn);

							if($pictureOriginal != ""){ 
								$directoryFile = $serverPath.$pictureOriginal;
								if(file_exists($directoryFile)){
									unlink($directoryFile);
								}
							}
							
							//Upload Image
							$serverPath = $uploadpath_cr; 
							$directoryFile =$serverPath.basename($new_filename_cr3);
							move_uploaded_file($_FILES["load_mgr_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
							
						}	
						}						
					}
					else {
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;		
						$indexPic = $pickey+1;
						$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_mgr_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
					}
				}    
		}
		
	if (!$errorflag) { 
		
		if ($user_login!="") {
			if(!inlist($crstm_whocanread,$user_login)) {
				if ($crstm_whocanread != "") { $crstm_whocanread = $crstm_whocanread .","; }
				$crstm_whocanread = $crstm_whocanread . $user_login;
			}
		}	
		// เช็คการลบรูปภาพ crstm_reson_img
	if ($allow_post) {	
		if ($del_reson=="1") {
			$new_filename0 = "SDV-".$crstm_nbr."_001"."_".$random.".".$ext; 
			$serverPath = $uploadpath; 
			$pictureOriginal = findsqlval("crstm_mstr", "crstm_reson_img", "crstm_nbr", $crstm_nbr ,$conn);
							
			//Delete Existing Picture
			if($pictureOriginal != ""){ 
				$directoryFile = $serverPath.$pictureOriginal;
					if(file_exists($directoryFile)){
						unlink($directoryFile);
					}
				}
			$new_filename0 = "";
		}
		// เช็คการลบรูปภาพ crstm_pj_img
		if ($del_pj=="1") {
			$new_filename = "SDV-".$crstm_nbr."_002"."_".$random.".".$ext; 
			$serverPath = $uploadpath; 
			$pictureOriginal = findsqlval("crstm_mstr", "crstm_pj_img", "crstm_nbr", $crstm_nbr ,$conn);
							
			//Delete Existing Picture
			if($pictureOriginal != ""){ 
				$directoryFile = $serverPath.$pictureOriginal;
					if(file_exists($directoryFile)){
						unlink($directoryFile);
					}
				}
			$new_filename = "";
		}
		// เช็คการลบรูปภาพ crstm_pj1_img
		if ($del_pj1=="1") {
			$new_filename1 = "SDV-".$crstm_nbr."_003"."_".$random.".".$ext; 
			$serverPath = $uploadpath; 
			$pictureOriginal = findsqlval("crstm_mstr", "crstm_pj1_img", "crstm_nbr", $crstm_nbr ,$conn);
			
			//Delete Existing Picture
			if($pictureOriginal != ""){ 
				$directoryFile = $serverPath.$pictureOriginal;
					if(file_exists($directoryFile)){
						unlink($directoryFile);
					}
				}
			$new_filename1 = "";
		}
	}	
		// เช็คการลบรูปภาพ crstm_cr1_img
		if ($del_cr1=="1") {
			$new_filename_cr1 = "CR1-".$crstm_nbr."_001"."_".$random.".".$ext; 
			$serverPath = $uploadpath_cr; 
			$pictureOriginal = findsqlval("crstm_mstr", "crstm_cr1_img", "crstm_nbr", $crstm_nbr ,$conn);
			
			//Delete Existing Picture
			if($pictureOriginal != ""){ 
				$directoryFile = $serverPath.$pictureOriginal;
					if(file_exists($directoryFile)){
						unlink($directoryFile);
					}
				}
			$new_filename_cr1 = "";
		}
		// เช็คการลบรูปภาพ crstm_dbd_img
		if ($del_dbd=="1") {
			$new_filename_cr21 = "CR2-".$crstm_nbr."_001"."_".$random.".".$ext; 
			$serverPath = $uploadpath_cr; 
			$pictureOriginal = findsqlval("crstm_mstr", "crstm_dbd_img", "crstm_nbr", $crstm_nbr ,$conn);
			//Delete Existing Picture

			if($pictureOriginal != ""){ 
				$directoryFile = $serverPath.$pictureOriginal;
					if(file_exists($directoryFile)){
						unlink($directoryFile);
					}
				}
			$new_filename_cr21 = "";
		}	
		// เช็คการลบรูปภาพ crstm_dbd1_img
		if ($del_dbd1=="1") {
			$new_filename_cr22 = "CR2-".$crstm_nbr."_002"."_".$random.".".$ext; 
			$serverPath = $uploadpath_cr; 
			$pictureOriginal = findsqlval("crstm_mstr", "crstm_dbd1_img", "crstm_nbr", $crstm_nbr ,$conn);
			//Delete Existing Picture

			if($pictureOriginal != ""){ 
				$directoryFile = $serverPath.$pictureOriginal;
					if(file_exists($directoryFile)){
						unlink($directoryFile);
					}
				}
			$new_filename_cr22 = "";
		}	
		// เช็คการลบรูปภาพ crstm_cr2_img
		if ($del_cr2=="1") {
			$new_filename_cr23 = "CR2-".$crstm_nbr."_003"."_".$random.".".$ext;  
			$serverPath = $uploadpath_cr; 
			$pictureOriginal = findsqlval("crstm_mstr", "crstm_cr2_img", "crstm_nbr", $crstm_nbr ,$conn);
			//Delete Existing Picture

			if($pictureOriginal != ""){ 
				$directoryFile = $serverPath.$pictureOriginal;
					if(file_exists($directoryFile)){
						unlink($directoryFile);
					}
				}
			$new_filename_cr23 = "";
		}	
		$params_edit = array($crstm_nbr);
		if (inlist($user_role,"SALE_VIEW")) { 	
			$sql_edit = "UPDATE crstm_mstr SET ".
			" crstm_nbr = '$crstm_nbr' ,".
			" crstm_date ='$curr_date' ,".
			" crstm_user = '$user_code' ,".
			" crstm_tel = '$crstm_tel' ,".
			" crstm_cus_nbr = '$crstm_cus_nbr' ,".
			" crstm_cus_name = '$crstm_cus_name' ,".
			" crstm_tax_nbr3 = '$crstm_tax_nbr3' ,".
			" crstm_address = '$crstm_address' ,".
			" crstm_district  = '$crstm_district' ,".
			" crstm_amphur = '$crstm_amphur' ,".
			" crstm_province = '$crstm_province' ,".
			" crstm_zip = '$crstm_zip' ,".
			" crstm_country = '$crstm_country' ,".
			" crstm_chk_rdo2 = '$crstm_chk_rdo2' ,".
			" crstm_approve = '$crstm_approve' ,".
			" crstm_sd_reson = '$crstm_sd_reson' ,".
			" crstm_reson_img  = '$new_filename0' ,".
			
			" crstm_pj_name  = '$crstm_pj_name' ,".
			" crstm_pj_prv = '$crstm_pj_prv' ,".
			" crstm_pj_term = '$crstm_pj_term' ,".
			" crstm_pj_amt = '$crstm_pj_amt' ,".
			" crstm_pj_dura = '$crstm_pj_dura' ,".
			" crstm_pj_beg = '$crstm_pj_beg' ,".
			" crstm_pj_img = '$new_filename' ,".
			
			" crstm_pj1_name  = '$crstm_pj1_name' ,".
			" crstm_pj1_prv = '$crstm_pj1_prv' ,".
			" crstm_pj1_term = '$crstm_pj1_term' ,".
			" crstm_pj1_amt = '$crstm_pj1_amt' ,".
			" crstm_pj1_dura = '$crstm_pj1_dura' ,".
			" crstm_pj1_beg = '$crstm_pj1_beg' ,".
			" crstm_pj1_img = '$new_filename1' ,".
			
			" crstm_term_add = '$crstm_term_add' ,".
			" crstm_cc_date_beg = '$crstm_cc_date_beg' ,".
			" crstm_cc_date_end = '$crstm_cc_date_end' ,".
			" crstm_cc_amt = '$crstm_cc_amt' ,".
			
			" crstm_rem_rearward = NULL," .
			
			" crstm_reviewer = '$crstm_reviewer' ,".
			" crstm_reviewer2 = '$crstm_reviewer2' ,".

			" crstm_noreviewer = '$crstm_noreviewer' ,".
			" crstm_scgc = '$crstm_scgc',".
			" crstm_email_app1 = '$crstm_email_app1' ,".
			" crstm_email_app2 = '$crstm_email_app2' ,".
			" crstm_email_app3 = '$crstm_email_app3' ,".
			
			" crstm_whocanread = '$crstm_whocanread' ,".
			" crstm_step_code = '$crstm_step_code' ,".
			" crstm_step_name = '$crstm_step_name' ,".
			" crstm_update_by = '$user_login' ,".
			" crstm_update_date = '$today' ".

			" WHERE crstm_nbr = ? ";
		}	
		
		if (inlist('10,11',$crstm_step_code) && inlist($user_role,'Action_View1')) {
	
			$sql_edit = " UPDATE crstm_mstr SET ".
			"crstm_cc1_reson = '$crstm_cc1_reson' ,".
			"crstm_cr1_img = '$new_filename_cr1' ,".
			"crstm_step_code = '$crstm_step_code' ,".
			"crstm_step_name = '$crstm_step_name' ,".
			"crstm_rem_rearward = NULL," .
			"crstm_whocanread = '$crstm_whocanread' ,".
			"crstm_create_by_cr1 = '$user_fullname' ,".

			"crstm_reviewer = '$crstm_reviewer' ,".
			"crstm_noreviewer = '$crstm_noreviewer' ,".
			//"crstm_scgc = '$crstm_scgc',".
			"crstm_email_app1 = '$crstm_email_app1',".
			"crstm_email_app2 = '$crstm_email_app2',".

			"crstm_create_cr1_date  = '$curr_date' ".
			" WHERE crstm_nbr = ? ";
		 }	
		//if (inlist('20,21,30',$crstm_step_code) && inlist($user_role,'Action_View2')) {
		if (inlist('20,21,30',$crstm_step_code) || inlist('Action_View1,Action_View2',$user_role)) {
			$sql_edit = "UPDATE crstm_mstr SET ".
			"crstm_cc1_reson = '$crstm_cc1_reson' ,".
			"crstm_cr1_img = '$new_filename_cr1' ,".
			
			"crstm_cc2_reson = '$crstm_cc2_reson' ,".
			"crstm_dbd_rdo = '$crstm_dbd_rdo' ,".
			"crstm_dbd_yy = '$crstm_dbd_yy' ,".
			"crstm_dbd1_yy = '$crstm_dbd1_yy' ,".
			"crstm_dbd_img = '$new_filename_cr21' ,".
			"crstm_dbd1_img = '$new_filename_cr22' ,".
			"crstm_cr2_img = '$new_filename_cr23' ,".
			"crstm_create_by_cr2 = '$user_fullname' ,".
			"crstm_create_cr2_date  = '$curr_date', ".
			"crstm_rem_rearward = NULL," .
			"crstm_step_code = '$crstm_step_code' ,".
			"crstm_step_name = '$crstm_step_name' ,".
			"crstm_whocanread = '$crstm_whocanread' ,".
			"crstm_update_by = '$user_login' ,".
			"crstm_update_date = '$today' ".
			"WHERE crstm_nbr = ? ";
		}	
	
		if (inlist('30,40,41',$crstm_step_code) && inlist($user_role,'FinCR Mgr')) {
		
			$sql_edit = "UPDATE crstm_mstr SET ".
			"crstm_cc1_reson = '$crstm_cc1_reson' ,".
			"crstm_cr1_img = '$new_filename_cr1' ,".
			
			"crstm_cc2_reson = '$crstm_cc2_reson' ,".
			"crstm_dbd_rdo = '$crstm_dbd_rdo' ,".
			"crstm_dbd_yy = '$crstm_dbd_yy' ,".
			"crstm_dbd1_yy = '$crstm_dbd1_yy' ,".
			"crstm_dbd_img = '$new_filename_cr21' ,".
			"crstm_dbd1_img = '$new_filename_cr22' ,".
			"crstm_cr2_img = '$new_filename_cr23' ,".
			
			"crstm_mgr_reson = '$crstm_mgr_reson' ,".
			"crstm_mgr_rdo = '$crstm_mgr_rdo' ,".
			"crstm_cr_mgr = '$crstm_cr_mgr' ,".
			"crstm_mgr_img = '$new_filename_cr3' ,".
			
			"crstm_create_by_mgr = '$user_fullname' ,".
			"crstm_create_mgr_date  = '$curr_date', ".
			
			"crstm_chk_rearward = '0' ," .
			"crstm_step_code = '$crstm_step_code' ,".
			"crstm_step_name = '$crstm_step_name' ,".
			"crstm_whocanread = '$crstm_whocanread' ,".
			"crstm_update_by = '$user_login' ,".
			"crstm_update_date = '$today' ".
			"WHERE crstm_nbr = ? ";
		}	
		$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if($result_edit) {
				$r="1";
				$errortxt="Edit success.";
				$nb=encrypt($crstm_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="Edit fail.";
			}
		
		///// Send email Sales ---> Cr1
		if (($crstm_step_code=="10") && ($formid == "frm_send_cr1")) {
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
				$mail_from = $user_fullname;
				$mail_from_email = $user_email;
				$mail_to = $cr_next_curprocessor_email;
				$mail_subject = "Credit 1 โปรดดำเนินการ: ใบขออนุมัติวงเงิน เลขที่ $crstm_nbr  ลูกค้า $crstm_cus_name ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน แผนกสินเชื่อ (Credit 1)<br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_nbr  ลูกค้า $crstm_cus_name <br>
				Credit 1 : โปรดดำเนินการในระบบ Credit Control ด้วยค่ะ  <br><br>
				$user_fullname เบอร์โทร  $crstm_tel และอีเมล $user_email<br><br>
				
				 ขอบคุณค่ะ<br></font>";	
				
				$mail_message .= $mail_no_reply;
				
				if ($mail_to!="") {
					$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
					
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			//}	
			///// Send email Sales ---> Cr1
			
			if($user_email!="") {
				$mail_from = $mail_from_text;
				$mail_from_email = $mail_credit_email;
				$mail_to = $user_email;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_nbr : $crstm_cus_name  ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $user_fullname <br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_nbr  ลูกค้า $crstm_cus_name ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ <br><br>
				
				ขอบคุณค่ะ<br></font>";
				$mail_message .= $mail_no_reply;
				if($mail_to!="") {
					$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			}
		}	
		///// Send email Sales ---> Cr1
		
		///// Send email Cr1 ---> Cr2
		if (($crstm_step_code=="20") && ($formid == "frm_send_cr1")) {
				//เก็บประวัติการดำเนินการ
				$cr_ap_f_step = $crstm_step_code;  
				$cr_ap_t_step = "30"; // Action_View1 to submit
				$cr_ap_text = "Submit for Cr1";
				$cr_ap_remark = "";		
					
				$cr_ap_id = getnewappnewid($crstm_nbr,$conn);
					
				$sql = "INSERT INTO  crctrl_approval (" . 
				" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
				" VALUES('$cr_ap_id','$crstm_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$user_login','$today')";				
	
				$result = sqlsrv_query($conn, $sql);
	
				//ดึงรายชื่อ email ของคนที่มี role Action_View2 ทุกคน
				$params = array('Action_View2');	
				$cr_next_curprocessor_email = "";
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
				// ค้นหารายชื่อ Credit 1	
				$params = array($user_login);		
				$sql_emp = "SELECT * from emp_mstr where emp_user_id = ?";
				$result_emp = sqlsrv_query($conn, $sql_emp,$params);	
				$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
					if ($r_emp) {
						$user_fullname = html_clear(trim($r_emp["emp_prefix_th_name"])) . html_clear(trim($r_emp["emp_th_firstname"])) . " " . html_clear(trim($r_emp["emp_th_lastname"]));
						$user_email = html_clear(strtolower($r_emp['emp_email_bus']));
						$user_inform_last_action = html_clear($r_emp['emp_inform_last_action']);
							if ($r_emp['emp_inform_last_action'] == "1") {$user_inform_last_action = true;}
								else {$user_inform_last_action = false;} 
						}
				
				if (isservonline($smtp)) { $can_sendmail=true;}
				else {
						$can_sendmail=false;
						$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
				}				
					$mail_from = $user_fullname;
					$mail_from_email = $user_email;
					$mail_to = $cr_next_curprocessor_email;
					$mail_subject ="Credit 2 โปรดดำเนินการ: ใบขออนุมัติวงเงิน  $crstm_nbr ลูกค้า  $crstm_cus_name ";
					$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน แผนกสินเชื่อ (Credit 2)<br><br>
					ใบขออนุมัติวงเงิน หมายเลข $crstm_nbr  ลูกค้า $crstm_cus_name <br>
					Credit 2 โปรดดำเนินการในระบบ  Credit Control ด้วยค่ะ  <br><br>
					$user_fullname  เบอร์โทรศัพท์ $crstm_tel และอีเมล $user_email<br><br>
					
					 ขอบคุณค่ะ<br></font>";	
					
					$mail_message .= $mail_no_reply;
			
				if ($mail_to!="") {
					$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
						}
				
					} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
				//}
			}	
		///// Send email Cr1 ---> Cr2
		
		///// Send email Cr2 ---> Manager
		if (($crstm_step_code=="30") && ($formid == "frm_send_cr1")) {
			//เก็บประวัติการดำเนินการ
			$cr_ap_f_step = $crstm_step_code;  
			$cr_ap_t_step = "40"; // Action_View2 to submit
			$cr_ap_text = "Submit for Cr2";
			$cr_ap_remark = "";		
				
			$cr_ap_id = getnewappnewid($crstm_nbr,$conn);
				
			$sql = "INSERT INTO  crctrl_approval (" . 
			" cr_ap_id,cr_ap_crctrl_nbr,cr_ap_f_step_code,cr_ap_t_step_code,cr_ap_text,cr_ap_remark,cr_ap_active,cr_ap_create_by,cr_ap_create_date)" .		
			" VALUES('$cr_ap_id','$crstm_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','1','$user_login','$today')";				

			$result = sqlsrv_query($conn, $sql);

			//ดึงรายชื่อ email ของคนที่มี role FinCR Mgr ทุกคน
			$cr_next_curprocessor_email = "";
			$params = array('FinCR Mgr');	
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
					$user_fullname = html_clear(trim($r_emp["emp_prefix_th_name"])) . html_clear(trim($r_emp["emp_th_firstname"])) . " " . html_clear(trim($r_emp["emp_th_lastname"]));
					$user_email = html_clear(strtolower($r_emp['emp_email_bus']));
					$user_inform_last_action = html_clear($r_emp['emp_inform_last_action']);
						if ($r_emp['emp_inform_last_action'] == "1") {$user_inform_last_action = true;}
							else {$user_inform_last_action = false;} 
				}
				
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
				$mail_from = $user_fullname;
				$mail_from_email = $user_email;
				$mail_to = $cr_next_curprocessor_email;
				$mail_subject = "FinCR Manager โปรดดำเนินการ: ใบขออนุมัติวงเงิน เลขที่   $crstm_nbr  ลูกค้า  $crstm_cus_name";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน Finance & Credit Manager<br><br>
				ใบขออนุมัติวงเงิน หมายเลข $crstm_nbr  ลูกค้า $crstm_cus_name<br>
				Finance & Credit Manager : โปรดดำเนินการในระบบ Credit Control ด้วยค่ะ  <br><br>
				$user_fullname  เบอร์โทรศัพท์ $crstm_tel และอีเมล $user_email<br><br>
				
				 ขอบคุณค่ะ<br></font>";	
				
				$mail_message .= $mail_no_reply;
		
			if ($mail_to!="") {
				$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
			
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			//}
		}	
		///// Send email Cr2 ---> Manager
		
		///// Send email Manager ---> Sales
		if (($crstm_step_code=="40" || $crstm_step_code=="41")  && ($formid == "frm_send_cr1")) {
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

			//ดึงรายชื่อ email ของคนที่มี role FinCR Mgr ทุกคน
			if($crstm_mgr_rdo=="1") {
				$txt_approve = "เห็นสมควรอนุมัติ";
				$txt_default = "<span style='color:Blue'>โปรดเข้าระบบ Credit Control เพื่อดำเนินการส่ง E-mail เสนอขออนุมัติผ่านระบบต่อไป  <br> $crstm_detail_mail </span>";
			}else {
				$txt_approve = "ไม่เห็นสมควรอนุมัติ";
				$txt_default = "";
			}
			$cr_next_curprocessor_email = "";
			$params = array($crstm_nbr);
			$sql_aucadmin = "SELECT crstm_curprocessor From crstm_mstr WHERE (crstm_nbr = ?)";
			$result_aucadmin = sqlsrv_query( $conn, $sql_aucadmin,$params);											
				while($r_aucadmin = sqlsrv_fetch_array($result_aucadmin, SQLSRV_FETCH_ASSOC)) {
					$aucadmin_user_login = $r_aucadmin['crstm_curprocessor'];
					$aucadmin_user_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$aucadmin_user_login,$conn);
					$cr_next_curprocessor_email = $aucadmin_user_email;
				}
				
				$cr_next_curprocessor_email= $cr_next_curprocessor_email.","."credit@scg.com,"."nuchanav@scg.com";				

				$params = array($aucadmin_user_login);
				$sql_emp = "SELECT * from emp_mstr where emp_user_id = ?";
				$result_emp = sqlsrv_query($conn, $sql_emp,$params);	
				$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
					if ($r_emp) {
						$user_fullname = html_clear(trim($r_emp["emp_prefix_th_name"])) . html_clear(trim($r_emp["emp_th_firstname"])) . " " . html_clear(trim($r_emp["emp_th_lastname"]));
						$user_email = html_clear(strtolower($rec_emp['emp_email_bus']));
						$user_inform_last_action = html_clear($r_emp['emp_inform_last_action']);
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
				$mail_subject = " แจ้งผลการพิจารณา ใบขออนุมัติวงเงินเลขที่  $crstm_nbr ลูกค้า   $crstm_cus_name ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน $user_fullname<br><br>
				ใบขออนุมัติวงเงิน เลขที่ $crstm_nbr ลูกค้า $crstm_cus_name ได้ผ่านการพิจารณาจากสินเชื่อแล้ว <br>
				สถานะ :  $txt_approve<br>
				$txt_default<br><br>
				
				ขอบคุณค่ะ<br></font>";	
				
				$mail_message .= $mail_no_reply;
		
			if ($mail_to!="") {
				$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
				if (!$sendstatus) {
					$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
			
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			//}
		}	
		///// Send email Manager ---> Sales
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	
	}
	
	if (inlist("submit_new",$action)) {	
		$params = array($crstm_nbr);
		$sql = "SELECT crstm_curprocessor from  crstm_mstr WHERE crstm_nbr = ? ";
		$result = sqlsrv_query($conn, $sql, $params);	
		$r_row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
		if ($r_row) {
			$crstm_curprocessor_check = $r_row['crstm_curprocessor'];
			
			if (inlist($crstm_curprocessor_check,$user_login )) {
				$allow_post = true;
				}else{
				$allow_post = false;
			}
		}
		// if (!$allow_post) {
			// if ($errortxt!="") {$errortxt .= "<br>";}
			// $errorflag = true;					
			// $errortxt .= "คุณไม่มีสิทธิ์แก้ไขเอกสารเลขที่   ".$crstm_nbr." | User : ".$crstm_curprocessor_check;
		// }
		
		if (!$errorflag) { 
		
		$params_edit = array($crstm_nbr);
		if (inlist($user_role,"SALE_VIEW")) { 		
			$sql_edit = "UPDATE crstm_mstr SET ".
			" crstm_step_code = '$crstm_step_code' ,".
			" crstm_update_by = '$user_login' ,".
			" crstm_update_date = '$today' ".
			" WHERE crstm_nbr = ? ";
		}	
		
		if (inlist('10,11,20,21,30,40,41',$crstm_step_code) || inlist('Action_View1,Action_View2,FinCR Mgr',$user_role)) {
			
			$sql_edit = " UPDATE crstm_mstr SET ".
			"crstm_cc1_reson = '$crstm_cc1_reson_rem1' ,".
			"crstm_cc2_reson = '$crstm_cc1_reson_rem2' ,".
			"crstm_step_code = '$crstm_step_code' ,".
			"crstm_rem_rearward = NULL," .
			"crstm_update_by = '$user_login' ,".
			"crstm_update_date = '$today' ".
			" WHERE crstm_nbr = ? ";
		}	
		
		$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if($result_edit) {
				$r="1";
				$errortxt="Edit success.";
				$nb=encrypt($crstm_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="Edit fail.";
			}
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	
	}
	
?>