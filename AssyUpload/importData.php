<?php
session_start();
if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}

include_once '../DBconfig/dbConfig2.php';

require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$sortfile = $_SESSION['filename'];
$email = $_SESSION['session_email'];
date_default_timezone_set('Asia/Bangkok');
$date_time = date("Y-m-d H:i:s");
if (isset($_POST['importSubmit']) && $_FILES['file']['name'] != "") {
    $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)) {

        // If the file is uploaded
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $filename;
            $reader = new Xlsx();
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet_arr = $worksheet->toArray();

            // Remove header row
            array_splice($worksheet_arr, 0, 6);
            foreach ($worksheet_arr as $row) {
                $SERIES = $row[0];
                $Model_Mass = $row[1];
                $STATUS = $row[2];
                $PROJECT = $row[3];
                $Type_comp = $row[4];
                $RDC = $row[5];
                $MO_SR = $row[6];
                $CUSTOMER = $row[7];
                $MODEL = $row[8];
                $CODE = $row[9];
                $LOT = $row[10];
                $Issued = $row[11];
                $REQUEST_BY = $row[12];
                $Request = $row[13];
                $plan_date = $row[14];
                $filename = $_FILES['file']['name'];

                if($plan_date != "" && $plan_date != "0000-00-00" && $plan_date != null){
                    $date_parts = explode('/', $plan_date);

                    $year = $date_parts[2];   // วัน
                    $day = $date_parts[1]; // เดือน
                    $month = $date_parts[0]; 
                    
                    $day = str_pad($day, 2, "0", STR_PAD_LEFT);
                    $month = str_pad($month, 2, "0", STR_PAD_LEFT);
                    $PLAN = $year."-".$month."-".$day;
                    
                }
                
                // Check whether member already exists in the database with the same RDC
                $prevQuery = "SELECT id FROM assy_excel_filename WHERE file_name = '" . $filename . "'";
                $prevResult = $db2->query($prevQuery);
                
                if ($RDC != "" && $prevResult->num_rows > 0) {
                    $prevDataQuery = "SELECT * FROM assy_upload WHERE RDC = '" . $RDC . "'";
                    $prevDataResult = $db2->query($prevDataQuery);
                    $prevData = $prevDataResult->fetch_assoc();
                    if ($RDC != "" && $RDC != "RDC") {
                        $db2->query("UPDATE assy_upload SET SERIES = '".$SERIES."', Model_Mass = '".$Model_Mass."', STATUSs = '".$STATUSs."', PROJECT = '".$PROJECT."', Type_comp = '".$Type_comp."', RDC = '".$RDC."', MO_SR = '".$MO_SR."',CUSTOMER = '".$CUSTOMER."', MODEL = '".$MODEL."', CODE = '".$CODE."', LOT = '".$LOT."', Issued = '".$Issued."', REQUEST_BY = '".$REQUEST_BY."', Request = '".$Request."', PLAN = '".$PLAN."', filename = '".$filename."' WHERE RDC = '".$RDC."'"); 
                        
                        if ($PLAN != $prevData['PLAN']) {
                            $db2->query("UPDATE assy_upload SET PLAN = '".$PLAN."', prevPLAN = '".$prevData['PLAN']."' WHERE RDC = '".$RDC."'"); 
                        }
                    }    

                } else {
                    // Insert member data in the database
                    if ($RDC != "" && $RDC != "RDC") {
                            $db2->query("INSERT INTO assy_upload (SERIES, Model_Mass, STATUSs, PROJECT, Type_comp, RDC, MO_SR, CUSTOMER, MODEL, CODE, LOT, Issued, REQUEST_BY, Request, PLAN, prevPLAN, file_name) VALUES ('" . $SERIES . "', '" . $Model_Mass . "', '" . $STATUS . "', '" . $PROJECT . "','" . $Type_comp . "', '" . $RDC . "', '" . $MO_SR . "','" . $CUSTOMER . "','" . $MODEL . "','" . $CODE . "','" . $LOT . "', '" . $Issued . "', '" . $REQUEST_BY . "','" . $Request . "','" . $PLAN . "','" . $PLAN . "','" . $filename . "')");
                            
                        }
                }
                
            }
            $prevQuery2 = "SELECT id FROM assy_excel_filename WHERE file_name = '" . $filename . "'";
            $prevFileName = $db2->query($prevQuery2);
            if ($prevFileName->num_rows > 0) {
                $db2->query("UPDATE assy_excel_filename SET file_name = '".$filename."', update_date = '".$date_time."'  WHERE file_name = '".$filename."'");
                
            }else{
                // $db2->query("INSERT INTO assy_excel_filename (file_name, update_date) VALUES ('" . $filename . "','".$date_time."')");
                // $filename = $_FILES['file']['name'];
                $filedata = file_get_contents($_FILES['file']['tmp_name']);

                // เตรียมคำสั่ง SQL
                $stmt = $db2->prepare("INSERT INTO assy_excel_filename (file_name, file_data, update_date) VALUES (?, ?, ?)");
                $stmt->bind_param("sbs", $filename, $filedata, $date_time);
                if ($stmt->execute() === TRUE) {
                    echo "File uploaded successfully.";
                } else {
                    echo "Error uploading file: " . $db2->error;
                }
                $stmt->close();
            }
            
        } 
    } 
}
if(isset($_REQUEST['send']) ){
    if($_REQUEST['sortfile'] != "" && $_REQUEST['sortfile'] != "none"){
        // while($line = mysqli_fetch_assoc($Result)){
        //     $email = $email.$line["e_mail"].",";
        // }
        // mysqli_free_result($Result);
        $sendTo = 'suklit.d@ku.th';
        $subject = 'I-Spec (MKT request SR)';
        $message = "Please see Assy plan from Engineer.
                    <br>
                    File name : $filename
                    <br>
                    Upload time : $date_time
                    <br>
                    Mail send from Engineer R&D page.
                    ";



        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $email;
        $mail->Password = 'vfbunvpkqbcujbck';
        $mail->SMTPSecure = 'ssl'; // ถ้าใช้ TLS ให้เปลี่ยนเป็น 'tls'
        $mail->Port = 465; // เปลี่ยนเป็น 587 ถ้าใช้ TLS

        $mail->setFrom($email);

        $mail->addAddress($sendTo);

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $message;

        try {
            $mail->send();
            echo "
            <script>
                alert('ส่งอีเมลเรียบร้อย');
                document.location.href = '../index.php';
            </script>";
            $_SESSION['filename'] = "none";
        } catch (Exception $e) {
            echo "ไม่สามารถส่งข้อความได้ ข้อผิดพลาดของ Mailer: {$mail->ErrorInfo}";
        }
        // $qstring = '?status=succ';
        echo "";
    }
}
// Redirect to the listing page
$_SESSION['filename'] = $filename;
header("Location: index.php");
?>
