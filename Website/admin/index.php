<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';


$user_logged_in = isset($_SESSION['admin_logged_in']) && !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == 1;
$admin_has_username = isset($_SESSION['admin_username']) && !empty(isset($_SESSION['admin_username']));

if (!$user_logged_in || !$admin_has_username ) {
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);
}
?>

<div class="container">
    <div class="page-header">
        <h1>My Account</h1>
    </div>
    <ul class="list-group">
        <li class="list-group-item"><a href="assignteacherstocourses.php">Assign Teachers</a></li>
        <li class="list-group-item"><a href="editschool.php">Edit School</a></li>
        <li class="list-group-item"><a href="postannouncements.php">Post Announcement</a></li>
        <li class="list-group-item"><a href="verifystudent.php">Verify Children</a></li>
        <li class="list-group-item"><a href="reviewschools.php">Review Schools</a></li>
    </ul>
</div>
