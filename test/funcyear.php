<?php 
date_default_timezone_set('Asia/Bangkok');
$curdate = date("d/m/Y H:i:s");
$curYear = '2024'; //e('Y'); 
//$day = isLeapYear($curYear);
function isLeapYear($year) {
    if ($year % 4 != 0) {
      return 28;
    } elseif ($year % 100 != 0) {
      return 29; // Leap year
    } elseif ($year % 400 != 0) {
      return 28;
    } else {
      return 29; // Leap year
    }
 }
echo 'ปี พศ. '.($curYear + 543).' เดือนกุมภาพันธ์มี '.isLeapYear($curYear).' วัน';
?>
