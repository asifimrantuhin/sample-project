<?php 
if(isset($_POST['page'])){ 
    // Include pagination library file 
    include_once 'Pagination.php'; 
     
    // Include database configuration file 
    require_once 'config.php';
     

    $baseURL = 'get_order_history_one.php';
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 5; 

    ## Custom Field value
	$searchByFromdate = $_POST['fromdate'];
	$searchByToate = $_POST['todate'];
	$searchByType = $_POST['type_id'];
	$searchByCurrency = $_POST['currency_id'];
	$searchByProfitLoss = $_POST['profitloss'];

	## Search 
	$searchQuery = " ";
	if($searchByType != ''){
	    $searchQuery .= " and (oh.type ='".$searchByType."' ) ";
	}
	if($searchByCurrency != ''){
	    $searchQuery .= " and (oh.currency='".$searchByCurrency."') ";
	}
	if($searchByProfitLoss != ''){
	    $searchQuery .= " and (oh.pl ='".$searchByProfitLoss."') ";
	}

	if($searchByFromdate != '' && $searchByToate !=''){
	    $searchQuery .= " and (DATE_FORMAT(oh.datetime, '%Y-%m-%d') BETWEEN '$searchByFromdate' AND '$searchByToate') ";
	}

	//echo $searchQuery;exit;
    // Count of all records 
    $query   = $conn->query("SELECT COUNT(*) as rowNum FROM order_history oh
 LEFT JOIN order_type ot ON ot.id = oh.type 
 LEFT JOIN currency cu ON cu.id = oh.currency
  WHERE 1 $searchQuery");
    $result  = $query->fetch_assoc(); 
    $rowCount= $result['rowNum']; 
     
    // Initialize pagination class 
    $pagConfig = array( 
        'baseURL' => $baseURL, 
        'totalRows' => $rowCount, 
        'perPage' => $limit, 
        'currentPage' => $offset, 
        'contentDiv' => 'dataContainerOne',
        'filterFunction'    => 'searchFilterOrderHistoryOne'
    ); 
    $pagination =  new Pagination($pagConfig); 
 
    // Fetch records based on the offset and limit
    $sql_query = "SELECT oh.*, ot.order_type as type_name, cu.currency_name FROM order_history oh
 LEFT JOIN order_type ot ON ot.id = oh.type 
 LEFT JOIN currency cu ON cu.id = oh.currency
 WHERE 1 $searchQuery ORDER BY id ASC LIMIT $offset, $limit";

//    $query = $conn->query("SELECT * FROM order_history WHERE 1 $searchQuery ORDER BY id ASC LIMIT $offset, $limit");
    $query = $conn->query($sql_query);

?> 
    <!-- Data list container --> 
    <table class="table table-hover" >
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
        <tbody id="dataContainerOne">
        <?php
        if($query->num_rows > 0){ $i=0;
            while($row = $query->fetch_assoc()){ $i++;
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
            echo '<tr><td colspan="11">No records found...</td></tr>';
        }
        ?>


        </tbody>
    </table>
    <?php echo $pagination->createLinks(); ?>



    
<?php 
} 
?>