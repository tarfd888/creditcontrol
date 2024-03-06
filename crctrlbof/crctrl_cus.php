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
	$cr_cust_code = mssql_escape($_POST['cr_cust_code']);
	$cus_city = mssql_escape($_POST['display_yes']);
	
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
			//$cus_city = $rec_cus['cus_city'];
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
  <meta name="description"
    content="">
  <meta name="keywords"
    content="">
  <meta name="author" content="PIXINVENT">
  <title><?php echo(TITLE) ?></title>
  <link rel="apple-touch-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/apple-icon-120.png">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_DIR;?>/theme/app-assets/images/ico/favicon.ico">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700"
    rel="stylesheet">
  <link rel="stylesheet" href="_libs/css/font-awesome/css/font-awesome.min.css">

  <!-- BEGIN VENDOR CSS-->

  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">


  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/pickadate/pickadate.css">
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

  <!-- BEGIN Custom CSS-->
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/assets/css/style.css">
  <!-- END Custom CSS-->
</head>

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover"
  data-menu="vertical-menu" data-col="2-columns">
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
                <div class="card-header mt-1 pt-0 pb-0">
                  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                      <li><a href="../crctrlbof/crctrladd.php"><i class="fa fa-plus"></i>
                          เพิ่มใบขออนุมัติวงเงินสินเชื่อ</a></li>
                      <li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all"
                            onclick="javascript:window.history.back();"></i></a></li>
                      <li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card-content collapse show ">
                  <div class="card-body" style="margin-top:-20px;">

                    <FORM id="frm_crctrl_add" name="frm_crctrl_add" autocomplete=OFF method="post">
                      <input type=hidden name="action" value="crctrladd">
                      <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
                      <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                      <div class="form-body">
                        <h4 class="form-section text-info"><i class="fa fa-user"></i> ผู้ขอเสนออนุมัติ</h4>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="font-weight-bold">ชื่อ-สกุล :</label>
                              <input type="text" id="user_fullname" name="user_fullname"
                                value="<?php echo $user_fullname ?>" class="form-control input-sm font-small-3"
                                disabled>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="font-weight-bold">หน่วยงาน : </label>
                              <input type="text" id="user_th_pos_name" name="user_th_pos_name"
                                value="<?php echo $user_th_pos_name ?>" class="form-control input-sm font-small-3"
                                disabled>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="font-weight-bold">ผู้บังคับบัญชา :</label>
                              <input type="text" id="user_manager_name" name="user_manager_name"
                                value="<?php echo $user_manager_name ?>" class="form-control input-sm font-small-3"
                                disabled>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="font-weight-bold">E-mail:</label>
                              <input type="text" id="user_email" name="user_email" value="<?php echo $user_email ?>"
                                class="form-control input-sm font-small-3" disabled>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <h6>Phone
                              <small class="text-muted font-weight-bold">(999) 999-9999</small>
                            </h6>
                            <div class="form-group">
                              <input type="text" class="form-control phone-inputmask form-control input-sm font-small-3"
                                id="phone_mask" name="phone_mask" placeholder="ระบุหมายเลขโทรศัพท์" />
                            </div>
                          </div>
                        </div>
                        <!-- End  Sales Register   -->

                        <!-- Start Customber -->
                        <h4 class="form-section text-info"><i class="fa fa-address-card-o"></i> ข้อมูลลูกค้า</h4>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="font-weight-bold">รหัสลูกค้า :<font
                                  class="text text-danger font-weight-bold"> *</font></label>
                              <input type="text" id="cr_cust_code" name="cr_cust_code"
                                value="<?php echo $cr_cust_code ?>" class="form-control input-sm font-small-3"
                                placeholder="พิมพ์ชื่อ หรือ รหัสลูกค้า" required>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="font-weight-bold">ชื่อลูกค้า : </label>
                              <input type="text" id="crstm_cus_name" name="crstm_cus_name"
                                value="<?php echo $crstm_cus_name ?>" class="form-control input-sm font-small-3"
                                disabled>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="font-weight-bold">เลขประจำตัวผู้เสียภาษี :</label>
                              <input type="text" id="cus_tax_nbr3" name="cus_tax_nbr3"
                                value="<?php echo $cus_tax_nbr3 ?>" class="form-control input-sm font-small-3" disabled>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-4">
                            <fieldset class="form-group">
                              <label for="placeTextarea" class="font-weight-bold">ที่อยู่ :</label>
                              <textarea name="cus_street" id="cus_street" class="form-control input-sm font-small-3"
                                id="placeTextarea" rows="3" placeholder="ที่อยู่"
                                style="line-height:1.5rem;"> <?php echo $cus_street; ?></textarea>
                            </fieldset>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group">
                              <label class="font-weight-bold">จังหวัด :</label>
                              <input type="text" id="cus_city" name="cus_city" value="<?php echo $cus_city ?>"
                                class="form-control input-sm font-small-3" disabled>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group">
                              <label class="font-weight-bold">ประเทศ :</label>
                              <input type="text" id="cus_country" name="cus_country" value="<?php echo $cus_country ?>"
                                class="form-control input-sm font-small-3" disabled>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="font-weight-bold">เงื่อนไขการชำระเงิน :</label>
                              <input type="text" id="cus_terms_paymnt" name="cus_terms_paymnt"
                                value="<?php echo $cus_terms_paymnt ?>" class="form-control input-sm font-small-3"
                                disabled>
                            </div>
                          </div>

                        </div>
                        <!-- End  Customber   -->


                        <? if($cus_nbr !="") {?>
                        <div class="row match-height detailcrc_display">
                          <?php } 
												else { ?>
                          <div class="row match-height detailcrc_display" style="display:none;">
                            <?php } ?>
                            <!-- Start First Column -->
                            <div class="col-md-6">
                              <div class="card">
                                <div class="card-header">
                                  <class="card-title">
                                    <h6>สถานะวงเงินและหนี้ ณ วันที่ :
                                      <?echo $curdate; ?>
                                    </h6>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                </div>
                                <!--<div class="card-body collapse show">-->
                                <div class="card-block">
                                  <div class="table-responsive" style="padding:0px 15px 50px 20px;">
                                    <!-- Start Datatables -->
                                    <!--class="table table-sm table-hover table-bordered compact nowrap-->
                                    <table id="" class="table table-sm table-bordered compact nowrap "
                                      style="width:100%;">
                                      <!--dt-responsive nowrap-->
                                      <thead>
                                        <tr class="bg-success text-white font-weight-bold">
                                          <th>สถานะวงเงินและหนี้ </th>
                                          <th class="text-center" colspan='2'>จำนวนเงิน (บาท) </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?
																						// ข้อมูลตารางที่ 1  ---> crctrlpost.php
																						$params = array($cr_cust_code);
																						//$cr_cust_code = '1000901';
																						
																						$sql_cr= "SELECT crlimit_mstr.crlimit_acc, crlimit_mstr.crlimit_amt_loc_curr, crlimit_mstr.crlimit_doc_date, crlimit_mstr.crlimit_due_date, crlimit_mstr.crlimit_ref, crlimit_mstr.crlimit_txt_ref, acc_mstr.acc_name ".
																						"FROM crlimit_mstr INNER JOIN acc_mstr ON crlimit_mstr.crlimit_txt_ref = acc_mstr.acc_code WHERE (crlimit_mstr.crlimit_acc = ? ) ".
																						"GROUP BY crlimit_mstr.crlimit_acc, crlimit_mstr.crlimit_amt_loc_curr, crlimit_mstr.crlimit_doc_date, crlimit_mstr.crlimit_due_date, crlimit_mstr.crlimit_ref, ".
																						"crlimit_mstr.crlimit_txt_ref, acc_mstr.acc_name order by crlimit_txt_ref ";
																						$result = sqlsrv_query($conn, $sql_cr,$params);
																						
																						$tot_acc = 0;
																						$sum_acc = 0;
																						$tot_cc = 0;
																						while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																						{
																							$tot_acc = number_format($row_cr['crlimit_amt_loc_curr']);
																							$sum_acc = $row_cr['crlimit_amt_loc_curr'];
																							$chk_name = $row_cr['crlimit_txt_ref'];
																							$acc_name = $row_cr['acc_name'];
																							
																							//echo "<tr><td >".$acc_name."</td>";
																							if ($chk_name <> 'CI') {
																								echo "<tr><td align='left' >".$row_cr['acc_name']."</td>";
																								echo "<td colspan='1'></td>";
																								echo "<td align='right' >".$tot_acc."</td>";	
																								$tot_cc = $tot_cc + $sum_acc ;
																								}else { 
																								echo "<tr><td align='center'>".$row_cr['acc_name']." </td>";
																								echo "<td align='right'>".$tot_acc."</td >";	
																								echo "<td align='right' colspan='1'></td>";	
																							}
																							
																							echo "</tr>";
																						}
																						$grtot_acc = $tot_cc;
																						$tot_cc = number_format($tot_cc);
																						
																						$cc_txt = 'รวมวงเงินสินเชื่อ ';
																						if ($tot_cc <> 0) {
																							echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$cc_txt."</td>";
																							echo "<td align='right' style='color:blue' colspan='2' bgcolor='#f2f2f2'>".$tot_cc."</td>";
																						}
																					?>

                                        <?
																						$params = array($cr_cust_code);
																						$sql_ar ="SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, sum(ar_mstr.ar_amt_loc_curr) as ar_amt ".
																						"FROM ar_mstr INNER JOIN cracc_mstr ON ar_mstr.ar_acc = cracc_mstr.cracc_customer INNER JOIN ".
																						"cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr  where cracc_mstr.cracc_acc= ? group by cracc_mstr.cracc_acc,cus_name1";
																						$result = sqlsrv_query($conn, $sql_ar,$params);
																						
																						$tot_ar = 0;
																						$sum_ar = 0;
																						while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																						{
																							$tot_ar = number_format($row_ar['ar_amt']);
																							$sum_ar = $row_ar['ar_amt'];
																							$ar_txt = 'หนี้ทั้งหมด ' ;
																							echo "<tr><td>".$ar_txt."</td>";
																							echo "<td colspan='1'></td>";
																							echo "<td align='right'>".$tot_ar."</td>";	
																							echo "</tr>";
																						}
																					?>

                                        <?
																						$params = array($cr_cust_code);
																						$sql_ar= "SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, ar_mstr.ar_dura_txt, sum(ar_mstr.ar_amt_loc_curr) as ar_amt ".
																						"FROM ar_mstr INNER JOIN cracc_mstr ON ar_mstr.ar_acc = cracc_mstr.cracc_customer INNER JOIN ".
																						"cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr where cracc_mstr.cracc_acc = ? ".
																						"group by cracc_mstr.cracc_acc,ar_mstr.ar_dura_txt, cus_mstr.cus_name1 ";
																						$result = sqlsrv_query($conn, $sql_ar,$params);
																						
																						$tot_cur = 0;
																						$sum_cur = 0;
																						while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																						{
																							$tot_cur = number_format($row_ar['ar_amt']);
																							$sum_cur = $row_ar['ar_amt'];
																							$ar_dura_txt = $row_ar['ar_dura_txt'];
																							if ($ar_dura_txt == 'cur') {
																								$cur_txt = 'Current  ' ;
																								}else if ($ar_dura_txt == 'due')  {
																								$cur_txt = 'Due Today  ' ;
																								}else  if($ar_dura_txt == 'ovr')  {
																								$cur_txt = 'Overdue ' ;
																							}
																							echo "<tr><td align='center'>".$cur_txt."</td>";
																							echo "<td align='right'>".$tot_cur."</td>";	
																							echo "<td align=center'></td>";	
																							echo "</tr>";
																						}
																					?>

                                        <?
																						$params = array($cr_cust_code);
																						$sql_ord ="SELECT  ord_cr_acc, SUM(ord_mstr.ord_sales_val) AS sales_val FROM ord_mstr  WHERE (ord_cr_acc = ? ) group by ord_cr_acc";
																						$result = sqlsrv_query($conn, $sql_ord,$params);
																						
																						$tot_ord = 0;
																						$sum_ord = 0;
																						$grand_ord = 0;
																						while($row_ord = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																						{
																							$tot_ord = number_format($row_ord['sales_val']);
																							$sum_ord = $row_ord['sales_val'];
																							$ord_txt = 'ใบสั่งซื้อระหว่างดำเนินการ' ;
																							echo "<tr><td>".$ord_txt."</td>";
																							echo "<td colspan='1'></td>";
																							echo "<td align='right'>".$tot_ord."</td>";	
																							echo "</tr>";
																						}
																						
																						$grand_ord = $sum_ord + $sum_ar;
																						$sumgr_ord =  $sum_ord + $sum_ar;
																						$grand_txt = 'รวมยอดใช้วงเงิน';
																						$grand_ord = number_format($grand_ord);
																						echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																						echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$grand_ord."</td>";
																						echo "</tr>";
																						
																						//$grand_lmt =    $grtot_acc - $sumgr_ord ;
																						$grand_lmt =    $grtot_acc - $sumgr_ord ;
																						$grand_lmt = number_format($grand_lmt);
																						if ($grand_lmt < 0) {
																							$grand_txt = 'คงเหลือ / (เกิน) วงเงิน';
																							echo "<tr><td align='center' style='color:red' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																							echo "<td align='right' style='color:red' bgcolor='#f2f2f2' colspan='2'>".$grand_lmt."</td>";
																							}else {
																							$grand_txt = 'คงเหลือวงเงิน';
																							echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																							echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$grand_lmt."</td>";
																						}
																						$gr_per = 0;
																						$grand_txt = '% การใช้วงเงิน';
																						if ($sumgr_ord > 0 || $grtot_acc > 0) {
																						$gr_per = ($sumgr_ord / $grtot_acc ) * 100 ;
																						}
																						$gr_per = number_format($gr_per);
																						echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																						echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$gr_per." % </td>";
																						echo "</tr>";
																					?>

                                      </tbody>
                                    </table>
                                  </div>
                                </div>

                                <!--</div>-->
                              </div>
                            </div>
                            <!-- End First Column -->

                            <div class="col-md-6">
                              <div class="card">
                                <div class="card-header">
                                  <class="card-title">
                                    <h6>ประวัติการซื้อสินค้า 12 เดือนที่ผ่านมา ณ วันที่
                                      <?echo $curdate; ?>
                                    </h6>
                                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                                    <!--<div class="heading-elements">
																				<ul class="list-inline mb-0">
																				<li><a title="Reload this page" data-action="reload"><i class="ft-rotate-cw"></i></a></li>
																				<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
																				</ul>
																			</div>-->
                                </div>
                                <!--<div class="card-body collapse show">-->
                                <div class="card-block">
                                  <div class="table-responsive" style="padding:0px 15px 50px 20px;">
                                    <!-- Start Datatables -->
                                    <table id="" class="table table-sm table-hover table-bordered compact nowrap"
                                      style="width:100%;">
                                      <thead class="text-center">
                                        <tr class="bg-warning text-white font-weight-bold">
                                          <th>ปี - เดือน</th>
                                          <th class="text-center">ยอด Billing (บาท)</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?
																							$params = array($cr_cust_code);
																							$sql_bll= "SELECT TOP 12 cus_mstr.cus_name1, bll_mstr.bll_ym, sum(bll_mstr.bll_amt_loc_curr) as amt, cracc_mstr.cracc_acc ".
																							"FROM bll_mstr INNER JOIN cracc_mstr ON bll_mstr.bll_acc = cracc_mstr.cracc_customer INNER JOIN ".
																							"cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr WHERE (cracc_mstr.cracc_acc = ?) ".
																							"group by bll_mstr.bll_ym,cus_mstr.cus_name1,cracc_mstr.cracc_acc order by amt  desc ";
																							$result_bll = sqlsrv_query($conn, $sql_bll,$params);
																							
																							$bll_tot = 0 ;
																							$no = 0 ;
																							while($row_bll = sqlsrv_fetch_array($result_bll, SQLSRV_FETCH_ASSOC))
																							{
																								$tot_amt = $row_bll['amt'];
																								$tot_ord = number_format($row_bll['amt']);
																								$bll_ym = $row_bll['bll_ym'];
																								//$bll_doc_ym1 = substr($bll_doc_ym,0,4);
																								//$bll_doc_ym2 = substr($bll_doc_ym,4,2);
																								
																								echo "<td align='center'>".$row_bll['bll_ym']."</td>";
																								//echo "<td align='center'>".$bll_doc_ym1.'/'.$bll_doc_ym2."</td>";
																								echo "<td align='right'>".$tot_ord."</td>";									
																								echo "</tr>";
																								$bll_tot = $bll_tot + $tot_amt ;
																								$no = $no + 1;
																							}
																							if ($bll_tot != 0 ) {
																								$bll_avr = $bll_tot / $no ;
																							}
																							$bll_tot = number_format($bll_tot);
																							$bll_avr = number_format($bll_avr);
																							$acc_txt = 'Total';
																							$acc_avr = 'Average';
																							
																							echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_txt."</td>";
																							echo "<td align='right' style='color:blue' bgcolor='#f2f2f2'>".$bll_tot."</td>";
																							echo "<tr><td align='center' style='color:blue' bgcolor='#C0C0C0'>".$acc_avr."</td>";
																							echo "<td align='right' colspan='2' style='color:blue' bgcolor='#C0C0C0'>".$bll_avr."</td>";
																							echo "</tr>";
																						?>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>


                          <!-- Start No.1 -->
                          <? if($cus_nbr !="") {?>
                          <div class="detailar_display">
                            <?php } 
													else { ?>
                            <div class="detailar_display" style="display:none;">
                              <?php } ?>
                              <h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 1.
                                สำหรับหน่วยงานขาย (เสนอขออนุมัติวงเงินสินเชื่อ)</h4>
                              <div class="row">
                                <div class="col-md-3">
                                  <input type="radio" id="cus_conf_no" name="cus_conf" value="0">
                                  <label class="font-weight-bold" for="cus_conf_no"> วงเงินลูกค้าใหม่</label>
                                </div>
                                <div class="col-md-3">
                                  <input type="radio" id="cus_conf_yes" name="cus_conf" value="1">
                                  <label class=" font-weight-bold" for="cus_conf_yes"> วงเงินลูกค้าเก่า</label>
                                </div>
                                <div class="col-md-3">
                                  <input type="radio" id="term_conf_yes" name="cus_conf" value="2">
                                  <label class="font-weight-bold" for="cus_conf_yes">เงื่อนไขการชำระเงินเดิม</label>
                                </div>
                                <div class="col-md-3">
                                  <input type="radio" id="chg_term_conf_yes" name="cus_conf" value="3">
                                  <label class="font-weight-bold"
                                    for="cus_conf_yes">เปลี่ยนเงื่อนไขการชำระเงินใหม่จาก</label>
                                </div>
                              </div>

                              <div class="cus_display" style="display:none;">
                                <div class="row">
                                  <div class="col-md-3">
                                    <input type="radio" id="chk_add" name="chk_rdo" value="0">
                                    <label class="font-weight-bold" for="cus_conf_yes"> ปรับเพิ่มวงเงิน</label>
                                  </div>
                                  <div class="col-md-3">
                                    <input type="radio" id="chk_red" name="chk_rdo" value="1">
                                    <label class=" font-weight-bold" for="cus_conf_yes"> ปรับลดวงเงิน</label>
                                  </div>
                                  <div class="col-md-3">
                                    <input type="radio" id="chk_ext" name="chk_rdo" value="2">
                                    <label class=" font-weight-bold" for="cus_conf_yes"> ต่ออายุวงเงิน</label>
                                  </div>
                                </div>
                              </div>

                              <div class="term_display" style="display:none;">
                                <div class="row">
                                  <div class="col-3">
                                    <fieldset>
                                      <label for="check_same" class="font-weight-bold">เงื่อนไขการชำระเงินเดิม:</label>
                                    </fieldset>
                                  </div>
                                  <div class="col-3">
                                    <input type="text" id="terms_paymnt" name="terms_paymnt"
                                      class="form-control input-sm font-small-3" disabled>
                                  </div>
                                  <div class="col-3">
                                    <fieldset>
                                      <label class="font-weight-bold">โปรดระบุเพิ่ม:</label>
                                    </fieldset>
                                  </div>
                                  <div class="col-3">
                                    <div class="form-group">
                                      <!--<label class="font-weight-bold">เปลี่ยนจาก</label>-->
                                      <select data-placeholder="Select a doc type ..."
                                        class="form-control input-sm border-warning font-small-3 select2"
                                        id="term_desc_add" name="term_desc_add">
                                        <option value="" selected>--- เลือกเงื่อนไขการชำระเงินเพิ่ม ---</option>
                                        <?php
																					$sql_doc = "SELECT * FROM term_mstr order by term_code";
																					$result_doc = sqlsrv_query($conn, $sql_doc);
																					while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																					?>
                                        <option value="<?php echo $r_doc['term_code']; ?>" data-icon="fa fa-wordpress">
                                          <?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>

                                </div>
                              </div>

                              <div class="chg_term_display" style="display:none;">
                                <div class="row">
                                  <div class="col-3">
                                    <fieldset>
                                      <label for="check_same" class="font-weight-bold">ขอเปลี่ยนเงื่อนไขการชำระเงินใหม่
                                        จาก:</label>
                                    </fieldset>
                                  </div>
                                  <div class="col-3">
                                    <input type="text" id="terms_paymnt1" name="terms_paymnt1"
                                      class="form-control input-sm font-small-3" disabled>
                                  </div>
                                  <div class="col-3">
                                    <fieldset>
                                      <label class="font-weight-bold">เปลี่ยนเงื่อนไข:</label>
                                    </fieldset>
                                  </div>
                                  <div class="col-3">
                                    <div class="form-group">
                                      <!--<label class="font-weight-bold">เปลี่ยนจาก</label>-->
                                      <select data-placeholder="Select a doc type ..."
                                        class="form-control input-sm border-warning font-small-3 select2" id="term_desc"
                                        name="term_desc">
                                        <option value="" selected>--- เลือกเงื่อนไขการชำระเงินใหม่ ---</option>
                                        <?php
																					$sql_doc = "SELECT * FROM term_mstr order by term_code";
																					$result_doc = sqlsrv_query($conn, $sql_doc);
																					while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																					?>
                                        <option value="<?php echo $r_doc['term_code']; ?>" data-icon="fa fa-wordpress">
                                          <?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>

                                </div>
                              </div>

                              <div class="form-group row">
                                <label class="col-md-3 label-control font-weight-bold"
                                  for="userinput1">ประมาณการณ์ขายเฉลี่ยต่อเดือน: <font
                                    class="text text-danger font-weight-bold"> *</font></label>
                                <div class="col-md-3">
                                  <a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_sd_per_mm"
                                      class="form-control input-sm " name="crstm_sd_per_mm"
                                      style="color:blue;text-align:right" onkeypress="return chkNumber_dot(event)"></a>
                                </div>
                                <label class="col-md-2 label-control font-weight-bold" for="userinput1">บาท</label>
                              </div>

                              <!-- Start Table Clean Credit -->
                              <div class="col-sm-9">
                                <div class="card">
                                  <div class="card-block">
                                    <div class="table-responsive" style="padding:0px 15px 50px 20px;">
                                      <!-- Start Datatables -->
                                      <table id="tb_ord"
                                        class="table table-sm table-hover table-bordered compact nowrap"
                                        style="width:100%;">
                                        <!--dt-responsive nowrap-->
                                        <thead class="text-center" style="background-color:#f1f1f1;">
                                          <tr class="bg-info text-white font-weight-bold">
                                            <th rowspan="2">ขออนุมัติปรับวงเงินสินเชื่อ (Clean Credit)</th>
                                            <th colspan="2">อายุวงเงิน</th>
                                            <th rowspan="2">วงเงิน (บาท)</th>
                                          </tr>
                                          <tr class="bg-info text-white font-weight-bold">
                                            <th>วันที่เริ่ม</th>
                                            <th>วันที่สิ้นสุด</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?
																						$params = array($cr_cust_code);
																						$sql_cc= "SELECT crlimit_acc, sum(crlimit_amt_loc_curr) as amt_loc, crlimit_doc_date,crlimit_due_date FROM crlimit_mstr WHERE(crlimit_acc = ? and crlimit_ref = 'CC') GROUP BY crlimit_acc, crlimit_doc_date,crlimit_due_date order by crlimit_doc_date,crlimit_due_date";
																						$result_cc = sqlsrv_query($conn, $sql_cc,$params);
																						$tot_ord = 0 ;
																						$rows = 0;
																						while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
																						{
																						    $acc_txt = "วงเงินปัจจุบัน";
																							$acc_tot_txt = "รวมวงเงินขออนุมัติ";
																							$rows = $rows + 1;
																							$tot_ord = number_format($row_cc['amt_loc']);
																							$sum_ord = $row_cc['amt_loc'];
																							$doc_date = dmytx($row_cc['crlimit_doc_date']);
																							$due_date = dmytx($row_cc['crlimit_due_date']);
																							if ($rows == 1) {
																								echo "<td align='center'>".$acc_txt."</td>";
																							}else 
																							{
																								echo "<td align='center' colspan='1'></td>";
																							}
																							echo "<td align='center'>".$doc_date."</td>";	
																							echo "<td align='center'>".$due_date."</td>";	
																							echo "<td align='right'>".$tot_ord."</td>";		
																							echo "</tr>";
																							$acc_tot = $acc_tot + $sum_ord ;
																							
																						}
																						$acc_tot = number_format($acc_tot);
																						echo "<td align='center' colspan='3' style='color:blue'>".$acc_tot_txt."</td>";
																						echo "<td align='right' style='color:blue'>".$acc_tot."</td>";	
																					?>
                                        </tbody>
                                      </table>
                                    </div>
                                    <!--</div>-->
                                  </div>
                                </div>
                              </div>
                              <!-- End Table Clean Credit -->

                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group row">
                                    <label class="col-md-4 label-control font-weight-bold"
                                      for="userinput1">อำนาจดำเนินการขออนุมัติวงเงิน:</label>
                                    <div class="col-md-4">
                                      <input type="text" name="crstm_approve" id="crstm_approve"
                                        class="form-control input-sm">
                                      <!--<input type="text" name="cus_acc_group" id="cus_acc_group" class="form-control input-sm"  >-->
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-12">
                                  <fieldset class="form-group">
                                    <label for="placeTextarea" class="font-weight-bold">ความเห็น / เหตุผลที่เสนอขอวงเงิน
                                      :</label>
                                    <textarea name="crstm_sd_reson" id="crstm_sd_reson"
                                      class="form-control input-sm font-small-3" id="placeTextarea" rows="5"
                                      style="line-height:1.5rem;"></textarea>
                                  </fieldset>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label class="font-weight-bold">ข้อมูลโครงการ (ถ้ามี):</label>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">ชื่อโครงการ (1):</label>
                                    <div class="col-md-9">
                                      <input type="text" name="crstm_pj_name" id="crstm_pj_name"
                                        class="form-control input-sm">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">ชื่อโครงการ (2):</label>
                                    <div class="col-md-9">
                                      <input type="text" id="crstm_pj1_name" class="form-control input-sm"
                                        name="crstm_pj1_name">
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">จังหวัด:</label>
                                    <div class="col-md-9">
                                      <select data-placeholder="Select a doc type ..."
                                        class="form-control input-sm border-warning font-small-3 select2"
                                        id="crstm_pj_prv" name="crstm_pj_prv">
                                        <option value="" selected>--- เลือกจังหวัด ---</option>
                                        <?php
																					$sql_doc = "SELECT * FROM province_mstr order by province_id";
																					$result_doc = sqlsrv_query($conn, $sql_doc);
																					while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																					?>
                                        <option value="<?php echo $r_doc['province_th_name']; ?>"
                                          data-icon="fa fa-wordpress"><?php echo $r_doc['province_th_name']; ?></option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">จังหวัด:</label>
                                    <div class="col-md-9">
                                      <select data-placeholder="Select a doc type ..."
                                        class="form-control input-sm border-warning font-small-3 select2"
                                        id="crstm_pj1_prv" name="crstm_pj1_prv">
                                        <option value="" selected>--- เลือกจังหวัด ---</option>
                                        <?php
																					$sql_doc = "SELECT * FROM province_mstr order by province_id";
																					$result_doc = sqlsrv_query($conn, $sql_doc);
																					while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																					?>
                                        <option value="<?php echo $r_doc['province_th_name']; ?>"
                                          data-icon="fa fa-wordpress"><?php echo $r_doc['province_th_name']; ?></option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">เงื่อนไขการชำระ:</label>
                                    <div class="col-md-9">
                                      <select data-placeholder="Select a doc type ..."
                                        class="form-control input-sm border-warning font-small-3 select2"
                                        id="crstm_pj_term" name="crstm_pj_term">
                                        <option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---</option>
                                        <?php
																					$sql_doc = "SELECT * FROM term_mstr order by term_code";
																					$result_doc = sqlsrv_query($conn, $sql_doc);
																					while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																					?>
                                        <option value="<?php echo $r_doc['term_code']; ?>" data-icon="fa fa-wordpress">
                                          <?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">เงื่อนไขการชำระ:</label>
                                    <div class="col-md-9">
                                      <select data-placeholder="Select a doc type ..."
                                        class="form-control input-sm border-warning font-small-3 select2"
                                        id="crstm_pj1_term" name="crstm_pj1_term">
                                        <option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---</option>
                                        <?php
																					$sql_doc = "SELECT * FROM term_mstr order by term_code";
																					$result_doc = sqlsrv_query($conn, $sql_doc);
																					while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																					?>
                                        <option value="<?php echo $r_doc['term_code']; ?>" data-icon="fa fa-wordpress">
                                          <?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">มูลค่างาน (บาท):</label>
                                    <div class="col-md-9">
                                      <a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj_amt"
                                          class="form-control input-sm" name="crstm_pj_amt"
                                          onkeypress="return chkNumber_dot(event)"></a>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">มูลค่างาน (บาท):</label>
                                    <div class="col-md-9">
                                      <a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj1_amt"
                                          class="form-control input-sm" name="crstm_pj1_amt"
                                          onkeypress="return chkNumber_dot(event)"></a>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">ระยะเวลา (เดือน):</label>
                                    <div class="col-md-9">
                                      <a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj_dura"
                                          class="form-control input-sm" name="crstm_pj_dura"
                                          onkeypress="return chkNumber_dot(event)"></a>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">ระยะเวลา (เดือน):</label>
                                    <div class="col-md-9">
                                      <a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj1_dura"
                                          class="form-control input-sm" name="crstm_pj1_dura"
                                          onkeypress="return chkNumber_dot(event)"></a>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">เริ่มใช้งาน:</label>
                                    <div class="col-md-9">
                                      <div class="input-group input-group-sm">
                                        <input id="crstm_pj_beg" name="crstm_pj_beg"
                                          class="form-control input-sm border-warning " type="text"
                                          placeholder="เลือกวันเริ่มใช้งาน">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text">
                                            <span class="fa fa-calendar-o"></span>
                                          </span>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">เริ่มใช้งาน:</label>
                                    <div class="col-md-9">
                                      <div class="input-group input-group-sm">
                                        <input id="crstm_pj1_beg" name="crstm_pj1_beg"
                                          class="form-control input-sm border-warning " type="text"
                                          placeholder="เลือกวันเริ่มใช้งาน">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text">
                                            <span class="fa fa-calendar-o"></span>
                                          </span>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
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
                                  <div class="form-group row">
                                    <label class="col-md-3 label-control" for="userinput1">เอกสารแนบ:</label>
                                    <div class="col-md-9">
                                      <div class="row">
                                        <div class="form-group col-12 mb-2">
                                          <label>Select File</label>
                                          <label id="projectinput8" class="file center-block">
                                            <input type="file" accept="image/*" name="img_prj2" id="img_prj2">
                                            <span class="file-custom"></span>
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
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
                                <? if($cr_cust_code !="") {?>
                                <button type="button" id="btnsave"
                                  class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1"><i
                                    class="fa fa-check-square-o"></i> Save</button>
                                <button type="reset" class="btn btn-warning"
                                  onclick="document.location.href='../crctrlbof/crctrlall.php'"><i class="ft-x"></i>
                                  Cancel</button>
                                <?php } 
																	else { ?>
                                <button type="submit" id="display_yes"
                                  class="btn btn-success glow mb-1 mb-sm-0 mr-0 mr-sm-1"> แสดงข้อมูลลูกค้า</button>
                                <?php } ?>
                              </div>
                            </div>

                    </form>

                  </div>
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
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/checkbox-radio.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/icheck/icheck.min.js"></script>

  <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.min.js"></script>
  <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/customizer.min.js"></script>

  <!-- END: Page JS-->
  <script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $("#btnsave").click(function() {
      $.ajax({
        beforeSend: function() {
          $('body').append(
          '<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
          $("#requestOverlay").show(); /*Show overlay*/
        },
        type: 'POST',
        url: '../serverside/crctrlpost.php',
        data: $('#frm_crctrl_add').serialize(),
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
            showmsg(json.e);
          } else {
            clearloadresult();
            $(location).attr('href', 'crctrladd.php?crmnumber=' + json.nb + '&pg=' + json.pg +
              '&current_tab=30')
          }
        },
        complete: function() {
          $("#requestOverlay").remove(); /*Remove overlay*/
        }
      });
    });


  });

  (function(window, document, $) {
    'use strict';
    // Nilubonp : inputMask : Email mask : form-extended-inputs.html
    $('#user_email').inputmask({
      mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
      greedy: false,
      onBeforePaste: function(pastedValue, opts) {
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
      return item.cus_nbr + " " + item.cus_name1;
    },

    source: function(query, process) {
      jQuery.ajax({
        url: "../_help/getcustomer_detail.php",
        data: {
          query: query
        },
        dataType: "json",
        type: "POST",
        success: function(data) {
          process(data)
        }
      })
    },

    items: "all",
    afterSelect: function(item) {

      $("#cr_cust_code").val(item.cus_nbr);
      $("#cr_cust_code1").val(item.cus_nbr);
      $("#crstm_cus_name").val(item.cus_name1);
      $("#cus_name2").val(item.cus_name2);
      $("#cus_name3").val(item.cus_name3);
      $("#cus_name4").val(item.cus_name4);
      $("#cus_street").val(item.cus_street + " " + item.cus_street2 + " " + item.cus_street3 + " " + item
        .cus_street4 + " " + item.cus_street5 + " " + item.cus_district + " " + item.cus_city + " " + item
        .cus_zipcode);
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
  $(function() {

    $(".css_data_item").click(function() { // เมื่อคลิก checkbox  ใดๆ  
      if ($(this).prop("checked") == true) { // ตรวจสอบ property  การ ของ   
        var indexObj = $(this).index(".css_data_item"); //   
        $(".css_data_item").not(":eq(" + indexObj + ")").prop("checked", false); // ยกเลิกการคลิก รายการอื่น  
      }
    });

    $("#form_checkbox1").submit(function() { // เมื่อมีการส่งข้อมูลฟอร์ม  
      if ($(".css_data_item:checked").length == 0) { // ถ้าไม่มีการเลือก checkbox ใดๆ เลย  
        alert("NO");
        return false;
      }
    });

  });

  $(function() {

    $(".css_data_item1").click(function() { // เมื่อคลิก checkbox  ใดๆ  
      if ($(this).prop("checked") == true) { // ตรวจสอบ property  การ ของ   
        var indexObj = $(this).index(".css_data_item1"); //   
        $(".css_data_item1").not(":eq(" + indexObj + ")").prop("checked", false); // ยกเลิกการคลิก รายการอื่น  
      }
    });

    $("#form_checkbox1").submit(function() { // เมื่อมีการส่งข้อมูลฟอร์ม  
      if ($(".css_data_item1:checked").length == 0) { // ถ้าไม่มีการเลือก checkbox ใดๆ เลย  
        alert("NO");
        return false;
      }
    });

  });

  $(function() {

    $(".css_data_item2").click(function() { // เมื่อคลิก checkbox  ใดๆ  
      if ($(this).prop("checked") == true) { // ตรวจสอบ property  การ ของ   
        var indexObj = $(this).index(".css_data_item2"); //   
        $(".css_data_item2").not(":eq(" + indexObj + ")").prop("checked", false); // ยกเลิกการคลิก รายการอื่น  
      }
    });

    $("#form_checkbox1").submit(function() { // เมื่อมีการส่งข้อมูลฟอร์ม  
      if ($(".css_data_item2:checked").length == 0) { // ถ้าไม่มีการเลือก checkbox ใดๆ เลย  
        alert("NO");
        return false;
      }
    });

  });


  $("#chk_term").change(function() {
    if (this.checked) {
      $("#terms_paymnt").val($("#cus_terms_paymnt").val());
    } else {
      $("#terms_paymnt").val("");
    }
  });
  $("#chk_term_old").change(function() {
    if (this.checked) {
      $("#terms_paymnt1").val($("#cus_terms_paymnt").val());
    } else {
      $("#terms_paymnt1").val("");
    }
  });
  $('#crstm_pj_beg').datetimepicker({
    format: 'DD/MM/YYYY'
  });
  $('#crstm_pj1_beg').datetimepicker({
    format: 'DD/MM/YYYY'
  });

  function formatCurrency(number) {
    number = parseFloat(number);
    return number.toFixed(2).replace(/./g, function(c, i, a) {
      return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
    });
  }

  $('input[type="radio"]').click(function() {
    if ($(this).attr('id') == 'cus_conf_yes') {
      $('.cus_display').show();
      $("#chk_add").prop("required", true);
      $("#chk_red").prop("required", true);
      $("#chk_ext").prop("required", true);

      //// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
      $('.term_display').hide();
      $("#terms_paymnt").prop("required", false);

      $("#terms_paymnt").val(" ");
      $("#term_desc_add").val("");
      /////
      //// ซ่อนข้อมูลขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก
      $('.chg_term_display').hide();
      $("#terms_paymnt1").prop("required", false);

      $("#terms_paymnt1").val(" ");
      $("#term_desc").val("");
      /////
    } else if ($(this).attr('id') == 'cus_conf_no') {
      $('.cus_display').hide();
      $("#chk_add").prop("required", false);
      $("#chk_red").prop("required", false);
      $("#chk_ext").prop("required", false);

      $("#chk_add").prop("checked", false);
      $("#chk_red").prop("checked", false);
      $("#chk_ext").prop("checked", false);
      //// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
      $('.term_display').hide();
      $("#terms_paymnt").prop("required", false);

      $("#terms_paymnt").val(" ");
      $("#term_desc_add").val("");
      /////
      //// ซ่อนข้อมูลขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก
      $('.chg_term_display').hide();
      $("#terms_paymnt1").prop("required", false);

      $("#terms_paymnt1").val(" ");
      $("#term_desc").val("");
      /////
    } else if ($(this).attr('id') == 'term_conf_yes') {
      $('.term_display').show();
      $("#terms_paymnt").prop("required", true);
      $("#terms_paymnt").val($("#cus_terms_paymnt").val());

      //// ซ่อนข้อมูลวงเงินลูกค้าเก่า
      $('.cus_display').hide();
      $("#chk_add").prop("required", false);
      $("#chk_red").prop("required", false);
      $("#chk_ext").prop("required", false);

      $("#chk_add").prop("checked", false);
      $("#chk_red").prop("checked", false);
      $("#chk_ext").prop("checked", false);
      /////
      //// ซ่อนข้อมูลขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก
      $('.chg_term_display').hide();
      $("#terms_paymnt1").prop("required", false);

      $("#terms_paymnt1").val(" ");
      $("#term_desc").val("");
      /////
    } else if ($(this).attr('id') == 'chg_term_conf_yes') {
      $('.chg_term_display').show();
      $("#terms_paymnt1").prop("required", true);
      $("#terms_paymnt1").val($("#cus_terms_paymnt").val());

      //// ซ่อนข้อมูลวงเงินลูกค้าเก่า
      $('.cus_display').hide();
      $("#chk_add").prop("required", false);
      $("#chk_red").prop("required", false);
      $("#chk_ext").prop("required", false);

      $("#chk_add").prop("checked", false);
      $("#chk_red").prop("checked", false);
      $("#chk_ext").prop("checked", false);
      /////
      //// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
      $('.term_display').hide();
      $("#terms_paymnt").prop("required", false);

      $("#terms_paymnt").val(" ");
      $("#term_desc_add").val("");
      /////
    }

  });

  $('button[type="submit"]').click(function() {
    if ($(this).attr('id') == 'display_yes') {
      $('.detailcrc_display').show();
      $('.detailar_display').show();

    }

  });

  function chkNumber_dot(e) {
    var keynum
    var keychar
    var numcheck
    if (window.event) { // IE
      keynum = e.keyCode
    } else if (e.which) { // Netscape/Firefox/Opera
      keynum = e.which
    }
    keychar = String.fromCharCode(keynum)
    numcheck = /\d|\./
    return numcheck.test(keychar)
  }
  //**** Check num dot (End) ***//
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