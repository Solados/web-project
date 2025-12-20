<?php
// save_question.php - FIXED MULTILINE CSV VERSION
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'يجب تسجيل الدخول أولاً']);
    exit;
}

// Get form data
$type = $_POST['type'] ?? '';
$dialect = $_POST['dialect'] ?? '';
$question = $_POST['question'] ?? '';
$answer = $_POST['answer'] ?? '';
$choices = $_POST['choices'] ?? '';
$user_name = $_SESSION['user_name'] ?? 'مستخدم';

// Log for debugging
$logMessage = "=== Save Question Request ===\n";
$logMessage .= "Type: $type\n";
$logMessage .= "Dialect: $dialect\n";
$logMessage .= "Question: $question\n";
$logMessage .= "Answer: $answer\n";
$logMessage .= "Choices: $choices\n";
$logMessage .= "User: $user_name\n\n";
file_put_contents('save_debug.log', $logMessage, FILE_APPEND);

// Validate
if (empty($type) || empty($dialect) || empty($question) || empty($answer)) {
    echo json_encode(['success' => false, 'message' => 'جميع الحقول المطلوبة يجب تعبئتها']);
    exit;
}

// Sanitize (but preserve newlines)
$question = trim($question);
$answer = trim($answer);
$choices = trim($choices);

// Format question block (CORRECT FORMAT) - keep newlines
$questionBlock = "السؤال: " . $question . "\n";

if (!empty($choices)) {
    // Format choices as أ) choice1\nب) choice2\nج) choice3\nد) choice4
    $choicesArray = explode("\n", $choices);
    
    // Arabic letters in correct order
    $arabicLetters = ['أ', 'ب', 'ج', 'د'];
    
    foreach ($choicesArray as $index => $choice) {
        $choice = trim($choice);
        if (!empty($choice) && isset($arabicLetters[$index])) {
            $questionBlock .= $arabicLetters[$index] . ') ' . $choice . "\n";
        }
    }
}

$questionBlock .= "الإجابة الصحيحة: " . $answer . "\n";
$questionBlock .= "تمت الإضافة بواسطة: " . $user_name . "\n";
$questionBlock .= "تاريخ الإضافة: " . date('Y-m-d H:i:s');

// Determine CSV column based on question type
$typeColumnMap = [
    'true_false' => 'True_False_question',
    'location' => 'Location_Recognition_question',
    'cultural' => 'Cultural_Interpretation_question',
    'contextual' => 'Contextual_Usage_question',
    'fill_blank' => 'Fill_in_Blank_question',
    'meaning' => 'Meaning_question'
];

$questionColumn = $typeColumnMap[$type] ?? 'True_False_question';

// Prepare FULL 12-column CSV row
$csvRow = [
    'Term' => 'سؤال_مستخدم',
    'Meaning_of_term' => 'سؤال مقدم من المستخدم',
    'Dialect type' => $dialect,
    'Location_Recognition_question' => '',
    'Cultural_Interpretation_question' => '',
    'Contextual_Usage_question' => '',
    'Fill_in_Blank_question' => '',
    'True_False_question' => '',
    'Meaning_question' => '',
    'Added_by' => $user_name,
    'Date_added' => date('Y-m-d H:i:s'),
    'Status' => 'pending'
];

// Put question in the correct column
$csvRow[$questionColumn] = $questionBlock;

// File path
$baseDir = dirname(__DIR__);
$dataDir = $baseDir . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$csvFile = $dataDir . '/UserQuestions-ar.csv';

// Custom CSV writing function to handle multiline content properly
function writeCsvRow($filePath, $row, $isFirstRow = false) {
    $line = '';
    
    // If first row, write BOM
    if ($isFirstRow) {
        $line = "\xEF\xBB\xBF";
    }
    
    // Build CSV line manually to ensure proper formatting
    $values = [];
    foreach ($row as $value) {
        // Escape quotes and wrap in quotes
        $value = str_replace('"', '""', $value);
        
        // Always wrap in quotes to handle commas and newlines
        $values[] = '"' . $value . '"';
    }
    
    $line .= implode(',', $values) . "\n";
    
    // Write to file
    return file_put_contents($filePath, $line, FILE_APPEND);
}

// Check if file exists and has content
$fileExists = file_exists($csvFile);
$fileSize = $fileExists ? filesize($csvFile) : 0;

if (!$fileExists || $fileSize == 0) {
    // Write headers first
    $headers = [
        'Term', 'Meaning_of_term', 'Dialect type',
        'Location_Recognition_question', 'Cultural_Interpretation_question',
        'Contextual_Usage_question', 'Fill_in_Blank_question',
        'True_False_question', 'Meaning_question',
        'Added_by', 'Date_added', 'Status'
    ];
    
    $headerRow = array_combine($headers, $headers);
    $written = writeCsvRow($csvFile, $headerRow, true);
    
    if ($written === false) {
        echo json_encode(['success' => false, 'message' => 'تعذر كتابة رأس الملف']);
        exit;
    }
}

// Write data row
$written = writeCsvRow($csvFile, $csvRow);

if ($written === false) {
    echo json_encode(['success' => false, 'message' => 'تعذر كتابة البيانات إلى الملف']);
    exit;
}

// Verify the file
if (file_exists($csvFile)) {
    $newSize = filesize($csvFile);
    $lines = file($csvFile);
    
    $logMessage = "File written successfully\n";
    $logMessage .= "File size: $newSize bytes\n";
    $logMessage .= "Total lines: " . count($lines) . "\n";
    
    // Log last line for verification
    if (count($lines) > 1) {
        $lastLine = $lines[count($lines) - 1];
        $logMessage .= "Last line (first 100 chars): " . substr($lastLine, 0, 100) . "...\n";
        
        // Parse last line to verify
        $data = str_getcsv($lastLine);
        $logMessage .= "Columns in last line: " . count($data) . "\n";
        
        // Check question column
        $headers = str_getcsv($lines[0]);
        foreach ($headers as $i => $header) {
            if ($header === $questionColumn && isset($data[$i])) {
                $logMessage .= "Question column found at index $i\n";
                if (strpos($data[$i], 'السؤال:') !== false) {
                    $logMessage .= "✓ Question text found in CSV\n";
                }
            }
        }
    }
    
    file_put_contents('save_debug.log', $logMessage, FILE_APPEND);
    
    echo json_encode([
        'success' => true, 
        'message' => 'تم إضافة السؤال بنجاح وسيتم مراجعته قريباً',
        'debug' => [
            'file' => $csvFile,
            'file_size' => $newSize,
            'lines_count' => count($lines),
            'question_column' => $questionColumn,
            'choices_provided' => !empty($choices)
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'تمت المعالجة لكن الملف لم يتم إنشاؤه']);
}
?>