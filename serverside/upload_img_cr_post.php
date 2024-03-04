<?php
//upload.php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
include "../_libs/SimpleImage/simpleimage.php";

/* if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
  if (!matchToken($csrf_key,$user_login)) {
    echo "System detect CSRF attack!!";
    exit;
  }
} else {
  echo "System detect CSRF attack!!";
  exit;
} */

$uploadpath = '../_fileuploads/sale/cust/';	
if(!is_dir($uploadpath)){
	mkdir($uploadpath,0,true);
  chmod($uploadpath ,0777);
}

set_time_limit(0);
date_default_timezone_set('Asia/Bangkok');
$today = date("Y-m-d H:i:s");
$errortxt = "";
$upd_create_by=$user_login;

$action = mssql_escape($_POST['action']);
$image_id = mssql_escape($_POST['image_id']);
$image_app_nbr = mssql_escape(trim($_POST['image_app_nbr']));
$image_name = mssql_escape(trim($_POST['image_name']));
$image_desc = mssql_escape(trim($_POST['image_desc']));
$temimagerandom = mssql_escape(decrypt($_POST['temimagerandom'], $key));
$cus_app_nbr = mssql_escape(decrypt($_POST['cus_app_nbr'], $key));

//ns29012024 add variable $cus_app_nbr_edit
$cus_app_nbr_edit = mssql_escape($_POST['cus_app_nbr']);

if($action=="cust_add"){
  $check_status = false;
}
else 
{
  $check_status = true;
}
// print_r($action);
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
    /* if(file_already_uploaded($file_name, $conn))
    {
    $file_name = $file_array[0] . '-'. rand() . '.' . $file_extension;
    }  */
    //$location = '../_fileuploads/sale/cust/' . $file_name;

    $new_filename = $random.'-'.$file_name; 
    $location = '../_fileuploads/sale/cust/';
    $serverPath = $location;
    $directoryFile = $serverPath.basename($new_filename);

    if(move_uploaded_file($tmp_name, $directoryFile))
    {
      chmod($directoryFile,0777); 
      $image_id = getnewseq("image_id","images_mstr",$conn);
      $query = "
      INSERT INTO images_mstr (image_id, image_tem_nbr, image_app_nbr, image_name, image_description, image_check_status, image_create_by, image_create_date) 
      VALUES ($image_id,'".$temimagerandom."','".$cus_app_nbr_edit."','".$new_filename."', '', '" .$check_status. "', '" .$upd_create_by. "', '" .$today. "')";
      $result_add = sqlsrv_query($conn,$query);
      $r="1";
      $nb=encrypt($temimagerandom, $key);
      $pg=encrypt("40", $key);
      $errortxt="Update success.";
      echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
    }
 }
}

function file_already_uploaded($file_name, $connect)
{
 
 $query = "SELECT * FROM images_mstr WHERE image_name = '".$file_name."'";

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

if ($action == "img_edit") {
  if (!$errorflag) {
  
    $params=array($image_id);
      $sql_edit = "UPDATE images_mstr SET " .
        " image_name = '$image_name'," .
        " image_description = '$image_desc'," .        
        " image_update_by = '$upd_create_by'," .
        " image_update_date = '$today'" .
        " WHERE image_id = ?";
      $result_edit = sqlsrv_query($conn, $sql_edit, $params);
  
     if ($result_edit) {
       $r="1";
       $nb=encrypt($image_app_nbr, $key);
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
  }

if ($action == "del_img") {
  if(isset($_POST["image_id"]))
  {
  $file_path = $uploadpath . $_POST["image_name"];
  $image_id = mssql_escape($_POST['image_id']);	

      if(unlink($file_path))
      {
        $params = array($image_id);
        $query = "DELETE FROM images_mstr WHERE image_id = ?";
        $result_del = sqlsrv_query($conn,$query,$params, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
        if ($result_del) {
          $r="1";
          $errortxt="Delete success.";
          $nb=encrypt($image_id, $key);
        }
        else {
          $r="0";
          $nb="";
          $errortxt="Delete fail.";
        }
      echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
      }
      else
      {
        $r="0";
        $nb="";
        $errortxt="Delete fail.";
        echo '{"r":"'.$r.'","e":"'.$errortxt.'","nb":"'.$nb.'","pg":"'.$pg.'"}';
      }
    }
  
}
?>