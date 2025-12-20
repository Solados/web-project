<?php
// test_csv_output.php
echo "=== Testing CSV Output Format ===\n\n";

// Simulate a form submission
$_POST = [
    'type' => 'true_false',
    'dialect' => 'general',
    'question' => 'هل هذا سؤال تجريبي؟',
    'answer' => 'صحيح',
    'choices' => "صحيح\nخطأ"
];

$_SESSION = [
    'logged_in' => true,
    'user_name' => 'tester'
];

session_start();
$_SESSION['logged_in'] = true;
$_SESSION['user_name'] = 'tester';

// Create test file
$testFile = 'test_output.csv';

// Test the writeCsvRow function
function writeCsvRow($filePath, $row, $isFirstRow = false) {
    $line = '';
    
    if ($isFirstRow) {
        $line = "\xEF\xBB\xBF";
    }
    
    $values = [];
    foreach ($row as $value) {
        $value = str_replace('"', '""', $value);
        $values[] = '"' . $value . '"';
    }
    
    $line .= implode(',', $values) . "\n";
    return file_put_contents($filePath, $line, FILE_APPEND);
}

// Test data
$testRow = [
    'Term' => 'سؤال_مستخدم',
    'Meaning_of_term' => 'سؤال مقدم من المستخدم',
    'Dialect type' => 'general',
    'Location_Recognition_question' => '',
    'Cultural_Interpretation_question' => '',
    'Contextual_Usage_question' => '',
    'Fill_in_Blank_question' => '',
    'True_False_question' => "السؤال: هل هذا سؤال تجريبي؟\nأ) صحيح\nب) خطأ\nالإجابة الصحيحة: صحيح\nتمت الإضافة بواسطة: tester\nتاريخ الإضافة: 2025-12-20 15:17:01",
    'Meaning_question' => '',
    'Added_by' => 'tester',
    'Date_added' => '2025-12-20 15:17:01',
    'Status' => 'pending'
];

// Write test file
$headers = array_keys($testRow);
$headerRow = array_combine($headers, $headers);

writeCsvRow($testFile, $headerRow, true);
writeCsvRow($testFile, $testRow);

echo "Test file created: $testFile\n";
echo "File size: " . filesize($testFile) . " bytes\n\n";

// Display file content
echo "=== File Content ===\n";
$content = file_get_contents($testFile);
echo htmlspecialchars($content) . "\n\n";

// Parse and verify
echo "=== Parsing Test ===\n";
$lines = file($testFile);
echo "Lines: " . count($lines) . "\n\n";

for ($i = 0; $i < count($lines); $i++) {
    echo "Line " . ($i+1) . ":\n";
    $data = str_getcsv($lines[$i]);
    echo "  Columns: " . count($data) . "\n";
    
    if ($i == 0) {
        echo "  [HEADER]\n";
        foreach ($data as $j => $col) {
            echo "    [$j] $col\n";
        }
    } else {
        echo "  [DATA]\n";
        foreach ($data as $j => $value) {
            if (!empty($value)) {
                $header = str_getcsv($lines[0]);
                $colName = $header[$j] ?? "Column $j";
                echo "    $colName: '" . substr($value, 0, 50) . (strlen($value) > 50 ? "..." : "") . "'\n";
            }
        }
    }
    echo "\n";
}

// Clean up
unlink($testFile);
echo "Test file cleaned up\n";
?>