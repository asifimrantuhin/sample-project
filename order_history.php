<?php 
// Include pagination library file 
include_once 'Pagination.php'; 
 
// Include database configuration file 
require_once 'config.php'; 
 
// Set some useful configuration 
$baseURL = 'get_order_history.php'; 
$baseURLOne = 'get_order_history_one.php';
$limit = 5;
 
// Count of all records 
$query   = $conn->query("SELECT COUNT(*) as rowNum FROM order_history oh
 LEFT JOIN order_type ot ON ot.id = oh.type 
 LEFT JOIN currency cu ON cu.id = oh.currency
 ");
$result  = $query->fetch_assoc(); 
$rowCount= $result['rowNum']; 
 
// Initialize pagination class 
$pagConfig = array( 
    'baseURL' => $baseURL, 
    'totalRows' => $rowCount, 
    'perPage' => $limit, 
    'contentDiv' => 'dataContainer',
    'filterFunction'    => 'searchFilterOrderHistory'
);

$pagConfig1 = array(
    'baseURL' => $baseURLOne,
    'totalRows' => $rowCount,
    'perPage' => $limit,
    'contentDiv' => 'dataContainerOne',
    'filterFunction'    => 'searchFilterOrderHistoryOne'
);
$pagination =  new Pagination($pagConfig); 
$pagination1 =  new Pagination($pagConfig1);

// Fetch records based on the limit
$sql_query = "SELECT oh.*, ot.order_type as type_name, cu.currency_name FROM order_history oh
 LEFT JOIN order_type ot ON ot.id = oh.type 
 LEFT JOIN currency cu ON cu.id = oh.currency
 ORDER BY id ASC LIMIT $limit";
$query = $conn->query($sql_query);
$query1 = $conn->query($sql_query);

/** Order Type Data */
$sql_type = "SELECT * FROM order_type ORDER BY order_type ASC";
$type_query = $conn->query($sql_type);

/** currency Data */
$sql_currency = "SELECT * FROM currency ORDER BY currency_name ASC";
$currency_query = $conn->query($sql_currency);


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
                                  <h4 class="card-title">Order History List</h4>
                                  <div class="row">
                                      <div class="col-sm-3">
                                        <input type="date" class="form-control mb-2 mr-sm-2" id="fromdate" placeholder="Fromdate">
                                      </div>
                                      <div class="col-sm-3">
                                        <input type="date" class="form-control mb-2 mr-sm-2" id="todate" placeholder="Todate">
                                      </div>
                                      <div class="col-sm-2">
                                          <input type="text" class="form-control mb-2 mr-sm-2" id="profitloss" placeholder="Profitloss">
                                      </div>
                                      <div class="col-sm-2">
<!--                                        <select class="js-example-basic-single w-100" data-placeholder="Order Type">-->
                                        <select class="js-example-basic-single w-100" id="type_id">
                                            <option value="">Order Type</option>
                                            <?php
                                                if($type_query->num_rows > 0) {
                                                    while ($row = $type_query->fetch_assoc()) {
                                                        echo '<option value="'.$row['id'].'">'.$row['order_type'].'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                      </div>
                                      <div class="col-sm-2">
                                          <select class="js-example-basic-single w-100" id="currency_id">
                                              <option value="">Currency</option>
                                              <?php
                                              if($currency_query->num_rows > 0) {
                                                  while ($row = $currency_query->fetch_assoc()) {
                                                      echo '<option value="'.$row['id'].'">'.$row['currency_name'].'</option>';
                                                  }
                                              }
                                              ?>
                                          </select>
                                      </div>
                                      <div class="col-sm-2">
                                        <button type="submit" class="btn btn-sm btn-dark btn-block mb-2" onclick="searchFilterOrderHistory(0)">Submit</button>
                                      </div>
                                  </div>
                                  <div class="table-responsive pt-3 " id="dataContainer">
                                    <table class="table table-bordered mb-2" >
                                      <thead>
                                        <tr class="customize-header">
                                            <th>SL</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Currency</th>
                                            <th>Units</th>
                                            <th>Price</th>
                                            <th>ProfitLoss</th>
                                            <th>H.S Cost</th>
                                            <th>Bid</th>
                                            <th>Ask</th>
                                            <th>Comment</th>
                                        </tr>
                                      </thead>
                                      <tbody id="dataContainer">
                                        <?php 
                                        if($query->num_rows > 0){ $i=0; 
                                            while($row = $query->fetch_assoc()){ $i++; 
                                        ?>
                                        <tr class="table-<?php echo ($row["pl"] > 0 ? 'success' : 'danger'); ?>">
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo date("Y-m-d", strtotime($row["datetime"])); ?></td>
                                          <td><?php echo $row["type_name"]; ?></td>
                                          <td><?php echo $row["currency_name"]; ?></td>
                                          <td><?php echo $row["units"]; ?></td>
                                          <td><?php echo $row["price"]; ?></td>
                                          <td><?php echo $row["pl"]; ?></td>
                                          <td><?php echo $row["halfspreadcost"]; ?></td>
                                          <td><?php echo $row["bid"]; ?></td>
                                          <td><?php echo $row["ask"]; ?></td>
                                          <td><?php echo $row["comment"]; ?></td>

                                        </tr>
                                            
                                        <?php 
                                            } 
                                        }else{ 
                                            echo '<tr><td colspan="11">No records found...</td></tr>';
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
                                        <p class="card-title">Order History List</p>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                	


                                                    <table id="orderHistory" class="display expandable-table" style="width:100%">
                                                        <thead>
                                                             
                                                            
                                                        
                                                        	<tr>
                                                                <th>Date</th>
                                                                <th>Type</th>
                                                                <th>Currency</th>
                                                                <th>Units</th>
                                                                <th>Price</th>
                                                                <th>ProfitLoss</th>
                                                                <th>H.S Cost</th>
                                                                <th>Bid</th>
                                                                <th>Ask</th>
                                                                <th>Comment</th>
                                                        	</tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Order History List Hover</h4>
                                        <div class="table-responsive" id="dataContainerOne">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Currency</th>
                                                    <th>Units</th>
                                                    <th>Price</th>
                                                    <th>ProfitLoss</th>
                                                    <th>H.S Cost</th>
                                                    <th>Bid</th>
                                                    <th>Ask</th>
                                                    <th>Comment</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody id="dataContainerOne">
                                                <?php
                                                    if($query1->num_rows > 0){ $i=0;
                                                        while($row = $query1->fetch_assoc()){ $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo date("Y-m-d", strtotime($row["datetime"])); ?></td>
                                                                <td><?php echo $row["type_name"]; ?></td>
                                                                <td><?php echo $row["currency_name"]; ?></td>
                                                                <td><?php echo $row["units"]; ?></td>
                                                                <td><?php echo $row["price"]; ?></td>
                                                                <td><?php echo $row["pl"]; ?></td>
                                                                <td><?php echo $row["halfspreadcost"]; ?></td>
                                                                <td><?php echo $row["bid"]; ?></td>
                                                                <td><?php echo $row["ask"]; ?></td>
                                                                <td><?php echo $row["comment"]; ?></td>
                                                                <td>
                                                                    <?php
                                                                        if($row['pl'] > 0 ) {
                                                                            echo '<label class="badge badge-success">Profit</label>';
                                                                        }elseif($row['pl'] < 0 ) {
                                                                            echo '<label class="badge badge-danger">Loss</label>';
                                                                        }else {
                                                                            echo '<label class="badge badge-warning">B/E</label>';
                                                                        }
                                                                    ?>
                                                                </td>

                                                            </tr>

                                                            <?php
                                                        }
                                                    }else{
                                                        echo '<tr><td colspan="12">No records found...</td></tr>';
                                                    }
                                                ?>

<!--                                                <tr>-->
<!--                                                    <td>Jacob</td>-->
<!--                                                    <td>Photoshop</td>-->
<!--                                                    <td class="text-danger"> 28.76% <i class="ti-arrow-down"></i></td>-->
<!--                                                    <td><label class="badge badge-danger">Pending</label></td>-->
<!--                                                </tr>-->
<!--                                                <tr>-->
<!--                                                    <td>Messsy</td>-->
<!--                                                    <td>Flash</td>-->
<!--                                                    <td class="text-danger"> 21.06% <i class="ti-arrow-down"></i></td>-->
<!--                                                    <td><label class="badge badge-warning">In progress</label></td>-->
<!--                                                </tr>-->
<!--                                                <tr>-->
<!--                                                    <td>John</td>-->
<!--                                                    <td>Premier</td>-->
<!--                                                    <td class="text-danger"> 35.00% <i class="ti-arrow-down"></i></td>-->
<!--                                                    <td><label class="badge badge-info">Fixed</label></td>-->
<!--                                                </tr>-->
<!--                                                <tr>-->
<!--                                                    <td>Peter</td>-->
<!--                                                    <td>After effects</td>-->
<!--                                                    <td class="text-success"> 82.00% <i class="ti-arrow-up"></i></td>-->
<!--                                                    <td><label class="badge badge-success">Completed</label></td>-->
<!--                                                </tr>-->
<!--                                                <tr>-->
<!--                                                    <td>Dave</td>-->
<!--                                                    <td>53275535</td>-->
<!--                                                    <td class="text-success"> 98.05% <i class="ti-arrow-up"></i></td>-->
<!--                                                    <td><label class="badge badge-warning">In progress</label></td>-->
<!--                                                </tr>-->
                                                </tbody>
                                            </table>
                                            <hr>
                                            <?php echo $pagination1->createLinks(); ?>
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
<?php 
include "inc/footer.php";
?>