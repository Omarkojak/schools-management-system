<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';
$user_logged_in = isset($_SESSION['student_logged_in']) && !empty($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] == 1;
$student_has_username = isset($_SESSION['student_username']) && !empty(isset($_SESSION['student_username']));
$student_has_ssn = isset($_SESSION['student_ssn']) && !empty(isset($_SESSION['student_ssn']));
if (!$user_logged_in || !$student_has_username || !$student_has_ssn || !isset($_GET['code'])){
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);
}

$student_username = $_SESSION['student_username'];
date_default_timezone_set('Africa/Cairo');

?>



<?php
//    Check that the course is taken by the student
$course_code = $_GET['code'];
$student_username = $_SESSION['student_username'];
$query = sprintf("CALL IsCourseTakenByStudent('%s',%d)", $student_username, $course_code);
$raw_results = mysqli_query($conn, $query);
if (mysqli_num_rows($raw_results) < 1) {
    die(formattedMessage("Oops! The Requested page not found or you aren't authorized to view this page.", 1));
}
mysqli_free_result($raw_results);
mysqli_next_result($conn);
?>


<?php

$query = sprintf("CALL ViewAssignmentsGrades('%s',%d);", $student_username, $_GET['code']);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG,1));
} else {
    $grades = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}


mysqli_free_result($raw_results);
mysqli_next_result($conn);


$query = sprintf("CALL ViewAssignments('%s',%d);", $student_username, $_GET['code']);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG,1));
} else {
    $assignments = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}
?>

<div class="container">
    <div class="panel panel-primary">
        <div class="panel panel-heading">
            Assignments
        </div>

        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Content</th>
                    <th>Posting Date</th>
                    <th>Due Date</th>
                    <th>Solution</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $today = date("Y-m-d H:i:s");
                for ($x = 0; $x < count($assignments); $x++) {
                    echo "<tr>";
                    echo "<td>" . $assignments[$x]['id'] . "</td>";
                    echo "<td>" . $assignments[$x]['content'] . "</td>";
                    echo "<td>" . $assignments[$x]['posting_date'] . "</td>";
                    echo "<td>" . $assignments[$x]['due_date'] . "</td>";
                    if ($today > $assignments[$x]['due_date'])
                        echo "<td class='alert-danger'> Deadline passed!</td>";
                    else {
                        if (solution_submitted_before($assignments[$x]['id'], $grades)) {
                            echo "<td class='alert-info'> You Already Submitted!</td>";
                        } else {
                            echo "<td><a href='solve.php?id=" . $assignments[$x]['id'] . "&code=" . $_GET['code'] .
                                "' class='btn btn-primary' role='button'>Submit Solution!</a>";
                            echo "</td>";
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>


    <!--    My solutions -->
    <hr/>

    <div class="panel panel-primary">
        <div class="panel panel-heading">
            Grades
        </div>

        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Content</th>
                    <th>Grade</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $today = date("Y-m-d H:i:s");
                for ($x = 0; $x < count($grades); $x++) {
                    echo "<tr>";
                    echo "<td>" . $grades[$x]['id'] . "</td>";
                    echo "<td>" . $grades[$x]['content'] . "</td>";
                    if ($grades[$x]['grade'] != null)
                        echo "<td>" . $grades[$x]['grade'] . "</td>";
                    else
                        echo "<td class='alert-info'> No Grade Yet.</td>";
                    echo "</tt>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php

function solution_submitted_before($id, $grades)
{
    foreach ($grades as $sol) {
        if ($sol['id'] == $id) {
            return true;
        }
    }

    return false;
}

?>