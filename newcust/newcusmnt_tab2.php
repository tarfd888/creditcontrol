<fieldset>
    <div class="bs-callout-info callout-transparent callout-bordered mt-1 p-1">
        <div class="media align-items-stretch">
            <div class="media-left d-flex align-items-center bg-info position-relative callout-arrow-left p-2">
                <i class="fa fa-bell-o white font-medium-5"></i>
            </div>
            <div class="media-body p-1 font-small-2 text-bold-400 text-grey">
                <strong class="font-small-3">หมายเหตุ </strong>
                <p class="font-small-3 blue">ช่วง 3-6 เดือนแรก
                    ควรตกลงเงื่อนไขการชำระเงินเป็นโอนเงินก่อนส่ง/LC
                    หรือขายภายใต้วงเงินค้ำประกันธนาคาร (BG)
                    เพื่อประเมินความเสี่ยงเบื้องต้น</p>
            </div>
        </div>
    </div>

    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">    
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cus_term_dom">เงื่อนไขการชำระเงิน</label>
                    <!-- case term domestic -->
                    <select data-placeholder="Select a doc type ..."
                        class="dom_term form-control input-sm border-info font-small-2 select2" id="cus_term_dom"
                        name="cus_term_dom" style="display:none;">
                        <option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---
                        </option>
                        <?php
                                                                    $sql_doc = "SELECT * FROM term_mstr where term_active='1' and term_group='1' order by term_code";
                                                                    $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                    while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                    ?>
                        <option value="<?php echo $r_doc['term_code']; ?>" data-icon="fa fa-wordpress">
                            <?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?>
                        </option>
                        <?php } ?>
                    </select>
    
                    <!-- case term export -->
                    <select data-placeholder="Select a doc type ..."
                        class="exp_term form-control input-sm border-info font-small-2 select2" id="cus_term_exp"
                        name="cus_term_exp" style="display:none;">
                        <option value="" selected>--- เลือกเงื่อนไขการชำระเงิน ---
                        </option>
                        <?php
                                                                    $sql_doc = "SELECT * FROM term_mstr where term_active='1' and term_group='2' order by term_code";
                                                                    $result_doc = sqlsrv_query($conn, $sql_doc);
                                                                    while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                                                    ?>
                        <option value="<?php echo $r_doc['term_code']; ?>" data-icon="fa fa-wordpress">
                            <?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?>
                        </option>
                        <?php } ?>
                    </select>
    
                </div>
            </div>
    
            <div class="col-md-3 text-center">
                <div class="form-group">
                    <p class="font-small-2 text-bold-600"><u>ค้ำประกันโดย </u></p>
                </div>
            </div>
    
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cus_bg1">ธนาคาร</label>
                    <select data-placeholder="Select a doc type ..."
                        class="form-control input-sm border-info font-small-2 select2" id="cus_bg1" name="cus_bg1">
                        <option value="" selected>--- เลือกธนาคาร ---</option>
                        <?php
                                    $sql_doc = "SELECT * FROM bank_mstr where bank_status='1' order by bank_id";
                                    $result_doc = sqlsrv_query($conn, $sql_doc);
                                    while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                    ?>
                        <option value="<?php echo $r_doc['bank_code']; ?>" data-icon="fa fa-wordpress">
                            <?php echo $r_doc['bank_code']." | ".$r_doc['bank_th_name']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
    
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cus_cr_limit1">วงเงิน / บาท</label>
                    <input type="text" class="form-control input-sm font-small-2" id="cus_cr_limit1" name="cus_cr_limit1"
                        style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)"
                        value="<?php echo $cus_cr_limit1;?>">
                </div>
            </div>
    
            <div class="col-md-3"></div>
    
            <div class="col-md-3 text-center"></div>
    
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cus_bg2">ธนาคาร</label>
                    <select data-placeholder="Select a doc type ..."
                        class="form-control input-sm border-info font-small-2 select2" id="cus_bg2" name="cus_bg2">
                        <option value="" selected>--- เลือกธนาคาร ---</option>
                        <?php
                                    $sql_doc = "SELECT * FROM bank_mstr where bank_status='1' order by bank_id";
                                    $result_doc = sqlsrv_query($conn, $sql_doc);
                                    while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
                                    ?>
                        <option value="<?php echo $r_doc['bank_code']; ?>" data-icon="fa fa-wordpress">
                            <?php echo $r_doc['bank_code']." | ".$r_doc['bank_th_name']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
    
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cus_cr_limit2">วงเงิน / บาท</label>
                    <input type="text" class="form-control input-sm font-small-2" id="cus_cr_limit2" name="cus_cr_limit2"
                        style="color:blue;text-align:right" onkeyup="format(this)" onchange="format(this)"
                        value="<?php echo $cus_cr_limit2;?>">
                </div>
            </div>
        </div>
    </div>

    <!-- <hr width=100%> -->
    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">    
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <p class="font-small-2 text-bold-600">
                        <u>กำหนดการจ่ายชำระเงิน</u>
                    </p>
                </div>
            </div>
        </div>
    
        <div class="row skin skin-square comNtxPage2">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="radio" name="cus_cond_term" id="cus_cond_term1" value="1">
                    <label for="cus_cond_term1">ชำระทุกวันตาม Due </label>
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group">
                    <input type="radio" name="cus_cond_term" id="cus_cond_term2" value="2">
                    <label for="cus_cond_term2">มีเงื่อนไขการวางบิลหรือชำระเงินพิเศษ
                        โปรดระบุ</label>
                    <!-- <input type="email" class="form-control input-sm font-small-2" id="emailAddress3"> -->
                </div>
            </div>
    
            <div class="col-md-4 cus_cond_term_txt" style="display:none;">
                <div class="form-group">
                    <input type="text" class="form-control input-sm font-small-2" id="cus_cond_term_oth" name="cus_cond_term_oth"
                        placeholder="โปรดระบุเงื่อนไขการวางบิลหรือชำระเงินพิเศษ">
                </div>
            </div>
        </div>
    
        <div class="row dis_step2" style="display:none;">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cus_pay_addr">สถานที่วางบิล / ชำระค่าสินค้า
                        :</label>
                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_pay_addr"
                        name="cus_pay_addr" maxlength="255">
                </div>
            </div>
    
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cus_contact_nme_pay">บุคคลที่ติดต่อเรื่องการจ่ายชำระเงิน
                        :</label>
                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2"
                        id="cus_contact_nme_pay" name="cus_contact_nme_pay" maxlength="255">
                </div>
            </div>
        </div>
    
        <div class="row dis_step2" style="display:none;">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cus_contact_tel">เบอร์โทรศัพท์ 
                        :</label>
                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_contact_tel"
                        name="cus_contact_tel" maxlength="20">
                </div>
            </div>
    
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cus_contact_fax">เบอร์ Fax :</label>
                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2" id="cus_contact_fax"
                        name="cus_contact_fax" maxlength="20">
                </div>
            </div>
    
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cus_contact_email">E-mail :</label>
                    <input type="text" class="form-control always-show-maxlength input-sm font-small-2 email-inputmask" id="cus_contact_email"
                        name="cus_contact_email" maxlength="50">
                </div>
            </div>
        </div>

    </div>

</fieldset>