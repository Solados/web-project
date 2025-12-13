<?php
// User profile API - handles profile data operations
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

header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$email = $_SESSION['user_email'] ?? '';
$data_file = dirname(__DIR__) . '/data/user_data.csv';

// Handle different request types
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    
    if ($_GET['action'] === 'get_profile') {
        // Get user profile data
        if (!file_exists($data_file)) {
            echo json_encode(['success' => false, 'message' => 'Data file not found']);
            exit();
        }
        
        $file = fopen($data_file, 'r');
        if ($file === false) {
            echo json_encode(['success' => false, 'message' => 'Unable to open data file']);
            exit();
        }
        
        $isFirstRow = true;
        $profile_data = null;
        
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }
            
            if (isset($row[1]) && trim($row[1]) === $email) {
                $quiz_records = json_decode($row[3] ?? '[]', true) ?: [];
                $points_sum = 0;
                if (is_array($quiz_records)) {
                    foreach ($quiz_records as $rec) {
                        if (is_array($rec) && isset($rec['score'])) $points_sum += intval($rec['score']);
                        elseif (is_numeric($rec)) $points_sum += intval($rec);
                    }
                }

                $profile_data = [
                    'name' => $row[0] ?? '',
                    'email' => $row[1] ?? '',
                    'quizzes_completed' => is_array($quiz_records) ? count($quiz_records) : 0,
                    'points_earned' => $points_sum,
                    'achievements' => 0
                ];
                break;
            }
        }
        fclose($file);
        
        if ($profile_data) {
            echo json_encode(['success' => true, 'data' => $profile_data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
        exit();
    }
    
    if ($_GET['action'] === 'get_stats') {
        // Get user statistics
        if (!file_exists($data_file)) {
            echo json_encode(['success' => false, 'message' => 'Data file not found']);
            exit();
        }
        
        $file = fopen($data_file, 'r');
        if ($file === false) {
            echo json_encode(['success' => false, 'message' => 'Unable to open data file']);
            exit();
        }
        
        $isFirstRow = true;
        $stats = [
            'quizzes_completed' => 0,
            'points_earned' => 0,
            'achievements' => 0,
            'regions_explored' => 0
        ];
        
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }
            
            if (isset($row[1]) && trim($row[1]) === $email) {
                $quiz_data = json_decode($row[3] ?? '[]', true) ?: [];
                $points_sum = 0;
                if (is_array($quiz_data)) {
                    foreach ($quiz_data as $rec) {
                        if (is_array($rec) && isset($rec['score'])) $points_sum += intval($rec['score']);
                        elseif (is_numeric($rec)) $points_sum += intval($rec);
                    }
                }
                $stats['quizzes_completed'] = is_array($quiz_data) ? count($quiz_data) : 0;
                $stats['points_earned'] = $points_sum;
                break;
            }
        }
        fclose($file);
        
        echo json_encode(['success' => true, 'stats' => $stats]);
        exit();
    }
}

// Handle POST requests for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'update_profile') {
        // Validate inputs
        if (!isset($_POST['username']) || !isset($_POST['email'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit();
        }
        
        $new_username = trim(htmlspecialchars($_POST['username']));
        $new_email = trim(htmlspecialchars($_POST['email']));
        
        if (empty($new_username) || empty($new_email)) {
            echo json_encode(['success' => false, 'message' => 'Username and email cannot be empty']);
            exit();
        }
        
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit();
        }
        
        if (!file_exists($data_file)) {
            echo json_encode(['success' => false, 'message' => 'Data file not found']);
            exit();
        }
        
        // Read all data
        $file = fopen($data_file, 'r');
        if ($file === false) {
            echo json_encode(['success' => false, 'message' => 'Unable to open data file']);
            exit();
        }
        
        $rows = [];
        $found = false;
        
        while (($row = fgetcsv($file)) !== FALSE) {
            if (isset($row[1]) && trim($row[1]) === $email) {
                $row[0] = $new_username;
                $row[1] = $new_email;
                $found = true;
            }
            $rows[] = $row;
        }
        fclose($file);
        
        if (!$found) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit();
        }
        
        // Write updated data
        $file = fopen($data_file, 'w');
        if ($file === false) {
            echo json_encode(['success' => false, 'message' => 'Unable to write to data file']);
            exit();
        }
        
        foreach ($rows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
        
        // Update session
        $_SESSION['user_name'] = $new_username;
        $_SESSION['user_email'] = $new_email;
        
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        exit();
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
