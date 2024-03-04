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

    $action_isDom = decrypt(mssql_escape($_REQUEST['isDom']), $key);
    if($action_isDom=="1"){
        $txtinfo = 'ในประเทศ';
    }else {
        $txtinfo = 'ต่างประเทศ';
    }


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
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
</head>

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="click"
    data-menu="vertical-menu" data-col="2-columns">
    <div id="result"></div>
    <?php include("../crctrlmain/menu_header.php"); ?>
    <?php include("../crctrlmain/menu_leftsidebar.php"); ?>
    <?php include("../crctrlmain/modal.php"); ?>
    <div class="app-content content font-small-2">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Download </h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../newcust/newcust_list.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    <font color="40ADF4">แบบฟอร์ม</font>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-md-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-top-bottom-center">ดาวน์โหลดแบบฟอร์มแต่งตั้งลูกค้า<?php echo $txtinfo; ?></h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <!-- <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li> -->
                                    <!-- <li><a data-action="close"><i class="ft-x"></i></a></li> -->
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collpase show">
                            <div class="card-body">
                            <?php if ($action_isDom == "1") { ?>
                                <div class="card">
                                    <div class="card-content">
                                        <div class="media align-items-stretch">
                                            <div class="p-2 bg-warning text-white media-body text-left rounded-left">
                                                <h5 class="text-white">สัญญาการแต่งตั้งเป็นตัวแทนจำหน่าย</h5>
                                            </div>
                                            <div class="p-2 text-center bg-warning bg-darken-2 rounded-right">
                                                <a class="menu-link"
                                                    href="../_filedownloads/DL_FORM/Domestic_1_สัญญาการแต่งตั้งเป็นผู้แทนจำหน่าย.pdf"
                                                    download="Domestic_1_สัญญาการแต่งตั้งเป็นผู้แทนจำหน่าย.pdf"
                                                    target="_blank">
                                                    <i class="fa fa-download font-large-2 text-white"></i>
                                            </div></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-content">
                                        <div class="media align-items-stretch">
                                            <div class="p-2 bg-info text-white media-body text-left rounded-left">
                                                <h5 class="text-white">เอกสารประกอบการแต่งตั้งลูกใหม่</h5>
                                            </div>
                                            <div class="p-2 text-center bg-info bg-darken-2 rounded-right">
                                                <a class="menu-link"
                                                    href="../_filedownloads/DL_FORM/Domestic_2_CheckList_Request_Document.pdf"
                                                    download="Domestic_2_CheckList_Request_Document.pdf"
                                                    target="_blank">
                                                    <i class="fa fa-download font-large-2 text-white"></i>
                                            </div></a>
                                        </div>
                                    </div>
                                </div>
                            <? } ?>    

                            <?php if ($action_isDom == "2") { ?>
                                <div class="card">
                                    <div class="card-content">
                                        <div class="media align-items-stretch">
                                            <div class="p-2 bg-warning text-white media-body text-left rounded-left">
                                                <h5 class="text-white">Customer Application Form And Sale Contract</h5>
                                            </div>
                                            <div class="p-2 text-center bg-warning bg-darken-2 rounded-right">
                                                <a class="menu-link"
                                                    href="../_filedownloads/DL_FORM/Export_1_Application_SaleContract.docx"
                                                    download="Export_1_Application_SaleContract.docx"
                                                    target="_blank">
                                                    <i class="fa fa-download font-large-2 text-white"></i>
                                            </div></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-content">
                                        <div class="media align-items-stretch">
                                            <div class="p-2 bg-info text-white media-body text-left rounded-left">
                                                <h5 class="text-white">Credit Application Form</h5>
                                            </div>
                                            <div class="p-2 text-center bg-info bg-darken-2 rounded-right">
                                                <a class="menu-link"
                                                    href="../_filedownloads/DL_FORM/Export_2_CreditApplication.docx"
                                                    download="Export_2_CreditApplication.docx"
                                                    target="_blank">
                                                    <i class="fa fa-download font-large-2 text-white"></i>
                                            </div></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-content">
                                        <div class="media align-items-stretch">
                                            <div class="p-2 bg-success text-white media-body text-left rounded-left">
                                                <h5 class="text-white">เอกสารประกอบการแต่งตั้งลูกใหม่</h5>
                                            </div>
                                            <div class="p-2 text-center bg-success bg-darken-2 rounded-right">
                                                <a class="menu-link"
                                                    href="../_filedownloads/DL_FORM/Export_3_CheckList_Request_Document.xlsx"
                                                    download="Export_3_CheckList_Request_Document.xlsx"
                                                    target="_blank">
                                                    <i class="fa fa-download font-large-2 text-white"></i>
                                            </div></a>
                                        </div>
                                    </div>
                                </div>
                            <? } ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    </div>
    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <!-- BEGIN: Footer-->
    <? include("../crctrlmain/menu_footer.php"); ?>

    <!-- END: Footer-->
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>



</body>

</html>