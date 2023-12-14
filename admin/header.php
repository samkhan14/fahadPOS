<?php session_start();

if(isset($_GET['logout'])){
	
	if(session_destroy()) 
	{
		header("Location: login.php"); 
	}
}
if(!isset($_SESSION['admin']))
	header("Location:login.php");

include("../api/connection.php");

	if(isset($_GET['export'])){
		 
		header('Content-Type: text/csv; charset=utf-8');  
		header('Content-Disposition: attachment; filename=products.csv');  
		$output = fopen("php://output", "w");  
		fputcsv($output, array ("Name", "Buying Price", "Selling Price", "Quantity"));  

		$products = $db->rawQuery ("SELECT name, buying_price, selling_price, quantity FROM product");
		
		foreach($products as $row)  
		{  
		   fputcsv($output, $row);  
		}  
		fclose($output);  
		exit();
	}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>POS</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />

    <!--  Fonts and icons     -->
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
	<link href="assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/themify-icons.css" rel="stylesheet">

	<!-- DataTable -->
	<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-background-color="white" data-active-color="danger">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                    POS
                </a>
            </div>

            <ul class="nav">
                <li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'index') !== false) {
									echo 'active';
								}?>">
                    <a href="index.php">
                        <i class="fa fa-tachometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'order') !== false) {
									echo 'active';
								}?>">
                    <a href="order.php">
                        <i class="fa fa-file-text"></i>
                        <p>Orders</p>
                    </a>
                </li>
                <li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'products') !== false) {
									echo 'active';
								}?>">
                    <a href="products.php">
                        <i class="fa fa-archive"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'categories') !== false) {
									echo 'active';
								}?>">
                    <a href="categories.php">
                        <i class="fa fa-th-large"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'brands') !== false) {
									echo 'active';
								}?>">
                    <a href="brands.php">
                        <i class="fa fa-tag"></i>
                        <p>Brands</p>
                    </a>
                </li>
                <li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'customers') !== false) {
									echo 'active';
								}?>">
                    <a href="customers.php">
                        <i class="fa fa-user"></i>
                        <p>Customers</p>
                    </a>
                </li>
				<li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'import') !== false) {
									echo 'active';
								}?>">
                    <a href="import.php">
                        <i class="fa fa-upload"></i>
                        <p>Import</p>
                    </a>
                </li>
				<li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'backup') !== false) {
									echo 'active';
								}?>">
                    <a href="backup.php">
                        <i class="fa fa-download"></i>
                        <p>Backup</p>
                    </a>
                </li>
				<li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'expense') !== false) {
									echo 'active';
								}?>">
                    <a href="expenses.php">
                        <i class="fa fa-money"></i>
                        <p>Expense</p>
                    </a>
                </li>
            	<li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'vendor_payments') !== false) {
								echo 'active';
							}?>">
                    <a href="vendor_payments.php">
                        <i class="fa fa-money"></i>
                        <p>Vendor Payments</p>
                    </a>
                </li>
                <li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'daily_report') !== false) {
									echo 'active';
								}?>">
                    <a href="daily_report.php">
                        <i class="fa fa-bar-chart"></i>
                        <p>Daily Summary</p>
                    </a>
                </li>
				<li class="<?php if (strpos($_SERVER['REQUEST_URI'], 'summary') !== false) {
									echo 'active';
								}?>">
                    <a href="summary.php">
                        <i class="fa fa-bar-chart"></i>
                        <p>Monthly Summary</p>
                    </a>
                </li>
                
				
            </ul>
    	</div>
    </div>

    <div class="main-panel">
		<nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                     <a class="navbar-brand" href="#">Stock Value : <?php $stock = $db->rawQueryOne('SELECT SUM(quantity * buying_price) as value FROM product_batch');
																		echo number_format($stock['value'], 2 , '.' , ',');?></a> 
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="index.php?logout=1">
								<i class="ti-lock"></i>
								<p>Logout</p>
                            </a>
                        </li>
                        
                    </ul>

                </div>
            </div>
        </nav>
		
		