<?php	
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php"); 
	include("../_incs/config.php");
	include("../_incs/funcServer.php");
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	clearstatcache();
	include("../crctrlbof/chkauthcr.php");
	include("../crctrlbof/chkauthcrctrl.php");
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key, $user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	$params = array();
	$activeid = decrypt(mssql_escape($_REQUEST['activeid']), $key);
	$crstm_nbr = decrypt(html_escape($_REQUEST['crnumber']), $key);
	
	$params = array($crstm_nbr);
	
	// $query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, emp_mstr.emp_th_firstname, ".
	// "emp_mstr.emp_th_lastname, emp_mstr.emp_email_bus FROM crstm_mstr INNER JOIN ".
	// "emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id where crstm_mstr.crstm_nbr = ?";
	
	$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_cus_name, emp_mstr.emp_th_firstname, ".
	"emp_mstr.emp_th_lastname, emp_mstr.emp_email_bus, emp_mstr.emp_th_pos_name, crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_rdo2, crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add,  ".
	"crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_sd_per_mm, crstm_mstr.crstm_cc1_reson, crstm_mstr.crstm_cc2_reson, crstm_mstr.crstm_cus_active,  ".
	"crstm_mstr.crstm_cr_mgr, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt  ".
	"FROM crstm_mstr INNER JOIN  ".
	"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id  ".
	"WHERE (crstm_mstr.crstm_nbr = ?)";
	$result_detail = sqlsrv_query($conn, $query_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$name_from = trim($rec_cus['emp_th_firstname']) . " " . trim($rec_cus['emp_th_lastname']);
		$email_bus = strtolower($rec_cus['emp_email_bus']);
		$emp_th_pos_name = html_clear($rec_cus['emp_th_pos_name']);
		$crstm_cus_name = html_clear($rec_cus['crstm_cus_name']);
		$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
		$crstm_chk_rdo2 = html_clear($rec_cus['crstm_chk_rdo2']);
		$crstm_approve = html_clear($rec_cus['crstm_approve']);
		$crstm_cc1_reson = html_clear($rec_cus['crstm_cc1_reson']);
		$crstm_cc2_reson = html_clear($rec_cus['crstm_cc2_reson']);
		$crstm_mgr_reson = html_clear($rec_cus['crstm_mgr_reson']);
		$crstm_cr_mgr = html_clear(number_format($rec_cus['crstm_cr_mgr']));
		$crstm_cus_active = html_clear($rec_cus['crstm_cus_active']);
	} 
	
	$params = array($crstm_nbr);
	//$query_cc="SELECT sum(tbl3_amt_loc_curr) as amt, tbl3_txt_ref FROM tbl3_mstr where tbl3_nbr = ? group by tbl3_txt_ref";
	$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? ";
	$result_cc = sqlsrv_query($conn, $sql_cc,$params);
	
	while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
	{
		
		$amt = html_clear($row_cc['tbl3_amt_loc_curr']);
		$txt_ref = html_clear($row_cc['tbl3_txt_ref']);
		$due_date = dmytx(html_clear($row_cc['tbl3_due_date']));
		$gr_tot +=  $amt ;
		
		if ($txt_ref == "C1") {
			$tot_c1 += $amt;
			} else {
			$tot_cc += $amt;
			$due_date = $due_date;
		}
	}
	if($crstm_cus_active=="1") { // เช็คลูกค้าเก่าหรือไม่
		if($crstm_chk_rdo2=="C1"){
			$subject = "เพื่อพิจารณาขออนุมัติปรับเพิ่มวงเงิน ให้ $crstm_cus_name";		
			//$txt_cc = "เพื่อพิจารณาขออนุมัติปรับเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น ".number_format($gr_tot)."  บาท ";																															
			$txt_cc = "<span style='color: red'><br>**&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; เพื่อพิจารณาขออนุมัติปรับเพิ่มวงเงิน ให้ $crstm_cus_name จาก ".number_format($tot_cc )." บาท   เป็น รวมวงเงินขออนุมัติ ".number_format($gr_tot)."  บาท  </span>";		

			}else {
			$subject ="เพื่อพิจารณาขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name "; 		
			$txt_cc = "เพื่อพิจารณาขออนุมัติต่ออายุวงเงิน ให้ $crstm_cus_name จาก $tot_cc  บาท 	จนถึงวันที่  $due_date";		
		}
	}else {
		    // ขอเพิ่มวงเงินลูกค้าใหม่
			$subject = "เพื่อพิจารณาขออนุมัติวงเงิน ให้ $crstm_cus_name";	
			$txt_cc = "<span style='color:Blue'><br>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;เพื่อพิจารณาขออนุมัติวงเงิน ให้ $crstm_cus_name   เป็น ".number_format($crstm_cc_amt)."  บาท <br></span> ";	
	}	
	
	// $mail_message = "เรียน คุณ ".$name."<br><br>" .
	// "เมื่อผ่านสินเชื่อแล้ว ขอให้ส่งใบขออนุมัติ เสนอ กจก. ผ่าน CMO / ผฝ. /  ผส. ทางอีเมล ต่อไป<br>" .
	// "<span style='color:red'><br>** หมายเหตุจากผู้อนุมัติ ** <br>" .$email . "</span><br><br>" .
	// " ขอบคุณค่ะ<br>";	
	
	//$mail_message = "เรียน คุณ ".$name." เมื่อผ่านสินเชื่อแล้ว ขอให้ส่งใบขออนุมัติ เสนอ กจก. ผ่าน CMO / ผฝ. /  ผส. ทางอีเมล ต่อไป";
	
	$mail_message = "เรียน คุณ ".$name." <br><br>" .
	"$txt_cc <br>".
	"ตามอำนาจดำเนินการ :  $crstm_approve <br><br>".
	"เหตุผลที่เสนอขอวงเงิน<br><br>".
	"$crstm_sd_reson <br><br>".
	"ความเห็นสินเชื่อ เห็นควรอนุมัติวงเงิน : $crstm_cr_mgr <br><br>".
	"$crstm_cc1_reson <br><br>".
	"$crstm_cc2_reson <br><br>".
	"$crstm_mgr_reson <br><br>".
	"รายละเอียดตามเอกสารแนบ <br><br>".
	"จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ <br>".
	"$name_from <br>".
	"$emp_th_pos_name <br>";
	
	
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
		<title><?php echo(TITLE) ?></title>
		<link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">
		<link rel="stylesheet" href="_libs/css/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">		
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-climacon.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/simple-line-icons/style.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/meteocons/style.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/forms/extended/form-extended.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/pickers/daterange/daterange.css">
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/forms/checkboxes-radios.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/icheck.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/custom.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/assets/css/style.css">
		<!-- END Custom CSS-->
	</head>
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
		<div id="result"></div>
		<?php include("../crctrlmain/menu_header.php"); ?>
		<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
		<?php include("../crctrlmain/modal.php"); ?>
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
								</ol>
							</div>
						</div>
					</div> 
				</div>
				
				<div class="content-body">
					<section class="new-project">
						<div class="row justify-content-md-center">
							<div class="col-8">	
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
											<FORM id="frm_crctrl_add" name="frm_crctrl_add" autocomplete=OFF method="POST" enctype="multipart/form-data">
												<input type=hidden name="action" value="send_mail"> 
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
												<input type="hidden" name="crstm_nbr" value="<?php echo($crstm_nbr) ?>">
												<input type="hidden" name="name_from" id="name_from" value="<?php echo($name_from) ?>">
												
												<h4 class="form-section text-info"><i class="fa fa-user"></i> ส่งอีเมลถึงผู้ที่เกี่ยวข้อง</h4>
												<div class="form-group">
													<label for="timesheetinput2">เรียน</label>
													<input type="text" id="name_to" class="form-control" placeholder="โปรดระบุ...ผู้อนุมัติ เช่น กจก. ผ่าน CMO / ผฝ.  / ผส." name="name_to" >
												</div>
												<div class="form-group">
													<label for="timesheetinput2">To... <font class="text text-danger font-weight-bold"> (xxx@xxx.com,xxx@xxx.com) กรณีส่งอีเมลมากกว่าหนึ่ง user คั่นด้วยเครื่องหมายคอมม่า (  ,  )  </font></label>
													<input type="text" id="email" class="form-control" placeholder="อีเมล" name="email">
												</div>
												<div class="form-group">
													<label for="timesheetinput2">Subject</label>
													<input type="text" id="subject" class="form-control" placeholder="หัวข้อเรื่อง" name="subject" value="<?php echo $subject ?>">
												</div>
												<div class="text_display" style="display:none;">
													<div class="form-group">
														<label for="timesheetinput2">Detail</label>
														<textarea type="hidden" id="detail" class="form-control" placeholder="หัวข้อเรื่อง" name="detail" rows="10" placeholder="รายละเอียด" style="line-height:1.5rem;" ><?php echo $mail_message ?></textarea>
													</div>
												</div>
											</form>		
												<div class="form-group row mt-n3"> 
													<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
														<button type="button" id="btnsave" name="btnsave" class="btn btn-success glow mb-1 mb-sm-0 mr-0 mr-sm-1" onclick="dispostform('frm_add')"><i class="fa fa-check-square-o"></i> Send</button>
														<button type="reset" class="btn btn-warning" onclick="document.location.href='../crctrlbof/crctrlall.php'"><i class="ft-x"></i> Cancel</button>
													</div>
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
		<!-- END: Footer-->
		<!-- BEGIN: Vendor JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<!-- BEGIN Vendor JS-->
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/jquery.knob.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/extensions/knob.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/raphael-min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/morris.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/data/jvector/visitor-data.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/chart.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/charts/jquery.sparkline.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/unslider-min.js"></script>
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>	
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/formatter/formatter.min.js"></script>
		<!-- END: Page Vendor JS-->
		
		<!-- BEGIN: Theme JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<!-- END: Theme JS-->
		
		<!-- BEGIN: Page JS-->
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/pages/dashboard-analytics.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/tables/datatables/datatable-basic.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/polyfill.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>
		
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/typeahead.bundle.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/handlebars.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-inputmask.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-formatter.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-maxlength.min.js"></script>
		<!-- END: Page JS-->
		<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
		<script type="text/javascript">
			function dispostform(formid) {
				//alert(formid);
				$(document).ready(function() {
					if (formid == 'frm_add') {
						Swalappform(formid);
					} 
					e.preventDefault();
				});
			}
			
			function Swalappform(formid) {
				//alert(formid+"--"+chk_action+"--"+cus_name);
				Swal.fire({
					//title: "Are you sure?",
					html: "คุณต้องการส่งอีเมลถึงผู้ที่เกี่ยวข้อง  นี้ใช่หรือไหม่ !!!! " , 
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, Send it!",
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
							var formObj = $('#frm_crctrl_add')[0];
							var formData = new FormData(formObj);
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								url: '../serverside/mailpost.php'  ,
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
										clearloadresult();
										//$('#sample_data').DataTable().ajax.reload(null, false); // call from external function
										Swal.fire({
											position: "top-end",
											type: "success",
											title: "Submit successfully.",
											showConfirmButton: false,
											timer: 500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										location.reload(true);
										$(location).attr('href', 'crctrlall.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
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