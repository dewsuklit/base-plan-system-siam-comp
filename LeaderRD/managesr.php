<?php
session_start();

if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}


require_once( '../DBconfig/dbConfig2.php' );
require_once('calendar/class/tc_calendar.php');
require_once( '../../../../include/dbfunction.php' );

require_once('../../../function/function.php');
require_once('../../../../include/class.phpmailer.php');
require_once('../../../../include/class.smtp.php');
    // $id = $_POST['answer'];
    if (isset($_GET['id']) && isset($_GET['srno'])&& isset($_GET['bom'])) {
        $encodedIdSr = $_GET['id'];
        $encodedSrNo = $_GET['srno'];
        $encodedBom = $_GET['bom'];
        
		
		// $id = 10498;
		$sr_no = base64_decode($encodedSrNo);
		$id = base64_decode($encodedIdSr);
		$BOM = base64_decode($encodedBom);
	}
	// $id = parameter_decode($_REQUEST["id"]);
	$checksave = $_REQUEST["checksave"];
	if($checksave== ""){$checksave=0;}
	if($checksave==1){ //เมื่อ กด submit
		$QuerySR = "SELECT * FROM assy_upload WHERE PLAN != '' AND MO_SR = '" . $sr_no . "'";
		$ResultSR =  $db2->query($QuerySR);
		if($ResultSR->num_rows > 0){
			define('Y', 'Y');
			define('m', 'm');
			define('d', 'd');
			define('H', 'H');
			define('i', 'i');
			define('s', 's');
			$count_model = $_REQUEST["count_model"];
			$txt_rd_informed_1st_schedule_to_mkt_date = '';
			$txt_rd_informed_revised1_schedule_to_mkt_date = '';
			$txt_rd_informed_revised2_schedule_to_mkt_date = '';
			$txt_rd_get_part_from_prod_all =  $_REQUEST["txt_rd_get_part_from_prod"];
			$txt_rd_get_part_from_prod_save =  $_REQUEST["txt_rd_get_part_from_prod"];
			$txt_rd_send_to_store_save =$_REQUEST["txt_rd_send_to_store"];
			$txt_new_date_to_store_rev1_save = $_REQUEST["txt_new_date_to_store_rev1"];
			$txt_new_date_to_store_rev2_save = $_REQUEST["txt_new_date_to_store_rev2"];
			$txt_serial_no_save = $_REQUEST["txt_serial_no"];
			$txt_sr_from = $_REQUEST["sr_from"];
			$txt_store_date_save = $_REQUEST["txt_store_date"];
			if( ($txt_store_date_save == "0000-00-00" || $txt_store_date_save == "" ) && $txt_serial_no_save != "" ){
				echo "<script>alert('กรอก Serial No แล้วต้องกรอกวันเข้า Store ด้วย , $txt_store_date_save, $txt_serial_no_save');location.href='managesr.php?id=$encodedIdSr&srno=$encodedSrNo&bom=$encodedBom';</script>";
			}else if($txt_sr_from == "" && $txt_serial_no_save != ""){
				echo "<script>alert('กรอก Serial No แล้วต้องกรอกที่มาของ comp ด้วย');location.href='managesr.php?id=$encodedIdSr&srno=$encodedSrNo&bom=$encodedBom';</script>";
			}else{
				$txt_leader_rd_confirm_orderingcode = $_REQUEST['txt_leader_rd_confirm_orderingcode'];
				$txt_qty = $_REQUEST['txt_qty'];
				$txt_leader_rd_remark = $_REQUEST['txt_leader_rd_remark'];
				$date_save = date(Y."-".m."-".d."-".H."-".i."-".s);
				$date_issued = date(Y."-".m."-".d);
				$filepath = $_SERVER["SCRIPT_FILENAME"];		
				$savepath = str_replace("index2.php","",$filepath);

				$se_FinishSR = '1';
				if($test_report_require != "No" && $test_report_require != "-"){
					if($se_FinishSR == '1'){$dateFinish = $date_issued;}else{$dateFinish = "0000-00-00";}
				}else{
					$dateFinish = $date_issued;
				}

				//insert
				$Query = "UPDATE sr SET 
				rd_leader_complete_date = '$dateFinish',
				rd_answer_leader_by = '{$_SESSION['session_name']}',
				rd_informed_1st_schedule_to_mkt_date = '$txt_rd_informed_1st_schedule_to_mkt_date',
				rd_informed_revised1_schedule_to_mkt_date = '$txt_rd_informed_revised1_schedule_to_mkt_date',
				rd_informed_revised2_schedule_to_mkt_date = '$txt_rd_informed_revised2_schedule_to_mkt_date',
				rd_get_part_from_prod = '$txt_rd_get_part_from_prod_save',
				rd_send_to_store = '$txt_rd_send_to_store_save',
				new_date_to_store_rev1 = '$txt_new_date_to_store_rev1_save',
				new_date_to_store_rev2 = '$txt_new_date_to_store_rev2_save',
				serial_no = '$txt_serial_no_save',
				store_date = '$txt_store_date_save',
				qty = '$txt_qty',
				leader_rd_remark = '$txt_leader_rd_remark',
				id_sr_status = '6',
				leader_rd_confirm_orderingcode = '$txt_leader_rd_confirm_orderingcode',
				sr_from = '$txt_sr_from'
				WHERE id = $id";
				
			$Result = $db2->query($Query);
				$Query2="select comp_id from sr where id = '$id' ";
				$Result2=  $db2->query($Query2);
				$line=mysqli_fetch_array($Result2);
				$comp_id=$line['comp_id'];
				mysqli_free_result($Result2);
				if($comp_id != 0){
					$Query2="select *,c.model as model,c.four_digit as four_digit from sr a left outer join customer b on a.customer_id = b.customer_id left outer join comp_data c on a.comp_id=c.id where a.id = '$id' ";
				}else{
					$Query2="select *,c.target_model_name as model,c.fourdigit as four_digit,customer_name from sr a left outer join customer b on a.customer_id = b.customer_id left outer join npr c on a.model_npr_id=c.id where a.id = '$id' ";
				}
				$Result2=  $db2->query($Query2);
				$line=mysqli_fetch_array($Result2);
				$txt_sr_no=$line['sr_no'];
				$customer_name=$line['customer_name'];
				$txt_model = $line['model'];
				$txt_four_digit = $line['four_digit'];
				$mkt_issued_by = $line['mkt_issued_by'];
				$mkt_make_by = $line['mkt_make_by'];
				$test_report_require = $line['test_report_require'];
				mysqli_free_result($Result2);
				
				//mail
				$string_subject = "I-Spec (Answer from Leader R&D)";
				$dear = "Dear $mkt_issued_by, $mkt_make_by";
				$message = "Please see details your SR.
				<br>
				SR No : $txt_sr_no
				<br>
				Model : $txt_model / $txt_four_digit
				<br>
				Customer : $customer_name
				<br>
				Mail send from Leader R&D page.
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

				$Query = "select e_mail from user where name = '$mkt_issued_by'"; // mkt issued by 
				$Result =  $db2->query($Query);
				$line = mysqli_fetch_assoc($Result);
				$email = $line["e_mail"];
				mysqli_free_result($Result);

				$mail->AddAddress($mail_to);
				$mail->AddCC($session_email);
				$mail->Send();

				if ($mail->Send()) {

				} else {
					echo 'Message could not be sent.';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
					echo "<script>alert('{$mail->ErrorInfo}');</script>";
				}
				echo "<script>alert('Update and Send e-mail to MKT Complete'); location.href='viewsr.php';</script>";
			}
		}else{
			echo "<script>alert('ยังไม่มีการ Upload แผนการประกอบ (Assy plan)'); location.href='managesr.php?id=$encodedIdSr&srno=$encodedSrNo&bom=$encodedBom';</script>";
		}
	} //end  save

	$Query2="select comp_id from sr where id = '$id' ";
	$Result2=  $db2->query($Query2);
	$line=mysqli_fetch_array($Result2);
	$comp_id=$line['comp_id'];
	mysqli_free_result($Result2);
	// $myQry = "SELECT serial_no FROM serial_of_lot WHERE sr_no = '$sr_no'";
	// $Result3=  $db2->query($myQry);
	
	// if($Result3->num_rows <1){
		if($comp_id != 0){
			$Query2="select *,d.model as model,d.four_digit as four_digit from sr a 
			left outer join customer b on a.customer_id = b.customer_id 
			left outer join department c on a.department_id = c.id 
			left outer join comp_data d on a.comp_id=d.id 
			where a.id = '$id' ";
			$Query5="select *,a.id as sr_id,e.topic_doc_no,d.model as model,d.four_digit as four_digit
			,sl.lot as sl_lot , sl.serial_no as all_serial ,sl.qty as sl_qty
			from sr a 
			left outer join customer b on a.customer_id = b.customer_id 
			left outer join department c on a.department_id = c.id 
			left outer join comp_data d on a.comp_id=d.id 
			left outer join rd_request e ON a.id = e.id_sr
			left join serial_of_lot sl ON a.sr_no = sl.sr_no
			where a.id = '$id'
			GROUP BY sl.id
			order by sl_lot ASC";
			
		}else{
			$Query2="select *,d.target_model_name as model,d.fourdigit as four_digit from sr a 
			left outer join customer b on a.customer_id = b.customer_id 
			left outer join department c on a.department_id = c.id 
			left outer join npr d on a.model_npr_id=d.id 
			where a.id = '$id' ";
			$Query5="select *,a.id as sr_id,e.topic_doc_no,d.target_model_name as model,d.fourdigit as four_digit,sl.lot as sl_lot , sl.serial_no as all_serial
			, sl.store_date as store_date
			,sl.qty as sl_qty
			from sr a 
			left outer join customer b on a.customer_id = b.customer_id 
			left outer join department c on a.department_id = c.id 
			left outer join npr d on a.model_npr_id=d.id 
			left outer join rd_request e ON a.id = e.id_sr
			left join serial_of_lot sl ON a.sr_no = sl.sr_no
			where a.id = '$id'
			GROUP BY sl.id
			order by sl_lot ASC ";
		}
	// }else{
		
	// 	$Query2="select *,d.target_model_name as model,d.fourdigit as four_digit ,sl.lot as sl_lot , sl.serial_no as all_serial
	// 		from sr a 
	// 		left outer join customer b on a.customer_id = b.customer_id 
	// 		left outer join department c on a.department_id = c.id 
	// 		left outer join npr d on a.model_npr_id=d.id 
	// 		left join serial_of_lot sl ON a.sr_no = sl.sr_no
	// 		where a.id = '$id' ";
	// }
	$Result2=  $db2->query($Query2);
	$Result5 = $db2->query($Query5);
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
	$test_performance = $line["test_performance"];
	$txt_additional_data=$line['additional_data'];
	$txt_detail_addition_file = $line["detail_addition_file"];
	$txt_model = $line['model'];
	$txt_four_digit = $line['four_digit'];
	$txt_status = $line['status'];
	$txt_qty = $line['qty'];
	$sl_lot = $line['sl_lot'];
	$txt_leader_rd_remark = $line['leader_rd_remark'];
	$txt_rd_take_comp_from = $line['rd_take_comp_from'];
	$txt_payment_condition = $line['payment_condition'];
	$txt_test_report_require = $line['test_report_require'];
	$txt_request_rd_to_sent_to_store_within = $line['request_rd_to_sent_to_store_within'];
	$txt_sight_glass_number = $line['sight_glass_number'];
	$txt_sight_glass_file = $line['sight_glass_file'];
	$txt_remark = $line['remark'];

	$txt_rd_informed_1st_schedule_to_mkt_date = $line["rd_informed_1st_schedule_to_mkt_date"];
	$txt_rd_informed_1st_schedule_to_mkt_date_edit_by = $line["rd_informed_1st_schedule_to_mkt_date_edit_by"];
	$txt_rd_informed_revised1_schedule_to_mkt_date = $line["rd_informed_revised1_schedule_to_mkt_date"];
	$txt_rd_informed_revised1_schedule_to_mkt_date_edit_by = $line["rd_informed_revised1_schedule_to_mkt_date_edit_by"];
	$txt_rd_informed_revised2_schedule_to_mkt_date = $line["rd_informed_revised2_schedule_to_mkt_date"];
	$txt_rd_informed_revised2_schedule_to_mkt_date_edit_by = $line["rd_informed_revised2_schedule_to_mkt_date_edit_by"];

	$txt_rd_informed_1st_schedule_to_mkt_date = $line["rd_informed_1st_schedule_to_mkt_date"];
	$txt_rd_informed_revised1_schedule_to_mkt_date = $line["rd_informed_revised1_schedule_to_mkt_date"];
	$txt_rd_informed_revised2_schedule_to_mkt_date = $line["rd_informed_revised2_schedule_to_mkt_date"];
	$num_lot = $line["num_lot"];
	$txt_rd_get_part_from_prod = $line["rd_get_part_from_prod"];
	$txt_rd_send_to_store = $line["rd_send_to_store"];

	$Result3 = $db2->query("SELECT storedate FROM base_plan_rd_detail where sr_no = '$txt_sr_no' ");
	$temp_line = mysqli_fetch_array($Result3);

	$txt_new_date_to_store_rev1 = $temp_line["storedate"];
	$txt_new_date_to_store_rev2 = $line["new_date_to_store_rev2"];
	$txt_serial_no = $line["serial_no"];
	$txt_store_date = $line["store_date"];
	$txt_leader_rd_confirm_orderingcode = $line["leader_rd_confirm_orderingcode"];
	$txt_engineer_rd_detail = $line["engineer_rd_detail"];
	$txt_engineer_rd_file = $line["engineer_rd_file"];
	$txt_sr_from = $line["sr_from"];
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
	<link href="../css/bootstrap-datepicker.min.css" rel="stylesheet">
    <style>
		.table_sample{
			font-size: 10px;
		}
		.table-dark{
			background-color: #7788c5!important;
		}
		
		.form-select{
			border: none;
    		margin-top: 18px;
		}
		a:hove{
			color: black;
		}
		.myCalendar {
			position: absolute;
			right: 40px;
			z-index: 0;
		}
    </style>
</head>

<body id="page-top">
<input type="hidden" id="count_model" name="count_model" value="<?=count($txt_model)?>" >
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
                    <a href="viewsr.php" class="back-menu" >
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
                                    <h6 class="m-0 font-weight-bold text-primary">Leader R&D Answer Sample Request (SR)</h6>
                                    
                                    
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
																<table  cellpadding="0" cellspacing="0" width="100%" >
																	<thead class="table-dark" style="font-size:15px">
																		<tr>
																			<td class="labelnprsubjectchange" colspan="100%">Information</td>
																		</tr>
																	</thead>
																<tr><td colspan="100%" style="height:10px"></td></tr> 
																<tr>
																	<td class="label">Sample Request No. :</td>
																	<td class="control"><?=$txt_sr_no?></td>		
																</tr>
																<tr><td colspan="100%" style="height:5px"></td></tr> 
																<tr>
																	<td class="label">Project :</td>
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
																			$Query = "select * from npr where project_id ='$txt_project_id' and project_id !='0'";
																			$Result = $db2->query($Query);					
																			$row_npr=mysqli_num_rows($Result);
																			mysqli_free_result($Result);
																			if($row_npr != 0 & $txt_project_id != ''){?>
																				<tr><td colspan="100%" style="height:20px"></td></tr> 
																				<tr>
																					<td colspan="100%" width = '100%' align="center">
																						<table width="100%">
																							<tr>
																								<td>NPR No.</td>
																								<td>Customer</td>
																								<td>Model</td>
																								<td>4Digit</td>
																								<td>Issued Date</td>
																								<td>Issued By</td>
																								<td class="icon">Status</td>
																							</tr>
																							<?
																							$Query = "select * from npr a left outer join customer b on a.customer_id=b.customer_id left outer join status_npr c on a.id_npr_status=c.id where project_id ='$txt_project_id' and project_id !='0'";
																							$Result = $db2->query($Query);					
																							while($line=mysqli_fetch_array($Result)){ ?>
																							<tr>
																								<td class="name"><?echo $line['npr_no'];?></td>
																								<td class="name"><?echo $line['customer_name'];?></td>
																								<td class="name"><?echo $line['target_model_name'];?></td>
																								<td class="name"><?echo $line['fourdigit'];?></td>
																								<td class="name"><?echo $line['mkt_issued_date'];?></td>
																								<td class="name"><?echo $line['mkt_issued_by'];?></td>
																								<td class="name"><?echo $line['npr_status'];?></td>
																							</tr>
																								<?}mysqli_free_result($Result);?>
																							<tr><td colspan="100%" style="height:1px" bgcolor="green"></td></tr> 
																						</table>
																					</td>		
																				</tr>
																				<?}?>

																				<?// check npr have sr this project
																				$Query = "select * from sr where project_id ='$txt_project_id' and project_id !='0'";
																				$Result = $db2->query($Query);					
																				$row_sr=mysqli_num_rows($Result);
																				mysqli_free_result($Result);
																				if($row_sr != 0  & $txt_project_id != ''){?>
																					<tr><td colspan="100%" style="height:10px"></td></tr> 
																					<tr>
																						<td colspan="100%" width = '100%' align="center">
																							<table width="100%">
																								<tr>
																									<td>SR No.</td>
																									<td>Customer</td>
																									<td>Model</td>
																									<td>4Digit</td>
																									<td>Issued Date</td>
																									<td>Issued By</td>
																									<td class="icon">Status</td>
																								</tr>
																								<?
																								$Query = "select * from sr a left outer join customer b on a.customer_id=b.customer_id left outer join status_main_sr c on a.id_sr_status=c.id where project_id = '$txt_project_id'  and project_id !='0'";
																								$Result = $db2->query($Query);				
																								while($line=mysqli_fetch_array($Result)){ ?>
																									<tr>
																										<td class="name"><?echo $line['sr_no'];?></td>
																										<td class="name"><?echo $line['customer_name'];?></td>
																										<td class="name"><?echo $line['model'];?></td>
																										<td class="name"><?echo $line['four_digit'];?></td>
																										<td class="name"><?echo $line['mkt_issued_date'];?></td>
																										<td class="name"><?echo $line['mkt_issued_by'];?></td>
																										<td class="name"><?echo $line['sr_status'];?></td>
																									</tr>
																									<?}mysqli_free_result($Result);?>
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
																	<td class="label"> <u>**Customer Test Performance only (Minimun oil)</u> :</td>
																	<td class="control"><input type="checkbox" disabled <?if($test_performance=="1") echo "checked";?>></td>	
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
																			<input type="hidden" class='inputAssy1' id="store_date" value="">
																			<table width="100%" class="table_sample" id="my_table"> 
																				<thead style="font-size:10px; background-color: #c8d0e7!important;">
																					<tr>
																						<th>Item</th>
																						<th>Model</th>
																						<th>4Digits</th>
																						<th>Status</th>
																						<th>Qty(Set)</th>
																						<th>R&D take comp. from</th>
																						<th>BOM</th>
																						<th>Payment condition</th>
																						<th>Test report require</th>
																						<th><div style="width: 50px;">Request R&D to send to store within</div></th>
																						<th>Sight Glass (Quantity)</th>
																						<th>Sight Glass (File)</th>
																						<th>Remark</th>
																						<th>Lot</th>
																						<th>Get part from prod.</th>
																						<th>Send to store 1st commitment</th>
																						<th>Send to store 2nd commitment</th>
																						<th>Send to store 3th commitment</th>
																						<th>RDC No.</th>
																						<th>Qty.</th>
																						<th>Serial No.</th>
																						<th>Store date.</th>
																					</tr>
																				</thead>
																				<input type="hidden" id="idSR" value="<?php echo $id?>">
																				<input type="hidden" id="sr_no" value="<?php echo $txt_sr_no?>">
																				<input type="hidden" id="result5_numrow" value="<?php echo $Result5->num_rows?>">
																				<?php
																				$i=1;
																				 while ($line5 = mysqli_fetch_array($Result5)){ 
																						if($i > 1){
																							$sample_lable= 'sample_lable2';
																							$HiddenStyle = 'display:none;';
																						}
																						$id_sr = $line5['sr_id'];
																				?>
																				<tr>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$jj+1?></span></td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$txt_model?></span></td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$txt_four_digit?></span></td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$txt_status?></span></td>
																					<td class="<?php echo $sample_lable ?>"><input type="text" class="txt" style="width:40px; <?php echo $HiddenStyle ?>" name="txt_qty" id="txt_qty" value="<?=$txt_qty?>"></td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$txt_rd_take_comp_from?></span></td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$BOM?></span></td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$txt_payment_condition?></span></td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$txt_test_report_require?></span></td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$txt_request_rd_to_sent_to_store_within?></span></td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$txt_sight_glass_number?></span></td>
																					<td class="<?php echo $sample_lable ?>">
																						<?if($txt_sight_glass_file != ''){?>
																							
																								
																						<?}else{?>
																							
																						<?}?>
																					</td>
																					<td class="<?php echo $sample_lable ?>"><span style="<?php echo $HiddenStyle ?>"><?=$txt_remark?></td>
																					<td class="sample_lable"><?=$i?></td>
																					<td>
																						<div class="date-contain">
																							<span><?php echo $txt_rd_get_part_from_prod?></span>
																							<input type="hidden" id="rd_get_part_from_prod" name="txt_rd_get_part_from_prod" value="<?php echo $txt_rd_get_part_from_prod ?>">
																						</div>
																					</td>
																							<td>
																								<div class="date-contain">
																									<span><?php echo $txt_rd_send_to_store;?></span>
																									<input type="hidden" id="send_to_store" name="txt_rd_send_to_store" value="<?php echo $txt_rd_send_to_store ?>">
																								</div>
																							</td>
																							<td>
																								<div class="date-contain">
																									<span ><?php echo $txt_new_date_to_store_rev1?></span>
																									<input type="hidden" id="new_date_to_store_rev1" name="txt_new_date_to_store_rev1" value="<?php echo $txt_new_date_to_store_rev1 ?>">
																								</div>
																							</td>
																							<td>
																								<div class="date-contain">
																									<span><?php echo $txt_new_date_to_store_rev2?></span>
																									<input type="hidden" id="new_date_to_store_rev2" name="txt_new_date_to_store_rev2" value="<?php echo $txt_new_date_to_store_rev2 ?>">
																								</div>
																							</td>
																							
																							<td>
																							<?php
																								$Query7 = "SELECT topic_doc_no FROM serial_of_lot WHERE sr_no = '$sr_no' AND lot = '$i'";
																								$Result7 = $db2->query($Query7);

																								$Query6 = "SELECT a.id, b.topic_doc_no FROM sr a LEFT JOIN rd_request b ON a.id = b.id_sr WHERE a.id = '$id_sr' GROUP BY b.topic_doc_no;";
																								$Result6 = $db2->query($Query6);

																								echo '<div class="input-group mb-3">';
																								echo '<select class="form-select" id="inputGroupSelect02_1" onchange="getValue(this.value,1)">';
																								echo '<option selected disabled>Choose...</option>';

																								while ($line6 = mysqli_fetch_array($Result6)) {
																									$selected = '';
																									// If there are rows in $Result7 and the current $line6['topic_doc_no'] matches any in $Result7, mark it as selected
																									if ($Result7->num_rows > 0) {
																										$Result7->data_seek(0); // Reset the result pointer
																										while ($line7 = mysqli_fetch_array($Result7)) {
																											if ($line6['topic_doc_no'] == $line7['topic_doc_no']) {
																												$selected = 'selected';
																												break;
																											}
																										}
																									}
																									echo '<option value="' . $line6['topic_doc_no'] . '" ' . $selected . '>' . $line6['topic_doc_no'] . '</option>';
																								}

																								echo '</select>';
																								echo '</div>';
																							?>

																							</td>
																							<td>
																								<input id="qty_serial1" class="qty_serial" value="<?php echo $line5['sl_qty']; ?>" readonly>
																							</td>
																							<?php if($Result5->num_rows > 1){ ?>
																							<td class="control center" ><textarea class="serial_no form-control" name="txt_serial_no" id="<?php echo $sr_no."_1" ?>" class="txt" style="width:80px;" onchange="get_serial(this)"><?=$line5["serial_no"];?></textarea></td>
																							<?php }else{ ?>
																							<td class="control center" ><textarea class="serial_no form-control" name="txt_serial_no" id="<?php echo $sr_no."_1" ?>" class="txt" style="width:80px;" onchange="get_serial(this)"><?=$txt_serial_no;?> </textarea></td>	
																							<?php } ?>
																							<td>
																								<?php if($Result5->num_rows > 1){ ?>
																								<div class="contain-calendar">	
																									<input type="text" class='inputAssy1' id="store_date" name="txt_store_date" value="<?=$line5["store_date"];?>"><i class="myCalendar fa-regular fa-calendar"></i>
																								</div>
																								<?php }else{ 
																								?>
																								<div class="container-calendar">
																									<input type="text" class='inputAssy1' id="store_date" name="txt_store_date" value="<?=$txt_store_date;?>"><i class="myCalendar fa-regular fa-calendar"></i>	
																								</div>
																								
																								<?php } ?>
																									
																								
																							</td>
																						</tr>
																						<? $i++; } ?>	
																					</table>
																						
																				</td>		
																			</tr>
																			<tr><th colspan="100%" style="height:5px"></th></tr>
																			<tr>
																				<td></td>
																				<td style="text-align: end;">
																					<a class="btn btn-success" id="add_btn"><i class="fa-solid fa-plus"></i></a>
																					<a class="btn btn-danger" id="del_btn"><i class="fa-solid fa-minus"></i></a>
																				</td>
																			</tr>
																			<tr><th colspan="100%" style="height:5px"></th></tr>
																			<tr>
																				<td class="label">Leader R&D Remark :</td>
																				<td class="control"><textarea class="form-control" name="txt_leader_rd_remark" id="txt_leader_rd_remark" class="txt" style="heigh:100px;width:200px;"><?=$txt_leader_rd_remark?></textarea></td>		
																			</tr>
																			<tr><td colspan="100%" style="height:5px"></td></tr> 
																			<tr>
																				<td class="label">Confirm Orderingcode :</td>
																				<td class="control"><input class="form-control" name="txt_leader_rd_confirm_orderingcode" id="txt_leader_rd_confirm_orderingcode" class="txt" style="heigh:100px;width:200px;" value="<?=$txt_leader_rd_confirm_orderingcode?>"></input></td>		
																			</tr>
																			<tr><td colspan="100%" style="height:5px"></td></tr> 
																			<tr>
																				<td class="label">Engineer R&D Detail :</td>
																				<td class="control"><?=$txt_engineer_rd_detail?></td>		
																			</tr>
																			<tr><td colspan="100%" style="height:5px"></td></tr> 
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
																				<tr><td colspan="100%" style="height:10px"></td></tr> 
																				<tr>
																					<td class="label">Report :</td>
																					<td class="control">
																						<div id="table_view_reportsr" style="clear:both;"><img src="../../../image/loading.gif"></div>
																					</td>
																				</tr>
																				<tr>
																					<td class="label"></td>
																					<td class="control">
																						<p id="f1_upload_process" style="font-size:11px;">&nbsp;&nbsp;Loading...<br/><img src="../../../image/loaderupload.gif" /><br/></p>
																						<p id="f1_upload_form" align="left"><br/>
																							<label class="myLabel"><input name="myfile" type="file" size="30" /></label>
																							<label><input type="button" name="submitUpload" id="submitUpload" class="btn btn-primary" value="Upload" onclick="startUpload();"/></label>
																						</p>
																						<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
																					</td>
																				</tr>
																				<tr><td colspan="100%" style="height:10px"></td></tr> 
																				<tr>
																					<td class="label">Compressor From :</td>
																					<td class="control">
																						<input type="radio" name="sr_from" <?if($txt_sr_from == "Mass Withdraw") echo "checked";?> value="Mass Withdraw" > Mass Withdraw<br>
																						<input type="radio" name="sr_from" <?if($txt_sr_from == "Mass Assembly") echo "checked";?> value="Mass Assembly" > Mass Assembly<br>  
																						<input type="radio" name="sr_from" <?if($txt_sr_from == "New Assembly") echo "checked";?> value="New Assembly" > New Assembly<br> 
																					</td>
																				</tr>
																				<tr><td colspan="100%" style="height:10px"></td></tr> 
																				<tr><td colspan="100%" style="height:8rem;"></td></tr> 
																				<tr>
																					<td  style="text-align:center">
																						<input type="button" name="saveBtn" id="saveBtn" class="btn btn-primary" value="Save">
																						&nbsp;
																						<input type="reset" class="btn btn-danger" value="Reset">
																					</td>
																					<td>
																						<input type="button" name="allSubmit" id="allSubmit" class="btn btn-success" value="Finish" onclick="startSaveAll()">
																					</td>
																				</tr> 
																				<tr><td colspan="100%" style="height:10px"></td></tr> 
																				<tr><td colspan="100%" style="text-align:right" class="labelnprsubjectchange"><a style="padding-right: 18rem;">Auto send e-mail to MKT when your click Finish</a></td></tr> 
																			</table>
																		</td>
														</tr>
													</table>
											</div>
											<input type="hidden"  name="checksave" id="checksave" value="1">
											<input type="hidden"  name="qty_value" id="qty_value" >

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
    <script src="../js/sb-admin-2.js"></script>

    
	
    <script src="calendar/calendar.js"></script>
	<script src="../js/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
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
			xmlHttp.open("GET","table_view_reportsr.php?id_sr=" + '<?=$id?>' + "&del=" + del,true);
			xmlHttp.send(null);
		}
		function handleStateChange(){
			if(xmlHttp.readyState == 4){
				if(xmlHttp.status == 200){
					document.getElementById("table_view_reportsr").innerHTML = xmlHttp.responseText;
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
			document.forms['frmConsole'].action = "upload.php?id_sr=" + <?=$id?>;
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
			document.getElementById('f1_upload_form').innerHTML = result + '<label class="myLabel"><input name="myfile" type="file" size="30" /><\/label><label><input type="button" name="submitUpload" id="submitUpload" class="btn btn-primary" value="Upload" onclick="startUpload();"/><\/label>';
			document.getElementById('f1_upload_form').style.visibility = 'visible';      

			startRequest(-1);
			return true;   
		}
		
	</script>
	<script>
		
		document.getElementById("add_btn").addEventListener("click", function() {
			addRow();
		});
		document.getElementById("del_btn").addEventListener("click", function() {
			delRow();
		});
		document.getElementById("saveBtn").addEventListener("click", function() {
			saveData();
		});

		function addRow() {
			var table = document.getElementById("my_table").getElementsByTagName("tbody")[0];
			var txt_qty = document.getElementById('txt_qty').value;
			if(table.rows.length < txt_qty){
				var newRow = table.insertRow(table.rows.length); // เพิ่มแถวใหม่
				var send_to_store = document.getElementById("send_to_store").value;
				var get_part_form_prod = document.getElementById('rd_get_part_from_prod').value;
				var new_date_to_store_rev1 = document.getElementById('new_date_to_store_rev1').value;
				var new_date_to_store_rev2 = document.getElementById('new_date_to_store_rev2').value;
				var store_date = document.getElementById('store_date').value;
				var sr_no = document.getElementById('sr_no').value;
				var length_row = table.rows.length;
				var cellCount = table.rows[0].cells.length; 
				for (var i = 0; i < cellCount; i++) {
					var cell = newRow.insertCell(i);
					cell.innerHTML = "";
					if (i < 13) {
						cell.style = "height:76px; width: 150px;";
						cell.style.border = "transparent";
					}else if(i == 13) {
						cell.innerHTML = length_row;
						
					}else if (i == 14) {
						cell.innerHTML = get_part_form_prod;
					}else if (i == 15) {
						cell.innerHTML = send_to_store;
					}else if (i == 16) {
						cell.innerHTML = new_date_to_store_rev1;
					}else if (i == 17) {
						cell.innerHTML = new_date_to_store_rev2;
					}else if(i == 18){
						var selectHtml1 = '<select class="form-select" id="inputGroupSelect02_'+length_row+'" onchange="getValue(this.value,'+length_row+')">';
						var selectHtml3 = '<?php 
											$Query7 = "SELECT sr_no, topic_doc_no FROM serial_of_lot WHERE sr_no = '$sr_no' AND lot = 'HERE2'"; 
											
											?>';
						
						
						var replace_qry = selectHtml3.replace('HERE2', length_row);
						var rdc_qry = '<?php
										while ($line7 = mysqli_fetch_array($Result7)) {
											$rdc = $line7['topic_doc_no'];
										}
										?>';
						var rdc_of_lot = '<?php echo $rdc  ?>';
						var selectHtml2 = '<?php 
											$Query6 = "SELECT a.id, b.topic_doc_no FROM sr a LEFT JOIN rd_request b ON a.id = b.id_sr WHERE a.id = '$id_sr' GROUP BY b.topic_doc_no;"; 
											$Result6 = $db2->query($Query6);
											echo '<div class="input-group mb-3">'; echo 'HERE';  
											echo '<option selected disabled>Choose...</option>'; 
											while ($line6 = mysqli_fetch_array($Result6)) {
												if ($line6['topic_doc_no'] == $rdc) { // เปรียบเทียบค่ากับ $rdc ที่ได้รับมา
													echo '<option value="' . $line6['topic_doc_no'] . '" selected>' . $line6['topic_doc_no'] . '</option>'; // กำหนด selected หากตรงกัน
												} else {
													echo '<option value="' . $line6['topic_doc_no'] . '">' . $line6['topic_doc_no'] . '</option>';
												}
											}
											echo '</select>';
											echo '</div>';
											?>';
						var selectHtml = selectHtml2.replace('HERE', selectHtml1);
						console.log(rdc_of_lot);
						cell.innerHTML = selectHtml;
					}else if(i == 19){
						cell.innerHTML = "<input id='qty_serial"+length_row+"' class='qty_serial' value='' readonly>";
					}else if(i == 20){
						cell.innerHTML = "<textarea class='serial_no form-control' name='txt_serial_no' id="+ "'"+sr_no+"_"+length_row+"'"+ " onchange='get_serial(this)'></textarea>";
						cell.style = "padding: 6px;padding-top: 16px;";
						cell.classList.add("control");
					}else if(i == 21){
						cell.innerHTML = "<div class='container-calendar'><input type='text' class='inputAssy1' placeholder='0000-00-00'><i class='myCalendar fa-regular fa-calendar'></i></div>"
						$('.inputAssy1').datepicker({
							format: 'yyyy-mm-dd',
							startDate: '2022-02-01',
							todayBtn: 'linked',
							todayHighlight: true,
							autoclose: true
						});
					}
					
				}
			}
			
		}
		
		function delRow(){
			var table = document.getElementById("my_table").getElementsByTagName("tbody")[0];
			var num_row = document.getElementById("result5_numrow").value;
			if (table.rows.length > 1 && table.rows.length != num_row) {
				table.deleteRow(table.rows.length - 1);
			}
		}

		function saveData() {
			var table = document.getElementById("my_table").getElementsByTagName("tbody")[0];
			count_row = table.rows.length;
			idSR = document.getElementById('idSR').value;
			sr_no = document.getElementById('sr_no').value;
			
			var serialNoData = [];
			var store_date = [];
			
			var textareas = document.getElementsByClassName('form-control');
			for (var i = 0; i < textareas.length; i++) {
				serialNoData.push(textareas[i].value);
			}

			var qty = [];
			for (var i = 1; i <= count_row; i++) {
				var qty_val = document.getElementById('qty_serial' + i);
				qty.push(qty_val.value);
			}

			
			var rdc_no = [];
			for (var i = 1; i <= count_row; i++) {
				var selectElement = document.getElementById('inputGroupSelect02_' + i);
				rdc_no.push(selectElement.value);
			}
			var txt_store_date = document.getElementsByClassName('inputAssy1');
			for (var i = 0; i < txt_store_date.length; i++) {
				store_date.push(txt_store_date[i].value);
			}

			$.ajax({
				url: 'update_num_lot.php',
				type: 'POST',
				data: {
					count_row: count_row,
					idSR: idSR,
					sr_no: sr_no,
					serialNoData: serialNoData, // ส่งค่าข้อมูลที่กรอกใน textarea ไปยัง ajax
					store_date: store_date, // ส่งค่าข้อมูลที่กรอกใน textarea ไปยัง ajax
					rdc_no: rdc_no,
					qty: qty, // ส่งค่าที่ถูกเลือกใน select ไปยัง ajax
				},
				success: function(response) {
					alert(response);
				},
				error: function(xhr, status, error) { // เมื่อเกิดข้อผิดพลาดในการส่งข้อมูล
					alert(error);
				}
			});
		}


	</script>
	<script> 
        $('.inputAssy1').datepicker({
            format: 'yyyy-mm-dd',
			startDate: '2022-02-01',
			todayBtn: 'linked',
			todayHighlight: true,
			autoclose: true
        });
    </script>
	 <script>
        function getValue(selectedValue,length_row) {
			var qty_serial = 'qty_serial'+length_row;
            $.ajax({
                type: "POST",
                url: "fetch_qty.php",
                data: { selectedValue: selectedValue },
                success: function(response) {
                    console.log("Selected value: " + selectedValue +qty_serial);
					$('#'+qty_serial).val(response);
					$('#qty_value').val(response);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred while processing the request: " + error);
                }
            });
        }
		
		function get_serial(textarea){
			var textarea_id = textarea.id;
			var serial_Value = textarea.value;
			var serial_Value = parseInt(serial_Value);
			var qty_val = parseInt(document.getElementById('qty_value').value);
			var total = serial_Value + qty_val;
			var total = total - 1 ;
			
			if(serial_Value == total){
				var result = serial_Value;
			}else{
				var result = serial_Value +"-"+total;
			}
			textarea.value = result;
            
        }
    </script>
</body>

</html>
