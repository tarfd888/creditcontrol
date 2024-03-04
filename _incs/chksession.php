<?php	
	$inipath = php_ini_loaded_file();
	$ini_array = parse_ini_file($inipath , true);
	$smtp=$ini_array["mail function"]["SMTP"];
	$dbkey=$ini_array["dbaccess"]["dbkey"];
	$key=$dbkey;	

	$user_login = html_escape0(decrypt0($_COOKIE['BkwNFcey_resu'], $key));
	$user_code = html_escape0(decrypt0($_COOKIE['BkwNFcey_eocd'], $key));
	$user_role = html_escape0(decrypt0($_COOKIE['BkwNFcey_elor'], $key));
	$user_fullname = html_escape0(decrypt0($_COOKIE['BkwNFcey_llmaneuf'], $key));
	$user_email = html_escape0(decrypt0($_COOKIE['crctrl_user_email'], $key));
	$user_org_name = html_escape0(decrypt0($_COOKIE['BkwNFcey_gro'], $key));
	$user_th_pos_name = html_escape0(decrypt0($_COOKIE['BkwNFcey_sopmane'], $key));
	$user_manager_name = html_escape0(decrypt0($_COOKIE['BkwNFcey_namag'], $key));
	$user_manager_email = html_escape0(decrypt0($_COOKIE['BkwNFcey_elimana'], $key));
	$user_email = html_escape0(decrypt0($_COOKIE['BkwNFcey_eli'], $key));
	$user_tel = html_escape0(decrypt0($_COOKIE['BkwNFcey_let'], $key));
	$user_scg_emp_id = html_escape0(decrypt0($_COOKIE['BkwNFcey_dipme'], $key));

	if (!isset($_COOKIE['BkwNFcey_resu']) || decrypt0($_COOKIE['BkwNFcey_resu'], $key) == "") {	
		echo "Please login before!!";
		exit;
	}		

	function html_escape0($value) {
		$v = $value;
		if (is_null($v) || $v == "") {
			return "";
		}
		else {
			return htmlspecialchars($v,ENT_QUOTES);
		}
	}
	function decrypt0($encrypted,$txtkey) {
		$encrypt_method = "AES-256-CBC";
		$secret_key = $txtkey;
		$secret_iv = $txtkey;
		// hash
		$key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		//$output = strtr(openssl_decrypt(base64_decode($encrypted), $encrypt_method, $key, 0, $iv),'-_,', '+/=');
		$output = openssl_decrypt(base64_decode($encrypted), $encrypt_method, $key, 0, $iv);
		return $output;
	}
?>