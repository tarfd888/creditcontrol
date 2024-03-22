<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";
include("../crctrlbof/chkauthcrctrl.php");
include("../crctrlbof/chkauthcr.php");
include_once('../_libs/Thaidate/Thaidate.php');
include_once('../_libs/Thaidate/thaidate-functions.php');

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
	if (!matchToken($csrf_key, $user_login)) {
		echo "System detect CSRF attack!!";
		exit;
	}
}
clearstatcache();
date_default_timezone_set('Asia/Bangkok');
$curdate = date("d/m/Y H:i:s");
$curYear = date('Y'); 
$nextYear = date("Y", strtotime("+5 years"));
$previousYear = date("Y", strtotime("-1 years"));
$headtitle =" - รายงานสรุป Risk Categories";
	
?>
<?php include("header.php"); ?>

<?php include("../crctrlmain/menu_header.php"); ?>
<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
<!-- BEGIN: Content-->
<div class="app-content content font-small-2">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
                <h3 class="content-header-title mb-0 d-inline-block">Report</h3>
                <div class="row breadcrumbs-top d-inline-block">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../crctrlbof/crctrlall.php">Home</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <font color="40ADF4">รายงานสรุป Risk Categories</font>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body font-small-2 mt-n1">
            <!-- Province All -->
            <section id="project-all">
                <div class="row grouped-multiple-statistics-card">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" id="basic-layout-form">Data search results</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-small-2"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <!--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
												<li><a data-action="expand"><i class="ft-maximize"></i></a></li>
												<li><a data-action="close"><i class="ft-x"></i></a></li>-->
                                    </ul>
                                </div>
                            </div>

                            <div id="content-search-box" class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="font-weight-bold">ลูกค้า</label>
                                                <div class="input-group input-group-sm">
                                                    <input id="cus_code" name="cus_code" value="<?php echo $cus_code ?>"
                                                        class="form-control input-sm border-info font-small-2"
                                                        type="hidden">
                                                    <input id="cus_name" name="cus_name" value="<?php echo $cus_name ?>"
                                                        class="form-control input-sm border-info font-small-2"
                                                        type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
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
                                        
										<div class="form-actions right pt-2 showBtnSearch" style="display:none">
											<div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">
												<button type="button" id="but_search" 
													class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1"><i	class="fa fa-search"></i> Search</button>
											</div>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--- End Search -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content collapse show">
                                <div class="card-body ">
                                    <div class="table-responsive">
                                        <!-- Project All -->
										 <table id="table-risk" class="table table-striped table-sm table-hover table-bordered compact nowrap"
                        					style="width:100%; font-size:0.89em;">	
                                            <thead class="text-center">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>รหัสลูกค้า</th>
                                                    <th>ชื่อลูกค้า</th>
                                                    <th>ชื่อไฟล์</th>
                                                    <th>ปีล่าสุด</th>
                                                    <th>รูปภาพ</th>
                                                    <th>Action</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                        <div class="loader-wrapper">
                                            <div class="loader-container">
                                                <div class="ball-spin-fade-loader loader-blue">
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <form name="frm_del_risk" id="frm_del_risk" action="">
                            <input type="hidden" name="action" value="del_risk">
                            <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
                            <input type="hidden" name="risk_id" value="">
                            <input type="hidden" name="risk_name" value="">
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- END: Content-->

<? include("../crctrlmain/menu_footer.php"); ?>
<div class="to-top">
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<!-- BEGIN: Vendor JS-->
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/vendors.min.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/unslider-min.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/pickers/daterange/daterangepicker.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app-menu.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/app.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/scripts/tables/datatables/datatable-basic.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert.min.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="<?php echo BASE_DIR;?>/_libs/js/bootstrap3-typeahead.min.js"></script>
<script src="<?php echo BASE_DIR;?>/theme/app-assets/js/core/main.js"></script> <!-- to-Top -->
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $(".ball-spin-fade-loader").hide(); 
    setTimeout(() => { $("#content-search-box").find(".showBtnSearch").show(); }, 1200);
});

function setcookie(input0, callback) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '../serverside/risk_setcookie.php',
        data: {
            param0: JSON.stringify(input0)
        },
        async: false,
        timeout: 50000,
        success: function(result) {
            //alert("close",5000);
            //var json = $.parseJSON(result);
            //return callback(json.ret);
        }
    });
}

$(document).on("click", "#but_search", function() {
    var iscookie;
    var $cus_name = "";
    let input0 = {};
    input0.cus_code = $("#cus_code").val();
    input0.up_year = $("#up_year").val();
    //alert(input0.cus_code);
    setcookie(input0, function(results) {
        iscookie = results;
    });

    $.ajax({
        url: "../serverside/risk_list.php",
        type: "POST",
        dataType: 'json',
        data: {
            param0: JSON.stringify(input0)
        },
        beforeSend: function() {
            // $('body').append(
            // '<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
            $(".ball-spin-fade-loader").show(); 
            $('#table-risk').DataTable().clear().destroy();
        },
        success: function(res) {
            if (res.success) {
                console.log(res);
                $("#table-risk").dataTable().fnDestroy();
                $("#table-risk").dataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'excel',
                        /* {
                        	extend: 'colvis',
                        	collectionLayout: 'fixed four-column'
                        } */
                    ],
                    "aaData": res.data,
                    "cache": false,
                    "columns": [{ // Add row no. (Line 1,2,3,n)
                            "data": "id",
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "data": "risk_cust_nbr"
                        },
                        {
                            "data": "risk_name"
                        },
                        {
                            "data": "risk_fname"
                        },
                        {
                            "data": "risk_year"
                        },
                        {
                            "targets": [5],
                            "render": function(data, type, row, meta) {
                                var btnActionALL = "";
                                var btnAction_View = '<a href="'+ row.Image +'" target="_blank"><img src="' + row.Image_icon +'" class="img-thumbnail" width="40" height="40"></a>';
                                    btnActionALL = btnAction_View; 

                                return btnActionALL;
                            }
                        },
                        {
                            "targets": [6],
                            "render": function(data, type, row, meta) {
                                var btnActionALL = "";

                                var btnAction_Del  = '<a id="btndel" data-risk_cust_nbr ="' + row.risk_cust_nbr + '" data-id1="' +row.risk_id + '" data-risk_name="' +row.risk_fname + '" href="javascript:void(0)"><i class="fa fa-trash-o fa-sm"></i></a>';
                                //var btnAction_View = '<img src="../_fileuploads/ac_risk/812-kitchen1.jpg" width="40px">';
                                    btnActionALL = btnAction_Del ; 

                                return btnActionALL;
                            }
                        },
                        {
                            "data": "risk_id",
                            "visible": false
                        }

                    ],
                    "columnDefs": [
                        {
                            "className": "text-center",
                            "targets": [0, 1, 4, 5, 6]
                        },
                    ],
                    "searching": false,
                    "ordering": false,
                    "stateSave": true,
                    "scrollY": "50vh",
					"scrollX": true,
                    "pageLength": 10,
                    "pagingType": "simple_numbers",
                });
                $("#table-risk").fadeIn();
            }
        },
        complete: function() {
            $(".loading").fadeOut();
            $(".ball-spin-fade-loader").hide(); 
        },
        error: function(res) {
            console.log(res)
            alert('error');
        }
    });
});

$(document).on('click', '#btndel', function(e) {
    //var risk_id = $(this).attr("id1");
    var risk_id = $(this).data("id1");
    var risk_name = $(this).data("risk_name");
    //alert(risk_id + '--' + risk_name);
    Swal.fire({
      title: "Are you sure?",
      html: "คุณต้องการลบไฟล์ นี้ใช่หรือไหม่ !!! ",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      confirmButtonClass: "btn btn-primary",
      cancelButtonClass: "btn btn-danger ml-1",
      buttonsStyling: false,
      showLoaderOnConfirm: true,
      preConfirm: function() {
        return new Promise(function(resolve) {
          document.frm_del_risk.risk_id.value = risk_id;
          document.frm_del_risk.risk_name.value = risk_name;
          $.ajax({
            beforeSend: function() {
                $(".ball-spin-fade-loader").show(); 
            },
            type: 'POST',
            url: '../serverside/upload_risk_post.php',
            data: $('#frm_del_risk').serialize(),
            timeout: 50000,
            error: function(xhr, error) {
              showmsg('[' + xhr + '] ' + error);
            },
            success: function(result) {
              //console.log(result);
              //alert(result);
              var json = $.parseJSON(result);
              if (json.r == '0') {
                //clearloadresult();
                Swal.fire({
                  title: "Error!",
                  html: json.e,
                  type: "error",
                  confirmButtonClass: "btn btn-danger",
                  buttonsStyling: false
                });
                } else {
                //clearloadresult();
                Swal.fire({
                  type: "success",
                  title: "ลบข้อมูลเรียบร้อยแล้ว",
                  showConfirmButton: false,
                  timer: 1500,
                  confirmButtonClass: "btn btn-primary",
                  buttonsStyling: false,
                  animation: false,
                });
                //load_risk_data();
                //location.reload(true);
                //$(location).attr('href', 'risk_list.php?id='+json.nb)
                setTimeout(() => { $("#content-search-box").find("#but_search").trigger('click'); }, 100);
              }
            },
            complete: function() {
              $(".ball-spin-fade-loader").hide(); 
              $("#requestOverlay").remove(); /*Remove overlay*/
            }
          });
        });
      },
      allowOutsideClick: false
    });
    e.preventDefault();
});

$('#cus_name').click(function(){
    $(".showBtnSearch").show(); 
});

$('#cus_name').typeahead({
    displayText: function(item) {
        return item.cus_nbr + " " + item.cus_name1;
    },
    source: function(query, process) {
        jQuery.ajax({
            url: "../_help/getcustomer_detail.php",
            data: {
                query: query
            },
            dataType: "json",
            type: "POST",
            success: function(data) {
                process(data)
            }
        })
    },
    items: "all",
    afterSelect: function(item) {

        $("#cus_code").val(item.cus_nbr);
        $("#cus_name").val(item.cus_name1);
    }
});


</script>
</body>
<!-- END: Body-->

</html>