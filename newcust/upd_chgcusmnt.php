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
//$request_tab = $_GET['current_tab'];
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

// check to acting position
$sql = "SELECT * from  sysc_ctrl ";
$result = sqlsrv_query($conn, $sql);	
$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);	
$sysc_cmo_act = mssql_escape($row['sysc_cmo_act']);
$sysc_cmo_pos_name = mssql_escape($row['sysc_cmo_pos_name']);

$sysc_cfo_act = mssql_escape($row['sysc_cfo_act']);
$sysc_cfo_pos_name = mssql_escape($row['sysc_cfo_pos_name']);

$sysc_md_act = mssql_escape($row['sysc_md_act']);
$sysc_md_pos_name = mssql_escape($row['sysc_md_pos_name']);


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
		$cus_tg_cust = html_clear($row['cus_tg_cust']); // domestic , export

		switch($cus_cond_cust){
			case "c3" :
				$readonlyC3 = "";
				$readonlyC4 = "readonly";
				$dis_apprv = "show";
				$dis_info_addr = "none"; $dis_reg_addr = "none";
				break;
			case "c4" :
				$readonlyC4 = "";
				$readonlyC3 = "readonly";
				$dis_apprv = "none";
				break;	
			case "c5" :
				$readonlyC5 = "";
				$dis_apprv = "show";
				break;	
			case "c6" :
				$readonlyC5 = "";
				$dis_apprv = "show";
				break;			
		}   
		
		$cus_type_code = html_clear($row['cus_cust_type']);
		$cus_code = html_clear($row['cus_code']);
		$cus_reg_nme = html_clear($row['cus_reg_nme']);
		$cus_reg_addr = html_clear($row['cus_reg_addr']);
		// $cus_district = html_clear($row['cus_district']);
		// $cus_amphur = html_clear($row['cus_amphur']);
		// $cus_prov = mssql_escape($row['cus_prov']);
		// $cus_zip = mssql_escape($row['cus_zip']);
		$cus_country = html_clear($row['cus_country']);
		$cus_tax_id = html_clear($row['cus_tax_id']);
		$cus_branch = html_clear($row['cus_branch']);

		$cus_mas_addr = findsqlval("cus_mstr","cus_street + ' ' + cus_street2+ ' ' + cus_street3+ ' ' + cus_district+ ' ' + cus_city+ ' ' + cus_zipcode+ ' เลขประจำตัวผู้เสียภาษี (Tax ID No.) ' + cus_tax_nbr3 +' สาขาที่ (Branch No.) ' +cus_tax_nbr4 + ' Account Group ' + cus_acc_group ","cus_nbr",$cus_code,$conn);
		$cus_type_bus = html_clear($row['cus_type_bus']);
		$cus_tel = html_clear($row['cus_tel']);
		$cus_fax = html_clear($row['cus_fax']);
		$cus_email = html_clear($row['cus_email']);
        $cus_cust_type_oth = html_clear($row['cus_cust_type_oth']);    
		$cus_effective_date = html_clear(dmytx($row['cus_effective_date']));
		$cus_step_code = html_clear($row['cus_step_code']);
		$cus_whocanread  = html_clear($row['cus_whocanread']);

        // -- images_mstr --//
        $temimagerandom  = html_clear($row['cus_tem_image']);    

        if($cus_type_code =="9") { $newbranch_input = "show"; } 
        if($cus_cond_term =="2") { $cus_cond_term_txt = "show"; } 

		// กรณี revise 51
        if($cus_step_code=="51"){
            $rev_nextstep_code = "511";
        }  
        if($cus_step_code=="52"){
            $rev_nextstep_code = "522";
        }  
		
		$cust_code = findsqlval("cus_mstr","cus_nbr + ' ' + cus_name1","cus_nbr",$cus_code,$conn);

	}
}	

$params = array($q);
$query_det = "SELECT * FROM cus_app_det WHERE cusd_app_nbr = ?";
$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
$rowCounts = sqlsrv_num_rows($result);
if($rowCounts > 0){
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
	{

        $cusd_is_sale1 = html_clear($row['cusd_is_sale1']);
        $cusd_is_sale1_email = html_clear($row['cusd_is_sale1_email']);
        $cusd_is_sale1_tel = html_clear($row['cusd_is_sale1_tel']);
        $cusd_is_sale1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_is_sale1,$conn);


        $cusd_is_sale2 = html_clear($row['cusd_is_sale2']);
        $cusd_is_sale2_email = html_clear($row['cusd_is_sale2_email']);
        $cusd_is_sale2_tel = html_clear($row['cusd_is_sale2_tel']);
        $cusd_is_sale2_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_is_sale2,$conn);

        $cusd_os_sale = html_clear($row['cusd_os_sale']);
        $cusd_os_sale_email = html_clear($row['cusd_os_sale_email']);
        $cusd_os_sale_tel = html_clear($row['cusd_os_sale_tel']);
        $cusd_os_sale_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_os_sale,$conn);

        $cusd_os_sale_mgr_code = html_clear($row['cusd_sale_manager']);
        $cusd_manger_email = html_clear($row['cusd_manger_email']);
        $cusd_sale_manager_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_os_sale_mgr_code,$conn);
        $cusd_mgr_pos = findsqlval("emp_mstr","emp_th_pos_name","emp_scg_emp_id",$cusd_os_sale_mgr_code,$conn);
        
        // -- page 5 --//
        $cusd_sale_reason = html_clear($row['cusd_sale_reason']);
        $cusd_op_app = html_clear($row['cusd_op_app']);

    }
}    

$apprv_id_array = array(); //เก็บ apprv_emp_id ผู้อนุมัติทั้งหมด
$params_stamp = array($q);
$sql_stamp = "select * FROM apprv_person Where apprv_cus_nbr = ?  order by apprv_seq,apprv_id asc";
    $result_stamp = sqlsrv_query($conn,$sql_stamp,$params_stamp); 
    if($result_stamp) {
        while($row_stamp = sqlsrv_fetch_array($result_stamp)) {						
            $apprv_emp_id = $row_stamp['apprv_emp_id'];
            array_push($apprv_id_array,$apprv_emp_id);
        }
    }
    /////////////
    $apprv_id_array_count = count($apprv_id_array);
    if($apprv_id_array_count == 1) {
        $cusd_review1 = $apprv_id_array[0];
        $cusd_review1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review1,$conn);
    }
    else
    {    
        $cusd_review1 = $apprv_id_array[0];
        $cusd_review1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review1,$conn);
        $cusd_review2 = $apprv_id_array[1];
        $cusd_review2_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review2,$conn);
        $cusd_review3 = $apprv_id_array[2];
        if($sysc_cmo_act!=1){
            $cusd_review3_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review3,$conn);
        } 
        else 
        {
            $cusd_review3_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname ","emp_scg_emp_id",$cusd_review3,$conn);
            $cusd_review3_name = $cusd_review3_name. "(รักษาการ " .$sysc_cmo_pos_name. ")";
        }

        $cusd_review4 = $apprv_id_array[3];
        if($sysc_cfo_act!=1){
            $cusd_review4_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review4,$conn);
        } 
        else 
        {
            $cusd_review4_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname ","emp_scg_emp_id",$cusd_review4,$conn);
            $cusd_review4_name = $cusd_review4_name. "(รักษาการ " .$sysc_cfo_pos_name. ")";
        }

        $cusd_approve_fin = $apprv_id_array[4];
        if($sysc_md_act!=1){
            $cusd_approve_fin_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_approve_fin,$conn);
        } 
        else 
        {
            $cusd_approve_fin_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname ","emp_scg_emp_id",$cusd_approve_fin,$conn);
            $cusd_approve_fin_name = $cusd_approve_fin_name. "(รักษาการ " .$sysc_md_pos_name. ")";
        }
    }
	
$rem_revise = findsqlval("cr_app_mstr","cr_rem_revise","cr_app_nbr",$cus_app_nbr,$conn);
  
    switch($cus_cond_cust){
      case "c3" :
		$cardtxt = "เปลี่ยนแปลงชื่อ";
       
        break;
      case "c4" :
        $cardtxt = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
       
        break;  
	  case "c5" :
		$cardtxt = "เปลี่ยนแปลงชื่อและที่อยู่";
		
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
                <!-- <h3 class="content-header-title mb-0"><?php echo $temimagerandom; ?></h3> -->
            </div>
        </div>
        <div class="card">
            <div class="card-header ">
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <?php if($cus_step_code=="21") { ?>
                        <button id="btnshowtext"
                            class="btn btn-sm btn-outline-warning btn-min-width btn-glow mr-1 mb-1"><i
                                class="fa fa-bell"></i> มีข้อความถึงคุณ</button>
                        <?php } ?>
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
                        <input type="hidden" name="action" id="action" value="cust_edit">
                        <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
                        <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                        <input type="hidden" name="info_cust" value="<?php echo encrypt($info_cust, $key) ?>">
                        <input type="hidden" name="form_cus" id="form_cus" value="<?php echo $action_cus ?>">
                        <input type="hidden" name="temimagerandom" id="temimagerandom"
                            value="<?php echo encrypt($temimagerandom, $key) ?>">
                        <input type="hidden" name="cus_app_nbr" id="cus_app_nbr"
                            value="<?php echo encrypt($cus_app_nbr, $key)?>">
                        <input type="hidden" name="old_step_code" id="old_step_code"
                            value="<?php echo encrypt($cus_step_code, $key)?>">
                        <input type="hidden" name="rem_revise" id="rem_revise" value="<?php echo $rem_revise ?>">
                        <input type="hidden" name="cus_whocanread" id="cus_whocanread"
                            value="<?php echo $cus_whocanread ?>">

                        <ul class="nav nav-tabs nav-linetriangle nav-justified">
                            <?php if ($current_tab == "10") { ?>
                            <?php $active = 'active'; ?>
                            <?php } else { ?>
                            <?php $active = ''; ?>
                            <?php } ?>
                            <li class="nav-item">
                                <a class="nav-link active" id="activeIcon1-tab1" data-toggle="tab" href="#activeIcon1"
                                    aria-controls="activeIcon1" aria-expanded="true"><i class="ft-heart"></i> Page 1</a>
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
                                <?php include("upd_chgcusmnt_tab1.php"); ?>
                            </div>

                            <?php
								if ($current_tab == "20") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
                            <div class="tab-pane <?php echo $active; ?>" id="activeIcon2" role="tabpanel"
                                aria-labelledby="activeIcon2-tab1" aria-expanded="false">
                                <?php include("upd_chgcusmnt_tab2.php"); ?>
                            </div>

                            <?php
								if ($current_tab == "30") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
                            <div class="tab-pane <?php echo $active; ?>" id="activeIcon3" role="tabpanel"
                                aria-labelledby="activeIcon3-tab1" aria-expanded="false">
                                <?php include("upd_chgcusmnt_tab3.php"); ?>
                            </div>

                            <div class="bs-callout-warning callout-transparent callout-bordered showNtxPage"
                                style="display:none">
                                <div class="media align-items-stretch">
                                    <div
                                        class="media-left d-flex align-items-center bg-warning position-relative callout-arrow-left p-2">
                                        <i class="fa fa-bell-o white font-medium-5"></i>
                                    </div>
                                    <div class="media-body p-1 font-small-2 text-bold-400 text-grey">
                                        <strong>หมายเหตุ </strong>
                                        <p>กรุณาป้อนข้อมูลลูกค้า ต่อในหน้าถนัดไป 2, 3, 4, 5 ด้านบนของแบบฟอร์ม
                                            เมื่อเสร็จสิ้นการกรอกรายละเอียดลูกค้า กรุณาคลิกปุ่ม Save ข้อมูล</p>
                                    </div>
                                </div>
                            </div><br>

                            <div class="form-actions right showBtnSave" style="display:none">
                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">

                                    <?php if(($can_editing) && ($cus_step_code=="0")) { ?>
                                    <?php if($apprv_id_array_count == 1) {?>
                                    <button type="button" id="btnsale_sub" name="btnsale_sub"
                                        class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1"
                                        style="display:show;"
                                        onclick="salesubmit('frm_submit','<?php echo encrypt('10', $key);?>','<?php echo $cus_app_nbr; ?>')"><i
                                            class="fa fa-paper-plane"></i> Submit</button>
                                    <!--Submit to Credit-->
                                    <?php } else { ?>
                                    <button type="button" id="btnsale_sub" name="btnsale_sub"
                                        class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1"
                                        style="display:show;"
                                        onclick="salesubmit('frm_reviewer','<?php echo encrypt('40', $key);?>','<?php echo $cus_app_nbr; ?>')"><i
                                            class="fa fa-envelope"></i> Submit </button>
                                    <!--Submit to Reviewer-->
                                    <? } ?>
                                    <? } ?>

                                    <?php if(($can_editing) && ($cus_step_code=="21")) { ?>
                                    <!-- Revising by Credit -->
                                    <button type="button" id="btnsale_sub" name="btnsale_sub"
                                        class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1"
                                        style="display:show;"
                                        onclick="salesubmit('frm_revise_cr','<?php echo encrypt('20', $key);?>','<?php echo $cus_app_nbr; ?>')"><i
                                            class="fa fa-spinner"></i> Submit </button>
                                    <!--Submit to Credit-->
                                    <? } ?>
                                    <?php if(($can_editing) && ($cus_step_code=="51") || ($cus_step_code=="52")) { ?>
                                    <!-- Revising by SEC -->
                                    <button type="button" id="btnsale_sub" name="btnsale_sub"
                                        class="btn btn-outline-info btn-min-width btn-glow mr-1 mb-1"
                                        style="display:show;"
                                        onclick="salesubmit('frm_revise_rev','<?php echo encrypt($rev_nextstep_code, $key);?>','<?php echo $cus_app_nbr; ?>')"><i
                                            class="fa fa-spinner"></i> Submit </button>
                                    <!--Submit Reviewer Revise-->
                                    <? } ?>
                                    <button type="button" id="btnsave_chg" name="btnsave_chg" value="add"
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

<? include("file_chg_script.php"); ?>
<script type="text/javascript">
< /body>  <
/html >