<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
include("../crctrlbof/chkauthcrctrl.php");		
include_once('../_libs/Thaidate/Thaidate.php');
include_once('../_libs/Thaidate/thaidate-functions.php');
	
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

$q = decrypt(mssql_escape($_REQUEST['q']), $key);
$params = array($q);
$query = "SELECT * FROM  cus_app_mstr WHERE cus_app_nbr = ?";
$result = sqlsrv_query($conn, $query, $params, array("Scrollable" => 'keyset' ));
$rowCounts = sqlsrv_num_rows($result);
if($rowCounts > 0){
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
	{
		$cus_app_nbr = html_clear($row['cus_app_nbr']);
		$cus_cond_cust = html_clear($row['cus_cond_cust']);
        $cus_code = mssql_escape($row['cus_code']);
		$cus_reg_nme = mssql_escape($row['cus_reg_nme']);
        $cus_step_code = html_clear($row['cus_step_code']);
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
    case "c3" :
      $cardtxt = "เปลี่ยนแปลงชื่อ";
      $book_case = 2;
      break;
    case "c4" :
      $cardtxt = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
      $book_case = 2;
      break;  
    case "c5" :
      $cardtxt = "เปลี่ยนแปลงชื่อและที่อยู่";
      $book_case = 2;
      break;  	
}

$params = array($q);
$query = "SELECT * FROM  cr_app_mstr WHERE cr_app_nbr = ?";
$result = sqlsrv_query($conn, $query, $params, array("Scrollable" => 'keyset' ));
$rowCounts = sqlsrv_num_rows($result);
if($rowCounts > 0){
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
	{
		$cr_app_nbr = html_clear($row['cr_app_nbr']);
        $cr_sap_code = html_clear($row['cr_sap_code']);
        $cr_sap_code_date = html_clear(dmytx($row['cr_sap_code_date']));
        $cr_cus_chk_date = html_clear(dmytx($row['cr_cus_chk_date']));
        $cr_date_of_reg = html_clear(dmytx($row['cr_date_of_reg']));
        $cr_reg_capital = CheckandShowNumber(html_clear($row['cr_reg_capital']),2);
        $cr_bankrupt = html_clear($row['cr_bankrupt']);
        $cr_md_bankrupt = html_clear($row['cr_md_bankrupt']);
        $cr_remark = html_clear($row['cr_remark']);
        $cr_mgr_remark = html_clear($row['cr_mgr_remark']);
        $cr_mgr_status = html_clear($row['cr_mgr_status']);
        $cr_status = html_clear($row['cr_status']);
        $cr_debt = CheckandShowNumber(html_clear($row['cr_debt']),2);
        $cr_due_date = html_clear(dmytx($row['cr_due_date']));
        $cr_so_amt = CheckandShowNumber(html_clear($row['cr_so_amt']),2);
        $cr_odue_amt = CheckandShowNumber(html_clear($row['cr_odue_amt']),2);
        $cr_rem_guarantee = html_clear($row['cr_rem_guarantee']);
        $cr_rem_other = html_clear($row['cr_rem_other']);
        $cr_sta_complete = html_clear($row['cr_sta_complete']);
        $cr_sta_rem = html_clear($row['cr_sta_rem']);
        $cr_rem_revise =  html_clear($row['cr_rem_revise']);
	}
}	
 
$apprv_id_array = array(); //เก็บ apprv_emp_id ผู้อนุมัติทั้งหมด
$params_stamp = array($q);
$sql_stamp = "select * FROM apprv_person Where apprv_cus_nbr = ?  order by apprv_id asc";
    $result_stamp = sqlsrv_query($conn,$sql_stamp,$params_stamp); 
    if($result_stamp) {
        while($row_stamp = sqlsrv_fetch_array($result_stamp)) {						
            $apprv_emp_id = $row_stamp['apprv_emp_id'];
            array_push($apprv_id_array,$apprv_emp_id);
        }
    }
    $apprv_id_array_count = count($apprv_id_array);
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
                            <li class="breadcrumb-item active">Doc No <?php echo $cus_app_nbr; ?>
                            </li>
                        </ol>
                    </div>
                </div></br>
               <!-- <h3 class="content-header-title mb-0"><?php echo $cus_cond_cust; ?></h3>   -->
            </div>

            <div class="content-header-right col-md-6 col-12">
                <div class="btn-group float-md-right">
                    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-settings mr-1"></i>Action</button>
                        <div class="dropdown-menu arrow"><a class="dropdown-item blue" href="../newcust/upd_chgcusmnt.php?q=<?php echo encrypt($cus_app_nbr, $key); ?>" target="_blank"><i class="fa fa-file-text-o mr-1"></i>ดูรายละเอียดเอกสาร</a>
                        <?php if (!inlist("10,20",$cus_step_code)) {?>
                            <div class="dropdown-divider"></div><a class="dropdown-item blue" href="#div_frm_remind" data-toggle="modal"><i class="fa fa-undo mr-1"></i> Remind Email</a> 
                        <?php } ?>    
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
                        <input type="hidden" name="action" id="action" value="cr_edit_chg">
                        <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
                        <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                        <input type="hidden" name="info_cust" value="<?php echo encrypt($info_cust, $key) ?>">
                        <input type="hidden" name="form_cus" value="<?php echo $cus_cond_cust ?>">
                        <input type="hidden" name="objArrayCount" id="objArrayCount" value="<?php echo $objArrayCount ?>">
                        <input type="hidden" name="projArrayCount" id="projArrayCount" value="<?php echo $projArrayCount ?>">
                        <input type="hidden" name="affArrayCount" id="affArrayCount" value="<?php echo $affArrayCount ?>">
                        <input type="hidden" name="dealerArrayCount" id="dealerArrayCount" value="<?php echo $dealerArrayCount ?>">
                        <input type="hidden" name="compArrayCount"  id="compArrayCount" value="<?php echo $compArrayCount ?>">
                        <input type="hidden" name="temimagerandom" id="temimagerandom"
                        value="<?php echo encrypt($temimagerandom, $key) ?>"> 
                        <input type="hidden" name="search_app_nbr" id="search_app_nbr"
                        value="<?php echo $cus_app_nbr ?>">
                        <input type="hidden" name="cr_step_code" id="cr_step_code">
                        <input type="hidden" name="cus_app_nbr" id="cus_app_nbr" value="<?php echo encrypt($cus_app_nbr, $key)?>">
                        <input type="hidden" name="book_case" id="book_case" value="<?php echo $book_case ?>">
                        	
                        <ul class="nav nav-tabs nav-linetriangle nav-justified">
                            <?php if ($current_tab == "10") { ?>
                            <?php $active = 'active'; ?>
                            <?php } else { ?>
                            <?php $active1 = 'active'; ?>
                            <?php } ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active; ?>" id="activeIcon22-tab1" data-toggle="tab" href="#activeIcon22"
                                    aria-controls="activeIcon22" aria-expanded="true"><i class="ft-heart"></i> Page
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
                                <?php include("upd_cr_chgcusmnt_tab1.php"); ?>
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
                                <?php include("upd_cr_chgcusmnt_tab2.php"); ?>
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

                            <div class="form-actions right _showBtnSave" style="display:show">
                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">
                                    <button type="button"  id="btnrevise_cr" name="btnrevise_cr" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1" style="display:none;" onclick="dispostform('frm_revise','<?php echo encrypt('21', $key); ?>','<?php echo $cr_app_nbr; ?>','<?php echo 'cr_revise'; ?>')"><i class="fa fa-comments-o"></i> Revise</button>
                                    <?php if(($can_edit_cr) && ($cus_step_code=="20")) { ?>
                                        <button type="button"  id="btnsubmit_cr" name="btnsubmit_cr" class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1" style="display:none;" onclick="dispostform('frm_cr_submit_mgr','<?php echo encrypt('30', $key); ?>','<?php echo $cr_app_nbr; ?>','<?php echo 'cr_submit_mgr'; ?>')"><i class="fa fa-paper-plane"></i> Submit</button>
                                    <? } ?>  
                                    <?php if(($can_edit_mgr) && ($cus_step_code=="30")) { ?>
                                        <?php if($apprv_id_array_count == 1) {?>
                                            <button type="button"  id="btnsubmit_mgr" name="btnsubmit_mgr" class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1" style="display:none;" onclick="dispostform('frm_cr_submit_app','<?php echo encrypt('50', $key); ?>','<?php echo $cr_app_nbr; ?>','<?php echo 'cr_submit_app'; ?>')"><i class="fa fa-paper-plane"></i> Submit</button>
                                        <?php } else { ?>
                                            <button type="button"  id="btnsubmit_mgr" name="btnsubmit_mgr" class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1" style="display:none;" onclick="dispostform('frm_cr_submit_app','<?php echo encrypt('61', $key); ?>','<?php echo $cr_app_nbr; ?>','<?php echo 'cr_submit_app'; ?>')"><i class="fa fa-paper-plane"></i> Submit</button>
                                        <? } ?> 
                                    <? } ?>    

                                    <button type="button" id="btnsave_chg" name="btnsave_chg" 
                                        class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"><i
                                            class="fa fa-check-square-o"></i> Save</button>
                                    <button type="button" id="btnclose" name="btnclose"
                                        class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1" onclick="document.location.href='../newcust/newcust_list.php'"><i
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
<script type="text/javascript">
< /body>  <
/html >