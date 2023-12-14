<?php include("header.php"); 
//Daily Status
$db->where ("DATE(time) = DATE(CURDATE())");
$db->where("status = 1");
$stats_today = $db->getOne ("orders", "count(id) as count,sum(total) as total_sale,sum(discount) as total_discount");
$daily_expense = $db->rawQueryOne('SELECT SUM(amount) as expense FROM expense where DATE(date) = DATE(CURDATE())');
$daily_buying = $db->rawQueryOne('SELECT SUM(order_product.quantity * product_batch.buying_price) as buying FROM orders JOIN order_product ON orders.id = order_product.order_id JOIN product_batch ON order_product.batch_id = product_batch.id where DATE(orders.time) = DATE(CURDATE()) AND status = 1 GROUP By DATE(orders.time)');	

//Monthly Status
$db->where ("MONTH(time) = MONTH(CURRENT_DATE()) AND YEAR(time) = YEAR(CURRENT_DATE()) AND status = 1");
$stats_month = $db->getOne ("orders", "count(id) as count,sum(total) as total_sale,sum(discount) as total_discount");
$monthly_expense = $db->rawQueryOne('SELECT SUM(amount) as expense FROM expense where MONTH(date) = MONTH(CURRENT_DATE()) && YEAR(date) = YEAR(CURRENT_DATE())');
$monthly_buying = $db->rawQueryOne('SELECT SUM(order_product.quantity * product_batch.buying_price) as buying FROM orders JOIN order_product ON orders.id = order_product.order_id JOIN product_batch ON order_product.batch_id = product_batch.id where MONTH(orders.time) =  MONTH(CURRENT_DATE()) AND YEAR(orders.time) = YEAR(CURRENT_DATE()) AND status = 1 ');	

$cols = Array ("DAY(time) as day", "SUM(total) as total_sale");
$db->groupBy("date(time)");
$db->where("MONTH(time) = MONTH(CURRENT_DATE()) AND YEAR(time) = YEAR(CURRENT_DATE()) AND status = 1");
$day_sale = $db->get("orders", null , $cols);


$eachday_buying = $db->rawQuery('SELECT DAY(time) as day, SUM(order_product.quantity * product_batch.buying_price) as buying FROM orders JOIN order_product ON orders.id = order_product.order_id JOIN product_batch ON order_product.batch_id = product_batch.id Where MONTH(orders.time) =  MONTH(CURRENT_DATE()) AND YEAR(orders.time) = YEAR(CURRENT_DATE()) AND status = 1 GROUP By DATE(orders.time)');	
$exp = $db->rawQuery('SELECT DAY(date) as day, SUM(amount) as amount FROM `expense` where MONTH(date)= MONTH(CURRENT_DATE()) AND YEAR(date)= YEAR(CURRENT_DATE()) group By DAY(date)');	
	$sales = array();
	$revenue = array();
	$expense= array();
	$days =array();
	$d= (cal_days_in_month(CAL_GREGORIAN, date('m'),date('Y')));
	for($i =0 ; $i < $d ; $i++)
	{
		$days [] = $i;
		$sales[] = 0;
		$revenue[]=0;
		$expense[]=0;
		
	}
	foreach($exp as $row)
	{
		$expense[$row['day']] = $row['amount'];
	}
	foreach($day_sale as $row)
	{
		$sales[$row['day']] = $row['total_sale'];
	}
	
	foreach($eachday_buying as $row)
	{
			$revenue[$row['day']] = ($sales[$row['day']]-$row['buying'])-$expense[$row['day']];
	}

	// var_dump($revenue);
	// exit();
	
?>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
					<div class="col-lg-12">
						<h3>Daily Summary</h3>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-warning text-center">
                                            <i class="ti-shopping-cart-full"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Orders</p><?= $stats_today['count'];?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> Today
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-success text-center">
                                            <i class="ti-bar-chart"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Sale</p><small><?= number_format((float)($stats_today['total_sale']+$stats_today['total_discount']), 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> Today
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-danger text-center">
                                            <i class="ti-pie-chart"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Discount</p><small><?= number_format((float)$stats_today['total_discount'], 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> Today
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-info text-center">
                                            <i class="ti-credit-card"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Expense</p><small><?= number_format((float)$daily_expense['expense'], 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> Today
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-info text-center">
                                            <i class="ti-wallet"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Cash</p><small><?= number_format((float)$stats_today['total_sale']-$daily_expense['expense'], 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> Today
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-revenue text-center">
                                            <i class="ti-stats-up"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Revenue</p><small><?= number_format((float)($stats_today['total_sale']-$daily_expense['expense']-$daily_buying['buying']), 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> Today
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="row">
					<div class="col-lg-12">
						<h3>Monthly Summary</h3>
					</div>
				</div>
				<div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-warning text-center">
                                            <i class="ti-shopping-cart-full"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Orders</p><?= $stats_month['count'];?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> This Month
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-success text-center">
                                            <i class="ti-bar-chart"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Sale</p><small><?= number_format((float)($stats_month['total_sale']+$stats_month['total_discount']), 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> This Month
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-danger text-center">
                                            <i class="ti-pie-chart"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Discount</p><small><?= number_format((float)$stats_month['total_discount'], 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> This Month
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-info text-center">
                                            <i class="ti-wallet"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Expense</p><small><?= number_format((float)$monthly_expense['expense'], 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i>  This Month
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-info text-center">
                                            <i class="ti-wallet"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Cash</p><small><?= number_format((float)$stats_month['total_sale']-$monthly_expense['expense'], 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i>  This Month
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="icon-big icon-revenue text-center">
                                            <i class="ti-stats-up"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="numbers">
                                            <p>Revenue</p><small><?= number_format((float)($stats_month['total_sale']-$monthly_expense['expense']-$monthly_buying['buying']), 2, '.', ',') ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-calendar"></i> This Month
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				
				<hr>
				 <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Monthly Sales</h4>
                                <p class="category">Sales for the month of <?= Date('M')?></p>
                            </div>
                            <div class="content">
                                <canvas id="myChart" width="400" height="150"></canvas>
                                <div class="footer">
                                    <hr>
                                    <div class="stats">
                                        <i class="ti-calendar"></i> This Month
                                    </div>
									
									
                                </div>
								
                            </div>
							
                        </div>
						

						
                    </div>
                </div>
                </div>
            </div>
     </div>


        <footer class="footer">
            <div class="container-fluid">
                <!-- <nav class="pull-left"> -->
                    <!-- <ul> -->

                        <!-- <li> -->
                            <!-- <a href="http://www.creative-tim.com"> -->
                                <!-- Creative Tim -->
                            <!-- </a> -->
                        <!-- </li> -->
                        <!-- <li> -->
                            <!-- <a href="http://blog.creative-tim.com"> -->
                               <!-- Blog -->
                            <!-- </a> -->
                        <!-- </li> -->
                        <!-- <li> -->
                            <!-- <a href="http://www.creative-tim.com/license"> -->
                                <!-- Licenses -->
                            <!-- </a> -->
                        <!-- </li> -->
                    <!-- </ul> -->
                <!-- </nav> -->
         
            </div>
        </footer>

    </div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio.js"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="assets/js/paper-dashboard.js"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>
	
	// <script type="text/javascript">
    	// $(document).ready(function(){
			
        	// var dataSales = {
				
				  // labels: <?= json_encode($days)?>,
				  // series: [<?= json_encode($sales)?>,<?= json_encode($revenue)?>
				  // ]
				// };

				// var optionsSales = {
				  // lineSmooth: false,
				  // low: 0,
				  // high: 50000,
				  // showArea: true,
				  // height: "245px",
				  // axisX: {
					// showGrid: false,
				  // },
				  // lineSmooth: Chartist.Interpolation.simple({
					// divisor: 3
				  // }),
				  // showLine: true,
				  // showPoint: false,
				// };

				// var responsiveSales = [
				  // ['screen and (max-width: 640px)', {
					// axisX: {
					  // labelInterpolationFnc: function (value) {
						// return value[0];
					  // }
					// }
				  // }]
				// ];

				// Chartist.Line('#chartHours', dataSales, optionsSales, responsiveSales);

				// });
	// </script>
	
	<script>
	var ctx = document.getElementById("myChart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?= json_encode($days)?>,
			datasets: [{
				label: 'Sales',
				data: <?= json_encode($sales)?>,
				backgroundColor: 'rgba(54, 162, 235, 0.2)',
				borderColor: 'rgba(54, 162, 235, 1)',
				borderWidth: 1
				},{
					label: 'Revenue',
					data: <?= json_encode($revenue)?>,
					backgroundColor: 'rgb(124,252,0,0.5)',
					borderColor: 'rgb(34,139,34)',
					borderWidth: 1
				}
			]
		},
		options: {
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var label = data.datasets[tooltipItem.datasetIndex].label || '';

						if (label) {
							label += ': ';
						}
						label += tooltipItem.yLabel + ' PKR' ;
						return label;
					},
					title: function(tooltipItem, data){
						
						return tooltipItem[0].xLabel + " <?= date("M Y");?>";
					}
				}
			}
		}
	});
	</script>
	

</html>
