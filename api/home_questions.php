<?php
header("Content-Type: application/json; charset=UTF-8;");

$page   = isset($_GET['page']) ? intval($_GET['page']) : 0;
$count  = 20; // عدد الأسئلة في الصفحة
$start  = $page * $count;

// الملفات المستخدمة في الصفحة الرئيسية
$files = [
    "GENERAL.csv",
    "Words.csv",
    "Phrases.csv",
    "Proverbs.csv"
];

$questions = [];

function load_csv($filename) {
    if (!file_exists("../data/$filename")) return [];

    $rows = array_map('str_getcsv', file("../data/$filename"));
    $header = array_shift($rows);
    return [$header, $rows];
}

foreach ($files as $file) {
    [$header, $rows] = load_csv($file);
    if (!$header) continue;

    $map = [];
    foreach ($header as $i => $h) {
        $map[strtolower(trim($h))] = $i;
    }

    foreach ($rows as $r) {
        // GENERAL (Question + Answer)
        if (isset($map['question']) && isset($map['answer'])) {
            if (trim($r[$map['question']]) === "") continue;

            $questions[] = [
                "question" => $r[$map['question']],
                "answer"   => $r[$map['answer']]
            ];
        }
        // Words / Phrases / Proverbs
        else {
            // السؤال: النص الكامل
            $question = $r[0] ?? "";
            $answer   = $r[1] ?? "";

            if ($question && $answer) {
                $questions[] = [
                    "question" => $question,
                    "answer"   => $answer
                ];
            }
        }
    }
}

// خلط كل الأسئلة
shuffle($questions);

// أخذ 20 سؤال فقط
$chunk = array_slice($questions, $start, $count);

echo json_encode([
    "page"      => $page,
    "count"     => $count,
    "questions" => $chunk,
    "total"     => count($questions)
], JSON_UNESCAPED_UNICODE);
