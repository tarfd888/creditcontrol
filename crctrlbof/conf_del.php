<?php 
include("../_incs/acunx_metaheader.php");
	include("../_incs/chksession.php");  
	include("../_incs/config.php");	
	include("../_incs/funcServer.php");	
	include("../_incs/acunx_cookie_var.php");
	include "../_incs/acunx_csrf_var.php";
	
	$prj_nbr = $_POST['prj_nbr'];
	echo '<pre>';
	print_r ($prj_nbr);
	echo '</pre>';
	$path = "../_fileuploads/sale/project/";
	
	
	$pictureOriginal = findsqlval("crstm_mstr", "crstm_pj_img", "crstm_nbr", $prj_nbr,$conn);
	$directoryFile = $path.$pictureOriginal;
	if($pictureOriginal != ""){ 
		if(file_exists($directoryFile)){
			unlink($directoryFile);
			echo ("deleted $directoryFile");
		}
	}
?>