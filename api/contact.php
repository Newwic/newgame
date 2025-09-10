<?php
// contact.php
// Accepts POST JSON: { name, email, message }
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
if (!$data || !isset($data['message']) || trim($data['message']) === '') {
    echo json_encode(['success' => false, 'message' => 'Message is required']);
    exit;
}

$name = isset($data['name']) ? $conn->real_escape_string(trim($data['name'])) : null;
$email = isset($data['email']) ? $conn->real_escape_string(trim($data['email'])) : null;
$message = $conn->real_escape_string(trim($data['message']));

$stmt = $conn->prepare("INSERT INTO `messages` (`name`,`email`,`message`) VALUES (?,?,?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$bind = $stmt->bind_param('sss', $name, $email, $message);
if ($bind === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'bind_param failed']);
    $stmt->close();
    exit;
}

$exec = $stmt->execute();
if ($exec) {
    echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}
$stmt->close();
