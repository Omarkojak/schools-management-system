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

<?php
//Check that the course is taught by the teacher
$course_code = $_GET['code'];
$teacher_username = $_SESSION['teacher_username'];
$query = sprintf("SELECT * FROM Courses_Taught_In_School_By_Teacher WHERE teacher_username = '%s' and course_code = '%u'",
    $teacher_username, $course_code);
$raw_results = mysqli_query($conn, $query);
if (mysqli_num_rows($raw_results) < 1) {
    die(formattedMessage("Oops! The Requested page not found or you aren't authorized to view this page.", 1));
}
mysqli_free_result($raw_results);
mysqli_next_result($conn);
?>

<?php
$query = sprintf("SELECT first_name, last_name FROM Children WHERE ssn = '%d'",
    $_GET['student_ssn']);
$names = mysqli_query($conn, $query)->fetch_array(MYSQLI_ASSOC);

?>

<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Grade Assignment :
        </div>

        <p> <?php echo $_GET['content'] . " by " . $names['first_name'] . " " . $names['last_name']; ?> </p>
        <div class="panel-body">
            <form action="" method="post">
                <div class="input-group">
                    <label for="grade"> Grade :</label>
                    <textarea class="form-control" type = "number" name="grade"
                              id="grade"></textarea>
                </div>
                <br/>

                <button class="btn btn-primary" name="grade_assignment" value="grade_assignment" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['grade_assignment'])) {

    $query = sprintf("CALL TeacherUpdatesAssignmentGrade('%s',%d,%d, %d)",$teacher_username , $_GET['assignment_id']
        , $_GET['student_ssn'], $_POST['grade']);
    $res = mysqli_query($conn, $query);
    if ($res == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        echo formattedMessage("Grade added!", 2);
        redirect_to(sprintf("viewassignments.php"), 1000);
    }
}
?>


