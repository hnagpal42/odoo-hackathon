<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/db_connect.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);

// Input validation
if (empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    exit;
}

// In production: Replace this with database check
$validEmail = 'user@example.com';
$validPassword = 'password123'; // In real apps, use password_verify()

if ($data['email'] === $validEmail && $data['password'] === $validPassword) {
    $_SESSION['user'] = [
        'email' => $data['email'],
        'name' => 'Demo User'
    ];
    echo json_encode(['success' => true]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}
?>