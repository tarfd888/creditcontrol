<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");
include("../_incs/config.php");
include("../_incs/funcServer.php");
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    if (!matchToken($csrf_key, $user_login)) {
        echo "System detect CSRF attack!!";
        exit;
    }
}
else {
	echo "Allow for POST Only";
	exit;
}
$params = array();
date_default_timezone_set('Asia/Bangkok');
$today = date("Y-m-d H:i:s");
$errortxt = "";
$allow_post = false;

//$pg = html_escape($_REQUEST['pg']);
$action = html_escape($_POST['action']);
$sysc_id = mssql_escape($_POST['sysc_id']);
$sysc_com_code = mssql_escape($_POST['sysc_com_code']);
$sysc_com_name = mssql_escape($_POST['sysc_com_name']);
$sysc_com_address = mssql_escape($_POST['sysc_com_address']);
$sysc_com_tel = mssql_escape($_POST['sysc_com_tel']);
$sysc_com_fax = mssql_escape($_POST['sysc_com_fax']);
$sysc_com_email = mssql_escape($_POST['sysc_com_email']);
$sysc_com_lineid = mssql_escape($_POST['sysc_com_lineid']);
$sysc_com_taxid = mssql_escape($_POST['sysc_com_taxid']);
$sysc_cr_approver1 = mssql_escape($_POST['sysc_cr_approver1']);
$sysc_cr_approver2 = mssql_escape($_POST['sysc_cr_approver2']);
$sysc_final_approver = mssql_escape($_POST['sysc_final_approver']);
$sysc_cmo = mssql_escape($_POST['sysc_cmo']);
$sysc_cmo_act = mssql_escape($_POST['sysc_cmo_act']);
$sysc_cmo_pos_name = mssql_escape($_POST['sysc_cmo_pos_name']);

$sysc_cfo = mssql_escape($_POST['sysc_cfo']);
$sysc_cfo_act = mssql_escape($_POST['sysc_cfo_act']);
$sysc_cfo_pos_name = mssql_escape($_POST['sysc_cfo_pos_name']);

$sysc_md = mssql_escape($_POST['sysc_md']);
$sysc_md_act = mssql_escape($_POST['sysc_md_act']);
$sysc_md_pos_name = mssql_escape($_POST['sysc_md_pos_name']);

if ($sysc_cmo_act == 'on') {
    $sysc_cmo_act = 'True';
}else{
    $sysc_cmo_act = 'False';
    $sysc_cmo_pos_name = findsqlval("emp_mstr","emp_th_pos_name","emp_user_id",$sysc_cmo,$conn);
}

if ($sysc_cfo_act == 'on') {
    $sysc_cfo_act = 'True';
}else{
    $sysc_cfo_act = 'False';
    $sysc_cfo_pos_name = findsqlval("emp_mstr","emp_th_pos_name","emp_user_id",$sysc_cfo,$conn);
}

if ($sysc_md_act == 'on') {
    $sysc_md_act = 'True';
}else{
    $sysc_md_act = 'False';
    $sysc_md_pos_name = findsqlval("emp_mstr","emp_th_pos_name","emp_user_id",$sysc_md,$conn);
}

$errorflag = false;
$errortxt = "";

if ($action == "syscadd" || $action == "syscedit") {
    if ($sysc_com_code == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Company Code ]";
    }
    if ($sysc_com_name == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Company Name ]";
    }
    if ($sysc_com_address == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Company Address ]";
    }
    if ($sysc_com_tel == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Company Tel ]";
    }
    if ($sysc_com_fax == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Company Fax ]";
    }
    if ($sysc_com_email == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Company Email ]";
    }
    if ($sysc_com_taxid == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Company Tax ]";
    }
    if ($sysc_cr_approver1 == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Credit Control Approval 1]";
    } else {
        $emp_user_id = findsqlval("emp_mstr", "emp_user_id", "emp_user_id", $sysc_cr_approver1, $conn);
        $emp_email_bus = findsqlval("emp_mstr", "emp_email_bus", "emp_user_id", $sysc_cr_approver1, $conn);
        if ($emp_user_id == "") {
            if ($errortxt != "") {
                $errortxt .= "<br>";
            }
            $errorflag = true;
            $errortxt .= "กรุณาระบุ - [ Credit Control Approval 1] ไม่พบ USER ID นี้";
        }
        if ($emp_email_bus == "") {
            if ($errortxt != "") {
                $errortxt .= "<br>";
            }
            $errorflag = true;
            $errortxt .= "ไม่พบ Email User id - [Credit Control Approval 1]";
        } else {
            if (!strrpos($emp_email_bus, "@SCG.COM")) {
                if ($errortxt != "") {
                    $errortxt .= "<br>";
                }
                $errorflag = true;
                $errortxt .= "Email User id " . $emp_email_bus . "- [Credit Control Approval 1] ไม่ถูกต้อง";
            }
        }
    }
    if ($sysc_cr_approver2 == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Credit Control Approval 2]";
    } else {
        $emp_user_id = findsqlval("emp_mstr", "emp_user_id", "emp_user_id", $sysc_cr_approver2, $conn);
        $emp_email_bus = findsqlval("emp_mstr", "emp_email_bus", "emp_user_id", $sysc_cr_approver2, $conn);
        if ($emp_user_id == "") {
            if ($errortxt != "") {
                $errortxt .= "<br>";
            }
            $errorflag = true;
            $errortxt .= "กรุณาระบุ - [ Credit Control Approval 2 ] ไม่พบ USER ID นี้";
        }
        if ($emp_email_bus == "") {
            if ($errortxt != "") {
                $errortxt .= "<br>";
            }
            $errorflag = true;
            $errortxt .= "ไม่พบ Email User id - [ Credit Control Approval 2 ]";
        } else {
            if (!strrpos($emp_email_bus, "@SCG.COM")) {
                if ($errortxt != "") {
                    $errortxt .= "<br>";
                }
                $errorflag = true;
                $errortxt .= "Email User id " . $emp_email_bus . "- [ Credit Control Approval 2] ไม่ถูกต้อง";
            }
        }
    }
    if ($sysc_final_approver == "") {
        if ($errortxt != "") {
            $errortxt .= "<br>";
        }
        $errorflag = true;
        $errortxt .= "กรุณาระบุ - [ Credit Control Final Approval ]";
    } else {
        $emp_user_id = findsqlval("emp_mstr", "emp_user_id", "emp_user_id", $sysc_final_approver, $conn);
        $emp_email_bus = findsqlval("emp_mstr", "emp_email_bus", "emp_user_id", $sysc_final_approver, $conn);
        if ($emp_user_id == "") {
            if ($errortxt != "") {
                $errortxt .= "<br>";
            }
            $errorflag = true;
            $errortxt .= "กรุณาระบุ - [ Credit Control Final Approval ] ไม่พบ USER ID นี้";
        }
        if ($emp_email_bus == "") {
            if ($errortxt != "") {
                $errortxt .= "<br>";
            }
            $errorflag = true;
            $errortxt .= "ไม่พบ Email User id - [Credit Control Final Approval ]";
        } else {
            if (!strrpos($emp_email_bus, "@SCG.COM")) {
                if ($errortxt != "") {
                    $errortxt .= "<br>";
                }
                $errorflag = true;
                $errortxt .= "Email User id " . $emp_email_bus . "- [Credit Control Final Approval ] ไม่ถูกต้อง";
            }
        }
    }
  
}

if ($action == "syscdel") {

    if ($errortxt != "") {
        $errortxt .= "<br>";
    }
    $errorflag = true;
    $errortxt .= "ไม่อนุญาติให้ลบ - [ Control file ]";
}
/*
	$allow_admin = false;
	if (!inlist($user_role,"ADMIN")) {
		$path = "../expense/expenseauthorize.php"; 
		//echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$path."\" />";
	}
	else {
		$allow_admin = true;
	}
	$allow_admin = true;
	

	*/

    if ($action == "syscedit") {
        array_push($params, $sysc_id);
        $sql_edit = "UPDATE sysc_ctrl SET " .
            " sysc_com_code   = '$sysc_com_code '," .
            " sysc_com_name = '$sysc_com_name'," .
            " sysc_com_address = '$sysc_com_address'," .
            " sysc_com_tel = '$sysc_com_tel'," .
            " sysc_com_fax = '$sysc_com_fax'," .
            " sysc_com_email = '$sysc_com_email'," .
            " sysc_com_lineid = '$sysc_com_lineid'," .
            " sysc_com_taxid = '$sysc_com_taxid'," .
            " sysc_cr_approver1 = '$sysc_cr_approver1'," .
            " sysc_cr_approver2 = '$sysc_cr_approver2'," .
            " sysc_final_approver = '$sysc_final_approver'," .
            " sysc_cmo = '$sysc_cmo'," .
            " sysc_cmo_act = '$sysc_cmo_act'," .
            " sysc_cmo_pos_name = '$sysc_cmo_pos_name'," .
            " sysc_cfo = '$sysc_cfo'," .
            " sysc_cfo_act = '$sysc_cfo_act'," .
            " sysc_cfo_pos_name = '$sysc_cfo_pos_name'," .
            " sysc_md = '$sysc_md'," .
            " sysc_md_act = '$sysc_md_act'," .
            " sysc_md_pos_name = '$sysc_md_pos_name'" .
            " WHERE sysc_id = ?";
        $result_edit = sqlsrv_query($conn, $sql_edit, $params);
        if ($result_edit) {
            $r = "1";
            $errortxt = "update success.";
            $nb = encrypt($sysc_id, $key);
        } else {
            $r = "0";
            $nb = "";
             $errortxt = "update fail.";
        }
        echo '{"r":"' . $r . '","e":"' . $errortxt . '","nb":"' . $nb . '","pg":"' . $pg . '"}';
    }
    if ($action == "link_sysc") {

        $sysc_id = mssql_escape($_POST['sysc_id']);
        $params_sysc_link = array();

        $sql_sysc_link = "select count(*) as rowCounts from  sysc_ctrl where sysc_id = ?";
        $params_sysc_link = array($sysc_id);
        $result_sysc_link = sqlsrv_query($conn, $sql_sysc_link, $params_sysc_link, array("Scrollable" => 'keyset'));
        $rowCounts_sysc_link = sqlsrv_num_rows($result_sysc_link);

        if ($result_sysc_link) {
            if ($rowCounts_sysc_link > 0) {
                $r = "1";
                $errortxt = "Link Success.";
                $nb = encrypt($sysc_id, $key);
            } else {
                if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
                    if (!matchToken($csrf_key, $user_login)) {
                        echo "System detect CSRF attack666!!";
                        exit;
                    }
                }
            }
        } else {
            $r = "0";
            $nb = "";
            $errortxt = "Link fail.";
        }
        echo '{"r":"' . $r . '","e":"' . $errortxt . '","nb":"' . $nb . '","pg":"' . $pg . '"}';
    }
   
