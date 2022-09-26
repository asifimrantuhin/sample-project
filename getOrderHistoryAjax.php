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
    $searchQuery .= " and (oh.datetime like '%".$searchValue."%' or 
        ot.order_type like '%".$searchValue."%' or 
        cu.currency_name like'%".$searchValue."%' or 
        oh.units like'%".$searchValue."%' or 
        oh.price like'%".$searchValue."%' or 
        oh.pl like'%".$searchValue."%' or 
        oh.halfspreadcost like'%".$searchValue."%' or 
        oh.bid like'%".$searchValue."%' or 
        oh.ask like'%".$searchValue."%' or 
        oh.comment like'%".$searchValue."%' ) ";
}

## Total number of records without filtering
$sel = mysqli_query($conn,"select count(*) as allcount from order_history");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$count_sql_query = "select count(*) as allcount from order_history oh
 LEFT JOIN order_type ot ON ot.id = oh.type 
 LEFT JOIN currency cu ON cu.id = oh.currency
 WHERE 1".$searchQuery;
//$sel = mysqli_query($conn,"select count(*) as allcount from order_history WHERE 1 ".$searchQuery);
$sel = mysqli_query($conn, $count_sql_query);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$dataQuery = "SELECT oh.*, ot.order_type as type_name, cu.currency_name FROM order_history oh
 LEFT JOIN order_type ot ON ot.id = oh.type 
 LEFT JOIN currency cu ON cu.id = oh.currency
 WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
//$dataQuery = "select * from order_history WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

$dataRecords = mysqli_query($conn, $dataQuery);
$data = array();

while ($row = mysqli_fetch_assoc($dataRecords)) {
    $data[] = array(
            "datetime"=>$row['datetime'],
    		"type_name"=>$row['type_name'],
    		"currency_name"=>$row['currency_name'],
    		"units"=>$row['units'],
    		"price"=>$row['price'],
    		"pl"=>$row['pl'],
    		"halfspreadcost"=>$row['halfspreadcost'],
    		"bid"=>$row['bid'],
    		"ask"=>$row['ask'],
    		"comment"=>$row['comment'],
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
