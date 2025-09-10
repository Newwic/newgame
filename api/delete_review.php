<?php
// delete_review.php
// POST JSON: { id: int }
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/db_local.php';
require_once __DIR__ . '/auth.php';

// require admin token
if (!is_admin_request()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing id']);
    exit;
}

$id = intval($data['id']);
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid id']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM `reviews` WHERE `id` = ? LIMIT 1");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$bind = $stmt->bind_param('i', $id);
if ($bind === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'bind_param failed']);
    $stmt->close();
    exit;
}

$exec = $stmt->execute();
if ($exec) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'deleted' => $stmt->affected_rows]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No review found with that id']);
    }
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}
$stmt->close();
