<?php 
    require_once 'config.php'; 
    include "inc/header.php";
    
    $currencies = $conn->query("SELECT * FROM currency ORDER BY id ASC");
    $brokers = $conn->query("SELECT * FROM website ORDER BY Id_Website ASC");
    
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body" id="graphform">
                        <h4 class="card-title " style="border-bottom: 1px solid #ededed; padding-bottom: 10px;">
                            <div class="row">
                                <div class="col-sm-2" style="    line-height: 40px;">Broker : </div>
                                <div class="col-sm-4">
                                    <select class="js-example-basic-multiple2 js-states mb-2 mr-sm-2 w-100" name="broker[]" multiple="multiple" id="Broker">
                                        <?php
                                            if($brokers->num_rows > 0) {
                                                while ($row = $brokers->fetch_assoc()) {
                                                    echo '<option value="'.$row['Id_Website'].'">'.$row['Website'].'</option>';
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                <!-- <label for="Currency">Currency*</label> -->
                                <select class="js-example-basic-multiple js-states mb-2 mr-sm-2 w-100" name="currency[]" multiple="multiple" id="Currency">
                                    <option>Select Currency</option>
                                    <?php
                                        if($currencies->num_rows > 0) {
                                            while ($row = $currencies->fetch_assoc()) {
                                                echo '<option value="'.$row['id'].'">'.$row['currency_name'].'</option>';
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select class="js-example-basic-multiple3 js-states mb-2 mr-sm-2 w-100" id="ChartType">

                                    <option value="">Select Chart</option>
                                    <option value="Column">Default Chart</option>
                                    <option value="Line">Line Chart</option>
                                </select>
                            </div>

                            </div>
                        </h4>
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="Valuetype">Sentiment *</label>
                                <select class="js-example-basic-single w-100" id="Valuetype">
                                    <option value="">Select Type</option>
                                    <option value="NetLong">NetLong</option>
                                    <option value="NetShort">NetShort</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="ValueLessOrGreater">Select (< / = / >)  *</label>
                                <select class="js-example-basic-single w-100" id="ValueLessOrGreater">
                                    <option value="=">=</option>
                                    <option value=">">></option>
                                    <option value="<"><</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="NetVal">Type Value*</label>
                                <input type="text" class="form-control mb-2 mr-sm-2" id="NetVal" placeholder="70">
                            </div>
                            
                            <div class="col-sm-2">
                                <label for="Timeframe">Time Frame*</label>
                                <select class="js-example-basic-single w-100" id="Timeframe" onchange="changeDateView()">
                                    <option value="">Select</option>
                                    <option value="Month">Monthly</option>
                                    <option value="Week">Weekly</option>
                                    <option value="Day">Daily</option>
                                    <option value="Hour">Hourly</option>
                                    <option value="Minute">Minute</option>
                                    <option value="Second">Second</option>
                                </select>
                            </div>
                            <div class="col-sm-4" id="monthRange">
                            	<div class="row">
		                            <div class="col-sm-6">
	                                    <label for="startDate">Start Date *</label>
	                                    <input type="text" class="form-control" name="start_date" id="startDate" value="<?php echo date("m/d/Y"); ?>" />
		                            </div>
		                            <div class="col-sm-6">
	                                    <label for="endDate">End Date *</label>
	                                    <input type="text" class="form-control" name="end_date" id="endDate" value="<?php echo date("m/d/Y"); ?>">
		                            </div>
		                        </div>
	                        </div>

                            <div class="col-sm-3" id="Hour" style="display:none">
                                <div class="row">
        	                        <div class="col-sm-6"  >
                                        <label for="Timeframe1">From Hour*</label>
                                        <select class="js-example-basic-single w-100" id="Timeframe1">
                                            <option value="">Select</option>
                                            <?php 
                                            $str = '0';
                                            for($h=0; $h<24; $h++){
                                                if($h > 9 ){
                                                    $str = '';
                                                }
                                            ?>
                                            <option value="<?= $str.$h ?>"><?= $str.$h ?></option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>

                                    <div class="col-sm-6" id="ToHour" >
                                        <label for="ToTimeframe1">To Hour*</label>
                                        <select class="js-example-basic-single w-100" id="ToTimeframe1">
                                            <option value="">Select</option>
                                            <?php 
                                            $str = '0';
                                            for($h=0; $h<24; $h++){
                                                if($h > 9 ){
                                                    $str = '';
                                                }
                                            ?>
                                            <option value="<?= $str.$h ?>"><?= $str.$h ?></option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-3" id="Minute" style="display:none">
                                <div class="row">
                                    <div class="col-sm-6" id="Minute">
                                        <label for="Timeframe2">From Minute*</label>
                                        <select class="js-example-basic-single w-100" id="Timeframe2">
                                            <option value="">Select</option>
                                            <?php 
                                            $str = '0';
                                            for($h=0; $h<60; $h++){
                                                if($h > 9 ){
                                                    $str = '';
                                                }
                                            ?>
                                            <option value="<?= $str.$h ?>"><?= $str.$h ?></option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                    <div class="col-sm-6" id="ToMinute">
                                        <label for="ToTimeframe2">To Minute*</label>
                                        <select class="js-example-basic-single w-100" id="ToTimeframe2">
                                            <option value="">Select</option>
                                            <?php 
                                            $str = '0';
                                            for($h=0; $h<60; $h++){
                                                if($h > 9 ){
                                                    $str = '';
                                                }
                                            ?>
                                            <option value="<?= $str.$h ?>"><?= $str.$h ?></option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-3" id="Second" style="display:none">
                                <div class="row">
                                    <div class="col-sm-6" id="Second">
                                        <label for="Timeframe3">From Second*</label>
                                        <select class="js-example-basic-single w-100" id="Timeframe3">
                                            <option value="">Select</option>
                                            <?php 
                                            $str = '0';
                                            for($h=0; $h<60; $h++){
                                                if($h > 9 ){
                                                    $str = '';
                                                }
                                            ?>
                                            <option value="<?= $str.$h ?>"><?= $str.$h ?></option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                    <div class="col-sm-6" id="ToSecond">
                                        <label for="ToTimeframe3">To Second*</label>
                                        <select class="js-example-basic-single w-100" id="ToTimeframe3">
                                            <option value="">Select</option>
                                            <?php 
                                            $str = '0';
                                            for($h=0; $h<60; $h++){
                                                if($h > 9 ){
                                                    $str = '';
                                                }
                                            ?>
                                            <option value="<?= $str.$h ?>"><?= $str.$h ?></option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>



                            <div class="col-sm-2">
                                <label for="sub" id="SubLevel" style="display:none">&nbsp;</label>
                            	<!-- <label for="sub" id="SubLevel">&nbsp;</label> -->
                                <button type="submit" id="sub" class="btn btn-md btn-dark btn-block mb-2" onclick="changeGraph()">Search</button>
                            </div>
                            <div class="col-sm-1" style="padding-top:15px">
                                <span class="loader1"></span>
                            </div>
                        </div>



                        <div class="row" id="replaceDiv">
                            
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




<script>
    function changeDateView(){
        var Timeframe = $('#Timeframe').val();
        $('#startDate, #endDate').datepicker('remove');
        if(Timeframe == "Hour"){
            $('#SubLevel').show();
             $('#Hour').show();
             $('#Minute').hide();
             $('#Second').hide();
        }
        else if(Timeframe == "Minute"){
            $('#SubLevel').show();
             $('#Hour').show();
             $('#Minute').show();
             $('#Second').hide();
        }else if(Timeframe == "Second"){
            $('#SubLevel').show();
             $('#Hour').show();
             $('#Minute').show();
             $('#Second').show();
        }else{
            $('#SubLevel').hide();
            $('#Hour').hide();
            $('#Minute').hide();
            $('#Second').hide();
        }

        
        if(Timeframe == "Month"){
            var currentYear = new Date().getFullYear();
            var currentMonth = new Date().getMonth() + 1;
            $('#startDate, #endDate').val(currentMonth+"/"+currentYear);
            $('#startDate, #endDate').datepicker({
                viewMode: 'years',
                format: "mm/yyyy",
                minViewMode: "months"            
            }).on('changeDate', function(selected){
                //$('#endDate').datepicker('remove');
                $('#endDate').datepicker('setDate', new Date(selected.date.valueOf()));
            });
        }else{
            $('#startDate, #endDate').val(new Date());
            
            var startD = new Date();
            var endD = new Date(new Date().setYear(startD.getFullYear()+1));
            $('#startDate, #endDate').datepicker('setDate', startD );
            $('#startDate').datepicker({
                startDate : startD,
                format: "mm/dd/yyyy",
                todayHighlight: true,
                autoclose: true,
                endDate   : endD
            }).on('changeDate', function(selected){
                
                var Timeframe = $('#Timeframe').val();
                var minDate = new Date(selected.date.valueOf());
                var lastDay = new Date(minDate.setDate(minDate.getDate()));
                if(Timeframe == "Month"){
                    lastDay = new Date(minDate.getFullYear(), minDate.getMonth() + 1, minDate.getDate());
                }else if(Timeframe == "Week"){
                     lastDay = new Date(minDate.setDate(minDate.getDate() + 7));
                }else if(Timeframe == "Day"){
                     lastDay = new Date(minDate.setDate(minDate.getDate()));
                }else if(Timeframe == "Hour"){
                     lastDay = new Date(minDate.setDate(minDate.getDate()));
                }
                else if(Timeframe == "Minute"){
                     lastDay = new Date(minDate.setDate(minDate.getDate()));
                }else if(Timeframe == "Second"){
                     lastDay = new Date(minDate.setDate(minDate.getDate()));
                }else{
                    lastDay = new Date(minDate.setDate(minDate.getDate()));
                }
                $('#endDate').datepicker('setDate', lastDay);
            });
        }

        //event.stopPropagation();

    }
//Start date and end date

</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.loader1').hide();
        //$('#startDate, #endDate').datepicker();
        $(".js-example-basic-multiple").select2({
            placeholder: "All Currency"
        });
        
        $(".js-example-basic-multiple2").select2({
            placeholder: "ALL Broker"
        });
        $(".js-example-basic-multiple3").select2({
            placeholder: "Default Chart"
        });
    });



    function changeGraph(){
        var Broker = $('#Broker').val();
        var sentiment = $('#Valuetype').val();
        var ValueLessOrGreater = $('#ValueLessOrGreater').val();
        var NetVal = $('#NetVal').val();
        var Currency = $('#Currency').val();
        var Timeframe = $('#Timeframe').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var Timeframe1 = $('#Timeframe1').val();
        var Timeframe2 = $('#Timeframe2').val();
        var Timeframe3 = $('#Timeframe3').val();
        var ToTimeframe1 = $('#ToTimeframe1').val();
        var ToTimeframe2 = $('#ToTimeframe2').val();
        var ToTimeframe3 = $('#ToTimeframe3').val();
        var ChartType = $('#ChartType').val();
        var url='getGraphData.php';

        if(sentiment=="" || ValueLessOrGreater=="" || NetVal=="" || Timeframe=="" || startDate=="" || endDate==""){
            alert("Please select required values");
            return false;
        }
        $('.loader1').show();
        if(ChartType =="Line"){
            url = 'linechart.php';
        }
        $.ajax({
            type: 'GET',
            url: url,
            data:'Broker='+Broker+'&sentiment='+sentiment+'&ValueLessOrGreater='+ValueLessOrGreater+'&NetVal='+NetVal+'&Currency='+Currency+'&Timeframe='+Timeframe+'&startDate='+startDate+'&endDate='+endDate+'&Timeframe1='+Timeframe1+'&Timeframe2='+Timeframe2+'&Timeframe3='+Timeframe3+'&ToTimeframe1='+ToTimeframe1+'&ToTimeframe2='+ToTimeframe2+'&ToTimeframe3='+ToTimeframe3,
            beforeSend: function () {
                $('.loading-overlay').show();
            },
            success: function (html) {
                $('#replaceDiv').html(html);
                $('.loading-overlay').fadeOut("slow");
                $('.loader1').hide();
            }
        });


        
    }

    function openNewTab(url){
        window.open(url);
    }
</script>