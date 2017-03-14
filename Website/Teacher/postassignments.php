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
$query = sprintf("SELECT * FROM Courses_Taught_In_School_By_Teacher WHERE teacher_username = '%s' and course_code = '%d'",
    $teacher_username, $course_code);
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
            Post Assignment :
        </div>

        <div class="panel-body">
            <form action="" method="post">
                <div class="input-group">
                    <label for="content">Content :</label>
                    <textarea class="form-control" name="content" rows="5" cols="40"
                              id="content"></textarea>
                </div>

                <div class="input-group">
                    <label for="ddate">Due Date</label>
                    <input type="text" name="ddate" class="form-control" placeholder="" data-provide="datepicker"
                           data-date-format="yyyy/mm/dd">
                </div>

                <br/>

                <button class="btn btn-primary" name="post_assignment" value="post_assignment" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['post_assignment'])) {
    $cont = $_POST['content'];
    $ddate = $_POST['ddate'];
    $course_code = $_GET['code'];
    $teacher_username = $_SESSION['teacher_username'];
    $sdate = time();

    $raw_results = mysqli_query($conn, "CALL PostAssigmentByTeacher('" . $teacher_username . "', ".$course_code . ",'"
        . $sdate ."','".  $ddate ."','" . $cont . "')");

    if ($raw_results == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        echo formattedMessage("Assignment Posted!", 2);
        redirect_to(sprintf("mycourses.php"), 1000);
    }
}
?>

