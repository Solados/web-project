<?php
// Configure session properly
if (session_status() === PHP_SESSION_NONE) {
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400);

session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/web-project/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);
}

session_start();
?>
<?php
// include session checker if available (try local then sibling sign folder)
$check_local = __DIR__ . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'check_session.php';
$check_sign = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'sign' . DIRECTORY_SEPARATOR . 'check_session.php';
if (file_exists($check_local)) {
    require_once $check_local;
} elseif (file_exists($check_sign)) {
    require_once $check_sign;
} else {
    // no session checker found; continue without forcing redirect
}
?>
<?php
// quiz.php
// Returns a JSON array of randomized quiz questions from CSV files in /data

header('Content-Type: application/json; charset=utf-8');

// If `source` parameter is provided (used by Arabic UI), serve questions from the specific CSV
if (isset($_GET['source'])) {
    $source = trim($_GET['source']);
    $allowed = ['Words','Phrases','Proverbs'];
    if (!in_array($source, $allowed)) {
        echo json_encode(['questions'=>[]], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $count = isset($_GET['count']) ? intval($_GET['count']) : 0;
    $arabic_filter = isset($_GET['arabic_filter']) ? trim($_GET['arabic_filter']) : '';

    $dataDirPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data');
    if ($dataDirPath === false) $dataDirPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';
    $path = rtrim($dataDirPath, '/\\') . DIRECTORY_SEPARATOR . $source . '.csv';
    if (!file_exists($path)) {
        echo json_encode(['questions'=>[]], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // lightweight CSV loader
    $rows = [];
    if (($h = fopen($path, 'r')) !== false) {
        $header = fgetcsv($h, 0, ',');
        if ($header !== false) {
            $header = array_map('trim', $header);
            while (($data = fgetcsv($h, 0, ',')) !== false) {
                $row = [];
                foreach ($header as $i => $col) $row[$col] = $data[$i] ?? '';
                $rows[] = $row;
            }
        }
        fclose($h);
    }

    // small helpers (unique names to avoid collisions)
    function __src_extractQuestionFromBlock($text) {
        if (!$text) return '';
        $lines = preg_split("/\r\n|\r|\n/", trim($text));
        foreach ($lines as $line) {
            $line = trim($line);
            if (mb_strpos($line, 'السؤال') === 0) {
                $parts = explode(':', $line, 2);
                return trim($parts[1] ?? $line);
            }
        }
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '' && mb_strpos($line, 'المهمة') !== 0) return $line;
        }
        return '';
    }

    function __src_extractCorrectLetter($text) {
        if (!$text) return '';
        $lines = preg_split("/\r\n|\r|\n/", trim($text));
        foreach ($lines as $line) {
            $line = trim($line);
            if (mb_strpos($line, 'الإجابة الصحيحة') === 0) {
                $parts = explode(':', $line, 2);
                $ans = trim($parts[1] ?? '');
                return mb_substr($ans, 0, 1);
            }
        }
        return '';
    }

    function __src_extractFullCorrectAnswer($text) {
        $letter = __src_extractCorrectLetter($text);
        if ($letter === '') return '';
        $lines = preg_split("/\r\n|\r|\n/", trim($text));
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^[أبجده]\)/u', $line)) {
                if (mb_substr($line, 0, 1) === $letter) {
                    return trim(preg_replace('/^[أبجده]\)\s*/u', '', $line));
                }
            }
        }
        return '';
    }

    function __src_extractOptionsArray($text) {
        $opts = [];
        if (!$text) return $opts;
        $lines = preg_split("/\r\n|\r|\n/", trim($text));
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^[أبجده]\)\s*(.*)/u', $line, $m)) {
                $opts[] = trim($m[1]);
            }
        }
        return $opts;
    }

    $blockCols = ['Location_Recognition_question','Cultural_Interpretation_question','Contextual_Usage_question','Fill_in_Blank_question','True_False_question','Meaning_question'];
    $blockColsLower = array_map('strtolower', $blockCols);
    $filterLower = mb_strtolower($arabic_filter);

    $questions = [];
    foreach ($rows as $r) {
        $dialect = strtolower(trim($r['Dialect type'] ?? ''));
        // if arabic_filter is a dialect, skip other dialects
        if ($arabic_filter !== '' && !in_array($filterLower, $blockColsLower)) {
            if ($dialect !== $filterLower) continue;
        }

        // if arabic_filter is a block column, only extract from that column
        if ($arabic_filter !== '' && in_array($filterLower, $blockColsLower)) {
            $col = $blockCols[array_search($filterLower, array_map('strtolower', $blockCols))];
            $block = trim($r[$col] ?? '');
            if ($block === '') continue;
            $qText = __src_extractQuestionFromBlock($block);
            $aText = __src_extractFullCorrectAnswer($block);
            $choices = __src_extractOptionsArray($block);
            if ($qText !== '' && $aText !== '') {
                $entry = ['question'=>$qText,'answer'=>$aText,'lang'=>'arabic','arabic_type'=>strtolower($col)];
                // Treat Fill_in_Blank_question as Open-ended regardless of detected choices
                if (strcasecmp($col, 'Fill_in_Blank_question') === 0) {
                    $entry['type'] = 'Open-ended';
                } else {
                    if (!empty($choices)) { $entry['choices'] = $choices; $entry['type'] = 'MCQ'; }
                }
                $questions[] = $entry;
            }
        } else {
            // otherwise extract from all block columns available in this row
                foreach ($blockCols as $col) {
                $block = trim($r[$col] ?? '');
                if ($block === '') continue;
                $qText = __src_extractQuestionFromBlock($block);
                $aText = __src_extractFullCorrectAnswer($block);
                $choices = __src_extractOptionsArray($block);
                if ($qText !== '' && $aText !== '') {
                    $entry = ['question'=>$qText,'answer'=>$aText,'lang'=>'arabic','arabic_type'=>strtolower($col),'dialect'=>$dialect];
                    // Fill-in-blank should be open-ended
                    if (strcasecmp($col, 'Fill_in_Blank_question') === 0) {
                        $entry['type'] = 'Open-ended';
                    } else {
                        if (!empty($choices)) { $entry['choices'] = $choices; $entry['type'] = 'MCQ'; }
                    }
                    $questions[] = $entry;
                }
            }
        }
    }

    if (count($questions) > 1) shuffle($questions);
    if ($count > 0) $questions = array_slice($questions, 0, max(1, min(100, $count)));

    echo json_encode(['questions'=>$questions], JSON_UNESCAPED_UNICODE);
    exit;
}

// If `file` parameter is provided, serve region-based questions (in-process)
if (isset($_GET['file'])) {
    $file = isset($_GET['file']) ? strtoupper(trim($_GET['file'])) : 'GENERAL';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
    $lang = isset($_GET['lang']) ? strtolower(trim($_GET['lang'])) : 'all';
    if (!in_array($lang, ['all','english','arabic'])) $lang = 'all';

    $regionConfig = [
        'GENERAL' => ['englishFiles' => ['GENERAL.csv'], 'dialects' => ['general']],
        'NORTH' => ['englishFiles' => ['NORTH.csv'], 'dialects' => ['northern','north']],
        'SOUTH' => ['englishFiles' => ['SOUTH.csv'], 'dialects' => ['southern','south']],
        'EAST'  => ['englishFiles' => ['EAST.csv'],  'dialects' => ['eastern','east']],
        'WEST'  => ['englishFiles' => ['WEST.csv'],  'dialects' => ['western','west']],
        'CENTERAL'=> ['englishFiles'=>['CENTERAL.csv'],'dialects'=>['central']]
    ];

    if (!isset($regionConfig[$file])) {
        echo json_encode(['error' => 'Invalid region'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // small helper to read CSV into associative arrays
    $dataDirPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data');
    if ($dataDirPath === false) $dataDirPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';
    function __region_loadCsvAssoc($path) {
        if (!file_exists($path)) return [];
        $rows = [];
        if (($h = fopen($path, 'r')) !== false) {
            $header = fgetcsv($h, 0, ',');
            if ($header === false) { fclose($h); return []; }
            $header = array_map('trim', $header);
            while (($data = fgetcsv($h, 0, ',')) !== false) {
                $row = [];
                foreach ($header as $i => $col) $row[$col] = $data[$i] ?? '';
                $rows[] = $row;
            }
            fclose($h);
        }
        return $rows;
    }

    function __region_extractQuestionFromBlock($text) {
        if (!$text) return '';
        $lines = preg_split("/\r\n|\r|\n/", trim($text));
        foreach ($lines as $line) {
            $line = trim($line);
            if (mb_strpos($line, 'السؤال') === 0) {
                $parts = explode(':', $line, 2);
                return trim($parts[1] ?? $line);
            }
        }
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '' && mb_strpos($line, 'المهمة') !== 0) return $line;
        }
        return '';
    }

    function __region_extractCorrectLetter($text) {
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

    function __region_extractOptionsFromBlock($text) {
        $options = [];
        $lines = preg_split("/\r\n|\r|\n/", trim($text));
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match("/^[أبجده]\)/u", $line)) $options[] = $line;
        }
        return $options;
    }

    function __region_extractFullCorrectAnswer($text) {
        $letter = __region_extractCorrectLetter($text);
        if ($letter === "") return "";
        $options = __region_extractOptionsFromBlock($text);
        foreach ($options as $opt) {
            if (mb_substr($opt, 0, 1) === $letter) return trim(preg_replace("/^[أبجده]\)\s*/u", "", $opt));
        }
        return "";
    }

    // ========= ENGLISH QUIZ HELPERS (NEW) =========

function __en_normalizeType($t) {
    $t = strtolower(trim((string)$t));
    if ($t === '') return 'open';
    if (strpos($t, 'multiple correct') !== false) return 'multi';
    if (strpos($t, 'one correct') !== false) return 'one';
    if (strpos($t, 'open-ended') !== false || strpos($t, 'open ended') !== false) return 'open';
    // UI shorthands (just in case)
    if ($t === 'mcq') return 'one';
    if ($t === 'multi' || $t === 'multiple') return 'multi';
    if ($t === 'fill' || $t === 'open') return 'open';
    return 'open';
}

function __region_parseChoices($choicesText) {
    // يحافظ على النص كما هو (حتى لو فيه / داخل الخيار) ولا يقسمه
    $choices = [];
    $choicesText = trim((string)$choicesText);
    if ($choicesText === '' || $choicesText === '–' || $choicesText === '-') return $choices;

    // يلتقط A. .... B. .... C. .... D. ....
    preg_match_all('/\b([A-D])\.\s*(.*?)(?=\s+[A-D]\.|$)/s', $choicesText, $matches, PREG_SET_ORDER);
    foreach ($matches as $m) {
        $key = trim($m[1]);
        $val = trim(preg_replace('/\s+/', ' ', $m[2]));
        if ($key !== '' && $val !== '') $choices[$key] = $val;
    }
    return $choices;
}

function __en_extractCorrectLetters($answerRaw, $typeNorm) {
    $answerRaw = trim((string)$answerRaw);
    if ($typeNorm === 'open') return [];

    // نلتقط فقط A-D حتى لا نلتقط حروف من كلمات مثل Tag
    preg_match_all('/\b([A-D])\b/', $answerRaw, $m1);
    $letters = $m1[1] ?? [];

    // احتياط: لو كانت "B." أو "A & B" ما اشتغلت لأي سبب
    if (empty($letters)) {
        preg_match_all('/([A-D])/', $answerRaw, $m2);
        $letters = $m2[1] ?? [];
    }

    // تنظيف + إزالة تكرار
    $uniq = [];
    foreach ($letters as $L) {
        $L = strtoupper(trim($L));
        if ($L < 'A' || $L > 'D') continue;
        $uniq[$L] = true;
    }
    $letters = array_keys($uniq);

    // one correct: أول حرف فقط
    if ($typeNorm === 'one') $letters = array_slice($letters, 0, 1);

    return $letters;
}

function __region_loadEnglishQuestions($dataDir, $files) {
    $output = [];

    foreach ($files as $name) {
        $path = rtrim($dataDir, '/\\') . DIRECTORY_SEPARATOR . $name;
        $rows = __region_loadCsvAssoc($path);

        foreach ($rows as $r) {
            $q = trim((string)($r['Question'] ?? ''));
            $aRaw = trim((string)($r['Answer'] ?? ''));
            $choicesText = trim((string)($r['Choices'] ?? ''));
            $typeRaw = trim((string)($r['Question Type'] ?? ''));
            $catRaw  = trim((string)($r['Category'] ?? ''));

            if ($q === '' || $aRaw === '') continue;

            $typeNorm = __en_normalizeType($typeRaw);
            $choiceMap = __region_parseChoices($choicesText);

            // choices as array of "A. ...." (يحافظ على الشكل + الحرف)
            $choicesArr = [];
            foreach ($choiceMap as $k => $v) $choicesArr[] = $k . '. ' . $v;

            $correctLetters = __en_extractCorrectLetters($aRaw, $typeNorm);

            // open-ended: نخزن النص كإجابة
            $answerText = ($typeNorm === 'open') ? $aRaw : '';

            // (اختياري) نص الإجابة الصحيحة للـ MCQ (للإظهار فقط لو احتجته)
            $correctTexts = [];
            if ($typeNorm !== 'open' && !empty($choiceMap) && !empty($correctLetters)) {
                foreach ($correctLetters as $L) {
                    if (isset($choiceMap[$L])) $correctTexts[] = $L . '. ' . $choiceMap[$L];
                }
            }

            $entry = [
                'question' => $q,
                'lang' => 'english',
                'english_type' => ($typeRaw !== '' ? $typeRaw : ''),
                'english_type_norm' => $typeNorm,
                'english_category' => ($catRaw !== '' ? $catRaw : ''),
                'choices' => $choicesArr,                 // للعرض
                'correct_letters' => $correctLetters,     // للتصحيح
                'answer_text' => $answerText,             // open-ended فقط
                'correct_texts' => $correctTexts          // اختياري
            ];

            // لو Open-ended (Choices = –) بنخلي choices فاضية فعلاً
            if ($typeNorm === 'open') $entry['choices'] = [];

            $output[] = $entry;
        }
    }

    return $output;
}

    
    function __region_loadArabicQuestions($dataDir, $dialectsLower) {
        $result = [];
        $arabicFiles = ['Words.csv','Phrases.csv','Proverbs.csv'];
        foreach ($arabicFiles as $file) {
            $path = rtrim($dataDir, '/\\') . DIRECTORY_SEPARATOR . $file;
            if (!file_exists($path)) continue;
            $rows = __region_loadCsvAssoc($path);
            foreach ($rows as $row) {
                $dialect = strtolower(trim($row['Dialect type'] ?? ''));
                if (!in_array($dialect, $dialectsLower)) continue;
                $blockColumns = ['Location_Recognition_question','Cultural_Interpretation_question','Contextual_Usage_question','Fill_in_Blank_question','True_False_question','Meaning_question'];
                foreach ($blockColumns as $col) {
                    if (!isset($row[$col])) continue;
                    $block = trim($row[$col]); if ($block === '') continue;
                    $qText = __region_extractQuestionFromBlock($block);
                    $aText = __region_extractFullCorrectAnswer($block);
                    if ($qText !== '' && $aText !== '') $result[] = ['question'=>$qText,'answer'=>$aText,'lang'=>'arabic','arabic_type'=>strtolower($col)];
                }
            }
        }
        return $result;
    }

    $config = $regionConfig[$file];
    $requestedArabicFilter = isset($_GET['arabic_filter']) ? trim($_GET['arabic_filter']) : '';
    $count = isset($_GET['count']) ? intval($_GET['count']) : 0;

    // Decide whether the arabic_filter is a block-column name or a dialect value.
    $blockColumns = ['Location_Recognition_question','Cultural_Interpretation_question','Contextual_Usage_question','Fill_in_Blank_question','True_False_question','Meaning_question'];
    $blockColsLower = array_map('strtolower', $blockColumns);
    $filterLower = mb_strtolower($requestedArabicFilter);

    if ($requestedArabicFilter !== '') {
        if (in_array($filterLower, $blockColsLower)) {
            // it's a block-column filter; keep dialects as configured
            $dialectsLower = array_map('strtolower', $config['dialects']);
            $isBlockFilter = true;
        } else {
            // treat as a dialect value: restrict to that dialect
            $dialectsLower = [$filterLower];
            $isBlockFilter = false;
        }
    } else {
        $dialectsLower = array_map('strtolower', $config['dialects']);
        $isBlockFilter = false;
    }

    $english = [];
    $arabic  = [];

    if ($lang === 'english') {
        $english = __region_loadEnglishQuestions($dataDirPath, $config['englishFiles']);
    } elseif ($lang === 'arabic') {
        $arabic = __region_loadArabicQuestions($dataDirPath, $dialectsLower);
    } else {
        $english = __region_loadEnglishQuestions($dataDirPath, $config['englishFiles']);
        $arabic  = __region_loadArabicQuestions($dataDirPath, $dialectsLower);
    }

    $questions = array_merge($english, $arabic);


    // ----- English type filter (reliable) -----
    $requestedType = isset($_GET['type']) ? trim($_GET['type']) : '';
    if ($requestedType !== '') {
        $reqNorm = __en_normalizeType($requestedType);
        $questions = array_values(array_filter($questions, function($q) use ($reqNorm) {
            if (($q['lang'] ?? '') !== 'english') return false;
            return (($q['english_type_norm'] ?? '') === $reqNorm);
        }));
    }

    // ----- Category filter (English) -----
    $requestedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';
    if ($requestedCategory !== '' && strtolower($requestedCategory) !== 'all') {
        $catLow = mb_strtolower($requestedCategory);
        $questions = array_values(array_filter($questions, function($q) use ($catLow) {
            if (($q['lang'] ?? '') !== 'english') return false;
            $qc = mb_strtolower(trim((string)($q['english_category'] ?? '')));
            return ($qc !== '' && $qc === $catLow);
        }));
    }

    if ($lang === 'all') shuffle($questions);
    if ($lang !== 'all') {
        $questions = array_values(array_filter($questions, function($q) use ($lang) { return isset($q['lang']) && $q['lang'] === $lang; }));
    }

    if ($count > 0) {
        shuffle($questions);
        $questions = array_slice($questions, 0, max(1, min(100, $count)));
    }

    if (is_array($questions) && count($questions) > 1) shuffle($questions);

    echo json_encode(['questions' => $questions], JSON_UNESCAPED_UNICODE);
    exit;
}

