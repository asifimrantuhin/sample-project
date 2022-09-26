<?php 
if(isset($_POST['daterange'])){ 
    // Include database configuration file 
    require_once 'config.php';

    $range = explode("-", $_POST['daterange']);
    //print_r($range);
    $fromdate = $range[0]; //date("Y-m-d", strtotime($range[0]));
    $todate = $range[1]; //date("Y-m-d", strtotime($range[1]));


    $query   = $conn->query("SELECT 
		SUM(IF(pl> 0,pl,0)) AS profit,
		SUM(IF(pl< 0,pl,0)) AS loss,
		DATE_ADD( DATE(DATETIME), INTERVAL (1 - DAYOFWEEK(DATETIME )) DAY) week_start,
		DATE_ADD( DATE(DATETIME), INTERVAL (7 - DAYOFWEEK(DATETIME )) DAY) week_ending
		FROM order_history
		WHERE DATE_FORMAT(DATETIME, '%d-%m-%Y')  BETWEEN '".$fromdate."' AND '".$todate."'
		GROUP BY week_ending");


    $labelStr = "";
	$profitStr = "";
	$lossStr = "";
	while($row = $query->fetch_assoc()){
	    $labelStr .= "'".date('d/m/Y', strtotime($row['week_start'])).'-'.date('d/m/Y', strtotime($row['week_ending']))."', ";
	    $profitStr .= $row['profit'].",";
		$lossStr .= $row['loss'].",";
	}
	$labelStr = rtrim($labelStr, ",");
	$profitStr = rtrim($profitStr, ",");
	$lossStr = rtrim($lossStr, ",");

	$output[] = array(
	   'currency_name'   => $labelStr,
	   'profit'  => $profitStr,
	   'loss'  => $lossStr
	  );
	echo json_encode($output); 
     
    
 
}
    
?>