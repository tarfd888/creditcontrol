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
//$q = 'NC-2308-0001';

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
		if($cus_tg_cust=="dom"){
			$dom_show = "show";
			$exp_show = "none";
		}
		else
		{
			$dom_show = "none";
			$exp_show = "show";
		} 
		$cus_type_code = mssql_escape($row['cus_cust_type']);
		$cus_reg_nme = mssql_escape($row['cus_reg_nme']);
		$cus_reg_addr = mssql_escape($row['cus_reg_addr']);
		$cus_district = mssql_escape($row['cus_district']);
		$cus_amphur = mssql_escape($row['cus_amphur']);
		$cus_prov = mssql_escape($row['cus_prov']);
		$cus_zip = mssql_escape($row['cus_zip']);
		$cus_country = mssql_escape($row['cus_country']);
		$cus_tax_id = mssql_escape($row['cus_tax_id']);
		$cus_branch = mssql_escape($row['cus_branch']);

		$cus_type_bus = mssql_escape($row['cus_type_bus']);
        $cus_type_bus_name = findsqlval("cus_tyofbus_mstr","cus_tyofbus_name","cus_tyofbus_id",$cus_type_bus,$conn);


		$cus_tel = mssql_escape($row['cus_tel']);
		$cus_fax = mssql_escape($row['cus_fax']);
		$cus_email = mssql_escape($row['cus_email']);
        $cus_cust_type_oth = mssql_escape($row['cus_cust_type_oth']);    


        $cus_contact1_nme = mssql_escape($row['cus_contact1_nme']);
        $cus_contact1_pos  = mssql_escape($row['cus_contact1_pos']);
        $cus_contact2_nme = mssql_escape($row['cus_contact2_nme']);
        $cus_contact2_pos  = mssql_escape($row['cus_contact2_pos']);
        $contactArray = array();	//เก็บชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า
        $contactPosArray = array(); //เก็บตำแหน่ง
        if($cus_contact1_nme !="") { array_push($contactArray,$cus_contact1_nme); 	array_push($contactPosArray, $cus_contact1_pos); }
        if($cus_contact2_nme !="") { array_push($contactArray,$cus_contact2_nme); 	array_push($contactPosArray, $cus_contact2_pos); }
        $contactArrayCount = count($contactArray);
      
        // -- page 2 --//
		$cus_term = mssql_escape($row['cus_term']);
        $cus_term_name = findsqlval("term_mstr","term_desc","term_code",$cus_term,$conn);

		$cus_bg1 = mssql_escape($row['cus_bg1']);
        $cus_bg1_name = findsqlval("bank_mstr","bank_th_name","bank_code",$cus_bg1,$conn);

		$cus_cr_limit1 = CheckandShowNumber(mssql_escape($row['cus_cr_limit1']),2);
		$cus_bg2 = mssql_escape($row['cus_bg2']);
		$cus_cr_limit2 = CheckandShowNumber(mssql_escape($row['cus_cr_limit2']),2);

		$cus_cond_term = mssql_escape($row['cus_cond_term']);
        $cus_cond_term_oth = mssql_escape($row['cus_cond_term_oth']);   
        if($cus_cond_term=="1"){
            $cus_cond_term_name = "ชำระทุกวันตาม Due";
        } else {
            $cus_cond_term_name = "มีเงื่อนไขการวางบิลหรือชำระเงินพิเศษ |  ".$cus_cond_term_oth;
        }

		$cus_pay_addr = mssql_escape($row['cus_pay_addr']);
		$cus_contact_nme_pay = mssql_escape($row['cus_contact_nme_pay']);
		$cus_contact_tel = mssql_escape($row['cus_contact_tel']);
		$cus_contact_fax = mssql_escape($row['cus_contact_fax']);
		$cus_contact_email = mssql_escape($row['cus_contact_email']);    

        // -- images_mstr --//
        $temimagerandom  = mssql_escape($row['cus_tem_image']);    

        if($cus_type_code =="9") { $newbranch_input = "show"; } 
        if($cus_cond_term =="2") { $cus_cond_term_txt = "show"; } 
        $cus_step_code = mssql_escape($row['cus_step_code']);  
	}
}	

$params = array($q);
$query_det = "SELECT * FROM cus_app_det WHERE cusd_app_nbr = ?";
$result = sqlsrv_query($conn, $query_det, $params, array("Scrollable" => 'keyset' ));
$rowCounts = sqlsrv_num_rows($result);
if($rowCounts > 0){
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
	{
        // -- page 3 --//
        $cusd_tg_beg_date = mssql_escape(dmytx($row['cusd_tg_beg_date']));
        $cusd_tg_end_date = mssql_escape(dmytx($row['cusd_tg_end_date']));
        $cusd_sale_est = CheckandShowNumber(mssql_escape($row['cusd_sale_est']),2);
        $cusd_sale_vol = CheckandShowNumber(mssql_escape($row['cusd_sale_vol']),2);

        $cusd_obj1 = mssql_escape($row['cusd_obj1']);
        $cusd_obj2 = mssql_escape($row['cusd_obj2']);
        $cusd_obj3 = mssql_escape($row['cusd_obj3']);
        $objArray = array();	//เก็บวัตถุประสงค์ / นโยบายด้านการตลาด
        if($cusd_obj1 !="") { array_push($objArray,$cusd_obj1);}
        if($cusd_obj2 !="") { array_push($objArray,$cusd_obj2);}
        if($cusd_obj3 !="") { array_push($objArray,$cusd_obj3);}
        $objArrayCount = count($objArray);

        $cusd_cust_prop1 = mssql_escape($row['cusd_cust_prop1']);
        $cusd_cust_prop2 = mssql_escape($row['cusd_cust_prop2']);
        $cusd_cust_prop3 = mssql_escape($row['cusd_cust_prop3']);

        $projArray = array();	//เก็บคุณสมบัติลูกค้า
        if($cusd_cust_prop1 !="") { array_push($projArray,$cusd_cust_prop1);}
        if($cusd_cust_prop2 !="") { array_push($projArray,$cusd_cust_prop2);}
        if($cusd_cust_prop3 !="") { array_push($projArray,$cusd_cust_prop3);}
        $projArrayCount = count($projArray);

        $cusd_aff1 = mssql_escape($row['cusd_aff1']);
        $cusd_aff2 = mssql_escape($row['cusd_aff2']);
        $cusd_aff3 = mssql_escape($row['cusd_aff3']);
        $affArray = array();	//เก็บกิจการในเครือ (Affiliate / Related Company)
        if($cusd_aff1 !="") { array_push($affArray,$cusd_aff1);}
        if($cusd_aff2 !="") { array_push($affArray,$cusd_aff2);}
        if($cusd_aff3 !="") { array_push($affArray,$cusd_aff3);}
        $affArrayCount = count($affArray);

        $cusd_dealer1_nme = mssql_escape($row['cusd_dealer1_nme']);
        $cusd_dealer1_avg_val = CheckandShowNumber(mssql_escape($row['cusd_dealer1_avg_val']),2);
        $cusd_dealer2_nme = mssql_escape($row['cusd_dealer2_nme']);
        $cusd_dealer2_avg_val = CheckandShowNumber(mssql_escape($row['cusd_dealer2_avg_val']),2);
        $cusd_dealer3_nme = mssql_escape($row['cusd_dealer3_nme']);
        $cusd_dealer3_avg_val = CheckandShowNumber(mssql_escape($row['cusd_dealer3_avg_val']),2);
        $dealerArray = array();	//เก็บรายชื่อผู้แทนจำหน่ายทั่วไป
        $dealerArrayVal = array();
        if($cusd_dealer1_nme !="") { array_push($dealerArray,$cusd_dealer1_nme);  array_push($dealerArrayVal, $cusd_dealer1_avg_val);}
        if($cusd_dealer2_nme !="") { array_push($dealerArray,$cusd_dealer2_nme);  array_push($dealerArrayVal, $cusd_dealer2_avg_val);}
        if($cusd_dealer3_nme !="") { array_push($dealerArray,$cusd_dealer3_nme);  array_push($dealerArrayVal, $cusd_dealer3_avg_val);}
        $dealerArrayCount = count($dealerArray);

        $cusd_comp1_nme = mssql_escape($row['cusd_comp1_nme']);
        $cusd_comp1_avg_val = CheckandShowNumber(mssql_escape($row['cusd_comp1_avg_val']),2);
        $cusd_comp2_nme = mssql_escape($row['cusd_comp2_nme']);
        $cusd_comp2_avg_val = CheckandShowNumber(mssql_escape($row['cusd_comp2_avg_val']),2);
        $cusd_comp3_nme = mssql_escape($row['cusd_comp3_nme']);
        $cusd_comp3_avg_val = CheckandShowNumber(mssql_escape($row['cusd_comp3_avg_val']),2);
        $compArray = array();	//เก็บรายชื่อคู่แข่ง ซึ่งลูกค้าที่ขอแต่งตั้งซื้อเป็นประจำ
        $compArrayVal = array();
        if($cusd_comp1_nme !="") { array_push($compArray,$cusd_comp1_nme);  array_push($compArrayVal, $cusd_comp1_avg_val);}
        if($cusd_comp2_nme !="") { array_push($compArray,$cusd_comp2_nme);  array_push($compArrayVal, $cusd_comp2_avg_val);}
        if($cusd_comp3_nme !="") { array_push($compArray,$cusd_comp3_nme);  array_push($compArrayVal, $cusd_comp3_avg_val);}
        $compArrayCount = count($compArray);

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
        /* default :
        $cardtxt = "ยกเลิกลูกค้า";
        $info_cust = "ยกเลิกลูกค้า";
        $cusd_op_app = "กจก."; */
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
            $cr_status = mssql_escape($row['cr_status']);
            $cr_mgr_status = mssql_escape($row['cr_mgr_status']);
            $cr_whocanread = mssql_escape($row['cr_whocanread']);
            $cr_rem_revise = mssql_escape($row['cr_rem_revise']);
            $cr_sta_complete = mssql_escape($row['cr_sta_complete']);
            $cr_sta_rem = mssql_escape($row['cr_sta_rem']);

            switch($cr_bankrupt){
                case "1" :
                    $cr_bankrupt_name = "ปกติ";
                    break;
                case "2" :
                    $cr_bankrupt_name = "ถูกฟ้องล้มละลาย";
                    break;  
                case "9" :
                    $cr_bankrupt_name = "ไม่มีข้อมูล (ลูกค้าต่างประเทศ)";
                    break;  
            }

            switch($cr_md_bankrupt){
                case "1" :
                    $cr_md_bankrupt_name = "ไม่มี";
                    break;
                case "2" :
                    $cr_md_bankrupt_name = "มี ระบุชื่อ";
                    break;  
                case "9" :
                    $cr_md_bankrupt_name = "ไม่มีข้อมูล (ลูกค้าต่างประเทศ)";
                    break;  
            }

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
                        <!-- <?php if($cus_step_code=="112") { ?>
                        <button id="btnshowtext"
                            class="btn btn-sm btn-outline-warning btn-min-width btn-glow mr-1 mb-1"><i
                                class="fa fa-bell"></i> มีข้อความถึงคุณ</button>
                        <?php } ?> -->
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
                        <input type="hidden" name="action" id="action" value="view_newcust">
                        <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
                        <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                        <input type="hidden" name="info_cust" value="<?php echo encrypt($info_cust, $key) ?>">
                        <input type="hidden" name="form_cus" value="<?php echo $cus_cond_cust ?>">
                        <input type="hidden" name="contactArrayCount" id="contactArrayCount"
                            value="<?php echo $contactArrayCount ?>">
                        <input type="hidden" name="objArrayCount" id="objArrayCount"
                            value="<?php echo $objArrayCount ?>">
                        <input type="hidden" name="projArrayCount" id="projArrayCount"
                            value="<?php echo $projArrayCount ?>">
                        <input type="hidden" name="affArrayCount" id="affArrayCount"
                            value="<?php echo $affArrayCount ?>">
                        <input type="hidden" name="dealerArrayCount" id="dealerArrayCount"
                            value="<?php echo $dealerArrayCount ?>">
                        <input type="hidden" name="compArrayCount" id="compArrayCount"
                            value="<?php echo $compArrayCount ?>">
                        <input type="hidden" name="temimagerandom" id="temimagerandom"
                            value="<?php echo encrypt($temimagerandom, $key) ?>">
                        <input type="hidden" name="old_step_code" id="old_step_code"
                            value="<?php echo encrypt($cus_step_code, $key)?>">
                        <input type="hidden" name="cus_app_nbr" id="cus_app_nbr"
                            value="<?php echo encrypt($cus_app_nbr, $key)?>">
                        <input type="hidden" name="rem_revise" id="rem_revise" value="<?php echo $rem_revise ?>">
                        <input type="hidden" name="search_app_nbr" id="search_app_nbr" value="<?php echo $cus_app_nbr ?>">
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
                                <a class="nav-link" id="aboutIcon21-tab1" data-toggle="tab" href="#aboutIcon21"
                                    aria-controls="aboutIcon21"><i class="ft-external-link"></i> Page 3</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active1; ?>" id="linkIcon21-tab1" data-toggle="tab"
                                    href="#linkIcon21" aria-controls="linkIcon21"><i class="fa fa-flag"></i> Page 4</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active1; ?>" id="helpIcon21-tab1" data-toggle="tab"
                                    href="#helpIcon21" aria-controls="helpIcon21"><i class="fa fa-send-o"></i> Page
                                    5</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active1; ?>" id="helpIcon22-tab1" data-toggle="tab"
                                    href="#helpIcon22" aria-controls="helpIcon22"><i class="fa fa-send-o"></i> Page Cr1
                                    </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active1; ?>" id="helpIcon23-tab1" data-toggle="tab"
                                    href="#helpIcon23" aria-controls="helpIcon23"><i class="fa fa-send-o"></i> Page Cr2
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
                                <?php include("view_newcusmnt_tab1.php"); ?>
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
                                <?php include("view_newcusmnt_tab2.php"); ?>
                            </div>

                            <?php
								if ($current_tab == "30") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
                            <div class="tab-pane <?php echo $active; ?>" id="aboutIcon21" role="tabpanel"
                                aria-labelledby="aboutIcon21-tab1" aria-expanded="false">
                                <?php include("view_newcusmnt_tab3.php"); ?>
                            </div>

                            <?php
								if ($current_tab == "40") {
									$active1 = 'active';
									} else {
									$active1 = '';
								}
							?>
                            <div class="tab-pane <?php echo $active1; ?>" id="linkIcon21" role="tabpanel"
                                aria-labelledby="linkIcon21-tab1" aria-expanded="false">
                                <?php include("view_newcusmnt_tab4.php"); ?>
                            </div>

                            <?php
								if ($current_tab == "50") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
                            <div class="tab-pane <?php echo $active; ?>" id="helpIcon21" role="tabpanel"
                                aria-labelledby="helpIcon21-tab1" aria-expanded="false">
                                <?php include("view_newcusmnt_tab5.php"); ?>
                            </div>

                            <?php
								if ($current_tab == "60") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
                            <div class="tab-pane <?php echo $active; ?>" id="helpIcon22" role="tabpanel"
                                aria-labelledby="helpIcon22-tab2" aria-expanded="false">
                                <?php include("view_cr_newcusmnt_tab1.php"); ?>
                            </div>

                            <?php
								if ($current_tab == "70") {
									$active = 'active';
									} else {
									$active = '';
								}
							?>
                            <div class="tab-pane <?php echo $active; ?>" id="helpIcon23" role="tabpanel"
                                aria-labelledby="helpIcon23-tab2" aria-expanded="false">
                                <?php include("view_cr_newcusmnt_tab2.php"); ?>
                            </div>


                            <div class="bs-callout-warning callout-transparent callout-bordered showNtxPage mt-1"
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
<? include("../crctrlmain/menu_footer.php"); ?>
<div class="to-top">
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>

<? include("file_script_view.php"); ?>
<script type="text/javascript">
</body>  
</html >