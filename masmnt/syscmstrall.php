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
	$allow_admin = false;
	include("../crctrlbof/chkauthcr.php");
	include("../crctrlbof/chkauthcrctrl.php");
	set_time_limit(0);
	$curdate = date('Ymd');
	$params = array();
	$activeid = decrypt(html_escape($_REQUEST['activeid']), $key);
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
	<!-- BEGIN: Head-->
	
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
		<div class="app-content content">
			<div class="content-overlay"></div>
			<div class="content-wrapper">
				<div class="content-header row mt-n1">
					<div class="content-header-left col-md-6 col-12 mb-2">
						<div class="row breadcrumbs-top">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php">Home</a>
									</li>
									<li class="breadcrumb-item active"><a href="syscmstrall.php">All Control File </a>
									</li>
								</ol>
							</div>
						</div>
						
					</div>
					<!--<div class="content-header-right col-md-6 col-12">
						<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
							<a class="btn btn-primary white" href="syscadd.php">
							Add Control file</a>
						</div>
					</div>-->
				</div>
				
				<div class="content-body mt-n1">
					<div class="content-overlay"></div>
					<section class="row all-contacts">
						<div class="col-12">
							<div class="card">
								<div class="card-head">
									<div class="card-header">
										<h4 class="card-title form-section text-info"><i class="fa fa-cogs"></i> All Control file</h4>
										
										<div class="heading-elements mt-0">
											<ul class="list-inline mb-0">
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>
										
									</div>
								</div>
								<div class="card-content font-small-3 mt-n1 collapse show">
									<div class="card-body" style="margin-top:-20px;">
										<div class="table-responsive">
											<!-- Project All -->
											<table id="sysctable" class="table table-sm table-hover table-bordered compact nowrap" style="width:100%;">
												<!--dt-responsive nowrap-->
												<thead>
													<tr class="bg-info font-weight-bold white text-center ">
													<th>ID</th>
                                                    <th class="text-center">Com Code</th>
                                                    <th class="text-center">Company Name</th>
                                                    <th class="text-center">Address</th>
                                                    <th class="text-center">Tel</th>
                                                    <th class="text-center">Email</th>
                                                    <th class="text-center">Tax</th>
                                                    <th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
											<form name="frm_link_sysc" id="frm_link_sysc">
												<input type="hidden" name="action" value="link_sysc">
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
												<input type="hidden" name="sysc_id" value="">
												<input type="hidden" name="pg" value="<?php echo $pg ?>">
											</form>
											<form name="frmdelete" id="frmdelete" method="post" action="">
												<input type="hidden" name="action" value="syscdel">
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
												<input type="hidden" name="sysc_id">
												<input type="hidden" name="pg">
											</form>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
		<!-- END: Content-->
		
		<div class="sidenav-overlay"></div>
		<div class="drag-target"></div>
		
		<!-- BEGIN: Footer-->
		<footer class="footer footer-static footer-light navbar-border">
			<p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 <a class="text-bold-800 grey darken-2" href="https://1.envato.market/pixinvent_portfolio" target="_blank">PIXINVENT </a></span><span class="float-md-right d-none d-lg-block">Power by IT Business Solution Team <i class="feather icon-heart pink"></i></span></p>
		</footer>
		<!-- END: Footer-->
		
		<!-- BEGIN: Vendor JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<!-- BEGIN Vendor JS-->
		
		<!-- BEGIN: Page Vendor JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/jquery.knob.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/extensions/knob.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/raphael-min.js"></script>
		<!--<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/morris.min.js"></script>-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/data/jvector/visitor-data.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/chart.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jquery.sparkline.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/unslider-min.js"></script>
		
		<!-- END: Page Vendor JS-->
		
		<!-- BEGIN: Theme JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<!-- END: Theme JS-->
		
		<!-- BEGIN: Page JS-->
		<!--<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/pages/dashboard-analytics.js"></script>-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/tables/datatables/datatable-basic.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<!--<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>-->
		<script language="javascript">
			$(document).ready(function() {
				var syscmstr_list = $('#sysctable').DataTable({
					"ajax": {
						url: "../serverside/sysc_list.php",
						type: "post",
						
					},
					"language": {
						"decimal": ",",
						"thousands": ".",
						//"emptyTable": '<a  href="#div_add_qtm_project" data-toggle="modal" style="font-size:1.2rem; line-height:3rem;"><i class="fa fa-plus"></i> เพิ่ม Quotation สำหรับโปรเจคนี้</a>'
					},
					"columnDefs": [{
                        "className": "text-center",
                        "targets": [0, 1]
					},
                    {
                        "className": "dt-left",
                        "targets": [2, 3, 4, 5]
					},
                    {
                        "targets": [7],
                        "render": function(data, type, row, meta) {
                            return '<button type="button" class="btn btn-info dropdown-toggle btn-sm " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>' +
							'<div class="dropdown-menu">' +
							'<a class="dropdown-item" id="btview" data-id=" ' + row.sysc_id + ' " ><i class="fa fa-search-plus"></i> View</a>' +
							'<a class="dropdown-item" id="btedit" data-id=" ' + row.sysc_id + ' " ><i class="fa fa-pencil-square-o"></i> Edit/Update</a>' //+
                            //'<a class="dropdown-item" id="btdel" data-id=" ' + row.sysc_id + ' " ><i class="fa fa-trash-o"></i> Delete</a></div>'
						}
					}
					],
					"columns": [{
                        "data": "sysc_id"
					},
                    {
                        "data": "sysc_com_code"
					},
                    {
                        "data": "sysc_com_name"
					},
                    {
                        "data": "sysc_com_address"
					},
                    {
                        "data": "sysc_com_tel"
					},
                    {
                        "data": "sysc_com_email",
                        render: function(data, type, row, meta) {
                            var sysemail_encrypt = window.atob(row.sysc_com_email);
                            var sysemail_replace = sysemail_encrypt.replace("!", "@");
                            var sysemail_cut = sysemail_replace.lastIndexOf("@");
                            var sysemail_substr = sysemail_replace.substring(0, sysemail_cut);
                            return sysemail_substr;
						}
					},
                    {
                        "data": "sysc_com_taxid"
					}
					
					],
					"ordering": false,
				});
				$(document).on('click', '#btview', function(e) {
					var sysc_id = $(this).data('id');
					document.frm_link_sysc.sysc_id.value = sysc_id;
					
					$.ajax({
						beforeSend: function() {},
						type: 'POST',
						url: '../serverside/syscmstrpost.php',
						data: $('#frm_link_sysc').serialize(),
						timeout: 50000,
						error: function(xhr, error) {
							showmsg('[' + xhr + '] ' + error);
						},
						success: function(result) {
							var json = $.parseJSON(result);
							if (json.r == '0') {
								clearloadresult();
								Swal.fire({
									title: "Error!",
									html: json.e,
									type: "error",
									confirmButtonClass: "btn btn-danger",
									buttonsStyling: false
								});
								} else {
								clearloadresult();
								$(location).attr('href', 'syscmstrmnt.php?syscnumber=' + json.nb)
							}
						},
						complete: function() {
							$("#requestOverlay").remove(); /*Remove overlay*/
						}
					});
				});
				$(document).on('click', '#btedit', function(e) {
					var sysc_id = $(this).data('id');
					document.frm_link_sysc.sysc_id.value = sysc_id;
					
					$.ajax({
						beforeSend: function() {
							
						},
						type: 'POST',
						url: '../serverside/syscmstrpost.php',
						data: $('#frm_link_sysc').serialize(),
						timeout: 50000,
						error: function(xhr, error) {
							showmsg('[' + xhr + '] ' + error);
						},
						success: function(result) {
							
							var json = $.parseJSON(result);
							if (json.r == '0') {
								clearloadresult();
								Swal.fire({
									title: "Error!",
									html: json.e,
									type: "error",
									confirmButtonClass: "btn btn-danger",
									buttonsStyling: false
								});
								} else {
								clearloadresult();
								$(location).attr('href', 'syscedit.php?syscnumber=' + json.nb)
							}
						},
						complete: function() {
							$("#requestOverlay").remove(); /*Remove overlay*/
						}
					});
				});
				
				$(document).on('click', '#btdel', function(e) {
					var sysc_id = $(this).data('id');
					SwalDelete(sysc_id);
					e.preventDefault();
				});
				
			});
			
			function SwalDelete(sysc_id) {
				Swal.fire({
					title: "Are you sure?",
					text: "คุณต้องการลบข้อมูลนี้ใช่หรือไหม่ !!!! ",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, delete it!",
					confirmButtonClass: "btn btn-primary",
					cancelButtonClass: "btn btn-danger ml-1",
					buttonsStyling: false,
					showLoaderOnConfirm: true,
					preConfirm: function() {
						return new Promise(function(resolve) {
							document.frmdelete.sysc_id.value = sysc_id;
							$.ajax({
								type: 'POST',
								url: '../serverside/syscmstrpost.php',
								data: $('#frmdelete').serialize(),
								success: function(result) {
									var json = $.parseJSON(result);
									if (json.r == '0') {
										clearloadresult();
										Swal.fire({
											title: "Error!",
											html: json.e,
											type: "error",
											confirmButtonClass: "btn btn-danger",
											buttonsStyling: false
										});
										} else {
										clearloadresult();
										Swal.fire({
											position: "top-end",
											type: "success",
											title: "ลบข้อมูลเรียบร้อยค่ะ",
											showConfirmButton: false,
											timer: 1500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										
										window.location.reload();
									}
								},
								complete: function() {
									$("#requestOverlay").remove(); /*Remove overlay*/
								}
							})
							
						});
					},
					allowOutsideClick: false
				});
				
			}
		</script>
		
		
		<script language="javascript">
			function loadresult() {
				document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
			}
			
			function showdata() {
				document.frm.submit();
			}
			
			function gotopage(mypage) {
				document.frm.pg.value = mypage;
				document.frm.submit();
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