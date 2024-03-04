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
	//printpageform_new($crstm_nbr,$nbr_for,$pdf_savefile,$nbrto_savefile,$conn)
	printpageform_new($crstm_nbr,$savefile,$output_folder,$cr_output_filename,$conn);
?>