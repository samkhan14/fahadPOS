<?php session_start();
include("api/connection.php");
date_default_timezone_set("Asia/Karachi");

if (isset($_GET['logout'])) {

    if (session_destroy()) {
        header("Location: login.php");
    }
}

if (!isset($_SESSION['user']))
    header("Location:login.php");


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>POS</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet"/>


    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>


    <!-- DataTable -->
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet"/>

</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-background-color="white" data-active-color="danger">

        <div class="sidebar-wrapper">
            <!--<div class="logo">
                <a href="#" class="simple-text">
                    POS
                </a>
            </div>-->
            <div class="row" style="margin-top:10px;">
                <div class="col-md-8 col-md-offset-1">
                    <div class="form-group">
                        <label>Customer</label>
                        <select multiple="multiple" id="customer-search" class="form-control border-input">

                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-primary" style="margin-top: 20px;" data-toggle="modal"
                       data-target="#CustomerModal"><i class="fa fa-plus"></i></a>
                </div>
            </div>

            <div class="row cust-mileage" style="margin-bottom:10px; display:none; ">
                <div class="col-md-4 col-md-offset-1">
                    <label>Previous Mileage</label>
                    <input type="text" id="prev_mileage" class="form-control border-input" disabled>
                </div>
                <div class="col-md-4">
                    <label>Current Mileage</label>
                    <input type="text" class="form-control border-input" id="current_mileage">
                    <input type="text" id="current_cust" style="display: none">
                </div>
                <div class="col-md-2">
                    <a href='#' style="margin-top: 25px;" onclick="openInNewTab()"
                       class="btn btn-info btn-fill b_padding"><i class="fa fa-file-text"></i></a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr style="border-top: 2px solid #eaeaea;">
                        <th></th>
                        <th class="text-center">Product</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Total</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                    <tr>

                        <td class="text-right" colspan="4"><h4>Discount :</h4></td>
                        <!--min="0" oninput="validity.valid||(value='');"-->
                        <td colspan="2"><input type="number" class="discount-amount form-control border-input"
                                               id="discount-amount" style="width: 100px; text-align: right"
                                               placeholder="" value="0"></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                        </td>
                        <td class="text-center"><h4>TOTAL :</h4></td>
                        <td class="text-center"><h4 id="total">000.00</h4></td>
                    </tr>
                </table>
            </div>
            <div class="text-center">
                <a class="btn btn-danger" id="clear_btn">CANCEL ORDER</a>
                <a class="btn btn-success">CONFIRM ORDER</a>
            </div>
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
                    <a class="navbar-brand" href="#"><?= date("d F, Y h:i A"); ?></a>

                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a id="btn-payment" data-toggle="modal" data-target="#PaymentModal">
                                <i class="ti-list"></i>
                                <p>Payment</p>
                            </a>
                        </li>
                        <li>
                            <a id="btn-expense" data-toggle="modal" data-target="#ExpenseModal">
                                <i class="ti-list"></i>
                                <p>Expense</p>
                            </a>
                        </li>

                        <li>
                            <a id="total-cash" data-toggle="modal" data-target="#CashModal">
                                <i class="ti-wallet"></i>
                                <p>Cash</p>
                            </a>
                        </li>
                        <li>
                            <a href="orders.php">
                                <i class="ti-file"></i>
                                <p>Orders</p>
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


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" id="search" class="form-control border-input"
                                                   placeholder="Search Product Name, Product Code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="searchbox">

                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="CustomerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Customer</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" name="name" class="form-control border-input" id="cust_name"
                                       placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Car Number</label>
                                <input type="text" name="car-no" class="form-control border-input" id="cust_carno"
                                       placeholder="" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control border-input" id="cust_phone"
                                       placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" name="email" class="form-control border-input" id="cust_email"
                                       placeholder="" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mileage</label>
                                <input type="text" name="mileage" class="form-control border-input" id="cust_mileage"
                                       placeholder="" value="">
                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="customer-add">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ExpenseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Expense</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control border-input" id="expense_name"
                                       placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="amount" class="form-control border-input" id="expense_amount"
                                       placeholder="" value="">
                            </div>
                        </div>
                    </div>


                    <div class="clearfix"></div>
                </form>
                <div class="table-responsive table-full-width" style="margin: 10px">
                    <h5>Today Expenses</h5>
                    <table id="expense_table" class="table table-bordered table-striped table-responsive"
                           style="width: 100% !important;">
                        <thead>
                        <th>Name</th>
                        <th>Amount</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="expense-add">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="PaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Payment</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="payment_name" class="form-control border-input"
                                       id="payment_name" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="payment_amount" class="form-control border-input"
                                       id="payment_amount" placeholder="" value="">
                            </div>
                        </div>
                    </div>


                    <div class="clearfix"></div>
                </form>
                <div class="table-responsive table-full-width" style="margin: 10px">
                    <h5>Today Payments</h5>
                    <table id="payment_table" class="table table-bordered table-striped table-responsive"
                           style="width: 100% !important;">
                        <thead>
                        <th>Name</th>
                        <th>Amount</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="payment-add">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="CashModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Cash Summary</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cash Collected</label>
                                <input type="text" name="name" class="form-control border-input" id="cash_collected"
                                       style="background-color: #fffcf5 !important;cursor: auto !important;color: #66615b;"
                                       disabled="disabled"
                                       placeholder="" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Expense</label>
                                <input type="text" name="name" class="form-control border-input" id="expense_display"
                                       style="background-color: #fffcf5 !important;cursor: auto !important;color: #66615b;"
                                       disabled="disabled"
                                       placeholder="" value="0">
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cash Available</label>
                                <input type="text" name="name" class="form-control border-input" id="cash_display"
                                       style="background-color: #fffcf5 !important;cursor: auto !important;color: #66615b;"
                                       disabled="disabled"
                                       placeholder="" value="0">
                            </div>
                        </div>

                    </div>


                    <div class="clearfix"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

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

<!--  Google Maps Plugin    -->
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

<!-- Paper Dashboard Core javascript and methods for Demo purpose -->
<script src="assets/js/paper-dashboard.js"></script>

<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<!-- DataTable -->
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


<script type="text/javascript">

    function openInNewTab() {
        var cust = $('#current_cust').val();
        var url = "customer_history.php?cust_id=" + cust;
        window.open(url, '_blank').focus();
    }

    function SendPrint(orderID) {
        myWindow = window.open('<?= $url?>bill.php?id=' + orderID, '_blank');
        myWindow.focus();
        myWindow.onload = function () {
            myWindow.print();
            //myWindow.close();
        };

        setTimeout(function () {
            //myWindow.print();
            myWindow.close();
        }, 3000);

    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>

<script type="text/javascript">
    /*$(document).ready(function(){

        demo.initChartist();

        $.notify({
            icon: 'ti-gift',
            message: "Welcome to <b>Paper Dashboard</b> - a beautiful Bootstrap freebie for your next project."

        },{
            type: 'success',
            timer: 4000
        });

    });*/
</script>
<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('click', '#customer-add', function () {
            //alert(document.getElementById("brand-name").value);
            $.post("api/index.php", {
                operation: "add_customer", name: $("#cust_name").val(),
                car_no: $("#cust_carno").val(),
                email: $("#cust_email").val(),
                phone: $("#cust_phone").val(),
                mileage: $("#cust_mileage").val()
            })
                .done(function (data) {
                    $('#CustomerModal').modal('hide');
                });


        });

        $(document).on('click', '#expense-add', function () {
            //alert(document.getElementById("brand-name").value);
            $.post("api/index.php", {
                operation: "add_expense", name: $("#expense_name").val(),
                amount: $("#expense_amount").val()
            })
                .done(function (data) {
                    $("#expense_name").val("");
                    $("#expense_amount").val("");
                    $('#ExpenseModal').modal('hide');
                });


        });

        $(document).on('click', '#payment-add', function () {
            //alert(document.getElementById("brand-name").value);
            $.post("api/index.php", {
                operation: "add_payment", name: $("#payment_name").val(),
                amount: $("#payment_amount").val()
            })
                .done(function (data) {
                    $("#payment_name").val("");
                    $("#payment_amount").val("");
                    $('#PaymentModal').modal('hide');
                });


        });

        $(document).on('click', '#total-cash', function () {

            $.post("api/index.php", {
                operation: "get_total_cash"
            })
                .done(function (data) {
                    data = $.parseJSON(data);
                    if (data[1]['total_sale'] > 0)
                        $("#cash_collected").val(numberWithCommas(data[1]['total_sale']));
                    else
                        $("#cash_collected").val("000.00");
                    if (data[0]['total_expense'] > 0)
                        $("#expense_display").val(numberWithCommas(data[0]['total_expense']));
                    else
                        $("#expense_display").val("000.00");
                    if (data[1]['total_sale'] > 0)
                        $("#cash_display").val(numberWithCommas(data[1]['total_sale'] - data[0]['total_expense']));
                    else
                        $("#cash_display").val("000.00");

                });


        });
    });
</script>
<script type="text/javascript">

    var cart_items = [];
    var cust_list = [];
    $(document).ready(function () {

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
                allowClear: true,
                processResults: function (data) {

                    cust_list = data;

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id,
                            }
                        })
                    };
                },
                cache: true
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });

        $("#customer-search").on("change", function (e) {

            id = $("#customer-search").val();

            if (id == null) {

                $("#prev_mileage").val("");
                $("#current_mileage").val("");
                $("#current_cust").val("");
                $(".cust-mileage").slideUp();
            } else {
                var user;
                cust_list.forEach((list, index) => {

                    if (list.id == id[0]) {
                        user = cust_list[index];
                    }
                });

                // console.log('user mileage: ', user.mileage);
                $(".cust-mileage").slideDown();
                $("#prev_mileage").val(user.mileage);
                $("#current_cust").val(user.id);
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

        $(document).on('click', '.btn-sm', function () {


            if ((parseInt($("#total").html()) - ($(this).attr("data-price") * $(this).attr("data-quantity"))) == 0)
                $("#total").html('000.00');
            else
                $("#total").html(parseInt($("#total").html()) - ($(this).attr("data-price") * $(this).attr("data-quantity")));
            //alert(parseInt($("#total").html()));
            if ((parseInt($("#total").html()) + parseInt($("#discount-amount").val())) == 0) {
                $("#total").html('000.00');
                $("#discount-amount").val("0");
            }
            item_id = parseInt($(this).attr("data-id"));

            index = cart_items.indexOf(item_id);

            cart_items.splice(index, 1);


            $(this).parent().parent().html("");
            //$("#total").html((parseInt($("#total").html())+(quantity*$(this).attr("data-price"))));

        });

        $(document).on('click', '#clear_btn', function () {


            $("#total").html('000.00');
            $("#tbody").html("");
            //alert($(this).attr("X"));
            $("#discount-amount").val("0");
            $("#customer-search").val(null).trigger('change');
            $("#search").val("").trigger("keyup");
            cart_items = [];
        });

        function update_total() {

            $("#total").html('0');
            var total;
            $(".btn-sm").each(function (index) {

                total = Number($("#total").html()) + Number($(this).attr("data-price") * $(this).attr("data-quantity"));

                $("#total").html(total.toFixed(2));
            });

            if (total > 0) {

                var total = Number($("#total").html()) - $('#discount-amount').val();
                $("#total").html(total.toFixed(2));
            }
        }

        $(document).on('click', '.search-item', function () {

            if (cart_items.indexOf(parseInt($(this).attr("data-id"))) < 0) {

                if ($(this).attr("data-quantity") > 0) {
                    var quantity = 1;
                    var name;
                    if ($(this).attr("data-name").length > 10)
                        name = $(this).attr("data-name").substr(0, 10) + "...";
                    else
                        name = $(this).attr("data-name");


                    tr = '<tr><td><a href="#" class="btn btn-sm btn-danger" data-quantity="' + quantity + '" data-name="' + $(this).attr("data-name") + '" data-price="' + $(this).attr("data-price") + '" data-id="' + $(this).attr("data-id") + '">X</a></td><td>' + name + '</td><td class="text-center" >' + Number($(this).attr("data-price")).toFixed(2) + '</td><td class="text-center"><b><input type="number" class="item-quantity form-control border-input" min="0" step="1" placeholder="" value="1"></b></td><td class="text-center item-mult-quantity" id="" >' + Number(quantity * $(this).attr("data-price")).toFixed(2) + '</td></tr>';
                    $("#tbody").append(tr);

                    cart_items.push(parseInt($(this).attr("data-id")));

                    //$("#total").html((parseInt($("#total").html())+(quantity*$(this).attr("data-price"))));
                    update_total();

                } else {
                    alert("Not Available");
                }
                //alert($(this).attr("data-id"));
            }

        });

        $(document).on('keyup change', '.item-quantity', function () {

            var row = $(this).parent().parent().parent();

            quantity = $(this).val();

            row.find(".btn-sm").attr("data-quantity", quantity);

            price = parseInt(row.find(".btn-sm").attr("data-price"));
            item_total = price * quantity;
            row.find(".item-mult-quantity").html(item_total.toFixed(2));

            update_total();
        });

        $(document).on('keyup change', '#discount-amount', function () {
            update_total();

        });


        $("#search").keyup(function () {

            $.get("api/", {name: $(this).val(), operation: "search_product"})
                .done(function (data) {
                    data = $.parseJSON(data);

                    $("#searchbox").html("");

                    div = '<div class="row">';

                    data.forEach((row, index) => {

                        div += `<div class="col-lg-4 col-sm-6">
                           <div class="card"> 
                              <a data-quantity="${row.quantity}" data-name="${row.name}" data-price="${row.selling_price}" data-id="${row.id}" class="search-item" href="#">
                                 <div class="content">
                                    <div class="row">
                                       <div class="col-xs-12">
                                          <p class="text-center"> ${row.name} </p>
                                          <div class="row" style="color: #333;">
                                             <div class="col-md-6 text-left">
                                                <i class="fa fa-money"></i>${(row.selling_price).toFixed(2)} 
                                             </div>
                                             <div class="col-md-6 text-right">
                                                <i class="fa fa-cubes"></i>${row.quantity}
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </a>
                           </div>
                        </div>
                  `;

                        if ((index + 1) % 3 == 0) {

                            div += '</div><div class="row">';
                        }
                    });

                    div += '</div>';
                    $("#searchbox").append(div);

                });
        });


        $(document).on('click', '.btn-success', function () {
            $('.btn-success').attr("disabled", "disabled");
            if ($(".btn-sm").length == 0) {

                alert("Order Empty!");
                $('.btn-success').removeAttr("disabled");

            } else if ($("#customer-search").val() == null) {
                alert("No Customer Selected!");
                $('.btn-success').removeAttr("disabled");
            } else {

                var products = [];
                $(".btn-sm").each(function (index) {

                    var item = [];

                    item [0] = $(this).attr("data-id");
                    item [1] = $(this).attr("data-quantity");

                    products.push(item);
                });

                //alert($("#customer-search").val());
                var cust_id = parseInt($("#customer-search").val());
                var discount = $("#discount-amount").val();
                var current_mileage = parseInt($("#current_mileage").val());


                $.post("api/", {operation: "update_mileage", customer_id: cust_id, mileage: current_mileage})
                    .done();

                $.post("api/", {
                    operation: "place_order",
                    customer_id: cust_id,
                    status: "1",
                    order_products: products,
                    discount: discount
                })
                    .done(function (data) {

                        $("#total").html('000.00');
                        $("#tbody").html("");
                        $("#discount-amount").val("0");

                        $("#prev_mileage").val("");
                        $("#current_mileage").val("");
                        $("#current_cust").val("");
                        $(".cust-mileage").hide();

                        //alert(data );
                        SendPrint(data);
                        $("#customer-search").val(null).trigger('change');
                        $("#search").val("").trigger("keyup");

                        cart_items = [];

                        $('.btn-success').removeAttr("disabled");

                    });
            }

//				console.log(products);
        });

        $(document).on('click', '#btn-payment', function () {

            console.log("Botton clicked");
            $('#payment_table').DataTable({
                "processing": true,
                "serverSide": true,
                "bDestroy": true,
                "order": [
                    [0, 'desc']
                ],
                "ajax": {
                    "url": "api/index.php?operation=get_today_vendor_payments"
                },
                "draw": "draw",
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                "recordsTotal": "recordsTotal",
                "recordsFiltered": "recordsFiltered",
                "columns": [
                    {
                        "data": "name"
                    },
                    {
                        "data": "amount"
                    },
                ],
            });

        });

        $(document).on('click', '#btn-expense', function () {

            $('#expense_table').DataTable({
                "processing": true,
                "serverSide": true,
                "bDestroy": true,
                "order": [
                    [0, 'desc']
                ],
                "ajax": {
                    "url": "api/index.php?operation=get_today_expenses"
                },
                "draw": "draw",
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                "recordsTotal": "recordsTotal",
                "recordsFiltered": "recordsFiltered",
                "columns": [
                    {
                        "data": "name"
                    },
                    {
                        "data": "amount"
                    },
                ],
            });

        });


    });
</script>

</html>
