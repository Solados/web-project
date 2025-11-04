<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Get form data
    $fullname = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    //file name
    $filename = __DIR__ .'/data/user_data.csv';
    
    // Check if file exists
    $fileExists = file_exists($filename);
    
    // Open file in append mode
    $file = fopen($filename, 'a');
    
    // Add headers if file is new
    if (!$fileExists) {
        fputcsv($file, [
            'Full Name', 
            'Email', 
            'Password Hash', 
        ]);
    }
    
   

    if ($emailExists) {
        echo "Error: Email already registered.";
    } else {
        echo "Signup successful!";
    }
    
    fclose($file);

     // Add user data
    fputcsv($file, [
        $fullname,
        $email,
        $password
    ]);
     $emailExists = false;
    if (file_exists($filename)) {
        $file = fopen($filename, 'r');
        while (($row = fgetcsv($file)) !== FALSE) {
            if (isset($row[1]) && $row[1] === $email) {
                $emailExists = true;
                break;
            }
        }
    
    fclose($file);
}
}