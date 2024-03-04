<!-- Step 1 -->
<fieldset>
    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">    
                <h6 class="success">กลุ่มลูกค้า</h6>

        <div class="row skin skin-square">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="radio" name="cus_tg_cust" id="cus_tg_cust1" value="dom" disabled
                        <?php if($cus_tg_cust=='dom'){ echo "checked"; }?>>
                    <label for="cus_tg_cust1">ลูกค้าในประเทศ (Domestic)</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="radio" name="cus_tg_cust" id="cus_tg_cust2" value="exp" disabled <?php if($cus_tg_cust=='exp'){ echo "checked"; }?>>
                    <label for="cus_tg_cust2">ลูกค้าต่างประเทศ (Export)</label>
                </div>
            </div>
        </div>
    
        <div class="row skin skin-square" style="display:display">
            <div class="col-md-6 ">
                <div class="form-group">
                    <label for="userinput1">ประเภทลูกค้าที่ขอแต่งตั้ง:</label>
                    <select data-placeholder="เลือกประเภทลูกค้า"
                        class="form-control input-sm border-info font-small-2 select2" id="cus_cust_type"
                        name="cus_cust_type" disabled>
                        <option value="" selected>--- เลือกประเภทลูกค้า ---</option>
                        <?php
                                                                        $sql_doc = "SELECT * FROM cus_type_mstr order by cus_type_seq";
                                                                        $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                        while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                        ?>
                        <option value="<?php echo $r_doc['cus_type_code']; ?>" <?php if ($cus_type_code == $r_doc['cus_type_code']) {
                                            echo "selected";
                                        } ?>>
                            <?php echo $r_doc['cus_type_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <!--  <div class="col-md-4 block-tag err_newbranch">
                <small class="badge badge-danger block-area">*** กรุณาเลือกประเภทลูกค้า ***</small>
            </div> -->
            <div class="col-md-6">
                <div class="form-group newbranch_input" style="display:<?php echo $newbranch_input ?>">
                    <label for="cus_cust_type_oth">กรุณาระบุอื่น ๆ :</label>
                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                        id="cus_cust_type_oth" name="cus_cust_type_oth" maxlength="100"
                        value="<?php echo $cus_cust_type_oth ?>">
                </div>
            </div>
        </div>
    </div>     
    
    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">    
        <h6 class="success">รหัสลูกค้า</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cr_sap_code">SAP Customer code :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_sap_code" name="cr_sap_code">
                            <div class="form-control-position">
                                <i class="ft-message-square"></i>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cr_sap_code_date">SAP Create Customer Date :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_sap_code_date" name="cr_sap_code_date">
                            <div class="form-control-position">
                                <i class="ft-message-square"></i>
                            </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">    
        <h6 class="success">ตรวจสอบสถานะลูกค้า</h6>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cr_cus_chk_date">ข้อมูล ณ วันที่ :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_cus_chk_date" name="cr_cus_chk_date">
                            <div class="form-control-position">
                                <i class="ft-message-square"></i>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cr_date_of_reg">วันที่จดทะเบียน (Date Of Registration) :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_date_of_reg" name="cr_date_of_reg">
                            <div class="form-control-position">
                                <i class="ft-message-square"></i>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cr_reg_capital">ทุนจดทะเบียน (Registered Capital) :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_reg_capital" name="cr_reg_capital" style="color:blue;text-align:right" onkeyup="format(this)"
                                onchange="format(this)">
                            <div class="form-control-position">
                                <i class="ft-message-square"></i>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">    
        <h6 class="success">ผลการตรวจสอบการเป็นบุคคลล้มละลาย </h6>
        <div class="row">
            <div class="col-md-4 skin skin-square">
                <div class="form-group">
                    <input type="radio" name="cr_bankrupt" id="cr_bankrupt[]" value="1">
                    <label for="cr_bankrupt">ปกติ</label>
                </div>
            </div>
            <div class="col-md-4 skin skin-square">
                <div class="form-group">
                    <input type="radio" name="cr_bankrupt" id="cr_bankrupt[]" value="2">
                    <label for="cr_bankrupt">ถูกฟ้องล้มละลาย</label>
                </div>
            </div>
            <div class="col-md-4 skin skin-square">
                <div class="form-group">
                    <input type="radio" name="cr_bankrupt" id="cr_bankrupt[]" value="9">
                    <label for="cr_bankrupt">ไม่มีข้อมูล (ลูกค้าต่างประเทศ)</label>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">  
        <h6 class="success">กรรมการที่ถูกฟ้องล้มละลาย/ถูกศาลพิทักษ์ทรัพย์ </h6>
        <div class="row">
            <div class="col-md-4 skin skin-square">
                <div class="form-group">
                    <input type="radio" name="cr_md_bankrupt" id="cr_md_bankrupt[]" value="1">
                    <label for="cr_md_bankrupt">ไม่มี</label>
                </div>
            </div>
            <div class="col-md-4 skin skin-square">
                <div class="form-group">
                    <input type="radio" name="cr_md_bankrupt" id="cr_md_bankrupt[]" value="2">
                    <label for="cr_md_bankrupt">มี ระบุชื่อ:</label>
                </div>
            </div>
            <div class="col-md-4 skin skin-square">
                    <div class="form-group">
                    <input type="radio" name="cr_md_bankrupt" id="cr_md_bankrupt[]" value="9">
                    <label for="cr_md_bankrupt">ไม่มีข้อมูล (ลูกค้าต่างประเทศ)</label>
                    </div>
            </div> 
        </div>
    </div>
 
</fieldset>