<?php
session_start();
include 'includes/header.php';
include "includes/footer.php";
?>

<?php
function display_results($results, $cols, $type, $header_title)
{
    echo "<div class=\"container\">";
    echo "<div class=\"panel panel-primary\">";
    echo "<div class=\"panel-heading\"> Results for query : " . $header_title . "</div>" .
        "<div class=\"panel-body\">";
    echo "<div class=\"row\">";
    echo "<div class=\" col-md-6 col-lg-9 \">
                    <table class=\"table table-user-information\">";
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
        echo " <td><a href='schools/school.php?id=" . $result['id'] . "'>" . $result['name'] . "</a></td>";
        echo "</tr>";
    }
    echo " </tbody>";
    echo " </table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

?>
<?php

// Address Query
if (isset($_GET['aquery'])) {
    $search_query = $_GET['aquery'];
    $search_query = htmlspecialchars($search_query);
    $search_query = mysqli_real_escape_string($conn, $search_query);
    $raw_results = mysqli_query($conn, "CALL Search_For_School_By_Address('" . $search_query . "')");
    if ($raw_results == false) {
        echo mysqli_error($conn);
    } else {
        $cols = array('Name');
        if (mysqli_num_rows($raw_results) < 1) {
            if (mysqli_num_rows($raw_results) != 1) {
                die(formattedMessage("Oops! Your search returned no results!"));
            }
        }
        display_results($raw_results, $cols, 1, $search_query);
    }

}

// Name Query
if (isset($_GET['nquery'])) {
    $search_query = $_GET['nquery'];
    $search_query = htmlspecialchars($search_query);
    $search_query = mysqli_real_escape_string($conn, $search_query);
    $raw_results = mysqli_query($conn, "CALL Search_For_School_By_Name('" . $search_query . "')");
    if ($raw_results == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        $cols = array('Name');
        if (mysqli_num_rows($raw_results) < 1) {
            if (mysqli_num_rows($raw_results) != 1) {
                die(formattedMessage("Oops! Your search returned no results!"));
            }
        }
        display_results($raw_results, $cols, 1, $search_query);
    }

}

// Type Query
if (isset($_GET['tquery'])) {
    $search_query = $_GET['tquery'];
    $search_query = htmlspecialchars($search_query);
    $search_query = mysqli_real_escape_string($conn, $search_query);
    $raw_results = mysqli_query($conn, "CALL Search_For_School_By_Type('" . $search_query . "')");
    if ($raw_results == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        $cols = array('Name');
        if (mysqli_num_rows($raw_results) < 1) {
            if (mysqli_num_rows($raw_results) != 1) {
                die(formattedMessage("Oops! Your search returned no results!"));
            }
        }
        display_results($raw_results, $cols, 1, $search_query);
    }

}
?>
