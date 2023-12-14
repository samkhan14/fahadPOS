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


    $record = Array("name" => $_POST['name']
    );

    update_sql('brand', $_POST['id'], $record);
} else if ($operation == 'update_category') {

    $record = Array("name" => $_POST['name']
    );

    update_sql('category', $_POST['id'], $record);
} else if ($operation == 'update_customer') {

    $record = Array("name"   => $_POST['name'],
                    "email"  => $_POST['email'],
                    "car_no" => $_POST['car_no'],
                    "phone"  => $_POST['phone']
    );

    update_sql('customer', $_POST['id'], $record);
} else if ($operation == 'update_product') {


    $record = Array("name"          => $_POST['name'],
                    "category_id"   => $_POST['category'],
                    "brand_id"      => $_POST['brand'],
                    "buying_price"  => $_POST['buy_price'],
                    "selling_price" => $_POST['sell_price']
    );

    update_sql('product', $_POST['id'], $record);

} else if ($operation == 'update_product_stock') {

    $record = Array("quantity"        => $_POST['quantity'],
                    "import_quantity" => $_POST['import_quantity'],
                    "buying_price"    => $_POST['buying_price'],
                    "selling_price"   => $_POST['selling_price']
    );

    update_sql('product_batch', $_POST['id'], $record);

    $result = $db->rawQuery('SELECT SUM(quantity) as total FROM product_batch where product_id = ' . $_POST['product_id'] . ' GROUP BY product_id');


    $record = Array("quantity" => $result[0]['total']);

    update_sql('product', $_POST['product_id'], $record);

} else if ($operation == 'add_product_batch') {
    $id = trim(strtolower($_POST['id']));
    $db->where('id', $id, 'like');
    $product = $db->getOne('product');
    if ($product) {
        $data = Array(
            'selling_price' => $_POST['sell_price'],
            'buying_price'  => $_POST['buy_price'],
            'quantity'      => $db->inc($_POST['quantity'])
        );
        $db->where('id', $product['id']);
        if ($db->update('product', $data)) {
            $prod_batch = Array("product_id"      => $product['id'],
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


?>