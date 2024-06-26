<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";
include("../crctrlbof/chkauthcrctrl.php");
include("../crctrlbof/chkauthcr.php");
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key, $user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	
set_time_limit(0);
$curdate = date('Ymd');
$params = array();
$query_data = "SELECT * FROM author_g_mstr";
$result_data = sqlsrv_query($conn,$query_data);
$rec = sqlsrv_fetch_array($result_data,SQLSRV_FETCH_ASSOC);
if($rec){
	$author_remark = html_clear($rec['author_remark']);
}
$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);
$action_tiles = decrypt(mssql_escape($_REQUEST['isTiles']), $key);
$gr = decrypt(mssql_escape($_REQUEST['gr']), $key);
if($action_tiles == "1"){
	$Tiles = "Tiles";
} else {
	$Tiles = "Geoluxe";
}
if($gr == "1"){
	$action_tiles = "1";
} else if($gr =="2") {
	$action_tiles = "2";
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
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/style.css">  <!--to-top -->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
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
									<li class="breadcrumb-item active"><font color="40ADF4">อำนาจดำเนินการอนุมัติ ลูกค้าในเครือ ( <?php echo ($Tiles); ?> ) </font></li>
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
												<li><a href='#div_frm_autho_affi_add' data-toggle='modal'><i class="fa fa-plus"></i> Add</a></li>
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>                             
									</div>
									<div class="card-content collapse show">
										<div class="card-body ">
											<div class="table-responsive">
												<!-- Project All -->
												<table id="table_affi" class="table table-sm table-hover table-bordered compact nowrap" style="width:100%; font-size:0.9em;">
													<!--dt-responsive nowrap-->
													<thead class="text-center" style="background-color:#f1f1f1;">
														<tr class="bg-info text-white font-weight-bold">
															<th>No.</th>
															<th>id</th>
															<th>group</th>
															<th>ชื่อ-ตำแหน่ง</th>
															<th>อีเมล</th>
															<th>อำนาจดำเนินการ</th>
															<th>เรียน</th>
															<th>ตำแหน่งในแบบฟอร์ม</th>
															<th>วงเงิน</th>
															<th>Status</th>
															<th>Email</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
													<tfoot>
													<tr class="bg-info text-white font-weight-bold">
															<th>No.</th>
															<th>id</th>
															<th>group</th>
															<th>ชื่อ-ตำแหน่ง</th>
															<th>อีเมล</th>
															<th>อำนาจดำเนินการ</th>
															<th>เรียน</th>
															<th>ตำแหน่งในแบบฟอร์ม</th>
															<th>วงเงิน</th>
															<th>Status</th>
															<th>Email</th>
															<th>Action</th>
														</tr>
													</tfoot>
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
																	<textarea  id="author_remark" name="author_remark" class="form-control textarea-maxlength input-sm font-small-3 border-warning" placeholder="Enter upto 500 characters.." maxlength="500"  rows="5" style="line-height:1.5rem;"><?php echo $author_remark; ?></textarea>
																</div>
															</div>
															<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
																<button type="button" id="btn-save" name="btn-save" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"><i class="ft-save"></i> Save</button>
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
		<? include("../crctrlmain/menu_footer.php"); ?>
		<div class="to-top">
			<i class="fa fa-angle-up" aria-hidden="true"></i>
		</div>
		<!-- End Section On to Top -->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/main.js"></script> <!-- to-Top -->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<script type="text/javascript" language="javascript" class="init">
			$(document).ready(function() {
				
				$('#table_affi').DataTable({
					"ajax": {
					url: "../serverside/autho_affi_list.php?author_nbr=<?php echo encrypt($action_tiles, $key); ?>",
					type: "post",
					error: function() {
						//$("#prv_list-error").html("Cannot Query Quotation List");
						// $("#prv_list").append('<tbody ><tr><th colspan="12"><a  href="#div_add_qtm_project" data-toggle="modal"><i class="fa fa-plus"></i> เพิ่ม Quotation สำหรับโปรเจคนี้</a></th></tr></tbody>');
						//$("#prv_list processing").css("display", "none");
						//$("#prv_list").css("display", "none");
					}
				},
				"language": {
					"decimal": ",",
					"thousands": ".",
				},

				"columnDefs": [{
						"className": "text-center",
						"targets": [0, 4, 6, 7, 8, 9, 10, 11]
					},

					//Nilubonp : Create Action Button
					{
						"targets": [11],
						"render": function(data, type, row, meta) {
							return '<a data-toggle="modal" class="open-EditDialog" data-author_id ="' + row.author_id + '" data-author_group ="' + row.author_group + '"  data-author_position ="' + row.author_position + '" data-author_code ="' + row.author_code + '" data-author_sign ="' + row.author_sign + '" data-author_email="' + row.author_email + '" data-author_sign_nme ="' + row.author_sign_nme + '" data-author_text="' + row.author_text + '" data-author_salutation="' + row.author_salutation + '" data-author_email_status="' + row.author_email_status + '" data-financial_amt_beg="' + row.financial_amt_beg + '" data-financial_amt_end="' + row.financial_amt_end + '" data-author_active="' + row.author_active + '" data-target="#div_frm_autho_edit" href="javascript:void(0)" ><i class="fa fa-pencil-square-o"></i></a>';
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
						"data": "author_id",
						"visible": false
					},
					{
						"data": "author_group",
						"visible": false
					},
					{
						"data": "author_sign_nme"
						/* "render": function(data, type, row, meta) {
							return row.author_sign_nme + " (" + row.author_code + ")";
						} */
					},
					{
						"data": "author_email"
					},
					{
						"data": "author_text"
					},
					{
						"data": "author_salutation"
					},
					{
						"data": "author_code"
					},
					{
						"targets": [8],
						"render": function(data, type, row, meta) {
							return row.financial_amt_beg + " - " + row.financial_amt_end;
						}
					},
					{
						"data": "author_email_status",
						render: function(data, type, row) {
							
							var active = '<span class="badge badge-success badge-pill"><style="font-size:11px;color:white">Active</a></span>';
							var inactive = '<span class="badge badge-warning badge-pill"><style="font-size:11px;color:white">Not</a></span>';
							var status = (data != 0) ? active : inactive;
							
							return status;
						}
					},
					{
						"data": "author_active",
						render: function(data, type, row) {
							
							var active = '<span class="badge badge-success badge-pill"><style="font-size:11px;color:white">Active</a></span>';
							var inactive = '<span class="badge badge-warning badge-pill"><style="font-size:11px;color:white">Not</a></span>';
							var status = (data != 0) ? active : inactive;
							
							return status;
						}
					}
					
					
				],
				"lengthMenu": [
					[10, 25, 50, -1],
					[10, 25, 50, "All"]
				],
				"order": [
					[0, "asc"]
				],
				"ordering": true,
				"stateSave" : true,
				"pageLength": 10,
				"pagingType": "simple_numbers",
			});
			
		});
			
			$(document).on("click", ".open-EditDialog", function() {
				let author_id = $(this).data('author_id');
				let author_sign_nme = $(this).data('author_sign_nme');
				let author_position = $(this).data('author_position');
				let author_code = $(this).data('author_code');
				let author_sign = $(this).data('author_sign');
				let author_email = $(this).data('author_email');
				let author_email_status = $(this).data('author_email_status');
				let author_text = $(this).data('author_text');
				let author_salutation = $(this).data('author_salutation');
				let author_active = $(this).data('author_active');
				let author_group = $(this).data('author_group');
				let financial_amt_beg = $(this).data('financial_amt_beg');
				let financial_amt_end = $(this).data('financial_amt_end');
				$("#div_frm_autho_edit .modal-body #author_id").val(author_id);
				$("#div_frm_autho_edit .modal-body #author_sign_nme").val(author_sign_nme);
				$("#div_frm_autho_edit .modal-body #author_position").val(author_position);
				$("#div_frm_autho_edit .modal-body #author_code").val(author_code);
				$("#div_frm_autho_edit .modal-body #author_sign").val(author_sign);
				$("#div_frm_autho_edit .modal-body #author_email").val(author_email);
				$("#div_frm_autho_edit .modal-body #author_text").val(author_text);
				$("#div_frm_autho_edit .modal-body #author_salutation").val(author_salutation);
				$("#div_frm_autho_edit .modal-body #author_active").val(author_active);
				$("#div_frm_autho_edit .modal-body #author_group").val(author_group);
				$("#div_frm_autho_edit .modal-body #author_email_status").val(author_email_status);
				$("#div_frm_autho_edit .modal-body #financial_amt_beg").val(financial_amt_beg);
				$("#div_frm_autho_edit .modal-body #financial_amt_end").val(financial_amt_end);
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
						url: '../serverside/authormnt_affi_post.php?action_tiles=<?php echo encrypt($action_tiles, $key); ?>',
						//data: $('#frm_remark').serialize(),
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
								$(location).attr('href', '../masmnt/authomnt.php?gr=' + json.gr)
							}
						},
						complete: function () {
							$("#requestOverlay").remove();
						}
					});
				//}
			});
			function authorpostform(formid) {
				$(document).ready(function() {
					$.ajax({
						beforeSend: function() {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
							$("#requestOverlay").show(); /*Show overlay*/
						},
						type: 'POST',
						url: '../serverside/authormnt_affi_post.php?action_tiles=<?php echo encrypt($action_tiles, $key); ?>',

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
								$(location).attr('href', '../masmnt/authomnt_affi.php?gr=' + json.gr)
							}
						},
						
						complete: function() {
							$("#requestOverlay").remove(); /*Remove overlay*/
						}
					});
				});
			}
			/// เมื่อกรอกตัวเลขจำนวนเงิน ใน textbox ให้มันใส่คอมม่า
			function format(input){
				var num = input.value.replace(/\,/g,'');
				if(!isNaN(num)){
					if(num.indexOf('.') > -1){ 
						num = num.split('.');
						num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'');
						if(num[1].length > 2){ 
							alert('You may only enter two decimals!');
							num[1] = num[1].substring(0,num[1].length-1);
						}  input.value = num[0]+'.'+num[1];        
					} else{ input.value = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'') };
				}
				else{ alert('You may enter only numbers in this field!');
					input.value = input.value.substring(0,input.value.length-1);
				}
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
</html>																																		