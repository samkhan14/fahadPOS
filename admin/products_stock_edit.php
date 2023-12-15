<?php include("header.php"); ?>

<?php
if (isset($_GET['edit'])) {
    $db->where("id", $_GET['edit']);
    $user = $db->getOne("product_batch");
}
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Product Batch <?php echo $_GET['edit'] ?></h4>
                    </div>
                    <div class="content">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Buying Price</label>
                                        <input type="text" class="form-control border-input" id="buying_price" placeholder="" value="<?php echo $user['buying_price']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Selling Price</label>
                                        <input type="text" class="form-control border-input" id="selling_price" placeholder="" value="<?php echo $user['selling_price']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="text" class="form-control border-input" id="quantity" placeholder="" value="<?php echo $user['quantity']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Import Quantity</label>
                                        <input type="text" class="form-control border-input" id="import_quantity" placeholder="" value="<?php echo $user['import_quantity']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="#" class="btn btn-info btn-fill btn-wd" id="btn-save">Save</a>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


<footer class="footer">
    <div class="container-fluid">
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
<script src="assets/js/bootstrap-notify.js"></script>

<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

<!-- Paper Dashboard Core javascript and methods for Demo purpose -->
<script src="assets/js/paper-dashboard.js"></script>

<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>
<!-- sweetalert popup -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '#btn-save', function() {
            if ($("#buying_price").val() == "" || $("#selling_price").val() == "" || $("#quantity").val() == "" || $("#import_quantity").val() == "") {
                // Use SweetAlert for missing fields
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Field',
                    text: 'Please fill in all fields',
                });
            } else if ($("#quantity").val() > $("#import_quantity").val()) {
                // Use SweetAlert for quantity validation
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Quantity',
                    text: 'Quantity cannot be greater than Import Quantity',
                });
            } else if ($("#buying_price").val() > $("#selling_price").val()) {
                // Use SweetAlert for price validation
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Prices',
                    text: 'Buying price cannot be greater than Selling price',
                });
            } else {
                $.post("update.php", {
                        operation: "update_product_stock",
                        id: <?php echo $_GET['edit']; ?>,
                        buying_price: document.getElementById("buying_price").value,
                        selling_price: document.getElementById("selling_price").value,
                        product_id: <?php echo $user['product_id']; ?>,
                        quantity: document.getElementById("quantity").value,
                        import_quantity: document.getElementById("import_quantity").value
                    })
                    .done(function(data) {
                        // Use SweetAlert for success
                        Swal.fire({
                            icon: 'success',
                            title: 'Product Stock Updated successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            var location = 'products_stock.php?id=' + <?php echo $user['product_id']; ?>;
                            window.location.replace(location);
                        });
                    });
            }
        });
    });
</script>


</html>