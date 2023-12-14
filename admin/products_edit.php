<?php include("header.php"); ?>
    
<?php
	if(isset($_GET['edit'])){
		$db->where ("id", $_GET['edit']);
		$user = $db->getOne ("product");
				
}
?>    

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Product <?php echo $_GET['edit'] ?></h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" class="form-control border-input" id="name" placeholder="" value="<?php echo $user['name']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Category</label>
												
													<select id="category" name="categories" class="form-control border-input">

														<?php 
															$brand = $db->get('category');

														foreach($brand as $row){
														?>
														<option <?php if($user['category_id'] == $row['id']) echo "Selected"?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
														
														<?php } ?>
													</select>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Brand</label>
													<select id="brand" name="brands" class="form-control border-input">
														
														<?php 
															
															$brands = $db->get('brand');

														foreach($brands as $row){
														?>
															<option <?php if($user['brand_id'] == $row['id']) echo "Selected"?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>														
														<?php } ?>
													</select>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Selling Price</label>
                                                <input type="text" class="form-control border-input" id="sell-price" placeholder="" value="<?php echo $user['selling_price']; ?>">
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Buying Price</label>
                                                <input type="text" class="form-control border-input" id="buy-price" placeholder="" value="<?php echo $user['buying_price']; ?>">
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="text-right">
                                        <a href="#" class="btn btn-info btn-fill btn-wd" id="btn-save">Save</a>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
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
	
	<script type="text/javascript">
		$(document).ready(function(){
			
			
			$(document).on('click', '#btn-save', function(){
				if($("#name").val() == "" || $("#sell-price").val() == "" || $("#buy-price").val() == ""|| $("#quantity").val() == "" )
				{
					alert("Missing Field");
				}
				else
				{
					$.post( "update.php", { operation: "update_product", id: <?php echo $_GET['edit'];?> , name: document.getElementById("name").value , 
																											category: document.getElementById("category").value,
																											brand:	document.getElementById("brand").value,
																											buy_price: document.getElementById("buy-price").value,
																											sell_price: document.getElementById("sell-price").value
																											
																											
																											})
					.done(function( data ) {
						
						window.location.replace('products.php');
					});

				}				
				
			});
			
		});
	</script>

</html>
