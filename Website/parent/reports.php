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
$query = sprintf("CALL ViewReports('%s')", $parent_username);
$raw_results = mysqli_query($conn, $query);
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    $children_reports = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}
?>

<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Children Reports
        </div>

        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Teacher Name</th>
                    <th>Teacher Comment</th>
                    <th>Your Reply</th>
                    <th>Reply</th>
                </tr>
                </thead>

                <tbody>
                <?php
                for ($x = 0; $x < count($children_reports); $x++) {
                    echo "<tr>";
                    echo "<td>".$children_reports[$x]['date']."</td>";
                    echo "<td>" . $children_reports[$x]['first_name'] ." ".$children_reports[$x]['middle_name']." ".
                        $children_reports[$x]['last_name'] ."</td>";
                    echo "<td>".$children_reports[$x]['teacher_comment']."</td>";
                    echo "<td>".$children_reports[$x]['parent_reply']."</td>";
                    if ($children_reports[$x]['parent_reply'] == null)
                        echo "<td><a href='reportreply.php?date=" . $children_reports[$x]['date'] .
                        "&student_ssn=".$children_reports[$x]['student_ssn']."&teacher=".$children_reports[$x]['teacher_username'].
                        "' class='btn btn-primary' role='button'>Reply</a></td>";
                    else
                        echo "<td class='alert-danger'>Already Replied!</td>";
                    echo "</tr>";
                }
                ?>

                </tbody>
            </table>
        </div>

    </div>

