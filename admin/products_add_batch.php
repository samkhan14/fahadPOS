<?php include("header.php"); ?>

<?php
if (isset($_GET['id'])) {
    $db->where("id", $_GET['id']);
    $user = $db->getOne("product");
}
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Product: <?php echo $user['name'] ?></h4>
                        <p>Add New Batch</p>
                    </div>
                    <div class="content">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Buying Price</label>
                                        <input type="text" class="form-control border-input" required id="buy-price" placeholder="" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Selling Price</label>
                                        <input type="text" class="form-control border-input" required id="sell-price" placeholder="" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="text" class="form-control border-input" required id="quantity" placeholder="" value="">
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
            if ($("#sell-price").val() == "" || $("#buy-price").val() == "" || $("#quantity").val() == "") {
                // Use SweetAlert for missing fields
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Field',
                    text: 'Please fill in all fields',
                });
            } else {
                $.post("update.php", {
                        operation: "add_product_batch",
                        id: <?php echo $_GET['id']; ?>,
                        quantity: document.getElementById("quantity").value,
                        buy_price: document.getElementById("buy-price").value,
                        sell_price: document.getElementById("sell-price").value


                    })
                    .done(function(data) {
                        // Use SweetAlert for success
                        Swal.fire({
                            icon: 'success',
                            title: 'Product Batch has been Updated',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.replace('products.php');
                        });
                    });

                }  
            });

    });

    // });

    //});
</script>

</html>