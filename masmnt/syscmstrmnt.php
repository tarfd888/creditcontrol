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
    $sysc_cr_approver1 = html_clear($rec_sysc['sysc_cr_approver1']);
    if ($sysc_cr_approver1 != "") {
        $emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_cr_approver1, $conn);
        $emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_cr_approver1, $conn);
        $emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_cr_approver1, $conn);
        $sysc_cr_name_approver1 = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
    }
    $sysc_cr_approver2 = html_clear($rec_sysc['sysc_cr_approver2']);
    if ($sysc_cr_approver2 != "") {
        $emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_cr_approver2, $conn);
        $emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_cr_approver2, $conn);
        $emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_cr_approver2, $conn);
        $sysc_cr_name_approver2 = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
    }
    $sysc_final_approver = html_clear($rec_sysc['sysc_final_approver']);
    if ($sysc_final_approver != "") {
        $emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_final_approver, $conn);
        $emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_final_approver, $conn);
        $emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_final_approver, $conn);
        $sysc_final_name_approver = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname;
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
} else {

     //$path = "cisauthorize.php?msg= $sysc_id ได้ถูกลบออกจากระบบแล้วค่ะ";
    // echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $path . "\" />";
}

?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

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
</head>

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="content-detached-left-sidebar">

    <?php include("../crctrlmain/menu_header.php"); ?>
	<?php include("../crctrlmain/menu_leftsidebar.php"); ?>

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
                    <!--<h3 class="content-header-title mb-0"><?php echo $sysc_com_code . " " . $sysc_com_name; ?></h3>-->
                </div>
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="new-project">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header mt-1 pt-0 pb-0">
									<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">                                           
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show font-small-3">
                                    <div class="card-body" style="margin-top:-20px;">
                                        <ul class="nav nav-tabs mb-2 mt-0" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link d-flex align-items-center active" id="sysc-tab" data-toggle="tab" href="#sysc" aria-controls="sysc" role="tab" aria-selected="false">
                                                    <i class="fa fa-cogs mr-25"></i><span class="d-none d-sm-block font-weight-bold">Control File</span>
                                                </a>
                                            </li>

                                        </ul>
                                        <!-- Start Project Tab -->
                                        <div class="tab-content">

                                            <div class="tab-pane active" id="sysc" aria-labelledby="sysc-tab" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-lg-6 border-right ">
                                                        <div class="row  p-1">
                                                            <div class="col-lg-12">
                                                                <h4 class="form-section text-info"><i class="fa fa-cube"></i> Company Information </h4>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">Company Code :</div>
                                                                    <div class="col-lg-8 col-md-6 pt-1 border-bottom"><? echo $sysc_com_code; ?></div>
                                                                </div>

                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">Company Tax :</div>
                                                                    <div class="col-lg-8 col-md-6 pt-1 border-bottom"><? echo $sysc_com_taxid; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">Company Name :</div>
                                                                    <div class="col-lg-8 col-md-6 pt-1 border-bottom"><? echo $sysc_com_name; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">Company Address :</div>
                                                                    <div class="col-lg-8 col-md-6 pt-1 border-bottom"><? echo $sysc_com_address; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">Company Tel :</div>
                                                                    <div class="col-lg-8 col-md-6 pt-1 border-bottom"><? echo $sysc_com_tel; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">Company Fax :</div>
                                                                    <div class="col-lg-8 col-md-6 pt-1 border-bottom"><? echo $sysc_com_fax; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">Company Email :</div>
                                                                    <div class="col-lg-8 col-md-6 pt-1 border-bottom"><? echo $sysc_com_email; ?></div>
                                                                </div>

                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">Line ID :</div>
                                                                    <div class="col-lg-8 col-md-6 pt-1 border-bottom"><? echo $sysc_com_lineid; ?></div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 ">
                                                        <div class="row p-1">
                                                            <div class="col-lg-12">
                                                                <h4 class="form-section text-info"><i class="fa fa-cube"></i> Approval Information </h4>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Credit Control Approval 1 :</div>
                                                                    <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $sysc_cr_name_approver1; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Credit Control Approval 2 :</div>
                                                                    <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $sysc_cr_name_approver2; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Credit Control Approval 3 :</div>
                                                                    <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $sysc_final_name_approver; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Marketing 1 :</div>
                                                                    <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $sysc_final_name_approver; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Marketing 2 :</div>
                                                                    <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $sysc_final_name_approver; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Chief Marketing Officer :</div>
                                                                    <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $sysc_name_cmo; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-5 col-md-6 pt-1 font-weight-bold">Chief Financial Officer :</div>
                                                                    <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $sysc_name_cfo; ?></div>
                                                                </div>
                                                                <div class="row pr-1 pl-1 ">
                                                                    <div class="col-lg-5 col-md-6 pt-1 font-weight-bold">กรรมการผู้จัดการ :</div>
                                                                    <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $sysc_name_md; ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="form-group row">
                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm mt-1">
                                                    <button type="reset" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" onclick="document.location.href='../masmnt/syscmstrall.php'"><i class="ft-x"></i> Close</button>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- End Project Tab -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users edit ends -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
		<div class="sidenav-overlay"></div>
		<div class="drag-target"></div>
		
		<!-- BEGIN: Footer-->
		<footer class="footer footer-static footer-light navbar-border">
			<p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 <a class="text-bold-800 grey darken-2" href="https://1.envato.market/pixinvent_portfolio" target="_blank">PIXINVENT </a></span><span class="float-md-right d-none d-lg-block">Power by IT Business Solution Team <i class="feather icon-heart pink"></i></span></p>
		</footer>
		<!-- END: Footer-->
		
		<!-- BEGIN: Vendor JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<!-- BEGIN Vendor JS-->
		
		<!-- BEGIN: Page Vendor JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/jquery.knob.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/extensions/knob.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/raphael-min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/morris.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/data/jvector/visitor-data.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/chart.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jquery.sparkline.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/unslider-min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-climacon.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/simple-line-icons/style.min.css">
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>			
		<!-- END: Page Vendor JS-->
		
		<!-- BEGIN: Theme JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<!-- END: Theme JS-->
		
		<!-- BEGIN: Page JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/pages/dashboard-analytics.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/tables/datatables/datatable-basic.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/typeahead.bundle.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/handlebars.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-inputmask.min.js"></script>
		
		<!-- END: Page JS-->
		<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
		<script type="text/javascript" language="javascript" class="init">
    <script language="javascript">
        $(document).ready(function() {
            $(".switch:checkbox").checkboxpicker();


        });
    </script>

</body>
<!-- END: Body-->

</html>