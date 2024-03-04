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
set_time_limit(0);
$curdate = date('Ymd');
$params = array();
$headtitle =" - รายงานสรุปรายการแต่งตั้งลูกใหม่และสาขา";
	
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
                                <font color="40ADF4">รายงานสรุปรายการแต่งตั้งลูกใหม่และสาขา</font>
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

                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">เลขที่เอกสาร</label>
                                                <div class="input-group input-group-sm">
                                                    <input id="cus_app_nbr" name="cus_app_nbr"
                                                        value="<?php echo $cus_app_nbr ?>"
                                                        class="form-control input-sm border-info font-small-2"
                                                        type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">วันที่ทำรายการ-สิ้นสุด</label>
                                                <div class="input-group input-group-sm">
                                                    <input id="cus_date" name="daterange"
                                                        value="<?php echo $cus_date ?>"
                                                        class="form-control input-sm border-info font-small-2"
                                                        type="text">
                                                        <input type="hidden" id="start">
                                                        <input type="hidden" id="end">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">ประเภทการขออนุมัติ</label>
                                                <div class="input-group input-group-sm">
													<select data-placeholder="เลือกประเภทการขออนุมัติ"
														class="form-control input-sm border-info font-small-2 select2" id="cus_cond_cust" name="cus_cond_cust">
														<option value="" selected>--- เลือกประเภทการขออนุมัติ ---</option>
														<option value="c1">แต่งตั้งลูกค้าใหม่</option>
														<option value="c2">แต่งตั้งร้านสาขา</option>
														<option value="c3">เปลี่ยนแปลงชื่อ</option>
														<option value="c4">เปลี่ยนแปลงที่อยู่จดทะเบียน</option>
														<option value="c5">เปลี่ยนแปลงชื่อและที่อยู่</option>
														<option value="c6">ยกเลิกลูกค้า</option>
													</select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">อำนาจดำเนินการ</label>
												<select data-placeholder="เลือกประเภทการขออนุมัติ"
													class="form-control input-sm border-info font-small-2 select2" id="cusd_op_app" name="cusd_op_app">
													<option value="" selected>--- เลือกอำนาจดำเนินการ ---</option>
													<option value="ผผ.">ผผ. อนุมัติ</option>
													<option value="ผส.">ผส. อนุมัติ</option>
													<option value="กจก.">กจก. อนุมัติ</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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
                                        
                                        <div class="col-md-3">
                                            <div class="form-group ">
                                                <label class="font-weight-bold">สถานะเอกสาร</label>
                                                <select data-placeholder="เลือกประเภทการขออนุมัติ"
													class="form-control input-sm border-info font-small-2 select2" id="cr_sta_complete" name="cr_sta_complete">
													<option value="" selected>--- เลือกสถานะเอกสาร ---</option>
													<option value="C">Completed</option>
													<option value="I">Incomplete</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group ">
                                                <label class="font-weight-bold">สถานะการอนุมัติ</label>
                                                <select data-placeholder="Select a doc type ..."
                                                    class="form-control input-sm border-info font-small-2 select2"
                                                    id="cusstep_name_en" name="cusstep_name_en">
                                                    <option value="" selected>--- เลือก ---</option>
                                                    <?php
																$sql_doc = "SELECT DISTINCT cusstep_code, cusstep_name_en from cusstep_mstr where cusstep_action=1 order by cusstep_name_en";
																$result_doc = sqlsrv_query($conn, $sql_doc);
																while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
																?>
                                                    <option value="<?php echo $r_doc['cusstep_name_en']; ?>" <?php if ($cusstep_name_en == $r_doc['cusstep_name_en']) {
																	echo "selected";
																} ?>>
                                                        <?php echo $r_doc['cusstep_name_en']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        
										<div class="form-actions right pt-2">
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
										 <table id="table-data" class="table table-striped table-sm table-hover table-bordered compact nowrap"
                        					style="width:100%; font-size:0.89em;">	
                                            <thead class="text-center">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>วันที่</th>
                                                    <th>เอกสารเลขที่</th>
                                                    <th>รหัสลูกค้า</th>
                                                    <th>ชื่อลูกค้า</th>
                                                    <th>เลขประจำตัวผู้เสียภาษี</th>
                                                    <th>จังหวัด</th>
                                                    <th>ประเทศ</th>
                                                    <th>ประเภทการขออนุมัติ</th>
                                                    <th>อนก.</th>
                                                    <th>สถานะการอนุมัติ.</th>
                                                    <th>สถานะเอกสาร</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<? include("../crctrlmain/menu_footer.php"); ?>
<div class="to-top">
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
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
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            $("#start").val(start.format('DD/MM/YYYY'));
            $("#end").val(end.format('DD/MM/YYYY'));
            console.log("A new date selection was made: " + start.format('DD/MM/YYYY') +
                ' to ' + end.format('DD/MM/YYYY'));
        });
    });
});

function setcookie(input0, callback) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '../serverside/newcust_setcookie.php',
        data: {
            param0: JSON.stringify(input0)
        },
        async: false,
        timeout: 50000,
        success: function(result) {
            var json = $.parseJSON(result);
            return callback(json.ret);
        }
    });
}
$(document).on("click", "#but_search", function() {
    var iscookie;
    var $cus_name = "";
    let input0 = {};
    input0.cus_app_nbr = $("#cus_app_nbr").val();
    input0.cus_code = $("#cus_code").val();
    input0.cus_date = $("#cus_date").val();
    input0.start = $("#start").val();
    input0.end = $("#end").val();
    input0.cus_cond_cust = $("#cus_cond_cust").val();
    input0.cusd_op_app = $("#cusd_op_app").val();
    input0.cusstep_name_en = $("#cusstep_name_en").val();
    input0.cr_sta_complete = $("#cr_sta_complete").val(); 
    //alert(input0.cr_sta_complete );
    // alert(input0.end);
    setcookie(input0,function(results) {iscookie = results;})
    $.ajax({
        url: "../serverside/newcusexport_list.php",
        type: "POST",
        dataType: 'json',
        data: {
            param0: JSON.stringify(input0)
        },
        beforeSend: function() {
            $('body').append(
            '<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
            $("#requestOverlay").show(); /*Show overlay*/
        },
        success: function(res) {
            if (res.success) {
                $("#table-data").dataTable().fnDestroy();
                $("#table-data").dataTable({
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
                            "data": "cus_date"
                        },
                        {
                            "data": "cus_app_nbr"
                        },
                        {
                            "data": "cus_code"
                        },
                        {
                            "data": "cus_reg_nme"
                        },
                        {
                            "data": "cus_tax_id"
                        },
                        {
                            "data": "cus_prov"
                        },
                        {
                            "data": "cus_country"
                        },
                        {
                            "data": "cus_cond_cust_name"
                        },
                        {
                            "data": "cusd_op_app"
                        },
                      
                        {
                            "data": "cus_step_name"
                        },
                        {
                            "data": "cr_sta_name"
                        },
                    ],
                    "columnDefs": [{
                            "className": "text-center",
                            "targets": [0, 1, 2, 3, 6, 7, 8, 9, 10, 11]
                        },
                       /*  {
                            "className": "text-right",
                            "targets": [6]
                        },  */
                       /*  {
                            "width": 5,
                            "targets": 0
                        }, */
                       /*  {
                            "width": 5,
                            "targets": 1
                        }, */
                      /*   {
                            "width": 10,
                            "targets": 2
                        }, */

                    ],
                    "searching": false,
                    "ordering": false,
                    "stateSave": true,
                    "pageLength": 10,
                    "pagingType": "simple_numbers",
                });
                $("#table-data").fadeIn();
            }
        },
        complete: function() {
            $(".loading").fadeOut();
        },
        error: function(res) {
            console.log(res)
            alert('error');
        }
    });
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

$('#cus_app_nbr').typeahead({

	displayText: function(item) {
		return item.cus_app_nbr + "  |  " + item.cus_date;
	},

	source: function(query, process) {
		jQuery.ajax({
			url: "../_help/getdocnum_detail.php",
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
		$("#cus_app_nbr").val(item.cus_app_nbr);
	}

});

function loadresult() {
    document.all.result.innerHTML =
        "<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>";
}

function showdata() {
    var errorflag = false;
    var errortxt = "";
    document.getElementById("msghead").innerHTML = "พบข้อผิดผลาดในการบันทึกข้อมูล";
    if (errorflag) {
        document.getElementById("msgbody").innerHTML = "<font color=red>" + errortxt + "</font>";
        $("#myModal").modal("show");
    } else {
        loadresult()
        document.frm.submit();
    }
}

/// เมื่อกรอกตัวเลขจำนวนเงิน ใน textbox ให้มันใส่คอมม่า
function format(input) {
    var num = input.value.replace(/\,/g, '');
    if (!isNaN(num)) {
        if (num.indexOf('.') > -1) {
            num = num.split('.');
            num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1,').split('')
                .reverse().join('').replace(/^[\,]/, '');
            if (num[1].length > 2) {
                alert('You may only enter two decimals!');
                num[1] = num[1].substring(0, num[1].length - 1);
            }
            input.value = num[0] + '.' + num[1];
        } else {
            input.value = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1,').split('')
                .reverse().join('').replace(/^[\,]/, '')
        };
    } else {
        alert('You may enter only numbers in this field!');
        input.value = input.value.substring(0, input.value.length - 1);
    }
}

function gotopage(mypage) {
    loadresult()
    document.frm.pg.value = mypage;
    document.frm.submit();
}

function loadresult() {
    $('#div_result').html("<center><img id='progress' src='../_images/loading0.gif' width=80 height=80><center>");
}

function clearloadresult() {
    $('#div_result').html("");
}

function showmsg(msg) {
    $("#modal-body").html(msg);
    $("#myModal").modal("show");
}
</script>
</body>
<!-- END: Body-->

</html>