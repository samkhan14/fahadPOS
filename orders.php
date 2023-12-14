<?php session_start();

date_default_timezone_set("Asia/Karachi");
include("api/connection.php");


if(isset($_GET['logout'])){
	
	if(session_destroy()) 
	{
		header("Location: login.php"); 
	}
}
	
if(!isset($_SESSION['user']))
	header("Location:login.php");
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
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">
	
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	
	<!-- DataTable -->
	<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />

</head>
<body>

<div class="wrapper">
   <nav class="navbar navbar-default">
      <div class="container-fluid">
            <div class="navbar-header">
               <button type="button" class="navbar-toggle">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar bar1"></span>
                  <span class="icon-bar bar2"></span>
                  <span class="icon-bar bar3"></span>
               </button>
               <a class="navbar-brand" href="#"><?= date("d F, Y h:i A"); ?></a>
            </div>
            <div class="collapse navbar-collapse">
               <ul class="nav navbar-nav navbar-right">
            <li>
                        <a href="index.php">
                  <i class="ti-arrow-left"></i>
                  <p>Back</p>
                        </a>
                  </li>
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

   <div class="container">
      <div class="content">
         <div class="container-fluid">
            <div class="row">
               
               <div class="col-md-12">
                  <div class="card p-card">
                     <div class="header">
                        <div class="row">
                           <div class="col-md-9 col-xs-7">
                              <h4 class="title">Orders</h4>
                              <p class="category">Complete order list</p>
                           </div>
                        </div>
                     </div>
               
                     <div class="content" style="margin: 20px;">	
                        <div class="row">
                           <div class="col-md-12">
                              <div class="table-responsive table-full-width">
                                 <table id="order_table" class="table table-bordered table-striped data-table">
                                    <thead>
                                       <th>Order ID</th>
                                       <th>Order Time</th>
                                       <th>Customer Name</th>
                                       <th>Car Number</th>
                                       <th>Order Total</th>
                                       <th>Action</th>
                                    </thead>
                                 
                                 </table>

                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </div>

    
</div>


</body>


    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio.js"></script>

	<!--  Charts Plugin -->
	<!--<script src="assets/js/chartist.min.js"></script>-->

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
	<script src="assets/js/paper-dashboard.js"></script>

	<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	
	<!-- DataTable -->
	<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
   

   <script type="text/javascript">

      $(document).ready( function() {

         tbl = $('#order_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [
               [0, 'desc']
            ],
            "ajax": {
               "url": "<?= $url ?>api/index.php?operation=get_orders"
            },
            "draw": "draw",
            "recordsTotal": "recordsTotal",
            "recordsFiltered": "recordsFiltered",
            "columns": [
               {
               "data": "order_id"
               },
               {
               "data": "order_time"
               },
               {
               "data": "customer_name"
               },
                {
                    "data": "car_number"
                },
               {
               "data": "order_total"
               },
               {
               "data": "action"
               },
            ],
         });
      });

   </script>

	<script type="text/javascript">
	
		
		function SendPrint(orderID)
		  {
			myWindow=window.open('<?= $url?>bill.php?id='+orderID,'_blank');
			myWindow.focus();
			myWindow.onload = function() {
			    myWindow.print(); 
				//myWindow.close();
			};
			
			setTimeout(function(){
				//myWindow.print(); 
				myWindow.close();
			}, 3000);

		  }
		
		
		 function numberWithCommas(x){
				return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
	</script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			
			$(document).on('click', '#customer-add', function(){
				//alert(document.getElementById("brand-name").value);
					$.post( "api/index.php", { operation: "add_customer", name: $("#cust_name").val() , 
                  car_no: $("#cust_carno").val(),
                  email:	$("#cust_email").val(),
                  phone: $("#cust_phone").val(),
                  mileage: $("#cust_mileage").val()								
                  })
				.done(function( data ) {
					$('#CustomerModal').modal('hide');
				});

									
				
			});

			$(document).on('click', '.order-print', function(){
				
						SendPrint($(this).attr("data-id"));
				});

									
				
			
		});
	</script>
	<script type="text/javascript">
	
		var cart_items = [];
		$(document).ready(function(){
			
			$("#customer-search").select2({
				ajax: {
					url: 'api/index.php',
					dataType: 'json',
					data: function (params) {
					  return {
						q: params.term,
						operation: 'get_customer'
					  };
					},
					minimumInputLength: 2,
					processResults: function (data) {
						return {
							results: $.map(data, function (item) {
								return {
									text: item.name,
									id: item.id
								}
							})
						};
					},
					cache: true
					// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
				}
			});
			//$(".search-item").click(function(){
				
				//alert($(this).attr("data-id"));
				//$.get( "api/", { name: "Havolin5", operation: "search_product" } )
				  //.done(function( data ) {
					//data = $.parseJSON(data);
					
					//$("#test2").find("p").html(data.name);
					//.val("12")
					
					//alert(data.name);
				  //});
				  //tr = '<tr><td><a href="#" class="btn btn-sm btn-danger">X</a></td><td>Shell 4.5L</td><td class="text-center">1550.00</td><td class="text-center"><b>2</b></td><td class="text-center">3100.00</td></tr>';
				//$(".table tbody").append(tr);
			//});
			
			$(document).on('click', '.btn-sm', function(){
				
				
				if((parseInt($("#total").html()) - ($(this).attr("data-price")*$(this).attr("data-quantity"))) == 0 )
					$("#total").html('000.00');
				else
					$("#total").html(parseInt($("#total").html()) - ($(this).attr("data-price")*$(this).attr("data-quantity")));
				
				
				item_id = parseInt($(this).attr("data-id"));
				
				index = cart_items.indexOf(item_id);
				
				cart_items.splice(index, 1);
				
				
				$(this).parent().parent().html("");
				//$("#total").html((parseInt($("#total").html())+(quantity*$(this).attr("data-price"))));
				
			});
			
			
			
			
			$(document).on('click', '#clear_btn', function(){
				
				
				$("#total").html('000.00');
				$("#tbody").html("");
				//alert($(this).attr("X"));
				$("#customer-search").val(null).trigger('change');
				$("#search").val("").trigger("keyup");
				cart_items = [];
			});
			
			function update_total(){
								
				$("#total").html('0');

				$(".btn-sm").each(function( index ) {
				
				
				//alert( );
				total = Number( $("#total").html())+ Number($(this).attr("data-price")*$(this).attr("data-quantity"));
				
				$("#total").html(total.toFixed(2));
				});
			
			}
			
			
			
			$(document).on('change', '.item-quantity', function(){
				
				var row = $(this).parent().parent().parent();
				
				quantity =$(this).val();
				
				row.find(".btn-sm").attr("data-quantity",quantity);
				
				price = parseInt(row.find(".btn-sm").attr("data-price"));
				item_total = price * quantity;
				row.find(".item-mult-quantity").html(item_total.toFixed(2));
				
				update_total();
			});
			
			
			
			
			
			
			
			$(document).on('click', '.btn-success', function(){
				if($(".btn-sm").length == 0) {
					
						alert("Order Empty!");
						
						
				}
				else if($("#customer-search").val()== null)
				{
					alert("No Customer Selected!");
					
				}
				else{
					var products = [];
					$(".btn-sm").each(function( index ) {
					
					var item = [];
					
					item [0] = $(this).attr("data-id");
					item [1] = $(this).attr("data-quantity");
					  
					products.push(item);
					});
					
					//alert($("#customer-search").val());
					cust_id =parseInt($("#customer-search").val());
					$.post( "api/", { operation: "place_order", customer_id: cust_id , status: "1" , order_products: products  })
					.done(function( data ) {
						
					$("#total").html('000.00');
					$("#tbody").html("");
						alert(data );
						$("#customer-search").val(null).trigger('change');
						$("#search").val("").trigger("keyup");
						cart_items = [];
						
					});
				}

//				console.log(products);
			});
			
			
		});
	</script>

</html>
