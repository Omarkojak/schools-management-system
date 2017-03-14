<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';

$user_logged_in = isset($_SESSION['student_logged_in']) && !empty($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] == 1;
$student_has_username = isset($_SESSION['student_username']) && !empty(isset($_SESSION['student_username']));
$student_has_ssn = isset($_SESSION['student_ssn']) && !empty(isset($_SESSION['student_ssn']));

if (!$user_logged_in || !$student_has_username || !$student_has_ssn || !isset($_GET['code'])) {
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);

}?>




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
$student_username = $_SESSION['student_username'];
$query = "CALL ViewQuestions('" . $student_username . "'," . $course_code . ")";
$course_info_raw_results = mysqli_query($conn, $query);

$course_info = null;
if ($course_info_raw_results == false) {
    die(formattedMessage(DIE_MSG,1));
} else {
    if (mysqli_num_rows($course_info_raw_results) < 1) {
        echo(formattedMessage("Oops! No Questions for this course yet! Want to add one?"));
    } else {
        $course_info = mysqli_fetch_all($course_info_raw_results, MYSQLI_ASSOC);
    }
}
?>
<div class="container">
    <a href="postquestion.php?code=<?php echo $_GET['code']; ?>" class='btn btn-success' role="button"
       style="float: right;">Post Question</a>
    <br/>
    <br/>
    <?php if ($course_info == null) die(); ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            Questions for course : <?php echo $_GET['code']; ?>
        </div>

        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Answer</th>
                </tr>
                </thead>

                <tbody>
                <?php
                for ($x = 0; $x < count($course_info); $x++) {
                    echo "<tr>";
                    echo "<td>" . $course_info[$x]['title'] . "</td>";
                    echo "<td>" . $course_info[$x]['content'] . "</td>";
                    if (strlen($course_info[$x]['answer']) > 0)
                        echo "<td>" . $course_info[$x]['answer'] . "</td>";
                    else
                        echo "<td class='alert-danger'>Not answered yet!</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

