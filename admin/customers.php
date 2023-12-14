<?php include("header.php"); ?>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-card">
                            <div class="header">
								<div class="row">
									<div class="col-md-6 col-xs-7">
										<h4 class="title">Customers</h4>
										<p class="category">Customer List</p>
									</div>
									<div class="col-md-6 col-xs-5 text-right">
										<a href="customers_add.php" class="btn btn-primary btn-fill">Add New</a>
										<a onclick="return confirm('Are you sure you want to delete all records?');" href="customers.php?clear=customers" class="btn btn-primary btn-fill">Delete All</a>
									</div>
								</div>
                            </div>
                            								
							<?php
										if(isset($_GET['clear'])){
											if($db->rawQueryOne ('TRUNCATE TABLE customer')) 
							?>					
							 <div class="alert alert-success">Successfully Deleted!</div>
								 
												
								<?php		
										}

								
								?>
                            <div class="table-responsive table-full-width">
                                <table id="cust_table" class="table table-bordered table-striped table-responsive">
                                    <thead>
									    <th>Customer ID</th>
                                       <th>Customer Name</th>
                                       <th>Car Number</th>
										         <th>Milage</th>
                                       <th>Phone</th>
                                       <th>Email</th>
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
    