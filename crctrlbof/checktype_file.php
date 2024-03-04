<?php 
	//Update can_editing
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
	$crstm_nbr = 'CR-2107-0015';
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
    "crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt,	crstm_mstr.crstm_reson_img,crstm_mstr.crstm_rem_rearward,crstm_mstr.crstm_chk_rearward ".
	"FROM crstm_mstr INNER JOIN ".
	"cus_mstr ON crstm_mstr.crstm_cus_nbr = cus_mstr.cus_nbr INNER JOIN ".
	"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id INNER JOIN ".
	"term_mstr ON cus_mstr.cus_terms_paymnt = term_mstr.term_code INNER JOIN ".
	"country_mstr ON cus_mstr.cus_country = country_mstr.country_code ".
	"WHERE(crstm_mstr.crstm_nbr = ?)";
	
	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$e_user_fullname = trim($rec_cus["emp_th_firstname"]) . " " . trim($rec_cus["emp_th_lastname"]);
		$e_user_th_pos_name = $rec_cus["emp_th_pos_name"];
		$e_user_manager_name = $rec_cus["emp_manager_name"];
		$e_user_email = $rec_cus["emp_email_bus"];
		$phone_mask = $rec_cus["crstm_tel"];
		
		$cr_cust_code = $rec_cus['crstm_cus_nbr'];
		$crstm_cus_name = $rec_cus['cus_name1'];
		$cus_street = $rec_cus['cus_street'];
		$cus_street2 = $rec_cus['cus_street2'];
		$cus_street3 = $rec_cus['cus_street3'];
		$cus_street4 = $rec_cus['cus_street4'];
		$cus_street5 = $rec_cus['cus_street5'];
		$cus_district = $rec_cus['cus_district'];
		$cus_city = $rec_cus['cus_city'];
		$cus_country = $rec_cus['country_desc'];
		$cus_zipcode = $rec_cus['cus_zipcode'];
		$cus_street = $cus_street ." " . $cus_street2 ." ". $cus_street3 ." ". $cus_street4 ." ". $cus_street5 ." ". $cus_district ." ". $cus_city ." ". $cus_zipcode;
		$cus_tax_nbr3 = $rec_cus['cus_tax_nbr3'];
		$cus_terms_paymnt = $rec_cus['term_desc'];
		$cus_acc_group = $rec_cus['cus_acc_group'];
		
		/// radio 
		$cus_conf_yes = $rec_cus['crstm_chk_rdo1'];
		$cusold_conf_yes = $rec_cus['crstm_chk_rdo2'];
		$crstm_chk_term = $rec_cus['crstm_chk_term'];
		
		$term_desc_add = $rec_cus['crstm_term_add'];
		$term_desc = $rec_cus['crstm_ch_term'];
		
		$crstm_approve = $rec_cus['crstm_approve'];
		$crstm_sd_reson = $rec_cus['crstm_sd_reson'];
		$crstm_step_code = $rec_cus['crstm_step_code'];
		$crstm_sd_per_mm = number_format($rec_cus['crstm_sd_per_mm']);
		$crstm_reson_img = $rec_cus['crstm_reson_img'];
		
		$crstm_pj_name = $rec_cus['crstm_pj_name'];
		$crstm_pj_amt = number_format($rec_cus['crstm_pj_amt']);
		$crstm_pj_prv = $rec_cus['crstm_pj_prv'];
		$crstm_pj_term = $rec_cus['crstm_pj_term'];
		$crstm_pj_dura = $rec_cus['crstm_pj_dura'];
		$crstm_pj_beg = dmytx($rec_cus['crstm_pj_beg']);
		$crstm_pj_img = $rec_cus['crstm_pj_img'];
		
		$crstm_pj1_name = $rec_cus['crstm_pj1_name'];
		$crstm_pj1_amt = number_format($rec_cus['crstm_pj1_amt']);
		$crstm_pj1_prv = $rec_cus['crstm_pj1_prv'];
		$crstm_pj1_term = $rec_cus['crstm_pj1_term'];
		$crstm_pj1_dura = $rec_cus['crstm_pj1_dura'];
		$crstm_pj1_beg = dmytx($rec_cus['crstm_pj1_beg']);
		$crstm_pj1_img = $rec_cus['crstm_pj1_img'];
		
		// ข้อมูลฝั่งสินเชื่อ 
		//$crstm_cus_nbr = $rec_cus['crstm_cus_nbr'];
		$crstm_pre_yy = $rec_cus['crstm_pre_yy'];
		$crstm_otd_pct = $rec_cus['crstm_otd_pct'];
		$crstm_ovr_due = $rec_cus['crstm_ovr_due'];
		$crstm_etc = $rec_cus['crstm_etc'];
		$crstm_cur_yy = $rec_cus['crstm_cur_yy'];
		$crstm_otd1_pct = $rec_cus['crstm_otd1_pct'];
		$crstm_ovr1_due = $rec_cus['crstm_ovr1_due'];
		$crstm_etc1 = $rec_cus['crstm_etc1'];
		$crstm_ins = $rec_cus['crstm_ins'];
		$crstm_cc1_reson = $rec_cus['crstm_cc1_reson'];
		$crstm_cr1_img = $rec_cus['crstm_cr1_img'];
		
		$dbd_conf_yes = $rec_cus['crstm_dbd_rdo'];
		$crstm_dbd_yy = $rec_cus['crstm_dbd_yy'];
		$crstm_dbd1_yy = $rec_cus['crstm_dbd1_yy'];
		$crstm_cc2_reson = $rec_cus['crstm_cc2_reson'];
		$crstm_dbd_img = $rec_cus['crstm_dbd_img'];
		$crstm_dbd1_img = $rec_cus['crstm_dbd1_img'];
		$crstm_cr2_img = $rec_cus['crstm_cr2_img'];
		
		$crstm_mgr_reson = $rec_cus['crstm_mgr_reson'];
		$crstm_mgr_rdo = $rec_cus['crstm_mgr_rdo'];
		$crstm_cr_mgr = $rec_cus['crstm_cr_mgr'];
		
		$crstm_cc_date_beg = dmytx($rec_cus['crstm_cc_date_beg']);
		$crstm_cc_date_end = dmytx($rec_cus['crstm_cc_date_end']);
		$crstm_cc_amt = number_format($rec_cus['crstm_cc_amt']);
		
		$crstm_rem_rearward = $rec_cus['crstm_rem_rearward'];
		$crstm_chk_rearward = $rec_cus['crstm_chk_rearward'];
		
		if ($crstm_cr_mgr != "" ) {
			$crstm_cr_mgr = number_format($crstm_cr_mgr);
		}
		$crstm_mgr_img = $rec_cus['crstm_mgr_img'];
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
	
	//Display Picture > Project 
	$uploadpath = "../_fileuploads/sale/project/";
	if ($crstm_reson_img=="") {
		$ImgReson = "$uploadpath"."nopicture.png";
		} else {
		$ImgReson = "$uploadpath"."$crstm_reson_img";
	}	
	
	if ($crstm_pj_img=="") {
		$ImgProject = "$uploadpath"."nopicture.png";
		} else {
		$ImgProject = "$uploadpath"."$crstm_pj_img";
	}	
	
	if ($crstm_pj1_img=="") {
		$ImgProject1 = "$uploadpath"."nopicture.png";
		} else {
		$ImgProject1 = "$uploadpath"."$crstm_pj1_img";
	}	
	
	//Display Picture > Credit Control 
	$uploadpath_cr = "../_fileuploads/ac/cr/";
	// cr1
	if ($crstm_cr1_img=="") {
		$ImgCr1 = "$uploadpath_cr"."nopicture.png";
		} else {
		$ImgCr1 = "$uploadpath_cr"."$crstm_cr1_img";
	}	
	// cr2
	if ($crstm_dbd_img=="") {
		$ImgCr21 = "$uploadpath_cr"."nopicture.png";
		} else {
		$ImgCr21 = "$uploadpath_cr"."$crstm_dbd_img";
	}	
	if ($crstm_dbd1_img=="") {
		$ImgCr22 = "$uploadpath_cr"."nopicture.png";
		} else {
		$ImgCr22 = "$uploadpath_cr"."$crstm_dbd1_img";
	}	
	if ($crstm_cr2_img=="") {
		$ImgCr23 = "$uploadpath_cr"."nopicture.png";
		} else {
		$ImgCr23 = "$uploadpath_cr"."$crstm_cr2_img";
	}	
	if ($crstm_mgr_img=="") {
		$ImgCr3 = "$uploadpath_cr"."nopicture.png";
		} else {
		$ImgCr3 = "$uploadpath_cr"."$crstm_mgr_img";
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
	if (inlist('11,20,21',$crstm_step_code) && inlist($user_role,'Action_View2')) {
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
	
	
	
	
//$file_name = 'my_picture_01.ppt' ;
$info = pathinfo( $crstm_reson_img , PATHINFO_EXTENSION ) ;
echo $info ;

$filetype = array('jpg', 'jpeg', 'png', 'gif', 'bmp','xlsx', 'xls','docx','doc','pdf','ppt','pptx');

if (in_array($info, $filetype))
  {
  echo "Match found";
  echo $crstm_reson_img;
  }
else
  {
  echo "Match not found";
   echo $crstm_reson_img;
  }

	

?>