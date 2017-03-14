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

$query = sprintf("CALL Teacher_list_Students('%s')",$teacher_username);
$students = mysqli_query($conn, $query);


display_results($students);
?>

<?php
function display_results($students)
{
    for ($grade = 1; $grade <= 12; $grade++) {
        echo "<div class=\"container\">";
        echo "<div class=\"panel panel-primary\">";
        echo "<div class=\"panel-heading\"><strong> Students for grade" . $grade . "</strong></div>" .
            "<div class=\"panel-body\">";
        echo "<table class=\"table table-user-information\">";
        echo " <thead>";
        echo "<tr>";
        echo "<th> first_name</th>";
        echo "<th>last_name</th>";
        echo "</tr>";
        echo " </thead>";
        echo " <tbody>";
        foreach ($students as $student) {
            if ($student['grade'] == $grade) {
                echo "<tr>";
                echo " <td>" . $student['first_name'] . "</td>";
                echo " <td>" . $student['last_name'] . "</td>";
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
