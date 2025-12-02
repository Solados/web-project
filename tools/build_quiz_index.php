<?php
// build_quiz_index.php
// Scans CSV files in ../data and builds a flattened JSON index at data/quiz_index.json
// Usage: run from CLI or browser (CLI recommended)

$dataDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';
$outFile = realpath($dataDir) . DIRECTORY_SEPARATOR . 'quiz_index.json';

if ($outFile === false) {
    fwrite(STDERR, "data directory not found\n");
    exit(1);
}

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

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dataDir));
$files = [];
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    if (!preg_match('/\.(csv)$/i', $path)) continue;
    $files[] = $path;
}
sort($files);

$all = [];
$global = 0;
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
        if ($candidateText === '') { $rowIndex++; continue; }
        $parsed = parseQuestionBlock($candidateText);
        if ($parsed === null) {
            // treat as open question (essay/free text) if no choices
            $parsed = ['question' => $candidateText, 'choices' => [], 'answer' => isset($assoc['Answer']) ? $assoc['Answer'] : null];
        }
        $parsed['source'] = basename($filePath);
        $parsed['row'] = $rowIndex;
        $parsed['global_index'] = $global;
        $all[] = $parsed;
        $rowIndex++;
        $global++;
    }
    fclose($fp);
}

$written = file_put_contents($outFile, json_encode(['generated' => time(), 'total' => count($all), 'questions' => $all], JSON_UNESCAPED_UNICODE));
if ($written === false) {
    fwrite(STDERR, "Failed to write index file to {$outFile}\n");
    exit(1);
}

echo "Index built: {$outFile} (" . count($all) . " questions)\n";
