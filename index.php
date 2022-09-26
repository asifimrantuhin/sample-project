<?php 
include "inc/header.php";
include("config.php");

$sql = "SELECT 
 SUM(IF(pl> 0,pl,0)) AS profit,
 SUM(IF(pl< 0,pl,0)) AS loss,
 DATE_ADD( DATE(DATETIME), INTERVAL (1 - DAYOFWEEK(DATETIME )) DAY) week_start,
 DATE_ADD( DATE(DATETIME), INTERVAL (7 - DAYOFWEEK(DATETIME )) DAY) week_ending
FROM order_history
GROUP BY week_ending";
$query 	= $conn->query($sql);
$query1 	= $conn->query($sql);

$query2   = $conn->query("SELECT  SUM(IF(pl> 0,pl,0)) AS profit,  SUM(IF(pl< 0,pl,0)) AS loss FROM order_history"); 
$lifetimeSummery  = $query2->fetch_assoc(); 

// Count of all currency records 
$query3   = $conn->query("SELECT COUNT(*) as rowNum FROM currency"); 
$result3  = $query3->fetch_assoc(); 
$TotalCurCount= $result3['rowNum']; 

// Count of all ordertype records 
$query4   = $conn->query("SELECT COUNT(*) as rowNum FROM order_type"); 
$result4  = $query4->fetch_assoc(); 
$TotalOrderTypeCount= $result4['rowNum']; 



$labelStr = "";
$profitStr = "";
$lossStr = "";
while($row = $query1->fetch_assoc()){
    $labelStr .= "'".date('d/m/Y', strtotime($row['week_start'])).'-'.date('d/m/Y', strtotime($row['week_ending']))."', ";
    $profitStr .= $row['profit'].",";
	$lossStr .= $row['loss'].",";
}
$labelStr = rtrim($labelStr, ",");
$profitStr = rtrim($profitStr, ",");
$lossStr = rtrim($lossStr, ",");







$curr_sql = "SELECT c.currency_name, 
 SUM(IF(oh.pl> 0,oh.pl,0)) AS profit,
 SUM(IF(oh.pl< 0,oh.pl,0)) AS loss,
 SUM(IF(oh.pl=0,oh.pl,0)) AS level_zero
FROM order_history oh
JOIN `currency` c ON c.id = oh.currency
GROUP BY oh.currency";

$curr_query 	= $conn->query($curr_sql);
$currencyStr = "";
$curprofitStr = "";
$curlossStr = "";
$curzeroStr = "";
while($row = $curr_query->fetch_assoc()){
    $currencyStr .= "'".$row['currency_name']."', ";
    $curprofitStr .= $row['profit'].",";
	$curlossStr .= $row['loss'].",";
	$curzeroStr .= $row['level_zero'].",";
}
$currencyStr = rtrim($currencyStr, ",");
$curprofitStr = rtrim($curprofitStr, ",");
$curlossStr = rtrim($curlossStr, ",");
$curzeroStr = rtrim($curzeroStr, ",");


$curr_sql2 = "SELECT c.currency_name, 
 SUM(oh.pl) AS profit
FROM order_history oh
JOIN `currency` c ON c.id = oh.currency
GROUP BY oh.currency";
$curr_query2 	= $conn->query($curr_sql2);

$currencyStr2 = "";
$curprofitStr2 = "";

while($row = $curr_query2->fetch_assoc()){
    $currencyStr2 .= "'".$row['currency_name']."', ";
    $curprofitStr2 .= $row['profit'].",";
	
}
$currencyStr2 = rtrim($currencyStr2, ",");
$curprofitStr2 = rtrim($curprofitStr2, ",");





?>


<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
  google.charts.load('current', {packages: ['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Status', 'Amount'],
          ['Profit',   <?php echo $lifetimeSummery['profit'];?>],
          ['Loss',     <?php echo abs($lifetimeSummery['loss']);?>]
        ]);

        var options = {
          title: 'Profit Loss Status'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }

</script>
 <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Welcome to Dashboard</h3>
                  
                </div>
                <div class="col-12 col-xl-4">
                 <div class="justify-content-end d-flex">
                  <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                    <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                     <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                      <a class="dropdown-item" href="#">January - March</a>
                      <a class="dropdown-item" href="#">March - June</a>
                      <a class="dropdown-item" href="#">June - August</a>
                      <a class="dropdown-item" href="#">August - November</a>
                    </div>
                  </div>
                 </div>
                </div>
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card tale-bg">
                <div class="card-people mt-auto">
                  <img src="images/dashboard/people.svg" alt="people">
                  <div class="weather-info">
                    <div class="d-flex">
                      <div>
                        <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>31<sup>C</sup></h2>
                      </div>
                      <div class="ml-2">
                        <h4 class="location font-weight-normal">Bangalore</h4>
                        <h6 class="font-weight-normal">India</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="mb-4">Profit</p>
                      <p class="fs-30 mb-2"><?php echo $lifetimeSummery['profit'];?></p>
                      <p>Lifetime</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Loss</p>
                      <p class="fs-30 mb-2"><?php echo abs($lifetimeSummery['loss']);?></p>
                      <p>Lifetime</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Order Type</p>
                      <p class="fs-30 mb-2"><?php echo $TotalOrderTypeCount; ?></p>
                      <p>Total</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Currency</p>
                      <p class="fs-30 mb-2"><?php echo $TotalCurCount; ?></p>
                      <p>Total</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card grid-margin stretch-card">
                <div class="card-body">
                   <div class="row">
	                <div class="col-12 col-xl-6 mb-4 mb-xl-0">
	                  <h4 class="font-weight-bold">Total Revenue Status</h4>
	                  
	                </div>
	                
	              </div>
                  
                  <div class="row mb-5">
                    <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                      <p class="text-muted">Profit</p>
                      <h3 class="text-primary fs-30 font-weight-medium"><?php echo $lifetimeSummery['profit'];?></h3>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                      <p class="text-muted">Loss</p>
                      <h3 class="text-primary fs-30 font-weight-medium"><?php echo $lifetimeSummery['loss'];?></h3>
                    </div>
                     
                  </div>
                  <div id="piechart" style="width: 100%x; height: 400px;"></div>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <div class="row">
	                <div class="col-12 col-xl-6 mb-4 mb-xl-0">
	                  <h4 class="font-weight-bold">Revenue by Weekly</h4>
	                  
	                </div>
	                <div class="col-12 col-xl-6">
	                 
	                  <!-- <div class="form-group" style="width:100%"> -->
	                    <select class="js-example-basic-single" onchange="weeklyPieChartChange(this.value)" placeholder="Select Week">
	                    	<option disabled selected>Select by week</option>
	                      <?php
                            while($row = $query->fetch_assoc()){
                            
                                echo '<option value="'.date('d/m/Y', strtotime($row['week_start'])).'-'.date('d/m/Y', strtotime($row['week_ending'])).'">'.date('d/m/Y', strtotime($row['week_start'])).' to '.date('d/m/Y', strtotime($row['week_ending'])).'</option>';
                            }
                            ?>
	                    </select>
	                  <!-- </div> -->
	                 
	                </div>
	              </div>
                  
                  <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
                  <canvas id="sales-chart"></canvas>
                </div>
              </div>
            </div>
          </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card position-relative">
                	<div class="card-header"><h3>Revenue By Currency</h3></div>
	                <div class="card-body">
	          			<canvas id="barChartExample"></canvas>
	          		</div>
          		</div>
            </div>
        </div>


          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card position-relative">
                <div class="card-body">
                  <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2" data-ride="carousel">
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <div class="row">
                          <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                            <div class="ml-xl-4 mt-3">
                            <p class="card-title">Detailed Reports</p>
                              <h1 class="text-primary">$34040</h1>
                              <h3 class="font-weight-500 mb-xl-4 text-primary">North America</h3>
                              <p class="mb-2 mb-xl-0">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
                            </div>  
                            </div>
                          <div class="col-md-12 col-xl-9">
                            <div class="row">
                              <div class="col-md-6 border-right">
                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                  <table class="table table-borderless report-table">
                                    <tr>
                                      <td class="text-muted">Illinois</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">713</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">Washington</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">583</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">Mississippi</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">924</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">California</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">664</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">Maryland</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">560</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">Alaska</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">793</h5></td>
                                    </tr>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6 mt-3">
                                <canvas id="north-america-chart"></canvas>
                                <div id="north-america-legend"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="carousel-item">
                        <div class="row">
                          <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                            <div class="ml-xl-4 mt-3">
                            <p class="card-title">Detailed Reports</p>
                              <h1 class="text-primary">$34040</h1>
                              <h3 class="font-weight-500 mb-xl-4 text-primary">North America</h3>
                              <p class="mb-2 mb-xl-0">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
                            </div>  
                            </div>
                          <div class="col-md-12 col-xl-9">
                            <div class="row">
                              <div class="col-md-6 border-right">
                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                  <table class="table table-borderless report-table">
                                    <tr>
                                      <td class="text-muted">Illinois</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">713</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">Washington</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">583</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">Mississippi</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">924</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">California</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">664</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">Maryland</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">560</h5></td>
                                    </tr>
                                    <tr>
                                      <td class="text-muted">Alaska</td>
                                      <td class="w-100 px-0">
                                        <div class="progress progress-md mx-4">
                                          <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                      </td>
                                      <td><h5 class="font-weight-bold mb-0">793</h5></td>
                                    </tr>
                                  </table>
                                </div>
                              </div>
                              <div class="col-md-6 mt-3">
                                <canvas id="south-america-chart"></canvas>
                                <div id="south-america-legend"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <a class="carousel-control-prev" href="#detailedReports" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#detailedReports" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          
          
        </div>
        
      </div>

<?php 
include "inc/footer.php";
?>

<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback();


function weeklyPieChartChange(daterange)
{
	var temp_title = "Amount";

    $.ajax({
        url:"datafetch.php",
        method:"POST",
        data:{daterange:daterange},
        dataType:"JSON",
        success:function(data)
        {
            drawMonthwiseChart(data, temp_title);
        }
    });

}




function drawMonthwiseChart(chart_data, chart_main_title)
{
    var jsonData = chart_data;
    var SalesChartCanvas = $("#sales-chart").get(0).getContext("2d");
      var SalesChart = new Chart(SalesChartCanvas, {
        type: 'bar',
        data: {
          labels: jsonData.ccurrency_name,
          datasets: [{
              label: 'Profit',
              data: [jsonData.profit],
              backgroundColor: '#98BDFF'
            },
            {
              label: 'Loss',
              data: [jsonData.loss],
              backgroundColor: '#4B49AC'
            }
          ]
        },
        options: {
          cornerRadius: 5,
          responsive: true,
          maintainAspectRatio: true,
          layout: {
            padding: {
              left: 0,
              right: 0,
              top: 20,
              bottom: 0
            }
          },
          scales: {
            yAxes: [{
              display: true,
              gridLines: {
                display: true,
                drawBorder: false,
                color: "#F2F2F2"
              },
              ticks: {
                display: true,
                min: 0,
                max: 560,
                callback: function(value, index, values) {
                  return  value + '$' ;
                },
                autoSkip: true,
                maxTicksLimit: 10,
                fontColor:"#6C7383"
              }
            }],
            xAxes: [{
              stacked: false,
              ticks: {
                beginAtZero: true,
                fontColor: "#6C7383"
              },
              gridLines: {
                color: "rgba(0, 0, 0, 0)",
                display: false
              },
              barPercentage: 1
            }]
          },
          legend: {
            display: false
          },
          elements: {
            point: {
              radius: 0
            }
          }
        },
      });
      document.getElementById('sales-legend').innerHTML = SalesChart.generateLegend();
}

</script>




<script>
    
$(document).ready(function(){

    
    if ($("#sales-chart").length) {
      var SalesChartCanvas = $("#sales-chart").get(0).getContext("2d");
      var SalesChart = new Chart(SalesChartCanvas, {
        type: 'bar',
        data: {
          labels: [<?php echo $labelStr;?>],
          datasets: [{
              label: 'Profit',
              data: [<?php echo $profitStr;?>],
              backgroundColor: '#98BDFF'
            },
            {
              label: 'Loss',
              data: [<?php echo $lossStr;?>],
              backgroundColor: '#4B49AC'
            }
          ]
        },
        options: {
          cornerRadius: 5,
          responsive: true,
          maintainAspectRatio: true,
          layout: {
            padding: {
              left: 0,
              right: 0,
              top: 20,
              bottom: 0
            }
          },
          scales: {
            yAxes: [{
              display: true,
              gridLines: {
                display: true,
                drawBorder: false,
                color: "#F2F2F2"
              },
              ticks: {
                display: true,
                min: 0,
                max: 560,
                callback: function(value, index, values) {
                  return  value + '$' ;
                },
                autoSkip: true,
                maxTicksLimit: 10,
                fontColor:"#6C7383"
              }
            }],
            xAxes: [{
              stacked: false,
              ticks: {
                beginAtZero: true,
                fontColor: "#6C7383"
              },
              gridLines: {
                color: "rgba(0, 0, 0, 0)",
                display: false
              },
              barPercentage: 1
            }]
          },
          legend: {
            display: false
          },
          elements: {
            point: {
              radius: 0
            }
          }
        },
      });
      document.getElementById('sales-legend').innerHTML = SalesChart.generateLegend();
    }




    if ($("#barChartExample").length) {
    	var data = {
	    labels: [<?php echo $currencyStr2;  ?>],
	    datasets: [{
	      label: 'Amount',
	      data: [<?php echo $curprofitStr2;  ?>],
	      backgroundColor: [
	        'rgba(255, 99, 132, 0.2)',
	        'rgba(54, 162, 235, 0.2)',
	        'rgba(255, 206, 86, 0.2)',
	        'rgba(75, 192, 192, 0.2)',
	        'rgba(153, 102, 255, 0.2)',
	        'rgba(255, 159, 64, 0.2)',
	        'rgba(255, 99, 132, 0.2)',
	        'rgba(54, 162, 235, 0.2)',
	        'rgba(255, 206, 86, 0.2)',
	        'rgba(75, 192, 192, 0.2)',
	        'rgba(153, 102, 255, 0.2)',
	        'rgba(255, 159, 64, 0.2)',
	        'rgba(255, 99, 132, 0.2)',
	        'rgba(54, 162, 235, 0.2)',
	        'rgba(255, 206, 86, 0.2)',
	      ],
	      borderColor: [
	        'rgba(255,99,132,1)',
	        'rgba(54, 162, 235, 1)',
	        'rgba(255, 206, 86, 1)',
	        'rgba(75, 192, 192, 1)',
	        'rgba(153, 102, 255, 1)',
	        'rgba(255, 159, 64, 1)',
	        'rgba(255,99,132,1)',
	        'rgba(54, 162, 235, 1)',
	        'rgba(255, 206, 86, 1)',
	        'rgba(75, 192, 192, 1)',
	        'rgba(153, 102, 255, 1)',
	        'rgba(255, 159, 64, 1)','rgba(54, 162, 235, 1)',
	        'rgba(255, 206, 86, 1)',
	        'rgba(75, 192, 192, 1)',
	      ],
	      borderWidth: 1,
	      fill: false
	    }]
	  };

    	var options = {
		    scales: {
		      yAxes: [{
		        ticks: {
		          beginAtZero: true
		        }
		      }]
		    },
		    legend: {
		      display: false
		    },
		    elements: {
		      point: {
		        radius: 0
		      }
		    }

		  };

	    var barChartCanvas = $("#barChartExample").get(0).getContext("2d");
	    // This will get the first returned node in the jQuery collection.
	    var barChart = new Chart(barChartCanvas, {
	      type: 'bar',
	      data: data,
	      options: options
	    });
	  }






});



</script>

