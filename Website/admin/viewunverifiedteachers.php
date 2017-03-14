<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';


$user_logged_in = isset($_SESSION['admin_logged_in']) && !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == 1;
$admin_has_username = isset($_SESSION['admin_username']) && !empty(isset($_SESSION['admin_username']));

if (!$user_logged_in || !$admin_has_username ) {
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);
}
?>

<?php
$raw_results = mysqli_query($conn, sprintf("CALL View_Unverified_Teachers_As_Administrator('%s')",$_SESSION['admin_username']));
if ($raw_results == false) {
    die("<div class=\"container\">
            <div class=\"alert alert-danger\">
                <p><strong>Oops! </strong>" . mysqli_error($conn) . "</p>
            </div>
            </div>");
} else {
    if (mysqli_num_rows($raw_results) < 1) {
        die("<div class=\"container\">
            <div class=\"alert alert-info\">
                <p><strong>All Done! </strong>The system has no unverified teachers for you to view!</p>
            </div>
            </div>");
    }

    display_results($raw_results, array( "First Name","Middle Name","Last Name","Birth Date",
        "Address","email", "Gender", "Years of Experience"), "Unverified Teachers");
}
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

        echo " <td>" . $result['first_name'] . "</td>";
        echo " <td>" . $result['middle_name'] . "</td>";
        echo " <td>" . $result['last_name'] . "</td>";
        echo " <td>" . $result['birthdate'] . "</td>";
        echo " <td>" . $result['address'] . "</td>";
        echo " <td>" . $result['email'] . "</td>";
        echo " <td>" . $result['gender'] . "</td>";
        echo "<td><a href='verifyteacher.php?username=" . $result['username'] . "&yox=" . $result['years_of_experience'] . "' class='btn btn-primary' role='button'>Verify Student!</a>";
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