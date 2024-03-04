<?php

function printpageform($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn) {
date_default_timezone_set('Asia/Bangkok');
$curdate = date("d/m/Y");	
$curMonth = date('Y-m'); 
	
	$params = array($crstm_nbr);
					 
	 $query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_tax_nbr3, ".
                     " crstm_mstr.crstm_address, crstm_mstr.crstm_district, crstm_mstr.crstm_amphur, crstm_mstr.crstm_province, crstm_mstr.crstm_zip, crstm_mstr.crstm_country, crstm_mstr.crstm_chk_rdo1, ". 
                     " crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_per_mm, ".
                     " crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_reson_img, crstm_mstr.crstm_pj_name, crstm_mstr.crstm_pj_prv, crstm_mstr.crstm_pj_term, crstm_mstr.crstm_pj_amt, crstm_mstr.crstm_pj_dura, ".
                     " crstm_mstr.crstm_pj_beg, crstm_mstr.crstm_pj_img, crstm_mstr.crstm_pj1_name, crstm_mstr.crstm_pj1_prv, crstm_mstr.crstm_pj1_term, crstm_mstr.crstm_pj1_amt, crstm_mstr.crstm_pj1_dura, ".
                     " crstm_mstr.crstm_pj1_beg, crstm_mstr.crstm_pj1_img, crstm_mstr.crstm_pre_yy, crstm_mstr.crstm_otd_pct, crstm_mstr.crstm_ovr_due, crstm_mstr.crstm_etc, crstm_mstr.crstm_cur_yy, ".
                     " crstm_mstr.crstm_otd1_pct, crstm_mstr.crstm_ovr1_due, crstm_mstr.crstm_etc1, crstm_mstr.crstm_ins, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cr1_img, crstm_mstr.crstm_dbd_rdo, ".
                     " crstm_mstr.crstm_dbd_yy, crstm_mstr.crstm_dbd_img, crstm_mstr.crstm_dbd1_yy, crstm_mstr.crstm_dbd1_img, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cr2_img,crstm_mstr.crstm_scgc, ".
                     " crstm_mstr.crstm_mgr_reson, crstm_mstr.crstm_mgr_rdo, crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_mgr_img, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, ".
                     " crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_create_by, crstm_mstr.crstm_create_date, crstm_mstr.crstm_update_by, crstm_mstr.crstm_update_date, crstm_mstr.crstm_step_code, ".
                     " crstm_mstr.crstm_step_name, crstm_mstr.crstm_whocanread, crstm_mstr.crstm_curprocessor, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_create_by_cr1, crstm_mstr.crstm_create_cr1_date, ".
                     " crstm_mstr.crstm_create_by_cr2, crstm_mstr.crstm_create_cr2_date, crstm_mstr.crstm_create_by_mgr, crstm_mstr.crstm_create_mgr_date, crstm_mstr.crstm_rem_rearward, ".
					 " crstm_email_app1, crstm_email_app2, crstm_email_app3, crstm_reviewer_date, crstm_reviewer2_date, crstm_stamp_app1_date, crstm_stamp_app2_date, crstm_stamp_app3_date, crstm_fin_app_date,crstm_reviewer, crstm_reviewer2, ".
                     " crstm_mstr.crstm_chk_rearward, cus_mstr.cus_terms_paymnt, term_mstr.term_desc, cus_mstr.cus_acc_group, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname ".
					 " FROM crstm_mstr INNER JOIN ".
                     " cus_mstr ON crstm_mstr.crstm_cus_nbr = cus_mstr.cus_nbr INNER JOIN ".
                     " term_mstr ON cus_mstr.cus_terms_paymnt = term_mstr.term_code INNER JOIN ".
                     " emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					 "WHERE(crstm_mstr.crstm_nbr = ?)";
					 
	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$phone_mask = html_clear($rec_cus['crstm_tel']);
		$crstm_user = html_clear($rec_cus['crstm_user']);
		$crstm_name = trim($rec_cus["emp_th_firstname"]) . " " . trim($rec_cus["emp_th_lastname"]);

		$crstm_date = html_escape(dmytx($rec_cus['crstm_date']));
		$crstm_create_by_cr1 = html_clear($rec_cus['crstm_create_by_cr1']);
		$crstm_create_by_cr2 = html_clear($rec_cus['crstm_create_by_cr2']);
		$crstm_create_by_mgr = html_clear($rec_cus['crstm_create_by_mgr']);
		$crstm_create_cr1_date = html_escape(dmytx($rec_cus['crstm_create_cr1_date']));
		$crstm_create_cr2_date = html_escape(dmytx($rec_cus['crstm_create_cr2_date']));
		$crstm_create_mgr_date = html_escape(dmytx($rec_cus['crstm_create_mgr_date']));
		$crstm_cus_nbr = html_clear($rec_cus['crstm_cus_nbr']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_province = html_clear($rec_cus['crstm_province']);
		$crstm_country = html_clear($rec_cus['crstm_country']);
		$addr = $crstm_country." / ".$crstm_province;
		$cus_terms_paymnt = html_clear($rec_cus['cus_terms_paymnt']);
		
		$cus_terms_desc = "(".$rec_cus['term_desc'].")";
		//$cus_terms_desc = "(".$cus_terms_desc.")";
		
		$cus_terms_paymnt1 = html_clear($rec_cus['cus_terms_paymnt']);
		$cus_terms_desc1 = "(".$rec_cus['term_desc'].")";
		
		$cus_acc_group = html_clear($rec_cus['cus_acc_group']);
		
		/// radio 
		$cus_conf_yes = html_clear($rec_cus['crstm_chk_rdo1']);
		$cusold_conf_yes = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
		
		$term_add = html_clear($rec_cus['crstm_term_add']);
		$term_desc_add = findsqlval("term_mstr", "term_desc", "term_code", $term_add ,$conn);
		if($term_desc_add !="") {$term_desc_add = "(".$term_desc_add.")";}
		
		$crstm_ch_term = html_clear($rec_cus['crstm_ch_term']);
		$crstm_ch_term_desc = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);
		if($crstm_ch_term_desc!="") {$crstm_ch_term_desc = "(".$crstm_ch_term_desc.")";}
		
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		$crstm_sd_per_mm = number_format($rec_cus['crstm_sd_per_mm']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		
		$crstm_pj_name = html_clear($rec_cus['crstm_pj_name']);
		$crstm_pj_amt = number_format($rec_cus['crstm_pj_amt']);
		$crstm_pj_prv = html_clear($rec_cus['crstm_pj_prv']);
		$crstm_pj_term = html_clear($rec_cus['crstm_pj_term']);
		$crstm_pj_dura = html_clear($rec_cus['crstm_pj_dura']);
		if ($crstm_pj_dura=="13") {
			$crstm_pj_dura = "มากกว่า 12 เดือน";
		}else {$crstm_pj_dura = $crstm_pj_dura;}
		
		$crstm_pj_beg = dmytx($rec_cus['crstm_pj_beg']);
		$crstm_pj_term_desc = findsqlval("term_mstr", "term_desc", "term_code", $crstm_pj_term ,$conn);
		
		$crstm_pj1_name = html_clear($rec_cus['crstm_pj1_name']);
		$crstm_pj1_amt = number_format($rec_cus['crstm_pj1_amt']);
		$crstm_pj1_prv = html_clear($rec_cus['crstm_pj1_prv']);
		$crstm_pj1_term = html_clear($rec_cus['crstm_pj1_term']);
		$crstm_pj1_dura = html_clear($rec_cus['crstm_pj1_dura']);
		if ($crstm_pj1_dura=="13") {
			$crstm_pj1_dura = "มากกว่า 12 เดือน";
		}else {$crstm_pj1_dura = $crstm_pj1_dura;}
		$crstm_pj1_beg = dmytx($rec_cus['crstm_pj1_beg']);
		$crstm_pj1_term_desc = findsqlval("term_mstr", "term_desc", "term_code", $crstm_pj1_term ,$conn);
		
		$crstm_pre_yy = html_clear($rec_cus['crstm_pre_yy']);
		$crstm_otd_pct = html_clear($rec_cus['crstm_otd_pct']);
		$crstm_ovr_due = html_clear($rec_cus['crstm_ovr_due']);
		$crstm_etc = html_clear($rec_cus['crstm_etc']);
		$crstm_cur_yy = html_clear($rec_cus['crstm_cur_yy']);
		$crstm_otd1_pct = html_clear($rec_cus['crstm_otd1_pct']);
		$crstm_ovr1_due = html_clear($rec_cus['crstm_ovr1_due']);
		$crstm_etc1 = html_clear($rec_cus['crstm_etc1']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_ins = html_clear($rec_cus['crstm_ins']);
		
		$dbd_conf_yes = html_clear($rec_cus['crstm_dbd_rdo']);
		$crstm_dbd_yy = html_clear($rec_cus['crstm_dbd_yy']);
		$crstm_dbd1_yy = html_clear($rec_cus['crstm_dbd1_yy']);
		if($crstm_dbd_yy == ""){$crstm_dbd_yy = $crstm_dbd1_yy;}
		
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_mgr_rdo = html_clear($rec_cus['crstm_mgr_rdo']);
		$crstm_cr_mgr = html_clear($rec_cus['crstm_cr_mgr']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		
		$crstm_cc_date_beg = html_escape(dmytx($rec_cus['crstm_cc_date_beg']));
		$crstm_cc_date_end = html_escape(dmytx($rec_cus['crstm_cc_date_end']));
		$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);

		$crstm_step_code = html_clear($rec_cus['crstm_step_code']);
		$crstm_reviewer_date = html_escape(dmytx($rec_cus['crstm_reviewer_date']));
		$crstm_reviewer2_date = html_escape(dmytx($rec_cus['crstm_reviewer2_date']));
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_stamp_app1_date = html_escape(dmytx($rec_cus['crstm_stamp_app1_date']));
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$crstm_stamp_app2_date = html_escape(dmytx($rec_cus['crstm_stamp_app2_date']));
		$crstm_email_app3 = html_clear($rec_cus['crstm_email_app3']);
		$crstm_stamp_app3_date = html_escape(dmytx($rec_cus['crstm_stamp_app3_date']));
		$crstm_fin_app_date = html_escape(dmytx($rec_cus['crstm_fin_app_date']));

		$crstm_reviewer = html_clear($rec_cus['crstm_reviewer']);
		$crstm_reviewer2 = html_clear($rec_cus['crstm_reviewer2']);

		if($crstm_reviewer!=""){
			$reviewer_nme = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_reviewer,$conn);	
		}
		if ($crstm_reviewer_date != "") { 
			$approve_reviewer = "*** Approved ***";
			$crstm_reviewer_date = $crstm_reviewer_date;
		}else {
			$approve_reviewer = "-"; $reviewer_nme = "-"; $crstm_reviewer_date = "-";
		}
		
		if($crstm_reviewer2!=""){
			$reviewer_nme2 = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_reviewer2,$conn);	
			if ($crstm_reviewer2_date != "") { 
				$approve_reviewer2 = "*** Approved ***";
				$crstm_reviewer2_date = $crstm_reviewer2_date;
			}else if($crstm_reviewer2_date == NULL) { 
				$reviewer_nme2 = $reviewer_nme2;
			}
			
		}else {
			$approve_reviewer2 = "-"; $reviewer_nme2 = "-"; $crstm_reviewer2_date = "-";
		}
		
		if ($crstm_otd_pct != "") {
			 $txt_cr = $crstm_otd_pct ;  
		} else if ($crstm_ovr_due != "") {
			$txt_cr = "ส่วนใหญ่ล้าช้าไม่เกิน ".$crstm_ovr_due."   วัน" ;
		} else {	
			$txt_cr = $crstm_etc ;
		} 
		
		if ($crstm_otd1_pct != "") {
			 $txt_cr1 = $crstm_otd1_pct ;  
		} else if ($crstm_ovr1_due != "") {
			$txt_cr1 = "ส่วนใหญ่ล้าช้าไม่เกิน ".$crstm_ovr1_due."  วัน" ;
		} else {	
			$txt_cr1 = $crstm_etc1 ;
		} 
	}	
	
	
		
	if($cus_acc_group=="ZC01" || $cus_acc_group=="ZC07" ) {
		if($crstm_cr_mgr <= 2000000) {
			$params = array($crstm_email_app1);
			$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
			$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
			$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_emp) {
				$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
				$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
				$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
				$author_sign_nme1 = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname  ;
				} 
		}	
	} else {
		if($crstm_cr_mgr <= 13000000) {
			$params = array($crstm_email_app1);
			$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
			$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
			$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_emp) {
				$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
				$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
				$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
				$author_sign_nme1 = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname  ;
				} 
		}	
	}	

	$foot_sign_nme1 = findsqlval("sign_mstr","sign_name","sign_code","01",$conn);
	$foot_sign1 = findsqlval("sign_mstr","sign_text","sign_code","01",$conn);

	$foot_sign_nme2 = findsqlval("sign_mstr","sign_name","sign_code","02",$conn);
	$foot_sign2 = findsqlval("sign_mstr","sign_text","sign_code","02",$conn);

	$foot_sign_nme3 = findsqlval("sign_mstr","sign_name","sign_code","03",$conn);
	$foot_sign3 = findsqlval("sign_mstr","sign_text","sign_code","03",$conn);

	switch ($crstm_approve) {
		case "คณะกรรมการบริหารอนุมัติ":
			// $author_sign_nme1 --> กจก.
			if ($crstm_scgc == true) { 
				$author_sign_nme1 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
				$author_sign1 = findsqlval_ZC("author_mstr", "author_sign", "author_email", $crstm_email_app1, "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme1 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
				$author_sign1 = findsqlval_ZC("author_g_mstr", "author_sign", "author_email", $crstm_email_app1, "author_text",  $crstm_approve ,$conn);
			}
			//$author_sign_nme1 = findsqlval("sign_mstr","sign_name","sign_code","01",$conn);
			//$author_sign1 = findsqlval("sign_mstr","sign_text","sign_code","01",$conn);
			//$author_sign1 = "NM";
			//$author_sign_nme1 = "คุณนำพล มลิชัย";
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign_nme2 = findsqlval("sign_mstr","sign_name","sign_code","02",$conn);
			$author_sign2 = findsqlval("sign_mstr","sign_text","sign_code","02",$conn);
			//$author_sign2 = "SK";
			//$author_sign_nme2 = "คุณสุรศักดิ์ ไกรวิทย์ชัยเจริญ";

			$author_sign_nme3 = findsqlval("sign_mstr","sign_name","sign_code","03",$conn);
			$author_sign3 = findsqlval("sign_mstr","sign_text","sign_code","03",$conn);
			//$author_sign3 = "NP";
			//$author_sign_nme3 = "คุณนิธิ ภัทรโชค";
			if (inlist("61",$crstm_step_code)) { 
				$approve1 = "*** Approved ***"; 
				$crstm_stamp_app2_date = "";
				$crstm_stamp_app3_date = "";
			}
			
			if ($crstm_fin_app_date != "") {
				 $approve1 = "*** Approved ***";
				 $approve2 = "*** Approved ***";
				 $approve3 = "*** Approved ***"; 
				 $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";
				 $crstm_stamp_app2_date = $crstm_fin_app_date;
				 $crstm_stamp_app3_date = $crstm_fin_app_date;
			}
			break;
		case "คณะกรรมการสินเชื่ออนุมัติ":
			$author_sign1 = "กจก";		
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;		
			if ($crstm_scgc == true) { 
				//$author_sign_nme1 = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_email_app1,$conn);	
				$author_sign_nme1 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
				//$author_sign1 = findsqlval_ZC("author_mstr", "author_sign", "author_email", $crstm_email_app1, "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme1 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
				//$author_sign1 = findsqlval_ZC("author_mstr", "author_sign", "author_email", $crstm_email_app1, "author_text",  $crstm_approve ,$conn);
			}

			$author_sign2 = "CFO";
			//$author_sign_nme2 = "คุณวรนันท์ โสดานิล (CFO)";
			//$author_sign_nme2 = findsqlval("author_mstr","author_sign_nme","author_code","CFO",$conn);
			$crstm_stamp_app2_date = $crstm_stamp_app2_date;
			if ($crstm_scgc == true) { 
				$author_sign_nme2 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app2 , "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme2 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app2 , "author_text", $crstm_approve ,$conn);
			}

			$author_sign3 = "CMO";
			$crstm_stamp_app3_date = $crstm_stamp_app3_date;
			if ($crstm_scgc == true) { 
				$author_sign_nme3 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app3 , "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme3 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app3 , "author_text", $crstm_approve ,$conn);
			}
			if (inlist("60",$crstm_step_code)) { 
				$approve1 = "*** Approved ***"; 
				$approve2 = "*** Approved ***"; 
				$approve3 = "*** Approved ***"; 
				$head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";
			}
			//$author_sign3 = "";
			//$author_sign_nme3 = "";
			break;	
		case "กจก. อนุมัติ":
			$author_sign1 = "กจก.";
			//$author_sign_nme1 = "คุณนำพล มลิชัย";
			if ($crstm_scgc == true) { 
				$author_sign_nme1 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme1 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}
			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";}
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;
			
			$author_sign2 = "";
			$author_sign_nme2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$crstm_stamp_app3_date = "";

			break;
		case "CO. อนุมัติ":
			$author_sign1 = "CO";
			//$author_sign_nme1 = "คุณสิทธิชัย สุขกิจประเสริฐ";
			if ($crstm_scgc == true) { 
				$author_sign_nme1 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme1 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}
			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>"; }
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign2 = "";
			$author_sign_nme2 = "";
			$approve2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$approve3 = "";
			$crstm_stamp_app3_date = "";
			break;
		case "ผฝ. อนุมัติ":
			$author_sign1 = "ผฝ.";
			$author_sign_nme1 = $author_sign_nme1 ;

			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>"; }
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign2 = "";
			$author_sign_nme2 = "";
			$approve2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$approve3 = "";
			$crstm_stamp_app3_date = "";
			break;	
		case "ผส. อนุมัติ":
			$author_sign1 = "ผส.";
			$author_sign_nme1 = $author_sign_nme1 ;

			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";}
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign2 = "";
			$author_sign_nme2 = "";
			$approve2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$approve3 = "";
			$crstm_stamp_app3_date = "";
			break;
		case "ผผ. อนุมัติ":
			$author_sign1 = "ผผ.";
			$author_sign_nme1 = $author_sign_nme1 ;

			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";}
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign2 = "";
			$author_sign_nme2 = "";
			$approve2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$approve3 = "";
			$crstm_stamp_app3_date = "";
			break;				
		}

	if ($cus_conf_yes  == '0' || $cus_conf_yes  == '1') {
		
		if($cus_conf_yes  == '0'){
			$chk_0 = '<img src="../_images/check_true.png" width=15px>';
			
		}else {
		$chk_0 = '<img src="../_images/check_blank.png" width=15px>';
		}
		if($cus_conf_yes  == '1'){
			$chk_1= '<img src="../_images/check_true.png" width=15px>';
			
		}else {
			$chk_1 = '<img src="../_images/check_blank.png" width=15px>';
		}
	}
	
	if ($cusold_conf_yes  == 'C1' || $cusold_conf_yes  == 'C2' || $cusold_conf_yes == 'C3') {
		
		if($cusold_conf_yes  == 'C1'){
			$chk_c1 = '<img src="../_images/check_true.png" width=15px>';
			
		}else {
			$chk_c1 = '<img src="../_images/check_blank.png" width=15px>';
		}
		if($cusold_conf_yes  == 'C2'){
			$chk_c2= '<img src="../_images/check_true.png" width=15px>';
			
		}else {
			$chk_c2 = '<img src="../_images/check_blank.png" width=15px>';
		}
		if($cusold_conf_yes  == 'C3'){
			$chk_c3= '<img src="../_images/check_true.png" width=15px>';
			
		}else {
			$chk_c3 = '<img src="../_images/check_blank.png" width=15px>';
		}
		if($cusold_conf_yes  == 'C4'){
			$chk_c4= '<img src="../_images/check_true.png" width=15px>';
			
		}else {
			$chk_c4 = '<img src="../_images/check_blank.png" width=15px>';
		}
	}
	
	if ($crstm_chk_term  == 'old' || $crstm_chk_term  == 'change') {
		
		if($crstm_chk_term  == 'old'){
			$chk_old = '<img src="../_images/check_true.png" width=15px>';
		}else {
			$chk_old = '<img src="../_images/check_blank.png" width=15px>';
			$cus_terms_paymnt = "";
			$cus_terms_desc = "";
		}
		if($crstm_chk_term  == 'change'){
			$chk_change= '<img src="../_images/check_true.png" width=15px>';
		}else {
			$chk_change = '<img src="../_images/check_blank.png" width=15px>';
			$cus_terms_paymnt1 = "";
			$cus_terms_desc1 = "";
		}
	}
		
		if($dbd_conf_yes  == '1' || $dbd_conf_yes == '2'){
			$chk_dbd = '<img src="../_images/check_true.png" width=15px>';
			
		}else{
			$chk_dbd0 = '<img src="../_images/check_true.png" width=15px>';
		}
		if($crstm_mgr_rdo  == '1'){
			$chk_mgr = '<img src="../_images/check_true.png" width=15px>';
			$chk_mgr1 = '<img src="../_images/check_blank.png" width=15px>';
			
		}else{
			$chk_mgr1 = '<img src="../_images/check_true.png" width=15px>';
			$chk_mgr = '<img src="../_images/check_blank.png" width=15px>';
		}
	
	
	$params = array($crstm_nbr);	
	$query_detail = "SELECT tbl2_id, tbl2_nbr, tbl2_cus_nbr, tbl2_amt_loc_curr, tbl2_doc_date, tbl2_create_by, tbl2_create_date, tbl2_stamp_date FROM tbl2_mstr where tbl2_nbr = ?";
	$result = sqlsrv_query($conn, $query_detail,$params);
	$rec_result = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if ($rec_result) {
		$stamp1_date = $rec_result['tbl2_stamp_date'];
	}
	
	$params = array($crstm_nbr);
	$sql_cr= "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, ".
	         "tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr WHERE (tbl1_nbr = ?)";
	$result = sqlsrv_query($conn, $sql_cr,$params);
	$tot_acc = 0;
		while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			{
				$tot_acc = $row_cr['tbl1_amt_loc_curr'];
				$chk_name = $row_cr['tbl1_txt_ref'];
				$acc_name = $row_cr['tbl1_acc_name'];
				$stamp_date = $row_cr['tbl1_stamp_date'];
				if($chk_name=="CI") {
					$tot_ci = $tot_ac;
				
				}
			}	
	
	/* if ($crstm_nbr == "WORK_CR_NUMBER") {
		$qtm_title = "ใบขออนุมัติวงเงินสินเชื่อ";
	} */
	if($cus_conf_yes=="1") {
		$chkbox_cus = "วงเงินลูกค้าเดิม" ;
	}else {
		$chkbox_cus = "วงเงินลูกค้าใหม่" ;
	}
	
	$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".   
				"<td colspan=10 align=center style='font-size:8pt'width=60% line-height: 1.8><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h3></td>".
			"</tr>".
			"<tr>".   
				"<td colspan=10 align=center style='line-height: 1.8'><h3>$head_app</h3></td>".
			"</tr>".
		"</table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=8></td>".
			"</tr>" .
		"</table>";
		
		/* "<table><tr><td calpan=10 width=100%></td></tr></table>". 
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".
				"<td style='font-size:8pt; border-bottom:0px;' bgcolor=gray align=left colspan=2 width=30%><h4>1. สำหรับหน่วยงานขาย</h4></td>".
			"</tr>" .
		"</table>".
	
		"<table><tr><td calpan=10 width=100%></td></tr></table>". 
		
		"<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20> ชื่อลูกค้า :$crstm_cus_name</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=10% height=20>รหัส : $crstm_cus_nbr</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=25>ประเทศ / จังหวัด : $addr</td>".
				"<td align=right style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=16% height=20>เอกสารเลขที่ : $crstm_nbr</td>".
			"</tr>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=16% height=20>ประมาณการขายเฉลี่ยต่อเดือน : $crstm_sd_per_mm &nbsp;บาท</td>".
			"</tr>".
		"</table>".	
		
		"<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;'width=16% height=20>$chk_0  วงเงินลูกค้าใหม่</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$chk_1  วงเงินลูกค้าเก่า</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$chk_c4  วงเงินใหม่</td>".
				"<td align=left  style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$chk_c1  ปรับเพิ่มวงเงิน</td>".
				"<td align=left  style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$chk_c3  ต่ออายุวงเงิน</td>".
			"</tr>".
		"</table>".		
		
		"<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;'width=16% height=20>$chk_old  เงื่อนไขการชำระเดิม  :&nbsp;&nbsp;&nbsp;$cus_terms_paymnt $cus_terms_desc</td>".
			"</tr>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;'width=1% height=20>$chk_change  ขอเปลี่ยนเงื่อนไขการชำระเงินใหม่จาก  :  &nbsp;&nbsp;&nbsp;$cus_terms_paymnt1 $cus_terms_desc1 &nbsp;&nbsp;&nbsp;เป็น  :  &nbsp;&nbsp;&nbsp;$crstm_ch_term_desc</td>".
			"</tr>".
		"</table>"; */
	
	$project_head=
/*  		"<table><tr><td calpan=10 width=100%></td></tr></table>".
 */ 		"<table width=100% border=1 style='border-collapse: collapse; font-size:8pt'>".
		"<tr>".
			"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' height=20><br>ข้อมูลโครงการ (ถ้ามี)</td>".
		"</tr>".
			"<tr>".   
				"<td align=center style='font-size:8pt' width=35% height=20>ชื่อโครงการ</td>".
				"<td align=center style='font-size:8pt' width=20% height=20>จังหวัด</td>".
				"<td align=center style='font-size:8pt' width=20% height=20>มูลค่างาน (บาท)</td>".
				"<td align=center style='font-size:8pt' width=35% height=20>เงื่อนไขการชำระเงิน</td>".
				"<td align=center style='font-size:8pt' width=15% height=20>ระยะเวลา (เดือน)</td>".	
				"<td align=center style='font-size:8pt' width=15% height=20>เริ่มใช้งาน</td>".	
		"</tr>".
		"</table>";
	
	$head_cc= 
		"<tr>".   
				"<td align=center style='font-size:6.5pt' width=35% height=20>ขออนุมัติปรับวงเงินสินเชื่อ(Clean Credit)</td>".
				"<td align=center style='font-size:6.5pt' width=20% height=20>เริ่ม</td>".
				"<td align=center style='font-size:6.5pt' width=20% height=20>สิ้นสุด</td>".
				"<td align=center style='font-size:6.5pt' width=25% height=20>วงเงิน (บาท)</td>".	
		"</tr>".
	
	
	
		require_once('../_libs/mpdf/mpdf.php');
		$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
		$pdf->SetTitle('ใบขออนุมัติวงเงินสินเชื่อ Clean Credit');
		$pdf->SetHTMLHeader($header);
		$pdf->SetFooter("Continue Next Page");
		//$pdf->SetHTMLFooter('<div style="text-align:left;font-size:6pt;">MS-F013  :  Rev. No. 03  :  อายุการจัดเก็บ  1  ปี</div>');
		//$pdf->SetHTMLFooter($footpage);
		$pdf->AddPage('', // L - landscape, P - portrait 
		
        '', '', '1', '0',
        5, // margin_left
        5, // margin right
        25, // margin top   20
        68, // margin bottom 40
        5, // margin header
        5); // margin footer	
		
		$data = "";
		$max_line = 30;
		$line_cnt = 0;
	 
	
	$row_rpt = "<style>".
		".rpt {".
		
		"border: 1px dotted;".
			"border-left: 1px solid gray;".
			"font-size: 6pt;".
			"}".
		".rpt_gl {".
			"border: 1px dotted;".
			"border-left: 1px solid gray;".
			"font-size: 8pt;".
		"}".
		".rpt_last_col {".
			"border: 1px solid;".
			"border-left: 1px solid gray;".
			"border-right: 1px solid gray;".
			"font-size: 8pt;".
		"}".
		".rpt_format_col {".
			"border: 1px solid;".
			"border-left: 1px solid gray;".
			"border-right: 1px  solid gray;".
			"font-size: 8pt;".
		"}".
		".table_std {".
			"border: 1px solid;".
			"border-left: 1px solid gray;".
			"border-right: 1px  solid gray;".
			"font-size: 8pt;".
		"}".
			
	"</style>";
	$vertical_text = "<style type='text/css'>".
		"body{".
		"	font-size:12px;". 
		"}".
		".textAlignVer{".
		"	display:block;".
		"	filter: flipv fliph;".
		"	-webkit-transform: rotate(-90deg);". 
		"	-moz-transform: rotate(-90deg); ".
		"	transform: rotate(-90deg);". 
		"	position:relative;".
		"	width:20px;".
		"	white-space:nowrap;".
		"	font-size:12px;".
		"	margin-bottom:10px;".
		"}".
	"</style>";
	
	$cus_detail=
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".
				"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=2 width=30%><h4>1. สำหรับหน่วยงานขาย</h4></td>".
			"</tr>" .
		"</table>".
		
		/* "<table><tr><td calpan=10 width=100%></td></tr></table>". */ 
		
		"<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20> ชื่อลูกค้า :$crstm_cus_name</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=10% height=20>รหัส : $crstm_cus_nbr</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=25>ประเทศ / จังหวัด : $addr</td>".
				"<td align=right style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=16% height=20>เอกสารเลขที่ : $crstm_nbr</td>".
			"</tr>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=16% height=5>ประมาณการขายเฉลี่ยต่อเดือน : $crstm_sd_per_mm &nbsp;บาท</td>".
			"</tr>".
		"</table>".	
		
		"<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;'width=16% height=20>$chk_0  วงเงินลูกค้าใหม่</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$chk_1  วงเงินลูกค้าเดิม</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$chk_c4  วงเงินใหม่</td>".
				"<td align=left  style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$chk_c1  ปรับเพิ่มวงเงิน</td>".
				"<td align=left  style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$chk_c3  ต่ออายุวงเงิน</td>".
			"</tr>".
		"</table>".		
		
		"<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;'width=16% height=20>$chk_old  เงื่อนไขการชำระเงินเดิม  :&nbsp;&nbsp;&nbsp;$cus_terms_paymnt $cus_terms_desc &nbsp;&nbsp;&nbsp; และ :  &nbsp;&nbsp;&nbsp;$term_add &nbsp;&nbsp;&nbsp; $term_desc_add</td>".
			"</tr>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;'width=1% height=20>$chk_change  ขอเปลี่ยนเงื่อนไขการชำระเงินใหม่จาก  :  &nbsp;&nbsp;&nbsp;$cus_terms_paymnt1 $cus_terms_desc1 &nbsp;&nbsp;&nbsp;เป็น :   &nbsp;&nbsp;&nbsp;$crstm_ch_term $crstm_ch_term_desc</td>".
			"</tr>".
		"</table>"; 
	$pdf->WriteHTML($cus_detail);
	
 	$pdf->WriteHTML($row_rpt);
	/* $pdf->WriteHTML("<table><tr><td calpan=10 width=100%></td></tr></table>"); 
	$pdf->WriteHTML("<table><tr><td calpan=10 width=100%></td></tr></table>");
	$pdf->WriteHTML("<table><tr><td calpan=10 width=100%></td></tr></table>"); 
	$pdf->WriteHTML("<table><tr><td calpan=10 width=100%></td></tr></table>");*/
	$pdf->WriteHTML("<table><tr><td calpan=10 width=100%></td></tr></table>");
	$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>");
	
	$pdf->WriteHTML($head_cc);
	
			$params = array($crstm_nbr);
			$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? ";
			$result_cc = sqlsrv_query($conn, $sql_cc,$params);
			$grand_ord = 0;
			while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC)) {		
				$sum_ord = html_escape($row_cc['tbl3_amt_loc_curr']);
				$txt_ref = html_escape($row_cc['tbl3_txt_ref']);
				$doc_date = html_escape(dmytx($row_cc['tbl3_doc_date']));
				$due_date = html_escape(dmytx($row_cc['tbl3_due_date']));
				$grand_ord = $grand_ord + $sum_ord;
			if ($txt_ref == "C1") {
					$acc_txt = "เสนอขอปรับเพิ่มวงเงิน";
				} else if ($txt_ref == "C2") {
					$acc_txt = "เสนอขอปรับลดวงเงิน";
				} else if ($txt_ref == "C3") {
					$acc_txt = "เสนอขอต่ออายุวงเงิน";	
				} else {
					$acc_txt = "วงเงินปัจจุบัน";
				}
				$total_amt_loc_curr += $sum_ord + $crstm_cc_amt;
			//}
	$data_detail= 
		"<tr>".   
				"<td align=center style='font-size:6.5pt' height=25 >$acc_txt</td>".
				"<td align=center style='font-size:6.5pt' height=25>$doc_date</td>".
				"<td align=center style='font-size:6.5pt' height=25 >$due_date</td>".
				"<td align=right style='font-size:6.5pt; padding-right:5;' height=20>".number_format($sum_ord)."</td>".	
		"</tr>";
	$pdf->WriteHTML($data_detail);
}

	$data_detail1= 
		"<tr>".   
			"<td colspan=3 align=center style='font-size:6.5pt' height=20>รวมวงเงินขออนุมัติ</td>".
			"<td align=right style='font-size:6.5pt; padding-right:5;' height=20>".number_format($grand_ord)."</td>".	
		"</tr>";
	$pdf->WriteHTML($data_detail1);
 	$pdf->WriteHTML("</table>"); 
	$pdf->WriteHTML("<tr><td height=10><font style='font-size:3px'>.</font><td></tr>");
 	
	$pdf->WriteHTML($row_rpt);
	$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>"); 
	$body_sd_reson=
		"<tr>".
			"<td style='font-size:7pt ;border-right:0px;border-bottom:0px' align=left height=35 width=20%>ความเห็น / เหตุผลที่เสนอขอวงเงิน :</td>".
			
			"<td colspan=5 style='font-size:7pt;border-left:0px;border-bottom:1px dotted;' height=20>$crstm_sd_reson</td>".
		"</tr>".
		 "<tr>".
			"<td colspan=6 style='font-size:7pt border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;'  height=5></td>".
		"</tr>";
		
		/* "<tr>".
			"<td colspan=6 style='font-size:7pt border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;'   height=20></td>".
		"</tr>".  */
		
		/* "<tr>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:1px solid;border-bottom:0px solid;' height=20><br>ข้อมูลโครงการ (ถ้ามี)</td>".
		"</tr>"; */
	$pdf->WriteHTML($body_sd_reson);
	$pdf->WriteHTML("</table>");
	$pdf->WriteHTML($vertical_text);
	
	$project_detail=
	
	"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			
			"<tr>".   
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=20>$crstm_pj_name</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_pj_prv</td>".
				"<td align=right style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; padding-right:5;' width=20% height=20>$crstm_pj_amt</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=20>$crstm_pj_term_desc</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=20>$crstm_pj_dura</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=20>$crstm_pj_beg</td>".
			"</tr>".
			"<tr>".   
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=35% height=20>$crstm_pj1_name</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=20% height=20>$crstm_pj1_prv</td>".
				"<td align=right style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid; padding-right:5;' width=20% height=20>$crstm_pj1_amt</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=35% height=20>$crstm_pj1_term_desc</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=15% height=20>$crstm_pj1_dura</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=15% height=20>$crstm_pj1_beg</td>".
			"</tr>".
			"</table>";
		
		
	$pdf->WriteHTML($project_head);
	$pdf->WriteHTML($project_detail);
	//$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; font-size:6pt'>");
	
	//// หาค่า max / month
	/* $params = array($crstm_nbr);
	$sql_bll= "SELECT TOP (6) tbl2_id, tbl2_nbr, tbl2_cus_nbr, tbl2_amt_loc_curr, tbl2_doc_date, tbl2_create_by, tbl2_create_date, tbl2_stamp_date ".
	          "FROM tbl2_mstr  WHERE (tbl2_nbr = ?)	ORDER BY tbl2_amt_loc_curr DESC";
	$result_bll = sqlsrv_query($conn, $sql_bll,$params);
					$tot_max_amt = 0 ;
					$tot_ord = 0;
					$noo = 0 ;
					while($row_bll = sqlsrv_fetch_array($result_bll, SQLSRV_FETCH_ASSOC))
					{
						$tot_ord = $row_bll['tbl2_amt_loc_curr'];
						if($noo==0) {
							$tot_max_amt = $tot_ord; 
						}
						$noo = $noo +1;
					}	 */
	$params = array($crstm_nbr);				
	$sql_bll= "SELECT TOP (6) tbl2_id, tbl2_nbr, tbl2_cus_nbr, tbl2_amt_loc_curr, tbl2_doc_date, tbl2_create_by, tbl2_create_date,tbl2_stamp_date ".
					  "FROM tbl2_mstr WHERE (tbl2_nbr = ?) ORDER BY tbl2_doc_date desc ";
			$result_bll = sqlsrv_query($conn, $sql_bll,$params);
					$tot_amt = 0 ;
					$sum_no_curr = 0;
					$no = 0 ;
					$a_amt = array();
					$a_month = array();
					while($row_bll = sqlsrv_fetch_array($result_bll, SQLSRV_FETCH_ASSOC))
					{
						$tot_amt = $row_bll['tbl2_amt_loc_curr'];
						$tot_ord = number_format($row_bll['tbl2_amt_loc_curr']);
						$bll_ym = $row_bll['tbl2_doc_date'];
						$stamp1_date = $row_bll['tbl2_stamp_date'];
						$bll_doc_ym1 = substr($bll_ym,0,4); //2021
						$bll_doc_ym2 = substr($bll_ym,-2);  //03
						
						if ($curMonth != $bll_ym) {
							$sum_no_curr += $tot_amt;
						}
						
						$a_amt[$no] = $tot_amt;	
						$a_month[$no] = $bll_ym;	
						//$sum_ord += $tot_amt;
						$no = $no + 1;
					}
						
						//$sum_org =  $a_amt[0] +$a_amt[1] +  $a_amt[2] +  $a_amt[3] +  $a_amt[4]  + $a_amt[5] ; 
						/// ไม่เอาเดือนปัจจุบันมารวม [0]
						$sum_org =  $a_amt[0] +$a_amt[1] +  $a_amt[2] +  $a_amt[3] +  $a_amt[4]  + $a_amt[5] ;
						if ($sum_org>0) {
							$max_amt = max($a_amt); // หาค่า max ใน array
						}

						if($sum_no_curr!=0) {
							$sum_avg = ($sum_no_curr / 5);
						}
	
	$body3_head =
	"<table><tr><td calpan=10 width=100%></td></tr></table>".
	
		"<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>".
		"<tr>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=70% height=20>ประวัติการซื้อสินค้า 6 เดือนที่ผ่านมา ณ  วันที่  $stamp1_date</td>".
			"<td align=right style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' height=20>หน่วย (บาท)</td>".
		"</tr>".
		
		"</table>";
			
	$pdf->WriteHTML($body3_head);
	
	$body3_detail=
		"<table width=100% border=1 style='border-collapse: collapse; font-size:11pt'>".
			"<tr>".   
 				/* "<td align=center style='font-size:11pt' width=20% height=30>ปี / เดือน</td>". */
  	            "<td align=center style='font-size:11pt' width=20% height=30>$a_month[5]</td>".
				"<td align=center style='font-size:11pt' width=20% height=30>$a_month[4]</td>".
				"<td align=center style='font-size:11pt' width=20% height=30>$a_month[3]</td>".	
				"<td align=center style='font-size:11pt' width=20% height=30>$a_month[2]</td>".	
				"<td align=center style='font-size:11pt' width=20% height=30>$a_month[1]</td>".
				"<td align=center style='font-size:11pt' width=20% height=30>$a_month[0]</td>".
				"<td align=center style='font-size:11pt' width=20% height=30>Total</td>".	
				"<td align=center style='font-size:11pt' width=20% height=30>Max/Month</td>".	
				"<td align=center style='font-size:11pt' width=20% height=30>Avg/Month</td>".	
		"</tr>".
			"<tr>".   
				/* "<td align=center style='font-size:11pt' width=17.1% height=30>$bll_doc_ym1</td>". */
				"<td align=center style='font-size:11pt' width=19% height=30>".number_format($a_amt[5])."</td>".
				"<td align=center style='font-size:11pt' width=20% height=30>".number_format($a_amt[4])."</td>".
				"<td align=center style='font-size:11pt' width=20% height=30>".number_format($a_amt[3])."</td>".	
				"<td align=center style='font-size:11pt' width=20% height=30>".number_format($a_amt[2])."</td>".	
				"<td align=center style='font-size:11pt' width=20% height=30>".number_format($a_amt[1])."</td>".		
				"<td align=center style='font-size:11pt' width=20% height=30>".number_format($a_amt[0])."</td>".	
				"<td align=center style='font-size:11pt' width=20% height=30>".number_format($sum_org)."</td>".	
				"<td align=center style='font-size:11pt' width=20% height=30>".number_format($max_amt)."</td>".	
				"<td align=center style='font-size:11pt' width=20% height=30>".number_format($sum_avg)."</td>".	
		"</tr>".
		"</table>";
	$pdf->WriteHTML($body3_detail);
	
	$body3_signature=
	"<table><tr><td calpan=10 width=100%></td></tr></table>".
		"<table width=100% border=0 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".  
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
			"</tr>".
			"<tr>". 
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_name<br><br></td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>ผจก.ภาค<br><brtd>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>ผจส.<br><br></td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>ผู้ขอเสนอวงเงิน ( วันที่ $crstm_date )</td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>วันที่..............................................</td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>วันที่..............................................</td>".
			"</tr>".
			
		"</table>";
	$pdf->WriteHTML($body3_signature);
	
	$body4_head =
	"<table><tr><td calpan=10 width=100%></td></tr></table>".
	
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
		/* "<tr>".
			"<td style='font-size:8pt; border-bottom:0px;' align=left></td>".
		"</tr>".  */
		
		"<tr>".
			// "<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=4 width=30% height=20>2. สำหรับหน่วยงานสินเชื่อ</td>".
			"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=4 width=30%><h4>2. สำหรับหน่วยงานสินเชื่อ</h4></td>".

		"</tr>".
		"<tr>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=45% height=20>ข้อมูลวงเงิน, หนี้ และ ประวัติการชำระเงิน ณ  วันที่ $stamp_date</td>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=45% height=20>งบการเงิน &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $chk_dbd0 ไม่มี   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $chk_dbd มี      ณ. ปี  $crstm_dbd_yy</td>".
			"<td align=right style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' height=20>หน่วย (บาท)</td>".
		"</tr>".
		"</table>";
	$pdf->WriteHTML($body4_head);
	
	$params = array($crstm_nbr);
	$sql_cr= "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, ".
	         "tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr WHERE (tbl1_nbr = ?)";
	$result = sqlsrv_query($conn, $sql_cr,$params);
	$tot_acc = 0;$tot_ovr = 0;
		while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			{
				$chk_name = rtrim($row_cr['tbl1_txt_ref']);
				$acc_name = $row_cr['tbl1_acc_name'];
				switch($chk_name) {
					case "CI":
						$tot_ci = $row_cr['tbl1_amt_loc_curr'];
						break;
					case "BG":	
						$tot_bg = $row_cr['tbl1_amt_loc_curr'];
						break;
					case "CC":		
						$tot_cc = $row_cr['tbl1_amt_loc_curr'];
						break;
					case "AR":
						$tot_ar = $row_cr['tbl1_amt_loc_curr'];	
						break;
					case "ORD":
						$tot_ord = $row_cr['tbl1_amt_loc_curr'];	
						break;
					case "Overdue":	
						$tot_ovr = $row_cr['tbl1_amt_loc_curr'];
						
						if($tot_ovr >= 0) {
							$tot_ovr = number_format($tot_ovr);
						} 
						else {
							$tot_ovr = ($tot_ovr * -1);
							$tot_ovr = "(".(number_format($tot_ovr)).")";
						}
						break;
					default:
						if($tot_ovr=0) {$tot_ovr=0;}
						break;	
				}	
				$line_cnt++;
			}	
			
			$t5_tot =($tot_cc+$tot_bg)-($tot_ar+$tot_ord);
			if($t5_tot<0) {
				$t5_tot = "(".(number_format($t5_tot * -1)).")";
			}else{
				$t5_tot = number_format($t5_tot);
			}
	
	$body6_detail=
/* 		"<table><tr><td calpan=10 width=100%></td></tr></table>".
 */		"<table width=100% border=1 style='border-collapse: collapse; font-size:11pt'>".
		"<tr>".
				"<td align=center rowspan=2 style='font-size:11pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid dotted;' width=15% height=22>Insurance</td>".
				"<td align=center colspan=2	style='font-size:11pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid dotted;' width=20% height=30>วงเงินสินเชื่อปัจจุบัน	</td>".
				"<td align=center rowspan=2	style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' 		width=20% height=22>ยอดใช้วงเงิน </td>".
				"<td align=center rowspan=2	style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;'  		width=20% height=22>วงเงิน  <br>คงเหลือ/(เกิน)</td>".
				"<td align=center rowspan=2	style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' 		width=20% height=22>ใบสั่งซื้อระหว่าง <br>ดำเนินการ </td>".
				"<td align=center rowspan=2 style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' 		width=20% height=22>AR <br>Balance</td>".
				"<td align=center rowspan=2	style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' 		width=20% height=22>หนี้ค้างชำระ<br>เกินกำหนด</td>".
				"<td align=center colspan=2 style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' 		width=30% height=22>ประวัติการชำระเงิน (%ตรงเวลา)</td>".
		"</tr>".
		"<tr>".
				"<td align=center style='font-size:11pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid dotted;' width=14% height=30>BG</td>".
				"<td align=center style='font-size:11pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid dotted;' width=14% height=30>Clean Credit	</td>".
 				
				"<td align=center style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' 		width=20% height=30>ปี $crstm_pre_yy</td>".
				"<td align=center style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' 		width=20% height=30>ปี $crstm_cur_yy</td>".  
				
		"</tr>". 
		"<tr>".
				"<td align=right style='font-size:11pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid dotted; padding-right:5' width=20% height=30>$crstm_ins</td>".
				"<td align=right style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid; padding-right:5' width=20% height=30>".number_format($tot_bg)."</td>".
				"<td align=right style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid; padding-right:5' width=20% height=30>".number_format($tot_cc)."</td>".
				"<td align=right style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid; padding-right:5' height=30>".number_format($tot_ar+$tot_ord)."</td>".
				//"<td align=right style='font-size:10pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;'>".number_format(($tot_cc+$tot_bg)-($tot_ar+$tot_ord))."</td>".
				"<td align=right style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid; padding-right:5' height=30>".$t5_tot."</td>".
				"<td align=right style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid; padding-right:5' height=30>".number_format($tot_ord)."</td>".
				"<td align=right style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid; padding-right:5' height=30>".number_format($tot_ar)."</td>".
				"<td align=right style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid; padding-right:5' height=30>".$tot_ovr."</td>".
				"<td align=center style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' height=30>".$txt_cr."</td>". 
				"<td align=center style='font-size:11pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' height=30>".$txt_cr1."</td>". 
			"</tr>".
		"</table>";
			
	$pdf->WriteHTML($body6_detail);
	$pdf->WriteHTML("</table>"); 
	$pdf->WriteHTML("<tr><td height=5><font style='font-size:3px'>.</font><td></tr>"); 
	
	$pdf->WriteHTML($row_rpt);
	$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>");
	$body_sd1_reson=
		
		"<tr>".
			"<td style='font-size:7pt ;border-right:0px;border-bottom:0px' align=left height=35 width=40%>ความเห็นสินเชื่อ :</td>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:1px solid;border-bottom:0px solid;' width=30% height=20>$chk_mgr เห็นควรอนุมัติ </td>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=30% height=20>$chk_mgr1  ไม่เห็นควรอนุมัติ</td>".

/* 			"<td colspan=5 style='font-size:8pt;border-left:0px;border-bottom:1px dotted;' height=20>$crstm_cc1_reson</td>".
 */		"</tr>".
		"<tr>".
			"<td colspan=6 style='font-size:9pt border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;'  height=20>ความเห็นสินเชื่อ 1 : $crstm_cc1_reson</td>".
		"</tr>".
		"<tr>".
			"<td colspan=6 style='font-size:9pt border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;'  height=20>ความเห็นสินเชื่อ 2 : $crstm_cc2_reson</td>".
		"</tr>".
		
		"<tr>".
			"<td colspan=6 style='font-size:9pt border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;'   height=20>ความเห็น Manager :$crstm_mgr_reson</td>".
		"</tr>";
	
	$pdf->WriteHTML($body_sd1_reson);
	$pdf->WriteHTML("</table>");
	$pdf->WriteHTML($vertical_text);
	
	/* $cc_signature=
	
	"<table><tr><td calpan=10 width=100%></td></tr></table>".
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".  
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
			"</tr>".
			"<tr>". 
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>ศุภิสรา  เชาวนาสถาพร<br><br></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>ผลาทิพย์  เติมสุขนิรันดร<br><brtd>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>นุชนารถ  วุฑฒินันท์<br><br></td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ ( วันที่  $curdate )</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ (  วันที่ $curdate )</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>Finance & Credit Manager ( วันที่ $curdate )</td>".
			"</tr>".
			
		"</table>";
			
	$pdf->WriteHTML($cc_signature); */
			
				/* if ($line_cnt % $max_line == 0) {
					if ($line_cnt < $total_record) {
						$pdf->WriteHTML("</table>");
						$pdf->AddPage();
						$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>");

					}
					else {
						$pdf->SetFooter("แผ่นที่: {PAGENO}/{nbpg}");
					}
				}
				else {
					if ($line_cnt >= $total_record) {
						$pdf->SetFooter("แผ่นที่: {PAGENO}/{nbpg}");		
					}	
				} */
	
	
	
	
	// $pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>");
	// $pdf->WriteHTML("</table>"); 
	
/// เช็คลูกค้าในเครือ ใช่หรือไม่ 

if($cus_acc_group=="ZC01" || $cus_acc_group=="ZC07" ) {
			$footer=
			$cc_signature=
			"<table><tr><td calpan=10 width=100%></td></tr></table>".
			"<table><tr><td calpan=10 width=100%></td></tr></table>".
			"<table><tr><td calpan=10 width=100%></td></tr></table>".
				"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
					"<tr>".  
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
					"</tr>".
					"<tr>". 
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
					"</tr>".
					"<tr>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_cr1 <br><br></td>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_cr2 <br><brtd>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_mgr <br><br></td>".
					"</tr>".
					"<tr>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ ( วันที่  $crstm_create_cr1_date )</td>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ (  วันที่ $crstm_create_cr2_date )</td>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>Finance & Credit Manager ( วันที่ $crstm_create_mgr_date )</td>".
					"</tr>".
					
				"</table>".
					
			//$pdf->WriteHTML($cc_signature);
			"<table><tr><td calpan=10 width=100%></td></tr></table>".
			
			"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
				"<tr>".
				//"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=4 width=30% height=20>3. สำหรับผู้อนุมัติ</td>".
				"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=2 width=30%><h4>3. สำหรับผู้อนุมัติ</h4></td>".

				"</tr>".
			"</table>".
			
			// อนก. คณะกรรมการบริหารอนุมัติ
			"<table width=100% border=1 style='border-collapse: collapse; font-size:8pt'>".
				"<tr>".
					"<td align=left style='font-size:9pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid; padding-left:10;' colspan=7 width=15% height=30>อำนาจดำเนินการ  : $crstm_approve</td>".
				"</tr>".
				"<tr>".
					"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=15% height=30></td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=15% height=30></td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=15% height=30>สถานะ</td>".

					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=35% height=30>ชื่อผู้ดำเนินการ</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=20% height=30>วันที่</td>".
					"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid; padding-left:10;' width=50% height=30>วงเงิน  <u><</u> 700,000 บาท  :  ผจส. อนุมัติ</td>".
					"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:1px dotted;border-bottom:0px solid;' width=30% height=30>วงเงิน  <u><</u> 2 ล้านบาท  :  ผฝ. อนุมัติ</td>".
				"</tr>" .
				"<tr>".
					"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>ผู้พิจารณา  1:</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>Reviewer 1</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve_reviewer </td>".

					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$reviewer_nme</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_reviewer_date</td>".
					"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=50% height=30>วงเงิน  <u><</u> 5 ล้าน  บาท  :  CO. อนุมัติ</td>".
					"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=30% height=30>วงเงิน  <u><</u> 7 ล้าน  บาท  :  กจก. อนุมัติ</td>".
				"</tr>" .
				"<tr>".
					"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>ผู้พิจารณา  2:</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>Reviewer 2</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve_reviewer2 </td>".

					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$reviewer_nme2</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_reviewer2_date</td>".
					"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=60% height=30>วงเงิน  <u><</u> 10 ล้าน บาท  คณะกรรมการสินเชื่ออนุมัติ </td>".
					"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=30% height=30></td>".
				"</tr>" .
				"<tr>".
					"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>ผู้อนุมัติ :</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$author_sign1</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve1 </td>".

					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$author_sign_nme1</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_stamp_app1_date</td>".
					"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=65% height=30>วงเงิน > 10 ล้าน บาท  คณะกรรมการบริหารอนุมัติ </td>".
					"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=30% height=30></td>".
				"</tr>" .
				"<tr>".
					"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30></td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$author_sign2</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve2 </td>".

					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$author_sign_nme2</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_stamp_app2_date</td>".
					"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=65% height=30></td>".
					"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=30% height=30></td>".
				"</tr>" .
				"<tr>".
					"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=15% height=30></td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=15% height=30>$author_sign3</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=15% height=30>$approve3 </td>".

					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=35% height=30>$author_sign_nme3</td>".
					"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1x dotted;' width=20% height=30>$crstm_stamp_app3_date</td>".
					"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted; padding-left:10;' width=50% height=30></td>".
					"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;' width=50% height=30></td>".
				"</tr>" .
				"<tr>".
					"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid; padding-left:10;'  colspan=5 height=10>*หมายเหตุ : คณะกรรมการบริหาร ได้แก่ $foot_sign_nme3 ($foot_sign3),$foot_sign_nme1 ($foot_sign1), $foot_sign_nme2 ($foot_sign2)</td>".

					"<td align=left style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid; padding-left:10;' width=50% height=30></td>".
					"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=50% height=30></td>".
				"</tr>" .
			"</table>". 

			$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:8pt'>.</font><td></tr>");
			$pdf->SetHTMLFooter($footer);
}else {
		$footer=
		$cc_signature=
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
			"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
				"<tr>".  
					"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
					"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
					"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
				"</tr>".
				"<tr>". 
					"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
					"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
					"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
				"</tr>".
				"<tr>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_cr1<br><br></td>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_cr2<br><brtd>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_mgr<br><br></td>".
				"</tr>".
				"<tr>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ ( วันที่  $crstm_create_cr1_date )</td>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ (  วันที่ $crstm_create_cr2_date )</td>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>Finance & Credit Manager ( วันที่ $crstm_create_mgr_date )</td>".
				"</tr>".
				
			"</table>".
				
		//$pdf->WriteHTML($cc_signature);
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".
			//"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=4 width=30% height=20>3. สำหรับผู้อนุมัติ</td>".
			"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=2 width=30%><h4>3. สำหรับผู้อนุมัติ</h4></td>".

			"</tr>".
		"</table>".
		
		// อนก. คณะกรรมการบริหารอนุมัติ
		"<table width=100% border=1 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".
				"<td align=left style='font-size:9pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid; padding-left:10;' colspan=7 width=15% height=30>อำนาจดำเนินการ : $crstm_approve</td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=15% height=30></td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=15% height=30></td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=15% height=30>สถานะ</td>".

				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=35% height=30>ชื่อผู้ดำเนินการ</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=20% height=30>วันที่</td>".
				"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid; padding-left:10;' width=40% height=30>วงเงิน  <u><</u> 500,000 บาท  :  ผผ. อนุมัติ</td>".
				"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:1px dotted;border-bottom:0px solid;' width=60% height=30>วงเงิน  <u><</u> 3 ล้านบาท  :  ผจส. อนุมัติ</td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>ผู้พิจารณา 1 :</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>Reviewer 1</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve_reviewer </td>".

				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$reviewer_nme</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_reviewer_date</td>".
				"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=40% height=30>วงเงิน  <u><</u> 13 ล้าน  บาท  :  ผฝ. อนุมัติ</td>".
				"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=60% height=30>วงเงิน  <u><</u> 25 ล้าน  บาท  :  CO. อนุมัติ</td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>ผู้พิจารณา 2 :</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>Reviewer 2</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve_reviewer2 </td>".

				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$reviewer_nme2</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_reviewer2_date</td>".
				"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=40% height=30>วงเงิน  <u><</u> 50 ล้าน บาท  กจก. อนุมัติ </td>".
				"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=60% height=30>วงเงิน > 50 ล้าน บาท  คณะกรรมการบริหารอนุมัติ </td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>ผู้อนุมัติ :</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$author_sign1</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve1 </td>".

				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$author_sign_nme1</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_stamp_app1_date</td>".
				"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=40% height=30></td>".
				"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=60% height=30></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30></td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$author_sign2</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve2 </td>".

				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$author_sign_nme2</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_stamp_app2_date</td>".
				"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=40% height=30></td>".
				"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=60% height=30></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=15% height=30></td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=15% height=30>$author_sign3</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=15% height=30>$approve3 </td>".

				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=35% height=30>$author_sign_nme3</td>".
				"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1x dotted;' width=20% height=30>$crstm_stamp_app3_date</td>".
				"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted; padding-left:10;' width=40% height=30></td>".
				"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;' width=60% height=30></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid; padding-left:10;'  colspan=5 height=10>*หมายเหตุ : คณะกรรมการบริหาร ได้แก่ $foot_sign_nme3 ($foot_sign3),$foot_sign_nme2 ($foot_sign2), $foot_sign_nme1 ($foot_sign1)</td>".

				"<td align=left style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid; padding-left:10;' width=50% height=30></td>".
				"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=50% height=30></td>".
			"</tr>" .
		"</table>". 

		$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:8pt'>.</font><td></tr>");
		$pdf->SetHTMLFooter($footer);
}
		if ($savefile) {
			//SAVE FILE
			$output_folder = $output_folder; 
			$output_filename = $cr_output_filename;
			if (file_exists($output_folder.$output_filename)) {
			unlink($output_folder.$output_filename);
			}
			$pdf->Output($output_folder.$output_filename,'F');
		}
		else {
			$pdf->Output();
		}
			
		return $output_filename;	
 //$pdf->Output();
}

///// ฟอร์มลูกค้าใหม่
function printpageform_new($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn) {
date_default_timezone_set('Asia/Bangkok');
$curdate = date("d/m/Y");	
	
	$params = array($crstm_nbr);
	
	$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_tax_nbr3, ".
                      "crstm_mstr.crstm_address, crstm_mstr.crstm_district, crstm_mstr.crstm_amphur, crstm_mstr.crstm_province, crstm_mstr.crstm_zip, crstm_mstr.crstm_country, crstm_mstr.crstm_chk_rdo1, ".
                      "crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_per_mm, ".
                      "crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_reson_img, crstm_mstr.crstm_pj_name, crstm_mstr.crstm_pj_prv, crstm_mstr.crstm_pj_term, crstm_mstr.crstm_pj_amt, crstm_mstr.crstm_pj_dura, ".
                      "crstm_mstr.crstm_pj_beg, crstm_mstr.crstm_pj_img, crstm_mstr.crstm_pj1_name, crstm_mstr.crstm_pj1_prv, crstm_mstr.crstm_pj1_term, crstm_mstr.crstm_pj1_amt, crstm_mstr.crstm_pj1_dura, ".
                      "crstm_mstr.crstm_pj1_beg, crstm_mstr.crstm_pj1_img, crstm_mstr.crstm_pre_yy, crstm_mstr.crstm_otd_pct, crstm_mstr.crstm_ovr_due, crstm_mstr.crstm_etc, crstm_mstr.crstm_cur_yy, ".
                      "crstm_mstr.crstm_otd1_pct, crstm_mstr.crstm_ovr1_due, crstm_mstr.crstm_etc1, crstm_mstr.crstm_ins, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cr1_img, crstm_mstr.crstm_dbd_rdo, ".
                      "crstm_mstr.crstm_dbd_yy, crstm_mstr.crstm_dbd_img, crstm_mstr.crstm_dbd1_yy, crstm_mstr.crstm_dbd1_img, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cr2_img, ".
                      "crstm_mstr.crstm_mgr_reson, crstm_mstr.crstm_mgr_rdo, crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_mgr_img, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, ".
                      "crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_create_by, crstm_mstr.crstm_create_date, crstm_mstr.crstm_update_by, crstm_mstr.crstm_update_date, crstm_mstr.crstm_step_code, ".
                      "crstm_mstr.crstm_step_name, crstm_mstr.crstm_whocanread, crstm_mstr.crstm_curprocessor, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_create_by_cr1, crstm_mstr.crstm_create_cr1_date, ".
                      "crstm_mstr.crstm_create_by_cr2, crstm_mstr.crstm_create_cr2_date, crstm_mstr.crstm_create_by_mgr, crstm_mstr.crstm_create_mgr_date, crstm_mstr.crstm_rem_rearward, ".
                      "crstm_mstr.crstm_chk_rearward, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, crstm_mstr.crstm_reviewer, crstm_mstr.crstm_reviewer_date, crstm_mstr.crstm_reviewer2, ".
                      "crstm_mstr.crstm_reviewer2_date, crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, crstm_mstr.crstm_email_app3, crstm_mstr.crstm_stamp_app1, crstm_mstr.crstm_stamp_app1_date, ".
                      "crstm_mstr.crstm_stamp_app2, crstm_mstr.crstm_stamp_app2_date,crstm_mstr.crstm_stamp_app3, crstm_mstr.crstm_stamp_app3_date, crstm_mstr.crstm_fin_app_date, crstm_mstr.crstm_fin_app_by, crstm_mstr.crstm_scgc ".
					  "FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id WHERE (crstm_mstr.crstm_nbr = ?)";
					 
	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$crstm_name = trim($rec_cus["emp_th_firstname"]) . " " . trim($rec_cus["emp_th_lastname"]);	
		$crstm_cus_nbr = html_clear($rec_cus['crstm_cus_nbr']);
		$crstm_date = html_escape(dmytx($rec_cus['crstm_date']));
		$crstm_create_cr1_date = html_escape(dmytx($rec_cus['crstm_create_cr1_date']));
		$crstm_create_cr2_date = html_escape(dmytx($rec_cus['crstm_create_cr2_date']));
		$crstm_create_mgr_date = html_escape(dmytx($rec_cus['crstm_create_mgr_date']));
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_province = html_clear($rec_cus['crstm_province']);
		$crstm_country = html_clear($rec_cus['crstm_country']);
		$addr = $crstm_country." / ".$crstm_province;
		$cus_terms_paymnt = html_clear($rec_cus['cus_terms_paymnt']);
		
		$cus_terms_desc = "(".$rec_cus['term_desc'].")";
		//$cus_terms_desc = "(".$cus_terms_desc.")";
		
		$cus_terms_paymnt1 = html_clear($rec_cus['cus_terms_paymnt']);
		$cus_terms_desc1 = "(".$rec_cus['term_desc'].")";
		
		$cus_acc_group = html_clear($rec_cus['cus_acc_group']);
		
		/// radio 
		$cus_conf_yes = html_clear($rec_cus['crstm_chk_rdo1']);
		$cusold_conf_yes = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
		
		$term_add = html_clear($rec_cus['crstm_term_add']);
		$term_desc_add = findsqlval("term_mstr", "term_desc", "term_code", $term_add ,$conn);
		if($term_desc_add!="") {$term_desc_add = "(".$term_desc_add.")";}
		
		$crstm_ch_term = html_clear($rec_cus['crstm_ch_term']);
		$crstm_ch_term_desc = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);
		if($crstm_ch_term_desc!=""){$crstm_ch_term_desc = "(".$crstm_ch_term_desc.")";}
		
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		$crstm_sd_per_mm = number_format($rec_cus['crstm_sd_per_mm']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);

		$crstm_pj_name = html_clear($rec_cus['crstm_pj_name']);
		$crstm_pj_amt = number_format($rec_cus['crstm_pj_amt']);
		$crstm_pj_prv = html_clear($rec_cus['crstm_pj_prv']);
		$crstm_pj_term = html_clear($rec_cus['crstm_pj_term']);
		$crstm_pj_dura = html_clear($rec_cus['crstm_pj_dura']);
		if ($crstm_pj_dura=="13") {
			$crstm_pj_dura = "มากกว่า 12 เดือน";
		}else {$crstm_pj_dura = $crstm_pj_dura;}
		
		$crstm_pj_beg = dmytx($rec_cus['crstm_pj_beg']);
		$crstm_pj_term_desc = findsqlval("term_mstr", "term_desc", "term_code", $crstm_pj_term ,$conn);
		
		$crstm_pj1_name = html_clear($rec_cus['crstm_pj1_name']);
		$crstm_pj1_amt = number_format($rec_cus['crstm_pj1_amt']);
		$crstm_pj1_prv = html_clear($rec_cus['crstm_pj1_prv']);
		$crstm_pj1_term = html_clear($rec_cus['crstm_pj1_term']);
		$crstm_pj1_dura = html_clear($rec_cus['crstm_pj1_dura']);
		if ($crstm_pj1_dura=="13") {
			$crstm_pj1_dura = "มากกว่า 12 เดือน";
		}else {$crstm_pj1_dura = $crstm_pj1_dura;}
		
		$crstm_pj1_beg = dmytx($rec_cus['crstm_pj1_beg']);
		$crstm_pj1_term_desc = findsqlval("term_mstr", "term_desc", "term_code", $crstm_pj1_term ,$conn);
		
		$crstm_pre_yy = html_clear($rec_cus['crstm_pre_yy']);
		$crstm_otd_pct = html_clear($rec_cus['crstm_otd_pct']);
		$crstm_ovr_due = html_clear($rec_cus['crstm_ovr_due']);
		$crstm_etc = html_clear($rec_cus['crstm_etc']);
		$crstm_cur_yy = html_clear($rec_cus['crstm_cur_yy']);
		$crstm_otd1_pct = html_clear($rec_cus['crstm_otd1_pct']);
		$crstm_ovr1_due = html_clear($rec_cus['crstm_ovr1_due']);
		$crstm_etc1 = html_clear($rec_cus['crstm_etc1']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_ins = html_clear($rec_cus['crstm_ins']);
		
		$dbd_conf_yes = html_clear($rec_cus['crstm_dbd_rdo']);
		$crstm_dbd_yy = html_clear($rec_cus['crstm_dbd_yy']);
		$crstm_dbd1_yy = html_clear($rec_cus['crstm_dbd1_yy']);
		if($crstm_dbd_yy == ""){$crstm_dbd_yy = $crstm_dbd1_yy;}
		
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_mgr_rdo = html_clear($rec_cus['crstm_mgr_rdo']);
		
		$crstm_cc_date_beg = dmytx($rec_cus['crstm_cc_date_beg']);
		$crstm_cc_date_end = dmytx($rec_cus['crstm_cc_date_end']);
		$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);

		$crstm_step_code = html_clear($rec_cus['crstm_step_code']);
		$crstm_reviewer_date = html_escape(dmytx($rec_cus['crstm_reviewer_date']));
		$crstm_reviewer2_date = html_escape(dmytx($rec_cus['crstm_reviewer2_date']));
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_stamp_app1_date = html_escape(dmytx($rec_cus['crstm_stamp_app1_date']));
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$crstm_stamp_app2_date = html_escape(dmytx($rec_cus['crstm_stamp_app2_date']));
		$crstm_email_app3 = html_clear($rec_cus['crstm_email_app3']);
		$crstm_stamp_app3_date = html_escape(dmytx($rec_cus['crstm_stamp_app3_date']));
		$crstm_fin_app_date = html_escape(dmytx($rec_cus['crstm_fin_app_date']));

		$crstm_reviewer = html_clear($rec_cus['crstm_reviewer']);
		$crstm_reviewer2 = html_clear($rec_cus['crstm_reviewer2']);

		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		
		if($crstm_reviewer!=""){
			$reviewer_nme = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_reviewer,$conn);	
		}
		if ($crstm_reviewer_date != "") { 
			$approve_reviewer = "*** Approved ***";
			$crstm_reviewer_date = $crstm_reviewer_date;
		}else {
			$approve_reviewer = "-"; $reviewer_nme = "-"; $crstm_reviewer_date = "-";
		}
		
		if($crstm_reviewer2!=""){
			$reviewer_nme2 = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_reviewer2,$conn);	
			if ($crstm_reviewer2_date != "") { 
				$approve_reviewer2 = "*** Approved ***";
				$crstm_reviewer2_date = $crstm_reviewer2_date;
			}else if($crstm_reviewer2_date == NULL) { 
				$reviewer_nme2 = $reviewer_nme2;
			}
			
		}else {
			$approve_reviewer2 = "-"; $reviewer_nme2 = "-"; $crstm_reviewer2_date = "-";
		}
		
		if($crstm_cr_mgr <= 2000000) {
			$params = array($crstm_email_app1);
			$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
			$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
			$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
			if ($rec_emp) {
				$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
				$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
				$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
				$author_sign_nme1 = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname  ;
				} 
		}	
	
		$foot_sign_nme1 = findsqlval("sign_mstr","sign_name","sign_code","01",$conn);
		$foot_sign1 = findsqlval("sign_mstr","sign_text","sign_code","01",$conn);
	
		$foot_sign_nme2 = findsqlval("sign_mstr","sign_name","sign_code","02",$conn);
		$foot_sign2 = findsqlval("sign_mstr","sign_text","sign_code","02",$conn);
	
		$foot_sign_nme3 = findsqlval("sign_mstr","sign_name","sign_code","03",$conn);
		$foot_sign3 = findsqlval("sign_mstr","sign_text","sign_code","03",$conn);
	
		switch ($crstm_approve) {
		case "คณะกรรมการบริหารอนุมัติ":
			//$author_sign_nme1 = findsqlval("sign_mstr","sign_name","sign_code","01",$conn);
			//$author_sign1 = findsqlval("sign_mstr","sign_text","sign_code","01",$conn);
			//$author_sign1 = "NM";
			//$author_sign_nme1 = "คุณนำพล มลิชัย";

			// $author_sign_nme1 --> กจก.
			if ($crstm_scgc == true) { 
				//$author_sign_nme1 = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_email_app1,$conn);	
				$author_sign_nme1 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
				$author_sign1 = findsqlval_ZC("author_mstr", "author_sign", "author_email", $crstm_email_app1, "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme1 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
				$author_sign1 = findsqlval_ZC("author_mstr", "author_sign", "author_email", $crstm_email_app1, "author_text",  $crstm_approve ,$conn);
			}
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign_nme2 = findsqlval("sign_mstr","sign_name","sign_code","02",$conn);
			$author_sign2 = findsqlval("sign_mstr","sign_text","sign_code","02",$conn);
			//$author_sign2 = "SK";
			//$author_sign_nme2 = "คุณสุรศักดิ์ ไกรวิทย์ชัยเจริญ";

			$author_sign_nme3 = findsqlval("sign_mstr","sign_name","sign_code","03",$conn);
			$author_sign3 = findsqlval("sign_mstr","sign_text","sign_code","03",$conn);
			//$author_sign3 = "NP";
			//$author_sign_nme3 = "คุณนิธิ ภัทรโชค";
			if (inlist("61",$crstm_step_code)) { 
				$approve1 = "*** Approved ***"; 
				 $crstm_stamp_app2_date = "";
				 $crstm_stamp_app3_date = "";
			}
			if ($crstm_fin_app_date != "") {
				 $approve1 = "*** Approved ***";
				 $approve2 = "*** Approved ***";
				 $approve3 = "*** Approved ***"; 
				 $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";
				 $crstm_stamp_app2_date = $crstm_fin_app_date;
				 $crstm_stamp_app3_date = $crstm_fin_app_date;
			}
			break;
		case "คณะกรรมการสินเชื่ออนุมัติ":
			$author_sign1 = "กจก";
			//$author_sign_nme1 = "คุณนำพล มลิชัย";
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;			
			// $author_sign_nme1  --> กจก.			
			if ($crstm_scgc == true) { 
				$author_sign_nme1 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme1 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}

			$author_sign2 = "CFO";
			//$author_sign_nme2 = "คุณวรนันท์ โสดานิล (CFO)";
			$crstm_stamp_app2_date = $crstm_stamp_app2_date;
			if ($crstm_scgc == true) { 
				$author_sign_nme2 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app2 , "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme2 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app2 , "author_text", $crstm_approve ,$conn);
			}

			$author_sign3 = "CMO";
			//$author_sign_nme3 = "คุณสิทธิชัย สุขกิจประเสริฐ (CMO)";
			//$author_sign_nme3 = findsqlval("author_mstr","author_sign_nme","author_code","CFO",$conn);
			$crstm_stamp_app3_date = $crstm_stamp_app3_date;		
			if ($crstm_scgc == true) { 
				$author_sign_nme3 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app3 , "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme3 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app3 , "author_text", $crstm_approve ,$conn);
			}
			if (inlist("60",$crstm_step_code)) { 
				$approve1 = "*** Approved ***"; 
				$approve2 = "*** Approved ***"; 
				$approve3 = "*** Approved ***"; 
				$head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";
			}
			//$author_sign3 = "";
			//$author_sign_nme3 = "";
			break;	
		case "กจก. อนุมัติ":
			$author_sign1 = "กจก.";
			//$author_sign_nme1 = "คุณนำพล มลิชัย";
			if ($crstm_scgc == true) { 
				$author_sign_nme1 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme1 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}
			
			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";}
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;
			
			$author_sign2 = "";
			$author_sign_nme2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$crstm_stamp_app3_date = "";

			break;
		case "CO. อนุมัติ":
			$author_sign1 = "CO";
			//$author_sign_nme1 = "คุณสิทธิชัย สุขกิจประเสริฐ";
			if ($crstm_scgc == true) { 
				$author_sign_nme1 = findsqlval_ZC("author_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}
			else {
				$author_sign_nme1 = findsqlval_ZC("author_g_mstr", "author_sign_nme", "author_email", $crstm_email_app1 , "author_text", $crstm_approve ,$conn);
			}
			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>"; }
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign2 = "";
			$author_sign_nme2 = "";
			$approve2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$approve3 = "";
			$crstm_stamp_app3_date = "";
			break;
		case "ผฝ. อนุมัติ":
			$author_sign1 = "ผฝ.";
			$author_sign_nme1 = $author_sign_nme1 ;

			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>"; }
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign2 = "";
			$author_sign_nme2 = "";
			$approve2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$approve3 = "";
			$crstm_stamp_app3_date = "";
			break;	
		case "ผส. อนุมัติ":
			$author_sign1 = "ผส.";
			$author_sign_nme1 = $author_sign_nme1 ;

			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";}
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign2 = "";
			$author_sign_nme2 = "";
			$approve2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$approve3 = "";
			$crstm_stamp_app3_date = "";
			break;
		case "ผผ. อนุมัติ":
			$author_sign1 = "ผผ.";
			$author_sign_nme1 = $author_sign_nme1 ;

			if (inlist("60",$crstm_step_code)) { $approve1 = "*** Approved ***";  $head_app = "<font color='green'>*** ได้รับการอนุมัติแล้ว *** <font>";}
			$crstm_stamp_app1_date = $crstm_stamp_app1_date;

			$author_sign2 = "";
			$author_sign_nme2 = "";
			$approve2 = "";
			$crstm_stamp_app2_date = "";

			$author_sign3 = "";
			$author_sign_nme3 = "";
			$approve3 = "";
			$crstm_stamp_app3_date = "";
			break;				
		}
		
		if ($crstm_otd_pct != "") {
			 $txt_cr = $crstm_otd_pct ;  
		} else if ($crstm_ovr_due != "") {
			$txt_cr = $crstm_ovr_due ;
		} else {	
			$txt_cr = $crstm_etc ;
		} 
		
		if ($crstm_otd1_pct != "") {
			 $txt_cr1 = $crstm_otd1_pct ;  
		} else if ($crstm_ovr1_due != "") {
			$txt_cr1 = $crstm_ovr1_due ;
		} else {	
			$txt_cr1 = $crstm_etc1 ;
		} 
	}	
		
	if ($cus_conf_yes  == '0' || $cus_conf_yes  == '1') {
		
		if($cus_conf_yes  == '0'){
			$chk_0 = '<img src="../_images/check_true.png" width=15px>';
			
		}else {
		$chk_0 = '<img src="../_images/check_blank.png" width=15px>';
		}
		if($cus_conf_yes  == '1'){
			$chk_1= '<img src="../_images/check_true.png" width=15px>';
			
		}else {
			$chk_1 = '<img src="../_images/check_blank.png" width=15px>';
		}
	}
	
		
		if($cusold_conf_yes  == 'C4'){
			$chk_c4 = '<img src="../_images/check_true.png" width=15px>';
			
		}else {
			$chk_c4 = '<img src="../_images/check_blank.png" width=15px>';
		}
		
	
	if ($crstm_chk_term  == 'old' || $crstm_chk_term  == 'change') {
		
		if($crstm_chk_term  == 'old'){
			$chk_old = '<img src="../_images/check_true.png" width=15px>';
		}else {
			$chk_old = '<img src="../_images/check_blank.png" width=15px>';
			$cus_terms_paymnt = "";
			$cus_terms_desc = "";
		}
		if($crstm_chk_term  == 'change'){
			$chk_change= '<img src="../_images/check_true.png" width=15px>';
		}else {
			$chk_change = '<img src="../_images/check_blank.png" width=15px>';
			$cus_terms_paymnt1 = "";
			$cus_terms_desc1 = "";
		}
	}
		
		if($dbd_conf_yes  == '1' || $dbd_conf_yes == '2'){
			$chk_dbd = '<img src="../_images/check_true.png" width=15px>';
			
		}else{
			$chk_dbd0 = '<img src="../_images/check_true.png" width=15px>';
		}
		if($crstm_mgr_rdo  == '1'){
			$chk_mgr = '<img src="../_images/check_true.png" width=15px>';
			$chk_mgr1 = '<img src="../_images/check_blank.png" width=15px>';
			
		}else{
			$chk_mgr1 = '<img src="../_images/check_true.png" width=15px>';
			$chk_mgr = '<img src="../_images/check_blank.png" width=15px>';
		}
	
	
	$params = array($crstm_nbr);	
	$query_detail = "SELECT tbl2_id, tbl2_nbr, tbl2_cus_nbr, tbl2_amt_loc_curr, tbl2_doc_date, tbl2_create_by, tbl2_create_date, tbl2_stamp_date FROM tbl2_mstr where tbl2_nbr = ?";
	$result = sqlsrv_query($conn, $query_detail,$params);
	$rec_result = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if ($rec_result) {
		$stamp1_date = $rec_result['tbl2_stamp_date'];
	}
	
	$params = array($crstm_nbr);
	$sql_cr= "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, ".
	         "tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr WHERE (tbl1_nbr = ?)";
	$result = sqlsrv_query($conn, $sql_cr,$params);
	$tot_acc = 0;
		while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			{
				$tot_acc = $row_cr['tbl1_amt_loc_curr'];
				$chk_name = $row_cr['tbl1_txt_ref'];
				$acc_name = $row_cr['tbl1_acc_name'];
				$stamp_date = $row_cr['tbl1_stamp_date'];
				if($chk_name=="CI") {
					$tot_ci = $tot_ac;
				
				}
			}	
	
	if($cus_conf_yes=="1") {
		$chkbox_cus = "วงเงินลูกค้าเดิม" ;
	}else {
		$chkbox_cus = "วงเงินลูกค้าใหม่" ;
	}
	
	$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".   
				"<td colspan=10  align=center style='font-size:8pt'width=60%><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h2></td>".
			"</tr>".
			"<tr>".   
				"<td colspan=10 align=center style='line-height: 1.8'><h3>$head_app</h3></td>".
			"</tr>".
		"</table>";
		
		/* "<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=20></td>".
			"</tr>" .
		"</table>"; */
		
	
	$project_head=
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
		"<table width=100% border=1 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".   
				"<td align=center style='font-size:8pt' width=35% height=20>ชื่อโครงการ</td>".
				"<td align=center style='font-size:8pt' width=20% height=20>จังหวัด</td>".
				"<td align=center style='font-size:8pt' width=20% height=20>มูลค่างาน (บาท)</td>".
				"<td align=center style='font-size:8pt' width=35% height=20>เงื่อนไขการชำระเงิน</td>".
				"<td align=center style='font-size:8pt' width=15% height=20>ระยะเวลา (เดือน)</td>".	
				"<td align=center style='font-size:8pt' width=15% height=20>เริ่มใช้งาน</td>".	
		"</tr>".
		"</table>";
	
	$head_cc= 
		"<tr>".   
				"<td align=center style='font-size:6.5pt' width=35% height=20>ขออนุมัติปรับวงเงินสินเชื่อ(Clean Credit)</td>".
				"<td align=center style='font-size:6.5pt' width=20% height=20>เริ่ม</td>".
				"<td align=center style='font-size:6.5pt' width=20% height=20>สิ้นสุด</td>".
				"<td align=center style='font-size:6.5pt' width=25% height=20>วงเงิน (บาท)</td>".	
		"</tr>".
	
	
	
		require_once('../_libs/mpdf/mpdf.php');
		$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
		$pdf->SetTitle('ใบขออนุมัติวงเงินสินเชื่อ Clean Credit');
		$pdf->SetHTMLHeader($header);
		$pdf->SetFooter("Continue Next Page");
		//$pdf->SetHTMLFooter('<div style="text-align:left;font-size:6pt;">MS-F013  :  Rev. No. 03  :  อายุการจัดเก็บ  1  ปี</div>');
		//$pdf->SetHTMLFooter($footpage);
		$pdf->AddPage('', // L - landscape, P - portrait 
		
         '', '', '1', '0',
        5, // margin_left
        5, // margin right
        25, // margin top   20
        68, // margin bottom 40
        5, // margin header
        5); // margin footer	
		
		$data = "";
		$max_line = 30;
		$line_cnt = 0;
	 
	
	$row_rpt = "<style>".
		".rpt {".
		
		"border: 1px dotted;".
			"border-left: 1px solid gray;".
			"font-size: 6pt;".
			"}".
		".rpt_gl {".
			"border: 1px dotted;".
			"border-left: 1px solid gray;".
			"font-size: 8pt;".
		"}".
		".rpt_last_col {".
			"border: 1px solid;".
			"border-left: 1px solid gray;".
			"border-right: 1px solid gray;".
			"font-size: 8pt;".
		"}".
		".rpt_format_col {".
			"border: 1px solid;".
			"border-left: 1px solid gray;".
			"border-right: 1px  solid gray;".
			"font-size: 8pt;".
		"}".
		".table_std {".
			"border: 1px solid;".
			"border-left: 1px solid gray;".
			"border-right: 1px  solid gray;".
			"font-size: 8pt;".
		"}".
			
	"</style>";
	$vertical_text = "<style type='text/css'>".
		"body{".
		"	font-size:12px;". 
		"}".
		".textAlignVer{".
		"	display:block;".
		"	filter: flipv fliph;".
		"	-webkit-transform: rotate(-90deg);". 
		"	-moz-transform: rotate(-90deg); ".
		"	transform: rotate(-90deg);". 
		"	position:relative;".
		"	width:20px;".
		"	white-space:nowrap;".
		"	font-size:12px;".
		"	margin-bottom:10px;".
		"}".
	"</style>";
	
	$cus_detail=
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".
				"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=2 width=30%><h4>1. สำหรับหน่วยงานขาย</h4></td>".
			"</tr>" .
		"</table>".
		
		"<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20> ชื่อลูกค้า :$crstm_cus_name</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=10% height=20>รหัส : $crstm_cus_nbr</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=25>ประเทศ / จังหวัด : $addr</td>".
				"<td align=right style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=16% height=20>เอกสารเลขที่ : $crstm_nbr</td>".
			"</tr>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=16% height=5>ประมาณการขายเฉลี่ยต่อเดือน : $crstm_sd_per_mm &nbsp;บาท</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;'width=16% height=20>$chk_0  วงเงินลูกค้าใหม่</td>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$chk_c4   วงเงินใหม่</td>".
			"</tr>".
		"</table>".	
		
		"<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".
				"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;'width=16% height=20>$chk_old  เงื่อนไขการชำระเดิม  :  &nbsp;&nbsp;&nbsp;$term_add &nbsp;&nbsp;&nbsp; $term_desc_add</td>".
 			"</tr>".
		"</table>";
	
	$pdf->WriteHTML($cus_detail);
	
 	$pdf->WriteHTML($row_rpt);
	$pdf->WriteHTML("<table><tr><td calpan=10 width=100%></td></tr></table>");
	$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>");
	
	$pdf->WriteHTML($head_cc);
	
			$params = array($crstm_nbr);
			//$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? ";
			$sql_cc= "SELECT crstm_nbr, crstm_cus_nbr, crstm_cus_name, crstm_cc_date_beg, crstm_cc_date_end, crstm_cc_amt, crstm_cus_active FROM crstm_mstr WHERE (crstm_nbr = ? and crstm_cus_active = 0)";
			
			$result_cc = sqlsrv_query($conn, $sql_cc,$params);
			$grand_ord = 0;
			while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC)) {		
				$sum_ord = html_escape($row_cc['crstm_cc_amt']);
				//$txt_ref = html_escape($row_cc['tbl3_txt_ref']);
				$doc_date = html_escape(dmytx($row_cc['crstm_cc_date_beg']));
				$due_date = html_escape(dmytx($row_cc['crstm_cc_date_end']));
				$grand_ord = $grand_ord + $sum_ord;
			
				$acc_txt = "เสนอขออนุมัติวงเงิน";
				
				$total_amt_loc_curr += $sum_ord + $crstm_cc_amt;
			//}
	$data_detail= 
		"<tr>".   
				"<td align=center style='font-size:6.5pt' height=20 >$acc_txt</td>".
				"<td align=center style='font-size:6.5pt' height=20 >$doc_date</td>".
				"<td align=center style='font-size:6.5pt' height=20 >$due_date</td>".
				"<td align=right style='font-size:6.5pt; padding-right:5;' height=20>".number_format($sum_ord)."</td>".	
		"</tr>";
	$pdf->WriteHTML($data_detail);
}

	/* $data_detail1= 
		"<tr>".   
			"<td colspan=3 align=center style='font-size:6.5pt' height=20>รวมวงเงินขออนุมัติ</td>".
			"<td align=right style='font-size:6.5pt' height=20>".number_format($grand_ord)."</td>".	
		"</tr>";
	$pdf->WriteHTML($data_detail1); */
 	$pdf->WriteHTML("</table>"); 
	$pdf->WriteHTML("<tr><td height=10><font style='font-size:3px'>.</font><td></tr>");
 	
	$pdf->WriteHTML($row_rpt);
	$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>"); 
	$body_sd_reson=
		"<tr>".
			"<td style='font-size:7pt ;border-right:0px;border-bottom:0px' align=left height=35 width=20%>ความเห็น / เหตุผลที่เสนอขอวงเงิน :</td>".
			
			"<td colspan=5 style='font-size:7pt;border-left:0px;border-bottom:1px dotted;' height=20>$crstm_sd_reson</td>".
		"</tr>".
		 "<tr>".
			"<td colspan=6 style='font-size:7pt border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;'  height=5></td>".
		"</tr>".
		
		"<tr>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:1px solid;border-bottom:0px solid;' height=20><br>ข้อมูลโครงการ (ถ้ามี)</td>".
		"</tr>";
	$pdf->WriteHTML($body_sd_reson);
	$pdf->WriteHTML("</table>");
	$pdf->WriteHTML($vertical_text);
	
	$project_detail=
	
	"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			
			"<tr>".   
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=20>$crstm_pj_name</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_pj_prv</td>".
				"<td align=right style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; padding-right:5;' width=20% height=20>$crstm_pj_amt</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=20>$crstm_pj_term_desc</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=20>$crstm_pj_dura</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=20>$crstm_pj_beg</td>".
			"</tr>".
			"<tr>".   
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=35% height=20>$crstm_pj1_name</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=20% height=20>$crstm_pj1_prv</td>".
				"<td align=right style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid; padding-right:5;' width=20% height=20>$crstm_pj1_amt</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=35% height=20>$crstm_pj1_term_desc</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=15% height=20>$crstm_pj1_dura</td>".
				"<td align=center style='font-size:8pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;' width=15% height=20>$crstm_pj1_beg</td>".
			"</tr>".
			"</table>";
		
		
	$pdf->WriteHTML($project_head);
	$pdf->WriteHTML($project_detail);
	
	
	$body3_signature=
	"<table><tr><td calpan=10 width=100%></td></tr></table>".
		"<table width=100% border=0 style='border-collapse: collapse; font-size:7pt'>".
			"<tr>".  
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
			"</tr>".
			"<tr>". 
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_name<br><br></td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>ผจก.ภาค<br><brtd>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>ผจส.<br><br></td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>ผู้ขอเสนอวงเงิน ( วันที่ $crstm_date )</td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>วันที่..............................................</td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>วันที่..............................................</td>".
			"</tr>".
			
		"</table>";
	$pdf->WriteHTML($body3_signature);
	
	$body4_head =
	"<table><tr><td calpan=10 width=100%></td></tr></table>".
	
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
		"<tr>".
			//"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=4 width=30% height=20>2. สำหรับหน่วยงานสินเชื่อ</td>".
			"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=4 width=30%><h4>2. สำหรับหน่วยงานสินเชื่อ</h4></td>".
		"</tr>".
		"<tr>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=45% height=20>งบการเงิน  &nbsp;&nbsp;  มี : $chk_dbd : ณ. ปี  $crstm_dbd_yy</td>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=45% height=20></td>".
			"<td align=right style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' height=20>หน่วย (บาท)</td>".
		"</tr>".
		"</table>";
	$pdf->WriteHTML($body4_head);
	
	$pdf->WriteHTML($row_rpt);
	$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; font-size:7pt'>");
	$body_sd1_reson=
		
		"<tr>".
			"<td style='font-size:7pt ;border-right:0px;border-bottom:0px' align=left height=35 width=40%>ความเห็นแผนกสินเชื่อ :</td>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:0px solid;border-top:1px solid;border-bottom:0px solid;' width=30% height=20>$chk_mgr เห็นควรอนุมัติ </td>".
			"<td align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=30% height=20>$chk_mgr1 ไม่เห็นควรอนุมัติ</td>".
 		"</tr>".
		"<tr>".
			"<td colspan=6 style='font-size:9pt border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;'  height=20>ความเห็นสินเชื่อ 1 : $crstm_cc1_reson</td>".
		"</tr>".
		"<tr>".
			"<td colspan=6 style='font-size:9pt border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;'  height=20>ความเห็นสินเชื่อ 2 : $crstm_cc2_reson</td>".
		"</tr>".
		
		"<tr>".
			"<td colspan=6 style='font-size:9pt border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;'   height=20>Finance & Credit Manager :$crstm_mgr_reson</td>".
		"</tr>";
	
	$pdf->WriteHTML($body_sd1_reson);
	$pdf->WriteHTML("</table>");
	$pdf->WriteHTML($vertical_text);
	
	$cc_signature=
	
	"<table><tr><td calpan=10 width=100%></td></tr></table>".
		"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
			"<tr>".  
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
			"</tr>".
			"<tr>". 
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_cr1<br><br></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_cr2<br><brtd>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_mgr<br><br></td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ ( วันที่  $crstm_create_cr1_date )</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ (  วันที่ $crstm_create_cr2_date )</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>Finance & Credit Manager ( วันที่ $crstm_create_mgr_date )</td>".
			"</tr>".
			
		"</table>";
			
	$pdf->WriteHTML($cc_signature); 
	
/// เช็คลูกค้าในเครือ ใช่หรือไม่ 
$footer=
$cc_signature=
"<table><tr><td calpan=10 width=100%></td></tr></table>".
"<table><tr><td calpan=10 width=100%></td></tr></table>".
"<table><tr><td calpan=10 width=100%></td></tr></table>".
	"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
		"<tr>".  
			"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
			"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
			"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=20></td>".
		"</tr>".
		"<tr>". 
			"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
			"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
			"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
		"</tr>".
		"<tr>".
			"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_cr1<br><br></td>".
			"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_cr2<br><brtd>".
			"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$crstm_create_by_mgr<br><br></td>".
		"</tr>".
		"<tr>".
			"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ ( วันที่  $crstm_create_cr1_date )</td>".
			"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>เจ้าหน้าที่สินเชื่อ (  วันที่ $crstm_create_cr2_date )</td>".
			"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>Finance & Credit Manager ( วันที่ $crstm_create_mgr_date )</td>".
		"</tr>".
		
	"</table>".
		
//$pdf->WriteHTML($cc_signature);
"<table><tr><td calpan=10 width=100%></td></tr></table>".

"<table width=100% border=0 style='border-collapse: collapse; font-size:8pt'>".
	"<tr>".
	//"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=4 width=30% height=20>3. สำหรับผู้อนุมัติ</td>".
	"<td style='font-size:8pt; border-bottom:0px;' bgcolor=LightGray align=left colspan=2 width=30%><h4>3. สำหรับผู้อนุมัติ</h4></td>".

	"</tr>".
"</table>".

// อนก. คณะกรรมการบริหารอนุมัติ
"<table width=100% border=1 style='border-collapse: collapse; font-size:8pt'>".
	"<tr>".
		"<td align=left style='font-size:9pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid; padding-left:10;' colspan=7 width=15% height=30>อำนาจดำเนินการ : $crstm_approve</td>".
	"</tr>".
	"<tr>".
		"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=15% height=30></td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=15% height=30></td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=15% height=30>สถานะ</td>".

		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=35% height=30>ชื่อผู้ดำเนินการ</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid;' width=20% height=30>วันที่</td>".
		"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:1px dotted;border-bottom:0px solid; padding-left:10;' width=50% height=30>วงเงิน  <u><</u> 700,000 บาท  :  ผจส. อนุมัติ</td>".
		"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:1px dotted;border-bottom:0px solid;' width=30% height=30>วงเงิน  <u><</u> 2 ล้านบาท  :  ผฝ. อนุมัติ</td>".
	"</tr>" .
	"<tr>".
		"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>ผู้พิจารณา 1 :</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>Reviewer 1</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve_reviewer </td>".

		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$reviewer_nme</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_reviewer_date</td>".
		"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=50% height=30>วงเงิน  <u><</u> 5 ล้าน  บาท  :  CO. อนุมัติ</td>".
		"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=30% height=30>วงเงิน  <u><</u> 7 ล้าน  บาท  :  กจก. อนุมัติ</td>".
	"</tr>" .
	"<tr>".
		"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>ผู้พิจารณา 2 :</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>Reviewer 2</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve_reviewer2 </td>".

		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$reviewer_nme2</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_reviewer2_date</td>".
		"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=60% height=30>วงเงิน  <u><</u> 10 ล้าน บาท  คณะกรรมการสินเชื่ออนุมัติ </td>".
		"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=30% height=30></td>".
	"</tr>" .
	"</tr>" .
	"<tr>".
		"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>ผู้อนุมัติ :</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$author_sign1</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve1 </td>".

		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$author_sign_nme1</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_stamp_app1_date</td>".
		"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=65% height=30>วงเงิน > 10 ล้าน บาท  คณะกรรมการบริหารอนุมัติ </td>".
		"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=30% height=30></td>".
	"</tr>" .
	"<tr>".
		"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30></td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$author_sign2</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=30>$approve2 </td>".

		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=35% height=30>$author_sign_nme2</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=30>$crstm_stamp_app2_date</td>".
		"<td align=left style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=65% height=30></td>".
		"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; padding-left:10;' width=65% height=30></td>".
	"</tr>" .
	"<tr>".
		"<td align=center style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=15% height=30></td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=15% height=30>$author_sign3</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=15% height=30>$approve3 </td>".

		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;' width=35% height=30>$author_sign_nme3</td>".
		"<td align=center style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1x dotted;' width=20% height=30>$crstm_stamp_app3_date</td>".
		"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted; padding-left:10;' width=50% height=30></td>".
		"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;' width=50% height=30></td>".
	"</tr>" .
	"<tr>".
		"<td align=left style='font-size:9pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid; padding-left:10;'  colspan=5 height=10>*หมายเหตุ : คณะกรรมการบริหาร ได้แก่ $foot_sign_nme3 ($foot_sign3),$foot_sign_nme1 ($foot_sign1), $foot_sign_nme2 ($foot_sign2)</td>".

		"<td align=left style='font-size:9pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid; padding-left:10;' width=50% height=30></td>".
		"<td align=left style='font-size:9pt; border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=50% height=30></td>".
	"</tr>" .
"</table>". 

$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:8pt'>.</font><td></tr>");
$pdf->SetHTMLFooter($footer);

		if ($savefile) {
			//SAVE FILE
			$output_folder = $output_folder; 
			$output_filename = $cr_output_filename;
			if (file_exists($output_folder.$output_filename)) {
			unlink($output_folder.$output_filename);
			}
			$pdf->Output($output_folder.$output_filename,'F');
		}
		else {
			$pdf->Output();
		}
			
		return $output_filename;	
 //$pdf->Output();
}


?>