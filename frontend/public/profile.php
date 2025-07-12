<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

// Redirect unauthorized users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user profile from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$profile = $stmt->fetch();

// Fetch skills
$skills_offered = [];
$skills_wanted = [];
$skills_stmt = $pdo->prepare("SELECT name, type FROM user_skills WHERE user_id = ?");
$skills_stmt->execute([$_SESSION['user_id']]);
while ($skill = $skills_stmt->fetch()) {
    if ($skill['type'] === 'offered') {
        $skills_offered[] = $skill['name'];
    } else {
        $skills_wanted[] = $skill['name'];
    }
}
?>

<?php
// Display messages
if (isset($_SESSION['profile_error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['profile_error']) . '</div>';
    unset($_SESSION['profile_error']);
}
if (isset($_SESSION['profile_success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['profile_success']) . '</div>';
    unset($_SESSION['profile_success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Skill Swap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .skill-hint {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .profile-pic-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Skill Swap</a>
        <div class="d-flex">
            <a href="dashboard.php" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
            <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-secondary mb-4">
                <div class="card-body">
                    <h2 class="card-title">Edit Your Profile</h2>
                    
                    <?php if (isset($_SESSION['profile_message'])): ?>
                        <div class="alert alert-<?= $_SESSION['profile_message_type'] ?? 'success' ?>">
                            <?= $_SESSION['profile_message'] ?>
                        </div>
                        <?php unset($_SESSION['profile_message'], $_SESSION['profile_message_type']); ?>
                    <?php endif; ?>

                    <form action="profile_save.php" method="POST" enctype="multipart/form-data">
                        <!-- Profile Photo -->
                        <div class="mb-4 text-center">
                            <img src="<?= htmlspecialchars($profile['profile_pic'] ?? 'assets/default-profile.png') ?>" 
                                 class="profile-pic-preview" id="profilePicPreview">
                            <div class="mt-2">
                                <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">
                                <button type="button" class="btn btn-sm btn-outline-light" 
                                        onclick="document.getElementById('photoInput').click()">
                                    Change Photo
                                </button>
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Full Name*</label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required>
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" 
                                   value="<?= htmlspecialchars($profile['location'] ?? '') ?>">
                        </div>

                        <!-- Bio -->
                        <div class="mb-3">
                            <label class="form-label">About You</label>
                            <textarea name="bio" class="form-control" rows="3"><?= 
                                htmlspecialchars($profile['bio'] ?? '') 
                            ?></textarea>
                        </div>

                        <!-- Skills Offered -->
                        <div class="mb-3">
                            <label class="form-label">Skills You Offer*</label>
                            <input type="text" name="skills_offered" class="form-control" 
                                   value="<?= htmlspecialchars(implode(', ', $skills_offered)) ?>" required>
                            <div class="skill-hint">Separate skills with commas (e.g., Photoshop, Excel, Python)</div>
                        </div>

                        <!-- Skills Wanted -->
                        <div class="mb-3">
                            <label class="form-label">Skills You Want*</label>
                            <input type="text" name="skills_wanted" class="form-control" 
                                   value="<?= htmlspecialchars(implode(', ', $skills_wanted)) ?>" required>
                            <div class="skill-hint">What would you like to learn?</div>
                        </div>

                        <!-- Availability -->
                        <div class="mb-3">
                            <label class="form-label">Availability*</label>
                            <select name="availability" class="form-select" required>
                                <option value="">Select availability</option>
                                <option value="Weekends" <?= ($profile['availability'] ?? '') === 'Weekends' ? 'selected' : '' ?>>Weekends</option>
                                <option value="Evenings" <?= ($profile['availability'] ?? '') === 'Evenings' ? 'selected' : '' ?>>Evenings</option>
                                <option value="Weekdays" <?= ($profile['availability'] ?? '') === 'Weekdays' ? 'selected' : '' ?>>Weekdays</option>
                                <option value="Flexible" <?= ($profile['availability'] ?? '') === 'Flexible' ? 'selected' : '' ?>>Flexible</option>
                            </select>
                        </div>

                        <!-- Public/Private -->
                        <div class="mb-4 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="public" id="public" 
                                   <?= ($profile['is_public'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="public">Make my profile public</label>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="dashboard.php" class="btn btn-outline-light me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Profile picture preview
document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('profilePicPreview').src = event.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>