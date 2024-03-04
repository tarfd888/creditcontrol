<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	include "../_libs/SimpleImage/simpleimage.php";
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack8!!";
			exit;
		}
	}
	else {
		echo "Allow for POST Only";
		exit;
	}
	$params = array();
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Y-m-d H:i:s");
	$curr_date = ymd(date("d/m/Y"));
	$errortxt = "";
	$allow_post = false;
	$action = html_escape($_POST['action']);
    $crstm_nbr = html_escape($_POST['crstm_nbr']);
	$crstm_step_code = "30"; // Status to FinCR Mgr
    $crstm_step_name = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_step_code, $conn);
    //$pictureOriginal = findsqlval("crstm_mstr", "crstm_cr1_img", "crstm_nbr", $cr1_id ,$conn);
if (inlist("recall",$action)) {	
		$params = array($crstm_nbr);
		//if (inlist($user_role,"Action_View1,Action_View2")) { 		
			$sql_edit = "UPDATE crstm_mstr SET ".
			" crstm_step_code = '$crstm_step_code' ,".
            " crstm_step_name = '$crstm_step_name' ,".
			" crstm_update_by = '$user_login' ,".
			" crstm_update_date = '$today' ".
			" WHERE crstm_nbr = ? ";
		//}	
		$result_edit = sqlsrv_query($conn,$sql_edit,$params, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if($result_edit) {
				$r="1";
				$errortxt="Recall-Successfull.";
				$nb=encrypt($crstm_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="Recall-fail.";
			}
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';			
	}
?>