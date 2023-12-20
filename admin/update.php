<?php

include("../api/connection.php");

if (isset($_POST['operation']))
    $operation = $_POST['operation'];

function update_sql($table, $id, $data)
{

    global $db;
    $db->where('id', $id);
    if ($db->update($table, $data))
        echo $db->count . ' records were updated';
    else
        echo 'update failed: ' . $db->getLastError();
}

if ($operation == 'update_brand') {


    $record = array(
        "name" => $_POST['name']
    );

    update_sql('brand', $_POST['id'], $record);
} else if ($operation == 'update_category') {

    $record = array(
        "name" => $_POST['name']
    );

    update_sql('category', $_POST['id'], $record);
} else if ($operation == 'update_customer') {

    $record = array(
        "name"   => $_POST['name'],
        "email"  => $_POST['email'],
        "car_no" => $_POST['car_no'],
        "phone"  => $_POST['phone']
    );

    update_sql('customer', $_POST['id'], $record);
} else if ($operation == 'update_product') {

    // log is generating when update the product or the any column
    $record = array(
        "name"          => $_POST['name'],
        "category_id"   => $_POST['category'],
        "brand_id"      => $_POST['brand'],
        "buying_price"  => $_POST['buy_price'],
        "selling_price" => $_POST['sell_price']
    );

    $productId = $_POST['id'];
    //$batchId = $_POST['id'];

    // Save the original values before the update
    $originalValues = $db->rawQuery('SELECT * FROM product WHERE id = ' . $productId);

    update_sql('product', $productId, $record);

    // Retrieve the updated values after the update
    $updatedValues = $db->rawQuery('SELECT * FROM product WHERE id = ' . $productId);

    // Log the update details in the table
    $logTable = 'log_products';
    $logValues = array(
        'product_id' => $productId,
        'original_values' => json_encode($originalValues[0]),
        'updated_values' => json_encode($updatedValues[0])
    );

    $db->insert($logTable, $logValues);

} else if ($operation == 'update_product_stock') {

    // log is generating when update the quantity or the any column

    $record = array(
        "quantity"        => $_POST['quantity'],
        "import_quantity" => $_POST['import_quantity'],
        "buying_price"    => $_POST['buying_price'],
        "selling_price"   => $_POST['selling_price']
    );

    $productId = $_POST['product_id'];
    $batchId = $_POST['id'];

    // Save the original values before the update
    $originalValues = $db->rawQuery('SELECT * FROM product_batch WHERE id = ' . $batchId);

    update_sql('product_batch', $batchId, $record);

    // Retrieve the updated values after the update
    $updatedValues = $db->rawQuery('SELECT * FROM product_batch WHERE id = ' . $batchId);

    // Log the update details in the table
    $logTable = 'log_product_batch';
    $logValues = array(
        'batch_id' => $batchId,
        'product_id' => $productId,
        'original_values' => json_encode($originalValues[0]),
        'updated_values' => json_encode($updatedValues[0])
    );

    $db->insert($logTable, $logValues);

    $result = $db->rawQuery('SELECT SUM(quantity) as total FROM product_batch where product_id = ' . $_POST['product_id'] . ' GROUP BY product_id');

    $productRecord = array("quantity" => $result[0]['total']);
    update_sql('product', $productId, $productRecord);
} else if ($operation == 'add_product_batch') {
    $id = trim(strtolower($_POST['id']));
    $db->where('id', $id, 'like');
    $product = $db->getOne('product');
    if ($product) {
        $data = array(
            'selling_price' => $_POST['sell_price'],
            'buying_price'  => $_POST['buy_price'],
            'quantity'      => $db->inc($_POST['quantity'])
        );
        $db->where('id', $product['id']);
        if ($db->update('product', $data)) {
            $prod_batch = array(
                "product_id"      => $product['id'],
                "buying_price"    => $_POST['buy_price'],
                "selling_price"   => $_POST['sell_price'],
                "quantity"        => $_POST['quantity'],
                "import_quantity" => $_POST['quantity']
            );
            $id         = $db->insert('product_batch', $prod_batch);
        } else
            echo 'update failed: ' . $db->getLastError();
    }
}
