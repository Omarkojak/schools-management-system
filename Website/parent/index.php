<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';

$user_logged_in = isset($_SESSION['parent_logged_in']) && !empty($_SESSION['parent_logged_in']) && $_SESSION['parent_logged_in'] == 1;
$parent_has_username = isset($_SESSION['parent_username']) && !empty(isset($_SESSION['parent_username']));
if (!$user_logged_in || !$parent_has_username) {
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);
}

$parent_username = $_SESSION['parent_username'];
?>


<div class="container">
    <div class="page-header">
        <h1>My Account</h1>
    </div>
    <ul class="list-group">
        <li class="list-group-item"><a href="applyforchild.php">Apply For Child</a></li>
        <li class="list-group-item"><a href="enroll.php">Enroll Child In School</a></li>
        <li class="list-group-item"><a href="reports.php">Children Reports</a></li>
        <li class="list-group-item"><a href="rateteachers.php">Rate Teachers</a></li>
        <li class="list-group-item"><a href="reviewschools.php">Review Schools</a></li>


    </ul>
</div>