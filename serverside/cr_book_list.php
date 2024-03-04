<?
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

set_time_limit(0);	
	$result = new stdClass();
	$result->success = FALSE;
	$params = json_decode(filter_input(INPUT_POST, "param0"), TRUE);
 	$result = getData($conn, $result, $params, $key);
	echo json_encode($result);
			
	function getData($conn, $result, $params,$key) {		
		$cus_app_nbr = $params["cus_app_nbr"];	
        $action = $params["action"];	
        $book_case = $params["book_case"];					
		$result_row = array();
		$query_params = array();		
		
        if ($cus_app_nbr !="") {	
			if ($criteria != "") { $criteria = $criteria . " AND "; }
			array_push($query_params, $cus_app_nbr);
			$criteria = $criteria . " book_app_nbr  = ?";
		}		
        if ($book_case !="") {	
		 	if ($criteria != "") { $criteria = $criteria . " AND "; }
		 	array_push($query_params, $book_case);
		 	$criteria = $criteria . " book_case  = ?";
		}		

		if ($criteria != "") {
			$criteria = " WHERE " . $criteria ;
		} 
        else 
        {
            $criteria = "";
        }	 
        if (inlist("cr_edit,cr_edit_chg",$action)) {	     
            $sql = "SELECT * FROM cr_book_mstr $criteria order by book_no";
        }
        else 
        {
            $sql = "SELECT * FROM book_mstr where book_case=$book_case order by book_no";
        }

        $query = sqlsrv_query($conn, $sql, $query_params);
		while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
			$data['book_no'] = mssql_escape($row['book_no']);
			$data['book_dom'] = mssql_escape($row['book_dom']);
			$data['book_exp'] = mssql_escape($row['book_exp']);
			$data['book_agent'] = mssql_escape($row['book_agent']);	
            $data['book_aff'] = mssql_escape($row['book_aff']);	
            $data['book_status'] = mssql_escape($row['book_status']);	
            $data['action']="<input type='checkbox' class='nbr_check' name='numbers_arr[]' id='delcheck_".$row['book_no']."'  value='".$row['book_no']."'>";

			array_push($result_row, $data);
		}
		$result->data = $result_row;
		$result->success = TRUE;		
		return $result;
	}

/* $params = array($q);
$sql_sysc_list = "SELECT * FROM cr_book_mstr  order by book_no";
//$sql_sysc_list = "SELECT book_mstr.*, cr_book_mstr.book_status FROM  book_mstr FULL JOIN cr_book_mstr ON book_mstr.book_no = cr_book_mstr.book_no";

//$params = array();
$result_sysc_list = sqlsrv_query($conn, $sql_sysc_list, $params, array("Scrollable" => 'keyset'));
$row_counts = sqlsrv_num_rows($result_sysc_list);

if($row_counts == 0){
    $sql_sysc_list = "SELECT * FROM book_mstr order by book_no";
    $params = array();
    $result_sysc_list = sqlsrv_query($conn, $sql_sysc_list, $params, array("Scrollable" => 'keyset'));
    $row_counts = sqlsrv_num_rows($result_sysc_list);
}

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
        $dataArray_sysc_list['book_status'] = mssql_escape($row_sysc_list['book_status']);

        $dataArray_sysc_list['action']="<input type='checkbox' class='nbr_check' name='numbers_arr[]' id='delcheck_".$row_sysc_list['book_no']."' onclick='cehckcheckbox();' value='".$row_sysc_list['book_no']."'>";
        //$dataArray_sysc_list['action']="<input type='checkbox' class='nbr_check' name='chk_app' id='delcheck_".$row_sysc_list['book_no']."' onclick='cehckcheckbox();' value='".$row_sysc_list['book_no']."'>";

        array_push($arrayJSON, $dataArray_sysc_list);

        $arrayMain['data'] = $arrayJSON;
    }
    echo json_encode($arrayMain);
}
 */

 