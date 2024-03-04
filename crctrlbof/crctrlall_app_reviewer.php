<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key, $user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	set_time_limit(0);
	$curdate = date('Ymd');
	$params = array();
	$rev_user_login = findsqlval("emp_mstr", "emp_email_bus", "emp_user_id", $user_login ,$conn);
	//$rev_user_login = strtolower($user_login)."@scg.com";
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
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
	</head>
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
		<div id="result"></div>
		<?php include("../crctrlmain/menu_header.php"); ?>
		<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
		<?php include("../crctrlmain/modal.php"); ?>
		<!-- BEGIN: Content-->
		<div class="app-content content">
			<div class="content-overlay"></div>
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">All Data</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall_app_reviewer.php">Home</a>
									</li>
									<li class="breadcrumb-item active"><font color="40ADF4">List Document</font></li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<div class="content-body font-small-3 mt-n1">
					<!-- Province All -->
					<section id="project-all">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header " >
										<!--<div class="card-title p-0" ></div>-->
										<div class="heading-elements">
											<ul class="list-inline mb-0">
											<label>
												<input type="radio" name="crctrl_select" id="radio1" value="1">
												<span class="cr"><i style="color:red;font-weight:bold"></i></span>
												<span>&nbsp;&nbsp;งานที่รอคุณดำเนินการ&nbsp;&nbsp;</span>
											</label>
											<label>
												<input type="radio" name="crctrl_select" id="radio2" value="2">
												<span class="cr"><i style="color:red;font-weight:bold"></i></span>
												<span>&nbsp;&nbsp;งานที่เกี่ยวกับคุณ&nbsp;&nbsp;</span>
											</label>
											<span style='text-right'>
												<div class="badge badge-primary badge-square">
													<input type="hidden" name="rev_user_login" id="rev_user_login" value="<?php echo $rev_user_login ?>">
													<a href="javascript:void(0)" id="but_search"><i class="fa fa-search font-medium-2"></i> 
														<span>ค้นหา</span></a>
													</div>
											</span>
											</ul>
										</div>                             
									</div>
									<div class="card-content collapse show">
										<div class="card-body ">
											<div class="table-responsive">
												<!-- Project All -->
												<table id="table-data" class="table table-sm table-hover table-bordered compact nowrap " style="width:100%;">
													<!--dt-responsive nowrap-->
													<thead class="text-center" style="background-color:#f1f1f1;">
														<tr class="bg-info text-white font-weight-bold">
															<th>No.</th>
															<th>เอกสารเลขที่</th>
															<th>วันที่</th>
															<th>รหัสลูกค้า</th>
															<th> ชื่อลูกค้า</th>
															<th>ผู้ขออนุมัติ</th>
															<th>Status</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
												<form name="frm_link_cr" id="frm_link_cr">
													<input type="hidden" name="action" value="link_cr">
													<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
													<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
													<input type="hidden" name="crstm_nbr" value="">
													<input type="hidden" name="pg" value="<?php echo $pg ?>">
												</form>
											</div>
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
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function () {  
			$("#radio1").trigger('click');
			//$("#but_search").trigger('click');
		});	
		</script>
		<script type="text/javascript" language="javascript" class="init">
				$(document).ready(function(e) {
				var iscookie;
				let input0 = {};
				input0.rev_user_login = $("#rev_user_login").val();
				var v = $('input[name="crctrl_select"]:checked').val();
				input0.crctrl_select  = v;
				$('#table-data').DataTable({
				"ajax": {
					url: "../serverside/crctrlall_app_reviewer_list.php",
					type: "post",
					dataType: 'json',
					data: {param0: JSON.stringify(input0)},
					error: function() {
						$("#table-data-error").html("Cannot Query Document List");
						$("#table-data processing").css("display", "none");
						$("#table-data").css("display", "none");
					}
				},
				"language": {
					"decimal": ",",
					"thousands": ".",
					"emptyTable": 'No data available in table'
				},

				"columnDefs": [{
						"className": "text-center",
						"targets": [0, 1, 2, 3, 6, 7]
					},
					{
						"targets": [7],
						"render": function(data, type, row, meta) {
							var btnAction_Edit = '<a title="Edit Credit Control" id="bteditcr" data-directions="EDIT" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>';
							return btnAction_Edit;
						}
					}
				],
				"createdRow": function( row, data, dataIndex ) {
						if ( data['crstm_cus_active'] == "0" ) {        
							$(row).addClass('text-black bg-info bg-lighten-5');	  	 
						}
					},
					
				"columns": [{ // Add row no. (Line 1,2,3,n)
						"data": "id",
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart+1 ;
						}
					},
					{
						"data": "crstm_nbr"
					},
					{
						"data": "crstm_date"
					},
					{
						"data": "crstm_cus_nbr"
					},
					{
						"data": "crstm_cus_name"
					},
					{ 
						"targets": [5],
						"render": function(data, type, row, meta) {
							 return row.emp_prefix_th_name+" "+row.emp_th_firstname+"   "+row.emp_th_lastname ;
						}
					},
					{
						"data": "crstm_step_name"
					},
					{
						"data": "action"
					},
					
				],
				"lengthMenu": [
					[10, 25, 50, -1],
					[10, 25, 50, "All"]
				],
				"order": [
					[0, "asc"]
				],
				"ordering": false,
				"stateSave" : true,
				"pageLength": 10,
				"pagingType": "simple_numbers",
			});
				
		});
		$(document).on("click", "#but_search", function (e) {
				e.preventDefault();
				var iscookie;
				let input0 = {};
				input0.rev_user_login = $("#rev_user_login").val();
				var v = $('input[name="crctrl_select"]:checked').val();
				input0.crctrl_select  = v;
				//setcookie(input0,function(results) {iscookie = results;})
				$.ajax({
					url: "../serverside/crctrlall_app_reviewer_list.php",
					type: "POST",
					dataType: 'json',
					data: {param0: JSON.stringify(input0)},
					beforeSend: function () {
						$(".loading").fadeIn();
						$('#table-data').DataTable().clear().destroy();
					},
					success: function (res) {
						if (res.success) {
						console.log(res.data);
							$("#table-data").dataTable().fnDestroy();
							$("#table-data").dataTable({
							"dom": 'tip',
							"aaData" : res.data,
							"cache": false,
								"columns": [{ // Add row no. (Line 1,2,3,n)
										"data": "id",
										render: function(data, type, row, meta) {
											return meta.row + meta.settings._iDisplayStart+1 ;
										}
									},
									{
										"data": "crstm_nbr"
									},
									{
										"data": "crstm_date"
									},
									{
										"data": "crstm_cus_nbr"
									},
									{
										"data": "crstm_cus_name"
									},
									{ 
										"targets": [5],
										"render": function(data, type, row, meta) {
											 return row.emp_prefix_th_name+" "+row.emp_th_firstname+"   "+row.emp_th_lastname ;
										}
									},
									{
										"data": "crstm_step_name"
									},
									{
										"data": "action"
									},
									
								],
								"columnDefs" : [
								
								{"className": "text-center", "targets": [0, 1, 2, 3, 5, 6, 7]},
									{
										"targets": [7],
										"render": function(data, type, row, meta) {
											var btnAction_Edit = '<a title="Edit Credit Control" id="bteditcr" data-directions="EDIT" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a>';
											return btnAction_Edit;
										}
									}
								
								],
								"searching": false,
								"ordering": false,
								"stateSave" : true,
								"pageLength": 10,
								"pagingType": "simple_numbers",						
							});
								$("#table-data").fadeIn();
						}
					},
					complete: function () {
						$(".loading").fadeOut();
					},
					error: function (res) {
						console.log(res)
						alert('error');
					}
				});
			});
		$(document).on('click', '#btviewcr,#bteditcr', function(e) {
				var crnumber = $(this).data('crnumber');
				var cus_nbr = $(this).data('cus_nbr');
				var directions = $(this).data('directions');
				var cus_active = $(this).data('cus_active');
				
				document.frm_link_cr.crstm_nbr.value = crnumber;
				document.frm_link_cr.crstm_cus_nbr = cus_nbr;
				
				$.ajax({
					type: 'POST',
					url: '../serverside/crmnt_detail_post.php',
					data: $('#frm_link_cr').serialize(),
					timeout: 3000,
					error: function(xhr, error) {
						showmsg('[' + xhr + '] ' + error);
					},
					success: function(result) {
					//console.log(result);
					//alert(result);
						var json = $.parseJSON(result);
						if (json.r == '0') {
							Swal.fire({
								title: "Error!",
								html: json.e,
								type: "error",
								confirmButtonClass: "btn btn-danger",
								buttonsStyling: false
							});
						} else {
							if (directions == "EDIT" && cus_active == "1") {
								var Linkdirections = 'cr_app_reviewer.php?crnumber=' + json.nb + '&current_tab=10&pg=' + json.pg;
							} else {
								var Linkdirections = 'cr_app_new_reviewer.php?crnumber=' + json.nb + '&current_tab=10&pg=' + json.pg;
							}
							$(location).attr('href', Linkdirections)
						}
					},
					complete: function() {
						$("#requestOverlay").remove(); 
					}
				});
			});
		/* function loadresult() {
				$('#div_result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
			} */
		/* function clearloadresult() {
				$('#div_result').html("");
			} */
		
		function showdata() {			
			document.frm.submit();									
		}
		function showmsg(msg) {
			$("#modal-body").html(msg);
			$("#myModal").modal({backdrop: 'static', keyboard: false});
			$("#myModal").modal("show");
			
		}
		</script>
		</body>
		<!-- END: Body-->
		
		</html>																																		