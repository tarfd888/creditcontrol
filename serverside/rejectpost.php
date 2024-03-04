<?php
	//Update can_editing
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
	$crstm_step_code = "42"; // cancel
    $crstm_step_name = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_step_code, $conn);
    //$pictureOriginal = findsqlval("crstm_mstr", "crstm_cr1_img", "crstm_nbr", $cr1_id ,$conn);
	if (inlist("reject",$action)) {	
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
				$errortxt="Cancel success.";
				$nb=encrypt($crstm_nbr, $key);
			}
			else {
				$r="0";
				$nb="";
				$errortxt="Cancel fail.";
			}
				echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}

	if (inlist("reject_cus",$action)) {	
		$cus_app_nbr = html_escape($_POST['cus_app_nbr']);
		$cus_step_code = html_escape($_POST['cus_step_code']);
		$cus_ap_remark = html_escape($_POST['cus_reject_rem']);
		$cus_reject_code = "32"; // cancel

		$params = array($cus_app_nbr);
		$sql_edit = "UPDATE cus_app_mstr SET ".
		" cus_step_code = '$cus_reject_code' ,".
		" cus_update_by = '$user_login' ,".
		" cus_update_date = '$today' ".
		" WHERE cus_app_nbr = ? ";
		$result_edit = sqlsrv_query($conn,$sql_edit,$params, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

		$params = array($cus_app_nbr);
		$sql_edit_cr = "UPDATE cr_app_mstr SET ".
		" cr_flag = 'CC' ,".
		" cr_update_by = '$user_login' ,".
		" cr_update_date = '$today' ".
		" WHERE cr_app_nbr = ? ";
		$result_edit_cr = sqlsrv_query($conn,$sql_edit_cr,$params, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

		if($result_edit_cr) {
			// เช็คคนอนุมัติก่อนหน้านี้
			$params = array($cus_app_nbr);
			$query_apprv = "SELECT top 1 * FROM cus_approval where cus_ap_nbr = ?  and cus_ap_active = '1' order by cus_ap_id desc";

			$result_apprv_detail = sqlsrv_query($conn, $query_apprv,$params);
			$row_app = sqlsrv_fetch_array($result_apprv_detail, SQLSRV_FETCH_ASSOC);
			if ($row_app) {
				$cus_ap_f_step = $row_app['cus_ap_t_step_code'];
				$cus_ap_t_step = $cus_reject_code; // เอกสารยกเลิก
			} 

			//เก็บประวัติการดำเนินการ
			$cus_ap_text = "เจ้าหน้าที่สินเชื่อ "."ยกเลิกเอกสาร ";
			$cus_ap_color = "text-danger";
			$cus_ap_id = getcusnewapp($cus_app_nbr,$conn);
			$sql = "INSERT INTO cus_approval(" . 
			" cus_ap_id,cus_ap_nbr,cus_ap_f_step_code,cus_ap_t_step_code,cus_ap_text,cus_ap_remark,cus_ap_color,cus_ap_active,cus_ap_create_by,cus_ap_create_date)" .		
			" VALUES('$cus_ap_id','$cus_app_nbr','$cus_ap_f_step','$cus_ap_t_step','$cus_ap_text','$cus_ap_remark','$cus_ap_color','1','$user_login','$today')";				
			$result = sqlsrv_query($conn, $sql);

		}
		if($result) {
			$r="1";
			$errortxt="Cancel success.";
			$nb=encrypt($cus_app_nbr, $key);
		}
		else {
			$r="0";
			$nb="";
			$errortxt="Cancel fail.";
		}
			echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
	}

?>