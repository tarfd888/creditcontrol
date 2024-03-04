<?php
//upload.php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
include "../_libs/SimpleImage/simpleimage.php";

// if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
//   if (!matchToken($csrf_key,$user_login)) {
//     echo "System detect CSRF attack!!";
//     exit;
//   }
// } else {
//   echo "System detect CSRF attack!!";
//   exit;
// } 

$uploadpath = '../_fileuploads/ac_risk/';	
if(!is_dir($uploadpath)){
	mkdir($uploadpath,0,true);
  chmod($uploadpath ,0777);
}

set_time_limit(0);
date_default_timezone_set('Asia/Bangkok');
$today = date("Y-m-d H:i:s");
$errortxt = "";
$upd_create_by=$user_login;
$errorflag = false;
$errortxt = "";

$action = mssql_escape($_POST['action']);
$up_year = mssql_escape($_POST['up_year']);
$cr_cust_code = mssql_escape($_POST['cr_cust_code']);


if($action=="risk_add"){
  $check_status = true;
}
// print_r($up_year);
// die();

  if(count($_FILES["file"]["name"]) > 0)
  {
   //$output = '';
   sleep(1);
     for($count=0; $count<count($_FILES["file"]["name"]); $count++)
     {
        $file_name = $_FILES["file"]["name"][$count];
        $tmp_name = $_FILES["file"]['tmp_name'][$count];
        $file_array = explode(".", $file_name);
        $file_extension = end($file_array);
        $random = (rand()%999);
        $new_filename = $random.'-'.$file_name; 
        $new_desc = $cr_cust_code.'-'.$up_year; 

        $location = '../_fileuploads/ac_risk/';
        $serverPath = $location;
        $directoryFile = $serverPath.basename($new_filename);
    
        if(move_uploaded_file($tmp_name, $directoryFile))
        {
          chmod($directoryFile,0777); 
          $risk_id = getnewseq("risk_id","risk_mstr",$conn);
          $query = "
          INSERT INTO risk_mstr (risk_id, risk_year, risk_cust_nbr, risk_name, risk_description, risk_check_status, risk_create_by, risk_create_date) 
          VALUES ($risk_id,'".$up_year."','".$cr_cust_code."','".$new_filename."', '".$new_desc."', '" .$check_status. "', '" .$upd_create_by. "', '" .$today. "')";
          $result_add = sqlsrv_query($conn,$query);
          $r="1";
          $nb=encrypt($cr_cust_code, $key);
          $errortxt="Update success.";
          echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'"}';
        }
     }
  }

function file_already_uploaded($file_name, $conn)
{
 
 $query = "SELECT * FROM risk_mstr WHERE risk_name = '".$file_name."'";

 $result = sqlsrv_query( $conn,$query, $params, array( "Scrollable" => 'keyset' ));	
 $number_of_rows = sqlsrv_num_rows($result);

 if($number_of_rows > 0)
 {
  return true;
 }
 else
 {
  return false;
 }
}

/* if ($action == "img_edit") {
  if (!$errorflag) {
  
    $params=array($risk_id);
      $sql_edit = "UPDATE risk_mstr SET " .
        " risk_name = '$risk_name'," .
        " risk_description = '$image_desc'," .        
        " image_update_by = '$upd_create_by'," .
        " image_update_date = '$today'" .
        " WHERE risk_id = ?";
      $result_edit = sqlsrv_query($conn, $sql_edit, $params);
  
     if ($result_edit) {
       $r="1";
       $nb=encrypt($risk_cust_nbr, $key);
       $pg=encrypt("40", $key);
       $pg1=encrypt("20", $key);
       $errortxt="Update success.";
     }
     else {
       $r="0";
       $nb="";
       $errortxt="Update fail.";
     }
     echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'","pg1":"'.$pg1.'"}';
  }
  else {
     $r="0";
     $nb="";
     echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
  }
} */

if ($action == "del_risk") {
  if(isset($_POST["risk_id"]))
  {
  $file_path = $uploadpath . $_POST["risk_name"];
  $risk_id = mssql_escape($_POST['risk_id']);	

      if(unlink($file_path))
      {
        $params = array($risk_id);
        $query = "DELETE FROM risk_mstr WHERE risk_id = ?";
        $result_del = sqlsrv_query($conn,$query,$params, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        if ($result_del) {
          $r="1";
          $errortxt="ลบข้อมูลเรียบร้อยแล้ว";
          $nb=encrypt($risk_id, $key);
        }
        else {
          $r="0";
          $nb="";
          $errortxt="ลบข้อมูลไม่สำเร็จ";
        }
      echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'"}';
      }
      else
      {
        $r="0";
        $nb="";
        $errortxt="ลบข้อมูลไม่สำเร็จ";
        echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'"}';
      }
    }
  
}
?>