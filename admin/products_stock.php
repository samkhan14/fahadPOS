<?php include("header.php"); ?>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-card">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-9 col-xs-7">
                                    <h4 class="title"><?php $db->where('id', $_GET['id']);
                                        $product = $db->getOne('product');
                                        echo $product['name'];
                                        ?>
                                    </h4>
                                </div>

                            </div>
                        </div>
                        <div class="table-responsive table-full-width">
                            <table id="stock_table" class="table table-bordered table-striped">
                                <thead>
                                <th>Batch</th>
                                <th>Import Date</th>
                                <th>Buying Price</th>
                                <th>Selling Price</th>
                                <th>Quantity</th>
                                <th>Import Quantity</th>
                                <th>Action</th>
                                </thead>
                                <tbody>
                                <?php
                                $db->where('product_id', $_GET['id']);
                                $users = $db->get('product_batch');
                                $batch = 1;
                                foreach ($users as $row) {
                                    ?>

                                    <tr>
                                        <td><?php echo($batch);
                                            $batch++; ?></td>
                                        <td>
                                            <?php echo $row['date']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['buying_price']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['selling_price']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['quantity']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['import_quantity']; ?>
                                        </td>
                                        <td>
                                            <a href="products_stock_edit.php?edit=<?php echo $row['id']; ?>"
                                               class="btn btn-info btn-fill b_padding"><i class="fa fa-pencil"></i></a>
                                            <a href="#" data-id="<?php echo $row['id']; ?>"
                                               class="btn btn-danger btn-del btn-fill b_padding"><i
                                                        class="fa fa-trash"></i></a>
                                        </td>

                                    </tr>

                                <?php } ?>

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