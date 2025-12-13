<?php
// Start session with same settings as login
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 2592000);
    ini_set('session.gc_maxlifetime', 2592000);
    
    session_set_cookie_params([
        'lifetime' => 2592000,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Check for logout request
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Clear session
    $_SESSION = [];
    
    // Clear cookies
    setcookie('user_id', '', time() - 3600, '/');
    setcookie('user_email', '', time() - 3600, '/');
    setcookie('user_name', '', time() - 3600, '/');
    setcookie('logged_in', '', time() - 3600, '/');
    setcookie('PHPSESSID', '', time() - 3600, '/');
    
    // Destroy session
    session_destroy();
    
    // Redirect to login page
    header('Location: Signup_Login_Form.html');
    exit();
}

// Validate session activity (prevent session fixation)
if (isset($_SESSION['last_activity'])) {
    $inactive = time() - $_SESSION['last_activity'];
    $max_inactive = 2592000; // 30 days
    
    if ($inactive > $max_inactive) {
        // Session expired
        $_SESSION = [];
        session_destroy();
        header('Location: Signup_Login_Form.html');
        exit();
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login
    header('Location: Signup_Login_Form.html');
    exit();
}

// User is authenticated - store user data for dashboard use
$user_data = [
    'user_id' => $_SESSION['user_id'] ?? '',
    'user_name' => $_SESSION['user_name'] ?? '',
    'user_email' => $_SESSION['user_email'] ?? '',
    'login_time' => $_SESSION['login_time'] ?? 0
];
?>