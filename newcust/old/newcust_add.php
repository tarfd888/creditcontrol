<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
include("../crctrlbof/chkauthcr.php");
include("../crctrlbof/chkauthcrctrl.php");
include_once('../_libs/Thaidate/Thaidate.php');
include_once('../_libs/Thaidate/thaidate-functions.php');

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    if (!matchToken($csrf_key, $user_login)) {
        echo "System detect CSRF attack!!";
        exit;
    }
}
set_time_limit(0);
$curdate = date('Ymd');
$params = array();
$chk_c2 = 'none';
$cus_cr_limit1 = 0;
$cus_cr_limit2 = 0;
$action_cus = decrypt(mssql_escape($_REQUEST['action_cus']), $key);
  
    switch($action_cus){
      case "c1" :
        $cardtxt = 'แต่งตั้งลูกค้าใหม่';
        $info_cust = 'ตั้งลูกค้าใหม่';
        $cusd_op_app = 'กจก.';
        $newbranch = "none";
        break;
      case "c2" :
        $cardtxt = 'แต่งตั้งร้านสาขา';
        $info_cust = 'แต่งตั้งร้านสาขา';
        $cusd_op_app = 'ผส.';
        $newbranch = "none";
        break;  
      default :
        $cardtxt = 'เปลี่ยนแปลงข้อมูลลูกค้า';
        $info_cust = 'เปลี่ยนแปลงข้อมูลลูกค้า';
        $cusd_op_app = 'กจก.';
        $newbranch = "none";
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
            $sysc_name_cmo = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname."(". $emp_th_pos_name .")";
        }
        $sysc_cfo = strtoupper($rec_sysc['sysc_cfo']);
        if ($sysc_cfo != "") {
            $emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_cfo, $conn);
            $emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_cfo, $conn);
            $emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_cfo, $conn);
            $emp_th_pos_name = findsqlval("emp_mstr", "emp_th_pos_name", "emp_user_id", $sysc_cfo, $conn);
            $sysc_name_cfo = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname."(". $emp_th_pos_name .")";
        }
        $sysc_md = strtoupper($rec_sysc['sysc_md']);
        if ($sysc_md != "") {
            $emp_prefix_th_name = findsqlval("emp_mstr", "emp_prefix_th_name", "emp_user_id", $sysc_md, $conn);
            $emp_th_firstname = findsqlval("emp_mstr", "emp_th_firstname", "emp_user_id", $sysc_md, $conn);
            $emp_th_lastname = findsqlval("emp_mstr", "emp_th_lastname", "emp_user_id", $sysc_md, $conn);
            $emp_th_pos_name = findsqlval("emp_mstr", "emp_th_pos_name", "emp_user_id", $sysc_md, $conn);
            $sysc_name_md = $emp_prefix_th_name . $emp_th_firstname . "  " . $emp_th_lastname."(". $emp_th_pos_name .")";
        }
    }
	
?>
<?php include("header.php"); ?>

<?php include("../crctrlmain/menu_header.php"); ?>
<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
<?php include("../crctrlmain/modal_cust.php"); ?>
<?php include("../crctrlmain/help_modal.php"); ?>

<div class="app-content content font-small-2">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
                <!-- <h3 class="content-header-title mb-0 d-inline-block">แต่งตั้งลูกค้าใหม่</h3> -->
                <div class="row breadcrumbs-top d-inline-block">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Home</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Page</a>
                            </li>
                            <li class="breadcrumb-item active"><?php echo $cardtxt; ?>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12">
                <div class="btn-group float-md-right mb-2">
                    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><i class="icon-settings mr-1"></i>Action</button>
                    <div class="dropdown-menu arrow"><a class="dropdown-item" href="#"><i
                                class="fa fa-calendar mr-1"></i>
                            Calender</a><a class="dropdown-item" href="#"><i class="fa fa-cart-plus mr-1"></i>
                            Cart</a><a class="dropdown-item" href="../newcust/_upload_img.php"><i
                                class="fa fa-life-ring mr-1"></i> Support</a>
                        <div class="dropdown-divider"></div><a class="dropdown-item" href="../newcust/upload_img.php"><i
                                class="fa fa-cog mr-1"></i>
                            Settings</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">

            <!-- Form wizard with icon tabs section start -->
            <section id="icon-tabs">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <!-- <h4 class="card-title"><?php echo $cardtxt; ?></h4> -->
                                <a class="heading-elements-toggle"><i class="ft-ellipsis-h font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        <li><a data-action="close"><i class="ft-x"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form id="frm_cust_add" name="frm_cust_add" autocomplete=OFF method="POST"
                                        class="icons-tab-steps wizard-notification">
                                        <input type="hidden" name="action" value="cust_add">
                                        <input type="hidden" name="csrf_securecode"
                                            value="<?php echo $csrf_securecode?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                                        <input type="hidden" name="info_cust"
                                            value="<?php echo encrypt($info_cust, $key) ?>">
                                        <input type="hidden" name="form_cus" value="<?php echo $action_cus ?>">
                                        <!-- <form action="#" class="icons-tab-steps wizard-notification"> -->

                                        <!-- Step 1 -->
                                        <h6><i class="step-icon fa fa-home"></i> Step 1</h6>
                                        <fieldset>
                                            <h4 class="form-section text-info"><i class="fa fa-id-card"></i>
                                                ข้อมูลทั่วไป</h4>
                                            <div class="row skin skin-square">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_tg_cust" id="cus_tg_cust1"
                                                            value="dom">
                                                        <label for="cus_tg_cust1">ลูกค้าในประเทศ (Domestic)</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_tg_cust" id="cus_tg_cust2"
                                                            value="exp">
                                                        <label for="cus_tg_cust2">ลูกค้าต่างประเทศ (Export)</label>
                                                    </div>
                                                </div>
                                               <!--  <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_tg_cust" id="cus_tg_cust3"
                                                            value="3">
                                                        <label for="cus_tg_cust3">ลูกค้าในเครือ SCG</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_tg_cust" id="cus_tg_cust4"
                                                            value="4">
                                                        <label for="cus_tg_cust4">ราชการ/รัฐวิสาหกิจ</label>
                                                    </div>
                                                </div> -->
                                            </div>
                                           
                                            <!-- กรณีเลือกลูกค้าในเครือ -->
                                           <!--  <div class="row skin skin-square sel_group_aff" style="display:none;">
                                                <div class="col-md-3">
                                                </div>
                                                <div class="col-md-3">
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_tg_cust_aff" id="cus_tg_cust_aff1"
                                                            value="">
                                                        <label for="cus_tg_cust3">ลูกค้าในเครือ (Domestic)</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_tg_cust_aff" id="cus_tg_cust_aff2"
                                                            value="">
                                                        <label for="cus_tg_cust4">ลูกค้าในเครือ (Export)</label>
                                                    </div>
                                                </div>
                                            </div> -->

                                            <!-- แต่งตั้งร้านสาขา (นิติบุคคลเดิม) -->
                                            <div class="row skin skin-square newbranch" style="display:<?php echo $newbranch?>"> 
                                                <div class="col-md-6 ">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 label-control" for="userinput1">ประเภทลูกค้าที่ขอแต่งตั้ง:</label>
                                                        <div class="col-md-9">
                                                            <select data-placeholder="เลือกประเภทลูกค้า" class="form-control input-sm border-info font-small-2 select2" id="cus_cust_type" name="cus_cust_type">
                                                                <option value="" selected>--- เลือกประเภทลูกค้า ---</option>
                                                                <?php
                                                                    $sql_doc = "SELECT * FROM cus_type_mstr order by cus_type_seq";
                                                                    $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                    while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                    ?>
                                                                    <option value="<?php echo $r_doc['cus_type_code']; ?>" data-icon="fa fa-wordpress"><?php echo $r_doc['cus_type_name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 block-tag err_newbranch">
                                                    <small class="badge badge-danger block-area">*** กรุณาเลือกประเภทลูกค้า ***</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group newbranch_input" style="display:none">
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_cust_type_oth" name="cus_cust_type_oth" maxlength="100" placeholder="ระบุอื่น ๆ">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- แต่งตั้งร้านสาขา (นิติบุคคลเดิม) -->

                                            <!-- ข้อมูลทั่วไป -->
                                            <div class="row dis_step1" style="display:none;">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cus_reg_nme">ชื่อจดทะเบียน (Registered Name)
                                                            :</label>
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_reg_nme" name="cus_reg_nme" maxlength="80">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cus_reg_addr">ที่อยู่จดทะเบียน (Registered Address)
                                                            :</label>
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_reg_addr" name="cus_reg_addr" maxlength="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row dis_step1" style="display:none;">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_district">ตำบล / แขวง (Sub-district) :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cus_district" name="cus_district">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_amphur">อำเภอ / เขต (District):</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cus_amphur" name="cus_amphur">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_prov">จังหวัด (Province) :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cus_prov" name="cus_prov">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_zip">รหัสไปรษณีย์ (Zip) :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cus_zip" name="cus_zip">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row dis_step1" style="display:none;">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_country">ประเทศ (Country) :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cus_country" name="cus_country" placeholder="">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_tel">เบอร์โทรศัพท์ :</label>
                                                        <input type="text"
                                                            class="form-control input-sm font-small-2"
                                                            id="cus_tel" name="cus_tel"
                                                            placeholder="ป้อนหมายเลขโทรศัพท์">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_fax">เบอร์ Fax :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cus_fax" name="cus_fax">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_email">E-mail :</label>
                                                        <input type="text"
                                                            class="form-control input-sm font-small-2 email-inputmask"
                                                            id="cus_email" name="cus_email" placeholder="ป้อนอีเมล">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label
                                                            for="cus_tax_id">เลขประจำตัวผู้เสียภาษี/เลขที่ทะเบียนพาณิชย์
                                                            :</label>
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_tax_id" name="cus_tax_id" maxlength="20">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_branch">สาขาที่ (Branch No.) :</label>
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_branch" name="cus_branch" maxlength="10">
                                                    </div>
                                                </div>
                                            </div>

                                            <hr width=100%>
                                            <div class="row dis_step1" style="display:none;">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600">
                                                            <u>ประเภทการจดทะเบียนบริษัท (Type Of
                                                                Business)</u></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row skin skin-square domestic" style="display:none;">  
                                                <div class="col-md-6 ">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 label-control" for="userinput1">ประเภทการจดทะเบียนบริษัท:</label>
                                                        <div class="col-md-9">
                                                            <select data-placeholder="เลือกประเภทลูกค้า" class="form-control input-sm border-info font-small-2 select2" id="cus_type_bus_dom" name="cus_type_bus_dom">
                                                                <option value="" selected>--- เลือกประเภทการจดทะเบียนบริษัท ---</option>
                                                                <?php
                                                                    $sql_doc = "SELECT * FROM cus_tyofbus_mstr where cus_tyofbus_group='1' order by cus_tyofbus_seq";
                                                                    $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                    while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                    ?>
                                                                    <option value="<?php echo $r_doc['cus_tyofbus_id']; ?>" data-icon="fa fa-wordpress"><?php echo $r_doc['cus_tyofbus_name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 block-tag err_domestic">
                                                    <small class="badge badge-danger block-area">*** กรุณาเลือกประเภทลูกค้า ***</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group domestic_input" style="display:none">
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_other_dom" name="cus_other_dom" maxlength="100" placeholder="ระบุอื่น ๆ">
                                                    </div>
                                                </div>

                                               <!-- <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus1"
                                                            value="dom1">
                                                        <label for="cus_type_bus1">บมจ.</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus2"
                                                            value="dom2">
                                                        <label for="cus_type_bus2">บจก.</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus3"
                                                            value="dom3">
                                                        <label for="cus_type_bus3">หจก.</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus4"
                                                            value="dom4">
                                                        <label for="cus_type_bus4">หสม.</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus5"
                                                            value="dom5">
                                                        <label for="cus_type_bus5">ร้าน</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus6"
                                                            value="dom6">
                                                        <label for="cus_type_bus6">อื่นๆ ระบุ :</label>
                                                    </div>
                                                </div>  -->
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3 ml-auto dom_oth" style="display:none;">
                                                    <div class="form-group">
                                                        <input type="text" name="cus_type_bus_oth" class="form-control input-sm font-small-2" placeholder="ระบุอื่น ๆ">
                                                    </div>
                                                </div>
                                            </div> 

                                            <!-- start radio export -->
                                            <div class="row skin skin-square export" style="display:none;">
                                                <div class="col-md-6 ">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="userinput1">ประเภทการจดทะเบียนบริษัท:</label>
                                                            <div class="col-md-9">
                                                                <select data-placeholder="เลือกประเภทลูกค้า" class="form-control input-sm border-info font-small-2 select2" id="cus_type_bus_exp" name="cus_type_bus_exp">
                                                                    <option value="" selected>--- เลือกประเภทการจดทะเบียนบริษัท ---</option>
                                                                    <?php
                                                                        $sql_doc = "SELECT * FROM cus_tyofbus_mstr where cus_tyofbus_group='2' order by cus_tyofbus_seq";
                                                                        $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                        while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                        ?>
                                                                        <option value="<?php echo $r_doc['cus_tyofbus_id']; ?>" data-icon="fa fa-wordpress"><?php echo $r_doc['cus_tyofbus_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 block-tag err_export">
                                                        <small class="badge badge-danger block-area">*** กรุณาเลือกประเภทลูกค้า ***</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group export_input" style="display:none">
                                                            <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                                id="cus_other_exp" name="cus_other_exp" maxlength="100" placeholder="ระบุอื่น ๆ">
                                                        </div>
                                                    </div>
                                               <!--  <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus1"
                                                            value="exp1">
                                                        <label for="cus_type_bus1">Company Limited</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus2"
                                                            value="exp2">
                                                        <label for="cus_type_bus2">Sole Proprietorship</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus3"
                                                            value="exp3">
                                                        <label for="cus_type_bus3">Partnership</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="cus_type_bus4"
                                                            value="exp4">
                                                        <label for="cus_type_bus4">Public Company</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_type_bus" id="expcus_type_bus6"
                                                            value="exp5">
                                                        <label for="cus_type_bus6">อื่นๆ :</label>
                                                    </div>
                                                </div> -->
                                            </div>

                                           <!--  <div class="row">
                                                <div class="col-md-4 ml-auto exp_oth" style="display:none;">
                                                    <div class="form-group">
                                                        <input type="text" name="cus_type_bus_oth" class="form-control input-sm font-small-2" placeholder="ระบุอื่น ๆ">
                                                    </div>
                                                </div>
                                            </div>     -->
                                            <!-- end radio export -->

                                            <div class="row dis_step1_1" style="display:none;">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600"><u>ชื่อเจ้าของ /
                                                                ผู้จัดการที่ติดต่อสั่งซื้อสินค้า</u></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- collapse -->
                                               <!--  <div class="col-12">
                                                    <div class="card"> 
                                                        <div class="card-content collapse show constrain-movement-container">
                                                                <div class="jqueryui-ele-container">
                                                                    <div class="accordion-collapsible">
                                                                        <h3>รายชื่อ 1</h3>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="cus_contact1_nme">ชื่อ - สกุล :</label>
                                                                                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                                                    id="cus_contact1_nme" name="cus_contact1_nme" maxlength="50">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="cus_contact1_pos">ตำแหน่ง :</label>
                                                                                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                                                    id="cus_contact1_pos" name="cus_contact1_pos" maxlength="50">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <h3>รายชื่อ 2</h3>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="cus_contact2_nme">ชื่อ - สกุล :</label>
                                                                                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                                                    id="cus_contact2_nme" name="cus_contact2_nme" maxlength="50">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="cus_contact2_pos">ตำแหน่ง :</label>
                                                                                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                                                    id="cus_contact2_pos" name="cus_contact2_pos" maxlength="50">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div> 
                                                </div> -->
                                            <!-- collapse -->

                                            <!-- <div class="row dis_step1_1" style="display:none;">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cus_contact1_nme">ชื่อ - สกุล :</label>
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_contact1_nme" name="cus_contact1_nme" maxlength="50">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cus_contact1_pos">ตำแหน่ง :</label>
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_contact1_pos" name="cus_contact1_pos" maxlength="50">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cus_contact2_nme">ชื่อ - สกุล :</label>
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_contact2_nme" name="cus_contact2_nme" maxlength="50">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cus_contact2_pos">ตำแหน่ง :</label>
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_contact2_pos" name="cus_contact2_pos" maxlength="50">
                                                    </div>
                                                </div>
                                            </div> --> 
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive dis_step1_1" style="display:none;">
                                                        <table class="table mb-1" id="dynamic_contact">
                                                            <tr>
                                                                <td><input type="text" name="cus_contact_nme[]"
                                                                        placeholder="ระบุชื่อ - สกุล..."
                                                                        class="form-control input-sm font-small-2 always-show-maxlength" maxlength="50">
                                                                </td>
                                                                <td><input type="text" name="cus_contact_pos[]"
                                                                        placeholder="ตำแหน่ง"
                                                                        class="form-control input-sm font-small-2 always-show-maxlength" maxlength="50">
                                                                </td>
                                                                <td><button type="button" name="btn-add-comp"
                                                                        id="btn-add-contact"
                                                                        class="btn btn-success btn-sm"><i
                                                                            class="ft-plus"></i></button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <!-- Step 2 -->
                                        <h6><i class="step-icon fa fa-pencil"></i>Step 2</h6>
                                        <fieldset>
                                            <h4 class="form-section text-info"><i class="fa fa-credit-card-alt"></i>
                                                เงื่อนไขการขายและการชำระเงิน</h4>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_term_dom">เงื่อนไขการชำระเงิน</label>
                                                        <!-- case term domestic -->
                                                        <select data-placeholder="Select a doc type ..."
                                                            class="dom_term form-control input-sm border-info font-small-2 select2"
                                                            id="cus_term_dom" name="cus_term_dom"  style="display:none;"> 
                                                            <option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---
                                                            </option>
                                                            <?php
                                                                $sql_doc = "SELECT * FROM term_mstr where term_active='1' and term_group='1' order by term_code";
                                                                $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                ?>
                                                            <option value="<?php echo $r_doc['term_code']; ?>"
                                                                data-icon="fa fa-wordpress">
                                                                <?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    
                                                        <!-- case term export -->
                                                        <select data-placeholder="Select a doc type ..."
                                                            class="cus_term_exp form-control input-sm border-info font-small-2 select2"
                                                            id="cus_term_exp" name="cus_term_exp" style="display:none;"> 
                                                            <option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---
                                                            </option>
                                                            <?php
                                                                $sql_doc = "SELECT * FROM term_mstr where term_active='1' and term_group='2' order by term_code";
                                                                $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                ?>
                                                            <option value="<?php echo $r_doc['term_code']; ?>"
                                                                data-icon="fa fa-wordpress">
                                                                <?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-md-3 text-center">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600"><u>ค้ำประกันโดย </u></p>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_bg1">ธนาคาร</label>
                                                        <select data-placeholder="Select a doc type ..."
                                                            class="form-control input-sm border-info font-small-2 select2"
                                                            id="cus_bg1" name="cus_bg1">
                                                            <option value="" selected>--- เลือกธนาคาร ---</option>
                                                            <?php
                                $sql_doc = "SELECT * FROM bank_mstr where bank_status='1' order by bank_id";
                                $result_doc = sqlsrv_query($conn, $sql_doc);
                                while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                ?>
                                                            <option value="<?php echo $r_doc['bank_code']; ?>"
                                                                data-icon="fa fa-wordpress">
                                                                <?php echo $r_doc['bank_code']." | ".$r_doc['bank_th_name']; ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_cr_limit1">วงเงิน / บาท</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cus_cr_limit1" name="cus_cr_limit1"
                                                            style="color:blue;text-align:right" onkeyup="format(this)"
                                                            onchange="format(this)"
                                                            value="<?php echo $cus_cr_limit1;?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-3"></div>

                                                <div class="col-md-3 text-center"></div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_bg2">ธนาคาร</label>
                                                        <select data-placeholder="Select a doc type ..."
                                                            class="form-control input-sm border-info font-small-2 select2"
                                                            id="cus_bg2" name="cus_bg2">
                                                            <option value="" selected>--- เลือกธนาคาร ---</option>
                                                            <?php
                                $sql_doc = "SELECT * FROM bank_mstr where bank_status='1' order by bank_id";
                                $result_doc = sqlsrv_query($conn, $sql_doc);
                                while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                ?>
                                                            <option value="<?php echo $r_doc['bank_code']; ?>"
                                                                data-icon="fa fa-wordpress">
                                                                <?php echo $r_doc['bank_code']." | ".$r_doc['bank_th_name']; ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_cr_limit2">วงเงิน / บาท</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cus_cr_limit2" name="cus_cr_limit2"
                                                            style="color:blue;text-align:right" onkeyup="format(this)"
                                                            onchange="format(this)"
                                                            value="<?php echo $cus_cr_limit2;?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bs-callout-warning callout-transparent callout-bordered">
                                                <div class="media align-items-stretch">
                                                    <div
                                                        class="media-left d-flex align-items-center bg-warning position-relative callout-arrow-left p-2">
                                                        <i class="fa fa-bell-o white font-medium-5"></i>
                                                    </div>
                                                    <div class="media-body p-1 font-small-2 text-bold-400 text-grey">
                                                        <strong>หมายเหตุ </strong>
                                                        <p>ช่วง 3-6 เดือนแรก
                                                            ควรตกลงเงื่อนไขการชำระเงินเป็นโอนเงินก่อนส่ง
                                                            หรือขายภายใต้วงเงินค้ำประกันธนาคาร (BG)
                                                            เพื่อประเมินความเสี่ยงเบื้องต้น</p>
                                                    </div>
                                                </div>
                                            </div><br>

                                            <hr width=100%>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600">
                                                            <u>กำหนดการจ่ายชำระเงิน</u></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row skin skin-square">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_cond_term" id="cus_cond_term1"
                                                            value="1">
                                                        <label for="cus_cond_term1">ชำระทุกวันตาม Due </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="radio" name="cus_cond_term" id="cus_cond_term2"
                                                            value="2">
                                                        <label for="cus_cond_term2">มีเงื่อนไขการวางบิลหรือชำระเงินพิเศษ
                                                            โปรดระบุ</label>
                                                        <!-- <input type="email" class="form-control input-sm font-small-2" id="emailAddress3"> -->
                                                    </div>
                                                </div>

                                                <div class="col-md-4 cus_cond_term_txt" style="display:none;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id=""
                                                            placeholder="โปรดระบุเงื่อนไขการวางบิลหรือชำระเงินพิเศษ">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row dis_step2" style="display:none;">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cus_pay_addr">สถานที่วางบิล / ชำระค่าสินค้า
                                                            :</label>
                                                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_pay_addr" name="cus_pay_addr" maxlength="255">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="cus_contact_nme_pay">บุคคลที่ติดต่อเรื่องการจ่ายชำระเงิน
                                                            :</label>
                                                        <input type="email" class="form-control always-show-maxlength input-sm font-small-2"
                                                            id="cus_contact_nme_pay" name="cus_contact_nme_pay" maxlength="255">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row dis_step2" style="display:none;">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_contact_tel">เบอร์โทรศัพท์ (999) 999-9999
                                                            :</label>
                                                        <input type="text"
                                                            class="form-control phone-inputmask input-sm font-small-2"
                                                            id="cus_contact_tel" name="cus_contact_tel"
                                                            placeholder="ป้อนหมายเลขโทรศัพท์">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_contact_fax">เบอร์ Fax :</label>
                                                        <input type="email" class="form-control input-sm font-small-2"
                                                            id="cus_contact_fax" name="cus_contact_fax">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cus_contact_email">E-mail :</label>
                                                        <input type="text"
                                                            class="form-control input-sm font-small-2 email-inputmask"
                                                            id="cus_contact_email" name="cus_contact_email"
                                                            placeholder="ป้อนอีเมล">
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                        <!-- Step 3 -->
                                        <h6><i class="step-icon fa fa-pencil"></i>Step 3</h6>
                                        <fieldset>
                                            <h4 class="form-section text-info"><i class="fa fa-id-card"></i>
                                                ขั้นตอนการแต่งตั้งลูกค้า
                                                (กรอกเฉพาะกรณีแต่งตั้งลูกค้าใหม่)</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600"><u>เป้าหมายการขาย </u></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cusd_tg_beg_date">เป้าหมายการขาย 6 เดือนแรก
                                                            เริ่มตั้งแต่วันที่ :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_tg_beg_date" name="cusd_tg_beg_date">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cusd_tg_end_date">ถึง :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_tg_end_date" name="cusd_tg_end_date">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cusd_sale_est">ประมาณการขายทุกเดือน เดือนละ (บาท)
                                                            :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_sale_est" name="cusd_sale_est" style="color:blue;text-align:right" onkeyup="format(this)"
                                                            onchange="format(this)">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cusd_sale_vol">หรือ ภายใน 6 เดือน สามารถขายได้ (บาท)
                                                            :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_sale_vol" name="cusd_sale_vol" style="color:blue;text-align:right" onkeyup="format(this)"
                                                            onchange="format(this)">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600"><u>วัตถุประสงค์ /
                                                                นโยบายด้านการตลาด </u></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table mb-1" id="dynamic_obj">
                                                            <tr>
                                                                <td><input type="text" name="cusd_obj[]" id="cusd_obj"
                                                                        placeholder="ระบุวัตถุประสงค์/นโยบายด้านการตลาด..."
                                                                        class="form-control input-sm font-small-2" />
                                                                </td>
                                                                <td><button type="button" name="btn-add-obj"
                                                                        id="btn-add-obj"
                                                                        class="btn btn-success btn-sm"><i
                                                                            class="ft-plus"></i></button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600"><u>คุณสมบัติลูกค้า </u>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table mb-1" id="dynamic_prop">
                                                            <tr>
                                                                <td><input type="text" name="cusd_cust_prop[]"
                                                                        placeholder="ระบุคุณสมบัติลูกค้า..."
                                                                        class="form-control input-sm font-small-2" />
                                                                </td>
                                                                <td><button type="button" name="btn-add-prop"
                                                                        id="btn-add-prop"
                                                                        class="btn btn-success btn-sm"><i
                                                                            class="ft-plus"></i></button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600"><u>กิจการในเครือ
                                                                (Affiliate / Related Company) </u>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table mb-1" id="dynamic_aff">
                                                            <tr>
                                                                <td><input type="text" name="cusd_aff[]"
                                                                        placeholder="ระบุกิจการในเครือ..."
                                                                        class="form-control input-sm font-small-2">
                                                                </td>
                                                                <td><button type="button" name="btn-add-aff"
                                                                        id="btn-add-aff"
                                                                        class="btn btn-success btn-sm"><i
                                                                            class="ft-plus"></i></button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600">
                                                            <u>รายชื่อผู้แทนจำหน่ายทั่วไป
                                                                ซึ่งลูกค้าที่ขอแต่งตั้งติดต่อเป็นประจำ (Trade Reference)
                                                            </u></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table mb-1" id="dynamic_dealer">
                                                            <tr>
                                                                <td><input type="text" name="cusd_dealer_nme[]"
                                                                        placeholder="ระบุรายชื่อผู้แทนจำหน่ายทั่วไป..."
                                                                        class="form-control input-sm font-small-2">
                                                                </td>
                                                                <td><input type="text" name="cusd_dealer_avg_val[]"
                                                                        placeholder="ระบุมูลค่าเฉลี่ย/เดือน"
                                                                        class="form-control input-sm font-small-2">
                                                                </td>
                                                                <td><button type="button" name="btn-add-dealer"
                                                                        id="btn-add-dealer"
                                                                        class="btn btn-success btn-sm"><i
                                                                            class="ft-plus"></i></button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600"><u>รายชื่อคู่แข่ง
                                                                ซึ่งลูกค้าที่ขอแต่งตั้งซื้อเป็นประจำ
                                                            </u></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table mb-1" id="dynamic_comp">
                                                            <tr>
                                                                <td><input type="text" name="cusd_comp_nme[]"
                                                                        placeholder="ระบุรายชื่อคู่แข่ง..."
                                                                        class="form-control input-sm font-small-2">
                                                                </td>
                                                                <td><input type="text" name="cusd_comp_avg_val[]"
                                                                        placeholder="ระบุมูลค่าเฉลี่ย/เดือน"
                                                                        class="form-control input-sm font-small-2">
                                                                </td>
                                                                <td><button type="button" name="btn-add-comp"
                                                                        id="btn-add-comp"
                                                                        class="btn btn-success btn-sm"><i
                                                                            class="ft-plus"></i></button>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <p class="font-small-2 text-bold-600"><u>ผู้ดูแลลูกค้า </u></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="label-control">ผู้เสนอ : </label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="hidden" class="form-control input-sm font-small-2"
                                                            id="cusd_is_sale1" name="cusd_is_sale1">
                                                        <input name="cusd_is_sale1_name" id="cusd_is_sale1_name"
                                                            value="<?php echo $cusd_is_sale1_name ?>"
                                                            data-disp_col1="emp_fullname"
                                                            data-disp_col2="emp_th_pos_name"
                                                            data-disp_col3="emp_scg_emp_id"
                                                            data-typeahead_src="../_help/get_help_emp_data.php" ,
                                                            data-ret_field_01="cusd_is_sale1_name"
                                                            data-ret_value_01="emp_fullnamepos"
                                                            data-ret_type_01="val"
                                                            data-ret_field_02="cusd_is_sale1_email"
                                                            data-ret_value_02="emp_email_bus"
                                                            data-ret_type_02="html"
                                                            data-ret_field_03="cusd_is_sale1"
                                                            data-ret_value_03="emp_scg_emp_id"
                                                            data-ret_type_03="html"
                                                           
                                                            class="form-control input-sm font-small-2 typeahead">

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <a id="buthelp" data-id_field_code="cusd_is_sale1_name"
                                                                    data-id_field_name="cusd_is_sale1_email"
                                                                    data-id_field_code1="cusd_is_sale1"
                                                                    data-modal_class="modal-dialog modal-lg"
                                                                    data-modal_title="ข้อมูลพนักงาน"
                                                                    data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                                                    data-modal_col_data1="emp_scg_emp_id"
                                                                    data-modal_col_data2="emp_fullnamepos"
                                                                    data-modal_col_data3="emp_dept"
                                                                    data-modal_col_data4="emp_email_bus"
                                                                    data-modal_col_data4_vis=true
                                                                    data-modal_ret_data1="emp_fullnamepos"
                                                                    data-modal_ret_data2="emp_email_bus"
                                                                    data-modal_ret_data3="emp_scg_emp_id"
                                                                    data-modal_src="../_help/get_help_emp_data.php"
                                                                    class="input-group-append" style="cursor:pointer">
                                                                    <span class="fa fa-search"></span>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="cusd_is_sale1_email">Email :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_is_sale1_email" name="cusd_is_sale1_email">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="cusd_sale_est">เบอร์โทร :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_sale_est1" name="cusd_sale_est1">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="label-control">ชื่อผู้แทนขาย (Inside Sale) : </label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="hidden" class="form-control input-sm font-small-2"
                                                            id="cusd_is_sale2" name="cusd_is_sale2">
                                                        <input name="cusd_is_sale2_name" id="cusd_is_sale2_name"
                                                            value="<?php echo $cusd_is_sale2_name ?>"
                                                            data-disp_col1="emp_fullname"
                                                            data-disp_col2="emp_th_pos_name"
                                                            data-disp_col3="emp_scg_emp_id"
                                                            data-typeahead_src="../_help/get_help_emp_data.php" ,
                                                            data-ret_field_01="cusd_is_sale2_name"
                                                            data-ret_value_01="emp_fullnamepos"
                                                            data-ret_type_01="val"
                                                            data-ret_field_02="cusd_is_sale2_email"
                                                            data-ret_value_02="emp_email_bus"
                                                            data-ret_type_02="html"
                                                            data-ret_field_03="cusd_is_sale2"
                                                            data-ret_value_03="emp_scg_emp_id"
                                                            data-ret_type_03="html"
                                                            class="form-control input-sm font-small-2 typeahead">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <a id="buthelp" data-id_field_code="cusd_is_sale2_name"
                                                                    data-id_field_name="cusd_is_sale2_email"
                                                                    data-id_field_code1="cusd_is_sale2"
                                                                    data-modal_class="modal-dialog modal-lg"
                                                                    data-modal_title="ข้อมูลพนักงาน"
                                                                    data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                                                    data-modal_col_data1="emp_scg_emp_id"
                                                                    data-modal_col_data2="emp_fullnamepos"
                                                                    data-modal_col_data3="emp_dept"
                                                                    data-modal_col_data4="emp_email_bus"
                                                                    data-modal_col_data4_vis=true
                                                                    data-modal_ret_data1="emp_fullnamepos"
                                                                    data-modal_ret_data2="emp_email_bus"
                                                                    data-modal_ret_data3="emp_scg_emp_id"
                                                                    data-modal_src="../_help/get_help_emp_data.php"
                                                                    class="input-group-append" style="cursor:pointer">
                                                                    <span class="fa fa-search"></span>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="cusd_is_sale2_email">Email :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_is_sale2_email" name="cusd_is_sale2_email">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="cusd_sale_est">เบอร์โทร :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_sale_est" name="cusd_sale_est">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="label-control">ชื่อผู้แทนขาย (Outside Sale) :</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="hidden" class="form-control input-sm font-small-2"
                                                            id="cusd_os_sale" name="cusd_os_sale">
                                                        <input name="cusd_os_sale_name" id="cusd_os_sale_name"
                                                            value="<?php echo $cusd_os_sale_name ?>"
                                                            data-disp_col1="emp_fullname"
                                                            data-disp_col2="emp_th_pos_name"
                                                            data-disp_col3="emp_scg_emp_id"
                                                            data-typeahead_src="../_help/get_help_emp_data.php" ,
                                                            data-ret_field_01="cusd_os_sale"
                                                            data-ret_value_01="emp_fullnamepos"
                                                            data-ret_type_01="val"
                                                            data-ret_field_02="cusd_os_sale_email"
                                                            data-ret_value_02="emp_email_bus"
                                                            data-ret_type_02="html"
                                                            data-ret_field_03="cusd_os_sale"
                                                            data-ret_value_03="emp_scg_emp_id"
                                                            data-ret_type_03="html"
                                                            class="form-control input-sm font-small-2 typeahead">
                                                        
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <a id="buthelp" data-id_field_code="cusd_os_sale_name"
                                                                    data-id_field_name="cusd_os_sale_email"
                                                                    data-id_field_code1="cusd_os_sale"
                                                                    data-modal_class="modal-dialog modal-lg"
                                                                    data-modal_title="ข้อมูลพนักงาน"
                                                                    data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                                                    data-modal_col_data1="emp_scg_emp_id"
                                                                    data-modal_col_data2="emp_fullnamepos"
                                                                    data-modal_col_data3="emp_dept"
                                                                    data-modal_col_data4="emp_email_bus"
                                                                    data-modal_col_data4_vis=true
                                                                    data-modal_ret_data1="emp_fullnamepos"
                                                                    data-modal_ret_data2="emp_email_bus"
                                                                    data-modal_ret_data3="emp_scg_emp_id"
                                                                    data-modal_src="../_help/get_help_emp_data.php"
                                                                    class="input-group-append" style="cursor:pointer">
                                                                    <span class="fa fa-search"></span>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="cusd_os_sale_email">Email :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_os_sale_email" name="cusd_os_sale_email">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="cusd_sale_est">เบอร์โทร :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_sale_est" name="cusd_sale_est">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="label-control">ชื่อผู้จัดการ : </label>
                                                    <div class="input-group input-group-sm">
                                                        <input name="cusd_sale_manager" id="cusd_sale_manager"
                                                            value="<?php echo $user_manager_name ?>"
                                                            data-disp_col1="emp_fullname" data-disp_col2="emp_email_bus"
                                                            data-typeahead_src="../_help/get_emp_data.php" ,
                                                            data-ret_field_01="cusd_is_sale2"
                                                            data-ret_value_01="emp_fullname" data-ret_type_01="val"
                                                            data-ret_field_02="reviewer_name"
                                                            data-ret_value_02="emp_email_bus" data-ret_type_02="html"
                                                            class="form-control input-sm font-small-2 typeahead">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <a id="buthelp" data-id_field_code="cusd_sale_manager"
                                                                    data-id_field_name="cusd_manger_email"
                                                                    data-modal_class="modal-dialog modal-lg"
                                                                    data-modal_title="ข้อมูลพนักงาน"
                                                                    data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                                                    data-modal_col_data1="emp_scg_emp_id"
                                                                    data-modal_col_data2="emp_fullnamedept"
                                                                    data-modal_col_data3="emp_dept"
                                                                    data-modal_col_data4="emp_email_bus"
                                                                    data-modal_col_data3_vis=true
                                                                    data-modal_col_data4_vis=true
                                                                    data-modal_ret_data1="emp_fullnamedept"
                                                                    data-modal_ret_data2="emp_email_bus"
                                                                    data-modal_src="../_help/get_emp_data.php"
                                                                    class="input-group-append" style="cursor:pointer">
                                                                    <span class="fa fa-search"></span>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="cusd_manger_email">Email :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_manger_email" name="cusd_manger_email"
                                                            value="<?php echo $user_email ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="cusd_manger_dept">หน่วยงานขาย :</label>
                                                        <input type="text" class="form-control input-sm font-small-2"
                                                            id="cusd_manger_dept" name="cusd_manger_dept"
                                                            value="<?php echo $cusd_manger_dept ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <!-- Step 4 -->
                                        <h6><i class="step-icon fa fa-tv"></i>Step 4</h6>
                                        <fieldset>
                                            <h4 class="form-section text-info"><i class="fa fa-picture-o"></i>
                                                เอกสารประกอบ</h4>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h6><i class="fa fa-check"></i> เอกสารประกอบที่ต้องมี</h6>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="table-responsive">
                                                        <table id="book_table"
                                                            class="table table-striped table-sm table-hover table-bordered"
                                                            style="width:100%; font-size:0.89em;">
                                                            <thead>
                                                                <tr>
                                                                    <!-- <th>No.</th> -->
                                                                    <th>Domestic</th>
                                                                    <th>Export</th>
                                                                    <th>ลูกค้าทั่วไป</th>
                                                                    <th>ลูกค้าเครือ SCG</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="form-group col-12 mb-2">
                                                            <label id="projectinput8" class="file center-block">
                                                                <input type="file" name="multiple_files"
                                                                    id="multiple_files" multiple />
                                                                <span class="file-custom"></span>
                                                                <span class="text-muted">Only .jpg, png, .gif file
                                                                    allowed</span>
                                                                <span id="error_multiple_files"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <!-- <br /> -->
                                                    <div class="table-responsive mb-2" id="image_table">
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <!-- Step 5 -->
                                        <h6><i class="step-icon fa fa-image"></i>Step 5</h6>
                                        <fieldset>
                                            <h4 class="form-section text-info"><i class="fa fa-picture-o"></i>
                                                ความเห็นของผู้แทนขาย &
                                                ผู้พิจารณา & ผู้อนุมัติ</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cusd_test">ความเห็นของผู้แทนขาย
                                                            (ใช้สำหรับเสนอขออนุมัติ) :</label>
                                                        <textarea name="crstm_sd_reson" id="crstm_sd_reson"
                                                            class="form-control textarea-maxlength input-sm font-small-2 border-info"
                                                            placeholder="Enter upto 500 characters.." maxlength="500"
                                                            rows="9" style="line-height:1.5rem;"></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="decisions2">อำนาจดำเนินการ :</label>
                                                        <input type="email" class="form-control input-sm font-small-2"
                                                            id="cusd_op_app" name="cusd_op_app" value="<?php echo $cusd_op_app; ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">

                                                            <!-- ผู้พิจารณา 1 -->
                                                            <div class="col-md-12 mb-1">
                                                                <label class="label-control">ผู้พิจารณา 1 (ผส.)
                                                                    :</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input name="cusd_review1" id="cusd_review1"
                                                                        value="<?php echo $cusd_review1 ?>"
                                                                        data-disp_col1="emp_fullname"
                                                                        data-disp_col2="emp_th_pos_name"
                                                                        data-typeahead_src="../_help/get_help_emp_data.php" ,
                                                                        data-ret_field_01="cusd_review1"
                                                                        data-ret_value_01="emp_fullnamepos"
                                                                        data-ret_type_01="val"
                                                                        data-ret_field_02="reviewer1_name"
                                                                        data-ret_value_02="emp_th_pos_name"
                                                                        data-ret_type_02="html"
                                                                        class="form-control input-sm font-small-2 typeahead">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">
                                                                            <a id="buthelp"
                                                                                data-id_field_code="cusd_review1"
                                                                                data-id_field_name="emp_fullname"
                                                                                data-modal_class="modal-dialog modal-lg"
                                                                                data-modal_title="ข้อมูลพนักงาน"
                                                                                data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                                                                data-modal_col_data1="emp_scg_emp_id"
                                                                                data-modal_col_data2="emp_fullnamepos"
                                                                                data-modal_col_data3="emp_scg_emp_id"
                                                                                data-modal_col_data4="emp_email_bus"
                                                                                data-modal_col_data3_vis=true
                                                                                data-modal_col_data4_vis=true
                                                                                data-modal_ret_data1="emp_fullnamepos"
                                                                                data-modal_ret_data2="emp_email_bus"
                                                                                data-modal_src="../_help/get_help_emp_data.php"
                                                                                class="input-group-append"
                                                                                style="cursor:pointer">
                                                                                <span class="fa fa-search"></span>
                                                                            </a>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                           <!--  <div class="col-md-6 mb-1">
                                                                <label for="decisions2">ตำแหน่ง :</label>
                                                                <input type="text"
                                                                    class="form-control input-sm font-small-2"
                                                                    id="reviewer1_name" name="reviewer1_name">
                                                            </div> -->

                                                            <!-- ผู้พิจารณา 2 -->
                                                            <div class="col-md-12 mb-1">
                                                                <label class="label-control">ผู้พิจารณา 2 (ผฝ.)
                                                                    :</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input name="cusd_review1" id="cusd_review2"
                                                                        value="<?php echo $cusd_review2 ?>"
                                                                        data-disp_col1="emp_fullname"
                                                                        data-disp_col2="emp_th_pos_name"
                                                                        data-typeahead_src="../_help/get_help_emp_data.php" ,
                                                                        data-ret_field_01="cusd_review2"
                                                                        data-ret_value_01="emp_fullnamepos"
                                                                        data-ret_type_01="val"
                                                                        data-ret_field_02="reviewer2_name"
                                                                        data-ret_value_02="emp_th_pos_name"
                                                                        data-ret_type_02="html"
                                                                        class="form-control input-sm font-small-2 typeahead">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">
                                                                            <a id="buthelp"
                                                                                data-id_field_code="cusd_review2"
                                                                                data-id_field_name="emp_fullname"
                                                                                data-modal_class="modal-dialog modal-lg"
                                                                                data-modal_title="ข้อมูลพนักงาน"
                                                                                data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                                                                data-modal_col_data1="emp_scg_emp_id"
                                                                                data-modal_col_data2="emp_fullnamepos"
                                                                                data-modal_col_data3="emp_dept"
                                                                                data-modal_col_data4="emp_email_bus"
                                                                                data-modal_col_data3_vis=true
                                                                                data-modal_col_data4_vis=true
                                                                                data-modal_ret_data1="emp_fullnamepos"
                                                                                data-modal_ret_data2="emp_email_bus"
                                                                                data-modal_src="../_help/get_help_emp_data.php"
                                                                                class="input-group-append"
                                                                                style="cursor:pointer">
                                                                                <span class="fa fa-search"></span>
                                                                            </a>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-6 mb-1">
                                                                <label for="decisions2">ตำแหน่ง :</label>
                                                                <input type="email"
                                                                    class="form-control input-sm font-small-2"
                                                                    id="cus_contact_fax" name="cus_contact_fax">
                                                            </div> -->

                                                            <!-- ผู้พิจารณา 3 -->
                                                            <div class="col-md-12 mb-1">
                                                                <label class="label-control">ผู้พิจารณา 3 (CMO)
                                                                    :</label>
                                                                <input type="email"
                                                                    class="form-control input-sm font-small-2"
                                                                    id="cus_contact_fax" name="cus_contact_fax">
                                                            </div>
                                                           <!--  <div class="col-md-6 mb-1">
                                                                <label for="decisions2">ตำแหน่ง :</label>
                                                                <input type="email"
                                                                    class="form-control input-sm font-small-2"
                                                                    id="cus_contact_fax" name="cus_contact_fax">
                                                            </div> -->

                                                            <!-- ผู้พิจารณา 4 -->
                                                            <div class="col-md-12 mb-1">
                                                                <label class="label-control">ผู้พิจารณา 4 (CFO)
                                                                    :</label>
                                                                <input type="email"
                                                                    class="form-control input-sm font-small-2"
                                                                    id="cus_contact_fax" name="cus_contact_fax">
                                                            </div>
                                                           <!--  <div class="col-md-6 mb-1">
                                                                <label for="decisions2">ตำแหน่ง :</label>
                                                                <input type="email"
                                                                    class="form-control input-sm font-small-2"
                                                                    id="cus_contact_fax" name="cus_contact_fax">
                                                            </div> -->

                                                            <!-- ผู้อนุมัติ (กจก.) -->
                                                            <div class="col-md-12 mb-1">
                                                                <label class="label-control">ผู้อนุมัติ (กจก.) :</label>
                                                                <input type="email"
                                                                    class="form-control input-sm font-small-2"
                                                                    id="cus_contact_fax" name="cus_contact_fax">
                                                            </div>
                                                           <!--  <div class="col-md-6 mb-1">
                                                                <label for="decisions2">ตำแหน่ง :</label>
                                                                <input type="email"
                                                                    class="form-control input-sm font-small-2"
                                                                    id="cus_contact_fax" name="cus_contact_fax">
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                    <form name="frm_del_img" id="frm_del_img"
                                        action="../serverside/upload_img_post.php">
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
            </section>
            <!-- Form wizard with icon tabs section end -->
        </div>
    </div>
</div>

<? include("../crctrlmain/menu_footer.php"); ?>

<div class="to-top">
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<!-- BEGIN VENDOR JS-->
<script src="../theme/app-assets/vendors/js/vendors.min.js"></script>
<script src="../theme/app-assets/vendors/js/forms/icheck/icheck.min.js"></script>
<script src="../theme/app-assets/vendors/js/extensions/jquery.steps.min.js"></script>
<script src="../theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
<script src="../theme/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js"></script>

<!-- <script src="../theme/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script> -->
<script src="../theme/app-assets/js/core/app-menu.js"></script>
<script src="../theme/app-assets/js/core/app.js"></script>
<!-- <script src="./theme/app-assets/js/scripts/customizer.min.js"></script>  -->
<script src="../theme/app-assets/js/scripts/forms/wizard-steps.js?v<?php echo rand(); ?>"></script>
<script src="../theme/app-assets/js/scripts/forms/checkbox-radio.min.js"></script>
<!-- <script src="../theme/app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js"></script> -->
<script src="../_libs/js/bootstrap3-typeahead.min.js"></script>
<script src="../theme/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
<script src="../theme/app-assets/js/scripts/forms/extended/form-inputmask.min.js"></script>
<script src="../theme/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="../theme/app-assets/js/scripts/forms/form-repeater.js"></script>
<script src="../theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="../theme/app-assets/vendors/js/ui/headroom.min.js"></script>
<script src="../theme/app-assets/js/core/main.js"></script> <!-- to-Top -->
<script src="../theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>

<script src="../_libs/js/script.js?v<?php echo rand(); ?>"></script>

<script src="../theme/app-assets/vendors/js/forms/extended/maxlength/bootstrap-maxlength.js"></script>
<script src="../theme/app-assets/js/scripts/forms/extended/form-maxlength.min.js"></script>

<script src="../theme/app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js"></script>
<!-- <script src="../theme/app-assets/js/scripts/ui/jquery-ui/navigations.js?v<?php echo rand(); ?>"></script> -->
<!-- END PAGE LEVEL JS-->
<script type="text/javascript">
< /body> 
< /html >