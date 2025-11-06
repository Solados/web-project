<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        exit('Email and password are required.');
    }

    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; 

    $filename = __DIR__ . '/data/user_data.csv';
    
    // Check if file exists
    if (!file_exists($filename)) {
        exit('Error: No user accounts found.');
    }

    // Open file in read mode
    $file = fopen($filename, 'r');
    if ($file === false) {
        exit('Unable to open user data file.');
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
        // Success! You might want to:
        // 1. Start a session
        session_start();
        $_SESSION['user_email'] = $email;
        // 2. Redirect to a dashboard or home page
        header('Location: index.html');
        exit();
    } else {
        echo "Error: Invalid email or password.";
    }
}