<?php
$can_edit_cr= false;
$can_edit_mgr= false;
$can_recall= false;
if (inlist($user_role,"Action_View1") || inlist($user_role,"Action_View2") ||
 	inlist($user_role,"Action_View3") || inlist($user_role,"Display_View")) {
    $can_edit_cr = true;
}
if (inlist($user_role,"FinCR Mgr")) {
    $can_recall = true; 
    $can_edit_cr = true; 
    $can_edit_mgr = true;
}
?>