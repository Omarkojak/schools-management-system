<?php
session_start();
$user_logged_in = isset($_SESSION['teacher_logged_in']) && !empty($_SESSION['teacher_logged_in']) && $_SESSION['teacher_logged_in'] == 1;
$teacher_has_username = isset($_SESSION['teacher_username']) && !empty(isset($_SESSION['teacher_username']));
if (!$user_logged_in || !$teacher_has_username)
    header("Location:/index.php");

$teacher_username = $_SESSION['teacher_username'];
date_default_timezone_set('Africa/Cairo');
?>

<?php
include '../includes/header.php';
include '../includes/footer.php';
?>

<div class="container">
    <div class="page-header">
        <h1>My Account</h1>
    </div>
    <ul class="list-group">
        <li class=" list-group-item"><a href="mycourses.php">Courses I teach</a></li>
        <li class=" list-group-item"><a href="viewassignments.php">View Assignments</a></li>
        <li class=" list-group-item"><a href="report.php">Report a student</a></li>
        <li class=" list-group-item"><a href="viewquestions.php">View questions</a></li>
        <li class=" list-group-item"><a href="liststudents.php">List students</a></li>

    </ul>
</div>
