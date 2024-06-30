<?php
require('../DBconfig/dbConfig2.php');

// ตรวจสอบว่ามีการส่งค่า id_sr มาหรือไม่
if(isset($_POST['id_sr'])) {
    // รับค่า id_sr จาก URL parameter
    $id_sr = $_POST['id_sr'];
    
    // สร้างคำสั่ง SQL ดึงข้อมูล doc_code จากฐานข้อมูล
    $query = "SELECT doc_code FROM `vw_part_request` WHERE doc_input_sr = $id_sr";
    
    // ทำการ query ฐานข้อมูล
    $result = mysqli_query($db2, $query);
    
    $doc_codes = array();
    while ($row = mysqli_fetch_assoc($result))
    {
        $doc_codes[] = $row['doc_code'];
    }


    echo json_encode($doc_codes);
} else {
    echo json_encode(array('error' => 'ไม่มีค่า id_sr'));
}
?>
