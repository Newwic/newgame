<?php
// get_reviews.php
// GET params: product (optional)
// Returns JSON: { success: true, avg: float, count: int, reviews: [...] }
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/db_local.php';

$product = isset($_GET['product']) ? $conn->real_escape_string($_GET['product']) : 'ข้าวมันไก่';

$res = $conn->query("SELECT COUNT(*) as cnt, AVG(rating) as avg FROM reviews WHERE product = '" . $product . "'");
$meta = ['count' => 0, 'avg' => 0];
if ($res) {
    if ($row = $res->fetch_assoc()) {
        $meta['count'] = intval($row['cnt']);
        $meta['avg'] = $row['avg'] !== null ? round(floatval($row['avg']), 2) : 0;
    }
}

$list = [];
$items = $conn->query("SELECT id, name, rating, comment, created_at FROM reviews WHERE product = '" . $product . "' ORDER BY created_at DESC LIMIT 10");
if ($items) {
    while ($r = $items->fetch_assoc()) {
        $list[] = [
            'id' => intval($r['id']),
            'name' => $r['name'],
            'rating' => intval($r['rating']),
            'comment' => $r['comment'],
            'created_at' => $r['created_at']
        ];
    }
}

echo json_encode(['success' => true, 'avg' => $meta['avg'], 'count' => $meta['count'], 'reviews' => $list]);
