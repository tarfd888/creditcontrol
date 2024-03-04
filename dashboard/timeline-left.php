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

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key, $user_login)) {
		echo "System detect CSRF attack2!!";
		exit;
	}
}
$q = decrypt(mssql_escape($_REQUEST['q']), $key);

//$q = "NC-2310-0003";
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

		$cus_type_bus = mssql_escape($row['cus_type_bus']);
        $cus_type_bus_name = findsqlval("cus_tyofbus_mstr","cus_tyofbus_name","cus_tyofbus_id",$cus_type_bus,$conn);

		$cus_tel = mssql_escape($row['cus_tel']);
		$cus_fax = mssql_escape($row['cus_fax']);
		$cus_email = mssql_escape($row['cus_email']);
        $cus_cust_type_oth = mssql_escape($row['cus_cust_type_oth']);    
		$cus_effective_date = mssql_escape(dmytx($row['cus_effective_date']));

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

switch($cus_cond_cust){
    case "c1" :
        $cardtxt = "แต่งตั้งลูกค้าใหม่";
        break;
      case "c2" :
        $cardtxt = "แต่งตั้งร้านสาขา";
        break;
    case "c3" :
		$cardtxt = "เปลี่ยนแปลงชื่อ";
        $newcus_txt = "<span style='color:DarkOrange'>ชื่อจดทะเบียน (ใหม่) :</span>";
        $newaddr_txt = "ที่อยู่จดทะเบียน :";
        break;
      case "c4" :
        $cardtxt = "เปลี่ยนแปลงที่อยู่จดทะเบียน";
        $newcus_txt = "ชื่อจดทะเบียน :";
        $newaddr_txt = "<span style='color:DarkOrange'>ที่อยู่จดทะเบียน (ใหม่) :</span>";
        break;  
	  case "c5" :
		$cardtxt = "เปลี่ยนแปลงชื่อและที่อยู่";
        $newcus_txt = "<span style='color:DarkOrange'>ชื่อจดทะเบียน (ใหม่) :</span>";
        $newaddr_txt = "<span style='color:DarkOrange'>ที่อยู่จดทะเบียน (ใหม่) :</span>";
		break;  	
      default :
        $cardtxt = "ยกเลิกลูกค้า";
        $newcus_txt = "ชื่อจดทะเบียน :";
        $newaddr_txt = "ที่อยู่จดทะเบียน :";
        break;  
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
?>
<?php include("../newcust/header.php"); ?>
<?php include("../crctrlmain/menu_header.php"); ?>
<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
<div class="app-content content font-small-2">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                <h3 class="content-header-title mb-0 d-inline-block">List Customer</h3>
                <div class="row breadcrumbs-top d-inline-block">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../newcust/newcust_list.php">Home</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <font color="40ADF4">ประวัติการดำเนินการกับเอกสาร</font>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 col-12">
                <div class="btn-group float-md-right btn-group-sm">
                    <a href="../newcust/newcust_list.php"><button class="btn btn-outline-info btn-min-width btn-glow" type="button"><i class="fa fa-reply-all mr-1"></i>ย้อนกลับ</button></a>
                </div>
            </div>

        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card"> 
                       <!--  <div class="card-header"> 
                            <h4 class="card-title" id="basic-layout-form">Upload Risk Categories</h4>
                        </div> --> 
                            <div class="card-body">
                                <h4 class="card-title" id="basic-layout-form">ข้อมูลการ<?php echo $cardtxt ;?></h4>

                                <?php if(inlist("c1,c2",$cus_cond_cust)){  ?>

                                    <?php include("timeline_newcus.php"); ?>
                                <?php } else { ?>   
                                    <?php include("timeline_chgcus.php"); ?>

                                 <?php } ?>

                            </div> 
                    </div> 
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <section id="timeline" class="timeline-left timeline-wrapper">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title" id="basic-layout-form"> ประวัติการดำเนินการกับเอกสาร</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php if(inlist("60,810,820,830,840,850",$cus_step_code)){  ?>
                                <ul class="timeline">
                                    <li class="timeline-line"></li>
                                    <li class="timeline-group">
                                        <a href="#" class="btn btn-primary"><i class="ft-calendar"></i> End</a>
                                    </li>
                                </ul>
                                <?php } ?>
                                <?php 
                                $cus_ap_nbr = $q;
                                $cus_create_by = findsqlval("cus_app_mstr", "cus_create_by", "cus_app_nbr", $cus_ap_nbr,$conn);
                                $cus_create_name = findsqlval("emp_mstr","emp_prefix_th_name + emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$cus_create_by,$conn);
                                $cus_create_date = findsqlval("cus_app_mstr", "cus_create_date", "cus_app_nbr", $cus_ap_nbr,$conn);
                                $cus_create_date = date_format($cus_create_date,"d/m/Y H:i:s");

                                $params_hist = array($cus_ap_nbr);
                                $sql_hist = "SELECT * FROM cus_approval where cus_ap_active = 1 and  cus_ap_nbr = ? order by cus_ap_id desc";
                                $result_hist = sqlsrv_query($conn, $sql_hist, $params_hist, array("Scrollable" => 'keyset'));
                                $row_hist = sqlsrv_num_rows($result_hist);
                                
                                if($row_hist >0) {
                                    $stepno = $row_hist;
                                    while ($r_hist = sqlsrv_fetch_array($result_hist, SQLSRV_FETCH_ASSOC)) {																
                                        $cus_ap_text = html_clear($r_hist['cus_ap_text']);
                                        $cus_ap_remark = html_clear($r_hist['cus_ap_remark']);
                                        $cus_ap_create_by = html_clear($r_hist['cus_ap_create_by']);
                                        $cus_process = html_clear($r_hist['cus_ap_t_step_code']);
                                        $textcolor = html_clear($r_hist['cus_ap_color']);
                                        $cus_ap_create_date = date_format($r_hist['cus_ap_create_date'],"d/m/Y H:i:s");
                                        $cus_process_name = findsqlval("cusstep_mstr", "cusstep_name_th", "cusstep_code", $cus_process,$conn);
                                        $cus_ap_create_name = findsqlval("emp_mstr","emp_prefix_th_name + emp_th_firstname + ' ' + emp_th_lastname" ,"emp_user_id",$cus_ap_create_by,$conn);
                                        //$cus_ap_create_by = findsqlval("emp_mstr","emp_prefix_th_name + emp_th_firstname + ' ' + emp_th_lastname +'('+  emp_th_pos_name +')'" ,"emp_user_id",$cus_ap_create_by,$conn);
                                       													
                                ?>
                                <ul class="timeline">
                                    <li class="timeline-line"></li>
                                    <li class="timeline-item">
                                        <div class="timeline-badge">
                                            <span class="bg-success bg-lighten-1" data-toggle="tooltip" data-placement="right"
                                                title="Portfolio project work"><i class="ft ft-check"></i></span>
                                        </div>
                                        <div class="timeline-card border-grey border-lighten-2">
                                            <div class="card-header">
                                                <h4 class="card-title <?php echo $textcolor; ?>"><i class="icon-direction"></i><?php echo $cus_ap_text; ?></h4>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2"><?php echo $cus_ap_create_name; ?></span>
                                                </p>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2">วันที่ <?php echo $cus_ap_create_date; ?></span>
                                                </p>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="text-primary font-small-2"><?php echo $cus_ap_remark; ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <?php $stepno = $stepno -1; } } else { $cus_create_name = findsqlval("emp_mstr","emp_prefix_th_name + emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$cus_create_by,$conn); } 

								?>

                                <ul class="timeline">
                                    <li class="timeline-line"></li>
                                    <li class="timeline-item">
                                        <div class="timeline-badge">
                                            <span class="bg-red bg-lighten-1" data-toggle="tooltip" data-placement="right"
                                                title="Portfolio project work"><i class="ft ft-check"></i></span>
                                        </div>
                                        <div class="timeline-card border-grey border-lighten-2">
                                        <div class="card-header">
                                                <h4 class="card-title text-info"><i class="ft ft-file"></i>สร้างเอกสาร</h4>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2"><?php echo $cus_create_name; ?></span>
                                                </p>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2">วันที่ <?php echo $cus_create_date; ?></span>
                                                </p>
                                                <!-- <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2"><?php echo $cus_ap_remark; ?></span>
                                                </p> -->
                                            </div>
                                        </div>
                                    </li>
                                </ul> 

                                <ul class="timeline"> 
                                    <!-- <li class="timeline-line"></li> -->
                                    <li class="timeline-group">
                                        <a href="#" class="btn btn-primary"><i class="fa fa-calendar-o"></i> Start</a>
                                    </li>
                                </ul> 

                            </div>
                        </section>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
<? include("../crctrlmain/menu_footer.php"); ?>
<div class="to-top">
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>

<? include("../newcust/file_script.php"); ?>
<script type="text/javascript">
< /body>  < /
html >