<?php
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
set_time_limit(0);
$result = new stdClass();
$result->success = FALSE;
$params = json_decode(filter_input(INPUT_POST, "param0"), TRUE);
				
setcookie ("cus_app_nbr", encrypt($params["cus_app_nbr"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("cus_date", encrypt($params["cus_date"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("cus_cond_cust", encrypt($params["cus_cond_cust"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("cusd_op_app", encrypt($params["cusd_op_app"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("cus_code", encrypt($params["cus_code"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("cusstep_name_en", encrypt($params["cusstep_name_en"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);


setcookie ("start", encrypt($params["start"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("end", encrypt($params["end"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

$result->success = TRUE;
echo json_encode($result);
?>