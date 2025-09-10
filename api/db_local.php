<?php
// db_local.php
// A small DB connector used by the demo API endpoints.
// It will try to include an existing db.php (if you already have one in /api), otherwise it falls back to default XAMPP credentials.

if (file_exists(__DIR__ . '/db.php')) {
    // If you already have a db.php created in your XAMPP api folder, include it.
    include_once __DIR__ . '/db.php';
    // Expect $conn or $mysqli to be defined in that file. Normalize to $conn (mysqli)
    if (isset($mysqli) && $mysqli instanceof mysqli) {
        $conn = $mysqli;
    }
    // else assume $conn exists
} else {
    $dbHost = '127.0.0.1';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'newgame';

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_errno) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'DB connection failed: ' . $conn->connect_error]);
        exit;
    }
}

// set utf8mb4
$conn->set_charset('utf8mb4');
