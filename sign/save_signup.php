<?php
// Configure session properly
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400);

session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => false,  // Set to true if using HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
?>
<?php
if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Basic validation
$required = ['username', 'email', 'password'];
foreach ($required as $r) {
    if (empty($_POST[$r])) {
        http_response_code(400);
        exit("Missing required field: $r");
    }
}

$fullname = trim(htmlspecialchars($_POST['username']));
$email = trim(htmlspecialchars($_POST['email']));
$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$filename = dirname(__DIR__) . '/data/user_data.csv';

// Ensure data directory exists
if (!is_dir(dirname($filename))) {
    if (!mkdir(dirname($filename), 0755, true)) {
        http_response_code(500);
        exit('Unable to create data directory.');
    }
}

// Open (or create) file for read/write and acquire exclusive lock
$fh = fopen($filename, 'c+'); // create if missing
if ($fh === false) {
    http_response_code(500);
    exit('Unable to open data file.');
}

if (!flock($fh, LOCK_EX)) {
    fclose($fh);
    http_response_code(503);
    exit('Unable to lock data file.');
}

// File is locked. Read existing entries to check duplicates.
rewind($fh);
$emailExists = false;
$isFirstRow = true;
while (($row = fgetcsv($fh)) !== false) {
    // skip header row if present
    if ($isFirstRow && isset($row[0]) && stripos($row[0], 'Full') !== false) {
        $isFirstRow = false;
        continue;
    }
    $isFirstRow = false;
    if (isset($row[1]) && strcasecmp(trim($row[1]), $email) === 0) {
        $emailExists = true;
        break;
    }
}

if ($emailExists) {
    // release lock and respond
    flock($fh, LOCK_UN);
    fclose($fh);
    http_response_code(409);
    exit('Error: Email already registered.');
}

// Append header if file was empty
// Move to end for appending
fseek($fh, 0, SEEK_END);
// If file is empty, write headers first
$stat = fstat($fh);
if ($stat['size'] === 0) {
    // ADD ALL 5 COLUMNS
    fputcsv($fh, ['Full Name', 'Email', 'Password Hash', 'Quiz Record', 'Quiz Answered']);
}

// Write the new user WITH EMPTY QUIZ DATA
if (fputcsv($fh, [$fullname, $email, $passwordHash, '[]', '[]']) === false) {
    flock($fh, LOCK_UN);
    fclose($fh);
    http_response_code(500);
    exit('Failed to write to data file.');
}

fflush($fh);
flock($fh, LOCK_UN);
fclose($fh);

// Start session and redirect to root index
session_start();
$_SESSION['user_email'] = $email;
$_SESSION['logged_in'] = true;

// Redirect to homepage (relative path)
header('Location: ../index.html');
exit();
?>