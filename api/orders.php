<?php
// orders.php
// POST JSON { table: int|null, items: [{id,name,quantity,price}], total: number, paymentMethod: string }
// Returns: { success: true, order_id: X }

header('Content-Type: application/json');
// Allow CORS from localhost dev server
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/db_local.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

$table_id = isset($data['table']) && is_numeric($data['table']) ? intval($data['table']) : null;
$items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
$total = isset($data['total']) ? floatval($data['total']) : 0;
$paymentMethod = isset($data['paymentMethod']) ? $conn->real_escape_string($data['paymentMethod']) : null;

// Begin transaction
$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO `orders` (`table_id`,`total`,`payment_method`,`status`) VALUES (?,?,?, 'pending')");
    $stmt->bind_param('ids', $table_id, $total, $paymentMethod);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    if ($order_id) {
        $itemStmt = $conn->prepare("INSERT INTO `order_items` (`order_id`, `product_id`, `name`, `quantity`, `price`) VALUES (?,?,?,?,?)");
        foreach ($items as $it) {
            $pid = isset($it['id']) ? intval($it['id']) : null;
            $name = isset($it['name']) ? $it['name'] : '';
            $qty = isset($it['quantity']) ? intval($it['quantity']) : 1;
            $price = isset($it['price']) ? floatval($it['price']) : null;
            $itemStmt->bind_param('iisis', $order_id, $pid, $name, $qty, $price);
            $itemStmt->execute();
        }
        $itemStmt->close();

        // mark table occupied if provided
        if ($table_id) {
            $u = $conn->prepare("UPDATE `tables` SET `occupied` = 1, `last_order_id` = ? WHERE `id` = ?");
            $u->bind_param('ii', $order_id, $table_id);
            $u->execute();
            $u->close();
        }

        $conn->commit();

        echo json_encode(['success' => true, 'order_id' => $order_id]);
        exit;
    } else {
        throw new Exception('Insert order failed');
    }
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
