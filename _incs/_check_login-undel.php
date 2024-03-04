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

////////////// Click เปิดเอกสารจากอีเมล ///////////////////////////
$auth_enc = html_escape($_POST['id']);
$auth = strtoupper(decrypt($auth_enc, $dbkey)); //return value is approver
$nbr_enc = mssql_escape($_POST['nbr']);

$user_login = strtoupper(mssql_escape($_POST['user_login']));
$user_enter_password = mssql_escape($_POST['user_passwd']);
$user_enter_encrypt_password = md5($user_login."+".$user_enter_password);

$params = array($user_login);
$sql_emp = "SELECT * FROM emp_mstr WHERE emp_user_id= ?"; //Check ว่าเป็นพนักงานหรือไม่
$result_emp = sqlsrv_query($conn, $sql_emp,$params);
$row_emp = sqlsrv_fetch_array($result_emp, SQLSRV_FETCH_ASSOC);	

if (!$row_emp) { //ถ้าไม่ใช่พนักงาน ให้เช็คว่า  SUPPLIER ไหม
	$path = "index.php?msg=".encrypt("This username is not found!!",$key);
	//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	$user_home = $path;
	$res = "0";
	$errortxt = "This username is not found!!";
} 
else 
{	//ถ้าเป็นพนักงาน
	$user_scg_emp_id = $row_emp["emp_scg_emp_id"];
	$user_code = $row_emp["emp_scg_emp_id"];
	$db_user_password = $row_emp["emp_user_password"];
	$user_fullname = trim($row_emp["emp_prefix_th_name"]) . trim($row_emp["emp_th_firstname"]) . " " . trim($row_emp["emp_th_lastname"]);

	$user_org_name = trim($row_emp["emp_th_div"]) . "/" . trim($row_emp["emp_th_dept"]) . "/" . trim($row_emp["emp_th_sec"]);
	$user_status = $row_emp["emp_status_code"]; //-> 3 = active,0=denine
	$user_th_pos_name = $row_emp["emp_th_pos_name"];
	$user_email = strtolower($row_emp["emp_email_bus"]);
	$user_tel = $row_emp["emp_tel_bus"];

	$user_com_code = $row_emp["emp_com_code"];
	$user_password_date = $row_emp["emp_user_password_date"];
	$user_password_change_next_signon = $row_emp["emp_user_password_change_next_signon"];
	$user_password_ldap = $row_emp["emp_user_password_ldap"];
	$user_password_resetcode = $row_emp["emp_user_password_resetcode"];

	$user_manager_name = $row_emp["emp_manager_name"];
	$user_manager_email = $rec_emp['emp_manager_email'];

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
			$path = "index.php?msg=".encrypt("(LOCAL PWD) Invalid Username or Password!!",$key);
			//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
			$user_home = $path;
			$res = "0";
			$errortxt = "Invalid Username or Password!!";
		} else {
			if ($user_status != '3') {
				$path = "index.php?msg=".encrypt("(LOCAL PWD) User not active",$key);
				//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
				$user_home = $path;
				$res = "0";
				$errortxt = "User not active !!";
			} else {
				$allow_access = true;
			}
		}
	} else {
		if (trim($user_enter_password) != "") {
			/**
			การใช้งานในอนาคตต้องลง extionsion ldap ที่ server และต้องเช็ควิธีการ connect ldap scg อีกครั้ง
			เปิดใช้งาน LDAP แล้วแต่จะมีผลกับคนที่ใช้ password LDAP เท่านั้น
			 **/
			$aduser = 'CEMENTHAI' . "\\" . $user_login;
			$server = 'ldap://172.30.53.91'; //Ip นี้ก็ใช้ได้แต่ใช้ชื่อจะดีกว่า

			//$server = 'cementhai.com';
			$ldap = ldap_connect($server);
			if (!$ldap) {
				die("Not connect to LDAP " . $server);
				echo "Not connect Ldap server ";
				exit();
				} else {
				ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
				$bind = @ldap_bind($ldap, $aduser, $user_enter_password);
				if (!$bind) {
					$errors = "(" . ldap_errno($ldap) . ") " . ldap_error($ldap);
					$path = "index.php?msg=(LDAP) $errors";
					//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
					$user_home = $path;
					$res = "0";
					$errortxt = "$errors";
				} else {
					$allow_access = true;
					ldap_unbind($ldap);
				}
			}
		} else {
			$errors = "กรุณาระบุ Password ค่ะ";
			$path = "index.php?msg=(LDAP) $errors";
			//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
			$user_home = $path;
			$res = "0";
			$errortxt = "กรุณาระบุ Password ค่ะ";
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
		if($user_role =="") {
			$r="0";
			$errortxt = "คุณไม่ได้รับสิทธิ ให้เข้าใช้งานระบบ";
		}
		else {
			$expire=0;	
			setcookie("crctrl_user_login", encrypt($user_login,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_code", encrypt($user_code,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_role", encrypt($user_role,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_fullname", encrypt($user_fullname,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_desc", encrypt($user_desc,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_org_name", encrypt($user_org_name,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_th_pos_name", encrypt($user_th_pos_name,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_manager_name", encrypt($user_manager_name,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_manager_email", encrypt($user_manager_email,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_email", encrypt($user_email,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			setcookie("crctrl_user_tel", encrypt($user_tel,$key),$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
			
			$r="1";
			//เช็คว่าถ้าคลิ๊กเปิดเอกสารจากอีเมล ให้ Redirect ไปยัง Document เลขนั้นเลย			
			if ($nbr_enc =="") {
				//เป็นการ login มาจากหน้า login
				
				if (inlist($user_role,"ADMIN") || inlist($user_role,"SALE_VIEW")) 
				{
					$path = "crctrlbof/crctrlall.php";
					$user_home = $path;
				}
				 else if (inlist($user_role,"Display_View") || inlist($user_role,"FinCR Mgr") || inlist($user_role,"Action_View1") || inlist($user_role,"Action_View2"))
				{
					$path = "crctrlbof/crctrlall.php";
					$user_home = $path;
				}
			//else
			//{
				//$path = "index.php?msg=".encrypt("You are not allowed. Please sign in again.",$key);
				//$user_home = $path;
			}
			
			
			else {
				//เป็นการ login มาจาก email
				if ($user_login == $auth) {
					$cr_nbr = decrypt($nbr_enc, $dbkey);
					$params = array($cr_nbr);
					$sql = "SELECT * from crstm_mstr where crstm_nbr = ? and (crstm_step_code = '110' or crstm_step_code = '50')";
					$result = sqlsrv_query($conn, $sql,$params);	
					$r_row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
					if ($r_row) {
						$crstm_curprocessor = $r_row['crstm_curprocessor'];
						$crstm_step_code = $r_row['crstm_step_code'];
						if (inlist($crstm_curprocessor,$user_login)) {
							$path = "crctrlbof/crctrlall_app_reviewer.php?crnbr=".encrypt($cr_nbr, $dbkey);
							//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
							$user_home = $path;
						}
						else {
							//$path = "index.php?doc=".$nbr_enc."&auth=".$auth_enc."&msg=".encrypt('คุณไม่มีสิทธืเข้าถึงเอกสารหมายเลขนี้!! ',$key);
							$path = "index.php?doc=".$nbr_enc."&auth=".$auth_enc."&msg=".('คุณไม่มีสิทธืเข้าถึงเอกสารหมายเลขนี้!! ');
							$user_home = $path;
						}
					}
					else {
						//$path = "index.php?doc=".$nbr_enc."&auth=".$auth_enc."&msg=".encrypt('ไม่พบเอกสารในสถานะรออนุมัติค่ะ!!',$key);
						$path = "index.php?doc=".$nbr_enc."&auth=".$auth_enc."&msg=".('ไม่พบเอกสารในสถานะรออนุมัติค่ะ!!');
						$user_home = $path;
					}
				}
				else {
					//$path = "index.php?doc=".$nbr_enc."&auth=".$auth_enc."&msg=".encrypt('ท่านไม่ใช้ผู้อนุมัติเอกสารฉบับนี้!!',$key);
					$path = "index.php?doc=".$nbr_enc."&auth=".$auth_enc."&msg=".('ท่านไม่ใช้ผู้อนุมัติเอกสารฉบับนี้!!');
					$user_home = $path;
				}
			}	
		}
			$user_ip = get_client_ip();			
			$sql_login = "insert into login_trans (log_user_login,log_user_code,log_user_role,log_user_email,log_date,log_ip,log_remark) values(?,?,?,?,?,?,?)";
			$params_login = array($user_login,$user_scg_emp_id,$user_role,$user_email,date("Y-m-d H:i:s"),$user_ip,"CRREC");
			$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$result_login = sqlsrv_query( $conn, $sql_login , $params_login, $options );
	}	
	//}
	else {
		$r="0";
		$user_home = "";
	}
}
echo '{"res":"'.$r.'","err":"'.$errortxt.'","home":"'.$user_home.'"}';
?>