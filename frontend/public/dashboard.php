<?php
session_start();
require_once __DIR__ . '/../includes/header.php';

// Redirect unauthorized users
if (!isset($_SESSION['user'])) {
    $_SESSION['redirect'] = 'dashboard.php';
    header("Location: login.php");
    exit();
}

// Display success/error messages
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            '.$_SESSION['success'].'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            '.$_SESSION['error'].'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skill Swap - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .skill-tag {
            background-color: #17a2b8;
            color: white;
            border-radius: 10px;
            padding: 3px 10px;
            margin-right: 5px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 5px;
        }
        .profile-card {
            background-color: #2c2f33;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: white;
            transition: transform 0.2s;
        }
        .profile-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .availability-badge {
            background-color: #28a745;
        }
        .initial-avatar {
            width: 120px;
            height: 120px;
            font-size: 3rem;
            background-color: #0d6efd;
        }
    </style>
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Skill Swap</a>
        <div class="d-flex">
            <a href="profile.php" class="btn btn-outline-light me-2">My Profile</a>
            <a href="logout.php" class="btn btn-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <h2 class="mb-4">Find a Skill to Swap</h2>
            
            <!-- Search Bar -->
            <div class="card bg-secondary mb-4">
                <div class="card-body">
                    <form id="searchForm">
                        <div class="input-group">
                            <input type="text" id="skillSearch" class="form-control" 
                                   placeholder="Search by skill (e.g., Photoshop, Excel)">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Skills Container (Loaded via AJAX) -->
            <div id="skills-container">
                <!-- Loading placeholder -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Loading available skills...</p>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card bg-secondary mb-4">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="rounded-circle initial-avatar d-flex align-items-center justify-content-center mx-auto">
                            <span class="text-white">
                                <?= strtoupper(substr(htmlspecialchars($_SESSION['user']['name'] ?? 'U'), 0, 1)) ?>
                            </span>
                        </div>
                    </div>
                    <h5><?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?></p>
                    <div class="mt-3">
                        <a href="profile.php" class="btn btn-outline-info btn-sm">Edit Profile</a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Requests Card -->
            <div class="card bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">Recent Requests</h5>
                    <div id="requests-container">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/dashboard.js"></script>
<script>
// Initialize Bootstrap tooltips and popovers
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>