<?php 
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/funcCrform.php");
include("../_incs/funcAppform.php");
include("../_incs/acunx_csrf_var.php");
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_metaheader.php");

	$data = $_POST;
	$crstm_email_app = $_POST['crstm_email_app'];
	$app1_name = $_POST['app1_name'];
	
	echo "<pre>";
	var_dump($data);
	echo "<hr>";
	var_dump($crstm_email_app);
	echo "<hr>";
	var_dump($app1_name);
	echo "<hr>";	
	echo($crstm_email_app[0]);
	echo "<hr>";	
	echo count($crstm_email_app);
	
	$count_array = count($crstm_email_app);
	
 	 // for($x = 0; $x < $count_array; $x++) {	
		// $sql = "INSERT INTO  ta_test (email,name) VALUES('$crstm_email_app[$x]','$app1_name[$x]')";
		// $result = sqlsrv_query($conn, $sql);
	// }	
	
		$sql = "INSERT INTO  ta_test (email,name, email1,name1, email2,name2, email3,name3) VALUES('$crstm_email_app[0]','$app1_name[0]', '$crstm_email_app[1]','$app1_name[1]', '$crstm_email_app[2]','$app1_name[2]', '$crstm_email_app[3]','$app1_name[3]')";
		$result = sqlsrv_query($conn, $sql);
	
		
		