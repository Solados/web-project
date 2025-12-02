<?php
header('Content-Type: application/json; charset=utf-8');
// quiz_search.php
// Search across all questions in /data CSV files and return matches with page info (20 per page)

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
$q = trim((string)($_GET['q'] ?? ''));
$perPage = 20; // questions per quiz page

if ($q === '') {
    echo json_encode(['total' => 0, 'hits' => []], JSON_UNESCAPED_UNICODE);
    exit;
}

$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'data';
$indexFile = $dataDir . DIRECTORY_SEPARATOR . 'quiz_index.json';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dataDir));
$files = [];
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    if (!preg_match('/\.(csv)$/i', $path)) continue;
    $files[] = $path;
}
// sort files to have deterministic ordering
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

$hits = [];
$globalIndex = 0;
$qLower = mb_strtolower($q, 'UTF-8');

// If a prebuilt JSON index exists, use it for faster searches (recommended for large datasets)
if (is_readable($indexFile)) {
    $raw = file_get_contents($indexFile);
    $idx = json_decode($raw, true);
    $questions = isset($idx['questions']) && is_array($idx['questions']) ? $idx['questions'] : [];
    foreach ($questions as $qObj) {
        $plain = isset($qObj['question']) ? strip_tags((string)$qObj['question']) : '';
        $plainLower = mb_strtolower($plain, 'UTF-8');
        if (mb_strpos($plainLower, $qLower) !== false) {
            $pos = mb_strpos($plainLower, $qLower, 0, 'UTF-8');
            $start = max(0, $pos - 60);
            $len = mb_strlen($q, 'UTF-8') + 120;
            $snippet = mb_substr($plain, $start, $len, 'UTF-8');
            $snippetHighlighted = preg_replace('/(' . preg_quote($q, '/') . ')/iu', '<mark>$1</mark>', htmlspecialchars($snippet, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401));
            $page = isset($qObj['global_index']) ? floor(((int)$qObj['global_index']) / $perPage) + 1 : 1;
            $hits[] = [
                'id' => (isset($qObj['source']) ? $qObj['source'] : 'unknown') . '::' . (isset($qObj['row']) ? $qObj['row'] : 0),
                'source' => isset($qObj['source']) ? $qObj['source'] : '',
                'row' => isset($qObj['row']) ? $qObj['row'] : 0,
                'global_index' => isset($qObj['global_index']) ? (int)$qObj['global_index'] : $globalIndex,
                'page' => $page,
                'question' => $plain,
                'choices' => isset($qObj['choices']) ? $qObj['choices'] : [],
                'answer' => isset($qObj['answer']) ? $qObj['answer'] : null,
                'snippet' => $snippetHighlighted,
            ];
        }
        $globalIndex++;
    }
} else {
    // fallback: scan CSV files (original behavior)
    foreach ($files as $filePath) {
        if (!is_readable($filePath)) continue;
        $fp = fopen($filePath, 'r');
        if (!$fp) continue;
        $header = fgetcsv($fp);
        if ($header === false) { fclose($fp); continue; }
        $rowIndex = 0;
        while (($row = fgetcsv($fp)) !== false) {
            $assoc = [];
            foreach ($header as $i => $col) {
                $assoc[$col] = isset($row[$i]) ? $row[$i] : '';
            }
            // attempt to get question text from common columns
            $candidateText = '';
            $colsToTry = ['Question','Location_Recognition_question','Cultural_Interpretation_question','Contextual_Usage_question','Fill_in_Blank_question','True_False_question','Meaning_question'];
            foreach ($colsToTry as $c) {
                if (isset($assoc[$c]) && trim($assoc[$c]) !== '') { $candidateText = trim($assoc[$c]); break; }
            }
            if ($candidateText === '') {
                // fallback: join all columns
                $candidateText = implode(' ', $assoc);
            }
            $plain = strip_tags($candidateText);
            $plainLower = mb_strtolower($plain, 'UTF-8');
            if (mb_strpos($plainLower, $qLower) !== false) {
                // get snippet around match
                $pos = mb_strpos($plainLower, $qLower, 0, 'UTF-8');
                $start = max(0, $pos - 60);
                $len = mb_strlen($q, 'UTF-8') + 120;
                $snippet = mb_substr($plain, $start, $len, 'UTF-8');
                // highlight
                $snippetHighlighted = preg_replace('/(' . preg_quote($q, '/') . ')/iu', '<mark>$1</mark>', htmlspecialchars($snippet, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401));
                // try to parse choices/answer for this row
                $parsed = parseQuestionBlock($candidateText);
                $choices = [];
                $answer = null;
                if ($parsed !== null) {
                    $choices = $parsed['choices'];
                    $answer = $parsed['answer'];
                }
                $page = floor($globalIndex / $perPage) + 1;
                $hits[] = [
                    'id' => $filePath . '::' . $rowIndex,
                    'source' => basename($filePath),
                    'row' => $rowIndex,
                    'global_index' => $globalIndex,
                    'page' => $page,
                    'question' => $plain,
                    'choices' => $choices,
                    'answer' => $answer,
                    'snippet' => $snippetHighlighted,
                ];
            }
            $rowIndex++;
            $globalIndex++;
        }
        fclose($fp);
    }
}

// sort by global_index (natural order)
usort($hits, function($a,$b){ return $a['global_index'] - $b['global_index']; });

$total = count($hits);
$hits = array_slice($hits, 0, max(0,min($limit,50)));

echo json_encode(['total' => $total, 'hits' => $hits], JSON_UNESCAPED_UNICODE);
