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

clearstatcache();	
set_time_limit(0);
$curdate = date('Ymd');
$params = array();
$default_current_tab = "10";
$newbranch_input = "none"; $cus_cond_term_txt = "none";
//$request_tab = $_GET['current_tab'];
$request_tab = mssql_escape(decrypt($_REQUEST['current_tab'], $key));
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

//$temimagerandom = mt_rand(10000000,99999999); 

$q = decrypt(mssql_escape($_REQUEST['q']), $key);
$params = array($q);
$query = "SELECT * FROM  cus_app_mstr WHERE cus_app_nbr = ?";
$result = sqlsrv_query($conn, $query, $params, array("Scrollable" => 'keyset' ));
$rowCounts = sqlsrv_num_rows($result);
if($rowCounts > 0){
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
	{
		$cus_app_nbr = html_clear($row['cus_app_nbr']);
        $cus_reg_nme = html_clear($row['cus_reg_nme']);
        $cus_step_code = html_clear($row['cus_step_code']);
		$cus_cond_cust = html_clear($row['cus_cond_cust']);
		$cus_tg_cust = html_clear($row['cus_tg_cust']); // domestic , export
		if($cus_tg_cust=="dom"){
			$dom_show = "show";
			$exp_show = "none";
		}
		else
		{
			$dom_show = "none";
			$exp_show = "show";
		} 
		$cus_type_code = html_clear($row['cus_cust_type']);
	}
}	


switch($cus_cond_cust){
	case "c1" :
	  $cardtxt = "แต่งตั้งลูกค้าใหม่";
	  $info_cust = "ตั้งลูกค้าใหม่";
	  $cusd_op_app = "กจก.";
      $book_case = 1;
	  break;
	case "c2" :
	  $cardtxt = "แต่งตั้งร้านสาขา";
	  $info_cust = "แต่งตั้งร้านสาขา";
	  $cusd_op_app = "ผส.";
      $book_case = 1;
	  break;  
  }
 
?>
<?php include("header.php"); ?>
<?php include("../crctrlmain/menu_header.php"); ?>
<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
<?php include("../crctrlmain/modal_cust.php"); ?>
<?php include("../crctrlmain/help_modal.php"); ?>
<?php include("../crctrlmain/modal.php"); ?>

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
                            <li class="breadcrumb-item active">Doc No <?php echo $cus_app_nbr; ?>
                            </li>
                        </ol>
                    </div>
                </div></br>
                <!-- <h3 class="content-header-title mb-0"><?php echo $current_tab; ?></h3>  -->
            </div>

            <div class="content-header-right col-md-6 col-12">
                <div class="btn-group float-md-right">
                    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-settings mr-1"></i>Action</button>
                        <div class="dropdown-menu arrow"><a class="dropdown-item blue" href="../newcust/upd_newcusmnt.php?q=<?php echo encrypt($cus_app_nbr, $key); ?>" target="_blank"><i class="fa fa-file-text-o mr-1"></i>ดูรายละเอียดเอกสาร</a>
                        <!-- <div class="dropdown-divider"></div><a class="dropdown-item blue" href="#div_frm_remind" data-toggle="modal"><i class="fa fa-undo mr-1"></i> Remind Email</a> -->
                        <div class="dropdown-divider"></div><a class="dropdown-item blue" href="../crcust/cr_form_newcus.php?crnumber=<?php echo encrypt($cus_app_nbr, $key);?>" target="_blank"><i class="ft-printer mr-1"></i>พิมพ์ใบขออนุมัติ<?php echo($cardtxt);?></a> 
                        <?php if (!inlist("60",$cus_step_code)) {?>
                            <div class="dropdown-divider"></div><a class="dropdown-item danger" href="#div_frm_reject" data-toggle="modal"><i class="fa fa-times-circle mr-1"></i>ยกเลิกเอกสาร<?php echo($cardtxt);?></a> 
                        <?php } ?>    
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header ">
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <!-- <li><a href="../newcust/upd_newcusmnt.php?q=<?php echo encrypt($cus_app_nbr, $key); ?>" target="_blank"><i class="fa fa-plus"></i> ดูรายละเอียดเอกสาร</a></li> -->
                        <li><a title="Click to go back,hold to see history" data-action="reload"><i
                                    class="fa fa-reply-all" onclick="javascript:window.history.back();"></i></a></li>
                        <li><a title="Click to expand the screen" data-action="expand"><i class="ft-maximize"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">

                    <form id="frm_cust_add" name="frm_cust_add" autocomplete=OFF method="POST"
                        class="icons-tab-steps wizard-notification">
                        <input type="hidden" name="action" id="action" value="cr_add">
                        <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
                        <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                        <input type="hidden" name="info_cust" value="<?php echo encrypt($info_cust, $key) ?>">
                        <input type="hidden" name="form_cus" value="<?php echo $cus_cond_cust ?>">
                        <input type="hidden" name="cr_step_code" id="cr_step_code">
                        <input type="hidden" name="cus_app_nbr" id="cus_app_nbr" value="<?php echo encrypt($cus_app_nbr, $key)?>">
                        <input type="hidden" name="temimagerandom" id="temimagerandom"
                            value="<?php echo encrypt($temimagerandom, $key) ?>">

                        <!-- <input type="hidden" name="numbers_arr[]" >-->
                        <input type="hidden" name="cus_app_nbr" id="cus_app_nbr"
                            value="<?php echo encrypt($cus_app_nbr, $key)?>">

                        <input type="hidden" name="search_app_nbr" id="search_app_nbr"
                        value="<?php echo $cus_app_nbr ?>">
						<input type="hidden" name="book_case" id="book_case"
							value="<?php echo $book_case ?>">	

                        <ul class="nav nav-tabs nav-linetriangle nav-justified">
                            <?php if ($current_tab == "10") { ?>
                            <?php $active = 'active'; ?>
                            <?php } else { ?>
                            <?php $active1 = 'active'; ?>
                            <?php } ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active; ?>" id="activeIcon22-tab1" data-toggle="tab"
                                    href="#activeIcon22" aria-controls="activeIcon22" aria-expanded="true"><i
                                        class="ft-heart"></i> Page
                                    1</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profileIcon22-tab1" data-toggle="tab" href="#profileIcon22"
                                    aria-controls="profileIcon22" aria-expanded="false"><i class="ft-link"></i> Page
                                    2</a>
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
                                <?php include("cr_newcusmnt_tab1.php"); ?>
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
                            <div class="tab-pane <?php echo $active; ?>" id="profileIcon22" role="tabpanel"
                                aria-labelledby="profileIcon22-tab1" aria-expanded="false">
                                <?php include("cr_newcusmnt_tab2.php"); ?>
                                <!-- <p>Chocolate bar gummies sesame snaps. Liquorice cake sesame snaps cotton candy cake sweet
									brownie. Cotton candy candy canes brownie. Biscuit pudding sesame snaps pudding pudding
									sesame snaps biscuit tiramisu.</p> -->
                            </div>


                            <div class="bs-callout-warning callout-transparent callout-bordered showNtxPage mt-1"
                                style="display:show">
                                <div class="media align-items-stretch">
                                    <div
                                        class="media-left d-flex align-items-center bg-warning position-relative callout-arrow-left p-2">
                                        <i class="fa fa-bell-o white font-medium-5"></i>
                                    </div>
                                    <div class="media-body p-1 font-small-2 text-bold-400 text-grey">
                                        <strong>หมายเหตุ </strong>
                                        <p>กรุณาป้อนข้อมูลลูกค้า ต่อในหน้าถนัดไป 2 ด้านบนของแบบฟอร์ม
                                            เมื่อเสร็จสิ้นการกรอกรายละเอียดลูกค้า กรุณาคลิกปุ่ม Save ข้อมูล</p>
                                    </div>
                                </div>
                            </div><br>

                            <div class="form-actions right showBtnSave" style="display:show">
                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">
                                    <button type="button" id="btnsave_cr" name="btnsave_cr" value="edit"
                                        class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"><i
                                            class="fa fa-check-square-o"></i> Save</button>
                                    <button type="button" id="btnclose" name="btnclose"
                                        class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1"
                                        onclick="document.location.href='../newcust/newcust_list.php'"><i
                                            class="fa fa-times"></i> Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form name="frm_del_img" id="frm_del_img" action="">
                        <input type="hidden" name="action" value="del_img">
                        <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
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


<? include("../crcust/file_cr_script.php"); ?>
<script type="text/javascript" language="javascript" class="init">
< /body> 
< /html >