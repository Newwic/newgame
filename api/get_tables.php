<?php
// get_tables.php
// Returns JSON: { success: true, tables: [{id, occupied}, ...] }
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/db_local.php';

$res = $conn->query("SELECT id, occupied FROM `tables` ORDER BY id ASC");
$tables = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $tables[] = ['id' => intval($row['id']), 'occupied' => intval($row['occupied']) === 1];
    }
}

echo json_encode(['success' => true, 'tables' => $tables]);
