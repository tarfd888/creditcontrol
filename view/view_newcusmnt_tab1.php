<fieldset>
	<!-- <h4 class="form-section text-info mt-n2" ><i class="fa fa-cube"></i>  ผู้ขอเสนออนุมัติ </h4>	 -->
    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
            <div class="col-lg-6 mt-n1">
                <div class="row p-1 ">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ชื่อจดทะเบียน :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_reg_nme; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ที่อยู่จดทะเบียน :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_reg_addr." ".$cus_district." ".$cus_amphur." ".$cus_prov." ".$cus_zip; ?></div>
                        </div>		
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">เบอร์โทรศัพท์ :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_tel; ?></div>
                        </div>
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ประเภทการจดทะเบียนบริษัท :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cus_type_bus_name; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
                        
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">อีเมล์ :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_email; ?></div>
                        </div>		
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">เลขประจำตัวผู้เสียภาษี :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_tax_id; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">สาขาที่ :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cus_branch; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
        </div>

    </div>

	<div class="bs-callout-success callout-border-left callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
            <?php for($i=0;$i<$contactArrayCount;$i++) { ?>
                <div class="col-lg-6 mt-n1">
                    <div class="row p-1">
                        <div class="col-lg-12 ">
                            <div class="row pr-1 pl-1 ">
                                <div class="col-lg-5 col-md-6 pt-1 ">ผู้จัดการที่ติดต่อสั่งซื้อสินค้า :</div>
                                <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $contactArray[$i]; ?></div>
                            </div>	
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-n1">
                    <div class="row p-1">
                        <div class="col-lg-12">
                            <div class="row pr-1 pl-1 ">
                                <div class="col-lg-5 col-md-6 pt-1 ">ตำแหน่ง :</div>
                                <div class="col-lg-7 pt-1 border-bottom"><? echo $contactPosArray[$i]; ?></div>
                            </div>		
                        </div>
                    </div>
                </div>

            <?php }?>    
        </div>
	</div>
	
</fieldset>																																																																																	