<?php include("header.php"); ?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6 col-xs-7">
                                <h4 class="title">Customers</h4>
                                <p class="category">Customer List</p>
                            </div>
                            <div class="col-md-6 col-xs-5 text-right">
                                <a href="customers_add.php" class="btn btn-primary btn-fill">Add New</a>
                                <a id="btn-delete-all" href="customers.php?clear=customers" class="btn btn-primary btn-fill">Delete All</a>
                            </div>
                        </div>
                    </div>

                 
                    <div class="table-responsive table-full-width">
                        <table id="cust_table" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Car Number</th>
                                <th>Milage</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Action</th>
                            </thead>
                            <tbody>

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

<script type="text/javascript">
    $(document).ready(function () {
        $("#btn-delete-all").click(function (e) {
            e.preventDefault();

            // Use SweetAlert for confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will delete all records. You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to clear records
                    window.location.href = "customers.php?clear=customers";
                }
            });
        });
    });
</script>

<?php
if (isset($_GET['clear'])) {
    if ($db->rawQueryOne('TRUNCATE TABLE customer')) {
        // Display success message with SweetAlert
        echo '<script type="text/javascript">Swal.fire("Successfully Deleted!", "", "success");</script>';
    }
}
?>