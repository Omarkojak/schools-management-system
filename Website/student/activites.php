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
$query = sprintf("CALL ViewActivities('%s')", $student_username);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    $activites = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}
?>


<?php
mysqli_free_result($raw_results);
mysqli_next_result($conn);

$query = sprintf("SELECT activity_id FROM Activities_Participated_In_By_Students WHERE student_ssn = 1", $_SESSION['student_ssn']);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    $student_activites = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}
?>


    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Activites
            </div>

            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Equipment</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Supervisor Name</th>
                        <th>Join</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    for ($x = 0; $x < count($activites); $x++) {
                        echo "<tr>";
                        echo "<td>" . $activites[$x]['type'] . "</td>";
                        echo "<td>" . $activites[$x]['equipment'] . "</td>";
                        echo "<td>" . $activites[$x]['date'] . "</td>";
                        echo "<td>" . $activites[$x]['location'] . "</td>";
                        echo "<td>" . $activites[$x]['first_name'] . " " . $activites[$x]['middle_name'] . " " . $activites[$x]['last_name'] . "</td>";
                        if (student_exists_in_activity($activites[$x]['id'], $student_activites)) {
                            echo "<td><a href='activites.php?unjoin_activity_id=" . $activites[$x]['id'] . "' class='btn btn-danger' role='button'>Unjoin</a>";
                        } else {
                            echo "<td><a href='activites.php?activity_id=" . $activites[$x]['id'] . "' class='btn btn-success' role='button'>Join</a>";
                        }
                        echo "</tr>";
                    }
                    ?>

                    </tbody>
                </table>
            </div>

            <div class="panel-footer">
                You can join any activity on the condition that not to join two activities of the same type on
                the same date.
            </div>
        </div>
    </div>

<?php

function student_exists_in_activity($id, $student_activites)
{
    foreach ($student_activites as $a) {
        if ($a['activity_id'] == $id)
            return true;
    }
    return false;
}

if (isset($_GET['activity_id'])) {
    mysqli_free_result($raw_results);
    mysqli_next_result($conn);
    $query = sprintf("CALL ApplyForActivity('%s',%d)", $student_username, $_GET['activity_id']);
    $res = mysqli_query($conn, $query);
    if ($res == false)
        die(formattedMessage(DIE_MSG, 1));
    else {
        if (!is_bool($res)) {
            $res = mysqli_fetch_all($res, MYSQLI_ASSOC);
            if ($res[0]['status'] == "Cannot JOIN")
                echo formattedMessage("Oops! You cann't join this activity!", 1);
        } else {
            echo formattedMessage("Congrats! You Joined this activity!", 2);
            redirect_to("activites.php", 500);
        }
    }
}

if (isset($_GET['unjoin_activity_id'])) {
    mysqli_free_result($raw_results);
    mysqli_next_result($conn);
    $query = sprintf("DELETE FROM Activities_Participated_In_By_Students WHERE student_ssn = %d AND activity_id = %d",
        $_SESSION['student_ssn'], $_GET['unjoin_activity_id']);

    $res = mysqli_query($conn, $query);
    if ($res == false)
        die(formattedMessage(DIE_MSG, 1));
    else {
        echo formattedMessage("You have successfully unjoined this activity!", 2);
        redirect_to("activites.php", 500);
    }
}
?>