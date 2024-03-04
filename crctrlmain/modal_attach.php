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
<!--<div class="modal fade text-left" id="div_frm_role_add<?php echo $role_user_login ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">-->
<div class="modal fade" id="div_frm_attach">	
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> เอกสารแนบการลงนามคณะกรรมการ</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_attach_add" id="frm_attach_add" autocomplete=OFF enctype="multipart/form-data">
					<input type="hidden" name="action" value="attach_add">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
						<div class="form-group row">
							<div class="col-md-6">	
								<label id="projectinput8" class="file center-block">
								<input type="file" accept="" name="load_att_img" id="load_att_img">
								<input type="hidden" name="crstm_att_img" id="crstm_att_img" value="<?php echo $crstm_att_img ?>">
								<span class="file-custom"></span>
								</label>
							</div>
						</div>	
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="attachpostform('<?php echo "frm_attach_add" . $load_att_img; ?>')">
					<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<!--<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1 close" onclick="document.location.href='../crctrlbof/crctrlall_stamp.php'"><i class="ft-x"></i> Cancel</button>-->
				</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Attach -->

<!-- Modal Modal Add Reviewer -->
<div class="modal fade" id="div_frm_rev_add">
	
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-edit"></i> Add Reviewer</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_rev_add" id="frm_rev_add" autocomplete=OFF>
					<input type="hidden" name="action" value="rev_add">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="emp_user_id" value="<?php echo($emp_user_id) ?>">
					<div class="form-group row">
						
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">ชื่อ</label>
								<input type="text" class="form-control " name="emp_th_firstname"  id="emp_th_firstname"  maxlength="30">
							</div>	
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">นามสกุล</label>
								<input type="text" class="form-control " name="emp_th_lastname"  id="emp_th_lastname"  maxlength="100"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">ตำแหน่ง</label>
								<input type="text" class="form-control " name="emp_th_pos_name"  id="emp_th_pos_name"  maxlength="100"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">อีเมล</label>
								<input type="text" class="form-control " name="emp_email_bus"  id="emp_email_bus"  maxlength="100"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="emp_flag" name="emp_flag" class="form-control font-small-3 select2">
									<option value="">--Select--</option>
									<option value="1" >Tiles</option>
									<option value="2" >Geoluxe</option>
								</select>
							</div>
						</div>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="revpostform('<?php echo "frm_rev_add" . $emp_user_id; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" onclick="document.location.href='../masmnt/reviewermnt.php?pg=<?php echo $pg; ?>'"><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Reviewer -->

<!-- Modal Modal Edit Reviewer -->
<div class="modal fade" id="div_frm_rev_edit">
	
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-edit"></i> Edit Reviewer</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_rev_edit" id="frm_rev_edit" autocomplete=OFF>
					<input type="hidden" name="action" value="rev_edit">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" id="emp_person_id" name="emp_person_id" value="<?php echo($emp_person_id) ?>">
					<div class="form-group  row">
						
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">ชื่อ</label>
								<input type="text" class="form-control " name="emp_th_firstname"  id="emp_th_firstname"  maxlength="30">
							</div>	
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">นามสกุล</label>
								<input type="text" class="form-control " name="emp_th_lastname"  id="emp_th_lastname"  maxlength="100"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">ตำแหน่ง</label>
								<input type="text" class="form-control " name="emp_th_pos_name"  id="emp_th_pos_name"  maxlength="100"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">อีเมล</label>
								<input type="text" class="form-control " name="emp_email_bus"  id="emp_email_bus"  maxlength="100"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="emp_flag" name="emp_flag" class="form-control font-small-3 select2">
									<option value="">--Select--</option>
									<option value="1" >Tiles</option>
									<option value="2" >Geoluxe</option>
								</select>
							</div>
						</div>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="revpostform('<?php echo "frm_rev_edit" . $emp_person_id; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" onclick="document.location.href='../masmnt/reviewermnt.php?pg=<?php echo $pg; ?>'"><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Edit Reviewer -->
