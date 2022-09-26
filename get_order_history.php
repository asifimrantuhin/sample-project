<?php 
if(isset($_POST['page'])){ 
    // Include pagination library file 
    include_once 'Pagination.php'; 
     
    // Include database configuration file 
    require_once 'config.php';
     

    $baseURL = 'get_order_history.php'; 
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 5; 

    ## Custom Field value
	$searchByFromdate = $_POST['fromdate'];
	$searchByToate = $_POST['todate'];
	$searchByAccName = $_POST['account'];
	$searchByBotName = $_POST['botname'];
	$searchByProfitLoss = $_POST['profitloss'];

	## Search 
	$searchQuery = " ";
	if($searchByAccName != ''){
	    $searchQuery .= " and (account like '%".$searchByAccName."%' ) ";
	}
	if($searchByBotName != ''){
	    $searchQuery .= " and (botname='".$searchByBotName."') ";
	}
	if($searchByProfitLoss != ''){
	    $searchQuery .= " and (profit_loss like '%".$searchByProfitLoss."%') ";
	}

	if($searchByFromdate != '' && $searchByToate !=''){
	    $searchQuery .= " and (DATE_FORMAT(datetime, '%Y-%m-%d') BETWEEN '$searchByFromdate' AND '$searchByToate') ";
	}

	//echo $searchQuery;exit;
    // Count of all records 
    $query   = $conn->query("SELECT COUNT(*) as rowNum FROM order_history WHERE 1 $searchQuery"); 
    $result  = $query->fetch_assoc(); 
    $rowCount= $result['rowNum']; 
     
    // Initialize pagination class 
    $pagConfig = array( 
        'baseURL' => $baseURL, 
        'totalRows' => $rowCount, 
        'perPage' => $limit, 
        'currentPage' => $offset, 
        'contentDiv' => 'dataContainer' 
    ); 
    $pagination =  new Pagination($pagConfig); 
 
    // Fetch records based on the offset and limit 
    $query = $conn->query("SELECT * FROM order_history WHERE 1 $searchQuery ORDER BY id ASC LIMIT $offset, $limit"); 
 
?> 
    <!-- Data list container --> 
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



    
<?php 
} 
?>