<?php
header("Content-Type: application/json; charset=UTF-8;");
mb_internal_encoding("UTF-8");

$q = isset($_GET['q']) ? trim($_GET['q']) : "";
$lang = isset($_GET['lang']) ? strtolower(trim($_GET['lang'])) : 'all';
if (!in_array($lang, ['all', 'english', 'arabic'])) $lang = 'all';

if ($q === "") {
  echo json_encode(['query' => $q, 'count' => 0, 'questions' => []], JSON_UNESCAPED_UNICODE);
  exit;
}

$dataDir = __DIR__ . '/../data/';

/* ---------- CSV Loader ---------- */
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

/* ---------- Arabic helpers ---------- */
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
  foreach ($lines as $line) {
    $line = trim($line);
    if ($line !== '' && mb_strpos($line, 'المهمة') !== 0) return $line;
  }
  return '';
}

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

function extractOptionsFromBlock($text) {
  $options = [];
  $lines = preg_split("/\r\n|\r|\n/", trim($text));
  foreach ($lines as $line) {
    $line = trim($line);
    if (preg_match("/^[أبجده]\)/u", $line)) $options[] = $line;
  }
  return $options;
}

function extractFullCorrectAnswer($text) {
  $letter = extractCorrectLetter($text);
  if ($letter === "") return "";
  $options = extractOptionsFromBlock($text);
  foreach ($options as $opt) {
    if (mb_substr($opt, 0, 1) === $letter) {
      return trim(preg_replace("/^[أبجده]\)\s*/u", "", $opt));
    }
  }
  return "";
}

/* ---------- English helpers ---------- */
function parseChoices($choicesText) {
  $choices = [];
  if (!$choicesText) return $choices;
  preg_match_all('/([A-Z])\.\s*(.*?)(?=\s+[A-Z]\.|$)/', $choicesText, $matches, PREG_SET_ORDER);
  foreach ($matches as $m) $choices[trim($m[1])] = trim($m[2]);
  return $choices;
}

function extractEnglishAnswerTextByType($answerLetters, $choicesText, $questionType) {
  if (!$answerLetters || !$choicesText) return "";
  $choices = parseChoices($choicesText);

  preg_match_all('/[A-Z]/', $answerLetters, $matches);
  $letters = $matches[0];

  // ✅ أهم نقطة: one correct = نأخذ أول حرف فقط
  if (stripos($questionType, 'one correct') !== false) {
    $letters = array_slice($letters, 0, 1);
  }

  $answers = [];
  foreach ($letters as $l) if (isset($choices[$l])) $answers[] = $choices[$l];
  return implode(" / ", $answers);
}

/* ---------- Load ALL English ---------- */
function loadEnglishQuestionsAll($dataDir) {
  // عدّل أسماء الملفات حسب الموجود عندك داخل data/
  $files = ['GENERAL.csv','NORTH.csv','SOUTH.csv','EAST.csv','WEST.csv','CENTERAL.csv'];
  $output = [];

  foreach ($files as $name) {
    $rows = loadCsvAssoc($dataDir . $name);
    foreach ($rows as $r) {
      $q = trim($r['Question'] ?? '');
      $a = trim($r['Answer'] ?? '');
      if ($q === '' || $a === '') continue;

      $choicesText  = trim($r['Choices'] ?? '');
      $questionType = strtolower(trim($r["Question Type"] ?? ""));
      $finalAnswer  = $a;

      if ($choicesText !== "" && $questionType !== "") {
        $extracted = extractEnglishAnswerTextByType($a, $choicesText, $questionType);
        if ($extracted !== "") $finalAnswer = $extracted;
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

/* ---------- Load ALL Arabic ---------- */
function loadArabicQuestionsAll($dataDir) {
  // عدّل أسماء الملفات حسب الموجود عندك داخل data/
  $arabicFiles = ['Words.csv', 'Phrases.csv', 'Proverbs.csv'];
  $blockColumns = [
    'Location_Recognition_question',
    'Cultural_Interpretation_question',
    'Contextual_Usage_question',
    'Fill_in_Blank_question',
    'True_False_question',
    'Meaning_question'
  ];

  $result = [];
  foreach ($arabicFiles as $file) {
    $path = $dataDir . $file;
    if (!file_exists($path)) continue;

    $rows = loadCsvAssoc($path);
    foreach ($rows as $row) {
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

function matchQuery($text, $q) {
  if ($text === null) return false;
  return mb_stripos($text, $q) !== false;
}

/* ---------- Build + Filter ---------- */
$questions = array_merge(
  loadEnglishQuestionsAll($dataDir),
  loadArabicQuestionsAll($dataDir)
);

if ($lang !== 'all') {
  $questions = array_values(array_filter($questions, fn($item) => ($item['lang'] ?? '') === $lang));
}

$filtered = array_values(array_filter($questions, function($item) use ($q) {
  return matchQuery($item['question'] ?? '', $q) || matchQuery($item['answer'] ?? '', $q);
}));

echo json_encode([
  'query' => $q,
  'count' => count($filtered),
  'questions' => $filtered
], JSON_UNESCAPED_UNICODE);
