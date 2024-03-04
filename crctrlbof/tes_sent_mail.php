<?PHP
require("PHPMailer_v5.0.2/class.phpmailer.php");
$mail = new PHPMailer();

$body = "ทดสอบการส่งอีเมล์ภาษาไทย UTF-8 ผ่าน SMTP Server ด้วย PHPMailer.";

$mail->CharSet = "utf-8";
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = true;
$mail->Host = "smtp.yourdomain.com"; // SMTP server
$mail->Port = 25; // พอร์ท
$mail->Username = "email@yourdomain.com"; // account SMTP
$mail->Password = "******"; // รหัสผ่าน SMTP

$mail->SetFrom("email@yourdomain.com", "yourname");
$mail->AddReplyTo("email@yourdomain.com", "yourname");
$mail->Subject = "ทดสอบ PHPMailer.";

$mail->MsgHTML($body);

$mail->AddAddress("recipient1@somedomain.com", "recipient1"); // ผู้รับคนที่หนึ่ง
$mail->AddAddress("recipient2@somedomain.com", "recipient2"); // ผู้รับคนที่สอง

if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
?>