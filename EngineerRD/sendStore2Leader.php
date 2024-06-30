<?php

if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}

require_once('../DBconfig/dbConfig2.php');
require_once '../vendor/autoload.php';
require_once('../../../function/function.php');
require_once('../../../../include/class.phpmailer.php');
require_once('../../../../include/class.smtp.php');
$session_name = $_SESSION["session_name"];
$session_email = $_SESSION["session_email"];
if ($db2->connect_error) {
    die("Connection failed: " . $db2->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sent_leader = $_POST['id_sr_value'];
    if($sent_leader==""){$sent_leader = 0;}
    if($sent_leader != 0){
        $date_checked = date('Y-m-d');
        $Query = "update sr set id_sr_status='5',pass_en_rd_by='$session_name', pass_en_rd_date='$date_checked' where id = '$sent_leader' ";
        $Result = $db2->query($Query);	

        $Query2="select comp_id from sr where id = '$sent_leader' ";
        $Result2= $db2->query($Query2);
        $line=mysqli_fetch_array($Result2);
        $comp_id=$line['comp_id'];
        mysqli_free_result($Result2);
        if($comp_id != 0){
            $Query2="select *,c.model as model,c.four_digit as four_digit from sr a left outer join customer b on a.customer_id = b.customer_id left outer join comp_data c on a.comp_id=c.id where a.id = '$sent_leader' ";
        }else{
            $Query2="select *,c.target_model_name as model,c.fourdigit as four_digit,customer_name from sr a left outer join customer b on a.customer_id = b.customer_id left outer join npr c on a.model_npr_id=c.id where a.id = '$sent_leader' ";
        }
        $Result2= $db2->query($Query2);
        $line=mysqli_fetch_array($Result2);
        $txt_sr_no = $line['sr_no'];
        $customer_name=$line['customer_name'];
        $txt_model = $line['model'];
        $txt_four_digit = $line['four_digit'];
        $mkt_issued_by = $line['mkt_issued_by'];
        mysqli_free_result($Result2);

        $string_subject = "I-Spec (R&D reject SR)";
        $dear = "Dear Leader R&D";
        $message = "Please see SR request from MKT.
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

        $Query = "select e_mail from user where department_id = '1' and position_id = '5' "; // reader rd email
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
            echo "<script>alert('Send email to Leader successfully');</script>";
        } else {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            echo "<script>alert('{$mail->ErrorInfo}');</script>";
        }
    }    
} else {
echo "Invalid request method";
}

// Close the database connection
$db2->close();
?>