<?php
header('Content-Type: application/json; charset=utf-8');

$q = trim((string)($_GET['q'] ?? ''));
$limit = (int)($_GET['limit'] ?? 10);

$meiliUrl = getenv('MEILI_URL') ?: null;
$meiliKey = getenv('MEILI_KEY') ?: null;

if ($meiliUrl) {
    // Proxy to Meilisearch
    $index = rtrim($meiliUrl, '/') . '/indexes/site/search';
    $payload = json_encode(["q" => $q, "limit" => $limit, "attributesToCrop" => ["content"], "cropLength" => 180, "highlightPreTag" => "<mark>", "highlightPostTag" => "</mark>"], JSON_UNESCAPED_UNICODE);

    $ch = curl_init($index);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", $meiliKey ? "X-Meili-API-Key: $meiliKey" : ""]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        echo json_encode(["error" => "Meili proxy error: $err"]);
        exit;
    }
    echo $res;
    exit;
}

// Fallback: local static-file search across HTML/PHP files in repo
// This is a best-effort fallback for environments without Meilisearch.
if ($q === '') {
    echo json_encode(["total" => 0, "hits" => []], JSON_UNESCAPED_UNICODE);
    exit;
}

$dir = __DIR__;
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$files = [];
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    // only search public pages and templates (html, php)
    if (!preg_match('/\.(html|htm|php)$/i', $path)) continue;
    // skip the search script itself and tools
    if (stripos($path, 'search.php') !== false) continue;
    if (stripos($path, 'tools') !== false) continue;
    $files[] = $path;
}

$hits = [];
$qLower = mb_strtolower($q, 'UTF-8');

foreach ($files as $filePath) {
    $content = @file_get_contents($filePath);
    if ($content === false) continue;
    // extract title
    $title = null;
    if (preg_match('/<title>(.*?)<\/title>/isu', $content, $m)) {
        $title = trim(strip_tags($m[1]));
    }
    // remove scripts/styles
    $text = preg_replace('#<script[^>]*>.*?<\/script>#isu', ' ', $content);
    $text = preg_replace('#<style[^>]*>.*?<\/style>#isu', ' ', $text);
    // get plain text
    $plain = trim(preg_replace('/\s+/u', ' ', strip_tags($text)));
    $plainLower = mb_strtolower($plain, 'UTF-8');
    $pos = mb_strpos($plainLower, $qLower, 0, 'UTF-8');
    if ($pos !== false) {
        // create snippet around match
        $start = max(0, $pos - 60);
        $len = mb_strlen($q, 'UTF-8') + 120;
        $snippet = mb_substr($plain, $start, $len, 'UTF-8');
        // highlight all occurrences in snippet
        $snippetHighlighted = preg_replace('/(' . preg_quote($q, '/') . ')/iu', '<mark>$1</mark>', htmlspecialchars($snippet, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401));
        $url = str_replace('\\', '/', substr($filePath, strlen($dir) + 1));
        if ($url === '') $url = basename($filePath);
        $hits[] = [
            'title' => $title ?: basename($filePath),
            'url' => $url,
            'excerpt' => $snippetHighlighted,
            'score' => 1, // simple score for sorting, could be expanded
        ];
    }
}

// sort by score (same), then by url
usort($hits, function($a, $b){
    return strcmp($a['url'], $b['url']);
});

$total = count($hits);
$hits = array_slice($hits, 0, $limit);

echo json_encode(["total" => $total, "hits" => $hits], JSON_UNESCAPED_UNICODE);

