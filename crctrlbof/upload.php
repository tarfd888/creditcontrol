<?php
	
	echo '<pre>';
	print_r($_FILES);
	echo '</pre>';
	
	$dir = "../_fileuploads/sale/project/";
	
	$project_id = 'CR-2107-0004';	
	$random = (rand()%9);
	if( isset($_FILES['file']['name'])) {
		// Check if file is selected
		if(isset($_FILES['file']['name']) && $_FILES['file']['size']> 0) {
			// Get the extension	
			$ext = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
			// check extension and upload
			if( in_array( $ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf','xls'))) {
				// check size of file
				$maxFileSize = 5 * 1024 * 1024; //5MB
				
				if($_FILES['file']['size'] > $maxFileSize){
					if ($errortxt!="") {$errortxt .= "<br>";}
					$errorflag = true;	
					$indexPic = $pickey+1;
					$errortxt .= "ขนาดของไฟล์ ลำดับที่  $indexPic มีขนาดใหญ่เกินไป ต้องไม่เกิน 5 MB";
					}else {
					
					if($_FILES['file']['size']< $maxFileSize){
						$new_filename = "SDV-".$project_id."_001"."_".$random.".".$ext; 
						
						
						$fileimage = $dir . $new_filename;
						///$fileimage = $dir . basename($_FILES["file"]["name"]);
						
						if (move_uploaded_file($_FILES["file"]["tmp_name"],$fileimage)) {
							echo "ไฟล์ภาพชื่อ ". $new_filename. "   อัพโหลดเสร็จแล้ว";
							chmod($fileimage,0777); 
							} else {
							echo "เกิดข้อผิดพลาดในการอัพโหลดไฟล์";
						}
						//echo $fileimage;
					}
				}	
			}	else {
				
				echo "ไฟล์ลำดับที่  ". $_FILES['file']['name']. "  อนุญาตเฉพาะไฟล์รูปภาพเท่านั้น  [ .jpg, .png ]";
			}	
		}
	}
?>			