<?php
// Index static HTML/PHP files into Meilisearch 'site' index.
// Usage: set MEILI_URL and MEILI_KEY env vars, then run: php tools/index_static.php

$meiliUrl = getenv('MEILI_URL');
$meiliKey = getenv('MEILI_KEY');
if (!$meiliUrl) {
    echo "MEILI_URL not set. Exiting.\n";
    exit(1);
}

$docs = [];
$dir = realpath(__DIR__ . '/..');
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    if (!preg_match('/\.(html|htm|php)$/i', $path)) continue;
    if (stripos($path, 'tools') !== false) continue;
    if (stripos($path, 'search.php') !== false) continue;
    $content = @file_get_contents($path);
    if ($content === false) continue;
    // strip scripts/styles
    $text = preg_replace('#<script[^>]*>.*?<\/script>#isu', ' ', $content);
    $text = preg_replace('#<style[^>]*>.*?<\/style>#isu', ' ', $text);
    $plain = trim(preg_replace('/\s+/u', ' ', strip_tags($text)));
    $title = null;
    if (preg_match('/<title>(.*?)<\/title>/isu', $content, $m)) $title = trim(strip_tags($m[1]));
    $url = str_replace('\\', '/', substr($path, strlen($dir) + 1));
    $docs[] = [
        'id' => $url,
        'title' => $title ?: basename($path),
        'content' => $plain,
        'url' => $url,
    ];
}

// send to Meilisearch
$indexUrl = rtrim($meiliUrl, '/') . '/indexes/site/documents';
$payload = json_encode($docs, JSON_UNESCAPED_UNICODE);

$ch = curl_init($indexUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
$headers = ['Content-Type: application/json'];
if ($meiliKey) $headers[] = "X-Meili-API-Key: $meiliKey";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$res = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);
if ($err) {
    echo "Error sending to Meilisearch: $err\n";
    exit(1);
}
echo "Indexing response: \n" . $res . "\n";
