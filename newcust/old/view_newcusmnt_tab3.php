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
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cusd_sale_est; ?></div>
                        </div>		
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ถึง :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cusd_tg_end_date; ?></div>
                        </div>		
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ภายใน 6 เดือน สามารถขายได้ :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $cusd_sale_vol; ?></div>
                        </div>	
                       	
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
        <?php for($i=0; $i<3; $i++) { ?>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ระบุวัตถุประสงค์ :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $objArray[$i]; ?></div>
                        </div>	
                       		
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">คุณสมบัติลูกค้า :</div>
                            <div class="col-lg-7 pt-1 border-bottom"><? echo $projArray[$i]; ?></div>
                        </div>		
                       
                       	
                    </div>
                </div>
            </div>

        <?php } ?>     
        </div>
	</div>

</fieldset>