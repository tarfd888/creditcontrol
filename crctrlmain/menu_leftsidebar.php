<div class="main-menu menu-fixed menu-dark menu-accordion  menu-shadow" data-scroll-to-active="true">
	<div class="main-menu-content">
		<ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
				<li class="nav-item"><a href="index.html"><i class="ft-layers"></i><span class="menu-title" data-i18n="nav.dash.main">ขออนุมัติวงเงิน </span></a>
					<ul class="menu-content">
						<?php if (inlist($user_role,"SALE_VIEW")) { ?>
							<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlbof/crctrladd.php?action_cus=<?php echo encrypt("Old", $key); ?>">ลูกค้าปัจจุบัน (มีรหัสลูกค้า)</a></li>
							<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlbof/crctrladd_new.php?action_cus=<?php echo encrypt("New", $key); ?>">ลูกค้าใหม่ (ไม่มีรหัสลูกค้า)</a></li>
						<? } ?>		
						<?php if (inlist($user_role,"ADMIN")) { ?>
							<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlbof/crctrlall_stamp.php" data-i18n="nav.dash.project">ลงนามคณะกรรมการบริหาร</a></li>
						<? } ?>	
							<!--<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlbof/crctrlall_app_reviewer.php" data-i18n="nav.dash.project">เอกสารขออนุมัติ</a></li>-->
					</ul>
				</li>
				<li class="nav-item"><a href="index.html"><i class="icon-direction"></i><span class="menu-title" data-i18n="nav.dash.main">ลูกค้าใหม่ & สาขาใหม่ </span></a>
					<ul class="menu-content">
						<ul class="menu-content">
							<li><a class="menu-item" href="<?php echo BASE_DIR;?>/newcust/newcusmnt.php?action_cus=<?php echo encrypt("c1", $key); ?>">แต่งตั้งลูกค้าใหม่</a></li>
						</ul>
						<ul class="menu-content">
							<li><a class="menu-item" href="<?php echo BASE_DIR;?>/newcust/newcusmnt.php?action_cus=<?php echo encrypt("c2", $key); ?>">แต่งตั้งร้านสาขา </a></li>
						</ul>
						<ul class="menu-content">
							<li><a class="menu-item" href="<?php echo BASE_DIR;?>/newcust/chgcusmnt.php?action_cus=<?php echo encrypt("c3", $key); ?>">เปลี่ยนแปลงข้อมูลลูกค้า </a></li>
						</ul>							
						<ul class="menu-content">
							<li><a class="menu-item" href="<?php echo BASE_DIR;?>/newcust/newcust_list.php">ดูสถานะข้อมูลลูกค้า </a></li>
						</ul>	
					</ul>
				</li>
				<li class="nav-item"><a href="index.html"><i class="fa fa-download"></i><span class="menu-title" data-i18n="nav.dash.main">ดาวน์โหลดแบบฟอร์ม</span></a>
					<ul class="menu-content">
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/dl_form.php?isDom=<?php echo encrypt("1", $key); ?>" data-i18n="nav.templates.vert.main">แบบฟอร์มลูกค้าในประเทศ</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/dl_form.php?isDom=<?php echo encrypt("2", $key); ?>" data-i18n="nav.templates.vert.main">แบบฟอร์มลูกค้าต่างประเทศ</a></li>
					</ul>
				</li>

			<?php if (inlist($user_role,"Action_View1") || inlist($user_role,"Action_View2") || inlist($user_role,"ADMIN")) { ?>
				<li class=" nav-item"><a href="index.html"><i class="fa fa-cloud-upload"></i><span class="menu-title" data-i18n="nav.dash.main">Upload Data</span></a>
					<ul class="menu-content">
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlupload/upload_cus.php" data-i18n="nav.dash.project">Customer Master</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlupload/upload_acc.php" data-i18n="nav.dash.project">Credit Account Master </a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlupload/upload_crlmt.php" data-i18n="nav.dash.project">Credit Limit Master</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlupload/upload_opord.php" data-i18n="nav.dash.project">Open Order Master </a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlupload/upload_bill.php" data-i18n="nav.dash.project">Billing Master </a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlupload/upload_ar.php" data-i18n="nav.dash.project">AR Master </a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlupload/upload_riskmstr.php" data-i18n="nav.dash.project">Risk Categories </a></li>
					</ul>
					</li>	
			<? } ?>	
			<?php if (inlist($user_role,"Action_View1") || inlist($user_role,"Action_View2") || inlist($user_role,"ADMIN") || inlist($user_role,"Display_View")) { ?>		
				<li class=" nav-item"><a href="index.html"><i class="icon-list"></i><span class="menu-title" data-i18n="nav.dash.main">Report</span></a>
					<ul class="menu-content">
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlbof/displaycredit.php" data-i18n="nav.dash.project">ข้อมูลเครดิตลูกค้า</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/crctrlbof/crctrlexport_excel.php" data-i18n="nav.dash.project">สรุปรายการขออนุมัติวงเงิน</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/newcust/newcusexport_excel.php" data-i18n="nav.dash.project">สรุปรายการแต่งตั้งลูกค้าใหม่</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/newcust/risk_list.php" data-i18n="nav.dash.project">สรุปรายการ Risk Categories</a></li>
					</ul>
				</li>	
			<? } ?>		
			<?php if (inlist($user_role,"Action_View1") || inlist($user_role,"Action_View2") || inlist($user_role,"ADMIN")) { ?>
				<li class=" nav-item"><a href="index.html"><i class="fa fa-envira"></i><span class="menu-title" data-i18n="nav.dash.main">Setting</span></a>
					<ul class="menu-content">
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/rolemastmnt.php" data-i18n="nav.dash.project">Roles</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/termmstrmnt.php" data-i18n="nav.dash.project">Payment Term</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/reviewermnt.php" data-i18n="nav.dash.project">Reviewer Master</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/countrymnt.php" data-i18n="nav.dash.project">Country Master</a></li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/bankmnt.php" data-i18n="nav.dash.project">Bank Master</a></li>
					</ul>
				</li>	
				<li class=" nav-item"><a href="index.html"><i class="fa fa-cog"></i><span class="menu-title" data-i18n="nav.dash.main">อำนาจดำเนินการ</span></a>
					<ul class="menu-content">
						<li><a class="menu-item" href="#" data-i18n="nav.templates.vert.main">อนก. ลูกค้าทั่วไป</a>
							<ul class="menu-content">
								<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/authomnt.php?isTiles=<?php echo encrypt("1", $key); ?>">Tiles</a></li>
								<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/authomnt.php?isTiles=<?php echo encrypt("2", $key); ?>">Geoluxe</a></li>
							</ul>
						</li>
						<li><a class="menu-item" href="#" data-i18n="nav.templates.vert.main">อนก. ลูกค้าในเครือ</a>
							<ul class="menu-content">
								<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/authomnt_affi.php?isTiles=<?php echo encrypt("1", $key); ?>">Tiles</a></li>
								<!--<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/authomnt_affi.php?isTiles=<?php echo encrypt("2", $key); ?>">Geoluxe</a></li>-->
							</ul>
						</li>
						<li><a class="menu-item" href="<?php echo BASE_DIR;?>/masmnt/syscmstrall.php" data-i18n="nav.dash.project">อนก. แต่งตั้งลูกค้าใหม่ฯ</a></li>
					</ul>
				</li>	
			<? } ?>	
			
			<li class=" navigation-header"><span data-i18n="nav.category.support">Support</span></li>
			<li class=" nav-item"><a href=""><i class="icon-support"></i><span class="menu-title" data-i18n="nav.support_raise_support.main">IT Business Solution</span></a>
			</ul>
		</div>
	</div>
	
