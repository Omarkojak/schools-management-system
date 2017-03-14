<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';
include '../includes/footer.php';
?>

<?php
$result = array();
if (isset($_GET['id'])) {
    $school_id = $_GET['id'];
    $school_id = htmlspecialchars($school_id);
    $school_id = mysqli_real_escape_string($conn, $school_id);
    $raw_results = mysqli_query($conn, "CALL View_Specific_School('" . $school_id . "')");
    if ($raw_results == false) {
        echo mysqli_error($conn);
    } else {
        $result = mysqli_fetch_array($raw_results);
        if (mysqli_num_rows($raw_results) != 1) {
            die("<div class=\"container\">
            <div class=\"alert alert-danger\">
                <p><strong>Oops! </strong>The selected school is not found or might have been deleted!</p>
            </div>
            </div>");
        }
        mysqli_free_result($raw_results);
        mysqli_next_result($conn);

        $raw_phone_nums = mysqli_query($conn, "CALL View_Specific_School_PhoneNumbers('" . $school_id . "')");
        $phone_nums = mysqli_fetch_all($raw_phone_nums, MYSQLI_ASSOC);
        mysqli_free_result($raw_phone_nums);
        mysqli_next_result($conn);

        $elementary = mysqli_query($conn, "SELECT supplies FROM Elementary_Schools WHERE school_id =" . $school_id . "");
        $has_elementary = mysqli_num_rows($elementary) == 1 ? "YES" : "NO";

        $has_middle = mysqli_num_rows(mysqli_query($conn, "SELECT school_id FROM Middle_Schools WHERE school_id =" . $school_id . ""));
        $has_middle = $has_middle == 1 ? "YES" : "NO";

        $has_high = mysqli_num_rows(mysqli_query($conn, "SELECT school_id FROM High_Schools WHERE school_id =" . $school_id . ""));
        $has_high = $has_high == 1 ? "YES" : "NO";

        $raw_school_reviews = mysqli_query($conn, "CALL View_Specific_School_Reviews('" . $school_id . "')");
        $school_reviews = mysqli_fetch_all($raw_school_reviews, MYSQLI_ASSOC);
        mysqli_free_result($raw_school_reviews);
        mysqli_next_result($conn);



        $raw_school_teachers = mysqli_query($conn, "CALL View_Specific_School_Teachers('" . $school_id . "')");
        $school_teachers = mysqli_fetch_all($raw_school_teachers, MYSQLI_ASSOC);
        mysqli_free_result($raw_school_teachers);
        mysqli_next_result($conn);

        $raw_school_announcements = mysqli_query($conn, "CALL View_Specific_School_Announcements('" . $school_id . "')");
        $school_announcements = mysqli_fetch_all($raw_school_announcements, MYSQLI_ASSOC);
        mysqli_free_result($raw_school_announcements);
        mysqli_next_result($conn);


        if ($raw_phone_nums == false) {
            echo mysqli_error($conn);
        } else {
            echo " <div class=\"container\">
            <div class=\"panel panel-primary\">
            <div class=\"panel-heading\">School Information</div>
                <table class=\"table table-user-information\">
                <tbody>";

            echo "<tr>" .
                "<td>Name:</td><td>" .
                $result['name'] . "</td></tr>";

            echo "<tr>" .
                "<td>Mission:</td><td>" .
                $result['mission'] . "</td></tr>";

            echo "<tr>" .
                "<td>Vision:</td><td>" .
                $result['vision'] . "</td></tr>";

            echo "<tr>" .
                "<td>Main language:</td><td>" .
                $result['main_language'] . "</td></tr>";

            echo "<tr>" .
                "<td>Type:</td><td>" .
                $result['type'] . "</td></tr>";

            echo "<tr>" .
                "<td>Fees:</td><td>" .
                $result['fees'] . "</td></tr>";

            echo "<tr>" .
                "<td>Address:</td><td>" .
                $result['address'] . "</td></tr>";

            echo "<tr>" .
                "<td>Phone Numbers:</td><td>";
            for ($x = 0; $x < count($phone_nums); $x++) {
                echo $phone_nums[$x]['telephone_number'] . "</br>";
            }
            echo "</td></tr>";

            echo "<tr>" .
                "<td>Email:</td><td>" .
                $result['email'] . "</td></tr>";

            echo "<tr>" .
                "<td>General Info:</td><td>" .
                $result['general_info'] . "</td></tr>";

            echo "<tr>" .
                "<td>Has Elementary School:</td><td>" .
                $has_elementary . "</td></tr>";
            if ($has_elementary == "YES") {
                $elementary = mysqli_fetch_array($elementary);
                echo "<tr>" .
                    "<td>Elementary School Supplies:</td><td>" .
                    $elementary['supplies'] . "</td></tr>";

            }
            echo "<tr>" .
                "<td>Has Middle School:</td><td>" .
                $has_middle . "</td></tr>";

            echo "<tr>" .
                "<td>Has High School:</td><td>" .
                $has_high . "</td></tr>";

            echo "  </tbody>
                </table>
                </div>";
        }
    }

}

echo " <div class=\"panel panel-primary\">
            <div class=\"panel-heading\">Reviews</div>";
echo "<table class=\"table table-user-information\">";
echo " <thead>";
echo "<tr>";
echo "<th>Parent Username</th>";
echo "<th>Review</th>";
echo "</tr>";
echo "</thead>";
echo " <tbody>";
for ($x = 0; $x < count($school_reviews); $x++) {
    echo "<tr>";
    echo "<td>" . $school_reviews[$x]['parent_username'] . "</br>" . "</td>";
    echo "<td>" . $school_reviews[$x]['review'] . "</br>" . "</td>";
    echo "</tr>";
}

echo " </tbody>";
echo "</table>";
echo "</div>";

echo " <div class=\"panel panel-primary\">
            <div class=\"panel-heading\">Teachers</div>";
echo "<table class=\"table table-user-information\">";
echo " <thead>";
echo "<tr>";
echo "<th>Teacher Name</th>";
echo "</tr>";
echo "</thead>";
echo " <tbody>";
for ($x = 0; $x < count($school_teachers); $x++) {
    echo "<tr>";
    echo "<td>" . $school_teachers[$x]['first_name'] . " " . $school_teachers[$x]['middle_name'] .
        " " . $school_teachers[$x]['last_name'] . "</br>" . "</td>";
    echo "</tr>";
}

echo " </tbody>";
echo "</table>";
echo "</div>";


echo " <div class=\"panel panel-primary\">
            <div class=\"panel-heading\">Announcements</div>";
echo "<table class=\"table table-user-information\">";
echo " <thead>";
echo "<tr>";
echo "<th>Title</th>";
echo "<th>Description</th>";
echo "</tr>";
echo "</thead>";
echo " <tbody>";
for ($x = 0; $x < count($school_announcements); $x++) {
    echo "<tr>";
    echo "<td>" . $school_announcements[$x]['title'] . "</br>" . "</td>";
    echo "<td>" . $school_announcements[$x]['description'] . "</br>" . "</td>";
    echo "</tr>";
}

echo " </tbody>";
echo "</table>";
echo "</div>";


echo "</div>";
?>


