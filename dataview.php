<?php 
// Include pagination library file 
include_once 'Pagination.php'; 
 
// Include database configuration file 
require_once 'config.php'; 
 
// Set some useful configuration 
$baseURL = 'getData.php'; 
$limit = 5; 
 
// Count of all records 
$query   = $conn->query("SELECT COUNT(*) as rowNum FROM oanda_db"); 
$result  = $query->fetch_assoc(); 
$rowCount= $result['rowNum']; 
 
// Initialize pagination class 
$pagConfig = array( 
    'baseURL' => $baseURL, 
    'totalRows' => $rowCount, 
    'perPage' => $limit, 
    'contentDiv' => 'dataContainer' 
); 
$pagination =  new Pagination($pagConfig); 
 
// Fetch records based on the limit 
$query = $conn->query("SELECT * FROM oanda_db ORDER BY id ASC LIMIT $limit"); 
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Skydash Admin</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="vendors/feather/feather.css">
        <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
        <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
        <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <link rel="stylesheet" href="css/vertical-layout-light/style.css">
        <!-- endinject -->
        <link rel="shortcut icon" href="images/favicon.png" />
    </head>
    <body>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="images/logo.svg" class="mr-2" alt="logo"/></a>
                    <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/logo-mini.svg" alt="logo"/></a>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                    </button>
                    <ul class="navbar-nav mr-lg-2">
                        <li class="nav-item nav-search d-none d-lg-block">
                            <div class="input-group">
                                <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                    <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="icon-bell mx-0"></i>
                            <span class="count"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                                <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-success">
                                            <i class="ti-info-alt mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            Just now
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-warning">
                                            <i class="ti-settings mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal">Settings</h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            Private message
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-info">
                                            <i class="ti-user mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal">New user registration</h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            2 days ago
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img src="images/faces/face28.jpg" alt="profile"/>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item">
                                <i class="ti-settings text-primary"></i>
                                Settings
                                </a>
                                <a class="dropdown-item">
                                <i class="ti-power-off text-primary"></i>
                                Logout
                                </a>
                            </div>
                        </li>
                        <li class="nav-item nav-settings d-none d-lg-flex">
                            <a class="nav-link" href="#">
                            <i class="icon-ellipsis"></i>
                            </a>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                    </button>
                </div>
            </nav>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial:partials/_settings-panel.html -->
                <div class="theme-setting-wrapper">
                    <div id="settings-trigger"><i class="ti-settings"></i></div>
                    <div id="theme-settings" class="settings-panel">
                        <i class="settings-close ti-close"></i>
                        <p class="settings-heading">SIDEBAR SKINS</p>
                        <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                            <div class="img-ss rounded-circle bg-light border mr-3"></div>
                            Light
                        </div>
                        <div class="sidebar-bg-options" id="sidebar-dark-theme">
                            <div class="img-ss rounded-circle bg-dark border mr-3"></div>
                            Dark
                        </div>
                        <p class="settings-heading mt-2">HEADER SKINS</p>
                        <div class="color-tiles mx-0 px-4">
                            <div class="tiles success"></div>
                            <div class="tiles warning"></div>
                            <div class="tiles danger"></div>
                            <div class="tiles info"></div>
                            <div class="tiles dark"></div>
                            <div class="tiles default"></div>
                        </div>
                    </div>
                </div>
                
                <!-- partial -->
                <!-- partial:partials/_sidebar.html -->
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-layout menu-icon"></i>
                            <span class="menu-title">UI Elements</span>
                            <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-basic">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Dropdowns</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                            <i class="icon-columns menu-icon"></i>
                            <span class="menu-title">Form elements</span>
                            <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="form-elements">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                            <i class="icon-bar-graph menu-icon"></i>
                            <span class="menu-title">Charts</span>
                            <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="charts">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                            <i class="icon-grid-2 menu-icon"></i>
                            <span class="menu-title">Tables</span>
                            <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="tables">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                            <i class="icon-contract menu-icon"></i>
                            <span class="menu-title">Icons</span>
                            <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="icons">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                            <i class="icon-head menu-icon"></i>
                            <span class="menu-title">User Pages</span>
                            <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="auth">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
                                    <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
                            <i class="icon-ban menu-icon"></i>
                            <span class="menu-title">Error pages</span>
                            <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="error">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
                                    <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/documentation/documentation.html">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Documentation</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper">
                        <!-- <div class="row">
                            <div class="col-md-6 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="card-title">Sales Report</p>
                                            <a href="#" class="text-info">View all</a>
                                        </div>
                                        <p class="font-weight-500">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
                                        <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
                                        <canvas id="sales-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-lg-12 stretch-card grid-margin">
                              <div class="card">
                                <div class="card-body">
                                  <h4 class="card-title">Data List</h4>
                                  <div class="row">
                                      <div class="col-sm-3">
                                        <input type="date" class="form-control mb-2 mr-sm-2" id="fromdate" placeholder="Fromdate">
                                      </div>
                                      <div class="col-sm-3">
                                        <input type="date" class="form-control mb-2 mr-sm-2" id="todate" placeholder="Todate">
                                      </div>
                                      <div class="col-sm-2">
                                        <input type="text" class="form-control mb-2 mr-sm-2" id="accountname" placeholder="Account">
                                      </div>
                                      <div class="col-sm-2">
                                        <input type="text" class="form-control mb-2 mr-sm-2" id="botname" placeholder="Botname">
                                      </div>
                                      <div class="col-sm-2">
                                        <input type="text" class="form-control mb-2 mr-sm-2" id="profitloss" placeholder="Profitloss">
                                      </div>
                                      <div class="col-sm-2">
                                        <button type="submit" class="btn btn-sm btn-dark btn-block mb-2" onclick="searchFilter(0)">Submit</button>
                                      </div>
                                  </div>
                                  <div class="table-responsive pt-3 " id="dataContainer">
                                    <table class="table table-bordered mb-2" >
                                      <thead>
                                        <tr class="customize-header">
                                            <th>SL</th>
                                            <th>Date</th>
                                            <th>Account</th>
                                            <th>Botname</th>
                                            <th>ProfitLoss</th>
                                        </tr>
                                      </thead>
                                      <tbody id="dataContainer">
                                        <?php 
                                        if($query->num_rows > 0){ $i=0; 
                                            while($row = $query->fetch_assoc()){ $i++; 
                                        ?>
                                        <tr class="table-<?php echo ($row["profit_loss"] > 0 ? 'success' : 'danger'); ?>">
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo date("Y-m-d", strtotime($row["datetime"])); ?></td>
                                          <td><?php echo $row["account"]; ?></td>
                                          <td><?php echo $row["botname"]; ?></td>
                                          <td><?php echo $row["profit_loss"]; ?></td>
                                          
                                        </tr>
                                            
                                        <?php 
                                            } 
                                        }else{ 
                                            echo '<tr><td colspan="5">No records found...</td></tr>'; 
                                        } 
                                        ?>


                                      </tbody>
                                    </table>
                                    <?php echo $pagination->createLinks(); ?>
                                  </div>
                                </div>
                              </div>
                            </div>
                            


                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="card-title">Data Table</p>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                	


                                                    <table id="example" class="display expandable-table" style="width:100%">
                                                        <thead>
                                                             
                                                            <!-- <tr>
                                                                <td colspan="4">
                                                                    <form class="form-inline">
                                                                        <input type="text" class="form-control mb-2 mr-sm-2" id="fromdate" placeholder="">
                                                                        <input type="text" class="form-control mb-2 mr-sm-2" id="todate" placeholder="">
                                                                        <input type="text" class="form-control mb-2 mr-sm-2" id="accountname" placeholder="">
                                                                        <input type="text" class="form-control mb-2 mr-sm-2" id="botname" placeholder="">
                                                                        <input type="text" class="form-control mb-2 mr-sm-2" id="profitloss" placeholder="">
                                                                        <button type="submit" class="btn btn-primary mb-2">Submit</button>
                                                                    </form>
                                                                </td>
                                                            </tr> -->
                                                        
                                                        	<tr>
                                                        		<th>DateTime</th>
                                                        		<th>Account</th>
                                                        		<th>Botname</th>
                                                        		<th>ProfitLoss</th>
                                                        	</tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="vendors/chart.js/Chart.min.js"></script>
        <script src="vendors/datatables.net/jquery.dataTables.js"></script>
        <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
        <script src="js/dataTables.select.min.js"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/hoverable-collapse.js"></script>
        <script src="js/template.js"></script>
        <script src="js/settings.js"></script>
        <script src="js/todolist.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page-->
        <script src="js/dashboard.js"></script>
        <script src="js/Chart.roundedBarCharts.js"></script>

        
        <!-- End custom js for this page-->

        <script>
            function searchFilter(page) {
                var limit =5;
                
                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();
                var account = $('#accountname').val();
                var botname = $('#botname').val();
                var profitloss = $('#profitloss').val();

                page_num = page?page:0;
                limit = limit?limit:5;


                $.ajax({
                    type: 'POST',
                    url: 'getData.php',
                    data:'fromdate='+fromdate+'&todate='+todate+'&account='+account+'&botname='+botname+'&profitloss='+profitloss+'&page='+page_num+'&limit='+limit,
                    beforeSend: function () {
                        $('.loading-overlay').show();
                    },
                    success: function (html) {
                        $('#dataContainer').html(html);
                        $('.loading-overlay').fadeOut("slow");
                    }
                });
            }
        </script>


        <script>
        // Show loading overlay when ajax request starts
        $( document ).ajaxStart(function() {
            $('.loading-overlay').show();
        });

        // Hide loading overlay when ajax request completes
        $( document ).ajaxStop(function() {
            $('.loading-overlay').hide();
        });
        </script>


    </body>
</html>