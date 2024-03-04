<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");

	$sql_role_list = "select * from  sign_mstr order by sign_code";
					
		$params_role_list = array();
		$result_role_list = sqlsrv_query( $conn,$sql_role_list, $params_role_list, array( "Scrollable" => 'keyset' ));	
		$row_counts = sqlsrv_num_rows($result_role_list);
		
		$arrayMain = array();	
		if($row_counts == 0) // Result == 0 row
			{
				$arrayMain['draw'] = 0;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				$arrayMain['data'] = array();
				echo json_encode($arrayMain);	
			}
		else //Nilubonp :  Result > 0 row
			{
				//Nilubonp : Create Array for Build JSON ( $arrayMain)
				$arrayMain['draw'] = 1;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				
				//Nilubonp : Create Array for Build data to push into $arrayMain ($arrayJSON)
				$arrayJSON = array();	
				$arrayDATA = array();	
				
				while($row_role_list = sqlsrv_fetch_array($result_role_list, SQLSRV_FETCH_ASSOC))
				{
						// $dataArray_role_list['doc_date'] = $row_role_list['doc_date']->format('Y-m-d');
						$dataArray_role_list['sign_name'] = html_escape($row_role_list['sign_name']);
						$dataArray_role_list['sign_text'] = html_escape($row_role_list['sign_text']);
						$dataArray_role_list['sign_email'] = html_escape($row_role_list['sign_email']);
						$dataArray_role_list['sign_email_status'] = html_escape($row_role_list['sign_email_status']);
						$dataArray_role_list['sign_active'] = html_escape($row_role_list['sign_active']);
						$dataArray_role_list['sign_code'] = html_escape($row_role_list['sign_code']);

					//Nilubonp : Put data from arrayDATA into arrayJSON  column by column to each row
					array_push($arrayJSON,$dataArray_role_list);
			
					//Nilubonp : Put data from arrayDATA into arrayJSON  object by object
					$arrayMain['data'] = $arrayJSON;
				}
								
				//Nilubonp : Finally Create JSON ARRAY
				echo json_encode($arrayMain);	
			}				
?>