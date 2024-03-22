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
	//$cus_step_code="0";

	// -- cr_newcusmnt.php -- //
	$action = mssql_escape($_POST['action']);
	$cr_app_nbr  = mssql_escape(decrypt($_POST['cus_app_nbr'], $key));
	$cr_sap_code = mssql_escape($_POST['cr_sap_code']);
	$cr_sap_code_date = mssql_escape(ymd($_POST['cr_sap_code_date']));
	$cr_cus_chk_date = mssql_escape(ymd($_POST['cr_cus_chk_date']));
	$cr_date_of_reg = mssql_escape(ymd($_POST['cr_date_of_reg']));
	$cr_reg_capital = mssql_escape(str_replace(",","",$_POST['cr_reg_capital']));
	$cr_bankrupt = mssql_escape($_POST['cr_bankrupt']);
	$cr_md_bankrupt = mssql_escape($_POST['cr_md_bankrupt']);
	$cr_remark = mssql_escape($_POST['cr_remark']);
	$cr_mgr_remark = mssql_escape($_POST['cr_mgr_remark']);
	$cr_rem_revise = mssql_escape($_POST['cr_rem_revise']);
	$cr_whocanread = mssql_escape($_POST['cr_whocanread']);
	$cr_step_code  = mssql_escape(decrypt($_POST['cr_step_code'], $key));
	$cr_sta_complete = mssql_escape($_POST['cr_sta_complete']);
	$cr_sta_rem = mssql_escape($_POST['cr_sta_rem']);
	$cus_step_code  = mssql_escape(decrypt($_POST['cus_step_code'], $key));

	// -- cr_chgcusmnt.php -- //
	$cr_debt = mssql_escape(str_replace(",","",$_POST['cr_debt']));
	$cr_due_date = mssql_escape(ymd($_POST['cr_due_date']));
	$cr_so_amt = mssql_escape(str_replace(",","",$_POST['cr_so_amt']));
	$cr_odue_amt = mssql_escape(str_replace(",","",$_POST['cr_odue_amt']));
	$cr_rem_guarantee = mssql_escape($_POST['cr_rem_guarantee']);
	$cr_rem_other = mssql_escape($_POST['cr_rem_other']);
	$cr_status = mssql_escape($_POST['cr_status']);
	$cr_mgr_status = mssql_escape($_POST['cr_mgr_status']);
	
	$numbers_arr = $_POST['apcheck'];
	if(isset($numbers_arr)){
	 	$numbers_arr = array_unique($numbers_arr); // ตัด array ที่ซ้ำออกไป
	}
	if($cr_status=="A"){
		$via_cr = "เห็นควรอนุมัติ";
	} else {
		$via_cr = "แก้ไข";
	}
	
	$cr_create_by=$user_login;
	$errorflag = false;
	$errortxt = "";
	if (inlist("cr_add",$action)) {	
		if($cr_cus_chk_date == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ข้อมูล ณ วันที่ ]";
		}

		/* if($cr_date_of_reg == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วันที่จดทะเบียน (Date Of Registration) ]";
		}

		if($cr_reg_capital == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ทุนจดทะเบียน (Registered Capital) ]";
		} 

		if($cr_bankrupt == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ผลการตรวจสอบการเป็นบุคคลล้มละลาย ]";
		}

		if($cr_md_bankrupt == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ กรรมการที่ถูกฟ้องล้มละลาย/ถูกศาลพิทักษ์ทรัพย์ ]";
		}*/

		if($cr_remark == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ความเห็นสินเชื่อ ]";
		}

		if(!isset($numbers_arr)){
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ เอกสารประกอบที่ต้องมี ]";
		}
	}
	if (inlist("cr_add_chg",$action)) {	
		if($cr_cus_chk_date == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ข้อมูล ณ วันที่ ]";
		}

		if($cr_debt == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ หนี้สินค่าสินค้า : ]";
		}

		if($cr_due_date == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ วันที่ครบกำหนดชำระเงินล่าสุด ]";
		}

		if($cr_so_amt == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ S/O คงเหลือ ]";
		}

		/* if($cr_odue_amt == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ค่าชำระเงินล่าช้า ]";
		} */

		if($cr_remark == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ความเห็นสินเชื่อ ]";
		}

		if(!isset($numbers_arr)){
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ เอกสารประกอบที่ต้องมี ]";
		}

		if($cr_status == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ เห็นควรอนุมัติ หรือ แก้ไข้ ]";
		}
	}
	if (inlist("cr_edit",$action)) {	
		if($cr_remark == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ความเห็นสินเชื่อ ]";
		}
		if(inlist($user_role,'FinCR Mgr')){
			if($cr_mgr_remark == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ความเห็น Finance & Credit Manager ]";
			}
		}
	}
	if (inlist("cr_edit_chg",$action)) {	
		if($cr_remark == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ความเห็นสินเชื่อ ]";
		}
		if($cr_status=="R"){
			if($cr_rem_revise==""){
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ หมายเหตุแก้ไข ]";			}
		}
		if(inlist($user_role,'FinCR Mgr')){
			if($cr_mgr_remark == "") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errorflag = true;					
				$errortxt .= "กรุณาระบุ - [ ความเห็น Finance & Credit Manager ]";
			}
		}
	}
	// -- Add Data --- //
	if ($action == "cr_add") {
		if (!$errorflag) {
			$cus_step_code="20";
			$cr_whocanread = "ADMIN";
			if ($user_login!="") {
				if(!inlist($cr_whocanread,$user_login)) {
					if ($cr_whocanread != "") { $cr_whocanread = $cr_whocanread .","; }
					$cr_whocanread = $cr_whocanread . $user_login;
				}
			}
			$params = array($cr_app_nbr,	
								$curr_date,
								$cr_sap_code,
								$cr_sap_code_date,
								$cr_cus_chk_date,
								$cr_date_of_reg,
								$cr_reg_capital,
								$cr_bankrupt,
								$cr_md_bankrupt,
								$cr_remark,
								$cr_mgr_remark,
								$cr_mgr_status,
								$cr_sta_complete,
								$cr_sta_rem,
								$cr_create_by,
								$cr_whocanread,
								$today );	
			$sql_add = " INSERT INTO cr_app_mstr (" . 
				"cr_app_nbr, ". 
				"cr_doc_date, ".
				"cr_sap_code, ". 
				"cr_sap_code_date, ". 
				"cr_cus_chk_date, ". 
				"cr_date_of_reg, ". 
				"cr_reg_capital, ". 
				"cr_bankrupt, ". 
				"cr_md_bankrupt, ". 
				"cr_remark, ". 
				"cr_mgr_remark, ".
				"cr_mgr_status, ". 
				"cr_sta_complete, ". 
				"cr_sta_rem, ". 
				"cr_create_by, ".
				"cr_whocanread, ".
				"cr_create_date)" .		   
				" VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";			
				
				$result_add = sqlsrv_query($conn,$sql_add,$params);
				if ($result_add) {
					for($i=1;$i<=10;$i++) { 
						$params = array($i);
						$query_det = "SELECT * FROM book_mstr where book_no = ?";
							$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
							$rowCounts = sqlsrv_num_rows($result);
							if($rowCounts > 0){
								while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
								{
									$book_no = mssql_escape($row['book_no']);
									$book_dom = mssql_escape($row['book_dom']);
									$book_exp = mssql_escape($row['book_exp']);
									$book_status = 0;
									$book_agent = mssql_escape($row['book_agent']);
									$book_aff = mssql_escape($row['book_aff']);
									$book_case = mssql_escape($row['book_case']);
								}
							}	
							$params = array($book_no,$book_status,$cr_app_nbr,$book_agent,$book_aff,$book_dom,$book_case,$book_exp);
							$sql = "INSERT INTO cr_book_mstr (".
									"book_no, ".
									"book_status, ".
									"book_app_nbr, ".
									"book_agent, ".
									"book_aff, ".
									"book_dom, ".
									"book_case, ".
									"book_exp)".
									"VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
							$result = sqlsrv_query($conn, $sql,$params);
					}
					if(isset($numbers_arr)){
						foreach($numbers_arr as $number){
							$params=array($cr_app_nbr, $number);
							$sql_edit = "UPDATE cr_book_mstr SET " .
							" book_status = '1'" .
							" WHERE book_app_nbr = ? and book_no = ?";
							$result_edit = sqlsrv_query($conn, $sql_edit, $params);
						} 
					}
					if($result_edit){
						$params=array($cr_app_nbr);
						$sql_edit = "UPDATE  cus_app_mstr SET " .
						" cus_code = '$cr_sap_code'," .
						" cus_step_code = '$cus_step_code'" .
						" WHERE cus_app_nbr = ?";
						$result_edit = sqlsrv_query($conn, $sql_edit, $params);
					}
					$r="1";
					$nb=encrypt($cr_app_nbr, $key);
					$errortxt="บันทึกข้อมูลเรียบร้อยแล้ว";
					$pg=encrypt("20", $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="ไม่สามารถบันทึกข้อมูลได้";
				}
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	} 

	/* if ($action == "cr_add") {
		if (!$errorflag) {
			foreach($numbers_arr as $number){
				$params = array($number);
				$query_det = "SELECT * FROM book_mstr WHERE book_no = ?";
				$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
				$rowCounts = sqlsrv_num_rows($result);
				if($rowCounts > 0){
					while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
					{
						$book_dom = mssql_escape($row['book_dom']);
						$book_exp = mssql_escape($row['book_exp']);
						$book_agent = mssql_escape($row['book_agent']);
						$book_aff = mssql_escape($row['book_aff']);
					}
				}	
				$params = array($number,$book_agent,$cr_app_nbr,$book_dom,$book_exp);
							$sql = "INSERT INTO cr_book_mstr (".
									"book_no, ".
									"book_status, ".
									"book_app_nbr, ".
									"book_dom, ".
									"book_exp)".
									"VALUES(?, ?, ?, ?, ?)";
							$result = sqlsrv_query($conn, $sql,$params);
			} 
			$r="1";
			$nb=encrypt($cr_app_nbr, $key);
			$errortxt="Insert success.";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else 
		{
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		
	}  */

	// -- Update Data ch_newcusmnt.php -- // 
	if (inlist("cr_edit,cr_edit_cr1",$action)) {		
		if (!$errorflag) {
			if ($user_login!="") {
				if(!inlist($cr_whocanread,$user_login)) {
					if ($cr_whocanread != "") { $cr_whocanread = $cr_whocanread .","; }
					$cr_whocanread = $cr_whocanread . $user_login;
				}
			}
			$params=array($cr_app_nbr);
			$sql_edit = "UPDATE cr_app_mstr SET " .
				"cr_sap_code = '$cr_sap_code', ".
				"cr_sap_code_date = '$cr_sap_code_date', ".
				"cr_cus_chk_date = '$cr_cus_chk_date', ".
				"cr_date_of_reg = '$cr_date_of_reg', ".
				"cr_reg_capital = '$cr_reg_capital', ".
				"cr_bankrupt = '$cr_bankrupt', ".
				"cr_md_bankrupt = '$cr_md_bankrupt', ".
				"cr_remark = '$cr_remark', ".
				"cr_status = '$cr_status', ".
				"cr_mgr_remark = '$cr_mgr_remark', ".
				"cr_mgr_status = '$cr_mgr_status', ".
				"cr_sta_complete ='$cr_sta_complete', ".
				"cr_sta_rem ='$cr_sta_rem', ".
				"cr_whocanread = '$cr_whocanread', ".
				"cr_update_by = '$user_login'," .
				"cr_update_date = '$today'" .
				" WHERE  cr_app_nbr = ?";
				$result_edit = sqlsrv_query($conn, $sql_edit, $params);
				if($result_edit){
					// ลบข้อมูลเก่าออกก่อน
					$params_del = array($cr_app_nbr);
					$sql_apprv = "delete from cr_book_mstr WHERE book_app_nbr = ?";
					$result_apprv = sqlsrv_query($conn,$sql_apprv,$params_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				}	
				if($result_apprv){
					if (inlist("cr_edit",$action)) {		
						$params=array($cr_app_nbr);
						$sql_edit = "UPDATE  cus_app_mstr SET " .
						" cus_code = '$cr_sap_code'," .
						" cus_step_code = '$cus_step_code'" .
						" WHERE cus_app_nbr = ?";
						$result_edit1 = sqlsrv_query($conn, $sql_edit, $params);
					}
				}

				if($result_edit){
					// เพิ่มเอกสารประกอบการพิจารณาใหม่
					for($i=1;$i<=10;$i++) { 
						$params = array($i);
						$query_det = "SELECT * FROM book_mstr where book_no = ?";
							$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
							$rowCounts = sqlsrv_num_rows($result);
							if($rowCounts > 0){
								while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
								{
									$book_no = mssql_escape($row['book_no']);
									$book_dom = mssql_escape($row['book_dom']);
									$book_exp = mssql_escape($row['book_exp']);
									$book_status = 0;
									$book_agent = mssql_escape($row['book_agent']);
									$book_aff = mssql_escape($row['book_aff']);
									$book_case = mssql_escape($row['book_case']);
								}
							}	
							$params = array($book_no,$book_status,$cr_app_nbr,$book_agent,$book_aff,$book_dom,$book_exp,$book_case);
							$sql = "INSERT INTO cr_book_mstr (".
									"book_no, ".
									"book_status, ".
									"book_app_nbr, ".
									"book_agent, ".
									"book_aff, ".
									"book_dom, ".
									"book_exp, ".
									"book_case)".
									"VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
							$result = sqlsrv_query($conn, $sql,$params);
					}
					if(isset($numbers_arr)){
						foreach($numbers_arr as $number){
							$params=array($cr_app_nbr, $number);
							$sql_edit = "UPDATE cr_book_mstr SET " .
							" book_status = '1'" .
							" WHERE book_app_nbr = ? and book_no = ?";
							$result_edit = sqlsrv_query($conn, $sql_edit, $params);
						} 
					}
				}	
				if($result_edit){
					$r="1";
					$nb=encrypt($cr_app_nbr, $key);
					$errortxt="บันทึกข้อมูลแก้ไขเรียบร้อยแล้ว";
					$pg=encrypt("20", $key);
				}
				else {
					$r="0";
					$nb="";
					$errortxt="ไม่สามารถบันทึกข้อมูลแก้ไขได้";
				}
					echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		 }
	}

	// -- Add data cr_newcusmnt.php --//
	if ($action == "cr_add_chg") {
		if (!$errorflag) {
			$cus_step_code="20";
			$cr_whocanread = "ADMIN";
			if ($user_login!="") {
				if(!inlist($cr_whocanread,$user_login)) {
					if ($cr_whocanread != "") { $cr_whocanread = $cr_whocanread .","; }
					$cr_whocanread = $cr_whocanread . $user_login;
				}
			}
			$params = array($cr_app_nbr,	
								$curr_date,
								$cr_cus_chk_date,
								$cr_debt,
								$cr_due_date,
								$cr_so_amt,
								$cr_odue_amt,
								$cr_status,
								$cr_remark,
								$cr_rem_guarantee,
								$cr_rem_other,
								$cr_sta_complete,
								$cr_sta_rem,
								$cr_create_by,
								$cr_whocanread,
								$today );	
			$sql_add = " INSERT INTO cr_app_mstr (" . 
				"cr_app_nbr, ". 
				"cr_doc_date, ".
				"cr_cus_chk_date, ". 
				"cr_debt, ". 
				"cr_due_date, ". 
				"cr_so_amt, ". 
				"cr_odue_amt, ". 
				"cr_status, ".
				"cr_remark, ". 
				"cr_rem_guarantee, ".
				"cr_rem_other, ". 
				"cr_sta_complete, ". 
				"cr_sta_rem, ". 
				"cr_create_by, ".
				"cr_whocanread, ".
				"cr_create_date)" .		   
				" VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";			
				
				$result_add = sqlsrv_query($conn,$sql_add,$params);
				if ($result_add) {
					for($i=11;$i<=13;$i++) { 
						$params = array($i);
						$query_det = "SELECT * FROM book_mstr where book_case=2 and book_no = ?";
							$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
							$rowCounts = sqlsrv_num_rows($result);
							if($rowCounts > 0){
								while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
								{
									$book_no = mssql_escape($row['book_no']);
									$book_dom = mssql_escape($row['book_dom']);
									$book_exp = mssql_escape($row['book_exp']);
									$book_status = 0;
									$book_agent = mssql_escape($row['book_agent']);
									$book_aff = mssql_escape($row['book_aff']);
									$book_case = mssql_escape($row['book_case']);
								}
							}	
							$params = array($book_no,$book_status,$cr_app_nbr,$book_agent,$book_aff,$book_dom,$book_case,$book_exp);
							$sql = "INSERT INTO cr_book_mstr (".
									"book_no, ".
									"book_status, ".
									"book_app_nbr, ".
									"book_agent, ".
									"book_aff, ".
									"book_dom, ".
									"book_case, ".
									"book_exp)".
									"VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
							$result = sqlsrv_query($conn, $sql,$params);
					}
					if(isset($numbers_arr)){
						foreach($numbers_arr as $number){
							$params=array($cr_app_nbr, $number);
							$sql_edit = "UPDATE cr_book_mstr SET " .
							" book_status = '1'" .
							" WHERE book_app_nbr = ? and book_no = ?";
							$result_edit = sqlsrv_query($conn, $sql_edit, $params);
						} 
					}
					if($result_add){
						$params=array($cr_app_nbr);
						$sql_edit = "UPDATE  cus_app_mstr SET " .
						" cus_step_code = '$cus_step_code'" .
						" WHERE cus_app_nbr = ?";
						$result_edit = sqlsrv_query($conn, $sql_edit, $params);
					}
					$r="1";
					$nb=encrypt($cr_app_nbr, $key);
					$errortxt="บันทึกข้อมูลเรียบร้อยแล้ว";
					
				}

				else {
					$r="0";
					$nb="";
					$errortxt="ไม่สามารถบันทึกข้อมูลได้";
				}
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
	} 
	// -- Update Data cr_chgcusmnt.php -- //
	if ($action == "cr_edit_chg") {
		if (!$errorflag) {
			if ($user_login!="") {
				if(!inlist($cr_whocanread,$user_login)) {
					if ($cr_whocanread != "") { $cr_whocanread = $cr_whocanread .","; }
					$cr_whocanread = $cr_whocanread . $user_login;
				}
			}
			$params=array($cr_app_nbr);
			$sql_edit = "UPDATE cr_app_mstr SET " .
				"cr_cus_chk_date = '$cr_cus_chk_date', ".
				"cr_debt = '$cr_debt', ".
				"cr_due_date = '$cr_due_date', ".
				"cr_so_amt = '$cr_so_amt', ".
				"cr_odue_amt = '$cr_odue_amt', ".
				"cr_rem_guarantee = '$cr_rem_guarantee', ".
				"cr_rem_other = '$cr_rem_other', ".
				"cr_status = '$cr_status', ".
				"cr_remark = '$cr_remark', ".
				"cr_mgr_remark = '$cr_mgr_remark', ".
				"cr_mgr_status = '$cr_mgr_status', ".
				"cr_sta_complete = '$cr_sta_complete', ".
				"cr_sta_rem = '$cr_sta_rem', ".
				"cr_rem_revise = '$cr_rem_revise', ".
				"cr_whocanread = '$cr_whocanread', ".
				"cr_update_by = '$user_login'," .
				"cr_update_date = '$today'" .
				" WHERE cr_app_nbr = ?";
				$result_edit = sqlsrv_query($conn, $sql_edit, $params);
				if($result_edit){
					// ลบข้อมูลเก่าออกก่อน
					$params_del = array($cr_app_nbr);
					$sql_apprv = "delete from cr_book_mstr WHERE book_app_nbr = ?";
					$result_apprv = sqlsrv_query($conn,$sql_apprv,$params_del, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				}	
				//if($result_apprv){
					// เพิ่มเอกสารประกอบการพิจารณาใหม่
					for($i=11;$i<=13;$i++) { 
						$params = array($i);
						$query_det = "SELECT * FROM book_mstr where book_case=2 and book_no = ?";
							$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
							$rowCounts = sqlsrv_num_rows($result);
							if($rowCounts > 0){
								while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
								{
									$book_no = mssql_escape($row['book_no']);
									$book_dom = mssql_escape($row['book_dom']);
									$book_exp = mssql_escape($row['book_exp']);
									$book_status = 0;
									$book_agent = mssql_escape($row['book_agent']);
									$book_aff = mssql_escape($row['book_aff']);
									$book_case = mssql_escape($row['book_case']);
								}
							}	
							$params = array($book_no,$book_status,$cr_app_nbr,$book_agent,$book_aff,$book_dom,$book_case,$book_exp);
							$sql = "INSERT INTO cr_book_mstr (".
									"book_no, ".
									"book_status, ".
									"book_app_nbr, ".
									"book_agent, ".
									"book_aff, ".
									"book_dom, ".
									"book_case, ".
									"book_exp)".
									"VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
							$result = sqlsrv_query($conn, $sql,$params);
					}
					if(isset($numbers_arr)){
						foreach($numbers_arr as $number){
							$params=array($cr_app_nbr, $number);
							$sql_edit = "UPDATE cr_book_mstr SET " .
							" book_status = '1'" .
							" WHERE book_app_nbr = ? and book_no = ?";
							$result_edit = sqlsrv_query($conn, $sql_edit, $params);
						} 
					}
				//}	
				if($result_edit){
					$r="1";
					$nb=encrypt($cr_app_nbr, $key);
					$errortxt="บันทึกข้อมูลแก้ไขเรียบร้อยแล้ว";
				}
				else {
					$r="0";
					$nb="";
					$errortxt="ไม่สามารถบันทึกข้อมูลแก้ไขได้";
				}
					echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		}
		else {
			$r="0";
			$nb="";
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
		 }
	}
	
	// -- Cr Revise -- //
	if ($action == "cr_revise") {
		if ($cr_rem_revise == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ หมายเหตุ : !!!! ]";
		}
		if (!$errorflag) {
			if ($user_login!="") {
				if(!inlist($cr_whocanread,$user_login)) {
					if ($cr_whocanread != "") { $cr_whocanread = $cr_whocanread .","; }
					$cr_whocanread = $cr_whocanread . $user_login;
				}
			}
			/////
			if (isservonline($smtp)) { $can_sendmail=true;}
			else {
				$can_sendmail=false;
				$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
			}
			if($can_sendmail) {
				$doc_url = "<a href='".$app_url."/index.php' target='_blank'>คลิ๊กเพื่อเข้าสู่ระบบ</a>";

				$cus_create_by = findsqlval("cus_app_mstr","cus_create_by","cus_app_nbr",$cr_app_nbr,$conn);
				$owner_email = findsqlval("emp_mstr","emp_email_bus","emp_user_id",$cus_create_by,$conn);
				$owner_name = findsqlval("emp_mstr","emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$cus_create_by,$conn);

				$mail_from = $mail_from_text;
				$mail_from_email = $mail_credit_email ;
				$mail_to = $owner_email;
				$mail_subject = $mail_subject_text."- เอกสารเลขที่ $cr_app_nbr ส่งกลับมาแก้ไข ";
				$mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน คุณ$owner_name <br><br>
				เอกสารเลขที่ $cr_app_nbr ได้ส่งเอกสารฉบับนี้กลับมาแก้ไข<br>
				$doc_url <br><br>
				<span style='color:green'><strong>*** กรุณาตรวจสอบเอกสารใหม่อีกครั้ง ***</strong> </span><br><br>
				ขอบคุณค่ะ</font>";
				$mail_message .= "<br>" .$mail_no_reply;
				if($mail_to!="") {
					$sendstatus = mail_normal($mail_from,$mail_from_email,$mail_to,$mail_subject,$mail_message);
					if (!$sendstatus) {
						$errortxt .= "ไม่สามารถส่ง Email ได้<br>";
					}
				} else {$errortxt .= "ไม่สามารถส่ง Email ได้<br>";}
			}	
			/////

			$params=array($cr_app_nbr);
			$sql_edit = "UPDATE cr_app_mstr SET " .
				"cr_rem_revise = '$cr_rem_revise', ".
				" cr_update_by = '$user_login'," .
				" cr_update_date = '$today'" .
				" WHERE  cr_app_nbr = ?";
			$result_cr = sqlsrv_query($conn, $sql_edit, $params);
			if($result_cr){
				$params=array($cr_app_nbr);
				$sql_edit = "UPDATE cus_app_mstr SET " .
					" cus_step_code = '$cr_step_code', ".
					" cus_update_by = '$user_login'," .
					" cus_update_date = '$today'" .
					" WHERE  cus_app_nbr = ?";
				$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			}
			if($result_edit){
				// เช็คคนอนุมัติก่อนหน้านี้
				$params = array($cr_app_nbr);
				$query_apprv = "SELECT top 1 * FROM cus_approval where cus_ap_nbr = ?  and cus_ap_active = '1' order by cus_ap_id desc";

				$result_apprv_detail = sqlsrv_query($conn, $query_apprv,$params);
				$row_app = sqlsrv_fetch_array($result_apprv_detail, SQLSRV_FETCH_ASSOC);
				if ($row_app) {
					$prev_step = $row_app['cus_ap_t_step_code'];
				} 
				$cr_ap_f_step = $prev_step;  
				$cr_ap_t_step = $cr_step_code; 
				if($cr_step_code=="21"){
					$cr_ap_text = "เจ้าหน้าที่สินเชื่อ "."Revise ";
					$cr_ap_color = "text-warning";
					$cr_ap_remark = $cr_rem_revise;
				} 
				//เก็บประวัติการดำเนินการ
					
				$cr_ap_id = getcusnewapp($cr_app_nbr,$conn);
					
				$sql = "INSERT INTO cus_approval(" . 
				" cus_ap_id,cus_ap_nbr,cus_ap_f_step_code,cus_ap_t_step_code,cus_ap_text,cus_ap_remark,cus_ap_color,cus_ap_active,cus_ap_create_by,cus_ap_create_date)" .		
				" VALUES('$cr_ap_id','$cr_app_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_ap_remark','$cr_ap_color','1','$user_login','$today')";				
				$result = sqlsrv_query($conn, $sql);			
			}
			if($result){
				$r="1";
				$nb=encrypt($cr_app_nbr, $key);
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

	// -- Cr submit to mgr -- //
	if ($action == "cr_submit_mgr") {
		if ($cr_remark == "") {
			if ($errortxt!="") {$errortxt .= "<br>";}
			$errorflag = true;					
			$errortxt .= "กรุณาระบุ - [ ความเห็นสินเชื่อ ]";
		}

		$params = array($cr_app_nbr);
		$sql = "SELECT * from cus_app_mstr WHERE cus_app_nbr = ? ";
		$result = sqlsrv_query($conn, $sql, $params, array("Scrollable" => 'keyset' ));
		$rowCounts = sqlsrv_num_rows($result);
		if($rowCounts > 0){
			while($r_row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			{
				$cus_curprocessor_check = mssql_escape($r_row['cus_curprocessor']);
				$cus_cond_cust = mssql_escape($r_row['cus_cond_cust']);
				$cus_reg_nme = mssql_escape($r_row['cus_reg_nme']);
				switch($cus_cond_cust){
					case "c1" :
					$cardtxt = "แต่งตั้งลูกค้าใหม่";
					break;
					case "c2" :
					$cardtxt = "แต่งตั้งร้านสาขา";
					break;  
					case "c3" :
					$cardtxt = "เปลี่ยนแปลงชื่อ";
					break;   
					case "c4" :
					$cardtxt = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
					break;  
					case "c5" :
					$cardtxt = "เปลี่ยนแปลงชื่อและที่อยู่";
					break;           
					default :
					$cardtxt = "ยกเลิกลูกค้า";
				}
			}
		}
		if (!$errorflag) {
			if ($user_login!="") {
				if(!inlist($cr_whocanread,$user_login)) {
					if ($cr_whocanread != "") { $cr_whocanread = $cr_whocanread .","; }
					$cr_whocanread = $cr_whocanread . $user_login;
				}
			}

			///// Send email Cr2 ---> Manager
			if ($cr_step_code=="30") {
				$doc_url = "<a href='".$app_url."/index.php' target='_blank'>คลิ๊กเพื่อเข้าสู่ระบบ</a>";

				if (isservonline($smtp)) { $can_sendmail=true;}
				else {
						$can_sendmail=false;
						$errortxt .= "<span style='color:red'>** พบปัญหาการส่ง Email ดังนั้นระบบจึงไม่สามารถส่ง Email แจ้งผู้ที่เกี่ยวข้องได้!!**</span><br>";
				}
				
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
							$user_fullname = 'คุณ'.html_clear(trim($r_emp["emp_th_firstname"])) . " " . html_clear(trim($r_emp["emp_th_lastname"]));
							$user_email = html_clear(strtolower($r_emp['emp_email_bus']));
							}
				
				//if ($action =="send_mail") {
					$mail_from = $user_fullname;
					$mail_from_email = $user_email;
					$mail_to = $cr_next_curprocessor_email;
					$mail_subject = "FinCR Manager โปรดดำเนินการ: ใบขอ$cardtxt เลขที่ $cr_app_nbr : $cus_reg_nme";
					$mail_message = "<font style='font-family:Cordia New;font-size:18px'>เรียน Finance & Credit Manager<br><br>
					ใบขอ$cardtxt เลขที่ $cr_app_nbr $cus_reg_nme<br>
					Finance & Credit Manager : โปรดดำเนินการในระบบ Credit Control ด้วยค่ะ  <br><br>
					$doc_url <br><br>
					
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

			$params=array($cr_app_nbr);
			$sql_edit = "UPDATE cr_app_mstr SET " .
				" cr_remark = '$cr_remark', ".
				" cr_status = '$cr_status', ".
				" cr_update_by = '$user_login'," .
				" cr_update_date = '$today'" .
				" WHERE  cr_app_nbr = ?";
			$result_cr = sqlsrv_query($conn, $sql_edit, $params);
			if($result_cr){
				$params=array($cr_app_nbr);
				$sql_edit = "UPDATE cus_app_mstr SET " .
					" cus_step_code = '$cr_step_code', ".
					" cus_update_by = '$user_login'," .
					" cus_update_date = '$today'" .
					" WHERE  cus_app_nbr = ?";
				$result_edit = sqlsrv_query($conn, $sql_edit, $params);
			}
			if($result_edit){
				//เก็บประวัติการดำเนินการ
				$cr_ap_f_step = "10";  // Wait Credit1 
				$cr_ap_t_step = $cr_step_code;
				$cr_ap_color = "text-info";
				$cr_ap_text = "เจ้าหน้าที่สินเชื่อ ".$via_cr;
				//$cr_ap_text = "เจ้าหน้าที่สินเชื่อ "."อนุมัติ";
					
				$cr_ap_id = getcusnewapp($cr_app_nbr,$conn);
					
				$sql = "INSERT INTO cus_approval(" . 
				" cus_ap_id,cus_ap_nbr,cus_ap_f_step_code,cus_ap_t_step_code,cus_ap_text,cus_ap_remark,cus_ap_color,cus_ap_active,cus_ap_create_by,cus_ap_create_date)" .		
				" VALUES('$cr_ap_id','$cr_app_nbr','$cr_ap_f_step','$cr_ap_t_step','$cr_ap_text','$cr_remark','$cr_ap_color','1','$user_login','$today')";				
				$result = sqlsrv_query($conn, $sql);
			}

			if($result){
				$r="1";
				$nb=encrypt($cr_app_nbr, $key);
				$errortxt="บันทึกข้อมูลแก้ไขเรียบร้อยแล้ว";
			}
			else {
				$r="0";
				$nb="";
				$errortxt="ไม่สามารถบันทึกข้อมูลแก้ไขได้";
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