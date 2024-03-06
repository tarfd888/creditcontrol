<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key, $user_login)) {
		echo "System detect CSRF attack!!";
		exit;
	}
}
include("../crctrlbof/chkauthcr.php");
include("../crctrlbof/chkauthcrctrl.php");
$allow_admin = false;
set_time_limit(0);
$curdate = date('Ymd');

$activeid = html_escape(decrypt($_REQUEST['activeid'], $key));
$sysc_id = html_escape(decrypt($_REQUEST['syscnumber'], $key));
$curdate = date('d/m/Y');
$params = array($sysc_id);

$sql_sysc = "SELECT * from sysc_ctrl where sysc_id = ? ";
$result_sysc = sqlsrv_query($conn, $sql_sysc, $params);
$rec_sysc = sqlsrv_fetch_array($result_sysc, SQLSRV_FETCH_ASSOC);
if ($rec_sysc) {

	$sysc_com_code = html_clear($rec_sysc['sysc_com_code']);
	$sysc_com_name = html_clear($rec_sysc['sysc_com_name']);
	$sysc_com_address = html_clear($rec_sysc['sysc_com_address']);
	$sysc_com_tel = html_clear($rec_sysc['sysc_com_tel']);
	$sysc_com_fax = html_clear($rec_sysc['sysc_com_fax']);
	$sysc_com_email = html_clear($rec_sysc['sysc_com_email']);
	$sysc_com_lineid = html_clear($rec_sysc['sysc_com_lineid']);
	$sysc_com_taxid = html_clear($rec_sysc['sysc_com_taxid']);
	$sysc_cr_approver1 = strtoupper(html_clear($rec_sysc['sysc_cr_approver1']));
	$sysc_cmo_act = html_clear($rec_sysc['sysc_cmo_act']);
	$sysc_cmo_pos_name = html_clear($rec_sysc['sysc_cmo_pos_name']);
	$sysc_cfo_act = html_clear($rec_sysc['sysc_cfo_act']);
	$sysc_cfo_pos_name = html_clear($rec_sysc['sysc_cfo_pos_name']);
	$sysc_md_act = html_clear($rec_sysc['sysc_md_act']);
	$sysc_md_pos_name = html_clear($rec_sysc['sysc_md_pos_name']);


	if ($sysc_cr_approver1 != "") {
		$emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_cr_approver1, $conn);
		$emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_cr_approver1, $conn);
		$emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_cr_approver1, $conn);
		$sysc_cr_name_approver1 = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
	}
	$sysc_cr_approver2 = strtoupper(html_clear($rec_sysc['sysc_cr_approver2']));
	if ($sysc_cr_approver2 != "") {
		$emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_cr_approver2, $conn);
		$emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_cr_approver2, $conn);
		$emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_cr_approver2, $conn);
		$sysc_cr_name_approver2 = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
	}
	$sysc_mk1 = strtoupper(html_clear($rec_sysc['sysc_mk1']));
	if ($sysc_mk1 != "") {
		$emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_mk1, $conn);
		$emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_mk1, $conn);
		$emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_mk1, $conn);
		$sysc_mk1_name = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
	}
	$sysc_mk2 = strtoupper(html_clear($rec_sysc['sysc_mk2']));
	if ($sysc_mk2 != "") {
		$emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_mk2, $conn);
		$emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_mk2, $conn);
		$emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_mk2, $conn);
		$sysc_mk2_name = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
	}
	$sysc_final_approver = strtoupper(html_clear($rec_sysc['sysc_final_approver']));
	if ($sysc_final_approver != "") {
		$emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_final_approver, $conn);
		$emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_final_approver, $conn);
		$emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_final_approver, $conn);
		$sysc_final_name_approver = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
	}

	$sysc_cmo = strtoupper(html_clear($rec_sysc['sysc_cmo']));
	if ($sysc_cmo != "") {
		$emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_cmo, $conn);
		$emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_cmo, $conn);
		$emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_cmo, $conn);
		$sysc_name_cmo = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
	}
	$sysc_cfo = strtoupper(html_clear($rec_sysc['sysc_cfo']));
	if ($sysc_cfo != "") {
		$emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_cfo, $conn);
		$emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_cfo, $conn);
		$emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_cfo, $conn);
		$sysc_name_cfo = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
	}
	$sysc_md = strtoupper(html_clear($rec_sysc['sysc_md']));
	if ($sysc_md != "") {
		$emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_md, $conn);
		$emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_md, $conn);
		$emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_md, $conn);
		$sysc_name_md = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
	}
	
	$sysc_editprice = html_clear($rec_sysc['sysc_editprice']);
	$sysc_auction_type = html_clear($rec_sysc['sysc_auction_type']);
	$sysc_qt_approval = html_clear($rec_sysc['sysc_qt_approval']);
	$sysc_inform_approved_to_aucadmin = html_clear($rec_sysc['sysc_inform_approved_to_aucadmin']);

	if($sysc_cmo_act == 1)
	{
		$textcolor_cmo = "text-success";
	}
	else
	{
		$textcolor_cmo = "text-warning";
	}
	if($sysc_cfo_act == 1)
	{
		$textcolor_cfo = "text-success";
	}
	else
	{
		$textcolor_cfo = "text-warning";
	}
	if($sysc_md_act == 1)
	{
		$textcolor_md = "text-success";
	}
	else
	{
		$textcolor_md = "text-warning";
	}
	
} else {

	// $path = "authorize.php?msg= $sysc_id ได้ถูกลบออกจากระบบแล้วค่ะ";
	// echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $path . "\" />";
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
	 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="PIXINVENT">
		<title><?php echo(TITLE) ?></title>
		<link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/meteocons/style.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/forms/extended/form-extended.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/pickers/daterange/daterange.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/assets/css/style.css">

		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/toggle/bootstrap-switch.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/toggle/switchery.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-content-menu.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/forms/switch.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/simple-line-icons/style.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-switch.css">
	</head>
<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
	<?php include("../crctrlmain/menu_header.php"); ?>
	<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
	<?php include("../crctrlmain/modal.php"); ?>
	<!-- BEGIN: Content-->
	<div class="app-content content">
		<div class="content-overlay"></div>
		<div class="content-wrapper">
			<div class="content-header row  mt-n1">
				<div class="content-header-left col-md-6 col-12 mb-2 mt-n1">
					<div class="row breadcrumbs-top">
						<div class="breadcrumb-wrapper col-12">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="syscmstrall.php">Home</a>
								</li>
								<li class="breadcrumb-item active"><a href="syscmstrall.php">All Control File </a>
								</li>
							</ol>
						</div>
					</div>
				</div>
				<div class="content-header-right col-md-6 col-12">
					

				</div>
			</div>
			<div class="content-body">
				<!-- Customer start -->
				<section class="Customer">
					<div class="card">
						<div class="card-content">
							<div class="card-body">
								<ul class="nav nav-tabs mb-2" role="tablist">
									<li class="nav-item">
										<a class="nav-link d-flex align-items-center active" id="cust-tab" data-toggle="tab" href="#cust" aria-controls="cust" role="tab" aria-selected="true">
											<i class="fa fa-pencil-square-o mr-25"></i><span class="d-none d-sm-block">Edit Control File</span>
										</a>
									</li>

								</ul>
								<!-- Start sysc Tab -->
								<div class="tab-content">
									<div class="tab-pane active" id="sysc" aria-labelledby="sysc-tab" role="tabpanel">

										<FORM id="frm_sysc_edit" name="frm_sysc_edit" autocomplete=OFF>
											<input type=hidden name="action" value="syscedit">
											<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
											<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
											<input type="hidden" name="sysc_id" value="<? echo $sysc_id ?>">
											<input type=hidden name="pg" value="<?php echo $pg ?>">
											<input type=hidden name="sysc_cmo_pos_name" value="<?php echo $sysc_cmo_pos_name ?>">
											<input type=hidden name="sysc_cfo_pos_name" value="<?php echo $sysc_cfo_pos_name ?>">
											<input type=hidden name="sysc_md_pos_name" value="<?php echo $sysc_md_pos_name ?>">
											<!-- Form Body -->
											<div class="form-body font-small-2">
												<div class="form-group row">
													<div class="col-md-12">
														<div class="row">
															<div class="col-lg-6 col-md-12">
																<h4 class="form-section text-info"><i class="fa fa-cube"></i> Company Information</h4>
																<div class="form-group row">
																	<div class="col-md-12">
																		<div class="row">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label class="font-weight-bold">Company Code: </label>
																					<div class="input-group ">
																						<input type="text" name="sysc_com_code" id="sysc_com_code" class="form-control input-sm font-small-2" value="<? echo $sysc_com_code ?>">

																					</div>
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label class="font-weight-bold">Company Tax: </label>
																					<input type="text" id="sysc_com_taxid " name="sysc_com_taxid" class="form-control input-sm font-small-2" value="<? echo $sysc_com_taxid ?>" placeholder="">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<div class="controls" id="custom-templates">
																						<label class="font-weight-bold">Company Name: </label>
																						<input type="text" id="sysc_com_name " name="sysc_com_name" class="form-control input-sm font-small-2" value="<? echo $sysc_com_name ?>" placeholder="">
																					</div>
																				</div>
																			</div>

																		</div>
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<fieldset class="form-group">
																						<label for="custpj_addr" class="font-weight-bold">Company Address</label>
																						<textarea class="form-control form-control-sm" id="sysc_com_address " name="sysc_com_address" rows="7" placeholder="Address"><? echo $sysc_com_address ?></textarea>
																					</fieldset>
																				</div>
																			</div>

																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label class="font-weight-bold">Tel.</label>
																					<input type="text" id="sysc_com_tel" name="sysc_com_tel" class="form-control input-sm font-small-2" value="<? echo $sysc_com_tel ?>" placeholder="Tel.">
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label class="font-weight-bold">Fax.</label>
																					<input type="text" id="sysc_com_fax" name="sysc_com_fax" class="form-control input-sm font-small-2" value="<? echo $sysc_com_fax ?>" placeholder="Fax.">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label class="font-weight-bold">Email</label>
																					<input type="text" id="sysc_com_email " name="sysc_com_email" class="form-control input-sm font-small-2" value="<? echo $sysc_com_email ?>" placeholder="Email">
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label class="font-weight-bold">Line ID.</label>
																					<input type="text" id="sysc_com_lineid" name="sysc_com_lineid" class="form-control input-sm font-small-2" value="<? echo $sysc_com_lineid ?>" placeholder="Line ID">
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-lg-6 col-md-12">
																<h4 class="form-section text-info"><i class="fa fa-cube"></i> Credit Control Approval </h4>
																<div class="form-group row">
																	<div class="col-md-12">
																		<div class="row">
																			<div class="col-md-12">
																				<label class="font-weight-bold">Credit Control Approval 1 :</label>
																				<div class="form-group input-group">
																					<input name="sysc_cr_approver2" id="sysc_cr_approver1" value="<?php echo $sysc_cr_approver1 ?>"
																						data-disp_col1="emp_user_id" data-disp_col2="emp_fullnamepos" 
																						data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="sysc_cr_approver1"
																						data-ret_value_01="emp_user_id" data-ret_type_01="val"
																						data-ret_field_02="sysc_cr_name_approver1" data-ret_value_02="emp_fullnamepos"
																						data-ret_type_02="html" 
																						class="col-md-4 form-control input-sm font-small-2 typeahead">
																					<input type="text" id="sysc_cr_name_approver1" name="sysc_cr_name_approver1" class="col-md-8 form-control input-sm font-small-2" placeholder="" value="<? echo $sysc_cr_name_approver1 ?>" readonly>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<label class="font-weight-bold">Credit Control Approval 2 :</label>
																				<div class="form-group input-group">
																					<input name="sysc_cr_approver2" id="sysc_cr_approver2" value="<?php echo $sysc_cr_approver2 ?>"
																						data-disp_col1="emp_user_id" data-disp_col2="emp_fullnamepos" 
																						data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="sysc_cr_approver2"
																						data-ret_value_01="emp_user_id" data-ret_type_01="val"
																						data-ret_field_02="sysc_cr_name_approver2" data-ret_value_02="emp_fullnamepos"
																						data-ret_type_02="html" 
																						class="col-md-4 form-control input-sm font-small-2 typeahead">
																					<input type="text" id="sysc_cr_name_approver2" name="sysc_cr_name_approver2" class="col-md-8 form-control input-sm font-small-2" placeholder="" value="<? echo $sysc_cr_name_approver2 ?>" readonly>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<label class="font-weight-bold">Credit Control Approval 3 :</label>
																				<div class="form-group input-group">
																					<input name="sysc_final_approver" id="sysc_final_approver" value="<?php echo $sysc_final_approver ?>"
																						data-disp_col1="emp_user_id" data-disp_col2="emp_fullnamepos" 
																						data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="sysc_final_approver"
																						data-ret_value_01="emp_user_id" data-ret_type_01="val"
																						data-ret_field_02="sysc_final_name_approver" data-ret_value_02="emp_fullnamepos"
																						data-ret_type_02="html" 
																						class="col-md-4 form-control input-sm font-small-2 typeahead">
																					<input type="text" id="sysc_final_name_approver" name="sysc_final_name_approver" class="col-md-8 form-control input-sm font-small-2" placeholder="" value="<? echo $sysc_final_name_approver ?>" readonly>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<label class="font-weight-bold">Marketing 1 :</label>
																				<div class="form-group input-group">
																					<input name="sysc_mk1" id="sysc_mk1" value="<?php echo $sysc_mk1 ?>"
																						data-disp_col1="emp_user_id" data-disp_col2="emp_fullnamepos" 
																						data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="sysc_mk1"
																						data-ret_value_01="emp_user_id" data-ret_type_01="val"
																						data-ret_field_02="sysc_mk1_name" data-ret_value_02="emp_fullnamepos"
																						data-ret_type_02="html" 
																						class="col-md-4 form-control input-sm font-small-2 typeahead">
																					<input type="text" id="sysc_mk1_name" name="sysc_mk1_name" class="col-md-8 form-control input-sm font-small-2" placeholder="" value="<? echo $sysc_mk1_name ?>" readonly>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<label class="font-weight-bold">Marketing 2 :</label>
																				<div class="form-group input-group">
																					<input name="sysc_mk2" id="sysc_mk2" value="<?php echo $sysc_mk2 ?>"
																						data-disp_col1="emp_user_id" data-disp_col2="emp_fullnamepos" 
																						data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="sysc_mk2"
																						data-ret_value_01="emp_user_id" data-ret_type_01="val"
																						data-ret_field_02="sysc_mk2_name" data-ret_value_02="emp_fullnamepos"
																						data-ret_type_02="html" 
																						class="col-md-4 form-control input-sm font-small-2 typeahead">
																					<input type="text" id="sysc_mk2_name" name="sysc_mk2_name" class="col-md-8 form-control input-sm font-small-2" placeholder="" value="<? echo $sysc_mk2_name ?>" readonly>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<label class="font-weight-bold mr-5">CMO :</label>
																				<input type="checkbox" id="sysc_cmo_act" name="sysc_cmo_act" class="switchery" data-size="sm" data-color="success" <?
                                                                                                                                    if ($sysc_cmo_act == 1) {
                                                                                                                                      echo 'checked="checked"';
                                                                                                                                    } ?> />
																				<label for="switcheryColor14" class="font-small-2 <?php echo $textcolor_cmo; ?> ml-1">รักษาการ (Chief Marketing Officer)</label>
																				<div class="form-group input-group">
																					<input name="sysc_cmo" id="sysc_cmo" value="<?php echo $sysc_cmo ?>"
																						data-disp_col1="emp_user_id" data-disp_col2="emp_fullnamepos" 
																						data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="sysc_cmo"
																						data-ret_value_01="emp_user_id" data-ret_type_01="val"
																						data-ret_field_02="sysc_name_cmo" data-ret_value_02="emp_fullnamepos"
																						data-ret_type_02="html" 
																						class="col-md-4 form-control input-sm font-small-2 typeahead">
																					<input type="text" id="sysc_name_cmo" name="sysc_name_cmo" class="col-md-8 form-control input-sm font-small-2" placeholder="" value="<? echo $sysc_name_cmo ?>" readonly>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<label class="font-weight-bold mr-5">CFO :</label>
																				<input type="checkbox" id="sysc_cfo_act" name="sysc_cfo_act" class="switchery" data-size="sm" data-color="success" <?
                                                                                                                                    if ($sysc_cfo_act == 1) {
                                                                                                                                      echo 'checked="checked"';
                                                                                                                                    } ?> />
																				<label for="switcheryColor14" class="font-small-2 <?php echo $textcolor_cfo; ?> ml-1">รักษาการ (Chief Financial Officer)</label>
																				<div class="form-group input-group">
																					<input name="sysc_cfo" id="sysc_cfo" value="<?php echo $sysc_cfo ?>"
																						data-disp_col1="emp_user_id" data-disp_col2="emp_fullnamepos" 
																						data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="sysc_cfo"
																						data-ret_value_01="emp_user_id" data-ret_type_01="val"
																						data-ret_field_02="sysc_name_cfo" data-ret_value_02="emp_fullnamepos"
																						data-ret_type_02="html" 
																						class="col-md-4 form-control input-sm font-small-2 typeahead">
																					<input type="text" id="sysc_name_cfo" name="sysc_name_cfo" class="col-md-8 form-control input-sm font-small-2" placeholder="" value="<? echo $sysc_name_cfo ?>" readonly>
																				</div>
																			</div>
																			<div class="col-md-12">
																				<label class="font-weight-bold mr-5">กจก :</label>
																				<input type="checkbox" id="sysc_md_act" name="sysc_md_act" class="switchery" data-size="sm" data-color="success" <?
                                                                                                                                    if ($sysc_md_act == 1) {
                                                                                                                                      echo 'checked="checked"';
                                                                                                                                    } ?> />

																				<label for="switcheryColor14" class="font-small-2 <?php echo $textcolor_md; ?> ml-1">รักษาการ (กรรมการผู้จัดการ)</label>
																				<div class="form-group input-group">
																					<input name="sysc_md" id="sysc_md" value="<?php echo $sysc_md ?>"
																						data-disp_col1="emp_user_id" data-disp_col2="emp_fullnamepos" 
																						data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="sysc_md"
																						data-ret_value_01="emp_user_id" data-ret_type_01="val"
																						data-ret_field_02="sysc_name_md" data-ret_value_02="emp_fullnamepos"
																						data-ret_type_02="html" 
																						class="col-md-4 form-control input-sm font-small-2 typeahead">
																					<input type="text" id="sysc_name_md" name="sysc_name_md" class="col-md-8 form-control input-sm font-small-2" placeholder="" value="<? echo $sysc_name_md ?>" readonly>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<!-- Submit Button -->
												<div class="form-actions right">
													<div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">
														<button type="button" id="btnsave" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"><i class="fa fa-check-square-o"></i> Save</button>
														<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" onclick="document.location.href='../masmnt/syscmstrall.php'"><i class="ft-x"></i> Cancel</button>
													</div>
												</div>
											</div>
											<!-- End Form Body -->
										</form>

									</div>
								</div>
								<!-- End  Tab -->
							</div>
						</div>
					</div>
				</section>
			</div>
			<!-- ends -->
		</div>
	</div>

	<!-- END: Content-->

	<div class="sidenav-overlay"></div>
	<div class="drag-target"></div>

	<? include("../crctrlmain/menu_footer.php"); ?>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>

	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>

	<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/navs/navs.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/ui/headroom.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/toggle/bootstrap-switch.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/toggle/switchery.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/switch.js"></script>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
	<script src="<?php echo BASE_DIR;?>/_libs/js/bootstrap3-typeahead.min.js"></script>

	<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#btnsave").click(function() {
				$.ajax({
					beforeSend: function() {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show(); /*Show overlay*/
					},
					type: 'POST',
					url: '../serverside/syscmstrpost.php',
					data: $('#frm_sysc_edit').serialize(),
					timeout: 50000,
					error: function(xhr, error) {
						showmsg('[' + xhr + '] ' + error);
					},
					success: function(result) {
					// console.log(result);
					// alert(result);
						var json = $.parseJSON(result);
						if (json.r == '0') {
							clearloadresult();
							Swal.fire({
								title: "Error!",
								html: json.e,
								type: "error",
								confirmButtonClass: "btn btn-danger",
								buttonsStyling: false
							});

						} else {
							clearloadresult();
							Swal.fire({
								position: "top-end",
								type: "success",
								title: "แก้ไขข้อมูลเรียบร้อยค่ะ",
								showConfirmButton: false,
								timer: 1500,
								confirmButtonClass: "btn btn-primary",
								buttonsStyling: false
							});
							$(location).attr('href', 'syscmstrmnt.php?syscnumber=' + json.nb)
						}
					},
					complete: function() {
						$("#requestOverlay").remove(); /*Remove overlay*/
					}
				});
			});
			$('.phone-inputmask').inputmask("999999999");
			// Email mask
			$('.email-inputmask').inputmask({
				mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
				greedy: false,
				onBeforePaste: function(pastedValue, opts) {
					pastedValue = pastedValue.toLowerCase();
					return pastedValue.replace("mailto:", "");
				},
				definitions: {
					'*': {
						validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]",
						cardinality: 1,
						casing: "lower"
					}
				}
			});
			
			$('#sysc_cr_approver1').on('input', function(e) {
				$("#frm_sysc_edit #sysc_cr_name_approver1").val("");
			});
			$('#sysc_cr_approver2').on('input', function(e) {
				$("#frm_sysc_edit #sysc_cr_name_approver2").val("");
			});
			$('#sysc_final_approver').on('input', function(e) {
				$("#frm_sysc_edit #sysc_final_name_approver").val("");
			});
		});
		// $('#sysc_md, #sysc_cfo, #sysc_cfo').typeahead({	
		// displayText: function(item) {
		// 	return item.emp_user_id+" "+item.emp_fullname;
		// }, 
		
		// source: function (query, process) {
		// 	jQuery.ajax({
		// 	url: "../_help/get_emp_data.php",
		// 	data: {query:query},
		// 	dataType: "json",
		// 	type: "POST",
		// 	success: function (data) {
		// 		process(data)
		// 	}
		// 	})
		// },				
		
		// items : "all",
		// afterSelect: function(item) {
		// 	$("#sysc_md").val(item.emp_user_id);
		// 	$("#sysc_name_md").val(item.emp_fullname);
		// 	$("#sysc_cfo").val(item.emp_user_id);
		// 	$("#sysc_name_cfo").val(item.emp_fullname);
		// }
		
		// });
		$('.typeahead').typeahead({
  
			displayText: function(item) {
				var disp_col1 = this.$element.attr('data-disp_col1');
				var disp_col2 = this.$element.attr('data-disp_col2');
				return item[disp_col1] + ' ' + item[disp_col2];
			},
			source: function(query, process) {
				var typeahead_src = this.$element.attr('data-typeahead_src')
				$.ajax({
				url: typeahead_src,
				data: {
					query: query
				},
				dataType: "json",
				type: "POST",
				success: function(data) {
					process(data)
				}
				})
			},
			items: "all",
			afterSelect: function(item) {
				var ret_field_01 = this.$element.attr('data-ret_field_01')
				var ret_value_01 = this.$element.attr('data-ret_value_01')
				var ret_type_01 = this.$element.attr('data-ret_type_01')
				var ret_field_02 = this.$element.attr('data-ret_field_02')
				var ret_value_02 = this.$element.attr('data-ret_value_02')
				var ret_type_02 = this.$element.attr('data-ret_type_02')
			
				if (ret_type_01 == "val") {
				$('#' + ret_field_01).val(item[ret_value_01]);
				} else {
				$('#' + ret_field_01).html(item[ret_value_01]);
				}
				if (ret_type_02 == "html") {
				$('#' + ret_field_02).val(item[ret_value_02]);
				} else {
				$('#' + ret_field_02).html(item[ret_value_02]);
				}
			}
			
			});

		function showdata() {
			document.frm.submit();
		}

		function loadresult() {
			$('#div_result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
		}

		function clearloadresult() {
			$('#div_result').html("");
		}

		function showmsg(msg) {
			$("#modal-body").html(msg);
			$("#myModal").modal("show");

		}
	</script>


</body>
<!-- END: Body-->


</html>