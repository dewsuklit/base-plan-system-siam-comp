<?php 
session_start();
if($_SESSION["session_name"] == ''){
    echo "<script>location.href = '../../../../index.html';</script>";
}
// Load the database configuration file 
include_once '../DBconfig/dbConfig2.php';
require_once('../assy_calendar/class/tc_calendar.php');
require_once( '../../../function/function.php' ); 
$_GET['status'] = 'default';

    
$config = array(
	"trace"      => 1,          // enable trace to view what is happening
	"exceptions" => 0,          // disable exceptions
	"cache_wsdl" => 0
);

$client = new SoapClient("http://172.31.23.7:80/bom/Services/GlobalWebService.asmx?wsdl", $config);
$result2 = $client->GetModelState()->GetModelStateResult;

$json = json_decode($result2,true);
$model = array();
$state = array();
$plan = array();

foreach ($json as $data) {
	array_push($model,$data['ModelName']); 
	array_push($state,$data['State']); 
	array_push($plan,$data['Ready_to_plan']); 
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
    
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/fontawesome-free-6.5.1-web/css/all.min.css">
    <link href="../css/bootstrap-datepicker.min.css" rel="stylesheet">
    <style>
        table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td{
            text-align: center;
        }
        .input-container {
            position: relative;
        }
        .inputAssy1 {
            font-size: 13px;
            font-weight: 900;
            z-index: 1; 
        }
        .myCalendar2{
            position: absolute;
            right: 32px;
            z-index: 0;
            padding-top: 2px;
        }
        .myInput{
            position: absolute;
            z-index: 0;
            font-size: 13px;
            bottom: 5px;
            left: 148px;
        }
        .table td{
            vertical-align: middle;
        }
        a:hover{
            color: black;
        }
        .chevron-down {
            display: none;
        }
        .fix-width-100{
            width: 100px;
        }
        .fix-width-200{
            width: 200px;
        }
        .left-72{
            left:72px
        }
        .left-179{
            left:179px
        }
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

        <!-- Sidebar -->
        
        <!-- End of Sidebar -->

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

                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle">CALORIE TEST</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <div class="mb-3">
                                                    <label for="recipient-name" class="col-form-label">Recipient:</label>
                                                    <input type="text" class="form-control" id="recipient-name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="message-text" class="col-form-label">Message:</label>
                                                    <textarea class="form-control" id="message-text"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Submit</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <div class="mb-3">
                                                    <label for="recipient-name" class="col-form-label">Recipient:</label>
                                                    <input type="text" class="form-control" id="recipient-name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="message-text" class="col-form-label">Message:</label>
                                                    <textarea class="form-control" id="message-text"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Send message</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-right: -2.75rem;margin-left: -2.75rem;">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <form method="post" id="myForm">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Assy Plan</h6>
                                        <div class="float-end" >
                                            <button type="submit" class="btn btn-success" style="border:none;" onclick="submitForm()">
                                                <i class="fa-regular fa-floppy-disk"></i>
                                                &nbsp;
                                                save
                                                <input type="text" name="save" style="display:none;" >
                                            </button>
                                        
                                            <button type="submit" class="btn btn-danger" style="border:none;">
                                                <i class="fa-solid fa-xmark"></i>
                                                &nbsp;
                                                cancel
                                                <input type="text" name="cancel" style="display:none; " >
                                            </button>
                                        </div>          
                                    </div>
                                        <!-- Card Body -->
                                    <div class="card-body" >
                                            <table id="myTable2" class="table table-bordered"  style="font-size:13px;" >
                                                <thead class="table-dark sticky-top">
                                                    <tr>
                                                        <th rowspan="2">#</th>
                                                        <th class="sticky-column" style="position:sticky;left: 0px;background: #257b42;z-index: 4;" rowspan="2">Series</th>
                                                        <th rowspan="2">Type</th>
                                                        <th rowspan="2">Part MO</th>
                                                        <th rowspan="2">Part JR/JM/JN</th>
                                                        <th rowspan="2">Date JR/JM/JN</th>
                                                        <th rowspan="2">Type Comp</th>
                                                        <th class="sticky-column" style="position:sticky;left: 72px;background: #257b42;z-index: 4;" rowspan="2">RDC</th> 
                                                        <th class="sticky-column" style="position:sticky;left:179px;background: #257b42;z-index: 4;" rowspan="2">SR</th>
                                                        <th rowspan="2">Serial No.</th>
                                                        <th rowspan="2">Lead Time</th>
                                                        <th rowspan="2">SR Requst by</th>
                                                        <th rowspan="2">MO</th>
                                                        <th rowspan="2">Customer</th>
                                                        <th rowspan="2">Model</th>
                                                        <th rowspan="2">Code</th>
                                                        <th rowspan="2">Lot size SR</th>
                                                        <th rowspan="2">RDC Close</th>
                                                        <th rowspan="2">Lot size RDC</th>
                                                        <th rowspan="2"><div style="width: 88px;">BOM Reference</div></th>
                                                        <th rowspan="2"><div style="width: 88px;">Design drawing (RD)</div></th>
                                                        <th rowspan="2"><div style="width: 88px;">Production line (RD)</div></th>
                                                        <th rowspan="2">Request by RDC</th>
                                                        <th rowspan="2">Responsible by NPR</th>
                                                        <th rowspan="2"><div style="width: 170px;">Request R&D to send to store within</div></th>
                                                        <th rowspan="2">MID Assy Plan</th>
                                                        <th rowspan="2">Assy Actual</th>
                                                        <th rowspan="2">View</th>
                                                        <th colspan="4">Calorie Test</th>
                                                        <th colspan="4">Noise Test</th>
                                                        <th colspan="4">Locked Rotor Test</th>
                                                        <th rowspan="2">
                                                            Send to Store 
                                                            <br>(Plan)
                                                        </th>
                                                        <th rowspan="2">
                                                            Send to Store 
                                                            <br>
                                                            (Actual)
                                                        </th>
                                                        
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Plan Test</th>
                                                        <th>M/C</th>
                                                        <th>Plan Finish</th>
                                                        <th>Actual Finish</th>

                                                        <th>Plan Test</th>
                                                        <th>M/C</th>
                                                        <th>Plan Finish</th>
                                                        <th>Actual Finish</th>

                                                        <th>Plan Test</th>
                                                        <th>M/C</th>
                                                        <th>Plan Finish</th>
                                                        <th>Actual Finish</th>
                                                    </tr>
                                                </thead>
                                                
                                            </table>
                                        
                                </form>  
                            </div>
                        </div>
                    </div>
                </div>

            </div>
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


    <!-- Page level custom scripts -->
    <script src="../js/dataTables/jquery.dataTables.min.js"></script>
    <script src="../assy_calendar/calendar.js"></script>
    <script src="../js/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
   
   
    <script>
        $(document).ready(function() {
            getData();
        });
        
        function getData(start, length, search) {
            dataTable = $('#myTable2').DataTable({
                "responsive": true,
                "scrollY": "550px",
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [{
                        targets: -1,
                        className: 'dt-body-center',
                        orderable: false
                    },
                    {
                        targets: 1,
                        className: 'sticky-column',
                    },
                    {
                        targets: 7,
                        className: 'sticky-column72',
                    },
                    {
                        targets: 8,
                        className: 'sticky-column179',
                    },
                    {
                        targets: 25,
                        className: 'input-container',
                    },
                    {
                        targets: 28,
                        className: 'input-container',
                    },
                    {
                        targets: 30,
                        className: 'input-container',
                    },
                    {
                        targets: 31,
                        className: 'input-container',
                    },
                    {
                        targets: 32,
                        className: 'input-container',
                    },
                    {
                        targets: 34,
                        className: 'input-container',
                    },
                    {
                        targets: 35,
                        className: 'input-container',
                    },
                    {
                        targets: 36,
                        className: 'input-container',
                    },
                    {
                        targets: 38,
                        className: 'input-container',
                    },
                    {
                        targets: 39,
                        className: 'input-container',
                    },
                    {
                        targets: 40,
                        className: 'input-container',
                    },
                ],
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "order": [],
                "retrieve": true,
                "ajax": {
                    url: "api_baseplan.php",
                    method: "POST",
                    data: {
                        start: start,
                        length: length,
                        search: search,
                    }
                },
                "drawCallback": function(settings) {
                    var page_info = dataTable.page.info();
                    $('#totalpages').text(page_info.pages);
                    var html = '';
                    var start = 0;
                    var length = page_info.length;

                    for (var count = 1; count <= page_info.pages; count++) {
                        var page_number = count - 1;
                        html += '<option value="' + page_number + '" data-start="' + start + '" data-length="' + length + '">' + count + '</option>';
                        start = start + page_info.length;
                    }

                    $('#pagelist').html(html);
                    $('#pagelist').val(page_info.page);
                    $('.inputAssy1').datepicker('remove'); 
                    $('.inputAssy1').datepicker({ 
                        format: 'yyyy-mm-dd',
                        startDate: '2022-02-01',
                        todayBtn: 'linked',
                        todayHighlight: true,
                        autoclose: true
                    });

                    // ตรวจสอบและเปลี่ยนสีพื้นหลังของคอลัมน์ที่มี class 'revive_date' เป็นสีแดง
                    $('#myTable2 .revive_date').closest('td').css('background-color', '#fdfdb8');
                    $('#myTable2 .mass-bg').closest('td').css('background-color', '#e7e7e7');
                },
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            });

        }


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

        function submitForm() {
            var inputElements = document.getElementsByClassName("inputAssy1");
            var inputElements2 = $(".inputAssy2");

            var values = [];
            var ids = [];
            var names = [];
            
            var id_sr_values = []; 
            var sr_no_values = []; 
            var rdc_values = []; 


            var values2 = [];
            var ids2 = [];
            var names2 = [];

            inputElements2.each(function() {
                var value = $(this).val().trim();
                if (value !== "") {
                    values.push(value);
                    ids.push(this.id);
                    names.push(this.name);
                    id_sr_values.push($(this).closest('tr').find('.id_sr_value').val()); 
                    sr_no_values.push($(this).closest('tr').find('.sr_no_value').val()); 
                    rdc_values.push($(this).closest('tr').find('.rdc_value').val()); 
                }
            });
            for (var i = 0; i < inputElements.length; i++) {
                if (inputElements[i].value.trim() !== "" && inputElements[i].value.trim() !== "- - - Select M/C - - -") {
                    values2.push(inputElements[i].value);
                    ids2.push(inputElements[i].id);
                    names2.push(inputElements[i].name);
                    id_sr_values.push($(inputElements[i]).closest('tr').find('.id_sr_value').val()); 
                    sr_no_values.push($(inputElements[i]).closest('tr').find('.sr_no_value').val()); 
                    rdc_values.push($(inputElements[i]).closest('tr').find('.rdc_value').val()); 
                }
            }
            let values3 = values.concat(values2);
            let ids3 = ids.concat(ids2);
            let names3 = names.concat(names2);
            
            var formData = new FormData();
            formData.append('values', JSON.stringify(values3));
            formData.append('ids', JSON.stringify(ids3));
            formData.append('names', JSON.stringify(names3));
            formData.append('id_sr', JSON.stringify(id_sr_values)); 
            formData.append('sr_no', JSON.stringify(sr_no_values)); 
            formData.append('rdc_no', JSON.stringify(rdc_values)); 

            $.ajax({
                url: 'update_plan_test.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response);
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });

            return false;
        }

    </script>
    <script>
        
        
        function fetchDocCode(id_sr, id_span) {
            $.ajax({
                url: 'fetch_doc_code.php',
                method: 'POST',
                data: { id_sr: id_sr },
                success: function(response) {
                    // เมื่อรับข้อมูลกลับมาสำเร็จ
                    var docCodes = JSON.parse(response);
                    var output = '';
                    // วนลูปผ่าน docCodes เพื่อสร้าง HTML สำหรับแสดงผล
                    for (var i = 1; i < docCodes.length; i++) {
                        output += '<div style="color:#5d5d5d;">' + docCodes[i] + '</div>'; // ปรับแต่งตามความต้องการ
                    }
                    // แสดงผลใน element ที่มี id เท่ากับ id_span
                    $('#' + id_span).show();
                    $('#' + id_span).html(output);
                    $('#chevron-up'+id_span).hide();
                    $('#chevron-down'+id_span).show();
                },
                error: function(xhr, status, error) {
                    // เมื่อเกิดข้อผิดพลาดในการรับข้อมูล
                    console.error(xhr.responseText);
                }
            });
        }

        function hideDocCode(id_span){
            $('#' + id_span).hide();
            $('#chevron-down'+id_span).hide();
            $('#chevron-up'+id_span).show();
        }
    </script>
    <script>
        $(document).ready(function(){
            $('.assy_plan').change(function() {
                var id_rr = $(this).attr('id');
                var planValue = $(this).val();
                
                
                $.ajax({
                    url: 'check_date_over.php', 
                    method: 'POST', 
                    data: {id_rr: id_rr,
                            planValue: planValue,}, 
                    success: function(response){
                        alert(response); 
                    },
                    error: function(xhr, status, error){
                        // การจัดการเมื่อเกิดข้อผิดพลาด
                        console.error(error); // แสดงข้อความข้อผิดพลาดใน console
                    }
                });
            });
        });
        $(document).ready(function() {
            // เมื่อ DataTables ถูกวาด (draw) ใหม่
            $('#myTable2').on('draw.dt', function() {
                $('.assy_plan').off('change').on('change', function() {
                    var id_rr = $(this).attr('id');
                    var planValue = $(this).val();
                    
                    $.ajax({
                        url: 'check_date_over.php', 
                        method: 'POST', 
                        data: {
                            id_rr: id_rr,
                            planValue: planValue,
                        }, 
                        success: function(response) {
                            alert(response); 
                        },
                        error: function(xhr, status, error) {
                            // การจัดการเมื่อเกิดข้อผิดพลาด
                            console.error(error); // แสดงข้อความข้อผิดพลาดใน console
                        }
                    });
                });
            });
        });

    </script>
</body>

</html>
