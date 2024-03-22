  $(document).ready(function() {
    load_image_data(); 
    $('.dom_term').show(); 

    // <!-- step 1 -- >
    $('#cus_tg_cust1').on('ifChanged', function(event){
      clearRadioButtons(); 
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
          $('#cus_country').val('Thailand')
      }         
    });   
    
    // radio ลูกค้าต่างประเทศ (Export)
    $('#cus_tg_cust2').on('ifChanged', function(event){
      clearRadioButtons(); 
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
          $('#cus_country').val('')
      }
    });

    // radio ลูกค้าในเครือ SCG
   /*  $('#cus_tg_cust3').on('ifChanged', function(event){
      clearRadioButtons() 
      if($(this).attr('id') == 'cus_tg_cust3') {
        let form_cus = document.getElementById("form_cus").value;
        if(form_cus=="c2") {
          $('.newbranch').hide();
          $('.sel_group_aff').show();  // แสดงกลุ่มลูกค้าในเครือ domestic , export
          $('.dis_step1').hide();
          $('.dis_step1_1').hide();
          $('.domestic').hide();
          $('.export').hide();  
        }
       
        $('#cus_tg_cust_aff1').iCheck('uncheck');  // เคลียร์ค่า radio box domestic
        $('#cus_tg_cust_aff2').iCheck('uncheck');  // เคลียร์ค่า radio box export
      }   
    }); */

    // radio  ราชการ/รัฐวิสาหกิจ
   /*  $('#cus_tg_cust4').on('ifChanged', function(event){
      clearRadioButtons(); 
      deselectListbox();
      if($(this).attr('id') == 'cus_tg_cust4') {
        let form_cus = document.getElementById("form_cus").value;
        $('.sel_group_aff').hide(); 
        if(form_cus=="c2") {
          $('.newbranch').show();
        }else {
          $('.dis_step1').show();
          $('.domestic').show();
          $('.export').hide();  
          //$('.sel_group_aff').hide(); 
        }   
      }
    }); */

    // radio ลูกค้าในเครือ SCG ==> ลูกค้าในประเทศ (Domestic)
    /* $('#cus_tg_cust_aff1').on('ifChanged', function(event){
    clearRadioButtons(); 
    deselectListbox();
    if($(this).attr('id') == 'cus_tg_cust_aff1') {
        let form_cus = document.getElementById("form_cus").value;
        if(form_cus=="c2") {
          $('.newbranch').show();
        }else {  
          $('.sel_group_aff').show();    
          $('.dis_step1').show();
          $('.domestic').show();
          $('.export').hide();   
          //clearRadioButtons()
        }
      }
     
    }); */

    // radio ลูกค้าในเครือ SCG ==> ลูกค้าต่างประเทศ (Export)
   /*  $('#cus_tg_cust_aff2').on('ifChanged', function(event){
    clearRadioButtons();
    deselectListbox();
    if($(this).attr('id') == 'cus_tg_cust_aff2') {
        let form_cus = document.getElementById("form_cus").value;
        if(form_cus=="c2") {
          $('.newbranch').show();
        }else {  
          $('.sel_group_aff').show(); 
          $('.dis_step1').show();
          $('.domestic').hide();
          $('.export').show();    
          $('.dom_oth').hide(); 
        }
      }
    }); */

    $('#cusd_tg_beg_date').datetimepicker({
      format: 'DD/MM/YYYY',
    })
    $('#cusd_tg_end_date').datetimepicker({
      format: 'DD/MM/YYYY',
    })

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
          document.getElementById('label_rev').innerHTML = 'ผู้อนุมัติ ';
        }else {
          $('#cusd_op_app').val("กจก.");
          $('.dis_apprv').show(); // โชว์ ผู้พิจารณา 2 (ผฝ.), (CMO), (CFO), กจก.
          document.getElementById('label_rev').innerHTML = 'ผู้พิจารณา 1 ';
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

    $('#cus_cr_limit1').click(function(){
      var cus_cr_limit1 = document.getElementById("cus_cr_limit1").value;

      if(cus_cr_limit1 == 0){document.getElementById("cus_cr_limit1").value ="";}
      
    });

    $('#cus_cr_limit2').click(function(){
      var cus_cr_limit2 = document.getElementById("cus_cr_limit2").value;

      if(cus_cr_limit2 == 0){document.getElementById("cus_cr_limit2").value ="";}
      
    });

    $('.dis_step1_1').click(function(){
      $('.showNtxPage').show();

    });

    $('#cusd_os_sale_mgr').click(function(){
      var mgr_sale = document.getElementById("cusd_os_sale_mgr").value;
    });

    /* $('#profileIcon22-tab1').click(function(){
      $('.showNtxPage').hide();
    });   */

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

    // แสดง div ชื่อเจ้าของ / ผู้จัดการที่ติดต่อสั่งซื้อสินค้า
    /* $('#cus_type_bus1,#cus_type_bus2,#cus_type_bus3,#cus_type_bus4,#cus_type_bus5,#cus_type_bus6,#expcus_type_bus6').on('ifChanged', function(event){
      
      if($(this).attr('id') == 'cus_type_bus1') {$('.dis_step1_1').show(); $('.dom_oth').hide(); $('.exp_oth').hide();}    

      if($(this).attr('id') == 'cus_type_bus2') {$('.dis_step1_1').show(); $('.dom_oth').hide(); $('.exp_oth').hide();}       
     
      if($(this).attr('id') == 'cus_type_bus3') {$('.dis_step1_1').show(); $('.dom_oth').hide(); $('.exp_oth').hide();}   

      if($(this).attr('id') == 'cus_type_bus4') {$('.dis_step1_1').show(); $('.dom_oth').hide(); $('.exp_oth').hide();}   
     
      if($(this).attr('id') == 'cus_type_bus5') {$('.dis_step1_1').show(); $('.dom_oth').hide(); $('.exp_oth').hide();}   

      if($(this).attr('id') == 'cus_type_bus6') {$('.dis_step1_1').show(); $('.dom_oth').show(); $('.exp_oth').hide();}   

      if($(this).attr('id') == 'expcus_type_bus6') {$('.dis_step1_1').show(); $('.exp_oth').show(); $('.dom_oth').hide(); $('.exp_oth').show();}   
      
    }); */

    var n = 1;
    $('#btn-add-contact').click(function() {
      if(n>=2){exit};
      n++;    
      
      $('#dynamic_contact').append('<tr id="row' + n +
        '"><td><input type="text" name="cus_contact_nme[]" placeholder="" class="form-control input-sm font-small-2 always-show-maxlength" maxlength="50"></td>'
        +
        '<td><input type="text" name="cus_contact_pos[]" placeholder="" class="form-control input-sm font-small-2 always-show-maxlength" maxlength="50"></td>'
        +
        '<td><button type="button" name="btn-del-contact" id="' + n +
        '" class="btn btn-danger btn-sm btn-del-contact"><i class="ft-x"></i></button></td></tr>');
    });
    $(document).on('click', '.btn-del-contact', function() {
      var button_id = $(this).attr("id");
      $('#row' + button_id + '').remove();
      n = n - 1;
    });

    ////////////////////////////////
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
   
    $('#cus_country').typeahead({	
      displayText: function(item) {
        return item.country_desc;
      }, 
      
      source: function (query, process) {
        jQuery.ajax({
          url: "../_help/getcountry_detail.php",
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
        $("#cus_country").val(item.country_desc);
      }
     
    });

    $("#frm_cust_add").on("click", "#buthelp", function() {
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
     /*  if (modal_col_data3 !== undefined && modal_col_data3 != "") {
        cols.push({
          "data": modal_col_data3,
          "visible": modal_col_data3_vis
        });
      } */
     /*  if (modal_col_data4 !== undefined && modal_col_data4 != "") {
        cols.push({
          "data": modal_col_data4,
          "visible": modal_col_data4_vis
        });
      } */
      //
      input0.field_code = $("#" + id_field_code).val();
      input0.field_code1 = $("#" + id_field_code1).val();
      //input0.login_plant = "<?php echo $login_plant;?>";
  
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
      //alert('manager ='+mgr + 'company ='+result)
       //ns18012024 unlock code 0650
        //if(result == "0650"){
          callInfoMgr();
        //} else {
        //  $("#cusd_os_sale_mgr").val("");
        //  $("#cusd_mgr_email").val("");			
        //  $("#cusd_mgr_pos").val("");	
        //}
      
    });
    //TYPE AHEAD
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
    
    // <!-- step 2 -- >
    // radio ชำระทุกวันตาม Due --> ไม่แสดง textbox
    $('#cus_cond_term1').on('ifChanged', function(event){
      if($(this).attr('id') == 'cus_cond_term1') {
        $('.cus_type_bus').hide();  
        $('.dis_step2').show();     
        $('.cus_cond_term_txt').hide();    
        $('.showNtxPage').show();  
      }
    });

    // radio มีเงื่อนไขการวางบิลหรือชำระเงินพิเศษ โปรดระบุ --> แสดง textbox
    $('#cus_cond_term2').on('ifChanged', function(event){
      if($(this).attr('id') == 'cus_cond_term2') {
        $('.cus_cond_term_txt').show();  
        $('.dis_step2').show();   
        $('.showNtxPage').show();         
      }
    });

    // <!-- step 3 -- >
    var i = 1;
    $('#btn-add-obj').click(function() {
      if(i>=3){exit};
      i++;    
      
      $('#dynamic_obj').append('<tr id="row' + i +
        '"><td><input type="text" name="cusd_obj[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td>'
        +
        '<td><button type="button" name="btn-del-obj" id="' + i +
        '" class="btn btn-danger btn-sm btn-del-obj"><i class="ft-x"></i></button></td></tr>');
    });
    $(document).on('click', '.btn-del-obj', function() {
      var button_id = $(this).attr("id");
      $('#row' + button_id + '').remove();
      i = i - 1;
    });

    // เคสหน้าจอ edit
    // $('#btn-add-obj1').click(function() {
    //   //var i = document.getElementById("objArrayCount").value;
    //   if(i>=3){exit};
    //   i++;    
    //   $('#dynamic_obj').append('<tr id="row' + i +
    //     '"><td><input type="text" name="cusd_obj[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td></tr>');
        
    // });

    var b = 1;
    $('#btn-add-prop').click(function() {  
      if(b>=3){exit}; 
      b++;     
      $('#dynamic_prop').append('<tr id="rowprop' + b +
        '"><td><input type="text" name="cusd_cust_prop[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td>'
        +
        '<td><button type="button" name="btn-del-prop" id="' + b +
        '" class="btn btn-danger btn-sm btn-del-prop"><i class="ft-x"></i></button></td></tr>');
    });
    $(document).on('click', '.btn-del-prop', function() {
      var button_id = $(this).attr("id");
      $('#rowprop' + button_id + '').remove();
      b = b - 1;
    });

    // เคสหน้าจอ edit
    // $('#btn-add-prop1').click(function() {  
    //   //var b = document.getElementById("projArrayCount").value;
    //   alert(b);
    //   if(b>=3){exit}; 
    //   b++;     
    //   $('#dynamic_prop').append('<tr id="rowprop' + b +
    //     '"><td><input type="text" name="cusd_cust_prop[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td></tr>');
    // }); 

    var c = 1;
    $('#btn-add-aff').click(function() {
      if(c>=3){exit}; 
      c++;
      $('#dynamic_aff').append('<tr id="rowaff' + c +
        '"><td><input type="text" name="cusd_aff[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td>'
        +
        '<td><button type="button" name="btn-del-aff" id="' + c +
        '" class="btn btn-danger btn-sm btn-del-aff"><i class="ft-x"></i></button></td></tr>');
    });
    $(document).on('click', '.btn-del-aff', function() {
      var button_id = $(this).attr("id");
      $('#rowaff' + button_id + '').remove();
      c = c - 1;
    });
    
    // เคสหน้าจอ edit
    // $('#btn-add-aff1').click(function() {
    //   var c = document.getElementById("affArrayCount").value;
    //   if(c>=3){exit}; 
    //   c++;
    //   $('#dynamic_aff').append('<tr id="rowaff' + c +
    //     '"><td><input type="text" name="cusd_aff[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td></tr>');
    // }); 

    var d = 1;
    $('#btn-add-dealer').click(function() {
      if(d>=3){exit}; 
      d++;
      $('#dynamic_dealer').append('<tr id="rowdealer' + d +
        '"><td><input type="text" name="cusd_dealer_nme[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td>'
        +
        '<td><input type="text" name="cusd_dealer_avg_val[]" placeholder="" class="form-control input-sm font-small-2 name_list" style="color:blue;text-align:right" onkeyup="format(this)"></td>'
        +
        '<td><button type="button" name="btn-del-dealer" id="' + d +
        '" class="btn btn-danger btn-sm btn-del-dealer"><i class="ft-x"></i></button></td></tr>');
    });
    $(document).on('click', '.btn-del-dealer', function() {
      var button_id = $(this).attr("id");
      $('#rowdealer' + button_id + '').remove();
      d = d - 1;
    });
    
    // เคสหน้าจอ edit
    // $('#btn-add-dealer1').click(function() {
    //   var d = document.getElementById("dealerArrayCount").value;
    //   if(d>=3){exit}; 
    //   d++;
    //   $('#dynamic_dealer').append('<tr id="rowdealer' + d +
    //     '"><td><input type="text" name="cusd_dealer_nme[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td>'
    //     +
    //     '<td><input type="text" name="cusd_dealer_avg_val[]" placeholder="" class="form-control input-sm font-small-2 name_list" style="color:blue;text-align:right" onkeyup="format(this)"></td></tr>');
    // }); 

    var e = 1;
    $('#btn-add-comp').click(function() {
      if(e>=3){exit}; 
      e++;
      $('#dynamic_comp').append('<tr id="rowcom' + e +
        '"><td><input type="text" name="cusd_comp_nme[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td>'
        +
        '<td><input type="text" name="cusd_comp_avg_val[]" placeholder="" class="form-control input-sm font-small-2 name_list" style="color:blue;text-align:right" onkeyup="format(this)"></td>'
        +
        '<td><button type="button" name="btn-del-comp" id="' + e +
        '" class="btn btn-danger btn-sm btn-del-comp"><i class="ft-x"></i></button></td></tr>');
    });
    $(document).on('click', '.btn-del-comp', function() {
      var button_id = $(this).attr("id");
      $('#rowcom' + button_id + '').remove();
      e = e - 1;
    });

    // เคสหน้าจอ edit
    // $('#btn-add-comp1').click(function() {
    //   var e = document.getElementById("compArrayCount").value;
    //   if(e>=3){exit}; 
    //   e++;
    //   $('#dynamic_comp').append('<tr id="rowcom' + e +
    //     '"><td><input type="text" name="cusd_comp_nme[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td>'
    //     +
    //     '<td><input type="text" name="cusd_comp_avg_val[]" placeholder="" class="form-control input-sm font-small-2 name_list" style="color:blue;text-align:right" onkeyup="format(this)"></td></tr>');
        
    // }); 

    // <--step 4 -->
    var book_case = $("#content-box").find("#book_case").val();
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
   
    function load_image_data() {
      var action = $("#content-box").find("#action").val();
      //alert(action);
      $.ajax({
        url: "../serverside/upload_img_list.php",// json datasource
				type: "post",
        data: $('#frm_cust_add').serialize(),
      
        success: function(data) {
          //console.log(data);
          $('#image_table').html(data);
        }
      });
    }

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
        $('#multiple_files_add').val('');
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

    // <-- end step 4 -->
    
    $(document).on('click', '#btnsave', function(e) {

      let cus_reg_nme = document.getElementById("cus_reg_nme").value;
      formname = 'frm_cust_add';
      if(cus_reg_nme==""){
        Swal.fire({
          title: "Error!",
          html:
            "ไม่สามารถบันทึกข้อมูลได้ เนื่องจากยังไม่ได้ระบุชื่อจดทะเบียน" +
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
        url: "../serverside/n_newcust_post.php",
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
              title: "บันทึกข้อมูลเรียบร้อย",
              showConfirmButton: false,
              timer: 50000,
              confirmButtonClass: "btn btn-primary",
              buttonsStyling: false,
              animation: false,
            });
            location.reload(true);
            $(location).attr("href", "../newcust/upd_newcusmnt.php?q=" + json.nb);
            //$(location).attr('href', 'upd_newcusmnt.php?q=' + json.nb + '&current_tab=' + json.pg)
          }
        },
        complete: function () {
          $("#requestOverlay").remove();
        },
      });
    });

    $(document).on('click','#helpIcon21-tab1',function(e){
      $('.showBtnSave').show();
    }); 

    $(document).on('click','#activeIcon22-tab1, #profileIcon22-tab1, #aboutIcon21-tab1, #linkIcon21-tab1',function(e){
      $('.showBtnSave').hide();
    }); 
   
  });  
 
  // funtion other
  var z = document.getElementById("contactArrayCount").value;
  $('#btn-add-contact1').click(function() {
    if(z>=2){exit};
    z++;    
    
    $('#dynamic_contact').append('<tr id="row' + z +
      '"><td><input type="text" name="cus_contact_nme[]" placeholder="" class="form-control input-sm font-small-2 always-show-maxlength" maxlength="50"></td>'
      +
      '<td><input type="text" name="cus_contact_pos[]" placeholder="" class="form-control input-sm font-small-2 always-show-maxlength" maxlength="50"></td>');
     
  });

  var i = document.getElementById("objArrayCount").value;
  $('#btn-add-obj1').click(function() {
    
    if(i>=3){exit};
    i++;    
    $('#dynamic_obj').append('<tr id="row' + i +
      '"><td><input type="text" name="cusd_obj[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td></tr>');
      
  });

  var b = document.getElementById("projArrayCount").value;
  $('#btn-add-prop1').click(function() {  
    if(b>=3){exit}; 
    b++;     
    $('#dynamic_prop').append('<tr id="rowprop' + b +
      '"><td><input type="text" name="cusd_cust_prop[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td></tr>');
  }); 

  var c = document.getElementById("affArrayCount").value;
  $('#btn-add-aff1').click(function() {
    if(c>=3){exit}; 
    c++;
    $('#dynamic_aff').append('<tr id="rowaff' + c +
      '"><td><input type="text" name="cusd_aff[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td></tr>');
  }); 

  var d = document.getElementById("dealerArrayCount").value;
  $('#btn-add-dealer1').click(function() {
    if(d>=3){exit}; 
    d++;
    $('#dynamic_dealer').append('<tr id="rowdealer' + d +
      '"><td><input type="text" name="cusd_dealer_nme[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td>'
      +
      '<td><input type="text" name="cusd_dealer_avg_val[]" placeholder="" class="form-control input-sm font-small-2 name_list" style="color:blue;text-align:right" onkeyup="format(this)"></td></tr>');
  }); 

  var e = document.getElementById("compArrayCount").value;
  $('#btn-add-comp1').click(function() {
    if(e>=3){exit}; 
    e++;
    $('#dynamic_comp').append('<tr id="rowcom' + e +
      '"><td><input type="text" name="cusd_comp_nme[]" placeholder="" class="form-control input-sm font-small-2 name_list always-show-maxlength" maxlength="255"></td>'
      +
      '<td><input type="text" name="cusd_comp_avg_val[]" placeholder="" class="form-control input-sm font-small-2 name_list" style="color:blue;text-align:right" onkeyup="format(this)"></td></tr>');
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
          apprvposteach(step_code);
          ///////////////////////updatedata(step_code);
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
        //alert(result);
        console.log(result);
        var json = $.parseJSON(result);
        if (json.r == '0') {
          Swal.fire({
            type: "warning",
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
      url: '../serverside/n_newcust_post.php?step_code=' +step_code  ,
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
      var Istitle = "หมายเหตุแก้ไข";
      
      Swal.fire({
        title: Istitle,
        html:  rem_revise , 
        type: "info",
        confirmButtonClass: "btn btn-info",
        buttonsStyling: false,
      });

  });
 
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
  
  $('.myFunction').click(function(){
       var mgr = document.getElementById("cusd_os_sale_mgr").value;
       //alert(mgr);
       $("#cusd_os_sale_mgr_code").val(mgr);
        let result = mgr.substring(0, 4);
        //ns18012024 unlock code 0650
        //if(result == "0650"){
          callInfoMgr();
        //} else {
        //  $("#cusd_os_sale_mgr").val("");
        //  $("#cusd_mgr_email").val("");			
        //  $("#cusd_mgr_pos").val("");	
        //}
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

  $('#cusd_review2_name').change(function() {
    $("#cusd_review2").val("");
  })


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
