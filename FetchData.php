<?php
class FetchData{ 
	var $dbconn ='';
    var $currencyId = '';
    var $broker = '';
	var $sentiment = '';
	var $ValueLessOrGreater = '';
	var $NetVal = '';
	var $Currency = '';
	var $timeframe = '';
	var $startDate = '';
	var $endDate = '';

	var $timeframe1 = '';
	var $timeframe2 = '';
	var $timeframe3 = '';

	var $toTimeframe1 = '';
	var $toTimeframe2 = '';
	var $toTimeframe3 = '';

	var $dataArr = array();
	var $bQueryStr = '';
     
    function __construct($params){ 
    	require("config.php");
        $this->dbconn = $conn; 
        if (count($params) > 0){
	        $this->Broker = $params['Broker'];
			$this->sentiment = $params['sentiment'];
			$this->ValueLessOrGreater = $params['ValueLessOrGreater'];
			$this->NetVal = $params['NetVal'];
			$this->Currency = $params['Currency'];
			$this->timeframe = $params['Timeframe'];
			$this->startDate = date("Y-m-d", strtotime($params['startDate']));
			$this->endDate = date("Y-m-d", strtotime($params['endDate']));

			$this->timeframe1 = $params['Timeframe1'];
			$this->timeframe2 = $params['Timeframe2'];
			$this->timeframe3 = $params['Timeframe3'];

			$this->toTimeframe1 = $params['ToTimeframe1'];
			$this->toTimeframe2 = $params['ToTimeframe2'];
			$this->toTimeframe3 = $params['ToTimeframe3'];

            //$this->searchData();         
        }
        
         
    }


    function searchData($currencyId){

    	$this->dataArr = array(); 

    	$this->currencyId = $currencyId;
		if($this->Broker){
			$brokersInfo = $this->dbconn->query("SELECT * FROM website where Id_Website IN (".$this->Broker.") ORDER BY Id_Website ASC");
		}else{
			$brokersInfo = $this->dbconn->query("SELECT * FROM website ORDER BY Id_Website ASC");
		}
		$brokerIdArr = [];

		$brokerStr = "['$this->timeframe', ";
		foreach($brokersInfo as $row) {
		    $brokerStr .= "'".$row['Website'] ."', ";
		    array_push($brokerIdArr, $row['Id_Website']);

		}
		$brokerStr = rtrim($brokerStr, ', ');
		$brokerStr .= "]";
		array_push($this->dataArr, $brokerStr);

		 $this->Broker = implode(",", $brokerIdArr);


		$this->bQueryStr ="";
		foreach($brokersInfo as $row) {
			$this->bQueryStr .= "ROUND(IF(Site_ID=$row[Id_Website], AVG(NetLong), 0)) AS $row[Website],";
		}
		$this->bQueryStr = rtrim($this->bQueryStr, ',');

		
		
		if($this->timeframe == "Month"){
			$sql = "SELECT DATE_FORMAT(datetime_begin, '%b-%y') AS dataTitle, 
				$this->bQueryStr
				FROM arch_sentiment_new 
				WHERE $this->sentiment $this->ValueLessOrGreater $this->NetVal 
				AND Currency_ID=$this->currencyId 
				AND datetime_begin >= '$this->startDate' and datetime_begin <='$this->endDate' 
				AND datetime_end >= '$this->startDate' and datetime_end <='$this->endDate'
				AND Site_ID IN ($this->Broker) GROUP BY MONTH(datetime_begin) ORDER BY MONTH(datetime_begin)";
		}else if($this->timeframe == "Week"){
			//@a:=0;select @a:=@a+1 serial_number,
			$sql = "SET @week=0; SELECT CONCAT('w', (SELECT @week:=@week+1))  AS dataTitle, 
				$this->bQueryStr
				FROM arch_sentiment_new 
				WHERE $this->sentiment $this->ValueLessOrGreater $this->NetVal 
				AND Currency_ID=$this->currencyId 
				AND	datetime_begin BETWEEN '$this->startDate' AND '$this->endDate' 
				AND Site_ID IN ($this->Broker)
				GROUP BY  WEEK(datetime_begin) 
				ORDER BY YEAR(datetime_begin) ASC, WEEK(datetime_begin) ASC";
		}else if($this->timeframe == "Day"){
			$sql = "SELECT DATE_FORMAT(datetime_begin, '%m/%d/%y') AS  dataTitle,
				$this->bQueryStr
				FROM arch_sentiment_new 
				WHERE $this->sentiment $this->ValueLessOrGreater $this->NetVal 
				AND Currency_ID=$this->currencyId 
				AND	datetime_begin BETWEEN '$this->startDate' AND '$this->endDate' 
				AND Site_ID IN ($this->Broker)
				GROUP BY  DATE_FORMAT(datetime_begin, '%d') 
				ORDER BY DATE_FORMAT(datetime_begin, '%d')";
		}else if($this->timeframe == "Hour"){
			$sql = "SELECT DATE_FORMAT(datetime_begin, '%d/%m/%y %H:00') AS  dataTitle,
				$this->bQueryStr
				FROM arch_sentiment_new 
				WHERE $this->sentiment $this->ValueLessOrGreater $this->NetVal 
				AND Currency_ID=$this->currencyId 
				AND	DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:') BETWEEN '$this->startDate $this->timeframe1:' AND '$this->endDate $this->toTimeframe1:' 
				AND Site_ID IN ($this->Broker)
				GROUP BY  DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:') 
				ORDER BY DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:')";
		}else if($this->timeframe == "Minute"){
			$sql = "SELECT DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:%i') AS  dataTitle,
				$this->bQueryStr
				FROM arch_sentiment_new 
				WHERE $this->sentiment $this->ValueLessOrGreater $this->NetVal 
				AND Currency_ID=$this->currencyId 
				AND	DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:%i:') BETWEEN '$this->startDate $this->timeframe1:' AND '$this->endDate $this->toTimeframe1:' 
				AND Site_ID IN ($this->Broker)
				GROUP BY  DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:%i:') 
				ORDER BY DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:%i:')";
		}else{
			$sql = "SELECT DATE_FORMAT(datetime_begin, '%H:%i:%s') AS  dataTitle,
				$this->bQueryStr
				FROM arch_sentiment_new 
				WHERE $this->sentiment $this->ValueLessOrGreater $this->NetVal 
				AND Currency_ID=$this->currencyId 
				AND	DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:%i:%s') BETWEEN '$this->startDate $this->timeframe1:' AND '$this->endDate $this->toTimeframe1:' 
				AND Site_ID IN ($this->Broker)
				GROUP BY  DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:%i:%s') 
				ORDER BY DATE_FORMAT(datetime_begin, '%Y-%m-%d %H:%i:%s')";
		}

		$result = $this->makeResult($sql);
		if($result){
			return json_encode($this->dataArr);
		}else{
			return false;
		}

		

    } 


    function makeResult($sql){
		$query = $this->dbconn->query($sql);
		if($query->num_rows > 0){
			foreach($query as $row) {
				// $dataStr = "[".implode(",", $row)."]";
				// array_push($this->dataArr, $dataStr);

				
				$dataStr = [];
				$keys = array_keys($row);
				for($i=0; $i < count($keys); ++$i) {
				    if($keys[$i] == "dataTitle"){
				    	$str = "'".$row[$keys[$i]]."'";
				    }else{
				    	$str = $row[$keys[$i]];
				    }
				    array_push($dataStr, $str);
				}
				array_push($this->dataArr, $dataStr);
			}
			return true;
		}else{
			return false;
		}
		
    }

}



?>