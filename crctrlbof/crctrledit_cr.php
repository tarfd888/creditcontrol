<?php 
//Update can_editing
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
include("chkauthcr.php");
include("chkauthcrctrl.php");

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
$chk_block = "none";
$crstm_nbr = decrypt(html_escape($_REQUEST['crnumber']), $key);

$params = array($crstm_nbr);
$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_rdo2, ".
"crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_sd_per_mm, ".
"crstm_mstr.crstm_pj_name, crstm_mstr.crstm_pj_prv, crstm_mstr.crstm_pj_term, crstm_mstr.crstm_pj_amt, crstm_mstr.crstm_pj_dura, crstm_mstr.crstm_pj_beg, crstm_mstr.crstm_pj1_name, ".
"crstm_mstr.crstm_pj1_prv, crstm_mstr.crstm_pj1_term, crstm_mstr.crstm_pj1_amt, crstm_mstr.crstm_pj1_dura, crstm_mstr.crstm_pj1_beg, crstm_mstr.crstm_whocanread, ".
"crstm_mstr.crstm_curprocessor, cus_mstr.cus_name1, cus_mstr.cus_street, cus_mstr.cus_street2, cus_mstr.cus_street3, cus_mstr.cus_street4, cus_mstr.cus_street5, cus_mstr.cus_district, ".
"cus_mstr.cus_city, cus_mstr.cus_zipcode, cus_mstr.cus_tax_nbr3, cus_mstr.cus_terms_paymnt, cus_mstr.cus_acc_group, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, ".
"emp_mstr.emp_th_pos_name, emp_mstr.emp_manager_name, emp_mstr.emp_email_bus, emp_mstr.emp_tel_bus, term_mstr.term_desc, cus_mstr.cus_country, country_mstr.country_desc, ".
"crstm_mstr.crstm_pre_yy, crstm_mstr.crstm_otd_pct, crstm_mstr.crstm_ovr_due, crstm_mstr.crstm_etc, crstm_mstr.crstm_cur_yy, crstm_mstr.crstm_otd1_pct, crstm_mstr.crstm_ovr1_due, ".
"crstm_mstr.crstm_etc1, crstm_mstr.crstm_ins, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_step_code, crstm_mstr.crstm_cr1_img, crstm_mstr.crstm_dbd_rdo, crstm_mstr.crstm_dbd_yy, ".
"crstm_mstr.crstm_dbd_img, crstm_mstr.crstm_dbd1_yy, crstm_mstr.crstm_dbd1_img, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_pj_img, crstm_mstr.crstm_pj1_img, ".
"crstm_mstr.crstm_cr2_img, crstm_mstr.crstm_mgr_reson, crstm_mstr.crstm_mgr_rdo, crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_mgr_img, crstm_mstr.crstm_cc_date_beg, ". 
"crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_reson_img, crstm_mstr.crstm_rem_rearward, crstm_mstr.crstm_chk_rearward, crstm_mstr.crstm_detail_mail, ".
"crstm_mstr.crstm_reviewer, crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc, crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, crstm_mstr.crstm_email_app3,crstm_mstr.crstm_reviewer2 ".
"FROM crstm_mstr INNER JOIN ".
"cus_mstr ON crstm_mstr.crstm_cus_nbr = cus_mstr.cus_nbr INNER JOIN ".
"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id INNER JOIN ".
"term_mstr ON cus_mstr.cus_terms_paymnt = term_mstr.term_code INNER JOIN ".
"country_mstr ON cus_mstr.cus_country = country_mstr.country_code ".
"WHERE (crstm_mstr.crstm_nbr = ?)";

$result_detail = sqlsrv_query($conn, $query_detail,$params);
$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
if ($rec_cus) {
	$e_user_fullname = trim($rec_cus["emp_th_firstname"]) . " " . trim($rec_cus["emp_th_lastname"]);
	$e_user_th_pos_name = html_clear($rec_cus["emp_th_pos_name"]);
	$e_user_manager_name = html_clear($rec_cus["emp_manager_name"]);
	$e_user_email = strtolower(html_clear($rec_cus["emp_email_bus"]));
	$phone_mask = html_clear($rec_cus["crstm_tel"]);
	
	$cr_cust_code = html_clear($rec_cus['crstm_cus_nbr']);
	$crstm_cus_name = html_clear($rec_cus['cus_name1']);
	$cus_street = html_clear($rec_cus['cus_street']);
	$cus_street2 = html_clear($rec_cus['cus_street2']);
	$cus_street3 = html_clear($rec_cus['cus_street3']);
	$cus_street4 = html_clear($rec_cus['cus_street4']);
	$cus_street5 = html_clear($rec_cus['cus_street5']);
	$cus_district = html_clear($rec_cus['cus_district']);
	$cus_city = html_clear($rec_cus['cus_city']);
	$cus_country = html_clear($rec_cus['country_desc']);
	$cus_zipcode = html_clear($rec_cus['cus_zipcode']);
	$cus_street = $cus_street ." " . $cus_street2 ." ". $cus_street3 ." ". $cus_street4 ." ". $cus_street5 ." ". $cus_district ." ". $cus_city ." ". $cus_zipcode;
	$cus_tax_nbr3 = html_clear($rec_cus['cus_tax_nbr3']);
	
	//$cus_terms_paymnt = html_clear($rec_cus['term_desc']);
	$cus_terms_paymnt = html_clear($rec_cus['cus_terms_paymnt']);
	$term_desc = html_clear($rec_cus['term_desc']);
	$cus_terms_paymnt = $cus_terms_paymnt ." | ". $term_desc;
	
	$cus_acc_group = html_clear($rec_cus['cus_acc_group']);
	
	/// radio 
	$cus_conf_yes = html_clear($rec_cus['crstm_chk_rdo1']);
	$cusold_conf_yes = html_clear($rec_cus['crstm_chk_rdo2']);
	$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
	
	$term_desc_add = html_clear($rec_cus['crstm_term_add']);
	$term_desc = html_clear($rec_cus['crstm_ch_term']);
	
	$crstm_approve = html_clear($rec_cus['crstm_approve']);
	if($crstm_approve == "คณะกรรมการสินเชื่ออนุมัติ" || $crstm_approve =="คณะกรรมการบริหารอนุมัติ"){$chk_block = 'display';}
	
	$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
	$crstm_step_code = html_clear($rec_cus['crstm_step_code']);
	$crstm_sd_per_mm = number_format($rec_cus['crstm_sd_per_mm']);
	$crstm_reson_img = html_clear($rec_cus['crstm_reson_img']);
	
	$crstm_pj_name = html_clear($rec_cus['crstm_pj_name']);
	$crstm_pj_amt = number_format($rec_cus['crstm_pj_amt']);
	$crstm_pj_prv = html_clear($rec_cus['crstm_pj_prv']);
	$crstm_pj_term = html_clear($rec_cus['crstm_pj_term']);
	$crstm_pj_dura = html_clear($rec_cus['crstm_pj_dura']);
	
	$crstm_pj_beg = dmytx(html_clear($rec_cus['crstm_pj_beg']));
	$crstm_pj_img = html_clear($rec_cus['crstm_pj_img']);
	
	$crstm_pj1_name = html_clear($rec_cus['crstm_pj1_name']);
	$crstm_pj1_amt = number_format($rec_cus['crstm_pj1_amt']);
	$crstm_pj1_prv = html_clear($rec_cus['crstm_pj1_prv']);
	$crstm_pj1_term = html_clear($rec_cus['crstm_pj1_term']);
	$crstm_pj1_dura = html_clear($rec_cus['crstm_pj1_dura']);
	$crstm_pj1_beg = dmytx(html_clear($rec_cus['crstm_pj1_beg']));
	$crstm_pj1_img = $rec_cus['crstm_pj1_img'];
	
	// ข้อมูลฝั่งสินเชื่อ 
	//$crstm_cus_nbr = $rec_cus['crstm_cus_nbr'];
	$crstm_pre_yy = html_clear($rec_cus['crstm_pre_yy']);
	$crstm_otd_pct = html_clear($rec_cus['crstm_otd_pct']);
	$crstm_ovr_due = html_clear($rec_cus['crstm_ovr_due']);
	$crstm_etc = html_clear($rec_cus['crstm_etc']);
	$crstm_cur_yy = html_clear($rec_cus['crstm_cur_yy']);
	$crstm_otd1_pct = html_clear($rec_cus['crstm_otd1_pct']);
	$crstm_ovr1_due = html_clear($rec_cus['crstm_ovr1_due']);
	$crstm_etc1 = html_clear($rec_cus['crstm_etc1']);
	$crstm_ins = html_clear($rec_cus['crstm_ins']);
	$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
	$crstm_cr1_img = $rec_cus['crstm_cr1_img'];
	
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
	
	$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
	$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
	$crstm_cc_amt = number_format($rec_cus['crstm_cc_amt']);
	
	$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
	$crstm_reviewer2 = strtolower(html_clear($rec_cus['crstm_reviewer2']));
	$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
	if($crstm_noreviewer == false) {$checkshow = 'disabled';}
	
	$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
	$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
	$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
	$crstm_email_app3 = html_clear($rec_cus['crstm_email_app3']);
	
	$crstm_rem_rearward = html_clear($rec_cus['crstm_rem_rearward']);
	$crstm_chk_rearward = html_clear($rec_cus['crstm_chk_rearward']);
	$crstm_whocanread = html_clear($rec_cus['crstm_whocanread']);
	$crstm_detail_mail = html_clear($rec_cus['crstm_detail_mail']);
	if ($crstm_cr_mgr != "" ) {
		$crstm_cr_mgr = number_format($crstm_cr_mgr);
	}
	$crstm_mgr_img = $rec_cus['crstm_mgr_img'];
	
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
	if ($crstm_scgc == true) {
		$reviewercanedit = "readOnly";
		$pointer_vie2 = "none";
		$dis_rev2 = "none";
		$chk_rev2 = "none";
		
		$_flag = "1";
		} else {
				if($crstm_approve == "คณะกรรมการสินเชื่ออนุมัติ" || $crstm_approve == "คณะกรรมการบริหารอนุมัติ") {
					if($crstm_reviewer2!=""){
						$reviewer_name2 = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_reviewer2,$conn);	
					}
				}
				$dis_rev2 = "block";
				$chk_rev2 = "block";
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

$iscurrentprocessor = false;
$iscan_editing_c1 = false;
$iscan_editing_c2 = false;
$iscan_editing_c3 = false;
$iscan_display_c1 = false;

$iscan_display_c2 = false;
$iscan_display_c3 = false;
$iscan_display_sd = false;
if (inlist($crstm_curprocessor,$user_login)) {
	//ดูว่า User ที่ Login เข้าระบบมีในรายชื่อ CurrentProcessor หรือไม่ถ้ามีก็มีสิทธิ์ในการ Edit เอกสาร
	//เช่นคนสร้างเอกสาร,หรือผู้อนุมัติเอกสาร
	$iscurrentprocessor = true;
}
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
if (inlist('11,20,21,30,40',$crstm_step_code) && inlist($user_role,'Action_View2')) {
	$iscan_display_c2 = true;
}
if (inlist('21,30,40,41',$crstm_step_code) && inlist($user_role,'FinCR Mgr')) {
	$iscan_display_c3 = true;
}
if (inlist('0,01',$crstm_step_code) && inlist($user_role,'SALE_VIEW')) {
	$iscan_display_sd = true;
}

$iscan_editing = false;
if ($iscurrentprocessor && inlist('0',$crstm_step_code)) {
	$iscan_editing = true;
}


$params = array($crstm_nbr);
$query_detail = "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr where tbl1_nbr = ?";
$result = sqlsrv_query($conn, $query_detail,$params);
$rec_result = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if ($rec_result) {
	$stamp_date = $rec_result['tbl1_stamp_date'];
}

$params = array($crstm_nbr);	
$query_detail = "SELECT tbl2_id, tbl2_nbr, tbl2_cus_nbr, tbl2_amt_loc_curr, tbl2_doc_date, tbl2_create_by, tbl2_create_date, tbl2_stamp_date FROM tbl2_mstr where tbl2_nbr = ?";
$result = sqlsrv_query($conn, $query_detail,$params);
$rec_result = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if ($rec_result) {
	$stamp1_date = $rec_result['tbl2_stamp_date'];
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
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/toastr.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/extensions/toastr.min.css">
	</head>
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
		<div id="result"></div>
		<?php include("../crctrlmain/menu_header.php"); ?>
		<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
		<?php include("../crctrlmain/modal.php"); ?>
		<?php include("../crctrlmain/help_modal.php"); ?>
		<!-- BEGIN: Content-->
		<div class="app-content content">
			<div class="content-overlay"></div>
			<div class="content-wrapper">
				<div class="content-header row mt-n1">
					<div class="content-header-left col-md-6 col-12 mb-2">
						<div class="row breadcrumbs-top">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php"> Home</a></li>
									<li class="breadcrumb-item"><a href=""> ใบขออนุมัติวงเงินสินเชื่อ <?php echo $crstm_nbr; ?></a></li>
								</ol>
							</div>
						</div>
					</div> 
					<?php if (substr($crstm_step_code,0,1) != 6) { ?>
						<div class="content-header-right col-md-6 col-12 mb-2">
							<div class="btn-group float-md-right">
								<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-settings mr-1"></i>Action</button>
									<div class="dropdown-menu arrow">
										<a class="dropdown-item blue" href="#div_frm_recall" data-toggle="modal"><i class="fa fa-undo mr-1"></i> Recall Email</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item danger" href="#div_frm_reject" data-toggle="modal"><i class="fa fa-times-circle mr-1"></i>ยกเลิกใบขออนุมัติวงเงินสินเชื่อ</a>
								</div>
							</div>
						</div>
					<? } ?>
				</div>
				<!--<div class="content-header row">
					<div class="breadcrumb-wrapper col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php"> Home</a></li>
							<li class="breadcrumb-item"><a href=""> ใบขออนุมัติวงเงินสินเชื่อ <?php echo $crstm_nbr; ?></a></li>
						</ol>
					</div>
				</div>-->
				<div class="content-body font-small-3 mt-n1">
					<section class="new-project">
						<div class="row grouped-multiple-statistics-card">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title" id="basic-layout-form"> </h4>
										<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
										<div class="heading-elements">
											<ul class="list-inline mb-0">
												<li><a data-action="collapse"><i class="ft-minus"></i></a></li>
												<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
												<li><a data-action="expand"><i class="ft-maximize"></i></a></li>
												<li><a data-action="close"><i class="ft-x"></i></a></li>
											</ul>
										</div>
									</div>
									<div class="card-content collapse show">
										<div class="card-body" style="margin-top:-20px;">
											<FORM id="frm_crctrledit_cr" name="frm_crctrledit_cr" autocomplete=OFF method="POST" enctype="multipart/form-data">
												<input type=hidden name="action" value="crctrlapp_edit">
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
												<input type="hidden" name="cr_cust_code" value="<?php echo($cr_cust_code) ?>">
												<input type="hidden" name="crstm_nbr" value="<?php echo($crstm_nbr) ?>">
												<input type="hidden" name="phone_mask" value="<?php echo($phone_mask) ?>">
												<input type="hidden" name="crstm_whocanread" value="<?php echo($crstm_whocanread) ?>">
												<h4 class="form-section text-info"><i class="fa fa-user"></i> ผู้ขอเสนออนุมัติ</h4>
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label class="font-weight-bold">ชื่อ-สกุล :</label>
															<input type="text" id="e_user_fullname" name ="e_user_fullname" value="<?php echo $e_user_fullname ?>" class="form-control input-sm font-small-3" disabled >
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="font-weight-bold">หน่วยงาน : </label>
															<input type="text" id="e_user_th_pos_name" name ="e_user_th_pos_name" value="<?php echo $e_user_th_pos_name ?>" class="form-control input-sm font-small-3" disabled>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label class="font-weight-bold">ผู้บังคับบัญชา :</label>
															<input type="text" id="e_user_manager_name" name="e_user_manager_name" value="<?php echo $e_user_manager_name ?>" class="form-control input-sm font-small-3" disabled>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label class="font-weight-bold">E-mail:</label>
															<input type="text" id="e_user_email" name="e_user_email" value="<?php echo $e_user_email ?>" class="form-control input-sm font-small-3" disabled>
														</div>
													</div>
													<div class="col-md-4">
														<label class="font-weight-bold">เบอร์โทรศัพท์:</label>
														<class="text-muted font-weight-bold">(999) 999-9999 <font class="text text-danger font-weight-bold"> ***</font>
															<div class="form-group">
																<input type="text" class="form-control phone-inputmask form-control input-sm font-small-3" id="phone_mask" name="phone_mask" value="<?php echo $phone_mask ?>" placeholder="ระบุหมายเลขโทรศัพท์" / readonly>
															</div>
														</div>
													</div>
													<!-- End  Sales Register   -->
													
													<!-- Start Customber -->
													<h4 class="form-section text-info"><i class="fa fa-address-card-o"></i> ข้อมูลลูกค้า</h4>
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">รหัสลูกค้า :<font class="text text-danger font-weight-bold"> *</font></label>
																<? if($cr_cust_code !="") {?>	
																	<input type="text" id="cr_cust_code" name ="cr_cust_code" value="<?php echo $cr_cust_code ?>" class="form-control input-sm font-small-3" placeholder="พิมพ์ชื่อ หรือ รหัสลูกค้า" required>
																	<?php } 
																	else { ?>
																	<input type="text" id="cr_cust_code" name ="cr_cust_code" value="<?php echo $cr_cust_code ?>" class="form-control input-sm font-small-3" placeholder="พิมพ์ชื่อ หรือ รหัสลูกค้า" required>
																<?php } ?>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">ชื่อลูกค้า : </label>
																<input type="text" id="crstm_cus_name" name ="crstm_cus_name" value="<?php echo $crstm_cus_name ?>" class="form-control input-sm font-small-3">
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">เลขประจำตัวผู้เสียภาษี :</label>
																<input type="text" id="cus_tax_nbr3" name="cus_tax_nbr3" value="<?php echo $cus_tax_nbr3 ?>" class="form-control input-sm font-small-3">
															</div>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md-4">
															<fieldset class="form-group">
																<label for="placeTextarea" class="font-weight-bold">ที่อยู่ :</label>
																<textarea  name="cus_street" id="cus_street" class="form-control input-sm font-small-3"  id="placeTextarea" rows="3" placeholder="ที่อยู่" style="line-height:1.5rem;"> <?php echo $cus_street; ?></textarea>
															</fieldset>	
														</div>
														<div class="col-md-2">
															<div class="form-group">
																<label class="font-weight-bold">จังหวัด :</label>
																<input type="text" id="cus_city" name="cus_city" value="<?php echo $cus_city ?>" class="form-control input-sm font-small-3">
															</div>
														</div>
														<div class="col-md-2">
															<div class="form-group">
																<label class="font-weight-bold">ประเทศ :</label>
																<input type="text" id="cus_country" name="cus_country" value="<?php echo $cus_country ?>" class="form-control input-sm font-small-3">
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">เงื่อนไขการชำระเงิน :</label>
																<input type="text" id="cus_terms_paymnt" name="cus_terms_paymnt" value="<?php echo $cus_terms_paymnt ?>" class="form-control input-sm font-small-3">
															</div>
														</div>
														
													</div>
													<!-- End  Customber   -->
													<div class="row match-height detailcrc_display" > 
														<!-- Start First Column -->
														<div class="col-md-6">
															<div class="card">
																<!--<div class="card-header">
																	<p style="font-size:14px;">สถานะวงเงินและหนี้ ณ วันที่ :  <?echo $stamp_date; ?></p>
																	<a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-6"></i></a>
																</div>-->
																<!--<div class="card-body collapse show">-->
																<div class="card-block">
																	<div class="table-responsive" style="padding:0px 15px 50px 20px;">
																		<p style="font-size:14px;">    สถานะวงเงินและหนี้ ณ วันที่ :  <?echo $stamp_date; ?></p>
																		<input type="hidden" name="stamp_date" id="stamp_date" value="<?php echo($stamp_date) ?>">
																		<!-- Start Datatables -->
																		<!--class="table table-sm table-hover table-bordered compact nowrap-->
																		<table id="" class="table table-sm table-bordered compact nowrap " style="width:100%;" > <!--dt-responsive nowrap-->
																			<thead>
																				<tr class="bg-success text-white font-weight-bold">								
																					<th>สถานะวงเงินและหนี้ </th>
																					<th class="text-center" colspan='2'>จำนวนเงิน (บาท) </th>
																				</tr>
																			</thead>
																			<tbody>
																				<?
																					// ข้อมูลตารางที่ 1  ---> crctrlpost.php
																					
																					$params = array($crstm_nbr);
																					// $sql_cr= "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, ".
																					// "tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr WHERE (tbl1_nbr = ?) and tbl1_group='1'";
																					
																					$sql_cr= "SELECT tbl1_nbr, tbl1_date, tbl1_cus_nbr, sum(tbl1_amt_loc_curr)as amt,  tbl1_txt_ref, tbl1_acc_name, tbl1_group FROM tbl1_mstr WHERE (tbl1_nbr = ?) and tbl1_group='1' group by tbl1_nbr, tbl1_date, tbl1_cus_nbr,  tbl1_txt_ref, tbl1_acc_name, tbl1_group";																	
																					$result = sqlsrv_query($conn, $sql_cr,$params);
																					
																					$tot_acc = 0;
																					$sum_acc = 0;
																					$tot_cc = 0;
																					while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																					{
																						$tot_acc = number_format($row_cr['amt']);
																						$sum_acc = $row_cr['amt'];
																						$chk_name = $row_cr['tbl1_txt_ref'];
																						$acc_name = $row_cr['tbl1_acc_name'];
																						
																						if ($chk_name <> 'CI') {
																							echo "<tr><td align='left' >".$acc_name."</td>";
																							echo "<td colspan='1'></td>";
																							echo "<td align='right' >".$tot_acc."</td>";	
																							$tot_cc = $tot_cc + $sum_acc ;
																							}else { 
																							echo "<tr><td align='center'>".$acc_name."</td>";
																							echo "<td align='right'>".$tot_acc."</td >";	
																							echo "<td align='right' colspan='1'></td>";	
																						}
																						
																						echo "</tr>";
																					}
																					$grtot_acc = $tot_cc;
																					$tot_cc = number_format($tot_cc);
																					
																					$cc_txt = 'รวมวงเงินสินเชื่อ ';
																					//if ($tot_cc <> 0) {
																					echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$cc_txt."</td>";
																					echo "<td align='right' style='color:blue' colspan='2' bgcolor='#f2f2f2'>".$tot_cc."</td>";
																					//}
																				?>
																				
																				<?
																					// $params = array($cr_cust_code);
																					// $sql_ar ="SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, sum(ar_mstr.ar_amt_loc_curr) as ar_amt ".
																					// "FROM ar_mstr INNER JOIN cracc_mstr ON ar_mstr.ar_acc = cracc_mstr.cracc_customer INNER JOIN ".
																					// "cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr  where cracc_mstr.cracc_acc= ? group by cracc_mstr.cracc_acc,cus_name1";
																					$params = array($crstm_nbr);
																					$sql_ar= "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, ".
																					"tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr WHERE (tbl1_nbr = ?) and tbl1_group='2'";
																					$result = sqlsrv_query($conn, $sql_ar, $params, array("Scrollable" => 'keyset'));
																					$row_counts = sqlsrv_num_rows($result);
																					
																					$tot_ar = 0;
																					$sum_ar = 0;
																					while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																					{
																						$tot_ar = $row_ar['tbl1_amt_loc_curr'];
																						if ($tot_ar < 0) {
																							$tot_ar = ($tot_ar * -1);
																							$tot_ar = "(".(number_format($tot_ar)).")";
																							}else {
																							$tot_ar = number_format($row_ar['tbl1_amt_loc_curr']);
																						}
																						$sum_ar = round($row_ar['tbl1_amt_loc_curr']);
																						$ar_txt = 'หนี้ทั้งหมด ' ;
																						echo "<tr><td>".$ar_txt."</td>";
																						echo "<td colspan='1'></td>";
																						echo "<td align='right'>".$tot_ar."</td>";	
																						echo "</tr>";
																					}
																					
																					if ($row_counts==0) {     
																						$tot_ar = ($tot_ar * -1);
																						$ar_txt = 'หนี้ทั้งหมด  ' ;
																						echo "<tr><td>".$ar_txt."</td>";
																						echo "<td colspan='1'></td>";
																						echo "<td align='right'>".$row_counts."</td>";	
																						echo "</tr>";
																					}
																				?>
																				
																				<?
																					// $params = array($cr_cust_code);
																					// $sql_ar= "SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, ar_mstr.ar_dura_txt, sum(ar_mstr.ar_amt_loc_curr) as ar_amt ".
																					// "FROM ar_mstr INNER JOIN cracc_mstr ON ar_mstr.ar_acc = cracc_mstr.cracc_customer INNER JOIN ".
																					// "cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr where cracc_mstr.cracc_acc = ? ".
																					// "group by cracc_mstr.cracc_acc,ar_mstr.ar_dura_txt, cus_mstr.cus_name1 ";
																					$params = array($crstm_nbr);
																					$sql_ar ="SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, ".
																					"tbl1_acc_name, tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr ".
																					"WHERE (tbl1_nbr = ?) AND (tbl1_group = '3')";
																					$result = sqlsrv_query($conn, $sql_ar,$params);
																					
																					$tot_cur = 0;
																					$sum_cur = 0;
																					while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																					{
																						$tot_cur = $row_ar['tbl1_amt_loc_curr'];
																						if ($tot_cur < 0) {
																							$tot_cur = ($tot_cur * -1);
																							$tot_cur = "(".(number_format($tot_cur)).")";
																							}else {
																							$tot_cur = number_format($row_ar['tbl1_amt_loc_curr']);
																						}
																						
																						$sum_cur = $row_ar['tbl1_amt_loc_curr'];
																						$cur_txt = $row_ar['tbl1_txt_ref'];
																						
																						echo "<tr><td align='center'>".$cur_txt."</td>";
																						echo "<td align='right'>".$tot_cur."</td>";	
																						echo "<td align=center'></td>";	
																						echo "</tr>";
																					}
																				?>
																				
																				<?
																					//$params = array($cr_cust_code);
																					//$sql_ord ="SELECT  ord_cr_acc, SUM(ord_mstr.ord_sales_val) AS sales_val FROM ord_mstr  WHERE (ord_cr_acc = ? ) group by ord_cr_acc";
																					$params = array($crstm_nbr);
																					$sql_ord ="SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, ".
																					"tbl1_acc_name, tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr ".
																					"WHERE (tbl1_nbr = ?) AND (tbl1_group = '4')";
																					$result = sqlsrv_query($conn, $sql_ord,$params);
																					
																					$tot_ord = 0;
																					$sum_ord = 0;
																					$grand_ord = 0;
																					while($row_ord = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																					{
																						
																						$tot_ord = $row_ord['tbl1_amt_loc_curr'];
																						if ($tot_ord < 0) {
																							$tot_ord = ($tot_ord * -1);
																							$tot_ord = "(".(number_format($tot_ord)).")";
																							}else {
																							$tot_ord = number_format($row_ord['tbl1_amt_loc_curr']);
																						}
																						
																						$sum_ord = round($row_ord['tbl1_amt_loc_curr']);
																						$ord_txt = 'ใบสั่งซื้อระหว่างดำเนินการ' ;
																						echo "<tr><td>".$ord_txt."</td>";
																						echo "<td colspan='1'></td>";
																						echo "<td align='right'>".$tot_ord."</td>";	
																						echo "</tr>";
																					}
																					
																					$grand_ord = $sum_ord + $sum_ar;
																					$sumgr_ord =  $sum_ord + $sum_ar;
																					
																					if($grand_ord < 0) {
																						$grand_ord = ($grand_ord * -1);
																						$grand_ord = "(".(number_format($grand_ord)).")";
																						}else {
																						$grand_ord = number_format($grand_ord);
																						
																					}	
																					
																					$grand_txt = 'รวมยอดใช้วงเงิน';
																					echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																					echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$grand_ord."</td>";
																					echo "</tr>";
																					if($grtot_acc > 0) {
																						$grand_lmt = $grtot_acc - $sumgr_ord ; //  ยอด $grtot_acc +
																						}else {
																						$grand_lmt = $sumgr_ord ; // ถ้ายอด  $grtot_acc เป็นลบ เอายอด $sumgr_org มาแสดง
																					}
																					if ($grand_lmt < 0) {
																						$grand_txt = '(เกิน) วงเงิน';
																						$grand_lmt = ($grand_lmt * -1) ;
																						$grand_lmt = "(".(number_format($grand_lmt)).")";
																						echo "<tr><td align='center' style='color:red' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																						echo "<td align='right' style='color:red' bgcolor='#f2f2f2' colspan='2'>".$grand_lmt."</td>";
																						} else {
																						$grand_txt = 'คงเหลือวงเงิน';
																						$grand_lmt = number_format($grand_lmt);
																						echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																						echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$grand_lmt."</td>";
																					}
																					
																					$gr_per = 0;
																					$grand_txt = '% การใช้วงเงิน';
																					//if ($sumgr_ord > 0 || $grtot_acc > 0) {
																					if ($grtot_acc > 0) {
																						$gr_per = ($sumgr_ord / $grtot_acc ) * 100 ;
																						} else {
																						$gr_per = '0';
																					}
																					$gr_per = number_format($gr_per);
																					echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																					echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$gr_per." % </td>";
																					echo "</tr>";
																				?>
																				
																			</tbody>
																		</table>
																	</div>								
																</div>
																
																<!--</div>-->
															</div>
														</div>
														<!-- End First Column -->	
														
														<div class="col-md-6">
															<div class="card">
																<!--<div class="card-header">
																	<p style="font-size:14px;">ประวัติการซื้อสินค้า 12 เดือนที่ผ่านมา ณ วันที่  <?echo $stamp1_date; ?></p>
																	<a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-6"></i></a>
																</div>-->
																<!--<div class="card-body collapse show">-->
																<div class="card-block">
																	<div class="table-responsive" style="padding:0px 15px 50px 20px;">
																		<p style="font-size:14px;">ประวัติการซื้อสินค้า 12 เดือนที่ผ่านมา ณ วันที่  <?echo $stamp1_date; ?></p>
																		<input type="hidden" name="stamp1_date" id="stamp1_date" value="<?php echo($stamp1_date) ?>">
																		<!-- Start Datatables -->
																		<table id="" class="table table-sm table-hover table-bordered compact nowrap" style="width:100%;" > 
																			<thead class="text-center">
																				<tr class="bg-warning text-white font-weight-bold">								
																					<th>ปี - เดือน</th>
																					<th class="text-center">ยอด Billing (บาท)</th>
																				</tr>
																			</thead>
																			<tbody>
																				<?
																					$params = array($crstm_nbr);
																					// $sql_bll= "SELECT TOP 12 cus_mstr.cus_name1, bll_mstr.bll_ym, sum(bll_mstr.bll_amt_loc_curr) as amt, cracc_mstr.cracc_acc ".
																					// "FROM bll_mstr INNER JOIN cracc_mstr ON bll_mstr.bll_acc = cracc_mstr.cracc_customer INNER JOIN ".
																					// "cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr WHERE (cracc_mstr.cracc_acc = ?) ".
																					// "group by bll_mstr.bll_ym,cus_mstr.cus_name1,cracc_mstr.cracc_acc order by bll_ym  desc  ";
																					
																					$sql_bll= "SELECT TOP (12) tbl2_id, tbl2_nbr, tbl2_cus_nbr, tbl2_amt_loc_curr, tbl2_doc_date, tbl2_create_by, tbl2_create_date ".
																					"FROM tbl2_mstr WHERE (tbl2_nbr = ?) ORDER BY tbl2_doc_date DESC ";
																					$result_bll = sqlsrv_query($conn, $sql_bll,$params);
																					
																					$bll_tot = 0 ;
																					$no = 0 ;
																					$max_tot = 0;
																					$a_max = array();
																					while($row_bll = sqlsrv_fetch_array($result_bll, SQLSRV_FETCH_ASSOC))
																					{
																						$tot_amt = $row_bll['tbl2_amt_loc_curr'];
																						$tot_ord = number_format($row_bll['tbl2_amt_loc_curr']);
																						$bll_ym = $row_bll['tbl2_doc_date'];
																						$bll_doc_ym1 = substr($bll_ym,0,4);
																						$bll_doc_ym2 = substr($bll_ym,5,2);
																						
																						$a_max[$bll_ym] = $tot_amt;	  //หาค่า max 
																						
																						echo "<td align='center'>".$bll_doc_ym1.'-'.$bll_doc_ym2."</td>";
																						echo "<td align='right'>".$tot_ord."</td>";									
																						echo "</tr>";
																						$bll_tot = $bll_tot + $tot_amt ;
																						$no = $no + 1;
																					}
																					if ($bll_tot != 0 ) {
																						$bll_avr = $bll_tot / $no ;
																						$max_tot = max($a_max);
																					}
																					
																					$bll_tot = number_format($bll_tot);
																					$bll_avr = number_format($bll_avr);
																					$acc_txt = 'Total';
																					$acc_avr = 'Average';
																					$acc_max = 'Max';
																					
																					echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_txt."</td>";
																					echo "<td align='right' style='color:blue' bgcolor='#f2f2f2'>".$bll_tot."</td>";
																					echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_avr."</td>";
																					echo "<td align='right' colspan='2' style='color:blue' bgcolor='#f2f2f2'>".$bll_avr."</td>";
																					echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_max."</td>";
																					echo "<td align='right' colspan='2' style='color:blue' bgcolor='#f2f2f2'>".number_format($max_tot)."</td>";
																					echo "</tr>";
																				?>
																			</tbody>
																		</table>
																	</div>								
																</div>
															</div>
														</div>
													</div>
													
													<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 1. สำหรับหน่วยงานขาย (เสนอขออนุมัติวงเงินสินเชื่อ)</h4>
													<div class="row">
														<div class="col-md-3">
															<input type="radio"  id="cus_conf_no" name="cus_conf" value="0" disabled <?php if($cus_conf_yes=='0'){ echo "checked"; }?>>
															<label class="font-weight-bold" for="cus_conf_no"> วงเงินลูกค้าใหม่</label>
														</div>	
														<div class="col-md-3">
															<input type="radio" id="cus_conf_yes" name="cus_conf" value="1" disabled <?php if($cus_conf_yes=='1'){ echo "checked"; }?>>
															<label class=" font-weight-bold" for="cus_conf_yes"> วงเงินลูกค้าเก่า</label>
														</div>
													</div>
													<div class="row">
														<div class="col-md-3">
															<input type="radio"  id="cusold_conf_yes" name="chk_rdo" value="C1" disabled <?php if($cusold_conf_yes=='C1') { echo "checked"; }?>>
															<label class="font-weight-bold" for="cusold_conf_yes"> ปรับเพิ่มวงเงิน</label>
														</div>	
														<!--<div class="col-md-3">
															<input type="radio"  id="cusold1_conf_yes" name="chk_rdo" value="C2" disabled <?php if($cusold_conf_yes=='C2') { echo "checked"; }?>>
															<label class=" font-weight-bold" for="cusold_conf_yes"> ปรับลดวงเงิน</label>
														</div>-->
														<div class="col-md-3">
															<input type="radio"  id="cusold2_conf_yes" name="chk_rdo" value="C3" disabled <?php if($cusold_conf_yes=='C3') { echo "checked"; }?>>
															<label class=" font-weight-bold" for="cusold_conf_yes"> ต่ออายุวงเงิน</label>
														</div>
													</div>
													<div class="row">
														<div class="col-md-3">
															<input type="radio"  id="term_conf_yes" name="rdo_conf1" value="old" disabled <?php if($crstm_chk_term=='old'){ echo "checked"; }?>>
															<label class="font-weight-bold" for="cus_conf_yes">เงื่อนไขการชำระเงินเดิม</label>
														</div>
														<div class="col-md-3">
															<input type="radio"  id="chg_term_conf_yes" name="rdo_conf1" value="change" disabled <?php if($crstm_chk_term=='change'){ echo "checked"; }?>>
															<label class="font-weight-bold" for="cus_conf_yes">เปลี่ยนเงื่อนไขการชำระเงินใหม่จาก</label>
														</div>
													</div>
													<div class="row">
														<div class="col-md-3">
															<fieldset>
																<label for="check_same" class="font-weight-bold">เงื่อนไขการชำระเงินเดิม:</label>
															</fieldset>
														</div>
														<div class="col-md-3">
															<input type="text" id="terms_paymnt" name="terms_paymnt" class="form-control input-sm font-small-3" value="<?php echo $cus_terms_paymnt ?>" readonly>
														</div>
														<div class="col-md-3">
															<fieldset>
																<label class="font-weight-bold">โปรดระบุเพิ่ม: (ถ้ามี)</label>
															</fieldset>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<!--<label class="font-weight-bold">เปลี่ยนจาก</label>-->
																<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="term_desc_add" name="term_desc_add" disabled >
																	<option value="" selected>--- เลือกเงื่อนไขการชำระเงินเพิ่ม ---</option>
																	<?php
																		$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
																		$result_doc = sqlsrv_query($conn, $sql_doc);
																		while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																		?>
																		<option value="<?php echo $r_doc['term_code']; ?>" 
																		<?php if ($term_desc_add == $r_doc['term_code']) {
																			echo "selected";
																		} ?>>
																		<?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													</div>				
													<div class="row">
														<div class="col-md-3">
															<fieldset>
																<label for="check_same" class="font-weight-bold">ขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก:</label>
															</fieldset>
														</div>
														<div class="col-md-3">
															<input type="text" id="terms_paymnt1" name="terms_paymnt1" class="form-control input-sm font-small-3" value="<?php echo $cus_terms_paymnt ?>" readonly>
														</div>
														<div class="col-md-2">
															<fieldset>
																<label class="font-weight-bold">เปลี่ยนเงื่อนไข:</label>
															</fieldset>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="term_desc" name="term_desc" disabled>
																	<option value="" selected>--- เลือกเงื่อนไขการชำระเงินใหม่ ---</option>
																	<?php
																		$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
																		$result_doc = sqlsrv_query($conn, $sql_doc);
																		while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																		?>
																		<option value="<?php echo $r_doc['term_code']; ?>" 
																		<?php if ($term_desc == $r_doc['term_code']) {
																			echo "selected";
																		} ?>>
																		<?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
																	<?php } ?>
																</select>
																
															</div>
														</div>
														
													</div>
													<div class="form-group row">
														<label class="col-md-3 label-control font-weight-bold" for="userinput1">ประมาณการขายเฉลี่ยต่อเดือน: <font class="text text-danger font-weight-bold"> *</font></label>
														<div class="col-md-3">
															<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_sd_per_mm" class="form-control input-sm " name="crstm_sd_per_mm" value="<?php echo $crstm_sd_per_mm ?>" readonly style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)" ></a>
														</div>
														<label class="col-md-2 label-control font-weight-bold" for="userinput1">บาท</label>
													</div>
													<div class="col-sm-9">
														<!--<div class="card">-->
														<!--<div class="card-block">-->
														<div class="table-responsive">
															<!-- Start Datatables -->
															<table id="tb_ord" class="table table-sm table-hover table-bordered compact nowrap" style="width:100%;">
																<thead class="text-center" style="background-color:#f1f1f1;">
																	<tr class="bg-info text-white font-weight-bold">
																		<th rowspan="2" class="align-middle" style="width:20%;">ขออนุมัติปรับวงเงินสินเชื่อ<br></br> (Clean Credit)</th>
																		<th colspan="2" class="align-middle" style="width:40%;">อายุวงเงิน</th>
																		<th rowspan="2" class="align-middle" style="width:20%;">วงเงิน (บาท)</th>
																		<!--<th rowspan="2" class="align-middle" style="width:10%;">Action</th>-->
																	</tr>
																	<tr class="bg-info text-white font-weight-bold" style="line-height:30px;">
																		<th style="width:10%;">วันที่เริ่ม</th>
																		<th style="width:10%;">วันที่สิ้นสุด</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																		$n = 0;													
																		$params = array($crstm_nbr);
																		/////C3  เคสต่ออายุวงเงิน 04/08/2564
																		// if($cusold_conf_yes=='C3') {
																		// $sql_cc= "SELECT crlimit_acc, sum(crlimit_amt_loc_curr) as amt_loc, crlimit_doc_date,crlimit_due_date,crlimit_txt_ref, crlimit_seq FROM crlimit_mstr WHERE(crlimit_acc = ? and crlimit_ref = 'CC' or crlimit_ref='C3') ".
																		// "GROUP BY crlimit_acc, crlimit_doc_date,crlimit_due_date,crlimit_txt_ref, crlimit_seq order by crlimit_due_date desc";
																		// }else{
																		$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? ";
																		//}
																		$result_cc = sqlsrv_query($conn, $sql_cc,$params);
																		$tot_ord = 0 ;
																		$rows = 0;
																		while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
																		{
																			$acc_txt = "วงเงินปัจจุบัน";
																			$acc_tot_txt = "รวมวงเงินขออนุมัติ";
																			$rows = $rows + 1;
																			///// เคสต่ออายุวงเงิน 04/08/2564
																			// if($cusold_conf_yes=='C3') {
																			// $tot_ord = number_format($row_cc['amt_loc']);
																			// $sum_ord = $row_cc['amt_loc'];
																			// $txt_ref = $row_cc['crlimit_txt_ref'];
																			// $doc_date = dmytx($row_cc['crlimit_doc_date']);
																			// $due_date = dmytx($row_cc['crlimit_due_date']);
																			// $row_seq = $row_cc['crlimit_seq'];
																			
																			// }else {
																			$tot_ord = number_format($row_cc['tbl3_amt_loc_curr']);
																			$sum_ord = $row_cc['tbl3_amt_loc_curr'];
																			$txt_ref = $row_cc['tbl3_txt_ref'];
																			$doc_date = dmytx($row_cc['tbl3_doc_date']);
																			$due_date = dmytx($row_cc['tbl3_due_date']);
																			$row_seq = $row_cc['tbl3_id'];
																			///}	
																			$acc_tot = $acc_tot + $sum_ord ;
																			
																			//$acc_txt = "วงเงินปัจจุบัน";
																			$acc_tot_txt = "รวมวงเงินขออนุมัติ";
																			if ($txt_ref == "C1") {
																				$acc_txt = "เสนอขอปรับเพิ่มวงเงิน";
																				} else if ($txt_ref == "C2") {
																				$acc_txt = "เสนอขอปรับลดวงเงิน";
																				} else if ($txt_ref == "C3") {
																				$acc_txt = "เสนอขอต่ออายุวงเงิน";	
																				} else {
																				$acc_txt = "วงเงินปัจจุบัน";
																			}
																			
																			$n++;																										
																		?>
																		<tr class="black">
																			<td class="pl-1 pr-0 text-center"><?php echo $acc_txt; ?></td>
																			<td class="pl-1 pr-0 text-center"><?php echo $doc_date; ?></td>	
																			<td class="pl-1 pr-0 text-center"><?php echo $due_date; ?></td>
																			<td class="pl-1 pr-1 text-right"><?php echo $tot_ord; ?></td>
																		</tr>
																		
																		
																	<?php }?>	
																	<?php 
																		//$acc_tot_app = $acc_tot;
																		//$acc_tot = number_format($acc_tot);
																		
																		$sum_acc_tot = number_format($acc_tot);
																		$acc_tot = number_format($acc_tot);
																		if($cusold_conf_yes=='C1') {
																			$txt_ccr = "เสนอขอปรับเพิ่มวงเงิน";
																			}else if($cusold_conf_yes=='C3') {
																			$txt_ccr = "เสนอขอต่ออายุวงเงิน";	
																		}
																	?>
																	
																	<!--<tr class="black">
																		<td class="pl-1 pr-1"><input type="text" name="txt_ccr" id="txt_ccr" value="<?php echo $txt_ccr ?>" class="form-control form-control input-sm font-small-3" style="color:green;text-align:center" readonly> </td>
																		<td class="pl-1 pr-1"><input type="text" name="beg_date1" id="beg_date1" class="form-control date-inputmask form-control input-sm font-small-3" style="color:green;text-align:center" value="<?php echo $crstm_cc_date_beg ?>" readonly></td>
																		<td class="pl-1 pr-1"><input type="text" name="end_date1" id="end_date1" class="form-control date-inputmask form-control input-sm font-small-3" style="color:green;text-align:center" value="<?php echo $crstm_cc_date_end ?>" readonly> </td>
																		<td class="pl-1 pr-1"><input type="text" name="cc_amt1" id="cc_amt1" class="form-control form-control input-sm font-small-3" style="color:green;text-align:right" readonly value="<?php echo $crstm_cc_amt ?>" onkeyup="format(this)" onchange="format(this)"> </td>
																	</tr>-->
																	<!--<tr class="black">		
																		<?php if($acc_tot<>0) { ?>
																			<td align='center' colspan='3' style='color:blue'><?php echo $acc_tot_txt; ?></td>
																			<td class="pl-1 pr-1 text-right" style='color:blue'><?php  echo $acc_tot; ?></td>
																		<?php } ?>
																	</tr>-->
																	<input type="hidden" name="acc_tot" id="acc_tot" value="<?php echo $acc_tot ?>" class="form-control form-control input-sm font-small-3" style="color:green;text-align:right">
																	
																	<?php if ($crstm_cc_amt!="") {?>
																		<?php
																			/* $crstm_cc_amt = str_replace(',','', $crstm_cc_amt);
																				$acc_tot = str_replace(',','', $acc_tot);
																				$sum_acc_tot = $acc_tot + $crstm_cc_amt ; 
																			$sum_acc_tot = number_format($sum_acc_tot); */
																		?>
																	<? } ?>
																	
																	<tr class="black">		
																		<td align='center' colspan='3' style='color:blue'>รวมวงเงินขออนุมัติ</td>
																		<!--<td class="pl-1 pr-1"><input type="text" name="sum_acc_tot" id="sum_acc_tot" value="<?php echo $sum_acc_tot ?>" class="form-control form-control input-sm font-small-3" style="color:green;text-align:right" readonly></td>-->
																		<td class="pl-1 pr-1 text-right"><?php echo $sum_acc_tot ?></td>
																	</tr>
																	<?php 
																		$sum_acc_tot = str_replace(',','',$sum_acc_tot);
																		$crstm_cr_mgr = number_format($sum_acc_tot);
																		$acc_tot_app = $sum_acc_tot;
																		
																	?>	
																</tbody>
															</table>
														</div>	
													</div>
													<!-- End Table Clean Credit -->
													<!--- เช็คอำนาจดำเนินการขออนุมัติวงเงิน --->
													<? if ($cus_acc_group == "ZC01" || $cus_acc_group == "ZC07") {
														if ($acc_tot_app  <= 700000) { 
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
																$canedit = "readOnly";
																$error_txt ="";
																$pointer = "none";															
															}else if ($acc_tot_app >= 5000001 && $acc_tot_app <= 7000000) { 
																$crstm_approve = 'กจก. อนุมัติ';
																$error_txt ="";
																$pointer = "none";																				
																$canedit = "readOnly";
															}else if ($acc_tot_app >= 7000001 && $acc_tot_app <= 10000000) { 
																$crstm_approve = 'คณะกรรมการสินเชื่ออนุมัติ';
																$canedit = "readOnly";
																$error_txt ="";
																$pointer = "none";	
																//$dis_rev2 = "none";														
															
															}else { 
																$crstm_approve = 'คณะกรรมการบริหารอนุมัติ';	
																$canedit = "readOnly";
																$error_txt ="";
																$pointer = "none";	
																//$dis_rev2 = "none";																
															} 
														} ?>
														<? if ($cus_acc_group == "DREP") {
															if ($acc_tot_app >= 1 && $acc_tot_app  <= 500000) { 
																	$crstm_approve = 'ผผ. อนุมัติ';	
																	$canedit = "";
																	$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
																	$pointer = "pointer";
																}else if ($acc_tot_app >= 500001 && $acc_tot_app <= 3000000) { 
																	$crstm_approve = 'ผส. อนุมัติ';	
																	$canedit = "";
																	$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
																	$pointer = "pointer";
																}else if ($acc_tot_app >= 3000001 && $acc_tot_app <= 13000000) { 
																	$crstm_approve = 'ผฝ. อนุมัติ';
																	$canedit = "";
																	$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
																	$pointer = "pointer";
																}else if ($acc_tot_app >= 13000001 && $acc_tot_app <= 25000000) { 
																	$crstm_approve = 'CO. อนุมัติ';
																	$canedit = "readOnly";
																	$error_txt ="";
																	$pointer = "none";																
																}else if ($acc_tot_app >= 25000001 && $acc_tot_app <= 50000000) { 
																	$crstm_approve = 'กจก. อนุมัติ';
																	$canedit = "readOnly";
																	$error_txt ="";
																	$pointer = "none";																
																}else { 
																	$crstm_approve = 'คณะกรรมการบริหารอนุมัติ';	
																	$canedit = "readOnly";
																	$error_txt ="";
																	$pointer = "none";																
																} 
														} ?>
														<!--- เช็คอำนาจดำเนินการขออนุมัติวงเงิน --->
														<div class="row">
															<div class="col-md-12">
																<div class="form-group row">
																	<label class="col-md-3 label-control font-weight-bold" for="userinput1">อำนาจดำเนินการอนุมัติวงเงิน:</label>
																	<div class="col-md-3">
																		<input type="text" name="crstm_approve" id="crstm_approve" value="<?php echo $crstm_approve ?>" class="form-control input-sm font-small-3" readonly>
																	</div>
																</div>
															</div>
															
															<div class="col-md-3">
																<label class="font-weight-bold" for="cus_conf_yes">Group:</label>
															</div>
															<div class="col-md-2">
																<input type="radio" name="crstm_scgc" id="crstm_scgc" value="true" disabled <?php if ($crstm_scgc==true){ echo "checked"; }?>>
																<label class="font-weight-bold">Tiles</label>
															</div>
															<div class="col-md-2">
																<input type="radio" name="crstm_scgc" id="crstm_scgc" value="false" disabled <?php if ($crstm_scgc==false){ echo "checked"; }?>>
																<label class="font-weight-bold">Geoluxe</label>
															</div>
															<div class="col-md-5"></div>
															
															
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา 1 : </label>
																	<div class="col-md-6">
																		<div class="input-group input-group-sm">
																			<input name="crstm_reviewer" id="crstm_reviewer" readonly value="<?php echo $crstm_reviewer ?>" 
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
																					class="input-group-append" style="pointer-events: none">
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
																		<input type="checkbox" class="form-control input-sm border-warning " name="isreviewer" id="isreviewer" <?php echo $checkshow; ?> <?php if ($crstm_noreviewer==true){ echo "checked"; }?> value="true" onclick="this.checked=true;">
																	</div>
																	<label class="col-md-4 label-control" for="userinput1">กรณีไม่ระบุผู้ตรวจสอบ :</label>
																</div>
															</div>
														
															<div class="col-md-6 displayRev2" style="display:<?php echo $chk_rev2 ?>">
																<div class="form-group row">
																	<label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา 2 : </label>
																	<div class="col-md-6">
																		<div class="input-group input-group-sm">
																			<input name="crstm_reviewer2" id="crstm_reviewer2" readonly value="<?php echo $crstm_reviewer2 ?>" 
																			data-disp_col1 = "emp_fullname"
																			data-disp_col2 = "emp_email_bus"
																			data-typeahead_src = "../_help/get_emp_data.php",
																			data-ret_field_01 = "crstm_reviewer"
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
																					class="input-group-append" style="pointer-events: none">
																						<span class="fa fa-search" id="pointer1"></span>
																					</a>
																				</span>
																			</div>
																		</div><br>
																		<div class="dis_reviewer_name">
																			<span id="reviewer_name2" name="reviewer_name2"  class="text-danger"><?php echo $reviewer_name2?></span>
																		</div>
																	</div>	
																</div>
															</div>
															<div class="col-md-6" style="display:<?php echo $dis_rev2 ?>"></div>
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 1:</label>
																	<div class="col-md-6">
																		<div class="input-group input-group-sm">
																			<input name="crstm_email_app1" id="crstm_email_app1" <?php echo $canedit ?> value="<?php echo $crstm_email_app1 ?>" 
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
																						<span class="fa fa-search"></span>
																					</a>
																				</span>
																			</div>
																		</div><br>
																		<div><span id="app1_name" name="app1_name"  class="text-danger"><?php echo $app1_name?></span></div>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<!--<div class="form-group row">
																	<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
																</div>-->
															</div>
															
															<div class="col-md-6 displayApp2" style="display:<?php echo $chk_block ?>">
																<div class="form-group row">
																	<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 2:</label>
																	<div class="col-md-6">
																		<div class="input-group input-group-sm">
																			<input name="crstm_email_app2" id="crstm_email_app2" readOnly <?php echo $canedit ?> value="<?php echo $crstm_email_app2 ?>" 
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
																					class="input-group-append" style="pointer-events: <?php echo $pointer ?>">
																						<span class="fa fa-search"></span>
																					</a>
																				</span>
																			</div>
																		</div><br>
																		<div><span id="app2_name" name="app2_name"  class="text-danger"><?php echo $app2_name?></span></div>
																	</div>
																</div>
																<!--<div class="col-md-6">
																	<div class="form-group row">
																		<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
																	</div>
																</div>-->
															</div>
															<div class="col-md-6 nonCol"></div>
															<div class="col-md-6 displayApp3" style="display:<?php echo $chk_block ?>">
																<div class="form-group row">
																	<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 3:</label>
																	<div class="col-md-6">
																		<div class="input-group input-group-sm">
																			<input name="crstm_email_app3" id="crstm_email_app3" readOnly <?php echo $canedit ?> value="<?php echo $crstm_email_app3 ?>" 
																			data-disp_col1 = "emp_fullname"
																			data-disp_col2 = "emp_email_bus"
																			data-typeahead_src = "../_help/get_emp_data.php",
																			data-ret_field_01 = "crstm_email_app3"
																			data-ret_value_01 = "emp_email_bus"
																			data-ret_type_01 = "val"
																			data-ret_field_02 = "app3_name"
																			data-ret_value_02 = "emp_fullnamedept"
																			data-ret_type_02 = "html"
																			class="form-control input-sm font-small-3 typeahead">
																			
																			<div class="input-group-prepend">
																				<span class="input-group-text">
																					<a id="buthelp"
																					data-id_field_code="crstm_email_app3" 
																					data-id_field_name="app3_name" 
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
																						<span class="fa fa-search"></span>
																					</a>
																				</span>
																			</div>
																		</div><br>
																		<div><span id="app3_name" name="app3_name"  class="text-danger"><?php echo $app3_name?></span></div>
																	</div>
																</div>
																<div class="col-md-6">
																	<!--<div class="form-group row">
																		<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
																	</div>-->
																</div>
															</div>
															<!---------------->
															<div class="col-md-12">
																<fieldset class="form-group">
																	<label for="placeTextarea" class="font-weight-bold">ความเห็น / เหตุผลที่เสนอขอวงเงิน :</label>
																	<textarea  name="crstm_sd_reson" id="crstm_sd_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;" readonly><?php echo $crstm_sd_reson; ?></textarea>
																</fieldset>	
															</div>
															<?php if(($crstm_rem_rearward !="") && inlist('0,01',$crstm_step_code)){ ?> 
																<div class="col-md-12">
																	<div class="form-group">
																		<label for="placeTextarea" class="font-weight-bold">เหตุผลการ Rearward:<font class="text text-danger font-weight-bold"> ***</font></label>
																		<textarea  name="crstm_rem_rearward" id="crstm_rem_rearward" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem; color:red;">*** <?php echo $crstm_rem_rearward; ?> ***</textarea>
																		<!--<textarea  name="crstm_rem_rearward" id="crstm_rem_rearward" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem; background:#99CC33; color:red;"><?php echo $crstm_rem_rearward; ?></textarea>-->
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
																					<input type="file" accept="" name="load_reson_img" id="load_reson_img" value="<?php echo $crstm_reson_img ?>" disabled>
																					<input type="hidden" name="crstm_reson_img" id="crstm_reson_img" value="<?php echo $crstm_reson_img ?>">
																					<span class="file-custom"></span>
																				</label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																	<div class="col-md-9">
																		<a href="<?php echo($ImgReson) ?>" target="_blank" id="linkcurrent_ImgProject">
																			<img src="<?php echo($ImgReson_icon) ?>" border="0" id="ImgReson" name="ImgReson"  width="60" height="60">
																		</a>	
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<label class="font-weight-bold">ข้อมูลโครงการ (ถ้ามี):</label>
																</div>
															</div>
															
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-3 label-control" for="userinput1">ชื่อโครงการ (1):</label>
																	<div class="col-md-9">
																		<input type="text" name="crstm_pj_name" id="crstm_pj_name" value="<?php echo($crstm_pj_name) ?>" class="form-control input-sm font-small-3" readonly>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-3 label-control" for="userinput1">ชื่อโครงการ (2):</label>
																	<div class="col-md-9">
																		<input type="text" id="crstm_pj1_name" class="form-control input-sm font-small-3" name="crstm_pj1_name"  value="<?php echo($crstm_pj1_name) ?>" readonly>
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
																		<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj_amt" value="<?php echo($crstm_pj_amt) ?>" class="form-control input-sm font-small-3" name="crstm_pj_amt" readonly style="color:black;text-align:right" onkeyup="format(this)" onchange="format(this)" ></a>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-3 label-control" for="userinput1">มูลค่างาน (บาท):</label>
																	<div class="col-md-9">
																		<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj1_amt" value="<?php echo($crstm_pj1_amt) ?>" class="form-control input-sm font-small-3" name="crstm_pj1_amt" readonly style="color:black;text-align:right" onkeyup="format(this)" onchange="format(this)"></a>
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
																			<input id="crstm_pj_beg" name="crstm_pj_beg" value="<?php echo $crstm_pj_beg ?>" class="form-control input-sm border-warning font-small-3" type="text" readonly>
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
																			<input id="crstm_pj1_beg" name="crstm_pj1_beg" value="<?php echo $crstm_pj1_beg ?>" class="form-control input-sm border-warning font-small-3" type="text" readonly>
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
																					<input type="file" accept="image/*" name="load_pj_img" id="load_pj_img" value="<?php echo $crstm_pj_img ?>" disabled>
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
																					<input type="file" accept="image/*" name="load_pj1_img" id="load_pj1_img" value="<?php echo $crstm_pj1_img ?>" disabled>
																					<input type="hidden" name="crstm_pj1_img" id="crstm_pj1_img" value="<?php echo $crstm_pj1_img ?>">
																					<span class="file-custom"></span>
																				</label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																	<div class="col-md-9">
																		<a href="<?php echo($ImgPrj) ?>" target="_blank" id="linkcurrent_scvisit_picture">
																			<img src="<?php echo($ImgPrj_icon) ?>" border="0" id="PathCurrent" name="PathCurrent"  width="60" height="60">
																		</a>	
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group row">
																	<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																	<div class="col-md-9">
																		<a href="<?php echo($ImgPrj1) ?>" target="_blank" id="linkcurrent_scvisit_picture">
																			<img src="<?php echo($ImgPrj1_icon) ?>" border="0" id="PathCurrent" name="PathCurrent"  width="60" height="60">
																		</a>	
																	</div>
																</div>
															</div>
														</div>
														<!-------------------  ข้อมูลสินเชื่อคนที่ 1---------------------------->
														<div class="detailcr_display" >
															
															<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 2. สำหรับหน่วยงานสินเชื่อ ( 1 )</h4>
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label class="font-weight-bold">ประวัติการชำระเงินลูกค้า:</label>
																	</div>
																</div>
																<div class="col-sm-3">
																	<div class="form-group ">
																		<label>ปีล่าสุด:</label>
																		
																		<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_cur_yy" name="crstm_cur_yy">
																			<option value="" selected>--- เลือกปี ---</option>
																			<?php
																				$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy WHERE tbl_yy_desc BETWEEN  $previousYear AND $curYear ORDER BY tbl_yy_desc";
																				$result_doc = sqlsrv_query($conn, $sql_doc);
																				while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																				?>
																				<option value="<?php echo $r_doc['tbl_yy_desc']; ?>" 
																				<?php if ($crstm_cur_yy == $r_doc['tbl_yy_desc']) {
																					echo "selected";
																				} ?>>
																				<?php echo $r_doc['tbl_yy_desc']; ?></option>
																			<?php } ?>
																		</select>
																		
																		
																	</div>	
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label>% ตรงเวลา :</label>
																		<input type="text" id="crstm_otd1_pct" name="crstm_otd1_pct" value="<?php echo $crstm_otd1_pct ?>" class="form-control input-sm font-small-3"  style="text-align: right" >
																		
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label>ส่วนใหญ่ล่าช้าไม่เกิน ( วัน ) :</label>
																		<input type="text" id="crstm_ovr1_due" name="crstm_ovr1_due" value="<?php echo $crstm_ovr1_due ?>" class="form-control input-sm font-small-3" style="text-align: right">
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label>อื่น ๆ (ถ้ามี)  :</label>
																		<input type="text" id="crstm_etc1" name="crstm_etc1" value="<?php echo $crstm_etc1 ?>" class="form-control input-sm font-small-3">
																	</div>
																</div>
															</div>
															<div class="row">
																
																<div class="col-sm-3">
																	<div class="form-group ">
																		<label>ปีก่อน:</label>
																		
																		<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pre_yy" name="crstm_pre_yy" >
																			<option value="" selected>--- เลือกปี ---</option>
																			<?php
																				$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy WHERE tbl_yy_desc BETWEEN $previousYear AND $curYear ORDER BY tbl_yy_desc";
																				$result_doc = sqlsrv_query($conn, $sql_doc);
																				while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																				?>
																				<option value="<?php echo $r_doc['tbl_yy_desc']; ?>" 
																				<?php if ($crstm_pre_yy == $r_doc['tbl_yy_desc']) {
																					echo "selected";
																				} ?>>
																				<?php echo $r_doc['tbl_yy_desc']; ?></option>
																			<?php } ?>
																		</select>
																		
																		
																	</div>	
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label>% ตรงเวลา :</label>
																		<input type="text" class="form-control  input-sm font-small-3" id="crstm_otd_pct" value="<?php echo $crstm_otd_pct ?>" name="crstm_otd_pct" style="text-align: right">
																		
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label>ส่วนใหญ่ล่าช้าไม่เกิน  ( วัน ) :</label>
																		<input type="text" id="crstm_ovr_due" name="crstm_ovr_due" value="<?php echo $crstm_ovr_due ?>" class="form-control input-sm font-small-3" style="text-align: right">
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<label>อื่น ๆ (ถ้ามี)  :</label>
																		<input type="text" id="crstm_etc" name="crstm_etc" value="<?php echo $crstm_etc ?>" class="form-control input-sm font-small-3">
																	</div>
																</div>
															</div>
															
															
															<div class="row">	
																<div class="col-md-3">
																	<div class="form-group">
																		<label>Insurance: <font class="text text-danger font-weight-bold"> *</font></label>
																		<input type="text" id="crstm_ins" name="crstm_ins" value="<?php echo $crstm_ins ?>" class="form-control input-sm font-small-3">
																	</div>
																</div>
																
																<div class="col-md-12">
																	<fieldset class="form-group">
																		<label for="placeTextarea" class="font-weight-bold">ความเห็นสินเชื่อ #1 : <font class="text text-danger font-weight-bold"> *</font></label>
																		<textarea  name="crstm_cc1_reson" id="crstm_cc1_reson" class="form-control textarea-maxlength input-sm font-small-3" placeholder="Enter upto 500 characters.." maxlength="500"  rows="5" style="line-height:1.5rem;"><?php echo $crstm_cc1_reson; ?></textarea>
																	</div>
																</fieldset>	
																<!--<div class="col-md-12">
																	<fieldset class="form-group">
																		<label for="placeTextarea" class="font-weight-bold">Message to sales : </label>
																		<textarea  name="crstm_detail_mail" id="crstm_detail_mail" class="form-control textarea-maxlength input-sm font-small-3" placeholder="Enter upto 200 characters.." maxlength="200"  rows="3" style="line-height:1.5rem;"><?php echo $crstm_detail_mail; ?></textarea>
																	</fieldset>	
																</div>-->
																
																
																<?php if(($crstm_rem_rearward !="") && inlist('10,11',$crstm_step_code)){ ?> 
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
																						<input type="file" accept="" name="load_cr1_img" id="load_cr1_img" value="<?php echo $crstm_cr1_img ?>" onkeyup="CheckValidFile_header_attach('load_cr1_img')" onchange="CheckValidFile_header_attach('load_cr1_img')">
																						<input type="hidden" name="crstm_cr1_img" id="crstm_cr1_img" value="<?php echo $crstm_cr1_img ?>">																						
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
																			<!--<a href="path/ไฟล์"><img src="path/image"></a>-->
																			<a href="<?php echo($ImgCr1) ?>" target="_blank" id="linkcurrent_ImgProject">
																				<img src="<?php echo($ImgCr1_icon) ?>" border="0" id="ImgCr1" name="ImgCr1"  width="60" height="60">
																			</a>	
																		</div>
																	</div>
																</div>
																<div class="col-md-2">
																	<input type="checkbox"  id="del_cr1" name="del_cr1" value="1">
																	<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
																</div>
															</div>
														</div>
														<!-------------------  สิ้นสุดข้อมูลสินเชื่อคนที่ 1---------------------------->
														<!---  เช็ค step ถ้าแผนกสินเชื่อคนที่ 1 ทำเสร็จ โชว์ Div สำหรับสินเชื่อคนที่ 2 --->	
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
																				<input type="file" accept="" name="load_dbd_img" id="load_dbd_img" value="<?php echo $crstm_dbd_img ?>" onkeyup="CheckValidFile_header_attach('load_dbd_img')" onchange="CheckValidFile_header_attach('load_dbd_img')">
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
																				<input type="file" accept="" name="load_dbd1_img" id="load_dbd1_img" value="<?php echo $crstm_dbd1_img ?>" onkeyup="CheckValidFile_header_attach('load_dbd1_img')" onchange="CheckValidFile_header_attach('load_dbd1_img')">
																				<input type="hidden" name="crstm_dbd1_img" id="crstm_dbd1_img" value="<?php echo $crstm_dbd1_img ?>">										
																				<span class="file-custom"></span>
																			</label>
																		</div>
																	</div>
																</div>
																<div class="col-md-2">
																	<input type="checkbox"  id="del_dbd1" name="del_dbd" value="1">
																	<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
																</div>
																<div class="card-body  my-gallery" itemscope itemtype="http://schema.org/ImageGallery">		
																	<div class="row">
																		<figure class="col-md-6 col-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
																			<a href="<?php echo($ImgCr21) ?>" target="_blank" itemprop="contentUrl" data-size="480x360">
																				<img class="img-thumbnail img-fluid" src="<?php echo($ImgCr21_icon) ?>" itemprop="thumbnail" alt="Click Here" />
																			</a>
																		</figure>
																		<figure class="col-md-6 col-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
																			<a href="<?php echo($ImgCr22) ?>" target="_blank" itemprop="contentUrl" data-size="480x360">
																				<img class="img-thumbnail img-fluid" src="<?php echo($ImgCr22_icon) ?>" itemprop="thumbnail" alt="Click Here" />
																			</a>
																		</figure>
																	</div>
																	<div class="row">
																		<div class="col-md-12">
																			<fieldset class="form-group">
																				<label for="placeTextarea" class="font-weight-bold">ความเห็นสินเชื่อ #2 : <font class="text text-danger font-weight-bold"> *</font></label>
																				<textarea  name="crstm_cc2_reson" id="crstm_cc2_reson" class="form-control textarea-maxlength input-sm font-small-3" placeholder="Enter upto 500 characters.." maxlength="500"  rows="5" style="line-height:1.5rem;"><?php echo $crstm_cc2_reson; ?></textarea>
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
																								<input type="file" accept="" name="load_cr2_img" id="load_cr2_img" value="<?php echo $crstm_cr2_img ?>" onkeyup="CheckValidFile_header_attach('load_cr2_img')" onchange="CheckValidFile_header_attach('load_cr2_img')">
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
																	</div>	
																<?php } ?>
																<!---- สิ้นสุด CR2 ---->
																<div class="row">	
																	<!--- AR Manager --- approve	--->
																	<?php if($iscan_display_c3) { ?> 
																		<div class="col-md-12">
																			<fieldset class="form-group">
																				<label for="placeTextarea" class="font-weight-bold">ความเห็น Manager:</label>
																				<textarea  name="crstm_mgr_reson" id="crstm_mgr_reson" class="form-control textarea-maxlength input-sm font-small-3" placeholder="Enter upto 200 characters.." maxlength="200"  rows="5" style="line-height:1.5rem;"><?php echo $crstm_mgr_reson; ?></textarea>
																			</fieldset>	
																		</div>
																		
																		<div class="col-md-3">
																			<input type="radio"  id="mgr_conf_yes" name="mgr_conf" value="1" <?php if($crstm_mgr_rdo=='1'){ echo "checked"; }?> >
																			<label class="font-weight-bold" for="cus_conf_yes"> เห็นควรอนุมัติวงเงินเป็น</label>
																			
																		</div>
																		<div class="col-md-2">
																			<input type="text" id="crstm_cr_mgr" name="crstm_cr_mgr" class="form-control input-sm font-small-3" value="<?php echo $crstm_cr_mgr ?>"  style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)">
																		</div>
																		
																		<div class="col-md-2">
																			<input type="radio"  id="mgr_conf_yes" name="mgr_conf" value="2" <?php if($crstm_mgr_rdo=='2'){ echo "checked"; }?> >
																			<label class="font-weight-bold" for="cus_conf_yes">ไม่เห็นควรอนุมัติ</label>
																		</div>
																	<?php } ?>
																</div>
																<div class="form-actions right">
																	<div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">
																		<?php if($iscan_editing_c1) { ?>
																			<?php if($crstm_cc1_reson != "" ) { ?>
																				<button type="button"  id="btnsavecr" name="btnsavecr" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send','<?php echo encrypt('20', $key); ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit1</button>
																			<? } ?>
																			<button type="button"  id="btnsavecr" name="btnsavecr" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_add','<?php echo encrypt('10', $key); ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-check-square-o"></i> Save1</button>
																			<button type="button" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" data-toggle="modal" data-target="#div_frm_rearward" data-crstm_nbr="<?php echo $crstm_nbr ?>"><i class="fa fa-backward"></i> Rearward1</button>
																		<?php } ?>
																		<?php if($iscan_editing_c2) { ?>
																			<?php if($crstm_cc2_reson!="" && $crstm_step_code=="20") { ?>
																				<button type="button"  id="btnsavecr" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send','<?php echo encrypt('30', $key); ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit2</button>
																			<?php } ?>	
																			<button type="button" id="btnsavecr" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_add','<?php echo encrypt('20', $key); ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-check-square-o"></i> Save2</button>
																			<button type="button" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" data-toggle="modal" data-target="#div_frm_rearward" data-crstm_nbr="<?php echo $crstm_nbr ?>"><i class="fa fa-backward"></i> Rearward2</button>
																		<?php } ?>
																		<?php if($iscan_editing_c3) { ?>
																			<?php if($crstm_mgr_reson != "" && $crstm_reviewer2 !="") {?>
																				<button type="button"  id="btnsavecr" class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send_reviewer2','<?php echo encrypt('220', $key); ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit</button>
																			<?php } ?>
																			<?php if($crstm_mgr_reson != "" && $crstm_reviewer2 =="") {?>
																				<button type="button"  id="btnsavecr" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_send_approve','<?php echo encrypt('40', $key); ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit</button>
																			<?php } ?>
																			<button type="button"  id="btnsavecr" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1" onclick="dispostform('frm_add','<?php echo encrypt('30', $key); ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-check-square-o"></i> Save</button>
																			<button type="button" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" data-toggle="modal" data-target="#div_frm_rearward" data-crstm_nbr="<?php echo $crstm_nbr ?>"><i class="fa fa-backward"></i> Rearward3</button>
																		<?php } ?>
																		<button type="reset" class="btn btn-outline-danger btn-min-width btn-glow mr-1 mb-1" onclick="document.location.href='../crctrlbof/crctrlall.php'"><i class="ft-x"></i> Cancel</button>
																	</div>
																</div>
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
			
			
			<!-- END: Content-->			
			<div class="sidenav-overlay"></div>
			<div class="drag-target"></div>
			
			<form name="frm_del_cc" id="frm_del_cc" >
				<input type="hidden" name="action" value="editdel_cc">
				<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
				<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
				<input type="hidden" name="cr_cust_code" value="<?php echo $cr_cust_code; ?>">
				<input type="hidden" name="row_seq" value="<?php echo $row_seq; ?>">
				<input type="hidden" name="pg" value="<?php echo $pg ?>">
			</form>
			<form name="frm_submit" id="frm_submit" >
				<input type="hidden" name="action" value="submit_new">
				<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
				<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
				<input type="hidden" name="crstm_nbr" value="<?php echo $crstm_nbr; ?>">
				<input type="hidden" name="pg" value="<?php echo $pg ?>">
			</form>
			<!-- BEGIN: Footer-->
			<footer class="footer footer-static footer-light navbar-border">
				<p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 <a class="text-bold-800 grey darken-2" href="https://1.envato.market/pixinvent_portfolio" target="_blank">PIXINVENT </a></span><span class="float-md-right d-none d-lg-block">Power by IT Business Solution Team <i class="feather icon-heart pink"></i></span></p>
			</footer>
			<!-- END: Footer-->
			<div class="to-top">
				<i class="fa fa-angle-up" aria-hidden="true"></i>
			</div>
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
			<script type="text/javascript">
				function dispostform(formid,chk_action,crstm_nbr,cus_name) {
					//alert(formid+"--"+chk_action+"--"+crstm_nbr+"--"+cus_name);
					//$(document).ready(function() {
						if (formid == 'frm_add') {
								Swalappform(formid,chk_action,crstm_nbr,cus_name);
							} else if (formid == 'frm_send') {
								SwalappformSend(formid,chk_action,crstm_nbr,cus_name);
							} else if (formid == 'frm_send_reviewer2') {
								SwalappformSend_reviewer2(formid,chk_action,crstm_nbr,cus_name);
							} else if (formid == 'frm_send_approve') {
								SwalappformSend_app(formid,chk_action,crstm_nbr,cus_name);
							} else if (formid == 'frm_rearward') {
								SwalappformRearward(formid,chk_action,crstm_nbr,cus_name);
						}
					//});
				}
				
				function Swalappform(formid,chk_action,crstm_nbr,cus_name) {
					//alert(formid+"--"+chk_action+"--"+crstm_nbr+"--"+cus_name);
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
								var formObj = $('#frm_crctrledit_cr')[0];
								var formData = new FormData(formObj);
								$.ajax({
									beforeSend: function() {
										$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
										$("#requestOverlay").show(); /*Show overlay*/
									},
									type: 'POST',
									//url: '../serverside/crctrl_cr_post.php?step_code=' +chk_action  ,
									url: '../serverside/crctrl_cr_post.php?step_code='+chk_action+'&formid='+formid+''  ,
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
												timer: 500,
												confirmButtonClass: "btn btn-primary",
												buttonsStyling: false
											});
											location.reload(true);
											$(location).attr('href', 'crctrledit_cr.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
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
				function SwalappformSend(formid,chk_action,crstm_nbr,cus_name) {
					//alert(formid+"--"+chk_action+"--"+crstm_nbr+"--"+cus_name);
					Swal.fire({
						//title: "Are you sure?",
						//html: "คุณต้องการส่งข้อมูล  <br>เอกสารเลขที่   " + crstm_nbr + "<br> ลูกค้า  : " + cus_name + "<br>"+ "ไปยังแผนกสินเชื่ออนุมัติ ใช่หรือไม่ !!!! ",
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
								var formObj = $('#frm_crctrledit_cr')[0];
								//var formObj = $('#frm_submit')[0];
								var formData = new FormData(formObj);
								$.ajax({
									beforeSend: function() {
										$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
										$("#requestOverlay").show(); /*Show overlay*/
									},
									type: 'POST',
									//url: '../serverside/crctrlapppost.php?step_code=' +chk_action  ,
									url: '../serverside/crctrl_cr_post.php?step_code='+chk_action+'&formid='+formid+''  ,
									//data: $('#' + formid).serialize(),
									data: formData,
									timeout: 100000,
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
				function SwalappformSend_reviewer2(formid,chk_action,crstm_nbr,cus_name) {
					//alert(formid+"--"+chk_action+"--"+crstm_nbr+"--"+cus_name);
					Swal.fire({
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
								var formObj = $('#frm_crctrledit_cr')[0];
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
									timeout: 100000,
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
				function SwalappformSend_app(formid,chk_action,crstm_nbr,cus_name) {
					//alert(formid+"--"+chk_action+"--"+crstm_nbr+"--"+cus_name);
					Swal.fire({
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
								var formObj = $('#frm_crctrledit_cr')[0];
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
									//url: '../serverside/approvepost_pdf.php?step_code='+chk_action+'&formid='+formid+''  ,
									url: '../serverside/approvepost_pdf.php?step_code='+chk_action+'&formid='+formid+''  ,

									data: formData,
									timeout: 100000,
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
									url: '../serverside/crctrl_cr_post.php?step_code=' +chk_action  ,
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
					//$('#user_tel').inputmask("(999) 999-9999");
				})(window, document, jQuery);
				
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
							timeout: 10000,
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
							timeout: 10000,
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
				
				function recallpostform(formid) {
					//alert(formid);
					$(document).ready(function() {
						$.ajax({
							beforeSend: function() {
								$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
								$("#requestOverlay").show(); /*Show overlay*/
							},
							type: 'POST',
							url: '../serverside/recallpost.php',
							data: $('#' + formid).serialize(),
							timeout: 10000,
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
										title: "Recall-Successfull",
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
				
				function ccpostform(formid) {
					$(document).ready(function() {
						$.ajax({
							beforeSend: function() {
								$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
								$("#requestOverlay").show(); /*Show overlay*/
							},
							type: 'POST',
							url: '../serverside/ccpost.php',
							data: $('#' + formid).serialize(),
							timeout: 10000,
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
									$(location).attr('href', '../crctrlbof/crctrledit.php?crnumber=' + json.nb + '&pg=' + json.pg)
								}
							},
							
							complete: function() {
								$("#requestOverlay").remove(); /*Remove overlay*/
							}
						});
					});
				}
				$('#crstm_pj_beg').datetimepicker({
					format: 'DD/MM/YYYY'
				});
				$('#crstm_pj1_beg').datetimepicker({
					format: 'DD/MM/YYYY'
				});
				$('#beg_date').datetimepicker({
					format: 'DD/MM/YYYY'
				});
				$('#end_date').datetimepicker({
					format: 'DD/MM/YYYY'
				});
				$('#edit_beg_date').datetimepicker({
					format: 'DD/MM/YYYY'
				});
				$('#edit_end_date').datetimepicker({
					format: 'DD/MM/YYYY'
				});
				function formatCurrency(number) {
					number = parseFloat(number);
					return number.toFixed(2).replace(/./g, function(c, i, a) {
						return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
					});
				}	
			
				//   ฝั่ง Credit Control
				$('input[type="radio"]').click(function() {
					if($(this).attr('id') == 'dbd_conf_yes') {
						$('.dbd_display').show();  
						
						$("#fin_sta").val("");
						$("#fin_sta1").val("");
						$("#img_fin_sta").val("");
						$("#img_fin_sta1").val("");
						
						$("#fin_sta").prop("required", true);
						$("#fin_sta1").prop("required", true);
						
					}
					else if($(this).attr('id') == 'oth_conf_yes') {
						$('.dbd_display').show(); 
						
						$("#fin_sta").val("");
						$("#fin_sta1").val("");
						$("#img_fin_sta").val("");
						$("#img_fin_sta1").val("");
						
						$("#fin_sta").prop("required", true);
						$("#fin_sta1").prop("required", true);
					}
					
				});
				
				function chkNumber_dot(e){
					var keynum
					var keychar
					var numcheck
					if(window.event) {// IE
						keynum = e.keyCode
					}
					else if(e.which){ // Netscape/Firefox/Opera
						keynum = e.which
					}
					keychar = String.fromCharCode(keynum)
					numcheck = /\d|\./
					return numcheck.test(keychar)
				}
				//**** Check num dot (End) ***//
				
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
				
				/// Adding percentage to input box
				$("input[name='crstm_otd_pct']").on('input', function() {
					$(this).val(function(i, v) {
					return v.replace('%','') + '%';  });
				});
				$("input[name='crstm_otd1_pct']").on('input', function() {
					$(this).val(function(i, v) {
					return v.replace('%','') + '%';  });
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
				
				$("#frm_crctrledit_cr").on("click", "#buthelp", function () {
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
				
			function CheckValidFile_header_attach(nFle) {
				var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png",".xls", ".xlsx", ".pdf", ".doc", ".docx", ".ppt", ".pptx"];    
				switch (nFle) {
					case "load_reson_img":
						var fileVal = document.getElementById('load_reson_img').value;
						break;
					case "load_pj_img":
						var fileVal = document.getElementById('load_pj_img').value;
						break;
					case "load_pj1_img":
						var fileVal = document.getElementById('load_pj1_img').value;
						break;
					case "load_cr1_img":
						var fileVal = document.getElementById('load_cr1_img').value;
						break;		
					case "load_dbd_img":
						var fileVal = document.getElementById('load_dbd_img').value;
						break;	
					case "load_dbd1_img":
						var fileVal = document.getElementById('load_dbd1_img').value;
						break;	
					case "load_cr2_img":
						var fileVal = document.getElementById('load_cr2_img').value;
						break;			
				}
				//var fileVal = document.getElementById('load_reson_img').value;
				var fileExt = fileVal.substring(fileVal.lastIndexOf('.'));
				if (fileVal == "") {
					Swal.fire({
						title: "Warning!",
						//html: json.e,
						html: "<b>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" + _validFileExtensions.join(", ") + "</span></b>",
						 icon: 'warning',
						confirmButtonClass: "btn btn-danger",
						buttonsStyling: false
					});
					return false;
				} 
				else if (fileVal.length > 0) {
					//Not Allow : check for configuration files like web.config
					//Not Allow : Check for files without a filename like .htaccess
					var ext = fileVal.split('.');
					if(ext[0] == "") {
						Swal.fire({
							title: "Warning!",
							//html: json.e,
							html: "<b>คุณกำลังพยายามอัพโหลดไฟล์ ที่จะส่งผลต่อการตั้งค่าของเว็บเซิฟเวอร์อยู่หรือไม่ จงหลีกเลี่ยง<br>เฉพาะไฟล์ที่มีนามสกุล <span class='text-danger'>" + _validFileExtensions.join(", ") + "</span> เท่านั้น </b>",
							 icon: 'warning',
							customClass: {
								confirmButton: "btn btn-danger"
							},
							buttonsStyling: false
						});
						return false;
					}
					else if(fileExt == ".config" || fileExt == ".exe" || fileExt == ".db" || fileExt == ".dll"  ) {
						Swal.fire({
							title: "Warning!",
							//html: json.e,
							html: "<b>คุณกำลังพยายามอัพโหลดไฟล์ ที่จะส่งผลต่อการตั้งค่าของเว็บอยู่หรือไม่ จงหลีกเลี่ยง <br>โปรดอัพโหลดเฉพาะไฟล์ที่มีนามสกุล <span class='text-danger'>" + _validFileExtensions.join(", ") + "</span> เท่านั้น </b>",
							 icon: 'warning',
							customClass: {
								confirmButton: "btn btn-danger"
							},
							buttonsStyling: false
						});
						return false;
					}
					else if(ext.length == 1) {
						Swal.fire({
							title: "Warning!",
							html: "<b>ไม่พบนามสกุลไฟล์ที่ต้องการอัพโหลด <br>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" + _validFileExtensions.join(", ") + "</span></b>",
							 icon: 'warning',
							customClass: {
								confirmButton: "btn btn-warning"
							},
							buttonsStyling: false
						});
						return false;
					}
					else if(ext.length > 2) {
						// console.log("found");
						// alert(ext[1]); // first extension
						// alert(ext[2]); // second extension
						Swal.fire({
							title: "Warning!",
							html: "<b>กรุณาตั้งชื่อไฟล์โดยไม่มีเครื่องหมาย (.) ในชื่อไฟล์ <br>นอกจากนามสกุลไฟล์เท่านั้น เพื่อป้องกันความผิดพลาด<br>และกรุณาอัพโหลดไฟล์ <br> เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>"+ _validFileExtensions.join(", ") + "</span></b>",
							 icon: 'warning',
							customClass: {
								confirmButton: "btn btn-warning"
							},
							buttonsStyling: false
						});
						return false;
					} else {			
						var blnValid = false;
						for (var j = 0; j < _validFileExtensions.length; j++) {
							var sCurExtension = _validFileExtensions[j];
							if (fileVal.substr(fileVal.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
								blnValid = true;
								return true;
							}
						}
								
						if (!blnValid) {
							//alert("Sorry, " + fileVal + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
							Swal.fire({
								title: "Warning!",
								html: "<b>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>"+ _validFileExtensions.join(", ") + "</span></b>",
								icon: 'warning',
								customClass: {
									confirmButton: "btn btn-warning"
								},
								buttonsStyling: false
							});
							return false;
						}
					}
				}
			}
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