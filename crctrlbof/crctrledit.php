<?php 
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
include("chkauthcr.php");

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

$crstm_step_code = "0";
clearstatcache();
$chk_block = "none";

$crstm_nbr = decrypt(html_escape($_REQUEST['crnumber']), $key);

$params = array($crstm_nbr);
$query_detail = "SELECT crstm_mstr.crstm_nbr, crstm_mstr.crstm_date, crstm_mstr.crstm_user, crstm_mstr.crstm_tel, crstm_mstr.crstm_cus_nbr, crstm_mstr.crstm_chk_rdo1, crstm_mstr.crstm_chk_rdo2, ".
"crstm_mstr.crstm_chk_term, crstm_mstr.crstm_term_add, crstm_mstr.crstm_ch_term, crstm_mstr.crstm_approve, crstm_mstr.crstm_sd_reson, crstm_mstr.crstm_sd_per_mm, ".
"crstm_mstr.crstm_pj_name, crstm_mstr.crstm_pj_prv, crstm_mstr.crstm_pj_term, crstm_mstr.crstm_pj_amt, crstm_mstr.crstm_pj_dura, crstm_mstr.crstm_pj_beg, crstm_mstr.crstm_pj1_name, ".
"crstm_mstr.crstm_pj1_prv, crstm_mstr.crstm_pj1_term, crstm_mstr.crstm_pj1_amt, crstm_mstr.crstm_pj1_dura, crstm_mstr.crstm_pj1_beg, crstm_mstr.crstm_whocanread, ".
"crstm_mstr.crstm_curprocessor, cus_mstr.cus_name1, cus_mstr.cus_street, cus_mstr.cus_street2, cus_mstr.cus_street3, cus_mstr.cus_street4, cus_mstr.cus_street5, cus_mstr.cus_district, ".
"cus_mstr.cus_city, cus_mstr.cus_zipcode, cus_mstr.cus_tax_nbr3, cus_mstr.cus_terms_paymnt, cus_mstr.cus_acc_group, emp_mstr.emp_th_firstname, emp_mstr.emp_th_lastname, ".
"emp_mstr.emp_th_pos_name, emp_mstr.emp_manager_name, emp_mstr.emp_email_bus, emp_mstr.emp_tel_bus, term_mstr.term_desc, cus_mstr.cus_country, country_mstr.country_desc, ".
"crstm_mstr.crstm_pj_img, crstm_mstr.crstm_pj1_img, crstm_mstr.crstm_step_code, crstm_mstr.crstm_cc_date_beg, crstm_mstr.crstm_cc_date_end, crstm_mstr.crstm_cc_amt, ".
"crstm_mstr.crstm_reson_img, crstm_mstr.crstm_rem_rearward,crstm_reviewer,crstm_reviewer2,crstm_noreviewer, crstm_mstr.crstm_scgc,crstm_email_app1,crstm_email_app2,crstm_email_app3 ".
"FROM crstm_mstr INNER JOIN ".
"cus_mstr ON crstm_mstr.crstm_cus_nbr = cus_mstr.cus_nbr INNER JOIN ".
"emp_mstr ON crstm_mstr.crstm_user = emp_mstr.emp_scg_emp_id INNER JOIN ".
"term_mstr ON cus_mstr.cus_terms_paymnt = term_mstr.term_code INNER JOIN ".
"country_mstr ON cus_mstr.cus_country = country_mstr.country_code where crstm_mstr.crstm_nbr = ?";

$result_detail = sqlsrv_query($conn, $query_detail,$params);
$rec_cus = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
if ($rec_cus) {
	$user_fullname = trim($rec_cus["emp_th_firstname"]) . " " . trim($rec_cus["emp_th_lastname"]);
	$user_th_pos_name = html_clear($rec_cus["emp_th_pos_name"]);
	$user_manager_name = html_clear($rec_cus["emp_manager_name"]);
	$user_email = html_clear($rec_cus["emp_email_bus"]);
	$phone_mask = html_clear($rec_cus["crstm_tel"]);
	
	$cr_cust_code = html_clear($rec_cus['crstm_cus_nbr']);
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
	$cus_terms_paymnt = html_clear($rec_cus['cus_terms_paymnt']);
	$term_desc = html_clear($rec_cus['term_desc']);
	$cus_terms_paymnt = $cus_terms_paymnt ." | ". $term_desc;
	
	$cus_acc_group = html_clear($rec_cus['cus_acc_group']);
	
	/// radio 
	$cus_conf_yes = html_clear($rec_cus['crstm_chk_rdo1']);
	$cusold_conf_yes = html_clear($rec_cus['crstm_chk_rdo2']);
	$crstm_chk_term = html_clear($rec_cus['crstm_chk_term']);
	
	$term_desc_add = html_clear($rec_cus['crstm_term_add']);
	$term_desc = html_clear($rec_cus['crstm_ch_term']);
	
	$crstm_approve = html_clear($rec_cus['crstm_approve']);
	if($crstm_approve == "คณะกรรมการสินเชื่ออนุมัติ" || $crstm_approve =="คณะกรรมการบริหารอนุมัติ"){$chk_block = 'display';}
	
	$crstm_sd_reson = html_clear($rec_cus['crstm_sd_reson']);
	$crstm_sd_per_mm = number_format($rec_cus['crstm_sd_per_mm']);
	$crstm_reson_img = html_clear($rec_cus['crstm_reson_img']);
	
	$crstm_pj_name = html_clear($rec_cus['crstm_pj_name']);
	$crstm_pj_amt = number_format($rec_cus['crstm_pj_amt']);
	$crstm_pj_prv = html_clear($rec_cus['crstm_pj_prv']);
	$crstm_pj_term = html_clear($rec_cus['crstm_pj_term']);
	$crstm_pj_dura = html_clear($rec_cus['crstm_pj_dura']);
	$crstm_pj_beg = dmytx($rec_cus['crstm_pj_beg']);
	$crstm_pj_img = html_clear($rec_cus['crstm_pj_img']);
	
	$crstm_pj1_name = html_clear($rec_cus['crstm_pj1_name']);
	$crstm_pj1_amt = number_format($rec_cus['crstm_pj1_amt']);
	$crstm_pj1_prv = html_clear($rec_cus['crstm_pj1_prv']);
	$crstm_pj1_term = html_clear($rec_cus['crstm_pj1_term']);
	$crstm_pj1_dura = html_clear($rec_cus['crstm_pj1_dura']);
	$crstm_pj1_beg = dmytx($rec_cus['crstm_pj1_beg']);
	$crstm_pj1_img = html_clear($rec_cus['crstm_pj1_img']);
	$crstm_step_code = html_clear($rec_cus['crstm_step_code']);
	$crstm_curprocessor = html_clear($rec_cus['crstm_curprocessor']);
	$crstm_rem_rearward = html_clear($rec_cus['crstm_rem_rearward']);
	
	$crstm_reviewer = strtolower(html_clear($rec_cus['crstm_reviewer']));
	$crstm_reviewer2 = strtolower(html_clear($rec_cus['crstm_reviewer2']));

	$crstm_noreviewer = html_clear($rec_cus['crstm_noreviewer']);
	$crstm_scgc = html_clear($rec_cus['crstm_scgc']);
	$crstm_email_app1 = html_clear($rec_cus['crstm_email_app1']);
	$crstm_email_app2 = html_clear($rec_cus['crstm_email_app2']);
	$crstm_email_app3 = html_clear($rec_cus['crstm_email_app3']);
	
	$crstm_cc_date_beg = dmytx($rec_cus['crstm_cc_date_beg']);
	$crstm_cc_date_end = dmytx($rec_cus['crstm_cc_date_end']);
	$crstm_cc_amt = number_format($rec_cus['crstm_cc_amt']);

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

	if ($crstm_noreviewer == true) {
		$reviewer1_read = "readOnly";
		$reviewer_code = "10";
	} else { 
		$reviewer_code = "110";
	};
	
	if ($crstm_scgc == true) {
		$reviewercanedit = "readOnly";
		$pointer_vie2 = "none";
		$dis_rev2 = "block";
		$chk_rev2 = "none";
		$_flag = "1";
		} else {
			$reviewercanedit = "readOnly";
			$pointer_vie2 = "none";
			$dis_rev2 = "none";
			$chk_rev2 = "block";
			$_flag = "2";			
		}
}

	if($crstm_reviewer!=""){
		$reviewer_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_reviewer,$conn);	
	}
	if($crstm_reviewer2!=""){
		$reviewer_name2 = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_reviewer2,$conn);	
	}
	if($crstm_email_app1!=""){
		$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
	}	
	if($crstm_email_app2!=""){
		$app2_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app2,$conn);	
	}
	if($crstm_email_app3!=""){
		$app3_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app3,$conn);	
	}

	$params = array($crstm_nbr);
	$query_detail = "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr where tbl1_nbr = ?";
	$result = sqlsrv_query($conn, $query_detail,$params);
	$rec_result = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if ($rec_result) {
		$stamp_date = $rec_result['tbl1_stamp_date'];
	}

	$params = array($crstm_nbr);	
	$query_detail = "SELECT tbl2_id, tbl2_nbr, tbl2_cus_nbr, tbl2_amt_loc_curr, tbl2_doc_date, tbl2_create_by, tbl2_create_date, tbl2_stamp_date FROM tbl2_mstr where tbl2_nbr = ?";
	$result = sqlsrv_query($conn, $query_detail,$params);
	$rec_result = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	if ($rec_result) {
		$stamp1_date = $rec_result['tbl2_stamp_date'];
	}
		
$uploadpath = "../_fileuploads/sale/project/";	
$info_reson = pathinfo( $crstm_reson_img , PATHINFO_EXTENSION ) ;	
switch ($info_reson) {
	case "xlsx":
	$ImgReson = "$uploadpath"."$crstm_reson_img";
	$ImgReson_icon = "$uploadpath"."excel.png";
	break;
	case "xls":
	$ImgReson = "$uploadpath"."$crstm_reson_img";
	$ImgReson_icon = "$uploadpath"."excel.png";
	break;
	case "doc":
	$ImgReson = "$uploadpath"."$crstm_reson_img";
	$ImgReson_icon = "$uploadpath"."word.png";
	break;	
	case "docx":
	$ImgReson = "$uploadpath"."$crstm_reson_img";
	$ImgReson_icon = "$uploadpath"."word.png";
	break;		
	case "pdf":
	$ImgReson = "$uploadpath"."$crstm_reson_img";
	$ImgReson_icon = "$uploadpath"."pdf.png";
	break;
	case "pptx":
	$ImgReson = "$uploadpath"."$crstm_reson_img";
	$ImgReson_icon = "$uploadpath"."ppt.png";
	break;
	case "ppt":
	$ImgReson = "$uploadpath"."$crstm_reson_img";
	$ImgReson_icon = "$uploadpath"."ppt.png";
	break;	
	// case "jpg":
	// $ImgReson = "$uploadpath"."$crstm_reson_img";
	// $ImgReson_icon = $ImgReson;
	// break;
	default:
	if($crstm_reson_img=="") {
		$ImgReson = "$uploadpath"."nopicture.png";
		$ImgReson_icon = $ImgReson;
		}else {
		$ImgReson = "$uploadpath"."$crstm_reson_img";
		$ImgReson_icon = $ImgReson;
	}
}	

$info_pj = pathinfo( $crstm_pj_img , PATHINFO_EXTENSION ) ;
switch ($info_pj) {
	case "xlsx":
	$ImgPrj = "$uploadpath"."$crstm_pj_img";
	$ImgPrj_icon = "$uploadpath"."excel.png";
	break;
	case "xls":
	$ImgPrj = "$uploadpath"."$crstm_pj_img";
	$ImgPrj_icon = "$uploadpath"."excel.png";
	break;
	case "doc":
	$ImgPrj = "$uploadpath"."$crstm_pj_img";
	$ImgPrj_icon = "$uploadpath"."word.png";
	break;	
	case "docx":
	$ImgPrj = "$uploadpath"."$crstm_pj_img";
	$ImgPrj_icon = "$uploadpath"."word.png";
	break;		
	case "pdf":
	$ImgPrj = "$uploadpath"."$crstm_pj_img";
	$ImgPrj_icon = "$uploadpath"."pdf.png";
	break;
	case "pptx":
	$ImgPrj = "$uploadpath"."$crstm_pj_img";
	$ImgPrj_icon = "$uploadpath"."ppt.png";
	break;
	case "ppt":
	$ImgPrj = "$uploadpath"."$crstm_pj_img";
	$ImgPrj_icon = "$uploadpath"."ppt.png";
	break;	
	// case "jpg":
	// $ImgPrj = "$uploadpath"."$crstm_pj_img";
	// $ImgPrj_icon = $$ImgPrj;
	// break;
	default:
	if($crstm_pj_img=="") {
		$ImgPrj = "$uploadpath"."nopicture.png";
		$ImgPrj_icon = $ImgPrj;
		}else {
		$ImgPrj = "$uploadpath"."$crstm_pj_img";
		$ImgPrj_icon = $ImgPrj;
	}
}

$info_pj1 = pathinfo( $crstm_pj1_img , PATHINFO_EXTENSION ) ;
switch ($info_pj1) {
	case "xlsx":
	$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
	$ImgPrj1_icon = "$uploadpath"."excel.png";
	break;
	case "xls":
	$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
	$ImgPrj1_icon = "$uploadpath"."excel.png";
	break;
	case "doc":
	$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
	$ImgPrj1_icon = "$uploadpath"."word.png";
	break;	
	case "docx":
	$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
	$ImgPrj1_icon = "$uploadpath"."word.png";
	break;		
	case "pdf":
	$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
	$ImgPrj1_icon = "$uploadpath"."pdf.png";
	break;
	case "pptx":
	$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
	$ImgPrj1_icon = "$uploadpath"."ppt.png";
	break;
	case "ppt":
	$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
	$ImgPrj1_icon = "$uploadpath"."ppt.png";
	break;	
	default:
	if($crstm_pj1_img=="") {
		$ImgPrj1 = "$uploadpath"."nopicture.png";
		$ImgPrj1_icon = $ImgPrj1;
		}else {
		$ImgPrj1 = "$uploadpath"."$crstm_pj1_img";
		$ImgPrj1_icon = $ImgPrj1;
	}
}
$iscurrentprocessor = false;
$iscan_editing = false;
$iscan_print = false;
if (inlist($crstm_curprocessor,$user_login)) {
	//ดูว่า User ที่ Login เข้าระบบมีในรายชื่อ CurrentProcessor หรือไม่ถ้ามีก็มีสิทธิ์ในการ Edit เอกสาร
	//เช่นคนสร้างเอกสาร,หรือผู้อนุมัติเอกสาร
	$iscurrentprocessor = true;
}
if ($iscurrentprocessor && inlist('0,01,112,222',$crstm_step_code)) {
	$iscan_editing = true;
}
if (inlist('40,41',$crstm_step_code)) {
	$iscan_print = true;
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
		<!--<link rel="stylesheet" href="_libs/css/font-awesome/css/font-awesome.min.css">-->
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/vendors.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/toastr.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/extensions/toastr.min.css">
	</head>
	<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover" data-menu="vertical-menu" data-col="2-columns">
		<div id="result"></div>
		<?php include("../crctrlmain/menu_header.php"); ?>
		<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
		<?php include("../crctrlmain/modal.php"); ?>
		<?php include("../crctrlmain/help_modal.php"); ?>
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
									<li class="breadcrumb-item"><font class="text text-info "> ใบขออนุมัติวงเงินสินเชื่อลูกค้าเก่า  <?php echo $crstm_nbr; ?></font></li>
								</ol>
							</div>
						</div>
					</div>               
					<?php if (substr($crstm_step_code,0,1) != 6) { ?>
						<div class="content-header-right col-md-6 col-12">
							<div class="btn-group float-md-right">
								<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-settings mr-1"></i>Action</button>
								<div class="dropdown-menu arrow"><a class="dropdown-item blue" href="#div_frm_reject" data-toggle="modal"><i class="fa fa-times-circle mr-1"></i>ยกเลิกใบขออนุมัติวงเงินสินเชื่อ</a>
									<div class="dropdown-divider"></div><a class="dropdown-item blue" href="crctrlall.php"><i class="fa fa-home mr-1"></i> Home</a>
								</div>
							</div>
						</div>
					<? } ?>
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
												<!--<li><a href="../crctrlbof/crctrladd.php"><i class="fa fa-plus"></i> เพิ่มใบขออนุมัติวงเงินสินเชื่อ</a></li>-->
												<li><a title="Click to go back,hold to see history" data-action="reload"><i class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
												<li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a></li>
											</ul>
										</div>
									</div>
									<div class="card-content collapse show ">                                    		
										<div class="card-body" style="margin-top:-20px;">
											<div id="div_result"></div>
											<FORM id="frm_crctrl_edit" name="frm_crctrl_edit" autocomplete=OFF method="POST" enctype="multipart/form-data" >
												<input type=hidden name="action" value="crctrledit">
												<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
												<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
												<input type="hidden" name="cr_cust_code" value="<?php echo encrypt($cr_cust_code, $key); ?>">
												<input type="hidden" name="crstm_nbr" value="<?php echo($crstm_nbr) ?>">
												<input type="hidden" name="phone_mask" value="<?php echo($phone_mask) ?>">
												<input type="hidden" id="cus_acc_group" name="cus_acc_group" value="<?php echo($cus_acc_group) ?>">
												
												<div class="form-body">		
													<h4 class="form-section text-info"><i class="fa fa-user"></i> ผู้ขอเสนออนุมัติ</h4>
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">ชื่อ-สกุล :</label>
																<input type="text" id="user_fullname" name ="user_fullname" value="<?php echo $user_fullname ?>" class="form-control input-sm font-small-3" disabled>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">หน่วยงาน : </label>
																<input type="text" id="user_th_pos_name" name ="user_th_pos_name" value="<?php echo $user_th_pos_name ?>" class="form-control input-sm font-small-3" disabled>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">ผู้บังคับบัญชา :</label>
																<input type="text" id="user_manager_name" name="user_manager_name" value="<?php echo $user_manager_name ?>" class="form-control input-sm font-small-3" disabled>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">E-mail:</label>
																<input type="text" id="user_email" name="user_email" value="<?php echo $user_email ?>" class="form-control input-sm font-small-3" disabled>
															</div>
														</div>
														<div class="col-md-4">
															<label class="font-weight-bold">เบอร์โทรศัพท์:</label>
															<class="text-muted font-weight-bold">(999) 999-9999 <font class="text text-danger font-weight-bold"> ***</font>
																<div class="form-group">
																	<input type="text" class="form-control phone-inputmask form-control input-sm font-small-3 border-warning" id="phone_mask" name="phone_mask" value="<?php echo $phone_mask ?>" placeholder="ระบุหมายเลขโทรศัพท์" />
																</div>
															</div>
															
														</div>	
													</div>
													<!-- End  Sales Register   -->
													<!-- Start Customber -->
													<h4 class="form-section text-info"><i class="fa fa-address-card-o"></i> ข้อมูลลูกค้า</h4>
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">รหัสลูกค้า :<font class="text text-danger font-weight-bold"> ***</font></label>
																<? if($cr_cust_code !="") {?>	
																	<input type="text" id="cr_cust_code" name ="cr_cust_code" value="<?php echo $cr_cust_code ?>" class="form-control input-sm font-small-3 border-warning" placeholder="พิมพ์ชื่อ หรือ รหัสลูกค้า" required disabled>
																<?php } else { ?>
																	<input type="text" id="cr_cust_code" name ="cr_cust_code" value="<?php echo $cr_cust_code ?>" class="form-control input-sm font-small-3 border-warning" placeholder="พิมพ์ชื่อ หรือ รหัสลูกค้า" required>
																<?php } ?>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">ชื่อลูกค้า : </label>
																<input type="text" id="crstm_cus_name1" name ="crstm_cus_name1" value="<?php echo $crstm_cus_name ?>" class="form-control input-sm font-small-3" disabled>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">เลขประจำตัวผู้เสียภาษี :</label>
																<input type="text" id="cus_tax_nbr3" name="cus_tax_nbr3" value="<?php echo $cus_tax_nbr3 ?>" class="form-control input-sm font-small-3" disabled>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-4">
															<fieldset class="form-group">
																<label for="placeTextarea" class="font-weight-bold">ที่อยู่ :</label>
																<textarea  name="cus_street" id="cus_street" class="form-control input-sm font-small-3"  id="placeTextarea" rows="3" placeholder="ที่อยู่" style="line-height:1.5rem;" disabled> <?php echo $cus_street; ?></textarea>
															</fieldset>	
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">จังหวัด :</label>
																<input type="text" id="cus_city" name="cus_city" value="<?php echo $cus_city ?>" class="form-control input-sm font-small-3" disabled>
																<div class="form-group">
																	<label class="font-weight-bold">ประเทศ :</label>
																	<input type="text" id="cus_country" name="cus_country" value="<?php echo $cus_country ?>" class="form-control input-sm font-small-3" disabled>
																</div>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<label class="font-weight-bold">เงื่อนไขการชำระเงิน :</label>
																<input type="text" id="cus_terms_paymnt" name="cus_terms_paymnt" value="<?php echo $cus_terms_paymnt ?>" class="form-control input-sm font-small-3" disabled><br>
																
															</div>
														</div>
													</div>	
													<!-- End  Customber   -->
													
													<div class="row match-height detailcrc_display" > 
														<!-- Start First Column -->
														<div class="col-md-6">
															<!--<div class="card">-->
															<!--<div class="card-body collapse show">-->
															<!--<div class="card-block">-->
															<div class="table-responsive" style="padding:0px 15px 50px 20px;">
																<p style="font-size:14px;">    สถานะวงเงินและหนี้ ณ วันที่ :  <?echo $stamp_date; ?></p>
																<input type="hidden" name="stamp_date" id="stamp_date" value="<?php echo($stamp_date) ?>">
																<!-- Start Datatables -->
																<!--class="table table-sm table-hover table-bordered compact nowrap-->
																<table id="" class="table table-sm table-bordered compact nowrap " style="width:100%;" > <!--dt-responsive nowrap-->
																	<thead>
																		<tr class="bg-success text-white font-weight-bold">								
																			<th>สถานะวงเงินและหนี้ </th>
																			<th class="text-center" colspan='2'>จำนวนเงิน (บาท) </th>
																		</tr>
																	</thead>
																	<tbody>
																		<?
																			$params = array($crstm_nbr);
																			$sql_cr= "SELECT tbl1_nbr,sum(tbl1_amt_loc_curr) as amt,  tbl1_txt_ref,tbl1_acc_name ".
																			"FROM tbl1_mstr WHERE (tbl1_nbr = ?) and tbl1_group='1' group by tbl1_nbr,tbl1_txt_ref,tbl1_acc_name order by tbl1_txt_ref";
																			$result = sqlsrv_query($conn, $sql_cr,$params);
																			
																			$tot_acc = 0;
																			$sum_acc = 0;
																			$tot_cc = 0;
																			while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																			{
																				$tot_acc = number_format($row_cr['amt']);
																				$sum_acc = $row_cr['amt'];
																				$chk_name = $row_cr['tbl1_txt_ref'];
																				$acc_name = $row_cr['tbl1_acc_name'];
																				
																				//echo "<tr><td >".$acc_name."</td>";
																				if ($chk_name <> 'CI') {
																					echo "<tr><td align='left' >".$acc_name."</td>";
																					echo "<td colspan='1'></td>";
																					echo "<td align='right' >".$tot_acc."</td>";	
																					$tot_cc = $tot_cc + $sum_acc ;
																					}else { 
																					echo "<tr><td align='center'>".$acc_name."</td>";
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
																			$params = array($crstm_nbr);
																			$sql_ar= "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, ".
																			"tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr WHERE (tbl1_nbr = ?) and tbl1_group='2'";
																			
																			$result = sqlsrv_query($conn, $sql_ar, $params, array("Scrollable" => 'keyset'));
																			$row_counts = sqlsrv_num_rows($result);
																			
																			$tot_ar = 0;
																			$sum_ar = 0;
																			
																			while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																			{
																				
																				//$tot_ar = number_format($row_ar['ar_amt']);
																				$tot_ar = $row_ar['tbl1_amt_loc_curr'];
																				if ($tot_ar < 0) {
																					$tot_ar = ($tot_ar * -1);
																					$tot_ar = "(".(number_format($tot_ar)).")";
																					}else {
																					$tot_ar = number_format($row_ar['tbl1_amt_loc_curr']);
																				}
																				$sum_ar = round($row_ar['tbl1_amt_loc_curr']);
																				$ar_txt = 'หนี้ทั้งหมด ' ;
																				echo "<tr><td>".$ar_txt."</td>";
																				echo "<td colspan='1'></td>";
																				echo "<td align='right'>".$tot_ar."</td>";	
																				echo "</tr>";
																			}
																			if ($tot_ar < 0) {
																				$tot_ar = "(".(number_format($tot_ar)).")";
																			}	
																			if ($row_counts==0 && $tot_ar==0) {     
																				$tot_ar = ($tot_ar * -1);
																				$ar_txt = 'หนี้ทั้งหมด  ' ;
																				echo "<tr><td>".$ar_txt."</td>";
																				echo "<td colspan='1'></td>";
																				echo "<td align='right'>".$tot_ar."</td>";	
																				echo "</tr>";
																			}
																		?>
																		
																		<?
																			$params = array($crstm_nbr);
																			$sql_ar ="SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, ".
																			"tbl1_acc_name, tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr ".
																			"WHERE (tbl1_nbr = ?) AND (tbl1_group = '3')";
																			$result = sqlsrv_query($conn, $sql_ar,$params);
																			
																			$tot_cur = 0;
																			$sum_cur = 0;
																			while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																			{
																				//$tot_cur = number_format($row_ar['tbl1_amt_loc_curr']);
																				$tot_cur = $row_ar['tbl1_amt_loc_curr'];
																				if ($tot_cur < 0) {
																					$tot_cur = ($tot_cur * -1);
																					$tot_cur = "(".(number_format($tot_cur)).")";
																					}else {
																					$tot_cur = number_format($row_ar['tbl1_amt_loc_curr']);
																				}
																				
																				$sum_cur = $row_ar['tbl1_amt_loc_curr'];
																				$cur_txt = $row_ar['tbl1_txt_ref'];
																				
																				echo "<tr><td align='center'>".$cur_txt."</td>";
																				echo "<td align='right'>".$tot_cur."</td>";	
																				echo "<td align=center'></td>";	
																				echo "</tr>";
																			}
																		?>
																		
																		<?
																			//$params = array($cr_cust_code);
																			//$sql_ord ="SELECT  ord_cr_acc, SUM(ord_mstr.ord_sales_val) AS sales_val FROM ord_mstr  WHERE (ord_cr_acc = ? ) group by ord_cr_acc";
																			$params = array($crstm_nbr);
																			$sql_ord ="SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, ".
																			"tbl1_acc_name, tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr ".
																			"WHERE (tbl1_nbr = ?) AND (tbl1_group = '4')";
																			$result = sqlsrv_query($conn, $sql_ord,$params);
																			
																			$tot_ord = 0;
																			$sum_ord = 0;
																			$grand_ord = 0;
																			while($row_ord = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																			{
																				
																				$tot_ord = $row_ord['tbl1_amt_loc_curr'];
																				if ($tot_ord < 0) {
																					$tot_ord = ($tot_ord * -1);
																					$tot_ord = "(".(number_format($tot_ord)).")";
																					}else {
																					$tot_ord = number_format($row_ord['tbl1_amt_loc_curr']);
																				}
																				
																				$sum_ord = round($row_ord['tbl1_amt_loc_curr']);
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
																			//if ($sumgr_ord > 0 && $grtot_acc > 0) {
																			if ($grtot_acc > 0) {
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
															<!--</div>
															</div>-->
															<!--</div>-->
														</div>
														<!-- End First Column -->	
														
														<div class="col-md-6">
															<div class="table-responsive" style="padding:0px 15px 50px 20px;">
																<p style="font-size:14px;">ประวัติการซื้อสินค้า 12 เดือนที่ผ่านมา ณ วันที่  <?echo $stamp1_date; ?></p>
																<input type="hidden" name="stamp1_date" id="stamp1_date" value="<?php echo($stamp1_date) ?>">
																<!-- Start Datatables -->
																<table id="" class="table table-sm table-hover table-bordered compact nowrap" style="width:100%;" > 
																	<thead class="text-center">
																		<tr class="bg-warning text-white font-weight-bold">								
																			<th>ปี - เดือน</th>
																			<th class="text-center">ยอด Billing (บาท)</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?
																			$params = array($crstm_nbr);
																			$sql_bll= "SELECT TOP (12) tbl2_id, tbl2_nbr, tbl2_cus_nbr, tbl2_amt_loc_curr, tbl2_doc_date, tbl2_create_by, tbl2_create_date ".
																			"FROM tbl2_mstr WHERE (tbl2_nbr = ?) ORDER BY tbl2_doc_date DESC ";
																			$result_bll = sqlsrv_query($conn, $sql_bll,$params);
																			
																			$bll_tot = 0;
																			$no = 0 ;
																			$tot_avr=0;
																			$a_max = array();
																			while($row_bll = sqlsrv_fetch_array($result_bll, SQLSRV_FETCH_ASSOC))
																			{
																				$tot_amt = $row_bll['tbl2_amt_loc_curr'];
																				$tot_ord = number_format($row_bll['tbl2_amt_loc_curr']);
																				$bll_ym = $row_bll['tbl2_doc_date'];
																				$bll_doc_ym1 = substr($bll_ym,0,4);
																				$bll_doc_ym2 = substr($bll_ym,5,2);
																				$bll_yofm = $bll_doc_ym1.'-'.$bll_doc_ym2;
																				$a_max[$bll_yofm] = $tot_amt;	
																				
																				if($no>=1) {
																					$tot_avr += $tot_amt;
																				}
																				
																				echo "<td align='center'>".$bll_doc_ym1.'-'.$bll_doc_ym2."</td>";
																				echo "<td align='right'>".$tot_ord."</td>";									
																				echo "</tr>";
																				$bll_tot = $bll_tot + $tot_amt ;
																				$no = $no + 1;
																			}
																			if ($tot_avr != 0 ) {
																				$bll_avr = $tot_avr / 11 ;
																			}
																			if ($bll_tot>0) {
																				$max_amt = max($a_max); // หาค่า max ใน array
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
													
													<div class="detailar_display" >
														<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 1. สำหรับหน่วยงานขาย (เสนอขออนุมัติวงเงินสินเชื่อ)</h4>
														<div class="row">
															<!--<div class="col-md-3">
																<input type="radio"  id="cus_conf_no" name="cus_conf" value="0" <?php if($cus_conf_yes=='0'){ echo "checked"; }?>>
																<label class="font-weight-bold" for="cus_conf_no"> วงเงินลูกค้าใหม่</label>
															</div>-->	
															<div class="col-md-3">
																<input type="radio" id="cus_conf_yes" name="cus_conf" value="1" <?php if($cus_conf_yes=='1'){ echo "checked"; }?>>
																<label class=" font-weight-bold" for="cus_conf_yes"> วงเงินลูกค้าเก่า</label>
															</div>
														</div>
														
														<? if($cus_conf_yes =="1") {?>
															<div class="cus_display" >
																<?php } 
																else { ?>
																<div class="cus_display" style="display:none;">
																<?php } ?>	
																<div class="row">
																	<div class="col-md-3">
																		<input type="radio"  id="cusold_conf_yes" name="chk_rdo" value="C1" <?php if($cusold_conf_yes=='C1') { echo "checked"; }?>>
																		<label class="font-weight-bold" for="cusold_conf_yes"> ปรับเพิ่มวงเงิน</label>
																	</div>	
																	<!--<div class="col-md-3">
																		<input type="radio"  id="cusold1_conf_yes" name="chk_rdo" value="C2" <?php if($cusold_conf_yes=='C2') { echo "checked"; }?>>
																		<label class=" font-weight-bold" for="cusold_conf_yes"> ปรับลดวงเงิน</label>
																	</div>-->
																	<div class="col-md-3">
																		<input type="radio"  id="cusold2_conf_yes" name="chk_rdo" value="C3" <?php if($cusold_conf_yes=='C3') { echo "checked"; }?>>
																		<label class=" font-weight-bold" for="cusold_conf_yes"> ต่ออายุวงเงิน</label>
																	</div>
																</div>
															</div>
															
															<div class="row">
																<div class="col-md-3">
																	<input type="radio"  id="term_conf_yes" name="rdo_conf1" value="old" <?php if($crstm_chk_term=='old'){ echo "checked"; }?>>
																	<label class="font-weight-bold" for="cus_conf_yes">เงื่อนไขการชำระเงินเดิม</label>
																</div>
																<div class="col-md-3">
																	<input type="radio"  id="chg_term_conf_yes" name="rdo_conf1" value="change" <?php if($crstm_chk_term=='change'){ echo "checked"; }?>>
																	<label class="font-weight-bold" for="cus_conf_yes">เปลี่ยนเงื่อนไขการชำระเงินใหม่จาก</label>
																</div>
															</div>
															
															<? if($crstm_chk_term =="old") {?>
																<div class="term_display">
																	<?php } 
																	else { ?>
																	<div class="term_display" style="display:none;">
																	<?php } ?>	
																	<div class="row">
																		<div class="col-md-3">
																			<fieldset>
																				<label for="check_same" class="font-weight-bold">เงื่อนไขการชำระเงินเดิม:</label>
																			</fieldset>
																		</div>
																		<div class="col-md-3">
																			<input type="text" id="terms_paymnt" name="terms_paymnt" class="form-control input-sm font-small-3" value="<?php echo $cus_terms_paymnt ?>" readonly>
																		</div>
																		<div class="col-md-3">
																			<fieldset>
																				<label class="font-weight-bold">โปรดระบุเพิ่ม: (ถ้ามี)</label>
																			</fieldset>
																		</div>
																		<div class="col-md-3">
																			<div class="form-group">
																				<!--<label class="font-weight-bold">เปลี่ยนจาก</label>-->
																				<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="term_desc_add" name="term_desc_add" >
																					<option value="" selected>--- เลือกเงื่อนไขการชำระเงินเพิ่ม ---</option>
																					<?php
																						$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
																						$result_doc = sqlsrv_query($conn, $sql_doc);
																						while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																						?>
																						<option value="<?php echo $r_doc['term_code']; ?>" 
																						<?php if ($term_desc_add == $r_doc['term_code']) {
																							echo "selected";
																						} ?>>
																						<?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
																					<?php } ?>
																				</select>
																			</div>
																		</div>
																		
																	</div>
																</div>
																
																<? if($crstm_chk_term =="change") {?>
																	<div class="chg_term_display">
																		<?php } 
																		else { ?>
																		<div class="chg_term_display" style="display:none;">
																		<?php } ?>	
																		<div class="row">
																			<div class="col-md-3">
																				<fieldset>
																					<label for="check_same" class="font-weight-bold">ขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก:</label>
																				</fieldset>
																			</div>
																			<div class="col-md-3">
																				<input type="text" id="terms_paymnt1" name="terms_paymnt1" class="form-control input-sm font-small-3" value="<?php echo $cus_terms_paymnt ?>" readonly>
																			</div>
																			<div class="col-md-2">
																				<fieldset>
																					<label class="font-weight-bold">เปลี่ยนเงื่อนไข:</label>
																				</fieldset>
																			</div>
																			<div class="col-md-4">
																				<div class="form-group">
																					<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="term_desc" name="term_desc">
																						<option value="" selected>--- เลือกเงื่อนไขการชำระเงินใหม่ ---</option>
																						<?php
																							$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
																							$result_doc = sqlsrv_query($conn, $sql_doc);
																							while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																							?>
																							<option value="<?php echo $r_doc['term_code']; ?>" 
																							<?php if ($term_desc == $r_doc['term_code']) {
																								echo "selected";
																							} ?>>
																							<?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
																						<?php } ?>
																					</select>
																					
																				</div>
																			</div>
																			
																		</div>
																	</div>
																	
																	<div class="form-group row">
																		<label class="col-md-3 label-control font-weight-bold" for="userinput1">ประมาณการขายเฉลี่ยต่อเดือน: <font class="text text-danger font-weight-bold"> ***</font></label>
																		<div class="col-md-3">
																			<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_sd_per_mm" class="form-control input-sm border-warning " name="crstm_sd_per_mm" value="<?php echo $crstm_sd_per_mm ?>" style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)" ></a>
																		</div>
																		<label class="col-md-2 label-control font-weight-bold" for="userinput1">บาท</label>
																	</div>
																	<!-- Start Table Clean Credit -->
																	<div class="col-sm-9">
																		<!--<div class="card">-->
																		<!--<div class="card-block">-->
																		<div class="table-responsive">
																			<!-- Start Datatables -->
																			<table id="tb_ord" class="table table-sm table-hover table-bordered" style="width:100%;" >
																				<thead class="text-center" style="background-color:#f1f1f1;">
																					<tr class="bg-info text-white font-weight-bold">
																						<th rowspan="2" class="align-middle">ขออนุมัติปรับวงเงินสินเชื่อ<br></br> (Clean Credit)</th>
																						<th colspan="2" class="align-middle">อายุวงเงิน</th>
																						<th rowspan="2" class="align-middle">วงเงิน (บาท)</th>
																						<th rowspan="2" class="align-middle" width="60">Action</th>
																					</tr>
																					<tr class="bg-info text-white font-weight-bold" style="line-height:30px;">
																						<th style="width:10;">วันที่เริ่ม</th>
																						<th style="width:10;">วันที่สิ้นสุด</th>
																					</tr>
																				</thead>
																				<tbody>
																					<?php
																						$n = 0;													
																						$params = array($crstm_nbr);
																						// $sql_cc= "SELECT crlimit_acc, sum(crlimit_amt_loc_curr) as amt_loc, crlimit_doc_date,crlimit_due_date,crlimit_txt_ref, crlimit_seq FROM crlimit_mstr WHERE(crlimit_acc = ? and crlimit_ref = 'CC') ".
																						// "GROUP BY crlimit_acc, crlimit_doc_date,crlimit_due_date,crlimit_txt_ref, crlimit_seq order by crlimit_doc_date,crlimit_due_date";
																						
																						$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? ";
																						
																						$result_cc = sqlsrv_query($conn, $sql_cc,$params);
																						$tot_ord = 0 ;
																						$rows = 0;
																						while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
																						{
																							$acc_txt = "วงเงินปัจจุบัน";
																							$acc_tot_txt = "รวมวงเงินขออนุมัติ";
																							$rows = $rows + 1;
																							$tot_ord = number_format($row_cc['tbl3_amt_loc_curr']);
																							$sum_ord = $row_cc['tbl3_amt_loc_curr'];
																							$txt_ref = $row_cc['tbl3_txt_ref'];
																							$doc_date = dmytx($row_cc['tbl3_doc_date']);
																							$due_date = dmytx($row_cc['tbl3_due_date']);
																							$row_seq = $row_cc['tbl3_id'];
																							$acc_tot = $acc_tot + $sum_ord ;
																							
																							//$acc_txt = "วงเงินปัจจุบัน";
																							$acc_tot_txt = "รวมวงเงินขออนุมัติ";
																							if ($txt_ref == "C1") {
																								$acc_txt = "เสนอขอปรับเพิ่มวงเงิน";
																								} else if ($txt_ref == "C2") {
																								$acc_txt = "เสนอขอปรับลดวงเงิน";
																								} else if ($txt_ref == "C3") {
																								$acc_txt = "เสนอขอต่ออายุวงเงิน";	
																								} else {
																								$acc_txt = "วงเงินปัจจุบัน";
																							}
																							
																							$n++;																										
																						?>
																						<tr class="black">
																							<!-- เสนอขอต่ออายุวงเงิน ตัวอักษรสีน้ำเงิน -->
																							<?php if($txt_ref == "C3") { ?>
																								<td class="pl-1 pr-0 text-center"><?php echo $acc_txt; ?></td>
																								<td class="pl-1 pr-0 text-center"><?php echo $doc_date; ?></td>	
																								<td class="pl-1 pr-0 text-center"><?php echo $due_date; ?></td>
																								<td class="pl-1 pr-1 text-right"><?php echo $tot_ord; ?></td>
																								<? } else { ?>	
																								<td class="pl-1 pr-0 text-center"><?php echo $acc_txt; ?></td>
																								<td class="pl-1 pr-0 text-center"><?php echo $doc_date; ?></td>	
																								<td class="pl-1 pr-0 text-center"><?php echo $due_date; ?></td>
																								<td class="pl-1 pr-1 text-right"><?php echo $tot_ord; ?></td>
																							<?php } ?>
																							
																							<td>
																								<?php if ($txt_ref <> "CC") {?>
																									<button type="button" class="btn btn-success dropdown-toggle btn-sm " data-boundary="viewport" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
																								<?php }?>
																								<div class="dropdown-menu">
																									<a class="open-EditCCDialog dropdown-item small blue" href="" data-toggle="modal" data-target="#div_frm_cc_edit1" data-begdte="<?php echo $doc_date?>" data-enddte="<?php echo $due_date?>" data-cc_amt="<?php echo $tot_ord ?>" data-txt_ref="<?php echo $txt_ref ?>" data-txt_cc="<?php echo $acc_txt ?>" data-row_seq="<?php echo $row_seq ?>" data-crstm_nbr="<?php echo $crstm_nbr ?>"><i class="ft-edit"></i> Edit</a>
																									<?php if ($iscan_editing) {?>
																										<!--<a id="btccdel" class="dropdown-item small red" data-row_seq="<?php echo $row_seq?>" data-tot_ord="<?php echo $tot_ord?>"><i class="fa fa-trash-o fa-sm "></i> Delete</a>-->
																									<?php }?>
																									
																								</div>
																							</td>
																						</tr>
																					<?php }?>	
																					<input type="hidden" name="beg_date1" id="beg_date1" value="<?php echo $crstm_cc_date_beg ?>">
																					<input type="hidden" name="end_date1" id="end_date1" trol input-sm font-small-3" value="<?php echo $crstm_cc_date_end ?>">
																					<input type="hidden" name="cc_amt1" id="cc_amt1" value="<?php echo $crstm_cc_amt ?>"> 
																					<?php 
																						//$acc_tot_app = $acc_tot;
																						//$acc_tot = number_format($acc_tot);
																						
																						$sum_acc_tot = number_format($acc_tot);
																						$acc_tot = number_format($acc_tot);
																						if($cusold_conf_yes=='C1') {
																							$txt_ccr = "เสนอขอปรับเพิ่มวงเงิน";
																						}
																					?>
																					
																					<!--<tr class="black">
																						<td class="pl-1 pr-1"><input type="text" name="txt_ccr" id="txt_ccr" value="<?php echo $txt_ccr ?>" class="form-control form-control input-sm font-small-3" style="color:green;text-align:center"> </td>
																						<td class="pl-1 pr-1"><input type="text" name="beg_date1" id="beg_date1" class="form-control date-inputmask form-control input-sm font-small-3" style="color:green;text-align:center" value="<?php echo $crstm_cc_date_beg ?>"></td>
																						<td class="pl-1 pr-1"><input type="text" name="end_date1" id="end_date1" class="form-control date-inputmask form-control input-sm font-small-3" style="color:green;text-align:center" value="<?php echo $crstm_cc_date_end ?>"> </td>
																						<td class="pl-1 pr-1"><input type="text" name="cc_amt1" id="cc_amt1" class="form-control form-control input-sm font-small-3" style="color:green;text-align:right" value="<?php echo $crstm_cc_amt ?>" onkeyup="format(this)" onchange="format(this)"> </td>
																						
																						<?php if ($iscan_editing) {?>
																							<td>
																							<div class="btn-group">
																							<button type="button" class="btn btn-success dropdown-toggle btn-sm " data-boundary="viewport" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
																							<div class="dropdown-menu">
																							<a class="open-EditCCDialog dropdown-item blue" href="" data-toggle="modal" data-target="#div_frm_cc_edit1" data-txt_ref="<?php echo $cusold_conf_yes ?>" data-crstm_nbr="<?php echo $crstm_nbr ?>" data-begdte="<?php echo $crstm_cc_date_beg ?>" data-enddte="<?php echo $crstm_cc_date_end ?>" data-cc_amt="<?php echo $crstm_cc_amt ?>" ><i class="ft-edit"></i> Edit</a>
																							<a class="dropdown-item red"  onclick="editdel_cc('<?php echo $crstm_nbr;?>')" ><i class="fa fa-trash-o fa-sm "></i> Delete</a>
																							
																							</div>
																							</div>
																							</td>
																						<?}?>
																						
																					</tr>-->
																					<!--<tr class="black">		
																						<?php if($acc_tot<>0) { ?>
																							<td align='center' colspan='3' style='color:blue'><?php echo $acc_tot_txt; ?></td>
																							<td class="pl-1 pr-1 text-right" style='color:blue'><?php  echo $acc_tot; ?></td>
																						<?php } ?>
																					</tr>-->
																					<input type="hidden" name="acc_tot" id="acc_tot" value="<?php echo $acc_tot ?>" class="form-control form-control input-sm font-small-3" style="color:green;text-align:right">
																					
																					<?php if ($crstm_cc_amt!="") {?>
																						<?php
																							$crstm_cc_amt = str_replace(',','', $crstm_cc_amt);
																							$acc_tot = str_replace(',','', $acc_tot);
																							$sum_acc_tot = $acc_tot; //+ $crstm_cc_amt ; 
																							$sum_acc_tot = number_format($sum_acc_tot);
																						?>
																					<? } ?>
																					
																					<tr class="black">		
																						<td align='center' colspan='3' style='color:blue'>รวมวงเงินขออนุมัติ</td>
																						<!--<td class="pl-1 pr-1 text-right" style="color:blue"><?php  echo $sum_acc_tot; ?></td>-->
																						<td class="pl-1 pr-1" width="80"><input type="text" name="sum_acc_tot" id="sum_acc_tot" value="<?php echo $sum_acc_tot ?>" class="form-control form-control input-sm font-small-3" style="color:green;text-align:right" readonly> </td>
																					</tr>
																					<?php 
																						//$acc_tot_app = $sum_acc_tot;
																						$sum_acc_tot = str_replace(',','',$sum_acc_tot);
																						$acc_tot_app = $sum_acc_tot;
																					?>	
																				</tbody>
																			</table>
																		</div>	
																	</div>
																	<!-- End Table Clean Credit -->
																	<!--- เช็คอำนาจดำเนินการขออนุมัติวงเงิน --->
																	<?
																	switch ($crstm_approve) {
																		case "ผส. อนุมัติ": 
																				$pointer = "pointer";
																				break;
																		case "ผฝ. อนุมัติ": 	
																				$pointer = "pointer";	
																				break;
																		case "CO. อนุมัติ": 	
																				$canedit = "readOnly";
																				$error_txt ="";
																				$pointer = "none";
																				break;
																		case "กจก. อนุมัติ": 	
																				$canedit = "readOnly";
																				$error_txt ="";
																				$pointer = "none";
																				break;
																		case "คณะกรรมการสินเชื่ออนุมัติ": 	
																				$canedit = "readOnly";
																				$error_txt ="";
																				$pointer = "none";
																				break;
																		case "คณะกรรมการบริหารอนุมัติ": 	
																				$canedit = "readOnly";
																				$error_txt ="";
																				$pointer = "none";	
																				break;	
																	}											
																	?>	
																	<!------------------------->
																	<? if ($cus_acc_group == "ZC01" || $cus_acc_group == "ZC07") {
																		if ($acc_tot_app  <= 700000) { 
																			$crstm_approve = 'ผส. อนุมัติ';	
																			//$crstm_email_app1 = "";	$app1_name = "";
																			$crstm_email_app2 = "";	$app2_name = "";
																			$crstm_email_app3 = "";	$app3_name = "";
																			$canedit = "";
																			$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
																			$pointer = "pointer"; $chk_block = "none"; $chk_rev2 = "none";	$dis_rev2 = "block";															
																		}else if ($acc_tot_app >= 700001 && $acc_tot_app <= 2000000) { 
																			$crstm_approve = 'ผฝ. อนุมัติ';
																			//$crstm_email_app1 = "";	$app1_name = "";
																			$crstm_email_app2 = "";	$app2_name = "";
																			$crstm_email_app3 = "";	$app3_name = "";
																			$canedit = "";
																			$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
																			$pointer = "pointer"; $chk_block = "none"; $chk_rev2 = "none"; $dis_rev2 = "block";
																		}else if ($acc_tot_app >= 2000001 && $acc_tot_app <= 5000000) { 
																			$crstm_approve = 'CO. อนุมัติ';
																			if ($crstm_scgc == true) {
																				$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																				$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
																			} else {
																				$crstm_email_app1 = findsqlvalfirst("author_g_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																				$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
																			}
																			$canedit = "readOnly";
																			$error_txt ="";
																			$pointer = "none"; $chk_block = "none"; $chk_rev2 = "none"; $dis_rev2 = "block";
																		}else if ($acc_tot_app >= 5000001 && $acc_tot_app <= 7000000) { 
																			$crstm_approve = 'กจก. อนุมัติ';
																			if ($crstm_scgc == true) {
																				$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																				$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
																			} else {
																				$crstm_email_app1 = findsqlvalfirst("author_g_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																				$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
																			} 
																			$canedit = "readOnly";
																			$error_txt ="";
																			$pointer = "none"; $chk_block = "none"; $chk_rev2 = "none"; $dis_rev2 = "block";
																		}else if ($acc_tot_app >= 7000001 && $acc_tot_app <= 10000000) { 
																				$crstm_approve = 'คณะกรรมการสินเชื่ออนุมัติ';
																				if ($crstm_scgc == true) {
																					$fle = 'author_mstr';
																				}else{
																					$fle = 'author_g_mstr';
																				}	
																				$params = array($crstm_approve);
																				$sql = "SELECT  author_id, author_seq, author_code, author_group, author_sign_nme, author_sign, author_position, author_email, author_email_status, author_active, author_text, author_salutation, ".
																				"financial_amt_beg, financial_amt_end, account_group, author_create_date, author_create_by, author_update_date, author_update_by, author_remark ".
																				"FROM $fle WHERE (author_text = ?) and account_group='ZC01' and author_active='1' order by author_no";
																					$result = sqlsrv_query($conn, $sql,$params);  				
																					$crstm_email_app = array();
																					$app_name = array();		
																					//$count = 0;
																					$no = 1;
																					while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																					{
																						//$count = $no+1;
																						$crstm_email_app[$no] = $row['author_email'];            
																						$app_name[$no]  = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app[$no],$conn);	
																						$no++;  
																						$crstm_email_app1 = $crstm_email_app[1]; $app1_name = $app_name[1];
																						$crstm_email_app2 = $crstm_email_app[2]; $app2_name = $app_name[2];
																						$crstm_email_app3 = $crstm_email_app[3]; $app3_name = $app_name[3];
																					}  
																					$canedit = "readOnly";
																					$error_txt ="";
																					$pointer = "none";
																					$chk_block = "block";
																		}else if ($acc_tot_app >= 10000001) { 
																			$crstm_approve = 'คณะกรรมการบริหารอนุมัติ';		
																			$crstm_email_app = array();
																			$app_name = array();																	
																			if ($crstm_scgc == true) {
																				$fle = 'author_mstr';
																			}else{
																				$fle = 'author_g_mstr';
																			}																				
																			$params = array($crstm_approve);
																			$sql = "SELECT  author_id, author_seq, author_code, author_group, author_sign_nme, author_sign, author_position, author_email, author_email_status, author_active, author_text, author_salutation, ".
																			"financial_amt_beg, financial_amt_end, account_group, author_create_date, author_create_by, author_update_date, author_update_by, author_remark ".
																			"FROM $fle WHERE (author_text = ?) and account_group='ZC01' and author_active='1' order by author_no";
																				$result = sqlsrv_query($conn, $sql,$params);  				
																			
																				//$count = 0;
																				$no = 1;
																				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																				{
																					//$count = $no+1;
																					$crstm_email_app[$no] = $row['author_email'];            
																					$app_name[$no]  = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app[$no],$conn);	
																					$no++;  
																					$crstm_email_app1 = $crstm_email_app[1]; $app1_name = $app_name[1];
																					$crstm_email_app2 = $crstm_email_app[2]; $app2_name = $app_name[2];
																					$crstm_email_app3 = $crstm_email_app[3]; $app3_name = $app_name[3];
																				}  
																				$canedit = "readOnly";
																				$error_txt ="";
																				$pointer = "none";
																				$chk_block = "block";
																		} 
																	} ?>
																	<? if ($cus_acc_group == "DREP") {
																	if ($acc_tot_app >= 1 && $acc_tot_app  <= 500000) { 
																		$crstm_approve = 'ผผ. อนุมัติ';	
																		$canedit = "";
																		$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
																		$pointer = "pointer";
																	}else if ($acc_tot_app >= 500001 && $acc_tot_app <= 3000000) { 
																		$crstm_approve = 'ผส. อนุมัติ';	
																		$canedit = "";
																		$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
																		$pointer = "pointer";
																	}else if ($acc_tot_app >= 3000001 && $acc_tot_app <= 13000000) { 
																		$crstm_approve = 'ผฝ. อนุมัติ';
																		$canedit = "";
																		$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
																		$pointer = "pointer";
																	}else if ($acc_tot_app >= 13000001 && $acc_tot_app <= 25000000) { 
																		$crstm_approve = 'CO. อนุมัติ';
																		$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																		$canedit = "readOnly";
																		$error_txt ="";
																		$pointer = "none";
																		if ($crstm_scgc == true) {
																				$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																				$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
																		}else {
																				$crstm_email_app1 = findsqlvalfirst("author_g_mstr", "author_email", "author_code", 'CFO' ,$conn);
																				$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
																		}
																	}else if ($acc_tot_app >= 25000001 && $acc_tot_app <= 50000000) { 
																		$crstm_approve = 'กจก. อนุมัติ';
																		$canedit = "readOnly";
																		$error_txt ="";
																		$pointer = "none";
																		if ($crstm_scgc == true) {
																				$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
																				$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
																		}else {
																				$crstm_email_app1 = findsqlvalfirst("author_g_mstr", "author_email", "author_code", 'CFO' ,$conn);
																				$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
																		}
																	}else if ($acc_tot_app >= 50000001) { 
																		$crstm_approve = 'คณะกรรมการบริหารอนุมัติ';																			
																		$crstm_email_app = array();
																			$app_name = array();																	
																			if ($crstm_scgc == true) {
																				$fle = 'author_mstr';
																			}else{
																				$fle = 'author_g_mstr';
																			}																				
																			$params = array($crstm_approve);
																			$sql = "SELECT  author_id, author_seq, author_code, author_group, author_sign_nme, author_sign, author_position, author_email, author_email_status, author_active, author_text, author_salutation, ".
																			"financial_amt_beg, financial_amt_end, account_group, author_create_date, author_create_by, author_update_date, author_update_by, author_remark ".
																			"FROM $fle WHERE (author_text = ?) and account_group='ZC01' and author_active='1' order by author_no";
																				$result = sqlsrv_query($conn, $sql,$params);  				
																			
																				//$count = 0;
																				$no = 1;
																				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
																				{
																					//$count = $no+1;
																					$crstm_email_app[$no] = $row['author_email'];            
																					$app_name[$no]  = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app[$no],$conn);	
																					$no++;  
																					$crstm_email_app1 = $crstm_email_app[1]; $app1_name = $app_name[1];
																					$crstm_email_app2 = $crstm_email_app[2]; $app2_name = $app_name[2];
																					$crstm_email_app3 = $crstm_email_app[3]; $app3_name = $app_name[3];
																				}  
																		$canedit = "readOnly";
																		$error_txt ="";
																		$pointer = "none";
																		$chk_block = "block";
																		} 
																	} ?>
																	<!------------------------->
																	
																	<!--- เช็คอำนาจดำเนินการขออนุมัติวงเงิน --->
																	<div class="row">
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-6 label-control font-weight-bold" for="userinput1">อำนาจดำเนินการอนุมัติวงเงิน:</label>
																				<div class="col-md-6">
																					<input type="text" name="crstm_approve" id="crstm_approve" value="<?php echo $crstm_approve ?>" class="form-control input-sm font-small-3" readonly>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-6"></div>
																		
																		<div class="col-md-3">
																			<label class="font-weight-bold" for="cus_conf_yes">Group:</label>
																		</div>
																		<div class="col-md-2">
																			<input type="radio" name="crstm_scgc" id="crstm_scgc" value=true <?php if ($crstm_scgc==true){ echo "checked"; }?>>
																			<label class="font-weight-bold">Tiles</label>
																		</div>
																		<div class="col-md-2">
																			<input type="radio" name="crstm_scgc" id="crstm_scgc1" value=false <?php if ($crstm_scgc==false){ echo "checked"; }?>>
																			<label class="font-weight-bold">Geoluxe</label>
																		</div>
																		<div class="col-md-5"></div>
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา 1 : </label>
																				<div class="col-md-6">
																					<div class="input-group input-group-sm">
																						<input name="crstm_reviewer" id="crstm_reviewer" <?php echo ($reviewer1_read) ?>  value="<?php echo $crstm_reviewer ?>" 
																						data-disp_col1 = "emp_fullname"
																						data-disp_col2 = "emp_email_bus"
																						data-typeahead_src = "../_help/get_emp_data.php",
																						data-ret_field_01 = "crstm_reviewer"
																						data-ret_value_01 = "emp_email_bus"
																						data-ret_type_01 = "val"
																						data-ret_field_02 = "reviewer_name"
																						data-ret_value_02 = "emp_fullnamedept"
																						data-ret_type_02 = "html"
																						class="form-control input-sm font-small-3 typeahead">
																						<div class="input-group-prepend">
																							<span class="input-group-text">
																								<a id="buthelp"
																								data-id_field_code="crstm_reviewer" 
																								data-id_field_name="reviewer_name" 
																								data-modal_class = "modal-dialog modal-lg" 
																								data-modal_title = "ข้อมูลพนักงาน" 
																								data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
																								data-modal_col_data1 = "emp_scg_emp_id"
																								data-modal_col_data2 = "emp_fullnamedept"
																								data-modal_col_data3 = "emp_dept"
																								data-modal_col_data4 = "emp_email_bus"
																								data-modal_col_data3_vis = true
																								data-modal_col_data4_vis = true 
																								data-modal_ret_data1 = "emp_email_bus"
																								data-modal_ret_data2 = "emp_fullnamedept"
																								data-modal_src = "../_help/get_emp_data.php"
																								class="input-group-append" style="cursor:pointer">
																									<span class="fa fa-search"></span>
																								</a>
																							</span>
																						</div>
																					</div><br>
																					<div class="dis_reviewer_name">
																						<span id="reviewer_name" name="reviewer_name"  class="text-danger"><?php echo $reviewer_name?></span>
																					</div>
																				</div>	
																			</div>
																		</div>
																		
																		<div class="col-md-6">
																			<div class="form-group row">
																				<div class="col-md-1">
																					<input type="checkbox" class="form-control input-sm border-warning " name="crstm_noreviewer" id="crstm_noreviewer" <?php if ($crstm_noreviewer==true){ echo "checked"; }?> value="true">
																				</div>
																				<label class="col-md-6 label-control" for="userinput1">กรณีไม่ระบุผู้พิจารณา1 :</label>
																			</div>
																		</div>
																		<div class="col-md-6 blockRev2" style="display:<?php echo $dis_rev2 ?>"></div>
																		<div class="col-md-6 displayRev2" style="display:<?php echo $chk_rev2 ?>">
																			<div class="form-group row">
																				<label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา 2 : </label>
																				<div class="col-md-6">
																					<div class="input-group input-group-sm">
																						<input name="crstm_reviewer2" id="crstm_reviewer2" <?php echo ($reviewercanedit) ?> value="<?php echo $crstm_reviewer2 ?>" 
																						data-disp_col1 = "emp_fullname"
																						data-disp_col2 = "emp_email_bus"
																						data-typeahead_src = "../_help/get_emp_data.php",
																						data-ret_field_01 = "crstm_reviewer2"
																						data-ret_value_01 = "emp_email_bus"
																						data-ret_type_01 = "val"
																						data-ret_field_02 = "reviewer_name2"
																						data-ret_value_02 = "emp_fullnamedept"
																						data-ret_type_02 = "html"
																						class="form-control input-sm font-small-3 typeahead">
																						<div class="input-group-prepend">
																							<span class="input-group-text">
																								<a id="buthelp"
																								data-id_field_code="crstm_reviewer2" 
																								data-id_field_name="reviewer_name2" 
																								data-modal_class = "modal-dialog modal-lg" 
																								data-modal_title = "ข้อมูลพนักงาน" 
																								data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
																								data-modal_col_data1 = "emp_scg_emp_id"
																								data-modal_col_data2 = "emp_fullnamedept"
																								data-modal_col_data3 = "emp_dept"
																								data-modal_col_data4 = "emp_email_bus"
																								data-modal_col_data3_vis = true
																								data-modal_col_data4_vis = true 
																								data-modal_ret_data1 = "emp_email_bus"
																								data-modal_ret_data2 = "emp_fullnamedept"
																								data-modal_src = "../_help/get_emp_data.php"
																								class="input-group-append" style="pointer-events: <?php echo $pointer_vie2 ?>">
																									<span class="fa fa-search" id="pointer1"></span>
																								</a>
																							</span>
																						</div>
																					</div><br>
																					<div class="dis_reviewer_name2">
																						<span id="reviewer_name2" name="reviewer_name2"  class="text-danger"><?php echo $reviewer_name2?></span>
																					</div>
																				</div>	
																			</div>
																		</div>

																		<!--<div class="col-md-3"></div>
																		<div class="dis_reviewer_name col-md-6">
																			<span id="reviewer_name" name="reviewer_name"  class="text-danger"><?php echo $reviewer_name?></span>
																		</div>-->
																		<div class="col-md-6"></div>
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 1:</label>
																				<div class="col-md-6">
																					<div class="input-group input-group-sm">
																						<input name="crstm_email_app1" id="crstm_email_app1" <?php echo $canedit ?> value="<?php echo $crstm_email_app1 ?>" 
																						data-disp_col1 = "emp_fullname"
																						data-disp_col2 = "emp_email_bus"
																						data-typeahead_src = "../_help/get_emp_data.php",
																						data-ret_field_01 = "crstm_email_app1"
																						data-ret_value_01 = "emp_email_bus"
																						data-ret_type_01 = "val"
																						data-ret_field_02 = "app1_name"
																						data-ret_value_02 = "emp_fullnamedept"
																						data-ret_type_02 = "html"
																						class="form-control input-sm font-small-3 typeahead">
																						
																						<div class="input-group-prepend">
																							<span class="input-group-text">
																								<a id="buthelp" 
																								data-id_field_code="crstm_email_app1" 
																								data-id_field_name="app1_name" 
																								data-modal_class = "modal-dialog modal-lg" 
																								data-modal_title = "ข้อมูลพนักงาน" 
																								data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
																								data-modal_col_data1 = "emp_scg_emp_id"
																								data-modal_col_data2 = "emp_fullnamedept"
																								data-modal_col_data3 = "emp_dept"
																								data-modal_col_data4 = "emp_email_bus"
																								data-modal_col_data3_vis = true
																								data-modal_col_data4_vis = true 
																								data-modal_ret_data1 = "emp_email_bus"
																								data-modal_ret_data2 = "emp_fullnamedept"
																								data-modal_src = "../_help/get_emp_data.php"
																								class="input-group-append" style="pointer-events: <?php echo $pointer ?>">
																									<span class="fa fa-search"></span>
																								</a>
																							</span>
																						</div>
																					</div><br>
																					<div><span id="app1_name" name="app1_name"  class="text-danger"><?php echo $app1_name?></span></div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
																			</div>
																		</div>
																		
																		<div class="col-md-6 displayApp2" style="display:<?php echo $chk_block ?>">
																				<div class="form-group row">
																					<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 2:</label>
																					<div class="col-md-6">
																						<div class="input-group input-group-sm">
																							<input name="crstm_email_app2" id="crstm_email_app2" readOnly <?php echo $canedit ?> value="<?php echo $crstm_email_app2 ?>" 
																							data-disp_col1 = "emp_fullname"
																							data-disp_col2 = "emp_email_bus"
																							data-typeahead_src = "../_help/get_emp_data.php",
																							data-ret_field_01 = "crstm_email_app2"
																							data-ret_value_01 = "emp_email_bus"
																							data-ret_type_01 = "val"
																							data-ret_field_02 = "app2_name"
																							data-ret_value_02 = "emp_fullnamedept"
																							data-ret_type_02 = "html"
																							class="form-control input-sm font-small-3 typeahead">
																							
																							<div class="input-group-prepend">
																								<span class="input-group-text">
																									<a id="buthelp"
																									data-id_field_code="crstm_email_app2" 
																									data-id_field_name="app2_name" 
																									data-modal_class = "modal-dialog modal-lg" 
																									data-modal_title = "ข้อมูลพนักงาน" 
																									data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
																									data-modal_col_data1 = "emp_scg_emp_id"
																									data-modal_col_data2 = "emp_fullnamedept"
																									data-modal_col_data3 = "emp_dept"
																									data-modal_col_data4 = "emp_email_bus"
																									data-modal_col_data3_vis = true
																									data-modal_col_data4_vis = true 
																									data-modal_ret_data1 = "emp_email_bus"
																									data-modal_ret_data2 = "emp_fullnamedept"
																									data-modal_src = "../_help/get_emp_data.php"
																									class="input-group-append" style="pointer-events: <?php echo $pointer ?>">
																										<span class="fa fa-search"></span>
																									</a>
																								</span>
																							</div>
																						</div><br>
																						<div><span id="app2_name" name="app2_name"  class="text-danger"><?php echo $app2_name?></span></div>
																					</div>
																				</div>
																			</div>
																		<div class="col-md-6">
																				<div class="form-group row">
																					<label class="col-md-9 label-control text-danger show_error" for="userinput1" style="display:<?php echo $chk_block ?>"><?php echo $error_txt ?></label>
																				</div>
																			</div>
																			<div class="col-md-6 displayApp3" style="display:<?php echo $chk_block ?>">
																				<div class="form-group row">
																					<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 3:</label>
																					<div class="col-md-6">
																						<div class="input-group input-group-sm">
																							<input name="crstm_email_app3" id="crstm_email_app3" readOnly <?php echo $canedit ?> value="<?php echo $crstm_email_app3 ?>" 
																							data-disp_col1 = "emp_fullname"
																							data-disp_col2 = "emp_email_bus"
																							data-typeahead_src = "../_help/get_emp_data.php",
																							data-ret_field_01 = "crstm_email_app3"
																							data-ret_value_01 = "emp_email_bus"
																							data-ret_type_01 = "val"
																							data-ret_field_02 = "app3_name"
																							data-ret_value_02 = "emp_fullnamedept"
																							data-ret_type_02 = "html"
																							class="form-control input-sm font-small-3 typeahead">
																							
																							<div class="input-group-prepend">
																								<span class="input-group-text">
																									<a id="buthelp"
																									data-id_field_code="crstm_email_app3" 
																									data-id_field_name="app3_name" 
																									data-modal_class = "modal-dialog modal-lg" 
																									data-modal_title = "ข้อมูลพนักงาน" 
																									data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
																									data-modal_col_data1 = "emp_scg_emp_id"
																									data-modal_col_data2 = "emp_fullnamedept"
																									data-modal_col_data3 = "emp_dept"
																									data-modal_col_data4 = "emp_email_bus"
																									data-modal_col_data3_vis = true
																									data-modal_col_data4_vis = true 
																									data-modal_ret_data1 = "emp_email_bus"
																									data-modal_ret_data2 = "emp_fullnamedept"
																									data-modal_src = "../_help/get_emp_data.php"
																									class="input-group-append" style="pointer-events: <?php echo $pointer ?>">
																										<span class="fa fa-search"></span>
																									</a>
																								</span>
																							</div>
																						</div><br>
																						<div><span id="app3_name" name="app3_name"  class="text-danger"><?php echo $app3_name?></span></div>
																					</div>
																				</div>
																			</div>
																		<div class="col-md-6">
																				<div class="form-group row">
																					<label class="col-md-9 label-control text-danger show_error" for="userinput1" style="display:<?php echo $chk_block ?>"><?php echo $error_txt ?></label>
																				</div>
																			</div>
																		<!---------------->
																		
																		<div class="col-md-12">
																			<fieldset class="form-group">
																				<label for="placeTextarea" class="font-weight-bold">ความเห็น / เหตุผลที่เสนอขอวงเงิน : <font class="text text-danger font-weight-bold"> ***</font></label>
																				<textarea  name="crstm_sd_reson" id="crstm_sd_reson" class="form-control textarea-maxlength input-sm font-small-3 border-warning" placeholder="Enter upto 500 characters.." maxlength="500"  rows="5" style="line-height:1.5rem;"><?php echo $crstm_sd_reson; ?></textarea>
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
																								<input type="file" accept="" name="load_reson_img" id="load_reson_img" value="<?php echo $crstm_reson_img ?>" onkeyup="CheckValidFile_header_attach('load_reson_img')" onchange="CheckValidFile_header_attach('load_reson_img')">
																								<input type="hidden" name="crstm_reson_img" id="crstm_reson_img" value="<?php echo $crstm_reson_img ?>">
																								<span class="file-custom"></span>
																							</label>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-4">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																				<div class="col-md-9">
																					<a href="<?php echo($ImgReson) ?>" target="_blank" id="linkcurrent_ImgProject">
																						<img src="<?php echo($ImgReson_icon) ?>" border="0" id="ImgReson" name="ImgReson"  width="60" height="60">
																					</a>	
																				</div>
																				
																			</div>
																		</div>
																		<div class="col-md-2">
																			<input type="checkbox"  id="del_reson" name="del_reson" value="1">
																			<label class="font-weight-bold" for="cus_conf_yes"> ลบรูปภาพ</label>
																		</div>
																		
																		
																		<?php if(($crstm_rem_rearward !="") && inlist('0,01',$crstm_step_code)){ ?> 
																			<div class="col-md-12">
																				<div class="form-group">
																					<label for="placeTextarea" class="font-weight-bold">เหตุผลการ Rearward:<font class="text text-danger font-weight-bold"> ***</font></label>
																					<textarea  name="crstm_rem_rearward" id="crstm_rem_rearward" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem; background:#99CC33; color:white;"><?php echo $crstm_rem_rearward; ?></textarea>
																				</div>
																			</div>
																		<?php } ?>
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
																					<input type="text" name="crstm_pj_name" id="crstm_pj_name" value="<?php echo($crstm_pj_name) ?>" class="form-control input-sm border-warning font-small-3" >
																				</div>
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">ชื่อโครงการ (2):</label>
																				<div class="col-md-9">
																					<input type="text" id="crstm_pj1_name" class="form-control input-sm border-warning font-small-3" name="crstm_pj1_name"  value="<?php echo($crstm_pj1_name) ?>">
																				</div>
																			</div>
																		</div>
																		
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">จังหวัด:</label>
																				<div class="col-md-9">
																					<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj_prv" name="crstm_pj_prv">
																						<option value="" selected>--- เลือกจังหวัด ---</option>
																						<?php
																							$sql_doc = "SELECT * FROM province_mstr order by province_th_name";
																							$result_doc = sqlsrv_query($conn, $sql_doc);
																							while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																							?>
																							<option value="<?php echo $r_doc['province_th_name']; ?>" 
																							<?php if ($crstm_pj_prv == $r_doc['province_th_name']) {
																								echo "selected";
																							} ?>>	
																							<?php echo $r_doc['province_th_name']; ?></option>
																						<?php } ?>
																					</select>
																				</div>
																			</div>
																		</div>
																		
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">จังหวัด:</label>
																				<div class="col-md-9">
																					<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj1_prv" name="crstm_pj1_prv">
																						<option value="" selected>--- เลือกจังหวัด ---</option>
																						<?php
																							$sql_doc = "SELECT * FROM province_mstr order by province_th_name";
																							$result_doc = sqlsrv_query($conn, $sql_doc);
																							while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																							?>
																							<option value="<?php echo $r_doc['province_th_name']; ?>" 
																							<?php if ($crstm_pj1_prv == $r_doc['province_th_name']) {
																								echo "selected";
																							} ?>>	
																							<?php echo $r_doc['province_th_name']; ?></option>
																						<?php } ?>
																					</select>
																				</div>
																			</div>
																		</div>
																		
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">เงื่อนไขการชำระ:</label>
																				<div class="col-md-9">
																					<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj_term" name="crstm_pj_term">
																						<option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---</option>
																						<?php
																							$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
																							$result_doc = sqlsrv_query($conn, $sql_doc);
																							while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																							?>
																							<option value="<?php echo $r_doc['term_code']; ?>" 
																							<?php if ($crstm_pj_term == $r_doc['term_code']) {
																								echo "selected";
																							} ?>>
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
																					<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj1_term" name="crstm_pj1_term">
																						<option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---</option>
																						<?php
																							$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
																							$result_doc = sqlsrv_query($conn, $sql_doc);
																							while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																							?>
																							<option value="<?php echo $r_doc['term_code']; ?>" 
																							<?php if ($crstm_pj1_term  == $r_doc['term_code']) {
																								echo "selected";
																							} ?>>
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
																					<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj_amt" value="<?php echo($crstm_pj_amt) ?>" class="form-control input-sm border-warning font-small-3" name="crstm_pj_amt" style="color:black;text-align:right" onkeyup="format(this)" onchange="format(this)" ></a>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">มูลค่างาน (บาท):</label>
																				<div class="col-md-9">
																					<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj1_amt" value="<?php echo($crstm_pj1_amt) ?>" class="form-control input-sm border-warning font-small-3" name="crstm_pj1_amt" style="color:black;text-align:right" onkeyup="format(this)" onchange="format(this)"></a>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">ระยะเวลา (เดือน):</label>
																				<div class="col-md-9">
																					<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj_dura" name="crstm_pj_dura">
																						<option value="" selected>--- เลือกจำนวนเดือน ---</option>
																						<?php
																							$sql_doc = "SELECT tbl_mm_code, tbl_mm_desc, tbl_mm_seq FROM tbl_mm ORDER BY tbl_mm_seq";
																							$result_doc = sqlsrv_query($conn, $sql_doc);
																							while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																							?>
																							<option value="<?php echo $r_doc['tbl_mm_code']; ?>" 
																							<?php if ($crstm_pj_dura == $r_doc['tbl_mm_code']) {
																								echo "selected";
																							} ?>>	
																							<?php echo $r_doc['tbl_mm_desc']; ?></option>
																						<?php } ?>
																					</select>
																				</div>
																				<!--<div class="col-md-9">
																					<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj_dura" class="form-control input-sm" name="crstm_pj_dura" onkeypress="return chkNumber_dot(event)"></a>
																				</div>-->
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">ระยะเวลา (เดือน):</label>
																				<div class="col-md-9">
																					<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj1_dura" name="crstm_pj1_dura">
																						<option value="" selected>--- เลือกจำนวนเดือน ---</option>
																						<?php
																							$sql_doc = "SELECT tbl_mm_code, tbl_mm_desc, tbl_mm_seq FROM tbl_mm ORDER BY tbl_mm_seq";
																							$result_doc = sqlsrv_query($conn, $sql_doc);
																							while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																							?>
																							<option value="<?php echo $r_doc['tbl_mm_code']; ?>" 
																							<?php if ($crstm_pj1_dura == $r_doc['tbl_mm_code']) {
																								echo "selected";
																							} ?>>	
																							<?php echo $r_doc['tbl_mm_desc']; ?></option>
																						<?php } ?>
																					</select>
																				</div>
																				<!--<div class="col-md-9">
																					<a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj1_dura" class="form-control input-sm" name="crstm_pj1_dura" onkeypress="return chkNumber_dot(event)"></a>
																				</div>-->
																			</div>
																		</div>
																		
																		<div class="col-md-6">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">เริ่มใช้งาน:</label>
																				<div class="col-md-9">
																					<div class="input-group input-group-sm">
																						<input id="crstm_pj_beg" name="crstm_pj_beg" value="<?php echo $crstm_pj_beg ?>" class="form-control input-sm border-warning font-small-3" type="text">
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
																						<input id="crstm_pj1_beg" name="crstm_pj1_beg" value="<?php echo $crstm_pj1_beg ?>" class="form-control input-sm border-warning font-small-3" type="text">
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
																								<input type="file" accept="image/*" name="load_pj_img" id="load_pj_img" value="<?php echo $crstm_pj_img ?>" onkeyup="CheckValidFile_header_attach('load_pj_img')" onchange="CheckValidFile_header_attach('load_pj_img')">
																								<input type="hidden" name="crstm_pj_img" id="crstm_pj_img" value="<?php echo $crstm_pj_img ?>">
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
																								<input type="file" accept="image/*" name="load_pj1_img" id="load_pj1_img" value="<?php echo $crstm_pj1_img ?>" onkeyup="CheckValidFile_header_attach('load_pj1_img')" onchange="CheckValidFile_header_attach('load_pj1_img')">
																								<input type="hidden" name="crstm_pj1_img" id="crstm_pj1_img" value="<?php echo $crstm_pj1_img ?>">
																								<span class="file-custom"></span>
																							</label>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																		
																		<div class="col-md-4">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																				<div class="col-md-9">
																					<a href="<?php echo($ImgPrj) ?>" target="_blank" id="linkcurrent_ImgProject">
																						<img src="<?php echo($ImgPrj_icon) ?>" border="0" id="ImgProject" name="ImgProject"  width="60" height="60">
																					</a>	
																				</div>
																			</div>
																		</div>
																		<div class="col-md-2">
																			<input type="checkbox"  id="del_pj" name="del_pj" value="1">
																			<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
																		</div>
																		
																		<div class="col-md-4">
																			<div class="form-group row">
																				<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
																				<div class="col-md-9">
																					<a href="<?php echo($ImgPrj1) ?>" target="_blank" id="linkcurrent_ImgProject1">
																						<img src="<?php echo($ImgPrj1_icon) ?>" border="0" id="ImgProject1" name="ImgProject1"  width="60" height="60">
																					</a>	
																				</div>
																			</div>
																		</div>
																		<div class="col-md-2">
																			<input type="checkbox"  id="del_pj1" name="del_pj1" value="1">
																			<label class="font-weight-bold" for="cus_conf_yes">ลบรูปภาพ</label>
																		</div>
																	</div>
																</div>
																<div class="form-group row mt-n3"> 
																	<div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1 btn-group-sm">
																	<?php if($iscan_editing) { ?>
																			<button type="button" id="btnsave" name="btnsave" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1" onclick="Swalappform('<?php echo encrypt($crstm_step_code, $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-check-square-o"></i> Save</button>
																		<? } ?>
																	<?php if($reviewer_code != "10") { ?>
																		<?php if($crstm_sd_reson !="" && $iscan_editing) { ?>
																			<button type="button" id="btnsubumit" name="btnsubumit" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" onclick="Swalappformsend('<?php echo $crstm_cus_name; ?>','<?php echo encrypt($reviewer_code, $key);?>','<?php echo $crstm_nbr; ?>')"><i class="fa fa-envelope-o"></i> Submit</button>
																		<? } ?>
																	<? } else { ?>	
																		<?php if($crstm_sd_reson !="" && $iscan_editing) { ?>
																			<button type="button" id="btnsubumit" name="btnsubumit" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="Swalappformsend_cr('<?php echo encrypt($reviewer_code, $key);?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit10</button>
																			<!--<button type="button" id="btnsubumit" name="btnsubumit" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" onclick="Swalappformsend_cr('<?php echo $reviewer_code; ?>','<?php echo $crstm_nbr; ?>','<?php echo $crstm_cus_name; ?>')"><i class="fa fa-envelope-o"></i> Submit10</button>-->
																		<? } ?>
																	<? } ?>	
																	
																		<button type="reset" class="btn btn-outline-danger btn-min-width btn-glow mr-1 mb-1" onclick="document.location.href='../crctrlbof/crctrlall.php'"><i class="ft-x"></i> Cancel</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!-- End Card -->
												</div>
											</form>
										</div>
									</div>
								</div>
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
		
		<form name="frm_del_cc" id="frm_del_cc" >
			<input type="hidden" name="action" value="editdel_cc">
			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
			<input type="hidden" name="crstm_nbr" value="<?php echo $crstm_nbr; ?>">
			<input type="hidden" name="pg" value="<?php echo $pg ?>">
		</form>
		<form name="frm_del_addcc" id="frm_del_addcc" >
			<input type="hidden" name="action" value="del_edit_cc">
			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
			<input type="hidden" name="crstm_nbr" value="<?php echo $crstm_nbr; ?>">
			<input type="hidden" name="row_seq" value="<?php echo $row_seq; ?>">
			<input type="hidden" name="pg" value="<?php echo $pg ?>">
		</form>
		<form name="frm_risk" id="frm_risk" >
			<input type="hidden" name="action" value="del_cc">
			<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
			<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
			<input type="hidden" name="cr_cust_code" value="">
		</form>
		<!-- BEGIN: Footer-->
		<footer class="footer footer-static footer-light navbar-border">
			<p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 <a class="text-bold-800 grey darken-2" href="https://1.envato.market/pixinvent_portfolio" target="_blank">PIXINVENT </a></span><span class="float-md-right d-none d-lg-block">Power by IT Business Solution Team <i class="feather icon-heart pink"></i></span></p>
		</footer>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/formatter/formatter.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/toastr.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-inputmask.min.js"></script>
		<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/forms/extended/form-maxlength.min.js"></script>
		<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
		<script type="text/javascript">
			//$(document).ready(function(){
				// $("#frm_crctrl_edit").find("#btnsubumit").hide();
			//});
			load_risk_data();

			$(document).on("click", "#btnsave", function () {
				$("#frm_crctrl_edit").find("#btnsubumit").show();
			});
			$(document).on('click', '#btccdel', function(e) {
				var tot_ord = $(this).data('tot_ord');
				var row_seq = $(this).data('row_seq');
				var pg = $(this).data('pg');
				Swal.fire({
					title: "Are you sure?",
					html: "คุณต้องการลบยอดวงเงิน " +<?echo tot_ord ; ?> + "  นี้ใช่หรือไม่ !!!! ",
					type: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, delete it!",
					confirmButtonClass: "btn btn-primary",
					cancelButtonClass: "btn btn-danger ml-1",
					buttonsStyling: false,
					showLoaderOnConfirm: true,
					preConfirm: function() {
						return new Promise(function(resolve) {
							document.frm_del_addcc.row_seq.value = row_seq;
							document.frm_del_addcc.pg.value = pg;
							$.ajax({
								beforeSend: function() {
									//$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									//$("#requestOverlay").show();/*Show overlay*/
								},
								type: 'POST',
								url: '../serverside/ccpost.php',
								data: $('#frm_del_addcc').serialize(),
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
										$(location).attr('href', 'crctrlall.php?crmnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
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
			function editdel_cc(crstm_nbr) {
				// html: "คุณต้องการลบยอดวงเงิน " +<?echo tot_ord ; ?> + "  นี้ใช่หรือไม่ !!!! ",
				//alert(crstm_nbr);
				if(confirm('ท่านต้องการลบรายการนี้  ใช่หรือไม่ ?')) {	
					document.frm_del_cc.crstm_nbr.value = crstm_nbr;
					$.ajax({
						beforeSend: function () {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>');
							$("#requestOverlay").show();
						},
						type: 'POST',
						url: '../serverside/crctrlpost.php',
						data: $('#frm_del_cc').serialize(),
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
								$(location).attr('href', 'crctrledit.php?crnumber='+json.nb+'&nb='+json.pg+'&pg='+json.pg);
							}
						},
						complete: function () {
							$("#requestOverlay").remove();
						}
					});
				}
			}
			
			function Swalappform(chk_action,crstm_nbr,cus_name) {
				//alert(formid+"--"+chk_action+"--"+crstm_nbr+"--"+cus_name);
				
				Swal.fire({
					html: "คุณต้องการบันทึกข้อมูล  <br>เอกสารเลขที่   " + crstm_nbr + "<br> ลูกค้า  : " + cus_name + " นี้ใช่หรือไม่ !!!! ",
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
							var formObj = $('#frm_crctrl_edit')[0];
							var formData = new FormData(formObj);
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								url: '../serverside/crctrlpost.php?step_code=' +chk_action  ,
								//data: $('#' + formid).serialize(),
								data: formData,
								timeout: 50000,
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
											title: "Save successfully.",
											showConfirmButton: false,
											timer: 500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										//location.reload(true);
										clearloadresult();
										$(location).attr('href', 'crctrledit.php?crnumber='+json.nb+'&pg='+json.pg+ '&current_tab=30')
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
			function Swalappformsend(cus_name,chk_action,crstm_nbr) {
				//alert(cus_name+"--"+chk_action+"--"+crstm_nbr);
				Swal.fire({
					//title: "Are you sure?",
					html: "คุณได้ทำการแก้ไข และ บันทึกข้อมูลเรียบร้อยแล้ว ก่อนส่งข้อมูล  <br>ลูกค้า   " + cus_name + " ไปให้ผู้ตรวจสอบ ใช่หรือไม่ !!!! " , 
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
							var formObj = $('#frm_crctrl_edit')[0];
							var formData = new FormData(formObj);
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								//url: '../serverside/crctrlsubmitpost.php?step_code=' +chk_action  ,
								url: '../serverside/crctrlsubmitpost_pdf_rev1.php?step_code=' +chk_action  ,
								//data: $('#' + formid).serialize(),
								data: formData,
								timeout: 50000,
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
											title: "Submit successfully.",
											showConfirmButton: false,
											timer: 500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										//location.reload(true);
										clearloadresult();
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
			function Swalappformsend_cr(chk_action,crstm_nbr,cus_name) {
				
				//alert(crstm_nbr+"--"+chk_action+"--"+cus_name);
				Swal.fire({
					//title: "Are you sure?",
					html: "คุณได้ทำการแก้ไข และ บันทึกข้อมูลเรียบร้อยแล้ว ก่อนส่งข้อมูล  <br>ลูกค้า   " + cus_name + " ไปให้แผนกสินเชื่ออนุมัติ ใช่หรือไม่ !!!! " , 
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
							var formObj = $('#frm_crctrl_edit')[0];
							var formData = new FormData(formObj);
							$.ajax({
								beforeSend: function() {
									$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
									$("#requestOverlay").show(); /*Show overlay*/
								},
								type: 'POST',
								url: '../serverside/crctrlpost.php?step_code=' +chk_action  ,
								//data: $('#' + formid).serialize(),
								data: formData,
								timeout: 50000,
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
											title: "Submit successfully.",
											showConfirmButton: false,
											timer: 500,
											confirmButtonClass: "btn btn-primary",
											buttonsStyling: false
										});
										//location.reload(true);
										clearloadresult();
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
				}
				
			});
			
			$('#crstm_reviewer111').typeahead({	
				displayText: function(item) {
					return item.emp_th_firstname+" "+item.emp_th_lastname+" "+item.emp_email_bus;
				}, 
				source: function (query, process) {
					jQuery.ajax({
						url: "../_help/getemp_detail.php",
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
					$("#crstm_reviewer").val(item.emp_email_bus);
					$("#reviewer_name").val(item.emp_prefix_th_name+" "+item.emp_th_firstname+" "+item.emp_th_lastname+"/"+item.emp_th_div+"/"+item.emp_th_dept+"/"+item.emp_th_sec);
					//$("#reviewer_name").$emp_prefix_th_name ." " . $emp_th_firstname ." ". $emp_th_lastname ."/". $emp_th_div ."/". $emp_th_dept ."/". $emp_th_sec ;
				}
				
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
								//$(location).attr('href', '../crctrlbof/crctrledit.php?crstm_nbr=' + json.nb + '&nb1=' + json.nb1 + '&nb2=' + json.nb2)
								$(location).attr('href', '../crctrlbof/crctrledit.php?crnumber=' + json.nb + '&pg=' + json.pg)
							}
						},
						
						complete: function() {
							$("#requestOverlay").remove(); /*Remove overlay*/
						}
					});
				});
			}
			$("#cc_amt1").on("change", function () {
				
				var cus_acc_group = $("#cus_acc_group").val();
				var cc_amt1 = $(this).val();
				var acc_tot = $("#acc_tot").val();
				var result_cc_amt1 = parseFloat(cc_amt1.replace(/\$|,/g, ''))
				var result_acc_tot = parseFloat(acc_tot.replace(/\$|,/g, ''))
				
				$("#crstm_email_app1").val("");
				$("#crstm_email_app2").val("");
				$("#crstm_email_app3").val("");
				$("#crstm_reviewer2").val("");
				document.getElementById("app1_name").innerHTML = "";
				document.getElementById("app2_name").innerHTML = "";
				document.getElementById("app3_name").innerHTML = "";
				document.getElementById("reviewer_name2").innerHTML = "";
				if(result_acc_tot !=0) {
					var sum_amt_acc = parseInt(result_cc_amt1)  + parseInt(result_acc_tot) ;
					}else {
					var sum_amt_acc = result_cc_amt1 ;
				}
				
				acc_tot_app = sum_amt_acc;

				if (cus_acc_group == "ZC01" || cus_acc_group == "ZC07") {
					if (acc_tot_app  <= 700000) { 
						crstm_approve = 'ผส. อนุมัติ';	
						$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
						document.getElementById('crstm_email_app1').readOnly = false; //คีย์ข้อมูลได้
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'auto';
					    document.getElementById("crstm_scgc").checked = false; // tiles
						document.getElementById("crstm_scgc1").checked = false; // geoluxe
						$('.displayApp2').hide();  
						$('.displayApp3').hide();  
						$('.nonCol').hide();
					}
					else if (acc_tot_app >= 700001 && acc_tot_app <= 2000000) { 
						crstm_approve = 'ผฝ. อนุมัติ';
						$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
						document.getElementById('crstm_email_app1').readOnly = false;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'auto';
						document.getElementById("crstm_scgc").checked = false; // tiles
						document.getElementById("crstm_scgc1").checked = false; // geoluxe
						$('.displayApp2').hide(); 
						$('.displayApp3').hide();   
						$('.nonCol').hide();
					}
					else if (acc_tot_app >= 2000001 && acc_tot_app <= 5000000) { 
						crstm_approve = 'CO. อนุมัติ';
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true; //คีย์ข้อมูลไม่ได้
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						document.getElementById("crstm_scgc").checked = false; // tiles
						document.getElementById("crstm_scgc1").checked = false; // geoluxe
						$('.displayApp2').hide();
						$('.displayApp3').hide();    
						$('.nonCol').hide();
					}
					else if (acc_tot_app >= 5000001 && acc_tot_app <= 7000000) { 
						crstm_approve = 'กจก. อนุมัติ';
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						document.getElementById("crstm_scgc").checked = false; // tiles
						document.getElementById("crstm_scgc1").checked = false; // geoluxe
						$('.displayApp2').hide();
						$('.displayApp3').hide();    
						$('.nonCol').hide();
					}
					else if (acc_tot_app >= 7000001 && acc_tot_app <= 10000000) { 
						crstm_approve = 'คณะกรรมการสินเชื่ออนุมัติ';
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true;
						document.getElementById('crstm_email_app2').readOnly = true;
						document.getElementById('crstm_email_app3').readOnly = true;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						document.getElementById("crstm_scgc").checked = false; // tiles
						document.getElementById("crstm_scgc1").checked = false; // geoluxe
						$('.displayApp2').show();  
						$('.displayApp3').show();  
						$('.nonCol').show();
					}
					else { 
						crstm_approve = 'คณะกรรมการบริหารอนุมัติ';	
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true;
						document.getElementById('crstm_email_app2').readOnly = true;
						document.getElementById('crstm_email_app3').readOnly = true;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						document.getElementById("crstm_scgc").checked = false; // tiles
						document.getElementById("crstm_scgc1").checked = false; // geoluxe
						$('.displayApp2').show(); 
						$('.displayApp3').show();   
						$('.nonCol').show();
					} 
				} 
				if (cus_acc_group == "DREP") {
					if (acc_tot_app >= 1 && acc_tot_app  <= 500000) { 
						crstm_approve = 'ผผ. อนุมัติ';	
						$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
						document.getElementById('crstm_email_app1').readOnly = false; //คีย์ข้อมูลได้
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'auto';
						$('.displayApp2').hide();  
						$('.displayApp3').hide();  
						$('.nonCol').hide();
					}else if (acc_tot_app >= 500001 && acc_tot_app <= 3000000) { 
						crstm_approve = 'ผส. อนุมัติ';
						$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
						document.getElementById('crstm_email_app1').readOnly = false; //คีย์ข้อมูลได้
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'auto';
						$('.displayApp2').hide(); 
						$('.displayApp3').hide();   
						$('.nonCol').hide();
					}else if (acc_tot_app >= 3000001 && acc_tot_app <= 13000000) { 
						crstm_approve = 'ผฝ. อนุมัติ';
						$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
						document.getElementById('crstm_email_app1').readOnly = false;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'auto';
						$('.displayApp2').hide();  
						$('.displayApp3').hide();  
						$('.nonCol').hide();
					}else if (acc_tot_app >= 13000001 && acc_tot_app <= 25000000) { 
						crstm_approve = 'CO. อนุมัติ';
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true; //คีย์ข้อมูลไม่ได้
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						$('.displayApp2').hide();  
						$('.displayApp3').hide();  
						$('.nonCol').hide();
					}else if (acc_tot_app >= 25000001 && acc_tot_app <= 50000000) { 
						crstm_approve = 'กจก. อนุมัติ';
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						$('.displayApp2').hide();
						$('.displayApp3').hide();    
						$('.nonCol').hide();
					}else { 
						crstm_approve = 'คณะกรรมการบริหารอนุมัติ';	
						$error_txt = "";
						document.getElementById('crstm_email_app1').readOnly = true;
						document.getElementById('crstm_email_app2').readOnly = true;
						document.getElementById('crstm_email_app3').readOnly = true;
						document.getElementById('error_txt').innerHTML = $error_txt; 
						document.getElementById('pointer1').style.pointerEvents = 'none';
						$('.displayApp2').show();  
						$('.displayApp3').show(); 
						$('.nonCol').hide();
					} 
				}
				
				// check_flag = "<?php echo encrypt('1', $key);?>";
				// $_approve = document.getElementById("crstm_approve").value;
				
				// if (crstm_approve == "") {
					// document.getElementById("app1_name").innerHTML = "";
					// return;
				// }	
				// const xhttp = new XMLHttpRequest();
				// xhttp.onload = function() {
					// let email_app1="";
					// let email_app2="";
					// const myObj = JSON.parse(this.responseText);
					// document.getElementById("app1_name").innerHTML = myObj.app1_name;
					// document.getElementById("app2_name").innerHTML = myObj.app2_name;
					// email_app1 = document.getElementById("crstm_email_app1").innerHTML = myObj.email1;
					// email_app2 = document.getElementById("crstm_email_app2").innerHTML = myObj.email2;
					// $("#crstm_email_app1").val(email_app1);
					// $("#crstm_email_app2").val(email_app2);
					// $("#error_txt").val($error_txt);
					// console.log(this.responseText);
				// }	
				// xhttp.open("GET", "../serverside/authorpost.php?q="+$_approve+'&check_flag='+ check_flag +'',true);
				// xhttp.send();   
				$("#crstm_approve").val(crstm_approve);
				sum_amt_acc = formatCurrency(sum_amt_acc);
				$("#sum_acc_tot").val(sum_amt_acc);
				
				document.getElementById("cusold2_conf_yes").disabled = true;
			});
			
			function load_risk_data() {
				var cr_cust_code = document.getElementById("cr_cust_code").value;
				document.frm_risk.cr_cust_code.value = cr_cust_code;
				$.ajax({
				url: "../serverside/upload_risk_list.php",// json datasource
				type: "post",
				data: $('#frm_risk').serialize(),
				
				success: function(data) {
					//console.log(data);
					$('#image_table').html(data);
				}
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
			$('#edit1_beg_date').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			$('#edit1_end_date').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			
			$(document).on("click", "#btn-ret-value", function(e) {
				e.preventDefault();
				var code = $(this).data('send_code');
				var name = $(this).data('send_name');
				var id_field_code = $(this).data('rec_code_id');
				var id_field_name = $(this).data('rec_name_id');
				var id_field_name_type = $(this).data('rec_name_type');
				
				$(".dis_reviewer_name").show(); 
				$('#but_help_close').trigger( "click" );
				$('#'+id_field_code).val(code);
				if (id_field_name_type == "value") {
					$('#'+id_field_name).val(name);
					} else {
					$('#'+id_field_name).html(name);
				}
				if (id_field_code.startsWith("sppart")) { 
					if (code == "DUMMY") {
						document.getElementById(id_field_name).readOnly = false;
						document.getElementById(id_field_name).value = "";
						document.getElementById(id_field_name).focus();
					}
					else {
						document.getElementById(id_field_name).readOnly = true;
					}
				}
			});
			
			$("#frm_crctrl_edit").on("click", "#buthelp", function () {
				let input0 = {};
				let data = {};
				var id_field_code = $(this).data('id_field_code');
				var id_field_name = $(this).data('id_field_name');
				var id_field_name_type = $(this).data('id_field_name_type')
				var modal_class = $(this).data('modal_class');
				var modal_title = $(this).data('modal_title');
				var modal_src = $(this).data('modal_src');
				var modal_col_name = $(this).data('modal_col_name'); 
				var modal_col_data1 = $(this).data('modal_col_data1');
				var modal_col_data2 = $(this).data('modal_col_data2');
				var modal_col_data3 = $(this).data('modal_col_data3');
				var modal_col_data4 = $(this).data('modal_col_data4');
				var modal_col_data3_vis = $(this).data('modal_col_data3_vis');
				var modal_col_data4_vis = $(this).data('modal_col_data4_vis');
				var modal_ret_data1 = $(this).data('modal_ret_data1');
				var modal_ret_data2 = $(this).data('modal_ret_data2');
				var modal_page_size = $(this).data('modal_page_size');
				var modal_page_type = $(this).data('modal_page_type');
				if (modal_page_size === undefined || modal_page_size == "") { modal_page_size = 10; }
				if (modal_page_type === undefined || modal_page_type == "") { modal_page_type = "simple"; }
				if (id_field_name_type === undefined || id_field_name_type == "") { id_field_name_type = "html"; }
				//Column Setting
				var cols = [{"data": modal_col_data1}, {"data": modal_col_data2} ];
				if (modal_col_data3 !== undefined && modal_col_data3 != "") { cols.push({"data": modal_col_data3,"visible": modal_col_data3_vis}); }
				if (modal_col_data4 !== undefined && modal_col_data4 != "") { cols.push({"data": modal_col_data4,"visible": modal_col_data4_vis}); }
				//
				input0.field_code = $("#"+id_field_code).val();
				//input0.login_plant = "<?php echo $login_plant;?>";
				
				$.ajax({
					url: modal_src,
					type: "POST",
					dataType: 'json',
					data: {param0: JSON.stringify(input0)},
					beforeSend: function () {
						$(".loading").fadeIn();
						$("#div_help").find("#div_help_size").attr("class", modal_class);
						$("#div_help").find("#help_title").html(modal_title);
						$("#div_help").find("#head0").html(modal_col_name);
						$("#div_help").modal({backdrop: 'static', keyboard: false});
						$('#table-help').DataTable().clear().destroy();
					},
					success: function (res) {
						//if (res.success) {
						$("#table-help").dataTable().fnDestroy();
						$("#table-help").dataTable({
							"oSearch": {
								"sSearch": input0.field_code
							},
							"dom": '<lf<t>ip>',
							"deferRender" : true,
							//"aaData" : res.data,
							"aaData" : res,
							"cache": false,
							"columns": cols,
							"columnDefs": [
							{"className": "dt-left","targets": [0,1]},
							{"width": "50px", "targets": 0},
							{"width": "100px", "targets":1},
							{
								"render": function(data, type, row) {
									return '<a href="javascript:void(0)" id="btn-ret-value"'+
									'" data-send_code="'+row[modal_ret_data1]+
									'" data-send_name="'+row[modal_ret_data2]+
									'" data-rec_code_id="'+id_field_code+
									'" data-rec_name_id="'+id_field_name+
									'" data-rec_name_type="'+id_field_name_type+'">'+data+'</a>';
								},
								"targets": 0
							},
							],
							"pagingType": modal_page_type,
							"pageLength": modal_page_size,
							"bPaginate": true,
							"bLengthChange": false,
							"bFilter": true,
							"bAutoWidth": false,
							"ordering": true,
							
						});
						$("#content-help").fadeIn();
						//}
					},
					complete: function () {
						$(".loading").fadeOut();
					},
					error: function (res) {
						alert('error');
					}
				});
			});
			
			function formatCurrency(number) {
				number = parseFloat(number);
				return number.toFixed(0).replace(/./g, function(c, i, a) {
					return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
				});
			}	
			
			$('input[type="radio"]').click(function() {
				if($(this).attr('id') == 'cus_conf_yes') {
					$('.cus_display').show();   
					$("#cusold_conf_yes").prop("required", true);
					
					//// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
					$('.term_display').hide(); 	
					$("#terms_paymnt").prop("required", false);
					
					$("#terms_paymnt").val(" ");
					//$("#term_desc_add").val("");
					/////
					//// ซ่อนข้อมูลขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก
					$('.chg_term_display').hide(); 	
					$("#terms_paymnt1").prop("required", false);
					
					$("#terms_paymnt1").val(" ");
					//$("#term_desc").val("");
					/////
				}
				else if($(this).attr('id') == 'cus_conf_no') {
					$('.cus_display').hide(); 	
					$("#cusold_conf_yes").prop("required", false);   //ปรับเพิ่มวงเงิน
					$("#cusold_conf_yes").prop("checked", false);
					
					$("#cusold1_conf_yes").prop("required", false);  //ปรับลดวงเงิน
					$("#cusold1_conf_yes").prop("checked", false);
					
					$("#cusold2_conf_yes").prop("required", false);   //ต่ออายุ
					$("#cusold2_conf_yes").prop("checked", false);
					
					$("#term_conf_yes").prop("required", false);   //เงื่อนไขการชำระเงินเดิม
					$("#term_conf_yes").prop("checked", false);
					
					$("#chg_term_conf_yes").prop("required", false);    //เปลี่ยนเงื่อนไขการชำระเงินใหม่จาก
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
				}
				else if($(this).attr('id') == 'term_conf_yes') {
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
				}
				else if($(this).attr('id') == 'chg_term_conf_yes') {
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
				}
				else if($(this).attr('id') == 'cusold_conf_yes') {
					$('.cusold_display').show();   
					$("#div_frm_cc_edit .modal-body #cus_conf_yes").val(1);
					$("#div_frm_cc_edit .modal-body #cusold_conf_yes").val("C1");
					//$("#div_frm_cc_edit").modal("show");
					
					$("#txt_cc").prop("required", true);
					$("#txt_cc").val("เสนอขอปรับเพิ่มวงเงิน");
					$("#beg_date").val("");
					$("#end_date").val("");
					$("#cc_amt").val("");
					
					//// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
					$('.term_display').hide(); 	
					$("#terms_paymnt").prop("required", false);
					
					$("#terms_paymnt").val(" ");
					//$("#term_desc_add").val("");
					/////
				}
				else if($(this).attr('id') == 'cusold1_conf_yes') {
					$('.cusold_display').show();   
					$("#div_frm_cc_edit .modal-body #cus_conf_yes").val(1);
					$("#div_frm_cc_edit .modal-body #cusold_conf_yes").val("C2");
					$("#div_frm_cc_edit").modal("show");
					
					$("#txt_cc").prop("required", true);
					$("#txt_cc").val("เสนอขอปรับลดวงเงิน");
					$("#beg_date").val("");
					$("#end_date").val("");
					$("#cc_amt").val("");
					
					//// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
					$('.term_display').hide(); 	
					$("#terms_paymnt").prop("required", false);
					
					$("#terms_paymnt").val(" ");
					//$("#term_desc_add").val("");
					/////
				}
				else if($(this).attr('id') == 'cusold2_conf_yes') {
					$('.cusold_display').show(); 
					$("#div_frm_cc_edit .modal-body #cus_conf_yes").val(1);
					$("#div_frm_cc_edit .modal-body #cusold_conf_yes").val("C3");
					//$("#div_frm_cc_edit").modal("show");
					
					$("#txt_cc").prop("required", true);
					$("#txt_cc").val("เสนอขอต่ออายุวงเงิน");
					$("#beg_date").val("");
					$("#end_date").val("");
					$("#cc_amt").val("");
					
					//// ซ่อนข้อมูลเงื่อนไขการชำระเงินเดิม
					$('.term_display').hide(); 	
					$("#terms_paymnt").prop("required", false);
					
					$("#terms_paymnt").val(" ");
					//$("#term_desc_add").val("");
					/////
				}
			});
			
			$(document).on("click", ".open-EditCCDialog", function() {
				var crstm_nbr = $(this).data('crstm_nbr');
				var beg_date = $(this).data('begdte');
				var end_date = $(this).data('enddte');
				var cc_amt = $(this).data('cc_amt');
				var txt_ref = $(this).data('txt_ref');
				var txt_ccr = $(this).data('txt_ccr');
				var row_seq = $(this).data('row_seq');
				//alert(row_seq);
				
				$("#div_frm_cc_edit1 .modal-body #crstm_nbr").val(crstm_nbr);
				$("#div_frm_cc_edit1 .modal-body #edit1_beg_date").val(beg_date);
				$("#div_frm_cc_edit1 .modal-body #edit1_end_date").val(end_date);
				$("#div_frm_cc_edit1 .modal-body #cc_amt").val(cc_amt);
				$("#div_frm_cc_edit1 .modal-body #txt_ref").val(txt_ref);
				$("#div_frm_cc_edit1 .modal-body #txt_ccr").val(txt_ccr);
				$("#div_frm_cc_edit1 .modal-body #row_seq").val(row_seq);
			});
			
			$("#crstm_noreviewer").change(function() {
				if (this.checked) {
					$("#crstm_reviewer").val("");
					$("#reviewer_name").val("");
					$(".dis_reviewer_name").hide();   
					document.getElementById("crstm_reviewer").readOnly = true;  // disabled textbox ในส่วนอีเมลผู้ตรวจสอบ
				}else {
					document.getElementById("crstm_reviewer").readOnly = false;
				}
			});
			$("#crstm_scgc").change(function() {
				document.getElementById("crstm_scgc").checked;
				document.getElementById("crstm_nbr").value;
				document.getElementById('crstm_reviewer2').readOnly = true;
				document.getElementById('pointer1').style.pointerEvents = 'none';
				$('.displayRev2').hide(); 
				$('.blockRev2').show();		
				
				$pointer_vie2 = 'none';
				$("#crstm_reviewer2").val("");
				reviewer_name2 = document.getElementById("reviewer_name2").innerHTML = "";
				//alert(reviewer_name2);
				$("#reviewer_name2").val(reviewer_name2);
				
				check_flag = "<?php echo encrypt('1', $key);?>";
				$_approve = document.getElementById("crstm_approve").value;
				check_form = $_approve;
				
				if (check_form == "") {
					document.getElementById("crstm_email_app1").innerHTML = "";
					return;
				}	
				const xhttp = new XMLHttpRequest();
				xhttp.onload = function() {
					let reviewer="";
					let email="";
					const myObj = JSON.parse(this.responseText);
					
					email1 = document.getElementById("crstm_email_app1").innerHTML = myObj.email1;
					$("#crstm_email_app1").val(email1);
					app1_name = document.getElementById("app1_name").innerHTML = myObj.app1_name;
					$("#app1_name").val(app1_name);
					
					if ($_approve == "คณะกรรมการสินเชื่ออนุมัติ" || $_approve == "คณะกรรมการบริหารอนุมัติ") {
						email2 = document.getElementById("crstm_email_app2").innerHTML = myObj.email2;
						$("#crstm_email_app2").val(email2);
						app2_name = document.getElementById("app2_name").innerHTML = myObj.app2_name;
						$("#app2_name").val(app2_name);
						
            email3 = document.getElementById("crstm_email_app3").innerHTML = myObj.email3;
						$("#crstm_email_app3").val(email3);
						app3_name = document.getElementById("app3_name").innerHTML = myObj.app3_name;
						$("#app3_name").val(app3_name);
						$('.displayApp3').show();  
					}
					//console.log(this.responseText);
				}	
					xhttp.open("POST", "../serverside/checkreviewer.php?q="+check_flag+"&group="+ check_form +" ",false);
					xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
					xhttp.setRequestHeader("Pragma", "no-cache");
					xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");		
					//url: '../serverside/crctrl_cr_post.php?step_code='+chk_action+'&formid='+formid+''  ,
					xhttp.send("q="+check_flag+'&csrf_securecode=<?php echo $csrf_securecode?>&csrf_token=<?php echo md5($csrf_token)?>');	
					//xhttp.send();   
			});
			$("#crstm_scgc1").change(function() {
				document.getElementById("crstm_scgc1").checked;
				document.getElementById("crstm_reviewer2").value;
				document.getElementById('crstm_reviewer2').readOnly = false;
				document.getElementById('pointer1').style.pointerEvents = 'auto';
			
				$pointer_vie2 = "auto"; 			
				
				check_flag = "<?php echo encrypt('2', $key);?>";
				$_approve = document.getElementById("crstm_approve").value;
				check_form = $_approve;
				
				if (check_form == "") {
					document.getElementById("crstm_email_app1").innerHTML = "";
					return;
				}	
				const xhttp = new XMLHttpRequest();
				xhttp.onload = function() {
					let reviewer="";
					let email="";
					const myObj = JSON.parse(this.responseText);
					reviewer = document.getElementById("reviewer_name2").innerHTML = myObj.reviewer;
					email = document.getElementById("crstm_reviewer2").innerHTML = myObj.email;
					$("#crstm_reviewer2").val(email);
					$("#reviewer_name2").val(reviewer);
					
					email1 = document.getElementById("crstm_email_app1").innerHTML = myObj.email1;
					$("#crstm_email_app1").val(email1);
					app1_name = document.getElementById("app1_name").innerHTML = myObj.app1_name;
					$("#app1_name").val(app1_name);
					
					
					if ($_approve == "คณะกรรมการสินเชื่ออนุมัติ" || $_approve == "คณะกรรมการบริหารอนุมัติ") {
						email2 = document.getElementById("crstm_email_app2").innerHTML = myObj.email2;
						$("#crstm_email_app2").val(email2);
						app2_name = document.getElementById("app2_name").innerHTML = myObj.app2_name;
						$("#app2_name").val(app2_name);
						document.getElementById('crstm_reviewer2').readOnly = true;  // ผู้พิจารณา 2 อ่านได้อย่างเดียว แก้ไขไม่ได้
						$('.displayRev2').show(); 									
						$('.blockRev2').hide();		
						
						email3 = document.getElementById("crstm_email_app3").innerHTML = myObj.email3;
						$("#crstm_email_app3").val(email3);
						app3_name = document.getElementById("app3_name").innerHTML = myObj.app3_name;
						$("#app3_name").val(app3_name);
						$('.displayApp3').show();  
					} else {
						document.getElementById('crstm_reviewer2').readOnly = true;
					}
					//console.log(this.responseText);
				}	
				//xhttp.open("GET", "../serverside/checkreviewer.php?q="+check_flag+'&group='+ check_form +'',true);
				//url: '../serverside/crctrlapppost.php?step_code='+chk_action+'&formid='+formid+''  ,
					xhttp.open("POST", "../serverside/checkreviewer.php?q="+check_flag+"&group="+ check_form +" ",false);
					xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
					xhttp.setRequestHeader("Pragma", "no-cache");
					xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");		
				xhttp.send();   
			});
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
			function validate(){
				let valid = false;
				let x = document.frm_crctrl_edit.crstm_scgc;    // document.form.name == form ==> frm_crctrl_add , name ==> isscgc
				for(let i=0; i<x.length; i++) {
					if(x[i].checked){
						valid = true;
						break;
					}
				}
				if(!valid){
					alert("กรุณาเลือกลูกค้า Tiles หรือ Geoluxe");
					return false;
				}
			}
			
			//TYPE AHEAD
			$('.typeahead').typeahead({	
				
				displayText: function(item) {
					var disp_col1 = this.$element.attr('data-disp_col1');
					var disp_col2 = this.$element.attr('data-disp_col2');
					return item[disp_col1]+' '+item[disp_col2];
				}, 
				source: function (query, process) {
					var typeahead_src = this.$element.attr('data-typeahead_src')
					$.ajax({
						url: typeahead_src,
						data: {query:query},
						dataType: "json",
						type: "POST",
						success: function (data) {
							process(data)
						}
					})
				},				
				items: "all",
				afterSelect: function(item) {
					var ret_field_01 = this.$element.attr('data-ret_field_01')
					var ret_value_01 = this.$element.attr('data-ret_value_01')
					var ret_type_01 = this.$element.attr('data-ret_type_01')
					var ret_field_02 = this.$element.attr('data-ret_field_02')
					var ret_value_02 = this.$element.attr('data-ret_value_02')
					var ret_type_02 = this.$element.attr('data-ret_type_02')
					if (ret_type_01 == "val") {
						$('#'+ret_field_01).val(item[ret_value_01]);
						} else {
						$('#'+ret_field_01).html(item[ret_value_01]);
					}
					if (ret_type_02 == "val") {
						$('#'+ret_field_02).val(item[ret_value_02]);
						} else {
						$('#'+ret_field_02).html(item[ret_value_02]);
					}
				}
			});

			function CheckValidFile_header_attach(nFle) {
				var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png",".xls", ".xlsx", ".pdf", ".doc", ".docx", ".ppt", ".pptx"];    
				switch (nFle) {
					case "load_reson_img":
						var fileVal = document.getElementById('load_reson_img').value;
						break;
					case "load_pj_img":
						var fileVal = document.getElementById('load_pj_img').value;
						break;
					case "load_pj1_img":
						var fileVal = document.getElementById('load_pj1_img').value;
						break;
					case "load_cr1_img":
						var fileVal = document.getElementById('load_cr1_img').value;
						break;		
					case "load_dbd_img":
						var fileVal = document.getElementById('load_dbd_img').value;
						break;	
					case "load_dbd1_img":
						var fileVal = document.getElementById('load_dbd1_img').value;
						break;	
					case "load_cr2_img":
						var fileVal = document.getElementById('load_cr2_img').value;
						break;			
				}
				//var fileVal = document.getElementById('load_reson_img').value;
				var fileExt = fileVal.substring(fileVal.lastIndexOf('.'));
				if (fileVal == "") {
					Swal.fire({
						title: "Warning!",
						//html: json.e,
						html: "<b>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" + _validFileExtensions.join(", ") + "</span></b>",
						 icon: 'warning',
						confirmButtonClass: "btn btn-danger",
						buttonsStyling: false
					});
					return false;
				} 
				else if (fileVal.length > 0) {
					//Not Allow : check for configuration files like web.config
					//Not Allow : Check for files without a filename like .htaccess
					var ext = fileVal.split('.');
					if(ext[0] == "") {
						Swal.fire({
							title: "Warning!",
							//html: json.e,
							html: "<b>คุณกำลังพยายามอัพโหลดไฟล์ ที่จะส่งผลต่อการตั้งค่าของเว็บเซิฟเวอร์อยู่หรือไม่ จงหลีกเลี่ยง<br>เฉพาะไฟล์ที่มีนามสกุล <span class='text-danger'>" + _validFileExtensions.join(", ") + "</span> เท่านั้น </b>",
							 icon: 'warning',
							customClass: {
								confirmButton: "btn btn-danger"
							},
							buttonsStyling: false
						});
						return false;
					}
					else if(fileExt == ".config" || fileExt == ".exe" || fileExt == ".db" || fileExt == ".dll"  ) {
						Swal.fire({
							title: "Warning!",
							//html: json.e,
							html: "<b>คุณกำลังพยายามอัพโหลดไฟล์ ที่จะส่งผลต่อการตั้งค่าของเว็บอยู่หรือไม่ จงหลีกเลี่ยง <br>โปรดอัพโหลดเฉพาะไฟล์ที่มีนามสกุล <span class='text-danger'>" + _validFileExtensions.join(", ") + "</span> เท่านั้น </b>",
							 icon: 'warning',
							customClass: {
								confirmButton: "btn btn-danger"
							},
							buttonsStyling: false
						});
						return false;
					}
					else if(ext.length == 1) {
						Swal.fire({
							title: "Warning!",
							html: "<b>ไม่พบนามสกุลไฟล์ที่ต้องการอัพโหลด <br>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" + _validFileExtensions.join(", ") + "</span></b>",
							 icon: 'warning',
							customClass: {
								confirmButton: "btn btn-warning"
							},
							buttonsStyling: false
						});
						return false;
					}
					else if(ext.length > 2) {
						// console.log("found");
						// alert(ext[1]); // first extension
						// alert(ext[2]); // second extension
						Swal.fire({
							title: "Warning!",
							html: "<b>กรุณาตั้งชื่อไฟล์โดยไม่มีเครื่องหมาย (.) ในชื่อไฟล์ <br>นอกจากนามสกุลไฟล์เท่านั้น เพื่อป้องกันความผิดพลาด<br>และกรุณาอัพโหลดไฟล์ <br> เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>"+ _validFileExtensions.join(", ") + "</span></b>",
							 icon: 'warning',
							customClass: {
								confirmButton: "btn btn-warning"
							},
							buttonsStyling: false
						});
						return false;
					} else {			
						var blnValid = false;
						for (var j = 0; j < _validFileExtensions.length; j++) {
							var sCurExtension = _validFileExtensions[j];
							if (fileVal.substr(fileVal.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
								blnValid = true;
								return true;
							}
						}
								
						if (!blnValid) {
							//alert("Sorry, " + fileVal + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
							Swal.fire({
								title: "Warning!",
								html: "<b>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>"+ _validFileExtensions.join(", ") + "</span></b>",
								icon: 'warning',
								customClass: {
									confirmButton: "btn btn-warning"
								},
								buttonsStyling: false
							});
							return false;
						}
					}
				}
			}

			function rejectpostform(formid) {
				//alert(formid);
				$(document).ready(function() {
					$.ajax({
						beforeSend: function() {
							$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
							$("#requestOverlay").show(); /*Show overlay*/
						},
						type: 'POST',
						url: '../serverside/rejectpost.php',
						data: $('#' + formid).serialize(),
						timeout: 10000,
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
								//$(location).attr('href', '../crctrlbof/crctrledit.php?crstm_nbr=' + json.nb + '&nb1=' + json.nb1 + '&nb2=' + json.nb2)
								$(location).attr('href', '../crctrlbof/crctrlall.php?crnumber=' + json.nb + '&pg=' + json.pg)
							}
						},
						
						complete: function() {
							$("#requestOverlay").remove(); /*Remove overlay*/
						}
					});
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