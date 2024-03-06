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
$allow_admin = false;
if (inlist($user_role,"ADMIN") || inlist($user_role,"SYS_ADMIN")) {
    $allow_admin = true;
}else{
	$path = "cisauthorize.php?";
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=" . $path . "\" />";
	exit;
}
set_time_limit(0);
$curdate = date('Ymd');
$activeid = html_escape(decrypt($_REQUEST['activeid'], $key));

?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT">
    <title><?php echo TITLE; ?></title>
    <link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/sysctom.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/toggle/switchery.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/components.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/pages/app-contacts.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/forms/switch.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: sysctom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/assets/css/style.css">
    <!-- END: sysctom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
    <!-- BEGIN: Header-->
    <? include("../cismain/menu_header.php"); ?>
    <!-- END: Header-->
    <!-- BEGIN: Main Menu-->
    <? include("../cismain/menu_leftsidebar.php"); ?>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row  mt-n1">
                <div class="content-header-left col-md-6 col-12 mb-2 mt-n1">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="syscmstrall.php">All Control File </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <h3 class="content-header-title mb-0">add <?php echo $sysc_com_code . " " . $sysc_com_name; ?></h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                        <a class="btn btn-primary white" href="syscadd.php">
                            </i>Add New Control file</a>
                    </div>

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
                                            <i class="fa fa-pencil-square-o mr-25"></i><span class="d-none d-sm-block">Add Control File</span>
                                        </a>
                                    </li>

                                </ul>
                                <!-- Start sysc Tab -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="sysc" aria-labelledby="sysc-tab" role="tabpanel">
                                        <!-- Form Body -->
                                        <FORM id="frm_sysc_add" name="frm_sysc_add" autocomplete=OFF>
                                            <input type=hidden name="action" value="syscadd">
                                            <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
                                            <input type="hidden" name="sysc_id" value="<? echo $sysc_id ?>">
                                            <input type=hidden name="pg" value="<?php echo $pg ?>">
                                            <div class="form-body font-small-3">
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-12">
                                                                <h4 class="form-section text-primary"><i class="fa fa-cube"></i> Company Information</h4>
                                                                <div class="form-group row">
                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Company Code :</label>
                                                                                    <div class="input-group ">
                                                                                        <input type="text" name="sysc_com_code" id="sysc_com_code" class="form-control form-control-sm font-small-3" value="" maxlength="30">

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Company Tax :</label>
                                                                                    <input type="text" id="sysc_com_taxid " name="sysc_com_taxid" class="form-control form-control-sm font-small-3" value="" placeholder="" maxlength="30">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <div class="controls" id="custom-templates">
                                                                                        <label class="font-weight-bold">Company Name :</label>
                                                                                        <input type="text" id="sysc_com_name " name="sysc_com_name" class="form-control form-control-sm font-small-3" value="" placeholder="">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <fieldset class="form-group">
                                                                                        <label for="custpj_addr" class="font-weight-bold">Company Address :</label>
                                                                                        <textarea class="form-control form-control-sm" id="sysc_com_address " name="sysc_com_address" rows="6" placeholder="Address"></textarea>
                                                                                    </fieldset>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Tel :</label>
                                                                                    <input type="text" id="sysc_com_tel" name="sysc_com_tel" class="form-control form-control-sm phone-inputmask" value="" placeholder="Tel.">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Fax :</label>
                                                                                    <input type="text" id="sysc_com_fax" name="sysc_com_fax" class="form-control form-control-sm phone-inputmask" value="" placeholder="Fax.">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Email :</label>
                                                                                    <input type="text" id="sysc_com_email " name="sysc_com_email" class="form-control form-control-sm email-inputmask" value="" placeholder="Email">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Line ID :</label>
                                                                                    <input type="text" id="sysc_com_lineid" name="sysc_com_lineid" class="form-control form-control-sm" value="" placeholder="Line ID">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-12">
                                                                <h4 class="form-section text-primary"><i class="fa fa-cube"></i> Quotation Approval </h4>
                                                                <div class="form-group row">
                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label class="font-weight-bold">Quotation Price Approval 1 :</label>
                                                                                <div class="form-group input-group">
                                                                                    <input type="text" id="sysc_qt_price_approver1" name="sysc_qt_price_approver1" class="col-md-4 form-control form-control-sm font-small-3" value="" placeholder="USER ID">
                                                                                    <input type="text" id="sysc_qt_price_name_approver1" name="sysc_qt_price_name_approver1" class="form-control form-control-sm font-small-3" value="" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="font-weight-bold">Quotation Price Approval 2 :</label>
                                                                                <div class="form-group input-group">
                                                                                    <input type="text" id="sysc_qt_price_approver2" name="sysc_qt_price_approver2" class="col-md-4 form-control form-control-sm font-small-3" value="" placeholder="USER ID">
                                                                                    <input type="text" id="sysc_qt_price_name_approver2" name="sysc_qt_price_name_approver2" class="form-control form-control-sm font-small-3" readonly value="">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label class="font-weight-bold">Quotation Final 1 :</label>
                                                                                <div class="form-group input-group">
                                                                                    <input type="text" id="sysc_qt_final_approver1" name="sysc_qt_final_approver1" class="col-md-4 form-control form-control-sm font-small-3" placeholder="USER ID" value="">
                                                                                    <input type="text" id="sysc_qt_final_name_approver1" name="sysc_qt_final_name_approver1" class="form-control form-control-sm font-small-3" readonly value="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="font-weight-bold">Quotation Final 2 :</label>
                                                                                <div class="form-group input-group">
                                                                                    <input type="text" id="sysc_qt_final_approver2" name="sysc_qt_final_approver2" class="col-md-4 form-control form-control-sm font-small-3" placeholder="USER ID" value="">
                                                                                    <input type="text" id="sysc_qt_final_name_approver2" name="sysc_qt_final_name_approver2" class="form-control form-control-sm font-small-3" readonly value="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="col-md-6" style="display:none">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Quotation Approval :</label>
                                                                                    <input type="checkbox" class="switch" id="sysc_qt_approval" name="sysc_qt_approval" data-group-cls="btn-group-sm" checked="checked" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <h4 class="form-section text-primary"><i class="fa fa-cube"></i> Project Approval</h4>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label class="font-weight-bold">Project Approval 1 :</label>
                                                                                <div class="form-group input-group">
                                                                                    <input type="text" class="col-md-4 form-control form-control-sm" id="sysc_pj_approver1" name="sysc_pj_approver1" placeholder="USER ID" value="">
                                                                                    <input type="text" id="sysc_pj_name_approver1" name="sysc_pj_name_approver1" class="form-control form-control-sm font-small-3" readonly value="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="font-weight-bold">Project Approval 2 :</label>
                                                                                <div class="form-group input-group">
                                                                                    <input type="text" class="col-md-4 form-control form-control-sm" id="sysc_pj_approver2" name="sysc_pj_approver2" placeholder="USER ID" value="">
                                                                                    <input type="text" id="sysc_pj_name_approver2" name="sysc_pj_name_approver2" class="form-control form-control-sm font-small-3" readonly value="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Auction Type :</label>
                                                                                    <select name="sysc_auction_type" id="sysc_auction_type" class="form-control  form-control-sm select2">
                                                                                        <option value="">--Select--</option>
                                                                                        <option value="PRICE">PRICE</option>
                                                                                        <option value="SEQ">SEQ</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Edit Price :</label>
                                                                                    <input type="checkbox" class="switch" id="sysc_editprice" name="sysc_editprice" data-group-cls="btn-group-sm" checked="checked" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="col-md-6" style="display:none">
                                                                                <div class="form-group">
                                                                                    <label class="font-weight-bold">Information Approval :</label>
                                                                                    <input type="checkbox" class="switch" id="sysc_inform_approved_to_aucadmin" name="sysc_inform_approved_to_aucadmin" data-group-cls="btn-group-sm" checked="checked" />
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
                                                <div class="form-group row">
                                                    <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                                        <button type="button" id="btnsave" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save
                                                            changes</button>
                                                        <button type="reset" class="btn btn-light" onclick="document.location.href='../masmnt/syscmstrall.php'">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- End Form Body -->
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

    <!-- BEGIN: Footer-->
    <? include("../crctrlmain/menu_footer.php"); ?>
    <!-- END: Footer-->

    <?php include("../cismain/modal.php"); ?>

    <!-- BEGIN: Vendor JS-->
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>

    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/typeahead.bundle.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/handlebars.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>

    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/toggle/switchery.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/pages/page-users.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/navs/navs.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/switch.js"></script>

    <!--END: Page JS-->

    <!-- BEGIN: Custom JS-->
    <script src="../_libs/js/bootstrap3-typeahead.min.js"></script>

    <!-- END: Custom JS-->


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
                    data: $('#frm_sysc_add').serialize(),
                    timeout: 50000,
                    error: function(xhr, error) {
                        showmsg('[' + xhr + '] ' + error);
                    },
                    success: function(result) {
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
                                title: "เพิ่มข้อมูลเรียบร้อยค่ะ",
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
            $('#sysc_qt_price_name_approver1,#sysc_qt_price_approver1').typeahead({
                displayText: function(item) {
                    return item.sc_emp_user_id + ">>" + item.sc_name
                    // $("#province").val(item.province);
                },
                source: function(query, process) {
                    jQuery.ajax({
                        url: "../serverside/sc_list.php", //even.php",
                        data: {
                            query: query
                        },
                        dataType: "json",
                        type: "POST",
                        success: function(data) {
                            process(data)
                            //$("#province").val(data[0].province);
                        }
                    })
                },
                afterSelect: function(item) {
                    $("#frm_sysc_add #sysc_qt_price_approver1").val(item.sc_emp_user_id);
                    $("#frm_sysc_add #sysc_qt_price_name_approver1").val(item.sc_name);
                }

            });
            $('#sysc_qt_price_name_approver2,#sysc_qt_price_approver2').typeahead({
                displayText: function(item) {
                    return item.sc_emp_user_id + ">>" + item.sc_name
                },
                source: function(query, process) {
                    jQuery.ajax({
                        url: "../serverside/sc_list.php", //even.php",
                        data: {
                            query: query
                        },
                        dataType: "json",
                        type: "POST",
                        success: function(data) {
                            process(data)
                            //$("#province").val(data[0].province);
                        }
                    })
                },
                afterSelect: function(item) {
                    $("#frm_sysc_add #sysc_qt_price_approver2").val(item.sc_emp_user_id);
                    $("#frm_sysc_add #sysc_qt_price_name_approver2").val(item.sc_name);
                }

            });
            $('#sysc_qt_final_name_approver1,#sysc_qt_final_approver1').typeahead({
                displayText: function(item) {
                    return item.sc_emp_user_id + ">>" + item.sc_name

                },
                source: function(query, process) {
                    jQuery.ajax({
                        url: "../serverside/sc_list.php", //even.php",
                        data: {
                            query: query
                        },
                        dataType: "json",
                        type: "POST",
                        success: function(data) {
                            process(data)
                            //$("#province").val(data[0].province);
                        }
                    })
                },
                afterSelect: function(item) {
                    $("#frm_sysc_add #sysc_qt_final_approver1").val(item.sc_emp_user_id);
                    $("#frm_sysc_add #sysc_qt_final_name_approver1").val(item.sc_name);
                }

            });
            $('#sysc_qt_final_name_approver2,#sysc_qt_final_approver2').typeahead({
                displayText: function(item) {
                    return item.sc_emp_user_id + ">>" + item.sc_name

                },
                source: function(query, process) {
                    jQuery.ajax({
                        url: "../serverside/sc_list.php", //even.php",
                        data: {
                            query: query
                        },
                        dataType: "json",
                        type: "POST",
                        success: function(data) {
                            process(data)
                            //$("#province").val(data[0].province);
                        }
                    })
                },
                afterSelect: function(item) {
                    $("#frm_sysc_add #sysc_qt_final_approver2").val(item.sc_emp_user_id);
                    $("#frm_sysc_add #sysc_qt_final_name_approver2").val(item.sc_name);
                }

            });
            $('#sysc_qt_price_approver1').on('input', function(e) {
                $("#frm_sysc_add #sysc_qt_price_name_approver1").val("");
            });
            $('#sysc_qt_price_approver2').on('input', function(e) {
                $("#frm_sysc_add #sysc_qt_price_name_approver2").val("");
            });
            $('#sysc_qt_final_napprover1').on('input', function(e) {
                $("#frm_sysc_add #sysc_qt_final_name_approver1").val("");
            });
            $('#sysc_qt_final_approver2').on('input', function(e) {
                $("#frm_sysc_add #sysc_qt_final_name_approver2").val("");
            });
            $('#sysc_pj_name_approver1,#sysc_pj_approver1').typeahead({
                displayText: function(item) {
                    return item.sc_emp_user_id + ">>" + item.sc_name

                },
                source: function(query, process) {
                    jQuery.ajax({
                        url: "../serverside/sc_list.php", //even.php",
                        data: {
                            query: query
                        },
                        dataType: "json",
                        type: "POST",
                        success: function(data) {
                            process(data)
                            //$("#province").val(data[0].province);
                        }
                    })
                },
                afterSelect: function(item) {
                    $("#frm_sysc_add #sysc_pj_approver1").val(item.sc_emp_user_id);
                    $("#frm_sysc_add #sysc_pj_name_approver1").val(item.sc_name);
                }
            });
            $('#sysc_pj_name_approver2,#sysc_pj_approver2').typeahead({
                displayText: function(item) {
                    return item.sc_emp_user_id + ">>" + item.sc_name

                },
                source: function(query, process) {
                    jQuery.ajax({
                        url: "../serverside/sc_list.php", //even.php",
                        data: {
                            query: query
                        },
                        dataType: "json",
                        type: "POST",
                        success: function(data) {
                            process(data)
                            //$("#province").val(data[0].province);
                        }
                    })
                },
                afterSelect: function(item) {
                    $("#frm_sysc_add #sysc_pj_approver2").val(item.sc_emp_user_id);
                    $("#frm_sysc_add #sysc_pj_name_approver2").val(item.sc_name);
                }
            });
            $('#sysc_pj_approver1').on('input', function(e) {
                $("#frm_sysc_add #sysc_pj_name_approver1").val("");
            });
            $('#sysc_pj_approver2').on('input', function(e) {
                $("#frm_sysc_add #sysc_pj_name_approver2").val("");
            });

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