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


<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Post Assignment :
        </div>

        <div class="panel-body">
            <form action="" method="post">
                <div class="input-group">
                    <label for="ssn"> Student_ssn :</label>
                    <textarea class="form-control" type = "number" name="ssn"
                              id="ssn"></textarea>
                </div>

                <div class="input-group">
                    <label for="content">Report :</label>
                    <textarea class="form-control" name="content" rows="5" cols="40"
                              id="content"></textarea>
                </div>

                <br/>

                <button class="btn btn-primary" name="post_report" value="post_report" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['post_report'])) {
    $cont = $_POST['content'];
    $ssn = $_POST['ssn'];
    $teacher_username = $_SESSION['teacher_username'];
    $pdate = time();


    $raw_results = mysqli_query($conn, "CALL ReportAboutStudentByTeacher('" . $teacher_username . "', '". $cont . "','"
        . $pdate ."',".  $ssn .")");
    if ($raw_results == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        echo formattedMessage("Report Posted!", 2);
        redirect_to(sprintf("index.php"), 1000);
    }
}
?>

