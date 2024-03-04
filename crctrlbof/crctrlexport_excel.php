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
		<title>รายงานสรุปรายการขออนุมัติวงเงิน</title>
		<link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/meteocons/style.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/pickers/daterange/daterange.css">
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
						<h3 class="content-header-title mb-0 d-inline-block">Report</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php">Home</a>
									</li>
									<li class="breadcrumb-item active"><font color="40ADF4">รายงานสรุปรายการขออนุมัติวงเงิน</font></li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<div class="content-body font-small-2 mt-n1">
					<!-- Province All -->
					<section id="project-all">
						<div class="row grouped-multiple-statistics-card">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title" id="basic-layout-form">Data search results</h4>
										<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-small-2"></i></a>
										<div class="heading-elements">
											<ul class="list-inline mb-0">
												<li><a data-action="collapse"><i class="ft-minus"></i></a></li>
												<!--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
												<li><a data-action="expand"><i class="ft-maximize"></i></a></li>
												<li><a data-action="close"><i class="ft-x"></i></a></li>-->
											</ul>
										</div>
									</div>
									
									<div class="card-content collapse show">
										<div class="card-body">
											<div class="row">
												<div class="col-md-3">
													<div class="form-group">
														<label class="font-weight-bold">วันที่ทำรายการ-เริ่ม</label>
														<div class="input-group input-group-sm">
															<input id="crstm_date" name="crstm_date" value="<?php echo $crstm_date ?>" class="form-control input-sm border-warning font-small-2" type="text">
															<div class="input-group-prepend">
																<span class="input-group-text">
																	<span class="fa fa-calendar-o"></span>
																</span>
															</div>
														</div>
													</div>
												</div>	
												<div class="col-md-3">
													<div class="form-group">
														<label class="font-weight-bold">วันที่ทำรายการ-สิ้นสุด</label>
														<div class="input-group input-group-sm">
															<input id="crstm_date1" name="crstm_date1" value="<?php echo $crstm_date1 ?>" class="form-control input-sm border-warning font-small-2" type="text">
															<div class="input-group-prepend">
																<span class="input-group-text">
																	<span class="fa fa-calendar-o"></span>
																</span>
															</div>
														</div>
													</div>
												</div>	
												<div class="col-md-3">
													<div class="form-group">
														<label class="font-weight-bold">วันที่วงเงินมีผล-เริ่ม</label>
														<div class="input-group input-group-sm">
															<input id="crstm_beg_date" name="crstm_beg_date" value="<?php echo $crstm_beg_date ?>" class="form-control input-sm border-warning font-small-2" type="text">
															<div class="input-group-prepend">
																<span class="input-group-text">
																	<span class="fa fa-calendar-o"></span>
																</span>
															</div>
														</div>
													</div>
												</div>	
												<div class="col-md-3">
													<div class="form-group">
														<label class="font-weight-bold">วันที่วงเงินมีผล-สิ้นสุด</label>
														<div class="input-group input-group-sm">
															<input id="crstm_end_date" name="crstm_end_date" value="<?php echo $crstm_end_date ?>" class="form-control input-sm border-warning font-small-2" type="text">
															<div class="input-group-prepend">
																<span class="input-group-text">
																	<span class="fa fa-calendar-o"></span>
																</span>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label class="font-weight-bold">วงเงินที่ขออนุมัติ-ตั้งแต่ </label>
														<div class="input-group input-group-sm">
															<input id="crstm_cc_amt" name="crstm_cc_amt" value="<?php echo $crstm_cc_amt ?>" class="form-control input-sm border-warning font-small-2" type="text" style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)">
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label class="font-weight-bold">วงเงินที่ขออนุมัติ-สิ้นสุด</label>
														<div class="input-group input-group-sm">
															<input id="crstm_cc_amt1" name="crstm_cc_amt1" value="<?php echo $crstm_cc_amt1 ?>" class="form-control input-sm border-warning font-small-2" type="text" style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)">
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group ">
														<label class="font-weight-bold">อนก.</label>
														<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-2 select2" id="crstm_approve" name="crstm_approve">
															<option value="" selected>--- เลือกอำนาจอนุมัติ ---</option>
															<?php
																$sql_doc = "SELECT DISTINCT author_text, author_seq from author_mstr where account_group='ZC01' order by  author_seq";
																$result_doc = sqlsrv_query($conn, $sql_doc);
																while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																?>
																<option value="<?php echo $r_doc['author_text']; ?>" 
																<?php if ($crstm_approve == $r_doc['author_text']) {
																	echo "selected";
																} ?>>
																<?php echo $r_doc['author_text']; ?></option>
															<?php } ?>
														</select>
													</div>	
												</div>	
												<div class="col-md-3">
													<div class="form-group ">
														<label class="font-weight-bold">สถานะ</label>
														<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-2 select2" id="crstm_step_name" name="crstm_step_name">
															<option value="" selected>--- เลือก ---</option>
															<?php
																$sql_doc = "SELECT DISTINCT crsta_step_code, crsta_step_name from crsta_mstr where crsta_step_action='1' order by crsta_step_name";
																$result_doc = sqlsrv_query($conn, $sql_doc);
																while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																?>
																<option value="<?php echo $r_doc['crsta_step_name']; ?>" 
																<?php if ($crstm_step_name == $r_doc['crsta_step_name']) {
																	echo "selected";
																} ?>>
																<?php echo $r_doc['crsta_step_name']; ?></option>
															<?php } ?>
														</select>
													</div>	
												</div>	
												<div class="col-md-3">
													<div class="form-group">
														<label class="font-weight-bold">ลูกค้า</label>
														<div class="input-group input-group-sm">
															<input id="cus_nbr" name="cus_nbr" value="<?php echo $cus_nbr ?>" class="form-control input-sm border-warning font-small-2" type="hidden">
															<input id="cus_name" name="cus_name" value="<?php echo $cus_name ?>" class="form-control input-sm border-warning font-small-2" type="text">
														</div>
													</div>
												</div>
												<div class="form-group text-center">
													<!--<button type="submit" class="btn btn-float btn-float-sm btn-outline-primary btn-round"><i class="fa fa-search"></i></button>-->
													<a href="javascript:void(0)" id="but_search" class="btn btn-social-icon mt-1 mr-1 btn-twitter"><span class="fa fa-search"></span></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--- End Search -->
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-content collapse show">
										<div class="card-body ">
											<div class="table-responsive">
												<!-- Project All -->
												<table id="table-data" class="table table-sm table-hover table-bordered compact nowrap" style="width:100%;">
													<!--dt-responsive nowrap-->
													<thead class="text-center" style="background-color:#f1f1f1;">
														<tr class="bg-info text-white font-weight-bold">
															<th>No.</th>
															<th>วันที่</th>
															<th>เอกสารเลขที่</th>
															<th>รหัสลูกค้า</th>
															<th>ชื่อลูกค้า</th>
															<th>วงเงินเดิม</th>
															<th>วงเงินที่ปรับ</th>
															<th>วงเงินใหม่</th>
															<th>วันที่เริ่ม</th>
															<th>วันที่สิ้นสุด</th>
															<th>อนก.</th>
															<th>สถานะ</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
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
		<!-- BEGIN: Vendor JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/unslider-min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/tables/datatables/datatable-basic.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
		<script type="text/javascript" language="javascript" class="init">
			$(document).ready(function() {
				//$("#but_search").trigger('click');
			});
			function setcookie(input0,callback) {
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: '../serverside/crctrlmnt_setcookie.php',
					data: {param0: JSON.stringify(input0)},
					async: false,
					timeout: 50000,
					success: function(result) {
						var json = $.parseJSON(result);
						return callback(json.ret);
					}
				});
			}
			$(document).on("click", "#but_search", function () {
				var iscookie;
				var $cus_name="";
				let input0 = {};
				input0.crstm_approve = $("#crstm_approve").val();
				input0.crstm_cc_amt = $("#crstm_cc_amt").val();
				input0.crstm_cc_amt1 = $("#crstm_cc_amt1").val();
				input0.crstm_date = $("#crstm_date").val();
				input0.crstm_date1 = $("#crstm_date1").val();
				input0.crstm_beg_date = $("#crstm_beg_date").val();
				input0.crstm_end_date = $("#crstm_end_date").val();
				input0.crstm_step_name = $("#crstm_step_name").val();
				input0.crstm_cus_nbr = $("#cus_nbr").val();
				$cus_name = $("#cus_name").val();
				if($cus_name != ""){
					input0.crstm_cus_nbr = $("#cus_nbr").val();
				} else {input0.crstm_cus_nbr = "";}
				
				setcookie(input0,function(results) {iscookie = results;})
				$.ajax({
					url: "../serverside/crctrlexport_list.php",
					type: "POST",
					dataType: 'json',
					data: {param0: JSON.stringify(input0)},
					beforeSend: function () {
						$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
						$("#requestOverlay").show();/*Show overlay*/
					},
					success: function (res) {
						if (res.success) {
							$("#table-data").dataTable().fnDestroy();
							$("#table-data").dataTable({
								dom: 'Bfrtip',
								buttons: [
								'excel',
									/* {
										extend: 'colvis',
										collectionLayout: 'fixed four-column'
									} */
								],
								"aaData" : res.data,
								"cache": false,
								"columns": [{ // Add row no. (Line 1,2,3,n)
									"data": "id",
									render: function(data, type, row, meta) {
										return meta.row + meta.settings._iDisplayStart+1 ;
									}
								},
								{"data": "crstm_date"},
								{"data": "crstm_nbr"},
								{"data": "crstm_cus_nbr"},
								{"data": "crstm_cus_name"},
								{"data": "crstm_cc_amt"},
								{"data": "crstm_cc1_amt"},
								{"data": "crstm_amt_new"},
								{"data": "crstm_cc_date_beg"},
								{"data": "crstm_cc_date_end"},
								{"data": "crstm_approve"},
								{"data": "crstm_step_name"},
								],
								"columnDefs" : [
								{"className": "text-center", "targets": [0, 1, 2, 8, 9, 11]},
								{"className": "text-right", "targets": [5, 6, 7]},
								{"width": 10, "targets": 0},
								{"width": 10, "targets": 1},
								{"width": 10, "targets": 2},
								
								/* { 
									"targets": [7],
									"render": function(data, type, row, meta) {
										return row.crstm_amt_new;
									}
								},
								{ 
									"targets": [8],
									"render": function(data, type, row, meta) {
										return row.crstm_cc_date_beg;
									}
								},
								{ 
									"targets": [9],
									"render": function(data, type, row, meta) {
										return row.crstm_cc_date_end;
									}
								},
								{ 
									"targets": [10],
									"render": function(data, type, row, meta) {
										return row.crstm_approve;
									}
								},
								{ 
									"targets": [11],
									"render": function(data, type, row, meta) {
										return row.crstm_step_name;
									}
								}, */
								
								],
								"searching": false,
								"ordering": false,
								"stateSave" : true,
								"pageLength": 10,
								"pagingType": "simple_numbers",						
							});
							$("#table-data").fadeIn();
							
							// var export_pg = "<a class='label label-success <?php echo $user_fb;?>' href='javascript:void(0)'>"+
							// " <i class='fa fa-download text-white'></i> Export to excel</a>";
							// $("#btn-export-to-excel").html(export_pg);
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
			
			$('#cus_name').typeahead({	
				
				displayText: function(item) {
					return item.cus_nbr+" "+item.cus_name1;
				}, 
				
				source: function (query, process) {
					jQuery.ajax({
						url: "../_help/getcustomer_detail.php",
						data: {query:query},
						dataType: "json",
						type: "POST",
						success: function (data) {
							process(data)
						}
					})
				},				
				
				items : "all",
				afterSelect: function(item) {
					
					$("#cus_nbr").val(item.cus_nbr);
					$("#cus_name").val(item.cus_name1);
				}
				
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
					
					$('#crstm_date').datetimepicker({
						format: 'DD/MM/YYYY'
					});
					$('#crstm_date1').datetimepicker({
						format: 'DD/MM/YYYY'
					});
					$('#crstm_beg_date').datetimepicker({
						format: 'DD/MM/YYYY'
					});
					$('#crstm_end_date').datetimepicker({
						format: 'DD/MM/YYYY'
					});
					
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
				<!-- END: Body-->
				
			</html>																																																