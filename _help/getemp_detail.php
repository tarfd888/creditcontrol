<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";


	// $query_cust_detail = "SELECT cus_mstr.cus_nbr, cus_mstr.cus_name1, cus_mstr.cus_name2, cus_mstr.cus_name3, cus_mstr.cus_name4, cus_mstr.cus_street, cus_mstr.cus_street2, cus_mstr.cus_street3, ". 
                         // "cus_mstr.cus_street4, cus_mstr.cus_street5, cus_mstr.cus_district, cus_mstr.cus_city, cus_mstr.cus_zipcode, cus_mstr.cus_country, cus_mstr.cus_tax_nbr3, cus_mstr.cus_terms_paymnt, ". 
                         // "term_mstr.term_code, term_mstr.term_desc FROM cus_mstr INNER JOIN term_mstr ON cus_mstr.cus_terms_paymnt = term_mstr.term_code ";
	
	$query_cust_detail = "SELECT * FROM emp_mstr";
	$result_cust_detail = sqlsrv_query($conn, $query_cust_detail);

	$data_cust_detail = array();
	while($row_cust_detail = sqlsrv_fetch_array($result_cust_detail, SQLSRV_FETCH_ASSOC))
		{
			$dataArray['emp_email_bus'] = strtolower($row_cust_detail['emp_email_bus']);
			$dataArray['emp_th_pos_name'] = $row_cust_detail['emp_th_pos_name'];
			$dataArray['emp_prefix_th_name'] = $row_cust_detail['emp_prefix_th_name'];
			$dataArray['emp_th_firstname'] = $row_cust_detail['emp_th_firstname'];
			$dataArray['emp_th_lastname'] = $row_cust_detail['emp_th_lastname'];
			$dataArray['emp_th_div'] = $row_cust_detail['emp_th_div'];
			$dataArray['emp_th_dept'] = $row_cust_detail['emp_th_dept'];
			$dataArray['emp_th_sec'] = $row_cust_detail['emp_th_sec'];
			array_push($data_cust_detail,$dataArray);
		}
	echo json_encode($data_cust_detail);

?>