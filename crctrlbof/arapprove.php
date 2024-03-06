<?php 
	//Update can_editing
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include("../_incs/acunx_csrf_var.php");
	
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
		if (!matchToken($csrf_key,$user_login)) {
			echo "System detect CSRF attack!!";
			exit;
		}
	}
	
	$curdate = date('d/m/Y H:i:s');
	
	clearstatcache();
	include("chkauthcr.php");
	//$cr_cust_code1 = '1000901';
	$display_yes = "";
	//$cr_cust_code = "";
	$cr_cust_code = mssql_escape($_POST['cr_cust_code']);
	$phone_mask = mssql_escape($_POST['phone_mask']);
	$crstm_sd_per_mm = mssql_escape($_POST['crstm_sd_per_mm']);
	
	//$cus_city = mssql_escape($_POST['display_yes']);
	
	$params = array($cr_cust_code);
	$query_cust_detail = "SELECT cus_mstr.cus_nbr, cus_mstr.cus_name1, cus_mstr.cus_name2, cus_mstr.cus_name3, cus_mstr.cus_name4, cus_mstr.cus_street, cus_mstr.cus_street2, cus_mstr.cus_street3, ".
	"cus_mstr.cus_street4, cus_mstr.cus_street5, cus_mstr.cus_district, cus_mstr.cus_city, cus_mstr.cus_zipcode, cus_mstr.cus_country, cus_mstr.cus_tax_nbr3, cus_mstr.cus_terms_paymnt, ".
	"term_mstr.term_code, term_mstr.term_desc, country_mstr.country_desc, cus_mstr.cus_acc_group FROM cus_mstr INNER JOIN term_mstr ON cus_mstr.cus_terms_paymnt = term_mstr.term_code INNER JOIN ".
	"country_mstr ON cus_mstr.cus_country = country_mstr.country_code where cus_mstr.cus_nbr = ?";
	$result_cust_detail = sqlsrv_query($conn, $query_cust_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_cust_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$cus_nbr = $rec_cus['cus_nbr'];
		$crstm_cus_name = $rec_cus['cus_name1'];
		$cus_street = $rec_cus['cus_street'];
		$cus_street2 = $rec_cus['cus_street2'];
		$cus_street3 = $rec_cus['cus_street3'];
		$cus_street4 = $rec_cus['cus_street4'];
		$cus_street5 = $rec_cus['cus_street5'];
		$cus_district = $rec_cus['cus_district'];
		$cus_city = $rec_cus['cus_city'];
		$cus_country = $rec_cus['country_desc'];
		$cus_zipcode = $rec_cus['cus_zipcode'];
		$cus_street = $cus_street ." " . $cus_street2 ." ". $cus_street3 ." ". $cus_street4 ." ". $cus_street5 ." ". $cus_district ." ". $cus_city ." ". $cus_zipcode;
		$cus_tax_nbr3 = $rec_cus['cus_tax_nbr3'];
		$cus_terms_paymnt = $rec_cus['term_desc'];
		$cus_acc_group = $rec_cus['cus_acc_group'];
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
		<link rel="stylesheet" href="_libs/css/font-awesome/css/font-awesome.min.css">
		
		<!-- BEGIN VENDOR CSS-->
		
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
		<!-- END VENDOR CSS-->
		
		<!-- BEGIN ROBUST CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<!-- END ROBUST CSS-->
		
		<!-- BEGIN Page Level CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/meteocons/style.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/forms/extended/form-extended.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/pickers/daterange/daterange.css">
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/forms/checkboxes-radios.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/icheck.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/forms/icheck/custom.css">
		<!-- END Page Level CSS-->
		
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/pages/gallery.min.css">
		<!-- BEGIN Custom CSS-->
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
									<li class="breadcrumb-item"><a href="../crctrlbof/crctrladd.php"> ใบขออนุมัติวงเงินสินเชื่อ</a></li>
								</ol>
							</div>
						</div>
					</div>               
					<div class="content-header-right col-md-6 col-12">
						<?php if($can_editing) {
						?>
						<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
							<!--<div class="btn-group" role="group">
								<button class="btn btn-outline-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings icon-left"></i>Actions</button>
								<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
								<a class="dropdown-item" href="crctrladd.php">ใบขออนุมัติวงเงินสินเชื่อ</a>
								</div>
								</div>
								<a class="btn btn-outline-primary" href="#"><i class="fa fa-download"></i></a>
							<a class="btn btn-outline-primary" href="#"><i class="fa fa-calendar"></i></a>-->
						</div>
						<? } ?>
					</div>
				</div>
				<div class="content-body">
					<!-- Start New Project Section -->
					<section class="new-project">
						<div class="row ">
							<div class="col-12">	
								<!-- Start Card -->
								<div class="card">
									<div class="card-header mt-1 pt-0 pb-0" >
										<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
										<div class="heading-elements">
											<ul class="list-inline mb-0">        
												<li><a href="../crctrlbof/crctrladd.php"><i class="fa fa-plus"></i> เพิ่มใบขออนุมัติวงเงินสินเชื่อ</a></li>
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>
									</div>
									<div class="card-content collapse show ">                                    		
										<div class="card-body" style="margin-top:-20px;">
											
											<FORM id="frm_crctrlapp_add" name="frm_crctrlapp_add" autocomplete=OFF method="POST" >
												<input type=hidden name="action" value="crctrlappadd">
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
												
												<div class="form-body">		
													
													<!-- Start No.1 -->
													
													<div class="detailar_display" >
														
														<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 2. สำหรับหน่วยงานสินเชื่อ</h4>
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<label class="font-weight-bold">ประวัติการชำระเงินลูกค้า:</label>
																</div>
															</div>
															<div class="col-sm-3">
																<div class="form-group ">
																	<label>ปีก่อน:</label>
																	
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstc_pre_yy" name="crstc_pre_yy" >
																		<option value="" selected>--- เลือกปี ---</option>
																		
																		<?php
																			$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy ORDER BY tbl_yy_desc";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
																			<option value="<?php echo $r_doc['tbl_yy_desc']; ?>" 
																			<?php if ($crstc_pre_yy == $r_doc['tbl_yy_desc']) {
																				echo "selected";
																			} ?>>
																			<?php echo $r_doc['tbl_yy_desc']; ?></option>
																		<?php } ?>
																	</select>
																	
																	
																</div>	
															</div>
															<div class="col-md-3">
																<div class="form-group">
																	<label>% ตรงเวลา :</label>
																	<input type="text" class="form-control  input-sm font-small-3" id="crstc_otd_pct" value="<?php echo $crstc_otd_pct ?>" name="crstc_otd_pct" style="text-align: right">
																	
																</div>
															</div>
															<div class="col-md-3">
																<div class="form-group">
																	<label>ส่วนใหญ่ล่าช้าไม่เกิน  ( วัน ) :</label>
																	<input type="text" id="crstc_ovr_due" name="crstc_ovr_due" value="<?php echo $crstc_ovr_due ?>" class="form-control input-sm font-small-3" style="text-align: right">
																</div>
															</div>
															<div class="col-md-3">
																<div class="form-group">
																	<label>อื่น ๆ (ถ้ามี)  :</label>
																	<input type="text" id="crstc_etc" name="crstc_etc" value="<?php echo $crstc_etc ?>" class="form-control input-sm font-small-3">
																</div>
															</div>
														</div>
														
														<div class="row">
															<div class="col-sm-3">
																<div class="form-group ">
																	<label>ปีล่าสุด:</label>
																	
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstc_cur_yy" name="crstc_cur_yy">
																		<option value="" selected>--- เลือกปี ---</option>
																		<?php
																			$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy ORDER BY tbl_yy_desc";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
																			<option value="<?php echo $r_doc['tbl_yy_desc']; ?>" data-icon="fa fa-wordpress"><?php echo $r_doc['tbl_yy_desc']; ?></option>
																		<?php } ?>
																	</select>
																	
																	
																</div>	
															</div>
															<div class="col-md-3">
																<div class="form-group">
																	<label>% ตรงเวลา :</label>
																	<input type="text" id="crstc_otd1_pct" name="crstc_otd_pct" value="<?php echo $crstc_otd1_pct ?>" class="form-control input-sm font-small-3"  style="text-align: right" >
																	
																</div>
															</div>
															<div class="col-md-3">
																<div class="form-group">
																	<label>ส่วนใหญ่ล่าช้าไม่เกิน ( วัน ) :</label>
																	<input type="text" id="crstc_ovr1_due" name="crstc_ovr1_due" value="<?php echo $crstc_ovr1_due ?>" class="form-control input-sm font-small-3" style="text-align: right">
																</div>
															</div>
															<div class="col-md-3">
																<div class="form-group">
																	<label>อื่น ๆ (ถ้ามี)  :</label>
																	<input type="text" id="crstc_etc1" name="crstc_etc1" value="<?php echo $crstc_etc1 ?>" class="form-control input-sm font-small-3">
																</div>
															</div>
															
															<div class="col-md-3">
																<div class="form-group">
																	<label>Insurance:</label>
																	<input type="text" id="crstc_ins" name="crstc_ins" value="<?php echo $crstc_ins ?>" class="form-control input-sm font-small-3">
																</div>
															</div>
															
															<div class="col-md-12">
																<fieldset class="form-group">
																	<label for="placeTextarea" class="font-weight-bold">ความเห็นสินเชื่อ #1 :</label>
																	<textarea  name="crstc_cc_reson" id="crstc_cc_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;"><?php echo $crstc_cc_reson; ?></textarea>
																</fieldset>	
															</div>
															
															<div class="col-md-6">	
																<div class="form-group row">
																	<label class="col-md-3 label-control" for="userinput1">เอกสารแนบ:</label>
																	<div class="col-md-9">
																		<div class="row">
																			<div class="form-group col-12 mb-2">
																				<label>Select File</label>
																				<label id="projectinput8" class="file center-block">
																					<input type="file" accept="image/*" name="img_prj1" id="img_prj1">
																					<span class="file-custom"></span>
																				</label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-md-6">				
																<div class="form-group"> 
																	<div class="col-12 d-flex flex-sm-row flex-column justify-content-end ">
																		<?php if($can_editing) { ?>
																			<button type="button"  id="btnsave" class="btn btn-info"><i class="fa fa-check-square-o"></i> Continue</button>
																		<?php } ?>
																		
																	</div>
																</div>
															</div>
														</div>
														
														<div class="web_display" style="display:none;">	
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label class="font-weight-bold">Upload งบการเงินลูกค้า:</label>
																	</div>
																</div>
															</div>
															
															<div class="row">
																<div class="col-md-6">
																	<input type="radio"  id="dbd_conf_yes" name="cus_conf" value="1"  >
																	<label class="font-weight-bold" for="cus_conf_yes">งบการเงินจากเว็บไซต์กรมพัฒนาธุรกิจ</label>
																</div>
																<div class="col-md-6">
																	<input type="radio"  id="oth_conf_yes" name="cus_conf" value="2"  >
																	<label class="font-weight-bold" for="cus_conf_yes">งบการเงินจากแหล่งอื่นๆ</label>
																</div>
															</div>
														</div>
														
														<div class="dbd_display" style="display:none;">
															<div class="row">
																<div class="col-3">
																	<fieldset>
																		<label for="check_same" class="font-weight-bold">งบการเงิน ช่วงปี:</label>
																	</fieldset>
																</div>
																
																<div class="col-3">
																	<!--<input type="text" id="fin_sta" name="fin_sta" class="form-control input-sm font-small-3" >-->
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="fin_sta" name="fin_sta">
																		<option value="" selected>--- เลือกปี ---</option>
																		<?php
																		$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy ORDER BY tbl_yy_desc";
																		$result_doc = sqlsrv_query($conn, $sql_doc);
																		while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																		?>
																		<option value="<?php echo $r_doc['tbl_yy_code']; ?>" data-icon="fa fa-wordpress"><?php echo $r_doc['tbl_yy_desc']; ?></option>
																	<?php } ?>
																	</select>
																</div>
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-12 mb-2">
																			<label>Select File</label>
																			<label id="projectinput8" class="file center-block">
																				<input type="file" accept="image/*" name="img_fin_sta" id="img_fin_sta">
																				<span class="file-custom"></span>
																			</label>
																		</div>
																	</div>
																</div>
																
																
																<div class="col-3">
																	<fieldset>
																		<label for="check_same" class="font-weight-bold">งบการเงิน ช่วงปี:</label>
																	</fieldset>
																</div>
																
																<div class="col-3">
																	<!--<input type="text" id="fin_sta" name="fin_sta" class="form-control input-sm font-small-3" >-->
																	<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="fin_sta1" name="fin_sta1">
																		<option value="" selected>--- เลือกปี ---</option>
																		<?php
																			$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy ORDER BY tbl_yy_desc";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
																			<option value="<?php echo $r_doc['tbl_yy_code']; ?>" data-icon="fa fa-wordpress"><?php echo $r_doc['tbl_yy_desc']; ?></option>
																		<?php } ?>
																	</select>
																</div>
																<div class="col-md-6">
																	<div class="row">
																		<div class="form-group col-12 mb-2">
																			<label>Select File</label>
																			<label id="projectinput8" class="file center-block">
																				<input type="file" accept="image/*" name="img_fin_sta1" id="img_fin_sta1">
																				<span class="file-custom"></span>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
															
															<div class="card-body  my-gallery" itemscope itemtype="http://schema.org/ImageGallery">		
																<div class="row">
																	<figure class="col-md-6 col-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
																		<a href="../_images/ScreenShot001.jpg" itemprop="contentUrl" data-size="480x360">
																			<img class="img-thumbnail img-fluid" src="../_images/ScreenShot001.jpg" itemprop="thumbnail" alt="Image description" />
																		</a>
																	</figure>
																	<figure class="col-md-6 col-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
																		<a href="../_images/ScreenShot001.jpg" itemprop="contentUrl" data-size="480x360">
																			<img class="img-thumbnail img-fluid" src="../_images/ScreenShot001.jpg" itemprop="thumbnail" alt="Image description" />
																		</a>
																	</figure>
																	
																</div>
																
																<div class="row">
																	
																	<div class="col-md-12">
																		<fieldset class="form-group">
																			<label for="placeTextarea" class="font-weight-bold">ความเห็นสินเชื่อ #2 :</label>
																			<textarea  name="crstc_sd_reson" id="crstc_sd_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;"></textarea>
																		</fieldset>	
																	</div>
																	<div class="col-md-12">
																		<fieldset class="form-group">
																			<label for="placeTextarea" class="font-weight-bold">ความเห็น Manager:</label>
																			<textarea  name="crstc_sd_reson" id="crstc_sd_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;"></textarea>
																		</fieldset>	
																	</div>
																	
																	<div class="col-md-2">
																		<input type="radio"  id="dbd_conf_yes" name="cus_conf" value="1"  >
																		<label class="font-weight-bold" for="cus_conf_yes"> เห็นควรอนุมัติวงเงิน</label>
																		
																	</div>
																	<div class="col-md-2">
																		<input type="text" id="cus_terms_paymnt" name="cus_terms_paymnt" class="form-control input-sm font-small-3" style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)">
																	</div>
																	
																	<div class="col-md-4">
																		<input type="radio"  id="oth_conf_yes" name="cus_conf" value="2"  >
																		<label class="font-weight-bold" for="cus_conf_yes">ไม่เห็นควรอนุมัติ</label>
																	</div>
																</div>
															</div>
															
															<!-- End Form Body -->	
															<!-- Submit Button -->
															<div class="form-group row mt-n3"> 
																<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
																	<!--<button type="button" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1" onclick="document.location.href='../crctrlbof/crctrladd.php?custcode=<?php echo encrypt($cr_cust_code, $key); ?>'">continue</button>
																		<a class="btn btn-info" href="crctrladd.php?custcode=<?php echo encrypt($cr_cust_code, $key); ?>" > Continue </a>
																	<a class="btn btn-info glow mb-1 mb-sm-0 mr-0 mr-sm-1" href="crctrladd.php?custcode=<?php echo ($cr_cust_code); ?>" > Continue </a>-->
																	<button type="button"  id="btnsave1" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1"><i class="fa fa-check-square-o"></i> Save</button>
																	<button type="reset" class="btn btn-warning" onclick="document.location.href='../crctrlbof/arapprove.php'"><i class="ft-x"></i> Cancel</button>
																</div>
															</div>
															
														</form>	
														
														<!--</div>-->
													</div>
												</div>
												<!-- End Card -->
											</div>
										</div>
									</section>
									<!-- End New Project Section -->
								</div>
							</div>
						</div>
						
						<!-- END: Content-->			
						<div class="sidenav-overlay"></div>
						<div class="drag-target"></div>
						
						<!-- BEGIN: Footer-->
						<? include("../crctrlmain/menu_footer.php"); ?>
						<!-- END: Footer-->
						
						<!-- BEGIN: Vendor JS-->
						<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
						<!-- BEGIN Vendor JS-->
						
						<!-- BEGIN: Page Vendor JS-->
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
						
						<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-climacon.css">
						<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/fonts/simple-line-icons/style.min.css">
						
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
						
						<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/typeahead.bundle.min.js"></script>
						<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
						<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/handlebars.js"></script>
						<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-inputmask.min.js"></script>
						<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-formatter.min.js"></script>
						
						
						<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.min.js"></script>
						<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/customizer.min.js"></script>
						
						<!-- END: Page JS-->
						<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
						<script type="text/javascript">
							
							$(document).on('click', '#btccdel', function(e) {
								var tot_ord = $(this).data('tot_ord');
								var row_seq = $(this).data('row_seq');
								var pg = $(this).data('pg');
								
								Swal.fire({
									title: "Are you sure?",
									html: "คุณต้องการลบยอดวงเงิน " +<?echo tot_ord ; ?> + "  นี้ใช่หรือไหม่ !!!! ",
									type: "warning",
									showCancelButton: true,
									confirmButtonText: "Yes, delete it!",
									confirmButtonClass: "btn btn-primary",
									cancelButtonClass: "btn btn-danger ml-1",
									buttonsStyling: false,
									showLoaderOnConfirm: true,
									preConfirm: function() {
										return new Promise(function(resolve) {
											document.frm_del_cc.row_seq.value = row_seq;
											document.frm_del_cc.pg.value = pg;
											$.ajax({
												beforeSend: function() {
													//$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
													//$("#requestOverlay").show();/*Show overlay*/
												},
												type: 'POST',
												url: '../serverside/ccpost.php',
												data: $('#frm_del_cc').serialize(),
												timeout: 50000,
												error: function(xhr, error) {
													showmsg('[' + xhr + '] ' + error);
												},
												success: function(result) {
													// console.log(result);
													// alert(result);
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
														//$(location).attr('href', 'crctrladd.php?crmnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
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
							$(document).ready(function() {
								$("#btnsave").click(function() {
									$.ajax({
										beforeSend: function () {
											$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
											$("#requestOverlay").show();/*Show overlay*/
										},
										type: 'POST',
										url: '../serverside/crctrlapppost.php',
										data: $('#frm_crctrlapp_add').serialize(),
										timeout: 50000,
										error: function(xhr, error){
											showmsg('['+xhr+'] '+ error);						
										},
										success: function(result) {	
											
											//console.log(result);
											//alert(result);
											var json = $.parseJSON(result);
											if (json.r == '0') {
												clearloadresult();
												showmsg(json.e);
											}
											else {
												clearloadresult();
												$(location).attr('href', 'arapprove.php?crmnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
											}
										},
										complete: function () {
											$("#requestOverlay").remove();/*Remove overlay*/
										}
									});
								});
								
								
							} );
							
							(function(window, document, $) {
								'use strict';			
								// Nilubonp : inputMask : Email mask : form-extended-inputs.html
								$('#user_email').inputmask({
									mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
									greedy: false,
									onBeforePaste: function (pastedValue, opts) {
										pastedValue = pastedValue.toLowerCase();
										return pastedValue.replace("mailto:", "");
									},
									definitions: {
										'*': {
											validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]",
											cardinality: 1,
											casing: "lower"
										}
									}
								});
								// inputMask : Phone mask
								//$('#user_tel').inputmask("(999) 999-9999");
							})(window, document, jQuery);
							
							$('#cr_cust_code').typeahead({	
								
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
									
									$("#cr_cust_code").val(item.cus_nbr);
									$("#cr_cust_code1").val(item.cus_nbr);
									$("#crstm_cus_name").val(item.cus_name1);
									$("#cus_name2").val(item.cus_name2);
									$("#cus_name3").val(item.cus_name3);
									$("#cus_name4").val(item.cus_name4);
									$("#cus_street").val(item.cus_street+" "+item.cus_street2+" "+item.cus_street3+" "+item.cus_street4+" "+item.cus_street5+" "+item.cus_district+" "+item.cus_city+" "+item.cus_zipcode);
									$("#cus_street2").val(item.cus_street2);
									$("#cus_street3").val(item.cus_street3);
									$("#cus_street4").val(item.cus_street4);					
									$("#cus_street5").val(item.cus_street5);
									$("#cus_district").val(item.cus_district);
									$("#cus_city").val(item.cus_city);
									$("#cus_country").val(item.cus_country);
									$("#cus_zipcode").val(item.cus_zipcode);
									$("#cus_tax_nbr3").val(item.cus_tax_nbr3);
									$("#cus_terms_paymnt").val(item.cus_terms_paymnt);
									$("#cus_acc_group").val(item.cus_acc_group);
									
									// var custnumber = item.cus_nbr;
									// alert(custnumber);
									
									// var cemail_encrypt = window.atob(item.custpj_contact_email);
									// var cemail_replace = cemail_encrypt.replace("!", "@");	
									// var cemail_cut = cemail_replace.lastIndexOf("@");	
									// var cemail_substr = cemail_replace.substring(0, cemail_cut);					
									// $("#pjm_contact_email").val(cemail_substr);
								}
								
							});
							//  function เลือก checkbox อันใดอันหนึ่ง
							$(function(){        
								
								$(".css_data_item").click(function(){  // เมื่อคลิก checkbox  ใดๆ  
									if($(this).prop("checked")==true){ // ตรวจสอบ property  การ ของ   
										var indexObj=$(this).index(".css_data_item"); //   
										$(".css_data_item").not(":eq("+indexObj+")").prop( "checked", false ); // ยกเลิกการคลิก รายการอื่น  
									}  
								});  
								
								$("#form_checkbox1").submit(function(){ // เมื่อมีการส่งข้อมูลฟอร์ม  
									if($(".css_data_item:checked").length==0){ // ถ้าไม่มีการเลือก checkbox ใดๆ เลย  
										alert("NO");  
										return false;     
									}  
								});     
								
							});  
							
							$(function(){        
								
								$(".css_data_item1").click(function(){  // เมื่อคลิก checkbox  ใดๆ  
									if($(this).prop("checked")==true){ // ตรวจสอบ property  การ ของ   
										var indexObj=$(this).index(".css_data_item1"); //   
										$(".css_data_item1").not(":eq("+indexObj+")").prop( "checked", false ); // ยกเลิกการคลิก รายการอื่น  
									}  
								});  
								
								$("#form_checkbox1").submit(function(){ // เมื่อมีการส่งข้อมูลฟอร์ม  
									if($(".css_data_item1:checked").length==0){ // ถ้าไม่มีการเลือก checkbox ใดๆ เลย  
										alert("NO");  
										return false;     
									}  
								});     
								
							});  
							
							$(function(){        
								
								$(".css_data_item2").click(function(){  // เมื่อคลิก checkbox  ใดๆ  
									if($(this).prop("checked")==true){ // ตรวจสอบ property  การ ของ   
										var indexObj=$(this).index(".css_data_item2"); //   
										$(".css_data_item2").not(":eq("+indexObj+")").prop( "checked", false ); // ยกเลิกการคลิก รายการอื่น  
									}  
								});  
								
								$("#form_checkbox1").submit(function(){ // เมื่อมีการส่งข้อมูลฟอร์ม  
									if($(".css_data_item2:checked").length==0){ // ถ้าไม่มีการเลือก checkbox ใดๆ เลย  
										alert("NO");  
										return false;     
									}  
								});     
								
							});  
							
							function ccpostform(formid) {
								$(document).ready(function() {
									$.ajax({
										beforeSend: function() {
											$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
											$("#requestOverlay").show(); /*Show overlay*/
										},
										type: 'POST',
										url: '../serverside/ccpost.php',
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
												//$(location).attr('href', '../crctrlbof/crctrladd.php?cusnbr=' + json.nb + '&pg=' + json.pg)
											}
										},
										
										complete: function() {
											$("#requestOverlay").remove(); /*Remove overlay*/
										}
									});
								});
							}
							$('#crstm_pj_beg').datetimepicker({
								format: 'DD/MM/YYYY'
							});
							$('#crstm_pj1_beg').datetimepicker({
								format: 'DD/MM/YYYY'
							});
							$('#beg_date').datetimepicker({
								format: 'DD/MM/YYYY'
							});
							$('#end_date').datetimepicker({
								format: 'DD/MM/YYYY'
							});
							function formatCurrency(number) {
								number = parseFloat(number);
								return number.toFixed(2).replace(/./g, function(c, i, a) {
									return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
								});
							}	
							
							$('input[type="radio"]').click(function() {
								if($(this).attr('id') == 'dbd_conf_yes') {
									$('.dbd_display').show();  
									
									$("#fin_sta").val("");
									$("#fin_sta1").val("");
									$("#img_fin_sta").val("");
									$("#img_fin_sta1").val("");
									
									$("#fin_sta").prop("required", true);
									$("#fin_sta1").prop("required", true);
									
								}
								else if($(this).attr('id') == 'oth_conf_yes') {
									$('.dbd_display').show(); 
									
									$("#fin_sta").val("");
									$("#fin_sta1").val("");
									$("#img_fin_sta").val("");
									$("#img_fin_sta1").val("");
									
									$("#fin_sta").prop("required", true);
									$("#fin_sta1").prop("required", true);
								}
								
							});
							
							$(document).on("click", ".open-EditCCDialog", function() {
								
								var beg_date = $(this).data('begdte');
								var end_date = $(this).data('enddte');
								var cc_amt = $(this).data('cc_amt');
								var txt_ref = $(this).data('txt_ref');
								var txt_cc = $(this).data('txt_cc');
								
								$("#div_frm_cc_edit .modal-body #beg_date").val(beg_date);
								$("#div_frm_cc_edit .modal-body #end_date").val(end_date);
								$("#div_frm_cc_edit .modal-body #cc_amt").val(cc_amt);
								$("#div_frm_cc_edit .modal-body #txt_ref").val(txt_ref);
								$("#div_frm_cc_edit .modal-body #txt_cc").val(txt_cc);
							});
							// $('button[type="submit"]').click(function() {
							// if($(this).attr('id') == 'display_yes') {
							// $('.detailcrc_display').show(); 
							// $('.detailar_display').show();
							
							// }
							
							// });
							
							function chkNumber_dot(e){
								var keynum
								var keychar
								var numcheck
								if(window.event) {// IE
									keynum = e.keyCode
								}
								else if(e.which){ // Netscape/Firefox/Opera
									keynum = e.which
								}
								keychar = String.fromCharCode(keynum)
								numcheck = /\d|\./
								return numcheck.test(keychar)
							}
							//**** Check num dot (End) ***//
							
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
							
							/// Adding percentage to input box
							$("input[name='crstc_otd_pct']").on('input', function() {
								$(this).val(function(i, v) {
								return v.replace('%','') + '%';  });
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
					<!-- END: Body-->
					
				</html>																																																																																																																																																																																																																															