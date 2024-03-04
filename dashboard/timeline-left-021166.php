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
?>
<?php include("../newcust/header.php"); ?>
<?php include("../crctrlmain/menu_header.php"); ?>
<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
<div class="app-content content font-small-2">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6  mb-2">
                <!-- <h3 class="content-header-title">Timeline Left</h3> -->
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper ">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Home</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Timelines</a>
                            </li>
                            <li class="breadcrumb-item active">Timeline Left
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 ">
                <div class="media width-250 float-right">
                    <media-left class="media-middle">
                        <div id="sp-bar-total-sales"></div>
                    </media-left>
                    <!-- <div class="media-body media-right text-right">
                <h3 class="m-0">$5,668</h3><span class="text-muted">Sales</span>
              </div> -->
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card"> 
                        <div class="card-header"> 
                            <h4 class="card-title" id="basic-layout-form">Upload Risk Categories</h4>
                        </div> 
                            <div class="card-body">
                                <div class="bs-callout-success callout-border-right callout-bordered callout-transparent p-1 mt-1">   
                                    <div class="col-lg-12 mt-n1">
                                        <div class="row pr-1 pl-1 ">
                                            <div class="col-lg-5 col-md-6 pt-1 ">ชื่อจดทะเบียน :</div>
                                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_reg_nme; ?></div>
                                        </div>	
                                        <div class="row pr-1 pl-1 ">
                                            <div class="col-lg-5 col-md-6 pt-1 ">ที่อยู่จดทะเบียน :</div>
                                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_reg_addr." ".$cus_district." ".$cus_amphur." ".$cus_prov." ".$cus_zip; ?></div>
                                        </div>		
                                        <div class="row pr-1 pl-1 ">
                                            <div class="col-lg-5 col-md-6 pt-1 ">เบอร์โทรศัพท์ :</div>
                                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_tel; ?></div>
                                        </div>
                                        <div class="row pr-1 pl-1 ">
                                            <div class="col-lg-5 col-md-6 pt-1 ">ประเภทการจดทะเบียนบริษัท :</div>
                                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_type_bus_name; ?></div>
                                        </div>
                                        <div class="row pr-1 pl-1 ">
                                            <div class="col-lg-5 col-md-6 pt-1 ">อีเมล์ :</div>
                                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_email; ?></div>
                                        </div>		
                                        <div class="row pr-1 pl-1 ">
                                            <div class="col-lg-5 col-md-6 pt-1 ">เลขประจำตัวผู้เสียภาษี :</div>
                                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_tax_id; ?></div>
                                        </div>	
                                        <div class="row pr-1 pl-1 ">
                                            <div class="col-lg-5 col-md-6 pt-1 ">สาขาที่ :</div>
                                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_branch; ?></div>
                                        </div>		
                                    </div>
                                </div>
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
                                <?php 
                                $cus_ap_nbr = "NC-2310-0003";
                                $cus_create_by = findsqlval("cus_app_mstr", "cus_create_by", "cus_app_nbr", $cus_ap_nbr,$conn);
                                $cus_create_name = findsqlval("emp_mstr","emp_prefix_th_name + emp_th_firstname + ' ' + emp_th_lastname","emp_user_id",$cus_create_by,$conn);

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
                                        $cus_ap_create_date = date_format($r_hist['cus_ap_create_date'],"d/m/Y H:i:s");
                                        $cus_process_name = findsqlval("cusstep_mstr", "cusstep_name_th", "cusstep_code", $cus_process,$conn);
                                        $cus_ap_create_name = findsqlval("emp_mstr","emp_prefix_th_name + emp_th_firstname + ' ' + emp_th_lastname" ,"emp_user_id",$cus_ap_create_by,$conn);
                                        //$cus_ap_create_by = findsqlval("emp_mstr","emp_prefix_th_name + emp_th_firstname + ' ' + emp_th_lastname +'('+  emp_th_pos_name +')'" ,"emp_user_id",$cus_ap_create_by,$conn);
                                       													
                                ?>
                                <ul class="timeline">
                                    <li class="timeline-line"></li>
                                    <li class="timeline-item">
                                        <div class="timeline-badge">
                                            <span class="bg-red bg-lighten-1" data-toggle="tooltip" data-placement="right"
                                                title="Portfolio project work"><i class="fa fa-plane"></i></span>
                                        </div>
                                        <div class="timeline-card card border-grey border-lighten-2">
                                            <div class="card-header">
                                                <h4 class="card-title"><a href="#"><?php echo $stepno; ?>. <?php echo $cus_ap_text; ?></a></h4>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2"><?php echo $cus_ap_create_name; ?></span>
                                                </p>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2">วันที่ <?php echo $cus_ap_create_date; ?></span>
                                                </p>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2"><?php echo $cus_ap_remark; ?></span>
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
                                                title="Portfolio project work"><i class="fa fa-plane"></i></span>
                                        </div>
                                        <div class="timeline-card card border-grey border-lighten-2">
                                        <div class="card-header">
                                                <h4 class="card-title"><a href="#"><?php echo $stepno; ?>. <?php echo $cus_ap_text; ?></a></h4>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2"><?php echo $cus_create_name; ?></span>
                                                </p>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2">วันที่ <?php echo $cus_ap_create_date; ?></span>
                                                </p>
                                                <p class="card-subtitle text-muted pt-1">
                                                    <span class="font-small-2"><?php echo $cus_ap_remark; ?></span>
                                                </p>
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