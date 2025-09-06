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
$errors = [];
if (empty($data['name'])) {
    $errors['name'] = 'Name is required';
}
if (empty($data['email'])) {
    $errors['email'] = 'Email is required';
} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email format';
}
if (empty($data['password'])) {
    $errors['password'] = 'Password is required';
}
if (empty($data['gender'])) {
    $errors['gender'] = 'Gender is required';
}

if (!empty($errors)) {
    json_response(['status' => 'error', 'message' => 'Validation failed', 'errors' => $errors], 422);
}

// Sanitize data
$name = $conn->real_escape_string($data['name']);
$email = $conn->real_escape_string($data['email']);
$gender = $conn->real_escape_string($data['gender']);
$interests = json_encode($data['interests'] ?? []); // Store interests as JSON string

// Hash the password
$password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    json_response(['status' => 'error', 'message' => 'This email is already registered.'], 409);
}
$stmt->close();

// Insert new user into the database
$stmt = $conn->prepare("INSERT INTO users (name, email, password, gender, interests) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $password_hash, $gender, $interests);

if ($stmt->execute()) {
    json_response(['status' => 'success', 'message' => 'Registration successful!']);
} else {
    json_response(['status' => 'error', 'message' => 'Registration failed: ' . $stmt->error], 500);
}

$stmt->close();
$conn->close();
?>