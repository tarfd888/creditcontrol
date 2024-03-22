<?php
include("_incs/acunx_metaheader.php");
include("_incs/config.php"); 	
include("_incs/funcServer.php");	
include("_incs/acunx_cookie_var.php");
include "_incs/acunx_csrf_var.php";
include("_libs/Thaidate/Thaidate.php");
include("_libs/Thaidate/thaidate-functions.php");

$msg = decrypt($_REQUEST['msg'],$key);
$curdate = date('Ymd');
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
    <title><?php echo TITLE?></title>
    <link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/pages/gallery.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/assets/css/style.css">
</head>

<body class="vertical-layout vertical-menu 1-column  bg-maintenance-image menu-expanded blank-page blank-page"
    data-open="click" data-menu="vertical-menu" data-col="1-column">

   <!-- header -->
   <nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
        <div class="navbar-wrapper">
            <h4 class="text-bold-600 blue text-center p-2">WELCOME TO WEB CREDITCONTROL</h4>
        </div>
    </nav>
   <!-- end header -->

    <div class="app-content content p-3">
            <div class="content-body mt-3">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="grid-hover row">
                            <div class="col-md-6 col-12">
                                <figure class="effect-bubba">
                                    <img src="<?php echo BASE_DIR;?>/theme/app-assets/images/gallery/9.jpg"
                                        alt="img09" />
                                    <figcaption>
                                        <h2>SCGC</h2>
                                        <p class="p-1">SCG CERAMICS PUBLIC CO.,LTD.</p>
                                        <a href="login.php">View more</a>
                                    </figcaption>
                                </figure>
                            </div>
                            <div class="col-md-6 col-12">
                                <figure class="effect-bubba">
                                    <img src="<?php echo BASE_DIR;?>/theme/app-assets/images/gallery/10.jpg"
                                        alt="img10" />
                                    <figcaption>
                                        <!-- <h2>Warm <span>Oscar</span></h2> -->
                                        <h2>SSW</h2>
                                        <p class="p-1">SIAM SANITARY WARE INDUSTRY CO., LTD.</p>
                                        <a href="<?php echo BASE_WARE_DIR;?>/login.php">View more</a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
    </div>

    <? include("crctrlmain/menu_footer.php"); ?>

    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
</body>

</html>