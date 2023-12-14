<?php include("header.php"); ?>
    
<?php
	if(isset($_GET['edit'])){
		$db->where ("id", $_GET['edit']);
		$user = $db->getOne ("orders");
				
}
?>     
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Order ID: <?= $_GET['edit'] ?><?= ($user['status'] == 2) ?  ' <span class="badge badge-primary" style="background-color: #eb5e28;font-size: 16px; margin-bottom: 4px;">Refunded</span>':''?></h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Customer Name</label>
                                                <input type="text" class="form-control border-input" placeholder="" value="<?php 
																																$db->where ("id", $user['customer_id']);
																																$customer = $db->getOne ("customer");
																																echo $customer['name'];?>">
                                            </div>
                                        </div>
										<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Order Time</label>
                                                <input type="text" class="form-control border-input" placeholder="" value="<?php echo date("d-m-Y H:i",strtotime($user['time'])); ?>">
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Order Total</label>
                                                <input type="text" class="form-control border-input" placeholder="" value="<?php echo number_format($user['total'], 2 , '.' , ','); ?>">
                                            </div>
											
                                        </div>
										 
                                    </div>

                                   
                                    <div class="clearfix"></div>
                                </form>
								<div class="table-responsive table-full-width">
                                <table class="table table-bordered table-striped">
                                    <thead>
										<th>Product</th>
                                        <th>Unit Price</th>
                                    	<th>Quantity</th>
                                    	<th>Total</th>
                                    </thead>
                                    <tbody>
									
									<?php 
										
										$items = $db->rawQuery('SELECT product_id, unit_price , SUM(quantity) as quantity FROM order_product WHERE order_id = '.$_GET['edit'].' GROUP BY product_id');

									foreach($items as $row){
									?>

									<tr>
										<td>
											<?php 
											$db->where ("id", $row['product_id']);
													$product = $db->getOne ("product");
													echo $product['name']; ?>
										</td>
										<td>
											<?php echo $row['unit_price']; ?>
										</td>
										<td>
											<?php 
													echo $row['quantity'];?>
										</td>
										<td style="text-align: right;">
											<?php 
													echo number_format($row['quantity']*$row['unit_price'], 2 , '.' , ',');?>
										</td>
										
									</tr>

									<?php } ?>
                                       
                                    </tbody>
                                </table>

                            </div>
							
									<div class="text-right">
									<a href="order.php" class="btn btn-info btn-fill btn-wd">Back</a>
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

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="assets/js/paper-dashboard.js"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

</html>
