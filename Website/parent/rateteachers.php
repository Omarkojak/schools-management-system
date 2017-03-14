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
$query = sprintf("CALL ViewTeachers('%s')", $parent_username);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    $children_teachers = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}

mysqli_free_result($raw_results);
mysqli_next_result($conn);

$query = sprintf("CALL ViewRatingsForAllTeachers()");
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    $teachers_ratings = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}
?>

    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Rate Teachers
            </div>

            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Teacher Name</th>
                        <th>Avg Rating</th>
                        <th>Rate Teacher</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    for ($x = 0; $x < count($children_teachers); $x++) {
                        echo "<tr>";
                        echo "<td>" . $children_teachers[$x]['first_name'] . " " . $children_teachers[$x]['last_name'] . "</td>";
                        echo "<td>" . findTeacherRating($teachers_ratings, $children_teachers[$x]['teacher_username']) . "</td>";
                        echo "<td>
                        <a href='rateteachers.php?teacher_username=" . $children_teachers[$x]['teacher_username'] . "&rating=1" .
                            "' class='btn btn-danger' role='button'>1</a>
                         <a href='rateteachers.php?teacher_username=" . $children_teachers[$x]['teacher_username'] . "&rating=2" .
                            "' class='btn btn-default' role='button'>2</a>
                            <a href='rateteachers.php?teacher_username=" . $children_teachers[$x]['teacher_username'] . "&rating=3" .
                            "' class='btn btn-warning' role='button'>3</a>
                            <a href='rateteachers.php?teacher_username=" . $children_teachers[$x]['teacher_username'] . "&rating=4" .
                            "' class='btn btn-primary' role='button'>4</a>
                            <a href='rateteachers.php?teacher_username=" . $children_teachers[$x]['teacher_username'] . "&rating=5" .
                            "' class='btn btn-success' role='button'>5</a>
                        </td>";
                        echo "</tr>";
                    }
                    ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>


<?php
function findTeacherRating($values, $t_username)
{
    foreach ($values as $value) {
        if ($value['teacher_username'] == $t_username)
            return $value['AVG(rating)'];
    }
    return 0;
}

if (isset($_GET['teacher_username']) && isset($_GET['rating'])){
    $teacher_u = mysqli_real_escape_string($conn,$_GET['teacher_username']);
    $t_rating = mysqli_real_escape_string($conn,$_GET['rating']);

    mysqli_free_result($raw_results);
    mysqli_next_result($conn);

    $query = sprintf("CALL RateTeachers('%s','%s',%s)",$_SESSION['parent_username'],$teacher_u,$t_rating);
    $raw_results = mysqli_query($conn, $query);
    if ($raw_results == false) {
        die(formattedMessage("You have already rated that teacher!", 0));
    } else {
        echo formattedMessage("Rating Updated!",2);
        redirect_to("rateteachers.php",1000);
    }

}
?>