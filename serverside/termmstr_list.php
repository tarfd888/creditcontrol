<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");

	$sql_role_list = "select * from term_mstr order by term_code";
					
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
		else // Result > 0 row
			{
				$arrayMain['draw'] = 1;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				$arrayJSON = array();	
				$arrayDATA = array();	
				
				while($row_role_list = sqlsrv_fetch_array($result_role_list, SQLSRV_FETCH_ASSOC))
				{
					
					$dataArray_role_list['term_code'] = html_escape($row_role_list['term_code']);
					$dataArray_role_list['term_active'] = html_escape($row_role_list['term_active']);
					$dataArray_role_list['term_desc'] = html_escape($row_role_list['term_desc']);
					$check_group = html_escape($row_role_list['term_group']);
					$dataArray_role_list['term_group'] = html_escape($row_role_list['term_group']);
					if($check_group=="1"){
					 	$dataArray_role_list['term_group'] = "Domestic";
					}else if($check_group=="2") {
						$dataArray_role_list['term_group'] = "Export";
					}else {
						$dataArray_role_list['term_group'] = "";
					} 


					array_push($arrayJSON,$dataArray_role_list);
					$arrayMain['data'] = $arrayJSON;
				}
				echo json_encode($arrayMain);	
			}				
?>