<?php
require_once 'db.php';

// Handle pre-flight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => 'error', 'message' => 'Invalid request method'], 405);
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

// Basic validation
if (empty($data['email']) || empty($data['password'])) {
    json_response(['status' => 'error', 'message' => 'Email and password are required.'], 422);
}

// Sanitize data
$email = $conn->real_escape_string($data['email']);
$password = $data['password'];

// Find user by email
$stmt = $conn->prepare("SELECT id, name, email, password, gender, interests FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    json_response(['status' => 'error', 'message' => 'Invalid email or password.'], 401);
}

$user = $result->fetch_assoc();

// Verify password
if (password_verify($password, $user['password'])) {
    // Password is correct
    
    // For a real application, generate a proper JWT token here.
    // This is just a placeholder.
    $token = base64_encode(random_bytes(32));

    // Don't send password back to client
    unset($user['password']);

    json_response([
        'status' => 'success',
        'message' => 'Login successful!',
        'token' => $token,
        'user' => $user
    ]);
} else {
    // Incorrect password
    json_response(['status' => 'error', 'message' => 'Invalid email or password.'], 401);
}

$stmt->close();
$conn->close();
?>