<?php
header('Content-Type: application/json; charset=utf-8');
// quiz_page.php
// Returns questions for a given page (paginated view of all quiz questions)

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = isset($_GET['per_page']) ? max(1, min(100, (int)$_GET['per_page'])) : 20;
$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'data';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dataDir));
$files = [];
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    if (!preg_match('/\.(csv)$/i', $path)) continue;
    $files[] = $path;
}
sort($files);

function parseQuestionBlock($text) {
    $result = ['question' => '', 'choices' => [], 'answer' => null];
    if (trim($text) === '') return null;
    $lines = preg_split('/\r\n|\r|\n/', $text);
    foreach ($lines as $line) {
        if (mb_strpos($line, 'السؤال:') !== false) {
            $parts = explode('السؤال:', $line, 2);
            $result['question'] = trim($parts[1]);
            break;
        }
    }
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
    $choices = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match('/^[\x{0621}-\x{064A}]\)/u', $line)) {
            $choiceText = preg_replace('/^[\x{0621}-\x{064A}]\)\s*/u', '', $line);
            $choices[] = trim($choiceText);
        }
    }
    $result['choices'] = $choices;
    foreach ($lines as $line) {
        if (mb_strpos($line, 'الإجابة الصحيحة') !== false) {
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
    if ($result['answer'] === null && count($choices) > 0) {
        foreach ($lines as $line) {
            if (mb_strpos($line, 'الإجابة الصحيحة') !== false && preg_match('/الإجابة\s*الصحيحة\s*:\s*(.+)$/u', $line, $m)) {
                $ansText = trim($m[1]);
                foreach ($choices as $c) {
                    if ($c === $ansText || mb_stripos($c, $ansText) !== false) {
                        $result['answer'] = $c; break 2;
                    }
                }
            }
        }
    }
    if (count($choices) === 0) return null;
    return $result;
}

// build flat question list

// If a prebuilt index exists use it for fast pagination
$indexFile = $dataDir . DIRECTORY_SEPARATOR . 'quiz_index.json';
if (is_readable($indexFile)) {
    $raw = file_get_contents($indexFile);
    $idx = json_decode($raw, true);
    $questions = isset($idx['questions']) && is_array($idx['questions']) ? $idx['questions'] : [];
    $total = count($questions);
    $start = ($page - 1) * $perPage;
    $slice = array_slice($questions, $start, $perPage);
} else {
    // build flat question list by scanning CSVs
    $all = [];
    foreach ($files as $filePath) {
        if (!is_readable($filePath)) continue;
        $fp = fopen($filePath, 'r');
        if (!$fp) continue;
        $header = fgetcsv($fp);
        if ($header === false) { fclose($fp); continue; }
        $rowIndex = 0;
        while (($row = fgetcsv($fp)) !== false) {
            $assoc = [];
            foreach ($header as $i => $col) $assoc[$col] = isset($row[$i]) ? $row[$i] : '';
            $colsToTry = ['Question','Location_Recognition_question','Cultural_Interpretation_question','Contextual_Usage_question','Fill_in_Blank_question','True_False_question','Meaning_question'];
            $candidateText = '';
            foreach ($colsToTry as $c) { if (isset($assoc[$c]) && trim($assoc[$c]) !== '') { $candidateText = trim($assoc[$c]); break; } }
            if ($candidateText === '') $candidateText = implode(' ', $assoc);
            // attempt parse if complex
            $parsed = null;
            if ($candidateText !== '') {
                $p = parseQuestionBlock($candidateText);
                if ($p !== null) {
                    $parsed = $p;
                } else {
                    // simple structure
                    $parsed = ['question' => $candidateText, 'choices' => [], 'answer' => isset($assoc['Answer']) ? $assoc['Answer'] : null];
                }
            }
            if ($parsed !== null) {
                $parsed['source'] = basename($filePath);
                $parsed['row'] = $rowIndex;
                $all[] = $parsed;
            }
            $rowIndex++;
        }
        fclose($fp);
    }
    $total = count($all);
    $start = ($page - 1) * $perPage;
    $slice = array_slice($all, $start, $perPage);
}

echo json_encode(['page' => $page, 'per_page' => $perPage, 'total_questions' => $total, 'questions' => $slice], JSON_UNESCAPED_UNICODE);
