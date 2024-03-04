<?php 
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
	$cur_pass = $_POST["cur_pass"];	
	$user_enter_encrypt_password = md5($user_login."+".$cur_pass."+".$dbkey);
	$user_enter_encrypt_password = substr($user_enter_encrypt_password,0,16);
	
	$vip = "SOURCING";
	
	$sql_record  = "select top 1 vip_resetcode from vip_mstr where vip_username = ?";
	$params = [$vip];
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$result_record = sqlsrv_query( $conn, $sql_record , $params, $options );
	$row_counts = sqlsrv_num_rows( $result_record );
	
	if ($row_counts === false)
		{
			echo 1;
		}
	else 
		{
			if($row_counts == 0)
			{
				echo 1;
			}
			else
			{
				$row = sqlsrv_fetch_array($result_record, SQLSRV_FETCH_ASSOC);
				$user_db_encrypt_password = md5($user_login."+".$row['vip_resetcode']."+".$dbkey);
				$user_db_encrypt_password = substr($user_db_encrypt_password,0,16);
				if ($user_enter_encrypt_password != $user_db_encrypt_password) {	
					echo 1;
				}
				else {
					echo 0;
				}
			}				
		}					
?>