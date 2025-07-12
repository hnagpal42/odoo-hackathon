<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/db_connect.php';

// In production: Query database
echo json_encode([
    ['id' => 1, 'name' => 'Python', 'type' => 'offered'],
    ['id' => 2, 'name' => 'Graphic Design', 'type' => 'wanted']
]);
?>