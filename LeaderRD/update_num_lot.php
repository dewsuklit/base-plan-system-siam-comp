<?php
require_once('../DBconfig/dbConfig2.php');

// ตรวจสอบว่ามีการส่งค่ามาในรูปแบบ POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีค่า key1 และ key2 ถูกส่งมาหรือไม่ (สามารถแก้ไขตามชื่อ key ที่ต้องการใช้ได้)
    if (isset($_POST['count_row']) && isset($_POST['idSR'])) {// && isset($_POST['txt_remark'])
        // รับค่าที่ส่งมาจาก Ajax
        $count_row = $_POST['count_row'];
        $id = $_POST['idSR'];
        $sr_no = $_POST['sr_no'];
        $serialNoData  = $_POST['serialNoData'];
        $store_date = $_POST['store_date'];
        $rdc_no = $_POST['rdc_no'];
        $qty = $_POST['qty'];
        $Query = "UPDATE sr SET num_lot = $count_row WHERE id = $id";
        $Query2 = "SELECT * FROM serial_of_lot WHERE sr_no = '$sr_no'";
        
        // ทำการส่งคำสั่ง SQL ไปยังฐานข้อมูล
        $Result = $db2->query($Query);
        $Result2 = $db2->query($Query2);
        $serial_sr = ''; 

        for ($x = 1; $x <= $count_row; $x++) {
            $serial_sr .= ($serial_sr != '' ? ',' : '') . $serialNoData[$x];
        }

        if($Result2->num_rows < 1){
            for ($i = 1; $i <= $count_row; $i++) { // แก้ไขตรงนี้ให้ถูกต้อง
                $insert = "INSERT INTO serial_of_lot (sr_no, lot, serial_no, topic_doc_no,qty,store_date) VALUES ('" . $sr_no . "','".$i."','".$serialNoData[$i]."','".$rdc_no[$i-1]."','".$qty[$i-1]."','".$store_date[$i]."')";   
                $update2 =  "UPDATE sr SET store_date = '{$store_date[$count_row]}' ,serial_no = '{$serial_sr}' WHERE sr_no = '{$sr_no}'";
                $update3 =  "UPDATE assy_upload SET send_to_store = '{$store_date[$count_row]}' WHERE MO_SR = '{$sr_no}'";

                $db2->query($insert);
                $db2->query($update2);
                $db2->query($update3);
            }
        }else{
            if($Result2->num_rows == $count_row){
                for ($i = 1; $i <= $count_row; $i++) {
                    $update = "UPDATE serial_of_lot SET serial_no = '{$serialNoData[$i]}',store_date = '{$store_date[$i]}',topic_doc_no = '{$rdc_no[$i-1]}',qty = '{$qty[$i-1]}' WHERE sr_no = '{$sr_no}' AND lot = '{$i}'";
                    $update2 =  "UPDATE sr SET store_date = '{$store_date[$count_row]}' ,serial_no = '{$serial_sr}' WHERE sr_no = '{$sr_no}'";
                    $update3 =  "UPDATE assy_upload SET send_to_store = '{$store_date[$count_row]}' WHERE MO_SR = '{$sr_no}'";
                    $db2->query($update);
                    $db2->query($update2);
                    $db2->query($update3);
                }
            }else{
                for ($i = $Result2->num_rows+1; $i <= $count_row; $i++) { 
                    $insert = "INSERT INTO serial_of_lot (sr_no, lot, serial_no, store_date, topic_doc_no,qty) VALUES ('" . $sr_no . "','".$i."','".$serialNoData[$i]."','".$store_date[$i]."','".$rdc_no[$i-1]."','".$qty[$i-1]."')";
                    $update =  "UPDATE sr SET store_date = '{$store_date[$i]}',serial_no = '{$serial_sr}' WHERE sr_no = '{$sr_no}'";
                    $update3 =  "UPDATE assy_upload SET send_to_store = '{$store_date[$i]}' WHERE MO_SR = '{$sr_no}'";
                    $db2->query($insert);
                    $db2->query($update);
                    $db2->query($update3);
                }
                for ($i = 1; $i <= $count_row; $i++) {
                    $update2 = "UPDATE serial_of_lot SET serial_no = '{$serialNoData[$i]}',store_date = '{$store_date[$i]}',topic_doc_no = '{$rdc_no[$i-1]}',qty = '{$qty[$i-1]}' WHERE sr_no = '{$sr_no}' AND lot = '{$i}'";
                    $db2->query($update2);
                }
            }
            
        }
        
        // ตรวจสอบว่าคำสั่ง SQL UPDATE สำเร็จหรือไม่
        if ($Result) {
            echo "บันทึกข้อมูลสำเร็จ";
        } else {
            echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $db2->error;
        }
    } else {
        echo "ไม่มีข้อมูลที่ส่งมาให้กับไฟล์นี้";
    }
} else {
    echo "ไม่สามารถเข้าถึงไฟล์นี้โดยตรงได้";
}
?>
