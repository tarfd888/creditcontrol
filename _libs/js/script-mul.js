
 $(document).ready(function() {
 
    load_image_data(); 

    // <!-- step 1 -- >
    $('#cus_tg_cust1').on('ifChanged', function(event){
      //clearRadioButtons(); 
      deselectListbox();
      if($(this).attr('id') == 'cus_tg_cust1') {
          $('.newbranch').show();
          $('.dis_step1').hide();
          $('.domestic').hide();
          $('.export').hide();    
          $('.dis_step1_1').hide();
          $('.select_cust_type').hide(); 

          $('.dom_term').show();  // term_mstr for domestic
          $('.exp_term').hide(); 
      }         
    });   
    
    // radio ลูกค้าต่างประเทศ (Export)
    $('#cus_tg_cust2').on('ifChanged', function(event){
      //clearRadioButtons(); 
      deselectListbox();
      if($(this).attr('id') == 'cus_tg_cust2') {
          $('.sel_group_aff').hide();   
          $('.newbranch').show();
          $('.dis_step1').hide();
          $('.domestic').hide();
          $('.export').hide();   
          $('.dis_step1_1').hide(); 

          $('.exp_term').show();  // term_mstr for export
          $('.dom_term').hide();
          $('.newbranch_input').hide();
      }
    });

    $('#cus_type_bus_dom').change(function() {
      $('.err_domestic').hide();
      var selectValue = $(this).val(); // เก็บค่าที่เลือก เป็นค่า value ที่อยู่ใน option ที่เลือก
      if(selectValue==8){
        $('.domestic_input').show();  // แสดงประเภทลูกค้าที่ขอแต่งตั้ง: กรณีระบุอื่น ๆ ในประเทศ
      }else {
        $('.domestic_input').hide();
      }
        $('.dis_step1_1').show() // แสดงชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า
    });

    $('#cus_type_bus_exp').change(function() {
      $('.err_export').hide();
      var selectValue = $(this).val(); // เก็บค่าที่เลือก เป็นค่า value ที่อยู่ใน option ที่เลือก
      if(selectValue==13){
        $('.export_input').show();  // แสดงประเภทลูกค้าที่ขอแต่งตั้ง: กรณีระบุอื่น ๆ ต่างประเทศ
      }else {
        $('.export_input').hide();
      }
        $('.dis_step1_1').show()  // แสดงชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า
    });

    $('#cus_tg_cust1').on('ifClicked', function(event){
      var cus_tg_cust1 = document.getElementById("cus_tg_cust1").value = 'dom';
      var cus_tg_cust2 = document.getElementById("cus_tg_cust2").value = '';
    });

    $('#cus_tg_cust2').on('ifClicked', function(event){
      var cus_tg_cust2 = document.getElementById("cus_tg_cust2").value = 'exp';
      var cus_tg_cust1 = document.getElementById("cus_tg_cust1").value = '';
    });
   

    $('.dis_step1_1').click(function(){
      $('.showNtxPage').show();
    });

    function clearRadio_mgr(){
      const ele = document.querySelectorAll('input[name="cr_mgr_status"]');
        for(var i=0;i<ele.length;i++){
          $('#cr_mgr_status'+[i+1]).iCheck('uncheck'); 
        } 
    }
    
    function clearRadioButtons(){
      const ele = document.querySelectorAll('input[name="cus_type_bus"]');
        for(var i=0;i<ele.length;i++){
          $('#cus_type_bus'+[i]).iCheck('uncheck'); 
        } 
    }

    function deselectListbox(){
      var clear = document.getElementById("cus_cust_type");
      for(var i=0; i<clear.length; i++){
        clear.options[i].selected = false;
      }
      $('.err_newbranch').show();
    }
    
    $(document).on('click', '#btnView,#btnEdit', function(e) {
      var book_no = $(this).data('book_no');
      var directions = $(this).data('directions');
    });
    
    function load_image_data() {
      var action = $("#content-box").find("#action").val();
      //alert(action);
      $.ajax({
        url: "../serverside/upload_img_list.php",// json datasource
        type: "post",
        data: $('#frm_cust_add').serialize(),
      
        success: function(data) {
          //console.log(data);
          $('.image_table_view').html(data);
        }
      });
    }

    var book_list = $('#book_table').DataTable({
      "ajax": {
        url: "../serverside/book_list.php?q=" +1,
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

    var book_table_chg = $('#book_table_chg').DataTable({
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

    $('#multiple_files_add').change(function() {
      var error_images = '';
      var form_data = new FormData();
      var files = $('#multiple_files_add')[0].files;
      if (files.length > 10) {
        error_images += 'You can not select more than 10 files';
      } else {
        for (var i = 0; i < files.length; i++) {
          var name = document.getElementById("multiple_files_add").files[i].name;
          var ext = name.split('.').pop().toLowerCase();
          if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'pdf', 'xls', 'xlsx', 'doc', 'docx']) == -1) {
            error_images += '<p>Invalid ' + i + ' File</p>';
          }
          var oFReader = new FileReader();
          oFReader.readAsDataURL(document.getElementById("multiple_files_add").files[i]);
          var f = document.getElementById("multiple_files_add").files[i];
          var fsize = f.size || f.fileSize;
          if (fsize > 10000000) {
            error_images += '<p>' + i + ' File Size is over 10 Mb.</p>';
          } else {
            form_data.append("file[]", document.getElementById('multiple_files_add').files[i]);
            form_data.append("temimagerandom", document.getElementById('temimagerandom').value);
            form_data.append("cus_app_nbr", document.getElementById('cus_app_nbr').value);
            form_data.append("action", document.getElementById('action').value);
          }
        }
      }
      if (error_images == '') {
        $.ajax({
          url: "../serverside/upload_img_cr_post.php",
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
        $('#multiple_files_add').val('');
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
                  $('#imageModal').modal('hide');
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
              $('#imageModal').modal('hide');
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
              //load_image_data();
              location.reload(true);
              $(location).attr('href', 'upd_cr_newcusmnt.php?q=' + json.nb + '&current_tab=' + json.pg1)
            }
          },
          
          complete: function() {
            $("#requestOverlay").remove(); 
          }
        });
    });

    $(document).on('click','#profileIcon22-tab1',function(e){
      $('.showBtnSave').show();
    }); 

    $(document).on('change','#profileIcon22-tab1',function(e){
      $('.showBtnSave').show();
    }); 

    $(document).on('click','#activeIcon22-tab1',function(e){
      $('.showBtnSave').hide();
    }); 
   
 });  

  $(document).ready(function() {
      let iscookie;
      let input0 = {};
      input0.cus_app_nbr = $("#search_app_nbr").val();   
      input0.action = $("#action").val();  
      input0.book_case = $("#book_case").val();  

      //alert(input0.cus_app_nbr+"--"+input0.action+"--"+input0.book_case);
      /* setcookie(input0, function(results) {
        iscookie = results;
      }); */
      $.ajax({
        url: "../serverside/cr_book_list.php",
        type: "POST",
        dataType: 'json',
        data: {
          param0: JSON.stringify(input0)
        },
        beforeSend: function() {
          $('.book_table_cr').DataTable().clear().destroy();
        },
        success: function(res) {
          console.log(res);
         
          if (res.success) {
            //$("#book_table_cr").dataTable().fnDestroy();
            table = $(".book_table_cr").dataTable({
              
              //"dom": 'tip',
              "aaData": res.data,
              "cache": false,
  
              "columnDefs": [{
                "className": "text-center",
                "targets": [0, 3, 4, 5, 6]
              },
              {
                "className": "dt-left",
                "targets": [1, 2]
              },
      
            ],

            "createdRow": function(row, data, dataIndex) {
              if (data['book_status'] == "1") {
                $(row).addClass('text-white bg- green');
              }
            },
            "columns": [
              { // Add row no. (Line 1,2,3,n)
                "data": "id",
                render: function(data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart+1 ;
                }
              },
              {
                "data": "book_dom"
              },
              {
                "data": "book_exp"
              },
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
              {
                "data": "book_status",
                render: function(data, type, row) {
                  var active =
                    '<i class="fa fa-check" title="You are not authorized to access."></i>';
                  var inactive =
                    '';
                  var status = (data == '1') ? active : inactive;
                  return status;
                }
              }, 
             /*  {
                "data": "book_status",
                render: function (data,type,row) {
                  if (data =='1') {
                    return '<input type="checkbox" checked>';
                  } else {
                    return '<input type="checkbox">';
                  }
                return data;
              } 
              }, */
              {
                "data": "book_status",
                "className": 'dt-body-center',
                render: function(data, type, row, meta) {
                  var show_checkbox = "";
                  if (data =='1') {
                    show_checkbox = show_checkbox +
                      '<label class="form-check form-check-inline  form-check-primary form-check-solid me-3 ml-3 is-valid">' +
                      '<input type="checkbox" class="form-check-input " name="chcheck[]" id="' +
                      row.book_no + '" value="' +
                      row.book_no + '" checked>' +
                      '</label>';
                  } 
                  else
                  {
                    show_checkbox = show_checkbox +
                      '<label class="form-check form-check-inline  form-check-primary form-check-solid me-3 ml-3 is-valid">' +
                      '<input type="checkbox" class="form-check-input " name="chcheck[]" id="' +
                      row.book_no + '" value="' +
                      row.book_no + '" >' +
                      '</label>';
                  } 
                  return show_checkbox;
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
          }
        },
        complete: function() {
          $(".ball-clip-rotate").fadeOut();
        },
        error: function(res) {
          console.log(res)
          alert('error');
        }
      });
  });

  // แผนกสินเชื่อบันทึกและแก้ไขข้อมูล
  $(document).on('click', '#btnsave_cr', function(e) {
    formname = 'frm_cust_add';  
    var numbers_arr = [];
  
    table.$('input[type="checkbox"]').each(function() {
      if (this.checked) {
        $('#frm_cust_add').append(
          $('<input>')
          .attr('type', 'hidden')
          .attr('name', 'apcheck[]')
          .val(this.value)
        );
      }
    });
   
    $.ajax({
      beforeSend: function () {
        $("body").append(
          '<div id="requestOverlay" class="request-overlay"></div>'
        );
        $("#requestOverlay").show();
      },
      type: "POST",
      url: "../serverside/c_newcust_post.php",
      data: $("#" + formname).serialize(),
      timeout: 100000,
      error: function (xhr, error) {
        showmsg("[" + xhr + "] " + error);
        table.$('input[name="apcheck[]"]', '#frm_cust_add').remove();
									table.$('input[type="checkbox"]').each(function() {
										table.$("input[type='checkbox']").prop(
											'checked', false);
									});
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
          $(location).attr("href", "../crcust/upd_cr_newcusmnt.php?q=" + json.nb + '&current_tab=' + json.pg);
          //$(location).attr("href", "../newcust/newcust_list.php?q=" + json.nb);
          table.$('input[name="apcheck[]"]', '#frm_cust_add').remove();
          table.$('input[type="checkbox"]').each(function() {
            table.$("input[type='checkbox']").prop(
                  'checked', false);
              });
        }
      },
      complete: function () {
        $("#requestOverlay").remove();
      },
    });
  });

  $(document).on('click', '#btnsave_chg', function(e) {
    formname = 'frm_cust_add';  
    var numbers_arr = [];
  
    table.$('input[type="checkbox"]').each(function() {
      if (this.checked) {
        $('#frm_cust_add').append(
          $('<input>')
          .attr('type', 'hidden')
          .attr('name', 'apcheck[]')
          .val(this.value)
        );
      }
    });
   
    $.ajax({
      beforeSend: function () {
        $("body").append(
          '<div id="requestOverlay" class="request-overlay"></div>'
        );
        $("#requestOverlay").show();
      },
      type: "POST",
      url: "../serverside/c_newcust_post.php",
      data: $("#" + formname).serialize(),
      timeout: 100000,
      error: function (xhr, error) {
        showmsg("[" + xhr + "] " + error);
        table.$('input[name="apcheck[]"]', '#frm_cust_add').remove();
									table.$('input[type="checkbox"]').each(function() {
										table.$("input[type='checkbox']").prop(
											'checked', false);
									});
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
          $(location).attr("href", "../crcust/upd_cr_chgcusmnt.php?q=" + json.nb + '&current_tab=' + json.pg);
          //$(location).attr("href", "../newcust/newcust_list.php?q=" + json.nb);
          table.$('input[name="apcheck[]"]', '#frm_cust_add').remove();
          table.$('input[type="checkbox"]').each(function() {
            table.$("input[type='checkbox']").prop(
                  'checked', false);
              });
        }
      },
      complete: function () {
        $("#requestOverlay").remove();
      },
    });
  });
  
  // Checkbox checked
  function checkcheckbox() {
    // Total checkboxes
    var length = $('.nbr_check').length;
    // Total checked checkboxes
    var totalchecked = 0;
    $('.nbr_check').each(function() {
      if ($(this).is(':checked')) {
        totalchecked += 1;
        }
    });
    // Checked unchecked checkbox
    if (totalchecked == length) {
      $("#checkall").prop('checked', true);
      } else {
      $('#checkall').prop('checked', false);
    }
  }

  // แผนกสินเชื่อส่งเอกสารกลับไปให้ sale แก้ไข
  $('#cr_status1,#cr_status2').on('ifChanged', function(event){
    //clearRadioButtons(); 
    //deselectListbox();
    if($(this).attr('id') == 'cr_status1') {
      var cr_date_of_reg = document.getElementById("cr_date_of_reg").value ;
      var cr_reg_capital = document.getElementById("cr_reg_capital").value ;
      if(cr_date_of_reg!="" && cr_reg_capital!=""){
        $('#btnsubmit_cr').show();
      }
    
      $('#btnrevise_cr').hide();
      $('.showremark').hide();
      $('.disshowremark').show();
    }     
    if($(this).attr('id') == 'cr_status2') {
      $('#btnrevise_cr').show();
      $('.showremark').show();
      $('.disshowremark').hide();
      $('#btnsubmit_cr').hide();
    }       
  });   

  $('#cr_status1').on('ifClicked', function(event){
    $('#btnsubmit_cr').show();
  }); 

  // upd_cr_chgcusmnt.php
  $('#cr_status1_chg,#cr_status2_chg').on('ifChanged', function(event){
    //clearRadioButtons(); 
    //deselectListbox();
    if($(this).attr('id') == 'cr_status1_chg') {
      var cr_due_date = document.getElementById("cr_due_date").value ;
      if(cr_due_date!=""){
        $('#btnsubmit_cr').show();
      }
    
      $('#btnrevise_cr').hide();
      $('.showremark').hide();
      $('.disshowremark').show();
    }     
    if($(this).attr('id') == 'cr_status2_chg') {
      $('#btnrevise_cr').show();
      $('.showremark').show();
      $('.disshowremark').hide();
      $('#btnsubmit_cr').hide();
    }       
  });   

  // ผจก. ส่งเอกสารกลับไปให้ผู้มีอำนาจอนุมัติ
  $('#cr_mgr_status1').on('ifClicked', function(event){
     /*  var cr_mgr_remark = document.getElementById("cr_mgr_remark").value ;
      if(cr_mgr_remark==""){
        Istitle = "กรุณาระบุความคิดเห็น Finance & Credit Manager !!!! " 
        Swal.fire({
          type: "warning",
          html:  Istitle,
          buttonsStyling: false,
          confirmButtonText: "รับทราบ",
          //timer: 50000,
          customClass: {
            confirmButton: "btn btn-primary"
          },
          allowOutsideClick: false
        });
         //clearRadio_mgr(); 
      }
      else  */
        $('#btnsubmit_mgr').show();
   
  });   

  $('#cr_mgr_status2').on('ifClicked', function(event){
      $('#btnsubmit_mgr').hide();
  });  

  $('#cusd_tg_beg_date').datetimepicker({
    format: 'DD/MM/YYYY',
  })
  $('#cusd_tg_end_date').datetimepicker({
    format: 'DD/MM/YYYY',
  })
  $('#cr_sap_code_date').datetimepicker({
    format: 'DD/MM/YYYY',
  })
  $('#cr_cus_chk_date').datetimepicker({
    format: 'DD/MM/YYYY',
  })
  $('#cr_date_of_reg').datetimepicker({
    format: 'DD/MM/YYYY',
  })
  $('#cr_due_date').datetimepicker({
    format: 'DD/MM/YYYY',
  })

  // submit ส่งไปหาแผนกสินเชื่อ
  function dispostform(chkform,Is_stepcode,Is_app_nbr,Ismethod) {
    //alert(chkform+"--"+Is_stepcode+"--"+Is_app_nbr+"--"+Ismethod)
    switch(chkform){
      case "frm_revise" :
        Istitle = "คุณต้องการส่งกลับไปแก้ข้อมูลเอกสาร  <br>เลขที่   " + Is_app_nbr + " ใช่หรือไม่ !!!! ";
        break;
      case "frm_cr_submit_mgr" :
        Istitle = "คุณต้องการข้อมูลเอกสาร  <br>เลขที่   " + Is_app_nbr + " ไปยังผู้จัดการแผนกสินเชื่อ ใช่หรือไม่ !!!! " 
        break;
       case "frm_cr_submit_app" :
        Istitle = "คุณต้องการข้อมูลเอกสาร  <br>เลขที่   " + Is_app_nbr + " ขอพิจารณาอนุมัติ ใช่หรือไม่ !!!! " 
        break;  
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
          $('#frm_cust_add').find('#action').val(Ismethod);
          $('#frm_cust_add').find('#cr_step_code').val(Is_stepcode);
          approvepost(chkform);

          // $('#frm_cust_add').find('#action').val('cr_edit_cr1');
          // $('#frm_cust_add').find('#cr_step_code').val(Is_stepcode);
          // updatedata(Is_app_nbr);
        });
      },
      allowOutsideClick: false
    });
  }

  function approvepost(isform) {
    switch(isform){
      case "frm_revise" :
        $('#frm_cust_add').find('#action').val('cr_revise');
        var url = '../serverside/c_newcust_post.php';
        var title = "ได้ส่งเอกสารฉบับนี้กลับไปเรียบร้อยแล้ว";
        break;
      case "frm_cr_submit_mgr" :
        var url = '../serverside/c_newcust_post.php';
        var title = "ได้ส่งเอกสารฉบับนี้เรียบร้อยแล้ว";
        break;
       case "frm_cr_submit_app" :
        //var url = '../serverside/cr_sendmail_approve.php';
        var url = '../serverside/c_newcustpost_approve.php';
        var title = "ได้ส่งเอกสารฉบับนี้ขออนุมัติเรียบร้อยแล้ว";
        break;  
    }
    //alert(url);
    //var url = '../serverside/c_newcust_post.php';
    var formObj = $('#frm_cust_add')[0];
    var formData = new FormData(formObj);
    $.ajax({
      type: 'POST',
      url: url,
      //data: $('#frm_cust_add').serialize(),
      data: formData,
      timeout: 100000,
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
        //alert(result);
        console.log(result);
        var json = $.parseJSON(result);
        if (json.r == '0') {
          Swal.fire({
            type: "error",
            html: json.e,
            type: "error",
            confirmButtonClass: "btn btn-danger",
            buttonsStyling: false,
          });
        } else {
          Swal.fire({
            title: title,
            //html: json.e,
            type: "success",
            buttonsStyling: false,
            confirmButtonText: "รับทราบ",
            timer: 50000,
            customClass: {
              confirmButton: "btn btn-primary"
            },
            allowOutsideClick: false
          }).then((result) => {
            if (result.isConfirmed) {
              setInterval(function() {
                //window.location.reload();
                $('#frm_cust_add').find('#action').val('cr_edit_cr1');
                $('#frm_cust_add').find('#cr_step_code').val(Is_stepcode);
                updatedata(Is_app_nbr);
              }, 10000);
            }
            location.reload(true);
            $(location).attr("href", "../newcust/newcust_list.php?q=" + json.nb);   
          });
        }
      },
      complete: function() {
        $("#requestOverlay").remove(); /*Remove overlay*/
      }
    });
  }

  function updatedata(Is_app_nbr){
    formname = 'frm_cust_add';  
    var numbers_arr = [];
    table.$('input[type="checkbox"]').each(function() {
      if (this.checked) {
        $('#frm_cust_add').append(
          $('<input>')
          .attr('type', 'hidden')
          .attr('name', 'apcheck[]')
          .val(this.value)
        );
      }
    });
   
    $.ajax({
      beforeSend: function () {
        $("body").append(
          '<div id="requestOverlay" class="request-overlay"></div>'
        );
        $("#requestOverlay").show();
      },
      type: "POST",
      url: "../serverside/c_newcust_post.php",
      data: $("#" + formname).serialize(),
      timeout: 100000,
      error: function (xhr, error) {
        showmsg("[" + xhr + "] " + error);
        table.$('input[name="apcheck[]"]', '#frm_cust_add').remove();
									table.$('input[type="checkbox"]').each(function() {
										table.$("input[type='checkbox']").prop(
											'checked', false);
									});
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
          //location.reload(true);
          //$(location).attr("href", "../crcust/upd_cr_newcusmnt.php?q=" + json.nb + '&current_tab=' + json.pg);
          clearloadresult();
          $(location).attr("href", "../newcust/newcust_list.php?q=" + json.nb);   
          table.$('input[name="apcheck[]"]', '#frm_cust_add').remove();
          table.$('input[type="checkbox"]').each(function() {
                table.$("input[type='checkbox']").prop('checked', false);
          });
        }
      },
      complete: function () {
        $("#requestOverlay").remove();
      },
    });
  }

  // remind email 
  function remindmail(chkform,Is_stepcode,Is_app_nbr,Ismethod) {
    //alert(chkform+"--"+Is_stepcode+"--"+Is_app_nbr+"--"+Ismethod)
    Istitle = "คุณต้องการข้อมูลเอกสาร  <br>เลขที่   " + Is_app_nbr + " ขอพิจารณาอนุมัติอีกครั้ง ใช่หรือไม่ !!!! " 
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
          $('#frm_cust_add').find('#action').val(Ismethod);
          $('#frm_cust_add').find('#cr_step_code').val(Is_stepcode);
          remindpost(Is_app_nbr);
        });
      },
      allowOutsideClick: false
    });
  }

  function remindpost(Is_app_nbr) {
    var url = '../serverside/c_remindpost_approve.php';
    var title = "ได้ส่งเอกสารฉบับนี้ขออนุมัติเรียบร้อยแล้ว";
    var formObj = $('#frm_cust_add')[0];
    var formData = new FormData(formObj);
    $.ajax({
      type: 'POST',
      url: url,
      //data: $('#frm_cust_add').serialize(),
      data: formData,
      timeout: 100000,
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
        //alert(result);
        //console.log(result);
        var json = $.parseJSON(result);
        if (json.r == '0') {
          Swal.fire({
            type: "error",
            html: json.e,
            type: "error",
            confirmButtonClass: "btn btn-danger",
            buttonsStyling: false,
          });
        } else {
          Swal.fire({
            title: title,
            //html: json.e,
            type: "success",
            buttonsStyling: false,
            confirmButtonText: "รับทราบ",
            timer: 50000,
            customClass: {
              confirmButton: "btn btn-primary"
            },
            allowOutsideClick: false
          }).then((result) => {
            if (result.isConfirmed) {
              setInterval(function() {
                 window.location.reload();
              }, 10000);
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
  /// เมื่อกรอกตัวเลขจำนวนเงิน ใน textbox ให้มันใส่คอมม่า
  function format(input){
    var num = input.value.replace(/\,/g,'');
    if(!isNaN(num)){
      if(num.indexOf('.') > -1){ 
        num = num.split('.');
        num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'');
        if(num[1].length > 2){ 
          alert('You may only enter two decimals!');
          num[1] = num[1].substring(0,num[1].length-1);
        }  input.value = num[0]+'.'+num[1];        
      } else{ input.value = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'') };
    }
    else{ alert('You may enter only numbers in this field!');
      input.value = input.value.substring(0,input.value.length-1);
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

  $(document).ready(function () {
    //call the action method and get the data.
    var table = $("#datatables").DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'My button',
                action: function () {
                  
                    var rows = table.rows( '.selected' ).indexes();
                    var data = table.rows( rows ).data();
  
                    for(var i = 0; i < data.length;i++){
                      
                      console.log(data[i][0]);
                      
                    }


                }
            }
        ],
        //data: dataSet,
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records",
        },
        "columns": [
            {
                orderable: false,
                className: '',
                defaultContent: '',
                data: null,
                title: ''
            },
            {
                data: 1,
                title: "Access Right Name"
            },
            {
                "className": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": ''
            },
        ],
        'columnDefs': [
            {
                'targets': 0,
                'checkboxes': {
                    'selectRow': true
                }
            }
        ],
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
    });

    $('#datatables tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });
  
  //selecting checkboxes from listA
  /* for(var i = 0; i < listA.length; i++){
    
    //Get the indicies based on values from listA
    var indicies = table.rows(listA[i]).indexes();
    
    //How would I 'Check' the indicies
    table.rows( indicies ).select();
    
  } */

  })
  