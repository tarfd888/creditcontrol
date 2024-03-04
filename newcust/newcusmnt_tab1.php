
    <!-- Step 1 -->
    <fieldset>
        <div class="row skin skin-square mt-1">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="radio" name="cus_tg_cust" id="cus_tg_cust1" value="dom">
                    <label for="cus_tg_cust1">ลูกค้าในประเทศ (Domestic)</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="radio" name="cus_tg_cust" id="cus_tg_cust2" value="exp">
                    <label for="cus_tg_cust2">ลูกค้าต่างประเทศ (Export)</label>
                </div>
            </div>
        </div>

        <!-- แต่งตั้งร้านสาขา (นิติบุคคลเดิม) -->
        <div class="newbranch bs-callout-success callout-border-right callout-bordered callout-transparent p-1" style="display:none;">    

            <div class="row skin skin-square newbranch" style="display:none">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="userinput1">ประเภทลูกค้าที่ขอแต่งตั้ง:</label>
                        
                            <select data-placeholder="เลือกประเภทลูกค้า"
                                class="form-control input-sm border-info font-small-2 select2" id="cus_cust_type"
                                name="cus_cust_type">
                                <option value="" selected>--- เลือกประเภทลูกค้า ---</option>
                                <?php
                                                                        $sql_doc = "SELECT * FROM cus_type_mstr order by cus_type_seq";
                                                                        $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                        while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                        ?>
                                <option value="<?php echo $r_doc['cus_type_code']; ?>" data-icon="fa fa-wordpress">
                                    <?php echo $r_doc['cus_type_name']; ?></option>
                                <?php } ?>
                            </select>
                        
                    </div>
                </div>
                <div class="col-md-4 block-tag err_newbranch"><br>
                    <small class="badge badge-danger block-area">*** กรุณาเลือกประเภทลูกค้า ***</small>
                </div>
                <div class="col-md-6">
                    <div class="form-group newbranch_input" style="display:none">
                    <label for="cus_cust_type_oth">กรุณาระบุอื่น ๆ :</label>
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                            id="cus_cust_type_oth" name="cus_cust_type_oth" maxlength="100" placeholder="ระบุอื่น ๆ">
                    </div>
                </div>
            </div>
            <!-- แต่งตั้งร้านสาขา (นิติบุคคลเดิม) -->

            <!-- ข้อมูลทั่วไป -->

            <div class="row dis_step1" style="display:none;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cus_reg_nme">ชื่อจดทะเบียน (Registered Name)
                            :</label>
                        <!-- <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_reg_nme"
                            name="cus_reg_nme" maxlength="80"> -->
                        <textarea name="cus_reg_nme" id="cus_reg_nme"
                            class="form-control textarea-maxlength input-sm font-small-2 border-info"
                            placeholder="Enter upto 80 characters.." maxlength="80" rows="2"
                            style="line-height:1.5rem;"></textarea>    
                    </div>
                </div>
    
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cus_reg_addr">ที่อยู่จดทะเบียน 
                            :</label>
                            <textarea name="cus_reg_addr" id="cus_reg_addr"
                                class="form-control textarea-maxlength input-sm font-small-2 border-info"
                                placeholder="Enter upto 200 characters.." maxlength="200" rows="2"
                                style="line-height:1.5rem;"></textarea>
                    </div>
                </div>
            </div>
    
            <div class="row dis_step1" style="display:none;">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cus_country">ประเทศ (Country) :</label>
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_country" name="cus_country"
                        maxlength="30">
                    </div>
                </div> 
    
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cus_tel">เบอร์โทรศัพท์ :</label>
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_tel" name="cus_tel"
                        maxlength="20">
                    </div>
                </div>
    
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cus_fax">เบอร์ Fax :</label>
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_fax" name="cus_fax" maxlength="20">
                    </div>
                </div>
    
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cus_email">E-mail :</label>
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2 email-inputmask" id="cus_email"
                            name="cus_email" maxlength="50">
                    </div>
                </div>
    
                <!-- กรณีเช็คเงื่อนไข domestic require tax 13 digit branch 5 digit -->
                <div class="col-md-3 domestic" style="display:<?php echo $dom_show; ?>">
                    <div class="form-group">
                        <label for="cus_tax_id_dom">เลขประจำตัวผู้เสียภาษี/เลขที่ทะเบียนพาณิชย์
                            :</label>
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_tax_id_dom"
                            name="cus_tax_id_dom" maxlength="13" value="<?php echo $cus_tax_id;?>">
                    </div>
                </div>
        
                <div class="col-md-3 domestic" style="display:<?php echo $dom_show; ?>">
                    <div class="form-group">
                        <label for="cus_branch_dom">สาขาที่ (Branch No.) :</label>
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_branch_dom"
                            name="cus_branch_dom" maxlength="5" value="<?php echo $cus_branch;?>">
                    </div>
                </div>
    
                <!-- กรณีเช็คเงื่อนไข export require tax 20 digit branch 20 digit -->
                <div class="col-md-3 export" style="display:<?php echo $exp_show; ?>">
                    <div class="form-group">
                        <label for="cus_tax_id_exp">เลขประจำตัวผู้เสียภาษี/เลขที่ทะเบียนพาณิชย์
                            :</label>
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_tax_id_exp"
                            name="cus_tax_id_exp" maxlength="20" value="<?php echo $cus_tax_id;?>">
                    </div>
                </div>
        
                <div class="col-md-3 export" style="display:<?php echo $exp_show; ?>">
                    <div class="form-group">
                        <label for="cus_branch_exp">สาขาที่ (Branch No.) :</label>
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_branch_exp"
                            name="cus_branch_exp" maxlength="20" value="<?php echo $cus_branch;?>">
                    </div>
                </div>
               
            </div>
        </div>

        <!--  <hr width=100%> -->
        <div class="domestic bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1" style="display:none;"> 
            <div class="row dis_step1" style="display:none;">
                <div class="col-md-6">
                    <div class="form-group">
                        <p class="font-small-2 text-bold-600">
                            <u>ประเภทการจดทะเบียนบริษัท (Type Of
                                Business)</u>
                        </p>
                    </div>
                </div>
            </div>
    
            <div class="row skin skin-square domestic" style="display:none;">
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label for="userinput1">ประเภทการจดทะเบียนบริษัท:</label>
                       
                            <select data-placeholder="เลือกประเภทลูกค้า"
                                class="form-control input-sm border-info font-small-2 select2" id="cus_type_bus_dom"
                                name="cus_type_bus_dom">
                                <option value="" selected>--- เลือกประเภทการจดทะเบียนบริษัท ---</option>
                                <?php
                                                                        $sql_doc = "SELECT * FROM cus_tyofbus_mstr where cus_tyofbus_group='1' order by cus_tyofbus_seq";
                                                                        $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                        while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                        ?>
                                <option value="<?php echo $r_doc['cus_tyofbus_id']; ?>" data-icon="fa fa-wordpress">
                                    <?php echo $r_doc['cus_tyofbus_name']; ?></option>
                                <?php } ?>
                            </select>
                       
                    </div>
                </div>
                <div class="col-md-4 block-tag err_domestic"><br>
                    <small class="badge badge-danger block-area">*** กรุณาเลือกประเภทลูกค้า ***</small>
                </div>
                <div class="col-md-6">
                    <div class="form-group domestic_input mt-2" style="display:none">
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                            id="cus_other_dom" name="cus_other_dom" maxlength="100" placeholder="ระบุอื่น ๆ">
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-3 ml-auto dom_oth" style="display:none;">
                    <div class="form-group">
                        <input type="text" name="cus_type_bus_oth" class="form-control input-sm font-small-2"
                            placeholder="ระบุอื่น ๆ">
                    </div>
                </div>
            </div>
        </div>   

        <!-- start radio export -->
        <div class="export bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1" style="display:none;">    
            <div class="row dis_step1" style="display:none;">
                <div class="col-md-6">
                    <div class="form-group">
                        <p class="font-small-2 text-bold-600">
                            <u>ประเภทการจดทะเบียนบริษัท (Type Of
                                Business)</u>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row skin skin-square export" style="display:none;">
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label  for="userinput1">ประเภทการจดทะเบียนบริษัท:</label>
                            <select data-placeholder="เลือกประเภทลูกค้า"
                                class="form-control input-sm border-info font-small-2 select2" id="cus_type_bus_exp"
                                name="cus_type_bus_exp">
                                <option value="" selected>--- เลือกประเภทการจดทะเบียนบริษัท ---</option>
                                <?php
                                                                            $sql_doc = "SELECT * FROM cus_tyofbus_mstr where cus_tyofbus_group='2' order by cus_tyofbus_seq";
                                                                            $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                            while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                            ?>
                                <option value="<?php echo $r_doc['cus_tyofbus_id']; ?>" data-icon="fa fa-wordpress">
                                    <?php echo $r_doc['cus_tyofbus_name']; ?></option>
                                <?php } ?>
                            </select>
                    </div>
                </div>
                <div class="col-md-4 block-tag err_export"><br>
                    <small class="badge badge-danger block-area">*** กรุณาเลือกประเภทลูกค้า ***</small>
                </div>
                <div class="col-md-6">
                    <div class="form-group export_input mt-2" style="display:none">
                        <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                            id="cus_other_exp" name="cus_other_exp" maxlength="100" placeholder="ระบุอื่น ๆ">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="dis_step1_1 bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1" style="display:none;">    
            <div class="row dis_step1_1" style="display:none;">
                <div class="col-md-3">
                    <div class="form-group">
                        <p class="font-small-2 text-bold-600"><u>ชื่อเจ้าของ /
                                ผู้จัดการที่ติดต่อสั่งซื้อสินค้า</u></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive dis_step1_1" style="display:none;">
                        <table class="table mb-1" id="dynamic_contact">
                            <tr>
                                <td><input type="text" name="cus_contact_nme[]" placeholder="ระบุชื่อ - สกุล..."
                                        class="form-control input-sm font-small-2 always-show-maxlength" maxlength="50">
                                </td>
                                <td><input type="text" name="cus_contact_pos[]" placeholder="ตำแหน่ง"
                                        class="form-control input-sm font-small-2 always-show-maxlength" maxlength="50">
                                </td>
                                <td><button type="button" name="btn-add-contact" id="btn-add-contact"
                                        class="btn btn-success btn-sm"><i class="ft-plus"></i></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
