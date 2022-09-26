<?php 
// Include pagination library file 
include_once 'Pagination.php'; 
 
// Include database configuration file 
require_once 'config.php'; 
 
// Set some useful configuration 
$baseURL = 'get_order_history.php'; 
$limit = 5; 
 
// Count of all records 
$query   = $conn->query("SELECT COUNT(*) as rowNum FROM order_history"); 
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
$query = $conn->query("SELECT * FROM order_history ORDER BY id ASC LIMIT $limit");
include "inc/header.php"; 
?>


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
                    url: 'get_order_history.php',
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