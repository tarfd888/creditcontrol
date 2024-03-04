<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
	ini_set("memory_limit","10000M");
	clearstatcache();
	include("../crctrlbof/chkauthcr.php");
	include("../crctrlbof/chkauthcrctrl.php");
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y H:i:s");
	$curYear = date('Y'); 
	$del_month = html_escape($_POST['del_month']);	
	$chk_month = $del_month ;
	///$chk_month = $curYear."/".$del_month ;
	
?>

<!DOCTYPE html>
<html lang="en">
	
	<head>
		<title>Upload Data</title>
		<!--<meta charset="utf-8">-->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	
	<body>
		
		<div class="container">
			<?php
				//include("../_incs/config.php");
				/** PHPExcel */
				require_once '../Classes/PHPExcel.php';
				
				/** PHPExcel_IOFactory - Reader */
				include '../Classes/PHPExcel/IOFactory.php';
				include '../Classes/PHPExcel/Shared/Date.php';
				
				
				function ChangeErrFloat($val)
				{
					if (trim(is_numeric($val))) {
						$val = floatval($val);
						} else {
						$val = 0;
					}
					return $val;
				}
				function CheckString($text){
					if($text !=""){
						if(strpos($text,"'")) {
							$text = strtoupper(str_replace("'","",$text));
							
						}
						if(strpos($text,'"')) {
							$text = strtoupper(str_replace('"',"",$text));
						}
						
						$resText = $text;
					}
					return $resText;
				}
				
				//  Not Check FileType : Check Filetype on formuploads.php and Assume User input Excel Type //
				if ($_POST['submit'] == "import") {
					if (isset($_FILES["bill_data"])) {
						$file_name = $_FILES['bill_data']['name'];
						$file_tmp = $_FILES["bill_data"]["tmp_name"];
						$file_type = $_FILES["bill_data"]["type"];
						$file_type_substr = substr($file_name, -4);
						
						$target_file_name = "Bill_Uploads_" . date("Y-m-d_His") . "_" . $file_name;
						$target_file_path = "../uploads/";
						$target_file = $target_file_path . $target_file_name;
						copy($file_tmp, $target_file);  //copy file into uploads folder
						
						$inputFileName = $target_file;
						
						$sql_ins_upload = "insert into upload_det ([upload_emp_code],[upload_emp_name],[upload_emp_file],[upload_name],[upload_create_date]) values ('$user_code','$user_fullname','$target_file_name','All','" . date("Y-m-d H:i:s") . "')";
						$result_ins_upload = sqlsrv_query($conn, $sql_ins_upload);
						
						$params = array($chk_month);
						$sql_del_upload = "delete from bll_mstr where bll_ym = ?";
						$result_del_upload = sqlsrv_query($conn, $sql_del_upload, $params);
						
						/*********** PHP Excel ******************/
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objReader->setReadDataOnly(true);
						$objPHPExcel = $objReader->load($inputFileName);
						
						$sheetCount = $objPHPExcel->getSheetCount($inputFileType);
						$sheetNames = $objPHPExcel->getSheetNames($inputFileType);
						
						$recordAll = 0;  // Nilubonp :: Variable to Count all record when Insert 
						$recordErr = 0;  // Nilubonp :: Variable to Count error record when Insert 
						
						// Nilubonp :: Variable to Count number of sheet in this excel (In case each sheet is order from each Supplier Country) 
						$row_count_sheet = 0;
						$arr_sheet_name = array();
						
						
						$row_count_sheet = 0;
						
						for ($row_count_sheet = 0; $row_count_sheet < $sheetCount; $row_count_sheet++) // loop in each sheet to Insert into Database differenct Order Supplier Country
						{
							//Nilubonp : Declare Index (Variable $row_count_sheet) to each sheet
							$objWorksheet = $objPHPExcel->setActiveSheetIndex($row_count_sheet);
							$highestRow = $objWorksheet->getHighestRow();
							$highestColumn = $objWorksheet->getHighestColumn();
							
							$headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
							$headingsArray = $headingsArray[1];
							//print_r($headingsArray);
							
							$r = -1;
							$namedDataArray = array();
							$columnnameDataArray = array();
							
							for ($row = 2; $row <= $highestRow; ++$row) {
								// ข้อมูลเก่า for ($row = 2; $row <= $highestRow; ++$row) {
								$dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
								if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
									++$r;
									foreach ($headingsArray as $columnKey => $columnHeading) {
										$namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];  //$namedDataArray keep data on Name of Original Column Name
										$columnnameDataArray[$r][$columnKey] = $dataRow[$row][$columnKey]; //$columnnameDataArray keep data on Name of Original Column Name
									}
								}
							}
							foreach ($columnnameDataArray as $result) {
								
								if (trim($result["A"]) != "") {
								$bll_acc = CheckString($result["A"]);
								$bll_doc_nbr = CheckString($result["B"]);
								$bll_assignmnt = CheckString($result["C"]);
								$bll_ref = CheckString($result["D"]);
								//$bll_doc_head_txt = CheckString(str_replace('/[^a-z0-9\_\- ]/i', '',$result["G"]));
								$bll_doc_head_txt = CheckString(str_replace(str_split('\\/:*?"<>|'), ' ', $result["E"]));

								$bll_doc_type = CheckString($result["F"]); 
								$bll_terms_paymnt = CheckString($result["G"]);
								$bll_doc_date = PHPExcel_Style_NumberFormat::toFormattedString($result["H"], 'DD/MM/YYYY');
								$bll_doc_date = ymd($bll_doc_date);
								
								$bll_doc_ym = substr($bll_doc_date,0,6);
								
								$bll_due_date = PHPExcel_Style_NumberFormat::toFormattedString($result["I"], 'DD/MM/YYYY');
								$bll_due_date = ymd($bll_due_date);
								$bll_dura_date = ChangeErrFloat($result["J"]);
								$bll_doc_curr = CheckString($result["K"]);
								$bll_amt_doc_curr = ChangeErrFloat($result["L"]);
								$bll_amt_loc_curr = ChangeErrFloat($result["M"]);
								$bll_exc_rate = CheckString($result["N"]);
								$bll_txt = CheckString(str_replace("'", "",$result["O"]));
								//$bll_txt = CheckString(str_replace('/[^a-z0-9\_\- ]/i', '',$result["Q"]));
								$bll_txt = CheckString(str_replace(str_split('\\/:*?"<>|'), ' ', $result["O"]));
								$bll_clearing_date = PHPExcel_Style_NumberFormat::toFormattedString($result["P"], 'DD/MM/YYYY');
								$bll_clearing_date = ymd($bll_clearing_date);
								$bll_clearing_doc = CheckString($result["Q"]);
								$bll_com_code = CheckString($result["R"]);
								$bll_spc_gl = CheckString($result["S"]);
								$bll_ym = CheckString($result["T"]);
								$bll_create_by = $user_login;
								$bll_create_date = date("Y-m-d H:i:s");
								$bll_stamp_date = ymd(date("d/m/Y"));
								$bll_update_by = '';
								$bll_update_date = '';
								if ($bll_dura_date == 0) {
									$bll_dura_txt = due;
								} else if ($bll_dura_date < 0) {
									$bll_dura_txt = cur;
								} else if ($bll_dura_date > 0) {
									$bll_dura_txt = ovr;
								}
								
								// $sql = "SELECT bll_ym FROM bll_mstr WHERE bll_acc='" . $result["C"] . "' and bll_doc_nbr='" . $result["D"] . "'  and bll_assignmnt='" . $result["E"] . "'";  // Nanthawat : Select the cells you want to check for duplicates.
								// $result_add = sqlsrv_query($conn, $sql);
								// $row = sqlsrv_fetch_array($result_add, SQLSRV_FETCH_ASSOC);
								
								// if ($row <= 0) {
									$sql_ins_ordm = "insert into bll_mstr ([bll_acc],[bll_doc_nbr],[bll_assignmnt]
									,[bll_ref]
									,[bll_doc_head_txt]
									,[bll_doc_type]
									,[bll_terms_paymnt]
									,[bll_doc_date]
									,[bll_doc_ym]
									,[bll_due_date]
									,[bll_dura_date]
									,[bll_dura_txt]
									,[bll_doc_curr]
									,[bll_amt_doc_curr]
									,[bll_amt_loc_curr]
									,[bll_exc_rate]
									,[bll_txt]
									,[bll_clearing_date]
									,[bll_clearing_doc]
									,[bll_com_code]
									,[bll_spc_gl]
									,[bll_ym]
									,[bll_stamp_date]
									,[bll_create_by]
									,[bll_create_date]) " .
									
									"values('" . $bll_acc . "','" . $bll_doc_nbr . "','" . $bll_assignmnt . "','"
									. $bll_ref . "','"
									. $bll_doc_head_txt . "','"
									. $bll_doc_type . "','"
									. $bll_terms_paymnt . "','"
									. $bll_doc_date . "','"
									. $bll_doc_ym . "','"
									. $bll_due_date . "',"
									. $bll_dura_date . ",'"
									. $bll_dura_txt . "','"
									. $bll_doc_curr . "',"
									. $bll_amt_doc_curr . ","
									. $bll_amt_loc_curr . ",'"
									. $bll_exc_rate . "','"
									. $bll_txt . "','"
									. $bll_clearing_date . "','"
									. $chk_month . "','"
									. $bll_com_code . "','"
									. $bll_spc_gl . "','"
									. $bll_ym . "','"
									. $bll_stamp_date . "','"
									. $bll_create_by . "','"
									. $bll_create_date . "')";
									$result_ins_ordm = sqlsrv_query($conn, $sql_ins_ordm);
									
									if ($result_ins_ordm) {
										$recordAll++;
										} else {
										
										echo "<div style='width:100%; border:1px solid #5E5E5E; text-align:left;'>";
										echo "<h1>Some Error hase occured.</h1>";
										echo "Billing . " . $bll_acc . "  " . $bll_doc_nbr . "  <br><br>";
										echo $sql_ins_ordm;
										$recordErr++;
										echo "</div>";
									}
									// } else {   // Check Duplicat data
									 // echo "<div style='width:100%; border:1px solid #5E5E5E; text-align:left;'>";
									 // echo "<h1>Duplicate Database</h1>";
									 // echo "Customber No. " . $bll_acc . "  " . $bll_doc_nbr . " <br><br>";
									 // echo $sql_ins_ordm;
									// $recordErr++;
								// }
							}
						}
						}
						if ($recordAll >= 0) {
							echo "<script type='text/javascript'>alert('Data import Successful total " . $recordAll . " items. Error Record =" . $recordErr . "'); </script>";
							echo "<meta http-equiv='refresh' content='0;url=../crctrlbof/crctrlall.php'>";
							exit();
						}
						} else {
						echo "<script type='text/javascript'>alert('No Input File Please try again or Contact IT Admin'); </script>";
						echo "<meta http-equiv='refresh' content='0;url=../crctrlbof/crctrlall.php'>";
						exit();
					}
					} else {
					echo "<script type='text/javascript'>alert('No Submit Form'); </script>";
					echo "<meta http-equiv='refresh' content='0;url=../crctrlbof/crctrlall.php'>";
				}
			?>
		</div>
	</body>
	
</html>												