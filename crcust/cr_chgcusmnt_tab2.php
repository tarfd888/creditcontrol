
<fieldset>
        <div class="bs-callout-success callout-border-left callout-bordered callout-transparent mt-1 p-1">    
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">
                        <label>สถานะเอกสาร</label>
                        <select data-placeholder="เลือกประเภทการขออนุมัติ"
                            class="form-control input-sm border-info font-small-2 select2" id="cr_sta_complete" name="cr_sta_complete">
                            <option value="" selected>--- เลือกสถานะเอกสาร ---</option>
                            <option value="C" <?php if($cr_sta_complete =='C') {echo "selected";}?>><?php echo 'Completed';?></option>
                            <option value="I" <?php if($cr_sta_complete =='I') {echo "selected";}?>><?php echo 'Incomplete';?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                    <label for="cr_rem_other">อื่นๆ :</label>
                    <textarea name="cr_sta_rem" id="cr_sta_rem"
                        class="form-control textarea-maxlength input-sm font-small-2 border-info"
                        placeholder="Enter upto 250 characters.." maxlength="250" rows="1"
                        style="line-height:0.5rem;"><?php echo $cr_sta_rem; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-12">
                <h6><i class="fa fa-check"></i> เอกสารประกอบที่ต้องมี</h6>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="book_table_cr" class="table table-striped table-sm table-hover table-bordered compact nowrap"
                        style="width:100%; font-size:0.89em;">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Domestic</th>
                                <th>Export</th>
                                <th>ลูกค้าทั่วไป</th>
                                <th>ลูกค้าเครือ SCG</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="form-group col-12 mt-2 mb-2">
                        <label id="projectinput8" class="file center-block">
                            <input type="file" name="multiple_files_add" id="multiple_files_add" multiple />
                            <span class="file-custom"></span>
                            <span class="text-muted">Only jpg, png, gif, pdf, xls, doc file
                                allowed</span>
                            <span id="error_multiple_files"></span>
                        </label>
                    </div>
                </div>
                <!-- <br /> -->
                <div class="table-responsive mb-2" id="image_table">
                </div>
            </div>

            <?php if (inlist($user_role,"Action_View1") || inlist($user_role,"Action_View2")) { ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cr_remark">ความเห็นสินเชื่อ :</label>
                        <textarea name="cr_remark" id="cr_remark"
                            class="form-control textarea-maxlength input-sm font-small-2 border-info"
                            placeholder="Enter upto 500 characters.." maxlength="500" rows="9"
                            style="line-height:1.5rem;"></textarea>
                    </div>
                </div>
                <div class="col-md-6 showremark" style="display:none">
                    <div class="form-group">
                        <label for="cr_rem_revise">หมายเหตุ :</label>
                        <textarea name="cr_rem_revise" id="cr_rem_revise"
                            class="form-control textarea-maxlength input-sm font-small-2 border-info"
                            placeholder="Enter upto 500 characters.." maxlength="500" rows="9"
                            style="line-height:1.5rem;"></textarea>
                    </div>
                </div>

                <div class="col-md-6 disshowremark" style="display:show"></div>
               
                <div class="col-md-3 skin skin-square">
                    <div class="form-group chk_btn_submit">
                        <input type="radio" name="cr_status" id="cr_status1_chg" value="A" <?php if($cr_status=="A"){ echo "checked"; }?>>
                        <label for="cr_status1">เห็นควรอนุมัติ</label>
                    </div>
                    
                </div>
                <div class="col-md-3 skin skin-square">
                    <div class="form-group chk_btn_submit">
                        <input type="radio" name="cr_status" id="cr_status2_chg" value="R" <?php if($cr_status=="R"){ echo "checked"; }?>>
                        <label for="cr_status2">แก้ไข</label>
                    </div>
                </div>
            <?php } ?>

            <?php if (inlist($user_role,'FinCR Mgr')) { ?>
               <!--  <div class="col-md-6">
                    <div class="form-group">
                        <label for="cr_mgr_remark">ความเห็น Finance & Credit Manager :</label>
                        <textarea name="cr_mgr_remark" id="cr_mgr_remark"
                            class="form-control textarea-maxlength input-sm font-small-2 border-info"
                            placeholder="Enter upto 500 characters.." maxlength="500" rows="9"
                            style="line-height:1.5rem;"></textarea>
                    </div>
                </div>
    
                <div class="col-md-6"></div> 
                    <div class="col-md-3 skin skin-square">
                        <div class="form-group">
                            <input type="radio" name="cr_mgr_status" id="cus_tg_cust11" value="true">
                            <label for="cr_mgr_status">เห็นควรอนุมัติ</label>
                        </div>
                        
                    </div>
                    <div class="col-md-3 skin skin-square">
                        <div class="form-group">
                            <input type="radio" name="cr_mgr_status" id="cus_tg_cust22" value="false">
                            <label for="cr_mgr_status">ไม่เห็นควรอนุมัติ</label>
                        </div>
                    </div> -->
            <?php } ?>
        </div>
    </fieldset>

