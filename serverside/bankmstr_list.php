<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");

	$sql = "select * from bank_mstr order by  bank_code,bank_th_name";
					
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
				
				while($rows = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
				{
					$dataArray['bank_code'] = html_escape($rows['bank_code']);
					$dataArray['bank_th_name'] = html_escape($rows['bank_th_name']);
					$dataArray['bank_status'] = html_escape($rows['bank_status']);
					array_push($arrayJSON,$dataArray);
			
					$arrayMain['data'] = $arrayJSON;
				}
								
				//Nilubonp : Finally Create JSON ARRAY
				echo json_encode($arrayMain);	
			}				
?>