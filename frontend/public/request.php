<?php
session_start();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

try {
    // Get sent requests (using your actual table structure)
    $sentStmt = $pdo->prepare("
        SELECT r.*, u.name as to_user_name 
        FROM swap_requests r
        JOIN users u ON r.to_user = u.id
        WHERE r.from_user = :user_id
        ORDER BY r.created_at DESC
    ");
    $sentStmt->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
    $sentStmt->execute();
    $sentRequests = $sentStmt->fetchAll();

    // Get received requests
    $receivedStmt = $pdo->prepare("
        SELECT r.*, u.name as from_user_name 
        FROM swap_requests r
        JOIN users u ON r.from_user = u.id
        WHERE r.to_user = :user_id
        ORDER BY r.created_at DESC
    ");
    $receivedStmt->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
    $receivedStmt->execute();
    $receivedRequests = $receivedStmt->fetchAll();

} catch (PDOException $e) {
    error_log("Database error in requests.php: " . $e->getMessage());
    $_SESSION['error'] = 'Error loading requests';
    $sentRequests = [];
    $receivedRequests = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Swap Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .request-card {
            background-color: #2c2f33;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-accepted { background-color: #28a745; }
        .badge-rejected { background-color: #dc3545; }
    </style>
</head>
<body class="bg-dark text-white">
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">Your Swap Requests</h2>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-secondary mb-4">
                <div class="card-header">
                    <h4>Sent Requests</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($sentRequests)): ?>
                        <p class="text-muted">No sent requests</p>
                    <?php else: ?>
                        <?php foreach ($sentRequests as $request): ?>
                            <div class="request-card p-3 mb-3">
                                <h5>To: <?= htmlspecialchars($request['to_user_name']) ?></h5>
                                <p><strong>Offered Skill:</strong> <?= htmlspecialchars($request['offered_skill']) ?></p>
                                <p><strong>Requested Skill:</strong> <?= htmlspecialchars($request['requested_skill'] ?? $request['skill_id']) ?></p>
                                <p><?= htmlspecialchars($request['message']) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge badge-<?= $request['status'] ?>">
                                        <?= ucfirst($request['status']) ?>
                                    </span>
                                    <small><?= date('M j, Y g:i a', strtotime($request['created_at'])) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card bg-secondary">
                <div class="card-header">
                    <h4>Received Requests</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($receivedRequests)): ?>
                        <p class="text-muted">No received requests</p>
                    <?php else: ?>
                        <?php foreach ($receivedRequests as $request): ?>
                            <div class="request-card p-3 mb-3">
                                <h5>From: <?= htmlspecialchars($request['from_user_name']) ?></h5>
                                <p><strong>Offered Skill:</strong> <?= htmlspecialchars($request['offered_skill']) ?></p>
                                <p><strong>Requested Skill:</strong> <?= htmlspecialchars($request['requested_skill'] ?? $request['skill_id']) ?></p>
                                <p><?= htmlspecialchars($request['message']) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge badge-<?= $request['status'] ?>">
                                        <?= ucfirst($request['status']) ?>
                                    </span>
                                    <small><?= date('M j, Y g:i a', strtotime($request['created_at'])) ?></small>
                                </div>
                                <?php if ($request['status'] === 'pending'): ?>
                                    <div class="mt-3">
                                        <a href="process_request.php?action=accept&id=<?= $request['id'] ?>" 
                                           class="btn btn-success btn-sm">Accept</a>
                                        <a href="process_request.php?action=reject&id=<?= $request['id'] ?>" 
                                           class="btn btn-danger btn-sm ms-2">Reject</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>