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
	$action_cus = decrypt(mssql_escape($_REQUEST['action_cus']), $key);
	if($action_cus=="") {
		$action_cus = "Old";
		}
	
	include("chkauthcr.php");
	include("chkauthcrctrl.php");
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
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
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
						<h3 class="content-header-title mb-0 d-inline-block">All Data</h3>
						<div class="row breadcrumbs-top d-inline-block">
							<div class="breadcrumb-wrapper col-12">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php">Home</a>
									</li>
									<li class="breadcrumb-item active"><font color="40ADF4">List Document</font></li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<div class="content-body mt-n1">
					<!-- Province All -->
					<section id="project-all">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header " >
										<h4 class="card-title blue darken-2 font-weight-bold">[วงเงินสินเชื่อ]</h4>
										<div class="heading-elements">
											<ul class="list-inline mb-0">
											<?php if($can_editing && $action_cus=="New") { ?>
												<li><a href="../crctrlbof/crctrladd_new.php"><i class="fa fa-plus"></i> สร้างใบขออนุมัติวงเงินสินเชื่อ</a></li>
											<?php }else if($can_editing && $action_cus=="Old"){ ?>	
												<li><a href="../crctrlbof/crctrladd.php"><i class="fa fa-plus"></i> สร้างใบขออนุมัติวงเงินสินเชื่อ</a></li>
											<?php } ?>	
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>                             
									</div>
									<div class="card-content collapse show">
										<div class="card-body ">
											<div class="table-responsive">
												<!-- Project All -->
												<table id="custsp_list" class="table table-sm table-hover table-bordered compact nowrap " style="width:100%;">
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
													<tfoot>
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
													</tfoot>
												</table>
												<form name="frm_link_cr" id="frm_link_cr">
													<input type="hidden" name="action" value="link_cr">
													<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
													<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
													<input type="hidden" name="crstm_nbr" value="">
													<input type="hidden" name="pg" value="<?php echo $pg ?>">
												</form>
												<form name="frm_del_nbr" id="frm_del_nbr" action="../serverside/crctrlpost.php">
													<input type="hidden" name="action" value="del_crnbr">
													<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
													<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
													<input type="hidden" name="crstm_nbr" value="">
													<input type="hidden" name="pg" value="<?php echo $pg ?>">
													</form>
												<form name="frm_prt_cr" id="frm_prt_cr">
													<input type="hidden" name="action" value="prt_cr">
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
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
<!-- BEGIN: Footer-->
<? include("../crctrlmain/menu_footer.php"); ?>
<!-- END: Footer-->
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script language="javascript">
    $(document).ready(function() {
		$('#custsp_list').DataTable({
				"ajax": {
						url: "../serverside/crctrlall_list.php?crnbr=<?php echo encrypt($crstm_nbr, $key); ?>",
					type: "post",
					error: function() {
						$("#custsp_list-error").html("Cannot Query Quotation List");
						$("#custsp_list").append('<tbody ><tr><th colspan="12"><a  href="#div_add_qtm_project" data-toggle="modal"><i class="fa fa-plus"></i> เพิ่ม Quotation สำหรับโปรเจคนี้</a></th></tr></tbody>');
						$("#custsp_list processing").css("display", "none");
						$("#custsp_list").css("display", "none");
					}
				},
				"stateSave": true,
				"language": {
					"decimal": ",",
					"thousands": ".",
					//"emptyTable": '<a  href="#div_add_qtm_project" data-toggle="modal" style="font-size:1.2rem; line-height:3rem;"><i class="fa fa-plus"></i> เพิ่ม Quotation สำหรับโปรเจคนี้</a>'
				},
				"columnDefs": [
					{
						"className": "text-center",
						"targets": [0, 1, 2, 3, 6]
					},
					{
						"render": function(data, type, row) {
							 if (row.crstm_step_name == "Sale Revise") {  // 01
								return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Sale Revise',$conn); echo $bg; ?>' + ' round btn-sm">Sale Revise</span>';
							 } else if (row.crstm_step_name == "Draft") { // 0
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Draft',$conn); echo $bg; ?>' + ' round btn-sm">Draft</span>';
							 } else if (row.crstm_step_name == "Wait Credit 1") { // 10
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Wait Credit 1',$conn); echo $bg; ?>' + ' round btn-sm">Wait Credit 1</span>';
							 } else if (row.crstm_step_name == "Credit 1 Revise") {  // 11
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Credit 1 Revise',$conn); echo $bg; ?>' + ' round btn-sm">Credit 1 Revise</span>';
							 } else if (row.crstm_step_name == "Wait Credit 2") {  // 20
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Wait Credit 2',$conn); echo $bg; ?>' + ' round btn-sm">Wait Credit 2</span>';
							 } else if (row.crstm_step_name == "Credit 2 Revise") {  // 21
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Credit 2 Revise',$conn); echo $bg; ?>' + ' round btn-sm">Credit 2 Revise</span>';
						     } else if (row.crstm_step_name == "FinCR Mgr") {  // 30
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'FinCR Mgr',$conn); echo $bg; ?>' + ' round btn-sm">FinCR Mgr</span>';
							 } else if (row.crstm_step_name == "Credit Approved") {  // 40
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Credit Approved',$conn); echo $bg; ?>' + ' round btn-sm">Credit Approved</span>';
							 } else if (row.crstm_step_name == "Credit Reject") {  // 41
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Credit Reject',$conn); echo $bg; ?>' + ' round btn-sm">Credit Reject</span>';
							} else if (row.crstm_step_name == "Cancel") {  // 42
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Cancel',$conn); echo $bg; ?>' + ' round btn-sm">Cancel</span>';
							} else if (row.crstm_step_name == "Wait Approver") {  // 50
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Wait Approver',$conn); echo $bg; ?>' + ' round btn-sm">Wait Approver</span>';
							} else if (row.crstm_step_name == "Final Approved") {  // 60
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Final Approved',$conn); echo $bg; ?>' + ' round btn-sm">Final Approved</span>';
							} else if (row.crstm_step_name == "Initial Approved") {  // 61
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Initial Approved',$conn); echo $bg; ?>' + ' round btn-sm">Initial Approved</span>';
							} else if (row.crstm_step_name == "Not Approved") {  // 690
								return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Not Approved ',$conn); echo $bg; ?>' + ' round btn-sm">Not Approved </span>';
							} else if (row.crstm_step_name == "Wait Reviewer") {  // 110
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Wait Reviewer',$conn); echo $bg; ?>' + ' round btn-sm">Wait Reviewer</span>';
							} else if (row.crstm_step_name == "Reviewer1 Reject") {  // 113
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Reviewer1 Reject',$conn); echo $bg; ?>' + ' round btn-sm">Reviewer1 Reject</span>';
							}else if (row.crstm_step_name == "Wait Reviewer 2") {  // 220
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Wait Reviewer 2',$conn); echo $bg; ?>' + ' round btn-sm">Wait Reviewer 2</span>';
							}else if (row.crstm_step_name == "Reviewer2 Approved") {  // 221
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Reviewer2 Approved',$conn); echo $bg; ?>' + ' round btn-sm">Reviewer2 Approved</span>';
							}else if (row.crstm_step_name == "Reviewer2 Reject") {  // 223
							 	return '<span class="badge badge-pill ' + '<? $bg=findsqlval('crsta_mstr', 'crsta_step_color', 'crsta_step_name', 'Reviewer2 Reject',$conn); echo $bg; ?>' + ' round btn-sm">Reviewer2 Reject</span>';
							}
							
						},
						"targets": [6],
					},
					{
						"targets": [7],
						"render": function(data, type, row, meta) {
							var btnActionALL = "";
							var btnAction_Edit = "";
							var btnAction_View = "";
							var btnAction_Del = "";
							var btnAction_Prt = "";
							var btnAction_Mail = "";
							
							<?php if($can_editing) { ?>   // Sales
								if(row.crstm_cus_active=="1"){ // เช็คลูกค้าเก่า
									if(row.crstm_step_code =="0" || row.crstm_step_code =="01" || row.crstm_step_code =="112" || row.crstm_step_code =="222") { 	// Edit Old Customer
										var btnAction_Edit = '<a title="Edit Credit Control" id="bteditcr" data-directions="EDIT" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a> |';
									}
								}else{
									if(row.crstm_step_code =="0" || row.crstm_step_code =="01" || row.crstm_step_code =="112" ) { 	// Edit Old Customer
										var btnAction_Edit = '<a title="Edit Credit Control" id="bteditcr" data-directions="EDIT_NEW" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a> |';
									}	
								}
							<? } ?>
							
							<?php if($can_recall) { ?>   // Recall Approve
								if(row.crstm_cus_active=="1"){ // เช็คลูกค้าเก่า
									if(row.crstm_step_code =="60" || row.crstm_step_code =="65") { 	// Edit Old Customer
										var btnAction_Edit = '<a title="Edit Credit Control" id="bteditcr" data-directions="EDIT" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a> |';
									}
								}else {
									if(row.crstm_step_code =="10" || row.crstm_step_code =="11" || row.crstm_step_code =="20" || row.crstm_step_code =="21" || row.crstm_step_code =="30" ) { 	// Edit Old Customer
									var btnAction_Edit = '<a title="Edit Credit Control" id="bteditcr" data-directions="EDIT_NEW" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a> |';
									}	
								}
							<? } ?>
							
							<?php if($can_edit_cr) { ?>   // Edit
								if(row.crstm_cus_active=="1"){ // เช็คลูกค้าเก่า
									if(row.crstm_step_code =="10" || row.crstm_step_code =="11" || row.crstm_step_code =="20" || row.crstm_step_code =="21" || row.crstm_step_code =="30" ) { 	// Edit Old Customer
										var btnAction_Edit = '<a title="Edit Credit Control" id="bteditcr" data-directions="EDIT" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a> |';
									}
								}else {
									if(row.crstm_step_code =="10" || row.crstm_step_code =="11" || row.crstm_step_code =="20" || row.crstm_step_code =="21" || row.crstm_step_code =="30" ) { 	// Edit Old Customer
									var btnAction_Edit = '<a title="Edit Credit Control" id="bteditcr" data-directions="EDIT_NEW" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i></a> |';
									}	
								}
							<? } ?>
							
							if(row.crstm_cus_active=="1"){  // View Old Customer
								var btnAction_View = '<a title="View Credit Control" id="btviewcr"  data-directions="VIEW" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" href="javascript:void(0)" ><i class="fa fa-search-plus"></i></a>';
							}else {  // View New Customer
								var btnAction_View = '<a title="View Credit Control" id="btviewcr"  data-directions="VIEW_NEW" data-crnumber=" ' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" href="javascript:void(0)" ><i class="fa fa-search-plus"></i></a>';
							}
							
							<?php if($can_editing) { ?>  // ลบข้อมูล
								if(row.crstm_step_code == 0) {
									var btnAction_Del = '| <a id="btdelnbr" data-crstm_nbr ="' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" href="javascript:void(0)"><i class="fa fa-trash-o fa-sm "></i></a>';
								}
							<? } ?>
							
							<?php if($can_editing || $can_edit_cr) { ?> // ปริ้นเอกสาร
								if(row.crstm_step_code == 40 || row.crstm_step_code == 41 || row.crstm_step_code == 60 || row.crstm_step_code == 61) {
									if(row.crstm_cus_active == 1) {
									var btnAction_Prt = '| <a id="btprt" data-crstm_nbr ="' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="ft-printer"></i></a>';
									}else {
										var btnAction_Prt = '| <a id="btprt_new" data-crstm_nbr ="' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" data-cus_active="' +row.crstm_cus_active +'" href="javascript:void(0)"><i class="ft-printer"></i></a>';
									}
								}
							<? } ?>
							
							<?php if($can_editing) { ?> // ส่งอีเมล
								//if(row.crstm_step_code == 40 && row.crstm_cus_active == 1) {
								if(row.crstm_step_code == 40 )  {
									var btnAction_Mail = '| <a id="btsendMail" data-directions="SEND" data-crnumber ="' + row.crstm_nbr + '" data-cus_nbr="' +row.crstm_cus_nbr +'" href="javascript:void(0)"><i class="ft-mail"></i></a>';
								}
							<? } ?>
							
							btnActionALL = btnAction_Edit + btnAction_View + btnAction_Del + btnAction_Prt + btnAction_Mail;
							
							return btnActionALL;
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
					// { 
						// "targets": [3],
						// "render": function(data, type, row, meta) {
							// return row.crstm_cus_nbr + " | " + row.crstm_cus_name;
						// }
					// },
					
					{ 
						"targets": [5],
						"render": function(data, type, row, meta) {
							 return row.emp_prefix_th_name+" "+row.emp_th_firstname+"   "+row.emp_th_lastname ;
						}
					},
					{
						"data": "crstm_step_name"
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
				
				initComplete: function () {
					this.api().columns([1,4,6]).every( function (d) {
							var column = this;
							var theadname = $("#crctrlall_list").eq([d]).text();
							///var select = $('<select><option value="">All</option></select>')
							var select = $('<select class="form-control form-control-sm "  ><option value="" ><small>' +theadname +'ALL</small></option></select>' )
							.css( 'height', '20' )
							.appendTo( $(column.footer()).empty() )
							.on( 'change', function () {
								var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
								);
								column
								.search( val ? '^'+val+'$' : '', true, false )
								.draw();
							} );
							column.data().unique().sort().each( function ( d, j ) {
								select.append( '<option value="'+d+'" ><small>'+d+'</small></option>' )
							} );
						} );
					}
			});
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
			
			$(document).on('click', '#btviewcr,#bteditcr', function(e) {
				var crnumber = $(this).data('crnumber');
				var cus_nbr = $(this).data('cus_nbr');
				var directions = $(this).data('directions');
				
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
						
							if (directions == "VIEW") {
								var Linkdirections = 'crctrlviewmnt.php?crnumber=' + json.nb + '&current_tab=10&pg=' + json.pg;
							} else if (directions == "VIEW_NEW") {
								var Linkdirections = 'crctrlviewmnt_new.php?crnumber=' + json.nb + '&current_tab=10&pg=' + json.pg;
							} else if (directions == "EDIT") {
								<?php if($can_editing) { ?>
								var Linkdirections = 'crctrledit.php?crnumber=' + json.nb + '&current_tab=10&pg=' + json.pg;
								<?php } ?>
								<?php if($can_edit_cr) { ?>
								var Linkdirections = 'crctrledit_cr.php?crnumber=' + json.nb + '&current_tab=10&pg=' + json.pg;
								<?php } ?>
							} else if (directions == "EDIT_NEW") {
								<?php if($can_editing) { ?>
								var Linkdirections = 'crctrledit_new.php?crnumber=' + json.nb + '&current_tab=10&pg=' + json.pg;
								<?php } ?>
								<?php if($can_edit_cr) { ?>
								var Linkdirections = 'crctrledit_new.php?crnumber=' + json.nb + '&current_tab=10&pg=' + json.pg;
								<?php } ?>
							}	
							$(location).attr('href', Linkdirections)
						}
					},
					complete: function() {
						$("#requestOverlay").remove(); 
					}
				});
			});
			
			$(document).on('click', '#btsendMail', function(e) {
				var crnumber = $(this).data('crnumber');
				var cus_nbr = $(this).data('cus_nbr');
				var directions = $(this).data('directions');
				
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
						
							if (directions == "SEND") {
								var Linkdirections = 'frm_send_mail.php?crnumber=' + json.nb + '&current_tab=10&pg=' + json.pg;
							} 
							$(location).attr('href', Linkdirections)
						}
					},
					complete: function() {
						$("#requestOverlay").remove(); 
					}
				});
			});
			
			$(document).on('click', '#btprt', function(e) {
				var crnumber = $(this).data('crstm_nbr');
				var cus_nbr = $(this).data('cus_nbr');
				var cus_active = $(this).data('crstm_cus_active');
				
				//alert(crnumber);
				var r = confirm("คุณต้องการปริ้นใบขออนุมัติวงเงิน ใช่หรือไม่ ?");
				if (r == true) {
					 //window.open('../crctrlbof/crform_credit_customer.php?crnumber=<?php echo crnumber;?>', '_blank');
					//return true;
				} else {
					return false;
				} 
				document.frm_prt_cr.crstm_nbr.value = crnumber;
				document.frm_prt_cr.crstm_cus_nbr = cus_nbr;
				
				$.ajax({
					type: 'POST',
					url: '../serverside/crmnt_detail_post.php',
					data: $('#frm_prt_cr').serialize(),
					timeout: 3000,
					error: function(xhr, error) {
						showmsg('[' + xhr + '] ' + error);
					},
					success: function(result) {
					//console.log(result);
					// alert(result);
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
						window.open('../crctrlbof/crform_credit_customer.php?crnumber=' + json.nb +'&pg=' + json.pg +'', '_blank');
						}
					},
					complete: function() {
						$("#requestOverlay").remove(); 
					}
				});
			});
			
			$(document).on('click', '#btprt_new', function(e) {
				var crnumber = $(this).data('crstm_nbr');
				var cus_nbr = $(this).data('cus_nbr');
				var cus_active = $(this).data('crstm_cus_active');
				
				//alert(crnumber);
				var r = confirm("คุณต้องการปริ้นใบขออนุมัติวงเงิน ใช่หรือไม่ ?");
				if (r == true) {
					 //window.open('../crctrlbof/crform_credit_customer.php?crnumber=<?php echo crnumber;?>', '_blank');
					//return true;
				} else {
					return false;
				} 
				document.frm_prt_cr.crstm_nbr.value = crnumber;
				document.frm_prt_cr.crstm_cus_nbr = cus_nbr;
				
				$.ajax({
					type: 'POST',
					url: '../serverside/crmnt_detail_post.php',
					data: $('#frm_prt_cr').serialize(),
					timeout: 3000,
					error: function(xhr, error) {
						showmsg('[' + xhr + '] ' + error);
					},
					success: function(result) {
					//console.log(result);
					// alert(result);
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
						window.open('../crctrlbof/crform_credit_customer_new.php?crnumber=' + json.nb, '_blank');
						}
					},
					complete: function() {
						$("#requestOverlay").remove(); 
					}
				});
			});
				$(document).on('click', '#btdelnbr', function(e) {
					var crstm_nbr = $(this).data('crstm_nbr');
					var pg = $(this).data('pg');
					Swal.fire({
						title: "Are you sure?",
						html: "คุณต้องการลบชื่อ " +<?echo crstm_nbr ; ?> + "  นี้ใช่หรือไหม่ !!!! ",
						type: "warning",
						showCancelButton: true,
						confirmButtonText: "Yes, delete it!",
						confirmButtonClass: "btn btn-primary",
						cancelButtonClass: "btn btn-danger ml-1",
						buttonsStyling: false,
						showLoaderOnConfirm: true,
						preConfirm: function() {
							return new Promise(function(resolve) {
								document.frm_del_nbr.crstm_nbr.value = crstm_nbr;
								document.frm_del_nbr.pg.value = pg;
								$.ajax({
									beforeSend: function() {
										//$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
										//$("#requestOverlay").show();/*Show overlay*/
									},
									type: 'POST',
									url: '../serverside/crctrlpost.php',
									data: $('#frm_del_nbr').serialize(),
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
											clearloadresult();
											//$('#role_list').DataTable().ajax.reload(null, false); // call from external function
											$(location).attr('href', 'crctrlall.php?nbr='+json.nb+'&pg='+json.pg)
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
			function dispostform(formid) {
				$(document).ready(function() {
					$.ajax({
						beforeSend: function() {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
							$("#requestOverlay").show(); /*Show overlay*/
						},
						type: 'POST',
						url: '../serverside/mailpost.php',
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
								$(location).attr('href', '../masmnt/rolemstrmnt.php?rolemnumber=' + json.nb + '&pg=' + json.pg)
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
	
</html>																																		