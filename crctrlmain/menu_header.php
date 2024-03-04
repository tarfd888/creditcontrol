<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">

    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a
                        class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i
                            class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item"><a class="navbar-brand" href=""><img class="brand-logo" alt="robust admin logo"
                            src="<?php echo $path_theme; ?>app-assets/images/logo/logo-light-sm.png">
                        <h3 class="brand-text">Credit Control</h3>
                    </a></li>
                <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse"
                        data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container content container-fluid">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs"
                            href="#"><i class="ft-menu"> </i></a></li>
                    <li class="dropdown nav-item mega-dropdown"><a class="dropdown-toggle nav-link" href="#"
                            data-toggle="dropdown">Menu</a>
                        <ul class="mega-dropdown-menu dropdown-menu row">
                            <li class="col-md-2">
                                <h6 class="dropdown-menu-header text-uppercase mb-1"><i class="fa fa-newspaper-o"></i>
                                    Home</h6>
                                <ul class="drilldown-menu">
                                    <li class="menu-list">
                                        <ul>
                                            <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/dashboard/dashboard-project.php"><i
                                                        class="icon-home"></i> Home</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="col-md-4">
                                <h6 class="dropdown-menu-header text-uppercase"><i class="fa fa-random"></i> Drill down
                                    menu</h6>
                                <ul class="drilldown-menu">
                                    <li class="menu-list">
                                        <ul>
                                            <li><a href="#"><i class="ft-layers"></i> ขออนุมัติวงเงิน</a>
                                                <ul>
                                                  <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/crctrlbof/crctrladd.php?action_cus=<?php echo encrypt("Old", $key); ?>">ลูกค้าปัจจุบัน (มีรหัสลูกค้า)</a></li>
                                                  <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/crctrlbof/crctrladd_new.php?action_cus=<?php echo encrypt("New", $key); ?>">ลูกค้าใหม่ (ไม่มีรหัสลูกค้า)</a></li>
                                                  <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/crctrlbof/crctrlall_stamp.php"></i>ลงนามคณะกรรมการบริหาร</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#"><i class="icon-direction"></i> ลูกค้าใหม่ & สาขาใหม่</a>
                                                <ul>
                                                  <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/newcust/newcusmnt.php?action_cus=<?php echo encrypt("c1", $key); ?>">แต่งตั้งลูกค้าใหม่</a></li>
                                                  <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/newcust/newcusmnt.php?action_cus=<?php echo encrypt("c2", $key); ?>">แต่งตั้งร้านสาขา</a></li>
                                                  <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/newcust/chgcusmnt.php?action_cus=<?php echo encrypt("c3", $key); ?>">เปลี่ยนแปลงข้อมูลลูกค้า</a></li>
                                                  <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/newcust/newcust_list.php">ดูสถานะข้อมูลลูกค้า</a></li>
                                                  <!-- <li><a href="#"><i class="fa fa-star-o"></i> Second level menu</a>
                                                      <ul>
                                                          <li><a class="dropdown-item" href="#"><i
                                                                      class="fa fa-heart"></i> Third level</a></li>
                                                          <li><a class="dropdown-item" href="#"><i
                                                                      class="fa fa-heart"></i> Third level</a></li>
                                                          <li><a class="dropdown-item" href="#"><i
                                                                      class="fa fa-heart"></i> Third level</a></li>
                                                          <li><a class="dropdown-item" href="#"><i
                                                                      class="fa fa-heart"></i> Third level</a></li>
                                                      </ul>
                                                  </li> -->
                                                </ul>
                                            </li>
                                            <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/masmnt/rolemastmnt.php"><i class="fa fa-skyatlas"></i> Role Master</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                              <h6 class="dropdown-menu-header text-uppercase mb-1"><i class="icon-list"></i>Report</h6>
                                <ul class="drilldown-menu">
                                    <li class="menu-list">
                                        <ul>
                                          <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/crctrlbof/displaycredit.php"><i class="ft-credit-card"></i> ข้อมูลเครดิตลูกค้า</a></li>
                                          <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/crctrlbof/crctrlexport_excel.php"><i class="ft-star"></i> สรุปรายการขออนุมัติวงเงิน</a></li>
                                          <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/newcust/newcusexport_excel.php"><i class="fa fa-address-card-o"></i> สรุปรายการแต่งตั้งลูกค้าใหม่</a></li>
                                          <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/newcust/risk_list.php"><i class="ft-alert-triangle"></i> สรุปรายการ Risk Categories</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                              <h6 class="dropdown-menu-header text-uppercase mb-1"><i class="fa fa-download"></i>Download</h6>
                                <ul class="drilldown-menu">
                                  <li class="menu-list">
                                    <ul>
                                      <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/masmnt/dl_form.php?isDom=<?php echo encrypt("1", $key); ?>"><i class="ft-download"></i> แบบฟอร์มลูกค้าในประเทศ</a></li>
                                      <li><a class="dropdown-item" href="<?php echo BASE_DIR;?>/masmnt/dl_form.php?isDom=<?php echo encrypt("2", $key); ?>"><i class="ft-download"></i> แบบฟอร์มลูกค้าต่างประเทศ</a></li>
                                    </ul>
                                  </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    </li>
                </ul>

                <ul class="nav navbar-nav mr-auto float-left">
                    <h4 class="text-bold-600 blue">SCG CERAMICS PUBLIC CO.,LTD.</h4>
                </ul>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                            <span class="avatar avatar-online"><img
                                    src="<?php echo $path_theme; ?>app-assets/images/portrait/small/new-customer.png"
                                    alt="avatar">
                                <i></i>
                            </span>
                            <span class="user-name"><?php echo $user_fullname; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?php echo BASE_DIR;?>/logout.php?msg=Logout Complete" class="dropdown-item">
                                <i class="ft-power"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
    </div>
</nav>