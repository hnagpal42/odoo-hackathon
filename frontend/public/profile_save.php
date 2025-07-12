<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

// Security checks
if (!isset($_SESSION['user_id'])) {
    $_SESSION['profile_error'] = 'Please login to update profile';
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['profile_error'] = 'Invalid request method';
    header("Location: profile.php");
    exit();
}

// Input validation
$name = trim($_POST['name'] ?? '');
$location = trim($_POST['location'] ?? '');
$skills_offered = array_filter(array_map('trim', explode(',', $_POST['skills_offered'] ?? '')));
$skills_wanted = array_filter(array_map('trim', explode(',', $_POST['skills_wanted'] ?? '')));
$availability = $_POST['availability'] ?? 'Weekends';
$is_public = isset($_POST['public']) ? 1 : 0;

if (empty($name)) {
    $_SESSION['profile_error'] = 'Name is required';
    header("Location: profile.php");
    exit();
}

// Temporary session storage (replace with DB save)
$_SESSION['profile'] = [
    'name' => $name,
    'location' => $location,
    'skills_offered' => $skills_offered,
    'skills_wanted' => $skills_wanted,
    'availability' => $availability,
    'is_public' => $is_public
];

// Simple file upload handling (basic example)
if (!empty($_FILES['photo']['tmp_name'])) {
    $allowed_types = ['image/jpeg', 'image/png'];
    $file_type = $_FILES['photo']['type'];
    
    if (in_array($file_type, $allowed_types)) {
        $upload_dir = __DIR__ . '/../uploads/';
        $filename = 'profile_' . $_SESSION['user_id'] . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $filename)) {
            $_SESSION['profile']['photo'] = 'uploads/' . $filename;
        }
    }
}

$_SESSION['profile_success'] = 'Profile updated successfully!';
header("Location: profile.php");
exit();
?>