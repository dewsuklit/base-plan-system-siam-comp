<?php
require('../DBconfig/db_connect.php');
session_start();
// $_SESSION['$inputValue'] = 0;
$inputValue = $_POST['planValue'];
$id_rr = $_POST['id_rr'];
if($inputValue != ''){
    
    $stmt_check = $pdo->prepare("SELECT SUM(rdc_amount) FROM answer_leader WHERE PLAN = '$inputValue';");
    $stmt_check->execute();
    $lot_size_rdc = $stmt_check->fetchColumn();

    $stmt_check2 = $pdo->prepare("SELECT id as rd_id,topic_doc_no,COUNT(serial_no) as count FROM rd_request WHERE topic_doc_no = (SELECT topic_doc_no FROM rd_request WHERE id = '$id_rr');;");
    $stmt_check2->execute();
    $result = $stmt_check2->fetch(PDO::FETCH_ASSOC);
    $rd_id = $result['rd_id'];
    $lot_size_rdc2 = $result['count'];
    $topic_doc_no = $result['topic_doc_no'];
    if($lot_size_rdc >= 40){
        echo "วันที่  "+$inputValue+" มีจำนวนครบแล้ว";
    }else{
        if($lot_size_rdc == '' OR $lot_size_rdc == null){
            echo "วันที่  ".$inputValue." บันทึกการลงแผนแล้วจำนวน 0 \n";
            echo $topic_doc_no." กำลังลงแผนวันที่ : ".$inputValue." อีกจำนวน ".$lot_size_rdc2;
        }else{
            if($lot_size_rdc+$lot_size_rdc2 > 40){
                echo "Warning : วันที่  ".$inputValue." มีการลงแผนแล้วจำนวนเกิน 40 \n";
                echo "                 วันที่  ".$inputValue." บันทึกการลงแผนแล้วจำนวน ".$lot_size_rdc."\n";
                echo  $topic_doc_no." : กำลังลงแผนวันที่  ".$inputValue." อีกจำนวน ".$lot_size_rdc2;
            }else{
                echo "วันที่  ".$inputValue." บันทึกการลงแผนแล้วจำนวน ".$lot_size_rdc."\n";
                echo $topic_doc_no." กำลังลงแผนวันที่  ".$inputValue." อีกจำนวน ".$lot_size_rdc2;
            }
            // $_SESSION['$inputValue'] = 0;
            // echo $_SESSION['$inputValue'];
        }
        
    }
} else {
    echo 0;
}

?>
