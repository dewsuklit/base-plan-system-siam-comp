<?php 
// Load the database configuration file 
session_start();

if($_SESSION["session_name"] == ''){
    echo "<script>location.href='../../../../index.html';</script>";
}

if($_SESSION['filename'] != ""){
    $sortfile = $_SESSION['filename'];
}else{
    $sortfile = 'none';
}

include_once '../DBconfig/dbConfig2.php'; 


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
    <link rel="stylesheet" href="../css/font-awesome/all.min.css">
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
        <!-- Content Wrapper -->
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
                            <a class="flex-sm-fill text-sm-center nav-link"  href="../EngineerRD/index.php">Sample Request</a>
                            <a class="flex-sm-fill text-sm-center nav-link active" aria-current="page" href="../LeaderRD/base_plan.php">Plan 
                                <div class="myActiveBar"></div>
                            </a>
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
                
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Answer Engineer</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Save</a>
                    </div> -->

                    <!-- Content Row -->
                    

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Import Exel : <?php  if($sortfile != 'none'){ echo $sortfile;} ?>  <a href="deleteData.php" class="del_file" <?php  if($sortfile == 'none'){ echo 'hidden';} ?>><img class="myIMG" src="../image/delete.png"></img></a></h6>
                                    
                                    <div class="float-end">
                                        <a href="javascript:void(0);" class="btn btn-success"  data-toggle="modal" data-target="#exampleModalCenter"><i class="plus" ></i>
                                         <i class="fa-solid fa-plus"></i>
                                         Import Excel
                                        </a>
                                        <a href="../AssyUpload/importData.php?send=true&sortfile=<?php echo $sortfile  ?>" class="btn btn-primary"><i class="plus"></i>
                                            <i class="fa-regular fa-paper-plane"></i>
                                            Send to leader
                                        </a>
                                    </div>
                                    
                                    
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-striped table-bordered" style="font-size:10px">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>SERIES</th>
                                                    <th>Model Mass</th>
                                                    <th>STATUS</th>
                                                    <th>PROJECT</th>
                                                    <th>Type Comp</th>
                                                    <th>RDC</th>
                                                    <th>MO/SR</th>
                                                    <th>CUSTOMER</th>
                                                    <th>MODEL</th>
                                                    <th>CODE</th>
                                                    <th>LOT</th>
                                                    <th>Issued</th>
                                                    <th>REQUEST BY</th>
                                                    <th>Request</th>
                                                    <th>ASSY PLAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            // Get member rows 

                                            $result = $db2->query("SELECT * FROM assy_upload WHERE file_name = '$sortfile'"); 
                                            if($result->num_rows > 0){ 
                                                $i=0; 
                                                while($row = $result->fetch_assoc()){ $i++; 
                                            ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row['SERIES']; ?></td>
                                                    <td><?php echo $row['Model_Mass']; ?></td>
                                                    <td><?php echo $row['STATUSs']; ?></td>
                                                    <td><?php echo $row['PROJECT']; ?></td>
                                                    <td><?php echo $row['Type_comp']; ?></td>
                                                    <td><?php echo $row['RDC']; ?></td>
                                                    <td><?php echo $row['MO_SR']; ?></td>
                                                    <td><?php echo $row['CUSTOMER']; ?></td>
                                                    <td><?php echo $row['MODEL']; ?></td>
                                                    <td><?php echo $row['CODE']; ?></td>
                                                    <td><?php echo $row['LOT']; ?></td>
                                                    <td><?php echo $row['Issued']; ?></td>
                                                    <td><?php echo $row['REQUEST_BY']; ?></td>
                                                    <td><?php echo $row['Request']; ?></td>
                                                    <td><?php echo $row['PLAN']; ?></td>
                                                    
                                                </tr>
                                            <?php } }else{ ?>
                                                <tbody>
                                                
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle" style="color: white;">Import Excel</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="row g-1" action="../AssyUpload/importData.php" method="post" enctype="multipart/form-data">
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="file" id="fileInput" aria-describedby="inputGroupFileAddon04">
                                                            <label class="custom-file-label" for="fileInput"></label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <input type="submit" class="btn btn-primary mb-3" name="importSubmit" value="Import">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        
                    </div>

                    <!-- Content Row -->
                    
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

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

    
    <script src="../js/dataTables/jquery.dataTables.min.js"></script>
    <script>
        var element = document.getElementById('menu-link');
        var element2 = document.getElementById('sidebarToggleTop');
        if (element2.style.display === "none"){
                element.style.display = "none";
            }
    </script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                lengthMenu: [ 25, 50, 100, 200], // ตั้งค่าตัวเลือกในเมนู dropdown
                pageLength: 200 // ตั้งค่าให้แสดง 10 แถวในหน้าเริ่มต้น
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
</body>

</html>
