<?php 
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

date_default_timezone_set('Asia/Bangkok');
$curdate = date("d/m/Y H:i:s");
$crstm_step_code = "0";
clearstatcache();
$crstm_cus_name = "หจก.รุ่งกิจวัสดุการสร้าง";
$reviewer_code = "110";
$crstm_nbr = 'CR-2206-0002';
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
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
		<!--<link rel="stylesheet" href="_libs/css/font-awesome/css/font-awesome.min.css">-->
		
		<!-- BEGIN VENDOR CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.min.css">
		
	</head>
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
		<FORM id="frm_crctrl_edit" name="frm_crctrl_edit" autocomplete=OFF method="POST" enctype="multipart/form-data" >
			<input type=hidden name="action" value="crctrledit">
			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
			<input type="hidden" name="crstm_nbr" value="<?php echo($crstm_nbr) ?>">
			<button type="button" id="btnsubumit" name="btnsubumit" class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1" onclick="Swalappformsend('<?php echo $crstm_cus_name; ?>','<?php echo encrypt($reviewer_code, $key);?>','<?php echo $crstm_nbr; ?>')"><i class="fa fa-envelope-o"></i> Submit110</button>
		</form>															
	
		<!-- BEGIN: Vendor JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<!-- BEGIN Vendor JS-->
		
		<script type="text/javascript">
			
			function Swalappformsend(cus_name,chk_action,crstm_nbr) {
				alert(cus_name+"--"+chk_action+"--"+crstm_nbr);
				if(confirm('คุณได้ทำการแก้ไข และ บันทึกข้อมูลเรียบร้อยแล้ว ก่อนส่งข้อมูล  <br> ลูกค้า   ' + cus_name + ' ไปให้ผู้ตรวจสอบ ใช่หรือไม่ !!!! ')) {	
					var result_text = "";
					$.ajaxSetup({
						cache: false,
						contentType: false,
						processData: false
					});
					var formObj = $('#frm_crctrl_edit')[0];
					var formData = new FormData(formObj);
					$.ajax({
						beforeSend: function() {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
							$("#requestOverlay").show(); /*Show overlay*/
						},
						type: 'POST',
						//url: '../serverside/crctrlsubmitpost.php?step_code=' +chk_action  ,
						url: '../serverside/crctrlsubmitpost_pdf_rev1.php?step_code=' +chk_action  ,
						//data: $('#' + formid).serialize(),
						data: formData,
						timeout: 50000,
						error: function(xhr, error) {
							showmsg('[' + xhr + '] ' + error);
							//alert(error);
						},
						success: function(data) {
							console.log(data);
							//alert(data);
							var json = $.parseJSON(data);
							if (json.r == '0') {
								clearloadresult();
								showmsg(json.e);
							} else {
								clearloadresult();
								showmsg(json.e);
								clearloadresult();
								$(location).attr('href', 'crctrlall.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
							}
						},
						complete: function() {
							$("#requestOverlay").remove(); /*Remove overlay*/
						}
					});
							
				}	
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
	<!-- END: Body-->
	
</html>																																																																																																																																																																																																																																																						