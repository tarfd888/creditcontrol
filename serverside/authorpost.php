<?php 
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";

	// if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		// if (!matchToken($csrf_key,$user_login)) {
			// echo "System detect CSRF attack!!";
			// exit;
		// }
	// }
	// else {
		// echo "Allow for POST Only";
		// exit;
	// }
	
	clearstatcache();
	date_default_timezone_set('Asia/Bangkok');
	$today = date("Ymd");
	$todaytime = date("Y-m-d H:i:s");
	$params = array();
	$errortxt = "";
	$errorflag = false;
	$allow_post = false;
	
			$crstm_approve =  html_escape($_REQUEST["q"]);
			$check_flag =  html_escape($_REQUEST["check_flag"]);
			$check_flag = html_escape(decrypt($check_flag, $key));
			//$crstm_approve = html_escape(decrypt($crstm_approve, $key));
			
			if ($crstm_approve == "") {
				$crstm_email_app1 = "";
				$finishdate = "";
				$errortxt="";
				$r="1";
			}
			else {
				switch ($crstm_approve) {
				case "ผส. อนุมัติ":
					$crstm_email_app1 = "";
					$app1_name = "";
					echo '{"email1":"'.$crstm_email_app1.'","app1_name":"'.$app1_name.'","app2_name":"'.$app2_name.'"}';
					$errortxt="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
					$r="1";
					break;
				case "ผฝ. อนุมัติ":
					$crstm_email_app1 = "";
					$app1_name = "";
					echo '{"email1":"'.$crstm_email_app1.'","app1_name":"'.$app1_name.'","app2_name":"'.$app2_name.'"}';
					$errortxt="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
					$r="1";
					break;
				case "CO. อนุมัติ":
					if ($check_flag == "1"){
						$crstm_email_app1 = findsqlvalfirst("author_mstr", " author_email", " author_text", $crstm_approve ,$conn);
						$app1_name = findsqlvalfirst("author_mstr", " author_position", " author_text", $crstm_approve ,$conn);
					}else{
						$crstm_email_app1 = findsqlvalfirst("author_g_mstr", " author_email", " author_text", $crstm_approve ,$conn);
						$app1_name = findsqlvalfirst("author_g_mstr", " author_position", " author_text", $crstm_approve ,$conn);
					}
					echo '{"email1":"'.$crstm_email_app1.'","app1_name":"'.$app1_name.'","app2_name":"'.$app2_name.'"}';
					$errortxt="";
					$r="1";
					break;
				case "กจก. อนุมัติ":
					if ($check_flag == "1"){
						$crstm_email_app1 = findsqlvalfirst("author_mstr", " author_email", " author_text", $crstm_approve ,$conn);
						$app1_name = findsqlvalfirst("author_mstr", " author_position", " author_text", $crstm_approve ,$conn);
					}else{
						$crstm_email_app1 = findsqlvalfirst("author_g_mstr", " author_email", " author_text", $crstm_approve ,$conn);
						$app1_name = findsqlvalfirst("author_g_mstr", " author_position", " author_text", $crstm_approve ,$conn);
					}
					echo '{"email1":"'.$crstm_email_app1.'","app1_name":"'.$app1_name.'","app2_name":"'.$app2_name.'"}';
					$errortxt="";
					$r="1";
					break;
				case "คณะกรรมการสินเชื่ออนุมัติ":
					if ($check_flag == "1"){
						$crstm_email_app1 = findsqlvalfirst("author_mstr", " author_email", " author_text", $crstm_approve ,$conn);
						$app1_name = findsqlvalfirst("author_mstr", " author_position", " author_text", $crstm_approve ,$conn);
						$crstm_email_app2 = findsqlvallast("author_mstr", "author_email", " author_text", $crstm_approve ,$conn);
						$app2_name = findsqlvallast("author_mstr", "author_position", " author_text", $crstm_approve ,$conn);
					}else{
						$crstm_email_app1 = findsqlvalfirst("author_g_mstr", " author_email", " author_text", $crstm_approve ,$conn);
						$app1_name = findsqlvalfirst("author_g_mstr", " author_position", " author_text", $crstm_approve ,$conn);
						$crstm_email_app2 = findsqlvallast("author_g_mstr", "author_email", " author_text", $crstm_approve ,$conn);
						$app2_name = findsqlvallast("author_g_mstr", "author_position", " author_text", $crstm_approve ,$conn);
					}
					echo '{"email1":"'.$crstm_email_app1.'","app1_name":"'.$app1_name.'","email2":"'.$crstm_email_app2.'","app2_name":"'.$app2_name.'"}';
					$errortxt="";
					$r="1";
					break;
				//default:	
				case "คณะกรรมการบริหารอนุมัติ":
					if ($check_flag == "1"){
						$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
						$app1_name = findsqlvalfirst("author_mstr", "author_position", "author_text", $crstm_approve ,$conn);
						$crstm_email_app2 = findsqlvallast("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
						$app2_name = findsqlvallast("author_mstr", "author_position", "author_text", $crstm_approve ,$conn);
					}else{
						$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_code", 'CFO' ,$conn);
						$app1_name = findsqlvalfirst("author_mstr", "author_position", "author_code", 'CFO' ,$conn);
						$crstm_email_app2 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
						$app2_name = findsqlvalfirst("author_mstr", "author_position", "author_text", $crstm_approve ,$conn);
					}
					echo '{"email1":"'.$crstm_email_app1.'","app1_name":"'.$app1_name.'","email2":"'.$crstm_email_app2.'","app2_name":"'.$app2_name.'"}';
					$errortxt="";
					$r="1";
					break;
				}
				
			}
		
?>