<?php include("header.php"); ?>

<?php

$user = $db->get("customer");
$num_rows = count($user);

$num_rows++;

?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Add New Customer</h4>
                    </div>
                    <div class="content">
                        <form>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control border-input" id="name" placeholder="" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Car Number</label>
                                        <input type="text" class="form-control border-input" id="car-no" placeholder="" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control border-input" id="phone" placeholder="" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Milage</label>
                                        <input type="number" class="form-control border-input" id="milage" placeholder="" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email</label>
                                        <input type="email" class="form-control border-input" id="email" placeholder="" value="">
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
            if ($("#name").val() == "" || $("#car-no").val() == "" || $("#email").val() == "" || $("#phone").val() == "" || $("#milage").val() == "") {
                // Use SweetAlert for missing fields
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Field',
                    text: 'Please fill in all fields',
                });
            } else {
                $.post("add.php", {
                        operation: "add_customer",
                        name: document.getElementById("name").value,
                        car_no: document.getElementById("car-no").value,
                        email: document.getElementById("email").value,
                        phone: document.getElementById("phone").value,
                        mileage: document.getElementById("milage").value
                    })
                    .done(function(data) {
                        // Use SweetAlert for success
                        Swal.fire({
                            icon: 'success',
                            title: 'Customer has been Added',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.replace('customers.php');
                        });
                    });
            }
        });
    });
</script>


</html>