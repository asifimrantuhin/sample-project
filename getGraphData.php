<script src="js/loader.js"></script>
<link rel="stylesheet" href="css/vertical-layout-light/style.css">
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

if($Currency){
		$currArr = explode(",", $Currency);
}else{
	  $result = $conn->query("SELECT id FROM currency");
	  $fetchArr= mysqli_fetch_all($result, MYSQLI_ASSOC);
	  $currArr = [];
	  foreach ($fetchArr as $val) {
	  	array_push($currArr, $val['id']);
	  }
}


foreach ($currArr as $key => $currency_id) {

	$curInfo = mysqli_fetch_assoc($conn->query("SELECT currency_name FROM currency where id=".$currency_id));
	$currency_name = $curInfo['currency_name'];
	$url = "graph-full-view.php?Broker=$Broker&sentiment=$sentiment&ValueLessOrGreater=$ValueLessOrGreater&NetVal=$NetVal&Currency=$currency_id&Timeframe=$Timeframe&startDate=$startDate&endDate=$endDate&Timeframe=$Timeframe&Timeframe1=$Timeframe1&Timeframe2=$Timeframe2&Timeframe3=$Timeframe3&ToTimeframe1=$ToTimeframe1&ToTimeframe2=$ToTimeframe2&ToTimeframe3=$ToTimeframe2&type=individual";
	$response = '';
	$response = $FetchData->searchData($currency_id);
	

	?>
	<div class="col-sm-6" >
		<div class="card" style="border:1px solid #ededed; margin-bottom: 20px;">
			<div class="card-header">
				<h4 class="cur-title"><?php echo $currency_name; ?></h4>
				<span class="fullviewicon" onclick="openNewTab('<?php echo $url; ?>')">Fullview</span>
			</div>
			<div class="card-body">
				<?php 
				if($response){
						$response = str_replace('"','',$response);
				?>
				<div id="chart_div_<?php echo $currency_id; ?>" style="width: 100%; height: 400px;"></div>
				<?php }else{ ?>
				<div >No Data Found</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<script>
	    google.setOnLoadCallback(function() {
	    	let obj = {
	    		currency_id: <?php echo $currency_id; ?>,
	    		currency_name: <?php echo "'".$currency_name."'"; ?>,
	    		title: '',
	    		data: <?php echo $response; ?>
	    	}
	        if(obj){
	        	drawVisualization(obj);
	        }
	    });
	</script>
<?php }  ?>

