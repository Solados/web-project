<?php
// api/favorite_questions.php
// Manage per-user favorite questions stored in data/user_data.csv (Favoritequestion column)

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Not logged in']);
    exit;
}

$userEmail = strtolower(trim((string)($_SESSION['user_email'] ?? '')));
if ($userEmail === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Missing user email in session']);
    exit;
}

$csvPath = dirname(__DIR__) . '/data/user_data.csv';

function readJsonBody(): array {
    $raw = file_get_contents('php://input');
    if (!is_string($raw) || trim($raw) === '') return [];
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function normalizeHeader(array $header): array {
    $out = [];
    foreach ($header as $h) {
        $out[] = trim((string)$h);
    }
    return $out;
}

function headerIndex(array $header, array $names): int {
    $lower = array_map(fn($v) => strtolower(trim((string)$v)), $header);
    foreach ($names as $name) {
        $idx = array_search(strtolower($name), $lower, true);
        if ($idx !== false) return (int)$idx;
    }
    return -1;
}

function parseFavoritesField(string $value): array {
    $value = trim($value);
    if ($value === '' || $value === '[]') return [];
    $decoded = json_decode($value, true);
    if (is_array($decoded)) return $decoded;
    return [];
}

function writeCsvAtomic(string $path, array $rows): bool {
    $tmp = $path . '.tmp';
    $fh = fopen($tmp, 'wb');
    if ($fh === false) return false;
    foreach ($rows as $row) {
        if (fputcsv($fh, $row) === false) {
            fclose($fh);
            @unlink($tmp);
            return false;
        }
    }
    fclose($fh);
    return rename($tmp, $path);
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$action = (string)($_GET['action'] ?? '');

if ($method === 'GET') {
    $action = $action !== '' ? $action : 'list';
}

$payload = $method === 'POST' ? (readJsonBody() + $_POST) : [];
if ($method === 'POST' && $action === '') {
    $action = (string)($payload['action'] ?? '');
}

if (!file_exists($csvPath)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'User data file not found']);
    exit;
}

// Read & lock the CSV
$fh = fopen($csvPath, 'c+');
if ($fh === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Unable to open user data file']);
    exit;
}
if (!flock($fh, LOCK_EX)) {
    fclose($fh);
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => 'Unable to lock user data file']);
    exit;
}

rewind($fh);
$rows = [];
while (($row = fgetcsv($fh)) !== false) {
    if ($row === [null] || $row === false) continue;
    $rows[] = $row;
}

if (count($rows) === 0) {
    flock($fh, LOCK_UN);
    fclose($fh);
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'User data file is empty']);
    exit;
}

$header = normalizeHeader($rows[0]);
$emailIdx = headerIndex($header, ['mail', 'email']);
if ($emailIdx < 0) $emailIdx = 1;

$favIdx = headerIndex($header, ['favoritequestion', 'favoritequestions', 'favorites']);
if ($favIdx < 0) {
    $header[] = 'Favoritequestion';
    $favIdx = count($header) - 1;
    $rows[0] = $header;
    // pad existing rows
    for ($i = 1; $i < count($rows); $i++) {
        while (count($rows[$i]) < count($header)) $rows[$i][] = '';
    }
}

// Ensure all rows are same length
for ($i = 1; $i < count($rows); $i++) {
    while (count($rows[$i]) < count($header)) $rows[$i][] = '';
}

$userRowIndex = -1;
for ($i = 1; $i < count($rows); $i++) {
    $rowEmail = strtolower(trim((string)($rows[$i][$emailIdx] ?? '')));
    if ($rowEmail !== '' && $rowEmail === $userEmail) {
        $userRowIndex = $i;
        break;
    }
}

if ($userRowIndex < 0) {
    flock($fh, LOCK_UN);
    fclose($fh);
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'User not found']);
    exit;
}

$favorites = parseFavoritesField((string)($rows[$userRowIndex][$favIdx] ?? ''));

if ($method === 'GET' && $action === 'list') {
    flock($fh, LOCK_UN);
    fclose($fh);
    echo json_encode(['ok' => true, 'favorites' => $favorites]);
    exit;
}

if ($method === 'POST' && $action === 'add') {
    $question = trim((string)($payload['question'] ?? ''));
    $answer = trim((string)($payload['answer'] ?? ''));
    $lang = trim((string)($payload['lang'] ?? ''));
    $region = trim((string)($payload['region'] ?? ''));
    $url = trim((string)($payload['url'] ?? ''));

    if ($question === '' || $answer === '') {
        flock($fh, LOCK_UN);
        fclose($fh);
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Missing question/answer']);
        exit;
    }

    $id = sha1($region . '|' . $lang . '|' . $question . '|' . $answer);
    $exists = false;
    foreach ($favorites as $f) {
        if (is_array($f) && (($f['id'] ?? '') === $id)) {
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $favorites[] = [
            'id' => $id,
            'question' => $question,
            'answer' => $answer,
            'lang' => $lang,
            'region' => $region,
            'url' => $url,
            'added_at' => date('c'),
        ];
    }

    $rows[$userRowIndex][$favIdx] = json_encode($favorites, JSON_UNESCAPED_UNICODE);

    // Persist
    // Unlock file handle before atomic write (Windows rename issues)
    flock($fh, LOCK_UN);
    fclose($fh);

    if (!writeCsvAtomic($csvPath, $rows)) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Failed to save favorites']);
        exit;
    }

    echo json_encode(['ok' => true, 'count' => count($favorites)]);
    exit;
}

if ($method === 'POST' && $action === 'remove') {
    $id = trim((string)($payload['id'] ?? ''));
    if ($id === '') {
        flock($fh, LOCK_UN);
        fclose($fh);
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Missing id']);
        exit;
    }

    $favorites = array_values(array_filter($favorites, function ($f) use ($id) {
        return !(is_array($f) && (($f['id'] ?? '') === $id));
    }));

    $rows[$userRowIndex][$favIdx] = json_encode($favorites, JSON_UNESCAPED_UNICODE);

    flock($fh, LOCK_UN);
    fclose($fh);

    if (!writeCsvAtomic($csvPath, $rows)) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Failed to save favorites']);
        exit;
    }

    echo json_encode(['ok' => true, 'count' => count($favorites)]);
    exit;
}

flock($fh, LOCK_UN);
fclose($fh);
http_response_code(400);
echo json_encode(['ok' => false, 'error' => 'Unsupported action']);
