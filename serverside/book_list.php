<?
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

//$q  = mssql_escape(decrypt($_REQUEST['q'], $key));	
$q  = mssql_escape($_REQUEST['q']);	

$params_sysc_list = array($q);
$sql_sysc_list = "SELECT * FROM  book_mstr where book_case=? order by book_no";

//$params_sysc_list = array();
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
} else // :  Result > 0 row
{
    $arrayMain['draw'] = 1;
    $arrayMain['recordsTotal']  = $row_counts;
    $arrayMain['recordsFiltered']  = $row_counts;

    // : Create Array for Build data to push into $arrayMain ($arrayJSON)
    $arrayJSON = array();
    $arrayDATA = array();

    while ($row_sysc_list = sqlsrv_fetch_array($result_sysc_list, SQLSRV_FETCH_ASSOC)) {
        $dataArray_sysc_list['book_no'] = mssql_escape($row_sysc_list['book_no']);
        $dataArray_sysc_list['book_dom'] = mssql_escape($row_sysc_list['book_dom']);
        $dataArray_sysc_list['book_exp'] = mssql_escape($row_sysc_list['book_exp']);
        $dataArray_sysc_list['book_agent'] = mssql_escape($row_sysc_list['book_agent']);
        $dataArray_sysc_list['book_aff'] = mssql_escape($row_sysc_list['book_aff']);

        $dataArray_sysc_list['action']="<input type='checkbox' class='nbr_check' name='chk_app' id='delcheck_".$row_sysc_list['book_no']."' onclick='checkcheckbox();' value='".$row_sysc_list['book_no']."'>";

        array_push($arrayJSON, $dataArray_sysc_list);

        $arrayMain['data'] = $arrayJSON;
    }
    echo json_encode($arrayMain);
}
