<?php 
	//Update can_editing
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	clearstatcache();
	include("chkauthcr.php");
	include("chkauthcrctrl.php");
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y H:i:s");
	
	$img_number = decrypt(mssql_escape($_REQUEST['img']), $key);

	clearstatcache();
	
	
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
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/toastr.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/extensions/toastr.min.css">
	</head>
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
		<div id="result"></div>
		<?php include("../crctrlmain/menu_header.php"); ?>
		<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
		<?php include("../crctrlmain/modal.php"); ?>
		<?php include("../crctrlmain/help_modal.php"); ?>
		
		<!-- BEGIN: Content-->
		<div class="app-content content font-small-3">
			<div class="content-overlay"></div>
			<div class="content-wrapper">
				<div class="content-header row mt-n1">
					<div class="content-header-left col-md-6 col-12 mb-2">
						<div class="row breadcrumbs-top">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php"> Home</a></li>
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrladd_new.php"> เอกสารลงนามคณะกรรมการบริหาร</a></li>
								</ol>
							</div>
						</div>
					</div> 
				</div>
				
				<div class="content-body">
					<section class="new-project">
						<div class="row ">
							<div class="col-12">	
								<div class="card">
									<div class="card-header mt-1 pt-0 pb-0" >
										<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
										<div class="heading-elements">
											<ul class="list-inline mb-0">        
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>
									</div>
									<div class="card-content collapse show ">  
										<div class="card-body" style="margin-top:-20px;">
											<FORM id="frm_crctrl_upload" name="frm_crctrl_upload" autocomplete=OFF method="POST" enctype="multipart/form-data">
												<input type=hidden name="action" value="add_new"> 
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
												<input type="hidden" name="img_number" value="<?php echo($img_number) ?>">
												<h4 class="form-section text-info"><i class="fa fa-user"></i> เอกสารลงนามคณะกรรมการบริหาร</h4>
													<div class="row">
														<div class="col-md-6">	
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">เอกสารแนบ:</label>
																<div class="col-md-9">
																	<div class="row">
																		<div class="form-group col-12 mb-2">
																			<label>Select File</label>
																			<label id="projectinput8" class="file center-block">
																				<input type="file" accept="" name="load_att_img" id="load_att_img">
																				<input type="hidden" name="crstm_att_img" id="crstm_att_img" value="upload">
																				<span class="file-custom"></span>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group row">
																<label class="col-md-3 label-control" for="userinput1">วันที่ลงนาม :</label>
																<div class="col-md-6">
																	<input type="text" name="crstm_fin_app_date" id="crstm_fin_app_date" class="form-control form-control input-sm font-small-3" placeholder="ระบุวันที่ลงนาม"> 
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
																<button type="button" class="btn mr-1 mb-1 btn-success btn-sm" onclick="Swalappform()"><i class="fa fa-check-square-o"></i> Upload</button>
																<button type="reset" class="btn mr-1 mb-1 btn-danger btn-sm" onclick="document.location.href='../crctrlbof/crctrlall.php'"><i class="ft-x"></i> Cancel</button>
															</div>
														</div>
													</div>
											</form>	
										</div>
									</div>	
								</div>
							</div>	
						</section>
					</div>
				</div>	
			</div>	
		</div>	
		<!-- END: Content-->			
		<div class="sidenav-overlay"></div>
		<div class="drag-target"></div>
		<!-- BEGIN: Footer-->
		<? include("../crctrlmain/menu_footer.php"); ?>
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/formatter/formatter.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/toastr.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-inputmask.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-maxlength.min.js"></script>
		<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
		<script type="text/javascript">
				function Swalappform() {
					//alert(formid+"--"+chk_action+"--"+cus_name);
					Swal.fire({
						//title: "Are you sure?",
						html: "ยืนยันการอัพโหลดไฟล์  นี้ใช่หรือไม่ !!!! " , 
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#3085d6",
						cancelButtonColor: "#d33",
						confirmButtonText: "Yes, Save it!",
						confirmButtonClass: "btn btn-primary",
						cancelButtonClass: "btn btn-danger ml-1",
						buttonsStyling: false,
						showLoaderOnConfirm: true,
						preConfirm: function() {
							return new Promise(function(resolve) {
								var result_text = "";
								$.ajaxSetup({
									cache: false,
									contentType: false,
									processData: false
								});
								var formObj = $('#frm_crctrl_upload')[0];
								var formData = new FormData(formObj);
								$.ajax({
									beforeSend: function() {
										$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
										$("#requestOverlay").show(); /*Show overlay*/
									},
									type: 'POST',
									url: '../serverside/crctrl_upload_post.php'  ,
									//data: $('#' + formid).serialize(),
									data: formData,
									timeout: 10000,
									error: function(xhr, error) {
										showmsg('[' + xhr + '] ' + error);
									},
									success: function(data) {
										//console.log(data);
										//alert(data);
										var json = $.parseJSON(data);
										if (json.r == '0') {
											clearloadresult();
											Swal.fire({
												title: "Warning !",
												html: json.e,
												type: "error",
												confirmButtonClass: "btn btn-danger",
												buttonsStyling: false
											});
											} else {
											//clearloadresult();
											//$('#sample_data').DataTable().ajax.reload(null, false); // call from external function
											Swal.fire({
												position: "top-end",
												type: "success",
												title: "Upload file successfully.",
												showConfirmButton: false,
												timer: 500000,
												confirmButtonClass: "btn btn-primary",
												buttonsStyling: false
											});
											location.reload(true);
											$(location).attr('href', 'crctrlall_stamp.php?img='+json.nb)
										}
									},
									complete: function() {
										$("#requestOverlay").remove(); /*Remove overlay*/
									}
								});
							});   
						},
						allowOutsideClick: false
					});
				}
			$('#crstm_fin_app_date').datetimepicker({
				format: 'DD/MM/YYYY'
			});	
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
</html>																																																																																																																																																																																																																																																																																																																		