<?php
include 'config.php';

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value


## Custom Field value
// $searchByFromdate = $_POST['fromdate'];
// $searchByToate = $_POST['todate'];
// $searchByAccName = $_POST['accountname'];
// $searchByBotName = $_POST['botname'];
// $searchByProfitLoss = $_POST['profitloss'];

## Search 
$searchQuery = " ";
// if($searchByAccName != ''){
//     $searchQuery .= " and (account like '%".$searchByAccName."%' ) ";
// }
// if($searchByBotName != ''){
//     $searchQuery .= " and (botname='".$searchByBotName."') ";
// }
// if($searchByProfitLoss != ''){
//     $searchQuery .= " and (profit_loss='".$searchByBotName."') ";
// }

if($searchValue != ''){
    $searchQuery .= " and (datetime like '%".$searchValue."%' or 
        account like '%".$searchValue."%' or 
        botname like'%".$searchValue."%' or 
        profit_loss like'%".$searchValue."%' ) ";
}

## Total number of records without filtering
$sel = mysqli_query($conn,"select count(*) as allcount from oanda_db");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($conn,"select count(*) as allcount from oanda_db WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$dataQuery = "select * from oanda_db WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

$dataRecords = mysqli_query($conn, $dataQuery);
$data = array();

while ($row = mysqli_fetch_assoc($dataRecords)) {
    $data[] = array(
            "datetime"=>$row['datetime'],
    		"account"=>$row['account'],
    		"botname"=>$row['botname'],
    		"profit_loss"=>$row['profit_loss']
    	);
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);