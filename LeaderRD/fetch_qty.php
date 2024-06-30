<?php
require_once('../DBconfig/dbConfig2.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedValue'])) {
    $rdc_no = $_POST['selectedValue'];
    $Query = "SELECT COUNT(b.serial_no) as count FROM sr a LEFT JOIN rd_request b ON a.id = b.id_sr WHERE b.topic_doc_no = '$rdc_no';";
    $Result = $db2->query($Query);
    $line = mysqli_fetch_array($Result);
    $qty = $line['count'];
    echo $qty;
} else {
    echo "";
}
?>
