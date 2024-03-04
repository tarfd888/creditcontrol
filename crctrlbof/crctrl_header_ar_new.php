<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 2. สำหรับหน่วยงานสินเชื่อ ( 1 )</h4><br>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label for="placeTextarea" class="font-weight-bold">ความเห็นสินเชื่อ #1 : </label>
			<textarea  name="crstm_cc1_reson" id="crstm_cc1_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;"><?php echo $crstm_cc1_reson; ?></textarea>
		</div>	
	</div>
	<div class="col-md-6"> 
		<div class="row pr-1 pl-1 ">
			<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">เจ้าหน้าที่สินเชื่อ :</div>
			<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_create_by_cr1."&emsp;".$crstm_create_cr1_date; ?></div>
		</div>	
	</div>
	<div class="col-md-6 ml-auto"> <!-- Left Offset -->
		<div class="form-group row">
			<label class="col-md-3 label-control font-weight-bold" for="userinput1" >ไฟล์แนบ :</label>
			<div class="col-md-9">
				<a href="<?php echo($ImgCr1) ?>" target="_blank" id="linkcurrent_ImgProject1">
					<img src="<?php echo($ImgCr1_icon) ?>" border="0" id="ImgCr1" name="ImgCr1"  width="60" height="60">
				</a>	
			</div>
		</div>
	</div>
</div>
<!-------------------  เริ่มข้อมูลสินเชื่อคนที่ 2---------------------------->
<?php if($crstm_cc2_reson !="") { ?>
	<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 2. สำหรับหน่วยงานสินเชื่อ ( 2 )</h4><br>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="font-weight-bold">Upload งบการเงินลูกค้า:</label>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<input type="radio" id="dbd_conf_yes" name="web_conf" value="1" disabled <?php if($dbd_conf_yes=='1'){ echo "checked"; }?>>
			<label class="font-weight-bold" for="cus_conf_yes">งบการเงินจากเว็บไซต์กรมพัฒนาธุรกิจ</label>
		</div>
		<div class="col-md-6">
			<input type="radio" id="oth_conf_yes" name="web_conf" value="2" disabled <?php if($dbd_conf_yes=='2'){ echo "checked"; }?>>
			<label class="font-weight-bold" for="cus_conf_yes">งบการเงินจากแหล่งอื่นๆ</label>
		</div>
	</div>
	<div class="row ml-1 mr-1 pb-2 mt-n2">
		<div class="col-lg-6 mt-n1">
			<div class="row p-1 ">
				<div class="col-lg-12 ">
					<div class="row pr-1 pl-1 ">
						<?php 
							$params = array($crstm_dbd_yy);
							$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy where tbl_yy_desc = ?";
							$result = sqlsrv_query($conn, $sql_doc, $params);	
							$r_row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
							if ($r_row) {
								$crstm_dbd_yy = $r_row['tbl_yy_desc'];
							}			
						?>		
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">งบการเงิน ช่วงปี :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_dbd_yy; ?></div>
					</div>	
				</div>
			</div>
		</div>
		<div class="col-lg-6 mt-n1">
			<div class="row p-1 ">
				<div class="col-lg-12 ">
					<div class="row pr-1 pl-1 ">
						<?php 
							$params = array($crstm_dbd1_yy);
							$sql_doc = "SELECT tbl_yy_code, tbl_yy_desc FROM tbl_yy where tbl_yy_desc = ?";
							$result = sqlsrv_query($conn, $sql_doc, $params);	
							$r_row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);		
							if ($r_row) {
								$crstm_dbd1_yy = $r_row['tbl_yy_desc'];
							}			
						?>		
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">งบการเงิน ช่วงปี :</div>
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_dbd1_yy; ?></div>
					</div>	
				</div>
			</div>
		</div>
	</div>	
	
	<div class="card-body  my-gallery" itemscope itemtype="http://schema.org/ImageGallery">		
		<div class="row">
			<figure class="col-md-6 col-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
				<a href="<?php echo($ImgCr21) ?>" target="_blank" itemprop="contentUrl" data-size="480x360">
					<img class="img-thumbnail img-fluid" src="<?php echo($ImgCr21_icon) ?>" itemprop="thumbnail" alt="Image description" />
				</a>
			</figure>
			<figure class="col-md-6 col-12" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
				<a href="<?php echo($ImgCr22) ?>" target="_blank" itemprop="contentUrl" data-size="480x360">
					<img class="img-thumbnail img-fluid" src="<?php echo($ImgCr22_icon) ?>" itemprop="thumbnail" alt="Image description" />
				</a>
			</figure>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-12">
			<fieldset class="form-group">
				<label for="placeTextarea" class="font-weight-bold">ความเห็นสินเชื่อ #2 : <font class="text text-danger font-weight-bold"> *</font></label>
				<textarea  name="crstm_cc2_reson" id="crstm_cc2_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;"><?php echo $crstm_cc2_reson; ?></textarea>
			</fieldset>	
		</div>
		<div class="col-md-6"> 
		<div class="row pr-1 pl-1 ">
			<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">เจ้าหน้าที่สินเชื่อ :</div>
			<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $crstm_create_by_cr2."&emsp;".$crstm_create_cr2_date; ?></div>
		</div>	
		</div>
		<div class="col-md-6 ml-auto"> <!-- Left Offset -->
			<div class="form-group row">
				<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
				<div class="col-md-9">
					<a href="<?php echo($ImgCr23) ?>" target="_blank" id="linkcurrent_ImgProject1">
						<img src="<?php echo($ImgCr23_icon) ?>" border="0" id="ImgCr23" name="ImgCr23"  width="60" height="60">
					</a>	
				</div>
			</div>
		</div>
	</div>	
<?php } ?>	
<!-------------------  สิ้นสุดข้อมูลสินเชื่อคนที่ 2---------------------------->
<?php if($crstm_mgr_reson !="") { ?>
	<div class="row">	
		<div class="col-md-12">
			<fieldset class="form-group">
				<label for="placeTextarea" class="font-weight-bold">ความเห็น Manager:</label>
				<textarea  name="crstm_mgr_reson" id="crstm_mgr_reson" class="form-control input-sm font-small-3" id="placeTextarea" rows="5" style="line-height:1.5rem;"><?php echo $crstm_mgr_reson; ?></textarea>
			</fieldset>	
		</div>
		
		<div class="col-md-2">
			<input type="radio"  id="mgr_conf_yes" name="mgr_conf" value="1" <?php if($crstm_mgr_rdo=='1'){ echo "checked"; }?>>
			<label class="font-weight-bold" for="cus_conf_yes"> เห็นควรอนุมัติวงเงิน</label>
		</div>
		
		<div class="col-md-4"> 
			<!--<div class="row pr-1 pl-1">-->
				<div class="col-md-4 border-bottom"><? echo $crstm_cr_mgr; ?></div>
			<!--</div>-->
		</div>
		
		<div class="col-md-2">
			<input type="radio"  id="mgr_conf_no" name="mgr_conf" value="2" <?php if($crstm_mgr_rdo=='2'){ echo "checked"; }?>>
			<label class="font-weight-bold" for="cus_conf_yes">ไม่เห็นควรอนุมัติ</label>
		</div>
		
		<!-- Left Offset -->
		<!--<div class="col-md-6 ml-auto">    
			<div class="form-group row">
			<label class="col-md-3 label-control" for="userinput1">รูปภาพ :</label>
			<div class="col-md-9">
			<a href="<?php echo($ImgCr3) ?>" target="_blank" id="linkcurrent_ImgProject1">
			<img src="<?php echo($ImgCr3) ?>" border="0" id="ImgCr3" name="ImgCr3"  width="60" height="60">
			</a>	
			</div>
			</div>
		</div>-->
	</div>
<?php } ?>													