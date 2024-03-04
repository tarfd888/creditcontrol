<?php 
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y H:i:s");
	$curYear = date('Y'); 
	$nextYear = date("Y", strtotime("+4 years"));
	$previousYear = date("Y", strtotime("-4 years"));
	clearstatcache();
	include("chkauthcr.php");
	include("chkauthcrctrl.php");
	
	$crstm_nbr = decrypt(html_escape($_REQUEST['crnumber']), $key);
	
	$params = array($crstm_nbr);
	
	$query_cust_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_tax_nbr3, ".
	"crstm_mstr.crstm_address, crstm_mstr.crstm_district, crstm_mstr.crstm_amphur, crstm_mstr.crstm_province, crstm_mstr.crstm_zip, crstm_mstr.crstm_country, crstm_mstr.crstm_chk_rdo1, ".
	"crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_per_mm, ".
	"crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_pj_name, crstm_mstr.crstm_pj_prv, crstm_mstr.crstm_pj_term, crstm_mstr.crstm_pj_amt, crstm_mstr.crstm_pj_dura, crstm_mstr.crstm_pj_beg, ".
	"crstm_mstr.crstm_pj_img, crstm_mstr.crstm_pj1_name, crstm_mstr.crstm_pj1_prv, crstm_mstr.crstm_pj1_term, crstm_mstr.crstm_pj1_amt, crstm_mstr.crstm_pj1_dura, crstm_mstr.crstm_pj1_beg, ".
	"crstm_mstr.crstm_pj1_img, crstm_mstr.crstm_pre_yy, crstm_mstr.crstm_otd_pct, crstm_mstr.crstm_ovr_due, crstm_mstr.crstm_etc, crstm_mstr.crstm_cur_yy, crstm_mstr.crstm_otd1_pct, ".
	"crstm_mstr.crstm_ovr1_due, crstm_mstr.crstm_etc1, crstm_mstr.crstm_ins, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cr1_img, crstm_mstr.crstm_dbd_rdo, crstm_mstr.crstm_dbd_yy, ".
	"crstm_mstr.crstm_dbd_img, crstm_mstr.crstm_dbd1_yy, crstm_mstr.crstm_dbd1_img, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cr2_img, crstm_mstr.crstm_mgr_reson, ".
	"crstm_mstr.crstm_mgr_rdo, crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_mgr_img, crstm_mstr.crstm_create_by, crstm_mstr.crstm_create_date, crstm_mstr.crstm_update_by, ".
	"crstm_mstr.crstm_update_date, crstm_mstr.crstm_step_code, crstm_mstr.crstm_whocanread, crstm_mstr.crstm_curprocessor, crstm_mstr.crstm_cus_active, emp_mstr.emp_th_firstname, ".
	"emp_mstr.emp_th_lastname, emp_mstr.emp_th_pos_name, emp_mstr.emp_manager_name, emp_mstr.emp_email_bus, emp_mstr.emp_tel_bus, crstm_mstr.crstm_cc_date_beg, ".
	"crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_reson_img ,crstm_mstr.crstm_rem_rearward,crstm_mstr.crstm_chk_rearward, ".
	"crstm_reviewer,crstm_reviewer2,crstm_noreviewer, crstm_mstr.crstm_scgc,crstm_email_app1,crstm_email_app2 ".
	"FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
	"WHERE (crstm_mstr.crstm_nbr = ?)";
	
	$result_cust_detail = sqlsrv_query($conn, $query_cust_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_cust_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$e_user_fullname = trim($rec_cus["emp_th_firstname"]) . " " . trim($rec_cus["emp_th_lastname"]);
		$user_th_pos_name = html_clear($rec_cus["emp_th_pos_name"]);
		$user_manager_name = html_clear($rec_cus["emp_manager_name"]);
		$user_email = html_clear($rec_cus["emp_email_bus"]);
		$phone_mask = html_clear($rec_cus["crstm_tel"]);
		
		$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
		$crstm_cus_nbr = html_clear($rec_cus['crstm_cus_nbr']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_tax_nbr3 = html_clear($rec_cus['crstm_tax_nbr3']);
		$crstm_address = html_clear($rec_cus['crstm_address']);
		$crstm_district = html_clear($rec_cus['crstm_district']);
		$crstm_amphur = html_clear($rec_cus['crstm_amphur']);
		$crstm_province = html_clear($rec_cus['crstm_province']);
		$crstm_zip = html_clear($rec_cus['crstm_zip']);
		$crstm_country = html_clear($rec_cus['crstm_country']);
		$crstm_tel = html_clear($rec_cus['crstm_tel']);
		$crstm_term_add = html_clear($rec_cus['crstm_term_add']);
		$rdo_cr_limit = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		$crstm_sd_per_mm = number_format($rec_cus['crstm_sd_per_mm']);
		$crstm_reson_img = html_clear($rec_cus['crstm_reson_img']);
		
		$crstm_pj_name = html_clear($rec_cus['crstm_pj_name']);
		$crstm_pj_amt = number_format($rec_cus['crstm_pj_amt']);
		$crstm_pj_prv = html_clear($rec_cus['crstm_pj_prv']);
		$crstm_pj_term = html_clear($rec_cus['crstm_pj_term']);
		$crstm_pj_dura = html_clear($rec_cus['crstm_pj_dura']);
		$crstm_pj_beg = dmytx($rec_cus['crstm_pj_beg']);
		$crstm_pj_img = html_clear($rec_cus['crstm_pj_img']);
		
		$crstm_pj1_name = html_clear($rec_cus['crstm_pj1_name']);
		$crstm_pj1_amt = number_format($rec_cus['crstm_pj1_amt']);
		$crstm_pj1_prv = html_clear($rec_cus['crstm_pj1_prv']);
		$crstm_pj1_term = html_clear($rec_cus['crstm_pj1_term']);
		$crstm_pj1_dura = html_clear($rec_cus['crstm_pj1_dura']);
		$crstm_pj1_beg = dmytx($rec_cus['crstm_pj1_beg']);
		$crstm_pj1_img = html_clear($rec_cus['crstm_pj1_img']);
		
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_cr1_img = html_clear($rec_cus['crstm_cr1_img']);
		
		$dbd_conf_yes = html_clear($rec_cus['crstm_dbd_rdo']);
		$crstm_dbd_yy = html_clear($rec_cus['crstm_dbd_yy']);
		$crstm_dbd1_yy = html_clear($rec_cus['crstm_dbd1_yy']);
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		$crstm_dbd_img = html_clear($rec_cus['crstm_dbd_img']);
		$crstm_dbd1_img = html_clear($rec_cus['crstm_dbd1_img']);
		$crstm_cr2_img = html_clear($rec_cus['crstm_cr2_img']);
		
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_mgr_rdo = html_clear($rec_cus['crstm_mgr_rdo']);
		$crstm_cr_mgr = html_clear($rec_cus['crstm_cr_mgr']);
		$crstm_mgr_img = html_clear($rec_cus['crstm_mgr_img']);
		$crstm_whocanread = html_clear($rec_cus['crstm_whocanread']);
		
		$crstm_step_code = html_clear($rec_cus['crstm_step_code']);
		$crstm_cc_amt = number_format($rec_cus['crstm_cc_amt']);
		$crstm_cc_date_beg = dmytx($rec_cus['crstm_cc_date_beg']);
		$crstm_cc_date_end = dmytx($rec_cus['crstm_cc_date_end']);
		
		$crstm_reviewer = html_clear($rec_cus['crstm_reviewer']);
		$crstm_reviewer2 = html_clear($rec_cus['crstm_reviewer2']);

		$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		
		$crstm_rem_rearward = html_clear($rec_cus['crstm_rem_rearward']);
		$crstm_chk_rearward = html_clear($rec_cus['crstm_chk_rearward']);
		if ($crstm_cr_mgr != "" ) {
			$crstm_cr_mgr = number_format($crstm_cr_mgr);
		}
		if ($crstm_scgc == true){
			$reviewercanedit = "readOnly";
			$pointer_vie2 = "none";
			$check_flag="1";
		} else {
			$reviewercanedit = "";
			$check_flag="2";
		}
		if ($crstm_noreviewer == true) { // กรณีไม่ระบุผู้พิจารณา1
			$reviewercanedit = "readOnly";
			$reviewer_code = "10";
		} else { 
			$reviewer_code = "110";
		};
		if($crstm_reviewer ==""){$crstm_reviewer = NULL;} 
		if($crstm_reviewer2 ==""){$crstm_reviewer2 = NULL;}
	}
	
	$crstm_th_pos_name = findsqlval("emp_mstr", "emp_th_pos_name", "emp_email_bus", $crstm_email_app1 ,$conn);
	
	$params = array($crstm_reviewer);
	$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
	$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
	$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_emp) {
		$reviwer = html_clear($rec_emp['emp_email_bus']);
		$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
		$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
		$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
		$emp_th_div = html_clear($rec_emp['emp_th_div']);
		$emp_th_dept = html_clear($rec_emp['emp_th_dept']);
		$emp_th_sec = html_clear($rec_emp['emp_th_sec']);
		$emp_th_pos_name = html_clear($rec_emp['emp_th_pos_name']);
		//$reviewer_name = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ."/". $emp_th_div ."/". $emp_th_dept ."/". $emp_th_sec ;
		$reviewer_name = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ."(". $emp_th_pos_name .")"  ;
		$reviewer_pos = "(". $emp_th_pos_name .")" ;		
	} else {
		$crstm_noreviewer = true;
		$reviewer_name = "";
	}

	$params = array($crstm_reviewer2);
	$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
	$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
	$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_emp) {
		$reviwer2 = html_clear($rec_emp['emp_email_bus']);
		$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
		$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
		$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
		$emp_th_div = html_clear($rec_emp['emp_th_div']);
		$emp_th_dept = html_clear($rec_emp['emp_th_dept']);
		$emp_th_sec = html_clear($rec_emp['emp_th_sec']);
		$emp_th_pos_name = html_clear($rec_emp['emp_th_pos_name']);
		//$reviewer_name = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ."/". $emp_th_div ."/". $emp_th_dept ."/". $emp_th_sec ;
		$reviewer_name2 = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ."(". $emp_th_pos_name .")"  ;
		$reviewer_pos = "(". $emp_th_pos_name .")" ;		
	} 

	if($crstm_email_app1!="") {
		$params = array($crstm_email_app1);
		$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
		$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
		$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
		if ($rec_emp) {
			$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
			$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
			$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
			$emp_th_div = html_clear($rec_emp['emp_th_div']);
			$emp_th_dept = html_clear($rec_emp['emp_th_dept']);
			$emp_th_sec = html_clear($rec_emp['emp_th_sec']);
			$emp_manager_email = html_clear($rec_emp['emp_manager_email']);
			$emp_th_pos_name = html_clear($rec_emp['emp_th_pos_name']);
			//$app1_name = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ."/". $emp_th_div ."/". $emp_th_dept ."/". $emp_th_sec ;
			$app1_name = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ."(". $emp_th_pos_name .")"  ;
			
		} 
	}
	$iscan_editing_c1 = false;
	$iscan_editing_c2 = false;
	$iscan_editing_c3 = false;
	
	$iscan_display_c1 = false;
	$iscan_display_c2 = false;
	$iscan_display_c3 = false;
	$iscan_display_sd = false;
	
	if (inlist('10,11',$crstm_step_code) && inlist($user_role,'Action_View1')) {
		$iscan_editing_c1 = true;
	}
	if (inlist('20,21',$crstm_step_code) && inlist($user_role,'Action_View2')) {
		$iscan_editing_c2 = true;
	}
	if (inlist('30',$crstm_step_code) && inlist($user_role,'FinCR Mgr')) {
		$iscan_editing_c3 = true;
	}
	
	if (inlist('10',$crstm_step_code) && inlist($user_role,'Action_View1')) {
		$iscan_display_c1 = true;
	}
	if (inlist('11,20,21',$crstm_step_code) && inlist($user_role,'Action_View2')) {
		$iscan_display_c2 = true;
	}
	if (inlist('21,30,40,41',$crstm_step_code) && inlist($user_role,'FinCR Mgr')) {
		$iscan_display_c3 = true;
	}
	if (inlist('0,01',$crstm_step_code) && inlist($user_role,'SALE_VIEW')) {
		$iscan_display_sd = true;
	}
	
	$iscurrentprocessor = false;
	$iscan_editing = false;
	if (inlist($crstm_curprocessor,$user_login)) {
		//ดูว่า User ที่ Login เข้าระบบมีในรายชื่อ CurrentProcessor หรือไม่ถ้ามีก็มีสิทธิ์ในการ Edit เอกสาร
		//เช่นคนสร้างเอกสาร,หรือผู้อนุมัติเอกสาร
		$iscurrentprocessor = true;
	}
	if ($iscurrentprocessor && inlist('0',$crstm_step_code)) {
		$iscan_editing = true;
	}
	//Display Picture > Project 
	$uploadpath = "../_fileuploads/sale/project/";
	
	$info_reson = pathinfo( $crstm_reson_img , PATHINFO_EXTENSION ) ;	
	switch ($info_reson) {
		case "xlsx":
		$ImgReson = "$uploadpath"."$crstm_reson_img";
		$ImgReson_icon = "$uploadpath"."excel.png";
        break;
		case "xls":
		$ImgReson = "$uploadpath"."$crstm_reson_img";
		$ImgReson_icon = "$uploadpath"."excel.png";
        break;
		case "doc":
		$ImgReson = "$uploadpath"."$crstm_reson_img";
		$ImgReson_icon = "$uploadpath"."word.png";
        break;	
		case "docx":
		$ImgReson = "$uploadpath"."$crstm_reson_img";
		$ImgReson_icon = "$uploadpath"."word.png";
        break;		
		case "pdf":
		$ImgReson = "$uploadpath"."$crstm_reson_img";
		$ImgReson_icon = "$uploadpath"."pdf.png";
        break;
		case "pptx":
		$ImgReson = "$uploadpath"."$crstm_reson_img";
		$ImgReson_icon = "$uploadpath"."ppt.png";
        break;
		case "ppt":
		$ImgReson = "$uploadpath"."$crstm_reson_img";
		$ImgReson_icon = "$uploadpath"."ppt.png";
        break;	
		// case "jpg":
		// $ImgReson = "$uploadpath"."$crstm_reson_img";
		// $ImgReson_icon = $ImgReson;
        // break;
		default:
		if($crstm_reson_img=="") {
			$ImgReson = "$uploadpath"."nopicture.png";
			$ImgReson_icon = $ImgReson;
			}else {
			$ImgReson = "$uploadpath"."$crstm_reson_img";
			$ImgReson_icon = $ImgReson;
		}
	}	
	
	$info_pj = pathinfo( $crstm_pj_img , PATHINFO_EXTENSION ) ;
	switch ($info_pj) {
		case "xlsx":
		$ImgPrj = "$uploadpath"."$crstm_pj_img";
		$ImgPrj_icon = "$uploadpath"."excel.png";
        break;
		case "xls":
		$ImgPrj = "$uploadpath"."$crstm_pj_img";
		$ImgPrj_icon = "$uploadpath"."excel.png";
        break;
		case "doc":
		$ImgPrj = "$uploadpath"."$crstm_pj_img";
		$ImgPrj_icon = "$uploadpath"."word.png";
        break;	
		case "docx":
		$ImgPrj = "$uploadpath"."$crstm_pj_img";
		$ImgPrj_icon = "$uploadpath"."word.png";
        break;		
		case "pdf":
		$ImgPrj = "$uploadpath"."$crstm_pj_img";
		$ImgPrj_icon = "$uploadpath"."pdf.png";
        break;
		case "pptx":
		$ImgPrj = "$uploadpath"."$crstm_pj_img";
		$ImgPrj_icon = "$uploadpath"."ppt.png";
        break;
		case "ppt":
		$ImgPrj = "$uploadpath"."$crstm_pj_img";
		$ImgPrj_icon = "$uploadpath"."ppt.png";
        break;	
		// case "jpg":
		// $ImgPrj = "$uploadpath"."$crstm_pj_img";
		// $ImgPrj_icon = $ImgPrj;
        // break;
		default:
		if($crstm_pj_img=="") {
			$ImgPrj = "$uploadpath"."nopicture.png";
			$ImgPrj_icon = $ImgPrj;
			}else {
			$ImgPrj = "$uploadpath"."$crstm_pj_img";
			$ImgPrj_icon = $ImgPrj;
		}
	}	
	
	$info_pj1 = pathinfo( $crstm_pj1_img , PATHINFO_EXTENSION ) ;
	switch ($info_pj1) {
		case "xlsx":
		$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
		$ImgPrj1_icon = "$uploadpath"."excel.png";
        break;
		case "xls":
		$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
		$ImgPrj1_icon = "$uploadpath"."excel.png";
        break;
		case "doc":
		$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
		$ImgPrj1_icon = "$uploadpath"."word.png";
        break;	
		case "docx":
		$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
		$ImgPrj1_icon = "$uploadpath"."word.png";
        break;		
		case "pdf":
		$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
		$ImgPrj1_icon = "$uploadpath"."pdf.png";
        break;
		case "pptx":
		$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
		$ImgPrj1_icon = "$uploadpath"."ppt.png";
        break;
		case "ppt":
		$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
		$ImgPrj1_icon = "$uploadpath"."ppt.png";
        break;	
		// case "jpg":
		// $ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
		// $ImgPrj1_icon = $ImgPrj1;
        // break;
		default:
		if($crstm_pj1_img=="") {
			$ImgPrj1 = "$uploadpath"."nopicture.png";
			$ImgPrj1_icon = $ImgPrj1;
			}else {
			$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
			$ImgPrj1_icon = $ImgPrj1;
			}
	}
	
	//Display Picture > Credit Control 
	$uploadpath_cr = "../_fileuploads/ac/cr/";
	// cr1
	$info_cr1 = pathinfo( $crstm_cr1_img , PATHINFO_EXTENSION ) ;
	switch ($info_cr1) {
		case "xlsx":
		$ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
		$ImgCr1_icon = "$uploadpath_cr"."excel.png";
        break;
		case "xls":
		$ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
		$ImgCr1_icon = "$uploadpath_cr"."excel.png";
        break;
		case "doc":
		$ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
		$ImgCr1_icon = "$uploadpath_cr"."word.png";
        break;	
		case "docx":
		$ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
		$ImgCr1_icon = "$uploadpath_cr"."word.png";
        break;		
		case "pdf":
		$ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
		$ImgCr1_icon = "$uploadpath_cr"."pdf.png";
        break;
		case "pptx":
		$ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
		$ImgCr1_icon = "$uploadpath_cr"."ppt.png";
        break;
		case "ppt":
		$ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
		$ImgCr1_icon = "$uploadpath_cr"."ppt.png";
        break;	
		// case "jpg":
		// $ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
		// $ImgCr22_icon = $ImgCr22;
        // break;
		default:
		if ($crstm_cr1_img=="") {
			$ImgCr1 = "$uploadpath_cr"."nopicture.png";
			$ImgCr1_icon = $ImgCr1;
			}else {
			$ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
			$ImgCr1_icon = $ImgCr1;
		}
	}	
	
	// cr2
	$info_cr2 = pathinfo( $crstm_dbd_img , PATHINFO_EXTENSION ) ;
	switch ($info_cr2) {
		case "xlsx":
		$ImgCr21 = "$uploadpath_cr"."$crstm_dbd_img";
		$ImgCr21_icon = "$uploadpath_cr"."excel.png";
        break;
		case "xls":
		$ImgCr21 = "$uploadpath_cr"."$crstm_dbd_img";
		$ImgCr21_icon = "$uploadpath_cr"."excel.png";
        break;
		case "doc":
		$ImgCr21 = "$uploadpath_cr"."$crstm_dbd_img";
		$ImgCr21_icon = "$uploadpath_cr"."word.png";
        break;	
		case "docx":
		$ImgCr21 = "$uploadpath_cr"."$crstm_dbd_img";
		$ImgCr21_icon = "$uploadpath_cr"."word.png";
        break;		
		case "pdf":
		$ImgCr21 = "$uploadpath_cr"."$crstm_dbd_img";
		$ImgCr21_icon = "$uploadpath_cr"."pdf.png";
        break;
		case "pptx":
		$ImgCr21 = "$uploadpath_cr"."$crstm_dbd_img";
		$ImgCr21_icon = "$uploadpath_cr"."ppt.png";
        break;
		case "ppt":
		$ImgCr21 = "$uploadpath_cr"."$crstm_dbd_img";
		$ImgCr21_icon = "$uploadpath_cr"."ppt.png";
        break;	
		// case "jpg":
		// $ImgCr22 = "$uploadpath_cr"."$crstm_dbd_img";
		// $ImgCr22_icon = $ImgCr22;
        // break;
		default:
		if($crstm_dbd_img==""){
			$ImgCr21 = "$uploadpath"."nopicture.png";
			$ImgCr21_icon = $ImgCr21;
			}else {
			$ImgCr21 = "$uploadpath_cr"."$crstm_dbd_img";
			$ImgCr21_icon = $ImgCr21;
		}
	}
	
	$info_cr3 = pathinfo( $crstm_dbd1_img , PATHINFO_EXTENSION ) ;
	switch ($info_cr3) {
		case "xlsx":
		$ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
		$ImgCr22_icon = "$uploadpath_cr"."excel.png";
        break;
		case "xls":
		$ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
		$ImgCr22_icon = "$uploadpath_cr"."excel.png";
        break;
		case "doc":
		$ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
		$ImgCr22_icon = "$uploadpath_cr"."word.png";
        break;	
		case "docx":
		$ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
		$ImgCr22_icon = "$uploadpath_cr"."word.png";
        break;		
		case "pdf":
		$ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
		$ImgCr22_icon = "$uploadpath_cr"."pdf.png";
        break;
		case "pptx":
		$ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
		$ImgCr22_icon = "$uploadpath_cr"."ppt.png";
        break;
		case "ppt":
		$ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
		$ImgCr22_icon = "$uploadpath_cr"."ppt.png";
        break;	
		// case "jpg":
		// $ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
		// $ImgCr22_icon = $ImgCr22;
        // break;
		default:
		if($crstm_dbd1_img==""){
			$ImgCr22 = "$uploadpath"."nopicture.png";
			$ImgCr22_icon = $ImgCr22;
			}else {
			$ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
			$ImgCr22_icon = $ImgCr22;
		}
	}
	
	$info_cr4 = pathinfo( $crstm_cr2_img , PATHINFO_EXTENSION ) ;
	switch ($info_cr4) {
		case "xlsx":
		$ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
		$ImgCr23_icon = "$uploadpath_cr"."excel.png";
        break;
		case "xls":
		$ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
		$ImgCr23_icon = "$uploadpath_cr"."excel.png";
        break;
		case "doc":
		$ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
		$ImgCr23_icon = "$uploadpath_cr"."word.png";
        break;	
		case "docx":
		$ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
		$ImgCr23_icon = "$uploadpath_cr"."word.png";
        break;		
		case "pdf":
		$ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
		$ImgCr23_icon = "$uploadpath_cr"."pdf.png";
        break;
		case "pptx":
		$ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
		$ImgCr23_icon = "$uploadpath_cr"."ppt.png";
        break;
		case "ppt":
		$ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
		$ImgCr23_icon = "$uploadpath_cr"."ppt.png";
        break;	
		// case "jpg":
		// $ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
		// $ImgCr23_icon = $ImgCr23;
        // break;
		default:
		if($crstm_cr2_img=="") {
			$ImgCr23 = "$uploadpath_cr"."nopicture.png";
			$ImgCr23_icon = $ImgCr23;
			}else {
			$ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
			$ImgCr23_icon = $ImgCr23;
		}
	}
	
	$info_cr5 = pathinfo( $crstm_mgr_img , PATHINFO_EXTENSION ) ;
	switch ($info_cr5) {
		case "xlsx":
		$ImgCr3 = "$uploadpath_cr"."$crstm_mgr_img";
		$ImgCr3_icon = "$uploadpath_cr"."excel.png";
        break;
		case "xls":
		$ImgCr3 = "$uploadpath_cr"."$crstm_mgr_img";
		$ImgCr3_icon = "$uploadpath_cr"."excel.png";
        break;
		case "doc":
		$ImgCr3 = "$uploadpath_cr"."$crstm_mgr_img";
		$ImgCr3_icon = "$uploadpath_cr"."word.png";
        break;	
		case "docx":
		$ImgCr3 = "$uploadpath_cr"."$crstm_mgr_img";
		$ImgCr3_icon = "$uploadpath_cr"."word.png";
        break;		
		case "pdf":
		$ImgCr3 = "$uploadpath_cr"."$crstm_mgr_img";
		$ImgCr3_icon = "$uploadpath_cr"."pdf.png";
        break;
		case "pptx":
		$ImgCr3 = "$uploadpath_cr"."$crstm_mgr_img";
		$ImgCr3_icon = "$uploadpath_cr"."ppt.png";
        break;
		case "ppt":
		$ImgCr3 = "$uploadpath_cr"."$crstm_mgr_img";
		$ImgCr3_icon = "$uploadpath_cr"."ppt.png";
        break;	
		// case "jpg":
		// $ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
		// $ImgCr23_icon = $ImgCr23;
        // break;
		default:
		if($crstm_mgr_img=="") {
			$ImgCr3 = "$uploadpath"."nopicture.png";
			$ImgCr3_icon = $ImgCr3;
			}else {
			$ImgCr3 = "$uploadpath_cr"."$crstm_mgr_img";
			$ImgCr3_icon = $ImgCr3;
		}
	}
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="PIXINVENT">
		<title><?php echo(TITLE) ?></title>
		<link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
		<!--<link rel="stylesheet" href="_libs/css/font-awesome/css/font-awesome.min.css">-->
		
		<!-- BEGIN VENDOR CSS-->
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">		
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-climacon.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/simple-line-icons/style.min.css">
		<!-- END VENDOR CSS-->
		
		<!-- BEGIN ROBUST CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<!-- END ROBUST CSS-->
		
		<!-- BEGIN Page Level CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/meteocons/style.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/forms/extended/form-extended.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/pickers/daterange/daterange.css">
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/forms/checkboxes-radios.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/icheck.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/custom.css">
		<!-- END Page Level CSS-->
		
		<!-- BEGIN Custom CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/assets/css/style.css">
		<!-- END Custom CSS-->
	</head>
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
		<div id="result"></div>
		<?php include("../crctrlmain/menu_header.php"); ?>
		<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
		<?php include("../crctrlmain/modal.php"); ?>
		<?php include("../crctrlmain/help_modal.php"); ?>
		<!-- BEGIN: Content-->
		<div class="app-content content font-small-3">
			<div class="content-overlay"></div>
			<div class="content-wrapper">
				<div class="content-header row mt-n1">
					<div class="content-header-left col-md-6 col-12 mb-2">
						<div class="row breadcrumbs-top">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php"> Home</a></li>
									<li class="breadcrumb-item"><font class="text text-info "> ใบขออนุมัติวงเงินสินเชื่อลูกค้าใหม่  <?php echo $crstm_nbr; ?></font></li>
								</ol>
							</div>
						</div>
					</div> 
				</div>
				<div class="content-body">
					<section class="new-project">
						<div class="row ">
							<div class="col-12">	
								<div class="card">
									<div class="card-header mt-1 pt-0 pb-0" >
										<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
										<div class="heading-elements">
											<ul class="list-inline mb-0"> 
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>
									</div>
									<div class="card-content collapse show ">  
										<div class="card-body" style="margin-top:-20px;">
											<FORM id="frm_crctrl_edit" name="frm_crctrl_edit" autocomplete=OFF method="POST" enctype="multipart/form-data">
												<input type=hidden name="action" value="edit_new"> 
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
												<input type="hidden" name="cr_cust_code" value="<?php echo($cr_cust_code) ?>">
												<input type="hidden" name="crstm_nbr" value="<?php echo($crstm_nbr) ?>">
												<input type="hidden" name="crstm_whocanread" value="<?php echo($crstm_whocanread) ?>">
												<h4 class="form-section text-info"><i class="fa fa-user"></i> ผู้ขอเสนออนุมัติ</h4>
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label class="font-weight-bold">ชื่อ-สกุล :</label>
															<input type="text" id="e_user_fullname" name ="e_user_fullname" value="<?php echo $e_user_fullname ?>" class="form-control input-sm font-small-3" disabled>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="font-weight-bold">หน่วยงาน : </label>
															<input type="text" id="user_th_pos_name" name ="user_th_pos_name" value="<?php echo $user_th_pos_name ?>" class="form-control input-sm font-small-3" disabled>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="font-weight-bold">ผู้บังคับบัญชา :</label>
															<input type="text" id="user_manager_name" name="user_manager_name" value="<?php echo $user_manager_name ?>" class="form-control input-sm font-small-3" disabled>
														</div>
													</div>
												</div>
												
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label class="font-weight-bold">E-mail:</label>
															<input type="text" id="user_email" name="user_email" value="<?php echo $user_email ?>" class="form-control input-sm font-small-3" disabled>
														</div>
													</div>
													<div class="col-md-4">
														<label class="font-weight-bold">เบอร์โทรศัพท์:</label>
														<class="text-muted font-weight-bold">(999) 999-9999 <font class="text text-danger font-weight-bold"> ***</font>
															<div class="form-group">
																<input type="text" class="form-control phone-inputmask form-control input-sm font-small-3" id="phone_mask" name="phone_mask" value="<?php echo $crstm_tel ?>" disabled >
															</div>
														</div>
													</div>
													<h4 class="form-section text-info"><i class="fa fa-address-card-o"></i> ข้อมูลลูกค้า</h4>
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">รหัสลูกค้า :</label>
																<input type="text" id="crstm_cus_nbr" name="crstm_cus_nbr" value="<?php echo $crstm_cus_nbr ?>" class="form-control input-sm font-small-3" disabled >
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">ชื่อลูกค้า : <font class="text text-danger font-weight-bold"> ***</font></label>
																<input type="text" id="crstm_cus_name" name="crstm_cus_name" value="<?php echo $crstm_cus_name ?>" class="form-control input-sm font-small-3" disabled >
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">เลขประจำตัวผู้เสียภาษี :<font class="text text-danger font-weight-bold"> ***</font></label>
																<input type="text" id="crstm_tax_nbr3" name="crstm_tax_nbr3" value="<?php echo $crstm_tax_nbr3 ?>" class="form-control position-maxlength input-sm font-small-3" disabled maxlength="13" >
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">ที่อยู่ :<font class="text text-danger font-weight-bold"> ***</font></label>
																<input type="text" id="crstm_address" name="crstm_address" value="<?php echo $crstm_address ?>" class="form-control input-sm font-small-3" disabled >
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">ตำบล / แขวง :</label>
																<input type="text" id="crstm_district" name="crstm_district" value="<?php echo $crstm_district ?>" class="form-control input-sm font-small-3" disabled >
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">อำเภอ / เขต :</label>
																<input type="text" id="crstm_amphur" name="crstm_amphur" value="<?php echo $crstm_amphur ?>" class="form-control input-sm font-small-3" disabled>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">จังหวัด :</label>
																<input type="text" id="crstm_province" name="crstm_province" value="<?php echo $crstm_province ?>" class="form-control input-sm font-small-3" disabled>
															</div>
														</div>
														<div class="col-md-2">
															<div class="form-group">
																<label class="font-weight-bold">รหัสไปรษณีย์ :</label>
																<input type="text" id="crstm_zip" name="crstm_zip" value="<?php echo $crstm_zip ?>" class="form-control input-sm font-small-3" maxlength="8" disabled>
															</div>
														</div>
														<div class="col-md-2">
															<div class="form-group">
																<label class="font-weight-bold">ประเทศ :</label>
																<input type="text" id="crstm_country" name="crstm_country" value="<?php echo $crstm_country ?>" class="form-control input-sm font-small-3" disabled>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">เงื่อนไขการชำระเงิน :</label>
																<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="term_desc_add" name="term_desc_add" disabled>
																	<option value="" selected>--- เลือกเงื่อนไขการชำระเงินเพิ่ม ---</option>
																	<?php
																		$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
																		$result_doc = sqlsrv_query($conn, $sql_doc);
																		while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																		?>
																		<option value="<?php echo $r_doc['term_code']; ?>" 
																		<?php if ($crstm_term_add == $r_doc['term_code']) {
																			echo "selected";
																		} ?>>
																		<?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													</div>
													
													<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 1. สำหรับหน่วยงานขาย (เสนอขออนุมัติวงเงินสินเชื่อ)</h4>
													<div class="row">
														<div class="col-md-4">
															<input type="radio"  id="cus_new" name="cus_conf" value="0" checked disabled>
															<label class="font-weight-bold" for="cus_conf_no"> ลูกค้าใหม่</label>
														</div>	
														
														<div class="col-md-4">
															<input type="radio"  id="rdo_cr_limit" name="chk_rdo" value="C4" <?php if($rdo_cr_limit=='C4') { echo "checked"; }?> disabled>
															<label class="font-weight-bold" for="cus_conf_no"> เสนอขออนุมัติวงเงิน</label>
														</div>
														<!-- <div class="col-md-4">
															<input type="radio"  id="rdo_cr_limit" name="chk_rdo" value="C5" <?php if($rdo_cr_limit=='C5') { echo "checked"; }?> > 
															<label class="font-weight-bold" for="cus_conf_no"> อื่น ๆ</label>
														</div> -->
													</div><br>
													<div class="cc_display">
														<div class="row">
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-6 label-control font-weight-bold" for="userinput1">วันที่เริ่ม :</label>
																	<div class="col-md-6">
																		<input type="text" name="beg_date_new" id="beg_date_new" value="<?php echo $crstm_cc_date_beg ?>" class="form-control form-control input-sm font-small-3" placeholder="ระบุวันที่เริ่ม" disabled> 
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-4 label-control font-weight-bold" for="userinput1">วันที่สิ้นสุด :</label>
																	<div class="col-md-6">
																		<input type="text" name="end_date_new" id="end_date_new" value="<?php echo $crstm_cc_date_end ?>" class="form-control form-control input-sm font-small-3" placeholder="ระบุวันที่สิ้นสุด" disabled> 
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-6 label-control font-weight-bold" for="userinput1">วงเงิน (บาท) :</label>
																	<div class="col-md-6">
																		<input type="text" name="cc_amt1" id="cc_amt1" value="<?php echo $crstm_cc_amt ?>" class="form-control form-control input-sm font-small-3" disabled style="color:black;text-align:right" onkeyup="format(this)" onchange="format(this)"> 
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<?php 
														$sum_acc_tot = str_replace(',','',$crstm_cc_amt);
														$crstm_cr_mgr = number_format($sum_acc_tot);
														$acc_tot_app = $sum_acc_tot;
													?>	
													<!--- เช็คอำนาจดำเนินการขออนุมัติวงเงิน --->
													<?	if ($acc_tot_app  <= 700000) { 
															$crstm_approve = 'ผส. อนุมัติ';	
															$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
															$pointer = "pointer";
														}else if ($acc_tot_app >= 700001 && $acc_tot_app <= 2000000) { 
															$crstm_approve = 'ผฝ. อนุมัติ';
															$canedit = "";
															$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
															$pointer = "pointer";
														}else if ($acc_tot_app >= 2000001 && $acc_tot_app <= 5000000) { 
															$crstm_approve = 'CO. อนุมัติ';
															if ($crstm_scgc == true) {
																$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app1_name = findsqlvalfirst("author_mstr", "author_position", "author_text", $crstm_approve ,$conn);
															} else {
																$crstm_email_app1 = findsqlvalfirst("author_g_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app1_name = findsqlvalfirst("author_g_mstr", "author_position", "author_text", $crstm_approve ,$conn);
															}
															$canedit = "readOnly";
															$error_txt ="";
															$pointer = "none";
														}else if ($acc_tot_app >= 5000001 && $acc_tot_app <= 7000000) { 
															$crstm_approve = 'กจก. อนุมัติ';
															if ($crstm_scgc == true) {
																$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app1_name = findsqlvalfirst("author_mstr", "author_position", "author_text", $crstm_approve ,$conn);
															} else {
																$crstm_email_app1 = findsqlvalfirst("author_g_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app1_name = findsqlvalfirst("author_g_mstr", "author_position", "author_text", $crstm_approve ,$conn);
															} 
															$canedit = "readOnly";
															$error_txt ="";
															$pointer = "none";
														}else if ($acc_tot_app >= 7000001 && $acc_tot_app <= 10000000) { 
															$crstm_approve = 'คณะกรรมการสินเชื่ออนุมัติ';
															if ($crstm_scgc == true) {
																$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app1_name = findsqlvalfirst("author_mstr", "author_position", "author_text", $crstm_approve ,$conn);
																$crstm_email_app2 = findsqlvallast("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app2_name = findsqlvallast("author_mstr", "author_position", "author_text", $crstm_approve ,$conn);
															} else { 
																$crstm_email_app1 = findsqlvalfirst("author_g_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app1_name = findsqlvalfirst("author_g_mstr", "author_position", "author_text", $crstm_approve ,$conn);
																$crstm_email_app2 = findsqlvallast("author_g_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app2_name = findsqlvallast("author_g_mstr", "author_position", "author_text", $crstm_approve ,$conn);
															}
															$canedit = "readOnly";
															$error_txt ="";
															$pointer = "none";
														}else { 
															$crstm_approve = 'คณะกรรมการบริหารอนุมัติ';	
															if ($crstm_scgc == true) {
																$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app1_name = findsqlvalfirst("author_mstr", "author_position", "author_text", $crstm_approve ,$conn);
																$crstm_email_app2 = findsqlvallast("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app2_name = findsqlvallast("author_mstr", "author_position", "author_text", $crstm_approve ,$conn);
															} else {
																$crstm_email_app1 = findsqlvalfirst("author_g_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app1_name = findsqlvalfirst("author_g_mstr", "author_position", "author_text", $crstm_approve ,$conn);
																$crstm_email_app2 = findsqlvallast("author_g_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																$app2_name = findsqlvallast("author_g_mstr", "author_position", "author_text", $crstm_approve ,$conn);
															}
															$canedit = "readOnly";
															$error_txt ="";
															$pointer = "none";
													}?>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-6 label-control font-weight-bold" for="userinput1">อำนาจดำเนินการอนุมัติวงเงิน:</label>
																<div class="col-md-6">
																	<input type="text" name="crstm_approve" id="crstm_approve" value="<?php echo $crstm_approve ?>" class="form-control input-sm font-small-3" disabled>
																</div>
															</div>
														</div>
														<div class="col-md-6"></div>
														<div class="col-md-3">
															<label class="font-weight-bold" for="cus_conf_yes">Group:</label>
														</div>
														<div class="col-md-2">
															<input type="radio" name="crstm_scgc" id="crstm_scgc" value=true <?php if ($crstm_scgc==true){ echo "checked"; }?> disabled>
															<label class="font-weight-bold">Tiles</label>
														</div>
														<div class="col-md-2">
															<input type="radio" name="crstm_scgc" id="crstm_scgc1" value=false <?php if ($crstm_scgc==false){ echo "checked"; }?> disabled>
															<label class="font-weight-bold">Geoluxe</label>
														</div>
														<div class="col-md-5"></div>
														<div class="col-md-6">
															<div class="form-group row">
																<label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา 1 : </label>
																<div class="col-md-6">
																	<div class="input-group input-group-sm">
																		<input name="crstm_reviewer" id="crstm_reviewer" value="<?php echo $crstm_reviewer ?>" disabled
																		data-disp_col1 = "emp_fullname"
																		data-disp_col2 = "emp_email_bus"
																		data-typeahead_src = "../_help/get_emp_data.php",
																		data-ret_field_01 = "crstm_reviewer"
																		data-ret_value_01 = "emp_email_bus"
																		data-ret_type_01 = "val"
																		data-ret_field_02 = "reviewer_name"
																		data-ret_value_02 = "emp_fullnamedept"
																		data-ret_type_02 = "html"
																		class="form-control input-sm font-small-3 typeahead">
																		<div class="input-group-prepend">
																			<span class="input-group-text">
																				<a id="buthelp"
																				data-id_field_code="crstm_reviewer" 
																				data-id_field_name="reviewer_name" 
																				data-modal_class = "modal-dialog modal-lg" 
																				data-modal_title = "ข้อมูลพนักงาน" 
																				data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
																				data-modal_col_data1 = "emp_scg_emp_id"
																				data-modal_col_data2 = "emp_fullnamedept"
																				data-modal_col_data3 = "emp_dept"
																				data-modal_col_data4 = "emp_email_bus"
																				data-modal_col_data3_vis = true
																				data-modal_col_data4_vis = true 
																				data-modal_ret_data1 = "emp_email_bus"
																				data-modal_ret_data2 = "emp_fullnamedept"
																				data-modal_src = "../_help/get_emp_data.php"
																				class="input-group-append" style="cursor:pointer">
																					<span class="fa fa-search"></span>
																				</a>
																			</span>
																		</div>
																	</div><br>
																	<div class="dis_reviewer_name">
																		<span id="reviewer_name" name="reviewer_name"  class="text-danger"><?php echo $reviewer_name?></span>
																	</div>
																</div>	
															</div>
														</div>
														
														<div class="col-md-6">
															<div class="form-group row">
																<div class="col-md-1">
																	<input type="checkbox" class="form-control input-sm border-warning " name="crstm_noreviewer" id="crstm_noreviewer" <?php if ($crstm_noreviewer==true){ echo "checked"; }?> disabled>
																</div>
																<label class="col-md-6 label-control" for="userinput1">กรณีไม่ระบุผู้พิจารณา1 :</label>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group row">
																<label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา 2 : </label>
																<div class="col-md-6">
																	<div class="input-group input-group-sm">
																		<input name="crstm_reviewer2" id="crstm_reviewer2" <?php echo $reviewercanedit ?> value="<?php echo $crstm_reviewer2 ?>" 
																		data-disp_col1 = "emp_fullname"
																		data-disp_col2 = "emp_email_bus"
																		data-typeahead_src = "../_help/get_emp_data.php",
																		data-ret_field_01 = "crstm_reviewer2"
																		data-ret_value_01 = "emp_email_bus"
																		data-ret_type_01 = "val"
																		data-ret_field_02 = "reviewer_name2"
																		data-ret_value_02 = "emp_fullnamedept"
																		data-ret_type_02 = "html"
																		class="form-control input-sm font-small-3 typeahead">
																		<div class="input-group-prepend">
																			<span class="input-group-text">
																				<a id="buthelp"
																				data-id_field_code="crstm_reviewer2" 
																				data-id_field_name="reviewer_name2" 
																				data-modal_class = "modal-dialog modal-lg" 
																				data-modal_title = "ข้อมูลพนักงาน" 
																				data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
																				data-modal_col_data1 = "emp_scg_emp_id"
																				data-modal_col_data2 = "emp_fullnamedept"
																				data-modal_col_data3 = "emp_dept"
																				data-modal_col_data4 = "emp_email_bus"
																				data-modal_col_data3_vis = true
																				data-modal_col_data4_vis = true 
																				data-modal_ret_data1 = "emp_email_bus"
																				data-modal_ret_data2 = "emp_fullnamedept"
																				data-modal_src = "../_help/get_emp_data.php"
																				class="input-group-append" style="pointer-events: <?php echo $pointer_vie2 ?>">
																					<span class="fa fa-search" id="pointer1"></span>
																				</a>
																			</span>
																		</div>
																	</div><br>
																	<div class="dis_reviewer_name2">
																		<span id="reviewer_name2" name="reviewer_name2"  class="text-danger"><?php echo $reviewer_name2?></span>
																	</div>
																</div>	
															</div>
														</div>
														<div class="col-md-6"></div>
														
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 1:</label>
																<div class="col-md-6">
																	<div class="input-group input-group-sm">
																		<input name="crstm_email_app1" id="crstm_email_app1" <?php echo $canedit ?> value="<?php echo $crstm_email_app1 ?>" disabled
																		data-disp_col1 = "emp_fullname"
																		data-disp_col2 = "emp_email_bus"
																		data-typeahead_src = "../_help/get_emp_data.php",
																		data-ret_field_01 = "crstm_email_app1"
																		data-ret_value_01 = "emp_email_bus"
																		data-ret_type_01 = "val"
																		data-ret_field_02 = "app1_name"
																		data-ret_value_02 = "emp_fullnamedept"
																		data-ret_type_02 = "html"
																		class="form-control input-sm font-small-3 typeahead">
																		
																		<div class="input-group-prepend">
																			<span class="input-group-text">
																				<a id="buthelp" 
																				data-id_field_code="crstm_email_app1" 
																				data-id_field_name="app1_name" 
																				data-modal_class = "modal-dialog modal-lg" 
																				data-modal_title = "ข้อมูลพนักงาน" 
																				data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
																				data-modal_col_data1 = "emp_scg_emp_id"
																				data-modal_col_data2 = "emp_fullnamedept"
																				data-modal_col_data3 = "emp_dept"
																				data-modal_col_data4 = "emp_email_bus"
																				data-modal_col_data3_vis = true
																				data-modal_col_data4_vis = true 
																				data-modal_ret_data1 = "emp_email_bus"
																				data-modal_ret_data2 = "emp_fullnamedept"
																				data-modal_src = "../_help/get_emp_data.php"
																				class="input-group-append" style="pointer-events: <?php echo $pointer ?>">
																					<span class="fa fa-search" id="pointer1"></span>
																				</a>
																			</span>
																		</div>
																	</div><br>
																	<div><span id="app1_name" name="app1_name"  class="text-danger"><?php echo $app1_name?></span></div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-9 label-control text-danger" id="error_txt"><?php echo $error_txt ?></label>
															</div>
														</div>
														
														<!--<div class="col-md-6 notdisplay"></div>-->
														
														<div class="col-md-6 displayApp2">
																<div class="form-group row">
																	<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 2:</label>
																	<div class="col-md-6">
																		<div class="input-group input-group-sm">
																			<input name="crstm_email_app2" id="crstm_email_app2" <?php echo $canedit ?> value="<?php echo $crstm_email_app2 ?>" 
																			data-disp_col1 = "emp_fullname"
																			data-disp_col2 = "emp_email_bus"
																			data-typeahead_src = "../_help/get_emp_data.php",
																			data-ret_field_01 = "crstm_email_app2"
																			data-ret_value_01 = "emp_email_bus"
																			data-ret_type_01 = "val"
																			data-ret_field_02 = "app2_name"
																			data-ret_value_02 = "emp_fullnamedept"
																			data-ret_type_02 = "html"
																			class="form-control input-sm font-small-3 typeahead">
																			
																			<div class="input-group-prepend">
																				<span class="input-group-text">
																					<a id="buthelp"
																					data-id_field_code="crstm_email_app2" 
																					data-id_field_name="app2_name" 
																					data-modal_class = "modal-dialog modal-lg" 
																					data-modal_title = "ข้อมูลพนักงาน" 
																					data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
																					data-modal_col_data1 = "emp_scg_emp_id"
																					data-modal_col_data2 = "emp_fullnamedept"
																					data-modal_col_data3 = "emp_dept"
																					data-modal_col_data4 = "emp_email_bus"
																					data-modal_col_data3_vis = true
																					data-modal_col_data4_vis = true 
																					data-modal_ret_data1 = "emp_email_bus"
																					data-modal_ret_data2 = "emp_fullnamedept"
																					data-modal_src = "../_help/get_emp_data.php"
																					class="input-group-append" style="pointer-events: none">
																						<span class="fa fa-search"></span>
																					</a>
																				</span>
																			</div>
																		</div><br>
																		<div><span id="app2_name" name="app2_name"  class="text-danger"><?php echo $app2_name?></span></div>
																	</div>
																</div>
														</div>
														<div class="col-md-6 nonCol"></div>
														<!---------------->
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-6 label-control font-weight-bold" for="userinput1">ประมาณการขายเฉลี่ยต่อเดือน (บาท) : <font class="text text-danger font-weight-bold"> ***</font></label>
																<div class="col-md-6">
																	<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_sd_per_mm" class="form-control input-sm " name="crstm_sd_per_mm" value="<?php echo $crstm_sd_per_mm ?>" style="color:blue;text-align:right" disabled onkeyup="format(this)" onchange="format(this)" ></a>
																</div>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<label for="placeTextarea" class="font-weight-bold">ความเห็น / เหตุผลที่เสนอขอวงเงิน : <font class="text text-danger font-weight-bold"> ***</font></label>
																<textarea  name="crstm_sd_reson" id="crstm_sd_reson" class="form-control textarea-maxlength input-sm font-small-3" placeholder="Enter upto 500 characters.." maxlength="500"  rows="5" style="line-height:1.5rem;" disabled><?php echo $crstm_sd_reson; ?></textarea>
															</div>	
														</div>
														
														<?php if(($crstm_rem_rearward !="") && inlist('0,01',$crstm_step_code)){ ?> 
															<div class="col-md-12">
																<div class="form-group">
																	<label for="placeTextarea" class="font-weight-bold">เหตุผลการ Rearward:<font class="text text-danger font-weight-bold"> ***</font></label>
																	<textarea  name="crstm_rem_rearward" id="crstm_rem_rearward" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem; background:#99CC33; color:white;" disabled><?php echo $crstm_rem_rearward; ?></textarea>
																</div>
															</div>
														<?php } ?>
														
														<div class="col-md-6">	
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">เอกสารแนบ:</label>
																<div class="col-md-9">
																	<div class="row">
																		<div class="form-group col-12 mb-2">
																			<label>Select File</label>
																			<label id="projectinput8" class="file center-block">
																				<input type="file" accept="" name="load_reson_img" id="load_reson_img">
																				<input type="hidden" name="crstm_reson_img" id="crstm_reson_img" value="<?php echo $crstm_reson_img ?>">
																				<span class="file-custom"></span>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																<div class="col-md-9">
																	<a href="<?php echo($ImgReson) ?>" target="_blank" id="linkcurrent_ImgProject1">
																		<img src="<?php echo($ImgReson_icon) ?>" border="0" id="ImgReson" name="ImgReson"  width="60" height="60" alt="Click Here">
																	</a>	
																</div>
															</div>
														</div>
														<div class="col-md-2">
															<input type="checkbox"  id="del_reson" name="del_reson" value="1" disabled>
															<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
														</div>
														
														<div class="col-md-12">
															<div class="form-group">
																<label class="font-weight-bold">ข้อมูลโครงการ (ถ้ามี):</label>
															</div>
														</div>
														
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">ชื่อโครงการ (1):</label>
																<div class="col-md-9">
																	<input type="text" name="crstm_pj_name" id="crstm_pj_name" value="<?php echo $crstm_pj_name ?>" class="form-control input-sm" disabled>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">ชื่อโครงการ (2):</label>
																<div class="col-md-9">
																	<input type="text" id="crstm_pj1_name" class="form-control input-sm" name="crstm_pj1_name" value="<?php echo $crstm_pj1_name ?>" disabled>
																</div>
															</div>
														</div>
														
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">จังหวัด:</label>
																<div class="col-md-9">
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj_prv" name="crstm_pj_prv" disabled>
																		<option value="" selected>--- เลือกจังหวัด ---</option>
																		<?php
																			$sql_doc = "SELECT * FROM province_mstr order by province_th_name";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
																			<option value="<?php echo $r_doc['province_th_name']; ?>" 
																			<?php if ($crstm_pj_prv == $r_doc['province_th_name']) {
																				echo "selected";
																			} ?>>	
																			<?php echo $r_doc['province_th_name']; ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">จังหวัด:</label>
																<div class="col-md-9">
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj1_prv" name="crstm_pj1_prv" disabled>
																		<option value="" selected>--- เลือกจังหวัด ---</option>
																		<?php
																			$sql_doc = "SELECT * FROM province_mstr order by province_th_name";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
																			<option value="<?php echo $r_doc['province_th_name']; ?>" 
																			<?php if ($crstm_pj1_prv == $r_doc['province_th_name']) {
																				echo "selected";
																			} ?>>	
																			<?php echo $r_doc['province_th_name']; ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">เงื่อนไขการชำระ:</label>
																<div class="col-md-9">
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj_term" name="crstm_pj_term" disabled>
																		<option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---</option>
																		<?php
																			$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
																			<option value="<?php echo $r_doc['term_code']; ?>" 
																			<?php if ($crstm_pj_term == $r_doc['term_code']) {
																				echo "selected";
																			} ?>>
																			<?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">เงื่อนไขการชำระ:</label>
																<div class="col-md-9">
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj1_term" name="crstm_pj1_term" disabled>
																		<option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---</option>
																		<?php
																			$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
																			<option value="<?php echo $r_doc['term_code']; ?>" 
																			<?php if ($crstm_pj1_term  == $r_doc['term_code']) {
																				echo "selected";
																			} ?>>
																			<?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">มูลค่างาน (บาท):</label>
																<div class="col-md-9">
																	<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj_amt" value="<?php echo($crstm_pj_amt) ?>" class="form-control input-sm" name="crstm_pj_amt" style="color:black;text-align:right" disabled onkeyup="format(this)" onchange="format(this)" ></a>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">มูลค่างาน (บาท):</label>
																<div class="col-md-9">
																	<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj1_amt" value="<?php echo($crstm_pj1_amt) ?>" class="form-control input-sm" name="crstm_pj1_amt" style="color:black;text-align:right" disabled onkeyup="format(this)" onchange="format(this)"></a>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">ระยะเวลา (เดือน):</label>
																<div class="col-md-9">
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj_dura" name="crstm_pj_dura" disabled>
																		<option value="" selected>--- เลือกจำนวนเดือน ---</option>
																		<?php
																			$sql_doc = "SELECT tbl_mm_code, tbl_mm_desc, tbl_mm_seq FROM tbl_mm ORDER BY tbl_mm_seq";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
																			<option value="<?php echo $r_doc['tbl_mm_code']; ?>" 
																			<?php if ($crstm_pj_dura == $r_doc['tbl_mm_code']) {
																				echo "selected";
																			} ?>>	
																			<?php echo $r_doc['tbl_mm_desc']; ?></option>
																		<?php } ?>
																	</select>
																</div>
																<!--<div class="col-md-9">
																	<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj_dura" class="form-control input-sm" name="crstm_pj_dura" onkeypress="return chkNumber_dot(event)"></a>
																</div>-->
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">ระยะเวลา (เดือน):</label>
																<div class="col-md-9">
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj1_dura" name="crstm_pj1_dura" disabled>
																		<option value="" selected>--- เลือกจำนวนเดือน ---</option>
																		<?php
																			$sql_doc = "SELECT tbl_mm_code, tbl_mm_desc, tbl_mm_seq FROM tbl_mm ORDER BY tbl_mm_seq";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
																			<option value="<?php echo $r_doc['tbl_mm_code']; ?>" 
																			<?php if ($crstm_pj1_dura == $r_doc['tbl_mm_code']) {
																				echo "selected";
																			} ?>>	
																			<?php echo $r_doc['tbl_mm_desc']; ?></option>
																		<?php } ?>
																	</select>
																</div>
																<!--<div class="col-md-9">
																	<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj1_dura" class="form-control input-sm" name="crstm_pj1_dura" onkeypress="return chkNumber_dot(event)"></a>
																</div>-->
															</div>
														</div>
														
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">เริ่มใช้งาน:</label>
																<div class="col-md-9">
																	<div class="input-group input-group-sm">
																		<input id="crstm_pj_beg" name="crstm_pj_beg" value="<?php echo $crstm_pj_beg ?>" class="form-control input-sm border-warning font-small-3" type="text" disabled>
																		<div class="input-group-prepend">
																			<span class="input-group-text">
																				<span class="fa fa-calendar-o"></span>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
														</div>	
														<div class="col-md-6">	
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">เริ่มใช้งาน:</label>
																<div class="col-md-9">
																	<div class="input-group input-group-sm">
																		<input id="crstm_pj1_beg" name="crstm_pj1_beg" value="<?php echo $crstm_pj1_beg ?>" class="form-control input-sm border-warning font-small-3" type="text" disabled>
																		<div class="input-group-prepend">
																			<span class="input-group-text">
																				<span class="fa fa-calendar-o"></span>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														
														<div class="col-md-6">	
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">เอกสารแนบ:</label>
																<div class="col-md-9">
																	<div class="row">
																		<div class="form-group col-12 mb-2">
																			<label>Select File</label>
																			<label id="projectinput8" class="file center-block">
																				<input type="file" accept="*" name="load_pj_img" id="load_pj_img">
																				<input type="hidden" name="crstm_pj_img" id="crstm_pj_img" value="<?php echo $crstm_pj_img ?>">
																				<span class="file-custom"></span>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">	
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">เอกสารแนบ:</label>
																<div class="col-md-9">
																	<div class="row">
																		<div class="form-group col-12 mb-2">
																			<label>Select File</label>
																			<label id="projectinput8" class="file center-block">
																				<input type="file" accept="*" name="load_pj1_img" id="load_pj1_img">
																				<input type="hidden" name="crstm_pj1_img" id="crstm_pj1_img" value="<?php echo $crstm_pj1_img ?>">
																				<span class="file-custom"></span>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																<div class="col-md-9">
																	<a href="<?php echo($ImgPrj) ?>" target="_blank" id="linkcurrent_ImgProject">
																		<img src="<?php echo($ImgPrj_icon) ?>" border="0" id="ImgProject" name="ImgProject"  width="60" height="60">
																	</a>	
																</div>
															</div>
														</div>
														<div class="col-md-2">
															<input type="checkbox"  id="del_pj" name="del_pj" value="1" disabled>
															<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
														</div>
														<div class="col-md-4">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																<div class="col-md-9">
																	<a href="<?php echo($ImgPrj1) ?>" target="_blank" id="linkcurrent_ImgProject1">
																		<img src="<?php echo($ImgPrj1_icon) ?>" border="0" id="ImgProject1" name="ImgProject1"  width="60" height="60">
																	</a>	
																</div>
															</div>
														</div>
														<div class="col-md-2">
															<input type="checkbox"  id="del_pj1" name="del_pj1" value="1" disabled>
															<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
														</div>
														
														<?php if(($iscan_display_c1) || ($iscan_display_c2) || ($iscan_display_c3)) { ?>
															<div class="col-md-12">
																<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 2. สำหรับหน่วยงานสินเชื่อ ( 1 )</h4>
																<div class="form-group">
																	<label for="placeTextarea" class="font-weight-bold">ความเห็นสินเชื่อ #1 : <font class="text text-danger font-weight-bold"> ***</font></label>
																	<textarea  name="crstm_cc1_reson" id="crstm_cc1_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;" disabled><?php echo $crstm_cc1_reson; ?></textarea>
																</div>	
															</div>
															
															<?php if(($crstm_rem_rearward !="") && inlist('10,11',$crstm_step_code)){ ?> 
																<div class="col-md-12">
																	<div class="form-group">
																		<label for="placeTextarea" class="font-weight-bold">เหตุผลการ Rearward:<font class="text text-danger font-weight-bold"> ***</font></label>
																		<textarea  name="crstm_rem_rearward" id="crstm_rem_rearward" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem; background:#99CC33; color:white;" disabled><?php echo $crstm_rem_rearward; ?></textarea>
																	</div>
																</div>
															<?php } ?>
															
															<div class="col-md-6">	
																<div class="form-group row">
																	<label class="col-md-3 label-control" for="userinput1">เอกสารแนบ:</label>
																	<div class="col-md-9">
																		<div class="row">
																			<div class="form-group col-12 mb-2">
																				<label>Select File</label>
																				<label id="projectinput8" class="file center-block">
																					<input type="file" accept="" name="load_cr1_img" id="load_cr1_img" value="<?php echo $crstm_cr1_img ?>" >
																					<input type="hidden" name="crstm_cr1_img" id="crstm_cr1_img" value="<?php echo $crstm_cr1_img ?>">																								<span class="file-custom"></span>
																				</label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group row">
																	<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																	<div class="col-md-9">
																		<a href="<?php echo($ImgCr1) ?>" target="_blank" id="linkcurrent_ImgProject">
																			<img src="<?php echo($ImgCr1_icon) ?>" border="0" id="ImgCr1" name="ImgCr1"  width="60" height="60">
																		</a>	
																	</div>
																</div>
															</div>
															<div class="col-md-2">
																<input type="checkbox"  id="del_cr1" name="del_cr1" value="1" disabled>
																<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
															</div>
														<?php } ?>	
														
													</div>
													
													<?php if(($iscan_display_c2) || ($iscan_display_c3)) { ?>
														<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 2. สำหรับหน่วยงานสินเชื่อ ( 2 )</h4>
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<label class="font-weight-bold">Upload งบการเงินลูกค้า:</label>
																</div>
															</div>
														</div>
														
														<div class="row">
															<div class="col-md-6">
																<input type="checkbox"  id="dbd_conf_yes" name="web_conf" value="1" <?php if($dbd_conf_yes=='1'){ echo "checked"; }?> >
																<label class="font-weight-bold" for="cus_conf_yes">งบการเงินจากเว็บไซต์กรมพัฒนาธุรกิจ</label>
															</div>
															<div class="col-md-6">
																<input type="checkbox"  id="oth_conf_yes" name="web_conf" value="2" <?php if($dbd_conf_yes=='2'){ echo "checked"; }?> >
																<label class="font-weight-bold" for="cus_conf_yes">งบการเงินจากแหล่งอื่นๆ</label>
															</div>
														</div>
														<div class="row">
															<div class="col-2">
																<fieldset>
																	<label for="check_same" class="font-weight-bold">งบการเงินล่าสุด:</label>
																</fieldset>
															</div>
															
															<div class="col-md-3 ml-auto">
																<!--<input type="text" id="fin_sta" name="fin_sta" class="form-control input-sm font-small-3" >-->
																<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_dbd_yy" name="crstm_dbd_yy">
																	<option value="" selected>--- เลือกปี ---</option>
																	<?php
																		$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy WHERE tbl_yy_desc BETWEEN $previousYear AND $curYear ORDER BY tbl_yy_desc";
																		$result_doc = sqlsrv_query($conn, $sql_doc);
																		while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																		?>
																		<option value="<?php echo $r_doc['tbl_yy_desc']; ?>" 
																		<?php if ($crstm_dbd_yy == $r_doc['tbl_yy_desc']) {
																			echo "selected";
																		} ?>>
																		<?php echo $r_doc['tbl_yy_desc']; ?></option>
																	<?php } ?>
																</select>
															</div>
															
															<div class="col-2">
																<fieldset>
																	<label for="check_same" class="font-weight-bold">งบการเงินล่าสุด:</label>
																</fieldset>
															</div>
															
															<div class="col-md-3 ml-auto">
																<!--<input type="text" id="fin_sta" name="fin_sta" class="form-control input-sm font-small-3" >-->
																<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_dbd1_yy" name="crstm_dbd1_yy">
																	<option value="" selected>--- เลือกปี ---</option>
																	<?php
																		$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy WHERE tbl_yy_desc BETWEEN $previousYear AND $curYear ORDER BY tbl_yy_desc";
																		$result_doc = sqlsrv_query($conn, $sql_doc);
																		while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																		?>
																		<option value="<?php echo $r_doc['tbl_yy_desc']; ?>" 
																		<?php if ($crstm_dbd1_yy == $r_doc['tbl_yy_desc']) {
																			echo "selected";
																		} ?>>
																		<?php echo $r_doc['tbl_yy_desc']; ?></option>
																	<?php } ?>
																</select>
															</div><br></br>
															
															<div class="col-md-4">
																<div class="row">
																	<div class="form-group col-12 mb-2">
																		<label>Select File</label>
																		<label id="projectinput8" class="file center-block">
																			<input type="file" accept="" name="load_dbd_img" id="load_dbd_img" value="<?php echo $crstm_dbd_img ?>" >
																			<input type="hidden" name="crstm_dbd_img" id="crstm_dbd_img" value="<?php echo $crstm_dbd_img ?>">										
																			<span class="file-custom"></span>
																		</label>
																	</div>
																</div>
															</div>
															<div class="col-md-2">
																<input type="checkbox"  id="del_dbd" name="del_dbd" value="1">
																<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
															</div>
															<div class="col-md-4">
																<div class="row">
																	<div class="form-group col-12 mb-2">
																		<label>Select File</label>
																		<label id="projectinput8" class="file center-block">
																			<input type="file" accept="" name="load_dbd1_img" id="load_dbd1_img" value="<?php echo $crstm_dbd1_img ?>" >
																			<input type="hidden" name="crstm_dbd1_img" id="crstm_dbd1_img" value="<?php echo $crstm_dbd1_img ?>">										
																			<span class="file-custom"></span>
																		</label>
																	</div>
																</div>
															</div>
															<div class="col-md-2">
																<input type="checkbox"  id="del_dbd1" name="del_dbd1" value="1">
																<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
															</div>
															
															<div class="card-body  my-gallery" itemscope itemtype="http://schema.org/ImageGallery">		
																<div class="row">
																	<figure class="col-md-6 col-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
																		<a href="<?php echo($ImgCr21) ?>" target="_blank" itemprop="contentUrl" data-size="480x360">
																			<img class="img-thumbnail img-fluid" src="<?php echo($ImgCr21_icon) ?>" itemprop="thumbnail" alt="Image description" />
																		</a>
																	</figure>
																	<figure class="col-md-6 col-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
																		<a href="<?php echo($ImgCr22) ?>" target="_blank" itemprop="contentUrl" data-size="480x360">
																			<img class="img-thumbnail img-fluid" src="<?php echo($ImgCr22_icon) ?>" itemprop="thumbnail" alt="Image description" />
																		</a>
																	</figure>
																</div>
																
																<div class="row">
																	<div class="col-md-12">
																		<fieldset class="form-group">
																			<label for="placeTextarea" class="font-weight-bold">ความเห็นสินเชื่อ #2 : <font class="text text-danger font-weight-bold"> *</font></label>
																			<textarea  name="crstm_cc2_reson" id="crstm_cc2_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;"><?php echo $crstm_cc2_reson; ?></textarea>
																		</fieldset>	
																	</div>
																	<?php if(($crstm_rem_rearward !="") && inlist('20,21',$crstm_step_code)){ ?> 
																		<div class="col-md-12">
																			<div class="form-group">
																				<label for="placeTextarea" class="font-weight-bold">เหตุผลการ Rearward:<font class="text text-danger font-weight-bold"> ***</font></label>
																				<textarea  name="crstm_rem_rearward" id="crstm_rem_rearward" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem; background:#99CC33; color:white;"><?php echo $crstm_rem_rearward; ?></textarea>
																			</div>
																		</div>
																	<?php } ?>
																	
																	<div class="col-md-6">	
																		<div class="form-group row">
																			<label class="col-md-3 label-control" for="userinput1">เอกสารแนบ:</label>
																			<div class="col-md-9">
																				<div class="row">
																					<div class="form-group col-12 mb-2">
																						<label>Select File</label>
																						<label id="projectinput8" class="file center-block">
																							<input type="file" accept="" name="load_cr2_img" id="load_cr2_img" value="<?php echo $crstm_cr2_img ?>" >
																							<input type="hidden" name="crstm_cr2_img" id="crstm_cr2_img" value="<?php echo $crstm_cr2_img ?>">										
																							<span class="file-custom"></span>
																							
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group row">
																			<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																			<div class="col-md-9">
																				<a href="<?php echo($ImgCr23) ?>" target="_blank" id="linkcurrent_ImgProject1">
																					<img src="<?php echo($ImgCr23_icon) ?>" border="0" id="ImgCr23" name="ImgCr23"  width="60" height="60">
																				</a>	
																			</div>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<input type="checkbox"  id="del_cr2" name="del_cr2" value="1">
																		<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
																	</div>
																<?php } ?>
																
																<?php if(($iscan_display_c3)) { ?> 
																	<div class="col-md-12">
																		<fieldset class="form-group">
																			<label for="placeTextarea" class="font-weight-bold">ความเห็น Manager:<font class="text text-danger font-weight-bold"> ***</font></label>
																			<textarea  name="crstm_mgr_reson" id="crstm_mgr_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;"><?php echo $crstm_mgr_reson; ?></textarea>
																		</fieldset>	
																	</div>
																	<div class="col-md-2">
																		<input type="radio"  id="mgr_conf_yes" name="mgr_conf" value="1" <?php if($crstm_mgr_rdo=='1'){ echo "checked"; }?> >
																		<label class="font-weight-bold" for="cus_conf_yes"> เห็นควรอนุมัติวงเงิน</label>
																	</div>
																	<div class="col-md-2">
																		<input type="text" id="crstm_cr_mgr" name="crstm_cr_mgr" class="form-control input-sm font-small-3" value="<?php echo $crstm_cc_amt ?>"  style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)">
																	</div>
																	<div class="col-md-2">
																		<input type="radio"  id="mgr_conf_no" name="mgr_conf" value="2" <?php if($crstm_mgr_rdo=='2'){ echo "checked"; }?> >
																		<label class="font-weight-bold" for="cus_conf_yes">ไม่เห็นควรอนุมัติ</label>
																	</div>
																<?php } ?>
																
																
															</div>
														
															<div class="form-group row mt-n3"> 
																<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
																	<?php if(inlist($user_role,"SALE_VIEW")) { ?>
																		<?php if($reviewer_code == "110") { ?>
																			<button type="button" id="btnsave" name="btnsave" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send','<?php echo encrypt($reviewer_code, $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit110</button>
																		<? } else { ?>	
																			<?php if($crstm_sd_reson !="") { ?> <!-- case แก้ไข ---->
																				<button type="button" id="btnsave" name="btnsave" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send_cr1','<?php echo encrypt($reviewer_code, $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit10</button>
																			<?php } ?>
																		<? } ?>		
																		<button type="button" id="btnsave" name="btnsave" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_edit','<?php echo encrypt($crstm_step_code, $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-check-square-o"></i> Save</button>
																	<?php } ?>
																	<?php if($iscan_editing_c1) { ?>
																		<?php if($crstm_cc1_reson != "") {?>
																			<button type="button" id="btnsave" name="btnsave" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send_cr1','<?php echo encrypt('20', $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit1</button>
																		<?php } ?>
																		<button type="button" id="btnsave" name="btnsave" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_edit','<?php echo encrypt($crstm_step_code, $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-check-square-o"></i> Save1</button>
																		<button type="button" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" data-toggle="modal" data-target="#div_frm_rearward" data-crstm_nbr="<?php echo $crstm_nbr ?>"><i class="fa fa-backward"></i> Rearward1</button>
																	<?php } ?>
																	
																	<?php if($iscan_editing_c2) { ?>
																		<?php if($crstm_cc2_reson != "") {?>
																			<button type="button" id="btnsave" name="btnsave" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send_cr1','<?php echo encrypt('30', $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit2</button>
																		<?php } ?>
																		<button type="button" id="btnsave" name="btnsave" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_edit','<?php echo encrypt($crstm_step_code, $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-check-square-o"></i> Save2</button>
																		<button type="button" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" data-toggle="modal" data-target="#div_frm_rearward" data-crstm_nbr="<?php echo $crstm_nbr ?>"><i class="fa fa-backward"></i> Rearward2</button>
																	<?php } ?>
																	
																	<?php if($iscan_editing_c3) { ?>
																		<?php if($crstm_mgr_reson != "" && $crstm_reviewer2 !="") {?>
																			<button type="button"  id="btnsavecr" class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send_reviewer2','<?php echo encrypt('220', $key); ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit</button>
																		<?php } ?>
																		<?php if($crstm_mgr_reson != "" && $crstm_reviewer2 =="") {?>
																			<button type="button"  id="btnsavecr" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send_approve','<?php echo encrypt('40', $key); ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit</button>
																		<?php } ?>

																		<button type="button" id="btnsave" name="btnsave" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_edit','<?php echo encrypt($crstm_step_code, $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-check-square-o"></i> Save</button>
																		<button type="button" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" data-toggle="modal" data-target="#div_frm_rearward" data-crstm_nbr="<?php echo $crstm_nbr ?>"><i class="fa fa-backward"></i> Rearward3</button>
																	<?php } ?>
																	<button type="reset" class="btn btn-outline-danger btn-min-width btn-glow mr-1 mb-1" onclick="document.location.href='../crctrlbof/crctrlall.php'"><i class="ft-x"></i> Cancel</button>
																</div>
															</div>
														</form>	
													</div>	
												</div>
											</div>	
										</div>
									</div>	
								</section>
							</div>
						</div>	
					</div>	
					<form name="frm_submit" id="frm_submit" >
						<input type="hidden" name="action" value="submit_new">
						<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
						<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
						<input type="hidden" name="crstm_nbr" value="<?php echo $crstm_nbr; ?>">
						<input type="hidden" name="pg" value="<?php echo $pg ?>">
					</form>
		<!-- END: Content-->			
		<div class="sidenav-overlay"></div>
		<div class="drag-target"></div>
		<!-- BEGIN: Footer-->
		<footer class="footer footer-static footer-light navbar-border">
			<p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 <a class="text-bold-800 grey darken-2" href="https://1.envato.market/pixinvent_portfolio" target="_blank">PIXINVENT </a></span><span class="float-md-right d-none d-lg-block">Power by IT Business Solution Team <i class="feather icon-heart pink"></i></span></p>
		</footer>
		<!-- END: Footer-->
		
		<!-- BEGIN: Vendor JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<!-- BEGIN Vendor JS-->
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/jquery.knob.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/extensions/knob.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/raphael-min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/morris.min.js"></script>
		<!--<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js"></script>-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/data/jvector/visitor-data.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/chart.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jquery.sparkline.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/unslider-min.js"></script>
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>	
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/formatter/formatter.min.js"></script>
		<!-- END: Page Vendor JS-->
		
		<!-- BEGIN: Theme JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<!-- END: Theme JS-->
		
		<!-- BEGIN: Page JS-->
		<!--<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/pages/dashboard-analytics.js"></script>-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/tables/datatables/datatable-basic.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<!--<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/typeahead.bundle.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/handlebars.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-inputmask.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-formatter.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-maxlength.min.js"></script>
		<!-- END: Page JS-->
		<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
			if(crstm_approve != 'คณะกรรมการบริหารอนุมัติ' || crstm_approve != 'คณะกรรมการสินเชื่ออนุมัติ') {
					$('.displayApp2').hide();  
					$('.nonCol').hide();
			}
		});
			function dispostform(formid,chk_action,crstm_nbr,cus_name) {
				//alert(formid+"--"+chk_action+"--"+cus_name+"--"+crstm_nbr);
				$(document).ready(function() {
					if (formid == 'frm_edit') {
							Swalappform(formid,chk_action,crstm_nbr,cus_name);
						} else if (formid =='frm_send'){
							Swalappformsend(formid,chk_action,crstm_nbr,cus_name);
						} else if (formid =='frm_send_cr1'){
							Swalappformsend_cr(formid,chk_action,crstm_nbr,cus_name);
						} else if (formid == 'frm_send_reviewer2') {
							SwalappformSend_reviewer2(formid,chk_action,crstm_nbr,cus_name);
						} else if (formid == 'frm_send_approve') {
							SwalappformSend_app(formid,chk_action,crstm_nbr,cus_name);
						} else if (formid =='frm_rearward') {
							SwalappformRearward(formid,chk_action,crstm_nbr,cus_name);
					}
					//e.preventDefault();
				});
			}
			
			function Swalappform(formid,chk_action,crstm_nbr,cus_name) {
				//alert(formid+"--"+chk_action+"--"+cus_name);
				Swal.fire({
					//title: "Are you sure?",
					html: "คุณต้องการบันทึกข้อมูล  <br>เอกสารเลขที่   " + crstm_nbr + "<br> ลูกค้า  : " + cus_name + " นี้ใช่หรือไม่ !!!! ",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, Save it!",
					confirmButtonClass: "btn btn-primary",
					cancelButtonClass: "btn btn-danger ml-1",
					buttonsStyling: false,
					showLoaderOnConfirm: true,
					preConfirm: function() {
						return new Promise(function(resolve) {
							var result_text = "";
							$.ajaxSetup({
								cache: false,
								contentType: false,
								processData: false
							});
							var formObj = $('#frm_crctrl_edit')[0];
							var formData = new FormData(formObj);
							
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								url: '../serverside/crctrlpost_new.php?step_code='+chk_action+'&formid='+formid+''  ,
								//url: '../serverside/crctrlpost_new.php?step_code=' +chk_action  ,
								//data: $('#' + formid).serialize(),
								data: formData,
								timeout: 10000,
								error: function(xhr, error) {
									showmsg('[' + xhr + '] ' + error);
								},
								success: function(data) {
									console.log(data);
									//alert(data);
									var json = $.parseJSON(data);
									if (json.r == '0') {
										clearloadresult();
										Swal.fire({
											title: "Warning !",
											html: json.e,
											type: "error",
											confirmButtonClass: "btn btn-danger",
											buttonsStyling: false
										});
										} else {
										clearloadresult();
										//$('#sample_data').DataTable().ajax.reload(null, false); // call from external function
										Swal.fire({
											position: "top-end",
											type: "success",
											title: "Save successfully.",
											showConfirmButton: false,
											timer: 500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										location.reload(true);
										$(location).attr('href', 'crctrledit_new.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
									}
								},
								complete: function() {
									$("#requestOverlay").remove(); /*Remove overlay*/
								}
							});
						});   
					},
					allowOutsideClick: false
				});
			}
			function Swalappformsend(formid,chk_action,crstm_nbr,cus_name) {
				//alert(formid+"--"+chk_action+"--"+cus_name);
				Swal.fire({
					html: "คุณได้ทำการแก้ไข และ บันทึกข้อมูลเรียบร้อยแล้ว ก่อนส่งข้อมูล  <br>ลูกค้า   " + cus_name + " ไปให้ผู้ตรวจสอบ ใช่หรือไม่ !!!! " , 
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, Send it!",
					confirmButtonClass: "btn btn-primary",
					cancelButtonClass: "btn btn-danger ml-1",
					buttonsStyling: false,
					showLoaderOnConfirm: true,
					preConfirm: function() {
						return new Promise(function(resolve) {
							var result_text = "";
							$.ajaxSetup({
								cache: false,
								contentType: false,
								processData: false
							});
							//var formObj = $('#frm_submit')[0];
							var formObj = $('#frm_crctrl_edit')[0];
							var formData = new FormData(formObj);
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								//url: '../serverside/crctrlsubmitpost_new.php?step_code=' +chk_action  ,
								url: '../serverside/crctrlsubmitpost_pdf_rev1.php?step_code=' +chk_action  ,
								//data: $('#' + formid).serialize(),
								data: formData,
								timeout: 50000,
								error: function(xhr, error) {
									showmsg('[' + xhr + '] ' + error);
								},
								success: function(data) {
									//console.log(data);
									//alert(data);
									var json = $.parseJSON(data);
									if (json.r == '0') {
										clearloadresult();
										Swal.fire({
											title: "Warning !",
											html: json.e,
											type: "error",
											confirmButtonClass: "btn btn-danger",
											buttonsStyling: false
										});
										} else {
										clearloadresult();
										//$('#sample_data').DataTable().ajax.reload(null, false); // call from external function
										Swal.fire({
											position: "top-end",
											type: "success",
											title: "Submit successfully.",
											showConfirmButton: false,
											timer: 500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										location.reload(true);
										$(location).attr('href', 'crctrlall.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
									}
								},
								complete: function() {
									$("#requestOverlay").remove(); /*Remove overlay*/
								}
							});
						});   /////
					},
					allowOutsideClick: false
				});
			}
			function Swalappformsend_cr(formid,chk_action,crstm_nbr,cus_name) {
				//alert(formid+"--"+aaa+"--"+chk_action+"--"+cus_name);
				Swal.fire({
					//title: "Are you sure?",
					html: "คุณได้ทำการแก้ไข และ บันทึกข้อมูลเรียบร้อยแล้ว ก่อนส่งข้อมูล  <br>ลูกค้า   " + cus_name + " ไปให้แผนกสินเชื่ออนุมัติ ใช่หรือไม่ !!!! " , 
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, Send it!",
					confirmButtonClass: "btn btn-primary",
					cancelButtonClass: "btn btn-danger ml-1",
					buttonsStyling: false,
					showLoaderOnConfirm: true,
					preConfirm: function() {
						return new Promise(function(resolve) {
							var result_text = "";
							$.ajaxSetup({
								cache: false,
								contentType: false,
								processData: false
							});
							var formObj = $('#frm_crctrl_edit')[0];
							var formData = new FormData(formObj);
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								url: '../serverside/crctrlpost_new.php?step_code=' +chk_action+ '&formid='+formid+'' ,
								//data: $('#' + formid).serialize(),
								data: formData,
								timeout: 50000,
								error: function(xhr, error) {
									showmsg('[' + xhr + '] ' + error);
								},
								success: function(data) {
									console.log(data);
									//alert(data);
									var json = $.parseJSON(data);
									if (json.r == '0') {
										clearloadresult();
										Swal.fire({
											title: "Warning !",
											html: json.e,
											type: "error",
											confirmButtonClass: "btn btn-danger",
											buttonsStyling: false
										});
										} else {
										clearloadresult();
										//$('#sample_data').DataTable().ajax.reload(null, false); // call from external function
										Swal.fire({
											position: "top-end",
											type: "success",
											title: "Submit successfully.",
											showConfirmButton: false,
											timer: 1500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										//location.reload(true);
										clearloadresult();
										$(location).attr('href', 'crctrlall.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
									}
								},
								complete: function() {
									$("#requestOverlay").remove(); /*Remove overlay*/
								}
							});
						});   
					},
					allowOutsideClick: false
				});
			}
			function SwalappformSend_reviewer2(formid,chk_action,crstm_nbr,cus_name) {
				//alert(formid+"--"+chk_action+"--"+crstm_nbr+"--"+cus_name);
				Swal.fire({
					//title: "Are you sure?",
					//html: "คุณต้องการส่งข้อมูล  <br>เอกสารเลขที่   " + crstm_nbr + "<br> ลูกค้า  : " + cus_name + "<br>"+ "ไปยังแผนกสินเชื่ออนุมัติ ใช่หรือไม่ !!!! ",
					html: "คุณได้ทำการแก้ไข และ บันทึกข้อมูลเรียบร้อยแล้ว ก่อนส่งข้อมูล  <br>ลูกค้า   " + cus_name + " นำเสนอผู้พิจารณา 2 ใช่หรือไม่ !!!! " , 
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, Send it!",
					confirmButtonClass: "btn btn-primary",
					cancelButtonClass: "btn btn-danger ml-1",
					buttonsStyling: false,
					showLoaderOnConfirm: true,
					preConfirm: function() {
						return new Promise(function(resolve) {
							var result_text = "";
							$.ajaxSetup({
								cache: false,
								contentType: false,
								processData: false
							});
							var formObj = $('#frm_crctrl_edit')[0];
							var formData = new FormData(formObj);
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								// ส่งเมลแจ้ง reviewer2 อนุมัติ
								//url: '../serverside/crctrlsubmitpost_rev2.php?step_code='+chk_action+'&formid='+formid+''  ,
								
								// ส่งไฟล์ pdfแจ้ง reviewer2 อนุมัติ
								url: '../serverside/crctrlsubmitpost_pdf_rev2.php?step_code='+chk_action+'&formid='+formid+''  ,
								data: formData,
								timeout: 10000,
								error: function(xhr, error) {
									showmsg('[' + xhr + '] ' + error);
								},
								success: function(data) {
									console.log(data);
									//alert(data);
									var json = $.parseJSON(data);
									if (json.r == '0') {
										clearloadresult();
										Swal.fire({
											title: "Warning !",
											html: json.e,
											type: "error",
											confirmButtonClass: "btn btn-danger",
											buttonsStyling: false
										});
										} else {
										clearloadresult();
										//$('#sample_data').DataTable().ajax.reload(null, false); // call from external function
										Swal.fire({
											position: "top-end",
											type: "success",
											title: "Submit successfully.",
											showConfirmButton: false,
											timer: 500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										location.reload(true);
										$(location).attr('href', 'crctrlall.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
									}
								},
								complete: function() {
									$("#requestOverlay").remove(); /*Remove overlay*/
								}
							});
						});   /////
					},
					allowOutsideClick: false
				});
			}
			function SwalappformSend_app(formid,chk_action,crstm_nbr,cus_name) {
				//alert(formid+"--"+chk_action+"--"+crstm_nbr+"--"+cus_name);
				Swal.fire({
					//title: "Are you sure?",
					//html: "คุณต้องการส่งข้อมูล  <br>เอกสารเลขที่   " + crstm_nbr + "<br> ลูกค้า  : " + cus_name + "<br>"+ "ไปยังแผนกสินเชื่ออนุมัติ ใช่หรือไม่ !!!! ",
					html: "คุณได้ทำการแก้ไข และ บันทึกข้อมูลเรียบร้อยแล้ว ก่อนส่งข้อมูล  <br>ลูกค้า   " + cus_name + " นำเสนอพิจารณาอนุมัติ ใช่หรือไม่ !!!! " , 
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, Send it!",
					confirmButtonClass: "btn btn-primary",
					cancelButtonClass: "btn btn-danger ml-1",
					buttonsStyling: false,
					showLoaderOnConfirm: true,
					preConfirm: function() {
						return new Promise(function(resolve) {
							var result_text = "";
							$.ajaxSetup({
								cache: false,
								contentType: false,
								processData: false
							});
							var formObj = $('#frm_crctrl_edit')[0];
							var formData = new FormData(formObj);
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								//url: '../serverside/approvepost.php?step_code='+chk_action+'&formid='+formid+'&crstm_nbr='+crstm_nbr  ,
								// ส่งเมลโดยการเขียนรายละเอียดที่หน้าจอเมล
								//url: '../serverside/approvepost.php?step_code='+chk_action+'&formid='+formid+''  ,
								
								// ส่งเมลโดยการเขียนลงไฟล์ pdf 
								url: '../serverside/approvepost_new_pdf.php?step_code='+chk_action+'&formid='+formid+''  ,
								data: formData,
								timeout: 10000,
								error: function(xhr, error) {
									showmsg('[' + xhr + '] ' + error);
								},
								success: function(data) {
									console.log(data);
									//alert(data);
									var json = $.parseJSON(data);
									if (json.r == '0') {
										clearloadresult();
										Swal.fire({
											title: "Warning !",
											html: json.e,
											type: "error",
											confirmButtonClass: "btn btn-danger",
											buttonsStyling: false
										});
										} else {
										clearloadresult();
										//$('#sample_data').DataTable().ajax.reload(null, false); // call from external function
										Swal.fire({
											position: "top-end",
											type: "success",
											title: "Submit successfully.",
											showConfirmButton: false,
											timer: 500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										location.reload(true);
										$(location).attr('href', 'crctrlall.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
									}
								},
								complete: function() {
									$("#requestOverlay").remove(); /*Remove overlay*/
								}
							});
						});   /////
					},
					allowOutsideClick: false
				});
			}
			function SwalappformRearward(formid,chk_action,crstm_nbr,cus_name) {
				//alert(formid+"--"+chk_action+"--"+crstm_nbr+"--"+cus_name);
				Swal.fire({
					//title: "Are you sure?",
					html: "คุณต้องการถอย  <br>เอกสารเลขที่   " + crstm_nbr + "<br> ลูกค้า  : " + cus_name + " ย้อนกลับไป 1 Step ใช่หรือไม่ !!!! ",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, Reward it!",
					confirmButtonClass: "btn btn-primary",
					cancelButtonClass: "btn btn-danger ml-1",
					buttonsStyling: false,
					showLoaderOnConfirm: true,
					preConfirm: function() {
						return new Promise(function(resolve) {
							var result_text = "";
							$.ajaxSetup({
								cache: false,
								contentType: false,
								processData: false
							});
							var formObj = $('#frm_submit')[0];
							var formData = new FormData(formObj);
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								//url: '../serverside/crctrlpost_new.php?step_code=' +chk_action  ,
								//$(location).attr('href', 'crctrlall.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
								url: '../serverside/crctrlpost_new.php?step_code='+chk_action+'&rem1='+crstm_nbr+''  ,
								//data: $('#' + formid).serialize(),
								data: formData,
								timeout: 10000,
								error: function(xhr, error) {
									showmsg('[' + xhr + '] ' + error);
								},
								success: function(data) {
									//console.log(data);
									//alert(data);
									var json = $.parseJSON(data);
									if (json.r == '0') {
										clearloadresult();
										Swal.fire({
											title: "Warning !",
											html: json.e,
											type: "error",
											confirmButtonClass: "btn btn-danger",
											buttonsStyling: false
										});
										} else {
										clearloadresult();
										//$('#sample_data').DataTable().ajax.reload(null, false); // call from external function
										Swal.fire({
											position: "top-end",
											type: "success",
											title: "Submit successfully.",
											showConfirmButton: false,
											timer: 500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										location.reload(true);
										$(location).attr('href', 'crctrlall.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
									}
								},
								complete: function() {
									$("#requestOverlay").remove(); /*Remove overlay*/
								}
							});
						});   /////
					},
					allowOutsideClick: false
				});
			}
			
			$("#cc_amt1").on("change", function () {
				var cc_amt = $(this).val();
				//var acc_tot = $("#acc_tot").val();
				var result_cc_amt1 = parseFloat(cc_amt.replace(/\$|,/g, ''))
				acc_tot_app = result_cc_amt1;
				if($crstm_scgc = true) {
					check_flag = "<?php echo encrypt('1', $key);?>";
				} else {
					check_flag = "<?php echo encrypt('2', $key);?>";
				}
				document.getElementById("crstm_scgc").checked = false;
				document.getElementById("crstm_scgc1").checked = false;
					if (acc_tot_app  <= 700000) { 
						crstm_approve = 'ผส. อนุมัติ';	
						$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
						document.getElementById('crstm_email_app1').readOnly = false; //คีย์ข้อมูลได้
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'auto';
						//document.getElementById("crstm_scgc").checked = false;
						//document.getElementById("crstm_scgc1").checked = true;
						$('.displayApp2').hide();  
						$('.nonCol').hide();
					}
					else if (acc_tot_app >= 700001 && acc_tot_app <= 2000000) { 
						crstm_approve = 'ผฝ. อนุมัติ';
						$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
						document.getElementById('crstm_email_app1').readOnly = false;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'auto';
						//document.getElementById("crstm_scgc").checked = false;
						//document.getElementById("crstm_scgc1").checked = true;
						$('.displayApp2').hide();  
						$('.nonCol').hide();
					}
					else if (acc_tot_app >= 2000001 && acc_tot_app <= 5000000) { 
						crstm_approve = 'CO. อนุมัติ';
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true; //คีย์ข้อมูลไม่ได้
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						//document.getElementById("crstm_scgc").checked = false;
						//document.getElementById("crstm_scgc1").checked = true;
						$('.displayApp2').hide();  
						$('.nonCol').hide();
					}
					else if (acc_tot_app >= 5000001 && acc_tot_app <= 7000000) { 
						crstm_approve = 'กจก. อนุมัติ';
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						//document.getElementById("crstm_scgc").checked = false;
						//document.getElementById("crstm_scgc1").checked = true;
						$('.displayApp2').hide();  
						$('.nonCol').hide();
					}
					else if (acc_tot_app >= 7000001 && acc_tot_app <= 10000000) { 
						crstm_approve = 'คณะกรรมการสินเชื่ออนุมัติ';
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true;
						document.getElementById('crstm_email_app2').readOnly = true;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						//document.getElementById("crstm_scgc").checked = false;
						//document.getElementById("crstm_scgc1").checked = true;
						$('.displayApp2').show();  
						$('.nonCol').show();
					}
					else { 
						crstm_approve = 'คณะกรรมการบริหารอนุมัติ';	
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true;
						document.getElementById('crstm_email_app2').readOnly = true;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						$('.displayApp2').show();  
						$('.nonCol').show();
					} 
				if (crstm_approve == "") {
					document.getElementById("app1_name").innerHTML = "";
					return;
				}	
				const xhttp = new XMLHttpRequest();
				xhttp.onload = function() {
					let email_app1="";
					let email_app2="";
					const myObj = JSON.parse(this.responseText);
					document.getElementById("app1_name").innerHTML = myObj.app1_name;
					document.getElementById("app2_name").innerHTML = myObj.app2_name;
					email_app1 = document.getElementById("crstm_email_app1").innerHTML = myObj.email1;
					email_app2 = document.getElementById("crstm_email_app2").innerHTML = myObj.email2;
					$("#crstm_email_app1").val(email_app1);
					$("#crstm_email_app2").val(email_app2);
					$("#error_txt").val($error_txt);
					console.log(this.responseText);
				}	
				//xhttp.open("GET", "../serverside/authorpost.php?q="+crstm_approve);
				xhttp.open("GET", "../serverside/authorpost.php?q="+crstm_approve+"&check_flag="+ check_flag);

				xhttp.send();   
				$("#crstm_approve").val(crstm_approve);
				//sum_amt_acc = formatCurrency(sum_amt_acc);
				//$("#sum_acc_tot").val(sum_amt_acc);
			});
			$("#crstm_noreviewer").change(function() {
				if (this.checked) {
					$("#crstm_reviewer").val("");
					$("#reviewer_name").val("");
					$(".dis_reviewer_name").hide();   
					document.getElementById("crstm_reviewer").disabled = true;  // disabled textbox ในส่วนอีเมลผู้ตรวจสอบ
					}else {
					document.getElementById("crstm_reviewer").disabled = false;
				}
			});
			
			$("#crstm_scgc").change(function() {
				document.getElementById("crstm_scgc").checked;
				document.getElementById("crstm_nbr").value;
				document.getElementById('crstm_reviewer2').readOnly = true;
				document.getElementById('pointer1').style.pointerEvents = 'none';
				
				$pointer_vie2 = 'none';
				$("#crstm_reviewer2").val("");
				reviewer_name2 = document.getElementById("reviewer_name2").innerHTML = "";
				//alert(reviewer_name2);
				$("#reviewer_name2").val(reviewer_name2);
				
				check_flag = "<?php echo encrypt('1', $key);?>";
				$_approve = document.getElementById("crstm_approve").value;
				check_form = $_approve;
				
				if (check_form == "") {
					document.getElementById("crstm_email_app1").innerHTML = "";
					return;
				}	
				const xhttp = new XMLHttpRequest();
				xhttp.onload = function() {
					let reviewer="";
					let email="";
					const myObj = JSON.parse(this.responseText);
					
					email1 = document.getElementById("crstm_email_app1").innerHTML = myObj.email1;
					$("#crstm_email_app1").val(email1);
					app1_name = document.getElementById("app1_name").innerHTML = myObj.app1_name;
					$("#app1_name").val(app1_name);
					
					if ($_approve == "คณะกรรมการสินเชื่ออนุมัติ" || $_approve == "คณะกรรมการบริหารอนุมัติ") {
						email2 = document.getElementById("crstm_email_app2").innerHTML = myObj.email2;
						$("#crstm_email_app2").val(email2);
						app2_name = document.getElementById("app2_name").innerHTML = myObj.app2_name;
						$("#app2_name").val(app2_name);
					}
					console.log(this.responseText);
				}	
				xhttp.open("GET", "../serverside/checkreviewer.php?q="+check_flag+'&group='+ check_form +'',true);
				//url: '../serverside/crctrlapppost.php?step_code='+chk_action+'&formid='+formid+''  ,
				xhttp.send();   
			});
			$("#crstm_scgc1").change(function() {
				document.getElementById("crstm_scgc1").checked;
				document.getElementById("crstm_reviewer2").value;
				document.getElementById('crstm_reviewer2').readOnly = false;
				document.getElementById('pointer1').style.pointerEvents = 'auto';
				$pointer_vie2 = "auto";
				
				check_flag = "<?php echo encrypt('2', $key);?>";
				$_approve = document.getElementById("crstm_approve").value;
				check_form = $_approve;
				
				if (check_form == "") {
					document.getElementById("crstm_email_app1").innerHTML = "";
					return;
				}	
				const xhttp = new XMLHttpRequest();
				xhttp.onload = function() {
					let reviewer="";
					let email="";
					const myObj = JSON.parse(this.responseText);
					reviewer = document.getElementById("reviewer_name2").innerHTML = myObj.reviewer;
					email = document.getElementById("crstm_reviewer2").innerHTML = myObj.email;
					$("#crstm_reviewer2").val(email);
					$("#reviewer_name2").val(reviewer);
					
					email1 = document.getElementById("crstm_email_app1").innerHTML = myObj.email1;
					$("#crstm_email_app1").val(email1);
					app1_name = document.getElementById("app1_name").innerHTML = myObj.app1_name;
					$("#app1_name").val(app1_name);
					
					
					if ($_approve == "คณะกรรมการสินเชื่ออนุมัติ" || $_approve == "คณะกรรมการบริหารอนุมัติ") {
						email2 = document.getElementById("crstm_email_app2").innerHTML = myObj.email2;
						$("#crstm_email_app2").val(email2);
						app2_name = document.getElementById("app2_name").innerHTML = myObj.app2_name;
						$("#app2_name").val(app2_name);
						document.getElementById('crstm_reviewer2').readOnly = true;  // ผู้พิจารณา 2 อ่านได้อย่างเดียว แก้ไขไม่ได้
					} else {
						document.getElementById('crstm_reviewer2').readOnly = true;
					}
					console.log(this.responseText);
				}	
				xhttp.open("GET", "../serverside/checkreviewer.php?q="+check_flag+'&group='+ check_form +'',true);
				//url: '../serverside/crctrlapppost.php?step_code='+chk_action+'&formid='+formid+''  ,
				xhttp.send();   
			});

			$('#crstm_district,#crstm_amphur,#crstm_province').typeahead({
				displayText: function(item) {
					
					return item.district + " >> อ. " + item.amphoe + "  >> จ. " + item.province + ">> รหัสไปรษณีย์ " + item.zipcode
					
				},
				emptyTemplate: function(item) {
					if (item.length > 0) {
						
						return 'No results found for "' + item + '"';
					}
				},
				source: function(query, response) {
					jQuery.ajax({
						url: "../_libs/thailandjson/raw_database.json", //even.php",
						data: {
							query: query
						},
						dataType: "json",
						type: "POST",
						success: function(data) {
							
							response(data)
						}
						
					})
				},
				
				afterSelect: function(item) {
					$("#crstm_province").val(item.province);
					$("#crstm_amphur").val(item.amphoe);
					$("#crstm_district").val(item.district);
					$("#crstm_zip").val(item.zipcode);
					$("#crstm_country").val("ประเทศไทย");
				}
			});
			function rearwardpostform(formid) {
				//alert(formid);
				$(document).ready(function() {
					$.ajax({
						beforeSend: function() {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
							$("#requestOverlay").show(); /*Show overlay*/
						},
						type: 'POST',
						url: '../serverside/rearwardpost.php',
						data: $('#' + formid).serialize(),
						timeout: 50000,
						error: function(xhr, error) {
							showmsg('[' + xhr + '] ' + error);
						},
						success: function(result) {
							//alert(result);
							var json = $.parseJSON(result);
							if (json.r == '0') {
								clearloadresult();
								Swal.fire({
									title: "Error!",
									html: json.e,
									type: "error",
									confirmButtonClass: "btn btn-danger",
									buttonsStyling: false
								});
								} else {
								clearloadresult();
								Swal.fire({
									type: "success",
									title: "Successful",
									showConfirmButton: false,
									timer: 1500,
									confirmButtonClass: "btn btn-primary",
									buttonsStyling: false,
									animation: false,
								});
								location.reload(true);
								//$(location).attr('href', '../crctrlbof/crctrledit.php?crstm_nbr=' + json.nb + '&nb1=' + json.nb1 + '&nb2=' + json.nb2)
								$(location).attr('href', '../crctrlbof/crctrlall.php?crnumber=' + json.nb + '&pg=' + json.pg)
							}
						},
						
						complete: function() {
							$("#requestOverlay").remove(); /*Remove overlay*/
						}
					});
				});
			}
			function rejectpostform(formid) {
				//alert(formid);
				$(document).ready(function() {
					$.ajax({
						beforeSend: function() {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
							$("#requestOverlay").show(); /*Show overlay*/
						},
						type: 'POST',
						url: '../serverside/rejectpost.php',
						data: $('#' + formid).serialize(),
						timeout: 50000,
						error: function(xhr, error) {
							showmsg('[' + xhr + '] ' + error);
						},
						success: function(result) {
							//alert(result);
							var json = $.parseJSON(result);
							if (json.r == '0') {
								clearloadresult();
								Swal.fire({
									title: "Error!",
									html: json.e,
									type: "error",
									confirmButtonClass: "btn btn-danger",
									buttonsStyling: false
								});
								} else {
								clearloadresult();
								Swal.fire({
									type: "success",
									title: "Successful",
									showConfirmButton: false,
									timer: 1500,
									confirmButtonClass: "btn btn-primary",
									buttonsStyling: false,
									animation: false,
								});
								location.reload(true);
								//$(location).attr('href', '../crctrlbof/crctrledit.php?crstm_nbr=' + json.nb + '&nb1=' + json.nb1 + '&nb2=' + json.nb2)
								$(location).attr('href', '../crctrlbof/crctrlall.php?crnumber=' + json.nb + '&pg=' + json.pg)
							}
						},
						
						complete: function() {
							$("#requestOverlay").remove(); /*Remove overlay*/
						}
					});
				});
			}
			
			(function(window, document, $) {
				'use strict';			
				// Nilubonp : inputMask : Email mask : form-extended-inputs.html
				$('#user_email').inputmask({
					mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
					greedy: false,
					onBeforePaste: function (pastedValue, opts) {
						pastedValue = pastedValue.toLowerCase();
						return pastedValue.replace("mailto:", "");
					},
					definitions: {
						'*': {
							validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]",
							cardinality: 1,
							casing: "lower"
						}
					}
				});
				// inputMask : Phone mask
				$('#phone_mask').inputmask("(999) 999-9999");
			})(window, document, jQuery);
			
			/// เมื่อกรอกตัวเลขจำนวนเงิน ใน textbox ให้มันใส่คอมม่า
			function format(input){
				var num = input.value.replace(/\,/g,'');
				if(!isNaN(num)){
					if(num.indexOf('.') > -1){ 
						num = num.split('.');
						num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'');
						if(num[1].length > 2){ 
							alert('You may only enter two decimals!');
							num[1] = num[1].substring(0,num[1].length-1);
						}  input.value = num[0]+'.'+num[1];        
					} else{ input.value = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'') };
				}
				else{ alert('You may enter only numbers in this field!');
					input.value = input.value.substring(0,input.value.length-1);
				}
			}
			
			$("#isreviewer").change(function() {
				if (this.checked) {
					$("#crstm_reviewer").val("");
					$("#reviewer_name").val("");
					$(".dis_reviewer_name").hide();   
					document.getElementById("crstm_reviewer").disabled = true;  // disabled textbox ในส่วนอีเมลผู้ตรวจสอบ
					}else {
					document.getElementById("crstm_reviewer").disabled = false;
				}
			});
			
			$(document).on("click", "#btn-ret-value", function(e) {
				e.preventDefault();
				var code = $(this).data('send_code');
				var name = $(this).data('send_name');
				var id_field_code = $(this).data('rec_code_id');
				
				var id_field_name = $(this).data('rec_name_id');
				var id_field_name_type = $(this).data('rec_name_type');
				
				$(".dis_reviewer_name").show(); 
				$('#but_help_close').trigger( "click" );
				$('#'+id_field_code).val(code);
				if (id_field_name_type == "value") {
					$('#'+id_field_name).val(name);
					} else {
					$('#'+id_field_name).html(name);
				}
				if (id_field_code.startsWith("sppart")) { 
					if (code == "DUMMY") {
						document.getElementById(id_field_name).readOnly = false;
						document.getElementById(id_field_name).value = "";
						document.getElementById(id_field_name).focus();
					}
					else {
						document.getElementById(id_field_name).readOnly = true;
					}
				}
			});
			
			$("#frm_crctrl_edit").on("click", "#buthelp", function () {
				let input0 = {};
				let data = {};
				var id_field_code = $(this).data('id_field_code');
				var id_field_name = $(this).data('id_field_name');
				var id_field_name_type = $(this).data('id_field_name_type')
				var modal_class = $(this).data('modal_class');
				var modal_title = $(this).data('modal_title');
				var modal_src = $(this).data('modal_src');
				var modal_col_name = $(this).data('modal_col_name'); 
				var modal_col_data1 = $(this).data('modal_col_data1');
				var modal_col_data2 = $(this).data('modal_col_data2');
				var modal_col_data3 = $(this).data('modal_col_data3');
				var modal_col_data4 = $(this).data('modal_col_data4');
				var modal_col_data3_vis = $(this).data('modal_col_data3_vis');
				var modal_col_data4_vis = $(this).data('modal_col_data4_vis');
				var modal_ret_data1 = $(this).data('modal_ret_data1');
				var modal_ret_data2 = $(this).data('modal_ret_data2');
				var modal_page_size = $(this).data('modal_page_size');
				var modal_page_type = $(this).data('modal_page_type');
				if (modal_page_size === undefined || modal_page_size == "") { modal_page_size = 10; }
				if (modal_page_type === undefined || modal_page_type == "") { modal_page_type = "simple"; }
				if (id_field_name_type === undefined || id_field_name_type == "") { id_field_name_type = "html"; }
				//Column Setting
				var cols = [{"data": modal_col_data1}, {"data": modal_col_data2} ];
				if (modal_col_data3 !== undefined && modal_col_data3 != "") { cols.push({"data": modal_col_data3,"visible": modal_col_data3_vis}); }
				if (modal_col_data4 !== undefined && modal_col_data4 != "") { cols.push({"data": modal_col_data4,"visible": modal_col_data4_vis}); }
				//
				input0.field_code = $("#"+id_field_code).val();
				//input0.login_plant = "<?php echo $login_plant;?>";
				
				$.ajax({
					url: modal_src,
					type: "POST",
					dataType: 'json',
					data: {param0: JSON.stringify(input0)},
					beforeSend: function () {
						$(".loading").fadeIn();
						$("#div_help").find("#div_help_size").attr("class", modal_class);
						$("#div_help").find("#help_title").html(modal_title);
						$("#div_help").find("#head0").html(modal_col_name);
						$("#div_help").modal({backdrop: 'static', keyboard: false});
						$('#table-help').DataTable().clear().destroy();
					},
					success: function (res) {
						//if (res.success) {
						$("#table-help").dataTable().fnDestroy();
						$("#table-help").dataTable({
							"oSearch": {
								"sSearch": input0.field_code
							},
							"dom": '<lf<t>ip>',
							"deferRender" : true,
							//"aaData" : res.data,
							"aaData" : res,
							"cache": false,
							"columns": cols,
							"columnDefs": [
							{"className": "dt-left","targets": [0,1]},
							{"width": "50px", "targets": 0},
							{"width": "100px", "targets":1},
							{
								"render": function(data, type, row) {
									return '<a href="javascript:void(0)" id="btn-ret-value"'+
									'" data-send_code="'+row[modal_ret_data1]+
									'" data-send_name="'+row[modal_ret_data2]+
									'" data-rec_code_id="'+id_field_code+
									'" data-rec_name_id="'+id_field_name+
									'" data-rec_name_type="'+id_field_name_type+'">'+data+'</a>';
								},
								"targets": 0
							},
							],
							"pagingType": modal_page_type,
							"pageLength": modal_page_size,
							"bPaginate": true,
							"bLengthChange": false,
							"bFilter": true,
							"bAutoWidth": false,
							"ordering": true,
							
						});
						$("#content-help").fadeIn();
						//}
					},
					complete: function () {
						$(".loading").fadeOut();
					},
					error: function (res) {
						alert('error');
					}
				});
			});
			
			//TYPE AHEAD
			$('.typeahead').typeahead({	
				displayText: function(item) {
					var disp_col1 = this.$element.attr('data-disp_col1');
					var disp_col2 = this.$element.attr('data-disp_col2');
					return item[disp_col1]+' '+item[disp_col2];
				}, 
				source: function (query, process) {
					var typeahead_src = this.$element.attr('data-typeahead_src')
					$.ajax({
						url: typeahead_src,
						data: {query:query},
						dataType: "json",
						type: "POST",
						success: function (data) {
							process(data)
						}
					})
				},				
				items: "all",
				afterSelect: function(item) {
					var ret_field_01 = this.$element.attr('data-ret_field_01')
					var ret_value_01 = this.$element.attr('data-ret_value_01')
					var ret_type_01 = this.$element.attr('data-ret_type_01')
					var ret_field_02 = this.$element.attr('data-ret_field_02')
					var ret_value_02 = this.$element.attr('data-ret_value_02')
					var ret_type_02 = this.$element.attr('data-ret_type_02')
					if (ret_type_01 == "val") {
						$('#'+ret_field_01).val(item[ret_value_01]);
						} else {
						$('#'+ret_field_01).html(item[ret_value_01]);
					}
					if (ret_type_02 == "val") {
						$('#'+ret_field_02).val(item[ret_value_02]);
						} else {
						$('#'+ret_field_02).html(item[ret_value_02]);
					}
				}
			});
			$('#beg_date_new').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			$('#end_date_new').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			function loadresult() {
				$('#div_result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
			}
			function clearloadresult() {
				$('#div_result').html("");
			}
			function showmsg(msg) {
				$("#modal-body").html(msg);
				$("#myModal").modal("show");
			}
		</script>
	</body>
</html>																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																									