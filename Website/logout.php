<?php
include 'includes/config.php';
session_start();
// Destroying All Sessions
if (session_destroy()) {
// Redirecting To Home Page
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);
}
?>