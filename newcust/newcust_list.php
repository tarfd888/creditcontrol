<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
    include("../crctrlbof/chkauthcr.php");
    include("../crctrlbof/chkauthcrctrl.php");
    include_once('../_libs/Thaidate/Thaidate.php');
    include_once('../_libs/Thaidate/thaidate-functions.php');
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key, $user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	
	set_time_limit(0);
	$curdate = date('Ymd');
	$params = array();
	$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);
	
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT"> 
    <title><?php echo(TITLE) ?></title>
    <link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/style.css"><!--to-top -->
</head>

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover"
    data-menu="vertical-menu" data-col="2-columns">
    <div id="result"></div>
    <?php include("header.php"); ?>

    <?php include("../crctrlmain/menu_header.php"); ?>
    <?php include("../crctrlmain/menu_leftsidebar.php"); ?>
    <?php include("../crctrlmain/modal_cust.php"); ?>
    <?php include("../crctrlmain/help_modal.php"); ?>
    <!-- BEGIN: Content-->
    <div class="app-content content font-small-2">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">List Customer</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../newcust/newcust_list.php">Home</a>
                                </li>
                                <!--<li class="breadcrumb-item"><a href="#">DataTables</a>
									</li>-->
                                <li class="breadcrumb-item active">
                                    <font color="40ADF4">List รายการขออนุมัติแต่งตั้งลูกค้าใหม่/สาขาใหม่/เปลี่ยนแปลง</font>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Province All -->
                <section id="project-all">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header ">
                                    <h4 class="card-title blue darken-2 font-weight-bold">[ แต่งตั้งลูกค้าใหม่ / สาขาใหม่ / เปลี่ยนแปลง ]</h4>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a href='newcust_list.php'><i class="fa fa-reply-all"></i></a></li>
                                            <!-- <li><a title="Click to go back,hold to see history" data-action="reload"><i
                                                        class="fa fa-reply-all"
                                                        onclick="javascript:window.history.back();"></i></a></li> -->
                                            <li><a title="Click to expand the screen" data-action="expand"><i
                                                        class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body ">
                                        <div class="table-responsive">
                                            <!-- Project All -->
                                            <table id="newcust_list"
                                                class="table table-sm table-hover table-bordered compact nowrap "
                                                style="width:100%;">
                                                <!--dt-responsive nowrap-->
                                                <thead class="text-center" style="background-color:#f1f1f1;">
                                                    <tr class="text-center" style="background-color:#DDF2FD;">
                                                        <th>No.</th>
                                                        <th>เลขที่เอกสาร</th>
                                                        <th>วันที่</th>
                                                        <th>ประเภทลูกค้า</th>
                                                        <th>ชื่อลูกค้า</th>
                                                        <th>ผู้ขออนุมัติ</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            <form name="frm_del_newcust" id="frm_del_newcust">
                                                <input type="hidden" name="action" value="cus_del">
                                                <input type="hidden" name="csrf_securecode"
                                                    value="<?php echo $csrf_securecode ?>">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo md5($csrf_token) ?>">
                                                <input type="hidden" name="cus_app_nbr" value="">
                                            </form>
                                            <form name="frm_del_cr" id="frm_del_cr">
                                                <input type="hidden" name="action" value="cr_del">
                                                <input type="hidden" name="csrf_securecode"
                                                    value="<?php echo $csrf_securecode ?>">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo md5($csrf_token) ?>">
                                                <input type="hidden" name="cus_app_nbr" value="">
                                            </form>
                                            <form name="frm_link_cr" id="frm_link_cr">
                                                <input type="hidden" name="action" value="link_cr">
                                                <input type="hidden" name="csrf_securecode"
                                                    value="<?php echo $csrf_securecode ?>">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo md5($csrf_token) ?>">
                                                <input type="hidden" name="cus_app_nbr" value="">
                                            </form>
                                            <form name="frm_prt_form" id="frm_prt_form">
                                                <input type="hidden" name="action" value="print_form">
                                                <input type="hidden" name="csrf_securecode"
                                                    value="<?php echo $csrf_securecode ?>">
                                                <input type="hidden" name="csrf_token"
                                                    value="<?php echo md5($csrf_token) ?>">
                                                <input type="hidden" name="cus_app_nbr" value="">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- File export table -->
            </div>
        </div>
    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <!-- BEGIN: Footer-->
    <? include("../crctrlmain/menu_footer.php"); ?>
    <div class="to-top">
        <i class="fa fa-angle-up" aria-hidden="true"></i>
    </div>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/main.js"></script> <!-- to-Top -->
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
        $('#newcust_list').DataTable({
            "ajax": {
                url: "../serverside/n_newcust_list.php",

                type: "post",
                error: function() {
                    $("#newcust_list-error").html("No data available in table");
                    $("#newcust_list processing").css("display", "none");
                    $("#newcust_list").css("display", "none");
                }
            },
            "language": {
                "decimal": ",",
                "thousands": ".",
                "emptyTable": "No data available in table"

            },

            "columnDefs": [{
                    "className": "text-center",
                    "targets": [0, 1, 2, 3, 6]
                },


            ],

            "createdRow": function( row, data, dataIndex ) {
                if ( data['cus_new_info'] == "เปลี่ยนแปลงที่อยู่จดทะเบียน" ) {        
                    $(row).addClass('text-black bg-success bg-lighten-5');	  	 
                }
                if ( data['cus_new_info'] == "เปลี่ยนแปลงชื่อและที่อยู่" ) {        
                    $(row).addClass('text-black bg-danger bg-lighten-5');	  	 
                }
            },

            "columns": [{ // Add row no. (Line 1,2,3,n)
                    "data": "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },

                {
                    "data": "cus_app_nbr"
                },
                {
                    "data": "cus_date"
                },
                {
                    "data": "cus_new_info"
                },
                {
                    "data": "cus_reg_nme"
                },
                {
                    "data": "cus_create_by"
                },
                {
                    "render": function(data, type, row) {
                        if(row.cus_step_code == "0") {
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '0',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '0',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "10"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '10',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '10',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "21"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '21',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '21',$conn); echo $name; ?></span>';    
                        } else if(row.cus_step_code == "20"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '20',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '20',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "30"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '30',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '30',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "32"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '32',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '32',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "40"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '40',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '40',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "50"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '50',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '50',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "51"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '51',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '51',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "52"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '52',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '52',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "61"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '61',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '61',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "62"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '62',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '62',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "63"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '63',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '63',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "64"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '64',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '64',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "60"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '60',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '60',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "511"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '511',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '511',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "522"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '522',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '522',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "830"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '830',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '830',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "840"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '840',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '840',$conn); echo $name; ?></span>';
                        } else if(row.cus_step_code == "850"){
                            return '<span class="badge badge-pill ' + '<? $bg=findsqlval('cusstep_mstr', 'cusstep_color', 'cusstep_code', '850',$conn); echo $bg; ?>' + ' round btn-sm"><? $name=findsqlval('cusstep_mstr', 'cusstep_name_en', 'cusstep_code', '850',$conn); echo $name; ?></span>';
                        }    
                    }
                },
                {
                    "targets": [7],
                    "render": function(data, type, row, meta) {
                        var btnActionALL = "";
                        var btnAction_Edit = "";
                        var btnAction_Del = "";
                        var btnAction_View = "";
                        var btnAction_Prt = "";
                        var btnAction_Time = "";

                        <?php if($can_editing) { ?>
                            if(row.cus_step_code =="0" || row.cus_step_code =="21" || row.cus_step_code =="51" || row.cus_step_code =="52") { 	// Edit
                                if(row.cus_cond_cust=="c1" || row.cus_cond_cust=="c2"){
                                    var btnAction_Edit =
                                        '<a title="แก้ไขข้อมูลลูกค้า" id="btnEdit" data-directions="EDIT" data-cus_app_nbr=" ' +
                                        row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                        '"  href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>  ';
    
                                }    
                                else
                                {
                                    var btnAction_Edit =
                                        '<a title="แก้ไขข้อมูลลูกค้า" id="btnEdit" data-directions="EDIT_CH" data-cus_app_nbr=" ' +
                                        row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                        '"  href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>  ';
                                }    
                            }

                                if(row.cus_cond_cust=="c1" || row.cus_cond_cust=="c2"){
                                    var btnAction_View =
                                            '<a title="รายละเอียดข้อมูลลูกค้า" id="btnView" data-directions="VIEW" data-cus_app_nbr=" ' +
                                            row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                            '"  href="javascript:void(0)"><i class="fa fa-search-plus"></i></a>  ';   
                                    var btnAction_Time =
                                            '<a title="Timeline" id="btnTime" data-directions="TIME" data-cus_app_nbr=" ' +
                                            row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                            '"  href="javascript:void(0)">&nbsp;<i class="ft ft-map-pin"></i></a>  ';           

                                }
                                else
                                {
                                    var btnAction_View =
                                            '<a title="รายละเอียดข้อมูลลูกค้า" id="btnView" data-directions="VIEW_CH" data-cus_app_nbr=" ' +
                                            row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                            '"  href="javascript:void(0)"><i class="fa fa-search-plus"></i></a>  ';   
                                    var btnAction_Time =
                                            '<a title="Timeline" id="btnTime" data-directions="TIME" data-cus_app_nbr=" ' +
                                            row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                            '"  href="javascript:void(0)">&nbsp;<i class="ft ft-map-pin"></i></a>  ';      
           
                                }
                        <? } ?>    

                        <?php if($can_edit_cr) { ?>   // Edit Action_View1,Action_View2,Action_View3
                            if(row.cus_step_code =="10") { 	// สินเชื่อกรอกข้อมูลพิจารณา
                                if(row.cus_cond_cust=="c1" || row.cus_cond_cust=="c2"){
                                    var btnAction_Edit =
                                    '<a title="แก้ไขข้อมูลลูกค้า" id="btnEdit" data-directions="ADD_CR" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>  ';
                                }
                                else 
                                {
                                    var btnAction_Edit =
                                    '<a title="แก้ไขข้อมูลลูกค้า" id="btnEdit" data-directions="ADD_CR_CH" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>  ';
                                }
                            }
                            // สินเชื่อแก้ไขข้อมูลพิจารณา
                            if(row.cus_step_code.substring(0, 1) =="2" || row.cus_step_code.substring(0, 1) =="4" || row.cus_step_code.substring(0, 1) =="5" || row.cus_step_code.substring(0, 1) =="6") { 	
                            //if(row.cus_step_code =="20" ||  row.cus_step_code =="840") { 	
                                if(row.cus_cond_cust=="c1" || row.cus_cond_cust=="c2"){
                                    var btnAction_Edit =
                                    '<a title="แก้ไขข้อมูลลูกค้า" id="btnEdit" data-directions="EDIT_CR" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>  ';
                                }
                                else 
                                {
                                    var btnAction_Edit =
                                    '<a title="แก้ไขข้อมูลลูกค้า" id="btnEdit" data-directions="EDIT_CR_CH" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>  ';
                                }
                            }
                            // สินเชื่อดูรายละเอียด
                            if(row.cus_step_code.substring(0, 1) =="1" || row.cus_step_code.substring(0, 1) =="2" || row.cus_step_code.substring(0, 1) =="3" || row.cus_step_code.substring(0, 1) =="4" || row.cus_step_code.substring(0, 1) =="5" ||  row.cus_step_code.substring(0, 1) =="6") { 	
                                if(row.cus_cond_cust=="c1" || row.cus_cond_cust=="c2"){
                                    var btnAction_View =
                                    '<a title="รายละเอียดข้อมูลลูกค้า" id="btnView" data-directions="VIEW" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-search-plus"></i></a>  ';

                                    var btnAction_Time =
                                    '<a title="รายละเอียดข้อมูลลูกค้า" id="btnTime" data-directions="TIME" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)">&nbsp;<i class="ft ft-map-pin"></i></a>  ';

                                }
                                else 
                                {
                                    var btnAction_View =
                                    '<a title="รายละเอียดข้อมูลลูกค้า" id="btnView" data-directions="VIEW_CH" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-search-plus"></i></a>  ';

                                    var btnAction_Time =
                                    '<a title="รายละเอียดข้อมูลลูกค้า" id="btnTime" data-directions="TIME" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)">&nbsp;<i class="ft ft-map-pin"></i></a>  ';

                                }
                            }
                            if(row.cus_step_code == 10 || row.cus_step_code == 20) {
                                var btnAction_Del = '<a id="btnDelcr" data-cus_app_nbr ="' + row
                                        .cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                        '" href="javascript:void(0)"><i class="fa fa-trash-o fa-sm "></i></a>';
                            } 
                                  
                        <? } ?>
                       
                        <?php if($can_edit_mgr) { ?>   
                            // ผจก. แก้ไข
                            if(row.cus_step_code.substring(0, 1) =="3") { 	
                                if(row.cus_cond_cust=="c1" || row.cus_cond_cust=="c2"){
                                    var btnAction_Edit =
                                    '<a title="แก้ไขข้อมูลลูกค้า" id="btnEdit" data-directions="EDIT_CR" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>  ';
                                }
                                else 
                                {
                                    var btnAction_Edit =
                                    '<a title="แก้ไขข้อมูลลูกค้า" id="btnEdit" data-directions="EDIT_CR_CH" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>  ';
                                }
                                   
                            }
                            // ผจก. ดูรายละเอียด
                            if(row.cus_step_code.substring(0, 1) =="3" || row.cus_step_code.substring(0, 1) =="4" || row.cus_step_code.substring(0, 1) =="5" ||  row.cus_step_code.substring(0, 1) =="6" ||  row.cus_step_code.substring(0, 1) =="8") { 	
                                if(row.cus_cond_cust=="c1" || row.cus_cond_cust=="c2"){
                                    var btnAction_View =
                                    '<a title="รายละเอียดข้อมูลลูกค้า" id="btnView" data-directions="VIEW" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-search-plus"></i></a>  ';

                                    var btnAction_Time =
                                    '<a title="รายละเอียดข้อมูลลูกค้า" id="btnTime" data-directions="TIME" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)">&nbsp;<i class="ft ft-map-pin"></i></a>  ';

                                }
                                else 
                                {
                                    var btnAction_View =
                                    '<a title="รายละเอียดข้อมูลลูกค้า" id="btnView" data-directions="VIEW_CH" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)"><i class="fa fa-search-plus"></i></a>  ';

                                    var btnAction_Time =
                                    '<a title="รายละเอียดข้อมูลลูกค้า" id="btnTime" data-directions="TIME" data-cus_app_nbr=" ' +
                                    row.cus_app_nbr + '" data-cus_id="' + row.cus_id +
                                    '"  href="javascript:void(0)">&nbsp;<i class="ft ft-map-pin"></i></a>  ';

                                }
                            }
                        <? } ?>

                        if(row.cus_step_code == 0) {
                                var btnAction_Del = ' <a id="btnDelcus" data-cus_app_nbr ="' + row
                                        .cus_app_nbr + '" data-cus_id="' + row.cus_id + '" data-cus_new_info="' + row.cus_new_info +
                                        '" href="javascript:void(0)"><i class="fa fa-trash-o fa-sm "></i></a>';
                        } 
                        if(row.cus_step_code == 60) {
                            var btnAction_Prt = ' <a id="btprt" data-cus_app_nbr ="' + row.cus_app_nbr + '" data-cus_id="' +row.cus_id + '"  href="javascript:void(0)"><i class="ft-printer"></i></a>';
                        }

                            btnActionALL = btnAction_Edit + btnAction_View + btnAction_Del + btnAction_Prt + btnAction_Time; 

                        return btnActionALL;
                    }
                },
                {
                    "data": "cus_id",
                    "visible": false
                }

            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "order": [
                [0, "asc"]
            ],
            "ordering": false,
            "stateSave": true,
            "pageLength": 10,
            "pagingType": "simple_numbers",
        });
    });

    $(document).on('click', '#btnDelcus', function(e) {
        var cus_app_nbr = $(this).data('cus_app_nbr');
        var cus_new_info = $(this).data('cus_new_info');

        Swal.fire({
            title: "Are you sure?",
            html: "คุณต้องการลบข้อมูล"  + <? echo cus_new_info; ?> + "<br>" + "เอกสารเลขที่ " +<? echo cus_app_nbr; ?> +" ใช่หรือไหม่ !!!! ",
            type : "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: false,
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve) {
                    document.frm_del_newcust.cus_app_nbr.value = cus_app_nbr;
                    $.ajax({
                        beforeSend: function() {
                            //$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
                            //$("#requestOverlay").show();/*Show overlay*/
                        },
                        type: 'POST',
                        url: '../serverside/n_newcust_post.php',
                        data: $('#frm_del_newcust').serialize(),
                        timeout: 50000,
                        error: function(xhr, error) {
                            showmsg('[' + xhr + '] ' + error);
                        },
                        success: function(result) {
                            //console.log(result);
                            //alert(result);
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
                                    type: "success",
                                    title: "ลบข้อมูลเรียบร้อบแล้ว",
                                    showConfirmButton: false,
                                    timer: 1500,
                                    confirmButtonClass: "btn btn-primary",
                                    buttonsStyling: false,
                                    animation: false,
                                });
                                location.reload(true);
                                $(location).attr('href',
                                    'newcust_list.php?q=' + json
                                    .nb)
                            }
                        },
                        complete: function() {
                            $("#requestOverlay")
                                .remove(); /*Remove overlay*/
                        }
                    });
                });
            },
            allowOutsideClick: false
        });
        e.preventDefault();
    });

    $(document).on('click', '#btnView,#btnEdit,#btnTime', function(e) {
        var cus_app_nbr = $(this).data('cus_app_nbr');
        var cus_id = $(this).data('cus_id');
        var directions = $(this).data('directions');

        document.frm_link_cr.cus_app_nbr.value = cus_app_nbr;
        document.frm_link_cr.cus_id = cus_id;

        $.ajax({
            type: 'POST',
            url: '../serverside/n_newcust_post.php',
            data: $('#frm_link_cr').serialize(),
            timeout: 3000,
            error: function(xhr, error) {
                showmsg('[' + xhr + '] ' + error);
            },
            success: function(result) {
                //console.log(result);
                //alert(result);
                var json = $.parseJSON(result);
                if (json.r == '0') {
                    Swal.fire({
                        title: "Error!",
                        html: json.e,
                        type: "error",
                        confirmButtonClass: "btn btn-danger",
                        buttonsStyling: false
                    });
                } else {

                    if (directions == "VIEW") {
                        var Linkdirections = '../view/view_newcusmnt.php?q=' + json.nb +
                            '&current_tab=10&pg=' + json.pg;
                    } else if (directions == "VIEW_CH") {
                        var Linkdirections = '../view/view_chgcusmnt.php?q=' + json.nb;      
                    } else if (directions == "TIME") {
                        var Linkdirections = '../dashboard/timeline-left.php?q=' + json.nb; 
                    } else if (directions == "EDIT") {
                        var Linkdirections = 'upd_newcusmnt.php?q=' + json.nb;
                            '&current_tab=10&pg=' + json.pg;
                    } else if (directions == "EDIT_CH") {
                        var Linkdirections = 'upd_chgcusmnt.php?q=' + json.nb;
                    } else if (directions == "ADD_CR") {
                        var Linkdirections = '../crcust/cr_newcusmnt.php?q=' + json.nb;
                    } else if (directions == "ADD_CR_CH") {
                        var Linkdirections = '../crcust/cr_chgcusmnt.php?q=' + json.nb;  
                    } else if (directions == "EDIT_CR") {
                        var Linkdirections = '../crcust/upd_cr_newcusmnt.php?q=' + json.nb;
                    } else if (directions == "EDIT_CR_CH") {
                        var Linkdirections = '../crcust/upd_cr_chgcusmnt.php?q=' + json.nb;      
                    } 
                    $(location).attr('href', Linkdirections)
                }
            },
            complete: function() {
                $("#requestOverlay").remove();
            }
        });
    });

    $(document).on('click', '#btnDelcr', function(e) {
        var cus_app_nbr = $(this).data('cus_app_nbr');
        Swal.fire({
            title: "ยืนยันการยกเลิกข้อมูล ?",
            html: "คุณต้องการยกเลิก " + <? echo cus_app_nbr; ?> + " ของแผนกสินเชื่อ ใช่หรือไหม่ !!! ",
            type : "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: false,
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve) {
                    document.frm_del_cr.cus_app_nbr.value = cus_app_nbr;
                    $.ajax({
                        beforeSend: function() {
                            //$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
                            //$("#requestOverlay").show();/*Show overlay*/
                        },
                        type: 'POST',
                        url: '../serverside/n_newcust_post.php',
                        data: $('#frm_del_cr').serialize(),
                        timeout: 50000,
                        error: function(xhr, error) {
                            showmsg('[' + xhr + '] ' + error);
                        },
                        success: function(result) {
                            //console.log(result);
                            //alert(result);
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
                                    type: "success",
                                    title: "ยกเลิกข้อมูลเรียบร้อบแล้ว",
                                    showConfirmButton: false,
                                    timer: 1500,
                                    confirmButtonClass: "btn btn-primary",
                                    buttonsStyling: false,
                                    animation: false,
                                });
                                location.reload(true);
                                $(location).attr('href',
                                    'newcust_list.php?q=' + json
                                    .nb)
                            }
                        },
                        complete: function() {
                            $("#requestOverlay")
                                .remove(); /*Remove overlay*/
                        }
                    });
                });
            },
            allowOutsideClick: false
        });
        e.preventDefault();
    });

    $(document).on('click', '#btprt', function(e) {
        var crnumber = $(this).data('cus_app_nbr');
        document.frm_prt_form.cus_app_nbr.value = crnumber;
        $.ajax({
            type: 'POST',
            url: '../serverside/n_newcust_post.php',
            data: $('#frm_prt_form').serialize(),
            timeout: 3000,
            error: function(xhr, error) {
                showmsg('[' + xhr + '] ' + error);
            },
            success: function(result) {
            //console.log(result);
            //alert(result);
                var json = $.parseJSON(result);
                if (json.r == '0') {
                    Swal.fire({
                        title: "Error!",
                        html: json.e,
                        type: "error",
                        confirmButtonClass: "btn btn-danger",
                        buttonsStyling: false
                    });
                } else {
                window.open('../crcust/cr_form_newcus.php?crnumber=' + json.nb , '_blank');
                }
            },
            complete: function() {
                $("#requestOverlay").remove(); 
            }
        });
    });
    
    function loadresult() {
        document.all.result.innerHTML =
            "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
    }

    function showdata() {
        var errorflag = false;
        var errortxt = "";
        document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
        if (errorflag) {
            document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
            $("#myModal").modal("show");
        } else {
            loadresult()
            document.frm.submit();
        }
    }

    function gotopage(mypage) {
        loadresult()
        document.frm.pg.value = mypage;
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

</html>