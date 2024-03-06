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

set_time_limit(0);
$curdate = date('Ymd');
$msg=html_escape($_REQUEST['msg']);
clearstatcache();
include("../crctrlbof/chkauthcr.php");
include("../crctrlbof/chkauthcrctrl.php");
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<meta name="description" content="Robust admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template.">
		<meta name="keywords" content="admin template, robust admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
		<meta name="author" content="PIXINVENT">
		<title>Credit Control</title>
		<link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
		<!-- BEGIN VENDOR CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
	
		<!-- END VENDOR CSS-->
		
		<!-- BEGIN ROBUST CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<!-- END ROBUST CSS-->
		
		<!-- BEGIN Page Level CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/meteocons/style.min.css">
		<!-- END Page Level CSS-->
		
		<!-- BEGIN Custom CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/assets/css/style.css">
		<!-- END Custom CSS-->
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="content-detached-left-sidebar">

    <?php include("../crctrlmain/menu_header.php"); ?>
	<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
	
    <!-- BEGIN: Content-->
    <div class="app-content content font-small-2">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row mt-n1">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">

                    </div>

                </div>

            </div>
            <div class="content-body">
                <!-- Project All -->
                <section id="project-all">
                   <div class="row match-height">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title" id="basic-layout-icons"><font color="1E90FF">Upload Credit Account Master</font></h4>
										<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
										<div class="heading-elements">
											<ul class="list-inline mb-0">
												<!--<li><a data-action="collapse"><i class="ft-minus"></i></a></li>
												<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>-->
												<li><a data-action="expand"><i class="ft-maximize"></i></a></li>
												<!--<li><a data-action="close"><i class="ft-x"></i></a></li>-->
											</ul>
										</div>
									</div>
									<div class="card-content collpase show">
										<div class="card-body">
											<div class="card-block">
												
												<form name="frmimport" id="frmimport"  class="form" action="importdata_acc.php" method="post" enctype="multipart/form-data" onsubmit="return CheckValidFile()">
													<div class="form-body">
														<? if($msg!="") echo "<center><h5 style='line-height:40px; border-bottom:1px solid #5E5E5E;'><div class='btn btn-danger active'  style='line-height:30px;'><i class='icon-warning'></i>  ".$msg."</div><br><div class='tag tag-pill tag-warning'>Warning !</div> Some Sheet Name is not Country Name.<br><i class='icon-spell-check'></i> Please Try Again.</h5></center><br>"; ?>
														
														<div class="alert alert-info alert-white rounded">
															<button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button>
															<i class="icon-info"></i>
															<strong>Warning</strong> 
															 <h4><font color="">คุณไม่มีสิทธิในการใช้งานหน้านี้</font></h4><br>
															 <h4><font color="">หากคุณเป็นบุคคลที่ต้องทำงานในกลุ่มงานนี้คุณสามารถแจ้ง</font></h4><br>
															 <h4><font color="">ผู้ดูแลระบบเพื่อปรับสิทธิการใช้งานให้สามารถใช้งานหน้านี้ได้</font></h4><br>
														</div>  
														
														
												</div>
												
												
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
    <!-- END: Content-->

   <div class="sidenav-overlay"></div>
		<div class="drag-target"></div>
		
		<!-- BEGIN: Footer-->
		<? include("../crctrlmain/menu_footer.php"); ?>
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
		<!-- END: Page JS-->



</body>
<!-- END: Body-->

</html>