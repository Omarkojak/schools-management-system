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
}
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


<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Post Question for course : <?php echo $_GET['code']; ?>
        </div>

        <div class="panel-body">
            <form action="" method="post">
                <div class="input-group">
                    <label for="title">Title :</label>
                    <input type="text" name="title" class="form-control" id="title"
                           placeholder="A Descriptive Title">
                </div>
                <br/>
                <div class="input-group">
                    <label for="description">Decription:</label>
                    <textarea class="form-control" name="description" rows="5" cols="40"
                              id="description"></textarea>
                </div>

                <br/>

                <button class="btn btn-primary" name="submit_question" value="submit_question" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['submit_question'])) {
    $query = sprintf("INSERT INTO Questions (title, content) VALUES ('%s', '%s')", $_POST['title'], $_POST['description']);
    $q_ins = mysqli_query($conn, $query);
    if ($q_ins == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        $question_id = mysqli_insert_id($conn);

        $query = sprintf("CALL PostQuestionsOnCourses2('%s',%d,%s)", $_SESSION['student_username'], $question_id, $_GET['code']);
        $q_ins = mysqli_query($conn, $query);
        if ($q_ins == false) {
            die(formattedMessage(DIE_MSG,1));
        } else {
            echo formattedMessage("Question Posted!", 2);
            redirect_to("questions.php?code=" . $_GET['code'], 500);
        }

    }
}
?>
