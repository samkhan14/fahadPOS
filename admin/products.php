<?php include("header.php");
 ?>


         <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-card">
                            <div class="header">
								<div class="row">
									<div class="col-md-6 col-xs-7">
										<h4 class="title">Products</h4>
										<p class="category">Product List</p>
									</div>
									<div class="col-md-6 col-xs-5 text-right">
										<a href="products.php?export=product" class="btn btn-primary btn-fill">Export</a>
										<a href="products_add.php" class="btn btn-primary btn-fill">Add New</a>
										<a onclick="return confirm('Are you sure you want to delete all records?');" href="products.php?clear=product" class="btn btn-primary btn-fill">Delete All</a>
									</div>
								</div>
                            </div>
							
								
								<?php
										if(isset($_GET['clear'])){
											$db->rawQueryOne ('TRUNCATE TABLE product_batch');
											$db->rawQueryOne ('TRUNCATE TABLE product'); 
							?>					
							 <div class="alert alert-success">Successfully Deleted!</div>
											
								<?php		
										}
										
								?>
                            <div class="table-responsive table-full-width">
                                <table id="prod_table" class="table table-bordered table-striped table-responsive">
                                    <thead>
                                        <th>Product Name</th>
                                    	<th>Category</th>
                                    	<th>Brand</th>
                                       <th>Selling Price</th>
                                       <th>Buying Price</th>
                                    	<th>Quantity Available</th>
                                    	<th>Action</th>
                                    </thead>
                                    <tbody>

                                   </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
         </div>

    <?php
include("footer.php"); ?>