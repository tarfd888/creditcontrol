<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");

	$sql = "select * from country_mstr order by  country_code,country_desc";
					
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
		else //Nilubonp :  Result > 0 row
			{
				$arrayMain['draw'] = 1;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				
				$arrayJSON = array();	
				$arrayDATA = array();	
				
				while($row_role_list = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					$dataArray['country_code'] = html_escape($row_role_list['country_code']);
					$dataArray['country_desc'] = html_escape($row_role_list['country_desc']);
					array_push($arrayJSON,$dataArray);
			
					$arrayMain['data'] = $arrayJSON;
				}
								
				//Nilubonp : Finally Create JSON ARRAY
				echo json_encode($arrayMain);	
			}				
?>