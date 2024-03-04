<?php
function isservonline($cfgServer) {
	$cfgPort    = "25";
	$cfgTimeOut = "5";
	
	$f=fsockopen("$cfgServer",$cfgPort,$cfgTimeOut);
	if ($f) {
		return true;
	}
	else {
		return false;
	}
}
function mail_normal($from_name,$from_mail,$mail_to,$replyto_email,$email_subject,$message) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8"."\r\n";
	$headers .= "From: ".$from_name."<".$from_mail.">"."\r\n";
	$headers .= "Reply-To: "."Accounting"."<".$replyto_email.">"."\r\n";
	$subject1 = "=?UTF-8?B?".base64_encode($email_subject)."?=";
	$result = mail($mail_to,$subject1,$message,$headers);
	if($result) {
		return true;
	} else {
		return false;
	}
}

function mail_message($from_name,$from_mail,$mail_to,$email_subject,$message) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8"."\r\n";
	$headers .= "From: ".$from_name."<".$from_mail.">"."\r\n";
	$subject1 = "=?UTF-8?B?".base64_encode($email_subject)."?=";
	
	$result = mail($mail_to,$subject1,$message,$headers);
	if($result) {
		return true;
	} else {
		return false;
	}
	
	//return true;
}
function mail_attachment($filename_attach, $filename_in_mail,$path, $mail_to, $from_mail, $from_name, $subject, $message) {
	$eol = "\r\n";
	$file = $path.$filename_attach;
	$file_size = filesize($file);
	$handle = fopen($file, "r");
	$content = fread($handle, $file_size);
	fclose($handle);

	$content = chunk_split(base64_encode($content));
	$uid = md5(uniqid(time()));
	$name = basename($file);

	
	$header = "MIME-Version: 1.0" . $eol;
	$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid ."\"" . $eol;
	$header .= "From: ".$from_name."<".$from_mail.">" . $eol;
	//Mail Message
	$emessage  = "--".$uid."\n";
	$emessage .= "Content-type:text/html;charset=UTF-8"."\n";
	$emessage .= "Content-Transfer-Encoding: 7bit\n\n";
	$emessage .= $message."\n\n";
	
	$emessage .= "--".$uid."\n";
    $emessage .= "Content-Type: application/octet-stream; name=\"".$filename_in_mail."\"\n";
    $emessage .= "Content-Transfer-Encoding: base64\n";
    $emessage .= "Content-Disposition: attachment; filename=\"".$filename_in_mail."\"\n\n";
    $emessage .= $content."\n\n";
    $emessage .= "--".$uid."--";
	
	$subject1 = "=?UTF-8?B?".base64_encode($subject)."?=";
	
    $result = mail($mail_to,$subject1,$emessage,$header);
	if($result) {
		return true;
	} else {
		return false;
	}
	
	//return true;
	/*
	ตัวอย่างการใช้งาน
	$my_file = "2562_07_09-10.pdf";
	$my_path = "d:/appserv/www/testmail/f/";
	$my_name = "Komsun";
	$my_mail = "komsunyu@scg.com";
	$my_replyto = "komsunyu@scg.com";
	$my_subject = "This is a mail with attachment.";
	$my_message = "Hallo,rndo you like this script? I hope it will help.rnrngr. Olaf";
	mail_attachment($my_file, "S19000000011.pdf",$my_path, "komsunyu@scg.com", $my_mail, $my_name, $my_subject, $my_message);
	*/
}

function mail_multiattachment($filename_attach,$filename_mail,$mail_to, $from_mail, $from_name, $subject, $message) {
	$eol = "\r\n";
	$to = $mail_to;
    $from = $from_mail; 
	$uid = md5(uniqid(time()));
	
	$header = "MIME-Version: 1.0" . $eol;
	$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid ."\"" . $eol;
	$header .= "From: ".$from_name."<".$from_mail.">" . $eol;

	//Mail Message
	$emessage  = "--".$uid."\n";
	$emessage .= "Content-type:text/html;charset=UTF-8"."\n";
	$emessage .= "Content-Transfer-Encoding: 7bit\n\n";
	$emessage .= $message."\n\n";
	
	$files = $filename_attach; //FILES ARRAY
    for($x=0;$x<count($files);$x++){	
		if (file_exists($files[$x])) {
			//$f = getfilename($files[$x]);
			//Read Binary File
			if (filesize($files[$x]) > 0) {
				$file = fopen($files[$x],"rb");
				$content = fread($file,filesize($files[$x]));
				fclose($file);
				$content = chunk_split(base64_encode($content));
				//Start Attach a File
				$emessage .= "--".$uid."\n";
				$emessage .= "Content-Type: application/octet-stream; name=\"".$filename_mail[$x]."\"\n";
				$emessage .= "Content-Transfer-Encoding: base64\n";
				$emessage .= "Content-Disposition: attachment; filename=\"".$filename_mail[$x]."\"\n\n";
				$emessage .= $content."\n\n";
			}
			///End Attach
		}
    }
	
	$subject1 = "=?UTF-8?B?".base64_encode($subject)."?=";
    $result = mail($mail_to,$subject1,$emessage,$header);
	if($result) {
		return true;
	} else {
		return false;
	}
}
?>