<?php
session_start();
include '../includes/header.php';
include '../includes/footer.php';

$user_logged_in = isset($_SESSION['student_logged_in']) && !empty($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] == 1;
$student_has_username = isset($_SESSION['student_username']) && !empty(isset($_SESSION['student_username']));
$student_has_ssn = isset($_SESSION['student_ssn']) && !empty(isset($_SESSION['student_ssn']));

if (!$user_logged_in || !$student_has_username || !$student_has_ssn) {
    $loc = 'Location: ' . $path . '/index.php';
    header($loc);
}
?>



<?php

$student_username = $_SESSION['student_username'];

$raw_results = mysqli_query($conn, "CALL DisplayAccountInformation('" . $_SESSION['student_ssn'] . "')");
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    $user = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}


?>

    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Profile</div>
            <div class="panel-body">
                <form action="" method="post">
                    <div class="input-group">
                        <label for="first_name">First Name :</label>
                        <input type="text" name="first_name" class="form-control" id="first_name"
                               value=<?php echo "'" . $user[0]['first_name'] . "'"; ?>>
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="first_name">Last Name :</label>
                        <input type="text" name="last_name" class="form-control" id="last_name" value=
                            <?php echo "'" . $user[0]['last_name'] . "'"; ?>>
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="first_name">Birth Date :</label>
                        <input data-provide="datepicker" data-date-format="yyyy/mm/dd" type="text" name="birth_date"
                               class="form-control" id="birth_date"
                               value=<?php echo "'" . $user[0]['birth_date'] . "'"; ?>>
                    </div>
                    <br/>

                    <div class="input-group">
                        <label for="first_name">Gender :</label>
                        <input type="text" name="gender" class="form-control" id="gender"
                               value=<?php echo "'" . $user[0]['gender'] . "'"; ?>>
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="first_name">Password :</label>
                        <input type="password" name="pass" class="form-control" id="pass"
                               value="">
                    </div>
                    <br/>

                    <button class="btn btn-primary" name="profile_changed" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

<?php
if (isset($_POST['profile_changed'])) {
    $password = md5($_POST['pass']);
    $query = callProcedure("UpdateMyAccount", array($student_username, $password, $_POST['first_name'], $_POST['last_name'],
        $_POST['birth_date'], $_POST['gender']));
    if ($password == md5('')) {
        die(formattedMessage("Oops! Password cannot be empty!", 1));
    }
    mysqli_free_result($raw_results);
    mysqli_next_result($conn);

    $updateMyInfo = mysqli_query($conn, $query);
    if ($updateMyInfo == false) {
        die(formattedMessage(DIE_MSG, 1));
    } else {
        echo formattedMessage("Account Information Updated! Redirecting to Your Account!", 2);
        redirect_to('../student');
    }
}
?>