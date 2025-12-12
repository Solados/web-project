<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request');
}

$index = isset($_POST['index']) ? intval($_POST['index']) : -1;
$file = 'user_data.csv';

if(!file_exists($file)){
    exit('File not found');
}

$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if($index < 0 || $index >= count($lines)){
    exit('Invalid index');
}

// إزالة السطر المحدد
unset($lines[$index]);

// إعادة كتابة الملف
file_put_contents($file, implode("\n", $lines) . "\n");

echo 'Question removed from favorites!';
?>
