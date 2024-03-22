<!-- Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="msghead">Message</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id='modal-body' class="modal-body text-sm">
        <p></p>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>-->
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Modal Modal Add Attach -->
<!--<div class="modal fade text-left" id="div_frm_country_add<?php echo $country_code ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">-->
<div class="modal fade" id="imageModal">
  <div class="modal-dialog ">
    <div class="modal-content font-small-2">
      <div class="modal-header">
        <h3 class="modal-title" id="myModalLabel33"><i class="ft-file-plus"></i> เอกสารประกอบ</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="frm_image_eidt" id="frm_image_eidt" autocomplete=OFF enctype="multipart/form-data">
          <input type="hidden" name="action" value="img_edit">
          <input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
          <input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" id="image_id" name="image_id">  
          <input type="hidden" id="image_app_nbr" name="image_app_nbr"> 
          <div class="modal-body">
            <label>Picture Name : </label>
            <div class="form-group">
              <input type="text" id="image_name" name="image_name" placeholder=""
                class="form-control input-sm font-small-2" readonly>
            </div>

            <label>Description : </label>
            <div class="form-group">
              <input type="text" id="image_desc" name="image_desc" placeholder=""
                class="form-control input-sm font-small-2">
            </div>
          </div>
        </form>
      </div>
     
      <div class="modal-footer right">
          <div class="col-12 d-flex flex-sm-row flex-column justify-content-end btn-group-sm">
              <button type="button" id="btnclose" name="btnclose"
                  class="btn btn-outline-warning btn-min-width btn-glow mr-1 mb-1"
                  onclick="document.location.href='../newcust/cr_newcusmnt.php'"><i
                      class="fa fa-times"></i> Close</button>
              <button type="button" id="imagepostform"
                  class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1" data-toggle="modal><i
                      class="fa fa-check-square-o"></i> Save</button>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Add Attach -->

<!-- Modal Modal Remind Email -->
<div class="modal fade" id="div_frm_remind">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> ยืนยันส่งอีเมลเตือน</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_remind" id="frm_remind" autocomplete=OFF>
					<input type="hidden" name="action" value="remind">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
          <input type="hidden" id="cus_step_code" name="cus_step_code" value="<?php echo $cus_step_code ?>"> 
					<div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
            <div class="col-lg-12 mt-n1">
                <div class="row p-1 ">
                    <div class="col-lg-12 ">
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-4  pt-1 ">เอกสารเลขที่ :</div>
                            <div class="col-lg-8  pt-1 border-bottom"><? echo $cus_app_nbr; ?></div>
                        </div>	
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-4  pt-1 ">ชื่อจดทะเบียน :</div>
                            <div class="col-lg-8  pt-1 border-bottom"><? echo $cus_reg_nme; ?></div>
                        </div>
                        <div class="row pr-1 pl-1 ">
                            <div class="col-lg-4  pt-1 ">อีเมลแจ้งเตือน :</div>
                            <div class="col-lg-8  pt-1 border-bottom"><? echo $remind_email; ?></div>
                        </div>
                       
                    </div>
                </div>
            </div>
          </div>
						<div class="alert alert-danger" role="alert">
							<span class="text-white">Warning !!! คุณต้องการส่งอีเมลฉบับนี้เตือน ใช่หรือไหม่ ? </span>
						</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="remindmail('frm_cr_submit_app','<?php echo encrypt($cus_step_code, $key); ?>','<?php echo $cus_app_nbr; ?>','<?php echo 'cr_submit_app'; ?>')">
						<span><i class="fa fa-check-square-o"></i> Confirm</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal" ><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal remind Email -->

<div class="modal fade" id="div_frm_country_add">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-success">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> Add Country</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_country_add" id="frm_country_add" autocomplete=OFF>
					<input type="hidden" name="action" value="country_add">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					
					<div class="form-group row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Code</label>
								<input type="text" class="form-control input-sm font-small-2" name="country_code"  id="country_code"  maxlength="10">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Country Name</label>
								<input type="text" class="form-control input-sm font-small-2" name="country_desc"  id="country_desc"  maxlength="100"> 
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="countrypostform('<?php echo "frm_country_add" . $country_code; ?>')">
					<span><i class="fa fa-check-square-o"></i> Save</span>
				</button>
				<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" onclick="document.location.href='../masmnt/countrymnt.php?pg=<?php echo $pg; ?>'"><i class="ft-x"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Role -->

<!-- Modal Modal Edit Role -->
<div class="modal fade" id="div_frm_country_edit">
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-edit"></i> Edit Country</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_country_edit" id="frm_country_edit" autocomplete=OFF>
					<input type="hidden" name="action" value="country_edit">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<!-- <input type="hidden" name="country_code" value="<?php echo($country_code) ?>"> -->
					<div class="form-group  row">
						<div class="col-sm-12">
							<div class="form-group">
								<input type="hidden" class="form-control input-sm font-small-2" name="country_code"  id="country_code" value="" maxlength="30">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Code</label>
								<input type="text" class="form-control input-sm font-small-2" name="country_code"  id="country_code"  maxlength="30">
							</div>	
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Country Name</label>
								<input type="text" class="form-control input-sm font-small-2" name="country_desc"  id="country_desc"  maxlength="100"> 
							</div>
						</div>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="countrypostform('<?php echo "frm_country_edit" . $country_code; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" onclick="document.location.href='../masmnt/countrymnt.php?pg=<?php echo $pg; ?>'"><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Edit Role -->

<div class="modal fade" id="div_frm_bank_add">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-success">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> Add Bank</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_bank_add" id="frm_bank_add" autocomplete=OFF>
					<input type="hidden" name="action" value="bank_add">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					
					<div class="form-group row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Code</label>
								<input type="text" class="form-control input-sm font-small-2" name="bank_code"  id="bank_code"  maxlength="10">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Bank Name</label>
								<input type="text" class="form-control input-sm font-small-2" name="bank_th_name"  id="bank_th_name"  maxlength="100"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="bank_status" name="bank_status" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="bankpostform('<?php echo "frm_bank_add" . $bank_code; ?>')">
					<span><i class="fa fa-check-square-o"></i> Save</span>
				</button>
				<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" onclick="document.location.href='../masmnt/bankmnt.php?pg=<?php echo $pg; ?>'"><i class="ft-x"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Role -->

<!-- Modal Modal Edit Bank -->
<div class="modal fade" id="div_frm_bank_edit">
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-edit"></i> Edit Country</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_bank_edit" id="frm_bank_edit" autocomplete=OFF>
					<input type="hidden" name="action" value="bank_edit">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<!-- <input type="hidden" name="bank_code" value="<?php echo($bank_code) ?>"> -->
					<div class="form-group  row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Code</label>
								<input type="text" class="form-control input-sm font-small-2" name="bank_code"  id="bank_code"  maxlength="30">
							</div>	
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Bank Name</label>
								<input type="text" class="form-control input-sm font-small-2" name="bank_th_name"  id="bank_th_name"  maxlength="100"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="bank_status" name="bank_status" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="bankpostform('<?php echo "frm_bank_edit" . $bank_code; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" onclick="document.location.href='../masmnt/bankmnt.php?pg=<?php echo $pg; ?>'"><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Edit Bank -->

<!-- Modal Modal Reject -->
<div class="modal fade" id="div_frm_reject">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> ยกเลิกเอกสาร<?php echo($cardtxt);?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_reject" id="frm_reject" autocomplete=OFF>
					<input type="hidden" name="action" value="reject_cus">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="cus_app_nbr" value="<?php echo($cus_app_nbr) ?>">
					<input type="hidden" name="cus_step_code" value="<?php echo($cus_step_code) ?>">

					<div class="form-group row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">เอกสารเลขที่ </label>
								<input type="text" class="form-control input-sm font-small-2" name="cus_app_nbr"  id="cus_app_nbr"  value="<?php echo $cus_app_nbr ?>">
							</div>
						</div>	
						<div class="col-md-12">	
							<div class="form-group">
								<label class="text-bold-600">ลูกค้า </label>
								<input type="text" class="form-control input-sm font-small-2" name="cus_code"  id="cus_code"  value="<?php echo $cus_code.'  '.$cus_reg_nme ?>">
							</div>
						</div>	
						<div class="col-md-12">	
							<div class="form-group">
								<label class="text-bold-600">หมายเหตุ </label>
								<input type="text" class="form-control input-sm font-small-2" name="cus_reject_rem"  id="cus_reject_rem"  value="">
							</div>
						</div>
					</div>				
						<div class="alert alert-danger" role="alert">
							<span class="text-white">Warning !!! คุณต้องการยกเลิกเอกสาร<?php echo($cardtxt);?> ใช่หรือไหม่ ? </span>
						</div>
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="rejectpostform('<?php echo "frm_reject"; ?>')">
						<span><i class="fa fa-check-square-o"></i> Confirm</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal" ><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Reject -->
