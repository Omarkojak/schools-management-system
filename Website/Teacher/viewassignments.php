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

$assignments = mysqli_query($conn, "CALL TeacherViewAssignment('" . $teacher_username . "')");
mysqli_next_result($conn);

$courses = mysqli_query($conn, "CALL ViewCoursesTaughtByTeacher('" . $teacher_username . "')");
mysqli_next_result($conn);

$solutions = mysqli_query($conn, "CALL TeacherViewAssignmentSolution('" . $teacher_username . "')");
mysqli_next_result($conn);

display_results($courses, $assignments, $solutions, array("student_ssn", "content", "grade"));
?>

<?php
function display_results($courses, $assignments, $solutions, $cols)
{
    foreach ($courses as $course) {
        echo "<div class=\"container\">";
        echo "<div class=\"panel panel-primary\">";
        echo "<div class=\"panel-heading\"><strong>" . "Assignments for course " . $course['course_code'] . " </strong></div>" .
            "<div class=\"panel-body\">";
        foreach ($assignments as $assignment) {
            if ($assignment['course_code'] == $course['course_code']) {
                echo "<div class=\"container\">";
                echo "<div class=\"panel panel-primary\">";
                echo "<div class=\"panel-heading\"> Assignment content :- " . $assignment['content'] . " posted on " . $assignment['posting_date'] .
                    " and due date on " . $assignment['due_date'] . "</div>" .
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
                foreach ($solutions as $solution) {
                    if ($solution['assignment_id'] == $assignment['id']) {
                        echo "<tr>";
                        echo " <td>" . $solution['student_ssn'] . "</td>";
                        echo " <td>" . $solution['content'] . "</td>";
                        if ($solution['grade']) {
                            echo " <td>" . $solution['grade'] . "</td>";
                        } else {
                            echo "<td><a href='gradeassignments.php?assignment_id=" . $assignment['id'] . "&student_ssn=" . $solution['student_ssn'] .
                                "&code=" . $course['course_code'] . "&cont =" . $solution['content'] .
                                "' class='btn btn-primary' role='button'>Grade Assignment!</a>";
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
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
}

?>
