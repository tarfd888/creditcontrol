<?php 
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
//include("../_incs/acunx_csrf_var.php");

//Double Cookies
session_start();
$sessionid = session_id();
$sessionid_enc = encrypt($sessionid, $key);
$expire=0;
setcookie ("rev_verify_csrf_mail", $sessionid_enc,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
clearstatcache();

$cus_approve_nbr = $_REQUEST['nbr'];  // cr_app_nbr
$cus_approve_step = $_REQUEST['act'];  // apprv_nextstep_cusstep_code
$cus_approved_by = $_REQUEST['id'];  // ผู้อนุมัติ 
$cus_auth_code = $_REQUEST['auth'];  

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
  	<title><?php echo(TITLE) ?></title>
    <script type="text/javascript">
		$(document).ready(function () {  
			var result_text = "";
			$('#div_result').html("<img src='../_images/Submitting.gif' width=50%>");
			$.ajax({
				type: 'POST',
				url: '../serverside/n_sendmail_reviewer.php',
				data: $('#frmapprove').serialize(),
				timeout: 50000,
				error: function(xhr, error){
					$('#div_result').html("<span style='color:red'>["+xhr+"] "+ error+"</span>");
					setTimeout(function(){ window.close() ; }, 2000);
				},
				success: function(result) {	
					//alert(result);
					console.log(result);
					var json = $.parseJSON(result);
					if (json.r == '0') {
						result_text += "<span style='color:red'><h4 style='text-align:center'>[ ** ดำเนินการไม่สำเร็จ ** ] </h4></span>";
						if (json.e != "") {
							result_text += "<span style='color:red'><h4 style='text-align:center'>" + json.e + "</h4></span>";
							//result_text += json.e;
						}
						$('#div_result').html(result_text);
						setTimeout(function(){ window.close() ; }, 2000);
					}
					else {
						result_text += "<span style='color:green'><h4 style='text-align:center'>[ ** ดำเนินการสำเร็จ ** ] </h4></span>";
						$('#div_result').html(result_text);
						setTimeout(function(){ window.close() ; }, 2000);
					}
				},
			});
		});		
	</script>
</head>
<body>
    <div id="div_result"></div>
	<form id="frmapprove" name="frmapprove" method="post">
		<input type="hidden" name="action" id="action" value="">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input type="hidden" name="cus_approve_nbr" value="<?php echo $cus_approve_nbr?>">
		<input type="hidden" name="cus_approve_step" value="<?php echo $cus_approve_step?>">
		<input type="hidden" name="cus_approved_by" value="<?php echo $cus_approved_by?>">
		<input type="hidden" name="cus_auth_code" value="<?php echo $cus_auth_code?>">
	</form>		
</body>
</html>