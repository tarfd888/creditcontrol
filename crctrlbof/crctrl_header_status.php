	<h4 class="form-section text-info mt-n2"><i class="fa fa-cube"></i> สถานะเอกสาร</h4>
	<div class="row ml-1 mr-1 pb-2 mt-n2">
	  <!-- border border-success rounded round-lg  -->
	  <div class="col-lg-6 mt-n1">
	    <div class="row p-1 ">
	      <div class="col-lg-12 ">
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">ผู้อนุมัติ 1 :</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $app1_name; ?>
	          </div>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">สถานะเอกสาร :</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $status_app1; ?>
	          </div>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">วันที่อนุมัติ:</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $crstm_stamp_app1_date; ?>
	          </div>
	        </div>
	      </div>

	    </div>
	  </div>

	  <?php if($crstm_approve == "คณะกรรมการบริหารอนุมัติ" || $crstm_approve == "คณะกรรมการสินเชื่ออนุมัติ") { ?>
	  <div class="col-lg-6 mt-n1">
	    <div class="row p-1 ">
	      <div class="col-lg-12 ">
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">ผู้อนุมัติ 2 :</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $app2_name; ?>
	          </div>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">สถานะเอกสาร :</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $status_app2; ?>
	          </div>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">วันที่อนุมัติ:</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $crstm_stamp_app2_date; ?>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
		<div class="col-lg-6 mt-n1">
	    <div class="row p-1 ">
	      <div class="col-lg-12 ">
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">ผู้อนุมัติ 3 :</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $app3_name; ?>
	          </div>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">สถานะเอกสาร :</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $status_app3; ?>
	          </div>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">วันที่อนุมัติ:</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $crstm_stamp_app3_date; ?>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	  <?php } ?>

	  <?php if($crstm_approve == "คณะกรรมการบริหารอนุมัติ" && $crstm_fin_app_date !="") { ?>
	  <div class="col-lg-6 mt-n1">
	    <div class="row p-1 ">
	      <div class="col-lg-12 ">
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold"></div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom font-weight-bold">
	            <center>คณะกรรมการบริหารอนุมัติ</center>
	          </div>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">สถานะเอกสาร :</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $status_app3; ?>
	          </div>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">วันที่อนุมัติ:</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $crstm_fin_app_date; ?>
	          </div>
	        </div>
	      </div>

	    </div>
	  </div>
	  <?php } ?>

	  <?php if($crstm_approve == "คณะกรรมการบริหารอนุมัติ" && $crstm_fin_img !="") { ?>
	  <div class="col-lg-6 mt-n1">
	    <div class="row p-1 ">
	      <div class="col-lg-12 ">
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold"></div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <center>เอกสารลงนามคณะกรรมการบริหาร<center>
	          </div>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">ไฟล์แนบ</div>
	          <a href="<?php echo($ImgFin) ?>" target="_blank" id="linkcurrent_ImgProject">
	            <img src="<?php echo($ImgFin_icon) ?>" border="0" id="ImgFin" name="ImgFin" width="60" height="60">
	          </a>
	        </div>
	        <div class="row pr-1 pl-1 ">
	          <div class="col-lg-4 col-md-6 pt-1 font-weight-bold">วันที่อนุมัติ:</div>
	          <div class="col-lg-8 col-md-6 pt-1 border-bottom">
	            <? echo $crstm_fin_app_date; ?>
	          </div>
	        </div>
	      </div>

	    </div>
	  </div>
	  <?php } ?>
	</div>