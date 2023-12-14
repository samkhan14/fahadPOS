<?php 

include("../api/connection.php");


if(isset($_POST['operation']))
	$operation =  $_POST['operation'];

function insert_sql($table,$data ) {
	
	global $db;
	$id =  $db->insert ($table, $data);
		if($id)
			echo 'created. Id=' . $id;
        
        }

	if($operation == 'add_brand')
	{
		
		
		$record = Array ("name" => $_POST['name']
		);

		insert_sql('brand',$record);
	}
	else if($operation == 'add_category')
	{
		
		$record = Array ("name" => $_POST['name']
		);

		insert_sql('category',$record);
	}
	else if($operation == 'add_customer')
	{
		
		$record = Array ("name" => $_POST['name'],
						"email" => $_POST['email'],
						"car_no" => $_POST['car_no'],
						"phone" => $_POST['phone'],
						"mileage" => $_POST['mileage']
		);

		insert_sql('customer',$record);
	}
	else if($operation == 'add_product')
	{
		
		$name = trim(strtolower($_POST['name']));
				$db->where('LOWER(name)',$name,'like' );
				$product = $db->getOne('product');
				if(!$product)
				{
					
					$data = Array ("name" =>$_POST['name'],
								   "category_id" => $_POST['category'],
								   "brand_id" =>  $_POST['brand'],
								   "description" => 'description',
								   "buying_price" => $_POST['buy_price'],
								   "selling_price" =>  $_POST['sell_price'],
								   "quantity" => $_POST['quantity']
					);
					$id = $db->insert ('product', $data);
					
					$prod_name = trim(strtolower($_POST['name']));
					$db->where('LOWER(name)',$prod_name,'like' );
					$product = $db->getOne('product');
					
					$prod_batch = Array ("product_id" => $product['id'],
										"buying_price" =>$_POST['buy_price'],
										"selling_price" =>  $_POST['sell_price'],
										"quantity" => $_POST['quantity'],
										"import_quantity" => $_POST['quantity']
					);
					$id = $db->insert ('product_batch', $prod_batch);
					
				}
				else{	
					
					$data = Array (
							'selling_price' => $_POST['sell_price'],
							'buying_price' =>$_POST['buy_price'],
							'quantity' => $db->inc($_POST['quantity'])
						);
					$db->where ('id', $product['id']);
					if ($db->update ('product', $data))
					{
						$prod_batch = Array ("product_id" => $product['id'],
									"buying_price" => $_POST['buy_price'],
									"selling_price" => $_POST['sell_price'],
									"quantity" => $_POST['quantity'],
									"import_quantity" => $_POST['quantity']
								);
						$id = $db->insert ('product_batch', $prod_batch);

					}
					else
						echo 'update failed: ' . $db->getLastError();
				
				}
				
				
		// $record = Array ("name" => $_POST['name'],
						// "category_id" => $_POST['category'],
						// "brand_id" => $_POST['brand'],
						// "buying_price" => $_POST['buy_price'],
						// "selling_price" => $_POST['sell_price'],
						// "quantity" => $_POST['quantity']
						
		// );

		// insert_sql('product',$record);
	}	
	
	


?>