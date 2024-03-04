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
	date_default_timezone_set('Asia/Bangkok');
	$curdate = date("d/m/Y H:i:s");
	$curYear = date('Y'); 
	$curMonth = date('m'); 
	$isreviewer = false;
	clearstatcache();
	
	include("chkauthcr.php");
	
	//// post ccpost.php  เช็คค่า  radio 
	$cusnbr = mssql_escape($_GET['cusnbr']);
	$cus_conf_yes = mssql_escape($_GET['nb1']);
	$cusold_conf_yes = mssql_escape($_GET['nb2']);
	$phone_mask1 = mssql_escape($_GET['nb3']);
	
	$cusnbr = html_escape(decrypt($cusnbr, $key));
	$cus_conf_yes = html_escape(decrypt($cus_conf_yes, $key));
	$cusold_conf_yes = html_escape(decrypt($cusold_conf_yes, $key));
	$phone_mask1 = html_escape(decrypt($phone_mask1, $key));
	
	$cr_cust_code = mssql_escape($_POST['cr_cust_code']);
	$phone_mask = mssql_escape($_POST['phone_mask']);
	$crstm_sd_per_mm = mssql_escape($_POST['crstm_sd_per_mm']);
	
	$reviewer = mssql_escape($_POST['reviewer']);
	
	if ($reviewer == "") {
			$reviewer = null;
	}
	
	if ($cusnbr != "") {
		$cr_cust_code = $cusnbr;
	} 
	if ($phone_mask1 != "") {
		$phone_mask = $phone_mask1;
		}else {
		$phone_mask = $phone_mask;
	}
	
	$params = array($cr_cust_code);
	$query_cust_detail = "SELECT cus_mstr.cus_nbr, cus_mstr.cus_name1, cus_mstr.cus_name2, cus_mstr.cus_name3, cus_mstr.cus_name4, cus_mstr.cus_street, cus_mstr.cus_street2, cus_mstr.cus_street3, ".
	"cus_mstr.cus_street4, cus_mstr.cus_street5, cus_mstr.cus_district, cus_mstr.cus_city, cus_mstr.cus_zipcode, cus_mstr.cus_country, cus_mstr.cus_tax_nbr3, cus_mstr.cus_terms_paymnt, ".
	"term_mstr.term_code, term_mstr.term_desc, country_mstr.country_desc, cus_mstr.cus_acc_group, cus_mstr.cus_stamp_date FROM cus_mstr INNER JOIN term_mstr ON cus_mstr.cus_terms_paymnt = term_mstr.term_code INNER JOIN ".
	"country_mstr ON cus_mstr.cus_country = country_mstr.country_code where cus_mstr.cus_nbr = ?";
	$result_cust_detail = sqlsrv_query($conn, $query_cust_detail,$params);
	$rec_cus = sqlsrv_fetch_array($result_cust_detail, SQLSRV_FETCH_ASSOC);
	if ($rec_cus) {
		$cus_nbr = html_clear($rec_cus['cus_nbr']);
		$crstm_cus_name = html_clear($rec_cus['cus_name1']);
		$cus_street = html_clear($rec_cus['cus_street']);
		$cus_street2 = html_clear($rec_cus['cus_street2']);
		$cus_street3 = html_clear($rec_cus['cus_street3']);
		$cus_street4 = html_clear($rec_cus['cus_street4']);
		$cus_street5 = html_clear($rec_cus['cus_street5']);
		$cus_district = html_clear($rec_cus['cus_district']);
		$cus_city = html_clear($rec_cus['cus_city']);
		$cus_country = html_clear($rec_cus['country_desc']);
		$cus_zipcode = html_clear($rec_cus['cus_zipcode']);
		$cus_street = $cus_street ." " . $cus_street2 ." ". $cus_street3 ." ". $cus_street4 ." ". $cus_street5 ." ". $cus_district ." ". $cus_city ." ". $cus_zipcode;
		$cus_tax_nbr3 = html_clear($rec_cus['cus_tax_nbr3']);
		$cus_terms_paymnt = html_clear($rec_cus['term_desc']);
		$cus_acc_group = html_clear($rec_cus['cus_acc_group']);
		$stamp_date = html_clear($rec_cus['cus_stamp_date']);
		$crstm_cus_nbr = html_clear($rec_cus['crstm_cus_nbr']);

	    $risk_cate = findsqlval("cracc_mstr", "cracc_risk_cate", "cracc_acc", $crstm_cus_nbr, $conn);
		
		switch($risk_cate) 
		{
			case "A" :
				$bgbadge = "badge badge-success";
				break;
			case "B" :
				$bgbadge = "badge badge-info";
				break;
			case "C" :
				$bgbadge = "badge badge-warning";
				break;	
			default:
				$bgbadge = "badge badge-danger";
		}
		
		$params = array($cr_cust_code);	
		//$query_detail = "SELECT  bll_acc,  bll_stamp_date FROM  bll_mstr where bll_acc =?";
		$query_detail = "SELECT top 1 bll_acc, bll_stamp_date FROM  bll_mstr where bll_acc =? group by bll_acc,bll_stamp_date order by bll_stamp_date desc";
		$result = sqlsrv_query($conn, $query_detail,$params);
		//$rec_result = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			$stamp1_date = html_clear(dmytx($row_ar['bll_stamp_date']));
		}
		
		
		$params = array($reviewer);
		$query_emp_detail = "SELECT * FROM emp_mstr where emp_email_bus = ? ";
		$result_emp_detail = sqlsrv_query($conn, $query_emp_detail,$params);
		$rec_emp = sqlsrv_fetch_array($result_emp_detail, SQLSRV_FETCH_ASSOC);
		if ($rec_emp) {
			$reviwer = html_clear($rec_emp['emp_email_bus']);
			$emp_prefix_th_name = html_clear($rec_emp['emp_prefix_th_name']);
			$emp_th_firstname = html_clear($rec_emp['emp_th_firstname']);
			$emp_th_lastname = html_clear($rec_emp['emp_th_lastname']);
			$reviewer_name = $emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ;
		} else {
			$isreviewer = true;
		}
	}

	$risk_cate = findsqlval("cracc_mstr", "cracc_risk_cate", "cracc_acc", $cus_nbr, $conn);
		
		switch($risk_cate) 
		{
			case "A" :
				$bgbadge = "badge badge-success";
				break;
			case "B" :
				$bgbadge = "badge badge-info";
				break;
			case "C" :
				$bgbadge = "badge badge-warning";
				break;	
			default:
				$bgbadge = "badge badge-danger";
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
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/extensions/toastr.min.css">
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
                                <li class="breadcrumb-item"><a href="../crctrlbof/crctrladd.php"> แสดงข้อมูลเครดิต</p>
                                        </a></li>
                            </ol>
                        </div>
                    </div>
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
                                    <a class="heading-elements-toggle"><i
                                            class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a title="Click to go back,hold to see history" data-action="reload"><i
                                                        class="fa fa-reply-all"
                                                        onclick="javascript:window.history.back();"></i></a></li>
                                            <li><a title="Click to expand the screen" data-action="expand"><i
                                                        class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show ">
                                    <div class="card-body" style="margin-top:-20px;">
                                        <FORM id="frm_crctrl_add" name="frm_crctrl_add" autocomplete=OFF method="POST"
                                            enctype="multipart/form-data">
                                            <input type=hidden name="action" value="crctrladd">
                                            <input type="hidden" name="csrf_securecode"
                                                value="<?php echo $csrf_securecode?>">
                                            <input type="hidden" name="csrf_token"
                                                value="<?php echo md5($csrf_token)?>">
                                            <input type="hidden" name="cr_cust_code"
                                                value="<?php echo encrypt($cr_cust_code, $key); ?>">
                                            <input type="hidden" name="phone_mask" value="<?php echo($phone_mask) ?>">
                                            <input type="hidden" id="cus_acc_group" name="cus_acc_group"
                                                value="<?php echo($cus_acc_group) ?>">

                                            <div class="form-body">
                                                <!-- Start Customber -->
                                                <h4 class="form-section text-info"><i class="fa fa-address-card-o"></i>
                                                    ข้อมูลลูกค้า</h4>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">รหัสลูกค้า :<font
                                                                    class="text text-danger font-weight-bold"> ***
                                                                </font></label>
                                                            <? if($cr_cust_code !="") {?>
                                                            <input type="text" id="cr_cust_code" name="cr_cust_code"
                                                                value="<?php echo $cr_cust_code ?>"
                                                                class="form-control input-sm font-small-3 border-warning"
                                                                placeholder="พิมพ์ชื่อ หรือ รหัสลูกค้า" required>
                                                            <?php } 
																else { ?>
                                                            <input type="text" id="cr_cust_code" name="cr_cust_code"
                                                                value="<?php echo $cr_cust_code ?>"
                                                                class="form-control input-sm font-small-3 border-warning"
                                                                placeholder="พิมพ์ชื่อ หรือ รหัสลูกค้า" required>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">ชื่อลูกค้า : </label>
                                                            <input type="text" id="crstm_cus_name1"
                                                                name="crstm_cus_name1"
                                                                value="<?php echo $crstm_cus_name ?>"
                                                                class="form-control input-sm font-small-3">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">เลขประจำตัวผู้เสียภาษี
                                                                :</label>
                                                            <input type="text" id="cus_tax_nbr3" name="cus_tax_nbr3"
                                                                value="<?php echo $cus_tax_nbr3 ?>"
                                                                class="form-control input-sm font-small-3">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">จังหวัด :</label>
                                                            <input type="text" id="cus_city" name="cus_city"
                                                                value="<?php echo $cus_city ?>"
                                                                class="form-control input-sm font-small-3">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">ประเทศ :</label>
                                                            <input type="text" id="cus_country" name="cus_country"
                                                                value="<?php echo $cus_country ?>"
                                                                class="form-control input-sm font-small-3">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">เงื่อนไขการชำระเงิน
                                                                :</label>
                                                            <input type="text" id="cus_terms_paymnt"
                                                                name="cus_terms_paymnt"
                                                                value="<?php echo $cus_terms_paymnt ?>"
                                                                class="form-control input-sm font-small-3">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <fieldset class="form-group">
                                                            <label for="placeTextarea" class="font-weight-bold">ที่อยู่
                                                                :</label>
                                                            <textarea name="cus_street" id="cus_street"
                                                                class="form-control input-sm font-small-3"
                                                                id="placeTextarea" rows="3" placeholder="ที่อยู่"
                                                                style="line-height:1.5rem;"> <?php echo $cus_street; ?></textarea>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <!-- End  Customber   -->

                                                <? if($cus_nbr !="") {?>
                                                <div class="row match-height detailcrc_display">
                                                    <?php } 
													else { ?>
                                                    <div class="row match-height detailcrc_display"
                                                        style="display:none;">
                                                        <?php } ?>
                                                        <!-- Start First Column -->
                                                        <div class="col-md-6">
                                                            <div class="table-responsive">
                                                                <p style="font-size:14px;">สถานะวงเงินและหนี้ ณ วันที่ :
                                                                    <?echo $stamp_date; ?>
                                                                </p>
                                                                <input type="hidden" name="stamp_date" id="stamp_date"
                                                                    value="<?php echo($stamp_date) ?>">
                                                                <table id=""
                                                                    class="table table-sm table-bordered compact nowrap "
                                                                    style="width:100%;">
                                                                    <!--dt-responsive nowrap-->
                                                                    <thead>
                                                                        <tr
                                                                            class="bg-success text-white font-weight-bold">
                                                                            <th>สถานะวงเงินและหนี้ </th>
                                                                            <th class="text-center" colspan='2'>
                                                                                จำนวนเงิน (บาท) </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?
																		// ข้อมูลตารางที่ 1  ---> crctrlpost.php
																		$params = array($cr_cust_code);
																		// $sql_cr= "SELECT crlimit_mstr.crlimit_acc, sum(crlimit_mstr.crlimit_amt_loc_curr) as crlimit_amt_loc_curr, crlimit_mstr.crlimit_txt_ref, acc_mstr.acc_name ".
																		// "FROM crlimit_mstr INNER JOIN acc_mstr ON crlimit_mstr.crlimit_txt_ref = acc_mstr.acc_code WHERE (crlimit_mstr.crlimit_acc = ? ) ".
																		// "GROUP BY crlimit_mstr.crlimit_acc, crlimit_mstr.crlimit_txt_ref, acc_mstr.acc_name order by crlimit_txt_ref";
																		
																		$sql_cr= "SELECT crlimit_mstr.crlimit_acc, SUM(crlimit_mstr.crlimit_amt_loc_curr) AS crlimit_amt_loc_curr, crlimit_mstr.crlimit_txt_ref, acc_mstr.acc_name, crlimit_mstr.crlimit_ref ".
																		"FROM crlimit_mstr INNER JOIN acc_mstr ON crlimit_mstr.crlimit_ref = acc_mstr.acc_code ".
																		"WHERE (crlimit_mstr.crlimit_acc = ?) GROUP BY crlimit_mstr.crlimit_acc, crlimit_mstr.crlimit_txt_ref, acc_mstr.acc_name, crlimit_mstr.crlimit_ref ORDER BY crlimit_mstr.crlimit_txt_ref ";
																		$result = sqlsrv_query($conn, $sql_cr, $params, array("Scrollable" => 'keyset'));
																		$row_counts = sqlsrv_num_rows($result);
																		
																		$tot_acc = 0;
																		$sum_acc = 0;
																		$tot_cc = 0;
																		while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																		{
																			$tot_acc = number_format($row_cr['crlimit_amt_loc_curr']);
																			$sum_acc = $row_cr['crlimit_amt_loc_curr'];
																			$chk_name = $row_cr['crlimit_ref'];
																			$acc_name = $row_cr['acc_name'];
																			
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
																		//if ($tot_cc <> 0) {
																		echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$cc_txt."</td>";
																		echo "<td align='right' style='color:blue' colspan='2' bgcolor='#f2f2f2'>".$tot_cc."</td>";
																		//}
																	?>

                                                                        <?
																		$params = array($cr_cust_code);
																		$sql_ar ="SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, sum(ar_mstr.ar_amt_loc_curr) as ar_amt ".
																		"FROM ar_mstr INNER JOIN cracc_mstr ON ar_mstr.ar_acc = cracc_mstr.cracc_customer INNER JOIN ".
																		"cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr  where cracc_mstr.cracc_acc= ? group by cracc_mstr.cracc_acc,cus_name1";
																		$result = sqlsrv_query($conn, $sql_ar, $params, array("Scrollable" => 'keyset'));
																		$row_counts = sqlsrv_num_rows($result);
																		
																		$tot_ar = 0;
																		$tot_ar1 = 0;
																		$sum_ar = 0;
																		while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																		{
																			
																			$tot_ar = round($row_ar['ar_amt']);
																			$tot_ar1 = round($row_ar['ar_amt']);
																			if ($tot_ar < 0) {
																				$tot_ar = ($tot_ar * -1);
																				$tot_ar = "(".(number_format($tot_ar)).")";  /// ใส่วงเล็บค่าที่ติดเลบ
																				}else {
																				$tot_ar = number_format($row_ar['ar_amt']);
																			}
																			$sum_ar = round($row_ar['ar_amt']);
																			$ar_txt = 'หนี้ทั้งหมด ' ;
																			echo "<tr><td>".$ar_txt."</td>";
																			echo "<td colspan='1'></td>";
																			echo "<td align='right'>".$tot_ar."</td>";	
																			echo "</tr>";
																		}
																		
																		if ($row_counts==0) {     
																			//$tot_ar = ($tot_ar * -1);
																			$ar_txt = 'หนี้ทั้งหมด  ' ;
																			echo "<tr><td>".$ar_txt."</td>";
																			echo "<td colspan='1'></td>";
																			echo "<td align='right'>0</td>";	
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
																			$tot_cur = $row_ar['ar_amt'];
																			if ($tot_cur < 0) {
																				$tot_cur = ($tot_cur * -1);
																				$tot_cur = "(".(number_format($tot_cur)).")";   /// ใส่วงเล็บค่าที่ติดเลบ
																				}else {
																				$tot_cur = number_format($row_ar['ar_amt']);
																			}
																			
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
																			$tot_ord = $row_ord['sales_val'];
																			if ($tot_ord < 0) {
																				$tot_ord = ($tot_ord * -1);
																				$tot_ord = "(".(number_format($tot_ord)).")";  /// ใส่วงเล็บค่าที่ติดเลบ
																				}else {
																				$tot_ord = number_format($row_ord['sales_val']);
																			}
																			
																			$sum_ord = round($row_ord['sales_val']);
																			$ord_txt = 'ใบสั่งซื้อระหว่างดำเนินการ' ;
																			echo "<tr><td>".$ord_txt."</td>";
																			echo "<td colspan='1'></td>";
																			echo "<td align='right'>".$tot_ord."</td>";	
																			echo "</tr>";
																		}
																		
																		$grand_ord = $sum_ord + $sum_ar;
																		$sumgr_ord =  $sum_ord + $sum_ar;
																		
																		if($grand_ord < 0) {
																			$grand_ord = ($grand_ord * -1);
																			$grand_ord = "(".(number_format($grand_ord)).")";
																			}else {
																			$grand_ord = number_format($grand_ord);
																			
																		}	
																		
																		$grand_txt = 'รวมยอดใช้วงเงิน';
																		echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																		echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$grand_ord."</td>";
																		echo "</tr>";
																		if($grtot_acc > 0) {
																			$grand_lmt = $grtot_acc - $sumgr_ord ; //  ยอด $grtot_acc +
																			}else {
																			$grand_lmt = $sumgr_ord ; // ถ้ายอด  $grtot_acc เป็นลบ เอายอด $sumgr_org มาแสดง
																		}
																		if ($grand_lmt < 0) {
																			$grand_txt = '(เกิน) วงเงิน';
																			$grand_lmt = ($grand_lmt * -1) ;
																			$grand_lmt = "(".(number_format($grand_lmt)).")";
																			echo "<tr><td align='center' style='color:red' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																			echo "<td align='right' style='color:red' bgcolor='#f2f2f2' colspan='2'>".$grand_lmt."</td>";
																			} else {
																			$grand_txt = 'คงเหลือวงเงิน';
																			$grand_lmt = number_format($grand_lmt);
																			echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
																			echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$grand_lmt."</td>";
																		}
																		
																		$gr_per = 0;
																		$grand_txt = '% การใช้วงเงิน';
																		if ($sumgr_ord > 0 && $grtot_acc > 0) {
																			$gr_per = ($sumgr_ord / $grtot_acc ) * 100 ;
																			} else {
																			$gr_per = '0';
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
                                                        <!-- End First Column -->
                                                        <div class="col-md-6">
                                                            <div class="table-responsive">
                                                                <p style="font-size:14px;">ประวัติการซื้อสินค้า 12
                                                                    เดือนที่ผ่านมา ณ วันที่
                                                                    <?echo $stamp1_date; ?>
                                                                </p>
                                                                <input type="hidden" name="stamp1_date" id="stamp1_date"
                                                                    value="<?php echo($stamp1_date) ?>">
                                                                <!-- Start Datatables -->
                                                                <table id=""
                                                                    class="table table-sm table-hover table-bordered compact nowrap"
                                                                    style="width:100%;">
                                                                    <thead class="text-center">
                                                                        <tr
                                                                            class="bg-warning text-white font-weight-bold">
                                                                            <th>ปี - เดือน</th>
                                                                            <th class="text-center">ยอด Billing (บาท)
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?
																		$params = array($cr_cust_code);
																		$sql_bll= "SELECT TOP 12 cus_mstr.cus_name1, bll_mstr.bll_ym, sum(bll_mstr.bll_amt_loc_curr) as amt, cracc_mstr.cracc_acc, bll_mstr.bll_stamp_date ".
																		"FROM bll_mstr INNER JOIN cracc_mstr ON bll_mstr.bll_acc = cracc_mstr.cracc_customer INNER JOIN ".
																		"cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr WHERE (cracc_mstr.cracc_acc = ?) ".
																		"group by bll_mstr.bll_ym,cus_mstr.cus_name1,cracc_mstr.cracc_acc, bll_mstr.bll_stamp_date order by bll_ym  desc  ";
																		$result_bll = sqlsrv_query($conn, $sql_bll,$params);
																		
																		$bll_tot = 0 ;
																		$no = 0 ;
																		$a = array();
																		$a_max = array();
																		while($row_bll = sqlsrv_fetch_array($result_bll, SQLSRV_FETCH_ASSOC))
																		{
																			$tot_amt = round($row_bll['amt']);
																			if($no>=1) {
																				$tot_avr += $tot_amt;
																			}
																			if($tot_amt < 0) {
																				$tot_amt = ($tot_amt * -1);
																				$tot_ord = "(".(number_format($tot_amt)).")";
																				}else {
																				$tot_ord = number_format($row_bll['amt']);
																			}	
																			$bll_ym = $row_bll['bll_ym'];
																			
																			$bll_doc_ym1 = substr($bll_ym,0,4);
																			$bll_doc_ym2 = substr($bll_ym,5,2);
																			
																			$bll_yofm = $bll_doc_ym1.'-'.$bll_doc_ym2;
																			$bll_tot = $bll_tot + $tot_amt ;
																			$no = $no + 1;
																			$a[$bll_yofm] = $tot_ord;	
																			$a_max[$bll_yofm] = $tot_amt;	
																			//print_r($a);
																		}
																		
																		$max_a = array_keys($a)[0];
																		$max_y = explode("-",$max_a)[0];
																		$max_m = explode("-",$max_a)[1];
																		
																		if($max_m < $curMonth) {$max_m = $curMonth ; }
																		
																		$min_a = array_keys($a)[count($a)-1];
																		$min_y = explode("-",$min_a)[0];
																		$min_m = explode("-",$min_a)[1];
																		
																		if($max_y !="" || $max_y !=0){
																			if($max_y-$min_y >=2){$min_y = $max_y - 1;}
																		}
																		$count = 0;
																		if ($tot_amt>0) {
																			$max_amt = max($a_max); // หาค่า max ใน array
																			
																			for ($y=$max_y; $y>=$min_y; $y--) {
																				for ($m=$max_m; $m>=1;$m--) {
																					$mx = substr("00{$m}", -2);
																					$period = "$y-$mx";
																					
																					if (array_key_exists($period, $a)) {
																						echo "<td align='center'>$period</td>";
																						echo "<td align='right'>".$a[$period]."</td>";									
																						echo "</tr>";
																						$count = $count + 1;
																						} else {
																						echo "<td align='center'>$period</td>";
																						echo "<td align='right'>0</td>";									
																						echo "</tr>";
																						$count = $count + 1;
																					}
																					if ($count >= 12) {
																					//if ($y == $min_y && $count == 12) {
																						break;
																					}
																				}
																				$max_m = 12;
																			}
																		}
																		if ($tot_avr != 0 ) {
																			$bll_avr = $tot_avr / 11 ;
																		}
																		$bll_tot = number_format($bll_tot);
																		$bll_avr = number_format($bll_avr);
																		$acc_txt = 'Total';
																		$acc_avr = 'Average';
																		$acc_max = 'Max';
																		
																		echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_txt."</td>";
																		echo "<td align='right' style='color:blue' bgcolor='#f2f2f2'>".$bll_tot."</td>";
																		echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_avr."</td>";
																		echo "<td align='right' colspan='2' style='color:blue' bgcolor='#f2f2f2'>".$bll_avr."</td>";
																		echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_max."</td>";
																		echo "<td align='right' colspan='2' style='color:blue' bgcolor='#f2f2f2'>".number_format($max_amt)."</td>";
																		echo "</tr>";
																	?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

														<div class="col-md-6 mt-1">
															<div class="row">
																<div class="form-group col-12 mb-2">
																	<small class="<?php echo $bgbadge; ?> block-area">Risk Categories (Grade) ปี <?php echo $curYear; ?> : <?php echo $risk_cate; ?>
																		<!-- <input type="file" name="multiple_files_edit" id="multiple_files_edit" multiple />
																		<span class="file-custom"></span>
																		<span class="text-muted">Only jpg, png, gif, pdf, xls, doc file
																			allowed</span>
																		<span id="error_multiple_files"></span> -->
																	</small>
																</div>
															</div> 
															<div class="table-responsive mb-2" id="image_table">
															</div>
														</div>
                                                    </div>

                                                    <div class="form-group row mt-n3">
                                                        <div
                                                            class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1 btn-group-sm">
                                                            <? if($cus_nbr !="") {?>
                                                            <button type="reset"
                                                                class="btn btn-outline-danger btn-min-width btn-glow mr-1 mb-1"
                                                                onclick="document.location.href='../crctrlbof/crctrlall.php'"><i
                                                                    class="ft-x"></i> Exit</button>
                                                            <?php } else { ?>
                                                            <button type="submit" id="display_yes"
                                                                class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1">
                                                                แสดงข้อมูลลูกค้า</button>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <!-- End New Project Section -->
                        </div>
                    </div>
            </div>
        </div>
        </section>
    </div>
    </div>
    </div>
    <form name="frm_risk" id="frm_risk">
        <input type="hidden" name="action" value="del_cc">
        <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
        <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
        <input type="hidden" name="cr_cust_code" value="">
    </form>


    <!-- END: Content-->
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-border">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span
                class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 <a
                    class="text-bold-800 grey darken-2" href="https://1.envato.market/pixinvent_portfolio"
                    target="_blank">PIXINVENT </a></span><span class="float-md-right d-none d-lg-block">Power by IT
                Business Solution Team <i class="feather icon-heart pink"></i></span></p>
    </footer>
    <!-- END: Footer-->
    <!-- BEGIN: Vendor JS-->
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js">
    </script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js">
    </script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/formatter/formatter.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/toastr.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script
        src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js">
    </script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js">
    </script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js">
    </script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-inputmask.min.js"></script>
    <script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-maxlength.min.js"></script>
    <script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
    <script type="text/javascript">
    load_risk_data()
    //// How to prevent the Confirm Form Resubmission dialog
    $(document).ready(function() {
        window.history.replaceState('', '', window.location.href)
    });

    function dispostform(formid, chk_action, cus_name) {
        //alert(formid+"--"+chk_action+"--"+cus_name);
        $(document).ready(function() {
            if (formid == 'frm_add') {
                Swalappform(formid, chk_action, cus_name);
            } else if (formid == 'frm_add_send') {
                Swalappformsend(formid, chk_action, cus_name);
            }
            e.preventDefault();
        });
    }

    function Swalappform(formid, chk_action, cus_name) {
        //alert(formid+"--"+chk_action+"--"+cus_name);
        Swal.fire({
            //title: "Are you sure?",
            html: "คุณต้องการบันทึกข้อมูล  <br>ลูกค้า   " + cus_name + " นี้ใช่หรือไหม่ !!!! ",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Save it!",
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
                            $('body').append(
                                '<div id="requestOverlay" class="request-overlay"></div>'
                                ); /*Create overlay on demand*/
                            $("#requestOverlay").show(); /*Show overlay*/
                        },
                        type: 'POST',
                        url: '../serverside/crctrlpost.php?step_code=' + chk_action,
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
                                //location.reload(true);
                                clearloadresult();
                                $(location).attr('href', 'crctrledit.php?crnumber=' +
                                    json.nb + '&pg=' + json.pg + '&current_tab=30')
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

    function Swalappformsend(formid, chk_action, cus_name) {
        //alert(formid+"--"+chk_action+"--"+cus_name);
        Swal.fire({
            //title: "Are you sure?",
            html: "คุณได้ทำการแก้ไข และ บันทึกข้อมูลเรียบร้อยแล้ว ก่อนส่งข้อมูล  <br>ลูกค้า   " + cus_name +
                " ไปให้แผนกสินเชื่ออนุมัติ ใช่หรือไม่ !!!! ",
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
                            $('body').append(
                                '<div id="requestOverlay" class="request-overlay"></div>'
                                ); /*Create overlay on demand*/
                            $("#requestOverlay").show(); /*Show overlay*/
                        },
                        type: 'POST',
                        url: '../serverside/crctrlpost.php?step_code=' + chk_action,
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
                                $(location).attr('href', 'crctrlall.php?crnumber=' +
                                    json.nb + '&pg=' + json.pg + '&current_tab=30')
                            }
                        },
                        complete: function() {
                            $("#requestOverlay").remove(); /*Remove overlay*/
                        }
                    });
                }); /////
            },
            allowOutsideClick: false
        });
    }

    ///
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
        $('#phone_mask').inputmask("(999) 999-9999");
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
            $("#crstm_cus_name1").val(item.cus_name1);
            $("#cus_name2").val(item.cus_name2);
            $("#cus_name3").val(item.cus_name3);
            $("#cus_name4").val(item.cus_name4);
            $("#cus_street").val(item.cus_street + " " + item.cus_street2 + " " + item.cus_street3 + " " +
                item.cus_street4 + " " + item.cus_street5 + " " + item.cus_district + " " + item
                .cus_city + " " + item.cus_zipcode);
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
        }

    });

    $('#reviewer').typeahead({

        displayText: function(item) {
            return item.emp_th_firstname + " " + item.emp_th_lastname + " " + item.emp_email_bus;
        },

        source: function(query, process) {
            jQuery.ajax({
                url: "../_help/getemp_detail.php",
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

            $("#reviewer").val(item.emp_email_bus);
            $("#reviewer_name").val(item.emp_th_pos_name);
        }

    });

    function ccpostform(formid) {
        $(document).ready(function() {
            $.ajax({
                beforeSend: function() {
                    $('body').append(
                    '<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
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
                        //location.reload(true);
                        $(location).attr('href', '../crctrlbof/crctrladd.php?cusnbr=' + json.nb +
                            '&nb1=' + json.nb1 + '&nb2=' + json.nb2 + '&nb3=' + json.nb3)
                    }
                },

                complete: function() {
                    $("#requestOverlay").remove(); /*Remove overlay*/
                }
            });
        });
    }

    $("#cc_amt1").on("change", function() {
        var cus_acc_group = $("#cus_acc_group").val();
        var cc_amt1 = $(this).val();
        var acc_tot = $("#acc_tot").val();
        var result_cc_amt1 = parseFloat(cc_amt1.replace(/\$|,/g, ''))
        var result_acc_tot = parseFloat(acc_tot.replace(/\$|,/g, ''))

        var sum_amt_acc = parseInt(result_cc_amt1) + parseInt(result_acc_tot);
        acc_tot_app = sum_amt_acc;

        if (cus_acc_group == "ZC01" || cus_acc_group == "ZC07") {
            if (acc_tot_app <= 700000) {
                crstm_approve = 'ผส. อนุมัติ';
            } else if (acc_tot_app >= 700001 && acc_tot_app <= 2000000) {
                crstm_approve = 'ผฝ. อนุมัติ';
            } else if (acc_tot_app >= 2000001 && acc_tot_app <= 5000000) {
                crstm_approve = 'CO. อนุมัติ';
            } else if (acc_tot_app >= 5000001 && acc_tot_app <= 7000000) {
                crstm_approve = 'กจก. อนุมัติ';
            } else if (acc_tot_app >= 7000001 && acc_tot_app <= 10000000) {
                crstm_approve = 'คณะกรรมการสินเชื่ออนุมัติ';
            } else {
                crstm_approve = 'คณะกรรมการบริหารอนุมัติ';
            }
        }
        if (cus_acc_group == "DREP") {
            if (acc_tot_app <= 500000) {
                crstm_approve = 'ผผ. อนุมัติ';
            } else if (acc_tot_app >= 500001 && acc_tot_app <= 3000000) {
                crstm_approve = 'ผส. อนุมัติ';
            } else if (acc_tot_app >= 3000001 && acc_tot_app <= 13000000) {
                crstm_approve = 'ผฝ. อนุมัติ';
            } else if (acc_tot_app >= 13000001 && acc_tot_app <= 25000000) {
                crstm_approve = 'CO. อนุมัติ';
            } else if (acc_tot_app >= 25000001 && acc_tot_app <= 50000000) {
                crstm_approve = 'กจก. อนุมัติ';
            } else {
                crstm_approve = 'คณะกรรมการบริหารอนุมัติ';
            }
        }

        $("#crstm_approve").val(crstm_approve);
        sum_amt_acc = formatCurrency(sum_amt_acc);
        $("#sum_acc_tot").val(sum_amt_acc);
    });

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
    $('#edit_beg_date').datetimepicker({
        format: 'DD/MM/YYYY'
    });
    $('#edit_end_date').datetimepicker({
        format: 'DD/MM/YYYY'
    });

    function formatCurrency(number) {
        number = parseFloat(number);
        return number.toFixed(0).replace(/./g, function(c, i, a) {
            return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
        });
    }


    $('input[type="radio"]').click(function() {
        if ($(this).attr('id') == 'cus_conf_yes') {
            $('.cus_display').show();
            $("#cusold_conf_yes").prop("required", true);

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
            $("#cusold_conf_yes").prop("required", false); //ปรับเพิ่มวงเงิน
            $("#cusold_conf_yes").prop("checked", false);

            $("#cusold1_conf_yes").prop("required", false); //ปรับลดวงเงิน
            $("#cusold1_conf_yes").prop("checked", false);

            $("#cusold2_conf_yes").prop("required", false); //ต่ออายุ
            $("#cusold2_conf_yes").prop("checked", false);

            $("#term_conf_yes").prop("required", false); //เงื่อนไขการชำระเงินเดิม
            $("#term_conf_yes").prop("checked", false);

            $("#chg_term_conf_yes").prop("required", false); //เปลี่ยนเงื่อนไขการชำระเงินใหม่จาก
            $("#chg_term_conf_yes").prop("checked", false);

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

            //// ซ่อนข้อมูลปรับลดวงเงิน
            $('.cusold_display').hide();
            $("#txt_cc").prop("required", false);
            $("#beg_date").prop("required", false);

            $("#cc_amt").prop("required", false);
        } else if ($(this).attr('id') == 'term_conf_yes') {
            $('.term_display').show();
            $("#terms_paymnt").prop("required", true);
            $("#terms_paymnt").val($("#cus_terms_paymnt").val());

            //// ซ่อนข้อมูลวงเงินลูกค้าเก่า
            // $('.cus_display').hide(); 	
            // $('.cusold_display').hide(); 
            // $("#cusold_conf_yes").prop("required", false);
            // $("#cusold1_conf_yes").prop("required", false);
            // $("#cusold2_conf_yes").prop("required", false);

            // $("#cusold_conf_yes").prop("checked", false);
            // $("#cusold1_conf_yes").prop("checked", false);
            // $("#cusold2_conf_yes").prop("checked", false);

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
            // $('.cus_display').hide(); 	
            // $('.cusold_display').hide(); 	
            // $("#cusold_conf_yes").prop("required", false);
            // $("#cusold1_conf_yes").prop("required", false);
            // $("#cusold2_conf_yes").prop("required", false);

            // $("#cusold_conf_yes").prop("checked", false);
            // $("#cusold1_conf_yes").prop("checked", false);
            // $("#cusold2_conf_yes").prop("checked", false);
            /////
            //// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
            $('.term_display').hide();
            $("#terms_paymnt").prop("required", false);

            $("#terms_paymnt").val(" ");
            $("#term_desc_add").val("");
            /////
        } else if ($(this).attr('id') == 'cusold_conf_yes') {

            $('.cusold_display').show();

            $("#div_frm_cc_edit .modal-body #cus_conf_yes").val(1);
            $("#div_frm_cc_edit .modal-body #cusold_conf_yes").val("C1");
            //$("#div_frm_cc_edit").modal("show");

            $("#txt_ccr").prop("required", true);
            $("#txt_ccr").val("เสนอขอปรับเพิ่มวงเงิน");

            $('.input_display').hide();
            $("#input_display").prop("required", true);

            $('.input_display1').show();
            $("#input_display1").prop("required", true);


            //// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
            $('.term_display').hide();
            $("#terms_paymnt").prop("required", false);

            $("#terms_paymnt").val(" ");
            $("#term_desc_add").val("");
        } else if ($(this).attr('id') == 'cusold1_conf_yes') {
            $('.cusold_display').show();
            $("#div_frm_cc_edit .modal-body #cus_conf_yes").val(1);
            $("#div_frm_cc_edit .modal-body #cusold_conf_yes").val("C2");
            $("#div_frm_cc_edit").modal("show");

            $("#txt_ccr").prop("required", true);
            $("#txt_ccr").val("เสนอขอปรับลดวงเงิน");

            //// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
            $('.term_display').hide();
            $("#terms_paymnt").prop("required", false);

            $("#terms_paymnt").val(" ");
            $("#term_desc_add").val("");
            /////
        } else if ($(this).attr('id') == 'cusold2_conf_yes') {
            $('.cusold_display').show();
            $('.input_display1').hide();
            $("#div_frm_cc_edit .modal-body #cus_conf_yes").val(1);
            $("#div_frm_cc_edit .modal-body #cusold_conf_yes").val("C3");
            //$("#div_frm_cc_edit").modal("show");

            $("#txt_ccr").prop("required", true);
            $("#txt_ccr").val("");
            $("#beg_date1").val("");
            $("#end_date1").val("");
            $("#cc_amt1").val("");
            //$("#txt_ccr").val("เสนอขอต่ออายุวงเงิน");
            var txt;
            var r = confirm("กรุณาเลือกรายการที่ต้องการต่ออายุวงเงิน ก่อนทำรายการต่อไป");
            if (r == true) {
                $('.input_display').show();
                $("#input_display").prop("required", true);
                $('.action_display').show();
                return true;

            } else {
                return false;
            }
            //// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
            $('.term_display').hide();
            $("#terms_paymnt").prop("required", false);

            $("#terms_paymnt").val(" ");
            $("#term_desc_add").val("");

            /////
        }
    });

    $(document).on("click", ".open-EditCCDialog", function() {

        var beg_date = $(this).data('begdte');
        var end_date = $(this).data('enddte');
        var cc_amt = $(this).data('cc_amt');
        var txt_ref = $(this).data('txt_ref');
        var txt_cc = $(this).data('txt_cc');
        var row_seq = $(this).data('row_seq');
        var phone_mask = $(this).data('phone_mask');
        var crstm_nbr = $(this).data('crstm_nbr');
        var cusold_conf_yes = "C3";

        if (txt_ref = "CC") { // เปลี่ยนจากวงเงินปัจจุบันเป็นต่ออายุวงเงิน
            txt_ref = "C3";
        }
        $("#div_frm_cc_edit .modal-body #crstm_nbr").val(crstm_nbr);
        $("#div_frm_cc_edit .modal-body #edit_beg_date").val(beg_date);
        $("#div_frm_cc_edit .modal-body #edit_end_date").val(end_date);
        $("#div_frm_cc_edit .modal-body #cc_amt").val(cc_amt);
        $("#div_frm_cc_edit .modal-body #txt_ref").val(txt_ref);
        $("#div_frm_cc_edit .modal-body #txt_cc").val(txt_cc);
        $("#div_frm_cc_edit .modal-body #row_seq").val(row_seq);
        $("#div_frm_cc_edit .modal-body #phone_mask").val(phone_mask);
        $("#div_frm_cc_edit .modal-body #cusold_conf_yes").val(cusold_conf_yes);

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

    /// เมื่อกรอกตัวเลขจำนวนเงิน ใน textbox ให้มันใส่คอมม่า
    function format(input) {
        var num = input.value.replace(/\,/g, '');
        if (!isNaN(num)) {
            if (num.indexOf('.') > -1) {
                num = num.split('.');
                num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1,').split('')
                    .reverse().join('').replace(/^[\,]/, '');
                if (num[1].length > 2) {
                    alert('You may only enter two decimals!');
                    num[1] = num[1].substring(0, num[1].length - 1);
                }
                input.value = num[0] + '.' + num[1];
            } else {
                input.value = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1,').split('')
                    .reverse().join('').replace(/^[\,]/, '')
            };
        } else {
            alert('You may enter only numbers in this field!');
            input.value = input.value.substring(0, input.value.length - 1);
        }
    }

    function load_risk_data() {
        var cr_cust_code = document.getElementById("cr_cust_code").value;
        document.frm_risk.cr_cust_code.value = cr_cust_code;
        $.ajax({
            url: "../serverside/upload_risk_list.php", // json datasource
            type: "post",
            data: $('#frm_risk').serialize(),

            success: function(data) {
                //console.log(data);
                $('#image_table').html(data);
            }
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
<!-- END: Body-->

</html>