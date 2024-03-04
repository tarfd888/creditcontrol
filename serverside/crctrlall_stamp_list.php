<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	include("../crctrlbof/chkauthcr.php");
	include("../crctrlbof/chkauthcrctrl.php");

	$params_custsp_list = array($user_login);

	
	$sql_custsp_list = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_step_code, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_prefix_th_name, ". 
                       "crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_step_name ".
					   "FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					   "WHERE (crstm_mstr.crstm_step_code = '61') ORDER BY crstm_mstr.crstm_nbr desc";
	
	/* if ($can_edit_cr) {
	$sql_custsp_list = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_step_code, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, emp_mstr.emp_prefix_th_name, ". 
                       "crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, crstm_mstr.crstm_cus_active, crstm_mstr.crstm_step_name ".
					   "FROM crstm_mstr INNER JOIN emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id ".
					   "WHERE (crstm_mstr.crstm_step_code != '0') ORDER BY crstm_mstr.crstm_nbr desc";
	}  */
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
				// : Create Array for Build JSON ( $arrayMain)
				$arrayMain['draw'] = 1;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				
				// : Create Array for Build data to push into $arrayMain ($arrayJSON)
				$arrayJSON = array();	
				$arrayDATA = array();	
				
				while($row_stamp_list = sqlsrv_fetch_array($result_custsp_list, SQLSRV_FETCH_ASSOC))
				{
						$rdataArray_stamp_list['crstm_nbr'] = html_escape($row_stamp_list['crstm_nbr']);
						$rdataArray_stamp_list['crstm_date'] = html_escape(dmytx($row_stamp_list['crstm_date']));
						$rdataArray_stamp_list['crstm_cus_nbr'] = html_escape($row_stamp_list['crstm_cus_nbr']);
						$rdataArray_stamp_list['crstm_cus_name'] = html_escape($row_stamp_list['crstm_cus_name']);
						$rdataArray_stamp_list['emp_prefix_th_name'] = html_escape($row_stamp_list['emp_prefix_th_name']);
						$rdataArray_stamp_list['emp_th_firstname'] = html_escape($row_stamp_list['emp_th_firstname']);
						$rdataArray_stamp_list['emp_th_lastname'] = html_escape($row_stamp_list['emp_th_lastname']);
						$rdataArray_stamp_list['crstm_step_code'] = html_escape($row_stamp_list['crstm_step_code']);
						$rdataArray_stamp_list['crstm_step_name'] = html_escape($row_stamp_list['crstm_step_name']);
						$rdataArray_stamp_list['crstm_cus_active'] = html_escape($row_stamp_list['crstm_cus_active']);
						$crstm_nbr_enc = encrypt($row_record['crstm_nbr'],$key);
						$rdataArray_stamp_list['action']="<input type='checkbox' class='nbr_check' name='chk_app' id='delcheck_".$row_stamp_list['crstm_nbr']."' onclick='checkcheckbox();' value='".$row_stamp_list['crstm_nbr']."'>";
						//$rdataArray_stamp_list['action']="<input type='checkbox' class='nbr_check' name='chk_app' id='delcheck_".$row_stamp_list['crstm_nbr']."' onclick='checkcheckbox();' value='".$crstm_nbr_enc."'>";

				//Put data from arrayDATA into arrayJSON  column by column to each row
					array_push($arrayJSON,$rdataArray_stamp_list);
			
					// : Put data from arrayDATA into arrayJSON  object by object
					$arrayMain['data'] = $arrayJSON;
				}
								
				// : Finally Create JSON ARRAY
				echo json_encode($arrayMain);	
			}				
?>