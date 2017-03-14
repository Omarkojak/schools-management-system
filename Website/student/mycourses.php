<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';

$user_logged_in = isset($_SESSION['student_logged_in']) && !empty($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] == 1;
$student_has_username = isset($_SESSION['student_username']) && !empty(isset($_SESSION['student_username']));
$student_has_ssn = isset($_SESSION['student_ssn']) && !empty(isset($_SESSION['student_ssn']));

if (!$user_logged_in || !$student_has_username || !$student_has_ssn) {
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);
}
?>


<?php
$student_username = $_SESSION['student_username'];
$student_username = htmlspecialchars($student_username);
$student_username = mysqli_real_escape_string($conn, $student_username);
$raw_results = mysqli_query($conn, "CALL ViewCoursesNames('" . $student_username . "')");

display_results($raw_results, array("Code", "Name", "Description", "Questions", "Assignments"), "My Courses")
?>

<?php
function display_results($results, $cols, $header_title)
{
    echo "<div class=\"container\">";
    echo "<div class=\"panel panel-primary\">";
    echo "<div class=\"panel-heading\"><strong>" . $header_title . "</strong></div>" .
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
    while ($result = mysqli_fetch_array($results)) {
        echo "<tr>";
        echo " <td>" . $result['code'] . "</td>";
        echo " <td>" . $result['name'] . "</td>";
        echo " <td>" . $result['description'] . "</td>";
        echo "<td><a href='questions.php?code=" . $result['code'] . "' class='btn btn-primary' role='button'>View Questions!</a>";
        echo "<td><a href='assigments.php?code=" . $result['code'] . "' class='btn btn-primary' role='button'>View Assignments!</a>";
//        echo "<td><a href=\"assigments.php?code=\'".$result['code']. "class=\'btn btn-primary\' role=\'button\'>View Assignments!</a>";
        echo "</tr>";
    }
    echo " </tbody>";
    echo " </table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

?>
<?php
include "../includes/footer.php";
?>