<?php
session_start();


if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}


require_once( '../DBconfig/dbConfig2.php' );
require_once('../../../function/function.php');
require_once('../../../../include/class.phpmailer.php');
require_once('../../../../include/class.smtp.php');

    if (isset($_GET['id']) && isset($_GET['srno'])&& isset($_GET['bom'])) {
        $encodedIdSr = $_GET['id'];
        $encodedSrNo = $_GET['srno'];
        $encodedBom = $_GET['bom'];
        
        // $id = 10498;
        $sr_no = base64_decode($encodedSrNo);
        $id = base64_decode($encodedIdSr);
        $BOM = base64_decode($encodedBom);
    }
    $checksave = $_REQUEST["checksave"];
    if($checksave==""){$checksave=0;}
    if($checksave==1){
        $txt_engineer_rd_detail = $_REQUEST["txt_engineer_rd_detail"];
        $Query = "update sr set 
        engineer_rd_detail = '$txt_engineer_rd_detail',
        id_sr_status='5',
        pass_en_rd_by='$_SESSION[session_name]',
        pass_en_rd_date='$date_checked'
        where id = $id ";
        $Result = $db2->query($Query);
        
        $Query2="select comp_id from sr where id = '$id' ";
        $Result2= $db2->query($Query2);
        $line=mysqli_fetch_array($Result2);
        $comp_id=$line['comp_id'];
        mysqli_free_result($Result2);
        if($comp_id != 0){
            $Query2="select *,c.model as model,c.four_digit as four_digit from sr a left outer join customer b on a.customer_id = b.customer_id left outer join comp_data c on a.comp_id=c.id where a.id = '$id' ";
        }else{
            $Query2="select *,c.target_model_name as model,c.fourdigit as four_digit,customer_name from sr a left outer join customer b on a.customer_id = b.customer_id left outer join npr c on a.model_npr_id=c.id where a.id = '$id' ";
        }
        $Result2= $db2->query($Query2);
        $line=mysqli_fetch_array($Result2);
        $txt_sr_no=$line['sr_no'];
        $customer_name=$line['customer_name'];
        $txt_model = $line['model'];
        $txt_four_digit = $line['orderingcode'];
        $mkt_issued_by = $line['mkt_issued_by'];
        mysqli_free_result($Result2);

        $string_subject = "I-Spec (MKT request SR)";
        $dear = "Dear Leader R&D";
        $message = "Please see SR request from MKT.
        <br>
        SR No : $txt_sr_no
        <br>
        Model : $txt_model / $txt_four_digit
        <br>
        Customer : $customer_name
        <br>
        Mail send from Engineer R&D page.
        ";
        $body_string = "
        <html>
        <table width='80%' style='font-size:14px;'>
        <tr>
        <td style='font-weight:bold;color:gray;text-align:left;' colspan='100%'>$dear</td>
        </tr>
        <tr>
        <td style='font-weight:bold;color:black;text-align:left'colspan='100%'>&nbsp;&nbsp;$message</td>
        </tr>
        <tr>
        <td style='font-weight:bold;color:black;text-align:left'colspan='100%'>
        Please login to <a href='https://".$_SERVER['HTTP_HOST']."/sci_web_admin/www'>Click for login</a>
        </td>
        </tr>
        <tr><td colspan='100%' height='10px'>&nbsp;</td></tr>
        </table>
        </html>
        ";
        $mail_to = 'dew022612678@gmail.com';
        $now = date("Y-m-d H:i:s");
        $session_name = $_SESSION["session_name"];
        $session_email = $_SESSION["session_email"];
        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->Body = $body_string;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Port = 25;
        $mail->Host = "email.siamcompressor.com";
        $mail->From = $session_email;
        $mail->FromName = "technic@siamcompressor.com"; // ปรับเป็นชื่อผู้ส่งอีเมลเท่านั้น
        $mail->Subject =  $string_subject;
        $mail->IsHTML(true);
        $Query = "select e_mail from user where department_id = '1' and position_id = '5' "; // reader rd email
        $Result = $db2->query($Query);
        while($line = mysqli_fetch_assoc($Result)){
            $email = $email.$line["e_mail"].",";
        }
        mysqli_free_result($Result);
        $countmailto = explode(",",$email);
        for($m=0;$m<count($countmailto);$m++){
			if($countmailto[$m] != "" && $countmailto[$m] != "-"){
				// $mail->AddAddress($countmailto[$m]);
			}
		}
        
        $mail->AddAddress($mail_to);
        $mail->AddCC($session_email);
        $mail->Send();
        if ($mail->Send()) {
        } else {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            echo "<script>alert('{$mail->ErrorInfo}');</script>";
        }


        
        // mailData($string_subject,$dear,$body_string,$email,"","",4);
        // //end mail
        
        // echo "<script>alert('Update Complete'); location.href='index2.php?option=".parameter_encode("engineerrd_viewsr")."';</script>";
        echo "<script>alert('Update and Send e-mail to Leader Complete'); location.href='managesr.php?id=$encodedIdSr&srno=$encodedSrNo&bom=$encodedBom';</script>";
    } //end  save

    $Query2="select comp_id from sr where id = '$id' ";
    $Result2= $db2->query($Query2);
    $line=mysqli_fetch_array($Result2);
    $comp_id=$line['comp_id'];
    mysqli_free_result($Result2);
    if($comp_id != 0){
        $Query2="select *,a.other_file as sr_other_file,d.model as model,d.four_digit as four_digit from sr a 
        left outer join customer b on a.customer_id = b.customer_id 
        left outer join department c on a.department_id = c.id 
        left outer join comp_data d on a.comp_id=d.id 
        where a.id = '$id' ";
    }else{
        $Query2="select *,a.other_file as sr_other_file,d.target_model_name as model,d.fourdigit as four_digit from sr a 
        left outer join customer b on a.customer_id = b.customer_id 
        left outer join department c on a.department_id = c.id 
        left outer join npr d on a.model_npr_id=d.id 
        where a.id = '$id' ";
    }
    $Result2= $db2->query($Query2);
    $line=mysqli_fetch_array($Result2);
    $txt_detail_sale_admin=$line['detail_sale_admin'];
    $txt_sr_no=$line['sr_no'];
    $txt_customer_name=$line['customer_name'];
    $txt_project_id = $line["project_id"];
    $txt_department_id=$line['department_id'];
    $txt_customer_status=$line['customer_status'];
    $txt_customer_request_sample_to_mkt_date=$line['customer_request_sample_to_mkt_date'];
    $txt_ship_method=$line['ship_method'];
    $txt_reason_of_request=$line['reason_of_request'];
    $txt_purpose_of_sr=$line['purpose_of_sr'];
    $txt_additional_data=$line['additional_data'];
    $txt_detail_addition_file = $line["detail_addition_file"];

    $txt_model = $line['model'];
    $txt_four_digit = $line['orderingcode'];
    $txt_status = $line['status'];
    $txt_qty = $line['qty'];
    $txt_rd_take_comp_from = $line['rd_take_comp_from'];
    $txt_payment_condition = $line['payment_condition'];
    $txt_test_report_require = $line['test_report_require'];
    $txt_type_of_compressor = $line['type_of_compressor'];
    $txt_request_rd_to_sent_to_store_within = $line['request_rd_to_sent_to_store_within'];
    $txt_sight_glass_number = $line['sight_glass_number'];
    $txt_sight_glass_file = $line['sight_glass_file'];
    $txt_other_file = $line['sr_other_file'];
    $txt_remark = $line['remark'];
    $txt_engineer_rd_detail = $line['engineer_rd_detail'];
    if($comp_id == 0 && $txt_engineer_rd_detail == "") $txt_engineer_rd_detail =  $line['model'];;
    mysqli_free_result($Result2);

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
        .table-dark{
			background-color: #7788c5!important;
		}
    </style>
</head>

<body>
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
                    <a href="index.php" class="back-menu" >
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
                                    <h6 class="m-0 font-weight-bold text-primary">Engineer R&D Edit Sample Request (SR)</h6>
                                </div>
                                <!-- Card Body -->
                                <form name="frmConsole" id="frmConsole" method="POST" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <input type="hidden" id="count_model" name="count_model" value="<?=count($txt_model)?>" >
                                                <div class="table">
                                                    <table class="myTable" width="100%" class="cpanel" border='0'>
                                                        <tr>
                                                            <td width="100%">
                                                                <table cellpadding="0" cellspacing="0" width="100%" >
                                                                    <thead class="table-dark" style="font-size:15px">
                                                                        <tr>
                                                                            <td class="labelnprsubjectchange" colspan="100%">Information</td>
                                                                        </tr>
                                                                    </thead>
                                                                    
                                                                    <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Sample Request No. :  </td>
                                                                        <td class="control"><?=$txt_sr_no?></td>		
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Project : </td>
                                                                        <td class="control">
                                                                                <?$Query2 = "select * from project_list where id = '$txt_project_id'";
                                                                                    $Result2 = $db2->query($Query2);
                                                                                    $line2 = mysqli_fetch_assoc($Result2);
                                                                                    echo $line2["project_no"];
                                                                                    mysqli_free_result($Result2);?>
                                                                        </td>	
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td colspan="100%" width = '100%' align="center">
                                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                                <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                                <thead class="table-dark" style="font-size:15px">
                                                                                    <tr>
                                                                                        <td class="labelnprsubjectchange" colspan="100%">Project</td>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                                <tr>
                                                                                    <td colspan="100%" width = '100%' align="center">
                                                                                        <table width="100%" cellspacing="0" cellpadding="0">
                                                                                            <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                                            <tr>
                                                                                                <td align="left">Project No</td>
                                                                                                <td align="left">Project Name</td>
                                                                                                <td align="left">Model</td>
                                                                                                <td align="left">Application</td>
                                                                                                <td align="left">Status</td>
                                                                                            </tr>
                                                                                            <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                                            <?
                                                                                                $Query3 = "select * from project_list where id = '$txt_project_id'";
                                                                                                $Result3 = $db2->query($Query3);				
                                                                                                $line3=mysqli_fetch_array($Result3);?>
                                                                                            <tr>
                                                                                                <td align="left"><?echo $line3['project_no'];?></td>
                                                                                                <td align="left"><?echo $line3['project_name'];?></td>
                                                                                                <td align="left"><?echo $line3['compressor_model'];?></td>
                                                                                                <td align="left"><?echo $line3['application'];?></td>
                                                                                                <td align="left"><?echo $line3['status'];?></td>
                                                                                            </tr>
                                                                                            <?mysqli_free_result($Result3);?>
                                                                                            <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                                            <tr><td colspan="100%" style="height:1px" bgcolor="gray"></td></tr> 
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                                <?// check npr have use this project
                                                                                    $Query4 = "select * from npr where project_id ='$txt_project_id' and project_id != '0'";
                                                                                    $Result4 = $db2->query($Query4);					
                                                                                    $row_npr=mysqli_num_rows($Result4);
                                                                                    mysqli_free_result($Result4);
                                                                                    if($row_npr != 0 & $txt_project_id != ''){?>
                                                                                        <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                                                        <tr>
                                                                                            <td colspan="100%" width = '100%' align="center">
                                                                                                <table width="100%">
                                                                                                    <tr>
                                                                                                        <td>NPR No.</td>
                                                                                                        <td>Verified Date</td>
                                                                                                        <td>Verified By</td>
                                                                                                        <td>4Digit</td>
                                                                                                        <td class="icon">Status</td>
                                                                                                    </tr>
                                                                                                    <?
                                                                                                        $Query5 = "select * from npr a left outer join status_npr b on a.id_npr_status=b.id where project_id ='$txt_project_id' and project_id != '0'";
                                                                                                        $Result5 = $db2->query($Query5);					
                                                                                                        while($line5=mysqli_fetch_array($Result5)){ ?>
                                                                                                    <tr>
                                                                                                        <td class="name"><?echo $line5['npr_no'];?></td>
                                                                                                        <td class="name"><?echo $line5['mkt_issued_date'];?></td>
                                                                                                        <td class="name"><?echo $line5['mkt_issued_by'];?></td>
                                                                                                        <td class="name"><?echo $line5['four_digit'];?></td>
                                                                                                        <td class="name"><?echo $line5['npr_status'];?></td>
                                                                                                    </tr>
                                                                                                    <?}mysqli_free_result($Result5);?>
                                                                                                    <tr><td colspan="100%" style="height:1px" bgcolor="green"></td></tr> 
                                                                                                </table>
                                                                                            </td>		
                                                                                        </tr>
                                                                                    <?}?>
                                                                                    
                                                                                    <?// check npr have sr this project
                                                                                        $Query6 = "select * from sr a left outer join status_main_sr b on a.id_sr_status=b.id where project_id ='$txt_project_id' and project_id != '0'";
                                                                                        $Result6 = $db2->query($Query6);					
                                                                                        $row_sr=mysqli_num_rows($Result6);
                                                                                        mysqli_free_result($Result6);
                                                                                        if($row_sr != 0  & $txt_project_id != ''){?>
                                                                                            <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                                                            <tr>
                                                                                                <td colspan="100%" width = '100%' align="center">
                                                                                                    <table width="100%">
                                                                                                        <tr>
                                                                                                            <td>SR No.</td>
                                                                                                            <td>Verified Date</td>
                                                                                                            <td >Verified By</td>
                                                                                                            <td class="icon">Status</td>
                                                                                                        </tr>
                                                                                                        <?
                                                                                                            $Query6 = "select * from sr where project_id = '$txt_project_id' and project_id != '0'";
                                                                                                            $Result6 = $db2->query($Query6);				
                                                                                                            while($line6=mysqli_fetch_array($Result6)){ ?>
                                                                                                        <tr>
                                                                                                            <td class="name"><?echo $line6['sr_no'];?></td>
                                                                                                            <td class="name"><?echo $line6['mkt_issued_date'];?></td>
                                                                                                            <td class="name"><?echo $line6['mkt_issued_by'];?></td>
                                                                                                            <td class="name"><?echo $line6['sr_status'];?></td>
                                                                                                        </tr>
                                                                                                        <?}mysqli_free_result($Result6);?>
                                                                                                        <tr><td colspan="100%" style="height:1px" bgcolor="green"></td></tr> 
                                                                                                    </table>
                                                                                                </td>		
                                                                                            </tr>
                                                                                    <?}?>
                                                                            </table>
                                                                        </td>		
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:20px"></td></tr>
                                                                    <thead class="table-dark" style="font-size:15px">
                                                                        <tr>
                                                                            <td class="labelnprsubjectchange" colspan="100%">Customer Information</td>
                                                                        </tr>
                                                                    </thead>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Customer name :</td>
                                                                        <td class="control"><?=$txt_customer_name;?></td>	
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Customer status :</td>
                                                                        <td class="control">
                                                                            <?echo $txt_customer_status;?>
                                                                        </td>	
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Customer request samples to MKT :</td>
                                                                        <td class="control">
                                                                            <?=$txt_customer_request_sample_to_mkt_date?>
                                                                        </td>		
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Ship method :</td>
                                                                        <td class="control">
                                                                            <?echo $txt_ship_method;?>
                                                                        </td>	
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Reason of request :</td>
                                                                        <td class="control">
                                                                            <?=$txt_reason_of_request?>
                                                                        </td>		
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Purpose of SR :</td>
                                                                        <td class="control">
                                                                            <?=$txt_purpose_of_sr?>
                                                                        </td>	
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Additional data :</td>
                                                                        <td class="control">
                                                                            <?=$txt_additional_data?>
                                                                        </td>		
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <?if($txt_detail_addition_file != ''){?>
                                                                        <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                        <tr>
                                                                            <td class="label">Old Detail file :</td>
                                                                            <td class="control">
                                                                                <!-- <a href="javascript:;" style="font-size:11px" onclick="window.open('file.php?faf=<parameter_encode("sr_detail_addition_folder,".$txt_detail_addition_file)?>',null,'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes');"><?=$txt_detail_addition_file?></a> -->
                                                                            </td>
                                                                        </tr>
                                                                    <?}?>
                                                                    <tr><td colspan="100%" style="height:5px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Detail Sale Admin Data :</td>
                                                                        <td class="control">
                                                                            <?=$txt_detail_sale_admin?>
                                                                        </td>		
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="100%" width = '100%' align="center">
                                                                            <table width="100%" class="table_sample" >
                                                                                <thead style="font-size:13px; background-color: #c8d0e7!important;">
                                                                                    <tr>
                                                                                        
                                                                                        <th>Model</th>
                                                                                        <th>Ordering Code</th>
                                                                                        
                                                                                        <th>Qty(Set)</th>
                                                                                        <th>R&D take comp. from</th>
                                                                                        <th>BOM</th>
                                                                                        <th>Payment condition</th>
                                                                                        <th>Test report require</th>
                                                                                        <th>Type of compressor</th>
                                                                                        <th>Request R&D to send to store within</th>
                                                                                        <th>Other file</th>
                                                                                        <th>Remark</th>
                                                                                        
                                                                                        <th>Get part from prod.</th>
                                                                                        <th>Send to store 1st commitment</th>
                                                                                        <th>Not finish in time new date to store (Rev1)</th>
                                                                                        <th>Not finish in time new date to store (Rev2)</th>
                                                                                        <th>Serial No.</th>
                                                                                        <th>Store date.</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tr>
                                                                                    
                                                                                    <td class="sample_lable"><?=$txt_model;?></td>
                                                                                    <td class="sample_lable"><?=$txt_four_digit;?></td>
                                                                                    
                                                                                    <td class="sample_lable"><?=$txt_qty?></td>
                                                                                    <td class="sample_lable"><?=$txt_rd_take_comp_from?></td>
                                                                                    <td class="sample_label"><?=$BOM?></td>
                                                                                    <td class="sample_lable"><?=$txt_payment_condition?></td>
                                                                                    <td class="sample_lable"><?=$txt_test_report_require?></td>
                                                                                    <td class="sample_lable"><?=$txt_type_of_compressor?></td>
                                                                                    <td class="sample_lable"><?=$txt_request_rd_to_sent_to_store_within?></td>
                                                                                    
                                                                                    <td class="sample_lable">
                                                                                        <?if($txt_other_file != ''){?>
                                                                                            <!-- <a href="javascript:;" style="font-size:11px" onclick="window.open('file.php?faf=<parameter_encode("sr_other_file_folder,".$txt_other_file)?>',null,'height=500,width=500,status=yes,toolbar=no,menubar=no,location=no,resizable=yes');"><img src="image/search16.png" border='0'></a> -->
                                                                                        <?}else{?>
                                                                                            -
                                                                                        <?}?>
                                                                                    </td>
                                                                                    <td class="sample_lable"><?=$txt_remark?></td>
                                                                                    
                                                                                    <td>-</td>
                                                                                    <td>-</td>
                                                                                    <td>-</td>
                                                                                    <td>-</td>
                                                                                    <td>-</td>
                                                                                    <td>-</td>
                                                                                </tr>
                                                                                <tr><th colspan="100%" style="height:5px"></th></tr>
                                                                            </table>		
                                                                        </td>		
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Engineer Detail :</td>
                                                                        <td class="control"><textarea class="form-control" id='txt_engineer_rd_detail' name='txt_engineer_rd_detail'><?=$txt_engineer_rd_detail?></textarea></td>
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                                    <tr>
                                                                        <td class="label">Engineer File :</td>
                                                                        <td class="control">
                                                                            <div id="table_view_engineerfilesr" style="clear:both;"><img src="../../../image/loading.gif"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="label"></td>
                                                                        <td class="control">
                                                                            <p id="f1_upload_process" style="font-size:11px;">&nbsp;&nbsp;Loading...<br/><img src="../../../image/loaderupload.gif" /><br/></p>
                                                                            <p id="f1_upload_form" align="left">
                                                                            <label class="myLabel"><input name="myfile" type="file" size="30" /></label>
                                                                            <label><input type="button" name="submitUpload" id="submitUpload" class="btn btn-primary" value="Upload" onclick="startUpload();"/></label>
                                                                            </p>
                                                                            <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                                                                        </td>
                                                                    </tr>
                                                                    <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                                    <tr><td colspan="100%" style="text-align:center"><input type="button" name="allSubmit" id="allSubmit" class="btn btn-success" value="Submit" onclick="startSaveAll()">&nbsp;<input type="reset" class="btn btn-danger" value="Reset"></td></tr> 
                                                                    <tr><td colspan="100%" style="height:10px"></td></tr> 
                                                                    <tr><td colspan="100%" class="labelnprsubjectchange"><a>Auto send e-mail to Leader when your click Submit</a></td></tr> 
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <input type="hidden"  name="checksave" id="checksave" value="1">
                                        </div>
                                    </div>
                                </form>
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
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>
    <script src="../js/dataTables/jquery.dataTables.min.js"></script>
    <script>

	function createXMLHttpRequest(){
		if(window.ActiveXObject){
			xmlHttp= new ActiveXObject("Microsoft.XMLHTTP");
		}else if(window.XMLHttpRequest){
			xmlHttp= new XMLHttpRequest();
		}
	}
	function startRequest(del){
		document.getElementById('f1_upload_process').style.visibility = 'hidden';
		createXMLHttpRequest();
		xmlHttp.onreadystatechange = handleStateChange;
		xmlHttp.open("GET","table_view_engineerfilesr.php?id_sr=" + '<?=$id?>'+ "&del=" + del,true);
		xmlHttp.send(null);
	}
	function handleStateChange(){
		if(xmlHttp.readyState == 4){
			if(xmlHttp.status == 200){
				document.getElementById("table_view_engineerfilesr").innerHTML = xmlHttp.responseText;
			}
		}
	}
	startRequest(-1);

	function startSaveAll(){
		document.forms['frmConsole'].action = document.URL;
		document.forms['frmConsole'].target = "frmConsole";
 		document.forms['frmConsole'].submit();
	}

	function startUpload(){
		document.forms['frmConsole'].action = "../../upload.php?id_sr=" + <?=$id?>;
		document.forms['frmConsole'].target = "upload_target";
 		document.forms['frmConsole'].submit();
        document.getElementById('f1_upload_process').style.visibility = 'visible';
        document.getElementById('f1_upload_form').style.visibility = 'hidden';
	  return true;
	}
	
	function stopUpload(success){
	  var result = '';
	  if (success == 1){
	     result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
	  }
	  else {
	     result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
	  }
	  document.getElementById('f1_upload_process').style.visibility = 'hidden';
	  document.getElementById('f1_upload_form').innerHTML = result + '<label class="myLabel"><input name="myfile" type="file" size="30" /><\/label><label><input type="button" name="submitUpload" id="submitUpload" class="btn btn-primary" style="background: #4e73df;" value="Upload" onclick="startUpload();"/><\/label>';
	  document.getElementById('f1_upload_form').style.visibility = 'visible';      
	  
	  startRequest(-1);
	  return true;   
	}
    
</script>

</body>

</html>
