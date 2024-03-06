<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	include("chkauthcr.php");
	include("chkauthcrctrl.php");
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key, $user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	set_time_limit(0);
	$curdate = date('Ymd');
	$params = array();
	
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
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php">Home</a>
									</li>
									<li class="breadcrumb-item active"><font color="40ADF4">เอกสารลงนามคณะกรรมการบริหาร</font></li>
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
												<li><a id="delete_record" value="Delete"><font class="text text-info"><i class="ft-check"></i>ยืนยันเอกสารลงนาม</a></li></font>
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>                             
									</div>
									<div class="card-content collapse show">
										<div class="card-body ">
											<div class="table-responsive">
												<!-- Project All -->
												<FORM id="frm_stamp" name="frm_stamp" autocomplete=OFF method="POST">
													<input type=hidden name="action" value="crctrledit">
													<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
													<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
													<input type="hidden" name="cr_cust_code" value="<?php echo($cr_cust_code) ?>">
													<table id="custsp_list" class="table table-sm table-hover table-bordered compact nowrap " style="width:100%;">
														<!--dt-responsive nowrap-->
														<thead class="text-center" style="background-color:#f1f1f1;">
															<tr class="bg-info text-white font-weight-bold">
																<th >No.</th>
																<th>เอกสารเลขที่</th>
																<th>วันที่</th>
																<th>รหัสลูกค้า</th>
																<th> ชื่อลูกค้า</th>
																<th>ผู้ขออนุมัติ</th>
																<th>Status</th>
																<th>All <input type="checkbox" class='checkall' id='checkall'></th>
																<!--<th>All <input type="checkbox" class='checkall' id='checkall'>&nbsp;&nbsp;<a id='delete_record' value='Delete'><i class="ft-check-square"></i></a></th>-->
															</tr>
														</thead>
														<tbody>
														</tbody>
													</table>
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
		<? include("../crctrlmain/menu_footer.php"); ?>

		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<!-- END: Page JS-->
		<script type="text/javascript" language="javascript" class="init">
			$(document).ready(function() {
				$('#custsp_list').DataTable({
				"ajax": {
					url: "../serverside/crctrlall_stamp_list.php?crnbr=<?php echo encrypt($crstm_nbr, $key); ?>",
					type: "post",
					error: function() {
						$("#custsp_list-error").html("Cannot Query Document List");
						// $("#custsp_list").append('<tbody ><tr><th colspan="12"><a  href="#div_add_qtm_project" data-toggle="modal"><i class="fa fa-plus"></i> เพิ่ม Quotation สำหรับโปรเจคนี้</a></th></tr></tbody>');
						$("#custsp_list processing").css("display", "none");
						$("#custsp_list").css("display", "none");
					}
				},
				"language": {
					"decimal": ",",
					"thousands": ".",
					// "emptyTable": '<a  href="#div_add_qtm_project" data-toggle="modal" style="font-size:1.2rem; line-height:3rem;"><i class="fa fa-plus"></i> เพิ่ม Quotation สำหรับโปรเจคนี้</a>'
				},

				"columnDefs": [{
						"className": "text-center",
						"targets": [0, 1, 2, 3, 6, 7]
					},

					//Nilubonp : Create Action Button
					
					{
						"render": function(data, type, row) {
							if (row.crstm_step_name == "Initial Approved") {  // 61
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Initial Approved',$conn); echo $bg; ?>' + ' round btn-sm">Initial Approved</span>';
							} 
						},
						"targets": [6],
					},
					
					// {
					// 	"targets": [7],
					// 	return row.action;
					// }
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
		
		// Check all the selected rows
		$('#checkall').click(function() {
			if ($(this).is(':checked')) {
				$('.nbr_check').prop('checked', true);
				} else {
				$('.nbr_check').prop('checked', false);
			} 
		});
		
		//delete all the selected rows from the database
		$('#delete_record').click(function() {
			var numbers_arr = [];
			// Read all checked checkboxes
			$("input:checkbox[class=nbr_check]:checked").each(function() {
				numbers_arr.push($(this).val());
			});
			
			// Check checkbox checked or not
			if (numbers_arr.length > 0) {
				//alert(numbers_arr);
				var confirmdelete = confirm("คุณต้องการอัพเดท "+numbers_arr.length+" records?");
				if (confirmdelete == true) {
					$.ajax({
									beforeSend: function() {
										$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
										$("#requestOverlay").show(); /*Show overlay*/
									},
									type: 'POST',
									url: '../serverside/crctrl_dt_chk_stamp.php'  ,
									//data: $('#frm_stamp').serialize(),
									data: {
										chk_status: 1,
										numbers_arr: numbers_arr
									},
									timeout: 10000,
									error: function(xhr, error) {
										showmsg('[' + xhr + '] ' + error);
									},
									success: function(data) {
										console.log(data);
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
											clearloadresult();
											//$('#sample_data').DataTable().ajax.reload(null, false); // call from external function
											Swal.fire({
												position: "top-end",
												type: "success",
												title: "Select document successfully.",
												showConfirmButton: false,
												timer: 500,
												confirmButtonClass: "btn btn-primary",
												buttonsStyling: false
											});
											location.reload(true);
											$(location).attr('href', 'crctrlall_upload.php?img='+json.nb+'&pg='+json.pg)
										}
									},
									complete: function() {
										$("#requestOverlay").remove(); /*Remove overlay*/
									}
								});
				}
			}
			else {
				alert("กรุณาเลือกเอกสารลงนามคณะกรรมการบริหาร !!!");
			}
		});
		// Checkbox checked
		function checkcheckbox() {
			// Total checkboxes
			var length = $('.nbr_check').length;
			// Total checked checkboxes
			var totalchecked = 0;
			$('.nbr_check').each(function() {
				if ($(this).is(':checked')) {
					totalchecked += 1;
					}
			});
			
			// Checked unchecked checkbox
			if (totalchecked == length) {
				$("#checkall").prop('checked', true);
				} else {
				$('#checkall').prop('checked', false);
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
</html>																																		