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


<?php

$admin_username = $_SESSION['admin_username'];

$raw_results = mysqli_query($conn, "CALL DisplaySchoolInformation('" . $admin_username . "')");
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG,1));
} else {
    $user = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
}


?>

    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">School Information</div>
            <div class="panel-body">
                <form action="" method="post">
                    <div class="input-group">
                        <label for="name">Scbool Name :</label>
                        <input type="text" name="name" class="form-control" id="name"
                               value=<?php echo "'" . $user[0]['name'] . "'"; ?>>
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="vision">Vision :</label>
                        <textarea class="form-control" name="vision" rows="5" cols="40"
                                  id="vision"></textarea
                            value=<?php echo "'" . $user[0]['vision'] . "'"; ?>>
                    </div>
                    <br/>

                    <br/>
                    <div class="input-group">
                        <label for="mission">Mission :</label>
                        <textarea class="form-control" name="mission" rows="5" cols="40"
                                  id="mission"></textarea
                            value=<?php echo "'" . $user[0]['mission'] . "'"; ?>>
                    </div>
                    <br/>

                    <div class="input-group">
                        <label for="main_language"> Main Language :</label>
                        <input type="text" name="main_language" class="form-control" id="main_language"
                               value=<?php echo "'" . $user[0]['main_language'] . "'"; ?>>
                    </div>

                    <div class="input-group">
                        <label for="type"> Type :</label>
                        <input type="text" name="type" class="form-control" id="type"
                               value=<?php echo "'" . $user[0]['type'] . "'"; ?>>
                    </div>

                    <div class="input-group">
                        <label for="fees"> Fees :</label>
                        <input type="text" name="fees" class="form-control" id="fees"
                               value=<?php echo "'" . $user[0]['fees'] . "'"; ?>>
                    </div>

                    <div class="input-group">
                        <label for="address"> Address :</label>
                        <input type="text" name="address" class="form-control" id="address"
                               value=<?php echo "'" . $user[0]['address'] . "'"; ?>>
                    </div>

                    <div class="input-group">
                        <label for="email"> Email :</label>
                        <input type="text" name="email" class="form-control" id="email"
                               value=<?php echo "'" . $user[0]['email'] . "'"; ?>>
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="general_info">General Info :</label>
                        <textarea class="form-control" name="general_info" rows="5" cols="40"
                                  id="general_info"></textarea
                            value=<?php echo "'" . $user[0]['general_info'] . "'"; ?>>
                    </div>
                    <br/>

                    <button class="btn btn-primary" name="profile_changed" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

<?php
if (isset($_POST['profile_changed'])) {

    $query = callProcedure("Edit_School_Information", array($admin_username,
                                        $_POST['name'], $_POST['vision'], $_POST['mission'],
                                        $_POST['main_language'], $_POST['type'], $_POST['fees'],
                                        $_POST['address'],$_POST['email'], $_POST['general_info']));

    mysqli_free_result($raw_results);
    mysqli_next_result($conn);

    $updateInfo = mysqli_query($conn, $query);
    if ($updateInfo == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        echo formattedMessage("School Information Updated!", 2);
        redirect_to('/editschool');
    }
}
?>