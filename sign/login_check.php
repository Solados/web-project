<?php
// Configure session properly with persistent cookies
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 2592000); // 30 days
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

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
        exit();
    }

    $email = trim(htmlspecialchars($_POST['email']));
    $password = $_POST['password']; 

    // Resolve CSV path in the project `data/` folder
    $filename = dirname(__DIR__) . '/data/user_data.csv';
    
    // Check if file exists
    if (!file_exists($filename)) {
        echo json_encode(['success' => false, 'message' => 'Error: No user accounts found.']);
        exit();
    }

    // Open file in read mode
    $file = fopen($filename, 'r');
    if ($file === false) {
        echo json_encode(['success' => false, 'message' => 'Unable to open user data file.']);
        exit();
    }

    $userFound = false;
    $isFirstRow = true;
    $userData = [];

    while (($row = fgetcsv($file)) !== FALSE) {
        // Skip header row
        if ($isFirstRow) {
            $isFirstRow = false;
            continue;
        }

        // Check if email and password match
        // row[0] is name, row[1] is email, row[2] is password hash
        if (isset($row[1]) && isset($row[2]) && 
            trim($row[1]) === $email && 
            password_verify($password, $row[2])) {
            $userFound = true;
            $userData = $row;
            break;
        }
    }
    fclose($file);

    if ($userFound) {
        // Store all user data in session
        $_SESSION['user_id'] = md5($email . time()); // Create unique user ID
        $_SESSION['user_name'] = $userData[0] ?? '';
        $_SESSION['user_email'] = $email;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        
        // Also set persistent cookies
        setcookie('user_id', $_SESSION['user_id'], time() + 2592000, '/');;
        setcookie('user_email', $email, time() + 2592000, '/');;
        setcookie('user_name', $userData[0] ?? '', time() + 2592000, '/');;
        setcookie('logged_in', '1', time() + 2592000, '/');;
        
        echo json_encode(['success' => true, 'message' => 'Login successful.', 'redirect' => '../index.php']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Invalid email or password.']);
        exit();
    }
}
?>