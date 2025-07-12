<?php
// Start session securely
session_start([
    'cookie_lifetime' => 86400, // 1 day
    'cookie_secure'   => true,  // Requires HTTPS
    'cookie_httponly' => true,  // Prevent JS access
    'use_strict_mode' => true   // Better session security
]);

// Regenerate session ID to prevent fixation
session_regenerate_id(true);

// Clear all session data
$_SESSION = [];

// Destroy the session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

// Clear client-side cache
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

// Redirect to login with status message
$_SESSION['logout_message'] = 'You have been successfully logged out';
header("Location: login.php");
exit();
?>