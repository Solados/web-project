<?php
// Enable all error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

// Start session and check login
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'يجب تسجيل الدخول أولاً']);
    exit;
}

// Log the incoming request
$log = "=== " . date('Y-m-d H:i:s') . " ===\n";
$log .= "POST Data: " . print_r($_POST, true) . "\n";
$log .= "Session: " . print_r($_SESSION, true) . "\n";
$log .= "Files: " . print_r($_FILES, true) . "\n\n";
file_put_contents('debug.log', $log, FILE_APPEND);

// Get form data
$type = $_POST['type'] ?? '';
$dialect = $_POST['dialect'] ?? '';
$question = $_POST['question'] ?? '';
$answer = $_POST['answer'] ?? '';
$choices = $_POST['choices'] ?? '';
$user_name = $_SESSION['user_name'] ?? 'مستخدم';

// Validate required fields
if (empty($type) || empty($dialect) || empty($question) || empty($answer)) {
    echo json_encode([
        'success' => false, 
        'message' => 'جميع الحقول المطلوبة يجب تعبئتها',
        'debug' => ['type' => $type, 'dialect' => $dialect, 'question_empty' => empty($question), 'answer_empty' => empty($answer)]
    ]);
    exit;
}

// Sanitize inputs (basic sanitization)
$question = htmlspecialchars(trim($question), ENT_QUOTES, 'UTF-8');
$answer = htmlspecialchars(trim($answer), ENT_QUOTES, 'UTF-8');
$choices = htmlspecialchars(trim($choices), ENT_QUOTES, 'UTF-8');

// Format the question block for CSV
$questionBlock = "السؤال: " . $question . "\n";

if (!empty($choices)) {
    $questionBlock .= $choices . "\n";
}

$questionBlock .= "الإجابة الصحيحة: " . $answer . "\n";
$questionBlock .= "تمت الإضافة بواسطة: " . $user_name . "\n";
$questionBlock .= "تاريخ الإضافة: " . date('Y-m-d H:i:s');

// Determine CSV file path
// Go up one level from current directory, then into data folder
$baseDir = dirname(__DIR__); // Go up one level from where save_question.php is located
$dataDir = $baseDir . '/data';

// Check if data directory exists
if (!is_dir($dataDir)) {
    // Try to create it
    if (!mkdir($dataDir, 0755, true)) {
        echo json_encode([
            'success' => false, 
            'message' => 'تعذر إنشاء مجلد البيانات',
            'debug' => ['dataDir' => $dataDir, 'baseDir' => $baseDir]
        ]);
        exit;
    }
}

$csvFile = $dataDir . '/UserQuestions-ar.csv';

// Log file path
file_put_contents('debug.log', "CSV File Path: " . $csvFile . "\n", FILE_APPEND);

// Prepare CSV row
$csvRow = [
    'Dialect type' => $dialect,
    $type => $questionBlock,
    'Added by' => $user_name,
    'Date added' => date('Y-m-d H:i:s'),
    'Status' => 'pending'
];

// Check if file exists and get headers
$headers = array_keys($csvRow);
$fileExists = file_exists($csvFile);

// Open file for writing (append mode)
$fp = fopen($csvFile, 'a');

if ($fp === false) {
    echo json_encode([
        'success' => false, 
        'message' => 'تعذر فتح ملف البيانات للكتابة',
        'debug' => ['file' => $csvFile, 'error' => error_get_last()]
    ]);
    exit;
}

// If file doesn't exist or is empty, write headers first
if (!$fileExists || filesize($csvFile) == 0) {
    // Write UTF-8 BOM for Arabic support
    fwrite($fp, "\xEF\xBB\xBF");
    fputcsv($fp, $headers);
}

// Write the data row
$written = fputcsv($fp, $csvRow);
fclose($fp);

if ($written === false) {
    echo json_encode([
        'success' => false, 
        'message' => 'تعذر كتابة البيانات إلى الملف',
        'debug' => ['csvRow' => $csvRow]
    ]);
    exit;
}

// Verify the file was written
if (file_exists($csvFile)) {
    $fileContent = file_get_contents($csvFile);
    file_put_contents('debug.log', "File written successfully. Size: " . filesize($csvFile) . " bytes\n", FILE_APPEND);
    
    echo json_encode([
        'success' => true, 
        'message' => 'تم إضافة السؤال بنجاح وسيتم مراجعته قريباً',
        'debug' => [
            'file' => $csvFile,
            'file_size' => filesize($csvFile),
            'row' => $csvRow
        ]
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'تمت المعالجة لكن الملف لم يتم إنشاؤه',
        'debug' => ['file' => $csvFile]
    ]);
}
?>