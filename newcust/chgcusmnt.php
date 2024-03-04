<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
include("../crctrlbof/chkauthcr.php");		
include_once('../_libs/Thaidate/Thaidate.php');
include_once('../_libs/Thaidate/thaidate-functions.php');
	
set_time_limit(0);
$curdate = date('Ymd');
$params = array();
$default_current_tab = "10";
$request_tab = $_GET['current_tab'];
if ($request_tab != "") {
	$current_tab = $request_tab;
	} else {
	$current_tab = $default_current_tab;
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key, $user_login)) {
		echo "System detect CSRF attack2!!";
		exit;
	}
}

$temimagerandom = mt_rand(10000000,99999999); 

$action_cus = decrypt(mssql_escape($_REQUEST['action_cus']), $key);

	switch($action_cus){
		case "c3" :
			$readonlyC3 = "";
			$readonlyC4 = "readonly";
			$dis_apprv = "show";
			$dis_info_addr = "none"; $dis_reg_addr = "none";
			$cusd_op_app = "ผผ.";
			$book_case = 2;
			break;
		case "c4" :
			$readonlyC4 = "";
			$readonlyC3 = "readonly";
			$dis_apprv = "none";
			$cusd_op_app = "กจก.";
			$book_case = 2;
			break;	
		case "c5" :
			$readonlyC5 = "";
			$dis_apprv = "none";
			$cusd_op_app = "กจก.";
			$book_case = 2;
			break;		
	}   

	$sql_sysc = "SELECT * from sysc_ctrl";
    $result_detail = sqlsrv_query($conn, $sql_sysc, $params);
    $rec_sysc = sqlsrv_fetch_array($result_detail, SQLSRV_FETCH_ASSOC);
    if ($rec_sysc) {
  
        $sysc_cmo = strtoupper($rec_sysc['sysc_cmo']);
        if ($sysc_cmo != "") {
            $emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_cmo, $conn);
            $emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_cmo, $conn);
            $emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_cmo, $conn);
            $emp_th_pos_name = findsqlval("emp_mstr", "emp_th_pos_name", "emp_user_id", $sysc_cmo, $conn);
			$sysc_cmo_id = findsqlval("emp_mstr", "emp_scg_emp_id", "emp_user_id", $sysc_cmo, $conn);
            $sysc_name_cmo = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname."(". $emp_th_pos_name .")";
        }
        $sysc_cfo = strtoupper($rec_sysc['sysc_cfo']);
        if ($sysc_cfo != "") {
            $emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_cfo, $conn);
            $emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_cfo, $conn);
            $emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_cfo, $conn);
            $emp_th_pos_name = findsqlval("emp_mstr", "emp_th_pos_name", "emp_user_id", $sysc_cfo, $conn);
			$sysc_cfo_id = findsqlval("emp_mstr", "emp_scg_emp_id", "emp_user_id", $sysc_cfo, $conn);
            $sysc_name_cfo = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname."(". $emp_th_pos_name .")";
        }
        $sysc_md = strtoupper($rec_sysc['sysc_md']);
        if ($sysc_md != "") {
            $emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_md, $conn);
            $emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_md, $conn);
            $emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_md, $conn);
            $emp_th_pos_name = findsqlval("emp_mstr", "emp_th_pos_name", "emp_user_id", $sysc_md, $conn);
			$sysc_md_id = findsqlval("emp_mstr", "emp_scg_emp_id", "emp_user_id", $sysc_md, $conn);
            $sysc_name_md = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname."(". $emp_th_pos_name .")";
        }
    }
	$cusd_is_sale1 = $user_scg_emp_id;
	$cusd_is_sale1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_is_sale1,$conn);
	$cusd_is_sale1_email = findsqlval("emp_mstr","emp_email_bus","emp_scg_emp_id",$cusd_is_sale1,$conn);
	$cusd_is_sale1_email = strtolower($cusd_is_sale1_email);

?>
<?php include("header.php"); ?>
<?php include("../crctrlmain/menu_header.php"); ?>
<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
<?php include("../crctrlmain/modal_cust.php"); ?>
<?php include("../crctrlmain/help_modal.php"); ?>
<!-- BEGIN: Content-->
<div id="content-box" class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row mt-n1">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../newcust/newcust_list.php">Home</a>
                            </li>
							<li class="breadcrumb-item active"><?php echo $cardtxt; ?>
                            </li>
                        </ol>
                    </div>
                </div></br>
                <!-- <h3 class="content-header-title mb-0"><?php echo $temimagerandom; ?></h3> -->
            </div>
        </div>
        <div class="card">
			<div class="card-header ">
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <!--<li><a href='#div_frm_rev_add' data-toggle='modal'><i class="fa fa-plus"></i> Add Reviewer</a></li>-->
                        <li><a title="Click to go back,hold to see history" data-action="reload"><i
                                    class="fa fa-reply-all"
                                    onclick="javascript:window.history.back();"></i></a></li>
                        <li><a title="Click to expand the screen" data-action="expand"><i
                                    class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">

					<form id="frm_cust_add" name="frm_cust_add" autocomplete=OFF method="POST"
						class="icons-tab-steps wizard-notification">
						<input type="hidden" name="action" id="action" value="cust_add">
						<input type="hidden" name="csrf_securecode"
							value="<?php echo $csrf_securecode?>">
						<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
						<input type="hidden" name="info_cust"
							value="<?php echo encrypt($info_cust, $key) ?>">
						<input type="hidden" name="form_cus" id="form_cus" value="<?php echo $action_cus ?>">
						<input type="hidden" name="temimagerandom" id="temimagerandom"
							value="<?php echo encrypt($temimagerandom, $key) ?>">
						<input type="hidden" name="book_case" id="book_case"
							value="<?php echo encrypt($book_case, $key) ?>">	

						<ul class="nav nav-tabs nav-linetriangle nav-justified">
							<?php if ($current_tab == "10") { ?>
							<?php $active = 'active'; ?>
							<?php } else { ?>
							<?php $active = ''; ?>
							<?php } ?>
							<li class="nav-item">
								<a class="nav-link active" id="activeIcon1-tab1" data-toggle="tab"
									href="#activeIcon1" aria-controls="activeIcon1" aria-expanded="true"><i
										class="ft-heart"></i> Page 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="activeIcon2-tab1" data-toggle="tab" href="#activeIcon2"
									aria-controls="activeIcon2" aria-expanded="false"><i class="ft-link"></i> Page 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="activeIcon3-tab1" data-toggle="tab" href="#activeIcon3"
									aria-controls="activeIcon3"><i class="ft-external-link"></i> Page 3</a>
							</li>
							
						</ul>
						<div class="tab-content px-1 pt-1 font-small-2">
							<?php
								if ($current_tab == "10") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
							<div role="tabpanel" class="tab-pane <?php echo $active; ?>" id="activeIcon1"
								aria-labelledby="activeIcon1-tab1" aria-expanded="true">
									<?php include("chgcusmnt_tab1.php"); ?>
							</div>

							<?php
								if ($current_tab == "20") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
							<div class="tab-pane <?php echo $active; ?>" id="activeIcon2" role="tabpanel" aria-labelledby="activeIcon2-tab1"
								aria-expanded="false">
									<?php include("chgcusmnt_tab2.php"); ?>
							</div>

							<?php
								if ($current_tab == "30") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
							<div class="tab-pane <?php echo $active; ?>" id="activeIcon3" role="tabpanel" aria-labelledby="activeIcon3-tab1"
								aria-expanded="false">
									<?php include("chgcusmnt_tab3.php"); ?>
							</div>

							<div class="bs-callout-warning callout-transparent callout-bordered showNtxPage mt-1" style="display:none">
								<div class="media align-items-stretch">
									<div class="media-left d-flex align-items-center bg-warning position-relative callout-arrow-left p-2">
										<i class="fa fa-bell-o white font-medium-5"></i>
									</div>
									<div class="media-body p-1 font-small-2 text-bold-400 text-grey">
										<strong>หมายเหตุ </strong>
										<p>กรุณาป้อนข้อมูลลูกค้า ต่อในหน้าถนัดไป 2, 3, 4, 5  ด้านบนของแบบฟอร์ม เมื่อเสร็จสิ้นการกรอกรายละเอียดลูกค้า กรุณาคลิกปุ่ม Save ข้อมูล</p>
									</div>
								</div>
							</div><br>

							<div class="form-actions right showBtnSave" style="display:none">
								<div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">
									<button type="button" id="btnsave_chg" name="btnsave_chg" value="add" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"><i class="fa fa-check-square-o"></i> Save</button>
									<button type="button" id="btnclose" name="btnclose" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" onclick="document.location.href='../newcust/newcust_list.php'"><i class="fa fa-times"></i> Close</button>
								</div>
							</div>

						</div>
					</form>
					<form name="frm_del_img" id="frm_del_img"
						action="">
						<input type="hidden" name="action" value="del_img">
						<input type="hidden" name="csrf_securecode"
							value="<?php echo $csrf_securecode ?>">
						<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
						<input type="hidden" name="image_id" value="">
						<input type="hidden" name="image_name" value="">
					</form>	
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
<!-- <div class="sidenav-overlay"></div>
<div class="drag-target"></div> -->
<? include("../crctrlmain/menu_footer.php"); ?>
<div class="to-top">
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>

<? include("file_chg_script.php"); ?>
<script type="text/javascript">
< /body> 
< /html >