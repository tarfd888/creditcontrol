<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");

$uploadpath = "../_fileuploads/sale/cust/";	

$action = mssql_escape($_POST['action']);
$cus_app_nbr = mssql_escape(decrypt($_POST['cus_app_nbr'], $key));
$image_tem_nbr = mssql_escape(decrypt($_POST['temimagerandom'], $key));
//print_r($image_tem_nbr);
//die();

if($action == "cust_edit" || $action == "cr_add" || $action == "cr_edit" || $action == "cr_add_chg" || $action == "cr_edit_chg" || $action == "view_newcust"){
  $params = array($cus_app_nbr);
  $sql_img = "SELECT * FROM images_mstr where image_app_nbr = ? and image_check_status = 1 order by image_id";
}
if($action == "cust_add"){ 
  $params = array($image_tem_nbr);
  $sql_img = "SELECT * FROM images_mstr where  image_check_status = 0 and image_tem_nbr = ? order by image_id";
}
$result = sqlsrv_query( $conn,$sql_img, $params, array( "Scrollable" => 'keyset' ));	
$number_of_rows = sqlsrv_num_rows($result);

$output = '';
$output .= '
 <table class="table table-bordered table-striped table-sm" style="width:100%; font-size:0.89em;">
  <tr>
   <th>No</th>
   <th>Image</th>
   <th>Name</th>
   <th>Description</th>
   <th>Action</th>
  </tr>
';
if($number_of_rows > 0)
{
 $count = 0;
 while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
 {
  $image_id = mssql_escape($row['image_id']);
  $image_app_nbr = mssql_escape($row['image_app_nbr']);
  $image_tem_nbr = mssql_escape($row['image_tem_nbr']);
  $image_name = mssql_escape($row['image_name']);
  $image_description = mssql_escape($row['image_description']);

  $info_img = pathinfo( $image_name , PATHINFO_EXTENSION ) ;	
  switch ($info_img) {
    case "pdf":
      $Image = "$uploadpath"."$image_name";
      $Image_icon = "$uploadpath"."pdf.png";
      break;
    case "xls":
    case "xlsx":  
      $Image = "$uploadpath"."$image_name";
      $Image_icon = "$uploadpath"."excel.png";
      break;
    case "doc":
    case "docx":  
      $Image = "$uploadpath"."$image_name";
      $Image_icon = "$uploadpath"."word.png";
      break;      
    default:
    if($image_name=="") {
      $Image = "$uploadpath"."nopicture.png";
      $Image_icon = $Image;
      }else {
      $Image = "$uploadpath"."$image_name";
      $Image_icon = $Image;
    }
  }	

  //<td align="center"><a data-toggle="modal" class="open-EditImgModal" data-target="#imageModal" href="javascript:void(0)" id="edit" data-id1="'.$image_id.'" data-image_app_nbr="'.$image_app_nbr.'" data-image_name="'.$image_name.'" data-image_desc="'.$image_description.'"><i class="fa fa-pencil-square-o " style="color:DodgerBlue"></i></a> | <a id="delete" id1="'.$image_id.'" data-image_name="'.$image_name.'"><i class="fa fa-trash-o fa-sm " style="color:Crimson"></i></a> </td>

    if($action == "cust_edit" || $action == "cr_edit" || $action == "cr_edit_chg"){
      $count ++; 
      $output .= '
      
      <tr>
      <td align="center">'.$count.'</td>
      <td align="center"><a href="'.$Image.'" target="_blank"> <img src= "'.$Image_icon.'" class="img-thumbnail" width="50" height="50" /> </a></td>
      <td>'.$image_name.'</td>
      <td>'.$image_description.'</td>
      <td align="center"><a data-toggle="modal" class="open-EditImgModal" data-target="#imageModal" href="javascript:void(0)" id="edit" data-id1="'.$image_id.'" data-image_app_nbr="'.$image_app_nbr.'" data-image_name="'.$image_name.'" data-image_desc="'.$image_description.'"><i class="fa fa-pencil-square-o " style="color:DodgerBlue"></i></a> | <a id="delete" id1="'.$image_id.'" data-image_name="'.$image_name.'"><i class="fa fa-trash-o fa-sm " style="color:Crimson"></i></a> </td>
      </tr>
      ';
    }
    else 
    {
      $count ++; 
      $output .= '
      
      <tr>
      <td align="center">'.$count.'</td>
      <td align="center"><a href="'.$Image.'" target="_blank"> <img src= "'.$Image_icon.'" class="img-thumbnail" width="50" height="50" /> </a></td>
      <td>'.$image_name.'</td>
      <td>'.$image_description.'</td>
      <td align="center"><a href="javascript:void(0)"><i class="fa fa-lock" style="color:DodgerBlue"></i></a> | <a href="javascript:void(0)"><i class="fa fa-lock" style="color:Crimson"></i></a> </td>
      </tr>
      ';
    }
 }
}
else
{
 $output .= '
  <tr>
   <td colspan="6" align="center">No Data Found</td>
  </tr>
 ';
}
$output .= '</table>';
echo $output; 
?>
