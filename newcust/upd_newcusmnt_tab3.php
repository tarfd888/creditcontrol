<fieldset id="mod-key-in">
    <div style="display:<?php echo $page3; ?>">
        <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">     
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <p class="font-small-2 text-bold-600"><u>เป้าหมายการขาย </u></p>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cusd_tg_beg_date">เป้าหมายการขาย 6 เดือนแรก
                            เริ่มตั้งแต่วันที่ :</label>
                        <input type="text" class="form-control input-sm font-small-2" id="cusd_tg_beg_date"
                            name="cusd_tg_beg_date" value="<?php echo $cusd_tg_beg_date ?>">
                    </div>
                </div>
        
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cusd_tg_end_date">ถึง :</label>
                        <input type="text" class="form-control input-sm font-small-2" id="cusd_tg_end_date"
                            name="cusd_tg_end_date" value="<?php echo $cusd_tg_end_date ?>">
                    </div>
                </div>
        
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cusd_sale_est">ประมาณการขายทุกเดือน เดือนละ (บาท)
                            :</label>
                        <input type="text" class="form-control input-sm font-small-2" id="cusd_sale_est" name="cusd_sale_est"
                            style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)"
                            value="<?php echo $cusd_sale_est ?>">
                    </div>
                </div>
        
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cusd_sale_vol">หรือ ภายใน 6 เดือน สามารถขายได้ (บาท)
                            :</label>
                        <input type="text" class="form-control input-sm font-small-2" id="cusd_sale_vol" name="cusd_sale_vol"
                            style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)"
                            value="<?php echo $cusd_sale_vol ?>">
                    </div>
                </div>
            </div>
        </div>    
    </div>
    
    <?php if($objArrayCount > 0) { ?>
        <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">   
            <?php if($objArrayCount > 0) { ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <p class="font-small-2 text-bold-600"><u>วัตถุประสงค์ /
                                    นโยบายด้านการตลาด </u></p>
                        </div>
                    </div>
                </div>
            <?php } ?>    
        
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <?php if($objArrayCount > 0) { ?>
                        <table class="table mb-1" id="dynamic_obj">
                            <?php for($i=1;$i<$objArrayCount+1;$i++) { ?>
                            <tr id="row<?php echo $i ?>">
                                <td><input type="text" name="cusd_obj[]" placeholder="ระบุวัตถุประสงค์/นโยบายด้านการตลาด..."
                                class="form-control input-sm font-small-2" value="<?php echo $objArray[$i-1] ?>">
                                </td>
                                <?php if($i == 1) {?>
                                    <td><button type="button" id="btn-add-obj1"
                                            class="btn btn-success btn-sm"><i class="ft-plus"></i></button>
                                    </td> 
                                <?php } else { ?>
                                <!--  <td><button type="button" name="btn-del-obj1"
                                            class="btn btn-danger btn-sm btn-del-obj1" id="<?php echo $i ?>"><i class="ft-x"></i></button>
                                    </td> -->
                                <?php } ?> 
                            </tr>
                            <?php }?>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?> 

    <?php if($projArrayCount > 0) { ?>
        <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">     
            <?php if($projArrayCount > 0) { ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <p class="font-small-2 text-bold-600"><u>คุณสมบัติลูกค้า </u>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>    
        
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <?php if($projArrayCount > 0) { ?>
                        <table class="table mb-1" id="dynamic_prop">
                            <?php for($i=1;$i<$projArrayCount+1;$i++) { ?>
                            <tr>
                                <td><input type="text" name="cusd_cust_prop[]" placeholder="ระบุคุณสมบัติลูกค้า..."
                                        class="form-control input-sm font-small-2" value="<?php echo $projArray[$i-1] ?>">
                                </td>
                                    <?php if($i == 1) {?>
                                        <td><button type="button" id="btn-add-prop1"
                                                class="btn btn-success btn-sm"><i class="ft-plus"></i></button>
                                        </td> 
                                    <?php } ?>     
                            </tr>
                            <?php }?>
                        </table>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>  

    <?php if($affArrayCount > 0) { ?>
        <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">   
            <?php if($affArrayCount > 0) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <p class="font-small-2 text-bold-600"><u>กิจการในเครือ
                                    (Affiliate / Related Company) </u>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>     
        
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <?php if($affArrayCount > 0) { ?>
                        <table class="table mb-1" id="dynamic_aff">
                            <?php for($i=1;$i<$affArrayCount+1;$i++) { ?>
                            <tr>
                                <td><input type="text" name="cusd_aff[]" placeholder="ระบุกิจการในเครือ..."
                                        class="form-control input-sm font-small-2" value="<?php echo $affArray[$i-1] ?>">
                                </td>
                                <?php if($i == 1) {?>
                                    <td><button type="button" id="btn-add-aff1"
                                                class="btn btn-success btn-sm"><i class="ft-plus"></i></button>
                                        </td> 
                                <?php } ?>        
                            </tr>
                            <?php }?>
                        </table>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>    

    <?php if($dealerArrayCount > 0) { ?>
        <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">    
            <?php if($dealerArrayCount > 0) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <p class="font-small-2 text-bold-600">
                                <u>รายชื่อผู้แทนจำหน่ายทั่วไป
                                    ซึ่งลูกค้าที่ขอแต่งตั้งติดต่อเป็นประจำ (Trade Reference)
                                </u>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>   
        
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <?php if($dealerArrayCount > 0) { ?>
                        <table class="table mb-1" id="dynamic_dealer">
                            <?php for($i=1;$i<$dealerArrayCount+1;$i++) { ?>
                            <tr>
                                <td><input type="text" name="cusd_dealer_nme[]" placeholder="ระบุรายชื่อผู้แทนจำหน่ายทั่วไป..."
                                        class="form-control input-sm font-small-2" value="<?php echo $dealerArray[$i-1] ?>">
                                </td>
                                <td><input type="text" name="cusd_dealer_avg_val[]" placeholder="ระบุมูลค่าเฉลี่ย/เดือน"
                                        class="form-control input-sm font-small-2" style="color:blue;text-align:right"
                                        onkeyup="format(this)" value="<?php echo $dealerArrayVal[$i-1] ?>">
                                </td>
                                <?php if($i == 1) {?>
                                    <td><button type="button" id="btn-add-dealer1"
                                            class="btn btn-success btn-sm"><i class="ft-plus"></i></button>
                                    </td> 
                                <?php }?>
                            </tr>
                            <?php }?>
                        </table>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div> 
    <?php } ?> 
    
    <?php if($compArrayCount > 0) { ?>
        <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">   
            <?php if($compArrayCount > 0) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <p class="font-small-2 text-bold-600"><u>รายชื่อคู่แข่ง
                                    ซึ่งลูกค้าที่ขอแต่งตั้งซื้อเป็นประจำ
                                </u></p>
                        </div>
                    </div>
                </div>
            <?php } ?>   
        
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <?php if($compArrayCount > 0) { ?>
                        <table class="table mb-1" id="dynamic_comp"
                            class="table table-sm table-hover table-bordered compact nowrap " style="width:100%;">
                            <?php for($i=1;$i<$compArrayCount+1;$i++) { ?>
                            <tr>
                                <td><input type="text" name="cusd_comp_nme[]" placeholder="ระบุรายชื่อคู่แข่ง..."
                                        class="form-control input-sm font-small-2" value="<?php echo $compArray[$i-1] ?>">
                                </td>
                                <td><input type="text" name="cusd_comp_avg_val[]" placeholder="ระบุมูลค่าเฉลี่ย/เดือน"
                                        class="form-control input-sm font-small-2" style="color:blue;text-align:right"
                                        onkeyup="format(this)" value="<?php echo $compArrayVal[$i-1] ?>">
                                </td>
                                <?php if($i == 1) {?>
                                    <td><button type="button" id="btn-add-comp1"
                                            class="btn btn-success btn-sm"><i class="ft-plus"></i></button>
                                    </td>
                                <?php }?>    
                            </tr>
                            <?php }?>
                        </table>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?> 

    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">    
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