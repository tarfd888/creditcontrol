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
                    <label for="userinput1">ประเภทการเปลี่ยนแปลงลูกค้า:</label>
                    <input type="hidden" name="cus_cust_type" id="cus_cust_type" value="">

                    <select data-placeholder="เลือกประเภทลูกค้า"
                        class="form-control input-sm border-info font-small-2 select2" id="ch_form_cus" name="ch_form_cus" disabled>
                        <option value="" selected>--- เลือกประเภทลูกค้า ---</option>
                        <?php for($i=1; $i<=1; $i++) {?>
                            <option value="c3" <?php if($cus_cond_cust =='c3') {echo "selected";}?>><?php echo 'เปลี่ยนแปลงชื่อ';?></option>
                            <option value="c4" <?php if($cus_cond_cust =='c4') {echo "selected";}?>><?php echo 'เปลี่ยนแปลงที่อยู่จดทะเบียน';?></option>
                            <option value="c5" <?php if($cus_cond_cust =='c5') {echo "selected";}?>><?php echo 'เปลี่ยนแปลงชื่อและที่อยู่';?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
           
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
        <h6 class="success">ตรวจสอบสถานะลูกค้า</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cr_cus_chk_date">บันทึกสถานะลูกค้า ณ วันที่ :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_cus_chk_date" name="cr_cus_chk_date" value="<?php echo $cr_cus_chk_date; ?>">
                            <div class="form-control-position">
                                <i class="fa fa-pencil-square-o"></i>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6"></div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="cr_debt">หนี้สินค่าสินค้า :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_debt" name="cr_debt" style="color:blue;text-align:right" onkeyup="format(this)" value="<?php echo $cr_debt; ?>">
                            <div class="form-control-position">
                                <i class="fa fa-pencil-square-o"></i>
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="cr_due_date">วันที่ครบกำหนดชำระเงินล่าสุด :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_due_date" name="cr_due_date" value="<?php echo $cr_due_date; ?>">
                            <div class="form-control-position">
                                <i class="fa fa-pencil-square-o"></i>
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="cr_so_amt">S/O คงเหลือ :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_so_amt" name="cr_so_amt" style="color:blue;text-align:right" onkeyup="format(this)" value="<?php echo $cr_so_amt; ?>">
                            <div class="form-control-position">
                                <i class="fa fa-pencil-square-o"></i>
                            </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cr_odue_amt">ค่าชำระเงินล่าช้า :</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" class="form-control input-sm font-small-2" id="cr_odue_amt" name="cr_odue_amt" style="color:blue;text-align:right" onkeyup="format(this)" value="<?php echo $cr_odue_amt; ?>">
                            <div class="form-control-position">
                                <i class="fa fa-pencil-square-o"></i>
                            </div>
                    </div>
                </div>
            </div>
           
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cr_rem_guarantee">บันทึกเกี่ยวกับภาระค้ำประกัน :</label>
                    <textarea name="cr_rem_guarantee" id="cr_rem_guarantee"
                        class="form-control textarea-maxlength input-sm font-small-2 border-info"
                        placeholder="Enter upto 500 characters.." maxlength="500" rows="7"
                        style="line-height:1.5rem;"><?php echo $cr_rem_guarantee; ?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cr_rem_other">อื่นๆ :</label>
                    <textarea name="cr_rem_other" id="cr_rem_other"
                        class="form-control textarea-maxlength input-sm font-small-2 border-info"
                        placeholder="Enter upto 500 characters.." maxlength="500" rows="7"
                        style="line-height:1.5rem;"><?php echo $cr_rem_other; ?></textarea>
                </div>
            </div>

        </div>
    </div>
   
</fieldset>