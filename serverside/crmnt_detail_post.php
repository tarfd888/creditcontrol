<?php
	//Update can_editing
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	set_time_limit(0);

	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack666!!";
			exit;
		}
	}
	else {
		echo "Allow for POST Only";
		exit;
	}
	$params = array();
	
	date_default_timezone_set('Asia/Bangkok');
	
	$today = date("Y-m-d H:i:s");  	
	$pg = html_escape($_REQUEST['pg']);
	$action = html_escape($_POST['action']);
	$crstm_nbr = mssql_escape($_POST['crstm_nbr']);
	
	//INPUT VALIDATION
	$errorflag = false;
	$errortxt = "";
	
	if ($action == "link_cr") {

		$crstm_nbr = mssql_escape($_POST['crstm_nbr']);
		$pg = mssql_escape($_POST['pg']);
		$params_cr_link = array();
		
		
		
		$sql_cr_link = "select count(*) as rowCounts from  crstm_mstr where crstm_nbr = ?";	
		$params_cr_link = array($crstm_nbr);
		$result_cr_link = sqlsrv_query($conn,$sql_cr_link,$params_cr_link, array( "Scrollable" => 'keyset' ));
		$rowCounts_cr_link = sqlsrv_num_rows($result_cr_link);
	
		if ($result_cr_link) {
			if($rowCounts_cr_link >0)
			{
				$r="1";
				$errortxt="Link Success.";
				$nb=encrypt($crstm_nbr, $key);
			}
			else
			{
				if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
					if (!matchToken($csrf_key,$user_login)) {
						echo "System detect CSRF attack!!";
						exit;
					}
				}
			}
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Link fail.";
		}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	
	if ($action == "prt_cr") {

		$crstm_nbr = mssql_escape($_POST['crstm_nbr']);
		$pg = mssql_escape($_POST['pg']);
		$params_cr_link = array();
		
		
		
		$sql_cr_link = "select count(*) as rowCounts from  crstm_mstr where crstm_nbr = ?";	
		$params_cr_link = array($crstm_nbr);
		$result_cr_link = sqlsrv_query($conn,$sql_cr_link,$params_cr_link, array( "Scrollable" => 'keyset' ));
		$rowCounts_cr_link = sqlsrv_num_rows($result_cr_link);
		$crstm_approve = findsqlval(" crstm_mstr", "crstm_approve", "crstm_nbr", $crstm_nbr ,$conn);
		$pg=encrypt($crstm_approve, $key);

	
		if ($result_cr_link) {
			if($rowCounts_cr_link >0)
			{
				$r="1";
				$errortxt="Success.";
				$nb=encrypt($crstm_nbr, $key);
			}
			else
			{
				if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
					if (!matchToken($csrf_key,$user_login)) {
						echo "System detect CSRF attack!!";
						exit;
					}
				}
			}
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Link fail.";
		}
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}
	

?> 