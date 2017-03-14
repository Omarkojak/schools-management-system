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
        <div class="panel-heading">
            Post an Announcement
        </div>

        <div class="panel-body">
            <form action="" method="post">

                <div class="input-group">
                    <label for="title">Title :</label>
                    <input type="text" name="title" class="form-control" id="title"
                           value= "">
                </div>
                <br/>

                <br/>
                <div class="input-group">
                    <label for="description">Description :</label>
                    <textarea class="form-control" name="description" rows="5" cols="40"
                              id="description"></textarea>
                </div>
                <br/>

                <div class="input-group">
                    <label for="type">Type :</label>
                    <input type="text" name="type" class="form-control" id="type"
                           value= "">
                </div>
                <br/>

                <button class="btn btn-primary" name="submit_announcement" value="submit_announcement" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>


<?php
if (isset($_POST['submit_announcement'])) {

    $query = sprintf(" INSERT INTO Announcements (date, type, description, title, administrator_username)
              VALUES  ('%s','%s','%s','%s','%s')",
               date("Y-m-d"),$_POST['type'], $_POST['description'], $_POST['title'],  $_SESSION['admin_username']);
    $q_ins = mysqli_query($conn, $query);
    if ($q_ins == false) {
       die(formattedMessage(DIE_MSG ,1));
    } else {
        $query = sprintf("CALL Post_Announcement('%s','%s','%s','%s','%s')", $_SESSION['admin_username'],
                                                date("Y-m-d"), $_POST['title'], $_POST['type'], $_POST['description']);
        $q_ins = mysqli_query($conn, $query);
        if ($q_ins == false) {
            die(formattedMessage(DIE_MSG ,1));
        } else {
            echo formattedMessage(sprintf("Announcement Posted on %s!",date("Y-m-d")), 2);
        }

    }
}
?>
