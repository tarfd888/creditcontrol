<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");

//if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
//		if (!matchToken($csrf_key, $user_login)) {
//			echo "System detect CSRF attack!!";
			//exit;
//		}
//}
$curr_date = ymd(date("d/m/Y"));
$uploadpath = "../_fileuploads/ap/approve/";
	if(!is_dir($uploadpath)){
		 mkdir($uploadpath,0,true);
	}
	chmod($uploadpath ,0777);
//delete all the selected rows from the database
$chk_status = html_escape($_POST['chk_status']);
$numbers_arr = $_POST['numbers_arr'];
$img_random = (rand(10000,99999));
  
  
if($chk_status == 1){
    foreach($numbers_arr as $number){
		$params = array($number);
		//$result_del=sqlsrv_query($conn,"DELETE FROM resent_ems_mstr WHERE dms_dp=".$deleteid  );
		//$sql="DELETE FROM resend_dp WHERE dms_dp=? and dms_curprocessor=?" ;
		$sql = "UPDATE crstm_mstr SET ".
				" crstm_fin_img_id = '$img_random'".
				" WHERE crstm_nbr = ? ";
		$result_del = sqlsrv_query($conn, $sql,$params);
    }

			if ($result_del) {
				$r="1";
				$errortxt="Update success.";
				//$nb=encrypt($number, $key);
				$nb=encrypt($img_random, $key);
			}
			else {
				$r="0";
				$nb="";
				$pg="0";
				$errortxt="Update fail.";
			}
}		
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
//}

?>