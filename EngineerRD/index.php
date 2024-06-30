<?php
// Load the database configuration file
session_start();

if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}

$sortfile = $_SESSION['filename'];
$_SESSION['company_name'] = "Siam Compressor Industry.CO.,LTD All rights reserved.";
include_once '../DBconfig/dbConfig2.php';
require_once( '../../../function/function.php' );
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
        
    </style>
</head>

<body id="page-top">
    <!-- Display status message -->
    <?php if(!empty($statusMsg)){ ?>
    <div class="col-xs-12 p-3">
        <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
    </div>
    <?php } ?>
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
                    <!-- <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" onclick="openMenuLink()">
                        <i class="fa fa-bars"></i>
                    </button> -->
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
            </div>
                
                <div class="container-fluid">
                    <ul class="nav nav-tabs" style="margin-top: 15px;">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../EngineerRD/index.php">Engineer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../LeaderRD/viewsr.php">Leader</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                        </li> -->
                    </ul>
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">R&D</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-striped table-bordered">
                                            <thead class="table-dark" style="font-size:13px">
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Sample No.</th>
                                                    <th>Model</th>
                                                    <th>4Digit</th>
                                                    <th>Orderingcode</th>
                                                    <th>BOM</th>
                                                    <th>ModelNPR</th>
                                                    <th>4Digit</th>
                                                    <th>Orderingcode</th>
                                                    <th>Customer</th>
                                                    <th>Issued Date</th>
                                                    <th>Issued By</th>
                                                    <th>DateToR&D</th>
                                                    <th>View</th>
                                                    <th>Edit</th>
                                                    <th >Sent2Leader</th>
                                                    <th>Reject</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            // Get member rows 
                                                
                                                $numrow=0;
                                                
                                                $Query = "SELECT a.*,a.id as id_sr 
                                                ,c.*
                                                ,b.*
                                                ,d.model as model
                                                ,d.four_digit
                                                , d.orderingcode as d_orderingcode
                                                ,f.target_model_name
                                                ,f.fourdigit
                                                , f.orderingcode as f_orderingcode
                                                ,a.qty
                                                ,IF(g.state IS NULL,'Develop', g.state) as BOM
                                                from sr a 
                                                left outer join customer b on a.customer_id= b.customer_id 
                                                left outer join status_main_sr c on a.id_sr_status=c.id 
                                                left outer join comp_data d on a.comp_id=d.id 
                                                left outer join npr f on a.model_npr_id=f.id
                                                left outer join formula_model g ON COALESCE(NULLIF(d.model, ''), f.target_model_name) = g.model 
                                                where customer_name like '%$search_customer_name%' 
                                                    and sr_no like '%$search_sr_no%' 
                                                    and a.mkt_issued_by like '%$search_issued_name%' 
                                                    and (d.model like '%$search_model_name%' or f.target_model_name like '%$search_model_name%')
                                                    and id_sr_status='4' 
                                                    and sr_cancle_by = ''
                                                    and ( leader_rd_remark like '%$remark%' OR engineer_rd_detail like '%$remark%' ) 
                                                    and IF(g.state IS NULL,'Develop', g.state) = 'Mass Production'";
                                                $Result = $db2->query($Query);
                                                $allrow = $Result->num_rows;
                                                
                                                $Query .=" order by  ".$sort_query." ".$sort_type_query. " ";
                                                if ( $p != -1 ){
                                                    $Query .=" limit ".$p*$numrowp.",".$numrowp ." ";
                                                }
                                                					
                                                while ($line = $Result->fetch_array()) { 
                                                        
                                                        if ($line['id_sr'] != "") {
                                                            $encodedIdSr = base64_encode($line['id_sr']);
                                                            $encodedSrNo = base64_encode($line['sr_no']);
                                                            $encodedBom = base64_encode($line['BOM']);
                                                            
                                                            $url = "srview.php?id=" . $encodedIdSr . "&srno=" .$encodedSrNo. "&bom=" .$encodedBom ;
                                                            $url2 = "managesr.php?id=" . $encodedIdSr . "&srno=" .$encodedSrNo. "&bom=" .$encodedBom ;
                                                        }
                                                    ?>
                                                <tr>
                                                    <?$year = explode("/",$line['sr_no']);?>
                                                    <td class="name"><?echo $year[1];?></td>
                                                    <td class="name"><?echo $line['sr_no'];?></td>
                                                    <td class="name"><?echo $line['model'];?></td>
                                                    <td class="name"><?echo $line['four_digit'];?></td>
			                                        <td class="name"><?echo $line['d_orderingcode'];?></td>
                                                    <td class="icon"><?echo $line['BOM'];?></td>
			                                        <td class="name"><?echo $line['target_model_name'];?></a></td>
                                                    <td class="name"><?echo $line['four_digit'];?></td>
                                                    <td class="name"><?echo $line['f_orderingcode'];?></td>
                                                    <td class="name"><?echo $line['customer_name'];?></td>
                                                    <td class="name"><?echo $line['mkt_issued_date'];?></td>
                                                    <td class="name"><?echo $line['mkt_issued_by'];?></td>
                                                    <td class="name"><?echo $line['mkt_approved_date'];?></td>
                                                    <td class="icon">
                                                        <a href="<?php echo $url; ?>"><i class="icon-edit fa-regular fa-eye"></i></a>
                                                    </td>
                                                    <td class="icon">
                                                        <a href="<?php echo $url2; ?>"><i class="icon-edit fa-regular fa-pen-to-square"></i></a>
                                                    </td>
                                                    
                                                    <td class="icon">
                                                        <input type="hidden" name="send2Lead" value="" >
                                                        <input type="hidden" id="id_sr_value" name="id_sr_value" value="<?php echo $line['id_sr'];  ?>">
                                                        <input type="hidden" id="sr_no_value" name="sr_no" value="<?php echo $line['sr_no']; ?>">
                                                        <input type="hidden" id="R_D_take_comp_from" name="R_D_take_comp_from" value="<?php echo $line['rd_take_comp_from'];  ?>">
                                                        <button type="button" class="viewIcon"  onclick="checkRdStore('<?php echo $line['id_sr'];  ?>', '<?php echo $line['sr_no']; ?>', '<?php echo $line['rd_take_comp_from'];  ?>')">
                                                            <i class="icon fa-regular fa-paper-plane"></i>
                                                        </button>
                                                    </td>
                                                    <td class="icon"><a onclick="openModal2(); document.getElementById('reject_sr').value='<?=$line['id_sr']?>';document.getElementById('reject_sr_no').value='<?=$line['sr_no']?>' "><i class="fa-regular fa-file-excel"></a></td>
                                                </tr>
                                                
                                            
                                                
                                                
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Store</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="myTable3" class="table table-striped table-bordered">
                                            <thead class="table-dark" style="font-size:13px">
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Sample No.</th>
                                                    <th>Model</th>
                                                    <th>4Digit</th>
                                                    <th>Orderingcode</th>
                                                    <th>BOM</th>
                                                    <th>ModelNPR</th>
                                                    <th>4Digit</th>
                                                    <th>Orderingcode</th>
                                                    <th>Customer</th>
                                                    <th>Issued Date</th>
                                                    <th>Issued By</th>
                                                    <th>DateToR&D</th>
                                                    <th>View</th>
                                                    <th>Edit</th>
                                                    <th >Sent2Leader</th>
                                                    <th>Reject</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            // Get member rows 
                                                
                                                $numrow=0;
                                                
                                                $Query = "SELECT a.*,a.id as id_sr 
                                                ,c.*
                                                ,b.*
                                                ,d.model as model,d.four_digit, d.orderingcode as d_orderingcode
                                                ,f.target_model_name,f.fourdigit, f.orderingcode as f_orderingcode
                                                ,a.qty
                                                ,IF(g.state IS NULL,'Develop', g.state) as BOM
                                                from sr a 
                                                left outer join customer b on a.customer_id= b.customer_id 
                                                left outer join status_main_sr c on a.id_sr_status=c.id 
                                                left outer join comp_data d on a.comp_id=d.id 
                                                left outer join npr f on a.model_npr_id=f.id 
                                                left outer join formula_model g ON COALESCE(NULLIF(d.model, ''), f.target_model_name) = g.model 
                                                where customer_name like '%$search_customer_name%' 
                                                    and sr_no like '%$search_sr_no%' 
                                                    and a.mkt_issued_by like '%$search_issued_name%' 
                                                    and (d.model like '%$search_model_name%' or f.target_model_name like '%$search_model_name%')
                                                    and id_sr_status='4' 
                                                    and sr_cancle_by = '' 
                                                    and ( leader_rd_remark like '%$remark%' OR engineer_rd_detail like '%$remark%' )
                                                    and IF(g.state IS NULL,'Develop', g.state) = 'Develop'";
                                                $Result = $db2->query($Query);
                                                $allrow = $Result->num_rows;
                                                
                                                $Query .=" order by  ".$sort_query." ".$sort_type_query. " ";
                                                if ( $p != -1 ){
                                                    $Query .=" limit ".$p*$numrowp.",".$numrowp ." ";
                                                }
                                                					
                                                while ($line = $Result->fetch_array()) { 
                                                       
                                                        
                                                        if ($line['id_sr'] != "") {
                                                            // ทำการ encode ค่าด้วย base64
                                                            $encodedIdSr = base64_encode($line['id_sr']);
                                                            $encodedSrNo = base64_encode($line['sr_no']);
                                                            $encodedBom = base64_encode($line['BOM']);
                                                            
                                                            $url = "srview.php?id=" . $encodedIdSr . "&srno=" .$encodedSrNo. "&bom=" .$encodedBom ;
                                                            $url2 = "managesr.php?id=" . $encodedIdSr . "&srno=" .$encodedSrNo. "&bom=" .$encodedBom ;
                                                        }
                                                    ?>
                                                <tr>
                                                    <?$year = explode("/",$line['sr_no']);?>
                                                    <td class="name"><?echo $year[1];?></td>
                                                    <td class="name"><?echo $line['sr_no'];?></td>
                                                    <td class="name"><?echo $line['model'];?></td>
                                                    <td class="name"><?echo $line['four_digit'];?></td>
			                                        <td class="name"><?echo $line['d_orderingcode'];?></td>
                                                    <?php
                                                        
                                                    ?>
                                                    <td class="icon"><?echo $line['BOM'];?></td>
			                                        <td class="name"><?echo $line['target_model_name'];?></a></td>
                                                    <td class="name"><?echo $line['four_digit'];?></td>
                                                    <td class="name"><?echo $line['f_orderingcode'];?></td>
                                                    <td class="name"><?echo $line['customer_name'];?></td>
                                                    <td class="name"><?echo $line['mkt_issued_date'];?></td>
                                                    <td class="name"><?echo $line['mkt_issued_by'];?></td>
                                                    <td class="name"><?echo $line['mkt_approved_date'];?></td>
                                                    <td class="icon">
                                                        <a href="<?php echo $url; ?>"><i class="icon-edit fa-regular fa-eye"></i></a>
                                                    </td>
                                                    <td class="icon">
                                                        <a href="<?php echo $url2; ?>"><i class="icon-edit fa-regular fa-pen-to-square"></i></a>
                                                    </td>
                                                    
                                                    <td class="icon">
                                                        <input type="hidden" name="send2Lead" value="" >
                                                        <input type="hidden" id="id_sr_value" name="id_sr_value" value="<?php echo $line['id_sr'];  ?>">
                                                        <input type="hidden" id="sr_no_value" name="sr_no" value="<?php echo $line['sr_no']; ?>">
                                                        <input type="hidden" id="R_D_take_comp_from" name="R_D_take_comp_from" value="<?php echo $line['rd_take_comp_from'];  ?>">
                                                        <button type="button" class="viewIcon"  onclick="checkRdStore('<?php echo $line['id_sr'];  ?>', '<?php echo $line['sr_no']; ?>', '<?php echo $line['rd_take_comp_from'];  ?>')">
                                                            <i class="icon fa-regular fa-paper-plane"></i>
                                                        </button>
                                                    </td>
                                                    <td class="icon"><a onclick="openModal2(); document.getElementById('reject_sr').value='<?=$line['id_sr']?>';document.getElementById('reject_sr_no').value='<?=$line['sr_no']?>' "><i class="fa-regular fa-file-excel"></a></td>
                                                </tr>
                                                
                                            
                                                
                                                
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                    
                    
                </div>

            </div>
            <!-- Modal -->
            
            <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                           <span id="srNoValue"></span>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <p id="idSrValue"></p> -->
                            <form onsubmit="submitForm()" method="post">
                                <table>
                                    <thead style="font-size:15px">
                                        <tr>
                                            <td class="headerTable" colspan="100%">Production line : </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="100%" style="height:10px"></td></tr>
                                        <tr>
                                            <td style="padding-left:28px">
                                                <div class="form-check"> 
                                                    <input class="form-check-input" name="flexRadioDefault" type="radio" value="MID" id="flexRadioDefault1" >
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                        MID
                                                    </label>
                                                    
                                                </div>
                                            </td>
                                            <td style="padding-left:28px">
                                                <div class="form-check"> 
                                                    <input class="form-check-input" name="flexRadioDefault" type="radio" value="Prod" id="flexRadioDefault2" >
                                                    <label class="form-check-label" for="flexRadioDefault2">
                                                        Prod
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left:28px">
                                                <div class="form-check">    
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                        <tr><td colspan="100%" style="height:10px"></td></tr>
                                    </tbody>
                                    <thead class="" style="font-size:15px">
                                        <tr>
                                            <td class="headerTable" colspan="100%">Design : </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="100%" style="height:10px"></td></tr>
                                        <tr>
                                            <td class="DrawingBomLabel">Drawing & BOM : </td>
                                            <td><input type="date" id="drawingBomInput" name="drawingBomInput" class="DrawingBomInput"></td>
                                        </tr>
                                        <tr><td colspan="100%" style="height:10px"></td></tr>
                                    </tbody>
                                </table>                        
                                 <input type="hidden" id="idSrValue" name="idSrValue">                   
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">SUBMIT</button>
                                </div>
                            </form>
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
    <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Reject sample</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table style="margin-left: 3rem;">
                        <tr style="padding-bottom:10px;" > 
                            <td>Reject SR :</td>
                            <td><input type="text" id="reject_sr_no" class="DrawingBomInput" readonly ></td>
                        </tr>
                        <tr style="height: 29px;"></tr>
                        <tr>
                            <td style="padding-bottom:28px;padding-left:10px;">Reason :</td>
                            <td><textarea type="text" class="DrawingBomInput" name="reject" placeholder="reason" id="reason_reject"></textarea></td>
                        </tr>
                    </table>
                    <input type="text" id="reject_sr" hidden>
                    <button onclick="reject_sample_request();" type="button" class="btn btn-primary" style="margin-left: 12rem;margin-top: 1rem;">Reject</button>
            </div>
        </div>
    </div>
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
        $(document).ready(function() {
            $('#myTable').DataTable({
                lengthMenu: [ 10, 50, 100, 200], // ตั้งค่าตัวเลือกในเมนู dropdown
                pageLength: 10 // ตั้งค่าให้แสดง 10 แถวในหน้าเริ่มต้น
            });
            $('#myTable3').DataTable({
                lengthMenu: [ 10, 50, 100, 200], // ตั้งค่าตัวเลือกในเมนู dropdown
                pageLength: 10 // ตั้งค่าให้แสดง 10 แถวในหน้าเริ่มต้น
            });
        });
    </script>
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
        function openModal2(){
            $('#modal2').modal('show');
        }
        function checkRdStore(id_sr_value, sr_no_value, take_comp_from) {
            
            if (take_comp_from === "Store"){
                $.ajax({
                    type: 'POST',
                    url: 'sendStore2Leader.php',
                    data: {
                        id_sr_value: id_sr_value,
                    },
                    success: function (response) {
                        
                        // You can handle the response from the server here if needed
                    },
                    error: function (error) {
                        console.error('Error submitting data:', error);
                    }
                });
            
            alert('Send email to leader successfully');
            location.href = document.URL;    
            }else{
                showIdSrModal(id_sr_value, sr_no_value);
                $('#modal1').modal('show');
            }
        }

    </script>
    <script>    
        function menuToggle(){
            var element = document.getElementById('menu-link');
            var element2 = document.getElementById('sidebarToggleTop');
            
            if(element.style.display == ""){
                element.style.display = "none";
            }
            if(element.style.display == "none"){
                element.style.display = "block";
            }else{
                element.style.display = "none";
            }
        }
        function showIdSrModal(id_sr_value, sr_no_value) {
            // นำค่า ID_SR มาแสดงใน modal
            document.getElementById('idSrValue').value = id_sr_value;
            document.getElementById('srNoValue').innerHTML = "Sample no. : " + sr_no_value;
        }
       
        function submitForm() {
            // Get values from the form
            var idSrValue = document.getElementById('idSrValue').value;
            var drawingBomInputValue = document.getElementById('drawingBomInput').value;

            // Get the values of the selected checkboxes
            var selectedCheckboxes = [];
            var checkboxes = document.querySelectorAll('input[type="radio"]:checked');
            checkboxes.forEach(function (checkbox) {
                selectedCheckboxes.push(checkbox.value);
            });

            if(idSrValue == "" || drawingBomInputValue == "" || selectedCheckboxes == ""){
                alert('กรุณากรอกข้อมูลให้ครบถ้วน');
            }else{
                $.ajax({
                type: 'POST',
                url: 'insert_Prodline_Design.php',
                data: {
                    idSrValue: idSrValue,
                    drawingBomInputValue: drawingBomInputValue,
                    selectedCheckboxes: selectedCheckboxes
                },
                success: function (response) {
                },
                error: function (error) {
                    console.error('Error submitting data:', error);
                }
                });
                alert('send e-mail to Leader successfully');
            }

            
            
            
        }

        function reject_sample_request(sort_query,sort_type_query,p){
            var reason = document.getElementById('reason_reject').value;
            var id = document.getElementById('reject_sr').value;
            window.location.href = "rejectSample.php?id_reject=" + id + "&reason_reject=" + reason +"&sort_query=sr_no" + "&sort_type_query=desc" + "&p=0"; 
        }
    </script>
</body>

</html>
