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
	
	clearstatcache();
	
	clearstatcache();
	include("chkauthcr.php");
	include("chkauthcrctrl.php");
	
	//// post ccpost.php  เช็คค่า  radio 
	$cusnbr = mssql_escape($_GET['cusnbr']);
	$cusnbr = html_escape(decrypt($cusnbr, $key));
	
	$cus_conf_yes = mssql_escape($_GET['nb1']);
	$cus_conf_yes = html_escape(decrypt($cus_conf_yes, $key));
	$crstm_pj_amt = 0;
	$crstm_pj1_amt = 0;
	$crstm_scgc = "null";
	$crstm_noreviewer = false;
	$chk_block = "none";
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
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/datatables.min.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css"
    href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/vendors/css/extensions/toastr.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/app.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/core/colors/palette-gradient.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo BASE_DIR;?>/theme/app-assets/css/plugins/extensions/toastr.min.css">
</head>

<body class="vertical-layout vertical-menu 2-columns menu-collapsed  fixed-navbar" data-open="hover"
  data-menu="vertical-menu" data-col="2-columns">
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
                <li class="breadcrumb-item"><a href="../crctrlbof/crctrladd_new.php"> ใบขออนุมัติวงเงินสินเชื่อ</a></li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="content-body">
        <section class="new-project">
          <div class="row ">
            <div class="col-12">
              <div class="card">
                <div class="card-header mt-1 pt-0 pb-0">
                  <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                  <div class="heading-elements">
                    <ul class="list-inline mb-0">
                      <li><a href="../crctrlbof/crctrladd_new.php"><i class="fa fa-plus"></i>
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
                    <FORM id="frm_crctrl_add" name="frm_crctrl_add" autocomplete=OFF method="POST"
                      enctype="multipart/form-data">
                      <input type=hidden name="action" value="add_new">
                      <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
                      <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                      <input type="hidden" name="cr_cust_code" value="<?php echo($cr_cust_code) ?>">
                      <input type="hidden" name="phone_mask" value="<?php echo($phone_mask) ?>">
                      <h4 class="form-section text-info"><i class="fa fa-user"></i> ผู้ขอเสนออนุมัติ</h4>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="font-weight-bold">ชื่อ-สกุล :</label>
                            <input type="text" id="user_fullname" name="user_fullname"
                              value="<?php echo $user_fullname ?>" class="form-control input-sm font-small-3" disabled>
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
                          <label class="font-weight-bold">เบอร์โทรศัพท์:</label>
                          <class="text-muted font-weight-bold">(999) 999-9999 <font
                              class="text text-danger font-weight-bold"> ***</font>
                            <div class="form-group">
                              <input type="text" class="form-control phone-inputmask form-control input-sm font-small-3"
                                id="phone_mask" name="phone_mask" value="<?php echo $phone_mask ?>"
                                placeholder="ระบุหมายเลขโทรศัพท์" / required>
                            </div>
                        </div>
                      </div>
                      <h4 class="form-section text-info"><i class="fa fa-address-card-o"></i> ข้อมูลลูกค้า</h4>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="font-weight-bold">รหัสลูกค้า :</label>
                            <input type="text" id="crstm_cus_nbr" name="crstm_cus_nbr"
                              value="<?php echo $crstm_cus_nbr ?>" class="form-control input-sm font-small-3"
                              placeholder="Auto Generate" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="font-weight-bold">ชื่อลูกค้า : <font
                                class="text text-danger font-weight-bold"> ***</font></label>
                            <input type="text" id="crstm_cus_name" name="crstm_cus_name"
                              value="<?php echo $crstm_cus_name ?>" class="form-control input-sm font-small-3"
                              placeholder="กรุณากรอกชื่อลูกค้า" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="font-weight-bold">เลขประจำตัวผู้เสียภาษี :<font
                                class="text text-danger font-weight-bold"> ***</font></label>
                            <input type="text" id="crstm_tax_nbr3" name="crstm_tax_nbr3" value=""
                              class="form-control position-maxlength input-sm font-small-3"
                              placeholder="กรุณากรอกเลขประจำตัวผู้เสียภาษี" maxlength="13" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="font-weight-bold">ที่อยู่ :<font class="text text-danger font-weight-bold">
                                ***</font></label>
                            <input type="text" id="crstm_address" name="crstm_address" value=""
                              class="form-control input-sm font-small-3" placeholder="กรุณากรอกที่อยู่" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="font-weight-bold">ตำบล / แขวง :</label>
                            <input type="text" id="crstm_district" name="crstm_district" value=""
                              class="form-control input-sm font-small-3" placeholder="กรุณากรอกแขวง/ตำบล">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="font-weight-bold">อำเภอ / เขต :</label>
                            <input type="text" id="crstm_amphur" name="crstm_amphur" value=""
                              class="form-control input-sm font-small-3" placeholder="กรุณากรอกอำเภอ/เขต">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="font-weight-bold">จังหวัด :</label>
                            <input type="text" id="crstm_province" name="crstm_province" value=""
                              class="form-control input-sm font-small-3" placeholder="กรุณากรอกจังหวัด">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label class="font-weight-bold">รหัสไปรษณีย์ :</label>
                            <input type="text" id="crstm_zip" name="crstm_zip" value=""
                              class="form-control input-sm font-small-3" maxlength="8"
                              placeholder="กรุณากรอกรหัสไปรษณีย์">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label class="font-weight-bold">ประเทศ :</label>
                            <input type="text" id="crstm_country" name="crstm_country" value=""
                              class="form-control input-sm font-small-3" placeholder="กรุณากรอกประเทศ">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="font-weight-bold">เงื่อนไขการชำระเงิน :</label>
                            <select data-placeholder="Select a doc type ..."
                              class="form-control input-sm border-warning font-small-3 select2" id="term_desc_add"
                              name="term_desc_add">
                              <option value="" selected>--- เลือกเงื่อนไขการชำระเงินเพิ่ม ---</option>
                              <?php
																		$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
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

                      <h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 1. สำหรับหน่วยงานขาย
                        (เสนอขออนุมัติวงเงินสินเชื่อ)</h4>
                      <div class="row">
                        <div class="col-md-4">
                          <input type="radio" id="cus_new" name="cus_conf" value="0" checked>
                          <label class="font-weight-bold" for="cus_conf_no"> ลูกค้าใหม่</label>
                        </div>

                        <div class="col-md-4">
                          <input type="radio" id="cus_conf_yes" name="chk_rdo" value="C4"
                            <?php if($cus_conf_yes=='C4') { echo "checked"; }?>>
                          <label class="font-weight-bold" for="cus_conf_no"> เสนอขออนุมัติวงเงิน</label>
                        </div>
                        <!-- <div class="col-md-4">
															<input type="radio"  id="cus_conf_no" name="chk_rdo" value="C5"  >
															<label class="font-weight-bold" for="cus_conf_no"> อื่น ๆ</label>
														</div> -->
                      </div><br>

                      <div class="cc_display" style="display:none;">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-md-6 label-control font-weight-bold" for="userinput1">วันที่เริ่ม
                                :</label>
                              <div class="col-md-6">
                                <input type="text" name="beg_date_new" id="beg_date_new"
                                  class="form-control form-control input-sm font-small-3" placeholder="ระบุวันที่เริ่ม">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-md-4 label-control font-weight-bold" for="userinput1">วันที่สิ้นสุด
                                :</label>
                              <div class="col-md-6">
                                <input type="text" name="end_date_new" id="end_date_new"
                                  class="form-control form-control input-sm font-small-3" placeholder="ระบุวันสิ้นสุด">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-md-6 label-control font-weight-bold" for="userinput1">วงเงิน (บาท)
                                :</label>
                              <div class="col-md-6">
                                <input type="text" name="cc_amt1" id="cc_amt1"
                                  class="form-control form-control input-sm font-small-3"
                                  placeholder="ระบุวงเงินขออนุมัติ" style="color:black;text-align:right"
                                  onkeyup="format(this)" onchange="format(this)">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!--- เช็คอำนาจดำเนินการขออนุมัติวงเงิน --->
                      <?	if ($acc_tot_app > 0 && $acc_tot_app <= 700000) { 
														$crstm_approve = 'ผส. อนุมัติ';	
														$canedit = "";
														$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
														$pointer = "pointer";
													}
													else if ($acc_tot_app >= 700001 && $acc_tot_app <= 2000000) { 
														$crstm_approve = 'ผฝ. อนุมัติ';
														$canedit = "";
														$error_txt ="*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
														$pointer = "pointer";
													}
													else if ($acc_tot_app >= 2000001 && $acc_tot_app <= 5000000) { 
														$crstm_approve = 'CO. อนุมัติ';
														$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
														$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
														$canedit = "readOnly";
														$error_txt ="";
														$pointer = "none";
													}
													else if ($acc_tot_app >= 5000001 && $acc_tot_app <= 7000000) { 
														$crstm_approve = 'กจก. อนุมัติ';
														$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
														$canedit = "readOnly";
														$error_txt ="";
														$pointer = "none";
													}
													else if ($acc_tot_app >= 7000001 && $acc_tot_app <= 10000000) { 
														$crstm_approve = 'คณะกรรมการสินเชื่ออนุมัติ';
														$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
														$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
														
														$crstm_email_app2 = findsqlvallast("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
														$app2_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app2,$conn);	
														$canedit = "readOnly";
														$error_txt ="";
														$pointer = "none";
													}
													else if ($acc_tot_app >= 10000001) { 
														$crstm_approve = 'คณะกรรมการบริหารอนุมัติ';	
														$crstm_email_app1 = findsqlvalfirst("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
														$app1_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app1,$conn);	
														
														$crstm_email_app2 = findsqlvallast("author_mstr", "author_email", "author_text", $crstm_approve ,$conn);
														$app2_name = findsqlval("emp_mstr","emp_prefix_th_name+emp_th_firstname+'  '+emp_th_lastname+ ' (' +emp_th_pos_name + ')'","emp_email_bus",$crstm_email_app2,$conn);	
														$canedit = "readOnly";
														$error_txt ="";
														$pointer = "none";
													} ?>

                      <!--- เช็คอำนาจดำเนินการขออนุมัติวงเงิน --->
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-md-6 label-control font-weight-bold"
                              for="userinput1">อำนาจดำเนินการอนุมัติวงเงิน:</label>
                            <div class="col-md-6">
                              <input type="text" name="crstm_approve" id="crstm_approve"
                                value="<?php echo $crstm_approve ?>" class="form-control input-sm font-small-3">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                          <label class="font-weight-bold" for="cus_conf_yes">Group:</label>
                        </div>
                        <div class="col-md-2">
                          <input type="radio" name="crstm_scgc" id="crstm_scgc" value="true">
                          <label class="font-weight-bold">Tiles</label>
                        </div>
                        <div class="col-md-2">
                          <input type="radio" name="crstm_scgc" id="crstm_scgc1" value="false">
                          <label class="font-weight-bold">Geoluxe</label>
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-md-6 dis_reviewer_block">
                          <div class="form-group row">
                            <label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา 1 : </label>
                            <div class="col-md-6">
                              <div class="input-group input-group-sm">
                                <input name="crstm_reviewer" id="crstm_reviewer" value="<?php echo $crstm_reviewer ?>"
                                  data-disp_col1="emp_fullname" data-disp_col2="emp_email_bus"
                                  data-typeahead_src="../_help/get_emp_data.php" , data-ret_field_01="crstm_reviewer"
                                  data-ret_value_01="emp_email_bus" data-ret_type_01="val"
                                  data-ret_field_02="reviewer_name" data-ret_value_02="emp_fullnamedept"
                                  data-ret_type_02="html" class="form-control input-sm font-small-3 typeahead">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <a id="buthelp" data-id_field_code="crstm_reviewer"
                                      data-id_field_name="reviewer_name" data-modal_class="modal-dialog modal-lg"
                                      data-modal_title="ข้อมูลพนักงาน"
                                      data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                      data-modal_col_data1="emp_scg_emp_id" data-modal_col_data2="emp_fullnamedept"
                                      data-modal_col_data3="emp_dept" data-modal_col_data4="emp_email_bus"
                                      data-modal_col_data3_vis=true data-modal_col_data4_vis=true
                                      data-modal_ret_data1="emp_email_bus" data-modal_ret_data2="emp_fullnamedept"
                                      data-modal_src="../_help/get_emp_data.php" class="input-group-append"
                                      style="cursor:pointer">
                                      <span class="fa fa-search"></span>
                                    </a>
                                  </span>
                                </div>
                              </div><br>
                              <div class="dis_reviewer_name">
                                <span id="reviewer_name" name="reviewer_name"
                                  class="text-danger"><?php echo $reviewer_name?></span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 dis_reviewer_block">
                          <div class="form-group row">
                            <div class="col-md-1">
                              <input type="checkbox" class="form-control input-sm border-warning " name="crstm_noreviewer"
                                id="crstm_noreviewer" value="true">
                            </div>
                            <label class="col-md-4 label-control" for="userinput1">กรณีไม่ระบุผู้พิจารณา 1 :</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา 2 : </label>
                            <div class="col-md-6">
                              <div class="input-group input-group-sm">
                                <input name="crstm_reviewer2" id="crstm_reviewer2" readOnly
                                  <?php echo ($reviewercanedit) ?> value="<?php echo $crstm_reviewer2 ?>"
                                  data-disp_col1="emp_fullname" data-disp_col2="emp_email_bus"
                                  data-typeahead_src="../_help/get_emp_data.php" , data-ret_field_01="crstm_reviewer2"
                                  data-ret_value_01="emp_email_bus" data-ret_type_01="val"
                                  data-ret_field_02="reviewer_name2" data-ret_value_02="emp_fullnamedept"
                                  data-ret_type_02="html" class="form-control input-sm font-small-3 typeahead">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <a id="buthelp" data-id_field_code="crstm_reviewer2"
                                      data-id_field_name="reviewer_name2" data-modal_class="modal-dialog modal-lg"
                                      data-modal_title="ข้อมูลพนักงาน"
                                      data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                      data-modal_col_data1="emp_scg_emp_id" data-modal_col_data2="emp_fullnamedept"
                                      data-modal_col_data3="emp_dept" data-modal_col_data4="emp_email_bus"
                                      data-modal_col_data3_vis=true data-modal_col_data4_vis=true
                                      data-modal_ret_data1="emp_email_bus" data-modal_ret_data2="emp_fullnamedept"
                                      data-modal_src="../_help/get_emp_data.php" class="input-group-append"
                                      style="pointer-events: none">
                                      <!--class="input-group-append" style="pointer-events: <?php echo $pointer_vie2 ?>">-->
                                      <span class="fa fa-search" id="pointer2"></span>
                                    </a>
                                  </span>
                                </div>
                              </div><br>
                              <div class="dis_reviewer_name2">
                                <span id="reviewer_name2" name="reviewer_name2"
                                  class="text-danger"><?php echo $reviewer_name2?></span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ
                              1:</label>
                            <div class="col-md-6">
                              <div class="input-group input-group-sm">
                                <input name="crstm_email_app1" id="crstm_email_app1" <?php echo $canedit ?>
                                  value="<?php echo $crstm_email_app1 ?>" data-disp_col1="emp_fullname"
                                  data-disp_col2="emp_email_bus" data-typeahead_src="../_help/get_emp_data.php" ,
                                  data-ret_field_01="crstm_email_app1" data-ret_value_01="emp_email_bus"
                                  data-ret_type_01="val" data-ret_field_02="app1_name"
                                  data-ret_value_02="emp_fullnamedept" data-ret_type_02="html"
                                  class="form-control input-sm font-small-3 typeahead">

                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <a id="buthelp" data-id_field_code="crstm_email_app1" data-id_field_name="app1_name"
                                      data-modal_class="modal-dialog modal-lg" data-modal_title="ข้อมูลพนักงาน"
                                      data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                      data-modal_col_data1="emp_scg_emp_id" data-modal_col_data2="emp_fullnamedept"
                                      data-modal_col_data3="emp_dept" data-modal_col_data4="emp_email_bus"
                                      data-modal_col_data3_vis=true data-modal_col_data4_vis=true
                                      data-modal_ret_data1="emp_email_bus" data-modal_ret_data2="emp_fullnamedept"
                                      data-modal_src="../_help/get_emp_data.php" class="input-group-append"
                                      style="pointer-events: none">
                                      <span class="fa fa-search" id="pointer1"></span>
                                    </a>
                                  </span>
                                </div>
                              </div><br>
                              <div><span id="app1_name" name="app1_name"
                                  class="text-danger"><?php echo $app1_name?></span></div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-md-9 label-control text-danger"
                              id="error_txt"><?php echo $error_txt ?></label>
                          </div>
                        </div>

                        <!--<div class="col-md-6 notdisplay"></div>-->

												<div class="col-md-6 displayApp2" style="display:<?php echo $chk_block ?>">
                          <div class="form-group row">
                            <label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ
                              2:</label>
                            <div class="col-md-6">
                              <div class="input-group input-group-sm">
                                <input name="crstm_email_app2" id="crstm_email_app2" readOnly <?php echo $canedit ?>
                                  value="<?php echo $crstm_email_app2 ?>" data-disp_col1="emp_fullname"
                                  data-disp_col2="emp_email_bus" data-typeahead_src="../_help/get_emp_data.php" ,
                                  data-ret_field_01="crstm_email_app2" data-ret_value_01="emp_email_bus"
                                  data-ret_type_01="val" data-ret_field_02="app2_name"
                                  data-ret_value_02="emp_fullnamedept" data-ret_type_02="html"
                                  class="form-control input-sm font-small-3 typeahead">

                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <a id="buthelp" data-id_field_code="crstm_email_app2" data-id_field_name="app2_name"
                                      data-modal_class="modal-dialog modal-lg" data-modal_title="ข้อมูลพนักงาน"
                                      data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                      data-modal_col_data1="emp_scg_emp_id" data-modal_col_data2="emp_fullnamedept"
                                      data-modal_col_data3="emp_dept" data-modal_col_data4="emp_email_bus"
                                      data-modal_col_data3_vis=true data-modal_col_data4_vis=true
                                      data-modal_ret_data1="emp_email_bus" data-modal_ret_data2="emp_fullnamedept"
                                      data-modal_src="../_help/get_emp_data.php" class="input-group-append"
                                      style="pointer-events: none">
                                      <span class="fa fa-search" id="pointer1"></span>
                                    </a>
                                  </span>
                                </div>
                              </div><br>
                              <div><span id="app2_name" name="app2_name"
                                  class="text-danger"><?php echo $app2_name?></span></div>
                            </div>
                          </div>
                          <!--<div class="col-md-6">
																<div class="form-group row">
																<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
																</div>
															</div>-->

                        </div>
                        <div class="col-md-6 nonCol"></div>
                        <div class="col-md-6 displayApp3" style="display:<?php echo $chk_block ?>">
                          <div class="form-group row">
                            <label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ
                              3:</label>
                            <div class="col-md-6">
                              <div class="input-group input-group-sm">
                                <input name="crstm_email_app3" id="crstm_email_app3" <?php echo $canedit ?>
                                  value="<?php echo $crstm_email_app3 ?>" data-disp_col1="emp_fullname"
                                  data-disp_col2="emp_email_bus" data-typeahead_src="../_help/get_emp_data.php" ,
                                  data-ret_field_01="crstm_email_app3" data-ret_value_01="emp_email_bus"
                                  data-ret_type_01="val" data-ret_field_02="app2_name"
                                  data-ret_value_02="emp_fullnamedept" data-ret_type_02="html"
                                  class="form-control input-sm font-small-3 typeahead">

                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                    <a id="buthelp" data-id_field_code="crstm_email_app3" data-id_field_name="app2_name"
                                      data-modal_class="modal-dialog modal-lg" data-modal_title="ข้อมูลพนักงาน"
                                      data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                      data-modal_col_data1="emp_scg_emp_id" data-modal_col_data2="emp_fullnamedept"
                                      data-modal_col_data3="emp_dept" data-modal_col_data4="emp_email_bus"
                                      data-modal_col_data3_vis=true data-modal_col_data4_vis=true
                                      data-modal_ret_data1="emp_email_bus" data-modal_ret_data2="emp_fullnamedept"
                                      data-modal_src="../_help/get_emp_data.php" class="input-group-append"
                                      style="pointer-events: none">
                                      <span class="fa fa-search" id="pointer1"></span>
                                    </a>
                                  </span>
                                </div>
                              </div><br>
                              <div><span id="app3_name" name="app3_name"
                                  class="text-danger"><?php echo $app3_name?></span></div>
                            </div>
                          </div>
                          <!--<div class="col-md-6">
																				<div class="form-group row">
																				<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
																				</div>
																			</div>-->

                        </div>

                        <div class="col-md-6 nonCol"></div>
                        <!---------------->
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-md-6 label-control font-weight-bold"
                              for="userinput1">ประมาณการขายเฉลี่ยต่อเดือน (บาท) : <font
                                class="text text-danger font-weight-bold"> ***</font></label>
                            <div class="col-md-6">
                              <a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_sd_per_mm"
                                  class="form-control input-sm " name="crstm_sd_per_mm"
                                  value="<?php echo $crstm_sd_per_mm ?>" style="color:blue;text-align:right"
                                  onkeyup="format(this)" onchange="format(this)"></a>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-12">
                          <fieldset class="form-group">
                            <label for="placeTextarea" class="font-weight-bold">ความเห็น / เหตุผลที่เสนอขอวงเงิน : <font
                                class="text text-danger font-weight-bold"> ***</font></label>
                            <textarea name="crstm_sd_reson" id="crstm_sd_reson"
                              class="form-control textarea-maxlength input-sm font-small-3"
                              placeholder="Enter upto 500 characters.." maxlength="500" rows="5"
                              style="line-height:1.5rem;"></textarea>
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
                                    <input type="file" accept="" name="load_reson_img" id="load_reson_img"
                                      onkeyup="CheckValidFile_header_attach('load_reson_img')"
                                      onchange="CheckValidFile_header_attach('load_reson_img')">
                                    <span class="file-custom"></span>
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
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
                              <input type="text" name="crstm_pj_name" id="crstm_pj_name" class="form-control input-sm">
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
                                class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj_prv"
                                name="crstm_pj_prv">
                                <option value="" selected>--- เลือกจังหวัด ---</option>
                                <?php
																			$sql_doc = "SELECT * FROM province_mstr order by province_th_name";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
                                <option value="<?php echo $r_doc['province_th_name']; ?>" data-icon="fa fa-wordpress">
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
                              <select data-placeholder="Select a doc type ..."
                                class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj1_prv"
                                name="crstm_pj1_prv">
                                <option value="" selected>--- เลือกจังหวัด ---</option>
                                <?php
																			$sql_doc = "SELECT * FROM province_mstr order by province_th_name";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
                                <option value="<?php echo $r_doc['province_th_name']; ?>" data-icon="fa fa-wordpress">
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
                              <select data-placeholder="Select a doc type ..."
                                class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj_term"
                                name="crstm_pj_term">
                                <option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---</option>
                                <?php
																			$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
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
                                class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj1_term"
                                name="crstm_pj1_term">
                                <option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---</option>
                                <?php
																			$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
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
                                  class="form-control input-sm" name="crstm_pj_amt" value="<?php echo $crstm_pj_amt ?>"
                                  style="color:black;text-align:right" onkeyup="format(this)"
                                  onchange="format(this)"></a>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-md-3 label-control" for="userinput1">มูลค่างาน (บาท):</label>
                            <div class="col-md-9">
                              <a title="ระบุเป็นจำนวนเลข"><input type="text" id="crstm_pj1_amt"
                                  class="form-control input-sm" name="crstm_pj1_amt"
                                  value="<?php echo $crstm_pj1_amt ?>" style="color:black;text-align:right"
                                  onkeyup="format(this)" onchange="format(this)"></a>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-md-3 label-control" for="userinput1">ระยะเวลา (เดือน):</label>
                            <div class="col-md-9">
                              <select data-placeholder="Select a doc type ..."
                                class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj_dura"
                                name="crstm_pj_dura">
                                <option value="" selected>--- เลือกจำนวนเดือน ---</option>
                                <?php
																			$sql_doc = "SELECT tbl_mm_code, tbl_mm_desc, tbl_mm_seq FROM tbl_mm ORDER BY tbl_mm_seq";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
                                <option value="<?php echo $r_doc['tbl_mm_code']; ?>" data-icon="fa fa-wordpress">
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
                              <select data-placeholder="Select a doc type ..."
                                class="form-control input-sm border-warning font-small-3 select2" id="crstm_pj1_dura"
                                name="crstm_pj1_dura">
                                <option value="" selected>--- เลือกจำนวนเดือน ---</option>
                                <?php
																			$sql_doc = "SELECT tbl_mm_code, tbl_mm_desc, tbl_mm_seq FROM tbl_mm ORDER BY tbl_mm_seq";
																			$result_doc = sqlsrv_query($conn, $sql_doc);
																			while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																			?>
                                <option value="<?php echo $r_doc['tbl_mm_code']; ?>" data-icon="fa fa-wordpress">
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
                                <input id="crstm_pj_beg" name="crstm_pj_beg"
                                  class="form-control input-sm border-warning font-small-3" type="text"
                                  placeholder="--- เลือกวันเริ่มใช้งาน ---">
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
                                  class="form-control input-sm border-warning font-small-3" type="text"
                                  placeholder="--- เลือกวันเริ่มใช้งาน ---">
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
                                    <input type="file" accept="*" name="load_pj_img" id="load_pj_img"
                                      onkeyup="CheckValidFile_header_attach('load_pj_img')"
                                      onchange="CheckValidFile_header_attach('load_pj_img')">
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
                                    <input type="file" accept="*" name="load_pj1_img" id="load_pj1_img"
                                      onkeyup="CheckValidFile_header_attach('load_pj1_img')"
                                      onchange="CheckValidFile_header_attach('load_pj1_img')">
                                    <span class="file-custom"></span>
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row mt-n3">
                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1 btn-group-sm">
                          <?php if ($crstm_sd_reson != "") { ?>
                          <button type="button" id="btnsave" name="btnsave"
                            class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1"
                            onclick="dispostform('frm_add_send','<?php echo encrypt('10', $key);?>','<?php echo $crstm_cus_name; ?>')"><i
                              class="fa fa-envelope-o"></i> Submit</button>
                          <?php } ?>
                          <button type="button" id="btnsave" name="btnsave"
                            class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"
                            onclick="dispostform('frm_add','<?php echo encrypt('0', $key);?>','<?php echo $crstm_cus_name; ?>')"><i
                              class="fa fa-check-square-o"></i> Save</button>
                          <button type="reset" class="btn btn-outline-danger btn-min-width btn-glow mr-1 mb-1"
                            onclick="document.location.href='../crctrlbof/crctrlall.php'"><i class="ft-x"></i>
                            Cancel</button>
                        </div>
                      </div>
                    </form>
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
  function dispostform(formid, chk_action, cus_name) {
    //alert(formid+"--"+chk_action+"--"+cus_name);
    $(document).ready(function() {
      if (formid == 'frm_add') {
        Swalappform(formid, chk_action, cus_name);
      } else {
        Swalappformsend(formid, chk_action, cus_name);
      }
      //e.preventDefault();
    });
  }

  function Swalappform(formid, chk_action, cus_name) {
    //alert(formid+"--"+chk_action+"--"+cus_name);
    Swal.fire({
      //title: "Are you sure?",
      html: "คุณต้องการบันทึกข้อมูลลูกค้า   นี้ใช่หรือไม่ !!!! ",
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
                '<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
              $("#requestOverlay").show(); /*Show overlay*/
            },
            type: 'POST',
            //url: '../serverside/crctrlpost_new.php?step_code='+chk_action+'&cus_name='+cus_name+''  ,
            url: '../serverside/crctrlpost_new.php?step_code=' + chk_action,
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
                $(location).attr('href', 'crctrledit_new.php?crnumber=' + json.nb + '&pg=' + json.pg +
                  '&current_tab=30')
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
      html: "คุณต้องการส่งข้อมูล  <br>ลูกค้า   " + cus_name + " ไปให้แผนกสินเชื่ออนุมัติ ใช่หรือไม่ !!!! ",
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
                '<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
              $("#requestOverlay").show(); /*Show overlay*/
            },
            type: 'POST',
            url: '../serverside/crctrlpost_new.php?step_code=' + chk_action,
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
                  title: "Submit successfully.",
                  showConfirmButton: false,
                  timer: 500,
                  confirmButtonClass: "btn btn-primary",
                  buttonsStyling: false
                });
                location.reload(true);
                $(location).attr('href', 'crctrlall.php?crnumber=' + json.nb + '&pg=' + json.pg +
                  '&current_tab=30')
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
  $('#crstm_district,#crstm_amphur,#crstm_province').typeahead({
    displayText: function(item) {
      return item.district + " >> อ. " + item.amphoe + "  >> จ. " + item.province + ">> รหัสไปรษณีย์ " + item
        .zipcode
    },
    emptyTemplate: function(item) {
      if (item.length > 0) {
        return 'No results found for "' + item + '"';
      }
    },
    source: function(query, response) {
      jQuery.ajax({
        url: "../_libs/thailandjson/raw_database.json", //even.php",
        data: {
          query: query
        },
        dataType: "json",
        type: "POST",
        success: function(data) {
          response(data)
        }

      })
    },

    afterSelect: function(item) {
      $("#crstm_province").val(item.province);
      $("#crstm_amphur").val(item.amphoe);
      $("#crstm_district").val(item.district);
      $("#crstm_zip").val(item.zipcode);
      $("#crstm_country").val("ประเทศไทย");
    }

  });
  $('input[type="radio"]').click(function() {
    if ($(this).attr('id') == 'cus_conf_yes') {
      $('.cc_display').show();
      $("#cc_amt").prop("required", true);
      $("#beg_date_new").prop("required", true);
      $("#end_date_new").prop("required", true);
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
            location.reload(true);
            $(location).attr('href', '../crctrlbof/crctrladd_new.php?cusnbr=' + json.nb + '&nb1=' + json.nb1)
          }
        },
        complete: function() {
          $("#requestOverlay").remove(); /*Remove overlay*/
        }
      });
    });
  }

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

  /// เมื่อกรอกตัวเลขจำนวนเงิน ใน textbox ให้มันใส่คอมม่า
  function format(input) {
    var num = input.value.replace(/\,/g, '');
    if (!isNaN(num)) {
      if (num.indexOf('.') > -1) {
        num = num.split('.');
        num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1,').split('').reverse()
          .join('').replace(/^[\,]/, '');
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

  $("#cc_amt1").on("change", function() {
    var cc_amt = $(this).val();
    var result_cc_amt1 = parseFloat(cc_amt.replace(/\$|,/g, ''))
    acc_tot_app = result_cc_amt1;

    $("#crstm_email_app1").val(""); // เคลียร์ค่าผู้อนุมัติ 1
    $("#crstm_email_app2").val(""); // เคลียร์ค่าผู้อนุมัติ 2
		$("#crstm_email_app3").val(""); // เคลียร์ค่าผู้อนุมัติ 3
    $("#crstm_reviewer2").val(""); // เคลียร์ค่าผู้พิจารณา 2
    document.getElementById("app1_name").innerHTML = "";
    document.getElementById("app2_name").innerHTML = "";
		document.getElementById("app3_name").innerHTML = "";
    document.getElementById("reviewer_name2").innerHTML = "";

    if (acc_tot_app <= 700000) {
      crstm_approve = 'ผส. อนุมัติ';
      $error_txt = "*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
      document.getElementById('crstm_email_app1').readOnly = false; //คีย์ข้อมูลได้
      document.getElementById('error_txt').innerHTML = $error_txt;
      document.getElementById('pointer1').style.pointerEvents = 'auto';
      document.getElementById("crstm_scgc").checked = false;
      document.getElementById("crstm_scgc1").checked = false; // geoluxe
			$('.displayApp2').hide(); 
			$('.displayApp3').hide();   
			$('.nonCol').hide();
			$('.nonCol1').show();
    } else if (acc_tot_app >= 700001 && acc_tot_app <= 2000000) {
      crstm_approve = 'ผฝ. อนุมัติ';
      $error_txt = "*** กรุณาเลือกผู้อนุมัติตาม อนก. บริษัท ฯ ***";
      document.getElementById('crstm_email_app1').readOnly = false;
      document.getElementById('error_txt').innerHTML = $error_txt;
      document.getElementById('pointer1').style.pointerEvents = 'auto';
      document.getElementById("crstm_scgc").checked = false;
      document.getElementById("crstm_scgc1").checked = false; // geoluxe
			$('.displayApp2').hide(); 
			$('.displayApp3').hide();   
			$('.nonCol').hide();
			$('.nonCol1').show();
    } else if (acc_tot_app >= 2000001 && acc_tot_app <= 5000000) {
      crstm_approve = 'CO. อนุมัติ';
      $error_txt = "";
      document.getElementById('crstm_email_app1').readOnly = true; //คีย์ข้อมูลไม่ได้
      document.getElementById('error_txt').innerHTML = $error_txt;
      document.getElementById('pointer1').style.pointerEvents = 'none';
      document.getElementById("crstm_scgc").checked = false;
      document.getElementById("crstm_scgc1").checked = false; // geoluxe
			$('.displayApp2').hide(); 
			$('.displayApp3').hide();   
			$('.nonCol').hide();
			$('.nonCol1').show();
    } else if (acc_tot_app >= 5000001 && acc_tot_app <= 7000000) {
      crstm_approve = 'กจก. อนุมัติ';
      $error_txt = "";
      document.getElementById('crstm_email_app1').readOnly = true;
      document.getElementById('error_txt').innerHTML = $error_txt;
      document.getElementById('pointer1').style.pointerEvents = 'none';
      document.getElementById("crstm_scgc").checked = false;
      document.getElementById("crstm_scgc1").checked = false; // geoluxe
			$('.displayApp2').hide();  
			$('.displayApp3').hide();  
			$('.nonCol').hide();
			$('.nonCol1').show();
    } else if (acc_tot_app >= 7000001 && acc_tot_app <= 10000000) {
      crstm_approve = 'คณะกรรมการสินเชื่ออนุมัติ';
      $error_txt = "";
      document.getElementById('crstm_email_app1').readOnly = true;
      document.getElementById('crstm_email_app2').readOnly = true;
      document.getElementById('crstm_email_app3').readOnly = true;
      document.getElementById('error_txt').innerHTML = $error_txt;
      document.getElementById('pointer1').style.pointerEvents = 'none';
      document.getElementById("crstm_scgc").checked = false;
      document.getElementById("crstm_scgc1").checked = false; // geoluxe
			$('.displayApp2').show();  
      $('.displayApp3').show();  
			$('.nonCol').show();
			$('.nonCol1').hide();
    } else {
      crstm_approve = 'คณะกรรมการบริหารอนุมัติ';
      document.getElementById('crstm_email_app1').readOnly = true;
      document.getElementById('crstm_email_app2').readOnly = true;
      document.getElementById('crstm_email_app3').readOnly = true;
      document.getElementById('pointer1').style.pointerEvents = 'none';
      document.getElementById("crstm_scgc").checked = false;
      document.getElementById("crstm_scgc1").checked = false; // geoluxe
			$('.displayApp2').show();  
      $('.displayApp3').show();  
			$('.nonCol').show();
			$('.nonCol1').hide();
    }

    $("#crstm_approve").val(crstm_approve);
  });

  $("#crstm_noreviewer").change(function() {
    if (this.checked) {
      $("#crstm_reviewer").val("");
      $("#reviewer_name").val("");
      $(".dis_reviewer_name").hide();
      document.getElementById("crstm_reviewer").disabled = true; // disabled textbox ในส่วนอีเมลผู้ตรวจสอบ
    } else {
      document.getElementById("crstm_reviewer").disabled = false;
    }
  });

  $("#crstm_scgc").change(function() {
    $('.dis_reviewer_block').show();

    document.getElementById("crstm_scgc").checked;
    document.getElementById("crstm_nbr").value;
    document.getElementById('crstm_reviewer2').readOnly = true;
    document.getElementById('pointer1').style.pointerEvents = 'none';

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
      let reviewer = "";
      let email = "";
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
    xhttp.open("POST", "../serverside/checkreviewer.php?q=" + check_flag + "&group=" + check_form + " ", false);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
    xhttp.setRequestHeader("Pragma", "no-cache");
    xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");
    //url: '../serverside/crctrl_cr_post.php?step_code='+chk_action+'&formid='+formid+''  ,
    xhttp.send("q=" + check_flag +
      '&csrf_securecode=<?php echo $csrf_securecode?>&csrf_token=<?php echo md5($csrf_token)?>');
    //xhttp.send();   
  });
  $("#crstm_scgc1").change(function() {
    $('.dis_reviewer_block').hide();

    document.getElementById("crstm_scgc1").checked;
    document.getElementById("crstm_reviewer2").value;
    document.getElementById('crstm_reviewer2').readOnly = false;
    //document.getElementById('pointer1').style.pointerEvents = 'auto';
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
      let reviewer = "";
      let email = "";
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
        document.getElementById('crstm_reviewer2').readOnly = true; // ผู้พิจารณา 2 อ่านได้อย่างเดียว แก้ไขไม่ได้
        $pointer2 = 'auto';

				email3 = document.getElementById("crstm_email_app3").innerHTML = myObj.email3;
				$("#crstm_email_app3").val(email3);
				app3_name = document.getElementById("app3_name").innerHTML = myObj.app3_name;
				$("#app3_name").val(app3_name);
				$('.displayApp3').show();  
      } else {
        document.getElementById('crstm_reviewer2').readOnly = true;
        $pointer_vie2 = "none";
        $pointer2 = 'none';
      }
      //console.log(this.responseText);
    }
    //xhttp.open("GET", "../serverside/checkreviewer.php?q="+check_flag+'&group='+ check_form +'',true);
    //url: '../serverside/crctrlapppost.php?step_code='+chk_action+'&formid='+formid+''  ,
    xhttp.open("POST", "../serverside/checkreviewer.php?q=" + check_flag + "&group=" + check_form + " ", false);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
    xhttp.setRequestHeader("Pragma", "no-cache");
    xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");
    xhttp.send();
  });

  function validate() {
    let valid = false;
    let x = document.frm_crctrl_add.isscgc; // document.form.name == form ==> frm_crctrl_add , name ==> isscgc
    for (let i = 0; i < x.length; i++) {
      if (x[i].checked) {
        valid = true;
        break;
      }
    }
    // if(!valid){
    // alert("กรุณาเลือกลูกค้า Tiles หรือ Geoluxe");
    // return false;
    // }
  }

  $(document).on("click", "#btn-ret-value", function(e) {
    e.preventDefault();
    var code = $(this).data('send_code');
    var name = $(this).data('send_name');
    var id_field_code = $(this).data('rec_code_id');

    var id_field_name = $(this).data('rec_name_id');
    var id_field_name_type = $(this).data('rec_name_type');

    $(".dis_reviewer_name").show();
    $('#but_help_close').trigger("click");
    $('#' + id_field_code).val(code);
    if (id_field_name_type == "value") {
      $('#' + id_field_name).val(name);
    } else {
      $('#' + id_field_name).html(name);
    }
    if (id_field_code.startsWith("sppart")) {
      if (code == "DUMMY") {
        document.getElementById(id_field_name).readOnly = false;
        document.getElementById(id_field_name).value = "";
        document.getElementById(id_field_name).focus();
      } else {
        document.getElementById(id_field_name).readOnly = true;
      }
    }
  });

  $("#frm_crctrl_add").on("click", "#buthelp", function() {
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
    if (modal_page_size === undefined || modal_page_size == "") {
      modal_page_size = 10;
    }
    if (modal_page_type === undefined || modal_page_type == "") {
      modal_page_type = "simple";
    }
    if (id_field_name_type === undefined || id_field_name_type == "") {
      id_field_name_type = "html";
    }
    //Column Setting
    var cols = [{
      "data": modal_col_data1
    }, {
      "data": modal_col_data2
    }];
    if (modal_col_data3 !== undefined && modal_col_data3 != "") {
      cols.push({
        "data": modal_col_data3,
        "visible": modal_col_data3_vis
      });
    }
    if (modal_col_data4 !== undefined && modal_col_data4 != "") {
      cols.push({
        "data": modal_col_data4,
        "visible": modal_col_data4_vis
      });
    }
    //
    input0.field_code = $("#" + id_field_code).val();
    //input0.login_plant = "<?php echo $login_plant;?>";

    $.ajax({
      url: modal_src,
      type: "POST",
      dataType: 'json',
      data: {
        param0: JSON.stringify(input0)
      },
      beforeSend: function() {
        $(".loading").fadeIn();
        $("#div_help").find("#div_help_size").attr("class", modal_class);
        $("#div_help").find("#help_title").html(modal_title);
        $("#div_help").find("#head0").html(modal_col_name);
        $("#div_help").modal({
          backdrop: 'static',
          keyboard: false
        });
        $('#table-help').DataTable().clear().destroy();
      },
      success: function(res) {
        //if (res.success) {
        $("#table-help").dataTable().fnDestroy();
        $("#table-help").dataTable({
          "oSearch": {
            "sSearch": input0.field_code
          },
          "dom": '<lf<t>ip>',
          "deferRender": true,
          //"aaData" : res.data,
          "aaData": res,
          "cache": false,
          "columns": cols,
          "columnDefs": [{
              "className": "dt-left",
              "targets": [0, 1]
            },
            {
              "width": "50px",
              "targets": 0
            },
            {
              "width": "100px",
              "targets": 1
            },
            {
              "render": function(data, type, row) {
                return '<a href="javascript:void(0)" id="btn-ret-value"' +
                  '" data-send_code="' + row[modal_ret_data1] +
                  '" data-send_name="' + row[modal_ret_data2] +
                  '" data-rec_code_id="' + id_field_code +
                  '" data-rec_name_id="' + id_field_name +
                  '" data-rec_name_type="' + id_field_name_type + '">' + data + '</a>';
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
      complete: function() {
        $(".loading").fadeOut();
      },
      error: function(res) {
        alert('error');
      }
    });
  });

  //TYPE AHEAD
  $('.typeahead').typeahead({

    displayText: function(item) {
      var disp_col1 = this.$element.attr('data-disp_col1');
      var disp_col2 = this.$element.attr('data-disp_col2');
      return item[disp_col1] + ' ' + item[disp_col2];
    },
    source: function(query, process) {
      var typeahead_src = this.$element.attr('data-typeahead_src')
      $.ajax({
        url: typeahead_src,
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
      var ret_field_01 = this.$element.attr('data-ret_field_01')
      var ret_value_01 = this.$element.attr('data-ret_value_01')
      var ret_type_01 = this.$element.attr('data-ret_type_01')
      var ret_field_02 = this.$element.attr('data-ret_field_02')
      var ret_value_02 = this.$element.attr('data-ret_value_02')
      var ret_type_02 = this.$element.attr('data-ret_type_02')
      if (ret_type_01 == "val") {
        $('#' + ret_field_01).val(item[ret_value_01]);
      } else {
        $('#' + ret_field_01).html(item[ret_value_01]);
      }
      if (ret_type_02 == "val") {
        $('#' + ret_field_02).val(item[ret_value_02]);
      } else {
        $('#' + ret_field_02).html(item[ret_value_02]);
      }
    }
  });

  function CheckValidFile_header_attach(nFle) {
    var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png", ".xls", ".xlsx", ".pdf", ".doc", ".docx",
      ".ppt", ".pptx"
    ];
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
    }
    //var fileVal = document.getElementById('load_reson_img').value;
    var fileExt = fileVal.substring(fileVal.lastIndexOf('.'));
    if (fileVal == "") {
      Swal.fire({
        title: "Warning!",
        //html: json.e,
        html: "<b>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" +
          _validFileExtensions.join(", ") + "</span></b>",
        icon: 'warning',
        confirmButtonClass: "btn btn-danger",
        buttonsStyling: false
      });
      return false;
    } else if (fileVal.length > 0) {
      //Not Allow : check for configuration files like web.config
      //Not Allow : Check for files without a filename like .htaccess
      var ext = fileVal.split('.');
      if (ext[0] == "") {
        Swal.fire({
          title: "Warning!",
          //html: json.e,
          html: "<b>คุณกำลังพยายามอัพโหลดไฟล์ ที่จะส่งผลต่อการตั้งค่าของเว็บเซิฟเวอร์อยู่หรือไม่ จงหลีกเลี่ยง<br>เฉพาะไฟล์ที่มีนามสกุล <span class='text-danger'>" +
            _validFileExtensions.join(", ") + "</span> เท่านั้น </b>",
          icon: 'warning',
          customClass: {
            confirmButton: "btn btn-danger"
          },
          buttonsStyling: false
        });
        return false;
      } else if (fileExt == ".config" || fileExt == ".exe" || fileExt == ".db" || fileExt == ".dll") {
        Swal.fire({
          title: "Warning!",
          //html: json.e,
          html: "<b>คุณกำลังพยายามอัพโหลดไฟล์ ที่จะส่งผลต่อการตั้งค่าของเว็บอยู่หรือไม่ จงหลีกเลี่ยง <br>โปรดอัพโหลดเฉพาะไฟล์ที่มีนามสกุล <span class='text-danger'>" +
            _validFileExtensions.join(", ") + "</span> เท่านั้น </b>",
          icon: 'warning',
          customClass: {
            confirmButton: "btn btn-danger"
          },
          buttonsStyling: false
        });
        return false;
      } else if (ext.length == 1) {
        Swal.fire({
          title: "Warning!",
          html: "<b>ไม่พบนามสกุลไฟล์ที่ต้องการอัพโหลด <br>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" +
            _validFileExtensions.join(", ") + "</span></b>",
          icon: 'warning',
          customClass: {
            confirmButton: "btn btn-warning"
          },
          buttonsStyling: false
        });
        return false;
      } else if (ext.length > 2) {
        // console.log("found");
        // alert(ext[1]); // first extension
        // alert(ext[2]); // second extension
        Swal.fire({
          title: "Warning!",
          html: "<b>กรุณาตั้งชื่อไฟล์โดยไม่มีเครื่องหมาย (.) ในชื่อไฟล์ <br>นอกจากนามสกุลไฟล์เท่านั้น เพื่อป้องกันความผิดพลาด<br>และกรุณาอัพโหลดไฟล์ <br> เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" +
            _validFileExtensions.join(", ") + "</span></b>",
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
          if (fileVal.substr(fileVal.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension
            .toLowerCase()) {
            blnValid = true;
            return true;
          }
        }

        if (!blnValid) {
          //alert("Sorry, " + fileVal + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
          Swal.fire({
            title: "Warning!",
            html: "<b>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" +
              _validFileExtensions.join(", ") + "</span></b>",
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

  function CheckValidFile_header_attach(nFle) {
    var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png", ".xls", ".xlsx", ".pdf", ".doc", ".docx",
      ".ppt", ".pptx"
    ];
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
        html: "<b>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" +
          _validFileExtensions.join(", ") + "</span></b>",
        icon: 'warning',
        confirmButtonClass: "btn btn-danger",
        buttonsStyling: false
      });
      return false;
    } else if (fileVal.length > 0) {
      //Not Allow : check for configuration files like web.config
      //Not Allow : Check for files without a filename like .htaccess
      var ext = fileVal.split('.');
      if (ext[0] == "") {
        Swal.fire({
          title: "Warning!",
          //html: json.e,
          html: "<b>คุณกำลังพยายามอัพโหลดไฟล์ ที่จะส่งผลต่อการตั้งค่าของเว็บเซิฟเวอร์อยู่หรือไม่ จงหลีกเลี่ยง<br>เฉพาะไฟล์ที่มีนามสกุล <span class='text-danger'>" +
            _validFileExtensions.join(", ") + "</span> เท่านั้น </b>",
          icon: 'warning',
          customClass: {
            confirmButton: "btn btn-danger"
          },
          buttonsStyling: false
        });
        return false;
      } else if (fileExt == ".config" || fileExt == ".exe" || fileExt == ".db" || fileExt == ".dll") {
        Swal.fire({
          title: "Warning!",
          //html: json.e,
          html: "<b>คุณกำลังพยายามอัพโหลดไฟล์ ที่จะส่งผลต่อการตั้งค่าของเว็บอยู่หรือไม่ จงหลีกเลี่ยง <br>โปรดอัพโหลดเฉพาะไฟล์ที่มีนามสกุล <span class='text-danger'>" +
            _validFileExtensions.join(", ") + "</span> เท่านั้น </b>",
          icon: 'warning',
          customClass: {
            confirmButton: "btn btn-danger"
          },
          buttonsStyling: false
        });
        return false;
      } else if (ext.length == 1) {
        Swal.fire({
          title: "Warning!",
          html: "<b>ไม่พบนามสกุลไฟล์ที่ต้องการอัพโหลด <br>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" +
            _validFileExtensions.join(", ") + "</span></b>",
          icon: 'warning',
          customClass: {
            confirmButton: "btn btn-warning"
          },
          buttonsStyling: false
        });
        return false;
      } else if (ext.length > 2) {
        // console.log("found");
        // alert(ext[1]); // first extension
        // alert(ext[2]); // second extension
        Swal.fire({
          title: "Warning!",
          html: "<b>กรุณาตั้งชื่อไฟล์โดยไม่มีเครื่องหมาย (.) ในชื่อไฟล์ <br>นอกจากนามสกุลไฟล์เท่านั้น เพื่อป้องกันความผิดพลาด<br>และกรุณาอัพโหลดไฟล์ <br> เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" +
            _validFileExtensions.join(", ") + "</span></b>",
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
          if (fileVal.substr(fileVal.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension
            .toLowerCase()) {
            blnValid = true;
            return true;
          }
        }

        if (!blnValid) {
          //alert("Sorry, " + fileVal + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
          Swal.fire({
            title: "Warning!",
            html: "<b>กรุณาอัพโหลดไฟล์ <br>เฉพาะไฟล์ที่มีนามสกุล ดังต่อไปนี้เท่านั้น <br><span class='text-danger'>" +
              _validFileExtensions.join(", ") + "</span></b>",
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
  $('#beg_date_new').datetimepicker({
    format: 'DD/MM/YYYY'
  });
  $('#end_date_new').datetimepicker({
    format: 'DD/MM/YYYY'
  });
  $('#crstm_pj_beg').datetimepicker({
    format: 'DD/MM/YYYY'
  });
  $('#crstm_pj1_beg').datetimepicker({
    format: 'DD/MM/YYYY'
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

</html>