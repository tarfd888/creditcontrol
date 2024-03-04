<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	
	$query_detail = "SELECT * FROM  cus_app_mstr";
	$result = sqlsrv_query($conn, $query_detail);

	$data_nbr = array();
	while($rows = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			$dataArray['cus_app_nbr'] = $rows['cus_app_nbr'];
			$dataArray['cus_date'] = dmytx($rows['cus_date']);
			array_push($data_nbr,$dataArray);
		}
	echo json_encode($data_nbr);

?>