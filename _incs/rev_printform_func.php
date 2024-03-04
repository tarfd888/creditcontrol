<?php
function print_formnewcust($cus_app_nbr,$savefile,$output_folder,$cr_output_filename,$conn,$watermark_text) {
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y");	
	$curMonth = date('Y-m'); 
	
	$sql = "SELECT * from  sysc_ctrl ";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
	$final_mgr_name = mssql_escape($row['sysc_final_approver']);
	$mgr_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' +emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$final_mgr_name,$conn);
	$mgr_pos = findsqlval("emp_mstr","emp_th_pos_name","emp_user_id",$final_mgr_name,$conn);

	$params=array($cus_app_nbr);
	$sql = "SELECT * from cus_app_mstr where cus_app_nbr = ? ";
	$result = sqlsrv_query($conn, $sql,$params);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	

	//$cus_app_nbr = mssql_escape($row['cus_app_nbr']);
	$cus_code = mssql_escape($row['cus_code']);
	$cus_date = mssql_escape(dmytx($row['cus_date']));
	$cus_cond_cust = mssql_escape($row['cus_cond_cust']);
	if($cus_cond_cust=="c1"){
		$filename = "แต่งตั้งลูกค้าใหม่";
	} else {
		$chkbox3 = '<img src="../_images/check_blank.png" width=15px>';
	} 
	if($cus_cond_cust=="c2"){
		$filename = "แต่งตั้งร้านสาขา";
	} else {
		$chkbox4 = '<img src="../_images/check_blank.png" width=15px>';
	} 

	$cus_tg_cust = mssql_escape($row['cus_tg_cust']); // domestic , export
	
	$cus_type_code = mssql_escape($row['cus_cust_type']);
	$type_code_name = findsqlval("cus_type_mstr","cus_type_name","cus_type_code",$cus_type_code,$conn);


	$cus_reg_nme = rtrim(mssql_escape($row['cus_reg_nme']));

	$cus_reg_addr = mssql_escape($row['cus_reg_addr']);
	$cus_district = mssql_escape($row['cus_district']);
	$cus_amphur = mssql_escape($row['cus_amphur']);
	$cus_prov = mssql_escape($row['cus_prov']);
	$cus_zip = mssql_escape($row['cus_zip']);
	$cus_country = mssql_escape($row['cus_country']);
	$address = $cus_reg_addr;
	
	$cus_tax_id = mssql_escape($row['cus_tax_id']);
	$cus_branch = mssql_escape($row['cus_branch']);
	$cus_type_bus = mssql_escape($row['cus_type_bus']);
	$type_bus_name = findsqlval("cus_tyofbus_mstr","cus_tyofbus_name","cus_tyofbus_id",$cus_type_bus,$conn);

	$cus_tel = mssql_escape($row['cus_tel']);
	$cus_fax = mssql_escape($row['cus_fax']);
	$cus_email = mssql_escape($row['cus_email']);
	$cus_cust_type_oth = mssql_escape($row['cus_cust_type_oth']);    

	$cus_contact1_nme = mssql_escape($row['cus_contact1_nme']);
	$cus_contact1_pos  = mssql_escape($row['cus_contact1_pos']);
	$cus_contact2_nme = mssql_escape($row['cus_contact2_nme']);
	$cus_contact2_pos  = mssql_escape($row['cus_contact2_pos']);
	$contactArray = array();	//เก็บชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า
	$contactPosArray = array(); //เก็บตำแหน่ง
	if($cus_contact1_nme !="") { array_push($contactArray,$cus_contact1_nme); 	array_push($contactPosArray, $cus_contact1_pos); }
	if($cus_contact2_nme !="") { array_push($contactArray,$cus_contact2_nme); 	array_push($contactPosArray, $cus_contact2_pos); }
	$contactArrayCount = count($contactArray);
	
	// -- page 2 --//
	$cus_term = mssql_escape($row['cus_term']);
    $term_name = findsqlval("term_mstr","term_code + ' (' + term_desc + ')' ","term_code",$cus_term,$conn);

	$cus_bg1 = mssql_escape($row['cus_bg1']);
	$cus_bg1_name = findsqlval("bank_mstr","bank_th_name","bank_code",$cus_bg1,$conn);

	$cus_cr_limit1 = CheckandShowNumber(mssql_escape($row['cus_cr_limit1']),2);
	$cus_bg2 = mssql_escape($row['cus_bg2']);
	$cus_cr_limit2 = CheckandShowNumber(mssql_escape($row['cus_cr_limit2']),2);
	$cus_cond_term = mssql_escape($row['cus_cond_term']);
	$cus_cond_term_oth = mssql_escape($row['cus_cond_term_oth']);    
	if($cus_cond_term =="1"){
		$cond_term_name = "ชำระทุกวันตาม Due";
	}
	else 
	{
		$cond_term_name = "เงื่อนไขการวางบิลหรือชำระเงินพิเศษ ".$cus_cond_term_oth ;
	}
	$cus_pay_addr = mssql_escape($row['cus_pay_addr']);
	$cus_contact_nme_pay = mssql_escape($row['cus_contact_nme_pay']);
	$cus_contact_tel = mssql_escape($row['cus_contact_tel']);
	$cus_contact_fax = mssql_escape($row['cus_contact_fax']);
	$cus_contact_email = mssql_escape($row['cus_contact_email']);    

	// -- images_mstr --//
	$temimagerandom  = mssql_escape($row['cus_tem_image']);    
	$cus_step_code = mssql_escape($row['cus_step_code']);  
	
	$params = array();
	$sql_cr= "SELECT * FROM book_mstr order by book_no";
	$result = sqlsrv_query($conn, $sql_cr,$params);
	$i = 0;
	$bookArray = array();	
	$statusArray = array();
	$chkArray = array();
	while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			if($cus_tg_cust=="dom"){
				$bookArray[$i] = rtrim($row_cr['book_dom']);
			}
			else
			{
				$bookArray[$i] = rtrim($row_cr['book_exp']);
			}

			$statusArray[$i] = rtrim($row_cr['book_status']);
			if($statusArray[$i]  == '1'){
				$chkArray[$i] = '<img src="../_images/check_true.png" width=10px>';
				
			}
			else {
				$chkArray[$i] = '<img src="../_images/check_blank.png" width=10px>';
			}
			$i++;
		}	

	$apprv_id_array = array(); //เก็บ apprv_emp_id ผู้อนุมัติทั้งหมด	
	$apprv_pos_array = array();
	$params = array($cus_app_nbr);
	$query_det = "SELECT * FROM cus_app_det WHERE cusd_app_nbr = ?";
	$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
	$rowCounts = sqlsrv_num_rows($result);
	if($rowCounts > 0){
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			// -- page 3 --//
			$cusd_tg_beg_date = mssql_escape(dmytx($row['cusd_tg_beg_date']));
			$cusd_tg_end_date = mssql_escape(dmytx($row['cusd_tg_end_date']));
			$cusd_sale_est = CheckandShowNumber(mssql_escape($row['cusd_sale_est']),0);
			$cusd_sale_vol = CheckandShowNumber(mssql_escape($row['cusd_sale_vol']),0);

			$cusd_obj1 = mssql_escape($row['cusd_obj1']);
			$cusd_obj2 = mssql_escape($row['cusd_obj2']);
			$cusd_obj3 = mssql_escape($row['cusd_obj3']);
			$objArray = array();	//เก็บวัตถุประสงค์ / นโยบายด้านการตลาด
			if($cusd_obj1 !="") { array_push($objArray,$cusd_obj1);}
			if($cusd_obj2 !="") { array_push($objArray,$cusd_obj2);}
			if($cusd_obj3 !="") { array_push($objArray,$cusd_obj3);}
			$objArrayCount = count($objArray);

			$cusd_cust_prop1 = mssql_escape($row['cusd_cust_prop1']);
			$cusd_cust_prop2 = mssql_escape($row['cusd_cust_prop2']);
			$cusd_cust_prop3 = mssql_escape($row['cusd_cust_prop3']);

			$projArray = array();	//เก็บคุณสมบัติลูกค้า
			if($cusd_cust_prop1 !="") { array_push($projArray,$cusd_cust_prop1);}
			if($cusd_cust_prop2 !="") { array_push($projArray,$cusd_cust_prop2);}
			if($cusd_cust_prop3 !="") { array_push($projArray,$cusd_cust_prop3);}
			$projArrayCount = count($projArray);

			$cusd_aff1 = mssql_escape($row['cusd_aff1']);
			$cusd_aff2 = mssql_escape($row['cusd_aff2']);
			$cusd_aff3 = mssql_escape($row['cusd_aff3']);
			$affArray = array();	//เก็บกิจการในเครือ (Affiliate / Related Company)
			if($cusd_aff1 !="") { array_push($affArray,$cusd_aff1);}
			if($cusd_aff2 !="") { array_push($affArray,$cusd_aff2);}
			if($cusd_aff3 !="") { array_push($affArray,$cusd_aff3);}
			$affArrayCount = count($affArray);

			$cusd_dealer1_nme = mssql_escape($row['cusd_dealer1_nme']);
			$cusd_dealer1_avg_val = CheckandShowNumber(mssql_escape($row['cusd_dealer1_avg_val']),2);
			$cusd_dealer2_nme = mssql_escape($row['cusd_dealer2_nme']);
			$cusd_dealer2_avg_val = CheckandShowNumber(mssql_escape($row['cusd_dealer2_avg_val']),2);
			$cusd_dealer3_nme = mssql_escape($row['cusd_dealer3_nme']);
			$cusd_dealer3_avg_val = CheckandShowNumber(mssql_escape($row['cusd_dealer3_avg_val']),2);
			$dealerArray = array();	//เก็บรายชื่อผู้แทนจำหน่ายทั่วไป
			$dealerArrayVal = array();
			if($cusd_dealer1_nme !="") { array_push($dealerArray,$cusd_dealer1_nme);  array_push($dealerArrayVal, $cusd_dealer1_avg_val);}
			if($cusd_dealer2_nme !="") { array_push($dealerArray,$cusd_dealer2_nme);  array_push($dealerArrayVal, $cusd_dealer2_avg_val);}
			if($cusd_dealer3_nme !="") { array_push($dealerArray,$cusd_dealer3_nme);  array_push($dealerArrayVal, $cusd_dealer3_avg_val);}
			$dealerArrayCount = count($dealerArray);

			$cusd_comp1_nme = mssql_escape($row['cusd_comp1_nme']);
			$cusd_comp1_avg_val = CheckandShowNumber(mssql_escape($row['cusd_comp1_avg_val']),2);
			$cusd_comp2_nme = mssql_escape($row['cusd_comp2_nme']);
			$cusd_comp2_avg_val = CheckandShowNumber(mssql_escape($row['cusd_comp2_avg_val']),2);
			$cusd_comp3_nme = mssql_escape($row['cusd_comp3_nme']);
			$cusd_comp3_avg_val = CheckandShowNumber(mssql_escape($row['cusd_comp3_avg_val']),2);
			$compArray = array();	//เก็บรายชื่อคู่แข่ง ซึ่งลูกค้าที่ขอแต่งตั้งซื้อเป็นประจำ
			$compArrayVal = array();
			if($cusd_comp1_nme !="") { array_push($compArray,$cusd_comp1_nme);  array_push($compArrayVal, $cusd_comp1_avg_val);}
			if($cusd_comp2_nme !="") { array_push($compArray,$cusd_comp2_nme);  array_push($compArrayVal, $cusd_comp2_avg_val);}
			if($cusd_comp3_nme !="") { array_push($compArray,$cusd_comp3_nme);  array_push($compArrayVal, $cusd_comp3_avg_val);}
			$compArrayCount = count($compArray);

			$cusd_is_sale1 = mssql_escape($row['cusd_is_sale1']);
			$cusd_is_sale1_email = mssql_escape($row['cusd_is_sale1_email']);
			$cusd_is_sale1_tel = mssql_escape($row['cusd_is_sale1_tel']);
			$cusd_is_sale1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_is_sale1,$conn);

			$cusd_is_sale2 = mssql_escape($row['cusd_is_sale2']);
			$cusd_is_sale2_email = mssql_escape($row['cusd_is_sale2_email']);
			$cusd_is_sale2_tel = mssql_escape($row['cusd_is_sale2_tel']);
			$cusd_is_sale2_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_is_sale2,$conn);

			$cusd_os_sale = mssql_escape($row['cusd_os_sale']);
			$cusd_os_sale_email = mssql_escape($row['cusd_os_sale_email']);
			$cusd_os_sale_tel = mssql_escape($row['cusd_os_sale_tel']);
			$cusd_os_sale_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_os_sale,$conn);

			$cusd_os_sale_mgr_code = mssql_escape($row['cusd_sale_manager']);
			$cusd_manger_email = mssql_escape($row['cusd_manger_email']);
			$cusd_sale_manager_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_os_sale_mgr_code,$conn);
			$cusd_mgr_pos = findsqlval("emp_mstr","emp_th_pos_name","emp_scg_emp_id",$cusd_os_sale_mgr_code,$conn);
			
			// -- page 5 --//
			$cusd_sale_reason = mssql_escape($row['cusd_sale_reason']);
			$cusd_op_app = mssql_escape($row['cusd_op_app']);

			// -- approve --//
			$cusd_review1 = mssql_escape($row['cusd_review1']);
			$cusd_review1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review1,$conn);
			$apprv_pos_array[0] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review1,$conn);
		
			$cusd_review2 = mssql_escape($row['cusd_review2']);
			$cusd_review2_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review2,$conn);
			$apprv_pos_array[1] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review2,$conn);

			$cusd_review3 = mssql_escape($row['cusd_review3']);
			$cusd_review3_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review3,$conn);
			$apprv_pos_array[2] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review3,$conn);

			$cusd_review4 = mssql_escape($row['cusd_review4']);
			$cusd_review4_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review4,$conn);
			$apprv_pos_array[3] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review4,$conn);

			$cusd_approve_fin = mssql_escape($row['cusd_approve_fin']);
			$cusd_approve_fin_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_approve_fin,$conn);
			$apprv_pos_array[4] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_approve_fin,$conn);

		}
	} 

	// หัวจดหมาย	
	$header = 
	"<table width=100% border=0 style='border-collapse: collapse;  cellpadding='5' cellspacing='0' font-size:7pt'>".
		"<tr>".   
			"<td colspan=10 align=center style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=60%></td>".
		"</tr>".
		"<tr>".   
			"<td colspan=10 align=center style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=60%><h3> บริษัทเอสซีจี เซรามิกส์ จำกัด(มหาชน) / SCG Ceramics Public Company Limited</h3></td>".
		"</tr>".
		"<tr>".   
		 	"<td colspan=10 align=center style='line-height: 1.8; font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;'><h4> ใบขออนุมัติ$filename</h4></td>".
		"</tr>".
	"</table>";
		
	require_once('../_libs/mpdf/mpdf.php');
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	$pdf->SetTitle('ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr);
	$pdf->SetHTMLHeader($header);
	$pdf->SetWatermarkText($watermark_text);
	$pdf->watermarkTextAlpha = 0.1;
	$pdf->showWatermarkText = true;
	$pdf->SetFooter("SCG Ceramics Public Company Limited");
	//$pdf->SetHTMLFooter('<div style="text-align:center;font-size: 12px;">Page {PAGENO}/{nbpg}</div>');
	$pdf->AddPage('', // L - landscape, P - portrait 
	
	'', '', '1', '0',
	5, // margin_left
	5, // margin right
	12, // margin top   20
	10, // margin bottom 40
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
		
	if($cus_cond_cust=="c1"){
		$body=
			"<table width=100% border=1 style='border-collapse: collapse; cellpadding='5' cellspacing='0' font-size:7pt'>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15></td>".
					"<td colspan='2' align=right style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15></td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; รหัสลูกค้า : $cus_code &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; วันที่ขออนุมัติ : $cus_date </td>".
					"<td colspan='2' align=right style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=15>เอกสารเลขที่ : $cus_app_nbr &nbsp;</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; ชื่อ : $cus_reg_nme</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>ที่อยู่ : $address &nbsp;</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; เบอร์โทรศัพท์ : $cus_tel &emsp;&emsp;&emsp;&emsp;&emsp;  Fax. : $cus_fax</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>อีเมล : $cus_email &nbsp;</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; เลขประจำตัวผู้เสียภาษี : $cus_tax_id</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>สาขาที่ : $cus_branch &nbsp;</td>". 
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; ประเภทลูกค้าที่ขอแต่งตั้ง : $type_code_name</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>ประเภทการจดทะเบียนบริษัท : $type_bus_name &nbsp;</td>". 
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; เงื่อนไขการชำระเงิน : $term_name</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>กำหนดการชำระเงิน : $cond_term_name &nbsp;</td>". 
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp; หลักค้ำประกันโดย ธนาคาร : $cus_bg1_name</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>วงเงิน : $cus_cr_limit1 &nbsp;บาท</td>". 
				"</tr>".
				// เอกสารประกอบการพิจารณา
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20>&nbsp; เอกสารประกอบการพิจารณา</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20></td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[0] $bookArray[0]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[1] $bookArray[1]</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[2] $bookArray[2]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[3] $bookArray[3]</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[4] $bookArray[4]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[5] $bookArray[5]</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[6] $bookArray[6]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[7] $bookArray[7]</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp;$chkArray[8] $bookArray[8]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp;$chkArray[9] $bookArray[9]</td>".
				"</tr>".

				// เป้าหมายการขาย
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=5'></td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=5'></td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;เป้าหมายการขาย 6 เดือนแรก เริ่มตั้งแต่ : $cusd_tg_beg_date &nbsp;ถึง&nbsp; $cusd_tg_end_date</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;ประมาณการขายทุกเดือน : $cusd_sale_est บาท หรือ ภายใน 6 เดือนขายได้ : $cusd_sale_vol บาท &nbsp;</td>".
				"</tr>".

				// ชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า
				/* "<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;ชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า : $contactArray[0] &nbsp; / $contactArray[1]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;ตำแหน่ง : $contactPosArray[0] &nbsp; / $contactPosArray[1]</td>".
				"</tr>". */
				"<tr>".
					"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;ชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า : $contactArray[0] &nbsp; $contactPosArray[0] &nbsp;&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;&nbsp; $contactArray[1] &nbsp; $contactPosArray[1]</td>".
					// "<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;ตำแหน่ง : $contactArray[1] &nbsp; $contactPosArray[1]</td>".
				"</tr>".
				// กำหนดการจ่ายชำระเงิน
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid; dotted; width=25% height=15'>&nbsp;บุคคลที่ติดต่อเรื่องการจ่ายชำระเงิน : $cus_contact_nme_pay</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid; dotted; width=25% height=15'>&nbsp;สถานที่วางบิล : $cus_pay_addr &nbsp; / tel. $cus_contact_tel &nbsp;/ fax. $cus_contact_fax &nbsp;/ อีเมล $cus_contact_email</td>".
				"</tr>".
			"</table>"; 
		$pdf->WriteHTML($body);

		// วัตถุประสงค์ / คุณสมบัติลูกค้า  / กิจการในเครือ / รายชื่อผู้แทนจำหน่ายทั่วไป /รายชื่อคู่แข่ง
		//$pdf->WriteHTML($row_rpt);
		$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; cellpadding='5' cellspacing='0' font-size:7pt'>"); 
		$cusd_obj=
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted; width=25% height=25'>&nbsp;วัตถุประสงค์  : $cusd_obj1 &nbsp; / &nbsp; $cusd_obj2 &nbsp; / &nbsp; $cusd_obj3 &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted; width=25% height=25'>&nbsp;คุณสมบัติลูกค้า  : $cusd_cust_prop1 &nbsp; / &nbsp; $cusd_cust_prop2 &nbsp; / &nbsp; $cusd_cust_prop3 &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted; width=25% height=25'>&nbsp;กิจการในเครือ  : $cusd_aff1 &nbsp; / &nbsp; $cusd_aff2 &nbsp; / &nbsp; $cusd_aff3 &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px; width=25% height=20'>&nbsp;รายชื่อผู้แทนจำหน่ายทั่วไป ซึ่งลูกค้าที่ขอแต่งตั้งติดต่อเป็นประจำ (Trade Reference) &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted; width=25% height=20'>&nbsp;1. $cusd_dealer1_nme &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted; width=25% height=20'>&nbsp;มูลค่าเฉลี่ย/เดือน  : $cusd_dealer1_avg_val &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted; width=25% height=20'>&nbsp;2. $cusd_dealer2_nme &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted; width=25% height=20'>&nbsp;มูลค่าเฉลี่ย/เดือน  : $cusd_dealer2_avg_val &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted; width=25% height=20'>&nbsp;3. $cusd_dealer3_nme &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted; width=25% height=20'>&nbsp;มูลค่าเฉลี่ย/เดือน  : $cusd_dealer3_avg_val &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px; width=25% height=20'>&nbsp;รายชื่อคู่แข่ง ซึ่งลูกค้าที่ขอแต่งตั้งซื้อเป็นประจำ &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;; width=25% height=20'>&nbsp;1. $cusd_comp1_nme &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;; width=25% height=20'>&nbsp;มูลค่าเฉลี่ย/เดือน  : $cusd_comp1_avg_val &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;; width=25% height=20'>&nbsp;2. $cusd_comp2_nme &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;; width=25% height=20'>&nbsp;มูลค่าเฉลี่ย/เดือน  : $cusd_comp2_avg_val &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px dotted;; width=25% height=20'>&nbsp;3. $cusd_comp3_nme &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px dotted;; width=25% height=20'>&nbsp;มูลค่าเฉลี่ย/เดือน  : $cusd_comp3_avg_val &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;ผู้เสนอ : $cusd_is_sale1_name &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;Email : $cusd_is_sale1_email &nbsp;&nbsp;&nbsp;&nbsp;เบอร์โทร : $cusd_is_sale1_tel</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;ชื่อผู้แทนขาย (Inside Sale) : $cusd_is_sale2_name &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;Email : $cusd_is_sale2_email &nbsp;&nbsp;&nbsp;&nbsp;เบอร์โทร : $cusd_is_sale2_tel</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;ชื่อผู้แทนขาย (Outside Sale) : $cusd_os_sale_name &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;Email : $cusd_os_sale_email &nbsp;&nbsp;&nbsp;&nbsp;เบอร์โทร : $cusd_os_sale_tel</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;; width=25% height=15'>&nbsp;ชื่อผู้จัดการ : $cusd_sale_manager_name &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;; width=25% height=15'>&nbsp;Email : $cusd_is_sale2_email &nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง : $cusd_mgr_pos</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid; width=25% height=30'>&nbsp;ความเห็นของผู้แทนขาย  : $cusd_sale_reason &nbsp; </td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;; width=25% height=20'>&nbsp;ความเห็นสินเชื่อ : &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;; width=25% height=20'>&nbsp;ตรวจสอบการเป็นบุคคลล้มละลาย  $chk_bank_dom ปกติ  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$chk_bank_exp Export(ไม่มีข้อมูล)</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid; width=25% height=20'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -  $cr_remark &nbsp; </td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;; width=25% height=25'>&nbsp;ความเห็นผู้จัดการสินเชื่อ : $cr_mgr_remark &nbsp;</td>".
			"</tr>";
		$pdf->WriteHTML($cusd_obj);
		$pdf->WriteHTML("</table>");
	}
	else 
	{
		$body=
			"<table width=100% border=1 style='border-collapse: collapse; cellpadding='5' cellspacing='0' font-size:7pt'>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15></td>".
					"<td colspan='2' align=right style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15></td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; รหัสลูกค้า : $cus_code &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; วันที่ขออนุมัติ : $cus_date </td>".
					"<td colspan='2' align=right style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=15>เอกสารเลขที่ : $cus_app_nbr &nbsp;</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; ชื่อ : $cus_reg_nme</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>ที่อยู่ : $address &nbsp;</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; เบอร์โทรศัพท์ : $cus_tel &emsp;&emsp;&emsp;&emsp;&emsp;  Fax. : $cus_fax</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>อีเมล : $cus_email &nbsp;</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; เลขประจำตัวผู้เสียภาษี : $cus_tax_id</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>สาขาที่ : $cus_branch &nbsp;</td>". 
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; ประเภทลูกค้าที่ขอแต่งตั้ง : $type_code_name</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>ประเภทการจดทะเบียนบริษัท : $type_bus_name &nbsp;</td>". 
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; เงื่อนไขการชำระเงิน : $term_name</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>กำหนดการชำระเงิน : $cond_term_name &nbsp;</td>". 
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp; หลักค้ำประกันโดย ธนาคาร : $cus_bg1_name</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>วงเงิน : $cus_cr_limit1 &nbsp;บาท</td>". 
				"</tr>".
				// เอกสารประกอบการพิจารณา
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20>&nbsp; เอกสารประกอบการพิจารณา</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20></td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[0] $bookArray[0]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[1] $bookArray[1]</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[2] $bookArray[2]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[3] $bookArray[3]</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[4] $bookArray[4]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[5] $bookArray[5]</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[6] $bookArray[6]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp;$chkArray[7] $bookArray[7]</td>".
				"</tr>".
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp;$chkArray[8] $bookArray[8]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp;$chkArray[9] $bookArray[9]</td>".
				"</tr>".

				// เป้าหมายการขาย
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=5'></td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=5'></td>".
				"</tr>".

				// ชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า
				/* "<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;ชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า : $contactArray[0] &nbsp; / $contactArray[1]</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;ตำแหน่ง : $contactPosArray[0] &nbsp; / $contactPosArray[1]</td>".
				"</tr>". */
				"<tr>".
					"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;ชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า : $contactArray[0] &nbsp; $contactPosArray[0] &nbsp;&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;&nbsp; $contactArray[1] &nbsp; $contactPosArray[1]</td>".
					// "<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid; dotted; width=25% height=15'>&nbsp;ตำแหน่ง : $contactArray[1] &nbsp; $contactPosArray[1]</td>".
				"</tr>".
				// กำหนดการจ่ายชำระเงิน
				"<tr>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid; dotted; width=25% height=15'>&nbsp;บุคคลที่ติดต่อเรื่องการจ่ายชำระเงิน : $cus_contact_nme_pay</td>".
					"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid; dotted; width=25% height=15'>&nbsp;สถานที่วางบิล : $cus_pay_addr &nbsp; / tel. $cus_contact_tel &nbsp;/ fax. $cus_contact_fax &nbsp;/ อีเมล $cus_contact_email</td>".
				"</tr>".
			"</table>"; 
		$pdf->WriteHTML($body);
		
		// วัตถุประสงค์ / คุณสมบัติลูกค้า  / กิจการในเครือ / รายชื่อผู้แทนจำหน่ายทั่วไป /รายชื่อคู่แข่ง
		//$pdf->WriteHTML($row_rpt);
		$pdf->WriteHTML("<table width=100% border=1 style='border-collapse: collapse; cellpadding='5' cellspacing='0' font-size:7pt'>"); 
		$cusd_obj=
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;ผู้เสนอ : $cusd_is_sale1_name &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;Email : $cusd_is_sale1_email &nbsp;&nbsp;&nbsp;&nbsp;เบอร์โทร : $cusd_is_sale1_tel</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;ชื่อผู้แทนขาย (Inside Sale) : $cusd_is_sale2_name &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;Email : $cusd_is_sale2_email &nbsp;&nbsp;&nbsp;&nbsp;เบอร์โทร : $cusd_is_sale2_tel</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;ชื่อผู้แทนขาย (Outside Sale) : $cusd_os_sale_name &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px dotted; width=25% height=15'>&nbsp;Email : $cusd_os_sale_email &nbsp;&nbsp;&nbsp;&nbsp;เบอร์โทร : $cusd_os_sale_tel</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;; width=25% height=15'>&nbsp;ชื่อผู้จัดการ : $cusd_sale_manager_name &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;; width=25% height=15'>&nbsp;Email : $cusd_is_sale2_email &nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง : $cusd_mgr_pos</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid; width=25% height=30'>&nbsp;ความเห็นของผู้แทนขาย  : $cusd_sale_reason &nbsp; </td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;; width=25% height=20'>&nbsp;ความเห็นสินเชื่อ : &nbsp;</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;; width=25% height=20'>&nbsp;ตรวจสอบการเป็นบุคคลล้มละลาย  $chk_bank_dom ปกติ  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$chk_bank_exp Export(ไม่มีข้อมูล)</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid; width=25% height=20'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -  $cr_remark &nbsp; </td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;; width=25% height=25'>&nbsp;ความเห็นผู้จัดการสินเชื่อ : $cr_mgr_remark &nbsp;</td>".
			"</tr>";
		$pdf->WriteHTML($cusd_obj);
		$pdf->WriteHTML("</table>");
	}

	$footer=
		"<table width=100% border=0 style='border-collapse: collapse; font-size:7pt'>".
			// ผู้เสนออนุมัติ 
			"<tr>". 
				"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
				"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
				"<td style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=15></td>".
			"</tr>".
	
			"<tr>". 
				"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
				"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>...................................................</td>".
			"</tr>".
			"<tr>".
				"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
				"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_is_sale1_name</td>".
			"</tr>".
			"<tr>".
				"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15></td>".
				"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>ผู้เสนอ ( วันที่ $cus_date )</td>".
			"</tr>".

			"<tr>".  
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=2></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=2></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=2></td>".
			"</tr>".
			
			"<tr>". 
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$mgr_status_app</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$status_app_array[0]</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$status_app_array[1]</td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$mgr_name<br></td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_review1_name<br><br></td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_review2_name<br><br></td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$mgr_pos ( วันที่ $cr_mgr_app_date )</td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[0] ( วันที่ $apprv_date_array[0] )</td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[1] ( วันที่ $apprv_date_array[1] )</td>".
			"</tr>".

			"<tr>".  
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=2></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=2></td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=2></td>".
			"</tr>".
			
			"<tr>". 
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$status_app_array[2]</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$status_app_array[3]</td>".
				"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$status_app_array[4]</td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_review3_name<br><br></td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_review4_name<br><br></td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_approve_fin_name<br><br></td>".
			"</tr>".
			"<tr>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[2] ( วันที่ $apprv_date_array[2] )</td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[3] ( วันที่ $apprv_date_array[3] )</td>".
				"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[4] ( วันที่ $apprv_date_array[4] )</td>".
			"</tr>".
		"</table>";
	$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:7pt'>.</font><td></tr>");
	$pdf->SetHTMLFooter($footer);
	

	if ($savefile) {
		//SAVE FILE
		$output_folder = $output_folder; 
		//$output_filename = $cus_app_nbr."-".$filename;
		$output_filename = 'ใบขออนุมัติ'.$filename."-".$cus_app_nbr;
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

function print_formchgcust($cus_app_nbr,$savefile,$output_folder,$cr_output_filename,$conn,$watermark_text) {
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y");	
	$curMonth = date('Y-m'); 

	$sql = "SELECT * from  sysc_ctrl ";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
	$final_mgr_name = mssql_escape($row['sysc_final_approver']);
	$mgr_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' +emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$final_mgr_name,$conn);
	$mgr_pos = findsqlval("emp_mstr","emp_th_pos_name","emp_user_id",$final_mgr_name,$conn);

	$params=array($cus_app_nbr);
	$sql = "SELECT * from cus_app_mstr where cus_app_nbr = ? ";
	$result = sqlsrv_query($conn, $sql,$params);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
	$cus_app_nbr = mssql_escape($row['cus_app_nbr']);
	$cus_code = mssql_escape($row['cus_code']);
	
	$cus_code_name = findsqlval("cus_mstr","cus_name1","cus_nbr",$cus_code,$conn);
	$cus_address = findsqlval("cus_mstr","cus_street+' '+cus_street2+' '+cus_district+' '+cus_city+' '+cus_zipcode","cus_nbr",$cus_code,$conn);
	$cus_date = mssql_escape(dmytx($row['cus_date']));
	$cus_cond_cust = mssql_escape($row['cus_cond_cust']);
	
	if($cus_cond_cust=="c3"){
		$chkbox3 = '<img src="../_images/check_true.png" width=10px>';
		$filename = "เปลี่ยนแปลงชื่อ";
	} else {
		$chkbox3 = '<img src="../_images/check_blank.png" width=10px>';
	} 
	if($cus_cond_cust=="c4"){
		$chkbox4 = '<img src="../_images/check_true.png" width=10px>';
		$filename = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
	} else {
		$chkbox4 = '<img src="../_images/check_blank.png" width=10px>';
	} 
	if($cus_cond_cust=="c5"){
		$chkbox5 = '<img src="../_images/check_true.png" width=10px>';
		$filename = "เปลี่ยนแปลงชื่อและที่อยู่";
	} else {
		$chkbox5 = '<img src="../_images/check_blank.png" width=10px>';
	} 
	if($cus_cond_cust=="c6"){
		$chkbox6 = '<img src="../_images/check_true.png" width=10px>';
		$filename = "ยกเลิก Code ลูกค้า";
	} else {
		$chkbox6 = '<img src="../_images/check_blank.png" width=10px>';
	} 
	
	$cus_tg_cust = mssql_escape($row['cus_tg_cust']); // domestic , export
	
	$cus_type_code = mssql_escape($row['cus_cust_type']);
	$type_code_name = findsqlval("cus_type_mstr","cus_type_name","cus_type_code",$cus_type_code,$conn);

	$cus_reg_nme = rtrim(mssql_escape($row['cus_reg_nme']));
	$cus_reg_addr = mssql_escape($row['cus_reg_addr']);
	$cus_district = mssql_escape($row['cus_district']);
	$cus_amphur = mssql_escape($row['cus_amphur']);
	$cus_prov = mssql_escape($row['cus_prov']);
	$cus_zip = mssql_escape($row['cus_zip']);
	$cus_country = mssql_escape($row['cus_country']);
	$address = $cus_reg_addr;
	//$address = $cus_reg_addr." ".$cus_district." ".$cus_amphur." ".$cus_prov." ".$cus_zip." ".$cus_country;

	
	$cus_tax_id = mssql_escape($row['cus_tax_id']);
	$cus_branch = mssql_escape($row['cus_branch']);

	$cus_effective_date = mssql_escape(dmytx($row['cus_effective_date']));  
	
	$params = array();
	$sql_cr= "SELECT * FROM book_mstr order by book_no";
	$result = sqlsrv_query($conn, $sql_cr,$params);
	$i = 0;
	$bookArray = array();	
	$statusArray = array();
	$chkArray = array();
	while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			if($cus_tg_cust=="dom"){
				$bookArray[$i] = rtrim($row_cr['book_dom']);
			}
			else
			{
				$bookArray[$i] = rtrim($row_cr['book_exp']);
			}
			
			$statusArray[$i] = rtrim($row_cr['book_status']);
			if($statusArray[$i]  == '1'){
				$chkArray[$i] = '<img src="../_images/check_true.png" width=10px>';
				
			}
			else {
				$chkArray[$i] = '<img src="../_images/check_blank.png" width=10px>';
			}
			$i++;
		}	
		

	////
	$apprv_pos_array = array();
	$params = array($cus_app_nbr);
	$query_det = "SELECT * FROM cus_app_det WHERE cusd_app_nbr = ?";
	$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
	$rowCounts = sqlsrv_num_rows($result);
	if($rowCounts > 0){
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			// -- page 3 --//
			$cusd_tg_beg_date = mssql_escape(dmytx($row['cusd_tg_beg_date']));
			$cusd_tg_end_date = mssql_escape(dmytx($row['cusd_tg_end_date']));
			$cusd_sale_est = CheckandShowNumber(mssql_escape($row['cusd_sale_est']),0);
			$cusd_sale_vol = CheckandShowNumber(mssql_escape($row['cusd_sale_vol']),0);

			$cusd_is_sale1 = mssql_escape($row['cusd_is_sale1']);
			$cusd_is_sale1_email = mssql_escape($row['cusd_is_sale1_email']);
			$cusd_is_sale1_tel = mssql_escape($row['cusd_is_sale1_tel']);
			$cusd_is_sale1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_is_sale1,$conn);


			$cusd_is_sale2 = mssql_escape($row['cusd_is_sale2']);
			$cusd_is_sale2_email = mssql_escape($row['cusd_is_sale2_email']);
			$cusd_is_sale2_tel = mssql_escape($row['cusd_is_sale2_tel']);
			$cusd_is_sale2_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_is_sale2,$conn);

			$cusd_os_sale = mssql_escape($row['cusd_os_sale']);
			$cusd_os_sale_email = mssql_escape($row['cusd_os_sale_email']);
			$cusd_os_sale_tel = mssql_escape($row['cusd_os_sale_tel']);
			$cusd_os_sale_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_os_sale,$conn);

			$cusd_os_sale_mgr_code = mssql_escape($row['cusd_sale_manager']);
			$cusd_manger_email = mssql_escape($row['cusd_manger_email']);
			$cusd_sale_manager_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_os_sale_mgr_code,$conn);
			$cusd_mgr_pos = findsqlval("emp_mstr","emp_th_pos_name","emp_scg_emp_id",$cusd_os_sale_mgr_code,$conn);
			
			// -- page 5 --//
			$cusd_sale_reason = mssql_escape($row['cusd_sale_reason']);
			$cusd_op_app = mssql_escape($row['cusd_op_app']);

			// -- approve --//
			// $cusd_review1 = mssql_escape($row['cusd_review1']);
			// $cusd_review1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review1,$conn);
			// $apprv_pos_array[0] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review1,$conn);
		
			// $cusd_review2 = mssql_escape($row['cusd_review2']);
			// $cusd_review2_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review2,$conn);
			// $apprv_pos_array[1] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review2,$conn);

			// $cusd_review3 = mssql_escape($row['cusd_review3']);
			// $cusd_review3_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review3,$conn);
			// $apprv_pos_array[2] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review3,$conn);

			// $cusd_review4 = mssql_escape($row['cusd_review4']);
			// $cusd_review4_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review4,$conn);
			// $apprv_pos_array[3] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review4,$conn);

			// $cusd_approve_fin = mssql_escape($row['cusd_approve_fin']);
			// $cusd_approve_fin_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_approve_fin,$conn);
			// $apprv_pos_array[4] = findsqlval("emp_mstr","'('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_approve_fin,$conn);
			
		}
	} 

	$apprv_id_array = array(); //เก็บ apprv_emp_id ผู้อนุมัติทั้งหมด
	$apprv_pos_array = array();
	$apprv_date_array = array();
	$status_app_array = array();
	$params = array($cus_app_nbr);
	$sql = "select * FROM apprv_person where apprv_cus_nbr=?  order by apprv_cus_nbr,apprv_id asc";
	$result = sqlsrv_query($conn, $sql, $params, array("Scrollable" => 'keyset' ));
	$rowCounts = sqlsrv_num_rows($result);
    if($rowCounts > 0) {
		while($rows = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{		
            $apprv_emp_id = $rows['apprv_emp_id'];
			$apprv_position = $rows['apprv_position'];
			$apprv_lasted = $rows['apprv_lasted'];
			$apprv_status = $rows['apprv_status'];

			if($apprv_status == "AP"){
				$status_app = "<font color='green'>*** Approved *** <font>";
			}else {
				$status_app = "";
			}

			if(isset($rows['apprv_date'])){
				$apprv_date = date_format($rows['apprv_date'], "d/m/Y");
			}else {
                $apprv_date = "";
            }

            array_push($apprv_id_array,$apprv_emp_id);
			array_push($apprv_pos_array,$apprv_position);
			array_push($apprv_date_array,$apprv_date);
			array_push($status_app_array,$status_app);

			if($apprv_lasted=="Y" && $apprv_status=="AP"){
				$watermark_text = '*** อนุมัติแล้ว ***';
			}
        }
    }
    $apprv_id_array_count = count($apprv_id_array);
    if($apprv_id_array_count == 1) {
		$status_approved1 = $status_app_array[0];
        $cusd_review1 = $apprv_id_array[0];
        $cusd_review1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review1,$conn);
    }
    else
    {    
        $cusd_review1 = $apprv_id_array[0];
        $cusd_review1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review1,$conn);
		
		$cusd_review2 = $apprv_id_array[1];
        $cusd_review2_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review2,$conn);
		
		$cusd_review3 = $apprv_id_array[2];
        $cusd_review3_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review3,$conn);
		
		$cusd_review4 = $apprv_id_array[3];
        $cusd_review4_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_review4,$conn);
		
		$cusd_approve_fin = $apprv_id_array[4];
        $cusd_approve_fin_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_approve_fin,$conn);
    }
	// หัวจดหมาย	
	$header = 
	"<table width=100% border=0 style='border-collapse: collapse;  cellpadding='5' cellspacing='0' font-size:7pt'>".
		"<tr>".   
			"<td colspan=10 align=center style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=60%></td>".
		"</tr>".
		"<tr>".   
			"<td colspan=10 align=center style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=60%><h3> บริษัทเอสซีจี เซรามิกส์ จำกัด(มหาชน) / SCG Ceramics Public Company Limited</h3></td>".
		"</tr>".
		"<tr>".   
		 	"<td colspan=10 align=center style='line-height: 1.8; font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;'><h4> ใบขออนุมัติ$filename</h4></td>".
		"</tr>".
	"</table>";
		
	require_once('../_libs/mpdf/mpdf.php');
	$pdf = new mPDF('th', 'A4', '0', 'THSaraban'); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
	$pdf->SetTitle('ใบขออนุมัติ'.$filename.'-'.$cus_app_nbr);
	$pdf->SetHTMLHeader($header);
	$pdf->SetWatermarkText($watermark_text);
	$pdf->watermarkTextAlpha = 0.1;
	$pdf->showWatermarkText = true;
	$pdf->SetFooter("SCG Ceramics Public Company Limited");
	//$pdf->SetHTMLFooter('<div style="text-align:center;font-size: 12px;">Page {PAGENO}/{nbpg}</div>');
	$pdf->AddPage('', // L - landscape, P - portrait 
	
	'', '', '1', '0',
	5, // margin_left
	5, // margin right
	14, // margin top   20
	20, // margin bottom 40
	5, // margin header
	5); // margin footer	
	
	$data = "";
	$max_line = 30;
	$line_cnt = 0;
		
	$body=
		"<table width=100% border=1 style='border-collapse: collapse; cellpadding='5' cellspacing='0' font-size:7pt'>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=5></td>".
				"<td colspan='2' align=right style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=5></td>".
			"</tr>". 
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; รหัสลูกค้า : $cus_code &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; วันที่ขออนุมัติ : $cus_date &nbsp;</td>".
				"<td colspan='2' align=right style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>เอกสารเลขที่ : $cus_app_nbr &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp; เลขประจำตัวผู้เสียภาษี (Tax ID No.) / เลขที่ทะเบียนพาณิชย์ : $cus_tax_id</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20>&nbsp; ข้อมูลเดิม </td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; ชื่อลูกค้า : $cus_code_name</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp; ที่อยู่ : $cus_address</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20>&nbsp; ข้อมูลที่ขอเปลี่ยนแปลง </td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkbox3 เปลี่ยนแปลง ชื่อ </td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkbox4 เปลี่ยนแปลงที่อยู่จดทะเบียน </td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkbox5 เปลี่ยนแปลงชื่อและที่อยู่ </td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkbox6 ยกเลิก Code ลูกค้า </td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=25% height=5></td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; ข้อมูลใหม่ </td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; ชื่อลูกค้า : $cus_reg_nme</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px dotted;' width=25% height=15>&nbsp; ที่อยู่ : $address</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp; วันที่เริ่มใช้ : $cus_effective_date</td>".
			"</tr>".

			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20>&nbsp; เอกสารประกอบการพิจารณา</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15></td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkArray[0] $bookArray[0]</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkArray[1] $bookArray[1]</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkArray[2] $bookArray[2]</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkArray[3] $bookArray[3]</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkArray[4] $bookArray[4]</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkArray[5] $bookArray[5]</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkArray[6] $bookArray[6]</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; $chkArray[7] $bookArray[7]</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp; $chkArray[8] $bookArray[8]</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp; $chkArray[9] $bookArray[9]</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20>&nbsp; เหตุผลในการเปลี่ยนแปลง / ยกเลิก</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=20>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -  $cusd_sale_reason &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=15>&nbsp; สถานะลูกค้า ณ วันที่ : $cr_cus_chk_date &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp; หนี้สินค่าสินค้า : $cr_debt &nbsp; บาท</td>".
				"<td colspan='2' align=left style='font-size:7pt;border-left:0px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=15>&nbsp; วันที่ครบกำหนดชำระเงินล่าสุด : $cr_due_date &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20>&nbsp; บันทึกเกี่ยวกับภาระค้ำประกัน : &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=20>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -  $cr_rem_guarantee &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20>&nbsp; อื่น ๆ : &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=20>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -  $cr_rem_other &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=25% height=20>&nbsp; ความเห็นสินเชื่อ : &nbsp;</td>".
			"</tr>".
			"<tr>".
				"<td colspan='4' align=left style='font-size:7pt;border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=25% height=20>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -  $cr_remark &nbsp;</td>".
			"</tr>".
		"</table>"; 
	$pdf->WriteHTML($body);

		// กรณีไม่ใช่เปลี่ยนแปลงที่อยู่จดทะเบียน	
		if($cus_cond_cust!="c4"){
			/* $signature_cr=
				"<table width=100% border=0 style='border-collapse: collapse; font-size:7pt'>".
					"<tr>". 
						"<td style='font-size:7pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
					"</tr>".
					
					"<tr>". 
						"<td style='font-size:7pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>...................................................</td>".
					"</tr>".
					"<tr>".
						"<td style='font-size:7pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$mgr_name</td>".
					"</tr>".
					"<tr>".
						"<td style='font-size:7pt; border-left:1px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>$mgr_pos ( วันที่ $cr_mgr_app_date )</td>".
					"</tr>".
				"</table>";
			$pdf->WriteHTML($signature_cr);
			$pdf->WriteHTML("</table>"); */

			$footer=
				"<table width=100% border=0 style='border-collapse: collapse; font-size:7pt'>".
					// ผู้เสนออนุมัติ 
					"<tr>". 
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
						"<td style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=15></td>".
					"</tr>".
			
					"<tr>". 
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=15% height=15>...................................................</td>".
					"</tr>".
					"<tr>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_is_sale1_name</td>".
					"</tr>".
					"<tr>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15></td>".
						"<td style='font-size:7pt; border-left:0px solid;border-right:0px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>ผู้เสนอ ( วันที่ $cus_date )</td>".
					"</tr>".

					"<tr>".  
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=2></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=2></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:1px solid;border-bottom:0px solid;' width=20% height=2></td>".
					"</tr>".
					
					"<tr>". 
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$mgr_status_app</td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$status_app_array[0]</td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$status_app_array[1]</td>".
					"</tr>".
	
					"<tr>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$mgr_name<br></td>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_review1_name<br><brtd>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_review2_name<br><br></td>".
					"</tr>".
					"<tr>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$mgr_pos ( วันที่ $cr_mgr_app_date )</td>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[0] ( วันที่ $apprv_date_array[0] )</td>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[1] ( วันที่ $apprv_date_array[1] )</td>".
					"</tr>".

					"<tr>".  
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=2></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=2></td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=2></td>".
					"</tr>".
					
					"<tr>". 
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$status_app_array[2]</td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$status_app_array[3]</td>".
						"<td align=center style='font-size:7pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$status_app_array[4]</td>".
					"</tr>".
					"<tr>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_review3_name<br><br></td>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_review4_name<br><brtd>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=15>$cusd_approve_fin_name<br><br></td>".
					"</tr>".
					"<tr>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[2] ( วันที่ $apprv_date_array[2] )</td>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[3] ( วันที่ $apprv_date_array[3] )</td>".
						"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=15>$apprv_pos_array[4] ( วันที่ $apprv_date_array[4] )</td>".
					"</tr>".
				"</table>";
			$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:7pt'>.</font><td></tr>");
			$pdf->SetHTMLFooter($footer);
		}	
		else
		{
			$footer=
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
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$cusd_is_sale1_name<br><brtd>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$mgr_name<br></td>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:0px solid;' width=20% height=20>$cusd_review1_name<br><br></td>".
				"</tr>".
				"<tr>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>ผู้เสนอ ( วันที่ $cus_date )</td>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>$mgr_pos ( วันที่ $cr_mgr_app_date )</td>".
					"<td align=center style='font-size:6.5pt; border-left:1px solid;border-right:1px solid;border-top:0px solid;border-bottom:1px solid;' width=20% height=20>$apprv_pos_array[0] ( วันที่ $apprv_date_array[0] )</td>".
				"</tr>".
			"</table>";
			$pdf->SetHTMLFooter("<tr><td height=20><font style='font-size:7pt'>.</font><td></tr>");
			$pdf->SetHTMLFooter($footer);
		}	
	
			if ($savefile) {
				//SAVE FILE
				$output_folder = $output_folder; 
				//$output_filename = $filename;
				$output_filename = 'ใบขออนุมัติ'.$filename."-".$cus_app_nbr;
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