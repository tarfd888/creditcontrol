
    <fieldset>
       <!--  <h6 class="form-section text-info"><i class="fa fa-picture-o"></i>
            เอกสารประกอบ</h6> -->
        <!-- <h3 class="content-header-title mb-0">Doc No. <?php echo $q; ?></h3>  -->
        <div class="row">
            <div class="col-md-6">
                <h6><i class="fa fa-check"></i> เอกสารประกอบที่ต้องมี</h6>
            </div>
            <div class="col-md-6">    
                <h6><i class="fa fa-picture-o"></i> รูปภาพเอกสารประกอบ</h6>          
            </div>
            <div class="col-md-6">
                <div class="table-responsive">
                    <table id="book_table" class="table table-striped table-sm table-hover table-bordered"
                        style="width:100%; font-size:0.89em;">
                        <thead>
                            <tr>
                                <!-- <th>No.</th> -->
                                <th>Domestic</th>
                                <th>Export</th>
                                <th>ลูกค้าทั่วไป</th>
                                <th>ลูกค้าเครือ SCG</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- <div class="row">
                    <div class="form-group col-12 mb-2">
                        <label id="projectinput8" class="file center-block">
                            <input type="file" name="multiple_files_edit" id="multiple_files_edit" multiple />
                            <span class="file-custom"></span>
                            <span class="text-muted">Only jpg, png, gif, pdf, xls, doc file
                                allowed</span>
                            <span id="error_multiple_files"></span>
                        </label>
                    </div>
                </div> -->
                <div class="table-responsive mb-2" id="image_table">
                </div>
            </div>
        </div>
    </fieldset>
