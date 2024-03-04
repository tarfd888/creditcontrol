<?php
include("../_incs/acunx_metaheader.php");
include("../_incs/chksession.php");  
include("../_incs/config.php");	
include("../_incs/funcServer.php");	
include("../_incs/acunx_cookie_var.php");
include "../_incs/acunx_csrf_var.php";
include "../_libs/SimpleImage/simpleimage.php";	

$params="1000283";	
$sql_bll= "SELECT TOP 12 cus_mstr.cus_name1, bll_mstr.bll_ym, sum(bll_mstr.bll_amt_loc_curr) as amt, cracc_mstr.cracc_acc, bll_mstr.bll_stamp_date ".
	      "FROM bll_mstr INNER JOIN cracc_mstr ON bll_mstr.bll_acc = cracc_mstr.cracc_customer INNER JOIN ".
		  "cus_mstr ON cracc_mstr.cracc_acc = cus_mstr.cus_nbr WHERE (cracc_mstr.cracc_acc = '1000283') ".
		  "group by bll_mstr.bll_ym,cus_mstr.cus_name1,cracc_mstr.cracc_acc, bll_mstr.bll_stamp_date order by bll_ym  desc  ";
		  $result_bll = sqlsrv_query($conn, $sql_bll);
		  $tot_amt = 0 ;
			$a = array();
				while($row_bll = sqlsrv_fetch_array($result_bll, SQLSRV_FETCH_ASSOC))
					{
						$tot_amt = $row_bll['amt'];
							if($tot_amt < 0) {
								$tot_amt = ($tot_amt * -1);
								$tot_ord = "(".(number_format($tot_amt)).")";
							}else {
								$tot_ord = number_format($row_bll['amt']);
							}	
																								
							$bll_ym = $row_bll['bll_ym'];
							$bll_doc_ym1 = substr($bll_ym,0,4);
							$bll_doc_ym2 = substr($bll_ym,5,2);
							$bll_yofm = $bll_doc_ym1.'-'.$bll_doc_ym2;
							//$a[$bll_yofm] = $tot_ord;
							$a[$bll_yofm] = $tot_amt;
							// var_dump($a);
							// echo "<br>";
					}
	
	// $aa = array(
		
	// "2021-07" => "1244096",
	// "2021-06" => "1532224",
	// "2021-05" => "1267696",
	// "2021-04" => "1753877",
	// "2021-02" => "1685830",
	// "2021-01" => "2022864",
	// "2020-11" => "954312",
	// "2020-09" => "579962",
	// "2020-08" => "794933"
	// );
	
	echo "<br>";
	
	print_r($a);
	echo "<hr>";	
	
	$max_a = array_keys($a)[0];
	
	
	echo $max_a."<br>";
	echo "<hr>";	
	echo "<hr>";	
	
	$max_y = explode("-",$max_a)[0];
	$max_m = explode("-",$max_a)[1];
	
	$min_a = array_keys($a)[count($a)-1];
	$min_y = explode("-",$min_a)[0];
	$min_m = explode("-",$min_a)[1];
	
	//echo(max(1244096,1532224,1267696,1753877,1685830,2022864,954312,579962,794933) . "<br>");
	$max_amt = (max($a)."<br>");
	$min_amt = (min($a)."<br>");
	echo $max_y."<br>";
	echo "<hr>";	
	echo $min_y."<br>";
	echo "<hr>";	
	
	for ($y=$max_y; $y>=$min_y; $y--) {
		for ($m=$max_m; $m>=1;$m--) {
			$mx = substr("00{$m}", -2);
			$period = "$y-$mx";
			
			if (array_key_exists($period, $a)) {
			
				echo $period . " | " . $a[$period] . "<br>";
				//echo $a[$period]."<br>";
				$tot_a = $a[$period];
				$tot_a = str_replace(",","",$tot_a);
				
				$params_update_his_pjm = array($crstm_nbr,$period,$tot_a,$tot_a,$stamp1_date,$user_login,$today);
				
				$sql_update_his_pjm = "insert into tbl2_mstr (tbl2_nbr,tbl2_doc_date,tbl2_cus_nbr,tbl2_amt_loc_curr,tbl2_stamp_date,tbl2_create_by,tbl2_create_date) ".
				"values (?,?,?,?,?,?,?)";
				$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	

				
				
			} else {
				echo $period . " 0" . "<br>";
			
				$tot_a = 0;
				$params_update_his_pjm = array($crstm_nbr,$period,$tot_a,$tot_a,$stamp1_date,$user_login,$today);
			
				$sql_update_his_pjm = "insert into tbl2_mstr (tbl2_nbr,tbl2_doc_date,tbl2_cus_nbr,tbl2_amt_loc_curr,tbl2_stamp_date,tbl2_create_by,tbl2_create_date) ".
				"values (?,?,?,?,?,?,?)";
				$result_update_his_pjm = sqlsrv_query($conn,$sql_update_his_pjm,$params_update_his_pjm, array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	
			}
				
				
			if ($y == $min_y && $m == $min_m) {
				break;
			}
		
		}
			$max_m = 12;
	}

?>