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

$params = array();

set_time_limit(0);
date_default_timezone_set('Asia/Bangkok');
$curr_date = ymd(date("d/m/Y"));
$today = date("Y-m-d H:i:s");
$curYear = date('Y'); 
$curMonth = date('m'); 
$errortxt = "";
$allow_post = false;
$crstm_reviewer_date = ""; $crstm_reviewer2_date = ""; $crstm_stamp_app1_date = ""; $crstm_stamp_app2_date = "";
$crstm_stamp_app3_date = ""; $crstm_stamp_app1 = ""; $crstm_stamp_app2 = "";$crstm_stamp_app3 = "";

$step_code = html_escape($_GET['step_code']);
$crstm_step_code = decrypt($step_code, $key);
//$crstm_step_code = mssql_escape($_REQUEST['step_code']);
$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_step_code ,$conn);


$cus_nbr = html_escape($_GET['cus_nbr']);
$cus_nbr = decrypt($cus_nbr, $key);

$pg = html_escape($_POST['pg']);
$action = html_escape($_POST['action']);

//--1. Parameter From crctrladd.php
//-- Section I : ผู้ขอเสนออนุมัติ
$crstm_nbr = mssql_escape($_POST['crstm_nbr']);	
$crstm_date = mssql_escape(ymd($_POST['curr_date']));
$crstm_tel = mssql_escape($_POST['phone_mask']);
$crstm_cus_nbr = mssql_escape($_POST['cr_cust_code']);
$crstm_cus_nbr = html_escape(decrypt($crstm_cus_nbr, $key));

// Check bok 
$crstm_chk_rdo1 = mssql_escape($_POST['cus_conf']);
$crstm_chk_rdo2 = mssql_escape($_POST['chk_rdo']);

$crstm_sd_per_mm = mssql_escape(str_replace(",","",$_POST['crstm_sd_per_mm']));
$crstm_approve = mssql_escape($_POST['crstm_approve']);
$crstm_sd_reson = mssql_escape($_POST['crstm_sd_reson']);

$crstm_chk_term =  mssql_escape($_POST['rdo_conf1']);
$crstm_term_add = mssql_escape($_POST['term_desc_add']);
$crstm_ch_term = mssql_escape($_POST['term_desc']);

// Section I Project Information
$crstm_pj_name = mssql_escape($_POST['crstm_pj_name']);
$crstm_pj_prv = mssql_escape($_POST['crstm_pj_prv']);
$crstm_pj_term = mssql_escape($_POST['crstm_pj_term']);
$crstm_pj_amt = mssql_escape(str_replace(",","",$_POST['crstm_pj_amt']));
$crstm_pj_dura = mssql_escape($_POST['crstm_pj_dura']);
$crstm_pj_beg = mssql_escape(ymd($_POST['crstm_pj_beg']));

$crstm_pj1_name = mssql_escape($_POST['crstm_pj1_name']);
$crstm_pj1_prv = mssql_escape($_POST['crstm_pj1_prv']);
$crstm_pj1_term = mssql_escape($_POST['crstm_pj1_term']);
$crstm_pj1_amt = mssql_escape(str_replace(",","",$_POST['crstm_pj1_amt']));
$crstm_pj1_dura = mssql_escape($_POST['crstm_pj1_dura']);
$crstm_pj1_beg = mssql_escape(ymd($_POST['crstm_pj1_beg']));
$crstm_rem_rearward = mssql_escape($_POST['crstm_rem_rearward']);

$crstm_reviewer = mssql_escape($_POST['crstm_reviewer']);
$crstm_reviewer2 = mssql_escape($_POST['crstm_reviewer2']);

$crstm_noreviewer = mssql_escape($_POST['crstm_noreviewer']);
$crstm_scgc = mssql_escape($_POST['crstm_scgc']);
$crstm_email_app1 = mssql_escape($_POST['crstm_email_app1']);
$crstm_email_app2 = mssql_escape($_POST['crstm_email_app2']);
$crstm_email_app3 = mssql_escape($_POST['crstm_email_app3']);

$del_reson = html_escape($_POST['del_reson']);
$del_pj = html_escape($_POST['del_pj']);
$del_pj1 = html_escape($_POST['del_pj1']);

	if ($crstm_chk_rdo2=="C1") {
		$crstm_cc_date_beg = html_escape(ymd($_POST['beg_date1']));
		$crstm_cc_date_end = html_escape(ymd($_POST['end_date1']));
		$crstm_cc_amt = html_escape(str_replace(",","",$_POST['cc_amt1']));
		}else{
		$crstm_cc_date_beg = "";
		$crstm_cc_date_end = "";
		$crstm_cc_amt = 0;
	}
	//if ($crstm_noreviewer != true) {$crstm_noreviewer=false;}
	if($crstm_step_code == "112") { // Sale Revise
		$crstm_status = 1;
	}else {
		$crstm_status = 0;
	}
	$stamp_date = mssql_escape($_POST['stamp_date']);
	$stamp1_date = mssql_escape($_POST['stamp1_date']);
	$e_txt_ref = $crstm_chk_rdo2;
	
	$params = array($crstm_cus_nbr);
	$query_cust_detail = "SELECT cus_mstr.cus_nbr AS Expr1, cus_mstr.cus_name1, cus_mstr.cus_name2, cus_mstr.cus_name3, cus_mstr.cus_name4, cus_mstr.cus_street, cus_mstr.cus_street2, cus_mstr.cus_street3, ".
	"cus_mstr.cus_street4, cus_mstr.cus_street5, cus_mstr.cus_district, cus_mstr.cus_city, cus_mstr.cus_zipcode, cus_mstr.cus_country, cus_mstr.cus_tax_nbr3, country_mstr.country_desc ".
	"FROM cus_mstr INNER JOIN country_mstr ON cus_mstr.cus_country = country_mstr.country_code where cus_nbr=?";
	
	$result_cust_detail = sqlsrv_query($conn, $query_cust_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_cust_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$crstm_cus_name = $rec_cus['cus_name1'];
		$crstm_address = $rec_cus['cus_street'];
		$crstm_district = $rec_cus['cus_street2'];
		$cus_street3 = $rec_cus['cus_street3'];
		$cus_street4 = $rec_cus['cus_street4'];
		$cus_street5 = $rec_cus['cus_street5'];
		$crstm_amphur = $rec_cus['cus_district'];
		$crstm_province = $rec_cus['cus_city'];
		$crstm_country = $rec_cus['country_desc'];
		$crstm_zip = $rec_cus['cus_zipcode'];
		$crstm_address = $crstm_address ." " . $crstm_district ." ". $cus_street3 ." ". $cus_street4 ." ". $cus_street5 ." ". $crstm_amphur ." ". $crstm_province ." ". $crstm_zip;
		$crstm_tax_nbr3 = $rec_cus['cus_tax_nbr3'];
		//$cus_terms_paymnt = $rec_cus['term_desc'];
		//$cus_acc_group = $rec_cus['cus_acc_group'];
	}
	
	//$crstm_pj1_img = mssql_escape($_POST['crstm_pj1_img']);
	//--2. INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	if (inlist("crctrladd,crctrledit",$action)) {	
		if ($crstm_cus_nbr=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ รหัสลูกค้า ]";
		}
		if ($crstm_tel=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ เบอร์โทรศัพท์  ]";
		}
		if ($crstm_sd_per_mm=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ประมาณการณ์ขายเฉลี่ยต่อเดือน ]";
		}
		if ($crstm_chk_rdo1=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [--- วงเงินลูกค้าใหม่ / วงเงินลูกค้าเก่า --- ]";
		}
		if ($crstm_chk_rdo1=="1") {
			if ($crstm_chk_rdo2=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [--- ปรับเพิ่มวงเงิน / ปรับลดวงเงิน / ต่ออายุวงเงิน --- ]";
			}
		}
		
		if ($crstm_chk_term=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [--- เลือกเงื่อนไขการชำระเงิน --- ]";
		}
		// if ($crstm_chk_term=="old") {
		// if ($crstm_term_add=="") {
		// if ($errortxt!="") {$errortxt .= "<br>";}
		// $errorflag = true;					
		// $errortxt .= "กรุณาระบุ - [--- เลือกเงื่อนไขการชำระเงินเพิ่ม --- ]";
		// }
		// }
		if ($crstm_chk_term=="change") {
			if ($crstm_ch_term=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [--- เลือกเงื่อนไขการชำระเงินใหม่ --- ]";
			}
		}
		if ($crstm_sd_reson=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [--- ความเห็น / เหตุผลที่เสนอขอวงเงิน --- ]";
		}
		if ($crstm_chk_rdo2=="C1") {
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
		}	
		if ($crstm_pj_amt=="") {
			$crstm_pj_amt = 0;
		}
		if ($crstm_pj1_amt=="") {
			$crstm_pj1_amt = 0;
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
	   if($crstm_noreviewer == false){
			if (!filter_var($crstm_reviewer, FILTER_VALIDATE_EMAIL)) {
				$errorflag = true;
				$errortxt .= "รูปแบบอีเมลผิด - [someone@xx.com]";
			}
	   }
		 if ($crstm_reviewer === $crstm_email_app1) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "ผู้พิจารณา - ผู้อนุมัติ คนเดียวกัน กรุณาเลือกใหม่";
		 }
	}
	if ($action == "crctrladd") {
		$crstm_whocanread = "ADMIN";
		//ADD ผู้สร้างเป็น ADMIN แก้ไขโปรเจคได้
		if ($user_login!="") {
			if(!inlist($crstm_whocanread,$user_login)) {
				if ($crstm_whocanread != "") { $crstm_whocanread = $crstm_whocanread .","; }
				$crstm_whocanread = $crstm_whocanread . $user_login;
			}
		}
		
		$crstm_nbr = getcrstmnbr("CR-",$conn);
		$crstm_cus_active = "1";
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
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_pj_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
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
			$sql_add = " INSERT INTO crstm_mstr (crstm_nbr,crstm_date,crstm_user,crstm_tel,crstm_cus_nbr,crstm_cus_name,".
			"crstm_tax_nbr3,crstm_address,crstm_district,crstm_amphur,crstm_province,crstm_zip,crstm_country, ".
			" crstm_chk_rdo1,crstm_chk_rdo2,crstm_approve,crstm_sd_reson,crstm_whocanread,crstm_curprocessor, ". 
			" crstm_pj_name,crstm_pj_prv,crstm_pj_term,crstm_pj_dura,crstm_pj_beg,crstm_pj_amt, ".
			" crstm_pj1_name,crstm_pj1_prv,crstm_pj1_term,crstm_pj1_dura,crstm_pj1_beg,crstm_pj1_amt, ".
			" crstm_chk_term,crstm_sd_per_mm,crstm_pj_img,crstm_pj1_img,crstm_cus_active, ".
			" crstm_cc_date_beg,crstm_cc_date_end,crstm_cc_amt,crstm_reson_img,crstm_reviewer,crstm_noreviewer, ".
			" crstm_term_add,crstm_ch_term,crstm_create_by,crstm_create_date,crstm_step_code,crstm_step_name, ".
			" crstm_reviewer_date,crstm_reviewer2_date,crstm_stamp_app1_date,crstm_stamp_app2_date, ".
			" crstm_stamp_app1,crstm_stamp_app2,crstm_scgc,crstm_reviewer2, ".
			" crstm_email_app1,crstm_email_app2,crstm_email_app3,crstm_status)".
			
			" VALUES ('$crstm_nbr','$curr_date','$user_code','$crstm_tel','$crstm_cus_nbr','$crstm_cus_name',
			'$crstm_tax_nbr3','$crstm_address','$crstm_district','$crstm_amphur','$crstm_province','$crstm_zip','$crstm_country',
			'$crstm_chk_rdo1','$crstm_chk_rdo2','$crstm_approve','$crstm_sd_reson','$crstm_whocanread','$user_login',
			'$crstm_pj_name','$crstm_pj_prv','$crstm_pj_term','$crstm_pj_dura','$crstm_pj_beg','$crstm_pj_amt',
			'$crstm_pj1_name','$crstm_pj1_prv','$crstm_pj1_term','$crstm_pj1_dura','$crstm_pj1_beg','$crstm_pj1_amt',
			'$crstm_chk_term','$crstm_sd_per_mm','$new_filename','$new_filename1','$crstm_cus_active',
			'$crstm_cc_date_beg','$crstm_cc_date_end','$crstm_cc_amt','$new_filename0','$crstm_reviewer','$crstm_noreviewer', 
			'$crstm_term_add','$crstm_ch_term','$user_login','$today','$crstm_step_code','$crstm_step_name',
			'$crstm_reviewer_date','$crstm_reviewer2_date','$crstm_stamp_app1_date','$crstm_stamp_app2_date' , 
			'$crstm_stamp_app1','$crstm_stamp_app2','$crstm_scgc','$crstm_reviewer2' ,
			'$crstm_email_app1','$crstm_email_app2','$crstm_email_app3','$crstm_status')";
			
			$result_add = sqlsrv_query($conn, $sql_add);
			if ($result_add) {
				// ข้อมูลตารางที่ 1  
				//วงเงิน Clean Credit , Insurance
				$params = array($crstm_cus_nbr);
				
				$sql_cr= "SELECT crlimit_mstr.crlimit_acc, sum(crlimit_mstr.crlimit_amt_loc_curr) as crlimit_amt_loc_curr,  crlimit_mstr.crlimit_ref, crlimit_mstr.crlimit_txt_ref, acc_mstr.acc_name ".
				"FROM crlimit_mstr INNER JOIN acc_mstr ON crlimit_mstr.crlimit_txt_ref = acc_mstr.acc_code WHERE (crlimit_mstr.crlimit_acc = ? ) ".
				"GROUP BY crlimit_mstr.crlimit_acc, crlimit_mstr.crlimit_ref, crlimit_mstr.crlimit_txt_ref, acc_mstr.acc_name order by crlimit_txt_ref";
				$result = sqlsrv_query($conn, $sql_cr,$params);
				$sum_acc = 0;
				while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					$sum_acc = $row_cr['crlimit_amt_loc_curr'];
					$chk_name = $row_cr['crlimit_txt_ref'];
					$acc_name = $row_cr['acc_name'];
					$doc_date = $row_cr['crlimit_doc_date'];
					$due_date = $row_cr['crlimit_due_date'];
					$tbl1_group = '1';
					
					$params_update_his_pjm = array($crstm_nbr,$curr_date,$crstm_cus_nbr,$chk_name,$acc_name,$sum_acc,$doc_date,$doc_date,$tbl1_group,$stamp_date,$user_login,$today);
					$sql_update_his_pjm = "insert into tbl1_mstr (tbl1_nbr,tbl1_date,tbl1_cus_nbr,tbl1_txt_ref,tbl1_acc_name,tbl1_amt_loc_curr,tbl1_doc_date,tbl1_due_date,tbl1_group,tbl1_stamp_date,tbl1_create_by,tbl1_create_date) ".
					"values (?,?,?,?,?,?,?,?,?,?,?,?)";
					$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				}
				
				//วงเงิน หนี้ทั้งหมด
				$params = array($crstm_cus_nbr);
				$sql_ar ="SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, sum(ar_mstr.ar_amt_loc_curr) as ar_amt ".
				"FROM ar_mstr INNER JOIN cracc_mstr ON ar_mstr.ar_acc = cracc_mstr.cracc_customer INNER JOIN ".
				"cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr  where cracc_mstr.cracc_acc= ? group by cracc_mstr.cracc_acc,cus_name1";
				$result = sqlsrv_query($conn, $sql_ar,$params);
				
				while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					$sum_ar = $row_ar['ar_amt'];
					$chk_name = 'หนี้ทั้งหมด ' ;
					$cur_txt = 'AR';
					$tbl1_group = '2';
					
					$params_update_his_pjm = array($crstm_nbr,$curr_date,$crstm_cus_nbr,$sum_ar,$tbl1_group,$cur_txt,$stamp_date,$user_login,$today);
					$sql_update_his_pjm = "insert into tbl1_mstr (tbl1_nbr,tbl1_date,tbl1_cus_nbr,tbl1_amt_loc_curr,tbl1_group,tbl1_txt_ref,tbl1_stamp_date,tbl1_create_by,tbl1_create_date) ".
					"values (?,?,?,?,?,?,?,?,?)";
					$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				}	
				
				// Current , Due Today , Overdue
				$params = array($crstm_cus_nbr);	
				$sql_ar= "SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, ar_mstr.ar_dura_txt, sum(ar_mstr.ar_amt_loc_curr) as ar_amt ".
				"FROM ar_mstr INNER JOIN cracc_mstr ON ar_mstr.ar_acc = cracc_mstr.cracc_customer INNER JOIN ".
				"cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr where cracc_mstr.cracc_acc= ? ".
				"group by cracc_mstr.cracc_acc,ar_mstr.ar_dura_txt, cus_mstr.cus_name1 ";
				$result = sqlsrv_query($conn, $sql_ar,$params);
				
				$sum_cur = 0;
				while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					$sum_cur = $row_ar['ar_amt'];
					$ar_dura_txt = $row_ar['ar_dura_txt'];
					
					if ($ar_dura_txt == 'cur') {
						$cur_txt = 'Current  ' ;
						}else if ($ar_dura_txt == 'due')  {
						$cur_txt = 'Due Today  ' ;
						}else  if($ar_dura_txt == 'ovr')  {
						$cur_txt = 'Overdue ' ;
					}
					$tbl1_group = '3';	
					
					$params_update_his_pjm = array($crstm_nbr,$curr_date,$crstm_cus_nbr,$sum_cur,$tbl1_group,$cur_txt,$stamp_date,$user_login,$today);
					$sql_update_his_pjm = "insert into tbl1_mstr (tbl1_nbr,tbl1_date,tbl1_cus_nbr,tbl1_amt_loc_curr,tbl1_group,tbl1_txt_ref,tbl1_stamp_date,tbl1_create_by,tbl1_create_date) ".
					"values (?,?,?,?,?,?,?,?,?)";
					$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	
				}	
				// ใบสั่งซื้อระหว่างดำเนินการ	
				$params = array($crstm_cus_nbr);	
				$sql_ord ="SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, SUM(ord_mstr.ord_sales_val) AS sales_val ".
				"FROM ord_mstr INNER JOIN cracc_mstr ON ord_mstr.ord_cus_nbr = cracc_mstr.cracc_customer INNER JOIN ".
				"cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr where cracc_mstr.cracc_acc= ? ".
				"GROUP BY cracc_mstr.cracc_acc, cus_mstr.cus_name1 ";
				$result = sqlsrv_query($conn, $sql_ord,$params);
				
				$sum_ord = 0;
				while($row_ord = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					$sum_ord = $row_ord['sales_val'];
					$ord_txt = 'ใบสั่งซื้อระหว่างดำเนินการ' ;
					$cur_txt = 'ORD' ;
					$tbl1_group = '4';	
					
					$params_update_his_pjm = array($crstm_nbr,$curr_date,$crstm_cus_nbr,$sum_ord,$tbl1_group,$cur_txt,$stamp_date,$user_login,$today);
					$sql_update_his_pjm = "insert into tbl1_mstr (tbl1_nbr,tbl1_date,tbl1_cus_nbr,tbl1_amt_loc_curr,tbl1_group,tbl1_txt_ref,tbl1_stamp_date,tbl1_create_by,tbl1_create_date) ".
					"values (?,?,?,?,?,?,?,?,?)";
					$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	
				}
				// สิ้นสุดข้อมูลตารางที่ 1 	
				// ข้อมูลตารางที่ 2
				
				$params = array($crstm_cus_nbr);
				$sql_bll= "SELECT TOP 12 cus_mstr.cus_name1, bll_mstr.bll_ym, sum(bll_mstr.bll_amt_loc_curr) as amt, cracc_mstr.cracc_acc, bll_mstr.bll_stamp_date ".
				"FROM bll_mstr INNER JOIN cracc_mstr ON bll_mstr.bll_acc = cracc_mstr.cracc_customer INNER JOIN ".
				"cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr WHERE (cracc_mstr.cracc_acc = ?) ".
				"group by bll_mstr.bll_ym,cus_mstr.cus_name1,cracc_mstr.cracc_acc, bll_mstr.bll_stamp_date order by bll_ym  desc  ";
				$result_bll = sqlsrv_query($conn, $sql_bll,$params);
				
				$bll_tot = 0 ;
				$no = 0 ;
				$a = array();
				$a_max = array();
				while($row_bll = sqlsrv_fetch_array($result_bll, SQLSRV_FETCH_ASSOC))
				{
					$tot_amt = $row_bll['amt'];
					
					if($tot_amt < 0) {
						$tot_amt = ($tot_amt * -1);
						$tot_ord = "(".(number_format($tot_amt)).")";
						}else {
						$tot_ord = number_format($row_bll['amt']);
					}	
					
					$bll_ym = $row_bll['bll_ym'];
					$bll_doc_ym1 = substr($bll_ym,0,4);
					$bll_doc_ym2 = substr($bll_ym,5,2);
					$bll_yofm = $bll_doc_ym1.'-'.$bll_doc_ym2;
					$bll_tot = $bll_tot + $tot_amt ;
					$a[$bll_yofm] = $tot_ord;	
					$a_max[$bll_yofm] = $tot_amt;	
				}
				$max_a = array_keys($a)[0];
				$max_y = explode("-",$max_a)[0];
				$max_m = explode("-",$max_a)[1];
				
				if($max_m < $curMonth) {$max_m = $curMonth ; }
				
				$min_a = array_keys($a)[count($a)-1];
				$min_y = explode("-",$min_a)[0];
				$min_m = explode("-",$min_a)[1];
				if ($tot_amt>0) {
					$max_amt = max($a_max); // หาค่า max ใน array
				
				
				for ($y=$max_y; $y>=$min_y; $y--) {
					for ($m=$max_m; $m>=1;$m--) {
						$mx = substr("00{$m}", -2);
						$period = "$y-$mx";
						
						if (array_key_exists($period, $a)) {
							
							//echo $period . " " . $a[$period] . "<br>";
							$tot_a = $a[$period];
							$tot_a = str_replace(",","",$tot_a);
							
							$params_update_his_pjm = array($crstm_nbr,$period,$crstm_cus_nbr,$tot_a,$stamp1_date,$user_login,$today);
							
							$sql_update_his_pjm = "insert into tbl2_mstr (tbl2_nbr,tbl2_doc_date,tbl2_cus_nbr,tbl2_amt_loc_curr,tbl2_stamp_date,tbl2_create_by,tbl2_create_date) ".
							"values (?,?,?,?,?,?,?)";
							$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	
							
							} else {
							//echo $period . " 0" . "<br>";
							
							$tot_a = 0;
							$params_update_his_pjm = array($crstm_nbr,$period,$crstm_cus_nbr,$tot_a,$stamp1_date,$user_login,$today);
							
							$sql_update_his_pjm = "insert into tbl2_mstr (tbl2_nbr,tbl2_doc_date,tbl2_cus_nbr,tbl2_amt_loc_curr,tbl2_stamp_date,tbl2_create_by,tbl2_create_date) ".
							"values (?,?,?,?,?,?,?)";
							$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	
						}
						if ($y == $min_y && $m == $min_m) {
							break;
						}
					}
					$max_m = 12;
				}
				}
				// สิ้นสุดข้อมูลตารางที่ 2	
				
				// ข้อมูลตารางที่ 3
				$params = array($crstm_cus_nbr);
				// $sql_cc= "SELECT crlimit_acc, sum(crlimit_amt_loc_curr) as amt_loc, crlimit_doc_date,crlimit_due_date,crlimit_ref,crlimit_txt_ref, crlimit_seq FROM crlimit_mstr WHERE(crlimit_acc = ? and crlimit_ref = 'CC') ".
				// "GROUP BY crlimit_acc, crlimit_doc_date,crlimit_due_date,crlimit_txt_ref, crlimit_seq order by crlimit_doc_date,crlimit_due_date";
				
				$sql_cc= "SELECT crlimit_acc,crlimit_doc_head_txt, sum(crlimit_amt_loc_curr) as amt_loc, crlimit_doc_date,crlimit_due_date,crlimit_txt_ref,crlimit_ref, crlimit_seq FROM crlimit_mstr WHERE(crlimit_acc = ? and crlimit_txt_ref = 'CC') ".
					     "GROUP BY crlimit_acc,crlimit_doc_head_txt, crlimit_doc_date,crlimit_due_date,crlimit_txt_ref,crlimit_ref, crlimit_seq order by crlimit_doc_date,crlimit_due_date";
				$result_cc = sqlsrv_query($conn, $sql_cc,$params);
				
				while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
				{
					$sum_ord = $row_cc['amt_loc'];
					//$txt_ref = $row_cc['crlimit_txt_ref'];  // 07/08/2021
					$txt_ref = $row_cc['crlimit_ref'];  // 07/08/2021
					$doc_head_txt = $row_cc['crlimit_doc_head_txt'];  // 13/07/2022
					$doc_date = $row_cc['crlimit_doc_date'];
					$due_date = $row_cc['crlimit_due_date'];
					// $row_seq = $row_cc['crlimit_seq'];
					
					if($doc_head_txt !="") {$txt_ref = $doc_head_txt;}
					
					$params_update_his_pjm = array($crstm_nbr,$doc_date,$due_date,$crstm_cus_nbr,$sum_ord,$txt_ref,$user_login,$today);
					$sql_update_his_pjm = "insert into tbl3_mstr (tbl3_nbr,tbl3_doc_date,tbl3_due_date,tbl3_cus_nbr,tbl3_amt_loc_curr,tbl3_txt_ref,tbl3_create_by,tbl3_create_date) ".
					"values (?,?,?,?,?,?,?,?)";
					$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	
					
				}
				if ($crstm_chk_rdo2!="C3") {
				$params_update_his_pjm = array($crstm_nbr,$crstm_cc_date_beg,$crstm_cc_date_end,$crstm_cus_nbr,$crstm_cc_amt,$e_txt_ref,$user_login,$today);
					$sql_update_his_pjm = "insert into tbl3_mstr (tbl3_nbr,tbl3_doc_date,tbl3_due_date,tbl3_cus_nbr,tbl3_amt_loc_curr,tbl3_txt_ref,tbl3_create_by,tbl3_create_date) ".
					"values (?,?,?,?,?,?,?,?)";
					$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	
				}
				
				/// 01/08/2021 เพิ่มข้อมูลลง table crlimit_mstr กรณีมีการเพิ่มวงเงิน
					$crlimit_id = getnewid("crlimit_id", " crlimit_mstr", $conn);
					$crlimit_ref="CC";
					$params = array($crstm_cus_nbr,$crstm_nbr,$crlimit_ref,$crstm_cc_date_beg,$crstm_cc_date_end,$txt_cc,$crstm_cc_amt,$e_txt_ref,$crlimit_id,$crlimit_id,$user_login,$today);	
					$sql_add = " INSERT INTO  crlimit_mstr (" . 
					" crlimit_acc,crlimit_doc_nbr,crlimit_ref,crlimit_doc_date,crlimit_due_date,crlimit_txt,".
					" crlimit_amt_loc_curr,crlimit_txt_ref,crlimit_seq,crlimit_id,crlimit_create_by,crlimit_create_date)" .					
					" VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";			
					$result_update_his_pjm = sqlsrv_query($conn,$sql_add,$params, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	

				// สิ้นสุดข้อมูลตารางที่ 3	
				
				if($result_update_his_pjm) {
					$r="1";
					$errortxt="Insert success.";
					$nb=encrypt($crstm_nbr, $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="Insert fail.";
				}
				
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
	
	if ($action == "crctrledit") {	
		//For Edit
		//ยืนยัน current processor อีกครั้ง กรณีที่มีคนที่ไม่ใช่ current processor login เข้ามาอีก page
		$crstm_nbr = mssql_escape($_POST['crstm_nbr']);	
		$crstm_reson_img = mssql_escape($_POST['crstm_reson_img']);	
		$crstm_pj_img = mssql_escape($_POST['crstm_pj_img']);	
		$crstm_pj1_img = mssql_escape($_POST['crstm_pj1_img']);
		$random = (rand()%9);
		$allow_post = false;
		
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
							if($conf_del!="1") {
								$directoryFile =$serverPath.basename($new_filename0);
								move_uploaded_file($_FILES["load_reson_img"]["tmp_name"],$directoryFile);
								chmod($directoryFile,0777); 
							}
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
		if (!$allow_post) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "คุณไม่มีสิทธิ์แก้ไขเอกสารเลขที่   ".$crstm_nbr." | User : ".$crstm_curprocessor_check;
		}
		// เช็คการลบรูปภาพ crstm_reson_img
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
			$new_filename = "SDV-".$project_id."_002"."_".$random.".".$ext; 
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
			$new_filename1 = "SDV-".$project_id."_003"."_".$random.".".$ext; 
			$serverPath = $uploadpath; 
			$pictureOriginal = findsqlval("crstm_mstr", "crstm_pj1_img", "crstm_nbr", $project_id ,$conn);
			
			//Delete Existing Picture
			if($pictureOriginal != ""){ 
				$directoryFile = $serverPath.$pictureOriginal;
				if(file_exists($directoryFile)){
					unlink($directoryFile);
				}
			}
			$new_filename1 = "";
		}
		if (!$errorflag) { 
			$params_edit = array($crstm_nbr);
			$sql_edit = "UPDATE crstm_mstr SET ".
			" crstm_nbr = '$crstm_nbr' ,".
			" crstm_date ='$curr_date' ,".
			" crstm_user = '$user_code' ,".
			" crstm_tel = '$crstm_tel' ,".
			" crstm_cus_nbr = '$crstm_cus_nbr' ,".
			" crstm_chk_rdo1 = '$crstm_chk_rdo1' ,".
			" crstm_chk_rdo2 = '$crstm_chk_rdo2' ,".
			" crstm_approve = '$crstm_approve' ,".
			" crstm_sd_reson = '$crstm_sd_reson' ,".
			" crstm_reson_img = '$new_filename0' ,".
			" crstm_pj_name = '$crstm_pj_name' ,".
			" crstm_pj_prv = '$crstm_pj_prv' ,".
			" crstm_pj_term = '$crstm_pj_term' ,".
			" crstm_pj_dura = '$crstm_pj_dura' ,".
			" crstm_pj_beg = '$crstm_pj_beg' ,".
			" crstm_pj_amt = '$crstm_pj_amt' ,".
			" crstm_pj_img = '$new_filename' ,".
			
			" crstm_pj1_name = '$crstm_pj1_name' ,".
			" crstm_pj1_prv = '$crstm_pj1_prv' ,".
			" crstm_pj1_term = '$crstm_pj1_term' ,".
			" crstm_pj1_dura = '$crstm_pj1_dura' ,".
			" crstm_pj1_beg = '$crstm_pj1_beg' ,".
			" crstm_pj1_amt = '$crstm_pj1_amt' ,".
			" crstm_pj1_img = '$new_filename1' ,".
			
			" crstm_chk_term = '$crstm_chk_term' ,".
			" crstm_sd_per_mm = '$crstm_sd_per_mm' ,".
			" crstm_term_add = '$crstm_term_add' ,".
			" crstm_ch_term = '$crstm_ch_term' ,".
			" crstm_rem_rearward = '$crstm_rem_rearward' ,".
			
			" crstm_reviewer = '$crstm_reviewer' ,".
			" crstm_reviewer2 = '$crstm_reviewer2' ,".
			" crstm_noreviewer = '$crstm_noreviewer' ,".
			" crstm_scgc = '$crstm_scgc',".
			" crstm_email_app1 = '$crstm_email_app1',".
			" crstm_email_app2 = '$crstm_email_app2',".
			" crstm_email_app3 = '$crstm_email_app3',".
			" crstm_status = '$crstm_status',".
			" crstm_chk_rearward = '0' , ".   // 21/03/2022 clear field rearward
			// " crstm_cc_date_beg = '$crstm_cc_date_beg' ,".
			// " crstm_cc_date_end = '$crstm_cc_date_end' ,".
			// " crstm_cc_amt = '$crstm_cc_amt' ,".
			
			" crstm_step_code = '$crstm_step_code' ,".
			" crstm_step_name = '$crstm_step_name' ,".
			" crstm_update_by = '$user_login' ,".
			" crstm_update_date = '$today' ".
			" WHERE crstm_nbr = ? ";
			
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
			if ($crstm_step_code=="10") {
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
				$mail_from = "แผนกสินเชื่อ ";
				$mail_from_email = $mail_credit_email;
				$mail_to = $user_email;
				$mail_subject = "ใบขออนุมัติวงเงิน $crstm_nbr : $crstm_cus_name  ได้ส่งไปให้แผนกสินเชื่อพิจารณาแล้วค่ะ ";
				$mail_message = "<font style='font-family:Cordia New;font-size:19px'>เรียน คุณ$user_fullname <br><br>
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
			
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		
	}
	
	if ($action == "del_crnbr") {
		$project_id = $crstm_nbr;
		$rowCounts = 0;	
		
		$serverPath = $uploadpath; 
		//Delete Existing Picture
		
		$pictureOriginal = findsqlval("crstm_mstr", "crstm_pj_img", "crstm_nbr", $project_id,$conn);
		$directoryFile = $serverPath.$pictureOriginal;
		if($pictureOriginal != ""){ 
			if(file_exists($directoryFile)){
				unlink($directoryFile);
			}
		}
		$pictureOriginal = findsqlval("crstm_mstr", "crstm_pj1_img", "crstm_nbr", $project_id,$conn);
		$directoryFile = $serverPath.$pictureOriginal;
		if($pictureOriginal != ""){ 
			if(file_exists($directoryFile)){
				unlink($directoryFile);
			}
		}
		
		$params_check_del = array($crstm_nbr);
		$sql_del = "delete from crstm_mstr WHERE crstm_nbr = ?";
		$result_del = sqlsrv_query($conn,$sql_del,$params_check_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		$params_del = array($crstm_nbr);
		
		$sql_del = "delete from tbl1_mstr WHERE tbl1_nbr = ?"; 
		$result_del = sqlsrv_query($conn, $sql_del,$params_del);
		
		$params_del = array($crstm_nbr);
		$sql_del = "delete from tbl2_mstr WHERE tbl2_nbr = ?"; 
		$result_del = sqlsrv_query($conn, $sql_del,$params_del);
		
		$params_del = array($crstm_nbr);
		$sql_del = "delete from tbl3_mstr WHERE tbl3_nbr = ?"; 
		$result_del = sqlsrv_query($conn, $sql_del,$params_del);
		
		$params_del = array($crstm_nbr);
		$sql_del = "delete from crlimit_mstr  WHERE crlimit_doc_nbr = ?";
		$result_del = sqlsrv_query($conn, $sql_del,$params_del);
		
		if ($result_del) {
			$r="1";
			$errortxt="Delete success.";
			$nb=encrypt($crstm_nbr, $key);
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Delete fail.";
		}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	
	/// form crctrledit.php ตารางอายุวงเงิน
	if ($action == "editdel_cc") {
		if (!$errorflag) {
			$params = array($crstm_nbr);	
			
			$sql_edit = "UPDATE  crstm_mstr SET " .
			" crstm_cc_date_beg = NULL," .
			" crstm_cc_date_end  = NULL,".		
			" crstm_cc_amt = 0,".		
			
			" crstm_update_by = '$user_login'," .
			" crstm_update_date = '$today'" .
			" WHERE crstm_nbr = ?";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			
			if ($result_edit) {
				$r="1";
				$nb=encrypt($crstm_nbr, $key);
				
				$errortxt="Update success.";
			}
			else {
				$r="0";
				$nb="";
				$errortxt="Update fail.";
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