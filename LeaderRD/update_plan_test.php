<?php

include_once '../DBconfig/db_connect.php';

$update_alert = 'บันทึกข้อมูลสำเร็จ';
$values = json_decode($_POST['values']);
$ids = json_decode($_POST['ids']);
$names = json_decode($_POST['names']);
$IDSR = json_decode($_POST['id_sr']);
$SRNO = json_decode($_POST['sr_no']);
$rdcNo = json_decode($_POST['rdc_no']);
for ($i = 0; $i < count($values); $i++) {
    $value = $values[$i];
    $id = $ids[$i];
    $name = $names[$i];
    $id_sr = $IDSR[$i];
    $sr_no = $SRNO[$i];
    $rdc_no = $rdcNo[$i];
    if ($value === '- - - Select M/C - - -') {
        continue;
    }

    
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM answer_leader WHERE id_rd_request = :id OR topic_doc_no = '$rdc_no'");
    $stmt_check->bindParam(':id', $id);
    $stmt_check->execute();
    $row_count = $stmt_check->fetchColumn();

    $stmt_insert_other = $pdo->prepare("SELECT a.id as id_rd,a.topic_doc_no as topic_doc_no ,b.id as id_sr ,b.sr_no as sr_no FROM rd_request a LEFT JOIN sr b ON a.id_sr = b.id  where a.id = '$id'");
    $stmt_insert_other->execute();
    $result4 = $stmt_insert_other->fetch(PDO::FETCH_ASSOC);
    $id_rd_request_insert = $result4['id_rd'];
    $id_sr_insert = $result4['id_sr'];
    $sr_no_insert = $result4['sr_no'];
    $topic_doc_no_insert = $result4['topic_doc_no'];

    $stmt_check_qty = $pdo->prepare("SELECT COUNT(id) AS total FROM rd_request where topic_doc_no = '$topic_doc_no_insert'");
    $stmt_check_qty->execute();
    $result3 = $stmt_check_qty->fetch(PDO::FETCH_ASSOC);
    $qty_rdc = $result3['total'];



    if ($row_count > 0) {
        $stmt_prev_plan = $pdo->prepare("SELECT PLAN, PLAN2, PLAN3 FROM answer_leader WHERE id_rd_request = $id OR topic_doc_no = '$rdc_no'");
        $stmt_prev_plan->execute();
        $result = $stmt_prev_plan->fetch(PDO::FETCH_ASSOC);
        $prev_plan = $result['PLAN'];
        $prev_plan2 = $result['PLAN2'];
        $prev_plan3 = $result['PLAN3'];
        if ($name == 'PLAN') {
            $value_timestamp = strtotime($value);
            $prev_plan_timestamp = strtotime($prev_plan);
            if($value == $prev_plan){
                continue;
            }else if ($value_timestamp < $prev_plan_timestamp) {
                $update_alert = "คุณกรอกวันที่เก่ากว่า";
                continue;
            }else{
                if($prev_plan != '' AND $prev_plan != null){
                    if ($prev_plan2 == '' OR $prev_plan2 == null) {
                        $stmt_update = $pdo->prepare("UPDATE answer_leader SET PLAN2 = '$prev_plan' WHERE id_rd_request = $id OR topic_doc_no = '$rdc_no'");
                        
                    }else if($prev_plan2 != '' AND $prev_plan2 != null){
                        $stmt_update = $pdo->prepare("UPDATE answer_leader SET PLAN3 = '$prev_plan' WHERE id_rd_request = $id OR topic_doc_no = '$rdc_no'");
                    }else if($prev_plan != '' AND $prev_plan != null AND $prev_plan2 != '' AND $prev_plan2 != null AND $prev_plan3 != '' AND $prev_plan3 != null){
                        $stmt_update = $pdo->prepare("UPDATE answer_leader SET PLAN = '$value' WHERE id_rd_request = $id OR topic_doc_no = '$rdc_no'");
                    }
                    try {
                        $stmt_update->execute();
                    } catch (PDOException $e) {
                        die("Error updating data: " . $e->getMessage());
                    }
                }
            }
        }
        $stmt = $pdo->prepare("SELECT sr_no, send_to_store , new_send_to_store_1, new_send_to_store_2 FROM answer_leader WHERE id_rd_request = $id OR topic_doc_no = '$rdc_no'");
       
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $prev_sr_no = $result['sr_no'];
        $send_to_store = $result['send_to_store'];
        $new_send_to_store_1 = $result['new_send_to_store_1'];
        $new_send_to_store_2 = $result['new_send_to_store_2'];
        $max = max($send_to_store, $new_send_to_store_1, $new_send_to_store_2);
        if($prev_sr_no == ""){
            $stmt_update2 = $pdo->prepare("UPDATE answer_leader SET $name = :value, id_rd_request = :id, id_sr = :id_sr, sr_no = :sr_no, topic_doc_no = :rdc_no, rdc_amount = :qty_rdc WHERE id_rd_request = :id OR topic_doc_no = '$rdc_no'");
           
            $stmt_update2->bindParam(':value', $value);
            $stmt_update2->bindParam(':id', $id);
            $stmt_update2->bindParam(':id_sr', $id_sr);
            $stmt_update2->bindParam(':sr_no', $sr_no);
            $stmt_update2->bindParam(':rdc_no', $rdc_no);
            $stmt_update2->bindParam(':qty_rdc', $qty_rdc);
            $stmt_update2->execute();
        }else{
            if($name == 'send_to_store'){
                $value_timestamp = strtotime($value);
                $max_plan_timestamp = strtotime($max);
                if($send_to_store != '' && $send_to_store != null){
                    if($new_send_to_store_1 == '' || $new_send_to_store_1 == null){
                        if($value != $max && $value > $max){ //send_to_store
                            $stmt_update2 = $pdo->prepare("UPDATE answer_leader SET new_send_to_store_1 = '$value'  WHERE id_rd_request = :id OR topic_doc_no = '$rdc_no'");
                            $stmt_update3 = $pdo->prepare("UPDATE sr SET new_date_to_store_rev1 = '$value' WHERE sr_no = '$sr_no'");
                            $stmt_update2->bindParam(':id', $id);
                            $stmt_update2->execute();
                            $stmt_update3->execute();
                        }else if ($value_timestamp < $max_plan_timestamp) {
                            if($send_to_store != ''){
                                $update_alert = 'คุณกรอกวันที่เก่ากว่า';
                                continue;
                            }
                            
                        }
                        
                    }else if(($new_send_to_store_2 == '' || $new_send_to_store_2 == null) && ($new_send_to_store_1 != '' || $new_send_to_store_1 != null)) {
                        if($send_to_store != $max && $value > $max){ //new_send_to_store_1
                            $stmt_update2 = $pdo->prepare("UPDATE answer_leader SET new_send_to_store_2 = '$value'  WHERE id_rd_request = :id OR topic_doc_no = '$rdc_no'");
                            $stmt_update3 = $pdo->prepare("UPDATE sr SET new_date_to_store_rev2 = $value WHERE sr_no = '$sr_no'");
                            $stmt_update2->bindParam(':id', $id);
                            $stmt_update2->execute();
                            $stmt_update3->execute();
                            
                        }else if ($value_timestamp < $max_plan_timestamp) {
                            if($send_to_store != ''){$update_alert = 'คุณกรอกวันที่เก่ากว่า';continue;}
                        }
                    }else{
                        $stmt_update2 = $pdo->prepare("UPDATE answer_leader SET $name = :value  WHERE id_rd_request = :id OR topic_doc_no = '$rdc_no'");
                        $stmt_update2->bindParam(':value', $value);
                        $stmt_update2->bindParam(':id', $id);
                        $stmt_update2->execute();
                    }
                }else{
                    $stmt_update2 = $pdo->prepare("UPDATE answer_leader SET $name = :value WHERE id_rd_request = :id OR topic_doc_no = '$rdc_no'");
                    $stmt_update3 = $pdo->prepare("UPDATE sr SET rd_send_to_store = '$value' WHERE sr_no = '$sr_no'");
                    $stmt_update2->bindParam(':value', $value);
                    $stmt_update2->bindParam(':id', $id);
                    $stmt_update2->execute();
                    $stmt_update3->execute();
                }
            }else{
                $stmt_update2 = $pdo->prepare("UPDATE answer_leader SET $name = :value WHERE id_rd_request = :id OR topic_doc_no = '$rdc_no'");
                $stmt_update2->bindParam(':value', $value);
                $stmt_update2->bindParam(':id', $id);
                $stmt_update2->execute();
            }
            
        }
       
        
       
        
        // Perform the database update using prepared statements to prevent SQL injection
        
    } else {
        
        // if($name == "PLAN"){
        //     $insertPosition = $i + 1;
        //     array_splice($names, $insertPosition, 0, ['PLAN2']);
        //     array_splice($values, $insertPosition, 0, $value); 
        //     array_splice($ids, $insertPosition, 0, $id); 
        // }

        

        

        $stmt_insert = $pdo->prepare("INSERT INTO answer_leader ($name, id_rd_request, id_sr, sr_no, topic_doc_no, rdc_amount) VALUES (:value, :id, '$id_sr_insert', '$sr_no_insert', '$topic_doc_no_insert', $qty_rdc)");
        
        
        
        $stmt_insert->bindParam(':value', $value);
        $stmt_insert->bindParam(':id', $id);
        
        try {
            $stmt_insert->execute();
        } catch (PDOException $e) {
            die("Error inserting data: " . $e->getMessage());
        }
    }
}

// Close the database connection
$pdo = null;
echo $update_alert;
?>