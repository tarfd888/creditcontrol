<?php	
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php"); 
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";
include_once('../_libs/Thaidate/Thaidate.php');
include_once('../_libs/Thaidate/thaidate-functions.php');

clearstatcache();
date_default_timezone_set('Asia/Bangkok');
$curdate = date("d/m/Y H:i:s");
$curYear = date('Y'); 
$nextYear = date("Y", strtotime("+5 years"));
$previousYear = date("Y", strtotime("-1 years"));

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    if (!matchToken($csrf_key, $user_login)) {
        echo "System detect CSRF attack!!";
        exit;
    }
}
$msg = $_REQUEST['msg'];
?>
<?php include("../newcust/header.php"); ?>
<?php include("../crctrlmain/menu_header.php"); ?>
<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
    <div class="app-content content font-small-2">
        <div class="content-wrapper">
            <div class="content-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">Upload Risk Categories</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-small-2"></i></a>
                                <!-- <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        <li><a data-action="close"><i class="ft-x"></i></a></li> 
                                        <button type="button" id="btnsave" name="btnsave" value="add" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"><i class="fa fa-check-square-o"></i> Save</button>

                                    </ul>
                                </div>  -->
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <form id="frm_risk_add" name="frm_risk_add" autocomplete=OFF method="POST">
                                        <input type="hidden" name="action" id="action" value="risk_add">
                                        <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token)?>">
                                        <input type="hidden" name="cr_cust_code" id="cr_cust_code">
                                        <div class="form-body">
                                            <!-- <h4 class="form-section"><i class="ft-user"></i> Personal Info</h4> -->
                                            <div class="row">
                                                <div class="add-risk col-md-6">
                                                    <div class="form-group">
                                                        <label for="projectinput1">รหัสลูกค้า</label>
                                                        <input type="text" id="up_cust_code" class="form-control input-sm font-small-2"
                                                            placeholder="" name="up_cust_code">
                                                    </div>
                                                </div>
                                                <div class="add-risk col-md-6">
													<div class="form-group">
														<label  for="userinput1">ประจำปีล่าสุด :</label>
															<select data-placeholder="เลือกประจำปี ..." class="form-control input-sm border-warning font-small-3 select2" id="up_year" name="up_year">
																<option value="" selected>--- เลือกประจำปีล่าสุด ---</option>
																<?php
																	$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy WHERE tbl_yy_desc BETWEEN $previousYear AND $nextYear ORDER BY tbl_yy_desc";
                                                                    //$sql_year = "SELECT * FROM year_mstr WHERE year_desc BETWEEN $curYear AND $nextYear ORDER BY year_desc";

																	$result_doc = sqlsrv_query($conn, $sql_doc);
																	while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																	?>
																	<option value="<?php echo $r_doc['tbl_yy_desc']; ?>" data-icon="fa fa-wordpress"><?php echo $r_doc['tbl_yy_desc']; ?></option>
																<?php } ?>
															</select>
													</div>
                                                </div>
                                            </div>
                                            <div class="form-group showupload" style="display:none">
												<label id="projectinput8" class="file center-block">
													<input type="file" name="multi_risk_add" id="multi_risk_add" multiple />
													<span class="file-custom"></span>
													<span class="text-muted">Only jpg, png, gif, pdf, xls, doc file
														allowed</span>
													<span id="error_multiple_files"></span>
												</label>
                                            </div>
                                            <div class="form-actions right">
                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">
                                                    <button type="button" id="btnclose" name="btnclose" class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1"><i class="fa fa-check-square-o"></i> Clear</button>
                                                    <button type="button" id="btnview" name="btnview" value="view" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"><i class="fa fa-check-square-o"></i> Show</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <form name="frm_del_risk" id="frm_del_risk" action="">
                                        <input type="hidden" name="action" value="del_risk">
                                        <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
                                        <input type="hidden" name="risk_id" value="">
                                        <input type="hidden" name="risk_name" value="">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" id="basic-layout-colored-form-control input-sm font-small-2">Risk Categories</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-small-2"></i></a>
                                <!-- <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        <li><a data-action="close"><i class="ft-x"></i></a></li>
                                    </ul>
                                </div> -->
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
									<div class="table-responsive mb-2" id="risk_table">
									</div>														
                                </div>
                            </div>
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

<? include("file_script.php"); ?>
<script type="text/javascript">

</body>

</html>