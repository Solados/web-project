<?php
// This file ONLY checks login status — it must NOT start sessions or send headers
$LOGGED_IN = false;
$USER_NAME = "";
$USER_ID = "";
$USER_EMAIL = "";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $LOGGED_IN = true;
    $USER_NAME = $_SESSION['user_name'] ?? "";
    $USER_ID = $_SESSION['user_id'] ?? "";
    $USER_EMAIL = $_SESSION['user_email'] ?? "";
}
?>
