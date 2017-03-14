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
$raw_results = mysqli_query($conn, sprintf("CALL View_Applied_And_Unverified_Children('%s')",$_SESSION['admin_username']));
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
                <p><strong>All Done! </strong>The system has no applications for you to view!</p>
            </div>
            </div>");
    }

    display_results($raw_results, array("First Name","Last Name","Age","gender"), "Unverified Applications");
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
        echo " <td>" . $result['last_name'] . "</td>";
        echo " <td>" . $result['age'] . "</td>";
        echo " <td>" . $result['gender'] . "</td>";
        echo "<td><a href='verifychildren.php?ssn=" . $result['child_ssn'] . "&response=1" . "&parent=".$result['parent_username'] . "' class='btn btn-primary' 
        role='button'>Accept!</a>";
        echo "<td><a href='verifychildren.php?ssn=" . $result['child_ssn'] . "&response=0" . "&parent=".$result['parent_username'] ."' class='btn btn-primary'
        role='button'>Reject!</a>";
        echo "</tr>";
    }
    echo " </tbody>";
    echo " </table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

?>