<?php
global $conn; 
define("TITLE","(CRCTRL R2.2022 DEV)");
define("PROJECT_RELEASE","CRCTRL R2.2022 DEV");
define("PROJECT_NAME","Credit Control System"); 
define("PROJECT_DESC","(การละเว้นเรื่องความปลอดภัยเป็นเรื่องที่ยอมกันไม่ได้)");
$defaultpwd = "scgc2018";
$maxagepwd = 90;
$lengthpwd = 8;
//$key=date("Ymd").$user_login;
//Get dbkey from ini file
$inipath = php_ini_loaded_file();
$ini_array = parse_ini_file($inipath , true);
$smtp=$ini_array["mail function"]["SMTP"];
$dbkey=$ini_array["dbaccess"]["dbkey"];
$key=$dbkey;

$path_theme = "../theme/";
$web_root = $_SERVER['DOCUMENT_ROOT'];
$app_folder = "crctrl_uat_ph3_1";   // nanthaws
$http_host = "http://".$_SERVER['HTTP_HOST']."/"; //For Test
//$http_host = "https://smartapp3.scgceramics.com/"; //For Production
$app_url = $http_host."$app_folder";
define("BASE_DIR",$app_url);

//$web_version = "Version : 2.1 (30/09/2022)";
$web_version = "Version : 2.2 (16/11/2023)";

$mail_subject_text = "[Credit Controls]";
$mail_from_text_app = "Credit Controls Approval";
$mail_from_text = "Credit Controls";
$mail_credit_email = "credit@scg.com";

// เอาบรรทัดนี้ออกด้วย
$mail_mgr_credit = "nanthaws@scg.com";
//$mail_mgr_credit = "nuchanav@scg.com";

$downloadpath = $web_root."/".$app_folder."/"."_filedownloads/";
$uploadpath = $web_root."/".$app_folder."/"."_fileuploads/";

// เอาบรรทัดนี้ออกด้วย
$mail_no_reply = "<span style='color: red'><br><font style='font-family:Cordia New;font-size:19px'> ** อยู่ในระหว่างการเทสระบบใช้กับ บริษัท สยามซานิทารีแวร์อินดัสทรี จำกัด ** <br></font></span>";

//$mail_no_reply = "<span style='color: red'><br><font style='font-family:Cordia New;font-size:19px'> ** NO-REPLY This eMail (กรุณาอย่า Reply Email ฉบับนี้ เพราะจะไม่มีผู้รับ) ** <br></font></span>";

//
$encdbpwd="7GGTy0cZ0kQH8THukLEfbyKFJxXDV12zqW9BkYJsiPk,"; //for production
//$encdbpwd="i6xksERPHoHgnbjBZFuqsK-M457RuaIs8GjMv6mHzOs,"; //for test
$decdbpwd=rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($dbkey), base64_decode(strtr($encdbpwd, '-_,', '+/=')), MCRYPT_MODE_CBC, md5(md5($dbkey))), "\0");
//
$encdbserv="MgrXkJo-0fVMNv4z3Hj5RdUKg2YxV0iG_kp-5o6XdNo,"; //for production
//$encdbserv="9BJ3TkzAvOX8goVw6k7cPuwyzDiZBHuRTBtYqBR_BjE,"; //for test
$decdbserv=rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($dbkey), base64_decode(strtr($encdbserv, '-_,', '+/=')), MCRYPT_MODE_CBC, md5(md5($dbkey))), "\0");

////$dbserver = $decdbserv;
$dbserver = "L0650NANTAWAS01\SQLEXPRESS";                 //10.28.101.94\SQLEXPRESS //L0650NANTAWAS01\SQLEXPRESS
//$dbname = "crctrldb_new";
$dbname = "crctrldb_uat";   // crctrldb_uat_fin  crctrldb_new
$dbuser = "sa";
$dbpwd = "Ta182924";     //"root"

$connectionInfo = array('Database' => $dbname ,"UID" => $dbuser, "PWD" => $dbpwd, "CharacterSet"  => 'UTF-8');
$conn = sqlsrv_connect($dbserver, $connectionInfo);
if(!$conn){
	echo"error connection";
	die(print_r(sqlsrv_errors(),true));	
}
?>