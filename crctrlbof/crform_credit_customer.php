<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/funcCrform.php");
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	date_default_timezone_set('Asia/Bangkok');
	
	$crstm_nbr = decrypt($_REQUEST['crnumber'], $key);
	$crstm_approve = decrypt($_REQUEST['pg'], $key);
	/* switch ($crstm_approve){
		case "คณะกรรมการบริหารอนุมัติ":
			printpageform($crstm_nbr,"WORK_CR_NUMBER",true,true,$conn);
			break;
		case "คณะกรรมการสินเชื่ออนุมัติ":
			printpageform($crstm_nbr,"WORK_CR_NUMBER",true,true,$conn);
			break;	
		case "กจก. อนุมัติ":
			printpageform($crstm_nbr,"WORK_CR_NUMBER",true,true,$conn);
			break;	
		case "CO. อนุมัติ":
			printpageform($crstm_nbr,"WORK_CR_NUMBER",true,true,$conn);
			break;	
		case "ผฝ. อนุมัติ":
			printpageform($crstm_nbr,"WORK_CR_NUMBER",true,true,$conn);
			break;				
	} */

	printpageform($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn);
?>