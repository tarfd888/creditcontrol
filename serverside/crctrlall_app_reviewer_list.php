<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	include("../crctrlbof/chkauthcr.php");
	include("../crctrlbof/chkauthcrctrl.php");

	/* $params_custsp_list = array($user_login);

	
	$sql_custsp_list = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_step_code, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_prefix_th_name, ". 
                       "crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_step_name ".
					   "FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					   "WHERE (crstm_mstr.crstm_curprocessor = ?) ORDER BY crstm_mstr.crstm_nbr desc";
	
		$result_custsp_list = sqlsrv_query( $conn,$sql_custsp_list, $params_custsp_list, array( "Scrollable" => 'keyset' ));	
		$row_counts = sqlsrv_num_rows($result_custsp_list);
		
		$arrayMain = array();	
		if($row_counts == 0) // Result == 0 row
			{
				$arrayMain['draw'] = 0;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				$arrayMain['data'] = array();
				echo json_encode($arrayMain);	
			}
		else // :  Result > 0 row
			{
				$arrayMain['draw'] = 1;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				
				$arrayJSON = array();	
				$arrayDATA = array();	
				
				while($row = sqlsrv_fetch_array($result_custsp_list, SQLSRV_FETCH_ASSOC))
				{
						$data['crstm_nbr'] = html_escape($row['crstm_nbr']);
						$data['crstm_date'] = html_escape(dmytx($row['crstm_date']));
						$data['crstm_cus_nbr'] = html_escape($row['crstm_cus_nbr']);
						$data['crstm_cus_name'] = html_escape($row['crstm_cus_name']);
						$data['emp_prefix_th_name'] = html_escape($row['emp_prefix_th_name']);
						$data['emp_th_firstname'] = html_escape($row['emp_th_firstname']);
						$data['emp_th_lastname'] = html_escape($row['emp_th_lastname']);
						$data['crstm_step_code'] = html_escape($row['crstm_step_code']);
						$data['crstm_step_name'] = html_escape($row['crstm_step_name']);
						$data['crstm_cus_active'] = html_escape($row['crstm_cus_active']);
						$crstm_nbr_enc = encrypt($row_record['crstm_nbr'],$key);
						$data['action']="<input type='checkbox' class='nbr_check' name='chk_app' id='delcheck_".$row['crstm_nbr']."' onclick='checkcheckbox();' value='".$row['crstm_nbr']."'>";

					array_push($arrayJSON,$data);
					$arrayMain['data'] = $arrayJSON;
				}
	
				echo json_encode($arrayMain);	
			}	 */		
	$result = new stdClass();
	$result->success = FALSE;
	$params = json_decode(filter_input(INPUT_POST, "param0"), TRUE);
	
    $result = getData($conn, $result, $params, $key, $user_team);
	echo json_encode($result);

	function getData($conn, $result, $params, $key, $user_team) {
		$crctrl_select = $params["crctrl_select"];
		$rev_user_login = $params["rev_user_login"];
		
		$result_row = array();
		$result_row1 = array();
		$query_params = array();
		
		if($crctrl_select==1){
		 if ($rev_user_login != "") {	
			if ($criteria != "") { $criteria = $criteria . " and "; }
			array_push($query_params, $rev_user_login);
			$criteria = $criteria . " crstm_reviewer = ? and crstm_step_code='110'";	
			//$criteria = $criteria . " crstm_reviewer = ? and crstm_reviewer_date  is null or crstm_reviewer_date ='' and crstm_step_code='110'";	
			}
		}
		if($crctrl_select==2){
		 if ($rev_user_login != "") {	
			if ($criteria != "") { $criteria = $criteria . " and "; }
			array_push($query_params, $rev_user_login);
			$criteria = $criteria . " crstm_reviewer = ? ";	
			//$criteria = $criteria . " crstm_whocanread like '%'+ ? +'%' ";	
			}
		}
		
		if ($criteria != "") {
			$criteria = " WHERE " . $criteria . " ";
		}
		$sql = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_step_code, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_prefix_th_name, ". 
                       "crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_step_name ".
					   "FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					   "$criteria ORDER BY crstm_mstr.crstm_nbr desc"; 
		/*  $sql = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_step_code, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_prefix_th_name, ". 
                       "crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_step_name ".
					   "FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					   " ORDER BY crstm_mstr.crstm_nbr desc";  */
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
			$data['crstm_cus_active'] = html_escape($row['crstm_cus_active']);
			
			
			array_push($result_row, $data);
		}	
		$result->data = $result_row;
		$result->success = TRUE;
		return $result;
	}			
?>