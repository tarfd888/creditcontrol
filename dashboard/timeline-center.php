<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include("../_incs/acunx_csrf_var.php");
include("../crctrlbof/chkauthcr.php");		
include_once('../_libs/Thaidate/Thaidate.php');
include_once('../_libs/Thaidate/thaidate-functions.php');
?>
<?php include("../newcust/header.php"); ?>
<?php include("../crctrlmain/menu_header.php"); ?>
<?php include("../crctrlmain/menu_leftsidebar.php"); ?>
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Home</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Timelines</a>
                            </li>
                            <li class="breadcrumb-item active">Timeline Center
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="timeline" class="timeline-center timeline-wrapper">
                <h3 class="page-title text-center">Timeline</h3>
                <ul class="timeline">
                    <li class="timeline-line"></li>
                    <li class="timeline-group">
                        <a href="#" class="btn btn-primary"><i class="fa fa-calendar-o"></i> เอกสารเลขที่ NC-2309-0008
                        </a>
                    </li>
                </ul>
                <ul class="timeline">
                    <li class="timeline-line"></li> 
                    <!-- 1 -->
                    <li class="timeline-item">
                        <div class="timeline-badge">
                            <span class="bg-warning bg-lighten-1" data-toggle="tooltip" data-placement="right"
                                title="Portfolio project work"><i class="fa fa-plane"></i></span>
                        </div>
                        <div class="timeline-card card border-grey border-lighten-2">
                            <div class="card-header">
                                <h4 class="card-title"><a href="#">1 สร้างเอกสาร</a></h4>
                                <p class="card-subtitle text-muted mb-0 pt-1">
                                    <span class="font-small-3">ผู้สร้างเอกสาร นายนันทวัฒน์ ศิริกัณฐรัตน์</span>
                                </p>
                                <p class="card-subtitle text-muted pt-1">
                                    <span class="font-small-3">วันที่ 01/11/2023 เวลา 23:32 น.</span>
                                </p>
                            </div>
                        </div>
                    </li>
                    <!-- 2-->
                    <li class="timeline-item mt-3">
                        <div class="timeline-badge">
                            <span class="bg-red bg-lighten-1" data-toggle="tooltip" data-placement="right"
                                title="Portfolio project work"><i class="fa fa-plane"></i></span>
                        </div>
                        <div class="timeline-card card border-grey border-lighten-2">
                            <div class="card-header">
                                <h4 class="card-title"><a href="#">2 รอผู้พิจารณาอนุมัติ</a></h4>
                                <p class="card-subtitle text-muted mb-0 pt-1">
                                    <span class="font-small-3">ผู้สร้างเอกสาร นายนันทวัฒน์ ศิริกัณฐรัตน์</span>
                                </p>
                                <p class="card-subtitle text-muted pt-1">
                                    <span class="font-small-3">วันที่ 01/11/2023 เวลา 23:32 น.</span>
                                </p>
                            </div>
                        </div>
                    </li>
                    <!-- <li class="timeline-line"></li> -->
                    <!-- 3 -->
                    <li class="timeline-item">
                        <div class="timeline-badge">
                            <span class="bg-warning bg-lighten-1" data-toggle="tooltip" data-placement="right"
                                title="Portfolio project work"><i class="fa fa-plane"></i></span>
                        </div>
                        <div class="timeline-card card border-grey border-lighten-2">
                            <div class="card-header">
                                <h4 class="card-title"><a href="#">3 สร้างเอกสาร</a></h4>
                                <p class="card-subtitle text-muted mb-0 pt-1">
                                    <span class="font-small-3">ผู้สร้างเอกสาร นายนันทวัฒน์ ศิริกัณฐรัตน์</span>
                                </p>
                                <p class="card-subtitle text-muted pt-1">
                                    <span class="font-small-3">วันที่ 01/11/2023 เวลา 23:32 น.</span>
                                </p>
                            </div>
                        </div>
                    </li>
                    <!-- 4-->
                    <li class="timeline-item">
                        <div class="timeline-badge">
                            <span class="bg-red bg-lighten-1" data-toggle="tooltip" data-placement="right"
                                title="Portfolio project work"><i class="fa fa-plane"></i></span>
                        </div>
                        <div class="timeline-card card border-grey border-lighten-2">
                            <div class="card-header">
                                <h4 class="card-title"><a href="#">4 สร้างเอกสาร</a></h4>
                                <p class="card-subtitle text-muted mb-0 pt-1">
                                    <span class="font-small-3">ผู้สร้างเอกสาร นายนันทวัฒน์ ศิริกัณฐรัตน์</span>
                                </p>
                                <p class="card-subtitle text-muted pt-1">
                                    <span class="font-small-3">วันที่ 01/11/2023 เวลา 23:32 น.</span>
                                </p>
                            </div>
                        </div>
                    </li>
                    <!-- 5 -->
                    <li class="timeline-item">
                        <div class="timeline-badge">
                            <span class="bg-warning bg-lighten-1" data-toggle="tooltip" data-placement="right"
                                title="Portfolio project work"><i class="fa fa-plane"></i></span>
                        </div>
                        <div class="timeline-card card border-grey border-lighten-2">
                            <div class="card-header">
                                <h4 class="card-title"><a href="#">5 สร้างเอกสาร</a></h4>
                                <p class="card-subtitle text-muted mb-0 pt-1">
                                    <span class="font-small-3">ผู้สร้างเอกสาร นายนันทวัฒน์ ศิริกัณฐรัตน์</span>
                                </p>
                                <p class="card-subtitle text-muted pt-1">
                                    <span class="font-small-3">วันที่ 01/11/2023 เวลา 23:32 น.</span>
                                </p>
                            </div>
                        </div>
                    </li>
                    <!-- 6-->
                    <li class="timeline-item">
                        <div class="timeline-badge">
                            <span class="bg-red bg-lighten-1" data-toggle="tooltip" data-placement="right"
                                title="Portfolio project work"><i class="fa fa-plane"></i></span>
                        </div>
                        <div class="timeline-card card border-grey border-lighten-2">
                            <div class="card-header">
                                <h4 class="card-title"><a href="#">6 สร้างเอกสาร</a></h4>
                                <p class="card-subtitle text-muted mb-0 pt-1">
                                    <span class="font-small-3">ผู้สร้างเอกสาร นายนันทวัฒน์ ศิริกัณฐรัตน์</span>
                                </p>
                                <p class="card-subtitle text-muted pt-1">
                                    <span class="font-small-3">วันที่ 01/11/2023 เวลา 23:32 น.</span>
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>

                <ul class="timeline">
                    <li class="timeline-line"></li>
                    <li class="timeline-group">
                        <a href="#" class="btn btn-primary"><i class="fa fa-calendar-o"></i> อนุมัติ</a>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</div>
<? include("../crctrlmain/menu_footer.php"); ?>
<div class="to-top">
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>

<? include("../newcust/file_script.php"); ?>
<script type="text/javascript">
< /body>  < /
html >