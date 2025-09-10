<?php
// mark_order_done.php
// POST JSON { order_id: int }
// Marks order status = 'done' and frees table if applicable
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/db_local.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data || !isset($data['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing order_id']);
    exit;
}

$order_id = intval($data['order_id']);

$conn->begin_transaction();
try {
    // get the order and table_id
    $r = $conn->prepare("SELECT table_id FROM orders WHERE id = ? LIMIT 1");
    $r->bind_param('i', $order_id);
    $r->execute();
    $res = $r->get_result();
    $table_id = null;
    if ($row = $res->fetch_assoc()) {
        $table_id = $row['table_id'] ? intval($row['table_id']) : null;
    }
    $r->close();

    // mark order done
    $u = $conn->prepare("UPDATE orders SET status = 'done' WHERE id = ?");
    $u->bind_param('i', $order_id);
    $u->execute();
    $u->close();

    // free table if applicable
    if ($table_id) {
        $f = $conn->prepare("UPDATE `tables` SET occupied = 0 WHERE id = ?");
        $f->bind_param('i', $table_id);
        $f->execute();
        $f->close();
    }

    $conn->commit();
    echo json_encode(['success' => true]);
    exit;
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
