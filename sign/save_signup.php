<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Get form data
    $fullname = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    //file name
    $filename = dirname(__DIR__) .'/data/user_data.csv';
    
    // Check if file exists
    $fileExists = file_exists($filename);
    
    // Open file in read mode
    $file = fopen($filename, 'a');
    if ($file === false) {
        exit('Unable to open data file for writing.');
    }
    
    // Add headers if file is new
    if (!$fileExists) {
        fputcsv($file, [
            'Full Name', 
            'Email', 
            'Password Hash', 
        ]);
    }
    
 
    fputcsv($file, [
        $fullname,
        $email,
        $password
    ]);
    fclose($file);
     $emailExists = false;
    if (file_exists($filename)) {
        $file = fopen($filename, 'r');
        while (($row = fgetcsv($file)) !== FALSE) {
            if (isset($row[1]) && $row[1] === $email) {
                $emailExists = true;
                break;
            }
        }
      

    if ($emailExists) {
        echo "Error: Email already registered.";
    } else {
        fclose($file);
        exit('Unable to lock data file.');
    }

    fclose($file);

    // Start session and redirect to root index
    session_start();
    $_SESSION['user_email'] = $email_raw;
    header('Location: /index.html');
    exit();

}
}