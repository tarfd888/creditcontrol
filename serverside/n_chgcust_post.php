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
	} else {
		echo "System detect CSRF attack!!";
		exit;
	}
	$params = array();
	
	
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");
	$curr_date = ymd(date("d/m/Y"));
	$errortxt = "";
	$pg = "";
	$check_status = true;
	$cus_step_code="0";
	$apprv_status="";

	// check to acting position
	$sql = "SELECT * from  sysc_ctrl ";
	$result = sqlsrv_query($conn, $sql);	
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
	$sysc_cmo_act = mssql_escape($row['sysc_cmo_act']);
	$sysc_cmo_pos_name = mssql_escape($row['sysc_cmo_pos_name']);

	$sysc_cfo_act = mssql_escape($row['sysc_cfo_act']);
	$sysc_cfo_pos_name = mssql_escape($row['sysc_cfo_pos_name']);

	$sysc_md_act = mssql_escape($row['sysc_md_act']);
	$sysc_md_pos_name = mssql_escape($row['sysc_md_pos_name']);

	$action = mssql_escape($_POST['action']);
  	$info_cust = mssql_escape(decrypt($_POST['info_cust'], $key));
	$cus_app_nbr  = mssql_escape(decrypt($_POST['cus_app_nbr'], $key));
	$cus_step_code  = mssql_escape(decrypt($_POST['old_step_code'], $key));
	
	$cus_cond_cust = mssql_escape($_POST['ch_form_cus']);	
	// -- page 1 -- //
	$cus_tg_cust = mssql_escape($_POST['cus_tg_cust']);	
	$cus_cust_type = mssql_escape($_POST['cus_cust_type']);	
	$cus_cust_type_oth = mssql_escape($_POST['cus_cust_type_oth']);	
	// $cus_code = mssql_escape(trim($_POST['cus_code']));	// for update 

	$cus_code_mas = mssql_escape(trim($_POST['cus_code_mas']));	// for add new

	$cus_reg_nme = mssql_escape(trim($_POST['cus_reg_nme']));	
	$cus_reg_addr = mssql_escape(trim($_POST['cus_reg_addr']));
	$cus_tax_id = mssql_escape(trim($_POST['cus_tax_id']));
	$cus_branch = mssql_escape(trim($_POST['cus_branch']));
	$cus_type_bus_dom = mssql_escape($_POST['cus_type_bus_dom']);
	$cus_type_bus_exp = mssql_escape($_POST['cus_type_bus_exp']);
	
	$cusd_is_sale1 = mssql_escape(trim($_POST['cusd_is_sale1']));
	$cusd_is_sale1_email = mssql_escape(trim($_POST['cusd_is_sale1_email']));
	$cusd_is_sale1_tel = mssql_escape(trim($_POST['cusd_is_sale1_tel']));
	$cusd_is_sale2 = mssql_escape(trim($_POST['cusd_is_sale2']));
	$cusd_is_sale2_email = mssql_escape(trim($_POST['cusd_is_sale2_email']));
	$cusd_is_sale2_tel = mssql_escape(trim($_POST['cusd_is_sale2_tel']));

	$cusd_os_sale = mssql_escape(trim($_POST['cusd_os_sale']));
	$cusd_os_sale_email = mssql_escape(trim($_POST['cusd_os_sale_email']));
	$cusd_os_sale_tel = mssql_escape(trim($_POST['cusd_os_sale_tel']));

	$cusd_sale_manager = mssql_escape(trim($_POST['cusd_os_sale_mgr_code']));  
	$cusd_manger_email = mssql_escape(trim($_POST['cusd_mgr_email']));

	$cus_country = findsqlval("cus_mstr","cus_country","cus_nbr",$cus_code_mas,$conn);
	$cus_country = findsqlval("country_mstr","country_desc","country_code",$cus_country,$conn);

	$emp_scg_emp_id = findsqlval("emp_mstr","emp_scg_emp_id","emp_user_id",$user_login,$conn);
	
	$emp_email_bus = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$user_login,$conn);
	$emp_email_bus = strtolower($emp_email_bus);

	//$cus_whocanread = findsqlval("cus_app_mstr","cus_whocanread","cus_app_nbr",$cus_app_nbr,$conn);
	$cus_whocanread = mssql_escape(trim($_POST['cus_whocanread']));
	// -- page 4 -- //	
	$TemImageRandom = decrypt(mssql_escape($_POST['temimagerandom']), $key);

	// -- page 5 -- //	
	$cusd_sale_reason = mssql_escape(trim($_POST['cusd_sale_reason']));
	$cusd_op_app = mssql_escape(trim($_POST['cusd_op_app']));
	$cusd_review1 = mssql_escape(trim($_POST['cusd_review1']));
	$sec_apprv = $cusd_review1; // ผส. / ผผ

	$cusd_review2 = mssql_escape(trim($_POST['cusd_review2']));
	$div_apprv = $cusd_review2; // ผฝ

	if($div_apprv =="" && $cus_cond_cust != "c4"){
		$div_apprv = "none";
	}
	
	if (!inlist("c4",$cus_cond_cust)) { 
		$cusd_review3 = mssql_escape(trim($_POST['sysc_cmo_id']));
		$cmo_apprv = $cusd_review3; 
	
		$cusd_review4 = mssql_escape(trim($_POST['sysc_cfo_id']));
		$cfo_apprv = $cusd_review4;
	
		$cusd_approve_fin = mssql_escape(trim($_POST['sysc_md_id']));
		$md_apprv = $cusd_approve_fin;
	}
	if($cus_cond_cust=="c3"){
		//$("#cus_mas_addr").val(item.cus_street+" "+item.cus_street2+" "+item.cus_street3+" "+item.cus_street4+" "+item.cus_street5+" "+item.cus_district+" "+item.cus_city+" "+item.cus_zipcode+" เลขประจำตัวผู้เสียภาษี (Tax ID No.) "+item.cus_tax_nbr3+" สาขาที่ (Branch No.) "+item.cus_tax_nbr4+" Account Group "+item.cus_acc_group);
		$cus_reg_addr = findsqlval("cus_mstr","cus_street+' '+cus_street2+' '+cus_street3+' '+cus_street4+' '+cus_street5+' '+cus_district+' '+cus_city+' '+cus_zipcode","cus_nbr",$cus_code_mas,$conn);
	}

	// -- เปลี่ยนแปลงข้อมูลลูกค้า --//
	$cus_effective_date = mssql_escape(ymd($_POST['cus_effective_date']));

	switch($cus_tg_cust){
		case 1 :
			$info_tg_cust = 'ลูกค้าในประเทศ (Domestic)';
			break;
		case 2 :
			$info_tg_cust = 'ลูกค้าต่างประเทศ (Export)';
			break;  
	}

	if($cus_cr_limit1 == ""){
		$cus_cr_limit1 = 0;
	} 
	if($cus_cr_limit2 == ""){
		$cus_cr_limit2 = 0;
	} 
	$cus_create_by=$user_login;
	$errorflag = false;
	$errortxt = "";
	 if (inlist("cust_add,cust_edit",$action)) {	

			if ($cus_tg_cust == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ กลุ่มลูกค้า	 ]";
			}
			if ($cusd_review1==""){
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;
				$errortxt .= "กรุณาระบุ - [ผู้พิจารณา ]";
			}
			if ($cusd_sale_reason==""){
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;
				$errortxt .= "กรุณาระบุ - [เหตุผลในการเปลี่ยนแปลง / ยกเลิก (ใช้สำหรับเสนอขออนุมัติ) ]";
			}
			if ($cusd_is_sale1=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ผู้เสนอ  ]";
			}

			if ($cusd_os_sale=="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ชื่อผู้แทนขาย (Outside Sale) ]";
			}
			if (!inlist("c1,c2",$cus_cond_cust)) {
				if ($cusd_review1==$cusd_review2){
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;					
					$errortxt .= "กรุณาระบุ - [ ผู้พิจารณา 1 และ ผู้พิจารณา 2 (ผฝ.) ต้องไม่เป็นบุคคลเดียวกัน : ]";
				}
			}

	}
	// -- Add Data --- //
	if ($action == "cust_add") {
		if (!$errorflag) {
			$cus_whocanread = "ADMIN";
			if ($cus_create_by!="") {
				if(!inlist($cus_whocanread,$cus_create_by)) {
					if ($cus_whocanread != "") { $cus_whocanread = $cus_whocanread .","; }
					$cus_whocanread = $cus_whocanread . $cus_create_by;
				}
			}	
			$cus_step_code="0";
			$cus_id = getnewseq("cus_id","cus_app_mstr",$conn);
			$cus_app_nbr = getcusnewmnbr("NC-",$conn);
			$params = array($cus_app_nbr,	
								$curr_date,
								$cus_cond_cust,
								$cus_tg_cust,
								$cus_cust_type,
								$cus_cust_type_oth,
								$cus_code_mas,
								$cus_reg_nme,
								$cus_reg_addr,
								// $cus_district,
								// $cus_amphur,
								// $cus_prov,
								// $cus_zip,
								$cus_country,
								$cus_tel,
								$cus_fax,
								$cus_email,
								$cus_tax_id,
								$cus_branch,
								$cus_type_bus,
								$cus_term,
								$cus_create_by,
								$today,
								$TemImageRandom,
								$cus_effective_date,
								$cus_step_code,
								$cus_whocanread,
								$cus_create_by,
								$cus_id);	
			$sql_add = " INSERT INTO cus_app_mstr (" . 
				"cus_app_nbr, ". 
				"cus_date, ".
				"cus_cond_cust, ".
				"cus_tg_cust, ". 
				"cus_cust_type, ".
				"cus_cust_type_oth, ".
				"cus_code, ". 
				"cus_reg_nme, ". 
				"cus_reg_addr, ".
				// "cus_district, ".
				// "cus_amphur, ".
				// "cus_prov, ".
				// "cus_zip, ".
				"cus_country, ".
				"cus_tel, ".
				"cus_fax, ".
				"cus_email, ".
				"cus_tax_id, ".
				"cus_branch, ".
				"cus_type_bus, ".
				"cus_term, ".
				"cus_create_by, ". 
				"cus_create_date, ".
				"cus_tem_image, ".		
				"cus_effective_date, ".
				"cus_step_code, ".
				"cus_whocanread, ".
				"cus_curprocessor, ".
				"cus_id)" .		   
				" VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";			
				
				$result_add = sqlsrv_query($conn,$sql_add,$params);
				if ($result_add) {
					// -- cus_app_det -- //
					$params = array($cus_app_nbr,
										$cusd_sale_reason,
										$cusd_op_app,
										$cusd_review1,
										$cusd_review2,
										$cusd_review3,
										$cusd_review4,
										$cusd_approve_fin,
										$cusd_is_sale1, 
										$cusd_is_sale1_email, 
										$cusd_is_sale1_tel, 
										$cusd_is_sale2, 
										$cusd_is_sale2_email, 
										$cusd_is_sale2_tel, 
										$cusd_os_sale, 
										$cusd_os_sale_email, 
										$cusd_os_sale_tel, 
										$cusd_sale_manager, 
										$cusd_manger_email, 
										$cus_create_by,
										$today);	
		
					$sql_det = " INSERT INTO cus_app_det(" .
					"cusd_app_nbr, ".
					"cusd_sale_reason, ".
					"cusd_op_app, ". 
					"cusd_review1, ". 
					"cusd_review2, ". 
					"cusd_review3, ". 
					"cusd_review4, ". 
					"cusd_approve_fin, ". 
					"cusd_is_sale1, ".
					"cusd_is_sale1_email, ".
					"cusd_is_sale1_tel, ".
					"cusd_is_sale2, ".
					"cusd_is_sale2_email, ".
					"cusd_is_sale2_tel, ".
					"cusd_os_sale, ".
					"cusd_os_sale_email, ".
					"cusd_os_sale_tel, ".
					"cusd_sale_manager, ".
					"cusd_manger_email, ". 
					"cusd_create_by, ".
					"cusd_create_date)" .		   
					" VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";	
					$result_add_det = sqlsrv_query($conn,$sql_det,$params);
				}

				if ($result_add_det) {
					$apprv_person_array = array();	//เก็บรหัสพนักงานคนอนุมัติ
					$apprv_aplevel_array = array(); //เก็บประเภทคนอนุมัติ เช่น ผู้จัดการแผนก,ผู้จัดการส่วน
					$apprv_step_array = array(); //เก็บ step ในการ approve
					$apprv_nextstep_array = array(); //เก็บ next step ในการ approve
					$apprv_backstep_array = array(); //เก็บ back step ในการ แก้ไข
					$apprv_delstep_array = array(); //เก็บ del step ในการ ยกเลิก
					$apprv_type_code_array = array(); //เก็บ apprv_type_code ในการ approve
					if($sec_apprv !="") { array_push($apprv_person_array,$sec_apprv); array_push($apprv_aplevel_array,"SEC"); array_push($apprv_step_array,"0"); array_push($apprv_nextstep_array,"10"); array_push($apprv_backstep_array,"51"); array_push($apprv_delstep_array,"810"); array_push($apprv_type_code_array,"VR");}
					if($div_apprv !="") { array_push($apprv_person_array,$div_apprv); array_push($apprv_aplevel_array,"DEP"); array_push($apprv_step_array,"10"); array_push($apprv_nextstep_array,"62"); array_push($apprv_backstep_array,"52"); array_push($apprv_delstep_array,"820"); array_push($apprv_type_code_array,"VR");}
					if($cmo_apprv !="") { array_push($apprv_person_array,$cmo_apprv); array_push($apprv_aplevel_array,"CMO"); array_push($apprv_step_array,"62"); array_push($apprv_nextstep_array,"63"); array_push($apprv_backstep_array,"52"); array_push($apprv_delstep_array,"830"); array_push($apprv_type_code_array,"AP");}
					if($cfo_apprv !="") { array_push($apprv_person_array,$cfo_apprv); array_push($apprv_aplevel_array,"CFO"); array_push($apprv_step_array,"63"); array_push($apprv_nextstep_array,"64"); array_push($apprv_backstep_array,"52"); array_push($apprv_delstep_array,"840"); array_push($apprv_type_code_array,"AP");}
					if($md_apprv !="")  { array_push($apprv_person_array,$md_apprv);  array_push($apprv_aplevel_array,"MD"); array_push($apprv_step_array,"64"); array_push($apprv_nextstep_array,"60"); array_push($apprv_backstep_array,"52"); array_push($apprv_delstep_array,"850"); array_push($apprv_type_code_array,"AP");}
							
					$apprv_person_array_count = count($apprv_person_array);
					$apprv_person_array_display = array();
	
					if($apprv_person_array_count > 0) {
						for($i=0;$i<$apprv_person_array_count;$i++) {
							
							array_push($apprv_person_array_display,$apprv_fullname); 					
							$apprv_emp_id = $apprv_person_array[$i];
							if($apprv_emp_id == "none") {
								$apprv_status = "AP";
							} else {
								$apprv_status = "";
							}

							if($apprv_person_array_count==1){
								$apprv_step = "0";
								$apprv_nextstep = "60"; // กรณีผู้อนุมัติคนเดียว
								$apprv_backstep = "52";
								$apprv_delstep = "850";
							} else {
								$apprv_nextstep = $apprv_nextstep_array[$i];
								$apprv_backstep = $apprv_backstep_array[$i];
								$apprv_delstep = $apprv_delstep_array[$i];
								$apprv_step = $apprv_step_array[$i];
							}

							$apprv_user_id = findsqlval("emp_mstr","emp_user_id","emp_scg_emp_id",$apprv_emp_id,$conn);
							$apprv_fullname = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$apprv_emp_id,$conn);
							$apprv_position = findsqlval("emp_mstr","emp_th_pos_name","emp_scg_emp_id",$apprv_emp_id,$conn);
							$apprv_email= findsqlval("emp_mstr","emp_email_bus","emp_scg_emp_id",$apprv_emp_id,$conn);
							//$apprv_step = "40";
							$apprv_aplevel_code =  $apprv_aplevel_array[$i];
							$apprv_type_code =  $apprv_type_code_array[$i];
							$apprv_tobestep_cusstep_code = $apprv_step_array[$i]; //"40";
							$apprv_seq = $i;

							// ns15112023
							if($i == 2){
								if($sysc_cmo_act==1 ){
									$apprv_position = "รักษาการ ".$sysc_cmo_pos_name;
								} 
							}
							if($i == 3){
								if($sysc_cfo_act==1 ){
									$apprv_position = "รักษาการ ".$sysc_cfo_pos_name;
								} 
							}
							if($i == 4){
								if($sysc_md_act==1 ){
									$apprv_position = "รักษาการ ".$sysc_md_pos_name;
								} 
							}
							if($i < $apprv_person_array_count-1) {
								$apprv_lasted = "";
							}
							else {
								$apprv_lasted = "Y";
							}
							
							$apprv_id = getnewid("apprv_id", "apprv_person", $conn);
							$sql_apprv = "INSERT INTO apprv_person (
								apprv_id
								,apprv_cus_nbr
								,apprv_aplevel_code
								,apprv_type_code
								,apprv_seq
								,apprv_user_id
								,apprv_emp_id
								,apprv_name
								,apprv_position
								,apprv_email
								,apprv_step
								,apprv_status
								,apprv_tobestep_cusstep_code 
								,apprv_nextstep_cusstep_code 
								,apprv_backstep_cusstep_code
								,apprv_delstep_cusstep_code
								,apprv_lasted)".
								" VALUES (
								'$apprv_id'
								,'$cus_app_nbr'
								,'$apprv_aplevel_code'
								,'$apprv_type_code'
								,'$apprv_seq'
								,'$apprv_user_id'
								,'$apprv_emp_id'
								,'$apprv_fullname'
								,'$apprv_position'
								,'$apprv_email'
								,'$apprv_step'
								,'$apprv_status'
								,'$apprv_tobestep_cusstep_code'
								,'$apprv_nextstep'
								,'$apprv_backstep'
								,'$apprv_delstep'
								,'$apprv_lasted')";
							$result_apprv = sqlsrv_query($conn, $sql_apprv);
						}
					}

					$params = array($TemImageRandom);
					$sql_updateimg = "UPDATE images_mstr SET" .
					" image_app_nbr ='$cus_app_nbr'," .
					" image_check_status = '$check_status'," .
					" image_update_by = '$user_login'," .
					" image_update_date = '$today' " .
					" WHERE image_tem_nbr = ?";						
					$result_updateimg = sqlsrv_query($conn,$sql_updateimg, $params);
				}

				if ($result_apprv) {
					// Using LINE Notify to send messages to LINE
					/* $header = $info_cust;			
					$message = $header.
											"\n". "กลุ่มลูกค้า : " . $info_tg_cust .
											"\n". "ชื่อจดทะเบียน : " . $cus_reg_nme .
											"\n". "ที่อยู่จดทะเบียน : " . $cus_reg_addr .
											"\n". "เลขประจำตัวผู้เสียภาษี : " . $cus_tax_id .
											"\n\n". "เข้าสู่ระบบ : " . $app_url;
			
							if ( $cus_reg_nme <> "" ||  $cus_reg_addr <> "" ||  $cus_tax_id <> "") {
									sendlinemesg();
									header('Content-Type: text/html; charset=utf8');
									$res = notify_message($message);
							} else {
									echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน');</script>";
									header("location: index.php");
							}  */

					$r="1";
					$nb=encrypt($cus_app_nbr, $key);
					$errortxt="บันทึกข้อมูลเรียบร้อยแล้ว";
				}
				else 
				{
					$params_check_del = array($cus_app_nbr);
					$sql_del = "delete from cus_app_mstr WHERE cus_app_nbr = ?";
					$result_del = sqlsrv_query($conn,$sql_del,$params_check_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
					if ($result_del) {
						$params_del = array($cus_app_nbr);
						$sql_del = "delete from cus_app_det WHERE cusd_app_nbr = ?";
						$result = sqlsrv_query($conn,$sql_del,$params_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
						if ($result) {
							$params_del = array($cus_app_nbr);
							$sql_del = "delete from images_mstr WHERE image_app_nbr = ?";
							$result_img = sqlsrv_query($conn,$sql_del,$params_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
						}else {
							$r="0";
							$nb="";
							$errortxt="ไม่สามารถบันทึกข้อมูลได้";
						}
					}
					
					$r="0";
					$nb="";
					$errortxt="ไม่สามารถในการบันทึก";
				}
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	}

	// -- Check Data -- //
	if ($action == "link_cr") {

		$cus_app_nbr = mssql_escape($_POST['cus_app_nbr']);
		$params = array();
		$sql = "select count(*) as rowCounts from  cus_app_mstr where cus_app_nbr = ?";	
		$params = array($cus_app_nbr);
		$result = sqlsrv_query($conn,$sql,$params, array( "Scrollable" => 'keyset' ));
		$rowCounts = sqlsrv_num_rows($result);
	
		if ($result) {
			if($rowCounts >0)
			{
				$r="1";
				$errortxt="Link Success.";
				$nb=encrypt($cus_app_nbr, $key);
			}
			else
			{
				if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
					if (!matchToken($csrf_key,$user_login)) {
						echo "System detect CSRF attack!!";
						exit;
					}
				}
			}
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Link fail.";
		}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}

	// -- Delete Data --- //
	if ($action == "cus_del") {
		$cus_app_nbr = mssql_escape($_POST['cus_app_nbr']);
		$rowCounts = 0;	
		$params_check_del = array($cus_app_nbr);
			$sql_del = "delete from cus_app_mstr WHERE cus_app_nbr = ?";
			$result_del = sqlsrv_query($conn,$sql_del,$params_check_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if ($result_del) {

				$params_del = array($cus_app_nbr);
				$sql_del = "delete from cus_app_det WHERE cusd_app_nbr = ?";
				$result = sqlsrv_query($conn,$sql_del,$params_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if ($result) {
					$params_del = array($cus_app_nbr);
					$sql_del = "delete from images_mstr WHERE image_app_nbr = ?";
					$result_img = sqlsrv_query($conn,$sql_del,$params_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				}else {
					$r="0";
					$nb="";
					$errortxt="ไม่สามารถลบข้อมูลได้ !!!";
				}
				if ($result_img) {
					$r="1";
					$errortxt="ลบข้อมูลเรียบร้อยแล้ว";
					$nb=encrypt($cus_app_nbr, $key);
				}else {
					$r="0";
					$nb="";
					$errortxt="ไม่สามารถลบข้อมูลได้ !!!";
				}
			}
			else {
				$r="0";
				$nb="";
				$errortxt="ไม่สามารถลบข้อมูลฉบับนี้ได้ !!!";
			}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}

	// -- Update Data -- //
	if ($action == "cust_edit") {
		if (!$errorflag) {

			if ($user_login!="") {
				if(!inlist($cus_whocanread,$user_login)) {
					if ($cus_whocanread != "") { $cus_whocanread = $cus_whocanread.","; }
					$cus_whocanread = $cus_whocanread . $user_login;
				}
			}	

			$params=array($cus_app_nbr);
			$sql_edit = "UPDATE cus_app_mstr SET " .
			" cus_cond_cust = '$cus_cond_cust'," .
			" cus_tg_cust = '$cus_tg_cust'," .
			" cus_cust_type  = '$cus_cust_type',".		
			" cus_cust_type_oth  = '$cus_cust_type_oth',".	
			" cus_code  = '$cus_code_mas',".	
			" cus_reg_nme = '$cus_reg_nme',".
			" cus_reg_addr = '$cus_reg_addr',".
			// " cus_district = '$cus_district',".
			// " cus_amphur = '$cus_amphur',".
			// " cus_prov = '$cus_prov',".
			// " cus_zip = '$cus_zip',".
			" cus_country = '$cus_country',".
			" cus_tel = '$cus_tel',".
			" cus_fax = '$cus_fax',".
			" cus_email = '$cus_email',".
			" cus_tax_id = '$cus_tax_id',".
			" cus_branch = '$cus_branch',".
			" cus_type_bus = '$cus_type_bus',".
			" cus_term = '$cus_term',".
			" cus_contact1_nme = '$cus_contact1_nme',".
			" cus_contact1_pos = '$cus_contact1_pos',".
			" cus_contact2_nme = '$cus_contact2_nme',".
			" cus_contact2_pos = '$cus_contact2_pos',".
			" cus_bg1 = '$cus_bg1',".
			" cus_cr_limit1 = '$cus_cr_limit1',".
			" cus_bg2 = '$cus_bg2',".
			" cus_cr_limit2 = '$cus_cr_limit2',".

			" cus_cond_term = '$cus_cond_term ',".
			" cus_pay_addr = '$cus_pay_addr ',".
			" cus_contact_nme_pay = '$cus_contact_nme_pay ',".
			" cus_contact_tel = '$cus_contact_tel ',".
			" cus_contact_fax = '$cus_contact_fax ',".
			" cus_contact_email = '$cus_contact_email ',".
			" cus_tem_image = '$cus_tem_image ',".	
			" cus_effective_date = '$cus_effective_date ',".	 

			" cus_whocanread = '$cus_whocanread ',".
			" cus_step_code = '$cus_step_code'," .
			" cus_update_by = '$user_login'," .
			" cus_update_date = '$today'" .
			" WHERE cus_app_nbr = ?";
			$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			if ($result_edit) {
				$params=array($cus_app_nbr);
				$sql_det = "UPDATE cus_app_det SET " .

				"cusd_is_sale1 = '$cusd_is_sale1'," .
				"cusd_is_sale1_email = '$cusd_is_sale1_email'," .
				"cusd_is_sale1_tel = '$cusd_is_sale1_tel'," .
				"cusd_is_sale2 = '$cusd_is_sale2'," .
				"cusd_is_sale2_email = '$cusd_is_sale2_email'," .
				"cusd_is_sale2_tel = '$cusd_is_sale2_tel'," .

				"cusd_os_sale = '$cusd_os_sale'," .
				"cusd_os_sale_email = '$cusd_os_sale_email'," .
				"cusd_os_sale_tel  = '$cusd_os_sale_tel'," .
	
				"cusd_sale_manager  = '$cusd_sale_manager'," .
				"cusd_manger_email = '$cusd_manger_email'," .
				"cusd_sale_reason = '$cusd_sale_reason'," .
				"cusd_op_app = '$cusd_op_app'," .
				"cusd_review1 = '$cusd_review1'," .
				"cusd_review2 = '$cusd_review2'," .
				"cusd_review3 = '$cusd_review3'," .
				"cusd_review4 = '$cusd_review4'," .
				"cusd_approve_fin = '$cusd_approve_fin'," .
				"cusd_update_by = '$user_login'," .
				"cusd_update_date = '$today'" .
				" WHERE cusd_app_nbr = ?";
				$result_det = sqlsrv_query($conn, $sql_det, $params);
			}	
			// update apprv_person กรณีเอกสารอยู่สถานะ draft
			if($cus_step_code==0){
				if ($result_det) {
					$params=array($cus_app_nbr);
					$sql_flag = "UPDATE apprv_person SET apprv_flag='U' where apprv_cus_nbr=?"; // update  
					$result_flag = sqlsrv_query($conn, $sql_flag, $params);
	
					$apprv_person_array = array();	//เก็บรหัสพนักงานคนอนุมัติ
					$apprv_aplevel_array = array(); //เก็บประเภทคนอนุมัติ เช่น ผู้จัดการแผนก,ผู้จัดการส่วน
					$apprv_step_array = array(); //เก็บ step ในการ approve
					$apprv_nextstep_array = array(); //เก็บ next step ในการ approve
					$apprv_backstep_array = array(); //เก็บ back step ในการ แก้ไข
					$apprv_delstep_array = array(); //เก็บ del step ในการ ยกเลิก
					$apprv_type_code_array = array(); //เก็บ apprv_type_code ในการ approve
					if($sec_apprv !="") { array_push($apprv_person_array,$sec_apprv); array_push($apprv_aplevel_array,"SEC"); array_push($apprv_step_array,"0"); array_push($apprv_nextstep_array,"10"); array_push($apprv_backstep_array,"51"); array_push($apprv_delstep_array,"810"); array_push($apprv_type_code_array,"VR");}
					if($div_apprv !="") { array_push($apprv_person_array,$div_apprv); array_push($apprv_aplevel_array,"DEP"); array_push($apprv_step_array,"10"); array_push($apprv_nextstep_array,"62"); array_push($apprv_backstep_array,"52"); array_push($apprv_delstep_array,"820"); array_push($apprv_type_code_array,"VR");}
					if($cmo_apprv !="") { array_push($apprv_person_array,$cmo_apprv); array_push($apprv_aplevel_array,"CMO"); array_push($apprv_step_array,"62"); array_push($apprv_nextstep_array,"63"); array_push($apprv_backstep_array,"52"); array_push($apprv_delstep_array,"830"); array_push($apprv_type_code_array,"AP");}
					if($cfo_apprv !="") { array_push($apprv_person_array,$cfo_apprv); array_push($apprv_aplevel_array,"CFO"); array_push($apprv_step_array,"63"); array_push($apprv_nextstep_array,"64"); array_push($apprv_backstep_array,"52"); array_push($apprv_delstep_array,"840"); array_push($apprv_type_code_array,"AP");}
					if($md_apprv !="")  { array_push($apprv_person_array,$md_apprv);  array_push($apprv_aplevel_array,"MD"); array_push($apprv_step_array,"64"); array_push($apprv_nextstep_array,"60"); array_push($apprv_backstep_array,"52"); array_push($apprv_delstep_array,"850"); array_push($apprv_type_code_array,"AP");}
	
					$apprv_person_array_count = count($apprv_person_array);
					$apprv_person_array_display = array();
	
					if($apprv_person_array_count > 0) {
						for($i=0;$i<$apprv_person_array_count;$i++) {
							
							array_push($apprv_person_array_display,$apprv_fullname); 					
							$apprv_emp_id = $apprv_person_array[$i];
	
							if($apprv_person_array_count==1){
								$apprv_step = "0";
								$apprv_nextstep = "60"; // กรณีผู้อนุมัติคนเดียว
								$apprv_backstep = "52";
								$apprv_delstep = "850";
							} else {
								$apprv_nextstep = $apprv_nextstep_array[$i];
								$apprv_backstep = $apprv_backstep_array[$i];
								$apprv_delstep = $apprv_delstep_array[$i];
								$apprv_step = $apprv_step_array[$i];
							}
	
							$apprv_user_id = findsqlval("emp_mstr","emp_user_id","emp_scg_emp_id",$apprv_emp_id,$conn);
							$apprv_fullname = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$apprv_emp_id,$conn);
							$apprv_position = findsqlval("emp_mstr","emp_th_pos_name","emp_scg_emp_id",$apprv_emp_id,$conn);
							$apprv_email= findsqlval("emp_mstr","emp_email_bus","emp_scg_emp_id",$apprv_emp_id,$conn);
	
							//$apprv_step = "40";
							$apprv_aplevel_code =  $apprv_aplevel_array[$i];
							$apprv_type_code =  $apprv_type_code_array[$i];
							$apprv_tobestep_cusstep_code = $apprv_step_array[$i]; //"40";
							$apprv_seq = $i;
							
							// ns15112023
							if($i == 2){
								if($sysc_cmo_act==1 ){
									$apprv_position = "รักษาการ ".$sysc_cmo_pos_name;
								} 
							}
							if($i == 3){
								if($sysc_cfo_act==1 ){
									$apprv_position = "รักษาการ ".$sysc_cfo_pos_name;
								} 
							}
							if($i == 4){
								if($sysc_md_act==1 ){
									$apprv_position = "รักษาการ ".$sysc_md_pos_name;
								} 
							}
							if($i < $apprv_person_array_count-1) {
								$apprv_lasted = "";
							}
							else {
								$apprv_lasted = "Y";
							}
							
							$apprv_id = getnewid("apprv_id", "apprv_person", $conn);
								$sql_apprv = "INSERT INTO apprv_person (
									apprv_id
									,apprv_cus_nbr
									,apprv_aplevel_code
									,apprv_type_code
									,apprv_seq
									,apprv_user_id
									,apprv_emp_id
									,apprv_name
									,apprv_position
									,apprv_email
									,apprv_step
									,apprv_status
									,apprv_by
									,apprv_tobestep_cusstep_code 
									,apprv_nextstep_cusstep_code 
									,apprv_backstep_cusstep_code
									,apprv_delstep_cusstep_code
									,apprv_lasted)".
									" VALUES (
									'$apprv_id'
									,'$cus_app_nbr'
									,'$apprv_aplevel_code'
									,'$apprv_type_code'
									,'$apprv_seq'
									,'$apprv_user_id'
									,'$apprv_emp_id'
									,'$apprv_fullname'
									,'$apprv_position'
									,'$apprv_email'
									,'$apprv_step'
									,''
									,''
									,'$apprv_tobestep_cusstep_code'
									,'$apprv_nextstep'
									,'$apprv_backstep'
									,'$apprv_delstep'
									,'$apprv_lasted')";
								$result_apprv = sqlsrv_query($conn, $sql_apprv);
							if($result_apprv){
								$params_check_del = array($cus_app_nbr);
								$sql_del = "delete from apprv_person WHERE apprv_cus_nbr = ? and apprv_flag='U'";
								$result_del = sqlsrv_query($conn,$sql_del,$params_check_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
							}
						}
					}
				}	
			} else {
				$result_apprv = $result_det;
			}
				//////
				if($result_apprv){
					$r="1";
					$nb=encrypt($cus_app_nbr, $key);
					$errortxt="บันทึกข้อมูลแก้ไขเรียบร้อยแล้ว";
				}
				else {
					$r="0";
					$nb="";
					$errortxt="ไม่สามารถบันทึกข้อมูลแก้ไข !!!";
				}
					echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		 }
	}

	function sendlinemesg() {
		// LINE LINE_API https://notify-api.line.me/api/notify
		// LINE TOKEN mhIYaeEr9u3YUfSH1u7h9a9GlIx3Ry6TlHtfVxn1bEu แนะนำให้ใช้ของตัวเองนะครับเพราะของผมยกเลิกแล้วไม่สามารถใช้ได้
				define('LINE_API',"https://notify-api.line.me/api/notify");
		//define('LINE_TOKEN',"xBN44t1iaAgdqNencRzKqgOLJGkWXoGpmYhYwbKcq3N"); // cr-line-notify
		define('LINE_TOKEN',"TLTekO5Ocb3pLLWoQCE50QKXXuqEjMXBOV7IPA2WIGr"); // ส่วนตัว

				function notify_message($message) {
						$queryData = array('message' => $message);
						$queryData = http_build_query($queryData,'','&');
						$headerOptions = array(
								'http' => array(
										'method' => 'POST',
										'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
																."Authorization: Bearer ".LINE_TOKEN."\r\n"
																."Content-Length: ".strlen($queryData)."\r\n",
										'content' => $queryData
								)
						);
						$context = stream_context_create($headerOptions);
						$result = file_get_contents(LINE_API, FALSE, $context);
						$res = json_decode($result);
						return $res;
				}
	}
?>