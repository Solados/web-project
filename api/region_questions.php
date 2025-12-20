<?php
header("Content-Type: application/json; charset=UTF-8;");

// قراءة بارامترات الطلب
$file = isset($_GET['file']) ? strtoupper(trim($_GET['file'])) : 'GENERAL';
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;

$lang = isset($_GET['lang']) ? strtolower(trim($_GET['lang'])) : 'all';
if (!in_array($lang, ['all', 'english', 'arabic'])) {
    $lang = 'all';
}

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
  Convert text choices to an array [A => text]
-----------------------------------------------*/
function parseChoices($choicesText) {
    $choices = [];
    if (!$choicesText) return $choices;

    // مثال: A. Circle B. Square C. Rectangle D. Straight line
    preg_match_all('/([A-Z])\.\s*(.*?)(?=\s+[A-Z]\.|$)/', $choicesText, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $letter = trim($m[1]);
        $text   = trim($m[2]);
        $choices[$letter] = $text;
    }

    return $choices;
}

/* ----------------------------------------------
  Extracting the answer text from Answer + Choices
-----------------------------------------------*/
function extractEnglishAnswerTextByType($answerLetters, $choicesText, $questionType) {
    if (!$answerLetters || !$choicesText) return "";

    $choices = parseChoices($choicesText);

    // Extract all letters (A, B, C...)
    preg_match_all('/[A-Z]/', $answerLetters, $matches);
    $letters = $matches[0];

    // MCQ (one correct) → first letter only
    if (stripos($questionType, 'one correct') !== false) {
        $letters = array_slice($letters, 0, 1);
    }

    $answers = [];
    foreach ($letters as $l) {
        if (isset($choices[$l])) {
            $answers[] = $choices[$l];
        }
    }

    return implode(" / ", $answers);
}

/* ----------------------------------------------
  Loading English Questions
-----------------------------------------------*/
function loadEnglishQuestions($dataDir, $files) {
    $output = [];

    foreach ($files as $name) {
        $path = $dataDir . $name;
        $rows = loadCsvAssoc($path);

        foreach ($rows as $r) {
            $q = trim($r['Question'] ?? '');
            $a = trim($r['Answer'] ?? '');
            $choicesText = trim($r['Choices'] ?? '');

            if ($q === '' || $a === '') continue;

            // If the question is MCQ → extract the answer text from Choices
            $finalAnswer = $a;

            $questionType = strtolower(trim($r["Question Type"] ?? ""));

            if (!empty($choicesText) && $questionType !== "") {
                $extracted = extractEnglishAnswerTextByType($a, $choicesText, $questionType);
                if ($extracted !== "") {
                    $finalAnswer = $extracted;
                }
            }

            $output[] = [
                'question' => $q,
                'answer'   => $finalAnswer,
                'lang'     => 'english',
                'english_type'     => strtolower($r["Question Type"] ?? ""),
                'english_category' => strtolower($r["Category"] ?? "")
            ];
        }
    }

    return $output;
}

/* ----------------------------------------------
  Loading Arabic Questions from Words / Phrases / Proverbs
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

            // Other columns as blocks
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
                        'answer'   => $aText,
                        'lang'     => 'arabic',
                        'arabic_type' => strtolower($col)
                    ];
                }
            }
        }
    }

    return $result;
}

/* ----------------------------------------------
  Questions collected for the website
-----------------------------------------------*/
$config        = $regionConfig[$file];
$dialectsLower = array_map('strtolower', $config['dialects']);

$english = loadEnglishQuestions($dataDir, $config['englishFiles']);
$arabic  = loadArabicQuestions($dataDir, $dialectsLower);

$questions = array_merge($english, $arabic);

// Shuffle only if no language filter is applied
if ($lang === 'all') {
    shuffle($questions);
}


if ($lang !== 'all') {
    $questions = array_values(array_filter($questions, function($q) use ($lang) {
        return isset($q['lang']) && $q['lang'] === $lang;
    }));
}

echo json_encode([
    'questions' => $questions
], JSON_UNESCAPED_UNICODE);
