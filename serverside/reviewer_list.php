<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");

	$sql = "select * from reviewer_mstr order by emp_person_id";
					
		$params = array();
		$result = sqlsrv_query( $conn,$sql, $params, array( "Scrollable" => 'keyset' ));	
		$row_counts = sqlsrv_num_rows($result);
		
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
				
				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
						$dataArray_role_list['emp_person_id'] = html_escape($row['emp_person_id']);
						$dataArray_role_list['emp_user_id'] = html_escape($row['emp_user_id']);
						$dataArray_role_list['emp_prefix_th_name'] = html_escape($row['emp_prefix_th_name']);
						$dataArray_role_list['emp_th_firstname'] = html_escape($row['emp_th_firstname']);
						$dataArray_role_list['emp_th_lastname'] = html_escape($row['emp_th_lastname']);
						$dataArray_role_list['emp_th_pos_name'] = html_escape($row['emp_th_pos_name']);
						$dataArray_role_list['emp_email_bus'] = strtolower(html_escape($row['emp_email_bus']));
						$dataArray_role_list['emp_flag'] = html_escape($row['emp_flag']);

					// : Put data from arrayDATA into arrayJSON  column by column to each row
					array_push($arrayJSON,$dataArray_role_list);
			
					// : Put data from arrayDATA into arrayJSON  object by object
					$arrayMain['data'] = $arrayJSON;
				}
								
				// : Finally Create JSON ARRAY
				echo json_encode($arrayMain);	
			}				
?>