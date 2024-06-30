<?
session_start();
error_reporting (E_ALL ^ E_NOTICE); // close notice
ini_set('memory_limit','-1');
set_time_limit(0);
if(!isset($_SESSION['session_id'])){
    echo "<script>location.href ='../index.php'</script>";
}
require_once("../../../../include/dbfunction.php");
require_once("../../../../include/dbconfig.php");
require_once('../../../function/function.php');
define('DB_HOST', 'localhost');
define('DB_NAME', 'siamcomp_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create a database connection
try {
  $conn2 = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
  $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die('Error connecting to database: ' . $e->getMessage());
}

$start = $_POST["start"];
$length = $_POST["length"];
$search = $_POST["search"]["value"];

$column = array(
    "b.id", 
    "a.model", 
    "a.type_of_compressor",
    "au.part_mo",
    "g.doc_code",
    "a.request_rd_to_sent_to_store_within",
    "a.type_of_compressor",
    "b.topic_doc_no",
    "a.sr_no",
    "a.serial_no",
    "a.mkt_approved_date",
    "a.mkt_issued_by",
    "b.rd_mo",
    "c.customer_name",
    "Model",
    "Code",
    "a.qty",
    "tt.serial_no_ok",
    "b.topic_amount",
    "b.assembly_bomdev_ref",
    "a.design_drawing",
    "a.production_line",
    "b.topic_write_by",
    "Responsible",
    "au.PLAN",
    "au.actual_assy",
    "VIEW",
    "au.Calorie_Plan_Test",
    "au.Calorie_M_C",
    "au.Calorie_Plan_Finish",
    "au.Calorie_Actual_Finish",
    "au.Noise_Plan_Test",
    "au.Noise_M_C",
    "au.Noise_Plan_Finish",
    "au.Noise_Actual_Finish",
    "au.Locked_Roter_Plan_Test",
    "au.Locked_Roter_M_C",
    "au.Locked_Roter_Plan_Finish",
    "au.Locked_Roter_Actual_Finish",
    "au.send_to_store",
    "a.store_date",
);
try {
    

    $sql = "SELECT 
                MAX(b.id) as id_rr,
                b.topic_doc_no as RDC, 
                b.rd_mo as MO,
                b.topic_amount as lot_rdc, 
                b.assembly_bomdev_ref,
                b.topic_write_by as RDC_Request_by,
                b.rd_orderingcode,
                b.topic_amount as lot_size_rdc,
                a.id as id_sr,
                a.design_drawing,
                a.production_line,
                a.request_rd_to_sent_to_store_within as Date_JR_JM_JN,
                a.type_of_compressor as Type_Comp,
                a.sr_no,
                a.serial_no as serial_no_sr,
                a.qty as qty,
                a.customer_id,
                a.mkt_issued_by,
                a.mkt_approved_date,
                a.model_npr_id,
                a.comp_id,
                a.request_rd_to_sent_to_store_within as request_rd_to_sent_to_store_within,
                a.store_date as store_date,
                a.type_of_compressor,
                c.customer_name as Customer, 
                COALESCE(NULLIF(d.orderingcode, ''), f.orderingcode, b.rd_orderingcode) AS Code,
                IF(f.model = '' OR f.model IS NULL, d.target_model_name,f.model) AS Model, 
                IF(f.check_by = '' or f.check_by is null,d.rd_decision_by,f.check_by) as Responsible,
                g.doc_code as doc_code,
                au.part_mo,
                au.prevPLAN,
                au.PLAN,
                au.PLAN2,
                au.PLAN3,
                au.actual_assy,
                au.Calorie_Plan_Test,
                au.Calorie_M_C,
                au.Calorie_Plan_Finish,
                au.Calorie_Actual_Finish,
                au.Noise_Plan_Test,
                au.Noise_M_C,
                au.Noise_Plan_Finish,
                au.Noise_Actual_Finish,
                au.Locked_Roter_Plan_Test,
                au.Locked_Roter_M_C,
                au.Locked_Roter_Plan_Finish,
                au.Locked_Roter_Actual_Finish,
                au.send_to_store,
                au.new_send_to_store_1,
                au.new_send_to_store_2,
				tt.serial_no_ok,
                IF(h.state IS NULL,'Develop', h.state) as BOM
            FROM  rd_request b 
            LEFT JOIN  sr a  ON a.id = b.id_sr
            LEFT JOIN customer c ON a.customer_id = c.customer_id  
            LEFT JOIN npr d ON a.model_npr_id = d.id
            LEFT JOIN comp_data f ON a.comp_id = f.id
            LEFT JOIN vw_part_request g ON g.doc_input_sr = a.id
            LEFT JOIN answer_leader au ON au.id_rd_request = b.id OR au.topic_doc_no = b.topic_doc_no 
            LEFT JOIN formula_model h ON IF(f.model = '' OR f.model IS NULL, d.target_model_name,f.model) = h.model 
            LEFT JOIN (SELECT 
                            COUNT(IFNULL(aa.serial_no, '')) as serial_no_ok
                            ,IF(final_finish_date IS NULL,'NG','OK') AS final_finish
                            ,aa.id_sr as sr_id
                            ,aa.topic_doc_no as rdc_no
                        FROM rd_request aa
                        LEFT JOIN rd_request_situation bb ON aa.id = bb.id_rd_request
                        LEFT JOIN rd_request_status cc ON cc.id = bb.id_rd_request_status
                        LEFT JOIN rd_request_plan dd ON bb.id = dd.id_rd_request_situation
                        LEFT JOIN sr ee ON aa.id_sr = ee.id
                        WHERE bb.id_rd_request_status = 1 
                        AND aa.cancel_by = ''
                        AND IF(dd.final_finish_date IS NULL, 'NG', 'OK') = 'OK'
                        GROUP By rd_request_status, final_finish,topic_doc_no) as tt ON tt.sr_id = b.id_sr AND tt.rdc_no = b.topic_doc_no
            
            WHERE b.topic_doc_no NOT LIKE 'SR%' 
            AND IFNULL(a.store_date, '0000-00-00') = '0000-00-00' 
            AND IFNULL(a.serial_no, '') = '' 
            AND a.mkt_issued_date  >= '2021-01-01'
            ";

   // WHERE a.sr_no = '1603/2018' AND a.mkt_issued_date  >= '2021-01-01'

    if($search != ""){
        $search = trim($search);
        $sql .= " AND (IF(f.model = '' OR f.model IS NULL, d.target_model_name,f.model) LIKE '%$search%' OR au.part_mo LIKE '%$search%'
                        OR a.type_of_compressor LIKE '%$search%' OR b.topic_doc_no LIKE '%$search%' OR a.mkt_issued_by LIKE '%$search%' 
                        OR  a.sr_no LIKE '%$search%' OR  a.serial_no LIKE '%$search%' OR  b.rd_mo LIKE '%$search%' OR  c.customer_name LIKE '%$search%'
                        OR COALESCE(NULLIF(d.orderingcode, ''), f.orderingcode, b.rd_orderingcode) LIKE '%$search%' 
                        OR a.request_rd_to_sent_to_store_within LIKE '%$search%' OR  a.qty LIKE '%$search%' OR  b.topic_amount LIKE '%$search%'
                        OR b.assembly_bomdev_ref LIKE '%$search%' OR  a.design_drawing LIKE '%$search%' OR  a.production_line  LIKE '%$search%'
                        OR b.topic_write_by LIKE '%$search%' OR IF(f.check_by = '' or f.check_by is null,d.rd_decision_by,f.check_by) LIKE '%$search%'
                        OR au.PLAN LIKE '%$search%' OR au.actual_assy LIKE '%$search%' OR au.Calorie_Plan_Test LIKE '%$search%' OR au.Calorie_M_C LIKE '%$search%'
                        OR au.Calorie_Plan_Finish LIKE '%$search%' OR au.Calorie_Actual_Finish LIKE '%$search%' OR au.Noise_Plan_Test LIKE '%$search%' 
                        OR au.Noise_M_C LIKE '%$search%' OR au.Noise_Plan_Finish LIKE '%$search%' OR au.Noise_Actual_Finish LIKE '%$search%' 
                        OR au.Locked_Roter_Plan_Test LIKE '%$search%'
                        OR au.Locked_Roter_M_C LIKE '%$search%' OR au.Locked_Roter_Plan_Finish LIKE '%$search%' OR au.Locked_Roter_Actual_Finish LIKE '%$search%' 
                        OR au.send_to_store LIKE '%$search%' OR tt.serial_no_ok LIKE '%$search%' OR  IF(h.state IS NULL,'Develop', h.state) LIKE '%$search%'
                        OR g.doc_code  LIKE '%$search%'
                       ) ";
    }
    $sql .= " GROUP BY b.topic_doc_no,a.sr_no ";
    $order = '';
    if(isset($_POST['order'])){
        $order = $_POST['order']['0']['dir'];
        if($order == 'asc') $sql .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' ASC ';
        else $sql .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' DESC ';
    }
    else{
        $order = 'DESC';
        $sql .= 'ORDER BY a.sr_no, b.topic_doc_no  ';
    }

    $q = $conn2->prepare($sql);
    $q->execute();
    $number_filter_row = $q->rowCount();

    $query_limit = '';
    if($length != -1){
        $query_limit = " LIMIT ".$start." , ".$length." ";
    }


    $q = $conn2->prepare($sql.$query_limit);
    $q->execute();
    $y=1;
    while($row = $q->fetch(PDO::FETCH_ASSOC)){
        $sub_array = array();
        $sub_array[] = $y;
        $sub_array[] = substr($row['Model'], 0, 1);
        $sub_array[] = '<div class="fix-width-100">'.$row['BOM'].'</div>';
        $sub_array[] = '<input type="text" class="inputAssy2" style="border-bottom: 1px double black;" name="part_mo" id="'.$row['id_rr'].'" value="'.$row['part_mo'].'"><div style="position: relative;"><i class="myInput fa-solid fa-pen-to-square"></i></div>';
        $sub_array[] = "<input type='hidden' id='docCode' name='docCode' value='" . $row['doc_code'] . "'>
        <span>" . $row['doc_code'] . "</span>
        <div style='position: relative;'>
            <a id='chevron-up" . $row['id_sr'] . $y . "' class='chevron-up' onclick='fetchDocCode(" . $row['id_sr'] . ", " . $row['id_sr'] . $y . ")'>
                <i class='icon-more-info fa-solid fa-chevron-up'></i>
            </a>
            <a id='chevron-down" . $row['id_sr'] . $y . "' class='chevron-down' onclick='hideDocCode(" . $row['id_sr'] . $y . ")'>
                <i class='icon-more-info fa-solid fa-chevron-down'></i>
            </a>
        </div>
        <div style='width: 100px;' id='" . $row['id_sr'] . $y . "'></div>";
        $id_sr = $row['id_sr'];
        $rdc_value = $row['RDC'];
        $sub_array[] = '<div class="fix-width-100">'.$row['request_rd_to_sent_to_store_within'].'</div>';
        $sub_array[] = '<div class="fix-width-200">'.$row['Type_Comp'].'</div>';
        $sub_array[] = $row['RDC'];
        $sub_array[] = '<div class="fix-width-100">'.$row['sr_no'].'</div>';
        $sub_array[] = '<div class="fix-width-100">'.$row['serial_no'].'</div>';
        if($row['mkt_approved_date'] != '' AND $row['mkt_approved_date'] != null AND $row['mkt_approved_date'] != '0000-00-00'){
            $mkt_approved_date = strtotime($row['mkt_approved_date']);
            $now = strtotime('now');
            $cal_approve_date = floor(($now - $mkt_approved_date) / (60 * 60 * 24));
            $cal_approve_date .= " days";

        }else{
            $cal_approve_date = "-";
        }
        $sub_array[] = '<div class="fix-width-100">'.$cal_approve_date.'</div>';
        $sub_array[] = '<div class="fix-width-200">'.$row['mkt_issued_by'].'</div>';
        $sub_array[] = '<div class="fix-width-100">'.$row['MO'].'</div>';
        $sub_array[] = '<div class="fix-width-200">'.$row['Customer'].'</div>';
        $sub_array[] = $row['Model'];
        $sub_array[] = '<div class="fix-width-100">'.$row['Code'].'</div>';
        $sub_array[] = '<div class="fix-width-100">'.$row['qty'].'</div>';
        $serial_no_ok = $row['serial_no_ok'];
        if($serial_no_ok == '' || $serial_no_ok == null){
            $serial_no_ok = 0;
        }
        $sub_array[] = '<div class="fix-width-100">'.$serial_no_ok.'</div>';
        $sub_array[] = '<div class="fix-width-100">'.$row['lot_size_rdc'].'</div>';
        $sub_array[] = '<div class="fix-width-100">'.$row['assembly_bomdev_ref'].'</div>';
        $sub_array[] = '<div class="fix-width-100">'.$row['design_drawing'].'</div>';
        $sub_array[] = '<div class="fix-width-100">'.$row['production_line'].'</div>';
        $sub_array[] = '<div class="fix-width-200">'.$row['topic_write_by'].'</div>';
        $sub_array[] = '<div class="fix-width-200">'.$row['Responsible'].'</div>';
        $sub_array[] = '<div class="fix-width-200">'.$row['request_rd_to_sent_to_store_within'].'</div>';
        if($bom != ''){
            $assy_plan = '';
            if($bom == 'Mass Production'){
                $assy_plan = "<div class='mass-bg'></div>";
            }else if ($row['PLAN'] == $row['PLAN2']) {
                $assy_plan = '<input type="text" class="assy_plan inputAssy1" name="PLAN" id="' . $row['id_rr'] .'" value="' . $row['PLAN'] . '">
                                <i class="myCalendar2 fa-regular fa-calendar"></i><br>' ;
            }else if ($row['PLAN'] != $row['PLAN2'] && $row['PLAN2'] != "" && $row['PLAN'] !=""){
                if($row['PLAN3'] != '' AND $row['PLAN3'] != null){
                    $assy_plan = '<input type="text" class="assy_plan inputAssy1" style="color:red;" name="PLAN" id="' . $row['id_rr'] .'" style="color: red;" value="' . $row['PLAN'] . '">
                        <i class="myCalendar2 fa-regular fa-calendar"></i><br>
                        <input class="revive_date" value="'.$row['PLAN3'].'" readonly><br>
                        <input class="revive_date" value="'.$row['PLAN2'].'" readonly>';
                }else{
                    $assy_plan = '<input type="text" class="assy_plan inputAssy1" style="color:red;" name="PLAN" id="' . $row['id_rr'] .'" style="color: red;" value="' . $row['PLAN'] . '"><i class="myCalendar2 fa-regular fa-calendar"></i><br>
                                    <input class="revive_date" value="'.$row['PLAN2'].'" readonly><br>';
                }
            }else {
                $assy_plan = '<input type="text" class="assy_plan inputAssy1" name="PLAN" id="' . $row['id_rr'] .'" value="' . $row['PLAN'] . '">
                              <i class="myCalendar2 fa-regular fa-calendar"></i><br>' ;
            }
        }
        $sub_array[] = $assy_plan;
        $sub_array[] = $row['actual_assy'];
        $sub_array[] = '<div style="display: flex;">
                            <a data-toggle="tooltip"  onclick="window.open(\'../../../../../iamhere/www/createdata/createpdfrdrequest2.php?id_rd_request=' . parameter_encode($row['id_rr']) . '&rdc=' . parameter_encode($row['RDC']) . '&check=1\',null,\'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes\');"  class="myView"><i class="fa-regular fa-file"></i></a>
                            &nbsp;&nbsp;
                            <a data-toggle="tooltip" onclick="window.open(\'/iamhere/www/createdata/createpdfprintqr2.php?id_rd_request=' . parameter_encode($row['id_rr']) . '&rdc=' . parameter_encode($row['RDC']) . '&check=&plot=1\',null,\'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes\');" class="myView"><i class="fa-solid fa-qrcode"></i></a>
                            &nbsp;&nbsp;
                            <a data-toggle="tooltip" onclick="window.open(\'/iamhere/www/createdata/createpdfprintqr2.php?id_rd_request=' . parameter_encode($row['id_rr']) . '&rdc=' . parameter_encode($row['RDC']) . '&check=&plot=2\',null,\'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes\');" class="myView"><i class="fa-solid fa-qrcode"></i></a>
                            &nbsp;&nbsp;
                            <a data-toggle="tooltip" onclick="window.open(\'/iamhere/www/createdata/createpdfprintqr2.php?id_rd_request=' . parameter_encode($row['id_rr']) . '&rdc=' . parameter_encode($row['RDC']) . '&check=&plot=3\',null,\'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes\');" class="myView"><i class="fa-solid fa-qrcode"></i></a>
                            &nbsp;&nbsp;
                            <a data-toggle="tooltip" onclick="window.open(\'/iamhere/www/createdata/createpdfprintqr2.php?id_rd_request=' . parameter_encode($row['id_rr']) . '&rdc=' . parameter_encode($row['RDC']) . '&check=&plot=4\',null,\'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes\');" class="myView"><i class="fa-solid fa-qrcode"></i></a>
                        </div>';


        $sub_array[] = '<input type="text" class="inputAssy1" name="Calorie_Plan_Test" id="'.$row["id_rr"] .'" value="'.$row["Calorie_Plan_Test"] .'" ><i class="myCalendar2 fa-regular fa-calendar">';
        $options = array("- - - Select M/C - - -","Z01", "Z013", "Z014","Z016","Z017","Z018","Z019",);
        $select_options = ''; 
        foreach ($options as $option) {
            $selected = ($option == $row['Calorie_M_C']) ? 'selected' : '';
            $select_options .= "<option value=\"$option\" $selected>$option</option>";
        }
    
        $sub_array[] = '<select class="inputAssy1 form-select" aria-label="Default select example" style="text-align: center;" name="Calorie_M_C" id="'.$row['id_rr'].'">'.$select_options.'</select>';
        $sub_array[] = '<input type="text" class="inputAssy1" name="Calorie_Plan_Finish" id="'.$row["id_rr"] .'" value="'.$row["Calorie_Plan_Finish"] .'" ><i class="myCalendar2 fa-regular fa-calendar">';
        $sub_array[] = '<input type="text" class="inputAssy1" name="Calorie_Actual_Finish" id="'.$row["id_rr"] .'" value="'.$row["Calorie_Actual_Finish"] .'" ><i class="myCalendar2 fa-regular fa-calendar">';
        $sub_array[] = '<input type="text" class="inputAssy1" name="Noise_Plan_Test" id="'.$row["id_rr"] .'" value="'.$row["Noise_Plan_Test"] .'" ><i class="myCalendar2 fa-regular fa-calendar">';
        
        $options2 = array("- - - Select M/C - - -","Z02-1", "Z02-2", "Z02-3");
        $select_options2 = ''; 
        foreach ($options2 as $option2) {
            $selected2 = ($option2 == $row['Noise_M_C']) ? 'selected' : '';
            $select_options2 .= "<option value=\"$option2\" $selected2>$option2</option>";
        }
        $sub_array[] = '<select class="inputAssy1 form-select" aria-label="Default select example" style="text-align: center;" name="Noise_M_C" id="'.$row['id_rr'].'">'.$select_options2.'</select>';
        $sub_array[] = '<input type="text" class="inputAssy1" name="Noise_Plan_Finish" id="'.$row["id_rr"] .'" value="'.$row["Noise_Plan_Finish"] .'" ><i class="myCalendar2 fa-regular fa-calendar">';
        $sub_array[] = '<input type="text" class="inputAssy1" name="Noise_Actual_Finish" id="'.$row["id_rr"] .'" value="'.$row["Noise_Actual_Finish"] .'" ><i class="myCalendar2 fa-regular fa-calendar">';
        $sub_array[] = '<input type="text" class="inputAssy1" name="Locked_Roter_Plan_Test" id="'.$row["id_rr"] .'" value="'.$row["Locked_Roter_Plan_Test"] .'" ><i class="myCalendar2 fa-regular fa-calendar">';
        
        $options3 = array("- - - Select M/C - - -","Z051", "Z052");
        $select_options3 = ''; 
        foreach ($options3 as $option3) {
            $selected3 = ($option3 == $row['Locked_Roter_M_C']) ? 'selected' : '';
            $select_options3 .= "<option value=\"$option3\" $selected3>$option3</option>";
        }
        $sub_array[] = '<select class="inputAssy1 form-select" aria-label="Default select example" style="text-align: center;" name="Locked_Roter_M_C" id="'.$row['id_rr'].'">'.$select_options3.'</select>';
        $sub_array[] = '<input type="text" class="inputAssy1" name="Locked_Roter_Plan_Finish" id="'.$row["id_rr"] .'" value="'.$row["Locked_Roter_Plan_Finish"] .'" ><i class="myCalendar2 fa-regular fa-calendar">';
        $sub_array[] = '<input type="text" class="inputAssy1" name="Locked_Roter_Actual_Finish" id="'.$row["id_rr"] .'" value="'.$row["Locked_Roter_Actual_Finish"] .'" ><i class="myCalendar2 fa-regular fa-calendar">';
        $arr_check = [
            $row['send_to_store'],
            $row['new_send_to_store_1'],
            $row['new_send_to_store_2']
        ];
        $max = max($row['send_to_store'], $row['new_send_to_store_1'], $row['new_send_to_store_2']);
        $min = min($row['send_to_store'], $row['new_send_to_store_1'], $row['new_send_to_store_2']);
        $mid = '';

        if($max == 0){
            $max = '';
        }
        if($min == 0){
            $min = '';
        }
        
        // Check and assign $mid
        for ($i = 0; $i < count($arr_check); $i++) {
            if($arr_check[$i] != $max && $arr_check[$i] != $min) {
                $mid = $arr_check[$i];
            }
        }
        if($min == '' && $mid == ''){
            $font_color = 'black';
            $bg_color = 'none';
        }else{
            $font_color = 'red';
            $bg_color = '#fdfdb8';
        }
        $send_to_store = '<input type="text" style="color:'.$font_color.';" class="inputAssy1" name="send_to_store" id="'.$row["id_rr"].'" value="'.$max.'" >
                          <i class="myCalendar2 fa-regular fa-calendar"></i><br>';
        if($mid != ''){ 
           $send_to_store .= '<input class="revive_date"  value="'.$mid.'" readonly><br>';
        } 
        if($min != ''){
            $send_to_store .= '<input class="revive_date"  value="'.$min.'" readonly><br>';
        }
           
        
        
        $sub_array[] = $send_to_store;
        $sub_array[] = '<div class="fix-width-100">'.$row['store_date'].'</div>';
        $data[] = $sub_array;
        $y++;
    }
    $q->closeCursor();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

try {
    $sql = "SELECT 
    MAX(b.id) as id_rr,
    b.topic_doc_no as RDC, 
    b.rd_mo as MO,
    b.topic_amount as lot_rdc, 
    b.assembly_bomdev_ref,
    b.topic_write_by as RDC_Request_by,
    b.rd_orderingcode,
    a.id as id_sr,
    a.design_drawing,
    a.production_line,
    a.request_rd_to_sent_to_store_within as Date_JR_JM_JN,
    a.type_of_compressor as Type_Comp,
    a.sr_no,
    a.serial_no as serial_no_sr,
    a.qty as qty,
    a.customer_id,
    a.mkt_issued_by,
    a.mkt_approved_date,
    a.model_npr_id,
    a.comp_id,
    a.request_rd_to_sent_to_store_within as request_rd_to_sent_to_store_within,
    a.store_date as store_date,
    a.type_of_compressor,
    c.customer_name as Customer, 
    COALESCE(NULLIF(d.orderingcode, ''), f.orderingcode, b.rd_orderingcode) AS Code,
    IF(f.model = '' OR f.model IS NULL, d.target_model_name,f.model) AS Model, 
    IF(f.check_by = '' or f.check_by is null,d.rd_decision_by,f.check_by) as Responsible,
    g.doc_code as doc_code,
    au.part_mo,
    au.prevPLAN,
    au.PLAN,
    au.PLAN2,
    au.PLAN3,
    au.actual_assy,
    au.Calorie_Plan_Test,
    au.Calorie_M_C,
    au.Calorie_Plan_Finish,
    au.Calorie_Actual_Finish,
    au.Noise_Plan_Test,
    au.Noise_M_C,
    au.Noise_Plan_Finish,
    au.Noise_Actual_Finish,
    au.Locked_Roter_Plan_Test,
    au.Locked_Roter_M_C,
    au.Locked_Roter_Plan_Finish,
    au.Locked_Roter_Actual_Finish,
    au.send_to_store,
    au.new_send_to_store_1,
    au.new_send_to_store_2

FROM  rd_request b 
LEFT JOIN  sr a  ON a.id = b.id_sr
LEFT JOIN customer c ON a.customer_id = c.customer_id  
LEFT JOIN npr d ON a.model_npr_id = d.id
LEFT JOIN comp_data f ON a.comp_id = f.id
LEFT JOIN vw_part_request g ON g.doc_input_sr = a.id
LEFT JOIN answer_leader au ON au.id_rd_request = b.id OR au.topic_doc_no = b.topic_doc_no 

WHERE b.topic_doc_no NOT LIKE 'SR%' 
AND IFNULL(a.store_date, '0000-00-00') = '0000-00-00' 
AND IFNULL(a.serial_no, '') = '' 
AND a.mkt_issued_date  >= '2021-01-01'
GROUP BY b.topic_doc_no,a.sr_no
";
    $q = $conn2->prepare($sql);
    $q->execute();
    $recordsTotal = $q->rowCount();

    $output = array(
        "draw" => intval($_POST["draw"]),
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $number_filter_row,
        "data" => $data
    );

    echo json_encode($output);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>