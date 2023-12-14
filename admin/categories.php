<?php include("header.php"); ?>

     
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-card">
                            <div class="header">
								<div class="row">
									<div class="col-md-6 col-xs-7">
										<h4 class="title">Categories</h4>
										<p class="category">All Product Categories</p>
									</div>
									<div class="col-md-6 col-xs-5 text-right">
										<a href="categories_add.php" class="btn btn-primary btn-fill">Add New</a>
										<a onclick="return confirm('Are you sure you want to delete all records?');" href="categories.php?clear=categories" class="btn btn-primary btn-fill">Delete All</a>
									</div>
								</div>
                            </div>
								<?php
										if(isset($_GET['clear'])){
											if($db->rawQueryOne ('TRUNCATE TABLE category')) 
							?>					
							 <div class="alert alert-success">Successfully Deleted!</div>
								 
												
								<?php		
										}
								?>
                            <div class="table-responsive table-full-width">
                                <table  id="catg_table" class="table table-bordered table-striped table-responsive">
                                    <thead>
                                        <th>Category ID</th>
                                    	<th>Category Name</th>
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
    