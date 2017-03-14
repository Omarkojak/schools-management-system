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

<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Reply To Report
        </div>

        <div class="panel-body">
            <form action="" method="post">
                <div class="input-group">
                    <label for="description">Reply:</label>
                    <textarea class="form-control" name="description" rows="5" cols="40"
                              id="description"></textarea>
                </div>

                <br/>

                <button class="btn btn-primary" name="submit_reply" value="submit_reply" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['submit_reply'])) {

    $date = mysqli_real_escape_string($conn,$_GET['date']);
    $student_ssn = mysqli_real_escape_string($conn,$_GET['student_ssn']);
    $t_username = mysqli_real_escape_string($conn,$_GET['teacher']);
    $desc = mysqli_real_escape_string($conn,$_POST['description']);

    $query = sprintf("CALL ReplyToReports('%s','%s','%s','%s')", $student_ssn,$t_username,$date,$desc);
    $q_ins = mysqli_query($conn, $query);
    if ($q_ins == false) {
        echo formattedMessage("Oops! You have already Replied to this report!",1);
        redirect_to("reports.php", 500);
    } else {
        echo formattedMessage("Reply Sent!", 2);
        redirect_to("reports.php", 1000);
    }
}
?>

