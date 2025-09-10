<?php
// reviews.php
// Accepts POST JSON: { product: string, name: string|null, rating: int, comment: string|null }
// Stores review in `reviews` table
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
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

$product = isset($data['product']) ? $conn->real_escape_string($data['product']) : 'ข้าวมันไก่';
$name_in = isset($data['name']) ? $data['name'] : '';
$comment_in = isset($data['comment']) ? $data['comment'] : '';
$rating = isset($data['rating']) ? intval($data['rating']) : 0;

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
    exit;
}

// sanitize and coerce to strings for bind_param
$name = $conn->real_escape_string($name_in !== null ? (string)$name_in : '');
$comment = $conn->real_escape_string($comment_in !== null ? (string)$comment_in : '');

$stmt = $conn->prepare("INSERT INTO `reviews` (`product`,`name`,`rating`,`comment`) VALUES (?,?,?,?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$bindOk = $stmt->bind_param('ssis', $product, $name, $rating, $comment);
if ($bindOk === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'bind_param failed']);
    $stmt->close();
    exit;
}

$ok = $stmt->execute();
if ($ok) {
    $id = $stmt->insert_id;
    echo json_encode(['success' => true, 'id' => $id]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}
$stmt->close();
