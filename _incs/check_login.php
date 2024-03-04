<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/config.php"); 	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key,"")) {
		echo "System detect CSRF attack!!";
		exit;
	}
}
$user_login = strtoupper(mssql_escape($_POST['user_login']));
$user_enter_password = mssql_escape($_POST['user_passwd']);
$user_enter_encrypt_password = md5($user_login."+".$user_enter_password);

$params = array($user_login);
$sql_emp = "SELECT * FROM emp_mstr WHERE emp_user_id= ?"; //Check ว่าเป็นพนักงานหรือไม่
$result_emp = sqlsrv_query($conn, $sql_emp,$params);
$row_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);	

if (!$row_emp) { 
	$r="0";
	$errortxt = "คุณกรอก Username หรือ รหัสผ่าน ไม่ถูกต้อง";		
	$errortxt_enc = encrypt($msg,$key);
	$path ="./index.php?user=".$user_enc."&msg4=".$errortxt_enc;
} 
else 
{	//ถ้าเป็นพนักงาน
	$user_scg_emp_id = html_clear($row_emp["emp_scg_emp_id"]);
	$user_code = html_clear($row_emp["emp_scg_emp_id"]);
	$db_user_password = html_clear($row_emp["emp_user_password"]);
	$user_fullname = trim(html_clear($row_emp["emp_prefix_th_name"])) . trim(html_clear($row_emp["emp_th_firstname"])) . " " . trim(html_clear($row_emp["emp_th_lastname"]));

	$user_org_name = trim(html_clear($row_emp["emp_th_div"])) . "/" . trim(html_clear($row_emp["emp_th_dept"])) . "/" . trim(html_clear($row_emp["emp_th_sec"]));
	$user_status = html_clear($row_emp["emp_status_code"]); //-> 3 = active,0=denine
	$user_th_pos_name = html_clear($row_emp["emp_th_pos_name"]);
	$user_email = strtolower(html_clear($row_emp["emp_email_bus"]));
	$user_tel = html_clear($row_emp["emp_tel_bus"]);

	$user_com_code = html_clear($row_emp["emp_com_code"]);
	$user_password_date = $row_emp["emp_user_password_date"];
	$user_password_change_next_signon = html_clear($row_emp["emp_user_password_change_next_signon"]);
	$user_password_ldap = html_clear($row_emp["emp_user_password_ldap"]);
	$user_password_resetcode = html_clear($row_emp["emp_user_password_resetcode"]);

	$user_manager_name = html_clear($row_emp["emp_manager_name"]);
	$user_manager_email = html_clear($row_emp['emp_manager_email']);

	if (is_null($user_password_resetcode)) {
		$user_password_resetcode = "";
	}

	if ($row["emp_inform_last_action"] == '1') {
		$user_inform_last_action = true;
	} else {
		$user_inform_last_action = false;
	}
	
	if ($user_password_ldap) {
		$user_use_password_from = "LDAP";
	} else {
		$user_use_password_from = "LOCAL";
	}
	$allow_access = false;
	if ($user_use_password_from == "LOCAL") {
		if ($db_user_password != $user_enter_encrypt_password) {
			//$path = "index.php?msg=".encrypt("(LOCAL PWD) Invalid Username or Password!!",$key);
			$r = "0";
			$errortxt = "คุณกรอก Username หรือ รหัสผ่าน ไม่ถูกต้อง !!";	
			$errortxt_enc = encrypt($msg,$key);
			$path ="./index.php?user=".$user_enc."&msg1=".$errortxt_enc;
			$user_home = $path;
		} else {
			if ($user_status != '3') {
				//$path = "index.php?msg=".encrypt("(LOCAL PWD) User not active",$key);
				$r = "0";
				$errortxt = "User not active !!";
				$errortxt_enc = encrypt($msg,$key);
				$path ="./index.php?user=".$user_enc."&msg2=".$errortxt_enc;
				$user_home = $path;
			} else {
				/* if ($user_password_date == "") {
					$path = "../index.php?msg7=".encrypt("(LOCAL PWD) Contact admin for reset your password!!",$key);
					echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
				} else {
					$datetime1 = new DateTime(date_format($user_password_date, "Y-m-d"));
					$datetime2 = new DateTime(date("Y-m-d"));
					$user_password_date = date_format($user_password_date, "d/m/Y H:i:s");
					$user_password_age = $datetime1->diff($datetime2)->d;
					if ($user_password_change_next_signon == true || $user_password_age > $maxagepwd) {
						if ($user_password_age > $maxagepwd) {
							$msg = "คุณต้องเปลี่ยนรหัสผ่าน เนื่องจากรหัสผ่านของคุณมีอายุมากกว่า $maxagepwd วันค่ะ";
						} else {
							$msg = "มีการ RESET Password คุณต้องเปลี่ยนรหัสผ่านใหม่ค่ะ ";
						}

						setcookie("crctrl_user_login", encrypt($user_login,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
						setcookie("crctrl_user_fullname", encrypt($user_login,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);				
						$path = "../masmnt/pwdmnt.php?user_login=$user_login&msg=$msg";
						echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
					} else {
						$allow_access = true;
					}
				} */
				$allow_access = true;
			}
		}
	} else {
		if (trim($user_enter_password) != "") {
			$aduser = 'CEMENTHAI' . "\\" . $user_login;
			$server = 'ldap://172.30.53.91'; //Ip นี้ก็ใช้ได้แต่ใช้ชื่อจะดีกว่า
			//$server = 'cementhai.com'; //ใช้ได้บ้างไม่ได้บ้างเป็นเฉพาะที่ CTE
			$ldap = ldap_connect($server);
			if(!$ldap) {
				$errortxt = "Not connect Ldap server";
					
			} else {	
				ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
				$bind = @ldap_bind($ldap, $aduser, $user_enter_password);		
				if(!$bind) {			
					$errortxt = "(".ldap_errno($ldap).") " . ldap_error($ldap);
				} 
				else {
					$allow_access = true;
					ldap_unbind($ldap);
				}
			}
		} else {
			$r = "0";
			$errortxt = "คุณกรอก Username หรือ รหัสผ่าน ไม่ถูกต้อง !!";	
			$errortxt_enc = encrypt($msg,$key);
			$path ="./index.php?user=".$user_enc."&msg3=".$errortxt_enc;
			$user_home = $path;
		}
	}
	
	//If LDAP SUCCESS :: ALLOW ACCESS
	if ($allow_access) 
	{	
		$user_role = "";		
		$user_desc = "";
		$params = array($user_login);
		$sql_role = "SELECT role_code,role_desc FROM role_mstr WHERE role_user_login = ? and  role_active = '1'";
		$result_role = sqlsrv_query($conn, $sql_role,$params);
		while($row = sqlsrv_fetch_array($result_role, SQLSRV_FETCH_ASSOC)) {
			if ($user_role != "") { $user_role = $user_role . ",";}
			$user_role = $user_role . $row['role_code'];
		}
		if ($user_role == "") { $user_role = "NORMAL_USER"; }
	
		$expire=0;	
		
		setcookie("BkwNFcey_resu", encrypt($user_login,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie("BkwNFcey_eocd", encrypt($user_code,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie("BkwNFcey_elor", encrypt($user_role,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie("BkwNFcey_llmaneuf", encrypt($user_fullname,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

		setcookie("BkwNFcey_dipme", encrypt($user_scg_emp_id,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie("BkwNFcey_gro", encrypt($user_org_name,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie("BkwNFcey_sopmane", encrypt($user_th_pos_name,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie("BkwNFcey_namag", encrypt($user_manager_name,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie("BkwNFcey_elimana", encrypt($user_manager_email,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie("BkwNFcey_eli", encrypt($user_email,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		setcookie("BkwNFcey_let", encrypt($user_tel,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
		
		$r="1";
		
		if (inlist($user_role,"ADMIN") || inlist($user_role,"SALE_VIEW")) 
		{
			$path = "dashboard/dashboard-project.php?msg=".encrypt("You are  authorized to access.",$key);
			$user_home = $path;
		}
		 else if (inlist($user_role,"Display_View") || inlist($user_role,"FinCR Mgr") || inlist($user_role,"Action_View1") || inlist($user_role,"Action_View2"))
		{
			$path = "dashboard/dashboard-project.php?msg=".encrypt("You are  authorized to access.",$key);
			//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
			$user_home = $path;
		}
		else
		{
			$path = "index.php?msg=".encrypt("You are not allowed. Please sign in again.",$key);
			//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
			$user_home = $path;
		}
		
			$user_ip = get_client_ip();			
			$sql_login = "insert into login_trans (log_user_login,log_user_code,log_user_role,log_user_email,log_date,log_ip,log_remark) values(?,?,?,?,?,?,?)";
			$params_login = array($user_login,$user_scg_emp_id,$user_role,$user_email,date("Y-m-d H:i:s"),$user_ip,"CRREC");
			$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$result_login = sqlsrv_query( $conn, $sql_login , $params_login, $options );
	}
	else {
		$r="0";
		$user_home = "";
	}
}
echo '{"r":"'.$r.'","e":"'.$errortxt.'","home":"'.$user_home.'"}';
?>