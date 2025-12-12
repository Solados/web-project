<?php
// save_favorite.php
// Save favorite question into user_data.csv

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $html = $_POST['html'] ?? '';
    $html = trim($html);

    if ($html !== '') {
        $file = 'user_data.csv';

        // If the file does not exist, create it
        if (!file_exists($file)) {
            file_put_contents($file, "");
        }

        // Small safety sanitization: replace some special characters to ensure safe CSV
        $html_sanitized = str_replace(["\r","\n"], ['',''], $html);

        // Append new line
        file_put_contents($file, $html_sanitized . "\n", FILE_APPEND | LOCK_EX);

        echo "Saved successfully!";
    } else {
        echo "No content to save.";
    }
} else {
    echo "Invalid request method.";
}
?>
