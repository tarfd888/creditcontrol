<fieldset>
	<div class="bs-callout-success callout-border-left callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
            <div class="col-lg-6 mt-n1">
                <div class="row p-1 ">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">บันทึกสถานะลูกค้า ณ วันที่ :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cr_cus_chk_date; ?></div>
                        </div>	
                    </div>
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">วันที่ครบกำหนดชำระเงินล่าสุด :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cr_due_date; ?></div>
                        </div>	
                    </div>
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">ค่าชำระเงินล่าช้า :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom" style="text-align:right"><? echo $cr_odue_amt; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-n1">
                <div class="row p-1">
                    <div class="col-lg-12">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">หนี้สินค่าสินค้า :</div>
                            <div class="col-lg-7 pt-1 border-bottom" style="text-align:right"><? echo $cr_debt; ?></div>
                        </div>		
                    </div>
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-5 col-md-6 pt-1 ">S/O คงเหลือ :</div>
                            <div class="col-lg-7 col-md-6 pt-1 border-bottom" style="text-align:right"><? echo $cr_so_amt; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
            <div class="col-lg-12 mt-n1">
                <div class="row p-1 ">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-3 col-md-6 pt-1 ">บันทึกเกี่ยวกับภาระค้ำประกัน :</div>
                            <div class="col-lg-9 col-md-6 pt-1 border-bottom"><? echo $cr_rem_guarantee; ?></div>
                        </div>	
                    </div>
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-3 col-md-6 pt-1 ">อื่นๆ :</div>
                            <div class="col-lg-9 col-md-6 pt-1 border-bottom"><? echo $cr_rem_other; ?></div>
                        </div>	
                    </div>
                </div>
            </div>
        </div>
    </div>

</fieldset>																																																																																	