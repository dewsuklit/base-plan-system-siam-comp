<?php
include_once '../DBconfig/dbConfig2.php';
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment; 


if ($db2->connect_error) {
    die("Connection failed: " . $db2->connect_error);
}
if($_REQUEST['file'] == 1){

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $filename = $_REQUEST['filename'];
    $decodedFilename = base64_decode($filename);

    // $sql = "SELECT file_name, file_data FROM assy_excel_filename WHERE file_name = ?";
    // $stmt = $db2->prepare($sql);
    // $stmt->bind_param("s", $decodedFilename);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // if ($result->num_rows > 0) {
    //     // อ่านข้อมูล
    //     while($row = $result->fetch_assoc()) {
    //         $filename = $row["file_name"];
    //         $filedata = $row["file_data"];
            
    //         // ตั้งค่า header สำหรับการดาวน์โหลดไฟล์
    //         header('Content-Description: File Transfer');
    //         header('Content-Type: application/octet-stream');
    //         header('Content-Disposition: attachment; filename="' . $filename . '"');
    //         header('Expires: 0');
    //         header('Cache-Control: must-revalidate');
    //         header('Pragma: public');
    //         header('Content-Length: ' . strlen($filedata));
    //         ob_clean();
    //         flush();
            
    //         // แสดงข้อมูลไฟล์
    //         echo $filedata;
    //     }
    // } else {
    //     echo "No file found";
    // }

    // // ปิดการเชื่อมต่อฐานข้อมูล
    // $stmt->close();
    // $db2->close();



    $sheet->setCellValue('A1', 'RDC ASSEMBLY REQUEST'); // กำหนดค่าให้เซลล์ A1
    $sheet->mergeCells('A1:N1');

    $colHeaders = ['Series', 'Model Mass', 'Status','Project','Type Comp', 'RDC', 'MO/SR', 'Customer', 'Model', 'Code', 'Lot', 'Mkt_issued', 'Mkt_by', 'Request date']; // แก้ตามจำนวนและชื่อคอลัมน์ที่ต้องการ
    $col = 1;
    foreach ($colHeaders as $header) {
        $sheet->setCellValueByColumnAndRow($col, 2, $header);
        $sheet->getStyleByColumnAndRow($col, 2)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('fff2cc');
        $sheet->getStyleByColumnAndRow($col, 2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // เพิ่มเส้นขอบให้กับเซลล์
        $sheet->getStyleByColumnAndRow($col, 2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // จัดให้ตัวหนังสือในเซลล์อยู่กึ่งกลางแนวนอน
        $col++;
    }
    // สิ้นสุดการเพิ่มหัวคอลัมน์
    $sheet->getColumnDimension('A')->setWidth(96/7);
    $sheet->getColumnDimension('B')->setWidth(175/7);
    $sheet->getColumnDimension('C')->setWidth(130/7);
    $sheet->getColumnDimension('D')->setWidth(388/7);
    $sheet->getColumnDimension('E')->setWidth(188/7);
    $sheet->getColumnDimension('F')->setWidth(160/7);
    $sheet->getColumnDimension('G')->setWidth(300/7);
    $sheet->getColumnDimension('H')->setWidth(480/7);
    $sheet->getColumnDimension('I')->setWidth(270/7);
    $sheet->getColumnDimension('J')->setWidth(134/7);
    $sheet->getColumnDimension('K')->setWidth(90/7);
    $sheet->getColumnDimension('L')->setWidth(200/7);
    $sheet->getColumnDimension('M')->setWidth(190/7);
    $sheet->getColumnDimension('N')->setWidth(90/7);
    // เรียกข้อมูลจากฐานข้อมูล
    $sql = "SELECT SERIES, Model_Mass, STATUSs, PROJECT, Type_comp, RDC, MO_SR, CUSTOMER, MODEL, CODE, LOT, Issued, REQUEST_BY, Request FROM assy_upload WHERE file_name = '$decodedFilename' ORDER BY id ASC";
    $result = $db2->query($sql);

    // เช็คว่ามีข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        $row = 3; // เริ่มต้นเพิ่มข้อมูลในแถวที่ 2 เนื่องจากแถวแรกเป็นหัวคอลัมน์
        while ($row_data = $result->fetch_assoc()) {
            $col = 1;
            foreach ($row_data as $value) {
                $sheet->setCellValueByColumnAndRow($col, $row, $value);
                $sheet->getStyleByColumnAndRow($col, $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyleByColumnAndRow($col, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); 
                $col++;
            }
            $row++;
        }
    } else {
        echo "0 results";
    }

    // สร้างไฟล์ Excel
    $writer = new Xlsx($spreadsheet);
    $outputFileName = "C:/Users/pimpedt/Downloads/{$decodedFilename}";
    $counter = 1;
    while (file_exists($outputFileName)) {
        $filenameWithout_xlsx = str_replace('.xlsx', '', $decodedFilename);
        $outputFileName = "C:/Users/pimpedt/Downloads/{$filenameWithout_xlsx} ($counter).xlsx";
        $counter++;
    }
    $writer->save($outputFileName);


    echo "<script>alert('Download file successfully'); location.href = '../LeaderRD/base_plan.php'</script>";
}else{
    $filename = $_REQUEST['filename'];
    $decodedFilename = base64_decode($filename);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $filename = $_REQUEST['filename'];
    $decodedFilename = base64_decode($filename);
    $sheet->setCellValue('A1', 'RDC ASSEMBLY REQUEST'); // กำหนดค่าให้เซลล์ A1
    $sheet->mergeCells('A1:AC1');
    $colHeaders = ['Series', 'Model Mass', 'Type','Status','Project','Type Comp', 'RDC', 'MO/SR', 'Customer', 'Model', 'Code', 'Lot', 'Issued', 'Request by ', 'Responsible by ', 'Request', 'Assy Plan', 'Actual Assy']; // แก้ตามจำนวนและชื่อคอลัมน์ที่ต้องการ
    $col = 1;
    foreach ($colHeaders as $header) {
        $sheet->setCellValueByColumnAndRow($col, 2, $header);
        $sheet->getStyleByColumnAndRow($col, 2)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('fff2cc');
        $sheet->getStyleByColumnAndRow($col, 2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // เพิ่มเส้นขอบให้กับเซลล์
        $sheet->getStyleByColumnAndRow($col, 3)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // เพิ่มเส้นขอบให้กับเซลล์
        $sheet->getStyleByColumnAndRow($col, 2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // จัดให้ตัวหนังสือในเซลล์อยู่กึ่งกลางแนวนอน
        $col++;
    }
    $col2 = 19;
    $col3 = 16;
    $colHeaders2 = ['Calorie Test','Noise Test','Locked Rotor Test'];
    $colHeaders3 = ['Plan Test','M/C','Plan Finish','Actual Finish','Plan Test','M/C','Plan Finish','Actual Finish','Plan Test','Plan Finish','Actual Finish',''];
    foreach ($colHeaders2 as $header2) {
        $sheet->setCellValueByColumnAndRow($col2, 2, $header2);
        $sheet->getStyleByColumnAndRow($col2, 2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyleByColumnAndRow($col3, 2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // $sheet->getStyleByColumnAndRow($col, 2)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('fff2cc');
        // $sheet->getStyleByColumnAndRow($col, 2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // เพิ่มเส้นขอบให้กับเซลล์
        // $sheet->getStyleByColumnAndRow($col, 3)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // เพิ่มเส้นขอบให้กับเซลล์
         // จัดให้ตัวหนังสือในเซลล์อยู่กึ่งกลางแนวนอน
        $col3++;
        $col2 = $col2 + 4;
        
    }
    foreach ($colHeaders3 as $header3) {
        $sheet->setCellValueByColumnAndRow($col3, 3, $header3);
        $sheet->getStyleByColumnAndRow($col3, 3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyleByColumnAndRow($col3, 2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyleByColumnAndRow($col3, 3)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // $sheet->getStyleByColumnAndRow($col, 2)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('fff2cc');
        // $sheet->getStyleByColumnAndRow($col, 2)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // เพิ่มเส้นขอบให้กับเซลล์
        // $sheet->getStyleByColumnAndRow($col, 3)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // เพิ่มเส้นขอบให้กับเซลล์
         // จัดให้ตัวหนังสือในเซลล์อยู่กึ่งกลางแนวนอน
        $col3++;
        
    }
    $sheet->setCellValue('AD2', 'Send to store');
    $sheet->getStyle('AD2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A2:A3');
    $sheet->mergeCells('B2:B3');
    $sheet->mergeCells('C2:C3');
    $sheet->mergeCells('D2:D3');
    $sheet->mergeCells('E2:E3');
    $sheet->mergeCells('F2:F3');
    $sheet->mergeCells('G2:G3');
    $sheet->mergeCells('H2:H3');
    $sheet->mergeCells('I2:I3');
    $sheet->mergeCells('J2:J3');
    $sheet->mergeCells('K2:K3');
    $sheet->mergeCells('L2:L3');
    $sheet->mergeCells('M2:M3');
    $sheet->mergeCells('N2:N3');
    $sheet->mergeCells('O2:O3');
    $sheet->mergeCells('P2:P3');
    $sheet->mergeCells('Q2:Q3');
    $sheet->mergeCells('R2:R3');
    $sheet->mergeCells('S2:V2');
    $sheet->mergeCells('W2:Z2');
    $sheet->mergeCells('AA2:AC2');
    $sheet->mergeCells('AD2:AD3');
    // $sheet->mergeCells('S2:S3');
    // $sheet->mergeCells('T2:T3');
    // $sheet->mergeCells('T2:V1'); // คอลัมน์ CALORIE TEST
    // $sheet->mergeCells('U2:V3'); 
    $sheet->getColumnDimension('A')->setWidth(75/7);
    $sheet->getColumnDimension('B')->setWidth(140/7);
    $sheet->getColumnDimension('C')->setWidth(120/7);
    $sheet->getColumnDimension('D')->setWidth(95/7);
    $sheet->getColumnDimension('E')->setWidth(555/7);
    $sheet->getColumnDimension('F')->setWidth(310/7);
    $sheet->getColumnDimension('G')->setWidth(150/7);
    $sheet->getColumnDimension('H')->setWidth(100/7);
    $sheet->getColumnDimension('I')->setWidth(435/7);
    $sheet->getColumnDimension('J')->setWidth(205/7);
    $sheet->getColumnDimension('L')->setWidth(66/7);
    $sheet->getColumnDimension('M')->setWidth(135/7);
    $sheet->getColumnDimension('N')->setWidth(197/7);
    $sheet->getColumnDimension('O')->setWidth(140/7);
    $sheet->getColumnDimension('P')->setWidth(120/7);
    $sheet->getColumnDimension('Q')->setWidth(145/7);
    $sheet->getColumnDimension('R')->setWidth(145/7);
    $sheet->getColumnDimension('S')->setWidth(100/7);
    $sheet->getColumnDimension('T')->setWidth(100/7);
    $sheet->getColumnDimension('U')->setWidth(100/7);
    $sheet->getColumnDimension('V')->setWidth(100/7);
    $sheet->getColumnDimension('W')->setWidth(100/7);
    $sheet->getColumnDimension('X')->setWidth(100/7);
    $sheet->getColumnDimension('Y')->setWidth(100/7);
    $sheet->getColumnDimension('Z')->setWidth(100/7);
    $sheet->getColumnDimension('AA')->setWidth(100/7);
    $sheet->getColumnDimension('AB')->setWidth(100/7);
    $sheet->getColumnDimension('AC')->setWidth(100/7);
    $sheet->getColumnDimension('AD')->setWidth(100/7);
    
    $sql = "SELECT  au.SERIES, 
                    au.Model_Mass, 
                    au.BOM, 
                    au.STATUSs, 
                    au.PROJECT, 
                    au.Type_comp, 
                    au.RDC, 
                    au.MO_SR, 
                    au.CUSTOMER, 
                    au.MODEL, 
                    au.CODE, 
                    au.LOT, 
                    au.Issued, 
                    au.REQUEST_BY, 
                    IF(d.check_by = '' OR d.check_by IS NULL, f.rd_decision_by, d.check_by) AS responsible, 
                    au.Request, 
                    au.PLAN, 
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
                    au.Locked_Roter_Plan_Finish, 
                    au.Locked_Roter_Actual_Finish, 
                    au.send_to_store 
            FROM assy_upload au 
            LEFT JOIN sr s ON au.MO_SR = s.sr_no 
            LEFT OUTER JOIN comp_data d ON s.comp_id = d.id 
            LEFT OUTER JOIN npr f ON s.model_npr_id = f.id 
            WHERE file_name = '$decodedFilename' 
            ORDER BY au.id ASC;
            ";
    $result = $db2->query($sql);

    // // เช็คว่ามีข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        $row = 4;
        while ($row_data = $result->fetch_assoc()) {
            $col = 1;
            foreach ($row_data as $value) {
                $sheet->setCellValueByColumnAndRow($col, $row, $value);
                $sheet->getStyleByColumnAndRow($col, $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyleByColumnAndRow($col, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); 
                $col++;
            }
            $row++;
        }
    } else {
        echo "0 results";
    }
    $writer = new Xlsx($spreadsheet);
    $outputFileName = "C:/Users/pimpedt/Downloads/{$decodedFilename}";
    $counter = 1;
    while (file_exists($outputFileName)) {
        $filenameWithout_xlsx = str_replace('.xlsx', '', $decodedFilename);
        $outputFileName = "C:/Users/pimpedt/Downloads/{$filenameWithout_xlsx} ($counter).xlsx";
        $counter++;
    }
    $writer->save($outputFileName);


    echo "<script>alert('Download file successfully'); location.href = '../LeaderRD/base_plan.php'</script>";
}
    // ปิดการเชื่อมต่อฐานข้อมูล
    $db2->close();
?>
