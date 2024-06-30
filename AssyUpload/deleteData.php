<?php

session_start();

if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}
require_once ('../DBconfig/dbConfig2.php');
$filename = $_SESSION['filename'];
$result_file_name = $db2->query("SELECT id FROM assy_excel_filename WHERE file_name = '$filename'"); 
$result_file_data = $db2->query("SELECT id FROM assy_upload WHERE file_name = '$filename'"); 
if($result_file_name->num_rows > 0){ 
    $db2->query("DELETE FROM assy_excel_filename WHERE file_name = '$filename'");
    $db2->query("DELETE FROM assy_upload WHERE file_name = '$filename'");

}
$_SESSION['filename'] = "none";
header("Location: index.php");
?>