<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key,$user_login)) {
		echo "System detect CSRF attack8!!";
		exit;
	}
}
else {
	echo "Allow for POST Only";
	exit;
}
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

$pg = mssql_escape($_POST['pg']);
$action = mssql_escape($_POST['action']);

$step_code = mssql_escape($_GET['step_code']);
$crstm_step_code = mssql_escape(decrypt($step_code, $key));

$formid = mssql_escape($_GET['formid']);

//--1. Parameter From approve.php
$crstm_nbr = mssql_escape($_POST['crstm_nbr']);
$crstm_cus_name = mssql_escape($_POST['crstm_cus_name']);
$crstm_cus_nbr = mssql_escape($_POST['cr_cust_code']);

$crstm_pre_yy = mssql_escape($_POST['crstm_pre_yy']);	
$crstm_otd_pct = mssql_escape($_POST['crstm_otd_pct']);
$crstm_ovr_due = mssql_escape($_POST['crstm_ovr_due']);
$crstm_etc = mssql_escape($_POST['crstm_etc']);

$crstm_cur_yy = mssql_escape($_POST['crstm_cur_yy']);	
$crstm_otd1_pct = mssql_escape($_POST['crstm_otd1_pct']);
$crstm_ovr1_due = mssql_escape($_POST['crstm_ovr1_due']);
$crstm_etc1 = mssql_escape($_POST['crstm_etc1']);

$crstm_ins = mssql_escape($_POST['crstm_ins']);
$crstm_cc1_reson = mssql_escape($_POST['crstm_cc1_reson']);

$crstm_dbd_rdo = mssql_escape($_POST['web_conf']);
$crstm_dbd_yy = mssql_escape($_POST['crstm_dbd_yy']);
$crstm_dbd1_yy = mssql_escape($_POST['crstm_dbd1_yy']);
$crstm_cc2_reson = mssql_escape($_POST['crstm_cc2_reson']);

$crstm_mgr_reson = mssql_escape($_POST['crstm_mgr_reson']);
$crstm_mgr_rdo = mssql_escape($_POST['mgr_conf']);
$crstm_cr_mgr = mssql_escape(str_replace(",","",$_POST['crstm_cr_mgr']));

$crstm_rem_rearward = mssql_escape($_POST['crstm_rem_rearward']);
$crstm_whocanread = mssql_escape($_POST['crstm_whocanread']);
$crstm_detail_mail = mssql_escape($_POST['crstm_detail_mail']);

$crstm_reviewer = mssql_escape($_POST['crstm_reviewer']);
$crstm_noreviewer = mssql_escape($_POST['isreviewer']);
$crstm_scgc = mssql_escape($_POST['crstm_scgc']);
$crstm_email_app1 = mssql_escape($_POST['crstm_email_app1']);
$crstm_email_app2 = mssql_escape($_POST['crstm_email_app2']);

$crstm_update_by = $user_login;
$crstm_update_date = $today;
	
$del_cr1 = mssql_escape($_POST['del_cr1']);
$del_dbd = mssql_escape($_POST['del_dbd']);
$del_dbd1 = mssql_escape($_POST['del_dbd1']);
$del_cr2 = mssql_escape($_POST['del_cr2']);

if ($crstm_mgr_rdo == "1") {
	$crstm_step_code = $crstm_step_code;	
//}else if ($crstm_mgr_rdo == "2" && $crstm_step_code=="40"){
}else if ($crstm_mgr_rdo == "2"){
	$crstm_step_code = "41";  // status non approve
}
$crstm_step_name = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_step_code ,$conn);
$crstm_tel = findsqlval("crstm_mstr", "crstm_tel", "crstm_nbr", $crstm_nbr ,$conn);

$errorflag = false;
$errortxt = "";
if (inlist("crctrlapp_edit",$action)) {	
	// Section I VALIDATION
	if ($crstm_pre_yy=="") {
		if ($errortxt!="") {$errortxt .= "<br>";}
		$errorflag = true;					
		$errortxt .= "กรุณาระบุ - [ ปีก่อน ]";
	}
	if ($crstm_cur_yy=="") {
		if ($errortxt!="") {$errortxt .= "<br>";}
		$errorflag = true;					
		$errortxt .= "กรุณาระบุ - [ ปีล่าสุด ]";
	}
	if ($crstm_ins=="") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [--- Insurance --- ]";
	}
	if ((($crstm_cc1_reson=="") && inlist($user_role,"Action_View1")) && inlist('10',$crstm_step_code)) {

			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;
			$errortxt .= "กรุณาระบุ - [--- ความเห็นสินเชื่อ #1 --]";
		}
	if ($formid != "frm_send") {	
		if ((($crstm_cc2_reson=="") && inlist($user_role,"Action_View2")) && inlist('20',$crstm_step_code)) {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [--- ความเห็นสินเชื่อ #2  --- ]";
		}
	}
	if ((($crstm_mgr_reson=="") && inlist($user_role,"FinCR Mgr")) && inlist('30,40,41',$crstm_step_code)) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [--- ความเห็น Manager  --- ]";
	}
	if ((($crstm_cr_mgr=="") && inlist($user_role,"FinCR Mgr")) && inlist('30,40,41',$crstm_step_code)) {
			if ($errortxt !="") {$errortxt .= "<br>";}
			$errorflag = true;
			$errortxt .= "กรุณาระบุ -[--- ยอดเงินอนุมัติ ---]";
	}
	if ((($crstm_mgr_rdo=="") && inlist($user_role,"FinCR Mgr")) && inlist('30,40,41',$crstm_step_code)) {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;
			$errortxt .= "กรุณาระบุ - [--- เห็นควรอนุมัติวงเงิน / ไม่เห็นควรอนุมัติ --]";
	}	
}
if ($action == "crctrlapp_edit") {
	$crstm_whocanread = $crstm_whocanread;
	// picture cr1
	$crstm_cr1_img = mssql_escape($_POST['crstm_cr1_img']);	
	// picture cr2
	$crstm_dbd_img = mssql_escape($_POST['crstm_dbd_img']);	
	$crstm_dbd1_img = mssql_escape($_POST['crstm_dbd1_img']);
	$crstm_cr2_img = mssql_escape($_POST['crstm_cr2_img']);
	// picture mgr
	$crstm_mgr_img = mssql_escape($_POST['crstm_mgr_img']);
	
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
		$params = array($crstm_nbr);
		if (inlist('0,10,20',$crstm_step_code) && inlist($user_role,'Action_View1')) {
		//if (inlist('10,11',$crstm_step_code) && inlist('Action_View1',$user_role)) {
		
			$sql_update = " UPDATE crstm_mstr SET ".
			"crstm_pre_yy  = '$crstm_pre_yy' ,".
			"crstm_otd_pct = '$crstm_otd_pct' ,".
			"crstm_ovr_due = '$crstm_ovr_due' ,".
			"crstm_etc = '$crstm_etc' ,".
			"crstm_cur_yy = '$crstm_cur_yy' ,".
			"crstm_otd1_pct = '$crstm_otd1_pct' ,".
			"crstm_ovr1_due = '$crstm_ovr1_due' ,".
			"crstm_etc1 = '$crstm_etc1' ,".
			"crstm_ins = '$crstm_ins' ,".
			"crstm_cc1_reson = '$crstm_cc1_reson' ,".
			"crstm_cr1_img = '$new_filename_cr1' ,".
			"crstm_step_code = '$crstm_step_code' ,".
			"crstm_step_name = '$crstm_step_name' ,".
			"crstm_rem_rearward = NULL," .
			"crstm_whocanread = '$crstm_whocanread' ,".
			//"crstm_curprocessor = '$user_login' ,".
			"crstm_detail_mail = '$crstm_detail_mail' ,".
			"crstm_reviewer = '$crstm_reviewer' ,".
			"crstm_noreviewer = '$crstm_noreviewer' ,".
			"crstm_email_app1 = '$crstm_email_app1',".
			"crstm_email_app2 = '$crstm_email_app2',".
			
			"crstm_create_by_cr1 = '$user_fullname' ,".
			"crstm_create_cr1_date  = '$curr_date' ,".
			"crstm_update_by = '$crstm_update_by' ,".
			"crstm_update_date = '$crstm_update_date' ".
			" WHERE crstm_nbr = ? ";
		}	
		//if (inlist('20,21,30',$crstm_step_code) || inlist('Action_View1,Action_View2',$user_role)) {
		if (inlist('20,21,30',$crstm_step_code) && inlist($user_role,'Action_View2')) {
			$sql_update = " UPDATE crstm_mstr SET ".
			/// cr2
			"crstm_dbd_rdo = '$crstm_dbd_rdo' ,".
			"crstm_dbd_yy = '$crstm_dbd_yy' ,".
			"crstm_dbd1_yy = '$crstm_dbd1_yy' ,".
			"crstm_cc2_reson = '$crstm_cc2_reson' ,".
			"crstm_dbd_img = '$new_filename_cr21' ,".
			"crstm_dbd1_img = '$new_filename_cr22' ,".
			"crstm_cr2_img = '$new_filename_cr23' ,".
			"crstm_step_code = '$crstm_step_code' ,".
			"crstm_step_name = '$crstm_step_name' ,".
			"crstm_rem_rearward = NULL," .
			
			//"crstm_curprocessor = '$user_login' ,".
			"crstm_whocanread = '$crstm_whocanread' ,".
			"crstm_create_by_cr2 = '$user_fullname' ,".
			"crstm_create_cr2_date  = '$curr_date', ".
			"crstm_update_by = '$crstm_update_by' ,".
			"crstm_update_date = '$crstm_update_date' ".
			" WHERE crstm_nbr = ? ";
			}
		
		// if (inlist('20,21,30',$crstm_step_code) || inlist('Action_View1,Action_View2',$user_role)) {
			// $sql_update = " UPDATE crstm_mstr SET ".
			//// cr1
			// "crstm_pre_yy  = '$crstm_pre_yy' ,".
			// "crstm_otd_pct = '$crstm_otd_pct' ,".
			// "crstm_ovr_due = '$crstm_ovr_due' ,".
			// "crstm_etc = '$crstm_etc' ,".
			// "crstm_cur_yy = '$crstm_cur_yy' ,".
			// "crstm_otd1_pct = '$crstm_otd1_pct' ,".
			// "crstm_ovr1_due = '$crstm_ovr1_due' ,".
			// "crstm_etc1 = '$crstm_etc1' ,".
			// "crstm_ins = '$crstm_ins' ,".
			// "crstm_cc1_reson = '$crstm_cc1_reson' ,".
			// "crstm_cr1_img = '$new_filename_cr1' ,".
			//// cr2
			// "crstm_dbd_rdo = '$crstm_dbd_rdo' ,".
			// "crstm_dbd_yy = '$crstm_dbd_yy' ,".
			// "crstm_dbd1_yy = '$crstm_dbd1_yy' ,".
			// "crstm_cc2_reson = '$crstm_cc2_reson' ,".
			// "crstm_dbd_img = '$new_filename_cr21' ,".
			// "crstm_dbd1_img = '$new_filename_cr22' ,".
			// "crstm_cr2_img = '$new_filename_cr23' ,".
			// "crstm_step_code = '$crstm_step_code' ,".
			// "crstm_step_name = '$crstm_step_name' ,".
			// "crstm_rem_rearward = NULL," .
		
			// "crstm_whocanread = '$crstm_whocanread' ,".
			// "crstm_create_by_cr2 = '$user_fullname' ,".
			// "crstm_create_cr2_date  = '$curr_date', ".
			// "crstm_update_by = '$crstm_update_by' ,".
			// "crstm_update_date = '$crstm_update_date' ".
			// " WHERE crstm_nbr = ? ";
			// }
		if (inlist('30,40,41',$crstm_step_code) && inlist($user_role,'FinCR Mgr')) {
			$sql_update = " UPDATE crstm_mstr SET ".
			"crstm_mgr_reson = '$crstm_mgr_reson' ,".
			"crstm_mgr_rdo = '$crstm_mgr_rdo' ,".
			"crstm_cr_mgr = '$crstm_cr_mgr' ,".
			"crstm_mgr_img = '$new_filename_cr3' ,".
			"crstm_step_code = '$crstm_step_code' ,".
			"crstm_step_name = '$crstm_step_name' ,".
			"crstm_chk_rearward = '0' ," .
			
			//"crstm_curprocessor = '$user_login' ,".
			"crstm_whocanread = '$crstm_whocanread' ,".
			"crstm_create_by_mgr = '$user_fullname' ,".
			"crstm_create_mgr_date  = '$curr_date', ".
			"crstm_update_by = '$crstm_update_by' ,".
			"crstm_update_date = '$crstm_update_date' ".
			" WHERE crstm_nbr = ? ";
			}
		$result_update = sqlsrv_query($conn, $sql_update,$params);
				
		if ($result_update) {
			$r="1";
			$nb=encrypt($crstm_nbr, $key);
			$errortxt="Update success.";
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Update fail.";
		}
		
		///// Send email Cr1 ---> Cr2
		if (($crstm_step_code=="20") && ($formid == "frm_send")) {

			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			$crstm_cus_name = findsqlval("crstm_mstr", "crstm_cus_name", "crstm_nbr", $crstm_nbr ,$conn);
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
			$params = array($user_login);		
			$sql_emp = "SELECT emp_prefix_th_name,emp_th_firstname,emp_th_lastname,emp_email_bus from emp_mstr where emp_user_id = ?";
				$result_emp = sqlsrv_query($conn, $sql_emp,$params);	
				$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
					if ($r_emp) {
						$user_fullname = html_clear(trim($r_emp["emp_prefix_th_name"])) . html_clear(trim($r_emp["emp_th_firstname"])) . " " . html_clear(trim($r_emp["emp_th_lastname"]));
						$user_email = html_clear(strtolower($r_emp['emp_email_bus']));
						
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
			
		}	
		///// Send email Cr1 ---> Cr2
		
		///// Send email Cr2 ---> Manager
		if (($crstm_step_code=="30") && ($formid == "frm_send")) {
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			$crstm_cus_name = findsqlval("crstm_mstr", "crstm_cus_name", "crstm_nbr", $crstm_nbr ,$conn);

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
			$sql_emp = "SELECT emp_prefix_th_name,emp_th_firstname,emp_th_lastname,emp_email_bus from emp_mstr where emp_user_id = ?";
				$result_emp = sqlsrv_query($conn, $sql_emp,$params);	
				$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
					if ($r_emp) {
						$user_fullname = html_clear(trim($r_emp["emp_prefix_th_name"])) . html_clear(trim($r_emp["emp_th_firstname"])) . " " . html_clear(trim($r_emp["emp_th_lastname"]));
						$user_email = html_clear(strtolower($r_emp['emp_email_bus']));
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
		if (($crstm_step_code=="40" || $crstm_step_code=="41")  && ($formid == "frm_send")) {
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
					$can_sendmail=false;
					$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			$crstm_cus_name = findsqlval("crstm_mstr", "crstm_cus_name", "crstm_nbr", $crstm_nbr ,$conn);

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
			$sql_aucadmin = "SELECT crstm_user From crstm_mstr WHERE (crstm_nbr = ?)";
			$result_aucadmin = sqlsrv_query( $conn, $sql_aucadmin,$params);											
				while($r_aucadmin = sqlsrv_fetch_array($result_aucadmin, SQLSRV_FETCH_ASSOC)) {
					$aucadmin_user_login = $r_aucadmin['crstm_user'];
					$aucadmin_user_email = findsqlval("emp_mstr","emp_email_bus","emp_scg_emp_id",$aucadmin_user_login,$conn);
					$cr_next_curprocessor_email = $aucadmin_user_email;
				}
				
				//$cr_next_curprocessor_email= $cr_next_curprocessor_email.","."credit@scg.com,"."nuchanav@scg.com";				
				$cr_next_curprocessor_email= $cr_next_curprocessor_email.","."credit@scg.com";				
				$params = array($aucadmin_user_login);
				$sql_emp = "SELECT emp_prefix_th_name,emp_th_firstname,emp_th_lastname,emp_email_bus from emp_mstr where emp_scg_emp_id = ?";
				$result_emp = sqlsrv_query($conn, $sql_emp,$params);	
				$r_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);
					if ($r_emp) {
						$user_fullname = html_clear(trim($r_emp["emp_prefix_th_name"])) . html_clear(trim($r_emp["emp_th_firstname"])) . " " . html_clear(trim($r_emp["emp_th_lastname"]));
						$user_email = html_clear(strtolower($rec_emp['emp_email_bus']));
						}
			
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
		
		if (inlist('10,20,30,40,41',$crstm_step_code) || inlist('Action_View1,Action_View2,FinCR Mgr',$user_role)) {
			
			$sql_edit = " UPDATE crstm_mstr SET ".
			"crstm_step_code = '$crstm_step_code' ,".
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