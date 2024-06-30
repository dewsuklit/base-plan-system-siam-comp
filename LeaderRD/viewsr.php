<?php
// Load the database configuration file
session_start();
if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}
$sortfile = $_SESSION['filename'];
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
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="../EngineerRD/index.php">Engineer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  active" aria-current="page" href="../LeaderRD/viewsr.php">Leader</a>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Leader View SR</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-striped table-bordered">
                                            <thead class="table-dark" style="font-size:13px">
                                                <tr>
                                                    <th >Y</th>
                                                    <th>No.</th>
                                                    <th>Model</th>
                                                    <th>Digit</th>
                                                    <th>Orderingcode</th>
                                                    <th>BOM</th>
                                                    <th>NPR</th>
                                                    <th>Digit</th>
                                                    <th>Orderingcode</th>
                                                    <th>Qty</th>
                                                    <th>Date</th>
                                                    <th>Answer</th>
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
                                                and sr_cancle_by ='' 
                                                and a.request_cancel_by ='' 
                                                and id_sr_status='5'
                                                and (processSR >= 7 and processSR < 9) and rd_send_to_store != '0000-00-00' 
                                                ORDER BY sr_no DESC";
                                                $Result = $db2->query($Query);
                                                $allrow = mysqli_num_rows($Result);
                                                // $Query .=" order by  ".$sort_query." ".$sort_type_query. " ";
                                                // if ( $p != -1 ){
                                                //     $Query .=" limit ".$p*$numrowp.",".$numrowp ." ";
                                                // }		
                                                while($line=mysqli_fetch_array($Result)){ 
                                                    
                                                    ?>
                                                    <tr>
                                                        <?$year = explode("/",$line['sr_no']);?>
                                                        <td class="name"><?echo $year[1];?></td>
                                                        <td class="name"><a><?echo $line['sr_no'];?></a></td>
                                                        <td class="name"><?echo $line['model'];?></td>
                                                        <td class="name"><?echo $line['four_digit'];?></td>
                                                        <td class="name"><?echo $line['d_orderingcode'];?></td>
                                                        <td class="icon"><?echo $line['BOM'];?></td>
                                                        <td class="name"><?echo $line['target_model_name'];?></td>
                                                        <td class="name"><?echo $line['fourdigit'];;?></td>
                                                        <td class="name"><?echo $line['f_orderingcode'];?></td>
                                                        <td class="name"><?echo $line['qty'];?></td>
                                                        <td class="name"><?echo $line['mkt_issued_date'];?></td>
                                                        <?php
                                                        // ตรวจสอบว่า $line['id_sr'] ไม่เป็นค่าว่าง
                                                        if ($line['id_sr'] != "") {
                                                            // ทำการ encode ค่าด้วย base64
                                                            $encodedIdSr = base64_encode($line['id_sr']);
                                                            $encodedSrNo = base64_encode($line['sr_no']);
                                                            $encodedBom = base64_encode($line['BOM']);
                                                            $url = "managesr.php?id=" . $encodedIdSr . "&srno=" .$encodedSrNo. "&bom=" .$encodedBom ;
                                                        ?>
                                                        <td class="icon">
                                                            <a href="<?php echo $url; ?>"><i class="icon fa-regular fa-pen-to-square"></i></a>
                                                        </td>
                                                    </tr>
                                                        <?
                                                        }    
                                                        ?>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
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

    <<!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.js"></script>

    <script src="../js/dataTables/jquery.dataTables.min.js"></script>
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = fileName;
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                order: [[0, 'desc']],
                lengthMenu: [ 10, 50, 100, 200], // ตั้งค่าตัวเลือกในเมนู dropdown
                pageLength: 10 // ตั้งค่าให้แสดง 10 แถวในหน้าเริ่มต้น
            });
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
</body>

</html>
