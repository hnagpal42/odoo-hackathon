<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['user']) || !isset($_GET['action']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$requestId = $_GET['id'];
$action = $_GET['action'];

try {
    // Verify request belongs to user
    $stmt = $pdo->prepare("SELECT * FROM swap_requests WHERE id = ? AND to_user = ?");
    $stmt->execute([$requestId, $_SESSION['user']['id']]);
    $request = $stmt->fetch();
    
    if (!$request) {
        $_SESSION['error'] = 'Request not found or unauthorized';
        header("Location: requests.php");
        exit();
    }
    
    // Update status
    $newStatus = ($action === 'accept') ? 'accepted' : 'rejected';
    $updateStmt = $pdo->prepare("UPDATE swap_requests SET status = ? WHERE id = ?");
    $updateStmt->execute([$newStatus, $requestId]);
    
    $_SESSION['success'] = "Request has been $newStatus";
    header("Location: requests.php");
    exit();

} catch (PDOException $e) {
    error_log("Request processing error: " . $e->getMessage());
    $_SESSION['error'] = 'Error processing request';
    header("Location: requests.php");
    exit();
}