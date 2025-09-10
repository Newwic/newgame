<?php
// get_orders.php
// Returns a list of orders with items in JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/db_local.php';

$type = isset($_GET['type']) ? $_GET['type'] : null; // optional filter

$sql = "SELECT o.id, o.table_id, o.total, o.payment_method, o.status, o.created_at
        FROM orders o
        WHERE 1=1";

if ($type === 'pending') {
    $sql .= " AND o.status = 'pending'";
}
$sql .= " ORDER BY o.created_at DESC";

$res = $conn->query($sql);
$orders = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $orderId = intval($row['id']);
        $row['items'] = [];
        $itemsRes = $conn->query("SELECT product_id, name, quantity, price FROM order_items WHERE order_id = " . $orderId);
        if ($itemsRes) {
            while ($it = $itemsRes->fetch_assoc()) {
                $row['items'][] = $it;
            }
        }
        $orders[] = $row;
    }
}

echo json_encode(['success' => true, 'orders' => $orders]);
