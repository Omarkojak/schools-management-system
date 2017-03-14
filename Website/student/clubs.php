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
date_default_timezone_set('Africa/Cairo');
?>


<?php

$is_high_school = 0;
$query = sprintf("SELECT * FROM Students WHERE child_ssn = %d;", $_SESSION['student_ssn']);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG,1));
} else {
    $student_info = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);

    mysqli_free_result($raw_results);
    mysqli_next_result($conn);

    $query = sprintf("SELECT school_id FROM High_Schools WHERE school_id = %d;", $student_info[0]['school_id']);
    $raw_results = mysqli_query($conn, $query);
    if ($raw_results == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        if (mysqli_num_rows($raw_results) > 0) {
            $is_high_school = 1;
        }
    }
}

if ($is_high_school == false) {
    redirect_to('../', 2000);
    die(formattedMessage("Oops! You are not authorized to view this page!", 1));
}
?>

<?php
$query = sprintf("CALL ViewClubs('%s')", $student_username);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG,1));
} else {
    $clubs = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}
?>


<?php
mysqli_free_result($raw_results);
mysqli_next_result($conn);

$query = sprintf("SELECT * FROM Clubs_Joined_By_Students WHERE student_ssn = %d", $_SESSION['student_ssn']);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage("Oops! Something Wrong Happened With the database!"));
} else {
    $student_clubs = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}
?>


<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Clubs
        </div>

        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Purpose</th>
                    <th>Join</th>
                </tr>
                </thead>

                <tbody>
                <?php
                for ($x = 0; $x < count($clubs); $x++) {
                    echo "<tr>";
                    echo "<td>" . $clubs[$x]['name'] . "</td>";
                    echo "<td>" . $clubs[$x]['purpose'] . "</td>";
                    if (student_exists_in_club($clubs[$x]['name'], $student_clubs)) {
                        echo "<td><a href='clubs.php?unjoin_club_name=" . $clubs[$x]['name'] . "' class='btn btn-danger' role='button'>Unjoin
                         </a>";
                    } else {
                        echo "<td><a href='clubs.php?club_name=" . $clubs[$x]['name'] . "' class='btn btn-success' role='button'>Join</a>";
                    }
                    echo "</tr>";
                }
                ?>

                </tbody>
            </table>
        </div>

        <div class="panel-footer">
            Joining a club is fun and helps you gain a lot of experience.
        </div>
    </div>
</div>

<?php

function student_exists_in_club($name, $student_clubs)
{
    foreach ($student_clubs as $a) {
        if ($a['club_name'] == $name)
            return true;
    }
    return false;
}

if (isset($_GET['club_name'])) {
    mysqli_free_result($raw_results);
    mysqli_next_result($conn);
    $query = sprintf("CALL JoinClubs('%s','%s')", $student_username, $_GET['club_name']);
    $res = mysqli_query($conn, $query);
    if ($res == false) {
        die(formattedMessage("Oops! An Error Occured with the database!"));
    } else {
        echo formattedMessage("You successfully joined the club!", 2);
        redirect_to("clubs.php", 500);
    }
}

if (isset($_GET['unjoin_club_name'])) {
    mysqli_free_result($raw_results);
    mysqli_next_result($conn);
    $query = sprintf("DELETE FROM Clubs_Joined_By_Students WHERE student_ssn = %d and club_name = '%s' and high_school_id = %d;",
        $_SESSION['student_ssn'], $_GET['unjoin_club_name'], $student_info[0]['school_id']);
    $res = mysqli_query($conn, $query);
    if ($res == false)
        die(formattedMessage("Oops! Something Wrong Happened!", 1));
    else {
        echo formattedMessage("You have successfully unjoined this club!", 2);
        redirect_to("clubs.php", 500);
    }
}
?>