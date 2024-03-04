<!-- Step 1 -->
<fieldset id="chagcusmnt_tab1">
    <div class="row skin skin-square">
        <div class="col-md-6">
            <div class="form-group">
                <input type="radio" name="cus_tg_cust" id="cus_tg_cust1" value="dom"
                    <?php if($cus_tg_cust=='dom'){ echo "checked"; }?>>
                <label for="cus_tg_cust1">ลูกค้าในประเทศ (Domestic)</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="radio" name="cus_tg_cust" id="cus_tg_cust2" value="exp" <?php if($cus_tg_cust=='exp'){ echo "checked"; }?>>
                <label for="cus_tg_cust2">ลูกค้าต่างประเทศ (Export)</label>
            </div>
        </div>
    </div>

    <!-- แต่งตั้งร้านสาขา (นิติบุคคลเดิม) -->
    <div class="dis_step1 bs-callout-success callout-border-right callout-bordered callout-transparent p-1">    
        <div class="row skin skin-square newbranch_ch" style="display:show">
            <div class="col-md-6 ">
                <div class="form-group">
                    <label for="userinput1">ประเภทการเปลี่ยนแปลงลูกค้า:</label>
                    <input type="hidden" name="cus_cust_type" id="cus_cust_type" value="">
    
                    <select data-placeholder="เลือกประเภทลูกค้า"
                        class="form-control input-sm border-info font-small-2 select2" id="ch_form_cus" name="ch_form_cus">
                        <option value="" selected>--- เลือกประเภทลูกค้า ---</option>
                        <?php for($i=1; $i<=1; $i++) {?>
                            <option value="c3" <?php if($cus_cond_cust =='c3') {echo "selected";}?>><?php echo 'เปลี่ยนแปลงชื่อ';?></option>
                            <option value="c4" <?php if($cus_cond_cust =='c4') {echo "selected";}?>><?php echo 'เปลี่ยนแปลงที่อยู่จดทะเบียน';?></option>
                            <option value="c5" <?php if($cus_cond_cust =='c5') {echo "selected";}?>><?php echo 'เปลี่ยนแปลงชื่อและที่อยู่';?></option>
                         <?php } ?>
                    </select>
                </div>
            </div>
            <!-- <div class="col-md-4 block-tag err_newbranch_ch">
                <div class="form-group"><br>
                    <small class="badge badge-danger block-area">*** กรุณาเลือกประเภทลูกค้า ***</small>
                </div>
            </div> -->
            <div class="col-md-3 dis_beg_date" style="display:show;">
                <div class="form-group">
                    <label for="cus_effective_date">เริ่มวันที่เริ่มใช้ : <font class="text text-danger font-weight-bold">***
                        </font></label>
                    <input type="text" class="form-control input-sm font-small-2" id="cus_effective_date"
                        name="cus_effective_date" value="<?php echo $cus_effective_date ?>">
                </div>
            </div>
    
            <!-- กรณีเปลี่ยนแปลงชื่อลูกค้าใหม่ -->
                <div class="col-md-3 dis_reg_nme" style="display:show;">
                    <div class="form-group">
                        <label for="cus_reg_nme">ชื่อจดทะเบียน (ใหม่) :</label><font class="text text-danger font-weight-bold">***
                        </font>
                        <input type="text" <?php echo $readonlyC3 ?> class="form-control input-sm font-small-2 always-show-maxlength" id="cus_reg_nme"
                            name="cus_reg_nme" maxlength="80" value="<?php echo $cus_reg_nme ?>">
                    </div>
                </div>
        </div>
        <!-- แต่งตั้งร้านสาขา (นิติบุคคลเดิม) -->
    
        <!-- ข้อมูลทั่วไป -->
        <div class="row dis_ch_step1" style="display:show;">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cust_code">รหัสลูกค้า</label>
                    <input type="text" class="form-control input-sm font-small-2" id="cust_code" name="cust_code" value="<?php echo $cust_code ?>">
                </div>
            </div>
    
            <!-- กรณีเปลี่ยนแปลงที่อยู่ใหม่ -->
            <div class="col-md-6 dis_reg_addr" style="display:<?php echo $dis_reg_addr ?>;";">
                <div class="form-group">
                    <label for="cus_reg_addr">ที่อยู่จดทะเบียนใหม่ : <font class="text text-danger font-weight-bold">***
                        </font></label>
                    <textarea name="cus_reg_addr" id="cus_reg_addr"
                        class="form-control textarea-maxlength input-sm font-small-2 border-info"
                        placeholder="Enter upto 200 characters.." maxlength="200" rows="2"
                        style="line-height:1.5rem;"><?php echo $cus_reg_addr; ?></textarea>    
                </div>
            </div>
    
        </div>
       
        <div class="row mt-0">
             <!-- ข้อมูลลูกค้าดึงมาจาก master มาแสดง -->
            <div class="col-md-6 dis_ch_step1" style="display:show;">
                <div class="form-group last">
                    <label for="cus_mas_addr">ที่อยู่จดทะเบียน (Registered Address) :</label>
                    <textarea name="cus_mas_addr" id="cus_mas_addr"
                        class="form-control textarea-maxlength input-sm font-small-2 border-info"
                        placeholder="" maxlength="500" rows="4" style="line-height:1.5rem;"
                        readonly><?php echo $cus_mas_addr ?></textarea>
                </div>
            </div>
                <input type="hidden" class="form-control input-sm font-small-2" id="cus_code_mas" name="cus_code_mas" value="<?php echo $cus_code ?>">
                <input type="hidden" class="form-control input-sm font-small-2" id="cus_tax_id" name="cus_tax_id" value="<?php echo $cus_tax_id ?>">
                <input type="hidden" class="form-control input-sm font-small-2" id="cus_branch" name="cus_branch" value="<?php echo $cus_branch ?>">
        </div>
    
    </div>

    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">    
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-1">
                    <p class="font-small-2 text-bold-600"><u>ผู้ดูแลลูกค้า </u></p>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-4">
                <label class="label-control">ผู้เสนอ : </label>
                <div class="input-group input-group-sm mb-1">
                    <input type="hidden" class="form-control input-sm font-small-2" id="cusd_is_sale1" name="cusd_is_sale1" value="<?php echo $cusd_is_sale1 ?>">
                    <input name="cusd_is_sale1_name" id="cusd_is_sale1_name" value="<?php echo $cusd_is_sale1_name ?>"
                        data-disp_col1="emp_fullname" data-disp_col2="emp_th_pos_name" data-disp_col3="emp_scg_emp_id"
                        data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="cusd_is_sale1_name"
                        data-ret_value_01="emp_fullnamepos" data-ret_type_01="val" data-ret_field_02="cusd_is_sale1_email"
                        data-ret_value_02="emp_email_bus" data-ret_type_02="html" data-ret_field_03="cusd_is_sale1"
                        data-ret_value_03="emp_scg_emp_id" data-ret_type_03="html"
                        class="form-control input-sm font-small-2 typeahead" value="<?php echo $cusd_is_sale1 ?>">
    
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <a id="buthelp" data-id_field_code="cusd_is_sale1_name" data-id_field_name="cusd_is_sale1_email"
                                data-id_field_code1="cusd_is_sale1" data-modal_class="modal-dialog modal-lg"
                                data-modal_title="ข้อมูลพนักงาน"
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
    
            <div class="col-md-4">
                <div class="form-group mb-1">
                    <label for="cusd_is_sale1_email">Email :</label>
                    <input type="text" class="form-control input-sm font-small-2" id="cusd_is_sale1_email"
                        name="cusd_is_sale1_email" value="<?php echo $cusd_is_sale1_email ?>">
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group mb-1">
                    <label for="cusd_is_sale1_tel">เบอร์โทร :</label>
                    <input type="text" class="form-control input-sm font-small-2" id="cusd_is_sale1_tel" name="cusd_is_sale1_tel" value="<?php echo $cusd_is_sale1_tel ?>">
                </div>
            </div>
    
        </div>
  
        <div class="row">
            <div class="col-md-4">
                <label class="label-control">ชื่อผู้แทนขาย (Inside Sale) : </label>
                <div class="input-group input-group-sm mb-1">
                    <input type="hidden" class="form-control input-sm font-small-2" id="cusd_is_sale2" name="cusd_is_sale2" value="<?php echo $cusd_is_sale2 ?>">
                    <input name="cusd_is_sale2_name" id="cusd_is_sale2_name" value="<?php echo $cusd_is_sale2_name ?>"
                        data-disp_col1="emp_fullname" data-disp_col2="emp_th_pos_name" data-disp_col3="emp_scg_emp_id"
                        data-typeahead_src="../_help/get_help_emp_data.php" , data-ret_field_01="cusd_is_sale2_name"
                        data-ret_value_01="emp_fullnamepos" data-ret_type_01="val" data-ret_field_02="cusd_is_sale2_email"
                        data-ret_value_02="emp_email_bus" data-ret_type_02="html" data-ret_field_03="cusd_is_sale2"
                        data-ret_value_03="emp_scg_emp_id" data-ret_type_03="html"
                        class="form-control input-sm font-small-2 typeahead" value="<?php echo $cusd_is_sale2 ?>">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <a id="buthelp" data-id_field_code="cusd_is_sale2_name" data-id_field_name="cusd_is_sale2_email"
                                data-id_field_code1="cusd_is_sale2" data-modal_class="modal-dialog modal-lg"
                                data-modal_title="ข้อมูลพนักงาน"
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
    
            <div class="col-md-4">
                <div class="form-group mb-1">
                    <label for="cusd_is_sale2_email">Email :</label>
                    <input type="text" class="form-control input-sm font-small-2" id="cusd_is_sale2_email"
                        name="cusd_is_sale2_email" value="<?php echo $cusd_is_sale2_email ?>">
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group mb-1">
                    <label for="cusd_is_sale2_tel">เบอร์โทร :</label>
                    <input type="text" class="form-control input-sm font-small-2" id="cusd_is_sale2_tel" name="cusd_is_sale2_tel" value="<?php echo $cusd_is_sale2_tel ?>">
                </div>
            </div>
    
        </div>
    
        <div class="row">
            <div class="col-md-4">
                <label class="label-control">ชื่อผู้แทนขาย (Outside Sale) : </label>
                <div class="input-group input-group-sm mb-1">
                    <input type="hidden" class="form-control input-sm font-small-2" id="cusd_os_sale" name="cusd_os_sale" value="<?php echo $cusd_os_sale ?>">
                    <input name="cusd_os_sale_name" id="cusd_os_sale_name" value="<?php echo $cusd_os_sale_name ?>"
                        data-disp_col1="emp_fullname" data-disp_col2="emp_th_pos_name" data-disp_col3="emp_scg_emp_id"
                        data-disp_col4="emp_manager_scg_emp_id" data-typeahead_src="../_help/get_help_emp_data.php" ,
                        data-ret_field_01="cusd_os_sale_name" data-ret_value_01="emp_fullnamepos" data-ret_type_01="val"
                        data-ret_field_02="cusd_os_sale_email" data-ret_value_02="emp_email_bus" data-ret_type_02="html"
                        data-ret_field_03="cusd_os_sale" data-ret_value_03="emp_scg_emp_id" data-ret_type_03="html"
                        data-ret_field_04="cusd_os_sale_mgr" data-ret_value_04="emp_manager_scg_emp_id"
                        data-ret_type_04="html" class="form-control input-sm font-small-2 typeahead" value="<?php echo $cusd_os_sale ?>">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <a id="buthelp" data-id_field_code="cusd_os_sale_name" data-id_field_name="cusd_os_sale_email"
                                data-id_field_code1="cusd_os_sale" data-id_field_mgr="cusd_os_sale_mgr"
                                data-modal_class="modal-dialog modal-lg" data-modal_title="ข้อมูลพนักงาน"
                                data-modal_col_name="<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>"
                                data-modal_col_data1="emp_scg_emp_id" data-modal_col_data2="emp_fullnamepos"
                                data-modal_col_data3="emp_dept" data-modal_col_data4="emp_email_bus"
                                data-modal_ret_data1="emp_fullnamepos" data-modal_ret_data2="emp_email_bus"
                                data-modal_ret_data3="emp_scg_emp_id" data-modal_ret_data4="emp_manager_scg_emp_id"
                                data-modal_src="../_help/get_help_emp_data.php" class="input-group-append"
                                style="cursor:pointer">
                                <span class="fa fa-search"></span>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group mb-1">
                    <label for="cusd_os_sale_email">Email :</label>
                    <input type="text" class="form-control input-sm font-small-2 myFunction" id="cusd_os_sale_email"
                        name="cusd_os_sale_email" value="<?php echo $cusd_os_sale_email ?>">
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group mb-1">
                    <label for="cusd_os_sale_tel">เบอร์โทร :</label>
                    <input type="text" class="form-control input-sm font-small-2 myFunction" id="cusd_os_sale_tel"
                        name="cusd_os_sale_tel" value="<?php echo $cusd_os_sale_tel ?>">
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group mb-1">
                    <label for="cusd_os_sale_mgr">ชื่อผู้จัดการ :</label>
                    <input type="text" class="form-control input-sm font-small-2 myFunction" id="cusd_os_sale_mgr"
                        name="cusd_os_sale_mgr" readonly value="<?php echo $cusd_sale_manager_name ?>">
                    <input type="hidden" class="form-control input-sm font-small-2 " id="cusd_os_sale_mgr_code"
                        name="cusd_os_sale_mgr_code" value="<?php echo $cusd_os_sale_mgr_code ?>">
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group mb-1">
                    <label for="cusd_mgr_email">Email :</label>
                    <input type="text" class="form-control input-sm font-small-2 myFunction" id="cusd_mgr_email"
                        name="cusd_mgr_email" readonly value="<?php echo $cusd_manger_email ?>">
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group mb-1">
                    <label for="cusd_mgr_pos">หน่วยงานขาย :</label>
                    <input type="text" class="form-control input-sm font-small-2 myFunction" id="cusd_mgr_pos"
                        name="cusd_mgr_pos" readonly value="<?php echo $cusd_mgr_pos ?>">
                </div>
            </div>
    
        </div>
    </div>
</fieldset>