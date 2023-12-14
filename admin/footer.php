<footer class="footer">
    <div class="container-fluid">
        <!-- <nav class="pull-left"> -->
        <!-- <ul> -->

        <!-- <li> -->
        <!-- <a href="http://www.creative-tim.com"> -->
        <!-- Creative Tim -->
        <!-- </a> -->
        <!-- </li> -->
        <!-- <li> -->
        <!-- <a href="http://blog.creative-tim.com"> -->
        <!-- Blog -->
        <!-- </a> -->
        <!-- </li> -->
        <!-- <li> -->
        <!-- <a href="http://www.creative-tim.com/license"> -->
        <!-- Licenses -->
        <!-- </a> -->
        <!-- </li> -->
        <!-- </ul> -->
        <!-- </nav> -->
    </div>
</footer>


</div>
</div>


</body>

<!--   Core JS Files   -->
<script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

<!--  Checkbox, Radio & Switch Plugins -->
<script src="assets/js/bootstrap-checkbox-radio.js"></script>

<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>


<!--  Notifications Plugin    -->
<!--<script src="assets/js/bootstrap-notify.js"></script>-->

<!--  Google Maps Plugin    -->
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>-->

<!-- Paper Dashboard Core javascript and methods for Demo purpose -->
<script src="assets/js/paper-dashboard.js"></script>

<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>

<!-- DataTable -->
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


<?php if (basename($_SERVER['PHP_SELF']) == 'order.php') { ?>
    <script>
        var tbl;
        $(document).ready(function () {

            tbl = $('#ot1').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [
                    [0, 'desc']
                ],
                "ajax": {
                    "url": "<?= $url ?>/api.php?operation=get_orders"
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
                        "data": "discount"
                    },
                    {
                        "data": "order_total"
                    },
                    {
                        "data": "action"
                    },
                ],

            });

            $("#ot1 tbody").on("click", "td .btn-del", function () {

                if (confirm("Are you sure you want to delete this order?")) {

                    id = $(this).attr("data-id");

                    $.get("<?= $url ?>admin/api.php?operation=del_order&order_id=" + id, function (data) {

                        msg = $.parseJSON(data);
                        alert(msg);
                        tbl.ajax.reload();
                    });
                }
            });

        });
    </script>
<?php } ?>


<?php if (basename($_SERVER['PHP_SELF']) == 'products.php') { ?>

    <script>

        var tbl;
        $(document).ready(function () {

            tbl = $('#prod_table').DataTable({
                "processing": true,
                "serverSide": true,
                // "order": [
                //    [0, 'desc']
                // ],
                "ajax": {
                    "url": "<?= $url ?>admin/api.php?operation=get_products"
                },
                "draw": "draw",
                "recordsTotal": "recordsTotal",
                "recordsFiltered": "recordsFiltered",
                "columns": [
                    {
                        "data": "product_name"
                    },
                    {
                        "data": "category"
                    },
                    {
                        "data": "brand"
                    },
                    {
                        "data": "selling_price"
                    },
                    {
                        "data": "buying_price"
                    },
                    {
                        "data": "quantity"
                    },
                    {
                        "data": "action"
                    },
                ],
            });

            $("#prod_table tbody").on("click", "td .btn-del", function () {

                if (confirm("Are you sure you want to delete this product?")) {

                    id = $(this).attr("data-id");

                    $.get("<?= $url ?>admin/api.php?operation=del_product&product_id=" + id, function (data) {

                        msg = $.parseJSON(data);

                        alert(msg);
                        tbl.ajax.reload();
                    });
                }
            });
        });

    </script>

<?php } ?>

<?php if (basename($_SERVER['PHP_SELF']) == 'customers.php') { ?>

    <script>

        var tbl;
        $(document).ready(function () {

            tbl = $('#cust_table').DataTable({
                "processing": true,
                "serverSide": true,
                // "order": [
                //    [0, 'desc']
                // ],
                "ajax": {
                    "url": "<?= $url ?>admin/api.php?operation=get_customers"
                },
                "draw": "draw",
                "recordsTotal": "recordsTotal",
                "recordsFiltered": "recordsFiltered",
                "columns": [
                    {
                        "data": "customer_id"
                    },
                    {
                        "data": "customer_name"
                    },
                    {
                        "data": "car_numbber"
                    },
                    {
                        "data": "mileage"
                    },
                    {
                        "data": "phone"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "action"
                    },
                ],
            });

            $("#cust_table tbody").on("click", "td .btn-del", function () {

                if (confirm("Are you sure you want to delete this product?")) {

                    id = $(this).attr("data-id");

                    $.get("<?= $url ?>admin/api.php?operation=del_customer&cust_id=" + id, function (data) {

                        msg = $.parseJSON(data);

                        alert(msg);
                        tbl.ajax.reload();
                    });
                }
            });
        });

    </script>
<?php } ?>

<?php if (basename($_SERVER['PHP_SELF']) == 'categories.php') { ?>
    <script>

        var tbl;
        $(document).ready(function () {

            tbl = $('#catg_table').DataTable({
                "processing": true,
                "serverSide": true,
                // "order": [
                //    [0, 'desc']
                // ],
                "ajax": {
                    "url": "<?= $url ?>admin/api.php?operation=get_categories"
                },
                "draw": "draw",
                "recordsTotal": "recordsTotal",
                "recordsFiltered": "recordsFiltered",
                "columns": [
                    {
                        "data": "category_id"
                    },
                    {
                        "data": "category_name"
                    },
                    {
                        "data": "action"
                    },
                ],
            });

            $("#catg_table tbody").on("click", "td .btn-del", function () {

                if (confirm("Are you sure you want to delete this category?")) {

                    id = $(this).attr("data-id");

                    $.get("<?= $url ?>admin/api.php?operation=del_category&cat_id=" + id, function (data) {

                        msg = $.parseJSON(data);
                        alert(msg);
                        tbl.ajax.reload();
                    });
                }
            });
        });

    </script>

<?php } ?>

<?php if (basename($_SERVER['PHP_SELF']) == 'brands.php') { ?>
    <script>

        var tbl;
        $(document).ready(function () {

            tbl = $('#brand_table').DataTable({
                "processing": true,
                "serverSide": true,
                // "order": [
                //    [0, 'desc']
                // ],
                "ajax": {
                    "url": "<?= $url ?>admin/api.php?operation=get_brands"
                },
                "draw": "draw",
                "recordsTotal": "recordsTotal",
                "recordsFiltered": "recordsFiltered",
                "columns": [
                    {
                        "data": "brand_id"
                    },
                    {
                        "data": "brand_name"
                    },
                    {
                        "data": "action"
                    },
                ],
            });

            $("#brand_table tbody").on("click", "td .btn-del", function () {

                if (confirm("Are you sure you want to delete this category?")) {

                    id = $(this).attr("data-id");

                    $.get("<?= $url ?>admin/api.php?operation=del_brand&brand_id=" + id, function (data) {

                        msg = $.parseJSON(data);
                        alert(msg);
                        tbl.ajax.reload();
                    });
                }
            });
        });

    </script>
<?php } ?>

<?php if (basename($_SERVER['PHP_SELF']) == 'expenses.php') { ?>
    <script>

        var tbl;
        $(document).ready(function () {

            tbl = $('#exp_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [
                    [0, 'desc']
                ],
                "ajax": {
                    "url": "<?= $url ?>admin/api.php?operation=get_expenses"
                },
                "draw": "draw",
                "recordsTotal": "recordsTotal",
                "recordsFiltered": "recordsFiltered",
                "columns": [
                    {
                        "data": "expense_id"
                    },
                    {
                        "data": "expense_date"
                    },
                    {
                        "data": "expense_name"
                    },
                    {
                        "data": "expense_amount"
                    },
                    {
                        "data": "action"
                    },
                ],
            });

            $("#exp_table tbody").on("click", "td .btn-del", function () {

                if (confirm("Are you sure you want to delete this record?")) {

                    id = $(this).attr("data-id");

                    $.get("<?= $url ?>admin/api.php?operation=del_expense&exp_id=" + id, function (data) {

                        msg = $.parseJSON(data);
                        alert(msg);
                        tbl.ajax.reload();
                    });
                }
            });
        });

    </script>
<?php } ?>

<?php if (basename($_SERVER['PHP_SELF']) == 'vendor_payments.php') { ?>
    <script>

        var tbl;
        $(document).ready(function () {

            tbl = $('#payment_table').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [
                    [0, 'desc']
                ],
                "ajax": {
                    "url": "<?= $url ?>admin/api.php?operation=get_vendor_payments"
                },
                "draw": "draw",
                "recordsTotal": "recordsTotal",
                "recordsFiltered": "recordsFiltered",
                "columns": [
                    {
                        "data": "id"
                    },
                    {
                        "data": "date"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "amount"
                    },
                    {
                        "data": "action"
                    },
                ],
            });

            $("#payment_table tbody").on("click", "td .btn-del", function () {

                if (confirm("Are you sure you want to delete this record?")) {

                    id = $(this).attr("data-id");

                    $.get("<?= $url ?>admin/api.php?operation=del_vendor_payment&pay_id=" + id, function (data) {

                        msg = $.parseJSON(data);
                        alert(msg);
                        tbl.ajax.reload();
                    });
                }
            });
        });

    </script>
<?php } ?>

<?php if (basename($_SERVER['PHP_SELF']) == 'summary.php' || basename($_SERVER['PHP_SELF']) == 'daily_report.php') { ?>

    <script>

        $(document).ready(function () {
            $('.table-summary').DataTable({

                "columnDefs": [{
                    "type": "html-num",
                    "targets": 0
                }]

            });

        });

        $(document).ready(function () {
            $('.table').DataTable({
                "order": [[0, "desc"]],

            });

        });
    </script>
<?php } ?>


<?php if (basename($_SERVER['PHP_SELF']) == 'products_stock.php') { ?>

    <script>

        var tbl;
        $(document).ready(function () {

            $("#stock_table tbody").on("click", "td .btn-del", function () {

                if (confirm("Are you sure you want to delete this batch?")) {

                    id = $(this).attr("data-id");

                    $.get("<?= $url ?>admin/api.php?operation=del_batch&batch_id=" + id, function (data) {

                        msg = $.parseJSON(data);

                        alert(msg);
                        location.reload(true);
                    });
                }
            });
        });

    </script>

<?php } ?>


<script type="text/javascript">
    $(document).ready(function () {
        $("#process-import").on("click", function () {
            $(this).hide();
        })
    });
</script>

</html>