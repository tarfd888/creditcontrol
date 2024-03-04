<?php
	include("../_incs/chksession.php");	
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	
	$errorflag = false;					
	$result_text = "";
	
	$mgr_nbr = mssql_escape($_POST["mgrnumber"]);
	///
	$params = array($mgr_nbr);
	$sql = "SELECT emp_scg_emp_id, emp_prefix_th_name, emp_th_firstname, emp_th_lastname, emp_th_org_name, emp_email_bus FROM  emp_mstr WHERE (emp_scg_emp_id = ?) and emp_scg_emp_id !='' ";
	$result = sqlsrv_query($conn, $sql,$params, array( "Scrollable" => 'keyset' ));
	$rowCounts = sqlsrv_num_rows($result);
	if($rowCounts >0){
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			$emp_scg_emp_id = $row['emp_scg_emp_id'];
			$emp_prefix_th_name = $row['emp_prefix_th_name'];
			$emp_th_firstname = $row['emp_th_firstname'];
			$emp_th_lastname = $row['emp_th_lastname'];
			$emp_th_org_name = $row['emp_th_org_name'];
			$emp_email_bus = strtolower($row['emp_email_bus']);
			$manager_name = $emp_prefix_th_name.$emp_th_firstname."  ".$emp_th_lastname;
			$result_text = "OK";
		}
	}
	else 
	{
		$errorflag = true;
		if ($result_text!="") {$result_text .= "<br>";}
		$result_text .= "ไม่พบข้อมูล ";
	}
		echo '{"result_text":"'.$result_text.'","manager_code":"'.$emp_scg_emp_id.'","manager_name":"'.$manager_name.'","emp_th_org_name":"'.$emp_th_org_name.'","emp_email_bus":"'.$emp_email_bus.'"}';

?>