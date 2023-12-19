<?php include("../api/connection.php");

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

function update_sql($table, $id, $data)
{

    global $db;
    $db->where('id', $id);
    $db->update($table, $data);
}


if ($operation == 'get_orders') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col     = $sort_column[0]['column'];
    $dir     = $sort_column[0]['dir'];
    $columns = ['o.id', 'o.time', 'c.name', 'o.discount', 'o.total'];

    if ($search['value'] != '') {

        // WHERE condition
        $where = 'WHERE o.id LIKE "%' . $search['value'] . '%" OR time LIKE "%' . $search['value'] . '%" OR c.name LIKE "%' . $search['value'] . '%" OR discount LIKE "%' . $search['value'] . '%" OR total LIKE "%' . $search['value'] . '%"';
    }

    // Query of the data to be shown
    $result = $db->rawQuery('SELECT o.*, c.name FROM orders o JOIN customer c ON o.customer_id=c.id ' . $where . ' ORDER BY ' . $columns[$col] . ' ' . strtoupper($dir) . ' LIMIT ' . $length . ' OFFSET ' . $start . '');


    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM orders o JOIN customer c ON o.customer_id=c.id ' . $where . '');
    $filter = $cnt[0]['cnt'];

    $orders = array();

    foreach ($result as $order) {

        $orders[] = array(

            'order_id'      => ($order['status'] == 2) ? $order['id'] . '<br><span class="badge badge-primary" style="background-color: #eb5e28;font-size: 13px;">Refunded</span>' : $order['id'],
            'order_time'    => date("d-m-Y h:i", strtotime($order['time'])),
            'customer_name' => $order['name'],
            'discount'      => number_format($order['discount'], 2, '.', ','),
            'order_total'   => number_format($order['total'], 2, '.', ','),
            'action'        => '<a href="order_edit.php?edit=' . $order['id'] . '" class="btn btn-info btn-fill b_padding"><i class="fa fa-pencil"></i></a>
         <a href="#" data-id="' . $order['id'] . '" class="btn btn-del btn-danger btn-fill b_padding"><i class="fa fa-trash"></i></a>'
        );
    }
    $count = $db->getValue('orders', 'count(*)');

    $tableInfo = array(
        "draw"            => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $orders
    );

    echo json_encode($tableInfo);
}
 else if ($operation == 'get_daily_report') {
    $users = $db->rawQuery('SELECT DATE(orders.time) as date, COUNT(orders.id) as orders, SUM(orders.discount) as discount, SUM(orders.total) as total FROM orders where status = 1 GROUP BY DATE(orders.time) ORDER BY DATE(orders.time) DESC');

    $data = array();
    $rows = 1;

    foreach ($users as $row) {
        $expense = $db->rawQueryOne('SELECT SUM(amount) as expense FROM expense where DATE(expense.date) = "' . $row['date'] . '"');
        $buying = $db->rawQueryOne('SELECT SUM(order_product.quantity * product_batch.buying_price) as buying FROM orders JOIN order_product ON orders.id = order_product.order_id JOIN product_batch ON order_product.batch_id = product_batch.id where status = 1 AND DATE(orders.time) = "' . $row['date'] . '" GROUP By DATE(orders.time)');

        $data[] = array(
            "DT_RowId" => $rows, // Add a unique identifier for each row
            "date" => $row['date'],
            "orders" => $row['orders'],
            "sales" => number_format($row['discount'] + $row['total'], 2, '.', ','),
            "discount" => number_format($row['discount'], 2, '.', ','),
            "expense" => number_format($expense['expense'], 2, '.', ','),
            "total" => number_format($row['total'] - $expense['expense'], 2, '.', ','),
            "profit" => number_format(($row['total'] - $buying['buying']) - $expense['expense'], 2, '.', ',')
        );

        $rows++;
    }

    echo json_encode(array("data" => $data));
}
 else if ($operation =='get_monthly_summary') {
    $users = $db->rawQuery('SELECT YEAR(orders.time) as year , MONTH(orders.time) as month , COUNT(orders.id) as orders , SUM(orders.discount) as discount , SUM(orders.total) as total FROM orders where status = 1 GROUP BY MONTH(orders.time) , YEAR(orders.time) ORDER BY YEAR(orders.time) DESC, MONTH(orders.time) DESC');

    $data = array();
    $rows = 1;

    foreach ($users as $row) {
        $expense = $db->rawQueryOne('SELECT SUM(amount) as expense FROM expense where MONTH(date) = '.$row['month'].' && YEAR(date) = '.$row['year'].'');

        $buying = $db->rawQueryOne('SELECT SUM(order_product.quantity * product_batch.buying_price) as buying FROM orders JOIN order_product ON orders.id = order_product.order_id JOIN product_batch ON order_product.batch_id = product_batch.id where status = 1 AND MONTH(orders.time) = '.$row['month'].' && YEAR(orders.time) = '.$row['year'].' GROUP By MONTH(orders.time) && YEAR(orders.time)');	

        $monthName = date("F", mktime(0, 0, 0, $row['month'], 10));		

        $expense = $db->rawQueryOne('SELECT SUM(amount) as expense FROM expense where MONTH(date) = '.$row['month'].' && YEAR(date) = '.$row['year'].'');

        $buying = $db->rawQueryOne('SELECT SUM(order_product.quantity * product_batch.buying_price) as buying FROM orders JOIN order_product ON orders.id = order_product.order_id JOIN product_batch ON order_product.batch_id = product_batch.id where status = 1 AND MONTH(orders.time) = '.$row['month'].' && YEAR(orders.time) = '.$row['year'].' GROUP By MONTH(orders.time) && YEAR(orders.time)');	

        $data[] = array(
            "DT_RowId" => $rows, // Add a unique identifier for each row           
            "month" => $monthName.'-'.$row['year'],
            "orders" => $row['orders'],
            "sales" => number_format($row['discount']+$row['total'], 2 , '.' , ','),
            "discount" => number_format($row['discount'], 2 , '.' , ','),
            "expense" => number_format($expense['expense'], 2 , '.' , ','),
            "total" => number_format($row['total'] - $expense['expense'], 2, '.', ','),
            "profit" => number_format(($row['total']-$buying['buying'])-$expense['expense'], 2 , '.' , ',')
        );
        $rows++;
    }

    echo json_encode(array("data" => $data));
} 
else if ($operation == 'del_order') {

    $order_id = $_GET['order_id'];

    $response = '';

    $db->where("order_id", $order_id);
    $products = $db->get("order_product");

    $db->where("id", $order_id);
    $order = $db->getOne('orders');

    foreach ($products as $row) {
        if ($order['status'] == 1) {
            $updated_quantity = array(
                'quantity' => $db->inc($row['quantity'])
            );

            $db->where('id', $row['product_id']);
            if ($db->update('product', $updated_quantity)) {
                $db->where('id', $row['batch_id']);
                $product_batch = $db->getone('product_batch');

                if ($product_batch != null) {
                    $prod_data = array(
                        'quantity' => ($product_batch['quantity'] + $row['quantity'])
                    );

                    $db->where('id', $product_batch['id']);
                    if ($db->update('product_batch', $prod_data)) {
                        $db->where('id', $row['id']);
                        $db->delete('order_product');
                    }
                } else {
                    $response = 'batch not found ';
                }
            } else {
                $response = 'Refund failed: ' . $db->getLastError();
            }
        } else {
            $db->where('id', $row['id']);
            $db->delete('order_product');
        }
    }

    $db->where('id', $order_id);

    $response = 'successfully deleted';
    if ($db->delete('orders'))
        echo json_encode($response);
} else if ($operation == 'get_products') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col = $sort_column[0]['column'];
    $dir = $sort_column[0]['dir'];

    $columns = ['p.name', 'c.name', 'b.name', 'p.selling_price', 'p.buying_price', 'p.quantity'];

    if ($search['value'] != '') {

        // WHERE condition
        $where = ' WHERE p.name LIKE "%' . $search['value'] . '%" OR c.name LIKE "%' . $search['value'] . '%" OR b.name LIKE "%' . $search['value'] . '%" OR p.selling_price LIKE "%' . $search['value'] . '%" OR p.buying_price LIKE "%' . $search['value'] . '%" OR p.quantity LIKE "%' . $search['value'] . '%"';
    }

    // Query of the data to be shown
    $result = $db->rawQuery('SELECT p.*, c.name as category, b.name as brand FROM product p JOIN category c ON p.category_id = c.id JOIN brand b ON p.brand_id = b.id ' . $where . ' ORDER BY ' . $columns[$col] . ' ' . strtoupper($dir) . ' LIMIT ' . $length . ' OFFSET ' . $start . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM product p JOIN category c ON p.category_id = c.id JOIN brand b ON p.brand_id = b.id ' . $where . '');
    $filter = $cnt[0]['cnt'];

    $products = array();

    foreach ($result as $product) {

        $products[] = array(
            "product_name"  => $product['name'],
            "category"      => $product['category'],
            "brand"         => $product['brand'],
            "selling_price" => $product['selling_price'],
            "buying_price"  => $product['buying_price'],
            "quantity"      => $product['quantity'],
            "action"        => '<a href="products_add_batch.php?id=' . $product['id'] . '" class="btn btn-info btn-fill b_padding"><i class="fa fa-plus"></i></a>
         <a href="products_edit.php?edit=' . $product['id'] . '" class="btn btn-info btn-fill b_padding"><i class="fa fa-pencil"></i></a>
         <a href="products_stock.php?id=' . $product['id'] . '" class="btn btn-info btn-fill b_padding"><i class="fa fa-stack-overflow"></i></a>
         <a href="#" data-id="' . $product['id'] . '" class="btn btn-danger btn-del btn-fill b_padding"><i class="fa fa-trash"></i></a>'

        );
    }

    $count = $db->getValue('product', 'count(*)');

    $tableInfo = array(
        "draw"            => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $products,
    );

    echo json_encode($tableInfo);
} else if ($operation == 'del_product') {

    $response = 'No product deleted!';

    if (isset($_GET['product_id'])) {

        $db->where('id', $_GET['product_id']);

        if ($db->delete('product')) {
            $db->where('product_id', $_GET['product_id']);

            if ($db->delete('product_batch')) {

                $response = 'Product Successfully Deleted!';
            }
        }
    }

    echo json_encode($response);
} else if ($operation == 'del_batch') {

    $response = 'No batch deleted!';

    $batch = $db->rawQuery('SELECT * FROM product_batch where id = ' . $_GET['batch_id']);

    if (isset($_GET['batch_id'])) {

        $db->where('id', $_GET['batch_id']);

        if ($db->delete('product_batch')) {
            $response = 'Product Batch Successfully Deleted!';
        }
    }

    $result = $db->rawQuery('SELECT SUM(quantity) as total FROM product_batch where product_id = ' . $batch[0]['product_id'] . ' GROUP BY product_id');


    if ($result) {
        $record = array("quantity" => $result[0]['total']);
    } else {
        $record = array("quantity" => 0);
    }

    update_sql('product', $batch[0]['product_id'], $record);


    echo json_encode($response);
} else if ($operation == 'get_customers') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col = $sort_column[0]['column'];
    $dir = $sort_column[0]['dir'];

    $columns = ['id', 'name', 'car_no', 'mileage', 'phone', 'email'];

    if ($search['value'] != '') {

        // WHERE condition
        $where = 'WHERE id LIKE "%' . $search['value'] . '%" OR name LIKE "%' . $search['value'] . '%" OR email LIKE "%' . $search['value'] . '%" OR car_no LIKE "%' . $search['value'] . '%" OR phone LIKE "%' . $search['value'] . '%" OR mileage LIKE "%' . $search['value'] . '%"';
    }
    // Query of the data to be shown
    $result = $db->rawQuery('SELECT * FROM customer ' . $where . ' ORDER BY ' . $columns[$col] . ' ' . strtoupper($dir) . ' LIMIT ' . $length . ' OFFSET ' . $start . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM customer ' . $where . '');
    $filter = $cnt[0]['cnt'];

    $customers = array();

    foreach ($result as $customer) {

        $customers[] = array(

            "customer_id"   => $customer['id'],
            "customer_name" => $customer['name'],
            "car_numbber"   => $customer['car_no'],
            "mileage"       => $customer['mileage'],
            "phone"         => $customer['phone'],
            "email"         => $customer['email'],
            "action"        => '<a href="customers_edit.php?edit=' . $customer['id'] . '" class="btn btn-info btn-fill b_padding"><i class="fa fa-pencil"></i></a><a href="#" data-id="' . $customer['id'] . '" class="btn btn-danger btn-fill b_padding btn-del"><i class="fa fa-trash"></i></a>'

        );
    }

    $count = $db->getValue('customer', 'count(*)');

    $tableInfo = array(
        "draw"            => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $customers,
    );

    echo json_encode($tableInfo);
} else if ($operation == 'del_customer') {

    $response = 'No product deleted!';

    if (isset($_GET['cust_id'])) {

        $db->where('id', $_GET['cust_id']);
        if ($db->delete('customer')) {

            $response = 'Product Successfully Deleted!';
        }
    }
    echo json_encode($response);
} else if ($operation == 'get_categories') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col     = $sort_column[0]['column'];
    $dir     = $sort_column[0]['dir'];
    $columns = ['id', 'name'];


    if ($search['value'] != '') {

        // WHERE condition
        $where = 'WHERE id LIKE "%' . $search['value'] . '%" OR name LIKE "%' . $search['value'] . '%"';
    }

    // Query of the data to be shown
    $result = $db->rawQuery('SELECT * FROM category ' . $where . ' ORDER BY ' . $columns[$col] . ' ' . strtoupper($dir) . ' LIMIT ' . $length . ' OFFSET ' . $start . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM category ' . $where . '');
    $filter = $cnt[0]['cnt'];

    $categories = array();

    foreach ($result as $category) {

        $categories[] = array(

            "category_id"   => $category['id'],
            "category_name" => $category['name'],
            "action"        => '<a href="categories_edit.php?edit=' . $category['id'] . '" class="btn btn-info btn-fill b_padding"><i class="fa fa-pencil"></i></a><a href="#" data-id="' . $category['id'] . '" class="btn btn-danger btn-fill b_padding btn-del"><i class="fa fa-trash"></i></a>'

        );
    }

    $count = $db->getValue('category', 'count(*)');

    $tableInfo = array(
        "draw"            => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $categories,
    );

    echo json_encode($tableInfo);
} else if ($operation == 'del_category') {

    $response = 'No category deleted!';

    if (isset($_GET['cat_id'])) {

        $db->where('id', $_GET['cat_id']);
        if ($db->delete('category')) {
            $response = 'Product Successfully Deleted!';
        }
    }
    echo json_encode($response);
} else if ($operation == 'get_brands') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col     = $sort_column[0]['column'];
    $dir     = $sort_column[0]['dir'];
    $columns = ['id', 'name'];

    if ($search['value'] != '') {

        // WHERE condition
        $where = 'WHERE id LIKE "%' . $search['value'] . '%" OR name LIKE "%' . $search['value'] . '%"';
    }

    // Query of the data to be shown
    $result = $db->rawQuery('SELECT * FROM brand ' . $where . ' ORDER BY ' . $columns[$col] . ' ' . strtoupper($dir) . ' LIMIT ' . $length . ' OFFSET ' . $start . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM brand ' . $where . '');
    $filter = $cnt[0]['cnt'];

    $brands = array();

    foreach ($result as $brand) {

        $brands[] = array(

            "brand_id"   => $brand['id'],
            "brand_name" => $brand['name'],
            "action"     => '<a href="brands_edit.php?edit=' . $brand['id'] . '" class="btn btn-info btn-fill b_padding"><i class="fa fa-pencil"></i></a><a href="#" data-id="' . $brand['id'] . '" class="btn btn-danger btn-fill b_padding btn-del"><i class="fa fa-trash"></i></a>'

        );
    }

    $count = $db->getValue('brand', 'count(*)');

    $tableInfo = array(
        "draw"            => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $brands,
    );

    echo json_encode($tableInfo);
} else if ($operation == 'del_brand') {

    $response = 'No brand deleted!';
    if (isset($_GET['brand_id'])) {

        $db->where('id', $_GET['brand_id']);
        if ($db->delete('brand')) {

            $response = 'Product Successfully Deleted!';
        }
    }
    echo json_encode($response);
} else if ($operation == 'get_expenses') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col     = $sort_column[0]['column'];
    $dir     = $sort_column[0]['dir'];
    $columns = ['id', 'date', 'name', 'amount'];

    if ($search['value'] != '') {
        // WHERE condition
        $where = 'WHERE id LIKE "%' . $search['value'] . '%" OR name LIKE "%' . $search['value'] . '%" OR amount LIKE "%' . $search['value'] . '%" OR date LIKE "%' . $search['value'] . '%"';
    }

    // Query of the data to be shown
    $result = $db->rawQuery('SELECT * FROM expense ' . $where . ' ORDER BY ' . $columns[$col] . ' ' . strtoupper($dir) . ' LIMIT ' . $length . ' OFFSET ' . $start . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM expense ' . $where . '');
    $filter = $cnt[0]['cnt'];


    $expenses = array();

    foreach ($result as $expense) {

        $expenses[] = array(

            "expense_id"     => $expense['id'],
            "expense_date"   => $expense['date'],
            "expense_name"   => $expense['name'],
            "expense_amount" => $expense['amount'],
            "action"         => '<a href="#" data-id="' . $expense['id'] . '" class="btn btn-danger btn-fill b_padding btn-del"><i class="fa fa-trash"></i></a>'

        );
    }

    $count = $db->getValue('expense', 'count(*)');

    $tableInfo = array(
        "draw"            => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $expenses,
    );

    echo json_encode($tableInfo);
} else if ($operation == 'del_expense') {

    $response = 'No expense deleted!';

    if (isset($_GET['exp_id'])) {
        $db->where('id', $_GET['exp_id']);
        if ($db->delete('expense')) {

            $response = 'Expense Record Successfully Deleted!';
        }
    }
    echo json_encode($response);
} else if ($operation == 'get_vendor_payments') {

    $filter = 0;
    $where  = '';

    $sort_column = $_GET['order'];
    $search      = $_GET['search'];
    $start       = $_GET['start'];
    $length      = $_GET['length'];

    $col     = $sort_column[0]['column'];
    $dir     = $sort_column[0]['dir'];
    $columns = ['id', 'date', 'name', 'amount'];

    if ($search['value'] != '') {
        // WHERE condition
        $where = 'WHERE id LIKE "%' . $search['value'] . '%" OR name LIKE "%' . $search['value'] . '%" OR amount LIKE "%' . $search['value'] . '%" OR date LIKE "%' . $search['value'] . '%"';
    }

    // Query of the data to be shown
    $result = $db->rawQuery('SELECT * FROM vendor_payments ' . $where . ' ORDER BY ' . $columns[$col] . ' ' . strtoupper($dir) . ' LIMIT ' . $length . ' OFFSET ' . $start . '');

    // Query for filtering
    $cnt    = $db->rawQuery('SELECT count(*) as cnt FROM vendor_payments ' . $where . '');
    $filter = $cnt[0]['cnt'];


    $expenses = array();

    foreach ($result as $expense) {

        $expenses[] = array(

            "id"     => $expense['id'],
            "date"   => $expense['date'],
            "name"   => $expense['name'],
            "amount" => $expense['amount'],
            "action" => '<a href="#" data-id="' . $expense['id'] . '" class="btn btn-danger btn-fill b_padding btn-del"><i class="fa fa-trash"></i></a>'

        );
    }

    $count = $db->getValue('vendor_payments', 'count(*)');

    $tableInfo = array(
        "draw"            => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
        "recordsTotal"    => intval($count),
        "recordsFiltered" => $filter,
        "data"            => $expenses,
    );

    echo json_encode($tableInfo);
} else if ($operation == 'del_vendor_payment') {

    $response = 'No Payment deleted!';

    if (isset($_GET['pay_id'])) {
        $db->where('id', $_GET['pay_id']);
        if ($db->delete('vendor_payments')) {

            $response = 'Payment Record Successfully Deleted!';
        }
    }
    echo json_encode($response);
}
