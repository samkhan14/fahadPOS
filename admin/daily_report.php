<?php include("header.php"); ?>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-card">
                            <div class="header">
								<div class="row">
									<div class="col-md-9 col-xs-7">
										<h4 class="title">Daily Summary</h4>
										<p class="category">Total Summary</p>
									</div>
								</div>
                            </div>
							
                            <div class="table-responsive table-full-width">
                                <table class="table-summary table-bordered table-striped">
                                    <thead>
										<th>Date</th>
									    <th>Orders</th>
                                        <th>Sales</th>
                                        <th>Discount</th>
										<th>Expense</th>
										<th>Cash</th>
										<th>Revenue</th>
                                    </thead>
                                    <tbody>
									<?php 
	
										$users = $db->rawQuery('SELECT DATE(orders.time) as date , COUNT(orders.id) as orders , SUM(orders.discount) as discount , SUM(orders.total) as total FROM orders where status = 1 GROUP BY DATE(orders.time) ORDER BY DATE(orders.time) DESC');
                              $rows = 1;
										foreach($users as $row){
										?>

										<tr>
											<td><p hidden> <?= $rows?></p>
												<?php echo $row['date'];?>

											</td>
											<td>
												<?php echo $row['orders']; ?>
											</td>
											<td style="text-align: right;">
												<?php echo number_format($row['discount']+$row['total'], 2 , '.' , ','); ?>
											</td>	
											<td style="text-align: right;">
												<?php echo number_format($row['discount'], 2 , '.' , ','); ?>
											</td>
											<td style="text-align: right;">
												<?php 
													$expense = $db->rawQueryOne('SELECT SUM(amount) as expense FROM expense where DATE(expense.date) = "'.$row['date'].'"');
													echo number_format($expense['expense'], 2 , '.' , ','); ?>
											</td>
											<td style="text-align: right;">
												<?php echo number_format($row['total']-$expense['expense'], 2 , '.' , ','); ?>
											</td>
										
											<td style="text-align: right;">
												<?php 
													
													$buying = $db->rawQueryOne('SELECT SUM(order_product.quantity * product_batch.buying_price) as buying FROM orders JOIN order_product ON orders.id = order_product.order_id JOIN product_batch ON order_product.batch_id = product_batch.id where status = 1 AND DATE(orders.time) = "'.$row['date'].'" GROUP By DATE(orders.time)');	
													echo number_format(($row['total']-$buying['buying'])-$expense['expense'], 2 , '.' , ',');
														
												?>
											</td>											
										</tr>

										<?php $rows+=1; } ?>
                                        
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
    