<?php 
	include("_incs/acunx_metaheader.php");
	include("_incs/acunx_cookie_var.php");
	$msg = $_REQUEST['msg'];														
?>
<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT">
    <title>Logout</title>
</head>
<body>
<?php		
	setcookie ("BkwNFcey_resu", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("BkwNFcey_eocd", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("BkwNFcey_elor", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("BkwNFcey_llmaneuf", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("BkwNFcey_gro", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("BkwNFcey_sopmane", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("BkwNFcey_namag", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("BkwNFcey_elimana", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("BkwNFcey_eli", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	setcookie ("BkwNFcey_let", "", time()-100,$ck_path,$ck_dom,$ck_secure,$ck_httponly);
	
	$path = "index.php?msg=$msg";
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	//echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php?msg=$msg\" />";	
?>
</body>
</html>
