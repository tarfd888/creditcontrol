<?
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

$sql_sysc_list = "SELECT sysc_id, sysc_com_code, sysc_com_name, sysc_com_address, sysc_com_tel, sysc_com_fax, sysc_com_email, sysc_com_lineid, sysc_com_taxid, sysc_cr_approver1, ".
                 "sysc_cr_approver2, sysc_final_approver, sysc_editprice, sysc_auction_type, sysc_qt_approval, sysc_inform_approved_to_aucadmin FROM sysc_ctrl";

$params_sysc_list = array();
$result_sysc_list = sqlsrv_query($conn, $sql_sysc_list, $params_sysc_list, array("Scrollable" => 'keyset'));
$row_counts = sqlsrv_num_rows($result_sysc_list);

$arrayMain = array();
if ($row_counts == 0) // Result == 0 row
{
    $arrayMain['draw'] = 0;
    $arrayMain['recordsTotal']  = $row_counts;
    $arrayMain['recordsFiltered']  = $row_counts;
    $arrayMain['data'] = array();
    echo json_encode($arrayMain);
} else //Nilubonp :  Result > 0 row
{
    //Nilubonp : Create Array for Build JSON ( $arrayMain)
    $arrayMain['draw'] = 1;
    $arrayMain['recordsTotal']  = $row_counts;
    $arrayMain['recordsFiltered']  = $row_counts;

    //Nilubonp : Create Array for Build data to push into $arrayMain ($arrayJSON)
    $arrayJSON = array();
    $arrayDATA = array();
 

    while ($row_sysc_list = sqlsrv_fetch_array($result_sysc_list, SQLSRV_FETCH_ASSOC)) {
        $dataArray_sysc_list['sysc_id'] = html_clear($row_sysc_list['sysc_id']);
        $dataArray_sysc_list['sysc_com_code'] = html_clear($row_sysc_list['sysc_com_code']);
        $dataArray_sysc_list['sysc_com_name'] = html_clear($row_sysc_list['sysc_com_name']);
        $dataArray_sysc_list['sysc_com_address'] = html_clear($row_sysc_list['sysc_com_address']);
        $dataArray_sysc_list['sysc_com_tel'] = html_clear($row_sysc_list['sysc_com_tel']);
        $dataArray_sysc_list['sysc_com_fax'] = html_clear($row_sysc_list['sysc_com_fax']);
        //$dataArray_sysc_list['sysc_com_email'] = html_escape($row_sysc_list['sysc_com_email']);
        $dataArray_sysc_list['sysc_com_taxid'] = html_clear($row_sysc_list['sysc_com_taxid']);


        $original_email = html_clear($row_sysc_list['sysc_com_email']);
        $original_email = str_replace("@","!",$original_email);
        $encrypt_email = base64_encode($original_email."@".$user_login);
        $dataArray_sysc_list['sysc_com_email'] = $encrypt_email;
       
        //Nilubonp : Put data from arrayDATA into arrayJSON  column by column to each row
        array_push($arrayJSON, $dataArray_sysc_list);

        //Nilubonp : Put data from arrayDATA into arrayJSON  object by object
        $arrayMain['data'] = $arrayJSON;
    }

    //Nilubonp : Finally Create JSON ARRAY
    echo json_encode($arrayMain);
}
