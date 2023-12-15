<?php include("header.php"); ?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-9 col-xs-7">
                                <h4 class="title">Expenses</h4>
                                <p class="category">Expenses List</p>
                            </div>
                            <div class="col-md-3 col-xs-5 text-right">
                                <a id="btn-delete-all" href="expenses.php?clear=expense" class="btn btn-primary btn-fill">Delete All</a>
                            </div>
                        </div>
                    </div>

                  
                    <div class="table-responsive table-full-width">
                        <table id="exp_table" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <th>S.No</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Amount</th>
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
                    window.location.href = "expenses.php?clear=expense";
                }
            });
        });
    });
</script>

<?php
if (isset($_GET['clear'])) {
    $db->rawQueryOne('TRUNCATE TABLE expense');
    // Display success message with SweetAlert
    echo '<div class="alert alert-success">Successfully Deleted!</div>';
}
?>