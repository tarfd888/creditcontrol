<?php
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
set_time_limit(0);
$result = new stdClass();
$result->success = FALSE;
$params = json_decode(filter_input(INPUT_POST, "param0"), TRUE);
				
setcookie ("crmnt_approve", encrypt($params["crstm_approve"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("crmnt_cc_amt", encrypt($params["crstm_cc_amt"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("crmnt_cc_amt1", encrypt($params["crstm_cc_amt1"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("crmnt_date", encrypt($params["crstm_date"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("crmnt_date1", encrypt($params["crstm_date1"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("crmnt_beg_date", encrypt($params["crmnt_beg_date"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("crmnt_end_date", encrypt($params["crmnt_end_date"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("crmnt_step_name", encrypt($params["crmnt_step_name"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
setcookie ("crmnt_cus_nbr", encrypt($params["crmnt_cus_nbr"],$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

$result->success = TRUE;
echo json_encode($result);
?>