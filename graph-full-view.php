<?php 
    require_once 'config.php'; 
    include "inc/header.php";    
?>
<script src="js/loader.js"></script>

<script>
    google.charts.load('current', {'packages':['bar']});

    function drawVisualization(object) {
    	//var object = obj;
    	
        var data = google.visualization.arrayToDataTable(object.data);
        var options = {
          chart: {
            title: 'Currency: '+object.currency_name,
          }
        };
        var chart = new google.charts.Bar(document.getElementById('chart_div_'+object.currency_id));
        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
			<?php 
			require_once 'config.php';
			include_once 'FetchData.php'; 
			$FetchData =  new FetchData($_GET); 
			$Broker = $_GET['Broker'];
			$sentiment = $_GET['sentiment'];
			$ValueLessOrGreater = $_GET['ValueLessOrGreater'];
			$NetVal = $_GET['NetVal'];
			$Currency = $_GET['Currency'];
			$Timeframe = $_GET['Timeframe'];
			$startDate = date("Y-m-d", strtotime($_GET['startDate']));
			$endDate = date("Y-m-d", strtotime($_GET['endDate']));

			$Timeframe1 = $_GET['Timeframe1'];
			$Timeframe2 = $_GET['Timeframe2'];
			$Timeframe3 = $_GET['Timeframe3'];

			$ToTimeframe1 = $_GET['ToTimeframe1'];
			$ToTimeframe2 = $_GET['ToTimeframe2'];
			$ToTimeframe3 = $_GET['ToTimeframe3'];

			$currArr = explode(",", $Currency);

			foreach ($currArr as $key => $currency_id) {
				$response = '';
				$response = $FetchData->searchData($currency_id);
				
				$response = str_replace('"','',$response);
				$curInfo = mysqli_fetch_assoc($conn->query("SELECT currency_name FROM currency where id=".$currency_id));
				$currency_name = $curInfo['currency_name'];
				?>
				<div class="col-sm-12" >
					<div class="card" style="border:1px solid #ededed; margin-bottom: 20px;">
						<div class="card-header"><h4><?php echo $currency_name; ?></h4></div>
						<div class="card-body">
							<div id="chart_div_<?php echo $currency_id; ?>" style="width: 100%; height: 400px;"></div>
						</div>
					</div>
				</div>
				<script>
				    google.setOnLoadCallback(function() {
				    	let obj = {
				    		currency_id: <?php echo $currency_id; ?>,
				    		currency_name: <?php echo "'".$currency_name."'"; ?>,
				    		data: <?php echo $response; ?>
				    	}
				        if(obj){
				        	drawVisualization(obj);
				        }
				    });
				</script>
			<?php } ?>
		</div>
	</div>
</div>


<?php 
include "inc/footer.php";
?>

