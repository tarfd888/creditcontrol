
    <fieldset>
        <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">   
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cusd_test">ความเห็นของผู้แทนขาย
                            (ใช้สำหรับเสนอขออนุมัติ) :</label>
                        <textarea name="cusd_sale_reason" id="cusd_sale_reason"
                            class="form-control textarea-maxlength input-sm font-small-2 border-info"
                            placeholder="Enter upto 500 characters.." maxlength="500" rows="9"
                            style="line-height:1.5rem;"></textarea>
                    </div>
                </div>
    
                <div class="col-md-6">
                    <div class="form-group mb-1">
                        <label for="decisions2">อำนาจดำเนินการ :</label>
                        <input type="email" class="form-control input-sm font-small-2" id="cusd_op_app" name="cusd_op_app"
                            value="<?php echo $cusd_op_app; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <div class="row">
    
                            <!-- ผู้พิจารณา 1 -->
                            <div class="col-md-12 mb-1">
                                <label class="label-control" id="label_rev">ผู้พิจารณา 1 
                                    :</label>
                                <div class="input-group input-group-sm">
                                    <input type="hidden" class="form-control input-sm font-small-2" id="cusd_review1"
                                        name="cusd_review1" value="<?php echo $cusd_review1 ?>">
                                    <input name="cusd_review1_name" id="cusd_review1_name" value="<?php echo $cusd_review1_name ?>"
                                        data-disp_col1="emp_fullname" data-disp_col2="emp_th_pos_name"
                                        data-typeahead_src="../_help/get_help_emp_data.php" ,
                                        data-ret_field_01="cusd_review1_name" data-ret_value_01="emp_fullnamepos"
                                        data-ret_type_01="val" data-ret_field_02="reviewer1_name"
                                        data-ret_value_02="emp_th_pos_name" data-ret_type_02="html"
                                        data-ret_field_03="cusd_review1"  data-ret_value_03="emp_scg_emp_id" data-ret_type_03="html" 
                                        class="form-control input-sm font-small-2 typeahead">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <a id="buthelp" data-id_field_code="cusd_review1_name"
                                                data-id_field_name="cusd_review1_email" data-id_field_code1="cusd_review1"
                                                data-modal_class="modal-dialog modal-lg" data-modal_title="ข้อมูลพนักงาน"
                                                data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                                data-modal_col_data1="emp_scg_emp_id" data-modal_col_data2="emp_fullnamepos"
                                                data-modal_col_data3="emp_dept" data-modal_col_data4="emp_email_bus"
                                                data-modal_col_data4_vis=true data-modal_ret_data1="emp_fullnamepos"
                                                data-modal_ret_data2="emp_email_bus" data-modal_ret_data3="emp_scg_emp_id"
                                                data-modal_src="../_help/get_help_emp_data.php" class="input-group-append"
                                                style="cursor:pointer">
                                                <span class="fa fa-search"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
    
                            <!-- ผู้พิจารณา 2 -->
                            <div class="col-md-12 mb-1 dis_apprv" style="display:none">
                                <label class="label-control">ผู้พิจารณา 2 
                                    :</label>
                                <div class="input-group input-group-sm">
                                    <input type="hidden" class="form-control input-sm font-small-2" id="cusd_review2"
                                        name="cusd_review2">
                                    <input name="cusd_review2_name" id="cusd_review2_name" value="<?php echo $cusd_review2_name ?>"
                                        data-disp_col1="emp_fullname" data-disp_col2="emp_th_pos_name"
                                        data-typeahead_src="../_help/get_help_emp_data.php" ,
                                        data-ret_field_01="cusd_review2_name" data-ret_value_01="emp_fullnamepos"
                                        data-ret_type_01="val" data-ret_field_02="reviewer1_name"
                                        data-ret_value_02="emp_th_pos_name" data-ret_type_02="html"
                                        data-ret_field_03="cusd_review2"  data-ret_value_03="emp_scg_emp_id" data-ret_type_03="html" 
                                        class="form-control input-sm font-small-2 typeahead">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <a id="buthelp" data-id_field_code="cusd_review2_name"
                                                data-id_field_name="cusd_review2_email" data-id_field_code1="cusd_review2"
                                                data-modal_class="modal-dialog modal-lg" data-modal_title="ข้อมูลพนักงาน"
                                                data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                                data-modal_col_data1="emp_scg_emp_id" data-modal_col_data2="emp_fullnamepos"
                                                data-modal_col_data3="emp_dept" data-modal_col_data4="emp_email_bus"
                                                data-modal_col_data4_vis=true data-modal_ret_data1="emp_fullnamepos"
                                                data-modal_ret_data2="emp_email_bus" data-modal_ret_data3="emp_scg_emp_id"
                                                data-modal_src="../_help/get_help_emp_data.php" class="input-group-append"
                                                style="cursor:pointer">
                                                <span class="fa fa-search"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                           
                            <!-- ผู้พิจารณา 3 -->
                            <div class="col-md-12 mb-1 dis_apprv" style="display:none">
                                <label class="label-control">ผู้พิจารณา 3 (CMO)
                                    :</label>
                                <input type="hidden" class="form-control input-sm font-small-2" id="sysc_cmo_id"
                                    name="sysc_cmo_id" value="<?php echo $sysc_cmo_id ?>">    
                                <input type="text" class="form-control input-sm font-small-2" id="cusd_review3"
                                    name="cusd_review3" value="<?php echo $sysc_name_cmo ?>" readonly>
                            </div>
                            <!--  <div class="col-md-6 mb-1">
                                                                    <label for="decisions2">ตำแหน่ง :</label>
                                                                    <input type="email"
                                                                        class="form-control input-sm font-small-2"
                                                                        id="cus_contact_fax" name="cus_contact_fax">
                                                                </div> -->
    
                            <!-- ผู้พิจารณา 4 -->
                            <div class="col-md-12 mb-1 dis_apprv" style="display:none">
                                <label class="label-control">ผู้พิจารณา 4 (CFO)
                                    :</label>
                                <input type="hidden" class="form-control input-sm font-small-2" id="sysc_cfo_id"
                                    name="sysc_cfo_id" value="<?php echo $sysc_cfo_id ?>">    
                                <input type="text" class="form-control input-sm font-small-2" id="cusd_review4"
                                    name="cusd_review4" value="<?php echo $sysc_name_cfo; ?>" readonly>
                            </div>
                            <!--  <div class="col-md-6 mb-1">
                                                                    <label for="decisions2">ตำแหน่ง :</label>
                                                                    <input type="email"
                                                                        class="form-control input-sm font-small-2"
                                                                        id="cus_contact_fax" name="cus_contact_fax">
                                                                </div> -->
    
                            <!-- ผู้อนุมัติ (กจก.) -->
                            <div class="col-md-12 mb-1 dis_apprv" style="display:none">
                                <label class="label-control">ผู้อนุมัติ (กจก.) :</label>
                                <input type="hidden" class="form-control input-sm font-small-2" id="sysc_md_id"
                                    name="sysc_md_id" value="<?php echo $sysc_md_id ?>">
                                <input type="text" class="form-control input-sm font-small-2" id="cusd_approve_fin"
                                    name="cusd_approve_fin" value="<?php echo $sysc_name_md; ?>" readonly>
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
        </div>
   
    </fieldset>
