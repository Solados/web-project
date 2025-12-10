<?php
// Start session with SAME settings
if (session_status() === PHP_SESSION_NONE) {
    // Use same cookie params as login_check.php
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/web-project/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Debug - REMOVE AFTER FIXING
echo "<!-- DEBUG: Session check -->";
echo "<!-- Session ID: " . session_id() . " -->";
echo "<!-- Logged in: " . (isset($_SESSION['logged_in']) ? 'YES' : 'NO') . " -->";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // JavaScript redirect as fallback
    echo '<script>window.location.href = "../Signup_Login_Form.html";</script>';
    exit();
}
?>