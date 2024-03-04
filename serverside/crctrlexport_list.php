<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	//include("../crctrlbof/chkauthcr.php");
	//include("../crctrlbof/chkauthcrctrl.php");
	$result = new stdClass();
	$result->success = FALSE;
	$params = json_decode(filter_input(INPUT_POST, "param0"), TRUE);
	
    $result = getData($conn, $result, $params, $key, $user_team);
	echo json_encode($result);

	function getData($conn, $result, $params, $key, $user_team) {
		$crstm_date = $params["crstm_date"];
		$crstm_date1 = $params["crstm_date1"];
		$crstm_cus_nbr = $params["crstm_cus_nbr"];
		$crstm_beg_date = $params["crstm_beg_date"];
		$crstm_end_date = $params["crstm_end_date"];
		$crstm_step_name = $params["crstm_step_name"];
		$crstm_approve = $params["crstm_approve"];
		$crstm_cc_amt = str_replace(",","", $params["crstm_cc_amt"]);
		$crstm_cc_amt1 = str_replace(",","", $params["crstm_cc_amt1"]);
		//$crstm_pj_amt = mssql_escape(str_replace(",","",$_POST['crstm_pj_amt']));
	
		$result_row = array();
		$result_row1 = array();
		$query_params = array();
		
		if ($crstm_approve != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $crstm_approve);
			$criteria = $criteria . " crstm_approve = ?";
		}
		if ($crstm_step_name != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $crstm_step_name);
			$criteria = $criteria . " crstm_step_name = ?";
		}
		if ($crstm_cus_nbr != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $crstm_cus_nbr);
			$criteria = $criteria . " crstm_cus_nbr = ?";
		}
		/* if ($crstm_cc_amt != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $crstm_cc_amt);
			$criteria = $criteria . " crstm_cc_amt = ?";
		} */
		if ($crstm_cc_amt != "" && $crstm_cc_amt1 != "") {
			array_push($query_params, $crstm_cc_amt);
			array_push($query_params, $crstm_cc_amt1);
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			$criteria = $criteria . " crstm_cc_amt >= ? AND crstm_cc_amt <= ?";
		} 
		if ($crstm_date != "" && $crstm_date1 != "") {
			array_push($query_params, ymd($crstm_date));
			array_push($query_params, ymd($crstm_date1));
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			$criteria = $criteria . " crstm_date >= ? AND crstm_date <= ?";
		} else {		
			if ($crstm_date != "") {
				array_push($query_params, ymd($crstm_date));
				if ($criteria != "") { $criteria = $criteria . " AND "; }
				$criteria = $criteria . " crstm_date >= ?";					
			} else {
				if ($crstm_date1 != "") {
					array_push($query_params, ymd($crstm_date1));
					if ($criteria != "") { $criteria = $criteria . " AND "; }
					$criteria = $criteria . " crstm_date1 <= ?";	
				}
			}
		}
		if ($crstm_beg_date != "" && $crstm_end_date != "") {
			array_push($query_params, ymd($crstm_beg_date));
			array_push($query_params, ymd($crstm_end_date));
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			$criteria = $criteria . " crstm_cc_date_beg >= ? AND crstm_cc_date_end <= ?";
		} else {		
			if ($crstm_beg_date != "") {
				array_push($query_params, ymd($crstm_beg_date));
				if ($criteria != "") { $criteria = $criteria . " AND "; }
				$criteria = $criteria . " crstm_beg_date >= ?";					
			} else {
				if ($crstm_end_date != "") {
					array_push($query_params, ymd($crstm_end_date));
					if ($criteria != "") { $criteria = $criteria . " AND "; }
					$criteria = $criteria . " crstm_end_date <= ?";	
				}
			}
		}
		if ($criteria != "") {
			$criteria = " WHERE " . $criteria . " ";
		}

		$sql_del = "delete from crstm_det "; // ลบไฟล์ temp
						$result_del_upload = sqlsrv_query($conn, $sql_del);

		$sql = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_step_code, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_prefix_th_name, ". 
                       "crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_step_name,crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_rdo2, ".
                       "crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, ".
                       "crstm_mstr.crstm_cc_amt, crstm_mstr.crstm_user,crstm_cus_active ".
					   "FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					   //"INNER JOIN tbl3_mstr ON crstm_mstr.crstm_nbr = tbl3_mstr.tbl3_nbr ".
					   "$criteria ORDER BY crstm_mstr.crstm_nbr desc";
		
	
		$query = sqlsrv_query($conn, $sql, $query_params);
		while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
			$crstm_user = html_escape($row['crstm_user']);
			$crstm_nbr = html_escape($row['crstm_nbr']);
			$crstm_date = html_escape($row['crstm_date']);
			$crstm_cus_nbr = html_escape($row['crstm_cus_nbr']);
			$crstm_cus_name = html_escape($row['crstm_cus_name']);
			$emp_prefix_th_name = html_escape($row['emp_prefix_th_name']);
			$emp_th_firstname = html_escape($row['emp_th_firstname']);
			$emp_th_lastname = html_escape($row['emp_th_lastname']);
			$crstm_step_code = html_escape($row['crstm_step_code']);
			$crstm_step_name = html_escape($row['crstm_step_name']);
			$crstm_cus_active = html_escape($row['crstm_cus_active']);
			$crstm_approve = html_escape($row['crstm_approve']);
			$crstm_cc_amt = html_escape($row['crstm_cc_amt']);
			$crstm_cc_date_beg = html_escape($row['crstm_cc_date_beg']);
			$crstm_cc_date_end = html_escape($row['crstm_cc_date_end']);	
			$tbl3_amt_loc_curr = html_escape($row['tbl3_amt_loc_curr']);
			$crstm_cus_active = html_escape($row['crstm_cus_active']);

			//$params_update_his_pjm = array($crstm_nbr);
			$sql_update_his_pjm = "insert into crstm_det (crstm_nbr,crstm_date,crstm_cus_nbr, crstm_cus_name, crstm_step_code, ".
			"crstm_step_name, crstm_user,crstm_cus_active, crstm_approve, crstm_cc_amt, crstm_cc_date_beg, ".
			"crstm_cc1_amt, ".
			"crstm_cc_date_end) ".
				"values ('$crstm_nbr',
				'$crstm_date',
				'$crstm_cus_nbr',
				'$crstm_cus_name',
				'$crstm_step_code',
				'$crstm_step_name',
				'$crstm_user',
				'$crstm_cus_active',
				'$crstm_approve',
				'0',
				'$crstm_cc_date_beg',
				'0',
				'$crstm_cc_date_end')";

			$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm);

			$params = array($crstm_nbr);
			$sql = "select tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date, tbl3_update_by, tbl3_update_date	".
				   "FROM tbl3_mstr  where tbl3_nbr = ? ORDER BY tbl3_nbr ";
			$result_detail = sqlsrv_query($conn, $sql,$params);
			//$row = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
			//if ($row) {
			while ($row = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC)) {
				$tbl3_nbr = html_escape($row['tbl3_nbr']);
				$tbl3_txt_ref =  html_escape($row['tbl3_txt_ref']);
				$tbl3_amt_loc_curr = html_escape($row['tbl3_amt_loc_curr']);
				$tbl3_doc_date = html_escape($row['tbl3_doc_date']);
				$tbl3_due_date = html_escape($row['tbl3_due_date']);
				if($tbl3_txt_ref =="CC"){	
						$tbl3_txt_ref =  html_escape($row['tbl3_txt_ref']);
						$params_edit = array($tbl3_nbr);
						$sql_edit = "UPDATE crstm_det SET ".
						" crstm_cc_txt = '$tbl3_txt_ref', ".
						" crstm_cc_amt = crstm_cc_amt + '$tbl3_amt_loc_curr' ".
						" WHERE crstm_nbr = ? ";
						//$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				}else if($tbl3_txt_ref =="C1"){
						$tbl3_txt_ref1 =  html_escape($row['tbl3_txt_ref']);
						$params_edit = array($tbl3_nbr);
						$sql_edit = "UPDATE crstm_det SET ".
						" crstm_cc1_txt = '$tbl3_txt_ref1', ".
						" crstm_cc1_amt =  '$tbl3_amt_loc_curr' ".
						" WHERE crstm_nbr = ? ";
						//$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				}else if($tbl3_txt_ref =="C3"){
						$tbl3_txt_ref1 =  html_escape($row['tbl3_txt_ref']);
						$params_edit = array($tbl3_nbr);
						$sql_edit = "UPDATE crstm_det SET ".
						" crstm_cc_date_beg = '$tbl3_doc_date', ".
						" crstm_cc_date_end = '$tbl3_due_date', ".
						" crstm_cc1_txt = '$tbl3_txt_ref1', ".
						" crstm_cc1_amt =  '$tbl3_amt_loc_curr' ".
						" WHERE crstm_nbr = ? ";
						//$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
				}  
						$result_edit = sqlsrv_query($conn,$sql_edit,$params_edit);
			}
			//array_push($result_row, $data);
		}
		$sql = "SELECT crstm_det.crstm_nbr, crstm_det.crstm_date, crstm_det.crstm_step_code, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_prefix_th_name, crstm_det.crstm_cus_nbr, ".
					   "crstm_det.crstm_cus_name, crstm_det.crstm_approve, crstm_det.crstm_cc_date_beg, crstm_det.crstm_cc_date_end, crstm_det.crstm_cc_amt, crstm_det.crstm_cc1_amt, ".
					   "crstm_step_name, crstm_cus_active ".
					   "FROM crstm_det INNER JOIN emp_mstr ON crstm_det.crstm_user = emp_mstr.emp_scg_emp_id ORDER BY crstm_det.crstm_nbr DESC ";
		$query = sqlsrv_query($conn, $sql, $query_params);
		while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
			$data['crstm_nbr'] = html_escape($row['crstm_nbr']);
			$data['crstm_date'] = html_escape(dmytx($row['crstm_date']));
			$data['crstm_cus_nbr'] = html_escape($row['crstm_cus_nbr']);
			$data['crstm_cus_name'] = html_escape($row['crstm_cus_name']);
			$data['emp_prefix_th_name'] = html_escape($row['emp_prefix_th_name']);
			$data['emp_th_firstname'] = html_escape($row['emp_th_firstname']);
			$data['emp_th_lastname'] = html_escape($row['emp_th_lastname']);
			$data['crstm_step_code'] = html_escape($row['crstm_step_code']);
			$data['crstm_step_name'] = html_escape($row['crstm_step_name']);
			$data['crstm_approve'] = html_escape($row['crstm_approve']);
			$data['crstm_cus_active'] = html_escape($row['crstm_cus_active']);
			$data['crstm_cc_amt'] = html_escape(CheckandShowNumber($row['crstm_cc_amt'],0));
			$data['crstm_cc1_amt'] = html_escape(CheckandShowNumber($row['crstm_cc1_amt'],0));
			$cc_amt = html_escape($row['crstm_cc_amt']);
			$cc1_amt = html_escape($row['crstm_cc1_amt']);
			$crstm_amt_new = $cc_amt + $cc1_amt;
			$data['crstm_amt_new'] = CheckandShowNumber($crstm_amt_new,0);
			$data['crstm_cc_date_beg'] = dmytx($row['crstm_cc_date_beg']);
			$data['crstm_cc_date_end'] = dmytx($row['crstm_cc_date_end']);	
			$data['tbl3_amt_loc_curr'] = html_escape($row['tbl3_amt_loc_curr']); 
			array_push($result_row, $data);
		}	
		$result->data = $result_row;
		$result->success = TRUE;
		return $result;
	}			
?>