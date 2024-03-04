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
$cus_approve_seq = $_REQUEST['seq'];  // ลำดับ
$action_type = html_escape($_REQUEST['ch']);

//test
$can_cmmt_email = True;
//$action_type = "Approve";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
	<link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700"
    rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
	<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
  	<title><?php echo(TITLE) ?></title>
    <script type="text/javascript">
		$(document).ready(function () {  
			function escapeHtml(text) {
				var map = {
					'&': '&amp;', '<': '&lt;', '>': '&gt;',	'"': '&quot;',	"'": '&#039;'
				};			  
				return text.replace(/[&<>"']/g, function(m) { return map[m]; });
			}
			Swal.fire({
				type: "info",
				title: '<p class="font-medium-3" >คุณกำลังดำเนินการ <span class="red">' + '<?php echo $action_type; ?></span>' + ' เอกสาร <br>เลขที่ ' + '<span class="red"><?php echo decrypt($cus_approve_nbr,$key); ?></span>' + '<br><br>*** ระบุเหตุผล (ถ้ามี) ***<br>(เป็นตัวอักษรเท่านั้น)</p>',
				input: 'textarea',
				confirmButtonText: "บันทึกข้อมูล",
				customClass: {
					confirmButton: "btn btn-info"
				},
			}).then(function(res) {
				var cmmt = "";
				if (res.value) {
					//กรอกข้อความแล้ว Submit
					cmmt = escapeHtml(res.value);
				} else {
					cmmt = "";
				}
				postdata ('<?php echo $can_cmmt_email; ?>',cmmt);
			}); 			
			
			
			function postdata (can_cmmt,cmmt) {
				if(can_cmmt == true || can_cmmt == '1' || can_cmmt == 1) {						
					var formData = $('#frmapprove').serialize() + "&cmmt=" + cmmt;
				}
				else {
					var formData = $('#frmapprove').serialize();
				}
				$.ajax({
					type: 'POST',
					url: '../serverside/n_sendmail_reviewer.php',
					data: formData,
					beforeSend: function () {							
						$('#overlay').fadeIn();  //$('#overlay').fadeIn().delay(2000).fadeOut();							
					},	
					error: function(xhr, error){
						$('#overlay').fadeOut();
						Swal.fire({
							type: "error",
							title: '<p class="font-medium-3">ดำเนินการ <span class="red"> ' + '<?php echo $action_type; ?>' + '</span> ไม่สำเร็จ!</p>',
							html: "<span style='red'>["+xhr+"] "+ error+"</span>",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "รับทราบ",
							customClass: {
								confirmButton: "btn btn-primary"
							},
							allowOutsideClick: false
						});
						setTimeout(function(){ window.close() (); }, 5000);
					},
					success: function(result) {
						var json = $.parseJSON(result); 
						if (json.r == '0') {
							$('#overlay').fadeOut();
							Swal.fire({
								type: "error",
								title: '<p class="font-medium-3">ดำเนินการ <span class="red"> ' + ' <?php echo $action_type; ?> ' + ' </span> ไม่สำเร็จ!</p>',
								html: '<p class="font-medium-3">เอกสาร <br>เลขที่ <span class="red text-bold-600">' +'<?php echo decrypt($cus_approve_nbr,$key); ?></span></p>' + json.e,
								icon: "error",
								buttonsStyling: false,
								confirmButtonText: "รับทราบ",
								customClass: {
									confirmButton: "btn btn-info"
								},
								allowOutsideClick: false
							}).then((result) => {
								if (result.isConfirmed) {
									window.close();
								}
							});	
						} else {
							$('#overlay').fadeOut();
							Swal.fire({
								type: "success",
								title: '<p class="font-medium-3">ดำเนินการ <span class="red">' + '<?php echo $action_type; ?>' + '</span> สำเร็จ !</p>',
								html: '<p class="font-medium-3">เอกสาร <br>เลขที่ ' + '<span class="red text-bold-600"><?php echo decrypt($cus_approve_nbr,$key); ?></span><br></p>' + json.e,
								icon: "success",
								buttonsStyling: false,
								confirmButtonText: "รับทราบ",
								customClass: {
									confirmButton: "btn btn-info"
								},
								allowOutsideClick: false
							}).then((result) => {
								if (result.isConfirmed) {
									window.close();
								}
							});						
						}
						//setTimeout(function(){ window.close() (); }, 6000);
					},
					complete: function() {					
						$('#overlay').fadeOut();
					}
				});
			}
		});		
	</script>
</head>
<body>
    <!-- <div id="div_result"></div> -->
	<div id="overlay" style="display:none;">
		<div class="spinner"></div>
		<br/>
		Loading...
	</div>
	<form id="frmapprove" name="frmapprove" method="post">
		<input type="hidden" name="action" id="action" value="">
		<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
		<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
		<input type="hidden" name="cus_approve_nbr" value="<?php echo $cus_approve_nbr?>">
		<input type="hidden" name="cus_approve_step" value="<?php echo $cus_approve_step?>">
		<input type="hidden" name="cus_approved_by" value="<?php echo $cus_approved_by?>">
		<input type="hidden" name="cus_auth_code" value="<?php echo $cus_auth_code?>">
		<input type="hidden" name="cus_approve_seq" value="<?php echo $cus_approve_seq?>">
	</form>		
</body>
</html>