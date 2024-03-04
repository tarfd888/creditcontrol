<?
function CheckandShowDate($date){
	$excel_date =$date;	
	if(trim($excel_date !="") or $excel_date !== NULL)
	{
		$CheckandShowDate = date_format($excel_date,"d/m/Y");	
		if($CheckandShowDate == "01/01/1900" or $CheckandShowDate == "00/01/1900")
		{
			$CheckandShowDate = "";
		}		
	}
	else{
		$CheckandShowDate = "";
	}
	return $CheckandShowDate;
}
function CheckandShowNumber($Val,$Digit){
	$Num =$Val;	
	if(trim($Num !="") or $Num !== NULL)
	{
		if($Num==0 or $Num=='0')
			$CheckandShowNumber = "";	
		else
			$CheckandShowNumber = number_format($Num,$Digit);	
	}
	else{
		$CheckandShowNumber = "";
	}
	return $CheckandShowNumber;
}
function CheckString ($text) {
	if($text !="")
	{
		if($text == "00/01/1900" or $text == "01/01/1900")
		{
			$restText = "";
		}
		else
		{
			$resText = strtoupper(str_replace("'"," ",$text));
		}
	}
	else
	{
		$resText = "";
	}
	return $resText;
}
?>