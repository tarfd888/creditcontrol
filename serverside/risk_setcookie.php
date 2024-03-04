<?php
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
set_time_limit(0);
$result = new stdClass();
$result->success = FALSE;
$params = json_decode(filter_input(INPUT_POST, "param0"), TRUE);
				
setcookie ("cus_code", encrypt($params["cus_code"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("up_year", encrypt($params["up_year"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

$result->success = TRUE;
echo json_encode($result);
?>