</div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="vendors/chart.js/Chart.min.js"></script>
        <script src="vendors/datatables.net/jquery.dataTables.js"></script>
        <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
        <script src="js/dataTables.select.min.js"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/hoverable-collapse.js"></script>
        <script src="js/template.js"></script>
        <script src="js/settings.js"></script>
        <script src="js/todolist.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page-->
        <script src="js/dashboard.js"></script>
        <script src="js/Chart.roundedBarCharts.js"></script>
        <script src="vendors/select2/select2.min.js"></script>
        <script src="js/select2.js"></script>
        <script src="js/chart.js"></script>
        
        <!-- End custom js for this page-->

        <script>
            function searchFilter(page) {
                var limit =5;
                
                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();
                var account = $('#accountname').val();
                var botname = $('#botname').val();
                var profitloss = $('#profitloss').val();

                page_num = page?page:0;
                limit = limit?limit:5;


                $.ajax({
                    type: 'POST',
                    url: 'getData.php',
                    data:'fromdate='+fromdate+'&todate='+todate+'&account='+account+'&botname='+botname+'&profitloss='+profitloss+'&page='+page_num+'&limit='+limit,
                    beforeSend: function () {
                        $('.loading-overlay').show();
                    },
                    success: function (html) {
                        $('#dataContainer').html(html);
                        $('.loading-overlay').fadeOut("slow");
                    }
                });
            }

            function searchFilterOrderHistory(page) {
                var limit =5;

                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();
                var type_id = $('#type_id').val();
                var currency_id = $('#currency_id').val();
                var profitloss = $('#profitloss').val();

                page_num = page?page:0;
                limit = limit?limit:5;


                $.ajax({
                    type: 'POST',
                    url: 'get_order_history.php',
                    data:'fromdate='+fromdate+'&todate='+todate+'&type_id='+type_id+'&currency_id='+currency_id+'&profitloss='+profitloss+'&page='+page_num+'&limit='+limit,
                    beforeSend: function () {
                        $('.loading-overlay').show();
                    },
                    success: function (html) {
                        $('#dataContainer').html(html);
                        $('.loading-overlay').fadeOut("slow");
                    }
                });
            }


            function searchFilterOrderHistoryOne(page) {
                var limit =5;

                var fromdate = $('#fromdate').val();
                var todate = $('#todate').val();
                var type_id = $('#type_id').val();
                var currency_id = $('#currency_id').val();
                var profitloss = $('#profitloss').val();

                page_num = page?page:0;
                limit = limit?limit:5;


                $.ajax({
                    type: 'POST',
                    url: 'get_order_history_one.php',
                    data:'fromdate='+fromdate+'&todate='+todate+'&type_id='+type_id+'&currency_id='+currency_id+'&profitloss='+profitloss+'&page='+page_num+'&limit='+limit,
                    beforeSend: function () {
                        $('.loading-overlay').show();
                    },
                    success: function (html) {
                        $('#dataContainerOne').html(html);
                        $('.loading-overlay').fadeOut("slow");
                    }
                });
            }


        </script>


        <script>
        // Show loading overlay when ajax request starts
        $( document ).ajaxStart(function() {
            $('.loading-overlay').show();
        });

        // Hide loading overlay when ajax request completes
        $( document ).ajaxStop(function() {
            $('.loading-overlay').hide();
        });
        </script>


    </body>
</html>