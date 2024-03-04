<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	include("../crctrlbof/chkauthcr.php");
	include("../crctrlbof/chkauthcrctrl.php");

	$params = array($user_login);

	if ($can_editing) {
		$sql_list = "SELECT * from cus_app_mstr where cus_create_by = ? order by cus_app_nbr desc";
	} 
	if ($can_edit_cr || $can_edit_mgr) {
		$sql_list = "SELECT * from cus_app_mstr where cus_step_code != 0 order by cus_app_nbr desc";
	}  
		$result_list = sqlsrv_query( $conn,$sql_list, $params, array( "Scrollable" => 'keyset' ));	
		$row_counts = sqlsrv_num_rows($result_list);
		
		$arrayMain = array();	
		if($row_counts == 0) // Result == 0 row
			{
				$arrayMain['draw'] = 0;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				$arrayMain['data'] = array();
				echo json_encode($arrayMain);	
			}
		else //Result > 0 row
			{
				//Create Array for Build JSON ( $arrayMain)
				$arrayMain['draw'] = 1;
				$arrayMain['recordsTotal']  = $row_counts;
				$arrayMain['recordsFiltered']  = $row_counts;	
				
				//Create Array for Build data to push into $arrayMain ($arrayJSON)
				$arrayJSON = array();	
				$arrayDATA = array();	
				
				while($rows = sqlsrv_fetch_array($result_list, SQLSRV_FETCH_ASSOC))
				{
						$rdataArray['cus_app_nbr'] = mssql_escape($rows['cus_app_nbr']);
						$rdataArray['cus_date'] = mssql_escape(dmytx($rows['cus_date']));
						$rdataArray['cus_tg_cust'] = mssql_escape($rows['cus_tg_cust']);
						$rdataArray['cus_reg_nme'] = mssql_escape($rows['cus_reg_nme']);
						$rdataArray['cus_step_code'] = mssql_escape($rows['cus_step_code']);
						$cus_create_by = mssql_escape($rows['cus_create_by']);
						$rdataArray['cus_id'] = mssql_escape($rows['cus_id']);
						$cus_cust_type = mssql_escape($rows['cus_cust_type']);
						$rdataArray['cus_cust_type'] = findsqlval("cus_type_mstr", "cus_type_name", "cus_type_code", $cus_cust_type ,$conn);
						$rdataArray['cus_create_by'] = findsqlval("emp_mstr", "emp_prefix_th_name+emp_th_firstname+' '+emp_th_lastname", "emp_user_id", $cus_create_by ,$conn);
						
						$rdataArray['cus_cond_cust'] = mssql_escape($rows['cus_cond_cust']);
						$cus_new_info = mssql_escape($rows['cus_cond_cust']);
						switch($cus_new_info){
							case "c1" :
								$rdataArray['cus_new_info'] = "แต่งตั้งลูกค้าใหม่";
							  break;
							case "c2" :
								$rdataArray['cus_new_info'] = "แต่งตั้งร้านสาขา";
							  break;  
							case "c3" :
								$rdataArray['cus_new_info'] = "เปลี่ยนแปลงชื่อ";
							  break;   
							case "c4" :
								$rdataArray['cus_new_info'] = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
							  break;  
							case "c5" :
								$rdataArray['cus_new_info'] = "เปลี่ยนแปลงชื่อและที่อยู่";
							  break;           
							default :
								$rdataArray['cus_new_info'] = "ยกเลิกลูกค้า";
						  }
					// Put data from arrayDATA into arrayJSON  column by column to each row
					array_push($arrayJSON,$rdataArray);
			
					//Put data from arrayDATA into arrayJSON  object by object
					$arrayMain['data'] = $arrayJSON;
				}
								
				//Finally Create JSON ARRAY
				echo json_encode($arrayMain);	
			}				
?>