<?php
$can_editing = false;
//if (inlist($user_role,"SALE_VIEW") || inlist($user_role,"ADMIN")) {
if (inlist($user_role,"SALE_VIEW")) {
    $can_editing = true;
}
?>