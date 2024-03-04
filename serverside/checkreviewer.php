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
	
			$check_flag =  html_escape($_REQUEST["q"]);
			$check_flag = html_escape(decrypt($check_flag, $key));
			
			$crstm_approve =  html_escape($_REQUEST["group"]);
			//$crstm_approve = html_escape(decrypt($check_form, $key));
			
				$params = array($check_flag);
				$query_emp_detail = "SELECT * FROM reviewer_mstr where emp_flag = ?";
				$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
				$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
				if ($rec_emp) {
					// $emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
					// $emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
					// $emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
					// $emp_th_div = html_clear($rec_emp['emp_th_div']);
					// $emp_th_dept = html_clear($rec_emp['emp_th_dept']);
					// $emp_th_sec = html_clear($rec_emp['emp_th_sec']);
					// $emp_th_pos_name = html_clear($rec_emp['emp_th_pos_name']);
					// $reviewer_pos2 = "(". $emp_th_pos_name .")" ;
					$email_reviewer2 = html_clear(strtolower($rec_emp['emp_email_bus']));
					$pointer_vie2 = "none";
					$pointer2 = 'none';
				
					if ($check_flag == "1"){
							$fle = 'author_mstr';
					}
					else {
						$fle = 'author_g_mstr';
						if ($crstm_approve == 'คณะกรรมการสินเชื่ออนุมัติ' || $crstm_approve == 'คณะกรรมการบริหารอนุมัติ' ){
							$reviewer_name2 = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$email_reviewer2,$conn);	
							$crstm_reviewer2 = $email_reviewer2;
						}
					}

						$params = array($crstm_approve);
						$sql = "SELECT  author_id, author_seq, author_code, author_group, author_sign_nme, author_sign, author_position, author_email, author_email_status, author_active, author_text, author_salutation, ".
						"financial_amt_beg, financial_amt_end, account_group, author_create_date, author_create_by, author_update_date, author_update_by, author_remark ".
						"FROM $fle WHERE (author_text = ?) and account_group='ZC01' and author_active='1' order by author_no";
							$result = sqlsrv_query($conn, $sql,$params);  				
				 
							$showEmail = array();
							$showName = array();
							//$count = 0;
							$no = 0;
						
							 while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
							 {
								 //$count = $no+1;
								 $crstm_email_app[$no] = $row['author_email'];            
								 $app_name[$no]  = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app[$no],$conn);	
								 $no++;    
								}    
					
					//echo '{"reviewer":"'.$reviewer_name2.'","email":"'.$crstm_reviewer2.'","email1":"'.$crstm_email_app1.'","app1_name":"'.$app1_name.'","email2":"'.$crstm_email_app2.'","app2_name":"'.$app2_name.'"}';
					echo '{"reviewer":"'.$reviewer_name2.'","email":"'.$crstm_reviewer2.'","email1":"'.$crstm_email_app[0].'","app1_name":"'.$app_name[0] .'","email2":"'.$crstm_email_app[1].'","app2_name":"'.$app_name[1].'","email3":"'.$crstm_email_app[2].'","app3_name":"'.$app_name[2].'"}';
					$r="1";
				}		
?>