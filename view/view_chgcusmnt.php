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
		$cus_app_nbr = mssql_escape($row['cus_app_nbr']);
		$cus_cond_cust = mssql_escape($row['cus_cond_cust']);
		$cus_tg_cust = mssql_escape($row['cus_tg_cust']); // domestic , export

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
				$dis_apprv = "none";
				break;		
		}   
		
		$cus_type_code = mssql_escape($row['cus_cust_type']);
		$cus_code = mssql_escape($row['cus_code']);
		$cus_reg_nme = mssql_escape($row['cus_reg_nme']);
		$cus_reg_addr = mssql_escape($row['cus_reg_addr']);
		$cus_district = mssql_escape($row['cus_district']);
		$cus_amphur = mssql_escape($row['cus_amphur']);
		$cus_prov = mssql_escape($row['cus_prov']);
		$cus_zip = mssql_escape($row['cus_zip']);
		$cus_country = mssql_escape($row['cus_country']);
		$cus_tax_id = mssql_escape($row['cus_tax_id']);
		$cus_branch = mssql_escape($row['cus_branch']);

		$cus_mas_addr = $cus_reg_addr." ".$cus_district." ".$cus_amphur." ".$cus_prov." ".$cus_zip." ".$cus_country." ".$cus_tax_id." ".$cus_branch;
		$cus_old_addr = findsqlval("cus_mstr","cus_street+' '+cus_street2+' '+cus_street3+' '+cus_street4+' '+cus_street5+' '+cus_district+' '+cus_city+' '+cus_zipcode","cus_nbr",$cus_code,$conn);

        $cus_type_bus = mssql_escape($row['cus_type_bus']);
		$cus_tel = mssql_escape($row['cus_tel']);
		$cus_fax = mssql_escape($row['cus_fax']);
		$cus_email = mssql_escape($row['cus_email']);
        $cus_cust_type_oth = mssql_escape($row['cus_cust_type_oth']);    
		$cus_effective_date = mssql_escape(dmytx($row['cus_effective_date']));
		$cus_step_code = mssql_escape($row['cus_step_code']);
		$cus_whocanread  = mssql_escape($row['cus_whocanread']);

        // -- images_mstr --//
        $temimagerandom  = mssql_escape($row['cus_tem_image']);    

        if($cus_type_code =="9") { $newbranch_input = "show"; } 
        if($cus_cond_term =="2") { $cus_cond_term_txt = "show"; } 

		$cust_code = findsqlval("cus_mstr","cus_nbr + ' ' + cus_name1","cus_nbr",$cust_code,$conn);

	}
}	

$params = array($q);
$query_det = "SELECT * FROM cus_app_det WHERE cusd_app_nbr = ?";
$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
$rowCounts = sqlsrv_num_rows($result);
if($rowCounts > 0){
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
	{

        $cusd_is_sale1 = mssql_escape($row['cusd_is_sale1']);
        $cusd_is_sale1_email = mssql_escape($row['cusd_is_sale1_email']);
        $cusd_is_sale1_tel = mssql_escape($row['cusd_is_sale1_tel']);
        $cusd_is_sale1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_is_sale1,$conn);


        $cusd_is_sale2 = mssql_escape($row['cusd_is_sale2']);
        $cusd_is_sale2_email = mssql_escape($row['cusd_is_sale2_email']);
        $cusd_is_sale2_tel = mssql_escape($row['cusd_is_sale2_tel']);
        $cusd_is_sale2_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_is_sale2,$conn);

        $cusd_os_sale = mssql_escape($row['cusd_os_sale']);
        $cusd_os_sale_email = mssql_escape($row['cusd_os_sale_email']);
        $cusd_os_sale_tel = mssql_escape($row['cusd_os_sale_tel']);
        $cusd_os_sale_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_os_sale,$conn);

        $cusd_os_sale_mgr_code = mssql_escape($row['cusd_sale_manager']);
        $cusd_manger_email = mssql_escape($row['cusd_manger_email']);
        $cusd_sale_manager_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname","emp_scg_emp_id",$cusd_os_sale_mgr_code,$conn);
        $cusd_mgr_pos = findsqlval("emp_mstr","emp_th_pos_name","emp_scg_emp_id",$cusd_os_sale_mgr_code,$conn);
        
        // -- page 5 --//
        $cusd_sale_reason = mssql_escape($row['cusd_sale_reason']);
        $cusd_op_app = mssql_escape($row['cusd_op_app']);
    }
}    

$apprv_id_array = array(); //เก็บ apprv_emp_id ผู้อนุมัติทั้งหมด
$apprv_date_array = array(); //เก็บ apprv_date วันที่อนุมัติทั้งหมด
$apprv_status_array = array();
$params = array($q);
$sql = "select * FROM apprv_person Where apprv_cus_nbr = ?  order by apprv_id asc";
    $result = sqlsrv_query($conn,$sql,$params); 
    if($result) {
        while($rows = sqlsrv_fetch_array($result)) {						
            $apprv_emp_id = $rows['apprv_emp_id'];
            $apprv_status = $rows['apprv_status'];
            if(isset($rows['apprv_date'])){
				$apprv_date = date_format($rows['apprv_date'], "d/m/Y");
			}else {
                $apprv_date = "";
            }
            if($apprv_status == "AP"){
				$apprv_status = " | <span style='color:green'><strong>*** Approved ***</strong></span>";
			}else {
				$apprv_status = "";
			}            
            array_push($apprv_id_array,$apprv_emp_id);
            array_push($apprv_date_array,$apprv_date);
            array_push($apprv_status_array,$apprv_status);
        }
    }
          
    /////////////
    $apprv_id_array_count = count($apprv_id_array);
    if($apprv_id_array_count == 1) {
        $cusd_review1 = $apprv_id_array[0];
        $cusd_review1_date = $apprv_date_array[0].' '.$apprv_status_array[0];
        $cusd_review1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review1,$conn);
    }
    else
    {    
        $cusd_review1 = $apprv_id_array[0];
        $cusd_review1_date = $apprv_date_array[0].$apprv_status_array[0];
        $cusd_review1_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review1,$conn);
        
        $cusd_review2 = $apprv_id_array[1];
        $cusd_review2_date = $apprv_date_array[1].$apprv_status_array[1];
        $cusd_review2_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review2,$conn);
       
        $cusd_review3 = $apprv_id_array[2];
        $cusd_review3_date = $apprv_date_array[2].$apprv_status_array[2];
        $cusd_review3_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review3,$conn);
        
        $cusd_review4 = $apprv_id_array[3];
        $cusd_review4_date = $apprv_date_array[3].$apprv_status_array[3];
        $cusd_review4_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_review4,$conn);
        
        $cusd_approve_fin = $apprv_id_array[4];
        $cusd_approve_fin_date = $apprv_date_array[4].$apprv_status_array[4];
        $cusd_approve_fin_name = findsqlval("emp_mstr","emp_prefix_th_name + ' ' + emp_th_firstname + ' ' + emp_th_lastname+' ('+ emp_th_pos_name +')'","emp_scg_emp_id",$cusd_approve_fin,$conn);
    }
	
$rem_revise = findsqlval("cr_app_mstr","cr_rem_revise","cr_app_nbr",$cus_app_nbr,$conn);

switch($cus_cond_cust){
    case "c3" :
		$cardtxt = "เปลี่ยนแปลงชื่อ";
        $newcus_txt = "<span style='color:DarkOrange'>ชื่อจดทะเบียน (ใหม่) :</span>";
        $newaddr_txt = "ที่อยู่จดทะเบียน :";
        $book_case = 2;
        break;
      case "c4" :
        $cardtxt = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
        $newcus_txt = "ชื่อจดทะเบียน :";
        $newaddr_txt = "<span style='color:DarkOrange'>ที่อยู่จดทะเบียน (ใหม่) :</span>";
        $oldaddr_txt = "<span style='color:DarkBlue'>ที่อยู่จดทะเบียน (เก่า) :</span>";
        $book_case = 2;
        break;  
	  case "c5" :
		$cardtxt = "เปลี่ยนแปลงชื่อและที่อยู่";
        $newcus_txt = "<span style='color:DarkOrange'>ชื่อจดทะเบียน (ใหม่) :</span>";
        $newaddr_txt = "<span style='color:DarkOrange'>ที่อยู่จดทะเบียน (ใหม่) :</span>";
        $oldaddr_txt = "<span style='color:DarkBlue'>ที่อยู่จดทะเบียน (เก่า) :</span>";
        $book_case = 2;
		break;  	
      default :
        $cardtxt = "ยกเลิกลูกค้า";
        $newcus_txt = "ชื่อจดทะเบียน :";
        $newaddr_txt = "ที่อยู่จดทะเบียน :";
        break;  
}

switch($cus_type_code){
    case "4" :   //ราชการ/รัฐวิสาหกิจ
      $cusd_op_app = "ผส.";
      $dis_apprv = "none";
      break;    
    case "8" :   //บริษัทในเครือ SCG
        $cusd_op_app = "ผส.";
      $dis_apprv = "none";
      break;      
}

$params = array($q);
$query = "SELECT * FROM  cr_app_mstr WHERE cr_app_nbr = ?";
$result = sqlsrv_query($conn, $query, $params, array("Scrollable" => 'keyset' ));
$rowCounts = sqlsrv_num_rows($result);
if($rowCounts > 0){
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
	{
		$cr_app_nbr = mssql_escape($row['cr_app_nbr']);
        $cr_sap_code = mssql_escape($row['cr_sap_code']);
        $cr_sap_code_date = mssql_escape(dmytx($row['cr_sap_code_date']));
        $cr_cus_chk_date = mssql_escape(dmytx($row['cr_cus_chk_date']));
        $cr_date_of_reg = mssql_escape(dmytx($row['cr_date_of_reg']));
        $cr_reg_capital = CheckandShowNumber(mssql_escape($row['cr_reg_capital']),2);
        $cr_bankrupt = mssql_escape($row['cr_bankrupt']);
        $cr_md_bankrupt = mssql_escape($row['cr_md_bankrupt']);
        $cr_remark = mssql_escape($row['cr_remark']);
        $cr_mgr_remark = mssql_escape($row['cr_mgr_remark']);
        $cr_mgr_status = mssql_escape($row['cr_mgr_status']);

        $cr_debt = CheckandShowNumber(mssql_escape($row['cr_debt']),2);
        $cr_due_date = mssql_escape(dmytx($row['cr_due_date']));
        $cr_so_amt = CheckandShowNumber(mssql_escape($row['cr_so_amt']),2);
        $cr_odue_amt = CheckandShowNumber(mssql_escape($row['cr_odue_amt']),2);
        $cr_rem_guarantee = mssql_escape($row['cr_rem_guarantee']);
        $cr_rem_other = mssql_escape($row['cr_rem_other']);
        $cr_sta_complete = mssql_escape($row['cr_sta_complete']);
        $cr_sta_rem = mssql_escape($row['cr_sta_rem']);

        switch($cr_sta_complete){
            case "C" :
                $cr_sta_complete_name = "Completed";
                break;
            case "I" :
                $cr_sta_complete_name = "Incomplete";
                break;  
        }
	}
}	
	
?>
<?php include("../newcust/header.php"); ?>

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
            <div class="card-header">
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <?php if (inlist("32,810,820,830",$cus_step_code)) {  ?>
                            <button class="btn btn-sm btn-danger btn-min-width btn-glow mr-1 mb-1"><i class="fa fa-bell"></i> ยกเลิกเอกสาร</button>
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
                        enctype="multipart/form-data" class="icons-tab-steps wizard-notification">
                        <input type="hidden" name="action" id="action" value="cr_edit">
                        <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
                        <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                        <input type="hidden" name="temimagerandom" id="temimagerandom"
                            value="<?php echo encrypt($temimagerandom, $key) ?>">
                        <input type="hidden" name="old_step_code" id="old_step_code"
                            value="<?php echo encrypt($cus_step_code, $key)?>">
                        <input type="hidden" name="cus_app_nbr" id="cus_app_nbr"
                            value="<?php echo encrypt($cus_app_nbr, $key)?>">
                        <input type="hidden" name="rem_revise" id="rem_revise" value="<?php echo $rem_revise ?>">
                        <input type="hidden" name="book_case" id="book_case" value="<?php echo $book_case ?>">	

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
                                    aria-controls="profileIcon22" aria-expanded="false"><i class="ft-link"></i> Page
                                    2</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profileIcon23-tab1" data-toggle="tab" href="#profileIcon23"
                                    aria-controls="profileIcon23" aria-expanded="false"><i class="ft-link"></i> Page Cr1
                                    </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profileIcon24-tab1" data-toggle="tab" href="#profileIcon24"
                                    aria-controls="profileIcon24" aria-expanded="false"><i class="ft-link"></i> Page Cr2
                                    </a>
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
                                <?php include("view_chgcusmnt_tab1.php"); ?>
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
                                <?php include("view_chgcusmnt_tab2.php"); ?>
                            </div>

                            <?php
								if ($current_tab == "30") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
                            <div class="tab-pane <?php echo $active; ?>" id="profileIcon23" role="tabpanel"
                                aria-labelledby="profileIcon23-tab1" aria-expanded="false">
                                <?php include("view_cr_chgcusmnt_tab3.php"); ?>
                            </div>

                            <?php
								if ($current_tab == "40") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
                            <div class="tab-pane <?php echo $active; ?>" id="profileIcon24" role="tabpanel"
                                aria-labelledby="profileIcon24-tab1" aria-expanded="false">
                                <?php include("view_cr_chgcusmnt_tab4.php"); ?>
                            </div>
                            
                            <div class="form-actions right" style="display:show">
                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">
                                    <button type="button" id="btnclose" name="btnclose"
                                        class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1"
                                        onclick="document.location.href='../newcust/newcust_list.php'"><i
                                            class="fa fa-times"></i> Close</button>
                                </div>
                            </div>
                        </div>

                </div>
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

<? include("file_script_view.php"); ?>
<script type="text/javascript">
< /body>  </
html >