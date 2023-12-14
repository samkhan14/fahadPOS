<?php include("header.php"); ?>
<?php

if(isset($_POST["filename"]))
{
//getcwd()."\\".
	$file_name=$_POST["filename"];

	if($file = fopen($file_name, "r"))
	{
		$count = 0;
		
        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
        {
             if($count>0){  
			 
				$name = trim(strtolower($emapData[0]));
				$db->where('LOWER(name)',$name,'like' );
				$product = $db->getOne('product');
				if(!$product)
				{
					$cat_name = trim(strtolower($emapData[1]));
					$brand_name = trim(strtolower($emapData[2]));
					

					$db->where('LOWER(name)',$cat_name,'like' );
					$category = $db->getOne('category');
					
					
					if(!$category)
					{
						$cat = Array ("name" => $emapData[1]
						);
						$cat_id = $db->insert ('category', $cat);
						
					}
					else
						$cat_id = $category['id'];
					 
					$db->where('LOWER(name)',$brand_name,'like' );
					$brand = $db->getOne('brand');
					
					
					if(!$brand)
					{
						$brand_data = Array ("name" => $emapData[2]
						);
						$brand_id = $db->insert ('brand', $brand_data);
						
					}
					else
						$brand_id = $brand['id'];
					
					$data = Array ("name" => $emapData[0],
								   "category_id" => $cat_id,
								   "brand_id" => $brand_id,
								   "description" => $emapData[3],
								   "buying_price" => (float)trim($emapData[4]),
								   "selling_price" => (float)trim($emapData[5]),
								   "quantity" => (float)trim($emapData[6])
					);
					$id = $db->insert ('product', $data);
					
					$prod_name = trim(strtolower($emapData[0]));
					$db->where('LOWER(name)',$prod_name,'like' );
					$product = $db->getOne('product');
					
					$prod_batch = Array ("product_id" => $product['id'],
										"buying_price" => (float)trim($emapData[4]),
										"selling_price" => (float)trim($emapData[5]),
										"quantity" => (float)trim($emapData[6]),
										"import_quantity" => (float)trim($emapData[6])
					);
					$id = $db->insert ('product_batch', $prod_batch);
					
				}
				else{	
					
					$data = Array (
							'selling_price' => (float)trim($emapData[5]),
							'buying_price' => (float)trim($emapData[4]),
							'quantity' => $db->inc((float)trim($emapData[6]))
						);
					$db->where ('id', $product['id']);
					if ($db->update ('product', $data))
					{
						$prod_batch = Array ("product_id" => $product['id'],
									"buying_price" => (float)trim($emapData[4]),
									"selling_price" => (float)trim($emapData[5]),
									"quantity" => (float)trim($emapData[6]),
									"import_quantity" => (float)trim($emapData[6])
								);
						$id = $db->insert ('product_batch', $prod_batch);

					}
					else
						echo 'update failed: ' . $db->getLastError();
				
				}
			 }
			  $count=1;
			 
        }
        fclose($file);
		unlink($file_name);
		$imported='done';
		

      
    }
    else
        echo 'Invalid File:Please Upload CSV File';
}
	
if(isset($_POST["import"]))
{
	
	if(end(explode(".", $_FILES['file']['name'])) == 'csv'){
	    
	    move_uploaded_file($_FILES["file"]["tmp_name"],"uploads/".$_FILES["file"]["name"]);
        $filename="uploads/".$_FILES["file"]["name"];
	}else{
	    
	    unset($_POST["import"]);
	}
	
}	
	
	
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-card">
							<div class="header">
								<div class="row">
									<div class="col-md-6 col-xs-5">
										<h4 class="title">Import</h4>
										<p class="category">Import Products</p>
									</div>
									<div class="col-md-6 col-xs-5 text-right">
										<a href="importSample.csv" download="sample.csv" class="btn btn-primary btn-fill">Import Sample</a>
									</div>
									
								</div>
								
                            </div>
							<?php
										if(isset($imported)){
											 
							?>					
							 <div class="alert alert-success">CSV File has been successfully Imported!</div>
								 
												
								<?php		
										}

								
								?>
							<form action="" method="post"
								name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
								<div class="row">
									<div class="col-md-3">
										<input  type="file" name="file"
										id="file" style="padding:10px" accept=".csv">
									</div>
									<div class="col-md-4">
										<button type="submit" id="submit" name="import"
										class="btn btn-submit btn-info btn-fill b_padding">Import CSV</button>
									</div> 
								</div>
							
							</form>
                            <?php if(isset($_POST["import"])){	?>
							<hr>
							<div class="row">	
								<div class="col-md-12 col-xs-12">
										<h5><b>REVIEW IMPORT SUMMARY</b></h5>
								</div>
							</div>
							
							<div class="row">	
								<div class="col-md-12 col-xs-12">
										<h5>New Products</h5>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-xs-12">
									<div class="table-responsive table-full-width">
									<table class="table-bordered table-striped">
										<thead>
											<th>Product Name</th>
											<th>Category</th>
											<th>Brand</th>
											<th>Buying Price</th>
											<th>Selling Price</th>
											<th>Quantity</th>
											
										</thead>
										<tbody>
										<?php 
		
											
											
											if($_FILES["file"]["size"] > 0)
											{
												$file = fopen($filename, "r");
												
												$count = 0;
												
												while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
												{
													if($count>0){  
														$name = trim(strtolower($emapData[0]));
														$db->where('LOWER(name)',$name,'like' );
														$product = $db->getOne('product');
														if(!$product)
														{
											?>

											<tr>
												<td>
													<?php echo $emapData[0]; ?>
												</td>
												<td>
													<?php 
															
															echo $emapData[1];?>
												</td>
												<td>
													<?php 
															
															echo $emapData[2]; ?>
												</td>
													<td>
														<?php 
															echo $emapData[4];
														
														?>
													</td>
												<td>
													<?php echo $emapData[5]; ?>
												</td>
															
												<td>
													<?php echo $emapData[6]; ?>
												</td>
											</tr>

													<?php }
													}
													$count=1;
												}
												?>
											
										</tbody>
									</table>

								</div>
							<?php fclose($file);
								$imported='done';
							
											}?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-xs-12">
										<h5>Stock Update</h5>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-xs-12">
								<div class="table-responsive table-full-width">
									<table class="table-bordered table-striped">
										<thead>
											<th>Product Name</th>
											<th>Category</th>
											<th>Brand</th>
											<th>Buying Price</th>
											<th>Selling Price</th>
											<th>Quantity</th>
											
										</thead>
										<tbody>
										<?php 
		
											if($_FILES["file"]["size"] > 0)
											{
												$file = fopen($filename, "r");
												
												$count = 0;
												
												while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
												{
													if($count>0){  
														$name = trim(strtolower($emapData[0]));
														$db->where('LOWER(name)',$name,'like' );
														$product = $db->getOne('product');
														if($product)
														{
											?>

											<tr>
												<td>
													<?php echo $emapData[0]; ?>
												</td>
												<td>
													<?php 
															
															echo $emapData[1];?>
												</td>
												<td>
													<?php 
															
															echo $emapData[2]; ?>
												</td>
													<td>
														<?php 
															echo $emapData[4];
														
														?>
													</td>
												<td>
													<?php echo $emapData[5]; ?>
												</td>
															
												<td>
													<?php echo $emapData[6]; ?>
												</td>
											</tr>

													<?php }
													}
													$count=1;
												}
												?>
											
										</tbody>
									</table>

								</div>
								</div>
								</div>
								<div class="row" style="padding:10px 0px">
									<div class="col-md-12">
										<form action="" method="POST">

											<input type="hidden" name="filename" value="<?= $filename?>" />
											<a href="#" class="btn btn-submit btn-info btn-fill b_padding" onclick="this.parentNode.submit()">Process Import</a>

										</form>
										
									</div>
								</div>
							<?php fclose($file);
								
							
											}?>			
								
											
							<?php }
								?>
								
                        </div>
                    </div>

                </div>
            </div>
        </div>

       <?php
include("footer.php"); ?>