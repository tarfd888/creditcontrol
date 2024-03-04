<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");

	$result = new stdClass();
	$result->success = FALSE;
	$params = json_decode(filter_input(INPUT_POST, "param0"), TRUE);
  	$result = getData($conn, $result, $params, $key);
	echo json_encode($result);

	function getData($conn, $result, $params, $key) {
		$cus_date = $params["cus_date"];
		$cus_code = $params["cus_code"];
		$cus_app_nbr = $params["cus_app_nbr"];
		$cus_cond_cust = $params["cus_cond_cust"];
		$cusd_op_app = $params["cusd_op_app"];
		$cr_sta_complete = $params["cr_sta_complete"];
		$cusstep_name_en = $params["cusstep_name_en"];
		$start = $params["start"];
		$end = $params["end"];

		$cus_step_code = findsqlval("cusstep_mstr","cusstep_code","cusstep_name_en",$cusstep_name_en,$conn);

		$result_row = array();
		$query_params = array();

		if ($cus_code != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $cus_code);
			$criteria = $criteria . " cus_code = ?";
		}
		if ($cus_app_nbr != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $cus_app_nbr);
			$criteria = $criteria . " cus_app_nbr = ?";
		}
		
		if ($cus_cond_cust != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $cus_cond_cust);
			$criteria = $criteria . " cus_cond_cust = ?";
		}
		
		if ($cusd_op_app != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $cusd_op_app);
			$criteria = $criteria . " cusd_op_app = ?";
		}

		if ($cus_step_code != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $cus_step_code);
			$criteria = $criteria . " cus_step_code = ?";
		}

		if ($cr_sta_complete != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $cr_sta_complete);
			$criteria = $criteria . " cr_sta_complete = ?";
		}

		if ($start != "" && $end != "") {
			array_push($query_params, ymd($stat));
			array_push($query_params, ymd($end));
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			$criteria = $criteria . " cus_date >= ? AND cus_date <= ?";
		} 	
		
		if ($criteria != "") {
			$criteria = " WHERE " . $criteria . " ";
		}

		$sql =  "SELECT cus_app_mstr.cus_app_nbr, cus_app_mstr.cus_date, cus_app_mstr.cus_cond_cust, cus_app_mstr.cus_code, cus_app_mstr.cus_reg_nme, cus_app_mstr.cus_reg_addr, ".
                "cus_app_mstr.cus_prov,cus_app_mstr.cus_country, cus_app_mstr.cus_tax_id, cus_app_det.cusd_op_app, cr_app_mstr.cr_sap_code, cr_app_mstr.cr_sap_code_date, cr_app_mstr.cr_sta_complete,cus_app_mstr.cus_step_code ".
			    "FROM cus_app_mstr INNER JOIN cus_app_det ON cus_app_mstr.cus_app_nbr = cus_app_det.cusd_app_nbr INNER JOIN ".
                "cr_app_mstr ON cus_app_mstr.cus_app_nbr = cr_app_mstr.cr_app_nbr $criteria ORDER BY cus_app_nbr desc";

		$query = sqlsrv_query($conn, $sql, $query_params);
		while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
			$data['cus_app_nbr'] = html_escape($row['cus_app_nbr']);
			$data['cus_date'] = html_escape(dmytx($row['cus_date']));
			$data['cus_code'] = html_escape($row['cus_code']);
			$data['cus_reg_nme'] = html_escape($row['cus_reg_nme']);
			$data['cus_tax_id'] = html_escape($row['cus_tax_id']);
			$data['cus_country'] = html_escape($row['cus_country']);
			$data['cus_prov'] = html_escape($row['cus_prov']);
			$data['cusd_op_app'] = html_escape($row['cusd_op_app']);
			$cus_step_code = html_escape($row['cus_step_code']);
			$cr_sta_complete = html_escape($row['cr_sta_complete']);
			$data['cus_cond_cust'] = html_escape($row['cus_cond_cust']);

			if($data['cus_code']!=""){
				$data['cus_code'] = html_escape($row['cus_code']);
			} else {
				$data['cus_code'] = html_escape($row['cr_sap_code']);
			}
			if($data['cus_cond_cust'] == "c1") {
				$data['cus_cond_cust_name'] = 'แต่งตั้งลูกค้าใหม่' ; 
			}
			if($data['cus_cond_cust'] == "c2") {
				$data['cus_cond_cust_name'] = 'แต่งตั้งร้านสาขา' ; 
			}
			if($data['cus_cond_cust'] == "c3") {
				$data['cus_cond_cust_name'] = 'เปลี่ยนแปลงชื่อ' ; 
			}
			if($data['cus_cond_cust'] == "c4") {
				$data['cus_cond_cust_name'] = 'เปลี่ยนแปลงที่อยู่จดทะเบียน' ; 
			}
			if($data['cus_cond_cust'] == "c5") {
				$data['cus_cond_cust_name'] = 'เปลี่ยนแปลงชื่อและที่อยู่' ; 
			}
			if($cr_sta_complete=="I"){
				$data['cr_sta_name'] = "Incomplete";
			} else if ($cr_sta_complete=="C"){
				$data['cr_sta_name'] = "Complete";
			} else {
				$data['cr_sta_name'] = "";
			}

			$data['cus_step_name'] = findsqlval("cusstep_mstr","cusstep_name_en","cusstep_code",$cus_step_code,$conn);
			array_push($result_row, $data);
		}	
		$result->data = $result_row;
		$result->success = TRUE;
		return $result;
	}			
?>