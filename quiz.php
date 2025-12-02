<?php
// quiz.php
// Returns a JSON array of randomized quiz questions from CSV files in /data

header('Content-Type: application/json; charset=utf-8');

$source = isset($_GET['source']) ? $_GET['source'] : 'Words';
$count = isset($_GET['count']) ? (int)$_GET['count'] : 5;
// optional filters
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// basic sanitize: allow only letters, numbers, dash and underscore
if (!preg_match('/^[A-Za-z0-9_\-]+$/', $source)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid source parameter']);
    exit;
}

$count = max(1, min(100, $count));

$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'data';
$csvFile = $dataDir . DIRECTORY_SEPARATOR . $source . '.csv';

if (!file_exists($csvFile) || !is_readable($csvFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'Source not found']);
    exit;
}

$fp = fopen($csvFile, 'r');
if (!$fp) {
    http_response_code(500);
    echo json_encode(['error' => 'Unable to open CSV file']);
    exit;
}

// read header
$header = fgetcsv($fp);
if ($header === false) {
    fclose($fp);
    echo json_encode(['error' => 'Empty CSV']);
    exit;
}

$rows = [];
while (($row = fgetcsv($fp)) !== false) {
    // map header to row
    $assoc = [];
    foreach ($header as $i => $col) {
        $assoc[$col] = isset($row[$i]) ? $row[$i] : '';
    }
    $rows[] = $assoc;
}
fclose($fp);

if (count($rows) === 0) {
    echo json_encode(['error' => 'No data rows']);
    exit;
}

// Apply server-side filtering for type and category if present
if ($type !== '' || $category !== '') {
    // build lowercase header map
    $lowerMap = [];
    foreach ($header as $col) {
        $lowerMap[mb_strtolower($col)] = $col;
    }

    // possible header keys
    $typeKeys = ['type','question_type','qtype','questiontype'];
    $catKeys = ['category','categories','topic','tag','tags','category_name'];

    $typeCol = null;
    foreach ($typeKeys as $k) { if (isset($lowerMap[$k])) { $typeCol = $lowerMap[$k]; break; } }
    $catCol = null;
    foreach ($catKeys as $k) { if (isset($lowerMap[$k])) { $catCol = $lowerMap[$k]; break; } }

    $rows = array_filter($rows, function($r) use ($type, $category, $typeCol, $catCol) {
        if ($type !== '' && $typeCol !== null) {
            $val = isset($r[$typeCol]) ? $r[$typeCol] : '';
            if ($val === '' || mb_stripos($val, $type) === false) return false;
        }
        if ($category !== '' && $catCol !== null) {
            $val = isset($r[$catCol]) ? $r[$catCol] : '';
            if ($val === '' || mb_stripos($val, $category) === false) return false;
        }
        return true;
    });

    // reindex
    $rows = array_values($rows);
}

// shuffle and take $count
shuffle($rows);
$rows = array_slice($rows, 0, $count);

// helper: parse a question block from a CSV cell
function parseQuestionBlock($text) {
    $result = ['question' => '', 'choices' => [], 'answer' => null];
    if (trim($text) === '') return null;

    // normalize newlines
    $lines = preg_split('/\r\n|\r|\n/', $text);

    // try to find the line that starts with 'السؤال:' and use following text as question
    foreach ($lines as $line) {
        if (mb_strpos($line, 'السؤال:') !== false) {
            $parts = explode('السؤال:', $line, 2);
            $result['question'] = trim($parts[1]);
            break;
        }
    }
    // if not found, try line that starts with 'المهمة:' or fallback to first non-empty line
    if ($result['question'] === '') {
        foreach ($lines as $line) {
            if (mb_strpos($line, 'المهمة:') !== false) {
                $parts = explode('المهمة:', $line, 2);
                $result['question'] = trim($parts[1]);
                break;
            }
        }
    }
    if ($result['question'] === '') {
        foreach ($lines as $line) {
            if (trim($line) !== '') { $result['question'] = trim($line); break; }
        }
    }

    // collect options lines that look like 'أ) ...' or 'أ)'
    $choices = [];
    foreach ($lines as $line) {
        $line = trim($line);
        // match Arabic option markers like 'أ)' 'ب)' 'ج)' 'د)'
        if (preg_match('/^[\x{0621}-\x{064A}]\)/u', $line)) {
            // remove first two chars (letter and parenthesis)
            $choiceText = preg_replace('/^[\x{0621}-\x{064A}]\)\s*/u', '', $line);
            $choices[] = trim($choiceText);
        }
        // some rows use Arabic letter followed by ')' and then more text on next lines; we ignore that complexity for now
    }

    $result['choices'] = $choices;

    // find correct answer line like 'الإجابة الصحيحة: أ' or 'الإجابة الصحيحة: ب'
    foreach ($lines as $line) {
        if (mb_strpos($line, 'الإجابة الصحيحة') !== false) {
            // extract Arabic letter
            if (preg_match('/الإجابة\s*الصحيحة\s*:\s*([\x{0621}-\x{064A}])/u', $line, $m)) {
                $letter = $m[1];
                $map = ['أ' => 0, 'ب' => 1, 'ج' => 2, 'د' => 3];
                if (isset($map[$letter]) && isset($choices[$map[$letter]])) {
                    $result['answer'] = $choices[$map[$letter]];
                }
            }
            break;
        }
    }

    // if we have choices but no parsed answer, try to find an option that matches 'الإجابة:' pattern with text
    if ($result['answer'] === null && count($choices) > 0) {
        // try to find a line like 'الإجابة الصحيحة: <text>'
        foreach ($lines as $line) {
            if (mb_strpos($line, 'الإجابة الصحيحة') !== false && preg_match('/الإجابة\s*الصحيحة\s*:\s*(.+)$/u', $line, $m)) {
                $ansText = trim($m[1]);
                // try to match to one of the choices
                foreach ($choices as $c) {
                    if ($c === $ansText || mb_stripos($c, $ansText) !== false) {
                        $result['answer'] = $c; break 2;
                    }
                }
            }
        }
    }

    // if no choices found, return null to indicate unparsable block
    if (count($choices) === 0) return null;
    // if no answer, we still return question with choices but answer null
    return $result;
}

$questionsOut = [];
foreach ($rows as $r) {
    // try columns in this order (based on Words.csv structure)
    $colsToTry = ['Location_Recognition_question','Cultural_Interpretation_question','Contextual_Usage_question','Fill_in_Blank_question','True_False_question','Meaning_question'];
    $parsed = null;

    // If the CSV uses a simple 'Question' + 'Answer' structure (like GENERAL.csv), use it directly
    if (isset($r['Question']) && trim($r['Question']) !== '') {
        $answer = isset($r['Answer']) ? $r['Answer'] : '';
        $parsed = ['question' => trim($r['Question']), 'choices' => [], 'answer' => trim($answer)];
    }

    // otherwise try the more complex columns
    if ($parsed === null) {
        foreach ($colsToTry as $c) {
            if (isset($r[$c]) && trim($r[$c]) !== '') {
                $parsed = parseQuestionBlock($r[$c]);
                if ($parsed !== null) break;
            }
        }
    }
    if ($parsed !== null) {
        // include type/category if present in row
        if (isset($r['type'])) {
            $parsed['type'] = $r['type'];
        }
        if (isset($r['category'])) {
            $parsed['category'] = $r['category'];
        }
        $questionsOut[] = $parsed;
    }


echo json_encode(['questions' => $questionsOut], JSON_UNESCAPED_UNICODE);

?>
