<?php
// save_quiz_result.php
// Accepts POST { score, total, source? } and appends the quiz result to data/user_data.csv for the logged-in user

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$email = $_SESSION['user_email'] ?? '';
$score = isset($_POST['score']) ? intval($_POST['score']) : null;
$total = isset($_POST['total']) ? intval($_POST['total']) : null;
$source = isset($_POST['source']) ? trim($_POST['source']) : '';

if ($score === null || $total === null) {
    echo json_encode(['success' => false, 'message' => 'Missing score or total']);
    exit();
}

$data_file = dirname(__DIR__) . '/data/user_data.csv';
if (!file_exists($data_file)) {
    echo json_encode(['success' => false, 'message' => 'Data file not found']);
    exit();
}

// Read all rows and update in-memory
$rows = [];
$found = false;
if (($h = fopen($data_file, 'r')) !== false) {
    while (($r = fgetcsv($h)) !== false) {
        $rows[] = $r;
    }
    fclose($h);
}

if (count($rows) === 0) {
    echo json_encode(['success' => false, 'message' => 'Empty data file']);
    exit();
}

// Determine header and user row
$header = $rows[0];
for ($i = 1; $i < count($rows); $i++) {
    $row = $rows[$i];
    if (isset($row[1]) && trim($row[1]) === $email) {
        // Ensure columns exist up to index 4
        for ($c = count($row); $c <= 4; $c++) $row[$c] = $row[$c] ?? '';

        // parse quizrecord (col index 3) and quizanswered (index 4)
        $quiz_record = json_decode($row[3] ?? '[]', true);
        if (!is_array($quiz_record)) $quiz_record = [];

        $quiz_answered = json_decode($row[4] ?? '[]', true);
        if (!is_array($quiz_answered)) $quiz_answered = [];

        $entry = ['date' => date('c'), 'score' => $score, 'total' => $total];
        if ($source !== '') $entry['source'] = $source;

        $quiz_record[] = $entry;
        $quiz_answered[] = intval($score);

        $row[3] = json_encode($quiz_record, JSON_UNESCAPED_UNICODE);
        $row[4] = json_encode($quiz_answered, JSON_UNESCAPED_UNICODE);

        $rows[$i] = $row;
        $found = true;
        break;
    }
}

if (!$found) {
    echo json_encode(['success' => false, 'message' => 'User not found in data file']);
    exit();
}

// Write back safely
$tmp = $data_file . '.tmp';
if (($h = fopen($tmp, 'w')) === false) {
    echo json_encode(['success' => false, 'message' => 'Unable to write temp file']);
    exit();
}
foreach ($rows as $r) {
    fputcsv($h, $r);
}
fclose($h);

if (!rename($tmp, $data_file)) {
    // Attempt fallback atomic write
    file_put_contents($data_file, '');
    if (($h = fopen($data_file, 'w')) !== false) {
        foreach ($rows as $r) fputcsv($h, $r);
        fclose($h);
    }
}

echo json_encode(['success' => true, 'message' => 'Quiz result saved']);
exit();
?>
