<?php
// Enable all error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

// Start session and check login
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

// Get form data
$question = $_POST['question'] ?? '';
$answer = $_POST['answer'] ?? '';
$choices = $_POST['choices'] ?? '';
$question_type = $_POST['question_type'] ?? '';
$domain = $_POST['domain'] ?? '';
$category = $_POST['category'] ?? '';

// Validate required fields
if (empty($question) || empty($answer) || empty($question_type) || empty($domain) || empty($category)) {
    echo json_encode([
        'success' => false, 
        'message' => 'All required fields must be filled',
        'debug' => $_POST // Show what was received
    ]);
    exit;
}

// Sanitize inputs
$question = trim($question);
$answer = trim($answer);
$choices = trim($choices);
$question_type = trim($question_type);
$domain = trim($domain);
$category = trim($category);

// Format choices - use "–" if empty for open-ended questions
if (empty($choices)) {
    $choices = '–';
}

// Determine CSV file path
$baseDir = dirname(__DIR__);
$dataDir = $baseDir . '/data';

// Check if data directory exists
if (!is_dir($dataDir)) {
    if (!mkdir($dataDir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Could not create data directory']);
        exit;
    }
}

// Use a single file for all user questions
$csvFile = $dataDir . '/UserQuestions.csv';

// Prepare CSV row (matching your CSV format exactly)
$csvRow = [
    $question,
    $choices,
    $answer,
    $question_type,
    $domain,
    $category,
    'Added by: ' . ($_SESSION['user_name'] ?? 'User'),
    'Date added: ' . date('Y-m-d H:i:s'),
    'Status: pending'
];

// Headers matching your CSV format
$headers = ['Question', 'Choices', 'Answer', 'Question Type', 'Domain', 'Category', 'Added by', 'Date added', 'Status'];

// Check if file exists
$fileExists = file_exists($csvFile);

// Open file for writing
$fp = fopen($csvFile, 'a');
if ($fp === false) {
    echo json_encode(['success' => false, 'message' => 'Could not open file for writing']);
    exit;
}

// Write headers if file is new
if (!$fileExists || filesize($csvFile) == 0) {
    fwrite($fp, "\xEF\xBB\xBF"); // UTF-8 BOM for Arabic/English support
    fputcsv($fp, $headers);
}

// Write the data row
$written = fputcsv($fp, $csvRow);
fclose($fp);

if ($written === false) {
    echo json_encode(['success' => false, 'message' => 'Could not write data to file']);
    exit;
}

// Success
echo json_encode([
    'success' => true, 
    'message' => 'Question added successfully! It will be reviewed soon.',
    'debug' => [
        'file' => $csvFile,
        'row_added' => $csvRow
    ]
]);
?>