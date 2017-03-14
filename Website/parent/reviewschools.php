<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';

$user_logged_in = isset($_SESSION['parent_logged_in']) && !empty($_SESSION['parent_logged_in']) && $_SESSION['parent_logged_in'] == 1;
$parent_has_username = isset($_SESSION['parent_username']) && !empty(isset($_SESSION['parent_username']));
if (!$user_logged_in || !$parent_has_username) {
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);
}

$parent_username = $_SESSION['parent_username'];
?>

<?php
$query = sprintf("CALL ViewSchoolsList('%s')", $parent_username);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    $children_schools = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}

mysqli_free_result($raw_results);
mysqli_next_result($conn);

$query = sprintf("CALL ViewMyReviews('%s')", $parent_username);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    $my_reviews = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}
?>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Review Schools
        </div>

        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>School Name</th>
                    <th>Review</th>
                </tr>
                </thead>

                <tbody>
                <?php
                for ($x = 0; $x < count($children_schools); $x++) {
                    echo "<tr>";
                    echo "<td>" . $children_schools[$x]['name'] . "</td>";
                    echo "<td><a href='writereview.php?school_id=" . $children_schools[$x]['id'] .
                        "' class='btn btn-primary' role='button'>Write Review</a></td>";
                    echo "</tr>";
                }
                ?>

                </tbody>
            </table>
        </div>

    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            My Reviews
        </div>

        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>School Name</th>
                    <th>Review</th>
                    <th>Delete</th>
                </tr>
                </thead>

                <tbody>
                <?php
                for ($x = 0; $x < count($my_reviews); $x++) {
                    echo "<tr>";
                    echo "<td>" . $my_reviews[$x]['name'] . "</td>";
                    echo "<td>" . $my_reviews[$x]['review'] . "</td>";
                    echo "<td><a href='reviewschools.php?delete_review=" . $my_reviews[$x]['id']  .
                        "' class='btn btn-danger' role='button'>Delete Review</a></td>";
                    echo "</tr>";
                }
                ?>

                </tbody>
            </table>
        </div>

    </div>
</div>

<?php
    if (isset($_GET['delete_review'])){
        $school_id = mysqli_real_escape_string($conn,$_GET['delete_review']);

        mysqli_free_result($raw_results);
        mysqli_next_result($conn);

        $query = sprintf("CALL DeleteReview('%s','%s')", $parent_username,$school_id);
        $raw_results = mysqli_query($conn, $query);
        if ($raw_results == false) {
            die(formattedMessage(DIE_MSG, 1));
        } else {
           echo formattedMessage("Review Deleted !",2);
           redirect_to("reviewschools.php",1000);
        }

    }
?>