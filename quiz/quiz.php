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

    function __region_parseChoices($choicesText) {
        $choices = [];
        if (!$choicesText) return $choices;
        preg_match_all('/([A-Z])\.\s*(.*?)(?=\s+[A-Z]\\.|$)/', $choicesText, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) $choices[trim($m[1])] = trim($m[2]);
        return $choices;
    }

    function __region_extractEnglishAnswerTextByType($answerLetters, $choicesText, $questionType) {
        if (!$answerLetters || !$choicesText) return "";
        $choices = __region_parseChoices($choicesText);
        preg_match_all('/[A-Z]/', $answerLetters, $matches);
        $letters = $matches[0];
        if (stripos($questionType, 'one correct') !== false) $letters = array_slice($letters, 0, 1);
        $answers = [];
        foreach ($letters as $l) if (isset($choices[$l])) $answers[] = $choices[$l];
        return implode(' / ', $answers);
    }

    function __region_loadEnglishQuestions($dataDir, $files) {
        $output = [];
        foreach ($files as $name) {
            $path = rtrim($dataDir, '/\\') . DIRECTORY_SEPARATOR . $name;
            $rows = __region_loadCsvAssoc($path);
            foreach ($rows as $r) {
                $q = trim($r['Question'] ?? '');
                $a = trim($r['Answer'] ?? '');
                $choicesText = trim($r['Choices'] ?? '');
                if ($q === '' || $a === '') continue;
                $finalAnswer = $a;
                $questionType = strtolower(trim($r['Question Type'] ?? ""));
                if (!empty($choicesText) && $questionType !== "") {
                    $extracted = __region_extractEnglishAnswerTextByType($a, $choicesText, $questionType);
                    if ($extracted !== "") $finalAnswer = $extracted;
                }
                $output[] = ['question'=>$q,'answer'=>$finalAnswer,'lang'=>'english','english_type'=>strtolower($r['Question Type'] ?? ''),'english_category'=>strtolower($r['Category'] ?? '')];
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

    $english = __region_loadEnglishQuestions($dataDirPath, $config['englishFiles']);
    $arabic = __region_loadArabicQuestions($dataDirPath, $dialectsLower);

    // If filter requested a specific block column name, keep only arabic questions of that type
    if (!empty($requestedArabicFilter) && !empty($isBlockFilter) && $isBlockFilter === true) {
        $arabic = array_values(array_filter($arabic, function($q) use ($filterLower) {
            return isset($q['arabic_type']) && mb_strtolower($q['arabic_type']) === $filterLower;
        }));
    }
    $questions = array_merge($english, $arabic);

    // Apply optional type filter from GET parameter (e.g., "MCQ (multiple correct)")
    $requestedType = isset($_GET['type']) ? trim($_GET['type']) : '';
    if ($requestedType !== '') {
        $tlow = mb_strtolower($requestedType);
        // If requesting multiple-correct MCQs, keep questions that are labeled multiple
        if (mb_strpos($tlow, 'multiple') !== false || mb_strpos($tlow, 'multiple correct') !== false || mb_strpos($tlow, 'multiple answers') !== false) {
            $questions = array_values(array_filter($questions, function($q) {
                $etype = isset($q['english_type']) ? mb_strtolower($q['english_type']) : '';
                $ans = isset($q['answer']) ? (string)$q['answer'] : '';
                // accept if explicit english_type mentions multiple
                if ($etype !== '' && mb_strpos($etype, 'multiple') !== false) return true;
                // accept if answer appears to contain multiple values (slashes, commas, or ' and ')
                if (strpos($ans, '/') !== false) return true;
                if (strpos($ans, ',') !== false) return true;
                if (preg_match('/\band\b/i', $ans)) return true;
                // also accept if answer is short letters sequence like 'AB' or 'A B'
                if (preg_match('/^[A-Za-z]{2,}$/', preg_replace('/\s+/', '', $ans))) return true;
                return false;
            }));
        } elseif (mb_strpos($tlow, 'one correct') !== false || mb_strpos($tlow, 'mcq (one') !== false) {
            // keep only single-correct MCQs
            $questions = array_values(array_filter($questions, function($q) {
                $etype = isset($q['english_type']) ? mb_strtolower($q['english_type']) : '';
                $ans = isset($q['answer']) ? (string)$q['answer'] : '';
                if ($etype !== '' && (mb_strpos($etype, 'one') !== false || mb_strpos($etype, 'one correct') !== false)) return true;
                // heuristics: not multi
                if (strpos($ans, '/') === false && strpos($ans, ',') === false && !preg_match('/\band\b/i', $ans)) return true;
                return false;
            }));
        } elseif (mb_strpos($tlow, 'open') !== false || mb_strpos($tlow, 'fill') !== false) {
            // keep open-ended (no choices)
            $questions = array_values(array_filter($questions, function($q) {
                return empty($q['choices']);
            }));
        }
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

