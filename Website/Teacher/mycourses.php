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

display_results($courses, array("Code", "Post Assignments"))
?>


<?php
function display_results($courses, $cols)
{
    // Elementary Level
    echo "<div class=\"container\">";
    echo "<div class=\"panel panel-primary\">";
    echo "<div class=\"panel-heading\"><strong>" . "Elementary Level". "</strong></div>" .
        "<div class=\"panel-body\">";

    for ($grade = 1; $grade <= 6; $grade++)
    {
        echo "<div class=\"container\">";
        echo "<div class=\"panel panel-primary\">";
        echo "<div class=\"panel-heading\"><strong> Grade " . $grade . "</strong></div>" .
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
        foreach ($courses as $course) {
            if ($course['level_id'] == 1 && $course['grade'] == $grade) {
                echo "<tr>";
                echo "<td>" . $course['course_code'] . "</td>";
                echo "<td><a href='postassignments.php?code=" . $course['course_code'] . "' class='btn btn-primary' role='button'>Post Assignments!</a>";
                echo "</tr>";
            }
        }
        echo " </tbody>";
        echo " </table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    // Middle Level
    echo "<div class=\"container\">";
    echo "<div class=\"panel panel-primary\">";
    echo "<div class=\"panel-heading\"><strong>" . "Middle Level". "</strong></div>" .
        "<div class=\"panel-body\">";

    for ($grade = 1; $grade <= 3; $grade++)
    {
        echo "<div class=\"container\">";
        echo "<div class=\"panel panel-primary\">";
        echo "<div class=\"panel-heading\"><strong> Grade " . $grade . "</strong></div>" .
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
        foreach ($courses as $course) {
            if ($course['level_id'] == 2 && $course['grade'] == $grade) {
                echo "<tr>";
                echo "<td>" . $course['course_code'] . "</td>";
                echo "<td><a href='postassignments.php?code=" . $course['course_code'] . "' class='btn btn-primary' role='button'>Post Assignments!</a>";
                echo "</tr>";
            }
        }
        echo " </tbody>";
        echo " </table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    // High Level
    echo "<div class=\"container\">";
    echo "<div class=\"panel panel-primary\">";
    echo "<div class=\"panel-heading\"><strong>" . "High Level". "</strong></div>" .
        "<div class=\"panel-body\">";

    for ($grade = 1; $grade <= 3; $grade++)
    {
        echo "<div class=\"container\">";
        echo "<div class=\"panel panel-primary\">";
        echo "<div class=\"panel-heading\"><strong> Grade " . $grade . "</strong></div>" .
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
        foreach ($courses as $course) {
            if ($course['level_id'] == 3 && $course['grade'] == $grade) {
                echo "<tr>";
                echo "<td>" . $course['course_code'] . "</td>";
                echo "<td><a href='postassignments.php?code=" . $course['course_code'] . "' class='btn btn-primary' role='button'>Post Assignments!</a>";
                echo "</tr>";
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


<?php
include "../includes/footer.php";
?>
