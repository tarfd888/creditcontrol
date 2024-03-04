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
<!-- Modal Modal Add Role -->
<!--<div class="modal fade text-left" id="div_frm_role_add<?php echo $role_user_login ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">-->
<div class="modal fade" id="div_frm_role_add">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-success">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> Add Role</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_role_add" id="frm_role_add" autocomplete=OFF>
					<input type="hidden" name="action" value="role_add">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					
					<div class="form-group row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Role User</label>
								<input type="text" class="form-control input-sm font-small-2" name="role_user_login"  id="role_user_login"  maxlength="30">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Role Desctiption</label>
								<input type="text" class="form-control input-sm font-small-2" name="role_desc"  id="role_desc"  maxlength="100"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Role Type</label>
								<select data-placeholder="Select a doc type ..." class="form-control input-sm font-small-2 select2" id="role_code" name="role_code">
									<option value="" selected>--- Select Role ---</option>
									<?php
										$sql_doc = "SELECT * FROM role_code order by role_code";
										$result_doc = sqlsrv_query($conn, $sql_doc);
										while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo $r_doc['role_code']; ?>" data-icon="fa fa-wordpress"><?php echo $r_doc['role_code']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<!-- <label class="text-bold-600">Status:</label> -->
								<select name="role_active" id="role_active" class="form-control input-sm font-small-2 select2">
									<option value="">-- Select Status --</option>
									<option value="0">NOT</option>
									<option value="1">ACTIVE</option>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status Mail</label>
								<select id="role_receive_mail" name="role_receive_mail" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="rolepostform('<?php echo "frm_role_add" . $role_user_login; ?>')">
				<span><i class="fa fa-check-square-o"></i> Save</span>
				</button>
				<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" onclick="document.location.href='../masmnt/rolemastmnt.php?pg=<?php echo $pg; ?>'"><i class="ft-x"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Role -->

<!-- Modal Modal Edit Role -->
<!--<div class="modal fade text-left" id="div_frm_role_edit<?php echo $role_user_login ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">-->
<div class="modal fade" id="div_frm_role_edit">
	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-edit"></i> Edit Role</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_role_edit" id="frm_role_edit" autocomplete=OFF>
					<input type="hidden" name="action" value="role_edit">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="role_id" value="<?php echo($role_id) ?>">
					<div class="form-group  row">
						
						<div class="col-sm-12">
							<div class="form-group">
								<input type="hidden" class="form-control input-sm font-small-2" name="role_id"  id="role_id" value="" maxlength="30">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Role User</label>
								<input type="text" class="form-control input-sm font-small-2" name="role_user_login"  id="role_user_login"  maxlength="30">
							</div>	
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Role Desctiption</label>
								<input type="text" class="form-control input-sm font-small-2" name="role_desc"  id="role_desc"  maxlength="100"> 
							</div>
						</div>
						
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Role Type</label>								
								<select id="role_code" name="role_code"  class="form-control form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<?php
										$sql_role = "SELECT role_code FROM role_code order by role_code";
										$result_role_list = sqlsrv_query($conn, $sql_role);
										while ($r_role_list = sqlsrv_fetch_array($result_role_list, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo trim($r_role_list['role_code']); ?>">
											<?php echo $r_role_list['role_code']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="role_active" name="role_active" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status Mail</label>
								<select id="role_receive_mail" name="role_receive_mail" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="rolepostform('<?php echo "frm_role_edit" . $role_user_login; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" onclick="document.location.href='../masmnt/rolemastmnt.php?pg=<?php echo $pg; ?>'"><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Edit Role -->

<!-- Modal Modal Edit Payment terme -->
<div class="modal fade" id="div_frm_term_edit">
	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-edit"></i> Edit Payment term</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_term_edit" id="frm_term_edit" autocomplete=OFF>
					<input type="hidden" name="action" value="term_edit">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<div class="form-group  row">
						
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Term Code</label>
								<input type="text" class="form-control input-sm font-small-2" name="term_code"  id="term_code"  maxlength="30">
							</div>	
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">Term Desctiption</label>
								<input type="text" class="form-control input-sm font-small-2" name="term_desc"  id="term_desc"  maxlength="100"> 
							</div>
						</div>
						
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="term_active" name="term_active" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="term_group" name="term_group" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="Domestic" >Domestic</option>
									<option value="Export" >Export</option>
								</select>
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="termpostform('<?php echo "frm_term_edit" ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" onclick="document.location.href='../masmnt/termmstrmnt.php?pg=<?php echo $pg; ?>'"><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Edit Payment term -->

<!-- Modal Modal Add Credit Control crctrladd.php -->
<div class="modal fade" id="div_frm_cc_add">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-success">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> Add Credit Control</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_cc_add" id="frm_cc_add" autocomplete=OFF>
					<input type="hidden" name="action" value="cc_add">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="cr_cust_code" value="<?php echo $cr_cust_code ?>">
					<input type="hidden" name="cus_conf_yes" id="cus_conf_yes" value="<?php echo $cus_conf_yes ?>">
					<input type="hidden" name="cusold_conf_yes" id="cusold_conf_yes" value="<?php echo $cusold_conf_yes ?>">
					
					<div class="form-group  row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วงเงินลูกค้าเก่า</label>
								<input type="text" class="form-control input-sm font-small-2" name="txt_cc"  id="txt_cc" >
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วันที่เริ่ม</label>
								<input type="text" class="form-control input-sm font-small-2" name="beg_date"  id="beg_date" placeholder="ระบุวันที่เริ่ม"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วันที่สิ้นสุด</label>
								<input type="text" class="form-control input-sm font-small-2" name="end_date"  id="end_date" placeholder="ระบุวันที่สิ้นสุด"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วงเงิน</label>
								<a title="ระบุเป็นจำนวนเลข"><input type="text" class="form-control input-sm font-small-2" name="cc_amt"  id="cc_amt" style="color:black;text-align:right"  onkeyup="format(this)" onchange="format(this)"></a>
								<!--<a title="ระบุเป็นจำนวนเลข"><input type="text" class="form-control " name="cc_amt"  id="cc_amt" style="color:black;text-align:right"  onkeyup="format(this)" onchange="format(this)" onblur="if(this.value.indexOf('.')==-1)this.value=this.value+'.00'"></a>--> 
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="ccpostform('<?php echo "frm_cc_add"; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal" ><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Credit Control crctrladd.php -->

<!-- Modal Modal Edit Credit Control crctrladd.php -->
<div class="modal fade" id="div_frm_cc_edit">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> Edit Credit Control</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_cc_add" id="frm_cc_edit" autocomplete=OFF>
					<input type="hidden" name="action" value="cc_edit">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="cr_cust_code" value="<?php echo $cr_cust_code ?>">
					<input type="hidden" name="row_seq" id="row_seq" value="<?php echo $row_seq ?>">
					<input type="hidden" name="crstm_nbr" id="crstm_nbr" value="<?php echo $crstm_nbr ?>">
					<input type="hidden" name="cus_conf_yes" id="cus_conf_yes" value="<?php echo $cus_conf_yes ?>">
					<input type="hidden" name="cusold_conf_yes" id="cusold_conf_yes" value="<?php echo $cusold_conf_yes ?>">
					<input type="hidden" name="term_conf_yes" id="term_conf_yes" value="<?php echo $term_conf_yes ?>">
					<input type="hidden" name="crstm_sd_per_mm" id="crstm_sd_per_mm" value="<?php echo $crstm_sd_per_mm ?>">
					<input type="hidden" name="phone_mask"  value="<?php echo($phone_mask) ?>">
					
					<div class="form-group  row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วงเงินลูกค้าเก่า</label>
								<!--<input type="text" class="form-control " name="txt_cc"  id="txt_cc" >-->
								<select data-placeholder="Select a doc type ..." class="form-control input-sm font-small-2 border-warning select2" id="txt_ref" name="txt_ref" readonly>
									<option value="" selected>--- เลือก ---</option>
									<?php
										$sql_doc = "SELECT * FROM adj_mstr where adj_active=1 order by adj_code";
										$result_doc = sqlsrv_query($conn, $sql_doc);
										while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo $r_doc['adj_code']; ?>" 
										<?php if ($txt_ref  == $r_doc['adj_code']) {
											echo "selected";
										} ?>>
										<?php echo $r_doc['adj_desc']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วันที่เริ่ม</label>
								<input type="text" class="form-control input-sm font-small-2" name="edit_beg_date"  id="edit_beg_date" value="<?php echo $edit_beg_date ?>" placeholder="ระบุวันที่เริ่ม" readonly> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วันที่สิ้นสุด</label>
								<input type="text" class="form-control input-sm font-small-2" name="edit_end_date"  id="edit_end_date" value="<?php echo $edit_end_date ?>" placeholder="ระบุวันที่สิ้นสุด"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วงเงิน</label>
								<a title="ระบุเป็นจำนวนเลข"><input type="text" class="form-control input-sm font-small-2" name="cc_amt"  id="cc_amt" style="color:black;text-align:right" readonly onkeyup="format(this)" onchange="format(this)"></a> 
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="ccpostform('<?php echo "frm_cc_edit"; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal" ><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Edit Credit Control crctrladd.php -->

<!-- Modal Modal Edit Credit Control crctrledit.php -->
<div class="modal fade" id="div_frm_cc_edit1">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> Edit Credit Control</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_cc_add" id="frm_cc_edit1" autocomplete=OFF>
					<input type="hidden" name="action" value="editcc_edit">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="cr_cust_code" value="<?php echo $cr_cust_code ?>">
					<input type="hidden" name="row_seq" id="row_seq" value="<?php echo $row_seq ?>">
					<input type="hidden" name="crstm_nbr" id="crstm_nbr" value="<?php echo $crstm_nbr ?>">
					
					<div class="form-group  row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วงเงินลูกค้าเก่า</label>
								<!--<input type="text" class="form-control " name="txt_cc"  id="txt_cc" >-->
								<select data-placeholder="Select a doc type ..." class="form-control input-sm font-small-2 border-warning select2" id="txt_ref" name="txt_ref">
									<option value="" selected>--- เลือก ---</option>
									<?php
										$sql_doc = "SELECT * FROM adj_mstr  where adj_active=1 order by adj_code";
										$result_doc = sqlsrv_query($conn, $sql_doc);
										while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo $r_doc['adj_code']; ?>" 
										<?php if ($txt_ref  == $r_doc['adj_code']) {
											echo "selected";
										} ?>>
										<?php echo $r_doc['adj_desc']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วันที่เริ่ม</label>
								<input type="text" class="form-control input-sm font-small-2" name="edit1_beg_date"  id="edit1_beg_date" value="<?php echo $edit1_beg_date ?>" placeholder="ระบุวันที่เริ่ม"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วันที่สิ้นสุด</label>
								<input type="text" class="form-control input-sm font-small-2" name="edit1_end_date"  id="edit1_end_date" value="<?php echo $edit1_end_date ?>" placeholder="ระบุวันที่สิ้นสุด"> 
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">วงเงิน</label>
								<a title="ระบุเป็นจำนวนเลข"><input type="text" class="form-control input-sm font-small-2" name="cc_amt"  id="cc_amt" style="color:black;text-align:right"  onkeyup="format(this)" onchange="format(this)"></a> 
								<!--<a title="ระบุเป็นจำนวนเลข"><input type="text" class="form-control " name="cc_amt"  id="cc_amt" style="color:black;text-align:right"  onkeyup="format(this)" onchange="format(this)" onblur="if(this.value.indexOf('.')==-1)this.value=this.value+'.00'"></a>-->
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="ccpostform('<?php echo "frm_cc_edit1"; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal" ><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Edit Credit Control crctrledit.php -->

<!-- Modal Modal Add Rearward -->
<div class="modal fade" id="div_frm_rearward">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-success">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> Add Comment</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_rearward" id="frm_rearward" autocomplete=OFF>
					<input type="hidden" name="action" value="remark_add">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="crstm_nbr" value="<?php echo($crstm_nbr) ?>">
					<input type="hidden" name="crstm_cus_name" value="<?php echo($crstm_cus_name) ?>">
					<input type="hidden" name="crstm_step_code" value="<?php echo($crstm_step_code) ?>">
					
					<div class="form-group  row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">ระบุเหตุผลในการ Rearward </label>
								<textarea  name="crstm_rem_rearward" id="crstm_rem_rearward" class="form-control input-sm font-small-2"  id="placeTextarea" rows="3" placeholder="ระบุเหตุผล" style="line-height:1.5rem;"></textarea>
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="rearwardpostform('<?php echo "frm_rearward"; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal" ><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Add Rearward -->

<!-- Modal Modal Reject -->
<div class="modal fade" id="div_frm_reject">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> ยืนยันการยกเลิก</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_reject" id="frm_reject" autocomplete=OFF>
					<input type="hidden" name="action" value="reject">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="crstm_nbr" value="<?php echo($crstm_nbr) ?>">
					<input type="hidden" name="crstm_cus_name" value="<?php echo($crstm_cus_name) ?>">
					<input type="hidden" name="crstm_step_code" value="<?php echo($crstm_step_code) ?>">
					<div class="form-group row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">ใบขออนุมัติวงเงินเลขที่ </label>
								<input type="text" class="form-control input-sm font-small-2" name="crstm_nbr"  id="crstm_nbr"  value="<?php echo $crstm_nbr ?>">
							</div>
						</div>	
						<div class="col-md-12">	
							<div class="form-group">
								<label class="text-bold-600">ลูกค้า </label>
								<input type="text" class="form-control input-sm font-small-2" name="crstm_cus_name"  id="crstm_cus_name"  value="<?php echo $crstm_cus_name ?>">
							</div>
						</div>	
					</div>				
						<div class="alert alert-danger" role="alert">
							<span class="text-white">Warning !!! คุณต้องการยกเลิกใบขออนุมัติวงเงิน ใช่หรือไหม่ ? </span>
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

<!-- Modal Modal Recall Email -->
<div class="modal fade" id="div_frm_recall">	
	<div class="modal-dialog ">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> ยืนยันดึงอีเมลกลับ</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_recall" id="frm_recall" autocomplete=OFF>
					<input type="hidden" name="action" value="recall">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="crstm_nbr" value="<?php echo($crstm_nbr) ?>">
					<input type="hidden" name="crstm_cus_name" value="<?php echo($crstm_cus_name) ?>">
					<input type="hidden" name="crstm_step_code" value="<?php echo($crstm_step_code) ?>">
					<div class="form-group row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="text-bold-600">ใบขออนุมัติวงเงินเลขที่ </label>
								<input type="text" class="form-control input-sm font-small-2" name="crstm_nbr"  id="crstm_nbr"  value="<?php echo $crstm_nbr ?>">
							</div>
						</div>	
						<div class="col-md-12">	
							<div class="form-group">
								<label class="text-bold-600">ลูกค้า </label>
								<input type="text" class="form-control input-sm font-small-2" name="crstm_cus_name"  id="crstm_cus_name"  value="<?php echo $crstm_cus_name ?>">
							</div>
						</div>	
					</div>				
						<div class="alert alert-danger" role="alert">
							<span class="text-white">Warning !!! คุณต้องการดึงเอกสารฉบับนี้ กลับมาแก้ไข ใช่หรือไหม่ ? </span>
						</div>
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="recallpostform('<?php echo "frm_recall"; ?>')">
						<span><i class="fa fa-check-square-o"></i> Confirm</span>
					</button>
					<button type="reset" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal" ><i class="ft-x"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal Recall Email -->

<!-- ลูกค้าทั่วไป (Tiles) -->
<div class="modal fade" id="div_frm_autho_add">	
	<div class="modal-dialog modal-lg">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-success">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> Add (อนก. ลูกค้าทั่วไป)</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_autho_add" id="frm_autho_add" autocomplete=OFF>
					<input type="hidden" name="action" value="autho_add">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="account_group" value="ZC01">
					
					<div class="form-group  row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">ชื่อ-สกุล</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_sign_nme"  id="author_sign_nme"  maxlength="150">
							</div>	
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">อีเมล</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_email"  id="author_email"  maxlength="50"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">ชื่อย่อ(ที่แสดงในแบบฟอร์ม)</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_sign"  id="author_sign"  maxlength="255"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">ตำแหน่งในแบบฟอร์ม</label>
								<!-- <input type="text" class="form-control font-small-3" name="author_code"  id="author_code"  maxlength="255">  -->
								<select id="author_code" name="author_code"  class="form-control form-control-md input-sm font-small-2 select2">
								<option value="">--Select--</option>
									<?php
										$sql = "SELECT position_desc FROM position_mstr order by position_code";
										$result_list = sqlsrv_query($conn, $sql);
										while ($r_list = sqlsrv_fetch_array($result_list, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo trim($r_list['position_desc']); ?>">
											<?php echo $r_list['position_desc']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="font-weight-bold">อำนาจดำเนินการอนุมัติ</label>								
								<select id="author_text" name="author_text"  class="form-control form-control-md input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<?php
										$sql_autho = "SELECT author_code,author_text FROM author_text_mstr order by author_code";
										$result_autho_list = sqlsrv_query($conn, $sql_autho);
										while ($r_autho_list = sqlsrv_fetch_array($result_autho_list, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo trim($r_autho_list['author_text']); ?>">
											<?php echo $r_autho_list['author_text']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">เรียน(ที่แสดงใน Email)</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_salutation"  id="author_salutation"  maxlength="255"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">วงเงินอนุมัติตั้งแต่</label>
								<input type="text" class="form-control input-sm font-small-2" name="financial_amt_beg"  id="financial_amt_beg"  onkeyup="format(this)" onchange="format(this)"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">วงเงินอนุมัติถึง</label>
								<input type="text" class="form-control input-sm font-small-2" name="financial_amt_end"  id="financial_amt_end"  onkeyup="format(this)" onchange="format(this)"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="author_email_status" name="author_email_status" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="font-weight-bold">Status Mail</label>
								<select id="author_active" name="author_active" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-wight-bold">Remark</label>
								<textarea  id="author_remark" name="author_remark" class="form-control textarea-maxlength input-sm font-small-2 border-warning" placeholder="Enter upto 500 characters.." maxlength="500"  rows="5" style="line-height:1.5rem;"><?php echo $author_remark; ?></textarea>
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="authorpostform('<?php echo "frm_autho_add" . $author_id; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="button" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ลูกค้าทั่วไป (ในเครือ) -->
<div class="modal fade" id="div_frm_autho_affi_add">	
	<div class="modal-dialog modal-lg">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-success">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-file-plus"></i> Add (อนก. ลูกค้าในเครือ)</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_autho_affi_add" id="frm_autho_affi_add" autocomplete=OFF>
					<input type="hidden" name="action" value="autho_add">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="account_group" value="DREP">
					
					<div class="form-group  row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">ชื่อ-สกุล</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_sign_nme"  id="author_sign_nme"  maxlength="150">
							</div>	
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">อีเมล</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_email"  id="author_email"  maxlength="50"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">ชื่อย่อ(ที่แสดงในแบบฟอร์ม)</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_sign"  id="author_sign"  maxlength="255"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">ตำแหน่งในแบบฟอร์ม</label>
								<!-- <input type="text" class="form-control font-small-3" name="author_code"  id="author_code"  maxlength="255">  -->
								<select id="author_code" name="author_code"  class="form-control form-control-md input-sm font-small-2 select2">
								<option value="">--Select--</option>
									<?php
										$sql = "SELECT position_desc FROM position_mstr order by position_code";
										$result_list = sqlsrv_query($conn, $sql);
										while ($r_list = sqlsrv_fetch_array($result_list, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo trim($r_list['position_desc']); ?>">
											<?php echo $r_list['position_desc']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="font-weight-bold">อำนาจดำเนินการอนุมัติ</label>								
								<select id="author_text" name="author_text"  class="form-control form-control-md input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<?php
										$sql_autho = "SELECT author_code,author_text FROM author_text_mstr order by author_code";
										$result_autho_list = sqlsrv_query($conn, $sql_autho);
										while ($r_autho_list = sqlsrv_fetch_array($result_autho_list, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo trim($r_autho_list['author_text']); ?>">
											<?php echo $r_autho_list['author_text']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">เรียน(ที่แสดงใน Email)</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_salutation"  id="author_salutation"  maxlength="255"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">วงเงินอนุมัติตั้งแต่</label>
								<input type="text" class="form-control input-sm font-small-2" name="financial_amt_beg"  id="financial_amt_beg"  onkeyup="format(this)" onchange="format(this)" > 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">วงเงินอนุมัติถึง</label>
								<input type="text" class="form-control input-sm font-small-2" name="financial_amt_end"  id="financial_amt_end"  onkeyup="format(this)" onchange="format(this)" > 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="author_email_status" name="author_email_status" class="form-control font-small-3 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="font-weight-bold">Status Mail</label>
								<select id="author_active" name="author_active" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-wight-bold">Remark</label>
								<textarea  id="author_remark" name="author_remark" class="form-control textarea-maxlength input-sm font-small-3 border-warning" placeholder="Enter upto 500 characters.." maxlength="500"  rows="5" style="line-height:1.5rem;"><?php echo $author_remark; ?></textarea>
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="authorpostform('<?php echo "frm_autho_affi_add" . $author_id; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="button" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!--<div class="modal fade text-left" id="div_frm_role_edit<?php echo $role_user_login ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">-->
<div class="modal fade" id="div_frm_autho_edit">
	
	<div class="modal-dialog modal-lg">
		<div class="modal-content font-small-2">
			<div class="modal-header bg-warning">
				<h4 class="modal-title white" id="myModalLabel33"><i class="ft-edit"></i> Edit</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_autho_edit" id="frm_autho_edit" autocomplete=OFF>
					<input type="hidden" name="action" value="autho_edit">
					<input type="hidden" name="csrf_securecode" value="<?php echo $csrf_securecode ?>">
					<input type="hidden" name="csrf_token" value="<?php echo md5($csrf_token) ?>">
					<input type="hidden" name="author_id" id="author_id" value="<?php echo($author_id) ?>">
					<input type="hidden" name="author_group" id="author_group" value="<?php echo($author_group) ?>">
					<div class="form-group  row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">ชื่อ-สกุล</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_sign_nme"  id="author_sign_nme"  maxlength="150">
							</div>	
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">อีเมล</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_email"  id="author_email"  maxlength="50"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">ชื่อย่อ(ที่แสดงในแบบฟอร์ม)</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_sign"  id="author_sign"  maxlength="255"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">ตำแหน่งในแบบฟอร์ม</label>
								<!-- <input type="text" class="form-control font-small-3" name="author_code"  id="author_code"  maxlength="255">  -->
								<select id="author_code" name="author_code"  class="form-control form-control-md input-sm font-small-2 select2">
								<option value="">--Select--</option>
									<?php
										$sql = "SELECT position_desc FROM position_mstr order by position_code";
										$result_list = sqlsrv_query($conn, $sql);
										while ($r_list = sqlsrv_fetch_array($result_list, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo trim($r_list['position_desc']); ?>">
											<?php echo $r_list['position_desc']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="font-weight-bold">อำนาจดำเนินการอนุมัติ</label>								
								<select id="author_text" name="author_text"  class="form-control form-control-md input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<?php
										$sql_autho = "SELECT author_code,author_text FROM author_text_mstr order by author_code";
										$result_autho_list = sqlsrv_query($conn, $sql_autho);
										while ($r_autho_list = sqlsrv_fetch_array($result_autho_list, SQLSRV_FETCH_ASSOC)) {
										?>
										<option value="<?php echo trim($r_autho_list['author_text']); ?>">
											<?php echo $r_autho_list['author_text']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">เรียน(ที่แสดงใน Email)</label>
								<input type="text" class="form-control input-sm font-small-2" name="author_salutation"  id="author_salutation"  maxlength="255"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">วงเงินอนุมัติตั้งแต่</label>
								<input type="text" class="form-control input-sm font-small-2" name="financial_amt_beg"  id="financial_amt_beg"  onkeyup="format(this)" onchange="format(this)"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="text-bold-600">วงเงินอนุมัติถึง</label>
								<input type="text" class="form-control input-sm font-small-2" name="financial_amt_end"  id="financial_amt_end"  onkeyup="format(this)" onchange="format(this)"> 
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="font-weight-bold">Status</label>
								<select id="author_email_status" name="author_email_status" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="font-weight-bold">Status Mail</label>
								<select id="author_active" name="author_active" class="form-control input-sm font-small-2 select2">
									<option value="">--Select--</option>
									<option value="0" >Not</option>
									<option value="1" >Active</option>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="font-wight-bold">Remark</label>
								<textarea  id="author_remark" name="author_remark" class="form-control textarea-maxlength input-sm font-small-2 border-warning" placeholder="Enter upto 500 characters.." maxlength="500"  rows="5" style="line-height:1.5rem;"><?php echo $author_remark; ?></textarea>
							</div>
						</div>
					</div>
					<!--<div class="alert alert-success" role="alert">
						<span class="text-bold-600">Well done!</span> You clicked the button save.
					</div>-->
					
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success btn-min-width btn-glow btn-sm mr-1 mb-1" data-toggle="modal" onclick="authorpostform('<?php echo "frm_autho_edit" . $author_id; ?>')">
						<span><i class="fa fa-check-square-o"></i> Save</span>
					</button>
					<button type="button" class="btn btn-outline-warning btn-min-width btn-glow btn-sm mr-1 mb-1" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>