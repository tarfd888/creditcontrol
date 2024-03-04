<?	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");

	
	$result = new stdClass();
	$result->success = FALSE;
	$params = json_decode(filter_input(INPUT_POST, "param0"), TRUE);
  	$result = getData($conn, $result, $params, $key);
	echo json_encode($result);

	function getData($conn, $result, $params, $key) {
		$uploadpath = "../_fileuploads/ac_risk/";	
		$pathicon = "../_fileuploads/icon/";	
	
		$cus_code = $params["cus_code"];
		$up_year = $params["up_year"];

		$result_row = array();
		$query_params = array();

		if ($cus_code != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $cus_code);
			$criteria = $criteria . " risk_cust_nbr = ?";
		}
		if ($up_year != "") {
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $up_year);
			$criteria = $criteria . " risk_year = ?";
		}
		
		if ($criteria != "") {
			$criteria = " WHERE " . $criteria . " ";
		}
		
		$sql =  "SELECT risk_id, risk_tem_nbr, risk_cust_nbr, risk_name, risk_description, risk_year, risk_create_by, risk_create_date, risk_update_by, risk_update_date, risk_check_status
				FROM  risk_mstr	$criteria ORDER BY risk_id desc";	

		$query = sqlsrv_query($conn, $sql, $query_params);
		while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
			$data['risk_id'] = html_escape($row['risk_id']);
			$data['risk_cust_nbr'] = html_escape($row['risk_cust_nbr']);
			$fname = html_escape($row['risk_name']);
			$data['risk_fname'] = html_escape($row['risk_name']);
			$data['risk_year'] = html_escape($row['risk_year']);
			$risk_cust_code = html_escape($row['risk_cust_nbr']);
			$data['risk_name'] = findsqlval("cus_mstr","cus_name1","cus_nbr",$risk_cust_code,$conn);

			$info_img = pathinfo( $fname , PATHINFO_EXTENSION ) ;	
			switch ($info_img) {
			  case "pdf":
				$data['Image'] = "$uploadpath"."$fname";
				$Image_icon =  "$pathicon"."pdf.png";
				$data['Image_icon'] = $Image_icon;
				break;
			  case "xls":
			  case "xlsx":  
				$data['Image'] = "$uploadpath"."$fname";
				$Image_icon = "$pathicon"."excel.png";
				$data['Image_icon'] = $Image_icon;
				break;
			  case "doc":
			  case "docx":  
				$data['Image'] = "$uploadpath"."$fname";
				$Image_icon = "$pathicon"."word.png";
				$data['Image_icon'] = $Image_icon;
				break;      
			  default:
			  if($fname=="") {
				$data['Image'] = "$pathicon"."nopicture.png";
				$data['Image_icon'] = "$pathicon"."nopicture.png";
				}else {
					$data['Image']= "$uploadpath"."$fname";
					$data['Image_icon'] = "$uploadpath"."$fname";
			  }
			}	
			array_push($result_row, $data);
		}	
		$result->data = $result_row;
		$result->success = TRUE;
		return $result;
	}			
?>