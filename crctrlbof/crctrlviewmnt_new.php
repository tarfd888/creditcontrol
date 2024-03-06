<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";
include("chkauthcr.php");
include("chkauthcrctrl.php");

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key, $user_login)) {
		echo "System detect CSRF attack2!!";
		exit;
	}
}

$default_current_tab = "10";
$request_tab = $_GET['current_tab'];
if ($request_tab != "") {
	$current_tab = $request_tab;
	} else {
	$current_tab = $default_current_tab;
}

$curdate = date('d/m/Y');
clearstatcache();
	
$crstm_nbr = decrypt(html_escape($_REQUEST['crnumber']), $key);

$params = array($crstm_nbr);

$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_tax_nbr3, ".
				"crstm_mstr.crstm_address, crstm_mstr.crstm_district, crstm_mstr.crstm_amphur, crstm_mstr.crstm_province, crstm_mstr.crstm_zip, crstm_mstr.crstm_country, crstm_mstr.crstm_chk_rdo2, ".
				"crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cr1_img, ".
				"crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cr2_img, crstm_mstr.crstm_mgr_reson, crstm_mstr.crstm_mgr_rdo, crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_mgr_img, ".
				"emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_th_pos_name, emp_mstr.emp_manager_name, emp_mstr.emp_email_bus, emp_mstr.emp_tel_bus, ".
				"crstm_mstr.crstm_step_code, crsta_mstr.crsta_step_name, crstm_mstr.crstm_dbd_rdo, crstm_mstr.crstm_dbd_yy, crstm_mstr.crstm_dbd_img, crstm_mstr.crstm_dbd1_yy, ". 
				"crstm_mstr.crstm_dbd1_img, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, ".
				"crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_reson_img, crstm_mstr.crstm_pj_name, crstm_mstr.crstm_pj_prv, crstm_mstr.crstm_pj_term, crstm_mstr.crstm_pj_amt, ".
				"crstm_mstr.crstm_pj_dura, crstm_mstr.crstm_pj_beg, crstm_mstr.crstm_pj_img, crstm_mstr.crstm_pj1_name, crstm_mstr.crstm_pj1_prv, crstm_mstr.crstm_pj1_term, crstm_mstr.crstm_pj1_amt, ".
				"crstm_mstr.crstm_pj1_dura, crstm_mstr.crstm_pj1_beg, crstm_mstr.crstm_pj1_img, crstm_mstr.crstm_pre_yy, crstm_mstr.crstm_otd_pct, crstm_mstr.crstm_ovr_due, crstm_mstr.crstm_etc, ".
				"crstm_mstr.crstm_cur_yy, crstm_mstr.crstm_otd1_pct, crstm_mstr.crstm_ovr1_due, crstm_mstr.crstm_etc1, crstm_mstr.crstm_ins, crstm_mstr.crstm_create_by_cr1, ".
				"crstm_mstr.crstm_create_cr1_date, crstm_mstr.crstm_create_by_cr2, crstm_mstr.crstm_create_cr2_date, crstm_mstr.crstm_create_by_mgr, crstm_mstr.crstm_create_mgr_date, ".
				"crstm_mstr.crstm_reviewer, crstm_mstr.crstm_reviewer2, crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc, crstm_mstr.crstm_reviewer2, crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, ".
				"crstm_mstr.crstm_email_app3,crstm_mstr.crstm_stamp_app1, crstm_mstr.crstm_stamp_app2, crstm_mstr.crstm_stamp_app3, crstm_mstr.crstm_stamp_app1_date, crstm_mstr.crstm_stamp_app2_date, crstm_mstr.crstm_stamp_app3_date ".
				"FROM crstm_mstr INNER JOIN ".
				"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id INNER JOIN ".
				"crsta_mstr ON crstm_mstr.crstm_step_code = crsta_mstr.crsta_step_code ".
				"WHERE (crstm_mstr.crstm_nbr = ?)";

$result_detail = sqlsrv_query($conn,$query_detail,$params);
$rec_cus = sqlsrv_fetch_array($result_detail,SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$e_user_fullname = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
		$e_user_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
		$e_user_manager_name = html_clear($rec_cus['emp_manager_name']);
		$e_user_email = strtolower(html_clear($rec_cus['emp_email_bus']));
		$e_crstm_tel = html_clear($rec_cus['crstm_tel']);
		$crstm_cus_nbr = html_clear($rec_cus['crstm_cus_nbr']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_address = html_clear($rec_cus['crstm_address']);
		$crstm_district = html_clear($rec_cus['crstm_district']);
		$crstm_amphur = html_clear($rec_cus['crstm_amphur']);
		$crstm_province  = html_clear($rec_cus['crstm_province']);
		$crstm_zip  = html_clear($rec_cus['crstm_zip']);
		$crstm_country  = html_clear($rec_cus['crstm_country']);
		$crstm_tax_nbr3 = html_clear($rec_cus['crstm_tax_nbr3']);
		$crstm_address = $crstm_address." " .$crstm_district." ".$crstm_amphur." ".$crstm_province." ".$crstm_zip ;
		$rdo_cr_limit = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		$crstm_reson_img = html_clear($rec_cus['crstm_reson_img']);
		
		$crstm_pj_name = html_clear($rec_cus['crstm_pj_name']);
		$crstm_pj_amt = html_clear(number_format($rec_cus['crstm_pj_amt']));
		$crstm_pj_prv = html_clear($rec_cus['crstm_pj_prv']);
		$crstm_pj_term = html_clear($rec_cus['crstm_pj_term']);
		$crstm_pj_dura = html_clear($rec_cus['crstm_pj_dura']);
		$crstm_pj_beg = dmytx(html_clear($rec_cus['crstm_pj_beg']));
		$crstm_pj_img = html_clear($rec_cus['crstm_pj_img']);
		
		$crstm_pj1_name = html_clear($rec_cus['crstm_pj1_name']);
		$crstm_pj1_amt = html_clear(number_format($rec_cus['crstm_pj1_amt']));
		$crstm_pj1_prv = html_clear($rec_cus['crstm_pj1_prv']);
		$crstm_pj1_term = html_clear($rec_cus['crstm_pj1_term']);
		$crstm_pj1_dura = html_clear($rec_cus['crstm_pj1_dura']);
		$crstm_pj1_beg = dmytx(html_clear($rec_cus['crstm_pj1_beg']));
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
		$crstm_cr_mgr = html_clear(number_format($rec_cus['crstm_cr_mgr']));
		$crstm_mgr_img = html_clear($rec_cus['crstm_mgr_img']);
		
		$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
		$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
		$crstm_cc_amt = html_clear(number_format($rec_cus['crstm_cc_amt']));
		
		$crstm_create_by_cr1 = html_clear($rec_cus['crstm_create_by_cr1']);
		$crstm_create_cr1_date = dmytx(html_clear($rec_cus['crstm_create_cr1_date']));
		$crstm_create_by_cr2 = html_clear($rec_cus['crstm_create_by_cr2']);
		$crstm_create_cr2_date = dmytx(html_clear($rec_cus['crstm_create_cr2_date']));
		
		$crstm_term_add = html_clear($rec_cus['crstm_term_add']);
		$crstm_step_code = html_clear($rec_cus['crstm_step_code']);

		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_reviewer = html_clear($rec_cus['crstm_reviewer']);
		$crstm_reviewer2 = html_clear($rec_cus['crstm_reviewer2']);

		$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_stamp_app1_date = dmytx(html_clear($rec_cus['crstm_stamp_app1_date']));
		if($crstm_stamp_app1_date != ""){ $status_app1 = "<span style='color:green'><strong>*** Approved ***</strong></span>";}

		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$crstm_stamp_app2_date = dmytx(html_clear($rec_cus['crstm_stamp_app2_date']));
		if($crstm_stamp_app2_date != ""){ $status_app2 = "<span style='color:green'><strong>*** Approved ***</strong></span>";}
		
		$crstm_email_app3 = html_clear($rec_cus['crstm_email_app3']);
		$crstm_stamp_app3_date = dmytx(html_clear($rec_cus['crstm_stamp_app3_date']));
		if($crstm_stamp_app3_date != ""){ $status_app3 = "<span style='color:green'><strong>*** Approved ***</strong></span>";}
		
		if ($crstm_scgc == true) {
			$reviewercanedit = "readOnly";
			$pointer_vie2 = "none";
			$rev_block = "none";
			$_flag = "1";
			} else {
				$reviewercanedit = "";
				$_flag = "2";
				$rev_block = "block";	
				/* $params = array($_flag);
				$query_emp_detail = "SELECT * FROM reviewer_mstr where emp_flag = ? ";
				$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
				$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
				if ($rec_emp) {
					$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
					$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
					$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
					$emp_th_div = html_clear($rec_emp['emp_th_div']);
					$emp_th_dept = html_clear($rec_emp['emp_th_dept']);
					$emp_th_sec = html_clear($rec_emp['emp_th_sec']);
					$emp_th_pos_name = html_clear($rec_emp['emp_th_pos_name']);
					$reviewer_pos2 = "(". $emp_th_pos_name .")" ;
					$reviewer_name2 = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ."(". $emp_th_pos_name .")"  ;
					$crstm_reviewer2 = html_clear(strtolower($rec_emp['emp_email_bus']));
					$pointer_vie2 = "";
					} */
			}
			
			if($crstm_reviewer!=""){
				$reviewer_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_reviewer,$conn);	
			}
			if($crstm_reviewer2!=""){
				$reviewer_name2 = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_reviewer2,$conn);	
			}
			if($crstm_email_app1!=""){
				$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
			}
			if($crstm_email_app2!=""){
				$app2_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app2,$conn);	
			}
			if($crstm_email_app3!=""){
				$app3_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app3,$conn);	
			}
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
	$crstm_term_add = findsqlval("term_mstr", "term_desc", "term_code", $crstm_term_add,$conn);
	$crstm_status = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_step_code,$conn);

	switch ($crstm_approve) {
		case "ผส. อนุมัติ": 
				$pointer = "pointer"; $chk_block = "none";			
				break;
		case "ผฝ. อนุมัติ": 	
			$pointer = "pointer"; $chk_block = "none";	
				break;
		case "CO. อนุมัติ": 			
				$pointer = "none"; $chk_block = "none";
				break;
		case "กจก. อนุมัติ": 	
				$pointer = "none"; $chk_block = "none";
				break;
		case "คณะกรรมการสินเชื่ออนุมัติ": 	
				$pointer = "none"; $chk_block = "block";
				break;
		case "คณะกรรมการบริหารอนุมัติ": 	
				$pointer = "none"; $chk_block = "block";
				break;	
	}									
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description"
    content="">
  <meta name="keywords"
    content="">
  <meta name="author" content="PIXINVENT">
  <title><?php echo(TITLE) ?></title>
  <link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700"
    rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/toastr.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/extensions/toastr.min.css">
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns menu-collapsed   fixed-navbar" data-open="hover"
  data-menu="vertical-menu" data-col="2-columns">

  <div id="result"></div>
  <?php include("../crctrlmain/menu_header.php"); ?>
  <?php include("../crctrlmain/menu_leftsidebar.php"); ?>
  <?php include("../crctrlmain/modal.php"); ?>

  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row mt-n1">
        <div class="content-header-left col-md-6 col-12 mb-2">
          <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php">Home</a>
                </li>

                <li class="breadcrumb-item active"><a
                    href="crctrlviewmnt_new.php?crnumber=<?php echo encrypt($crstm_nbr, $key); ?>">Credit limit :
                    <?php echo $crstm_nbr; ?> </a>
                </li>
              </ol>
            </div>
          </div></br>
          <!-- <h3 class="content-header-title mb-0"><?php echo $crstm_nbr; ?></h3> -->
        </div>
        <?php if (substr($crstm_step_code,0,1) != 6 && $crstm_step_code != "42" && inlist($user_role,'ADMIN')) { ?>
        <div class="content-header-right col-md-6 col-12 mb-2">
          <div class="btn-group float-md-right">
            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"><i class="icon-settings mr-1"></i>Action</button>
            <div class="dropdown-menu arrow">
              <a class="dropdown-item blue" href="#div_frm_recall" data-toggle="modal"><i class="fa fa-undo mr-1"></i>
                Recall Email</a>
            </div>
          </div>
        </div>
        <? } ?>
      </div>
      <div class="content-body mt-n1">
        <!-- users edit start -->
        <section class="new-project">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header mt-1 pt-0 pb-1">
                  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
						<?php if (inlist("42",$crstm_step_code)) {  ?>
							<button class="btn btn-sm btn-danger btn-min-width btn-glow mr-1 mb-1"><i class="fa fa-bell"></i> เอกสารยกเลิก</button>
						<?php } ?>                                          
                      <li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all"
                            onclick="javascript:window.history.back();"></i></a></li>
                      <li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card-content collapse show font-small-3">
                  <div class="card-body" style="margin-top:-20px;">
                    <?php
												$spanred = "<span class='bg-red bg-accent-4 badge badge-pill'>";
												$spangrn = "<span class='bg-teal bg-accent-4 badge badge-pill'>";
												$span_end = "</span>";
											?>
                    <ul class="nav nav-tabs mb-2 mt-0" role="tablist">
                      <?php if ($current_tab == "10") { ?>
                      <?php $active = 'active'; ?>
                      <?php } else { ?>
                      <?php $active = ''; ?>
                      <?php } ?>
                      <li class="nav-item">
                        <a class="nav-link d-flex align-items-center <?php echo $active; ?>" id="project-tab"
                          data-toggle="tab" href="#project" aria-controls="project" role="tab" aria-selected="true">
                          <i class="fa fa-cube mr-25"></i><span class="d-none d-sm-block font-weight-bold">Document Info
                          </span>
                        </a>
                      </li>

                      <?php if ($current_tab == "20") { ?>
                      <?php $active = 'active'; ?>
                      <?php } else { ?>
                      <?php $active = ''; ?>
                      <?php } ?>
                      <li class="nav-item">
                        <?php if($crstm_cc1_reson<>"") { ?>
                        <a class="nav-link d-flex align-items-center <?php echo $active; ?>" id="customer-tab"
                          data-toggle="tab" href="#customer" aria-controls="customer" role="tab" aria-selected="false">
                          <i class="fa fa-user-o mr-25"></i><span class="d-none d-sm-block font-weight-bold">Credit
                            Control</span>
                        </a> <?php } ?>
                      </li>

                      <?php if ($current_tab == "30") { ?>
                      <?php $active = 'active'; ?>
                      <?php } else { ?>
                      <?php $active = ''; ?>
                      <?php } ?>
                      <li class="nav-item">
                        <?php if($crstm_stamp_app1_date !="" || $crstm_stamp_app2_date != "" || $crstm_stamp_app3_date != "") { ?>
                        <a class="nav-link d-flex align-items-center <?php echo $active; ?>" id="status-tab"
                          data-toggle="tab" href="#status" aria-controls="status" role="tab" aria-selected="false">
                          <i class="fa fa-envelope-o mr-25"></i><span class="d-none d-sm-block font-weight-bold">Status
                            Approve</span>
                        </a>
                        <?php } ?>
                      </li>
                    </ul>
                    <!-- Start Project Tab -->
                    <div class="tab-content">
                      <?php
													if ($current_tab == "10") {
														$active = 'active';
														} else {
														$active = '';
													}
												?>
                      <div class="tab-pane <?php echo $active; ?>" id="project" aria-labelledby="project-tab"
                        role="tabpanel">
                        <?php include("crctrl_header_new.php"); ?>
                      </div>

                      <?php
													if ($current_tab == "20") {
														$active = 'active';
														} else {
														$active = '';
													}
												?>
                      <div class="tab-pane <?php echo $active; ?>" id="customer" aria-labelledby="customer-tab"
                        role="tabpanel">
                        <?php include("crctrl_header_ar_new.php"); ?>
                      </div>

                      <?php
													if ($current_tab == "30") {
														$active = 'active';
														} else {
														$active = '';
													}
												?>
                      <div class="tab-pane <?php echo $active; ?>" id="status" aria-labelledby="status-tab"
                        role="tabpanel">
                        <?php include("crctrl_header_status_new.php"); ?>
                      </div>
                    </div>
                    <!-- End Project Tab -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- users edit ends -->
      </div>
    </div>
  </div>
  <!-- END: Content-->
  <div class="sidenav-overlay"></div>
  <div class="drag-target"></div>

  <!-- BEGIN: Footer-->
  <? include("../crctrlmain/menu_footer.php"); ?>
  
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/formatter/formatter.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/toastr.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-inputmask.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-maxlength.min.js"></script>
  <script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
  <script type="text/javascript" language="javascript" class="init">
  </body></
  html>	