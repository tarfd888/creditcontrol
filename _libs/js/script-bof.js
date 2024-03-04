  $(document).ready(function() {
    //load_risk_data(); 
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
          load_risk_data();
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
          load_risk_data();
        }
      });
    } else {
      $('#multiple_files_add').val('');
      $('#error_multiple_files').html("<span class='text-danger'>" + error_images + "</span>");
      return false;
    }
  });

  $(document).on('click', '#delete', function(e) {
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
              //$('body').append('<div id="requestOverlay" class="request-overlay"></div>'); /*Create overlay on demand*/
              //$("#requestOverlay").show();/*Show overlay*/
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
              alert(result);
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
                  title: "ลบข้อมูลเรียบร้อยแล้ว",
                  showConfirmButton: false,
                  timer: 1500,
                  confirmButtonClass: "btn btn-primary",
                  buttonsStyling: false,
                  animation: false,
                });
                  load_risk_data();
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

  function load_risk_data() {
    var action = $("#content-box").find("#action").val();
    //alert(action);
    $.ajax({
      url: "../serverside/upload_risk_list.php",// json datasource
      type: "post",
      data: $('#frm_risk_add').serialize(),
    
      success: function(data) {
        //console.log(data);
        $('#risk_table').html(data);
      }
    });
  }

  $('#up_cust_code').typeahead({	
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
      $("#up_cust_code").val(item.cus_nbr+" "+item.cus_name1);
      $("#cr_cust_code").val(item.cus_nbr);
      //$('.showupload').show();
    }
   
  });
  $('#up_year').click(function(){
      $('.showupload').show();
  });
  $('#multi_risk_add').change(function() {
    var error_images = '';
    var form_data = new FormData();
    var files = $('#multi_risk_add')[0].files;
    if (files.length > 10) {
      error_images += 'You can not select more than 10 files';
    } else {
      for (var i = 0; i < files.length; i++) {
        var name = document.getElementById("multi_risk_add").files[i].name;
        var ext = name.split('.').pop().toLowerCase();
        if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'pdf', 'xls', 'xlsx', 'doc', 'docx']) == -1) {
          error_images += '<p>Invalid ' + i + ' File</p>';
        }
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("multi_risk_add").files[i]);
        var f = document.getElementById("multi_risk_add").files[i];
        var fsize = f.size || f.fileSize;
        if (fsize > 10000000) {
          error_images += '<p>' + i + ' File Size is over 10 Mb.</p>';
        } else {
          form_data.append("file[]", document.getElementById('multi_risk_add').files[i]);
          form_data.append("cr_cust_code", document.getElementById('cr_cust_code').value);
          form_data.append("up_year", document.getElementById('up_year').value);
          form_data.append("action", document.getElementById('action').value);
          var up_year = document.getElementById('up_year').value;
          if(up_year==""){
            error_images += '<p>'  + ' กรุณาระบุประจำปี.</p>';
          }
        }
      }
    }
    if (error_images == '') {
      $.ajax({
        url: "../serverside/upload_risk_post.php",
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
              load_risk_data();
        }
      });
    } else {
      $('#multi_risk_add').val('');
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
          load_risk_data();
        }
      });
    } else {
      $('#multiple_files_add').val('');
      $('#error_multiple_files').html("<span class='text-danger'>" + error_images + "</span>");
      return false;
    }
  });

  $(document).on('click', '#btnview', function(e) {
    let up_cust_code = document.getElementById("up_cust_code").value;
      formname = 'frm_risk_add';
      if(up_cust_code==""){
        Swal.fire({
          title: "Error!",
          html:
            "ไม่สามารถแสดงข้อมูลได้ เนื่องจากยังไม่ได้ระบุรหัสลูกค้า" +
            "\n" +
            "กรุณาตรวจสอบอีกครั้ง!",
          type: "error",
          confirmButtonClass: "btn btn-danger",
          buttonsStyling: false,
        });
      };
 
    $.ajax({
        type: 'POST',
        url: '../serverside/upload_risk_list.php',
        data: $('#frm_risk_add').serialize(),
        timeout: 10000,
        error: function(xhr, error) {
            showmsg('[' + xhr + '] ' + error);
        },
        success: function(result) {
        $('#risk_table').html(result);
        //console.log(result);
        //alert(result);
            var json = $.parseJSON(result);
            if (json.r == '0') {
                Swal.fire({
                    title: "Error!",
                    html: json.e,
                    type: "error",
                    confirmButtonClass: "btn btn-danger",
                    buttonsStyling: false
                });
            } else {
            location.reload(true);
            $(location).attr('href', '../crctrlupload/upload_riskmstr.php?q=' + json.nb)
            }
        },
        complete: function() {
            $("#requestOverlay").remove(); 
        }
    });
  });

  $(document).on('click', '#btnclose', function(e) {
    document.getElementById("up_cust_code").value = '';
    $('.showupload').hide();
    $("#risk_table tr").remove(); 
    var clear = document.getElementById("up_year");
    for(var i=0; i<clear.length; i++){
      clear.options[i].selected = false;
    }
    location.reload(true);
    $(location).attr('href', 'upload_riskmstr.php');

  });
  
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
