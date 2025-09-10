<?php
// update_profile.php
// Accepts multipart/form-data: name, avatar (file), email (optional for lookup)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/db_local.php';

// For simplicity we expect client to include 'email' to identify the user.
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
$name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : null;

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email is required to update profile']);
    exit;
}

// Check user exists. The project's DB uses `avatar` as the column name in many installs;
// select avatar AS avatar_url so frontend receives a consistent key.
$res = $conn->query("SELECT id, name, email, avatar AS avatar_url FROM users WHERE email = '" . $email . "' LIMIT 1");
if (!$res || $res->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$user = $res->fetch_assoc();
$userId = intval($user['id']);

$avatarUrl = isset($user['avatar_url']) ? $user['avatar_url'] : null;
// Handle avatar upload
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $uploadsDir = __DIR__ . '/uploads/avatars';
    if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0777, true);
    $tmp = $_FILES['avatar']['tmp_name'];
    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $fname = 'avatar_' . $userId . '_' . time() . '.' . $ext;
    $dest = $uploadsDir . '/' . $fname;
        if (move_uploaded_file($tmp, $dest)) {
        // Build absolute URL to Apache-served API so dev server (webpack/vite) on :8080 doesn't try to serve it
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
        $avatarUrl = $protocol . '://' . $host . '/api/uploads/avatars/' . $fname;
    }
}
// Update name and avatar (DB column is often `avatar` in this project); update whichever exists.
$stmt = $conn->prepare("UPDATE users SET name = ?, avatar = ? WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$bind = $stmt->bind_param('ssi', $name, $avatarUrl, $userId);
if ($bind === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'bind_param failed']);
    $stmt->close();
    exit;
}

$exec = $stmt->execute();
if (!$exec) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
    $stmt->close();
    exit;
}
$stmt->close();

// Return updated user row
$res2 = $conn->query("SELECT id, name, email, avatar AS avatar_url FROM users WHERE id = " . $userId . " LIMIT 1");
if ($res2 && $row = $res2->fetch_assoc()) {
    // Ensure the returned user object uses 'avatar_url' key for frontend compatibility
    if (!isset($row['avatar_url'])) $row['avatar_url'] = null;
    echo json_encode(['success' => true, 'user' => $row, 'message' => 'Profile updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not retrieve updated user']);
}
