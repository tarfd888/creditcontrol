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
					if (isset($_FILES["ar_data"])) {
						$file_name = $_FILES['ar_data']['name'];
						$file_tmp = $_FILES["ar_data"]["tmp_name"];
						$file_type = $_FILES["ar_data"]["type"];
						$file_type_substr = substr($file_name, -4);
						
						$target_file_name = "Ar_Uploads_" . date("Y-m-d_His") . "_" . $file_name;
						$target_file_path = "../uploads/";
						$target_file = $target_file_path . $target_file_name;
						copy($file_tmp, $target_file);  //copy file into uploads folder
						
						$inputFileName = $target_file;
						
						$sql_ins_upload = "insert into upload_det ([upload_emp_code],[upload_emp_name],[upload_emp_file],[upload_name],[upload_create_date]) values ('$user_code','$user_fullname','$target_file_name','All','" . date("Y-m-d H:i:s") . "')";
						$result_ins_upload = sqlsrv_query($conn, $sql_ins_upload);
						
						$sql_del_upload = "delete from ar_mstr ";
						$result_del_upload = sqlsrv_query($conn, $sql_del_upload);
						
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
								
								// $dms_po = iconv( 'UTF-8', 'UTF-8', $result["B"]);
								// $dms_po = str_replace("'","",$dms_po);
								if (trim($result["A"]) != "") {
									
									$ar_acc = CheckString($result["A"]);
									$ar_doc_nbr = CheckString($result["B"]);
									$ar_assignmnt = CheckString($result["C"]);
									$ar_ref = CheckString($result["D"]);
									$ar_doc_head_txt = CheckString(str_replace('/[^a-z0-9\_\- ]/i', '',$result["E"]));
									//$ar_doc_head_txt = CheckString(str_replace(str_split('\\/:*?"<>|'), ' ', $result["G"]));
									$ar_doc_type = CheckString($result["F"]); 
									$ar_terms_paymnt = CheckString($result["G"]);
									$ar_doc_date = PHPExcel_Style_NumberFormat::toFormattedString($result["H"], 'DD/MM/YYYY');
									$ar_doc_date = ymd($ar_doc_date);
									$ar_due_date = PHPExcel_Style_NumberFormat::toFormattedString($result["I"], 'DD/MM/YYYY');
									$ar_due_date = ymd($ar_due_date);
									$ar_dura_date = ChangeErrFloat($result["J"]);
									$ar_doc_curr = CheckString($result["K"]);
									$ar_amt_doc_curr = ChangeErrFloat($result["L"]);
									$ar_amt_loc_curr = ChangeErrFloat($result["M"]);
									$ar_exc_rate = ChangeErrFloat($result["N"]);
									$ar_txt = CheckString(str_replace("'","",$result['O']));
									//$ar_txt = iconv( 'UTF-8', 'UTF-8', $result["Q"]);
									$ar_clearing_date = PHPExcel_Style_NumberFormat::toFormattedString($result["P"], 'DD/MM/YYYY');
									$ar_clearing_date = ymd($ar_clearing_date);
									$ar_clearing_doc = CheckString($result["Q"]);
									$ar_com_code = CheckString($result["R"]);
									$ar_spc_gl = CheckString($result["S"]);
									$ar_ym = CheckString($result["T"]);
									$ar_create_by = $user_login;
									$ar_create_date = date("Y-m-d H:i:s");
									
									$ar_stamp_date = date("d/m/Y/ H:i:s");
									
									$ar_update_by = '';
									$ar_update_date = '';
									if ($ar_dura_date == 0) {
										$ar_dura_txt = due;
										} else if ($ar_dura_date < 0) {
										$ar_dura_txt = cur;
										} else if ($ar_dura_date > 0) {
										$ar_dura_txt = ovr;
									}
									// $sql = "SELECT ar_acc,ar_doc_nbr,ar_assignmnt,ar_ref FROM ar_mstr WHERE ar_acc='" . $result["C"] . "' and ar_doc_nbr='" . $result["D"] . "' and ar_assignmnt='" . $result["E"] . "' and ar_ref='" . $result["F"] . "'";  // Nanthawat : Select the cells you want to check for duplicates.
									// $result_add = sqlsrv_query($conn, $sql);
									// $row = sqlsrv_fetch_array($result_add, SQLSRV_FETCH_ASSOC);
									//if ($row <= 0) {
									$sql_ins_ordm = "insert into ar_mstr ([ar_acc],[ar_doc_nbr],[ar_assignmnt]
									,[ar_ref]
									,[ar_doc_head_txt]
									,[ar_doc_type]
									,[ar_terms_paymnt]
									,[ar_doc_date]
									,[ar_due_date]
									,[ar_dura_date]
									,[ar_dura_txt]
									,[ar_doc_curr]
									,[ar_amt_doc_curr]
									,[ar_amt_loc_curr]
									,[ar_exc_rate]
									,[ar_txt]
									,[ar_clearing_date]
									,[ar_clearing_doc]
									,[ar_com_code]
									,[ar_spc_gl]
									,[ar_ym]
									,[ar_stamp_date]
									,[ar_create_by]
									,[ar_create_date]) " .
									
									"values('" . $ar_acc . "','" . $ar_doc_nbr . "','" . $ar_assignmnt . "','"
									. $ar_ref . "','"
									. $ar_doc_head_txt . "','"
									. $ar_doc_type . "','"
									. $ar_terms_paymnt . "','"
									. $ar_doc_date . "','"
									. $ar_due_date . "',"
									. $ar_dura_date . ",'"
									. $ar_dura_txt . "','"
									. $ar_doc_curr . "',"
									. $ar_amt_doc_curr . ","
									. $ar_amt_loc_curr . ",'"
									. $ar_exc_rate . "','"
									. $ar_txt . "','"
									. $ar_clearing_date . "','"
									. $ar_clearing_doc . "','"
									. $ar_com_code . "','"
									. $ar_spc_gl . "','"
									. $ar_ym . "','"
									. $ar_stamp_date . "','"
									. $ar_create_by . "','"
									. $ar_create_date . "')";
									$result_ins_ordm = sqlsrv_query($conn, $sql_ins_ordm);
									
									if ($result_ins_ordm) {
										$recordAll++;
									} else {
										echo "<div style='width:100%; border:1px solid #5E5E5E; text-align:left;'>";
										echo "<h1>Some Error hase occured.</h1>";
										echo "Billing . " . $ar_acc . "  " . $ar_doc_nbr . "  <br><br>";
										echo $sql_ins_ordm;
										$recordErr++;
										echo "</div>";
									}
									// } else {   // Check Duplicat data
									// echo "<div style='width:100%; border:1px solid #5E5E5E; text-align:left;'>";
									// echo "<h1>Duplicate Database</h1>";
									// echo "Customber No. " . $ar_acc . "  " . $ar_doc_nbr . " <br><br>";
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