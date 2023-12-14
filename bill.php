<?php
include("api/connection.php");

if(isset($_GET['id'])){
	$db->where("id",$_GET['id']);
	$order = $db->getOne('orders');

	$db->where("id",$order['customer_id']);
	$customer = $db->getOne('customer');

}
?>
<!doctype html>
<html>
<head>

	<meta charset="utf-8">
	<title>POS Print</title>



</head>
<body style="margin:0;padding:0;">
	 <!--<div class="invoice-box" style="padding-top: 200px; background: url(http://localhost/pos/assets/img/bill_header.jpeg) no-repeat;">-->
	 <div class="invoice-box">
		<img style="margin-left:110px;" src="<?= $url ?>assets/img/bill_header.png" width="500px"/>
        <table style="width: 700px; margin: 0px 20px;">
            <tr>
				<td ><b>BILL # </b><?= $order['id']?></td>
				<td style=" text-align:right"><b>CUSTOMER : </b><?= $customer['name']?></td>
			</tr>
            <tr>

				<td  ><b>DATE : </b><?= date("d/m/Y", strtotime($order['time'])) ?></td>
				<td style=" text-align:right"><b>CAR # : </b><?= $customer['car_no']?></td>
			</tr>

			<tr >
				<td colspan="2">
					<table style="border-collapse: collapse; width:100%; " border="1">
						<tr>
							<th>S.NO</th>
							<th>DESCRIPTION</th>
							<th>RATE</th>
							<th>QYT</th>
							<th>AMOUNT</th>


						</tr>
						<?php
									$products = $db->rawQuery('SELECT product_id, unit_price , SUM(quantity) as quantity FROM order_product WHERE order_id = '.$_GET['id'].' GROUP BY product_id');
									$counter = 1;

									foreach($products as $row){
									?>
						<tr>

									<td style="text-align: center;"><?php echo $counter;
											$counter++;
										?>
									</td>
									<td>
										<?php	$db->where ("id", $row['product_id']);
													$user = $db->getOne ("product");
													echo $user['name'];?>
									</td>
									<td style="text-align: right;"><?= number_format($row['unit_price'], 2 , '.' , ',');?></td>
									<td style="text-align: center;"><?= $row['quantity'];?></td>
									<td style="text-align: right;"><?= number_format($row['unit_price'] *$row['quantity'], 2 , '.' , ',')?></td>
						</tr>

							<?php } ?>

					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2"><p style=" text-align: right; margin-top: 0px; margin-bottom:0px"><b style="padding-right: 34px;">DISCOUNT : </b><span style="width: 90px;float: right;"><?= number_format($order['discount'], 2 , '.' , ',') ?></span></p></td>
			</tr>
			<tr>
				<td colspan="2"><p style=" text-align: right; margin-top: 0px;"><b style="padding-right: 40px;">TOTAL : </b><span style="float: right; width: 85px;"><?= number_format($order['total'], 2 , '.' , ',') ?></span></p></td>

			</tr>
			<tr>
				<td colspan="2" style="text-align:center;font:Arial;">
					<h3 style="margin:0px">0301-2989411 &nbsp;  &nbsp; 0315-8615938 &nbsp;  &nbsp; 0315-8615939</h3>
					<p style="margin-top: 0px;">Shop # 22, 23 & 24, Block 2, VIP City Complex, Gulshan-e-Safia, Sector 11/A, North Karachi.</p>
				</td>
			</tr>
        </table>


    </div>

</body>
</html>