<?php

//Mail Approve & Acunx
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
//Double Cookies
session_start();
$sessionid = session_id();
$sessionid_enc = encrypt($sessionid, $key);
$expire=0;
setcookie ("rev2_verify_csrf_mail", $sessionid_enc,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);

clearstatcache();
$crstm_approve_nbr = html_escape($_REQUEST['nbr']);  // crstm_nbr
$crstm_approve_select = html_escape($_REQUEST['act']);  // step_code
$crstm_approved_by = html_escape($_REQUEST['id']);  // ผู้พิจารณา , ผู้อนุมัติ 

// $crstm_approve_select = decrypt($crstm_approve_select, $key);
// $crstm_approve_nbr = decrypt($crstm_approve_nbr, $key);
// $crstm_approved_by = decrypt($crstm_approved_by, $key);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"> 
    <title><?php echo TITLE; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<script src="../_libs/js/jquery-2.1.4.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function () {
			var result_text = "";
				$('#div_result').html("<img src='../_images/Submitting.gif' width=50%>");
				$.ajax({
					type: 'POST',
					url: '../serverside/cr_verify_rev2_post.php',
					data: $('#frmapprove').serialize(),
					timeout: 50000,
					error: function(xhr, error){
						$('#div_result').html("<span style='color:red'>["+xhr+"] "+ error+"</span>");
						setTimeout(function(){ window.close() (); }, 5000);
					},
					success: function(result) {	
						//alert(result);
						console.log(result);
						var json = $.parseJSON(result);
						if (json.r == '0') {
							result_text += "<span style='color:red'><h4 style='text-align:center'>[ ** ดำเนินการไม่สำเร็จ ** ] </h4></span>";
							if (json.e != "") {
								//result_text += json.e;
								result_text += "<span style='color:red'><h4 style='text-align:center'>" + json.e + "</h4></span>";
							}
							$('#div_result').html(result_text);
							setTimeout(function(){ window.close() (); }, 3000);
						}
						else {
							//result_text += "<span style='color:green'><h4 style='text-align:center'>[ ** ดำเนินการสำเร็จ ** ] </h4></span>";
							if (json.e != "") {
								//result_text += "<br><span style='color:red'>" + json.e + "</span>";
								result_text += "<span style='color:green'><h4 style='text-align:center'>[ ** ดำเนินการสำเร็จ ** ] </h4></span>";
							}
							$('#div_result').html(result_text);
							setTimeout(function(){ window.close() (); }, 3000);
						}
					},
				});
		});
	</script>
	
</head>
<body>	
	<div id="div_result"></div>
	<!--<center><h2>Welcome to Credit Control </h2></center>
	<center><h3>Step_code <?php echo $crstm_approve_select ?></h3></center>
	<center><h3>No. <?php echo $crstm_approve_nbr ?></h3></center>
	<center><h3>User Approve. <?php echo $crstm_approved_by ?></h3></center>-->
	<form id="frmapprove" name="frmapprove" method="post">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input type="hidden" name="crstm_approve_nbr" value="<?php echo $crstm_approve_nbr?>">
		<input type="hidden" name="crstm_approve_select" value="<?php echo $crstm_approve_select?>">
		<input type="hidden" name="crstm_approved_by" value="<?php echo $crstm_approved_by?>">
	</form>				
</body>
</html>
