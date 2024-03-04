<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");

	$action_author = decrypt(mssql_escape($_REQUEST['author_nbr']), $key);
	$account_group = decrypt(mssql_escape($_REQUEST['acc_group']), $key);

	//$action_author = mssql_escape($_REQUEST['author_nbr']);
	if($action_author == "1"){
		$sql_autho_list = "select * from author_mstr where account_group !='ZC01' order by author_id,author_seq";
	} else {
		$sql_autho_list = "select * from author_g_mstr where account_group !='ZC01' order by author_id,author_seq";
	}
	
					
		$params_autho_list = array();
		$result_autho_list = sqlsrv_query( $conn,$sql_autho_list, $params_autho_list, array( "Scrollable" => 'keyset' ));	
		$row_counts = sqlsrv_num_rows($result_autho_list);
		
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
				
				while($row_autho_list = sqlsrv_fetch_array($result_autho_list, SQLSRV_FETCH_ASSOC))
				{
					$dataArray_autho_list['author_id'] = html_escape($row_autho_list['author_id']);
					$dataArray_autho_list['author_group'] = html_escape($row_autho_list['author_group']);
					$dataArray_autho_list['author_sign_nme'] = html_escape($row_autho_list['author_sign_nme']);
					$dataArray_autho_list['author_code'] = html_escape($row_autho_list['author_code']);
					$dataArray_autho_list['author_sign'] = html_escape($row_autho_list['author_sign']);
					$dataArray_autho_list['author_email'] = html_escape($row_autho_list['author_email']);
					$dataArray_autho_list['author_text'] = html_escape($row_autho_list['author_text']);
					$dataArray_autho_list['author_salutation'] = html_escape($row_autho_list['author_salutation']);
					$dataArray_autho_list['author_email_status'] = html_escape($row_autho_list['author_email_status']);
					$dataArray_autho_list['author_active'] = html_escape($row_autho_list['author_active']);
					$dataArray_autho_list['financial_amt_beg'] = CheckandShowNumber($row_autho_list['financial_amt_beg'],0);
					$dataArray_autho_list['financial_amt_end'] = CheckandShowNumber($row_autho_list['financial_amt_end'],0);
					//Nilubonp : Put data from arrayDATA into arrayJSON  column by column to each row
					array_push($arrayJSON,$dataArray_autho_list);
			
					//Nilubonp : Put data from arrayDATA into arrayJSON  object by object
					$arrayMain['data'] = $arrayJSON;
				}
								
				//Nilubonp : Finally Create JSON ARRAY
				echo json_encode($arrayMain);	
			}				
?>