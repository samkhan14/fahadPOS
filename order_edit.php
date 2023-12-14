<?php session_start();

date_default_timezone_set("Asia/Karachi");
include("api/connection.php");


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

    <?php
    if (isset($_GET['edit']) && isset($_GET['product_id'])) {

        // var_dump($discount);
        // exit();
        $db->where("order_id", $_GET['edit']);
        $db->where("product_id", $_GET['product_id']);
        $products = $db->get("order_product");

        foreach ($products as $row) {
            $updated_quantity = Array(
                'quantity' => $db->inc($row['quantity'])
            );

            $db->where('id', $row['product_id']);
            if ($db->update('product', $updated_quantity)) {
                $db->where('id', $row['batch_id']);
                $product_batch = $db->getone('product_batch');

                if ($product_batch != null) {
                    $prod_data = Array(
                        'quantity' => ($product_batch['quantity'] + $row['quantity'])
                    );
                    $db->where('id', $product_batch['id']);
                    if ($db->update('product_batch', $prod_data)) {
                        $db->where('id', $row['id']);
                        $db->delete('order_product');
                    }
                } else {
                    echo 'batch not found ';
                }
            } else
                echo 'Refund failed: ' . $db->getLastError();
        }
        $total         = $db->rawQueryOne('SELECT SUM(unit_price * quantity) as value FROM order_product WHERE order_id = ' . $_GET['edit'] . '');
        $discount      = $db->rawQueryOne('SELECT discount FROM orders where id = ' . $_GET['edit'] . '');
        $updated_total = Array(
            'total' => ($total['value'] - $discount['discount'])

        );
        $db->where('id', $_GET['edit']);
        $db->update('orders', $updated_total);
    }

    if (isset($_GET['edit'])) {
        $db->where("id", $_GET['edit']);
        $user = $db->getOne("orders");

    }
    ?>
    <div class="container">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Order
                                    ID: <?= $_GET['edit'] ?> <?= ($user['status'] == 2) ? ' <span class="badge badge-primary" style="background-color: #eb5e28;font-size: 16px; margin-bottom: 4px;">Refunded</span>' : '' ?></h4>
                            </div>
                            <div class="content">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Customer Name</label>
                                                <input type="text" class="form-control border-input"
                                                       style="background-color: #fffcf5 !important;cursor: auto !important;color: #66615b;" disabled="disabled"
                                                       placeholder="" value="<?php
                                                $db->where("id", $user['customer_id']);
                                                $customer = $db->getOne("customer");
                                                echo $customer['name']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Order Time</label>
                                                <input type="text" disabled="disabled" class="form-control border-input"
                                                       style="background-color: #fffcf5 !important;cursor: auto !important;color: #66615b;"
                                                       placeholder=""
                                                       value="<?php echo date("d-m-Y h:i", strtotime($user['time'])); ?>">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Order Total</label>
                                                <input type="text" disabled="disabled" class="form-control border-input"
                                                       style="background-color: #fffcf5 !important;cursor: auto !important;color: #66615b;"
                                                       placeholder=""
                                                       value="<?php echo number_format(($user['total'] + $user['discount']), 2, '.', ','); ?>">
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Discount</label>
                                                <input type="text" disabled="disabled" class="form-control border-input"
                                                       style="background-color: #fffcf5 !important;cursor: auto !important;color: #66615b;"
                                                       placeholder=""
                                                       value="<?php echo number_format($user['discount'], 2, '.', ','); ?>">
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Cash Collected</label>
                                                <input type="text" disabled="disabled" class="form-control border-input"
                                                       style="background-color: #fffcf5 !important;cursor: auto !important;color: #66615b;"
                                                       placeholder=""
                                                       value="<?php echo number_format($user['total'], 2, '.', ','); ?>">
                                            </div>

                                        </div>


                                    </div>


                                    <div class="clearfix"></div>
                                </form>

                                <div class="table-responsive table-full-width">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <th>Product</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <?php if ($user['status'] != 2) { ?>
                                            <th>Refund</th>
                                        <?php } ?>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $items = $db->rawQuery('SELECT product_id, unit_price , SUM(quantity) as quantity FROM order_product WHERE order_id = ' . $_GET['edit'] . ' GROUP BY product_id');

                                        foreach ($items as $row) {
                                            ?>

                                            <tr>
                                                <td>
                                                    <?php
                                                    $db->where("id", $row['product_id']);
                                                    $product = $db->getOne("product");
                                                    echo $product['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo number_format($row['unit_price'], 2, '.', ','); ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo $row['quantity']; ?>
                                                </td>
                                                <td style="text-align:right">
                                                    <?php
                                                    echo number_format($row['quantity'] * $row['unit_price'], 2, '.', ','); ?>
                                                </td>
                                                <?php if ($user['status'] != 2) { ?>
                                                    <td>
                                                        <a onclick="return confirm('Are you sure you want to refund this item?');"
                                                           href="order_edit.php?edit=<?= $_GET['edit'] ?>&product_id=<?= $row['product_id'] ?>"
                                                           class="btn btn-danger btn-fill b_padding"><i
                                                                    class="fa fa-undo"></i></a>
                                                    </td>
                                                <?php } ?>
                                            </tr>

                                        <?php } ?>

                                        </tbody>
                                    </table>

                                </div>


                                <div class="text-right">
                                    <?php if ($user['status'] != 2) { ?>
                                        <a id="refund" class="btn btn-info btn-fill btn-wd">Refund</a>
                                    <?php } ?>
                                    <a href="orders.php" class="btn btn-info btn-fill btn-wd">Back</a>
                                </div>

                            </div>
                        </div>
                    </div>


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

    });
</script>
<script type="text/javascript">

    var cart_items = [];
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

        $(document).on('click', '.btn-sm', function () {


            if ((parseInt($("#total").html()) - ($(this).attr("data-price") * $(this).attr("data-quantity"))) == 0)
                $("#total").html('000.00');
            else
                $("#total").html(parseInt($("#total").html()) - ($(this).attr("data-price") * $(this).attr("data-quantity")));


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
            $("#customer-search").val(null).trigger('change');
            $("#search").val("").trigger("keyup");
            cart_items = [];
        });

        $(document).on('click', '#refund', function () {

            if (confirm('Are you sure you want to refund this order?')) {
                $.post("api/", {operation: "delete_order", id: <?php echo $_GET['edit'];?>  })
                    .done(function (data) {

                        alert(data);
                        window.location.replace('orders.php');
                    });
            }


        });

        function update_total() {

            $("#total").html('0');

            $(".btn-sm").each(function (index) {


                //alert( );
                total = Number($("#total").html()) + Number($(this).attr("data-price") * $(this).attr("data-quantity"));

                $("#total").html(total.toFixed(2));
            });

        }

        $(document).on('click', '.search-item', function () {

            if (cart_items.indexOf(parseInt($(this).attr("data-id"))) < 0) {

                var quantity = 1;


                tr = '<tr><td><a href="#" class="btn btn-sm btn-danger" data-quantity="' + quantity + '" data-name="' + $(this).attr("data-name") + '" data-price="' + $(this).attr("data-price") + '" data-id="' + $(this).attr("data-id") + '">X</a></td><td>' + $(this).attr("data-name") + '</td><td class="text-center" >' + Number($(this).attr("data-price")).toFixed(2) + '</td><td class="text-center"><b><input type="number" class="item-quantity form-control border-input" min="1" step="0.1" placeholder="" value="1"></b></td><td class="text-center item-mult-quantity" id="" >' + Number(quantity * $(this).attr("data-price")).toFixed(2) + '</td></tr>';
                $("#tbody").append(tr);

                cart_items.push(parseInt($(this).attr("data-id")));

                //$("#total").html((parseInt($("#total").html())+(quantity*$(this).attr("data-price"))));
                update_total();
                //alert($(this).attr("data-id"));
            }

        });

        $(document).on('change', '.item-quantity', function () {

            var row = $(this).parent().parent().parent();

            quantity = $(this).val();

            row.find(".btn-sm").attr("data-quantity", quantity);

            price = parseInt(row.find(".btn-sm").attr("data-price"));
            item_total = price * quantity;
            row.find(".item-mult-quantity").html(item_total.toFixed(2));

            update_total();
        });


        $("#search").keyup(function () {

            $.get("api/", {name: $(this).val(), operation: "search_product"})
                .done(function (data) {
                    data = $.parseJSON(data);

                    $("#searchbox").html("");
                    items = data.length;
                    for (var i = 0, len = data.length; i < len; i++) {
                        //console.log(data[i]);

                        div = '<div class="col-lg-3 col-sm-6"><div class="card"><a data-quantity="' + data[i].quantity + '" data-name="' + data[i].name + '" data-price="' + data[i].selling_price + '" data-id="' + data[i].id + '" class="search-item" href="#"><div class="content"><div class="row"><div class="col-xs-12"><p class="text-center">';
                        div += data[i].name;
                        div += '</p><div class="row" style="color: #333;"><div class="col-md-6 text-left"><i class="fa fa-money"></i>' + data[i].selling_price + ' </div><div class="col-md-6 text-right"><i class="fa fa-cubes"></i>' + data[i].quantity + '</div></div></div></div></div></div></a></div>';
                        //alert(div);

                        $("#searchbox").append(div);

                    }
                    //alert(items);
                    //$("#searchbox").find("p").html(data.name);
                    //.val("12")

                });
        });


        $(document).on('click', '.btn-success', function () {
            if ($(".btn-sm").length == 0) {

                alert("Order Empty!");


            } else if ($("#customer-search").val() == null) {
                alert("No Customer Selected!");

            } else {
                var products = [];
                $(".btn-sm").each(function (index) {

                    var item = [];

                    item [0] = $(this).attr("data-id");
                    item [1] = $(this).attr("data-quantity");

                    products.push(item);
                });

                //alert($("#customer-search").val());
                cust_id = parseInt($("#customer-search").val());
                $.post("api/", {operation: "place_order", customer_id: cust_id, status: "1", order_products: products})
                    .done(function (data) {

                        $("#total").html('000.00');
                        $("#tbody").html("");
                        alert(data);
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
