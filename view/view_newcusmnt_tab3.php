<fieldset id="mod-key-in">
    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
            <div class="col-lg-6 mt-n1">
                <div class="row p-1 ">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">เป้าหมายการขาย 6 เดือนแรก :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cusd_tg_beg_date; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ประมาณการขายทุกเดือน :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom" style="text-align:right"><? echo $cusd_sale_est; ?>&nbsp;บาท</div>
                        </div>		
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1">ถึง :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cusd_tg_end_date; ?></div>
                        </div>		
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1">ภายใน 6 เดือน สามารถขายได้ :</div>
                            <div class="col-lg-7 pt-1 border-bottom" style="text-align:right"><? echo $cusd_sale_vol; ?>&nbsp;บาท</div>
                        </div>	
                       	
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent p-1 mt-1">  
        <div class="col-md-6">
            <h6>วัตถุประสงค์ / นโยบายด้านการตลาด</h6>
        </div>
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
        <?php for($i=0; $i<3; $i++) { ?>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-12 col-md-6 pt-1 border-bottom"><?php echo $i+1; ?>. <? echo $objArray[$i]; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
        <?php } ?>     
        </div>
	</div>

    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent p-1 mt-1">  
        <div class="col-md-6">
            <h6>คุณสมบัติลูกค้า</h6>
        </div>
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
        <?php for($i=0; $i<3; $i++) { ?>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-12 col-md-6 pt-1 border-bottom"><?php echo $i+1; ?>. <? echo $projArray[$i]; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
        <?php } ?>     
        </div>
	</div>

    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent p-1 mt-1">  
        <div class="col-md-6">
            <h6>กิจการในเครือ (Affiliate / Related Company) </h6>
        </div>
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
        <?php for($i=0; $i<3; $i++) { ?>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-12 col-md-6 pt-1 border-bottom"><?php echo $i+1; ?>. <? echo $affArray[$i]; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
        <?php } ?>     
        </div>
	</div>

    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
        <div class="col-md-12">
            <h6>รายชื่อผู้แทนจำหน่ายทั่วไป ซึ่งลูกค้าที่ขอแต่งตั้งติดต่อเป็นประจำ (Trade Reference)</h6>
        </div>
        <?php for($i=0; $i<3; $i++) { ?>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1">
                            <div class="col-lg-12 col-md-6 pt-1 border-bottom"><?php echo $i+1; ?>. <? echo $dealerArray[$i]; ?> </div>
                        </div>	
                       		
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
                        <div class="row pr-1 pl-1">
                            <div class="col-lg-12 col-md-6 pt-1 border-bottom" style="text-align:right"><? echo $dealerArrayVal[$i]; ?>&nbsp;บาท </div>
                        </div>		
                    </div>
                </div>
            </div>

        <?php } ?>     
        </div>
	</div>

    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
        <div class="col-md-12">
            <h6>รายชื่อคู่แข่ง ซึ่งลูกค้าที่ขอแต่งตั้งซื้อเป็นประจำ</h6>
        </div>
        <?php for($i=0; $i<3; $i++) { ?>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <div class="row pr-1 pl-1">
                            <div class="col-lg-12 col-md-6 pt-1 border-bottom"><?php echo $i+1; ?>. <? echo $compArray[$i]; ?> </div>
                        </div>	
                       		
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
                        <div class="row pr-1 pl-1">
                            <div class="col-lg-12 col-md-6 pt-1 border-bottom" style="text-align:right"><? echo $compArrayVal[$i]; ?>&nbsp;บาท </div>
                        </div>		
                    </div>
                </div>
            </div>

        <?php } ?>     
        </div>
	</div>

    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
            <div class="col-lg-6 mt-n1">
                <div class="row p-1 ">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ผู้เสนอ :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cusd_is_sale1_name; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ชื่อผู้แทนขาย (Inside Sale) :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cusd_is_sale2_name; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ชื่อผู้แทนขาย (Outside Sale) :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cusd_os_sale_name; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ชื่อผู้จัดการ :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cusd_sale_manager_name; ?></div>
                        </div>		
	
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1">อีเมล / เบอร์โทร :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cusd_is_sale1_email; ?> | <?php echo $cusd_is_sale1_tel; ?></div>
                        </div>		
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1">อีเมล / เบอร์โทร :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cusd_is_sale2_email; ?> | <?php echo $cusd_is_sale2_tel; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1">อีเมล / เบอร์โทร :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cusd_os_sale_email; ?> | <?php echo $cusd_os_sale_tel; ?></div>
                        </div>		
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1">อีเมล / หน่วยงานขาย :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cusd_manger_email; ?> | <?php echo $cusd_mgr_pos; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
        </div>
    </div>

</fieldset>