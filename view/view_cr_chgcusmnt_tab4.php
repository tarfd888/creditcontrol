
    <fieldset>
        <div class="bs-callout-success callout-border-left callout-bordered callout-transparent p-1 mt-1">   
            <div class="row ml-1 mr-1 pb-2 mt-n2"><!-- border border-success rounded round-lg  -->
                <div class="col-lg-6 mt-n1">
                    <div class="row p-1 ">
                        <div class="col-lg-12 ">
                            <div class="row pr-1 pl-1 ">
                                <div class="col-lg-5 col-md-6 pt-1 ">สถานะเอกสาร :</div>
                                <div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $cr_sta_complete_name; ?></div>
                            </div>	
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-n1">
                    <div class="row p-1">
                        <div class="col-lg-12">
                            <!--<h4 class="form-section text-info" ><i class="fa fa-cube"></i> Contact Information </h4>	-->
                            
                            <div class="row pr-1 pl-1 ">
                                <div class="col-lg-5 col-md-6 pt-1 ">อื่นๆ :</div>
                                <div class="col-lg-7 pt-1 border-bottom"><? echo $cr_sta_rem; ?></div>
                            </div>		
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row mt-1">
             <div class="col-md-6">
                <h6><i class="fa fa-check"></i> เอกสารประกอบที่ต้องมี</h6>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-hover table-bordered compact nowrap book_table_cr"
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
                            <input type="file" name="multiple_files_edit" id="multiple_files_edit" multiple disabled/>
                            <span class="file-custom"></span>
                            <span class="text-muted">Only jpg, png, gif, pdf, xls, doc file
                                allowed</span>
                            <span id="error_multiple_files"></span>
                        </label>
                    </div>
                </div>
                <!-- <br /> -->
                <!-- <div class="table-responsive mb-2" id="image_table_view">
                </div> -->
                <table class="table table-striped table-sm table-hover table-bordered image_table_view"
                        style="width:100%; font-size:0.89em;">
                    </table>
            </div>

            
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cr_remark">ความเห็นสินเชื่อ :</label>
                        <textarea name="cr_remark" id="cr_remark"
                            class="form-control textarea-maxlength input-sm font-small-2 border-info"
                            placeholder="Enter upto 500 characters.." maxlength="500" rows="9"
                            style="line-height:1.5rem;"><?php echo $cr_remark; ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cr_mgr_remark">ความเห็น Finance & Credit Manager :</label>
                        <textarea name="cr_mgr_remark" id="cr_mgr_remark"
                            class="form-control textarea-maxlength input-sm font-small-2 border-info"
                            placeholder="Enter upto 500 characters.." maxlength="500" rows="9"
                            style="line-height:1.5rem;"><?php echo $cr_mgr_remark; ?></textarea>
                    </div>
                </div>
    
                <div class="col-md-6"></div> 
                    <div class="col-md-3 skin skin-square">
                        <div class="form-group">
                            <input type="radio" name="cr_mgr_status" id="cr_mgr_status1" disabled value="A" <?php if($cr_mgr_status=="A"){ echo "checked"; }?>>
                            <label for="cr_mgr_status">เห็นควรอนุมัติ</label>
                        </div>
                        
                    </div>
                    <div class="col-md-3 skin skin-square">
                        <div class="form-group">
                            <input type="radio" name="cr_mgr_status" id="cr_mgr_status2" disabled value="R" <?php if($cr_mgr_status=="R"){ echo "checked"; }?>>
                            <label for="cr_mgr_status">ไม่เห็นควรอนุมัติ</label>
                        </div>
                    </div>
           
        </div>
    </fieldset>

