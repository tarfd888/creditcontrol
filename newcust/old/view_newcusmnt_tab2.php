<fieldset>
    <!--  <h6 class="form-section text-info"><i class="fa fa-credit-card-alt"></i>
            เงื่อนไขการขายและการชำระเงิน</h6>  -->
    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent mt-1 p-1">    
        <div class="row ml-1 mr-1 pb-2 mt-n2">
            <div class="col-lg-6 mt-n1">
                <div class="row p-1 ">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">เงื่อนไขการชำระเงิน :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_term_name; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ธนาคาร :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_bg1_name; ?></div>
                        </div>		
                        <?php if($cus_bg2_name!="") { ?>
                            <div class="row pr-1 pl-1 ">
                                <div class="col-lg-5 col-md-6 pt-1 ">ธนาคาร :</div>
                                <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_bg2_name; ?></div>
                            </div>	
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
                        
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 text-white">.</div>
                            <div class="col-lg-7 pt-1"></div>
                        </div>		
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">วงเงิน :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_cr_limit1; ?></div>
                        </div>
                        <?php if($cus_cr_limit2!="") { ?>
                            <div class="row pr-1 pl-1 ">
                                <div class="col-lg-5 col-md-6 pt-1 ">วงเงิน :</div>
                                <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_cr_limit2; ?></div>
                            </div>
                        <?php } ?>	
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <hr width=100%> -->
    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">    
        
        <div class="row ml-1 mr-1 pb-2 mt-n2">
            <div class="col-lg-6 mt-n1">
                <div class="row p-1 ">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">กำหนดการจ่ายชำระเงิน :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_cond_term_name; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">สถานที่วางบิล / ชำระค่าสินค้า :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_pay_addr; ?></div>
                        </div>		
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">เบอร์โทรศัพท์ :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_contact_tel; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">E-mail :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_contact_email; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
                        
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 text-white">.</div>
                            <div class="col-lg-7 pt-1"></div>
                        </div>		
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">บุคคลที่ติดต่อการจ่ายชำระเงิน :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_contact_nme_pay; ?></div>
                        </div>
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">เบอร์ Fax :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_contact_fax; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       

    </div>

</fieldset>