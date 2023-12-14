<?php include("connection.php");


if (isset($_POST['operation']))
    $operation = $_POST['operation'];
if (isset($_GET['operation']))
    $operation = $_GET['operation'];


function insert_sql($table, $data)
{

    global $db;
    $id = $db->insert($table, $data);
    if ($id)
        echo '';

}

if ($operation == 'add_customer') {
    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $car_no = $_POST['car_no'];
    $phone  = $_POST['phone'];

    $record = Array("name" => $_POST['name'], "email" => $_POST['email'], "car_no" => $_POST['car_no'], "phone" => $_POST['phone'], "mileage" => $_POST['mileage']

    );

    insert_sql('customer', $record);
} else if ($operation == 'update_mileage') {

    $cust_id = $_POST['customer_id'];
    $mileage = $_POST['mileage'];

    //var_dump($cust_id, $mileage);

    if ($mileage != '' && $cust_id != '') {

        $data = Array("mileage" => $mileage);
        $db->where('id', $cust_id);
        $db->update('customer', $data);
    }

} else if ($operation == 'search_product') {
    if ($_GET["name"] != "") {

        $db->where("name", '%' . $_GET['name'] . '%', 'like');

        $cols = Array("id", "name", "selling_price", "quantity");
        $user = $db->get("product", null, $cols);

    } else {
        $user = array();
    }

    echo json_encode($user);

} else if ($operation == 'place_order') {

    $current_time = date("Y-m-d H:i:00", strtotime('+5 hours'));

    $db->where("customer_id", $_POST['customer_id']);
    $db->where("status", $_POST['status']);
    $db->where("discount", $_POST['discount']);
    $db->where("time", $current_time);
    $check_order = $db->getOne("orders");

    if ($check_order == null) {
        $data = Array("customer_id" => $_POST['customer_id'],
                      "status"      => $_POST['status'],
                      "discount"    => $_POST['discount'],
                      "time"        => $current_time
        );
        $id   = $db->insert('orders', $data);

        $total = 0;
        if ($id) {

            if (count($_POST['order_products']) > 0) {
                foreach ($_POST['order_products'] as $row) {

                    $db->where("id", $row[0]);
                    $user = $db->getOne("product");


                    $updated_quantity = Array(

                        'quantity' => $db->inc(-1 * $row[1])

                    );

                    $quantity = $row[1];
                    $db->where('id', $row[0]);
                    if ($db->update('product', $updated_quantity)) {
                        $db->where('product_id', $row[0]);
                        $db->where('quantity', 1, ">=");
                        $product_batch = $db->get('product_batch');

                        foreach ($product_batch as $line) {

                            if ($line['quantity'] >= $quantity) {
                                $line['quantity'] = $line['quantity'] - $quantity;
                                $prod_data        = Array(
                                    'quantity' => $line['quantity']
                                );
                                $db->where('id', $line['id']);
                                if ($db->update('product_batch', $prod_data)) {

                                    $entry = Array("order_id" => $id, "product_id" => $row[0], "batch_id" => $line['id'], "unit_price" => $user['selling_price'], "quantity" => $quantity
                                    );
                                    insert_sql('order_product', $entry);

                                    break;
                                }

                            } else {
                                $quantity  = $quantity - $line['quantity'];
                                $prod_data = Array(
                                    'quantity' => 0
                                );
                                $db->where('id', $line['id']);
                                if ($db->update('product_batch', $prod_data)) {
                                    $entry = Array("order_id" => $id, "product_id" => $row[0], "batch_id" => $line['id'], "unit_price" => $user['selling_price'], "quantity" => $line['quantity']
                                    );
                                    insert_sql('order_product', $entry);
                                }

                            }
                        }
                    } else
                        echo 'Order failed: ' . $db->getLastError();

                    $total = $total + ($user['selling_price'] * $row[1]);

                }


                $updated_total = Array(
                    'total' => ($total - $_POST['discount'])

                );
                $db->where('id', $id);
                if ($db->update('orders', $updated_total))
                    echo $id;
                else
                    echo 'Order Failed: ' . $db->getLastError();
            }
        }
    }
} else if ($operation == 'get_orders') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col     = $sort_column[0]['column'];
    $dir     = $sort_column[0]['dir'];
    $columns = ['o.id', 'o.time', 'c.name', 'o.total'];

    if ($search['value'] != '') {

        $where = ' WHERE o.id LIKE "' . $search['value'] . '%" OR o.time LIKE "' . $search['value'] . '%" OR c.car_no LIKE "' . $search['value'] . '%" OR c.name LIKE "' . $search['value'] . '%"';
    }

    $result = $db->rawQuery('SELECT o.*, c.name, c.car_no FROM orders o JOIN customer c ON o.customer_id=c.id ' . $where . ' ORDER BY ' . $columns[$col] . ' ' . strtoupper($dir) . ' LIMIT ' . $length . ' OFFSET ' . $start . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM orders o JOIN customer c ON o.customer_id=c.id ' . $where . '');
    $filter = $cnt[0]['cnt'];

    $orders = array();

    foreach ($result as $order) {

        $orders[] = Array(
            'order_id'      => ($order['status'] == 2) ? $order['id'] . '<br><span class="badge badge-primary" style="background-color: #eb5e28;font-size: 13px;">Refunded</span>' : $order['id'],
            'order_time'    => date("d-m-Y H:i", strtotime($order['time'])),
            'customer_name' => $order['name'],
            'car_number'    => $order['car_no'],
            'order_total'   => number_format($order['total'], 2, '.', ','),
            'action'        => '<a href="order_edit.php?edit=' . $order['id'] . '" class="btn btn-info btn-fill b_padding"><i class="fa fa-file-text"></i></a>
            <a href="#" data-id="' . $order['id'] . '"  class="btn btn-danger btn-fill b_padding order-print"><i class="fa fa-print"></i></a>'
        );

    }
    $count = $db->getValue('orders', 'count(*)');

    $tableInfo = array(
        "draw"            => isset ($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $orders
    );

    echo json_encode($tableInfo);
} else if ($operation == 'get_customer_orders') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col     = $sort_column[0]['column'];
    $dir     = $sort_column[0]['dir'];
    $columns = ['o.id', 'o.time', 'c.name', 'o.total'];

    if ($search['value'] != '') {

        $where = ' WHERE c.id=' . $_GET['cust_id'] . ' AND (o.id LIKE "' . $search['value'] . '%" OR o.time LIKE "' . $search['value'] . '%" OR c.car_no LIKE "' . $search['value'] . '%" OR c.name LIKE "' . $search['value'] . '%" OR o.total = "' . $search['value'] . '%")';
    } else {
        $where = ' WHERE c.id=' . $_GET['cust_id'] . ' ';
    }

    $result = $db->rawQuery('SELECT o.*, c.name, c.car_no FROM orders o JOIN customer c ON o.customer_id=c.id ' . $where . ' ORDER BY ' . $columns[$col] . ' ' . strtoupper($dir) . ' LIMIT ' . $length . ' OFFSET ' . $start . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM orders o JOIN customer c ON o.customer_id=c.id ' . $where . '');
    $filter = $cnt[0]['cnt'];

    $orders = array();

    foreach ($result as $order) {

        $orders[] = Array(
            'order_id'      => ($order['status'] == 2) ? $order['id'] . '<br><span class="badge badge-primary" style="background-color: #eb5e28;font-size: 13px;">Refunded</span>' : $order['id'],
            'order_time'    => date("d-m-Y H:i", strtotime($order['time'])),
            'customer_name' => $order['name'],
            'car_number'    => $order['car_no'],
            'order_total'   => number_format($order['total'], 2, '.', ','),
            'action'        => '<a href="#" data-id="' . $order['id'] . '"  class="btn btn-danger btn-fill b_padding order-print"><i class="fa fa-print"></i></a>'
        );

    }
    $count = $db->getValue('orders', 'count(*)');

    $tableInfo = array(
        "draw"            => isset ($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $orders
    );

    echo json_encode($tableInfo);
} else if ($operation == 'get_customer') {

    $customer_array = array();

    $customers = $db->where("name", $_GET['q'] . "%", "like")->orWhere("phone", $_GET['q'] . "%", "like")->orWhere("car_no", $_GET['q'] . "%", "like")->get("customer");

    foreach ($customers as $customer) {

        $cust             = array();
        $cust["name"]     = $customer['name'] . " / " . $customer['car_no'];
        $cust["id"]       = strval($customer['id']);
        $cust["mileage"]  = $customer["mileage"];
        $customer_array[] = $cust;

    }

    echo json_encode($customer_array);
} else if ($operation == 'get_total_cash') {
    $db->where("DATE(date) = '" . date("Y-m-d") . "'");
    $stats_today[0] = $db->getOne("expense", "ROUND(sum(amount),2) as total_expense");

    $db->where("DATE(time) = '" . date("Y-m-d") . "'");
    $db->where("status != 2");
    $stats_today[1] = $db->getOne("orders", "ROUND(sum(total),2) as total_sale");
    echo json_encode($stats_today);
} else if ($operation == 'add_expense') {

    if ($_POST['amount'] > 0) {
        $record = Array(
            "name"   => $_POST['name'],
            "amount" => $_POST['amount'],
            "date"   => $db->now('+5h')

        );
        insert_sql('expense', $record);
    }

} else if ($operation == 'add_payment') {

    if ($_POST['amount'] > 0) {
        $record = Array(
            "name"   => $_POST['name'],
            "amount" => $_POST['amount'],
            "date"   => $db->now('+5h')

        );
        insert_sql('vendor_payments', $record);
    }

} else if ($operation == 'delete_order') {

    $db->where("order_id", $_POST['id']);
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
                    // $db->where('id', $row['id']);
                    // $db->delete('order_product');
                }
            } else {
                echo 'batch not found ';
            }

        } else {
            echo 'Refund failed: ' . $db->getLastError();
        }

    }

    $db->where('id', $_POST['id']);
    $status = ["status" => 2];
    if ($db->update('orders', $status))

        echo 'Order Successfully Refunded';
} else if ($operation == 'get_today_vendor_payments') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col     = $sort_column[0]['column'];
    $dir     = $sort_column[0]['dir'];
    $columns = ['id', 'date', 'name', 'amount'];

    $where = 'WHERE date >= CURDATE() ';

    // Query of the data to be shown
    $result = $db->rawQuery('SELECT * FROM vendor_payments ' . $where . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM vendor_payments ' . $where . '');
    $filter = $cnt[0]['cnt'];


    $payments = array();

    foreach ($result as $expense) {

        $payments[] = Array(

            "id"     => $expense['id'],
            "name"   => $expense['name'],
            "amount" => $expense['amount']

        );
    }

    $count = $db->getValue('vendor_payments', 'count(*)');

    $tableInfo = array(
        "draw"            => isset ($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $payments,
    );

    echo json_encode($tableInfo);
} else if ($operation == 'get_today_expenses') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col     = $sort_column[0]['column'];
    $dir     = $sort_column[0]['dir'];
    $columns = ['id', 'date', 'name', 'amount'];

    $where = 'WHERE date >= CURDATE() ';

    // Query of the data to be shown
    $result = $db->rawQuery('SELECT * FROM expense ' . $where . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM expense ' . $where . '');
    $filter = $cnt[0]['cnt'];


    $expenses = array();

    foreach ($result as $expense) {

        $expenses[] = Array(

            "id"     => $expense['id'],
            "name"   => $expense['name'],
            "amount" => $expense['amount']

        );
    }

    $count = $db->getValue('vendor_payments', 'count(*)');

    $tableInfo = array(
        "draw"            => isset ($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $expenses,
    );

    echo json_encode($tableInfo);
}

?>