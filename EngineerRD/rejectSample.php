<?php

require_once( '../../../../include/dbfunction.php' );
require_once( '../../../../include/config.php' );
require_once '../vendor/autoload.php';
require_once('../DBconfig/dbConfig2.php');
require_once('../../../function/function.php');
require_once('../../../../include/class.phpmailer.php');
require_once('../../../../include/class.smtp.php');

$session_name = $_SESSION["session_name"];
if ($db2->connect_error) {
    die("Connection failed: " . $db2->connect_error);
}

$rej_id = $_REQUEST["id_reject"];
$reason = $_REQUEST["reason_reject"];
if($rej_id!=''){
	$date = date(Y."-".m."-".d);
	$Query = "update sr set id_sr_status='8',mkt_issued_by = '',mkt_issued_date = '0000-00-00',mkt_verified_by=''
	,mkt_verified_date = '0000-00-00',mkt_checked_by = '',mkt_checked_date = '0000-00-00',mkt_approved_by='',
	mkt_approved_date = '0000-00-00',reject_sr = '$reason', reject_sr_by = '$_SESSION[session_name]',reject_sr_date = '$date'
	where id = '$rej_id' ";
	$Result = mysql_query($Query) or die(mysql_error());	

	$Query2="select *,c.model as model,c.four_digit as four_digit from sr a left outer join customer b on a.customer_id = b.customer_id left outer join comp_data c on a.comp_id=c.id where a.id = '$rej_id' ";
	$Result2= mysql_query($Query2) or die(mysql_error());
	$line=mysql_fetch_array($Result2);
	$txt_sr_no=$line['sr_no'];
	$customer_name=$line['customer_name'];
	$txt_model = $line['model'];
	$txt_four_digit = $line['orderingcode'];
	$mkt_issued_by = $line['mkt_issued_by'];
	$mkt_make_by = $line['mkt_make_by'];
	mysql_free_result($Result2);

	$string_subject = "I-Spec (R&D reject SR)";
	$dear = "Dear $mkt_issued_by,$mkt_make_by";
	$message = "Please see SR was reject from R&D.
	<br>
	SR No : $txt_sr_no
	<br>
	Model : $txt_model / $txt_four_digit
	<br>
	Customer : $customer_name
	<br>
	Mail send from Engineer R&D page.
	";
	$body_string = "
	<html>
	<table width='80%' style='font-size:14px;'>
	<tr>
	<td style='font-weight:bold;color:gray;text-align:left;' colspan='100%'>$dear</td>
	</tr>
	<tr>
	<td style='font-weight:bold;color:black;text-align:left'colspan='100%'>&nbsp;&nbsp;$message</td>
	</tr>
	<tr>
	<td style='font-weight:bold;color:black;text-align:left'colspan='100%'>
	Please login to <a href='https://".$_SERVER['HTTP_HOST']."/sci_web_admin/www'>Click for login</a>
	</td>
	</tr>
	<tr><td colspan='100%' height='10px'>&nbsp;</td></tr>
	</table>
	</html>
	";
	$mail_to = 'dew022612678@gmail.com';
	$now = date("Y-m-d H:i:s");
	$session_email = $_SESSION["session_email"];
	$mail = new PHPMailer(true);
	$mail->CharSet = "UTF-8";
	$mail->Body = $body_string;
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Port = 25;
	$mail->Host = "email.siamcompressor.com";
	$mail->From = $session_email;
	$mail->FromName = "technic@siamcompressor.com"; // ปรับเป็นชื่อผู้ส่งอีเมลเท่านั้น
	$mail->Subject =  $string_subject;
	$mail->IsHTML(true);

	$Query = "select e_mail from user where name = '$mkt_issued_by' OR name = '$mkt_make_by' "; // reader rd email
	$Result = $db2->query($Query);
	while($line = mysqli_fetch_assoc($Result)){
		$email = $email.$line["e_mail"].",";
	}
	mysqli_free_result($Result);
	$countmailto = explode(",",$email);
	for($m=0;$m<count($countmailto);$m++){
		if($countmailto[$m] != "" && $countmailto[$m] != "-"){
			// $mail->AddAddress($countmailto[$m]);
		}
	}
	$mail->AddAddress($mail_to);
	$mail->AddCC($session_email);
	$mail->Send();
	if ($mail->Send()) {

	} else {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		echo "<script>alert('{$mail->ErrorInfo}');</script>";
	}
    echo "<script>alert('Reject and Send e-mail Complete'); location.href = 'index.php';</script>";
}else{
    echo "<script>alert('Reject Error'); location.href = 'index.php';</script>";
}

?>
