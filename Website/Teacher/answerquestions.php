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
            Answer Question :
        </div>
        <p><?php echo $_GET['title']; ?></p>
        <p> <?php echo $_GET['content']; ?></p>
        <p> <?php echo "by ". $_GET['name']; ?></p>

        <div class="panel-body">
            <form action="" method="post">
                <div class="input-group">
                    <label for="ans">Answer :</label>
                    <textarea class="form-control" name="ans" rows="5" cols="40"
                              id="ans"></textarea>
                </div>
                <br/>

                <button class="btn btn-primary" name="answer_question" value="answer_question" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['answer_question'])) {

    $query = sprintf("CALL Teacher_Answer_Questions('%s',%d, '%s')",$teacher_username , $_GET['q_id'], $_POST['ans']);
    $res = mysqli_query($conn, $query);
    if ($res == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        echo formattedMessage("Answer submitted!", 2);
        redirect_to(sprintf("viewquestions.php"), 1000);
    }
}
?>


