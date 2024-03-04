<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	
	$query_cust_detail = "SELECT cus_mstr.cus_nbr, cus_mstr.cus_name1, cus_mstr.cus_name2, cus_mstr.cus_name3, cus_mstr.cus_name4, cus_mstr.cus_street, cus_mstr.cus_street2, cus_mstr.cus_street3, ".
                         "cus_mstr.cus_street4, cus_mstr.cus_street5, cus_mstr.cus_district, cus_mstr.cus_city, cus_mstr.cus_zipcode, cus_mstr.cus_country, cus_mstr.cus_tax_nbr3, cus_mstr.cus_terms_paymnt, ".
                         "term_mstr.term_code, term_mstr.term_desc, country_mstr.country_desc, cus_mstr.cus_acc_group, cus_mstr.cus_tax_nbr4 FROM cus_mstr INNER JOIN term_mstr ON cus_mstr.cus_terms_paymnt = term_mstr.term_code INNER JOIN ".
                         "country_mstr ON cus_mstr.cus_country = country_mstr.country_code";
	$result_cust_detail = sqlsrv_query($conn, $query_cust_detail);

	$data_cust_detail = array();
	while($row_cust_detail = sqlsrv_fetch_array($result_cust_detail, SQLSRV_FETCH_ASSOC))
		{
			$dataArray['cus_nbr'] = $row_cust_detail['cus_nbr'];
			$dataArray['cus_name1'] = $row_cust_detail['cus_name1'];
			$dataArray['cus_name2'] = $row_cust_detail['cus_name2'];
			$dataArray['cus_name3'] = $row_cust_detail['cus_name3'];
			$dataArray['cus_name4'] = $row_cust_detail['cus_name4'];
			$dataArray['cus_street'] = $row_cust_detail['cus_street'];
			$dataArray['cus_street2'] = $row_cust_detail['cus_street2'];
			$dataArray['cus_street3'] = $row_cust_detail['cus_street3'];
			$dataArray['cus_street4'] = $row_cust_detail['cus_street4'];
			$dataArray['cus_street5'] = $row_cust_detail['cus_street5'];
			$dataArray['cus_district'] = $row_cust_detail['cus_district'];
			$dataArray['cus_city'] = $row_cust_detail['cus_city'];
			$dataArray['cus_country'] = $row_cust_detail['country_desc'];
			$dataArray['cus_zipcode'] = $row_cust_detail['cus_zipcode'];
			$dataArray['cus_tax_nbr3'] = $row_cust_detail['cus_tax_nbr3'];
			$dataArray['cus_tax_nbr4'] = $row_cust_detail['cus_tax_nbr4'];
			$dataArray['cus_terms_paymnt'] = $row_cust_detail['term_desc'];
			$dataArray['cus_acc_group'] = $row_cust_detail['cus_acc_group'];
			array_push($data_cust_detail,$dataArray);
		}
	echo json_encode($data_cust_detail);

?>