<?php 
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");

//Double Cookies
session_start();
$sessionid = session_id();
$sessionid_enc = encrypt($sessionid, $key);
$expire=0;
setcookie ("rev_verify_csrf_mail", $sessionid_enc,$expire,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
//
clearstatcache();

//$crstm_auth_code = $_REQUEST['auth'];
$crstm_approve_nbr = $_REQUEST['nbr'];  // crstm_nbr
$crstm_approve_select = $_REQUEST['act'];  // step_code
$crstm_approved_by = $_REQUEST['id'];  // ผู้พิจารณา , ผู้อนุมัติ 
$crstm_auth_code = $_REQUEST['auth'];  

//$crstm_approve_select = decrypt($crstm_approve_select, $key);
//$crstm_approve_nbr = decrypt($crstm_approve_nbr, $key);
//$crstm_approved_by = decrypt($crstm_approved_by, $key);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
    <title>Document</title>
    <script type="text/javascript">
		$(document).ready(function () {  
			var result_text = "";
			$('#div_result').html("<img src='../_images/Submitting.gif' width=50%>");
			$.ajax({
				type: 'POST',
				// ส่งผ่านไฟล์ pdf
				url: '../serverside/cr_verify_rev1_post.php',
				data: $('#frmapprove').serialize(),
				timeout: 50000,
				error: function(xhr, error){
					$('#div_result').html("<span style='color:red'>["+xhr+"] "+ error+"</span>");
					setTimeout(function(){ window.close() (); }, 2000);
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
						setTimeout(function(){ window.close() (); }, 2000);
					}
					else {
						result_text += "<span style='color:green'><h4 style='text-align:center'>[ ** ดำเนินการสำเร็จ ** ] </h4></span>";
						$('#div_result').html(result_text);
						setTimeout(function(){ window.close() (); }, 2000);
					}
				},
			});
		});		
	</script>
</head>
<body>
    <!--<center><h2>Welcome to Credit Control </h2></center>
	<center><h3>Step_code <?php echo $crstm_approve_select ?></h3></center>
	<center><h3>No. <?php echo $crstm_approve_nbr ?></h3></center>
	<center><h3>User Approve. <?php echo $crstm_approved_by ?></h3></center>
	<center><h3>Auth <?php echo $crstm_auth_code ?></h3></center>-->
    <div id="div_result"></div>
	<form id="frmapprove" name="frmapprove" method="post">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input type="hidden" name="crstm_approve_nbr" value="<?php echo $crstm_approve_nbr?>">
		<input type="hidden" name="crstm_approve_select" value="<?php echo $crstm_approve_select?>">
		<input type="hidden" name="crstm_approved_by" value="<?php echo $crstm_approved_by?>">
		<input type="hidden" name="crstm_auth_code" value="<?php echo $crstm_auth_code?>">
	</form>		
</body>
</html>