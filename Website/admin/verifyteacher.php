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


    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Enter Teacher's Data</div>
            <div class="panel-body">
                <form action="" method="post">

                    <div class="input-group">
                        <label for="first_name">Username :</label>
                        <input type="text" name="username" class="form-control" id="username"
                               value= "">
                    </div>
                    <br/>


                    <div class="input-group">
                        <label for="password">Password :</label>
                        <input type="password" name="pass" class="form-control" id="pass"
                               value= "">
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
    $query = callProcedure("Verify_Teacher_As_Administrator", array($_SESSION['admin_username'],$_GET['username'],$_POST['username'],
        $password, $_GET['yox']));

    if ($password == md5('')) {
        die(formattedMessage("Oops! Password cannot be empty!", 1));
    }
    $updateInfo = mysqli_query($conn, $query);
    if ($updateInfo == false) {
        die(formattedMessage("Oops! An error occured!" . mysqli_error($conn), 1));
    } else {
        echo formattedMessage("Teacher Has Been Verified! Redirecting You to Unverified Teachers!", 2);
        redirect_to('viewunverifiedteachers.php');
    }
}
?>