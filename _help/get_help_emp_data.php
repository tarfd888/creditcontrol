<?php
	include("../_incs/acunx_metaheader.php");
	//include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	
	$result = new stdClass();
	$result->success = FALSE;
	$params = json_decode(filter_input(INPUT_POST, "param0"), TRUE);
	
    $result = getData($conn, $result, $params );
	echo json_encode($result);

	function getData($conn, $result, $params) {
		$result_row = array();
		$query_params = array();
		
		//get emp
		//$sql = "SELECT emp_scg_emp_id, emp_th_firstname+' '+emp_th_lastname + ' (' + emp_en_firstname + ' ' + emp_en_lastname + ')' as 'emp_fullname',LOWER(emp_email_bus) AS emp_email_bus ,emp_th_div+'/'+emp_th_dept+'/'+emp_th_sec as 'emp_dept',emp_th_firstname+' '+emp_th_lastname+'('+emp_th_pos_name+')' as 'emp_fullnamepos',  emp_th_pos_name as emp_th_pos_name FROM emp_mstr WHERE emp_status_code  = '3'";
		$sql = "SELECT emp_scg_emp_id, emp_user_id, emp_manager_scg_emp_id, emp_manager_scg_emp_id, emp_th_firstname+' '+emp_th_lastname + ' (' + emp_en_firstname + ' ' + emp_en_lastname + ')' as 'emp_fullname',LOWER(emp_email_bus) AS emp_email_bus ,emp_manager_scg_emp_id+'/'+emp_manager_name+'/'+emp_manager_pos_name as 'emp_dept',emp_prefix_th_name+' '+emp_th_firstname+' '+emp_th_lastname+'('+emp_th_pos_name+')' as 'emp_fullnamepos',  emp_th_pos_name as emp_th_pos_name FROM emp_mstr WHERE emp_status_code  = '3'";

		$query = sqlsrv_query($conn, $sql);
		while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
			array_push($result_row, $row);
		}

		//$result->data = $result_row;
		//$result->success = TRUE;
		//return $result;
		
		return $result_row;
	}
