<fieldset>
    <div class="bs-callout-success callout-border-right callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2">
			<div class="col-lg-12 col-md-12  pl-4 pr-4">
				<div class="row">
					<div class="col-md-3  pt-1 "><span style='color:DarkBlue'>ชื่อจดทะเบียน (ข้อมูลเดิม)</span></div>
					<div class="col-md-9  pt-1 border-bottom"><?php echo $cus_old_name; ?></div>
					<div class="col-md-3  pt-1 "><span style='color:DarkBlue'>ที่อยู่จดทะเบียน (Registered Address)</span></div>
					<div class="col-md-9  pt-1 border-bottom"><?php echo $cus_old_addr; ?></div>
					<div class="col-md-3  pt-1 "><span style='color:DarkBlue'>เลขประจำตัวผู้เสียภาษี (Tax ID No.)</span></div>
					<div class="col-md-9  pt-1 border-bottom"><?php echo $cus_tax_id; ?></div>
				</div>
				<div class="row">
					<div class="col-md-3 pt-1 "><span style='color:DarkBlue'>จังหวัด (Province)</span></div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cus_city; ?></div>
					<div class="col-md-2 pt-1 "><span style='color:DarkBlue'>ประเทศ (Country)</span></div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $country_name; ?></div>
				</div>
				<div class="row">
					<div class="col-md-3 pt-1 "><span style='color:DarkBlue'>รหัสลูกค้า</span></div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cus_old_code; ?></div>
					<div class="col-md-2 pt-1 "><span style='color:DarkBlue'>สาขาที่ (Branch No.)</span></div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $cus_branch; ?></div>
				</div>
				<div class="row">
					<div class="col-md-3 pt-1 "><span style='color:DarkBlue'>Account Group</span></div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cus_acc_group; ?></div>
				</div>
			</div>
		</div>
    </div>

    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2">
			<div class="col-lg-12 col-md-12  pl-4 pr-4">
				<div class="row">
					<div class="col-md-3 pt-1 ">เริ่มวันที่เริ่มใช้ :</div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cus_effective_date; ?></div>
					<div class="col-md-2 pt-1 "><?php echo $newcus_txt; ?></div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $cus_code.' '.$cus_reg_nme; ?></div>
				</div>
				<div class="row">
					<div class="col-md-3  pt-1 "><?php echo $newaddr_txt; ?></div>
					<div class="col-md-9  pt-1 border-bottom"><?php echo $cus_new_addr; ?></div>
				</div>
				<div class="row">
					<div class="col-md-3 pt-1 ">จังหวัด (Province)</div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cus_prov; ?></div>
					<div class="col-md-2 pt-1 ">ประเทศ (Country)</div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $newcus_country; ?></div>
					<div class="col-md-3  pt-1 ">เลขประจำตัวผู้เสียภาษี (Tax ID No.)</div>
					<div class="col-md-4  pt-1 border-bottom"><?php echo $cus_tax_id; ?></div>
					<div class="col-md-2 pt-1 ">สาขาที่ (Branch No.)</div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $newcus_branch; ?></div>
				</div>
				<div class="row">
					<div class="col-md-3  pt-1 ">ความเห็นของผู้แทนขาย :</div>
					<div class="col-md-9  pt-1 border-bottom"><?php echo $cusd_sale_reason; ?></div>
				</div>
			</div>
		</div>
    </div>

    <div class="bs-callout-success callout-border-left callout-bordered callout-transparent p-1 mt-1">   
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
	
	<div class="bs-callout-success callout-border-right callout-bordered callout-transparent p-1 mt-1">   
        <div class="row ml-1 mr-1 pb-2 mt-n2">
			<div class="col-lg-12 col-md-12  pl-4 pr-4">
				<div class="row">
					<div class="col-md-3 pt-1 ">อำนาจดำเนินการ :</div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cusd_op_app; ?></div>
				</div>
			<?php if($apprv_id_array_count == 1) { ?>
				<div class="row">
					<div class="col-md-3 pt-1 ">ผู้พิจารณา 1 :</div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $cusd_review1_name; ?></div>
					<div class="col-md-2 pt-1 ">วันที่ :</div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $cusd_review1_date; ?></div>
				</div>
			<?php }  else { ?>
				<div class="row">
					<div class="col-md-3 pt-1 ">ผู้พิจารณา 1 :</div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cusd_review1_name; ?></div>
					<div class="col-md-2 pt-1 ">วันที่ :</div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $cusd_review1_date; ?></div>
					<div class="col-md-3 pt-1 ">ผู้พิจารณา 2 :</div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cusd_review2_name; ?></div>
					<div class="col-md-2 pt-1 ">วันที่ :</div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $cusd_review2_date; ?></div>
					<div class="col-md-3 pt-1 ">ผู้พิจารณา 3 (CMO) :</div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cusd_review3_name; ?></div>
					<div class="col-md-2 pt-1 ">วันที่ :</div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $cusd_review3_date; ?></div>
					<div class="col-md-3 pt-1 ">ผู้พิจารณา 4 (CFO) :</div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cusd_review4_name; ?></div>
					<div class="col-md-2 pt-1 ">วันที่ :</div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $cusd_review4_date; ?></div>
					<div class="col-md-3 pt-1 ">ผู้อนุมัติ (กจก.) :</div>
					<div class="col-md-4 pt-1 border-bottom"><?php echo $cusd_approve_fin_name; ?></div>
					<div class="col-md-2 pt-1 ">วันที่ :</div>
					<div class="col-md-3 pt-1 border-bottom"><?php echo $cusd_approve_fin_date; ?></div>
				</div>
				<?php } ?>
			</div>
		</div>
    </div>
</fieldset>																																																																																	