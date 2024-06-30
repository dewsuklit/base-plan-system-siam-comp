<?php
// Load the database configuration file
session_start();

if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}


$sortfile = $_SESSION['filename'];
include_once '../DBconfig/dbConfig2.php';
if (isset($_GET['id']) && isset($_GET['srno'])) {
    $encodedIdSr = $_GET['id'];
    $encodedSrNo = $_GET['srno'];
    $encodedBom = $_GET['bom'];
    
    // $id = 10498;
    $sr_no = base64_decode($encodedSrNo);
    $id = base64_decode($encodedIdSr);
    $BOM = base64_decode($encodedBom);

    $Query2 = "SELECT comp_id FROM sr WHERE id = '$id'";
    $Result2 = $db2->query($Query2) or die($db2->error);

    if ($Result2) {
        $line = $Result2->fetch_assoc();
        $comp_id = $line['comp_id'];

        // Free the result set
        mysqli_free_result($Result2);
        if($comp_id != 0){
            $Query2="select *,d.model as model,d.four_digit as four_digit ,
            a.mkt_issued_date as srmkt_issued_date ,a.check_four_date as srcheck_four_date,
            a.mkt_checked_date as srmkt_checked_date,a.mkt_verified_date as srmkt_verified_date
            ,a.mkt_approved_date as srmkt_approved_date,a.other_file as sr_other_flie
            from sr a 
            left outer join customer b on a.customer_id = b.customer_id 
            left outer join department c on a.department_id = c.id 
            left outer join comp_data d on a.comp_id=d.id 
            left outer join npr e on e.id = d.id_npr 
            left outer join user f on f.id = e.rd_responsibility
            where a.id = '$id' ";
        }else{
            $Query2="select *,d.target_model_name as model,d.fourdigit as four_digit ,
            a.mkt_issued_date as srmkt_issued_date ,a.check_four_date as srcheck_four_date,
            a.mkt_checked_date as srmkt_checked_date,a.mkt_verified_date as srmkt_verified_date
            ,a.mkt_approved_date as srmkt_approved_date,a.other_file as sr_other_flie
            from sr a 
            left outer join customer b on a.customer_id = b.customer_id 
            left outer join department c on a.department_id = c.id 
            left outer join npr d on a.model_npr_id=d.id 
            left outer join user e on d.rd_responsibility = e.id
            where a.id = '$id' ";
        } 	
        $Result2 = $db2->query($Query2) or die($db2->error);
        $line = $Result2->fetch_assoc();
        $txt_detail_sale_admin = $line["detail_sale_admin"];
        $txt_sr_no = $line["sr_no"];
        // echo "<script>alert('$id');</script>";
        $txt_enginner_rd_respon = $line["name"];
        $txt_enginner_rd_respon = $line["pass_en_rd_by"];//$txt_orderingcode = $line["orderingcode"];
        $txt_customer_name = $line["customer_name"];
        $txt_department = $line["department"];
        $txt_customer_request_sample_to_mkt_date = $line["customer_request_sample_to_mkt_date"];
        $txt_ship_method = $line["ship_method"];
        $txt_purpose_of_sr = $line["purpose_of_sr"];
        $test_performance = $line["test_performance"];
        $txt_additional_data = $line["additional_data"];
        $txt_detail_addition_file = $line["sr_other_flie"];
        $txt_project_id = $line["project_id"];
        $txt_model = $line["model"];
        $txt_status= $line["status"];
        $txt_four_digit = $line["four_digit"];
        $txt_qty = $line["qty"];
        $reason_free = $line['reason_free'];
        $agile_team = $line['agile_team'];
        if($agile_team != "") $agile_team = "  Agile : ". $agile_team;
        $sr_from = $line['sr_from'];
        $txt_leader_rd_remark = $line["leader_rd_remark"];
        $txt_rd_take_comp_from = $line["rd_take_comp_from"];
        $txt_payment_condition = $line["payment_condition"];
        $txt_test_report_require = $line["test_report_require"];
        $txt_request_rd_to_sent_to_store_within = $line["request_rd_to_sent_to_store_within"];
        $txt_sight_glass_number = $line["sight_glass_number"];
        $txt_sight_glass_file = $line["sight_glass_file"];
        $txt_attach_report_date = $line["file_attach_report_date"];
        $txt_file_attach_report = $line["file_attach_report"];
        $txt_rd_report_file = $line["rd_report_file"];
        
        $txt_rd_informed_1st_schedule_to_mkt_date = $line["rd_informed_1st_schedule_to_mkt_date"];
        $txt_rd_informed_1st_schedule_to_mkt_date_edit_by = $line["rd_informed_1st_schedule_to_mkt_date_edit_by"];
        $txt_rd_informed_revised1_schedule_to_mkt_date = $line["rd_informed_revised1_schedule_to_mkt_date"];
        $txt_rd_informed_revised1_schedule_to_mkt_date_edit_by = $line["rd_informed_revised1_schedule_to_mkt_date_edit_by"];
        $txt_rd_informed_revised2_schedule_to_mkt_date = $line["rd_informed_revised2_schedule_to_mkt_date"];
        $txt_rd_informed_revised2_schedule_to_mkt_date_edit_by = $line["rd_informed_revised2_schedule_to_mkt_date_edit_by"];
        $txt_rd_informed_1st_schedule_to_mkt_date = $line["rd_informed_1st_schedule_to_mkt_date"];
        $txt_rd_informed_revised1_schedule_to_mkt_date = $line["rd_informed_revised1_schedule_to_mkt_date"];
        $txt_rd_informed_revised2_schedule_to_mkt_date = $line["rd_informed_revised2_schedule_to_mkt_date"];
        
        $txt_rd_get_part_from_prod = $line["rd_get_part_from_prod"];
        $txt_rd_send_to_store = $line["rd_send_to_store"];
        $txt_new_date_to_store_rev1 = $line["new_date_to_store_rev1"];
        $txt_new_date_to_store_rev2 = $line["new_date_to_store_rev2"];
        $txt_serial_no = $line["serial_no"];
        $txt_store_date = $line["store_date"];
        
        $txt_mkt_informed_schedule_to_customer_date = $line["mkt_informed_schedule_to_customer_date"];
        $txt_mkt_informed_schedule_to_customer_reason = $line["mkt_informed_schedule_to_customer_reason"];
        $txt_mkt_revised_sr1_date = $line["mkt_revised_sr1_date"];
        $txt_mkt_revised_sr1_reason = $line["mkt_revised_sr1_reason"];
        $txt_mkt_revised_sr2_date = $line["mkt_revised_sr2_date"];
        $txt_mkt_revised_sr2_reason = $line["mkt_revised_sr2_reason"];
        $txt_mkt_already_sent_to_customer_on_date = $line["mkt_already_sent_to_customer_on_date"];
        
        $mkt_issued_date = $line['srmkt_issued_date'];
        $check_four_date = $line['srcheck_four_date'];
        $mkt_checked_date = $line['srmkt_checked_date'];
        $mkt_verified_date = $line['srmkt_verified_date'];
        $mkt_approved_date = $line['srmkt_approved_date'];
        $rd_answer_leader_by = $line['rd_answer_leader_by'];
        $mkt_already_sent_to_customer_on_by = $line["mkt_already_sent_to_customer_on_by"];
        
        
        $txt_sr_cancle_by = $line["sr_cancle_by"];
        $txt_sr_cancle_date = $line["sr_cancle_date"];
        $txt_mkt_sr_cancle_reason = $line["reason_of_cancle"];
        $txt_rd_accept_cancel_by = $line["rd_accept_cancel_by"];
        $txt_rd_accept_cancel_date = $line["rd_accept_cancel_date"];
        $txt_request_cancel_by = $line["request_cancel_by"];
        $txt_request_cancel_date = $line["request_cancel_date"];
        $txt_request_cancel_reason = $line["request_cancel_reason"];
        $txt_approve_request_cancel_by = $line["approve_request_cancel_by"];
        $txt_approve_request_cancel_date = $line["approve_request_cancel_date"];
        $txt_request_cancel_canstop = $line["request_cancel_canstop"];
        $txt_rd_accept_cancel_reason = $line["rd_accept_cancel_reason"];
        
        
        $txt_engineer_rd_detail = $line["engineer_rd_detail"];
        $txt_engineer_rd_file = $line["engineer_rd_file"];
        $leader_rd_confirm_orderingcode = $line["leader_rd_confirm_orderingcode"];
        mysqli_free_result($Result2);
        
        
        $sr_from_rd = 'SR'.$txt_sr_no;
        $Querysr = "SELECT * FROM rd_request a left join sr b on a.id_sr = b.id where b.sr_no = '$txt_sr_no' ";
        $Resultsr = $db2->query($Querysr) or die($db2->error);
        $linesr = $Resultsr->fetch_assoc();
        $sr_id_rd = $linesr["id"]; 
        mysqli_free_result($Resultsr);
        
        $room_arr = array();
        $Query4 = "SELECT b.rd_request_status FROM `rd_request_situation` a inner join rd_request_status b on a.id_rd_request_status =b.id where a.id_rd_request = '$sr_id_rd'  ";
        $Result4 =  $db2->query($Query4);
        if ($Result4->num_rows > 0) {
                while ($line4 = $Result4->fetch_assoc()) {
                    array_push($room_arr,$line4["rd_request_status"]);       
                }
    
        }else{
            array_push($room_arr,"0"); 
        }
    
    } else {
        echo "Error: " . $db2->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Siamcompressor</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/fontawesome-free-6.5.1-web/css/all.min.css">
    <style>
        .myTable2 thead tr th,
        .myTable2 tr td{
            border:1px solid gray
        }
        .table-dark{
			background-color: #7788c5!important;
		}
    </style>
</head>

<body id="page-top">
    <!-- Display status message -->
    <!-- <?php if(!empty($statusMsg)){ ?>
    <div class="col-xs-12 p-3">
        <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
    </div>
    <?php } ?> -->
    <!-- Page Wrapper -->
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="https://localhost/sci_web_admin/www/index2.php?option=9fd1d395c4d194cbd4"><img src="../image/logo.png"></a>
                    </div>
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" onclick="menuToggle()">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="menu-nav">
                        <nav class="nav nav-pills flex-column flex-sm-row">
                            <a class="menuSR flex-sm-fill text-sm-center nav-link active" aria-current="page" href="../EngineerRD/index.php">
                                Sample Request
                                <div class="myActiveBar2"></div>
                            </a>
                            <a class="flex-sm-fill text-sm-center nav-link" href="../LeaderRD/base_plan.php">Plan</a>
                        </nav>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["session_name"]; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <div class="back-menu-container">
                    <a onclick="window.history.back();" class="back-menu" >
                        <i class="fa-solid fa-left-long"></i>
                    </a>
                </div>
                <div class="container-fluid">

                  
                    <div class="row2" id="row2">

                        <!-- Area Chart -->
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">SR View</h6>
                                    
                                    
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="myTable" width="100%" class="cpanel" border="1">
                                            <thead class="table-dark" style="font-size:15px">
                                                <tr>
                                                    <td class="labelnprsubjectchange" colspan="100%">Date</td>
                                                </tr>
                                            </thead>
                                                <tr>
                                                    <td colspan="100%" style="height:10px"></td>
                                                </tr>
                                                <tr >
                                                    <td class="label" >Sample Request No. :  </td>
                                                    <td class="control" ><?=$txt_sr_no?></td>
                                                </tr>
                                                <tr>
                                                    <td class="label" >MKT Issued Date : </td>
                                                    <td class="control" ><?=$mkt_issued_date?></td>
                                                </tr>
                                                <tr>
                                                    <td class="label" >TIC Check 4Digit : </td>
                                                    <td class="control" ><?=$check_four_date?></td>
                                                </tr>
                                                <tr>
                                                    <td class="label" >Sale Admin : </td>
                                                    <td class="control" ><?=$mkt_checked_date?></td>
                                                </tr>
                                                <tr>
                                                    <td class="label" >Dept.Mgr MKT : </td>
                                                    <td class="control" ><?=$mkt_verified_date?></td>
                                                </tr>
                                                <tr>
                                                    <td class="label" >MKT.Div.Mgr : </td>
                                                    <td class="control" ><?=$mkt_approved_date?></td>
                                                </tr>
                                                    <!-- END -->
                                                <tr><td colspan="100%" style="height:30px"></td></tr>
                                                <thead class="table-dark" style="font-size:15px">
                                                    <tr>
                                                        <td class="labelnprsubjectchange" colspan="100%">Customer Information</td>
                                                    </tr>
                                                </thead>
                                                <tr><td colspan="100%" style="height:10px"></td></tr>
                                                <tr>
                                                    <td class="label" >Customer name : </td>
                                                    <td class="control" ><?=$txt_customer_name.$agile_team?></td>
                                                    <td></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label" >Customer request sample to MKT date : </td>
                                                    <td class="control" ><?=$txt_customer_request_sample_to_mkt_date?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label" >Ship method : </td>
                                                    <td class="control" ><?=$txt_ship_method?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label" >Purpose of sample request : </td>
                                                    <td class="control" ><?=$txt_purpose_of_sr?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label" ><u>**Customer Test Performance only (Minimun oil)</u> :</td>
                                                    <td class="control"><input type="checkbox" disabled <?if($test_performance=="1") echo "checked";?>></td>	
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label" >Additional data :</td>
                                                    <td class="control"><?=$txt_additional_data?></td>		
                                                </tr>
                                                
                                                <tr>
                                                    <td class="labelnpr" style="text-align: right;">Detail Additional data File :</td>
                                                    <td class="controlnpr"><?= $txt_detail_addition_file?></td>		
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label"  >Detail Sale Admin data :</td>
                                                    <td class="control"><?=$txt_detail_sale_admin?></td>		
                                                </tr>
                                                <tr><td colspan="100%" style="height:30px"></td></tr> 
                                                <tr>
                                                    <td colspan="100%" width = '100%' align="center">
                                                        <div id="show_sr"><img src="image/loading.gif"></div>	
                                                    </td>		
                                                </tr>
                                                <tr><td colspan="100%" style="height:30px"></td></tr> 
                                                
                                                <thead class="table-dark" style="font-size:15px">
                                                    <tr>
                                                        <td class="labelnprsubjectchange" colspan="100%">Detail</td>
                                                    </tr>
                                                </thead>
                                                <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                <tr>
                                                    <td class="label">Reason or Cost for free :</td>
                                                    <td class="control"><?=$reason_free?></td>		
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label">Engineer R&D Detail :</td>
                                                    <td class="control"><?=$txt_engineer_rd_detail?></td>		
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label">Engineer R&D Responsibility :</td>
                                                    <td class="control"><?=$txt_enginner_rd_respon?></td>		
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label">Engineer R&D File :</td>
                                                    <td class="control">
                                                        <table>
                                                            <?
                                                            $separate_engineer_rd_file = explode("/",$txt_engineer_rd_file);
                                                            for($i=0;$i<count($separate_engineer_rd_file);$i++){?>
                                                                <tr>
                                                                    <td style="cursor:pointer" onclick="window.open('uploadfile/sr_engineer_rd_folder/<?echo $separate_engineer_rd_file[$i];?>',null,'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes')"><?echo $separate_engineer_rd_file[$i];?></td>
                                                                </tr>
                                                            <?}?>
                                                        </table>
                                                    </td>		
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label">Leader R&D Remark :</td>
                                                    <td class="control"><?=$txt_leader_rd_remark?></td>		
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label">Report R&D File :</td>
                                                    <td>
                                                        <table>
                                                        <?$numrow = 0;
                                                            $Query = "select * from sr where id = '$id'"; 
                                                            $Result = $db2->query($Query);
                                                            $line=mysqli_fetch_array($Result);
                                                            $separate_file = explode("/",$line['rd_report_file']);
                                                            for($i=0;$i<count($separate_file);$i++){
                                                                if($separate_file[$i] != ""){?>
                                                                <tr>
                                                                    <td class="controlnpr" style="cursor:pointer" onclick="window.open('uploadfile/sr_report_folder/<?echo $separate_file[$i];?>',null,'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes')"><?echo $separate_file[$i];?></td>
                                                                </tr>
                                                            <?$numrow++;
                                                                }
                                                            }
                                                            if($numrow == 0){?>
                                                                <tr>
                                                                    <td class="controlnpr">No File</td>
                                                                </tr>
                                                        <?}else{?>
                                                                <tr>
                                                                    <td class="controlnpr"><a href="javascript:;" onclick="window.open('mixfile/createzipsrreport.php?id_sr=<?=$id?>',null,'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes')">Download All File</a></td>
                                                                </tr>
                                                        <?}
                                                        
                                                        mysqli_free_result($Result);?>
                                                        
                                                        </table>
                                                    </td>
                                                <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                <tr>
                                                    <td class="label">Leader RD confirm Orderingcode:</td>
                                                    <td class="control" ><span style="background-color: #FFFF00"><?=$leader_rd_confirm_orderingcode?> </span></td>
                                                </tr>
                                                <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                <tr>
                                                    <td class="label">SR From:</td>
                                                    <td class="control" ><span style="background-color: #FFFF00"><?=$sr_from?> </span></td>
                                                </tr>
                                                <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                <tr>
                                                    <td class="label">R&D informed 1st schedule to MKT :</td>
                                                    <td class="control"><?=$txt_rd_informed_1st_schedule_to_mkt_date?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label">R&D informed 2nd schedule to MKT :</td>
                                                    <td class="control"><?=$txt_rd_informed_revised1_schedule_to_mkt_date?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="label">R&D informed 3th schedule to MKT :</td>
                                                    <td class="control"><?=$txt_rd_informed_revised2_schedule_to_mkt_date?></td>
                                                </tr>
                                                <tr><td colspan="100%" style="height:20px"></td></tr> 
                                                <thead class="table-dark" style="font-size:15px">
                                                    <tr><td  class="labelnprsubjectchange"  colspan="100%" class="labelnprsubjectchange">Action by MKT</td></tr>
                                                </thead> 
                                                <tr>
                                                    <td colspan="100%" width = '100%' align="center">
                                                        <table class="myTable2" width="100%" class="table_sample" border=0>
                                                            
                                                            <thead style="background-color: #c8d0e7!important;">
                                                                <tr>
                                                                    <th width="30%">Item</th>
                                                                    <th >Date</th>
                                                                    <th >MKT,please notify reason of cancellation & point of revision</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="sample_lable">MKT revised SR #1</td>
                                                                    <td class="sample_lable"><?=$txt_mkt_revised_sr1_date?></td>
                                                                    <td class="sample_lable"><?=$txt_mkt_revised_sr1_reason?></td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td class="sample_lable">MKT revised SR #2</td>
                                                                    <td class="sample_lable"><?=$txt_mkt_revised_sr2_date?></td>
                                                                    <td class="sample_lable"><?=$txt_mkt_revised_sr2_reason?></td>
                                                                </tr>
                                                                <tr><th colspan="100%" style="height:5px"></th></tr> 
                                                            </tbody>
                                                            
                                                            
                                                        </table>
                                                    </td>		
                                                </tr>
                                                <tr><td colspan="100%" style="height:20px"></td></tr> 
                                                <thead class="table-dark" style="font-size:15px">
                                                    <tr><td class="labelnprsubjectchange" colspan="100%" class="labelnprsubjectchange">Conclusion SR</td></tr> 
                                                </thead>
                                                <tr>
                                                    <td class="label" >MKT informed schedule to customer :</td>
                                                    <td class="control"><?=$txt_mkt_informed_schedule_to_customer_date." ".$txt_mkt_informed_schedule_to_customer_reason?></td>
                                                </tr>
                                                <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                <tr>
                                                    <td class="label">MKT already sent to customer on :</td>
                                                    <td class='control'><?=$txt_mkt_already_sent_to_customer_on_date?></td>
                                                </tr>
                                                <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                <tr>
                                                    <td class="label">MKT sent to customer by :</td>
                                                    <td class='control'><?=$txt_mkt_already_sent_to_customer_on_by?></td>
                                                </tr>
                                                
                                                <?if($txt_mkt_sr_cancle_reason != ""){?>
                                                    <tr><td colspan="100%" style="height:20px"></td></tr> 
                                                    <tr><td colspan="100%" class="labelnprsubjectchange">Cancel Memo</td></tr> 
                                                    
                                                    <tr>
                                                        <td class="label">Request cancel reason (MKT) :</td>
                                                        <td class='control'><?=$txt_mkt_sr_cancle_reason?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="label">Request cancel by :</td>
                                                        <td class='control'><?=$txt_request_cancel_by." ".$txt_request_cancel_date?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="label">Approved cancel by (MKT) :</td>
                                                        <td class='control'><?=$txt_approve_request_cancel_by." ".$txt_approve_request_cancel_date?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="label">R&D accept cancel reason :</td>
                                                        <td class='control'><?=$txt_request_cancel_canstop." ".$txt_rd_accept_cancel_reason?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="label">R&D accept cancel by :</td>
                                                        <td class='control'><?=$txt_rd_accept_cancel_by." ".$txt_rd_accept_cancel_date?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="label">Conclusion cancel reason :</td>
                                                        <td class='control'><?=$txt_reason_of_cancle?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="label">Conclusion cancel by :</td>
                                                        <td class='control'><?=$txt_sr_cancle_by." ".$txt_sr_cancle_date?></td>
                                                    </tr>
                                                <?}?>
                                        </table>    
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                    
                </div>

            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <? echo $_SESSION['company_name']; ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../../../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>
    <script src="../js/dataTables/jquery.dataTables.min.js"></script>
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = fileName;
        });
    </script>
    <script>
        function formToggle(ID){
            var element = document.getElementById(ID);
            if(element.style.display === "none"){
                element.style.display = "block";
            }else{
                element.style.display = "none";
            }
        }
    </script>
    <script>
        $("#add_new_btn").click(function(){
            add_new();
        });
            
        function add_new(){
            var srno = document.getElementById('srno').value;
            var selectedCheckBoxValue = [];
            var selectedCheckBox = $(':checkbox:checked').map(function(i) {
                selectedCheckBoxValue[i] = this.value;
                return this;
            }).get();
            console.log(selectedCheckBoxValue);
            window.location.href = "addrdrequest.php?room=" + selectedCheckBoxValue + "&srno=" +srno ; 
            
        }
        function createXMLHttpRequest(){
            if(window.ActiveXObject){
                xmlHttp= new ActiveXObject("Microsoft.XMLHTTP");
            }else if(window.XMLHttpRequest){
                xmlHttp= new XMLHttpRequest();
            }
        }
        function startRequest(check_first){
            createXMLHttpRequest();
            if(check_first != '0'){
                var txt_status_change = document.getElementById('txt_status').value;	
                xmlHttp.onreadystatechange = handleStateChange;
                xmlHttp.open("GET","sr_status_some_model.php?id=" + '<?=$id?>'+ "&myBOM=" + '<?=$BOM?>' + "&txt_status_change=" + txt_status_change,true);
            }else{
                var txt_status_change = "";
                xmlHttp.onreadystatechange = handleStateChange;
                xmlHttp.open("GET","sr_status_some_model.php?id=" + '<?=$id?>'+ "&myBOM=" + '<?=$BOM?>' + "&txt_status_change=" + txt_status_change,true );
            }		
            xmlHttp.send(null);
        }
        function handleStateChange(){
            if(xmlHttp.readyState == 4){
                if(xmlHttp.status == 200){
                    document.getElementById("show_sr").innerHTML = xmlHttp.responseText;
                }
            }
        }
        startRequest('0')
    </script>
</body>

</html>
