<?php 
	//Update can_editing
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}

?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <head>
   <title><?php echo(TITLE) ?></title>
	<link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
	<link rel="stylesheet" href="_libs/css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/pages/under-maintenance.css">
  
  </head>
  <body class="vertical-layout vertical-menu 1-column  bg-maintenance-image menu-expanded blank-page blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">
    <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
		</div>
        <div class="content-body">
			<section class="flexbox-container">
				<div class="col-12 d-flex align-items-center justify-content-center">
					<div class="col-md-4 col-10 box-shadow-2 p-0">
						<div class="card border-grey border-lighten-3 px-1 py-1 box-shadow-3 m-0">
							<div class="card-body">
								<span class="card-title text-center">
									<h1 class="modal-title" id="msghead"><font class="text text-warning font-weight-bold">Warning !!! </font></h1>
									<!--<img src="<?php echo BASE_DIR;?>/theme/app-assets/images/logo/logo-dark-lg.png" class="img-fluid mx-auto d-block pt-2" width="250" alt="logo">-->
								</span>
							</div>
							<div class="card-body text-center">
								<h4>คุณไม่สามารถสร้างใบขออนุมัติวงเงินสินเชื่อ<br><br> ในขณะนี้ได้เพราะมีการขออนุมัติก่อนหน้านี้แล้ว</h4>
								<div class="mt-2"><i class="fa fa-cog spinner font-large-2"></i></div>
							</div>
							<hr>
							<p class="socialIcon card-text text-center pt-2 pb-2">
								<a href="../crctrlbof/crctrlall.php" class="btn btn-social-icon mr-1 mb-1 btn-outline-linkedin"><span class="fa fa-times font-meduim-4"></span></a>
							</p>
						</div>
					</div>
				</div>
			</section>
        </div>
      </div>
    </div>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
  </body>
</html>