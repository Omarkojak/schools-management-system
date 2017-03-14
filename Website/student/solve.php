<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';

$user_logged_in = isset($_SESSION['student_logged_in']) && !empty($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] == 1;
$student_has_username = isset($_SESSION['student_username']) && !empty(isset($_SESSION['student_username']));
$student_has_ssn = isset($_SESSION['student_ssn']) && !empty(isset($_SESSION['student_ssn']));
if (!$user_logged_in || !$student_has_username || !$student_has_ssn || !isset($_GET['id']) || !isset($_GET['code'])){
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
//    Check that the assignment is in the course code specified.
$query = sprintf("CALL IsAssignmentInCourse(%d,%d)", $_GET['id'], $course_code);
$raw_results = mysqli_query($conn, $query);
if (mysqli_num_rows($raw_results) < 1) {
    die(formattedMessage("Oops! The Requested page not found or you aren't authorized to view this page.", 1));
}
mysqli_free_result($raw_results);
mysqli_next_result($conn);
?>


<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Solve Assignment : <?php echo $_GET['id']; ?>
        </div>

        <div class="panel-body">
            <form action="" method="post">
                <div class="input-group">
                    <label for="answer">Answer :</label>
                    <textarea class="form-control" name="answer" rows="5" cols="40"
                              id="answer"></textarea>
                </div>

                <br/>

                <button class="btn btn-primary" name="answer_assignment" value="answer_assignment" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['answer_assignment'])) {
    $query = sprintf("CALL SolveAssignments('%s',%d,'%s')", $student_username, $_GET['id'], $_POST['answer']);
    $res = mysqli_query($conn, $query);
    if ($res == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        echo formattedMessage("Solution Submitted!", 2);
        redirect_to(sprintf("assigments.php?code=%d", $course_code), 1000);
    }
}
?>