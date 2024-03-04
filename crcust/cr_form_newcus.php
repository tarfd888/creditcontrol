<?php
	include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/cus_printform_func.php");
	include("../_incs/funcCrform.php");
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	date_default_timezone_set('Asia/Bangkok');
	
	$cus_app_nbr = decrypt($_REQUEST['crnumber'], $key);

	$check_form = findsqlval("cus_app_mstr","cus_cond_cust","cus_app_nbr",$cus_app_nbr,$conn);

	if (inlist("c1,c2",$check_form)) { 	
		print_formnewcust($cus_app_nbr,$savefile,$output_folder,$cr_output_filename,$conn,$watermark_text);
	} else {
		print_formchgcust($cus_app_nbr,$savefile,$output_folder,$cr_output_filename,$conn,$watermark_text);
	}
?>