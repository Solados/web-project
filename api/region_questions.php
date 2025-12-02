<?php
header("Content-Type: application/json; charset=UTF-8;");

$file = isset($_GET['file']) ? strtoupper($_GET['file']) : "";
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;

$valid = ["NORTH", "SOUTH", "EAST", "WEST", "CENTERAL"];
if (!in_array($file, $valid)) {
    echo json_encode(["error" => "Invalid region"]);
    exit;
}

$csv = "../data/{$file}.csv";
if (!file_exists($csv)) {
    echo json_encode(["error" => "File not found"]);
    exit;
}

$count = 20;
$start = $page * $count;

$rows = array_map('str_getcsv', file($csv));
$header = array_shift($rows);

$map = [];
foreach ($header as $i => $h) {
    $map[strtolower(trim($h))] = $i;
}

$questions = [];

foreach ($rows as $r) {
    if (!isset($map['question']) || trim($r[$map['question']]) == "") continue;

    $q = [
        "question" => $r[$map['question']],
        "answer"   => $r[$map['answer']] ?? "",
    ];

    // إذا عندك Choices
    if (isset($map['choices']) && trim($r[$map['choices']]) !== "") {
        $choices = preg_split("/[;|]/", $r[$map['choices']]);
        $q["choices"] = array_map('trim', $choices);
    }

    $questions[] = $q;
}

$total = count($questions);
$chunk = array_slice($questions, $start, $count);

echo json_encode([
    "page"      => $page,
    "count"     => $count,
    "questions" => $chunk,
    "total"     => $total
], JSON_UNESCAPED_UNICODE);
