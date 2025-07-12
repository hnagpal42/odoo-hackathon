<?php
session_start();

// Temporary dummy data (remove in production)
const DUMMY_CREDENTIALS = [
    'email' => 'user@example.com',
    'password' => 'password123' // Never store plain passwords in production
];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("HTTP/1.1 405 Method Not Allowed");
    exit("Invalid request method");
}

// Validate inputs
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if (!$email || empty($password)) {
    $_SESSION['error'] = 'Invalid email or password format';
    header("Location: login.php");
    exit();
}

// Authentication check
if ($email === DUMMY_CREDENTIALS['email'] && 
    $password === DUMMY_CREDENTIALS['password']) {
    
    $_SESSION['user'] = [
        'email' => $email,
        'name' => 'Demo User',
        'ip' => $_SERVER['REMOTE_ADDR'] // Basic security
    ];
    
    header("Location: dashboard.php");
    exit();
}

// Failed login
$_SESSION['error'] = 'Invalid credentials';
header("Location: login.php");
exit();
?>