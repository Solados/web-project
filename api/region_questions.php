<?php
header("Content-Type: application/json; charset=UTF-8;");

// قراءة بارامترات الطلب
$file = isset($_GET['file']) ? strtoupper(trim($_GET['file'])) : 'GENERAL';
$page = isset($_GET['page']) ? max(0, intval($_GET['page'])) : 0;

// عدد الأسئلة لكل صفحة
$perPage = 20;

// خريطة الملفات الإنجليزية + اللهجات العربية
$regionConfig = [
    'GENERAL' => [
        'englishFiles' => ['GENERAL.csv'],
        'dialects'     => ['general']
    ],
    'NORTH' => [
        'englishFiles' => ['NORTH.csv'],
        'dialects'     => ['northern', 'north']
    ],
    'SOUTH' => [
        'englishFiles' => ['SOUTH.csv'],
        'dialects'     => ['southern', 'south']
    ],
    'EAST' => [
        'englishFiles' => ['EAST.csv'],
        'dialects'     => ['eastern', 'east']
    ],
    'WEST' => [
        'englishFiles' => ['WEST.csv'],
        'dialects'     => ['western', 'west']
    ],
    'CENTERAL' => [
        'englishFiles' => ['CENTERAL.csv'],
        'dialects'     => ['central']
    ]
];

// لو المنطقة غير موجودة
if (!isset($regionConfig[$file])) {
    echo json_encode(['error' => 'Invalid region'], JSON_UNESCAPED_UNICODE);
    exit;
}

$dataDir = __DIR__ . '/../data/';

/* ----------------------------------------------
   دوال عامة لتحميل CSV
-----------------------------------------------*/
function loadCsvAssoc($path) {
    if (!file_exists($path)) return [];

    $rows = [];
    if (($handle = fopen($path, 'r')) !== false) {
        $header = fgetcsv($handle, 0, ',');
        if ($header === false) return [];
        $header = array_map('trim', $header);

        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            $row = [];
            foreach ($header as $i => $col) {
                $row[$col] = $data[$i] ?? '';
            }
            $rows[] = $row;
        }

        fclose($handle);
    }
    return $rows;
}

/* ----------------------------------------------
   استخراج السؤال من بلوك عربي
-----------------------------------------------*/
function extractQuestionFromBlock($text) {
    if (!$text) return '';
    $lines = preg_split("/\r\n|\r|\n/", trim($text));

    foreach ($lines as $line) {
        $line = trim($line);

        if (mb_strpos($line, 'السؤال') === 0) {
            $parts = explode(':', $line, 2);
            return trim($parts[1] ?? $line);
        }
    }

    // fallback: أول سطر غير المهمة
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '' && mb_strpos($line, 'المهمة') !== 0) return $line;
    }

    return '';
}

/* ----------------------------------------------
   استخراج الحرف الصحيح من "الإجابة الصحيحة:"
-----------------------------------------------*/
function extractCorrectLetter($text) {
    if (!$text) return "";

    $lines = preg_split("/\r\n|\r|\n/", trim($text));
    foreach ($lines as $line) {
        $line = trim($line);

        if (mb_strpos($line, "الإجابة الصحيحة") === 0) {
            $parts = explode(":", $line, 2);
            $ans = trim($parts[1] ?? "");
            return mb_substr($ans, 0, 1);
        }
    }
    return "";
}

/* ----------------------------------------------
   استخراج الخيارات من البلوك
-----------------------------------------------*/
function extractOptionsFromBlock($text) {
    $options = [];
    $lines = preg_split("/\r\n|\r|\n/", trim($text));

    foreach ($lines as $line) {
        $line = trim($line);

        // صيغة: أ) السعودية
        if (preg_match("/^[أبجده]\)/u", $line)) {
            $options[] = $line;
        }
    }
    return $options;
}

/* ----------------------------------------------
   استخراج الإجابة الصحيحة كنص كامل
-----------------------------------------------*/
function extractFullCorrectAnswer($text) {
    $letter = extractCorrectLetter($text);
    if ($letter === "") return "";

    $options = extractOptionsFromBlock($text);
    foreach ($options as $opt) {
        if (mb_substr($opt, 0, 1) === $letter) {
            // إزالة "أ) " من البداية
            return trim(preg_replace("/^[أبجده]\)\s*/u", "", $opt));
        }
    }
    return "";
}

/* ----------------------------------------------
   تحميل الأسئلة الإنجليزية
-----------------------------------------------*/
function loadEnglishQuestions($dataDir, $files) {
    $output = [];

    foreach ($files as $name) {
        $path = $dataDir . $name;
        $rows = loadCsvAssoc($path);

        foreach ($rows as $r) {
            $q = trim($r['Question'] ?? '');
            $a = trim($r['Answer'] ?? '');

            if ($q !== '' && $a !== '') {
                $output[] = ['question' => $q, 'answer' => $a];
            }
        }
    }

    return $output;
}

/* ----------------------------------------------
   تحميل الأسئلة العربية من Words / Phrases / Proverbs
-----------------------------------------------*/
function loadArabicQuestions($dataDir, $dialectsLower) {
    $result = [];
    $arabicFiles = ['Words.csv', 'Phrases.csv', 'Proverbs.csv'];

    foreach ($arabicFiles as $file) {
        $path = $dataDir . $file;
        if (!file_exists($path)) continue;

        $rows = loadCsvAssoc($path);

        foreach ($rows as $row) {
            $dialect = strtolower(trim($row['Dialect type'] ?? ''));
            if (!in_array($dialect, $dialectsLower)) continue;

            // السؤال الرئيسي Term + Meaning_of_term
            $term = trim($row['Term'] ?? '');
            $mean = trim($row['Meaning_of_term'] ?? '');

            if ($term !== "" && $mean !== "") {
                $result[] = [
                    'question' => $term,
                    'answer'   => $mean
                ];
            }

            // الأعمدة الأخرى كبلوكات
            $blockColumns = [
                'Location_Recognition_question',
                'Cultural_Interpretation_question',
                'Contextual_Usage_question',
                'Fill_in_Blank_question',
                'True_False_question',
                'Meaning_question'
            ];

            foreach ($blockColumns as $col) {
                if (!isset($row[$col])) continue;

                $block = trim($row[$col]);
                if ($block === '') continue;

                $qText = extractQuestionFromBlock($block);
                $aText = extractFullCorrectAnswer($block);

                if ($qText !== '' && $aText !== '') {
                    $result[] = [
                        'question' => $qText,
                        'answer'   => $aText
                    ];
                }
            }
        }
    }

    return $result;
}

/* ----------------------------------------------
   جمع الأسئلة للموقع
-----------------------------------------------*/
$config        = $regionConfig[$file];
$dialectsLower = array_map('strtolower', $config['dialects']);

$english = loadEnglishQuestions($dataDir, $config['englishFiles']);
$arabic  = loadArabicQuestions($dataDir, $dialectsLower);

$questions = array_merge($english, $arabic);

// خلط
shuffle($questions);

// تقسيم صفحات
$total = count($questions);
$start = $page * $perPage;
$chunk = array_slice($questions, $start, $perPage);

// النتيجة
echo json_encode([
    'page'      => $page,
    'count'     => $perPage,
    'total'     => $total,
    'questions' => $chunk
], JSON_UNESCAPED_UNICODE);
