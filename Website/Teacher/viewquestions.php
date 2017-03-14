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
$teacher_username = $_SESSION['teacher_username'];
$teacher_username = htmlspecialchars($teacher_username);
$teacher_username = mysqli_real_escape_string($conn, $teacher_username);

$courses = mysqli_query($conn, "CALL ViewCoursesTaughtByTeacher('" . $teacher_username . "')");
mysqli_next_result($conn);

$questions = mysqli_query($conn, "CALL Teacher_View_Questions('" . $teacher_username . "')");
mysqli_next_result($conn);


display_results($courses, $questions, array('first_name', 'last_name', 'title', 'content', 'answer'));
?>

<?php
function display_results($courses, $questions, $cols)
{

    foreach ($courses as $course) {
        echo "<div class=\"container\">";
        echo "<div class=\"panel panel-primary\">";
        echo "<div class=\"panel-heading\"><strong>" . "Questions for course " . $course['course_code'] . " </strong></div>" .
            "<div class=\"panel-body\">";
        echo "<table class=\"table table-user-information\">";
        echo " <thead>";
        echo "<tr>";
        foreach ($cols as $col) {
            echo "<th>" . $col . "</th>";
        }
        echo "</tr>";
        echo " </thead>";
        echo " <tbody>";
        foreach ($questions as $question) {
            if ($question['course_code'] == $course['course_code']) {
                echo "<tr>";
                echo " <td>" . $question['first_name'] . "</td>";
                echo " <td>" . $question['last_name'] . "</td>";
                echo " <td>" . $question['title'] . "</td>";
                echo " <td>" . $question['content'] . "</td>";
                if($question['answer']) {
                    echo "<td>" . $question['answer'] . "</td>";
                }else {
                    $name = $question['first_name'] ."". $question['last_name'];
                    echo "<td><a href='answerquestions.php?q_id=" . $question['id'] . "&name=" . $name .
                        "&title=" . $question['title'] . "&content=" . $question['content'] .
                        "' class='btn btn-primary' role='button'>answer question!</a>";
                    echo "</tr>";
                }
            }


        }
        echo " </tbody>";
        echo " </table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

    }


}

?>
