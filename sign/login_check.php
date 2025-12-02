<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
        exit();
    }

    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; 

    // Resolve CSV path in the project `data/` folder (one level above this sign/ directory)
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

    while (($row = fgetcsv($file)) !== FALSE) {
        // Skip header row
        if ($isFirstRow) {
            $isFirstRow = false;
            continue;
        }

        // Check if email and password match
        // row[1] is email, row[2] is password hash
        if (isset($row[1]) && isset($row[2]) && 
            $row[1] === $email && 
            password_verify($password, $row[2])) {
            $userFound = true;
            break;
        }
    }
    fclose($file);

    if ($userFound) {
        // Success! Start a session
        session_start();
        $_SESSION['user_email'] = $email;
        echo json_encode(['success' => true, 'message' => 'Login successful.']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: Invalid email or password.']);
        exit();
    }
}