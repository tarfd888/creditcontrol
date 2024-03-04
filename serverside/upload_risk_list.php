<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
$params = array();
$uploadpath = "../_fileuploads/ac_risk/";	
$pathicon = "../_fileuploads/icon/";	


$action = mssql_escape($_POST['action']);
$cr_cust_code = mssql_escape($_POST['cr_cust_code']);

//if($action != "view"){ 
  $params = array($cr_cust_code);
  $sql_risk = "SELECT * FROM risk_mstr where risk_cust_nbr = ? order by risk_id";
// }
// else 
// {
//   $params = array();
//   $sql_risk = "SELECT * FROM risk_mstr  order by risk_id";
// }
$result = sqlsrv_query( $conn,$sql_risk, $params, array( "Scrollable" => 'keyset' ));	
$number_of_rows = sqlsrv_num_rows($result);
if($action == "risk_add"){
  $output = '';
  $output .= '
    <table class="table table-bordered table-striped table-sm" style="width:100%; font-size:0.89em;">
      <tr>
      <th>No</th>
      <th>Image</th>
      <th>Description</th>
      <th>Action</th>
      </tr>
    ';
}
else 
{
  $output = '';
  $output .= '
    <table class="table table-bordered table-striped table-sm" style="width:100%; font-size:0.89em;">
      <tr>
      <th>No</th>
      <th>Image</th>
      <th>Description</th>
      </tr>
    ';
}  
if($number_of_rows > 0)
{
 $count = 0;
 while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
 {
  $risk_id = mssql_escape($row['risk_id']);
  $risk_cust_nbr = mssql_escape($row['risk_cust_nbr']);
  $risk_tem_nbr = mssql_escape($row['risk_tem_nbr']);
  $risk_name = mssql_escape($row['risk_name']);
  $risk_description = mssql_escape($row['risk_description']);

  $info_img = pathinfo( $risk_name , PATHINFO_EXTENSION ) ;	
  switch ($info_img) {
    case "pdf":
      $Image = "$uploadpath"."$risk_name";
      $Image_icon = "$pathicon"."pdf.png";
      break;
    case "xls":
    case "xlsx":  
      $Image = "$uploadpath"."$risk_name";
      $Image_icon = "$pathicon"."excel.png";
      break;
    case "doc":
    case "docx":  
      $Image = "$uploadpath"."$risk_name";
      $Image_icon = "$pathicon"."word.png";
      break;      
    default:
    if($risk_name=="") {
      $Image = "$pathicon"."nopicture.png";
      $Image_icon = $Image;
      }else {
      $Image = "$uploadpath"."$risk_name";
      $Image_icon = $Image;
    }
  }	


    if($action == "risk_add"){
      $count ++; 
      $output .= '
      
      <tr>
      <td align="center">'.$count.'</td>
      <td align="center"><a href="'.$Image.'" target="_blank"> <img src= "'.$Image_icon.'" class="img-thumbnail" width="50" height="50" /> </a></td>
      <td>'.$risk_description.'</td>
      <td align="center"><a id="delete" data-id1="'.$risk_id.'" data-risk_name="'.$risk_name.'"><i class="fa fa-trash-o fa-sm " style="color:Crimson"></i></a> </td>
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
      <td>'.$risk_description.'</td>
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
