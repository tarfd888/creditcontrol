<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	
	$query = "SELECT * from COUNTRY_MSTR";
	$result = sqlsrv_query($conn, $query);

	$data = array();
	while($row_cust_detail = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			$dataArray['country_desc'] = $row_cust_detail['country_desc'];
			$dataArray['country_code'] = $row_cust_detail['country_code'];
			array_push($data,$dataArray);
		}
	echo json_encode($data);

?>