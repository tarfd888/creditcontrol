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
	</head>
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
		<div id="result"></div>
		<?php include("../crctrlmain/menu_header.php"); ?>
		<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
		<?php include("../crctrlmain/modal.php"); ?>
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
									<li class="breadcrumb-item active"><font color="40ADF4">Create Role</font></li>
								</ol>
							</div>
						</div>
					</div>
					<!--<div class="content-header-right col-md-6 col-12">
						<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
						<div class="btn-group" role="group">
						<a class="btn btn-primary white" data-toggle="modal" data-target="#div_frm_role_add"> 
						<i class="feather icon-file-plus icon-left"></i> Add Role</a>
						</div>
						<a class="btn btn-outline-primary" href="full-calender-basic.html"><i class="fa fa-download"></i></a>
						<a class="btn btn-outline-primary" href="full-calender-events.html"><i class="fa fa-calendar"></i></a>
						</div>
					</div>-->
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
												<li><a href='#div_frm_role_add' data-toggle='modal'><i class="fa fa-plus"></i> Add Role</a></li>
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>                             
									</div>
									<div class="card-content collapse show">
										<div class="card-body ">
											<div class="table-responsive">
												<!-- Project All -->
												<table id="role_list" class="table table-sm table-hover table-bordered compact nowrap " style="width:100%; font-size:0.9em;">
													<!--dt-responsive nowrap-->
													<thead class="text-center" style="background-color:#f1f1f1;">
														<tr class="bg-info text-white font-weight-bold">
															<th>No.</th>
															<th>User</th>
															<th>role</th>
															<th>Description</th>
															<th></th>
															<th>Status</th>
															<th>Mail</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
												
												<form name="frm_del_role" id="frm_del_role" action="../serverside/rolemastpost.php">
													<input type="hidden" name="action" value="roledel">
													<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
													<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
													<input type="hidden" name="role_id" value="">
													<input type="hidden" name="pg" value="<?php echo $pg ?>">
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
		<? include("../crctrlmain/menu_footer.php"); ?>
		<!-- END: Footer-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<script type="text/javascript" language="javascript" class="init">
			$(document).ready(function() {
				
				$(document).on('click', '#btdelrole', function(e) {
					var rolenumber = $(this).data('rolenumber');
					var rolename = $(this).data('rolename');
					var pg = $(this).data('pg');
					Swal.fire({
						title: "Are you sure?",
						html: "คุณต้องการลบชื่อ " +<?echo rolename ; ?> + "  นี้ใช่หรือไหม่ !!!! ",
						type: "warning",
						showCancelButton: true,
						confirmButtonText: "Yes, delete it!",
						confirmButtonClass: "btn btn-primary",
						cancelButtonClass: "btn btn-danger ml-1",
						buttonsStyling: false,
						showLoaderOnConfirm: true,
						preConfirm: function() {
							return new Promise(function(resolve) {
								document.frm_del_role.role_id.value = rolenumber;
								document.frm_del_role.pg.value = pg;
								$.ajax({
									beforeSend: function() {
										//$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
										//$("#requestOverlay").show();/*Show overlay*/
									},
									type: 'POST',
									url: '../serverside/rolemastpost.php',
									data: $('#frm_del_role').serialize(),
									timeout: 50000,
									error: function(xhr, error) {
										showmsg('[' + xhr + '] ' + error);
									},
									success: function(result) {
										//console.log(result);
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
												title: "Delete Successful",
												showConfirmButton: false,
												timer: 1500,
												confirmButtonClass: "btn btn-primary",
												buttonsStyling: false,
												animation: false,
											});
											location.reload(true);
											//$('#role_list').DataTable().ajax.reload(null, false); // call from external function
											$(location).attr('href', 'rolemastmnt.php?rolenumber='+json.nb+'&pg='+json.pg)
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
					e.preventDefault();
				});
				
				$('#role_list').DataTable({
					"ajax": {
						url: "../serverside/rolemast_list.php?role_id=<?php echo encrypt($role_id, $key); ?>",
						
						type: "post",
						error: function() {
							$("#role_list-error").html("Cannot Query Quotation List");
							$("#role_list").append('<tbody ><tr><th colspan="12"><a  href="#div_add_qtm_project" data-toggle="modal"><i class="fa fa-plus"></i> เพิ่ม Quotation สำหรับโปรเจคนี้</a></th></tr></tbody>');
							$("#role_list processing").css("display", "none");
							$("#role_list").css("display", "none");
						}
					},
					"language": {
						"decimal": ",",
						"thousands": ".",
						"emptyTable": '<a  href="" data-toggle="modal" data-target="#div_frm_role_add" style="font-size:1.2rem; line-height:3rem;"><i class="fa fa-plus"></i> Add New Role</a>'
					},
					
					"columnDefs": [{
						"className": "text-center",
						"targets": [0,1,2,3,4,5,6,7]
					},
					
					{
						"targets": [7],
						"render": function(data, type, row, meta) {
							return '<a data-toggle="modal" class="open-EditrRoleModal" data-role_id ="' + row.role_id + '" data-role_desc ="' + row.role_desc + '" data-role_code="' + row.role_code + '" data-role_user_login="' + row.role_user_login +'"data-role_active="'+row.role_active + '" '+
							'data-role_receive_mail ="' + row.role_receive_mail + '" data-target="#div_frm_role_edit" href="javascript:void(0)" ><i class="fa fa-pencil-square-o"></i></a> | <a id="btdelrole" data-rolenumber=" ' + row.role_id + '" data-rolename=" ' + row.role_user_login + '" href="javascript:void(0)"><i class="fa fa-trash-o fa-sm "></i></a>';
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
						"data": "role_user_login"
					},
					{
						"data": "role_code"
					},
					{
						"data": "role_desc"
					},
					{
						"data": "role_id",  
						"visible": false
					},
					
					{
						"data": "role_active",
						render: function(data, type, row) {
							
							var active = '<span class="badge badge-success badge-pill"><style="font-size:11px;color:white">Active</a></span>';
							var inactive = '<span class="badge badge-warning badge-pill"><style="font-size:11px;color:white">Not</a></span>';
							var status = (data != 0) ? active : inactive;
							
							return status;
						}
					},
					{
						"data": "role_receive_mail",
						render: function(data, type, row) {
							
							var active = '<span class="badge badge-success badge-pill"><style="font-size:11px;color:white">Active</a></span>';
							var inactive = '<span class="badge badge-warning badge-pill"><style="font-size:11px;color:white">Not</a></span>';
							var status = (data != 0) ? active : inactive;
							
							return status;
						}
					}
					// {
						// "data": "role_receive_mail"
					// }
					
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
				var role_id = $(this).data('role_id');
				var role_desc = $(this).data('role_desc');
				var role_code = $(this).data('role_code');
				var role_user_login = $(this).data('role_user_login');
				var role_active = $(this).data('role_active');
				var role_receive_mail = $(this).data('role_receive_mail');
				//alert(role_active); 
				$("#div_frm_role_edit .modal-body #role_id").val(role_id);
				$("#div_frm_role_edit .modal-body #role_desc").val(role_desc);
				$("#div_frm_role_edit .modal-body #role_user_login").val(role_user_login);
				$("#div_frm_role_edit .modal-body #role_code").val(role_code);
				//$("#div_frm_role_edit .modal-body #role_code").children("option[value=" + role_code + "]").attr("selected", true);
				$("#div_frm_role_edit .modal-body #role_receive_mail").children("option[value=" + role_receive_mail + "]").attr("selected", true);
				$("#div_frm_role_edit .modal-body #role_active").children("option[value=" + role_active + "]").attr("selected", true);
				
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
			
			function rolepostform(formid) {
				$(document).ready(function() {
					$.ajax({
						beforeSend: function() {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
							$("#requestOverlay").show(); /*Show overlay*/
						},
						type: 'POST',
						url: '../serverside/rolemastpost.php',
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
								$(location).attr('href', '../masmnt/rolemastmnt.php?rolemnumber=' + json.nb + '&pg=' + json.pg)
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
			</body>
			<!-- END: Body-->
			
			</html>																																		