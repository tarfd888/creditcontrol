<FORM >
	<h4 class="form-section text-info mt-n2" ><i class="fa fa-cube"></i>  ผู้ขอเสนออนุมัติ </h4>	
	<div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
		<div class="col-lg-6 mt-n1">
			<div class="row p-1 ">
				<div class="col-lg-12 ">
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">ชื่อ-สกุล :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $e_user_fullname; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">E-mail :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $e_user_email; ?></div>
					</div>		
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">เบอร์โทรศัพท์ :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $e_crstm_tel; ?></div>
					</div>	
				</div>
			</div>
		</div>
		<div class="col-lg-6 mt-n1">
			<div class="row p-1">
				<div class="col-lg-12">
					<!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
					
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">หน่วยงาน :</div>
						<div class="col-lg-7 pt-1 border-bottom"><? echo $e_user_th_pos_name; ?></div>
					</div>		
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">ผู้บังคับบัญชา :</div>
						<div class="col-lg-7 pt-1 border-bottom"><? echo $e_user_manager_name; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">สถานะใบขออนุมัติ :</div>
						<div class="col-lg-7 pt-1 border-bottom"><? echo $crstm_status; ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<h4 class="form-section text-info mt-n2" ><i class="fa fa-cube"></i>  ข้อมูลลูกค้า </h4>	
	<div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
		<div class="col-lg-12 col-md-12  pl-4 pr-4">
			<div class="row">
				<div class="col-md-3  pt-1 font-weight-bold">รหัสลูกค้า :</div>
				<div class="col-md-9  pt-1 border-bottom"><?php echo $crstm_cus_nbr. " | ".$crstm_cus_name; ?></div>
			</div>
			<div class="row">
				<div class="col-md-3 pt-1 font-weight-bold">ที่อยู่ :</div>
				<div class="col-md-9 pt-1 border-bottom"><?php echo $crstm_address; ?></div>
			</div>
			
			<div class="row">
				<div class="col-md-3 pt-1 font-weight-bold">จังหวัด :</div>
				<div class="col-md-3 pt-1 border-bottom"><?php echo $crstm_province; ?></div>
				<div class="col-md-3 pt-1 font-weight-bold">ประเทศ :</div>
				<div class="col-md-3 pt-1 border-bottom"><?php echo $crstm_country; ?></div>
			</div>
			<div class="row">
				<div class="col-md-3 pt-1 font-weight-bold">เลขประจำตัวผู้เสียภาษี :</div>
				<div class="col-md-3 pt-1 border-bottom"><?php echo $crstm_tax_nbr3; ?></div>
				<div class="col-md-3 pt-1 font-weight-bold">เงื่อนไขการชำระเงิน :</div>
				<div class="col-md-3 pt-1 border-bottom"><?php echo $crstm_term_add; ?></div>
			</div>
			
		</div>
	</div>	
	
	<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 1. สำหรับหน่วยงานขาย (เสนอขออนุมัติวงเงินสินเชื่อ)</h4>
	
	<div class="row">
		<div class="col-md-4">
			<input type="radio"  id="cus_new" name="cus_conf" value="0" checked disabled>
			<label class="font-weight-bold" for="cus_conf_no"> ลูกค้าใหม่</label>
		</div>	
		
		<div class="col-md-4">
			<input type="radio"  id="rdo_cr_limit" name="chk_rdo" value="C4" <?php if($rdo_cr_limit=='C4') { echo "checked"; }?> disabled>
			<label class="font-weight-bold" for="cus_conf_no"> เสนอขออนุมัติวงเงิน</label>
		</div>
		<div class="col-md-4">
			<input type="radio"  id="rdo_cr_limit" name="chk_rdo" value="C5" <?php if($rdo_cr_limit=='C5') { echo "checked"; }?> disabled> 
			<label class="font-weight-bold" for="cus_conf_no"> อื่น ๆ</label>
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<div class="col-md-8 pt-1 font-weight-bold border-bottom">วันที่เริ่ม    :&emsp; <?php echo $crstm_cc_date_beg; ?></div>
		</div>
		<div class="col-4">
			<div class="col-md-8 pt-1 font-weight-bold border-bottom">วันที่สิ้นสุด    :&emsp;<?php echo $crstm_cc_date_end; ?></div>
		</div>
		<div class="col-4">
			<div class="col-md-8 pt-1 font-weight-bold border-bottom">วงเงิน (บาท)    :&emsp;<?php echo $crstm_cc_amt; ?></div>
		</div>
	</div><br>
	<?php 
		$sum_acc_tot = str_replace(',','',$crstm_cc_amt);
		$acc_tot_app = $sum_acc_tot;
	?>	
	
	
	<!--- เช็คอำนาจดำเนินการขออนุมัติวงเงิน --->
	
	<div class="row">
		<div class="col-md-12">
			<div class="form-group row">
				<!--<label class="col-md-3 label-control font-weight-bold" for="userinput1">อำนาจดำเนินการขออนุมัติวงเงิน:</label>
					<div class="col-md-3">
					<input type="text" name="crstm_approve" id="crstm_approve" value="<?php echo $crstm_approve ?>" class="form-control input-sm font-small-3" readonly>
				</div>-->
				<div class="col-lg-4 col-md-8 pt-1 font-weight-bold">อำนาจดำเนินการอนุมัติวงเงิน :</div>
				<div class="col-4">
					<div class="col-md-8 pt-1 border-bottom"><? echo $crstm_approve; ?></div>
				</div>
			</div>
		</div>

		<div class="col-md-3">
				<label class="font-weight-bold" for="cus_conf_yes">Group:</label>
			</div>
			<div class="col-md-2">
				<input type="radio" name="crstm_scgc" id="crstm_scgc" value=true disabled <?php if ($crstm_scgc==true){ echo "checked"; }?>>
				<label class="font-weight-bold">Tiles</label>
			</div>
			<div class="col-md-2">
				<input type="radio" name="crstm_scgc" id="crstm_scgc" value=false disabled <?php if ($crstm_scgc==false){ echo "checked"; }?>>
				<label class="font-weight-bold">Geoluxe</label>
			</div>
			<div class="col-md-5"></div>
			<div class="col-md-6">
				<div class="form-group row">
					<label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา1 : </label>
					<div class="col-md-6">
						<div class="input-group input-group-sm">
							<input name="crstm_reviewer" id="crstm_reviewer" readOnly value="<?php echo $crstm_reviewer ?>" 
							data-disp_col1 = "emp_fullname"
							data-disp_col2 = "emp_email_bus"
							data-typeahead_src = "../_help/get_emp_data.php",
							data-ret_field_01 = "crstm_reviewer"
							data-ret_value_01 = "emp_email_bus"
							data-ret_type_01 = "val"
							data-ret_field_02 = "reviewer_name"
							data-ret_value_02 = "emp_fullnamedept"
							data-ret_type_02 = "html"
							class="form-control input-sm font-small-3 typeahead">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<a id="buthelp"
									data-id_field_code="crstm_reviewer" 
									data-id_field_name="reviewer_name" 
									data-modal_class = "modal-dialog modal-lg" 
									data-modal_title = "ข้อมูลพนักงาน" 
									data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
									data-modal_col_data1 = "emp_scg_emp_id"
									data-modal_col_data2 = "emp_fullnamedept"
									data-modal_col_data3 = "emp_dept"
									data-modal_col_data4 = "emp_email_bus"
									data-modal_col_data3_vis = true
									data-modal_col_data4_vis = true 
									data-modal_ret_data1 = "emp_email_bus"
									data-modal_ret_data2 = "emp_fullnamedept"
									data-modal_src = "../_help/get_emp_data.php"
									class="input-group-append" style="pointer-events: none">
										<span class="fa fa-search"></span>
									</a>
								</span>
							</div>
						</div><br>
						<div class="dis_reviewer_name">
							<span id="reviewer_name" name="reviewer_name"  class="text-danger"><?php echo $reviewer_name?></span>
						</div>
					</div>	
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-1">
						<input type="checkbox" class="form-control input-sm border-warning " name="crstm_noreviewer" id="crstm_noreviewer" <?php if ($crstm_noreviewer==true){ echo "checked"; }?>>
					</div>
					<label class="col-md-4 label-control" for="userinput1">กรณีไม่ระบุผู้พิจารณา1 :</label>
				</div>
			</div>
			
			<div class="col-md-6 reviewer2" style="display:<?php echo $rev_block ?>">
				<div class="form-group row">
					<label class="font-weight-bold col-md-6 label-control">ผู้พิจารณา 2 : </label>
					<div class="col-md-6">
						<div class="input-group input-group-sm">
							<input name="crstm_reviewer2" id="crstm_reviewer2" readonly value="<?php echo $crstm_reviewer2 ?>" 
							data-disp_col1 = "emp_fullname"
							data-disp_col2 = "emp_email_bus"
							data-typeahead_src = "../_help/get_emp_data.php",
							data-ret_field_01 = "crstm_reviewer"
							data-ret_value_01 = "emp_email_bus"
							data-ret_type_01 = "val"
							data-ret_field_02 = "reviewer_name2"
							data-ret_value_02 = "emp_fullnamedept"
							data-ret_type_02 = "html"
							class="form-control input-sm font-small-3 typeahead">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<a id="buthelp"
									data-id_field_code="crstm_reviewer2" 
									data-id_field_name="reviewer_name2" 
									data-modal_class = "modal-dialog modal-lg" 
									data-modal_title = "ข้อมูลพนักงาน" 
									data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
									data-modal_col_data1 = "emp_scg_emp_id"
									data-modal_col_data2 = "emp_fullnamedept"
									data-modal_col_data3 = "emp_dept"
									data-modal_col_data4 = "emp_email_bus"
									data-modal_col_data3_vis = true
									data-modal_col_data4_vis = true 
									data-modal_ret_data1 = "emp_email_bus"
									data-modal_ret_data2 = "emp_fullnamedept"
									data-modal_src = "../_help/get_emp_data.php"
									class="input-group-append" style="pointer-events: none">
										<span class="fa fa-search" id="pointer1"></span>
									</a>
								</span>
							</div>
						</div><br>
						<div class="dis_reviewer_name2">
							<span id="reviewer_name2" name="reviewer_name2"  class="text-danger"><?php echo $reviewer_name2?></span>
						</div>
					</div>	
				</div>
			</div>			
			<div class="col-md-6" style="display:<?php echo $rev_block ?>"></div>
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 1:</label>
					<div class="col-md-6">
						<div class="input-group input-group-sm">
							<input name="crstm_email_app1" id="crstm_email_app1" readOnly <?php echo $canedit ?> value="<?php echo $crstm_email_app1 ?>" 
							data-disp_col1 = "emp_fullname"
							data-disp_col2 = "emp_email_bus"
							data-typeahead_src = "../_help/get_emp_data.php",
							data-ret_field_01 = "crstm_email_app1"
							data-ret_value_01 = "emp_email_bus"
							data-ret_type_01 = "val"
							data-ret_field_02 = "app1_name"
							data-ret_value_02 = "emp_fullnamedept"
							data-ret_type_02 = "html"
							class="form-control input-sm font-small-3 typeahead">
							
							<div class="input-group-prepend">
								<span class="input-group-text">
									<a id="buthelp" 
									data-id_field_code="crstm_email_app1" 
									data-id_field_name="app1_name" 
									data-modal_class = "modal-dialog modal-lg" 
									data-modal_title = "ข้อมูลพนักงาน" 
									data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
									data-modal_col_data1 = "emp_scg_emp_id"
									data-modal_col_data2 = "emp_fullnamedept"
									data-modal_col_data3 = "emp_dept"
									data-modal_col_data4 = "emp_email_bus"
									data-modal_col_data3_vis = true
									data-modal_col_data4_vis = true 
									data-modal_ret_data1 = "emp_email_bus"
									data-modal_ret_data2 = "emp_fullnamedept"
									data-modal_src = "../_help/get_emp_data.php"
									class="input-group-append" style="pointer-events: <?php echo $pointer ?>">
										<span class="fa fa-search"></span>
									</a>
								</span>
							</div>
						</div><br>
						<div><span id="app1_name" name="app1_name"  class="text-danger"><?php echo $app1_name?></span></div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
				</div>
			</div>

			<div class="col-md-6 displayApp2" style="display:<?php echo $chk_block ?>">
				<div class="form-group row">
					<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 2:</label>
					<div class="col-md-6">
						<div class="input-group input-group-sm">
							<input name="crstm_email_app2" id="crstm_email_app2" readonly value="<?php echo $crstm_email_app2 ?>" 
							data-disp_col1 = "emp_fullname"
							data-disp_col2 = "emp_email_bus"
							data-typeahead_src = "../_help/get_emp_data.php",
							data-ret_field_01 = "crstm_email_app2"
							data-ret_value_01 = "emp_email_bus"
							data-ret_type_01 = "val"
							data-ret_field_02 = "app2_name"
							data-ret_value_02 = "emp_fullnamedept"
							data-ret_type_02 = "html"
							class="form-control input-sm font-small-3 typeahead">
							
							<div class="input-group-prepend">
								<span class="input-group-text">
									<a id="buthelp"
									data-id_field_code="crstm_email_app2" 
									data-id_field_name="app2_name" 
									data-modal_class = "modal-dialog modal-lg" 
									data-modal_title = "ข้อมูลพนักงาน" 
									data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
									data-modal_col_data1 = "emp_scg_emp_id"
									data-modal_col_data2 = "emp_fullnamedept"
									data-modal_col_data3 = "emp_dept"
									data-modal_col_data4 = "emp_email_bus"
									data-modal_col_data3_vis = true
									data-modal_col_data4_vis = true 
									data-modal_ret_data1 = "emp_email_bus"
									data-modal_ret_data2 = "emp_fullnamedept"
									data-modal_src = "../_help/get_emp_data.php"
									class="input-group-append" style="pointer-events: <?php echo $pointer ?>">
										<span class="fa fa-search"></span>
									</a>
								</span>
							</div>
						</div><br>
						<div><span id="app2_name" name="app2_name"  class="text-danger"><?php echo $app2_name?></span></div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
				</div>
			</div>

			<div class="col-md-6 displayApp3" style="display:<?php echo $chk_block ?>">
				<div class="form-group row">
					<label class="col-md-6 label-control font-weight-bold" for="userinput1">ผู้อนุมัติ 3:</label>
					<div class="col-md-6">
						<div class="input-group input-group-sm">
							<input name="crstm_email_app3" id="crstm_email_app3" readonly value="<?php echo $crstm_email_app3 ?>" 
							data-disp_col1 = "emp_fullname"
							data-disp_col2 = "emp_email_bus"
							data-typeahead_src = "../_help/get_emp_data.php",
							data-ret_field_01 = "crstm_email_app3"
							data-ret_value_01 = "emp_email_bus"
							data-ret_type_01 = "val"
							data-ret_field_02 = "app3_name"
							data-ret_value_02 = "emp_fullnamedept"
							data-ret_type_02 = "html"
							class="form-control input-sm font-small-3 typeahead">
							
							<div class="input-group-prepend">
								<span class="input-group-text">
									<a id="buthelp"
									data-id_field_code="crstm_email_app3" 
									data-id_field_name="app3_name" 
									data-modal_class = "modal-dialog modal-lg" 
									data-modal_title = "ข้อมูลพนักงาน" 
									data-modal_col_name = "<tr><th>รหัสพนักงาน</th><th>ชื่อพนักงาน</th><th>หน่วยงาน</th><th>อีเมล</th></tr>" 
									data-modal_col_data1 = "emp_scg_emp_id"
									data-modal_col_data2 = "emp_fullnamedept"
									data-modal_col_data3 = "emp_dept"
									data-modal_col_data4 = "emp_email_bus"
									data-modal_col_data3_vis = true
									data-modal_col_data4_vis = true 
									data-modal_ret_data1 = "emp_email_bus"
									data-modal_ret_data2 = "emp_fullnamedept"
									data-modal_src = "../_help/get_emp_data.php"
									class="input-group-append" style="pointer-events: <?php echo $pointer ?>">
										<span class="fa fa-search"></span>
									</a>
								</span>
							</div>
						</div><br>
						<div><span id="app3_name" name="app3_name"  class="text-danger"><?php echo $app3_name?></span></div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
				</div>
			</div>

			<div class="col-md-12">
				<fieldset class="form-group">
					<label for="placeTextarea" class="font-weight-bold">ความเห็น / เหตุผลที่เสนอขอวงเงิน :</label>
					<textarea  name="crstm_sd_reson" id="crstm_sd_reson" class="form-control input-sm font-small-3" id="placeTextarea"  rows="5" style="line-height:1.5rem;"><?php echo $crstm_sd_reson; ?></textarea>
				</fieldset>	
			</div>
	</div>
	
	<div class="col-lg-6 mt-n1">
		<div class="row pr-1 pl-1 ">
			<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">ไฟล์แนบ:</div>
			<div class="col-lg-7 col-md-6 pt-1">
				<a href="<?php echo($ImgReson) ?>" target="_blank" id="linkcurrent_scvisit_picture">
					<img src="<?php echo($ImgReson) ?>" border="0" id="ImgReson" name="ImgReson"  width="60" height="60">
				</a>	
			</div>
		</div>	
	</div>						
	
	<div class="row">
		
		<div class="col-lg-6 mt-n1">
			<div class="row p-1 ">
				<label class="font-weight-bold">ข้อมูลโครงการ (ถ้ามี):</label>
				<div class="col-lg-12 ">
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">ชื่อโครงการ (1) :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj_name; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">จังหวัด :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj_prv; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<?php 
							$params = array($crstm_pj_term);
						$sql_doc = "SELECT * FROM term_mstr where term_code=? ";
						$result = sqlsrv_query($conn, $sql_doc, $params);	
						$r_row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
						if ($r_row) {
							$crstm_pj_term_name = $r_row['term_code']." | ".$r_row['term_desc'];
						}			
						?>		
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">เงื่อนไขการชำระ :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj_term_name; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">มูลค่างาน (บาท) :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj_amt; ?>  บาท</div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">ระยะเวลา (เดือน):</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj_dura; ?>  เดือน</div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">เริ่มใช้งาน:</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj_beg; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">ไฟล์แนบ:</div>
						<div class="col-lg-7 col-md-6 pt-1">
							<a href="<?php echo($ImgPrj) ?>" target="_blank" id="linkcurrent_scvisit_picture">
								<img src="<?php echo($ImgPrj_icon) ?>" border="0" id="PathCurrent" name="PathCurrent"  width="60" height="60">
							</a>	
						</div>
					</div>	
				</div>
				
			</div>
		</div>
		<div class="col-lg-6 mt-n1">
			<div class="row p-1">
				<div class="col-lg-12">
					<!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
					<label class="font-weight-bold">ข้อมูลโครงการ (ถ้ามี):</label>
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">ชื่อโครงการ (2) :</div>
						<div class="col-lg-7 pt-1 border-bottom"><? echo $crstm_pj1_name; ?></div>
					</div>		
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">จังหวัด :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj1_prv; ?></div>
					</div>
					<div class="row pr-1 pl-1 ">
						<?php 
							$params = array($crstm_pj1_term);
							$sql_doc = "SELECT * FROM term_mstr where term_code=? ";
							$result = sqlsrv_query($conn, $sql_doc, $params);	
							$r_row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
							if ($r_row) {
								$crstm_pj1_term_name = $r_row['term_code']." | ".$r_row['term_desc'];
							}			
						?>		
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">เงื่อนไขการชำระ :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj1_term_name; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">มูลค่างาน (บาท) :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj1_amt; ?>  บาท</div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">ระยะเวลา (เดือน):</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj1_dura; ?>  เดือน</div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">เริ่มใช้งาน:</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_pj1_beg; ?></div>
					</div>	
					<div class="row pr-1 pl-1 ">
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">ไฟล์แนบ:</div>
						<div class="col-lg-7 col-md-6 pt-1">
							<a href="<?php echo($ImgPrj1) ?>" target="_blank" id="linkcurrent_scvisit_picture">
								<img src="<?php echo($ImgPrj1_icon) ?>" border="0" id="PathCurrent" name="PathCurrent"  width="60" height="60">
							</a>
						</div>
					</div>	
				</div>
			</div>
		</div>	
	</div>		
</form>																							