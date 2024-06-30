<?php
session_start();
if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}
require_once( '../DBconfig/dbConfig2.php' );
// require_once( '../../include/dbfunction.php' );
// require("../../include/class.phpmailer.php");
// require("../../include/class.smtp.php");

$id_sr = $_REQUEST["id_sr"];

if(basename( $_FILES['myfile']['name']) != ""){
	if(($_FILES["myfile"]["type"]!="application/pdf")||
	($_FILES["myfile"]["type"]!="application/excel")){

			$Query1 = "select rd_report_file,sr_no,mkt_issued_by from sr a left outer join customer b on a.customer_id = b.customer_id where id = '$id_sr'"; 
			$Result1 = $db2->query($Query1);
			$line1=mysqli_fetch_array($Result1);
			$old_file = $line1['rd_report_file'];
			$sr_no = $line1['sr_no'];
			$customer_name = $line1['customer_name'];
			$mkt_issued_by = $line1['mkt_issued_by'];
			mysqli_free_result($Result1);

			$date_save = date(Y."-".m."-".d."-".H."-".i."-".s);
			$new_file = $date_save.basename( $_FILES['myfile']['name']);
			if($old_file != ""){
				$all_file = $old_file."/".$new_file;
			}else{
				$all_file = $new_file;
			}
			$Queryup = "update sr set rd_report_file = '$all_file' where id = '$id_sr'";
		$Resultup = $db2->query($Queryup);
			$result = 0;
			$target_path = "../uploadfile/sr_report_folder/" .$date_save. basename( $_FILES['myfile']['name']);
			if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
			$result = 1;
			}
			/*
		//mail
			$string_subject = "I-Spec (R&D update report SR)";
			$body_string = "
			<html>
					<table width='80%' style='font-size:12px;'>
						<tr>
							<td style='font-weight:bold;color:gray;text-align:left;' colspan='100%'>Dear $mkt_issued_by</td>
						</tr>
						<tr>
							<td style='font-weight:bold;color:black;text-align:left'colspan='100%'>&nbsp;&nbsp;R&D update report SR request No. $sr_no ($customer_name)</td>
						</tr>
						<tr><td colspan='100%' height='10px'>&nbsp;</td></tr>
						<tr>
							<td style='font-weight:bold;color:gray;text-align:left;' colspan='100%'>Siam Compressor Industry Co., Ltd.</td>
						</tr>
						<tr>
							<td style='font-weight:bold;color:gray;text-align:left;' colspan='100%'>Laemchabang Industrial Estate 87/10 Moo2 Sukhumvit Road, Sri Racha, Chonburi 20230 Thailand.</td>
						</tr>
						<tr>
							<td style='font-weight:bold;color:gray;text-align:left;' colspan='100%'>Tel : (66)38 490-900 to 10 Fax : (66)38-490-917, 490 919 Email : marketing@siamcompressor.com</td>
						</tr>
					</table>
				</html>
			";
			
			$Query = "select e_mail from user where name like '%$mkt_issued_by%'"; // user creaet sr
			$Result = $db2->query($Query) or die(mysqli_error());
			$max_npr = '';
			while($line = mysqli_fetch_assoc($Result)){
				$email = $max_npr.$line["e_mail"].",";
			}
			mysqli_free_result($Result);
			$to_email = $email;
			mail_send($string_subject,$body_string,$to_email,"","");
			//end mail
	
	*/
	
	}
	
}else{
	$result = 0;
}
sleep(1);
?>

<script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo $result; ?>);</script>   
