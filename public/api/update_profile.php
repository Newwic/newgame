<?php
require_once 'db.php';
require __DIR__ . '/../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Handle pre-flight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

// Define secret key
define('JWT_SECRET', 'your_secret_key'); // Should be the same as in login.php

// --- Middleware: Check for Authorization Header ---
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    json_response(['status' => 'error', 'message' => 'Authorization header not found'], 401);
}

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
list($jwt) = sscanf($authHeader, 'Bearer %s');

if (!$jwt) {
    json_response(['status' => 'error', 'message' => 'JWT not found in Authorization header'], 401);
}

try {
    $decoded = JWT::decode($jwt, new Key(JWT_SECRET, 'HS256'));
    $user_id = $decoded->data->id; // Get user ID from token
} catch (Exception $e) {
    json_response(['status' => 'error', 'message' => 'Invalid token: ' . $e->getMessage()], 401);
}
// --- End of Middleware ---

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => 'error', 'message' => 'Invalid request method'], 405);
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (empty($data['name'])) {
    json_response(['status' => 'error', 'message' => 'Name cannot be empty'], 422);
}

// Sanitize data
$name = $conn->real_escape_string($data['name']);

// Update user in the database
$stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
$stmt->bind_param("si", $name, $user_id);

if (!$stmt->execute()) {
    json_response(['status' => 'error', 'message' => 'Update failed: ' . $stmt->error], 500);
}
$stmt->close();

// Fetch the updated user information to send back
$stmt = $conn->prepare("SELECT id, name, email, gender, interests FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$updated_user = $result->fetch_assoc();

json_response([
    'status' => 'success',
    'message' => 'Profile updated successfully!',
    'user' => $updated_user
]);

$stmt->close();
$conn->close();
?>
