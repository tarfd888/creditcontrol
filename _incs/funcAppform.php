<?php
function printMailapp($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn) {
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y");	
	$curMonth = date('Y-m'); 
		
	if (isservonline($smtp)) { $can_sendmail=true;}
	else {
		$can_sendmail=false;
		$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
	}

	$params = array($crstm_nbr);
	$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, emp_mstr.emp_prefix_th_name, emp_mstr.emp_th_firstname, ".
	"emp_mstr.emp_th_lastname, emp_mstr.emp_email_bus, emp_mstr.emp_th_pos_name, crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add,  ".
	"crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active,  ".
	"crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt,crstm_mstr.crstm_detail_mail, crstm_mstr.crstm_mgr_reson,  ".
	"crstm_mstr.crstm_mail_status, crstm_mstr.crstm_reviewer, crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc,crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, ".
	"cus_mstr.cus_terms_paymnt FROM crstm_mstr INNER JOIN  ".
	"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id INNER JOIN ".
	"cus_mstr ON crstm_mstr.crstm_cus_nbr = cus_mstr.cus_nbr ".
	"WHERE (crstm_mstr.crstm_nbr = ?)"; 

	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
		$name_from = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
		$email_bus = strtolower($rec_cus['emp_email_bus']);
		$emp_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		//$crstm_sd_reson       =  str_replace(chr(13),"<br>",$crstm_sd_reson); // เว้นบรรทัดเหมือนหน้า web
		$crstm_chk_rdo2 = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_cr_mgr = html_clear(number_format($rec_cus['crstm_cr_mgr']));
		$crstm_cus_active = html_clear($rec_cus['crstm_cus_active']);
		$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
		$terms_paymnt = html_clear($rec_cus['cus_terms_paymnt']);
		
		$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);
		$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
		$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
		
		$crstm_ch_term =  html_clear($rec_cus['crstm_ch_term']);
		
		$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
		$crstm_reviewer2 = strtolower(html_clear($rec_cus['crstm_reviewer2']));
		$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$email_mgr = $user_email;
		
		/////////////$email_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg.","."credit@scg.com";
		//$email_app_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg;
		$email_app_to1 =  $crstm_email_app1;
		$email_app_to2 =  $crstm_email_app2;
		
		$crstm_cus_nbr =  html_clear($rec_cus['crstm_cus_nbr']);
		$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);		
		$old_term = findsqlval("term_mstr", "term_desc", "term_code", $terms_paymnt ,$conn);
	} 
		
		$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", '50' ,$conn);

		switch($crstm_approve) {
			case "ผผ. อนุมัติ":
				$author_to = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_email_app1,$conn);	
				$step_app = "60";
				break;
			case "ผส. อนุมัติ":
				$author_to = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_email_app1,$conn);	
				$step_app = "60";
				break;
			case "ผฝ. อนุมัติ":
				$author_to = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_email_app1,$conn);	
				$step_app = "60";
				break;	
			case "CO. อนุมัติ":
				if($crstm_scgc == true) {
					$author_to = findsqlval_aut("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval_aut("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$author_to = findsqlval("emp_mstr", "emp_th_pos_name", "emp_email_bus", $crstm_email_app1 ,$conn);
				$step_app = "60";
				break;
			case "กจก. อนุมัติ":
				if($crstm_scgc == true) {
					$author_to = findsqlval_aut("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval_aut("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "60";
				break;	
			case "คณะกรรมการสินเชื่ออนุมัติ":
				if($crstm_scgc == true) {
					$author_to = findsqlval_1("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval_1("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "600";
				break;		
			case "คณะกรรมการบริหารอนุมัติ":	
				if($crstm_scgc == true) {
					$author_to = findsqlval_1("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval_1("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "61";
				break;		
			}

	$params = array($crstm_nbr);
	$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? ";
	$result_cc = sqlsrv_query($conn, $sql_cc,$params);

	while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
	{
		$amt = html_clear($row_cc['tbl3_amt_loc_curr']);
		$txt_ref = html_clear($row_cc['tbl3_txt_ref']);
		
		$gr_tot +=  $amt ;
		if ($txt_ref == "C1") {
			$tot_c1 += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		} else if ($txt_ref == "C3"){
			$tot_cc += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}else if ($txt_ref == "CC"){
			$tot_cc += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}	
	}

	if($crstm_chk_term == "old") {  /// เคสเปลี่ยนเงื่อนไขการชำระเงิน
		$txt_term = "";		
	}else if($crstm_chk_term == "change"){
		$txt_term = "และขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก :  $old_term  เป็น  $change_term";
	}
	if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
		if($crstm_chk_rdo2=="C1"){ // ขอเพิ่มวงเงิน
			$subject = "เพื่อพิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name";
			if($crstm_approve != "คณะกรรมการบริหารอนุมัติ"){
				$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท <br></span> ";																															
			}else{
				$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดพิจารณาให้ความเห็นชอบก่อนนำเสนอ คบ.  พิจารณาอนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท  หลังจาก คก.สช. ให้ความเห็นชอบ ทางสินเชื่อจะเสนอ memo ให้ทาง คบ. อนุมัติ อีกครั้ง<br></span> ";																															
			}						
		}else {  //ขอต่ออายุวงเงิน
			$subject ="เพื่อพิจารณาอนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name "; 	
			if($crstm_approve != "คณะกรรมการบริหารอนุมัติ"){
				$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาอนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name ".number_format($tot_cc)."  บาท 	จนถึงวันที่  $due_date <br><br></span> ";
			}else{
				$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดพิจารณาให้ความเห็นชอบก่อนนำเสนอ คบ.  พิจารณาอนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name วงเงิน ".number_format($tot_cc )." บาท   จนถึงวันที่ $due_date หลังจาก คก.สช. ให้ความเห็นชอบ ทางสินเชื่อจะเสนอ memo ให้ทาง คบ. อนุมัติ อีกครั้ง<br></span> ";																															
			}
		}
	}else {
			// ขอเพิ่มวงเงินลูกค้าใหม่
			$subject = "เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name";	
			$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";	
	}
	if($crstm_email_app1 != ""){
	$approver1_user_id = $crstm_email_app1;
	$approve_url = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color=DarkGreen>... Approve ...|</font></a>";
	$reject_url  = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color=Red>... Reject </font></a>";

	$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".   
				"<td colspan=10  align=center style='font-size:10pt'width=60%><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h3><br><br></td>".
			"</tr>".
		"</table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8 '>เรียน $author_to <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_cc</td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_term<br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><b>ตามอำนาจดำเนินการ :</b> $crstm_approve <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>เหตุผลที่เสนอขอวงเงิน</b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_sd_reson <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>ความเห็นสินเชื่อ เห็นควรอนุมัติ </b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc1_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc2_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; Finance & Credit Manager :$crstm_mgr_reson<br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>$crstm_detail_mail <br><br></td>".
			"</tr>" .
			/* "<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>คลิ๊กเพื่อ   $approve_url  $reject_url <br><br></td>".
			"</tr>" . */
		"</table>";
	}
	if($crstm_email_app2 != ""){
		$approver1_user_id = $crstm_email_app2;
		$approve_url = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver2_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color=DarkGreen>... Approve ...|</font></a>";
		$reject_url  = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver2_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color=Red>... Reject </font></a>";

		$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".   
				"<td colspan=10  align=center style='font-size:10pt'width=60%><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h3><br><br></td>".
			"</tr>".
		"</table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; '>เรียน $author_to<br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_cc</td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_term<br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><b>ตามอำนาจดำเนินการ :</b> $crstm_approve <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>เหตุผลที่เสนอขอวงเงิน</b></span><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_sd_reson <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>ความเห็นสินเชื่อ เห็นควรอนุมัติ </b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc1_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc2_reson <br><br></td>".
				"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; Finance & Credit Manager :$crstm_mgr_reson<br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>$crstm_detail_mail <br><br></td>".
			"</tr>" .
			/* "<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>คลิ๊กเพื่อ   $approve_url  $reject_url <br><br></td>".
			"</tr>" . */
		"</table>";
	}
	
		require_once('../_libs/mpdf/mpdf.php');
		$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
		$pdf->SetTitle('ใบขออนุมัติวงเงินสินเชื่อ Clean Credit');
		$pdf->SetHTMLHeader($header);
		$pdf->SetFooter("Continue Next Page");
		//$pdf->SetHTMLFooter('<div style="text-align:left;font-size:6pt;">MS-F013  :  Rev. No. 03  :  อายุการจัดเก็บ  1  ปี</div>');
		//$pdf->SetHTMLFooter($footpage);
		$pdf->AddPage('', // L - landscape, P - portrait 
		
        '', '', '1', '0',
        10, // margin_left
        10, // margin right
        20, // margin top   50   23.7
        40, // margin bottom 40
        5, // margin header
        10); // margin footer	
		
		$data = "";
		$max_line = 30;
		$line_cnt = 0;

		$footer=
		//$cc_signature=
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
			"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$name_from <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$emp_th_pos_name <br></td>".
			"</tr>" .
		"</table>".

		$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:10pt'>.</font><td></tr>");
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



function printMailapp_rev1($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn) {
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y");	
	$curMonth = date('Y-m'); 
		
	if (isservonline($smtp)) { $can_sendmail=true;}
	else {
		$can_sendmail=false;
		$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
	}

	$params = array($crstm_nbr);
	$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, emp_mstr.emp_prefix_th_name, emp_mstr.emp_th_firstname, ".
	"emp_mstr.emp_th_lastname, emp_mstr.emp_email_bus, emp_mstr.emp_th_pos_name, crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add,  ".
	"crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active,  ".
	"crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt,crstm_mstr.crstm_detail_mail, crstm_mstr.crstm_mgr_reson,  ".
	"crstm_mstr.crstm_mail_status, crstm_mstr.crstm_reviewer, crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc,crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, ".
	"cus_mstr.cus_terms_paymnt FROM crstm_mstr INNER JOIN  ".
	"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id INNER JOIN ".
	"cus_mstr ON crstm_mstr.crstm_cus_nbr = cus_mstr.cus_nbr ".
	"WHERE (crstm_mstr.crstm_nbr = ?)"; 

	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
		$name_from = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
		$email_bus = strtolower($rec_cus['emp_email_bus']);
		$emp_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		//$crstm_sd_reson       =  str_replace(chr(13),"<br>",$crstm_sd_reson); // เว้นบรรทัดเหมือนหน้า web
		$crstm_chk_rdo2 = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_cr_mgr = html_clear(number_format($rec_cus['crstm_cr_mgr']));
		$crstm_cus_active = html_clear($rec_cus['crstm_cus_active']);
		$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
		$terms_paymnt = html_clear($rec_cus['cus_terms_paymnt']);
		
		$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);
		$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
		$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
		
		$crstm_ch_term =  html_clear($rec_cus['crstm_ch_term']);
		
		$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
		$crstm_reviewer2 = strtolower(html_clear($rec_cus['crstm_reviewer2']));
		$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$email_mgr = $user_email;
		
		/////////////$email_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg.","."credit@scg.com";
		//$email_app_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg;
		$email_app_to1 =  $crstm_email_app1;
		$email_app_to2 =  $crstm_email_app2;
		
		$crstm_cus_nbr =  html_clear($rec_cus['crstm_cus_nbr']);
		$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);		
		$old_term = findsqlval("term_mstr", "term_desc", "term_code", $terms_paymnt ,$conn);
	} 

		$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", '50' ,$conn);
		
		$params = array($crstm_reviewer);
		$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
		$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
		$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
		if ($rec_emp) {
			$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
			$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
			$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
			$reviewer_name = html_clear($rec_emp['emp_th_pos_name']);
			$reviewName = $emp_prefix_th_name . $emp_th_firstname." ".$emp_th_lastname;
		} 

		switch($crstm_approve) {
			case "ผผ. อนุมัติ":
				$step_app = "60";
				break;
			case "ผส. อนุมัติ":
				$step_app = "60";
				break;
			case "ผฝ. อนุมัติ":
				$step_app = "60";
				break;	
			case "CO. อนุมัติ":
				$step_app = "60";
				break;
			case "กจก. อนุมัติ":
				$step_app = "60";
				break;	
			case "คณะกรรมการสินเชื่ออนุมัติ":
				$step_app = "600";
				break;		
			case "คณะกรรมการบริหารอนุมัติ":	
				$step_app = "61";
				break;		
			}

	$params = array($crstm_nbr);
	$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? order by tbl3_txt_ref desc ";
	$result_cc = sqlsrv_query($conn, $sql_cc,$params);
	while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
	{
		$amt = html_clear($row_cc['tbl3_amt_loc_curr']);
		$txt_ref = html_clear($row_cc['tbl3_txt_ref']);
		
		$gr_tot +=  $amt ;
		if ($txt_ref == "C1") {
			$tot_c1 += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		} else if ($txt_ref == "C3"){
			$tot_cc += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}else if ($txt_ref == "CC"){
			$tot_cc += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}	
	}

	if($crstm_chk_term == "old") {  /// เคสเปลี่ยนเงื่อนไขการชำระเงิน
		$txt_term = "";		
	}else if($crstm_chk_term == "change"){
		$txt_term = "และขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก :  $old_term  เป็น  $change_term";
	}
	if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
		if($crstm_chk_rdo2=="C1"){ // ขอเพิ่มวงเงิน
			$subject = "เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name";
			$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท <br></span> ";																															
	}else {  //ขอต่ออายุวงเงิน
			$subject ="เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name "; 	
			$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name ".number_format($tot_cc)."  บาท 	จนถึงวันที่  $due_date <br></span> ";
		}
	}else {
			// ขอเพิ่มวงเงินลูกค้าใหม่
			$subject = "เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name";	
			$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";	
	}
	if($crstm_reviewer != ""){
	$approver1_user_id = $crstm_reviewer;
	$approve_url = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color=DarkGreen>... Approve ...|</font></a>";
	$reject_url  = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color=Red>... Reject </font></a>";

	$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".   
				"<td colspan=10  align=center style='font-size:10pt'width=60%><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h3><br><br></td>".
			"</tr>".
		"</table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>เรียน $reviewName <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_cc<br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_term<br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><b>ตามอำนาจดำเนินการ :</b> $crstm_approve <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>เหตุผลที่เสนอขอวงเงิน</b></span><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_sd_reson <br><br><br></td>".
			"</tr>" .
			
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>$crstm_detail_mail <br><br></td>".
			"</tr>" .
			/* "<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>คลิ๊กเพื่อ   $approve_url  $reject_url <br><br></td>".
			"</tr>" . */
		"</table>";
	}

	
		require_once('../_libs/mpdf/mpdf.php');
		$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
		$pdf->SetTitle('ใบขออนุมัติวงเงินสินเชื่อ Clean Credit');
		$pdf->SetHTMLHeader($header);
		$pdf->SetFooter("Continue Next Page");
		//$pdf->SetHTMLFooter('<div style="text-align:left;font-size:6pt;">MS-F013  :  Rev. No. 03  :  อายุการจัดเก็บ  1  ปี</div>');
		//$pdf->SetHTMLFooter($footpage);
		$pdf->AddPage('', // L - landscape, P - portrait 
		
        '', '', '1', '0',
        10, // margin_left
        10, // margin right
        20, // margin top   50   23.7
        40, // margin bottom 40
        5, // margin header
        10); // margin footer	
		
		$data = "";
		$max_line = 30;
		$line_cnt = 0;

		$footer=
		//$cc_signature=
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
			"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$name_from <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$emp_th_pos_name <br></td>".
			"</tr>" .
		"</table>".

		$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:10pt'>.</font><td></tr>");
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

function printMailapp_rev2($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn) {
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y");	
	$curMonth = date('Y-m'); 
		
	if (isservonline($smtp)) { $can_sendmail=true;}
	else {
		$can_sendmail=false;
		$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
	}

	$params = array($crstm_nbr);
	$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, emp_mstr.emp_th_firstname, ".
	"emp_mstr.emp_th_lastname, emp_mstr.emp_email_bus, emp_mstr.emp_th_pos_name, crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add,  ".
	"crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active,  ".
	"crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt,crstm_mstr.crstm_detail_mail, crstm_mstr.crstm_mgr_reson,  ".
	"crstm_mstr.crstm_mail_status, crstm_mstr.crstm_reviewer, crstm_mstr.crstm_reviewer2, crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc,crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2 ".
	"FROM crstm_mstr INNER JOIN  ".
	"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id  ".
	"WHERE (crstm_mstr.crstm_nbr = ?)";

	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
		$name_from = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
		$email_bus = strtolower($rec_cus['emp_email_bus']);
		$emp_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		//$crstm_sd_reson       =  str_replace(chr(13),"<br>",$crstm_sd_reson); // เว้นบรรทัดเหมือนหน้า web
		$crstm_chk_rdo2 = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_cr_mgr = html_clear(number_format($rec_cus['crstm_cr_mgr']));
		$crstm_cus_active = html_clear($rec_cus['crstm_cus_active']);
		$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
		
		$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);
		$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
		$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
		
		$crstm_ch_term =  html_clear($rec_cus['crstm_ch_term']);
		$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);
		
		$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
		$crstm_reviewer2 = strtolower(html_clear($rec_cus['crstm_reviewer2']));
		$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$email_mgr = $user_email;
		
		/////////////$email_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg.","."credit@scg.com";
		//$email_app_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg;
		$email_app_to1 =  $crstm_email_app1;
		$email_app_to2 =  $crstm_email_app2;
		
		$crstm_cus_nbr =  html_clear($rec_cus['crstm_cus_nbr']);
		$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);	
		$cus_term = findsqlval("cus_mstr", "cus_terms_paymnt", "cus_nbr", $crstm_cus_nbr ,$conn);
		$old_term = findsqlval("term_mstr", "term_desc", "term_code", $cus_term,$conn);
	} 
		
		$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", '50' ,$conn);
		
		$params = array($crstm_reviewer2);
		$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
		$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
		$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
		if ($rec_emp) {
			$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
			$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
			$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
			$reviewer_name = html_clear($rec_emp['emp_th_pos_name']);
			$reviewName = $emp_prefix_th_name . $emp_th_firstname." ".$emp_th_lastname;
		} 

		switch($crstm_approve) {
			case "ผผ. อนุมัติ":
				$step_app = "60";
				break;
			case "ผส. อนุมัติ":
				$step_app = "60";
				break;
			case "ผฝ. อนุมัติ":
				$step_app = "60";
				break;	
			case "CO. อนุมัติ":
				$step_app = "60";
				break;
			case "กจก. อนุมัติ":
				$step_app = "60";
				break;	
			case "คณะกรรมการสินเชื่ออนุมัติ":
				$step_app = "600";
				break;		
			case "คณะกรรมการบริหารอนุมัติ":	
				$step_app = "61";
				break;		
			}

	$params = array($crstm_nbr);
	$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? order by tbl3_txt_ref desc ";
	$result_cc = sqlsrv_query($conn, $sql_cc,$params);

	while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
	{
		$amt = html_clear($row_cc['tbl3_amt_loc_curr']);
		$txt_ref = html_clear($row_cc['tbl3_txt_ref']);
		
		$gr_tot +=  $amt ;
		if ($txt_ref == "C1") {
			$tot_c1 += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		} else if ($txt_ref == "C3"){
			$tot_cc = $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}else if ($txt_ref == "CC"){
			$tot_cc += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}	
	}

	if($crstm_chk_term == "old") {  /// เคสเปลี่ยนเงื่อนไขการชำระเงิน
		$txt_term = "<br>";	
	}else if($crstm_chk_term == "change"){
		$txt_term = "และขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก :  $old_term  เป็น  $change_term";
	}
	
	if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
		if($crstm_chk_rdo2=="C1"){ // ขอเพิ่มวงเงิน
			$subject = "เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name";
			$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ ". $crstm_cus_name . " จาก ".number_format($tot_cc )." บาท" ."  เป็น ".number_format($gr_tot)."  บาท "."<br></span> ";																															
		}else {  //ขอต่ออายุวงเงิน
			$subject ="เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name "; 	
			$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ ". $crstm_cus_name ." ".number_format($tot_cc). "  บาท "." 	จนถึงวันที่ "  .$due_date. "<br></span> ";
		}
	}else {
			// ขอเพิ่มวงเงินลูกค้าใหม่
			$subject = "เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name";	
			$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";	
	}
	if($crstm_reviewer2 != ""){
	$approver1_user_id = $crstm_reviewer2;
	//$approve_url = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color=DarkGreen>... Approve ...|</font></a>";
	//$reject_url  = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color=Red>... Reject </font></a>";

	$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".   
				"<td colspan=10  align=center style='font-size:10pt'width=60%><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h3><br><br></td>".
			"</tr>".
		"</table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>เรียน $reviewName <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_cc<br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_term<br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><b>ตามอำนาจดำเนินการ :</b> $crstm_approve <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>เหตุผลที่เสนอขอวงเงิน</b></span><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_sd_reson <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>ความเห็นสินเชื่อ เห็นควรอนุมัติ </b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc1_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc2_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; Finance & Credit Manager :$crstm_mgr_reson<br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>$crstm_detail_mail <br><br></td>".
			"</tr>" .
			/* "<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>คลิ๊กเพื่อ   $approve_url  $reject_url <br><br></td>".
			"</tr>" . */
		"</table>";
	}
	
	
		require_once('../_libs/mpdf/mpdf.php');
		$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
		$pdf->SetTitle('ใบขออนุมัติวงเงินสินเชื่อ Clean Credit');
		$pdf->SetHTMLHeader($header);
		$pdf->SetFooter("Continue Next Page");
		//$pdf->SetHTMLFooter('<div style="text-align:left;font-size:6pt;">MS-F013  :  Rev. No. 03  :  อายุการจัดเก็บ  1  ปี</div>');
		//$pdf->SetHTMLFooter($footpage);
		$pdf->AddPage('', // L - landscape, P - portrait 
		
        '', '', '1', '0',
        10, // margin_left
        10, // margin right
        20, // margin top   50   23.7
        40, // margin bottom 40
        5, // margin header
        10); // margin footer	
		
		$data = "";
		$max_line = 30;
		$line_cnt = 0;

		$footer=
		//$cc_signature=
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
			"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$name_from <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$emp_th_pos_name <br></td>".
			"</tr>" .
		"</table>".

		$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:10pt'>.</font><td></tr>");
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

//// ลูกค้าใหม่ที่ยังไม่มี code
function printMailapp_new($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn) {
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y");	
	$curMonth = date('Y-m'); 
		
	if (isservonline($smtp)) { $can_sendmail=true;}
	else {
		$can_sendmail=false;
		$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
	}

	$params = array($crstm_nbr);
	
	$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_chk_rdo1,  ".
                    "crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson,  ".
                    "crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg,  ".
                    "crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_detail_mail, crstm_mstr.crstm_mgr_reson, crstm_mstr.crstm_mail_status, crstm_mstr.crstm_reviewer,  ".
                    "crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc, crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, emp_mstr.emp_prefix_th_name, emp_mstr.emp_th_firstname,  ".
                    "emp_mstr.emp_th_lastname, emp_mstr.emp_th_pos_name, emp_mstr.emp_email_bus ".
					"FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					"WHERE (crstm_mstr.crstm_nbr = ?)";

	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
		$name_from = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
		$email_bus = strtolower($rec_cus['emp_email_bus']);
		$emp_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		//$crstm_sd_reson       =  str_replace(chr(13),"<br>",$crstm_sd_reson); // เว้นบรรทัดเหมือนหน้า web
		$crstm_chk_rdo2 = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_cr_mgr = html_clear(number_format($rec_cus['crstm_cr_mgr']));
		$crstm_cus_active = html_clear($rec_cus['crstm_cus_active']);
		$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
		$terms_paymnt = html_clear($rec_cus['cus_terms_paymnt']);
		
		$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);
		$gr_tot = $crstm_cc_amt;
		$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
		$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
		
		$crstm_ch_term =  html_clear($rec_cus['crstm_ch_term']);
		
		$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
		$crstm_reviewer2 = strtolower(html_clear($rec_cus['crstm_reviewer2']));
		$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$email_mgr = $user_email;
		
		/////////////$email_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg.","."credit@scg.com";
		//$email_app_to =  $crstm_email_app1.",".$crstm_email_app2.",".$email_mrg;
		//$email_app_to1 =  $crstm_email_app1;
		//$email_app_to2 =  $crstm_email_app2;
		$crstm_cus_nbr =  html_clear($rec_cus['crstm_cus_nbr']);
	} 
		
		$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", '50' ,$conn);

		switch($crstm_approve) {
			case "ผผ. อนุมัติ":
				$author_to = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_email_app1,$conn);	
				$step_app = "60";
				break;
			case "ผส. อนุมัติ":
				$author_to = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_email_app1,$conn);	
				$step_app = "60";
				break;
			case "ผฝ. อนุมัติ":
				$author_to = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname","emp_email_bus",$crstm_email_app1,$conn);	
				$step_app = "60";
				break;	
			case "CO. อนุมัติ":
				if($crstm_scgc == true) {
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$author_to = findsqlval("emp_mstr", "emp_th_pos_name", "emp_email_bus", $crstm_email_app1 ,$conn);
				$step_app = "60";
				break;
			case "กจก. อนุมัติ":
				if($crstm_scgc == true) {
					$author_to = findsqlval("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "60";
				break;	
			case "คณะกรรมการสินเชื่ออนุมัติ":
				if($crstm_scgc == true) {
					$author_to = findsqlval_1("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval_1("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "600";
				break;		
			case "คณะกรรมการบริหารอนุมัติ":	
				if($crstm_scgc == true) {
					$author_to = findsqlval_1("author_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				} else {
					$author_to = findsqlval_1("author_g_mstr", "author_salutation", "author_text", $crstm_approve ,$conn);
				}
				$step_app = "61";
				break;		
			}
	
	// ขอเพิ่มวงเงินลูกค้าใหม่
	$subject = "เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name";	
	//$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br><br></span> ";	
	if($crstm_approve != "คณะกรรมการบริหารอนุมัติ"){
		$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($gr_tot)."  บาท <br></span> ";																															
	}else{
		$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดพิจารณาให้ความเห็นชอบก่อนนำเสนอ คบ.  พิจารณาอนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($gr_tot)."  บาท  หลังจาก คก.สช. ให้ความเห็นชอบ ทางสินเชื่อจะเสนอ memo ให้ทาง คบ. อนุมัติ อีกครั้ง<br></span> ";																															
	}	
	if($crstm_email_app1 != ""){
	$approver1_user_id = $crstm_email_app1;
	$approve_url = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color=DarkGreen>... Approve ...|</font></a>";
	$reject_url  = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color=Red>... Reject </font></a>";

	$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".   
				"<td colspan=10  align=center style='font-size:10pt'width=60%><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h3><br><br></td>".
			"</tr>".
		"</table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>เรียน $author_to <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_cc<br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><b>ตามอำนาจดำเนินการ :</b> $crstm_approve <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>เหตุผลที่เสนอขอวงเงิน</b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_sd_reson <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>ความเห็นสินเชื่อ เห็นควรอนุมัติ </b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc1_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc2_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; Finance & Credit Manager :$crstm_mgr_reson<br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>$crstm_detail_mail <br><br></td>".
			"</tr>" .
			/* "<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>คลิ๊กเพื่อ   $approve_url  $reject_url <br><br></td>".
			"</tr>" . */
		"</table>";
	}
	if($crstm_email_app2 != ""){
		$approver1_user_id = $crstm_email_app2;
		$approve_url = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver2_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color=DarkGreen>... Approve ...|</font></a>";
		$reject_url  = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver2_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color=Red>... Reject </font></a>";

		$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".   
				"<td colspan=10  align=center style='font-size:10pt'width=60%><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h3><br><br></td>".
			"</tr>".
		"</table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; '>เรียน $author_to<br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_cc<br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><b>ตามอำนาจดำเนินการ :</b> $crstm_approve <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>เหตุผลที่เสนอขอวงเงิน</b></span><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_sd_reson <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>ความเห็นสินเชื่อ เห็นควรอนุมัติ </b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc1_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc2_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; Finance & Credit Manager :$crstm_mgr_reson<br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>$crstm_detail_mail <br><br></td>".
			"</tr>" .
			/* "<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>คลิ๊กเพื่อ   $approve_url  $reject_url <br><br></td>".
			"</tr>" . */
		"</table>";
	}
	
		require_once('../_libs/mpdf/mpdf.php');
		$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
		$pdf->SetTitle('ใบขออนุมัติวงเงินสินเชื่อ Clean Credit');
		$pdf->SetHTMLHeader($header);
		$pdf->SetFooter("Continue Next Page");
		//$pdf->SetHTMLFooter('<div style="text-align:left;font-size:6pt;">MS-F013  :  Rev. No. 03  :  อายุการจัดเก็บ  1  ปี</div>');
		//$pdf->SetHTMLFooter($footpage);
		$pdf->AddPage('', // L - landscape, P - portrait 
		
        '', '', '1', '0',
        10, // margin_left
        10, // margin right
        20, // margin top   50   23.7
        40, // margin bottom 40
        5, // margin header
        10); // margin footer	
		
		$data = "";
		$max_line = 30;
		$line_cnt = 0;

		$footer=
		//$cc_signature=
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
			"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$name_from <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$emp_th_pos_name <br></td>".
			"</tr>" .
		"</table>".

		$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:10pt'>.</font><td></tr>");
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

function printMailapp_rev1_new($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn) {
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y");	
	$curMonth = date('Y-m'); 
		
	if (isservonline($smtp)) { $can_sendmail=true;}
	else {
		$can_sendmail=false;
		$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
	}

	$params = array($crstm_nbr);
	$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_chk_rdo1, ".
                    "crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, ".
                    "crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg, ".
                    "crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_detail_mail, crstm_mstr.crstm_mgr_reson, crstm_mstr.crstm_mail_status, crstm_mstr.crstm_reviewer, ".
                    "crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc, crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, emp_mstr.emp_prefix_th_name, emp_mstr.emp_th_firstname, ".
                    "emp_mstr.emp_th_lastname, emp_mstr.emp_th_pos_name, emp_mstr.emp_email_bus ".
					"FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					"WHERE (crstm_mstr.crstm_nbr = ?)";

	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
		$name_from = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
		$email_bus = strtolower($rec_cus['emp_email_bus']);
		$emp_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		//$crstm_sd_reson       =  str_replace(chr(13),"<br>",$crstm_sd_reson); // เว้นบรรทัดเหมือนหน้า web
		$crstm_chk_rdo2 = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_cr_mgr = html_clear(number_format($rec_cus['crstm_cr_mgr']));
		$crstm_cus_active = html_clear($rec_cus['crstm_cus_active']);
		$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
		
		$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);
		$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
		$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
		
		$crstm_ch_term =  html_clear($rec_cus['crstm_ch_term']);
		
		$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
		//$crstm_reviewer2 = strtolower(html_clear($rec_cus['crstm_reviewer2']));
		$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$email_mgr = $user_email;
	
		$email_app_to1 =  $crstm_email_app1;
		$email_app_to2 =  $crstm_email_app2;
		
		$crstm_cus_nbr =  html_clear($rec_cus['crstm_cus_nbr']);
		$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);		
	} 

		$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", '50' ,$conn);
		
		$params = array($crstm_reviewer);
		$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
		$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
		$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
		if ($rec_emp) {
			$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
			$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
			$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
			$reviewer_name = html_clear($rec_emp['emp_th_pos_name']);
			$reviewName = $emp_prefix_th_name . $emp_th_firstname." ".$emp_th_lastname;
		} 

		switch($crstm_approve) {
			case "ผผ. อนุมัติ":
				$step_app = "60";
				break;
			case "ผส. อนุมัติ":
				$step_app = "60";
				break;
			case "ผฝ. อนุมัติ":
				$step_app = "60";
				break;	
			case "CO. อนุมัติ":
				$step_app = "60";
				break;
			case "กจก. อนุมัติ":
				$step_app = "60";
				break;	
			case "คณะกรรมการสินเชื่ออนุมัติ":
				$step_app = "600";
				break;		
			case "คณะกรรมการบริหารอนุมัติ":	
				$step_app = "61";
				break;		
			}
	$params = array($crstm_nbr);
	$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? order by tbl3_txt_ref desc ";
	$result_cc = sqlsrv_query($conn, $sql_cc,$params);
	while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
	{
		$amt = html_clear($row_cc['tbl3_amt_loc_curr']);
		$txt_ref = html_clear($row_cc['tbl3_txt_ref']);
		
		$gr_tot +=  $amt ;
		if ($txt_ref == "C1") {
			$tot_c1 += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		} else if ($txt_ref == "C3"){
			$tot_cc += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}else if ($txt_ref == "CC"){
			$tot_cc += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}	
	}

	if($crstm_chk_term == "old") {  /// เคสเปลี่ยนเงื่อนไขการชำระเงิน
		$txt_term = "";		
	}else if($crstm_chk_term == "change"){
		$txt_term = "และขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก :  $old_term  เป็น  $change_term";
	}
	if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
		if($crstm_chk_rdo2=="C1"){ // ขอเพิ่มวงเงิน
			$subject = "เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name";
			$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท <br></span> ";																															
							
		}else {  //ขอต่ออายุวงเงิน
			$subject ="เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name "; 	
			$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name ".number_format($tot_cc)."  บาท 	จนถึงวันที่  $due_date <br></span> ";
		}
	}else {
			// ขอเพิ่มวงเงินลูกค้าใหม่
			$subject = "เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name";	
			$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";	
	}
	if($crstm_reviewer != ""){
	$approver1_user_id = $crstm_reviewer;
	$approve_url = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color=DarkGreen>... Approve ...|</font></a>";
	$reject_url  = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color=Red>... Reject </font></a>";

	$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".   
				"<td colspan=10  align=center style='font-size:10pt'width=60%><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h3><br><br></td>".
			"</tr>".
		"</table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>เรียน $reviewName <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_cc<br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_term<br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><b>ตามอำนาจดำเนินการ :</b> $crstm_approve <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>เหตุผลที่เสนอขอวงเงิน</b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_sd_reson <br><br><br></td>".
			"</tr>" .
			
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>$crstm_detail_mail <br><br></td>".
			"</tr>" .
			/* "<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>คลิ๊กเพื่อ   $approve_url  $reject_url <br><br></td>".
			"</tr>" . */
		"</table>";
	}

	
		require_once('../_libs/mpdf/mpdf.php');
		$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
		$pdf->SetTitle('ใบขออนุมัติวงเงินสินเชื่อ Clean Credit');
		$pdf->SetHTMLHeader($header);
		$pdf->SetFooter("Continue Next Page");
		//$pdf->SetHTMLFooter('<div style="text-align:left;font-size:6pt;">MS-F013  :  Rev. No. 03  :  อายุการจัดเก็บ  1  ปี</div>');
		//$pdf->SetHTMLFooter($footpage);
		$pdf->AddPage('', // L - landscape, P - portrait 
		
        '', '', '1', '0',
        10, // margin_left
        10, // margin right
        20, // margin top   50   23.7
        40, // margin bottom 40
        5, // margin header
        10); // margin footer	
		
		$data = "";
		$max_line = 30;
		$line_cnt = 0;

		$footer=
		//$cc_signature=
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
			"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$name_from <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$emp_th_pos_name <br></td>".
			"</tr>" .
		"</table>".

		$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:10pt'>.</font><td></tr>");
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
function printMailapp_rev2_new($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn) {
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y");	
	$curMonth = date('Y-m'); 
		
	if (isservonline($smtp)) { $can_sendmail=true;}
	else {
		$can_sendmail=false;
		$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
	}

	$params = array($crstm_nbr);
	$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_chk_rdo1, ".
                    "crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, ".
                    "crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg, ".
                    "crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_detail_mail, crstm_mstr.crstm_mgr_reson, crstm_mstr.crstm_mail_status, crstm_mstr.crstm_reviewer, crstm_mstr.crstm_reviewer2, ".
                    "crstm_mstr.crstm_noreviewer, crstm_mstr.crstm_scgc, crstm_mstr.crstm_email_app1, crstm_mstr.crstm_email_app2, emp_mstr.emp_prefix_th_name, emp_mstr.emp_th_firstname, ".
                    "emp_mstr.emp_th_lastname, emp_mstr.emp_th_pos_name, emp_mstr.emp_email_bus ".
					"FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					"WHERE (crstm_mstr.crstm_nbr = ?)";

	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$crstm_nbr = html_clear($rec_cus['crstm_nbr']);
		$name_from = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
		$email_bus = strtolower($rec_cus['emp_email_bus']);
		$emp_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		//$crstm_sd_reson       =  str_replace(chr(13),"<br>",$crstm_sd_reson); // เว้นบรรทัดเหมือนหน้า web
		$crstm_chk_rdo2 = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_cr_mgr = html_clear(number_format($rec_cus['crstm_cr_mgr']));
		$crstm_cus_active = html_clear($rec_cus['crstm_cus_active']);
		$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
		
		$crstm_cc_amt = html_clear($rec_cus['crstm_cc_amt']);
		$crstm_cc_date_beg = dmytx(html_clear($rec_cus['crstm_cc_date_beg']));
		$crstm_cc_date_end = dmytx(html_clear($rec_cus['crstm_cc_date_end']));
		
		$crstm_ch_term =  html_clear($rec_cus['crstm_ch_term']);
		
		$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
		$crstm_reviewer2 = strtolower(html_clear($rec_cus['crstm_reviewer2']));
		$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
		$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
		$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
		$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
		$email_mgr = $user_email;
	
		$email_app_to1 =  $crstm_email_app1;
		$email_app_to2 =  $crstm_email_app2;
		
		$crstm_cus_nbr =  html_clear($rec_cus['crstm_cus_nbr']);
		$change_term = findsqlval("term_mstr", "term_desc", "term_code", $crstm_ch_term ,$conn);		
	} 

		$crstm_step_name = findsqlval(" crsta_mstr", "crsta_step_name", "crsta_step_code", '50' ,$conn);
		
		$params = array($crstm_reviewer2);
		$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
		$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
		$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
		if ($rec_emp) {
			$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
			$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
			$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
			$reviewer_name = html_clear($rec_emp['emp_th_pos_name']);
			$reviewName = $emp_prefix_th_name . $emp_th_firstname." ".$emp_th_lastname;
		} 

		switch($crstm_approve) {
			case "ผผ. อนุมัติ":
				$step_app = "60";
				break;
			case "ผส. อนุมัติ":
				$step_app = "60";
				break;
			case "ผฝ. อนุมัติ":
				$step_app = "60";
				break;	
			case "CO. อนุมัติ":
				$step_app = "60";
				break;
			case "กจก. อนุมัติ":
				$step_app = "60";
				break;	
			case "คณะกรรมการสินเชื่ออนุมัติ":
				$step_app = "600";
				break;		
			case "คณะกรรมการบริหารอนุมัติ":	
				$step_app = "61";
				break;		
			}
	$params = array($crstm_nbr);
	$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? order by tbl3_txt_ref desc ";
	$result_cc = sqlsrv_query($conn, $sql_cc,$params);
	while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
	{
		$amt = html_clear($row_cc['tbl3_amt_loc_curr']);
		$txt_ref = html_clear($row_cc['tbl3_txt_ref']);
		
		$gr_tot +=  $amt ;
		if ($txt_ref == "C1") {
			$tot_c1 += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		} else if ($txt_ref == "C3"){
			$tot_cc += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}else if ($txt_ref == "CC"){
			$tot_cc += $amt;
			$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		}	
	}

	if($crstm_chk_term == "old") {  /// เคสเปลี่ยนเงื่อนไขการชำระเงิน
		$txt_term = "";		
	}else if($crstm_chk_term == "change"){
		$txt_term = "และขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก :  $old_term  เป็น  $change_term";
	}
	if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
		if($crstm_chk_rdo2=="C1"){ // ขอเพิ่มวงเงิน
			$subject = "เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name";
			$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาการเสนอขออนุมัติเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท <br></span> ";																															
							
		}else {  //ขอต่ออายุวงเงิน
			$subject ="เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name "; 	
			$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name ".number_format($tot_cc)."  บาท 	จนถึงวันที่  $due_date <br></span> ";
		}
	}else {
			// ขอเพิ่มวงเงินลูกค้าใหม่
			$subject = "เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name";	
			$txt_cc = "<span style='color:Blue'>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาการเสนอขออนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";	
	}
	if($crstm_reviewer2 != ""){
	$approver1_user_id = $crstm_reviewer2;
	$approve_url = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt($step_app, $dbkey)."' target='_blank'><font color=DarkGreen>... Approve ...|</font></a>";
	$reject_url  = "<a href='".$app_url."../crctrlbof/crctrlapprovemail.php?nbr=".encrypt($crstm_nbr, $dbkey)."&id=".encrypt($approver1_user_id, $dbkey)."&cus=".encrypt($crstm_cus_name, $dbkey)."&act=".encrypt('42', $dbkey)."' target='_blank'><font color=Red>... Reject </font></a>";

	$header = 
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".   
				"<td colspan=10  align=center style='font-size:10pt'width=60%><h2> บริษัทเอสซีจี เซรามิกส์ จำกัด  (มหาชน) </h2><br><h3> ใบขออนุมัติวงเงินสินเชื่อ (Clean Credit)</h3><br><br></td>".
			"</tr>".
		"</table>".
		
		"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>เรียน $reviewName <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_cc<br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>$txt_term<br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><b>ตามอำนาจดำเนินการ :</b> $crstm_approve <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>เหตุผลที่เสนอขอวงเงิน</b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_sd_reson <br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'><span><b>ความเห็นสินเชื่อ เห็นควรอนุมัติ </b></span><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc1_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; $crstm_cc2_reson <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8; '>&emsp;&emsp; &#8226; &nbsp;&nbsp; Finance & Credit Manager :$crstm_mgr_reson<br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>รายละเอียดใบขออนุมัติวงเงินเลขที่  $crstm_nbr ตามเอกสารแนบ <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>$crstm_detail_mail <br><br></td>".
			"</tr>" .
			/* "<tr>".
				"<td align=left style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; line-height: 1.8;'>คลิ๊กเพื่อ   $approve_url  $reject_url <br><br></td>".
			"</tr>" . */
		"</table>";
	}

	
		require_once('../_libs/mpdf/mpdf.php');
		$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
		$pdf->SetTitle('ใบขออนุมัติวงเงินสินเชื่อ Clean Credit');
		$pdf->SetHTMLHeader($header);
		$pdf->SetFooter("Continue Next Page");
		//$pdf->SetHTMLFooter('<div style="text-align:left;font-size:6pt;">MS-F013  :  Rev. No. 03  :  อายุการจัดเก็บ  1  ปี</div>');
		//$pdf->SetHTMLFooter($footpage);
		$pdf->AddPage('', // L - landscape, P - portrait 
		
        '', '', '1', '0',
        10, // margin_left
        10, // margin right
        20, // margin top   50   23.7
        40, // margin bottom 40
        5, // margin header
        10); // margin footer	
		
		$data = "";
		$max_line = 30;
		$line_cnt = 0;

		$footer=
		//$cc_signature=
		"<table><tr><td calpan=10 width=100%></td></tr></table>".
			"<table width=100% border=0 style='border-collapse: collapse; font-size:10pt'>".
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br><br><br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$name_from <br><br></td>".
			"</tr>" .
			"<tr>".
				"<td align=center style='font-size:10pt;border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; padding-left: 18.8em;'>$emp_th_pos_name <br></td>".
			"</tr>" .
		"</table>".

		$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:10pt'>.</font><td></tr>");
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
