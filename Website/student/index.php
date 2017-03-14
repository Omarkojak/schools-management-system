<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';

$user_logged_in = isset($_SESSION['student_logged_in']) && !empty($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] == 1;
$student_has_username = isset($_SESSION['student_username']) && !empty(isset($_SESSION['student_username']));
$student_has_ssn = isset($_SESSION['student_ssn']) && !empty(isset($_SESSION['student_ssn']));
if (!$user_logged_in || !$student_has_username || !$student_has_ssn) {
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);
}

$student_username = $_SESSION['student_username'];
$student_ssn = $_SESSION['student_ssn'];
date_default_timezone_set('Africa/Cairo');
?>


<?php

$is_high_school = 0;
$query = sprintf("SELECT * FROM Students WHERE child_ssn = %d;", $_SESSION['student_ssn']);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    $student_info = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);

    mysqli_free_result($raw_results);
    mysqli_next_result($conn);

    $query = sprintf("SELECT school_id FROM High_Schools WHERE school_id = %d;", $student_info[0]['school_id']);
    $raw_results = mysqli_query($conn, $query);
    if ($raw_results == false) {
        die(formattedMessage(DIE_MSG, 1));
    } else {
        if (mysqli_num_rows($raw_results) > 0)
            $is_high_school = 1;
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1>My Account</h1>
    </div>
    <ul class="list-group">
        <li class="list-group-item"><a href="profile.php?ssn=<?php echo $student_ssn . "\""; ?> >My Profile</a></li>
        <li class=" list-group-item"><a href="mycourses.php">My Courses</a></li>
        <li class="list-group-item"><a href="activites.php">My Activites</a></li>
        <?php
        if ($is_high_school)
            echo "<li class=\"list-group-item\"><a href=\"clubs.php\"><a href=\"clubs.php\">My Clubs</a></li>";
        ?>

    </ul>
</div>
