  $(document).ready(function() {
    load_image_data(); 
    //$('.dom_term').show(); 
    // <!-- step 1 -- >
    $('#chg_cus_tg_cust1').on('ifChanged', function(event){
      deselectListbox1();
      if($(this).attr('id') == 'chg_cus_tg_cust1') {
     
          $('.newbranch_ch').show();
          $('.dis_ch_step1, .dis_beg_date, .dis_reg_nme, .dis_info_addr').hide();
      }         
    });   
    
    $('#chg_cus_tg_cust2').on('ifChanged', function(event){
      deselectListbox1();
      if($(this).attr('id') == 'chg_cus_tg_cust2') {
          $('.newbranch_ch').show();
          $('.dis_ch_step1, .dis_beg_date, .dis_reg_nme, dis_info_addr').hide();
      }
    });
    ////////////////////
    $('#cus_cust_type').change(function() {
      var cus_tg_cust1 = document.getElementById("cus_tg_cust1").value;
      var cus_tg_cust2 = document.getElementById("cus_tg_cust2").value;
      var selectValue = $(this).val(); // เก็บค่าที่เลือก เป็นค่า value ที่อยู่ใน option ที่เลือก
      if(selectValue==9){
        $('.newbranch_input').show();  // แสดงประเภทลูกค้าที่ขอแต่งตั้ง: กรณีระบุอื่น ๆ ในประเทศ
      }else {
        $('.newbranch_input').hide();
      }

      if(selectValue==4 || selectValue==8){
        $('#cusd_op_app').val("ผส.");
        $('.dis_apprv').hide(); 
      }else {
        $('#cusd_op_app').val("กจก.");
        $('.dis_apprv').show(); // โชว์ ผู้พิจารณา 2 (ผฝ.), (CMO), (CFO), กจก.
      }

      $('.dis_step1').show();
      $('.listTypeofBus').show();
      $('.export').hide();   
      $('.err_newbranch').hide();
     
      if(cus_tg_cust1=="dom") {
        $('.domestic').show(); 
      }else {
        $('.domestic').hide();
      } 
      if(cus_tg_cust2=="exp") {
        $('.export').show();
      }else {
        $('.export').hide();
      }  
    });
   
    $('#ch_form_cus').change(function() {
      var ch_form_cus = document.getElementById("ch_form_cus").value;	
      $('.dis_ch_step1').show();
      $('.err_newbranch_ch').hide();
      $('.dis_reg_nme').hide();  // ไม่โชว์ชื่อจดทะเบียน (ใหม่)
      $('.dis_reg_addr').hide(); // ไม่โชว์ที่อยู่จดทะเบียน (ใหม่)
      $('.dis_new_addr').hide(); // ไม่โชว์ที่อยู่จดทะเบียน (Registered Address)
      $('.dis_beg_date').hide(); // ไม่โชว์วันที่เริ่มใช้
      $('.dis_info_addr').hide(); // ไม่โชว์ ตำบล, อำเภอ, จังหวัด, ไปรษณีย์, ประเทศ
      $('#cust_code').val("");
      $('#cus_old_addr').val("");
      $('#cus_new_addr').val("");
      $('#cusd_op_app').val("");
      $('#cus_mas_addr').val("");
      $('#cus_effective_date').val("");
        if(ch_form_cus != "c4"){ 
          $('.dis_apprv').show(); // โชว์ ผู้พิจารณา 2 (ผฝ.), (CMO), (CFO), กจก.
          $('#cusd_op_app').val("กจก.");
          document.getElementById('label_rev').innerHTML = 'ผู้พิจารณา 1 ';
        } 
        else 
        {
          $('.dis_apprv').hide();
          $('#cusd_op_app').val("ผผ.");
          document.getElementById('label_rev').innerHTML = 'ผู้อนุมัติ ';
        }
    });

    $('#cust_code').typeahead({	
      displayText: function(item) {
        return item.cus_nbr+" "+item.cus_name1;
      }, 
      
      source: function (query, process) {
        jQuery.ajax({
          url: "../_help/getcustomer_detail.php",
          data: {query:query},
          dataType: "json",
          type: "POST",
          success: function (data) {
            process(data)
          }
        })
      },				
      
      items : "all",
      afterSelect: function(item) {
        $("#cust_code").val(item.cus_nbr+" "+item.cus_name1);
        $("#cus_reg_nme").val(item.cus_name1);
        $("#cus_district").val(item.cus_street2);
        $("#cus_amphur").val(item.cus_district); // อำเภอ / เขต
        $("#cus_prov").val(item.cus_city); // จังหวัด
        $("#cus_country").val(item.cus_country); //ประเทศ
        $("#cus_zip").val(item.cus_zipcode);  // รหัสไปรษณีย์
        $("#cus_tax_id").val(item.cus_tax_nbr3); // เลขประจำตัวผู้เสียภาษี
        $("#cus_branch").val(item.cus_tax_nbr4); // สาขาที่ (Branch No.)
        $("#cus_country").val("Thailand");
        $("#cus_code_mas").val(item.cus_nbr);
        $("#cus_mas_addr").val(item.cus_street+" "+item.cus_street2+" "+item.cus_street3+" "+item.cus_street4+" "+item.cus_street5+" "+item.cus_district+" "+item.cus_city+" "+item.cus_zipcode+" เลขประจำตัวผู้เสียภาษี (Tax ID No.) "+item.cus_tax_nbr3+" สาขาที่ (Branch No.) "+item.cus_tax_nbr4+" Account Group "+item.cus_acc_group);
      }
     
    });

    $('#cust_code').change(function() {
      var ch_form_cus = document.getElementById("ch_form_cus").value;
      $('.dis_beg_date').show();
      switch (ch_form_cus){
        case "c3":                   // เปลี่ยนแปลงชื่อ
          $('.dis_reg_nme').show();  // โชว์ชื่อจดทะเบียน (ใหม่)
          $('.dis_new_addr').show(); // โชว์ที่อยู่จดทะเบียน (Registered Address)
          $('.showNtxPage').show();
          document.getElementById("cusd_op_app").value = "กจก.";

          $('input[type=text]').removeAttr('readonly');  // Disable readonly to text box
          break;
        case "c4":                   // เปลี่ยนแปลงที่อยู่จดทะเบียน
          $('.dis_reg_addr').show(); // โชว์ที่อยู่จดทะเบียน (ใหม่)
          $('.dis_new_addr').show();
          $('.dis_info_addr').show(); // โชว์ ตำบล, อำเภอ, จังหวัด, ไปรษณีย์, ประเทศ
          $('.showNtxPage').show();
          document.getElementById("cusd_op_app").value = "ผส.";

          //document.getElementById('cus_reg_addr').removeAttribute('readonly');
          $('input[type=text]').removeAttr('readonly');  // Disable readonly to text box
          break;
        case "c5":                   // เปลี่ยนแปลงชื่อและที่อยู่
          $('.dis_reg_nme').show();
          $('.dis_reg_addr').show(); // โชว์ที่อยู่จดทะเบียน (ใหม่)
          $('.dis_new_addr').show();
          $('.dis_info_addr').show(); // โชว์ ตำบล, อำเภอ, จังหวัด, ไปรษณีย์, ประเทศ
          $('.showNtxPage').show();
          document.getElementById("cusd_op_app").value = "กจก.";
          $('input[type=text]').removeAttr('readonly');  // Disable readonly to text box
          break;
      }
      
    });

    // <--step 2 -->
    var book_case = $("#content-box").find("#book_case").val();
    var book_list = $('#book_table').DataTable({
      "ajax": {
        url: "../serverside/book_list.php?q=" +2,
        type: "post",

      },
      "language": {
        "decimal": ",",
        "thousands": ".",
        //"emptyTable": '<a  href="#div_add_qtm_project" data-toggle="modal" style="font-size:1.2rem; line-height:3rem;"><i class="fa fa-plus"></i> เพิ่ม Quotation สำหรับโปรเจคนี้</a>'
      },
      "columnDefs": [{
          "className": "text-center",
          "targets": [2, 3]
        },
        {
          "className": "dt-left",
          "targets": [0, 1]
        },

      ],
      "columns": [
        // {
        //   "data": "book_no"
        // },
        {
          "data": "book_dom"
        },
        {
          "data": "book_exp"
        },
       /*  {
          "data": "book_agent"
        },
        {
          "data": "book_aff"
        }, */
        {
           "data": "book_agent",
           render: function(data, type, row) {
             var active =
               '<i class="fa fa-check" title="You are not authorized to access."></i>';
             var inactive =
               '';
             var status = (data != '') ? active : inactive;
             return status;
           }
         }, 
         {
          "data": "book_aff",
          render: function(data, type, row) {
            var active =
              '<i class="fa fa-check" title="You are not authorized to access."></i>';
            var inactive =
              '';
            var status = (data != '') ? active : inactive;
            return status;
          }
        }, 
      ],
      "searching": false, // ไม่แสดงช่อง search
      "paging": false, // ไม่แสดงการแบ่งหน้า
      "ordering": false,
      "pageLength": 10,
      "scrollX": false,
      "pagingType": "simple_numbers",
      "info": false,
    });

    function load_image_data() {
      var action = $("#content-box").find("#action").val();
      //alert(action);
      $.ajax({
        url: "../serverside/upload_img_list.php",// json datasource
				type: "post",
        data: $('#frm_cust_add').serialize(),
      
        success: function(data) {
          console.log(data);
          $('#image_table').html(data);
        }
      });
    }

    $('#multiple_files').change(function() {
      var error_images = '';
      var form_data = new FormData();
      var files = $('#multiple_files')[0].files;
      if (files.length > 10) {
        error_images += 'You can not select more than 10 files';
      } else {
        for (var i = 0; i < files.length; i++) {
          var name = document.getElementById("multiple_files").files[i].name;
          var ext = name.split('.').pop().toLowerCase();
          if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'pdf', 'xls', 'xlsx', 'doc', 'docx']) == -1) {
            error_images += '<p>Invalid ' + i + ' File</p>';
          }
          var oFReader = new FileReader();
          oFReader.readAsDataURL(document.getElementById("multiple_files").files[i]);
          var f = document.getElementById("multiple_files").files[i];
          var fsize = f.size || f.fileSize;
          if (fsize > 10000000) {
            error_images += '<p>' + i + ' File Size is over 10 Mb.</p>';
          } else {
            form_data.append("file[]", document.getElementById('multiple_files').files[i]);
            form_data.append("temimagerandom", document.getElementById('temimagerandom').value);
            form_data.append("action", document.getElementById('action').value);
          }
        }
      }
      if (error_images == '') {
        $.ajax({
          url: "../serverside/upload_img_post.php",
          method: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function() {
            $('#error_multiple_files').html('<label class="text-primary">Uploading...</label>');
          },
          success: function(data) {
            $('#error_multiple_files').html('<label class="text-success">Uploaded</label>');
            load_image_data();
          }
        });
      } else {
        $('#multiple_files').val('');
        $('#error_multiple_files').html("<span class='text-danger'>" + error_images + "</span>");
        return false;
      }
    });

    $('#multiple_files_edit').change(function() {
      var error_images = '';
      var form_data = new FormData();
      var files = $('#multiple_files_edit')[0].files;
      if (files.length > 10) {
        error_images += 'You can not select more than 10 files';
      } else {
        for (var i = 0; i < files.length; i++) {
          var name = document.getElementById("multiple_files_edit").files[i].name;
          var ext = name.split('.').pop().toLowerCase();
          if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'pdf', 'xls', 'xlsx', 'doc', 'docx']) == -1) {
            error_images += '<p>Invalid ' + i + ' File</p>';
          }
          var oFReader = new FileReader();
          oFReader.readAsDataURL(document.getElementById("multiple_files_edit").files[i]);
          var f = document.getElementById("multiple_files_edit").files[i];
          var fsize = f.size || f.fileSize;
          if (fsize > 10000000) {
            error_images += '<p>' + i + ' File Size is over 10 Mb.</p>';
          } else {
            form_data.append("file[]", document.getElementById('multiple_files_edit').files[i]);
            form_data.append("temimagerandom", document.getElementById('temimagerandom').value);
            form_data.append("cus_app_nbr", document.getElementById('cus_app_nbr').value);
            form_data.append("action", document.getElementById('action').value);
          }
        }
      }
      if (error_images == '') {
        $.ajax({
          url: "../serverside/upload_img_post.php",
          method: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function() {
            $('#error_multiple_files').html('<label class="text-primary">Uploading...</label>');
          },
          success: function(data) {
            $('#error_multiple_files').html('<label class="text-success">Uploaded</label>');
            load_image_data();
          }
        });
      } else {
        $('#multiple_files_edit').val('');
        $('#error_multiple_files').html("<span class='text-danger'>" + error_images + "</span>");
        return false;
      }
    });

    $(document).on('click', '#delete', function(e) {
      var image_id = $(this).attr("id1");
      var image_name = $(this).data("image_name");
    //alert(image_id + '--' + image_name);
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
            document.frm_del_img.image_id.value = image_id;
            document.frm_del_img.image_name.value = image_name;
            $.ajax({
              beforeSend: function() {
                //$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
                //$("#requestOverlay").show();/*Show overlay*/
              },
              type: 'POST',
              url: '../serverside/upload_img_post.php',
              data: $('#frm_del_img').serialize(),
              timeout: 50000,
              error: function(xhr, error) {
                showmsg('[' + xhr + '] ' + error);
              },
              success: function(result) {
                //console.log(result);
                //alert(result);
                var json = $.parseJSON(result);
                if (json.r == '0') {
                  clearloadresult();
                  Swal.fire({
                    title: "Error!",
                    html: json.e,
                    type: "error",
                    confirmButtonClass: "btn btn-danger",
                    buttonsStyling: false
                  });
                  } else {
                  clearloadresult();
                  Swal.fire({
                    type: "success",
                    title: "Delete Successful",
                    showConfirmButton: false,
                    timer: 1500,
                    confirmButtonClass: "btn btn-primary",
                    buttonsStyling: false,
                    animation: false,
                  });
                  load_image_data();
                  //location.reload(true);
                  //$('#image_table').DataTable().ajax.reload(null, false); // call from external function
                  //$(location).attr('href', 'newcusmnt.php?id='+json.nb)
                }
              },
              complete: function() {
                $("#requestOverlay").remove(); /*Remove overlay*/
              }
            });
          });
        },
        allowOutsideClick: false
      });
      e.preventDefault();
    });

    $(document).on("click", ".open-EditImgModal", function() {
      var image_id = $(this).data("id1");
      var image_app_nbr = $(this).data("image_app_nbr");
      var image_name = $(this).data("image_name");
      var image_desc = $(this).data("image_desc");
      //alert(image_id +'--'+ image_name); 
      $("#imageModal .modal-body #image_id").val(image_id);
      $("#imageModal .modal-body #image_app_nbr").val(image_app_nbr);
      $("#imageModal .modal-body #image_name").val(image_name);
      $("#imageModal .modal-body #image_desc").val(image_desc);
    });

  
    $(document).on("click", "#imagepostform", function() {
        let formid = 'frm_image_eidt';
        $.ajax({
          beforeSend: function() {
            $('body').append('<div id="requestOverlay" class="request-overlay"></div>'); 
            $("#requestOverlay").show();
          },
          type: 'POST',
          url: '../serverside/upload_img_post.php',
          data: $('#' + formid).serialize(),
          timeout: 50000,
          error: function(xhr, error) {
            showmsg('[' + xhr + '] ' + error);
          },
          success: function(result) {
            //alert(result);
            var json = $.parseJSON(result);
            if (json.r == '0') {
              clearloadresult();
              Swal.fire({
                title: "Error!",
                html: json.e,
                type: "error",
                confirmButtonClass: "btn btn-danger",
                buttonsStyling: false
              });
              } else {
              clearloadresult();
              Swal.fire({
                type: "success",
                title: "Successful",
                showConfirmButton: false,
                timer: 1500,
                confirmButtonClass: "btn btn-primary",
                buttonsStyling: false,
                animation: false,
              });
              location.reload(true);
              $(location).attr('href', 'upd_newcusmnt.php?q=' + json.nb + '&current_tab=' + json.pg)

            }
          },
          
          complete: function() {
            $("#requestOverlay").remove(); 
          }
        });
    });

    $('.typeahead').typeahead({
  
      displayText: function(item) {
        var disp_col1 = this.$element.attr('data-disp_col1');
        var disp_col2 = this.$element.attr('data-disp_col2');
        var disp_col3 = this.$element.attr('data-disp_col3');
        var disp_col4 = this.$element.attr('data-disp_col4');
        //alert(item[disp_col1] + ' ' + item[disp_col2]+ ' ' + item[disp_col3]+ ' ' + item[disp_col4]);
        return item[disp_col1] + ' ' + item[disp_col2]+ ' ' + item[disp_col3]+ ' ' + item[disp_col4];
      },
      source: function(query, process) {
        var typeahead_src = this.$element.attr('data-typeahead_src')
        $.ajax({
          url: typeahead_src,
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
        var ret_field_01 = this.$element.attr('data-ret_field_01')
        var ret_value_01 = this.$element.attr('data-ret_value_01')
        var ret_type_01 = this.$element.attr('data-ret_type_01')
        var ret_field_02 = this.$element.attr('data-ret_field_02')
        var ret_value_02 = this.$element.attr('data-ret_value_02')
        var ret_type_02 = this.$element.attr('data-ret_type_02')
        var ret_field_03 = this.$element.attr('data-ret_field_03')
        var ret_value_03 = this.$element.attr('data-ret_value_03')
        var ret_type_03 = this.$element.attr('data-ret_type_03')
        var ret_field_04 = this.$element.attr('data-ret_field_04')
        var ret_value_04 = this.$element.attr('data-ret_value_04')
        var ret_type_04 = this.$element.attr('data-ret_type_04')
        if (ret_type_01 == "val") {
          $('#' + ret_field_01).val(item[ret_value_01]);
        } else {
          $('#' + ret_field_01).html(item[ret_value_01]);
        }
        if (ret_type_02 == "html") {
          $('#' + ret_field_02).val(item[ret_value_02]);
        } else {
          $('#' + ret_field_02).html(item[ret_value_02]);
        }
        if (ret_type_03 == "html") {
          $('#' + ret_field_03).val(item[ret_value_03]);
        } else {
          $('#' + ret_field_03).html(item[ret_value_03]);
        }
        if (ret_type_04 == "html") {
          $('#' + ret_field_04).val(item[ret_value_04]);
          callInfoMgr();
        } else {
          $('#' + ret_field_04).html(item[ret_value_04]);
        }
      }
     
    });

    $('.myFunction').click(function(){
      var mgr = document.getElementById("cusd_os_sale_mgr").value;
      //alert(mgr);
      $("#cusd_os_sale_mgr_code").val(mgr);
       let result = mgr.substring(0, 4);
       if(result == "0650"){
         callInfoMgr();
       } else {
         $("#cusd_os_sale_mgr").val("");
         $("#cusd_mgr_email").val("");			
         $("#cusd_mgr_pos").val("");	
       }
    });

    function callInfoMgr() {	
      var errorflag = false;
      var errortxt = "";
      var mgr = document.getElementById("cusd_os_sale_mgr").value;
      //alert(mgr);
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {								
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            const myObj = JSON.parse(this.responseText);
            result_text =  myObj.result_text;
            if (result_text != "OK") {
              if (errortxt != "") {errortxt = errortxt + "<br>";}
              errorflag = true;					
            }				
            if (errorflag) {
              document.getElementById("modal-body").innerHTML = "<font color=red>" + "พบข้อผิดผลาดในการบันทึกข้อมูล" + "</font>";
              $("#myModal").modal("show");
            } 
            manager_code = document.getElementById("cusd_os_sale_mgr_code").innerHTML = myObj.manager_code;
            manager = document.getElementById("cusd_os_sale_mgr").innerHTML = myObj.manager_name;
            email = document.getElementById("cusd_mgr_email").innerHTML = myObj.emp_email_bus;
            mgr_pos = document.getElementById("cusd_mgr_pos").innerHTML = myObj.emp_th_org_name;

            $("#cusd_os_sale_mgr_code").val(manager_code);
            $("#cusd_os_sale_mgr").val(manager);
            $("#cusd_mgr_email").val(email);			
            $("#cusd_mgr_pos").val(mgr_pos);				
        }			

      }
      xhttp.open("POST", "../_chk/chkusermanager.php",false);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.setRequestHeader("Cache-Control", "no-cache, must-revalidate");
      xhttp.setRequestHeader("Pragma", "no-cache");
      xhttp.setRequestHeader("Expires", "Sat, 26 Jul 1997 05:00:00 GMT");				
      xhttp.send("mgrnumber="+mgr);	
    }


    $("#frm_cust_add, #frm_cust_edit").on("click", "#buthelp", function() {
      var input0 = {};
      var data = {};
      var id_field_code = $(this).data('id_field_code');
      var id_field_name = $(this).data('id_field_name');
      var id_field_code1 = $(this).data('id_field_code1');
      var id_field_mgr = $(this).data('id_field_mgr');
      var id_field_name_type = $(this).data('id_field_name_type')
      var modal_class = $(this).data('modal_class');
      var modal_title = $(this).data('modal_title');
      var modal_src = $(this).data('modal_src');
      var modal_col_name = $(this).data('modal_col_name');
      var modal_col_data1 = $(this).data('modal_col_data1');
      var modal_col_data2 = $(this).data('modal_col_data2');
      var modal_col_data3 = $(this).data('modal_col_data3');
      var modal_col_data4 = $(this).data('modal_col_data4');
      var modal_col_data3_vis = $(this).data('modal_col_data3_vis');
      var modal_col_data4_vis = $(this).data('modal_col_data4_vis');
      var modal_ret_data1 = $(this).data('modal_ret_data1');
      var modal_ret_data2 = $(this).data('modal_ret_data2');
      var modal_ret_data3 = $(this).data('modal_ret_data3');
      var modal_ret_data4 = $(this).data('modal_ret_data4');
      var modal_page_size = $(this).data('modal_page_size');
      var modal_page_type = $(this).data('modal_page_type');
      if (modal_page_size === undefined || modal_page_size == "") {
        modal_page_size = 10;
      }
      if (modal_page_type === undefined || modal_page_type == "") {
        modal_page_type = "simple";
      }
      if (id_field_name_type === undefined || id_field_name_type == "") {
        id_field_name_type = "html";
      }
      //Column Setting
      var cols = [{
        "data": modal_col_data1
      }, 
      {
        "data": modal_col_data2
      },
      {
        "data": modal_col_data3
      },
      {
        "data": modal_col_data4
      }];
   
      input0.field_code = $("#" + id_field_code).val();
      input0.field_code1 = $("#" + id_field_code1).val();
  
      $.ajax({
        url: modal_src,
        type: "POST",
        dataType: 'json',
        data: {
          param0: JSON.stringify(input0)
        },
        beforeSend: function() {
          $(".loading").fadeIn();
          $("#div_help").find("#div_help_size").attr("class", modal_class);
          $("#div_help").find("#help_title").html(modal_title);
          $("#div_help").find("#head0").html(modal_col_name);
          $("#div_help").modal({
            backdrop: 'static',
            keyboard: false
          });
          $('#table-help').DataTable().clear().destroy();
        },
        success: function(res) {
          console.log(res);
          //if (res.success) {
          $("#table-help").dataTable().fnDestroy();
          $("#table-help").dataTable({
            "oSearch": {
              "sSearch": input0.field_code
            },
            "dom": '<lf<t>ip>',
            "deferRender": true,
            //"aaData" : res.data,
            "aaData": res,
            "cache": false,
            "columns": cols,
            "columnDefs": [{
                "className": "dt-left",
                "targets": [0, 1]
              },
              {
                "width": "50px",
                "targets": 0
              },
              {
                "width": "100px",
                "targets": 1
              },
              {
                "render": function(data, type, row) {
                  return '<a href="javascript:void(0)" id="btn-ret-value"' +
                    '" data-send_code="' + row[modal_ret_data1] +
                    '" data-send_name="' + row[modal_ret_data2] +
                    '" data-send_code1="' + row[modal_ret_data3] +
                    '" data-send_code2="' + row[modal_ret_data4] +
                    '" data-rec_code_id="' + id_field_code +
                    '" data-rec_name_id="' + id_field_name +
                    '" data-rec_code1_id="' + id_field_code1 +
                    '" data-rec_code2_id="' + id_field_mgr +
                    '" data-rec_name_type="' + id_field_name_type + '">' + data + '</a>';
                },
                "targets": 0
              },
            ],
            "pagingType": modal_page_type,
            "pageLength": modal_page_size,
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bAutoWidth": false,
            "ordering": true,
  
          });
          $("#content-help").fadeIn();
          //}
        },
        complete: function() {
          $(".loading").fadeOut();
        },
        error: function(res) {
          alert('error');
        }
      });
    });
  
    $(document).on("click", "#btn-ret-value", function(e) {
      e.preventDefault();
      var code = $(this).data('send_code');
      var name = $(this).data('send_name');
      var code1 = $(this).data('send_code1');
      var code2 = $(this).data('send_code2');
  
      var id_field_code = $(this).data('rec_code_id');
      var id_field_name = $(this).data('rec_name_id');
      var id_field_code1 = $(this).data('rec_code1_id');
      var id_field_code2 = $(this).data('rec_code2_id');
      
      $('#but_help_close').trigger("click");
      $('#' + id_field_code).val(code);
      $('#' + id_field_name).val(name);  
      $('#' + id_field_code1).val(code1);   
      $('#' + id_field_code2).val(code2);  
      var mgr = code2;
      var result = mgr.substring(0, 4);
        if(result == "0650"){
          callInfoMgr();
        } else {
          $("#cusd_os_sale_mgr").val("");
          $("#cusd_mgr_email").val("");			
          $("#cusd_mgr_pos").val("");	
        }
      
    });

    $('#cus_district,#cus_amphur,#cus_prov').typeahead({
      displayText: function(item) {
        return item.district + " >> อ. " + item.amphoe + "  >> จ. " + item.province + ">> รหัสไปรษณีย์ " + item
          .zipcode
      },
      emptyTemplate: function(item) {
        if (item.length > 0) {
          return 'No results found for "' + item + '"';
        }
      },
      source: function(query, response) {
        jQuery.ajax({
          url: "../_libs/thailandjson/raw_database.json", //even.php",
          data: {
            query: query
          },
          dataType: "json",
          type: "POST",
          success: function(data) {
            response(data)
          }
  
        })
      },
  
      afterSelect: function(item) {
        $("#cus_prov").val(item.province);
        $("#cus_amphur").val(item.amphoe);
        $("#cus_district").val(item.district);
        $("#cus_zip").val(item.zipcode);
        $("#cus_country").val("Thailand");
      }
       
    });

    $(document).on('click','#activeIcon3-tab1',function(e){
      $('.showBtnSave').show();
    }); 

    $(document).on('click','#activeIcon1-tab1, #activeIcon2-tab1',function(e){
      $('.showBtnSave').hide();
    }); 

    $(document).on('click', '#btnsave_chg', function(e) {
      let ch_form_cus = document.getElementById("ch_form_cus").value;
      //let cus_tg_cust = document.getElementById("cus_tg_cust1").value; 
      formname = 'frm_cust_add';

      if(ch_form_cus==""){
        Swal.fire({
          title: "Error!",
          html:
            "ไม่สามารถบันทึกข้อมูลได้ เนื่องจากยังไม่ได้ระบุชื่อประเภทการเปลี่ยนแปลงลูกค้า:" +
            "\n" +
            "กรุณาตรวจสอบอีกครั้ง!",
          type: "error",
          confirmButtonClass: "btn btn-danger",
          buttonsStyling: false,
        });
      }; 
      $.ajax({
        beforeSend: function () {
          $("body").append(
            '<div id="requestOverlay" class="request-overlay"></div>'
          );
          $("#requestOverlay").show();
        },
        type: "POST",
        url: "../serverside/n_chgcust_post.php",
        data: $("#" + formname).serialize(),
        //data: formData,
        timeout: 100000,
        error: function (xhr, error) {
          showmsg("[" + xhr + "] " + error);
        },
        success: function (result) {
          //alert(result);
          var json = $.parseJSON(result);
          //consolealert(json.r);
          if (json.r == "0") {
            clearloadresult();
            Swal.fire({
              title: "Error!",
              html: json.e,
              type: "error",
              confirmButtonClass: "btn btn-danger",
              buttonsStyling: false,
            });
          } else {
            clearloadresult();
            Swal.fire({
              type: "success",
              title: "บันทึกข้อมูลเรียบร้อยแล้ว",
              showConfirmButton: false,
              timer: 50000,
              confirmButtonClass: "btn btn-primary",
              buttonsStyling: false,
              animation: false,
            });
            location.reload(true);
            $(location).attr("href", "../newcust/upd_chgcusmnt.php?q=" + json.nb);
            //$(location).attr("href", "../newcust/newcust_list.php?q=" + json.nb);
          }
        },
        complete: function () {
          $("#requestOverlay").remove();
        },
      });
    });
  });  

  $('#cus_effective_date').datetimepicker({
    format: 'DD/MM/YYYY',
  });

  // submit ส่งไปหาแผนกสินเชื่อ
  function salesubmit(chkform,step_code,Is_app_nbr) {
    if(chkform=="frm_reviewer"){
      Istitle = "คุณต้องการส่งเอกสาร  <br>เลขที่   " + Is_app_nbr + " ไปยังผู้พิจารณาใช่หรือไม่ !!!! " 
      Iscomplete = "ส่งเอกสารฉบับนี้ถึงผู้พิจารณาเรียบร้อยแล้ว"
    }
    if(chkform=="frm_revise_cr"){
      Istitle = "คุณต้องการส่งเอกสารฉบับแก้ไข  <br>เลขที่   " + Is_app_nbr + " ไปยังแผนกสินเชื่อใช่หรือไม่ !!!! " 
      Iscomplete = "ส่งเอกสารแก้ไขฉบับนี้ถึงแผนกสินเชื่อเรียบร้อยแล้ว"
    }
    if(chkform=="frm_revise_rev"){
      Istitle = "คุณต้องการส่งเอกสารฉบับแก้ไข  <br>เลขที่   " + Is_app_nbr + " ไปยังผู้พิจารณาใช่หรือไม่ !!!! " 
      Iscomplete = "ส่งเอกสารแก้ไขฉบับนี้ถึงผู้พิจารณาเรียบร้อยแล้ว"
    }
    if(chkform=="frm_submit"){
      Istitle = "คุณต้องการส่งเอกสาร  <br>เลขที่   " + Is_app_nbr + " ไปยังแผนกสินเชื่อใช่หรือไม่ !!!! " 
      Iscomplete = "ส่งเอกสารแก้ไขฉบับนี้ถึงแผนกสินเชื่อเรียบร้อยแล้ว"
    }
    Swal.fire({
      type: "warning",
      html:  Istitle,
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Send it!",
      confirmButtonClass: "btn btn-primary",
      cancelButtonClass: "btn btn-danger ml-1",
      buttonsStyling: false,
      showLoaderOnConfirm: true,
      preConfirm: function() {
        return new Promise(function(resolve) {
          updatedata(step_code);
          apprvposteach(step_code);
        });
      },
      allowOutsideClick: false
    });
  }

  function apprvposteach(step_code) {
    var url = '../serverside/salesubmit_post.php?step_code=' +step_code;
    //Process Data
    var formObj = $('#frm_cust_add')[0];
    var formData = new FormData(formObj);
    $.ajax({
      type: 'POST',
      url: url,
      //data: $('#frm_cust_add').serialize(),
      data: formData,
      timeout: 50000,
      cache: false,
      contentType: false,
      processData: false,
      error: function(xhr, error) {
        alert('[' + xhr + '] ' + error);
      },
      beforeSend: function() {
        //$(".loading0").fadeIn();
      },
      success: function(result) {
        console.log(result);
        var json = $.parseJSON(result);
        if (json.r == '0') {
          Swal.fire({
            type: "Warning!",
            html: json.e,
            icon: "warning",
            customClass: {
              confirmButton: "btn btn-warning"
            },
            //timer: 1500,
            buttonsStyling: false,
            allowOutsideClick: false
          });
        } else {
          Swal.fire({
            title:  Iscomplete, //"ส่งเอกสารฉบับนี้ถึงแผนกสินเชื่อเรียบร้อยแล้ว",
            //html: json.e,
            type: "success",
            buttonsStyling: false,
            confirmButtonText: "รับทราบ",
            //timer: 50000,
            customClass: {
              confirmButton: "btn btn-primary"
            },
            allowOutsideClick: false
          }).then((result) => {
            if (result.isConfirmed) {
              setInterval(function() {
                window.location.reload();
              }, 1000);
            }
            location.reload(true);
            $(location).attr("href", "../newcust/newcust_list.php?q=" + json.nb);
          });
        }
      },
      complete: function() {
        //$(".loading0").fadeOut();
      }
    });
  }

  function updatedata(step_code){
    document.getElementById("old_step_code").value = step_code;
    formname = 'frm_cust_add';
    $.ajax({
      beforeSend: function () {
        $("body").append(
          '<div id="requestOverlay" class="request-overlay"></div>'
        );
        $("#requestOverlay").show();
      },
      type: "POST",
      url: '../serverside/n_chgcust_post.php?step_code=' +step_code  ,
      data: $("#" + formname).serialize(),
      timeout: 50000,
      error: function (xhr, error) {
        showmsg("[" + xhr + "] " + error);
      },
      success: function (result) {
        //alert(result);
        var json = $.parseJSON(result);
        //consolealert(json.r);
        if (json.r == "0") {
          clearloadresult();
          Swal.fire({
            type: "error",
            html: json.e,
            type: "error",
            confirmButtonClass: "btn btn-danger",
            buttonsStyling: false,
          });
        } else {
          clearloadresult();
         /*  Swal.fire({
            title: "ส่งเอกสารฉบับนี้ถึงแผนกสินเชื่อเรียบร้อยแล้ว",
            html: json.e,
            type: "success",
            buttonsStyling: false,
            confirmButtonText: "รับทราบ",
            //timer: 50000,
            customClass: {
              confirmButton: "btn btn-primary"
            },
            allowOutsideClick: false
          }).then((result) => {
            if (result.isConfirmed) {
              setInterval(function() {
                window.location.reload();
              }, 1000);
            }
          }); */
          //location.reload(true);
          //$(location).attr("href", "../newcust/upd_newcusmnt.php?q=" + json.nb);
        }
      },
      complete: function () {
        $("#requestOverlay").remove();
      },
    });
  }
  
  $(document).on('click', '#btnshowtext', function(e) {
    let rem_revise = document.getElementById("rem_revise").value;
    Swal.fire({
      title: "หมายเหตุจากแผนกสินเชื่อ",
      html:  rem_revise , 
      type: "info",
      confirmButtonClass: "btn btn-info",
      buttonsStyling: false,
    });

  });

  function deselectListbox1(){
    var clear = document.getElementById("ch_form_cus");
    for(var i=0; i<clear.length; i++){
      clear.options[i].selected = false;
    }
    $('.err_newbranch').show();
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
