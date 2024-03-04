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
      case "c1" :
        $cardtxt = "แต่งตั้งลูกค้าใหม่";
        $info_cust = "แต่งตั้งลูกค้าใหม่";
        $cusd_op_app = "กจก.";
        $newbranch = "none";
		$page3 = "show";
		$book_case = 1;
        break;
      case "c2" :
        $cardtxt = "แต่งตั้งร้านสาขา";
        $info_cust = "แต่งตั้งร้านสาขา";
        $cusd_op_app = "ผส.";
        $newbranch = "none";
		$page3 = "none";
		$book_case = 1;
        break;  
      default :
        $cardtxt = "เปลี่ยนแปลงข้อมูลลูกค้า";
        $info_cust = "เปลี่ยนแปลงข้อมูลลูกค้า";
        $cusd_op_app = "กจก.";
        $newbranch = "none";
		$book_case = 1;
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
						<input type="hidden" name="form_cus" value="<?php echo $action_cus ?>">
						<input type="hidden" name="temimagerandom" id="temimagerandom"
							value="<?php echo encrypt($temimagerandom, $key) ?>"> 
						<input type="hidden" name="book_case" id="book_case"
							value="<?php echo encrypt($book_case, $key) ?>">	

						<ul class="nav nav-tabs nav-linetriangle nav-justified">
							<?php if ($current_tab == "10") { ?>
							<?php $active = 'active'; ?>
							<?php } else { ?>
							<?php $active1 = ''; ?>
							<?php } ?>
							<li class="nav-item">
								<a class="nav-link <?php echo $active; ?>" id="activeIcon22-tab1" data-toggle="tab"
									href="#activeIcon22" aria-controls="activeIcon22" aria-expanded="true"><i
										class="ft-heart"></i> Page 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="profileIcon22-tab1" data-toggle="tab" href="#profileIcon22"
									aria-controls="profileIcon22" aria-expanded="false"><i class="ft-link"></i> Page 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="aboutIcon21-tab1" data-toggle="tab" href="#aboutIcon21"
									aria-controls="aboutIcon21"><i class="ft-external-link"></i> Page 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?php echo $active1; ?>" id="linkIcon21-tab1" data-toggle="tab" href="#linkIcon21"
									aria-controls="linkIcon21"><i class="fa fa-flag"></i> Page 4</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="helpIcon21-tab1" data-toggle="tab" href="#helpIcon21"
									aria-controls="helpIcon21"><i class="fa fa-send-o"></i> Page 5</a>
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
							<div role="tabpanel" class="tab-pane <?php echo $active; ?>" id="activeIcon22"
								aria-labelledby="activeIcon22-tab1" aria-expanded="true">
									<?php include("newcusmnt_tab1.php"); ?>
								<!-- <p>Macaroon candy canes tootsie roll wafer lemon drops liquorice jelly-o tootsie roll cake.
									Marzipan liquorice soufflé cotton candy jelly cake jelly-o sugar plum marshmallow.
									Dessert cotton candy macaroon chocolate sugar plum cake donut.</p> -->
							</div>

							<?php
								if ($current_tab == "20") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
							<div class="tab-pane <?php echo $active; ?>" id="profileIcon22" role="tabpanel" aria-labelledby="profileIcon22-tab1"
								aria-expanded="false">
									<?php include("newcusmnt_tab2.php"); ?>
								<!-- <p>Chocolate bar gummies sesame snaps. Liquorice cake sesame snaps cotton candy cake sweet
									brownie. Cotton candy candy canes brownie. Biscuit pudding sesame snaps pudding pudding
									sesame snaps biscuit tiramisu.</p> -->
							</div>

							<?php
								if ($current_tab == "30") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
							<div class="tab-pane <?php echo $active; ?>" id="aboutIcon21" role="tabpanel" aria-labelledby="aboutIcon21-tab1"
								aria-expanded="false">
									<?php include("newcusmnt_tab3.php"); ?>
								<!-- <p>Fruitcake marshmallow donut wafer pastry chocolate topping cake. Powder powder gummi
									bears jelly beans. Gingerbread cake chocolate lollipop. Jelly oat cake pastry
									marshmallow sesame snaps.</p> -->
							</div>
							
							<?php
								if ($current_tab == "40") {
									$active1 = 'active';
									} else {
									$active1 = '';
								}
							?>
							<div class="tab-pane <?php echo $active1; ?>" id="linkIcon21" role="tabpanel" aria-labelledby="linkIcon21-tab1"
								aria-expanded="false">
									<?php include("newcusmnt_tab4.php"); ?>
								<!-- <p>Cookie icing tootsie roll cupcake jelly-o sesame snaps. Gummies cookie dragée cake jelly
									marzipan donut pie macaroon. Gingerbread powder chocolate cake icing. Cheesecake gummi
									bears ice cream marzipan.</p> -->
							</div>

							<?php
								if ($current_tab == "50") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
							<div class="tab-pane <?php echo $active; ?>" id="helpIcon21" role="tabpanel" aria-labelledby="helpIcon21-tab1"
								aria-expanded="false">
									<?php include("newcusmnt_tab5.php"); ?>
								<!-- <p>Cookie icing tootsie roll cupcake jelly-o sesame snaps. Gummies cookie dragée cake jelly
									marzipan donut pie macaroon. Gingerbread powder chocolate cake icing. Cheesecake gummi
									bears ice cream marzipan.</p> -->
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
									<button type="button" id="btnsave" name="btnsave" value="add" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"><i class="fa fa-check-square-o"></i> Save</button>
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

<? include("file_script.php"); ?>
<script type="text/javascript">
< /body> 
< /html >