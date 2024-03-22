<?php
include("_incs/acunx_metaheader.php");
include("_incs/config.php"); 	
include("_incs/funcServer.php");	
include("_incs/acunx_cookie_var.php");
include "_incs/acunx_csrf_var.php";
$msg = decrypt($_REQUEST['msg'],$key);
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/custom.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/pages/login-register.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/pages/under-maintenance.css">
  </head>
  <body class="vertical-layout vertical-menu 1-column  bg-maintenance-image menu-expanded blank-page blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">		
   <div class="app-content content">
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body"><section class="flexbox-container">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-md-4 col-10 box-shadow-2 p-0">
            <div class="card border-grey border-lighten-3 m-0">
                <div class="card-header border-0">
                    <!--<div class="card-title text-center">
                        <div class="p-1"><img src="theme/app-assets/images/logo/logo-dark.png" alt="branding logo"></div>
                    </div>-->
                    <h3 class="card-subtitle text-center  pt-2"><span class="text-bold-600 blue">SCG CERAMICS PUBLIC CO.,LTD.</span></h3>
					
					<div class="d-flex justify-content-center pt-2">
						<h4 class="text-bold-600 blue">Creditcontrol</h4>
					</div>
									
                </div>
                <div class="card-content">
                    <div class="card-body">
						<form class="form-horizontal form-simple" id="frmlogin" name="frmlogin"  method="post" novalidate="novalidate"  action="#">
						<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
						<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                            <fieldset class="form-group position-relative has-icon-left mb-0">
                                <input type="text" class="form-control form-control-lg input-lg" id="user_login" name ="user_login" autocomplete="off" placeholder="Your Username" required>
                                <div class="form-control-position">
                                    <i class="ft-user"></i>
                                </div>
                            </fieldset>
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="password" class="form-control form-control-lg input-lg" id="user_passwd" name ="user_passwd" autocomplete="off" placeholder="Enter Password" required>
                                <div class="form-control-position">
                                    <i class="fa fa-key"></i>
                                </div>
                            </fieldset>
                            <div class="form-group row">
                                <div class="col-md-6 col-12 text-center text-md-left">
                                    <fieldset>
                                        <!--<input type="checkbox" id="remember-me" class="chk-remember">
                                        <label for="remember-me"> Remember Me</label>-->
                                    </fieldset>
                                </div>
                                <!--<div class="col-md-6 col-12 text-center text-md-right"><a href="pwdreset.php" class="card-link">Forgot Password?</a></div>-->
                            </div>
                           <button type="submit" id="btn-login" value="Login" class="btn btn-info btn-lg btn-block" ><i class="ft-unlock"></i> Login</button>
					   </form>
                    </div>
                </div>
                <div class="card-footer">
                    <!--<div class="">-->
					<div class="d-flex justify-content-center text-info text-bold-600">
                        <!--<p class="float-sm-left text-center m-0"><a href="recover-password.php" class="card-link">Recover password</a></p>
                        <p class="float-sm-right text-center m-0">New to Moden Admin? <a href="register-simple.php" class="card-link">Sign Up</a></p>-->
						<?php echo $web_version; ?>
					</div>
                </div>
            </div>
        </div>
    </div>
</section>

	</div>
  </div>
</div>

<!-- BEGIN VENDOR JS-->
<script src="theme/app-assets/vendors/js/vendors.min.js"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<script src="theme/app-assets/vendors/js/ui/jquery.sticky.js"></script>
<script src="theme/app-assets/vendors/js/forms/icheck/icheck.min.js"></script>
<script src="theme/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
<script src="theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<!-- END PAGE VENDOR JS-->
<!-- BEGIN ROBUST JS-->
<script src="theme/app-assets/js/core/app-menu.js"></script>
<script src="theme/app-assets/js/core/app.js"></script>
<!-- END ROBUST JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="theme/app-assets/js/scripts/forms/form-login-register.js"></script>
<!-- END PAGE LEVEL JS-->
	<script language="javascript">
		$(document).ready(function () {
            $('#user_login').focus();
        });
        $(document).on("click", "#btn-login", function(e) {
            var errorflag = false;
            var errortxt = "";
                e.preventDefault();
                $.ajax({
                    beforeSend: function () {						 
                    },
                    type: 'POST',
                    url: '_incs/check_login.php',
                    data: $('#frmlogin').serialize(),
                    timeout: 5000,
                    error: function(xhr, error){
                        alert('['+xhr+'] '+ error);
                    },
                    success: function(result) {
                        console.log(result);
                        //alert(result);
                        var json = $.parseJSON(result);
                        if (json.r == '0') {
                            Swal.fire({
                                title: "Warning!",
                                html: json.e,
                                type: "warning",
                                confirmButtonClass: "btn btn-warning",
                                buttonsStyling: false
                            });		
                        } 
                        else {								
                            $(location).attr('href', json.home);
                        }	
                    },
                    complete: function () {
                        
                    }
                }); 
        });
	</script>
  </body>
</html>