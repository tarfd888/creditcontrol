<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key, $user_login)) {
			echo "System detect CSRF attack!!";
			//exit;
		}
}
$errortxt = "";
$curr_date = ymd(date("d/m/Y"));
$action = html_escape($_POST['action']);
$chk_status = html_escape($_POST['chk_status']);
$img_number = html_escape($_POST['img_number']);
$crstm_fin_app_date = html_escape(ymd($_POST['crstm_fin_app_date']));
$uploadpath = "../_fileuploads/ap/approve/";
	if(!is_dir($uploadpath)){
		 mkdir($uploadpath,0,true);
	}
chmod($uploadpath ,0777);

if(isset($_FILES['load_att_img']['name'])) {
	$crstm_step_code = "60";
    $crstm_step_name = findsqlval("crsta_mstr", "crsta_step_name", "crsta_step_code", $crstm_step_code ,$conn);
    $random = (rand()%9);
	  //// Check if file is selected
			if(isset($_FILES['load_att_img']['name']) && $_FILES['load_att_img']['size']> 0) {
				//// Get the extension	
				$ext = strtolower(pathinfo($_FILES["load_att_img"]["name"], PATHINFO_EXTENSION));
				////check extension and upload
				if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx'))) {
					//// check size of file
					$maxFileSize = 5 * 1024 * 1024; //5MB
					
					if($_FILES['load_att_img']['size'] > $maxFileSize){
						if ($errortxt!="") {$errortxt .= "<br>";}
						$errorflag = true;	
						$errortxt .= "ขนาดของไฟล์  มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
						}else {
						
						if($_FILES['load_att_img']['size']< $maxFileSize){
							$new_filename = "APP-".$curr_date."_".$random.".".$ext; 
							
							$serverPath = $uploadpath; 
							$directoryFile =$serverPath.basename($new_filename);
							move_uploaded_file($_FILES["load_att_img"]["tmp_name"],$directoryFile);
							chmod($directoryFile,0777); 
						}
					}	
					}	else {
					
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;		
					$errortxt .= "ไฟล์ชื่อ  ". $_FILES["load_att_img"]["name"]."<br>". "  อนุญาตเฉพาะนามสกุล  [ .jpg .pdf .xls .xlsx .doc .docx .ppt .pptx]";
				}	
			}
			
			/* $r="1";
			$errortxt="Update image success.";
			$nb=encrypt($new_filename, $key); */
	} 
	if($new_filename =="" || $crstm_fin_app_date == "")	
		{
			$r="0";
			if($new_filename =="") {
				if ($errortxt!="") {$errortxt .= "<br>";}
				$errortxt .= "ไม่พบเอกสารไฟล์แนบ กรุณาเลือกเอกสารใหม่ !!!!!";
			}
			if($crstm_fin_app_date =="") {
				if ($errortxt!="") {$errortxt .= "<br>";}	
				$errortxt .= "กรุณาระบุวันที่ลงนาม !!!!!";
			}
		}
		else {

			$params = array($img_number);
			$sql = "UPDATE crstm_mstr SET ".
					" crstm_fin_img = '$new_filename',".
					" crstm_step_code = '$crstm_step_code' ,".
					" crstm_step_name = '$crstm_step_name' ,".
					" crstm_fin_app_by = '$user_login' ,".
					" crstm_fin_app_date = '$crstm_fin_app_date' ".
					" WHERE crstm_fin_img_id = ? ";
			$result = sqlsrv_query($conn, $sql,$params);
			if ($result) {
					$r="1";
					$errortxt="Update success.";
					$nb=encrypt($img_number, $key);
				}
				else {
					$r="0";
					$nb="";
					$pg="0";
					$errortxt="Update fail.";
				}
		}	
		echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'"}';
?>