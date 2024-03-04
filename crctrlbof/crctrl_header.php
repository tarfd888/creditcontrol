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
						<div class="col-lg-7 col-md-6 pt-1 border-bottom"><? echo $phone_mask; ?></div>
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
						<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">สถานะใบขออนุมัติ  :</div>
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
				<div class="col-md-9  pt-1 border-bottom"><?php echo $cr_cust_code. " | ".$crstm_cus_name; ?></div>
			</div>
			<div class="row">
				<div class="col-md-3 pt-1 font-weight-bold">ที่อยู่ :</div>
				<div class="col-md-9 pt-1 border-bottom"><?php echo $cus_street; ?></div>
			</div>
			
			<div class="row">
				<div class="col-md-3 pt-1 font-weight-bold">จังหวัด :</div>
				<div class="col-md-3 pt-1 border-bottom"><?php echo $cus_city; ?></div>
				<div class="col-md-3 pt-1 font-weight-bold">ประเทศ :</div>
				<div class="col-md-3 pt-1 border-bottom"><?php echo $cus_country; ?></div>
			</div>
			<div class="row">
				<div class="col-md-3 pt-1 font-weight-bold">เลขประจำตัวผู้เสียภาษี :</div>
				<div class="col-md-3 pt-1 border-bottom"><?php echo $cus_tax_nbr3; ?></div>
				<div class="col-md-3 pt-1 font-weight-bold">เงื่อนไขการชำระเงิน :</div>
				<div class="col-md-3 pt-1 border-bottom"><?php echo $cus_terms_paymnt; ?></div>
			</div>
			
		</div>
	</div>	
	
	
	<div class="row match-height detailcrc_display" > 
		<!-- Start First Column -->
		<div class="col-md-6">
			<!--<div class="card">-->
			<!--<div class="card-body collapse show">-->
			<!--<div class="card-block">-->
			<div class="table-responsive" style="padding:0px 15px 50px 20px;">
				<p style="font-size:14px;">สถานะวงเงินและหนี้ ณ วันที่ :  <?echo $stamp_date; ?></p>
				<!-- Start Datatables -->
				<!--class="table table-sm table-hover table-bordered compact nowrap-->
				<table id="" class="table table-sm table-bordered compact nowrap " style="width:100%;" > <!--dt-responsive nowrap-->
					<thead>
						<tr class="bg-success text-white font-weight-bold">								
							<th>สถานะวงเงินและหนี้ </th>
							<th class="text-center" colspan='2'>จำนวนเงิน (บาท) </th>
						</tr>
					</thead>
					<tbody>
						<?
							// ข้อมูลตารางที่ 1  ---> crctrlpost.php
							//$params = array($cr_cust_code);
							
							$params = array($crstm_nbr);
							$sql_cr= "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, ".
							"tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr WHERE (tbl1_nbr = ?) and tbl1_group='1'";
							$result = sqlsrv_query($conn, $sql_cr,$params);
							
							$tot_acc = 0;
							$sum_acc = 0;
							$tot_cc = 0;
							while($row_cr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
							{
								$tot_acc = number_format($row_cr['tbl1_amt_loc_curr']);
								$sum_acc = $row_cr['tbl1_amt_loc_curr'];
								$chk_name = $row_cr['tbl1_txt_ref'];
								$acc_name = $row_cr['tbl1_acc_name'];
								
								//echo "<tr><td >".$acc_name."</td>";
								if ($chk_name <> 'CI') {
									echo "<tr><td align='left' >".$acc_name."</td>";
									echo "<td colspan='1'></td>";
									echo "<td align='right' >".$tot_acc."</td>";	
									$tot_cc = $tot_cc + $sum_acc ;
									}else { 
									echo "<tr><td align='center'>".$acc_name."</td>";
									echo "<td align='right'>".$tot_acc."</td >";	
									echo "<td align='right' colspan='1'></td>";	
								}
								
								echo "</tr>";
							}
							$grtot_acc = $tot_cc;
							$tot_cc = number_format($tot_cc);
							
							$cc_txt = 'รวมวงเงินสินเชื่อ ';
							//if ($tot_cc <> 0) {
							echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$cc_txt."</td>";
							echo "<td align='right' style='color:blue' colspan='2' bgcolor='#f2f2f2'>".$tot_cc."</td>";
							//}
						?>
						
						<?
							// $params = array($cr_cust_code);
							// $sql_ar ="SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, sum(ar_mstr.ar_amt_loc_curr) as ar_amt ".
							// "FROM ar_mstr INNER JOIN cracc_mstr ON ar_mstr.ar_acc = cracc_mstr.cracc_customer INNER JOIN ".
							// "cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr  where cracc_mstr.cracc_acc= ? group by cracc_mstr.cracc_acc,cus_name1";
							$params = array($crstm_nbr);
							$sql_ar= "SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, tbl1_acc_name, ".
							"tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr WHERE (tbl1_nbr = ?) and tbl1_group='2'";
							
							$result = sqlsrv_query($conn, $sql_ar, $params, array("Scrollable" => 'keyset'));
							$row_counts = sqlsrv_num_rows($result);
							
							$tot_ar = 0;
							$sum_ar = 0;
							
							while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
							{
								
								//$tot_ar = number_format($row_ar['ar_amt']);
								$tot_ar = $row_ar['tbl1_amt_loc_curr'];
								if ($tot_ar < 0) {
									$tot_ar = ($tot_ar * -1);
									$tot_ar = "(".(number_format($tot_ar)).")";
									}else {
									$tot_ar = number_format($row_ar['tbl1_amt_loc_curr']);
								}
								$sum_ar = round($row_ar['tbl1_amt_loc_curr']);
								$ar_txt = 'หนี้ทั้งหมด ' ;
								echo "<tr><td>".$ar_txt."</td>";
								echo "<td colspan='1'></td>";
								echo "<td align='right'>".$tot_ar."</td>";	
								echo "</tr>";
							}
							if ($tot_ar < 0) {
								$tot_ar = "(".(number_format($tot_ar)).")";
							}	
							if ($row_counts==0 && $tot_ar==0) {     
								$tot_ar = ($tot_ar * -1);
								$ar_txt = 'หนี้ทั้งหมด  ' ;
								echo "<tr><td>".$ar_txt."</td>";
								echo "<td colspan='1'></td>";
								echo "<td align='right'>".$tot_ar."</td>";	
								echo "</tr>";
							}
						?>
						
						<?
							// $params = array($cr_cust_code);
							// $sql_ar= "SELECT cracc_mstr.cracc_acc,cus_mstr.cus_name1, ar_mstr.ar_dura_txt, sum(ar_mstr.ar_amt_loc_curr) as ar_amt ".
							// "FROM ar_mstr INNER JOIN cracc_mstr ON ar_mstr.ar_acc = cracc_mstr.cracc_customer INNER JOIN ".
							// "cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr where cracc_mstr.cracc_acc = ? ".
							// "group by cracc_mstr.cracc_acc,ar_mstr.ar_dura_txt, cus_mstr.cus_name1 ";
							$params = array($crstm_nbr);
							$sql_ar ="SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, ".
							"tbl1_acc_name, tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr ".
							"WHERE (tbl1_nbr = ?) AND (tbl1_group = '3')";
							$result = sqlsrv_query($conn, $sql_ar,$params);
							
							$tot_cur = 0;
							$sum_cur = 0;
							while($row_ar = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
							{
								//$tot_cur = number_format($row_ar['tbl1_amt_loc_curr']);
								$tot_cur = $row_ar['tbl1_amt_loc_curr'];
								if ($tot_cur < 0) {
									$tot_cur = ($tot_cur * -1);
									$tot_cur = "(".(number_format($tot_cur)).")";
									}else {
									$tot_cur = number_format($row_ar['tbl1_amt_loc_curr']);
								}
								
								$sum_cur = $row_ar['tbl1_amt_loc_curr'];
								$cur_txt = $row_ar['tbl1_txt_ref'];
								
								echo "<tr><td align='center'>".$cur_txt."</td>";
								echo "<td align='right'>".$tot_cur."</td>";	
								echo "<td align=center'></td>";	
								echo "</tr>";
							}
						?>
						
						<?
							//$params = array($cr_cust_code);
							//$sql_ord ="SELECT  ord_cr_acc, SUM(ord_mstr.ord_sales_val) AS sales_val FROM ord_mstr  WHERE (ord_cr_acc = ? ) group by ord_cr_acc";
							$params = array($crstm_nbr);
							$sql_ord ="SELECT tbl1_id, tbl1_nbr, tbl1_date, tbl1_cus_nbr, tbl1_amt_loc_curr, tbl1_doc_date, tbl1_due_date, tbl1_txt_ref, ".
							"tbl1_acc_name, tbl1_create_by, tbl1_create_date, tbl1_group, tbl1_stamp_date FROM tbl1_mstr ".
							"WHERE (tbl1_nbr = ?) AND (tbl1_group = '4')";
							$result = sqlsrv_query($conn, $sql_ord,$params);
							
							$tot_ord = 0;
							$sum_ord = 0;
							$grand_ord = 0;
							while($row_ord = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
							{
								
								$tot_ord = $row_ord['tbl1_amt_loc_curr'];
								if ($tot_ord < 0) {
									$tot_ord = ($tot_ord * -1);
									$tot_ord = "(".(number_format($tot_ord)).")";
									}else {
									$tot_ord = number_format($row_ord['tbl1_amt_loc_curr']);
								}
								
								$sum_ord = round($row_ord['tbl1_amt_loc_curr']);
								$ord_txt = 'ใบสั่งซื้อระหว่างดำเนินการ' ;
								echo "<tr><td>".$ord_txt."</td>";
								echo "<td colspan='1'></td>";
								echo "<td align='right'>".$tot_ord."</td>";	
								echo "</tr>";
							}
							
							$grand_ord = $sum_ord + $sum_ar;
							$sumgr_ord =  $sum_ord + $sum_ar;
							
							if($grand_ord < 0) {
								$grand_ord = ($grand_ord * -1);
								$grand_ord = "(".(number_format($grand_ord)).")";
								}else {
								$grand_ord = number_format($grand_ord);
								
							}	
							
							$grand_txt = 'รวมยอดใช้วงเงิน';
							echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
							echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$grand_ord."</td>";
							echo "</tr>";
							if($grtot_acc > 0) {
								$grand_lmt = $grtot_acc - $sumgr_ord ; //  ยอด $grtot_acc +
								}else {
								$grand_lmt = $sumgr_ord ; // ถ้ายอด  $grtot_acc เป็นลบ เอายอด $sumgr_org มาแสดง
							}
							if ($grand_lmt < 0) {
								$grand_txt = '(เกิน) วงเงิน';
								$grand_lmt = ($grand_lmt * -1) ;
								$grand_lmt = "(".(number_format($grand_lmt)).")";
								echo "<tr><td align='center' style='color:red' bgcolor='#f2f2f2'>".$grand_txt."</td>";
								echo "<td align='right' style='color:red' bgcolor='#f2f2f2' colspan='2'>".$grand_lmt."</td>";
								} else {
								$grand_txt = 'คงเหลือวงเงิน';
								$grand_lmt = number_format($grand_lmt);
								echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
								echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$grand_lmt."</td>";
							}
							
							$gr_per = 0;
							$grand_txt = '% การใช้วงเงิน';
							//if ($sumgr_ord > 0 && $grtot_acc > 0) {
							if ($grtot_acc > 0) {
								$gr_per = ($sumgr_ord / $grtot_acc ) * 100 ;
								} else {
								$gr_per = '0';
							}
							$gr_per = number_format($gr_per);
							echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$grand_txt."</td>";
							echo "<td align='right' style='color:blue' bgcolor='#f2f2f2' colspan='2'>".$gr_per." % </td>";
							echo "</tr>";
						?>
					</tbody>
				</table>
			</div>								
			<!--</div>-->
			<!--</div>-->
			<!--</div>-->
		</div>
		<!-- End First Column -->	
		
		<div class="col-md-6">
			<!--<div class="card">-->
			<!--<div class="card-body collapse show">-->
			<!--<div class="card-block">-->
			<div class="table-responsive" style="padding:0px 15px 50px 20px;">
				<p style="font-size:14px;">ประวัติการซื้อสินค้า 12 เดือนที่ผ่านมา ณ วันที่  <?echo $stamp1_date; ?></p>
				<!-- Start Datatables -->
				<table id="" class="table table-sm table-hover table-bordered compact nowrap" style="width:100%;" > 
					<thead class="text-center">
						<tr class="bg-warning text-white font-weight-bold">								
							<th>ปี - เดือน</th>
							<th class="text-center">ยอด Billing (บาท)</th>
						</tr>
					</thead>
					<tbody>
						<?
							$params = array($crstm_nbr);
							$sql_bll= "SELECT TOP (12) tbl2_id, tbl2_nbr, tbl2_cus_nbr, tbl2_amt_loc_curr, tbl2_doc_date, tbl2_create_by, tbl2_create_date ".
							"FROM tbl2_mstr WHERE (tbl2_nbr = ?) ORDER BY tbl2_doc_date DESC ";
							$result_bll = sqlsrv_query($conn, $sql_bll,$params);
							
							$bll_tot = 0 ;
							$no = 0 ;
							$tot_avr=0;
							$a_max = array();
							while($row_bll = sqlsrv_fetch_array($result_bll, SQLSRV_FETCH_ASSOC))
							{
								$tot_amt = $row_bll['tbl2_amt_loc_curr'];
								$tot_ord = number_format($row_bll['tbl2_amt_loc_curr']);
								$bll_ym = $row_bll['tbl2_doc_date'];
								$bll_doc_ym1 = substr($bll_ym,0,4);
								$bll_doc_ym2 = substr($bll_ym,5,2);
								$bll_yofm = $bll_doc_ym1.'-'.$bll_doc_ym2;
								$a_max[$bll_yofm] = $tot_amt;	
								if($no>=1) {
									$tot_avr += $tot_amt;
								}
								
								echo "<td align='center'>".$bll_doc_ym1.'-'.$bll_doc_ym2."</td>";
								echo "<td align='right'>".$tot_ord."</td>";									
								echo "</tr>";
								$bll_tot = $bll_tot + $tot_amt ;
								$no = $no + 1;
							}
							if ($tot_avr != 0 ) {
								$bll_avr = $tot_avr / 11 ;
							}
							if ($tot_amt>0) {
								$max_amt = max($a_max); // หาค่า max ใน array
							}
							
							$bll_tot = number_format($bll_tot);
							$bll_avr = number_format($bll_avr);
							$acc_txt = 'Total';
							$acc_avr = 'Average';
							$acc_max = 'Max';
							
							echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_txt."</td>";
							echo "<td align='right' style='color:blue' bgcolor='#f2f2f2'>".$bll_tot."</td>";
							echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_avr."</td>";
							echo "<td align='right' colspan='2' style='color:blue' bgcolor='#f2f2f2'>".$bll_avr."</td>";
							echo "<tr><td align='center' style='color:blue' bgcolor='#f2f2f2'>".$acc_max."</td>";
							echo "<td align='right' colspan='2' style='color:blue' bgcolor='#f2f2f2'>".number_format($max_amt)."</td>";
							echo "</tr>";
						?>
					</tbody>
				</table>
			</div>								
			<!--</div>-->
			<!--</div>-->
		</div>
	</div>
	
	<h4 class="form-section text-info"><i class="fa fa-shopping-cart"></i> 1. สำหรับหน่วยงานขาย (เสนอขออนุมัติวงเงินสินเชื่อ)</h4>
	<div class="row">
		<div class="col-md-3">
			<input type="radio"  id="cus_conf_no" name="cus_conf" value="0" disabled <?php if($cus_conf_yes=='0'){ echo "checked"; }?>>
			<label class="font-weight-bold" for="cus_conf_no"> วงเงินลูกค้าใหม่</label>
		</div>	
		<div class="col-md-3">
			<input type="radio" id="cus_conf_yes" name="cus_conf" value="1" disabled <?php if($cus_conf_yes=='1'){ echo "checked"; }?>>
			<label class=" font-weight-bold" for="cus_conf_yes"> วงเงินลูกค้าเก่า</label>
		</div>
	</div>
	
	<? if($cus_conf_yes =="1") {?>
		<div class="cus_display" >
			<?php } 
			else { ?>
			<div class="cus_display" style="display:none;">
			<?php } ?>	
			<div class="row">
				<div class="col-md-3">
					<input type="radio"  id="cusold_conf_yes" name="chk_rdo" value="C1" disabled <?php if($cusold_conf_yes=='C1') { echo "checked"; }?>>
					<label class="font-weight-bold" for="cusold_conf_yes"> ปรับเพิ่มวงเงิน</label>
				</div>	
				<div class="col-md-3">
					<input type="radio"  id="cusold1_conf_yes" name="chk_rdo" value="C2" disabled <?php if($cusold_conf_yes=='C2') { echo "checked"; }?>>
					<label class=" font-weight-bold" for="cusold_conf_yes"> ปรับลดวงเงิน</label>
				</div>
				<div class="col-md-3">
					<input type="radio"  id="cusold2_conf_yes" name="chk_rdo" value="C3" disabled <?php if($cusold_conf_yes=='C3') { echo "checked"; }?>>
					<label class=" font-weight-bold" for="cusold_conf_yes"> ต่ออายุวงเงิน</label>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-3">
				<input type="radio"  id="term_conf_yes" name="rdo_conf1" value="old" disabled <?php if($crstm_chk_term=='old'){ echo "checked"; }?>>
				<label class="font-weight-bold" for="cus_conf_yes">เงื่อนไขการชำระเงินเดิม</label>
			</div>
			<div class="col-md-3">
				<input type="radio"  id="chg_term_conf_yes" name="rdo_conf1" value="change" disabled <?php if($crstm_chk_term=='change'){ echo "checked"; }?>>
				<label class="font-weight-bold" for="cus_conf_yes">เปลี่ยนเงื่อนไขการชำระเงินใหม่จาก</label>
			</div>
		</div>
		
		
		<div class="term_display">
			<div class="row">
				<div class="col-md-3">
					<fieldset>
						<label for="check_same" class="font-weight-bold">เงื่อนไขการชำระเงินเดิม:</label>
					</fieldset>
				</div>
				<div class="col-md-3">
					<input type="text" id="terms_paymnt" name="terms_paymnt" class="form-control input-sm font-small-3" value="<?php echo $cus_terms_paymnt ?>">
				</div>
				<div class="col-md-2">
					<fieldset>
						<label class="font-weight-bold">โปรดระบุเพิ่ม: (ถ้ามี)</label>
					</fieldset>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<!--<label class="font-weight-bold">เปลี่ยนจาก</label>-->
						<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="term_desc_add" name="term_desc_add" disabled>
							<option value="" selected>--- เลือกเงื่อนไขการชำระเงินเพิ่ม ---</option>
							<?php
								$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
								$result_doc = sqlsrv_query($conn, $sql_doc);
								while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
								?>
								<option value="<?php echo $r_doc['term_code']; ?>" 
								<?php if ($term_desc_add == $r_doc['term_code']) {
									echo "selected";
								} ?>>
								<?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				
			</div>
		</div>
		
		
		<div class="chg_term_display">
			<div class="row">
				<div class="col-md-3">
					<fieldset>
						<label for="check_same" class="font-weight-bold">ขอเปลี่ยนเงื่อนไขการชำระเงินใหม่ จาก:</label>
					</fieldset>
				</div>
				<div class="col-md-3">
					<input type="text" id="terms_paymnt1" name="terms_paymnt1" class="form-control input-sm font-small-3" value="<?php echo $cus_terms_paymnt ?>">
				</div>
				<div class="col-md-2">
					<fieldset>
						<label class="font-weight-bold">เปลี่ยนเงื่อนไข:</label>
					</fieldset>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<select data-placeholder="Select a doc type ..." class="form-control input-sm border-warning font-small-3 select2" id="term_desc" name="term_desc" disabled>
							<option value="" selected>--- เลือกเงื่อนไขการชำระเงินใหม่ ---</option>
							<?php
								$sql_doc = "SELECT * FROM term_mstr where term_active='1' order by term_code";
								$result_doc = sqlsrv_query($conn, $sql_doc);
								while ($r_doc = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
								?>
								<option value="<?php echo $r_doc['term_code']; ?>" 
								<?php if ($term_desc == $r_doc['term_code']) {
									echo "selected";
								} ?>>
								<?php echo $r_doc['term_code']." | ".$r_doc['term_desc']; ?></option>
							<?php } ?>
						</select>
						
					</div>
				</div>
				
			</div>
		</div>
		
		<div class="form-group row">
			<!--<label class="col-md-3 label-control font-weight-bold" for="userinput1">ประมาณการณ์ขายเฉลี่ยต่อเดือน: <font class="text text-danger font-weight-bold"> *</font></label>-->
			<div class="col-lg-3 col-md-6 pt-1 font-weight-bold">ประมาณการณ์ขายเฉลี่ยต่อเดือน :</div>
			<div class="col-lg-3 col-md-6 pt-1 border-bottom"><? echo $crstm_sd_per_mm; ?>  บาท</div>
		</div>
		
		<!-- Start Table Clean Credit -->
		<div class="col-sm-8">
			<!--<div class="card">-->
			<!--<div class="card-block">-->
			<div class="table-responsive" style="width:100%">
				<!-- Start Datatables -->
				<table id="tb_ord" class="table table-sm table-hover table-bordered compact nowrap" >
					<thead class="text-center" style="background-color:#f1f1f1;">
						<tr class="bg-info text-white font-weight-bold">
							<th rowspan="2" class="align-middle">ขออนุมัติปรับวงเงินสินเชื่อ (Clean Credit)</th>
							<th colspan="2" class="align-middle">อายุวงเงิน</th>
							<th rowspan="2" class="align-middle">วงเงิน (บาท)</th>
						</tr>
						<tr class="bg-info text-white font-weight-bold" style="width:100%; line-height:30px;">
							<th>วันที่เริ่ม</th>
							<th>วันที่สิ้นสุด</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$n = 0;													
							$params = array($crstm_nbr);
							// $sql_cc= "SELECT crlimit_acc, sum(crlimit_amt_loc_curr) as amt_loc, crlimit_doc_date,crlimit_due_date,crlimit_txt_ref, crlimit_seq FROM crlimit_mstr WHERE(crlimit_acc = ? and crlimit_ref = 'CC') ".
							// "GROUP BY crlimit_acc, crlimit_doc_date,crlimit_due_date,crlimit_txt_ref, crlimit_seq order by crlimit_doc_date,crlimit_due_date";
							
							$sql_cc= "SELECT tbl3_id, tbl3_nbr, tbl3_cus_nbr, tbl3_amt_loc_curr, tbl3_doc_date, tbl3_due_date, tbl3_txt_ref, tbl3_create_by, tbl3_create_date FROM tbl3_mstr where tbl3_nbr = ? ";
							
							$result_cc = sqlsrv_query($conn, $sql_cc,$params);
							$tot_ord = 0 ;
							$rows = 0;
							while($row_cc = sqlsrv_fetch_array($result_cc, SQLSRV_FETCH_ASSOC))
							{
								$acc_txt = "วงเงินปัจจุบัน";
								$acc_tot_txt = "รวมวงเงินขออนุมัติ";
								$rows = $rows + 1;
								$tot_ord = number_format($row_cc['tbl3_amt_loc_curr']);
								$sum_ord = $row_cc['tbl3_amt_loc_curr'];
								$txt_ref = $row_cc['tbl3_txt_ref'];
								$doc_date = dmytx($row_cc['tbl3_doc_date']);
								$due_date = dmytx($row_cc['tbl3_due_date']);
								$row_seq = $row_cc['tbl3_id'];
								$acc_tot = $acc_tot + $sum_ord ;
								
								//$acc_txt = "วงเงินปัจจุบัน";
								$acc_tot_txt = "รวมวงเงินขออนุมัติ";
								if ($txt_ref == "C1") {
									$acc_txt = "เสนอขอปรับเพิ่มวงเงิน";
									} else if ($txt_ref == "C2") {
									$acc_txt = "เสนอขอปรับลดวงเงิน";
									} else if ($txt_ref == "C3") {
									$acc_txt = "เสนอขอต่ออายุวงเงิน";	
									} else {
									$acc_txt = "วงเงินปัจจุบัน";
								}
								
								$n++;																										
							?>
							<tr class="black">
								<td class="pl-1 pr-0 text-center"><?php echo $acc_txt; ?></td>
								<td class="pl-1 pr-0 text-center"><?php echo $doc_date; ?></td>	
								<td class="pl-1 pr-0 text-center"><?php echo $due_date; ?></td>
								<td class="pl-1 pr-1 text-right"><?php echo $tot_ord; ?></td>
							</tr>
							
							
						<?php }?>	
						<?php 
							//$acc_tot_app = $acc_tot;
							//$acc_tot = number_format($acc_tot);
							
							$sum_acc_tot = number_format($acc_tot);
							$acc_tot = number_format($acc_tot);
							if($cusold_conf_yes=='C1') {
								$txt_ccr = "เสนอขอปรับเพิ่มวงเงิน";
								}else if($cusold_conf_yes=='C3') {
								$txt_ccr = "เสนอขอต่ออายุวงเงิน";	
							}
						?>
						<!--<tr class="black">		
							<?php if($acc_tot<>0) { ?>
								<td align='center' colspan='3' style='color:blue'><?php echo $acc_tot_txt; ?></td>
								<td class="pl-1 pr-1 text-right" style='color:blue'><?php  echo $acc_tot; ?></td>
							<?php } ?>
						</tr>-->
						<input type="hidden" name="acc_tot" id="acc_tot" value="<?php echo $acc_tot ?>" class="form-control form-control input-sm font-small-3" style="color:green;text-align:right">
						
						<?php if ($crstm_cc_amt!="") {?>
							<?php
								/* $crstm_cc_amt = str_replace(',','', $crstm_cc_amt);
									$acc_tot = str_replace(',','', $acc_tot);
									$sum_acc_tot = $acc_tot + $crstm_cc_amt ; 
								$sum_acc_tot = number_format($sum_acc_tot); */
							?>
						<? } ?>
						
						<tr class="black">		
							<td align='center' colspan='3' style='color:blue'>รวมวงเงินขออนุมัติ</td>
							<!--<td class="pl-1 pr-1"><input type="text" name="sum_acc_tot" id="sum_acc_tot" value="<?php echo $sum_acc_tot ?>" class="form-control form-control input-sm font-small-3" style="color:green;text-align:right" readonly></td>-->
							<td class="pl-1 pr-1 text-right"><?php echo $sum_acc_tot ?></td>
						</tr>
						<?php 
							$sum_acc_tot = str_replace(',','',$sum_acc_tot);
							$crstm_cr_mgr = number_format($sum_acc_tot);
							$acc_tot_app = $sum_acc_tot;
							
						?>	
					</tbody>
				</table>
			</div>	
		</div>
		<!-- End Table Clean Credit -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-lg-3 col-md-6 pt-1 font-weight-bold">อำนาจดำเนินการอนุมัติวงเงิน :</div>
					<div class="col-lg-3 col-md-6 pt-1 border-bottom"><? echo $crstm_approve; ?></div>
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
						<input type="checkbox" class="form-control input-sm border-warning " name="crstm_noreviewer" id="crstm_noreviewer" disabled <?php if ($crstm_noreviewer==true){ echo "checked"; }?>>
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
						<div class="dis_reviewer_name">
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
							<input name="crstm_email_app1" id="crstm_email_app1" readonly value="<?php echo $crstm_email_app1 ?>" 
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
							data-ret_field_02 = "app2_name"
							data-ret_value_02 = "emp_fullnamedept"
							data-ret_type_02 = "html"
							class="form-control input-sm font-small-3 typeahead">
							
							<div class="input-group-prepend">
								<span class="input-group-text">
									<a id="buthelp"
									data-id_field_code="crstm_email_app3" 
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
						<div><span id="app3_name" name="app3_name"  class="text-danger"><?php echo $app3_name?></span></div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-md-9 label-control text-danger" for="userinput1"><?php echo $error_txt ?></label>
				</div>
			</div>				
			<!---------------->
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
						<img src="<?php echo($ImgReson_icon) ?>" border="0" id="ImgReson" name="ImgReson"  width="60" height="60">
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
							<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">รูปภาพ:</div>
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
							<div class="col-lg-5 col-md-6 pt-1 font-weight-bold">รูปภาพ:</div>
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