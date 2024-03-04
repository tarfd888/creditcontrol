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
	include("../crctrlbof/chkauthcrctrl.php");
	include("../crctrlbof/chkauthcr.php");
	set_time_limit(0);
	$curdate = date('Ymd');
	$params = array();
	$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);
	
	$query_data = "SELECT * FROM reviewer_mstr";
	$result_data = sqlsrv_query($conn,$query_data);
	$rec = sqlsrv_fetch_array($result_data,SQLSRV_FETCH_ASSOC);
	if($rec){
		$emp_remark = html_clear($rec['emp_remark']);
	}
	
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
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<!--<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/meteocons/style.min.css">-->
	</head>
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
		<div id="result"></div>
		<?php include("../crctrlmain/menu_header.php"); ?>
		<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
		<?php include("../crctrlmain/modal_attach.php"); ?>
		<!-- BEGIN: Content-->
		<div class="app-content content font-small-2">
			<div class="content-overlay"></div>
			<div class="content-wrapper">
				<div class="content-header row">
					<div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
						<h3 class="content-header-title mb-0 d-inline-block">Setting</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php">Home</a>
									</li>
									<!--<li class="breadcrumb-item"><a href="#">DataTables</a>
									</li>-->
									<li class="breadcrumb-item active"><font color="40ADF4">Create Reviewer</font></li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<div class="content-body">
					<!-- Province All -->
					<section id="project-all">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header " >
										<!--<div class="card-title p-0" ></div>-->
										<div class="heading-elements">
											<ul class="list-inline mb-0">
												<!--<li><a href='#div_frm_rev_add' data-toggle='modal'><i class="fa fa-plus"></i> Add Reviewer</a></li>-->
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>                             
									</div>
									<div class="card-content collapse show">
										<div class="card-body ">
											<div class="table-responsive">
												<!-- Project All -->
												<table id="rev_list" class="table table-sm table-hover table-bordered compact nowrap " style="width:100%; font-size:0.9em;">
													<!--dt-responsive nowrap-->
													<thead class="text-center" style="background-color:#f1f1f1;">
														<tr class="bg-info text-white font-weight-bold">
															<th>No.</th>
															<th>User</th>
															<th>ชื่อ - สกุล</th>
															<th>ตำแหน่ง</th>
															<th>Mail</th>
															<th>Group</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								
								<div class="row grouped-multiple-statistics-card">
									<div class="col-12">
										<div class="card">
											<div class="card-header">
												<h4 class="card-title" id="basic-layout-form">Remark</h4>
												<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
												<div class="heading-elements">
													<ul class="list-inline mb-0">
														<li><a data-action="collapse"><i class="ft-minus"></i></a></li>
													</ul>
												</div>
											</div>
											
											<div class="card-content collapse show">
												<form id="frm_remark" name="frm_remark" autocomplete=OFF method="POST">
													<input type=hidden name="action" value="add-remark">
													<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
													<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
													<div class="card-body">
														<div class="row">
															<div class="col-sm-12">
																<div class="form-group">
																	<textarea  id="emp_remark" name="emp_remark" class="form-control textarea-maxlength input-sm font-small-3 border-warning" placeholder="Enter upto 500 characters.." maxlength="500"  rows="5" style="line-height:1.5rem;"><?php echo $emp_remark; ?></textarea>
																</div>
															</div>
															<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
																<button type="button" id="btn-save" name="btn-save" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1"><i class="fa fa-envelope-o"></i> Save</button>
															</div>
														</div>
													</div>
												</form>	
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
						
					</section>
					<!-- File export table -->
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
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<script type="text/javascript" language="javascript" class="init">
			$(document).ready(function() {
			
				$('#rev_list').DataTable({
					"ajax": {
						url: "../serverside/reviewer_list.php",
						
						type: "post",
						error: function() {
							$("#rev_list-error").html("Cannot Query Quotation List");
							$("#rev_list processing").css("display", "none");
							$("#rev_list").css("display", "none");
						}
					},
					"language": {
						"decimal": ",",
						"thousands": ".",
						"emptyTable": '<a  href="" data-toggle="modal" data-target="#div_frm_rev_add" style="font-size:1.2rem; line-height:3rem;"><i class="fa fa-plus"></i> Add New Role</a>'
					},
					
					"columnDefs": [{
						"className": "text-center",
						"targets": [0,1,2,3,4,5,6]
						},
					
						{
							"targets": [6],
							"render": function(data, type, row, meta) {
								return '<a data-toggle="modal" class="open-EditrRoleModal" data-prefix ="' + row.emp_prefix_th_name + '" data-fname ="' + row.emp_th_firstname + '" data-lname="' + row.emp_th_lastname + '" data-pos_name="' + row.emp_th_pos_name +'"data-flag="'+row.emp_flag + '" '+
								'data-email="' + row.emp_email_bus + '" data-user_id="' + row.emp_user_id + '" data-emp_person_id="' + row.emp_person_id + '" data-target="#div_frm_rev_edit" href="javascript:void(0)" ><i class="fa fa-pencil-square-o"></i></a>';
								// return '<a data-toggle="modal" class="open-EditrRoleModal" data-prefix ="' + row.emp_prefix_th_name + '" data-fname ="' + row.emp_th_firstname + '" data-lname="' + row.emp_th_lastname + '" data-pos_name="' + row.emp_th_pos_name +'"data-flag="'+row.emp_flag + '" '+
								// 'data-email="' + row.emp_email_bus + '" data-user_id="' + row.emp_user_id + '" data-target="#div_frm_rev_edit" href="javascript:void(0)" ><i class="fa fa-pencil-square-o"></i></a> | <a id="btdelrole" data-rolenumber=" ' + row.role_id + '" data-rolename=" ' + row.role_user_login + '" href="javascript:void(0)"><i class="fa fa-trash-o fa-sm "></i></a>';
							}
						}
						
					],
					"columns": [{ // Add row no. (Line 1,2,3,n)
							"data": "id",
							render: function(data, type, row, meta) {
								return meta.row + meta.settings._iDisplayStart+1 ;
							}
						},
						{
							"data": "emp_user_id",
							"visible": false
						},
						{ 
							"targets": [2],
							"render": function(data, type, row, meta) {
								 return row.emp_prefix_th_name+row.emp_th_firstname+"  "+row.emp_th_lastname ;
							}
						},
						{
							"data": "emp_th_pos_name"
						},
						{
							"data": "emp_email_bus"
						},
						{
						"data": "emp_flag",
						render: function(data, type, row) {
							
							var active = '<span class="badge badge-success badge-pill"><style="font-size:11px;color:white">Tiles</a></span>';
							var inactive = '<span class="badge badge-warning badge-pill"><style="font-size:11px;color:white">Geoluxe</a></span>';
							var status = (data == "1") ? active : inactive;
							
							return status;
						}
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
			
			$(document).on("click", ".open-EditrRoleModal", function() {
				var emp_person_id = $(this).data('emp_person_id');
				var emp_user_id = $(this).data('user_id');
				var emp_prefix_th_name = $(this).data('prefix');
				var emp_th_firstname = $(this).data('fname');
				var emp_th_lastname = $(this).data('lname');
				var emp_th_pos_name = $(this).data('pos_name');
				var emp_email_bus = $(this).data('email');
				var emp_flag = $(this).data('flag');
			
				$("#div_frm_rev_edit .modal-body #emp_person_id").val(emp_person_id);
				$("#div_frm_rev_edit .modal-body #emp_user_id").val(emp_user_id);
				$("#div_frm_rev_edit .modal-body #emp_prefix_th_name").val(emp_prefix_th_name);
				$("#div_frm_rev_edit .modal-body #emp_th_firstname").val(emp_th_firstname);
				$("#div_frm_rev_edit .modal-body #emp_th_lastname").val(emp_th_lastname);
				$("#div_frm_rev_edit .modal-body #emp_th_pos_name").val(emp_th_pos_name);
				$("#div_frm_rev_edit .modal-body #emp_email_bus").val(emp_email_bus);
				$("#div_frm_rev_edit .modal-body #emp_flag").children("option[value=" + emp_flag + "]").attr("selected", true);
				//$("#div_frm_role_edit .modal-body #role_active").children("option[value=" + role_active + "]").attr("selected", true);
				
			});
			
			$(document).on("click", "#btn-save", function(e) {
				e.preventDefault();
				var errorflag = false;
				var errortxt = "";
				
					formData = $('#frm_remark').serialize();
				
					$.ajax({
						beforeSend: function () {
						  $('body').append('<div id="requestOverlay" class="request-overlay"></div>'); 
						  $("#requestOverlay").show();
						},
						type: 'POST',
						url: '../serverside/reviewerpost.php',
						//data: $('#frm_rcadd').serialize(),
						data: formData,
						timeout: 50000,
						error: function(xhr, error){
						  showmsg('['+xhr+'] '+ error);
						},
						success: function(result) {
							console.log(result);
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
									type: "success",
									title: "Successful",
									showConfirmButton: false,
									timer: 1500,
									confirmButtonClass: "btn btn-primary",
									buttonsStyling: false,
									animation: false,
								});
								location.reload(true);
								$(location).attr('href', '../masmnt/reviewermnt.php?nb=' + json.nb)
							}
						},
						complete: function () {
							$("#requestOverlay").remove();
						}
					});
				//}
			});
			
			function loadresult() {
				document.all.result.innerHTML = "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
			}
			
			function showdata() {
				var errorflag = false;
				var errortxt = "";
				document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
				if (errorflag) {
					document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
					$("#myModal").modal("show");
					} else {
					loadresult()
					document.frm.submit();
				}
			}
			
			function revpostform(formid) {
				$(document).ready(function() {
					$.ajax({
						beforeSend: function() {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
							$("#requestOverlay").show(); /*Show overlay*/
						},
						type: 'POST',
						url: '../serverside/reviewerpost.php',
						data: $('#' + formid).serialize(),
						timeout: 50000,
						error: function(xhr, error) {
							showmsg('[' + xhr + '] ' + error);
						},
						success: function(result) {
							//alert(result);
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
									type: "success",
									title: "Successful",
									showConfirmButton: false,
									timer: 1500,
									confirmButtonClass: "btn btn-primary",
									buttonsStyling: false,
									animation: false,
								});
								location.reload(true);
								$(location).attr('href', '../masmnt/reviewermnt.php?user_id=' + json.nb + '&pg=' + json.pg)
							}
						},
						
						complete: function() {
							$("#requestOverlay").remove(); /*Remove overlay*/
						}
					});
				});
			}
			
	function gotopage(mypage) {
		loadresult()
		document.frm.pg.value = mypage;
		document.frm.submit();
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
</html>																																		