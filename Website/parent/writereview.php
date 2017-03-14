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
            Write Review For School : <?php echo $_GET['school_id']; ?>
        </div>

        <div class="panel-body">
            <form action="" method="post">
                <div class="input-group">
                    <label for="description">Decription:</label>
                    <textarea class="form-control" name="description" rows="5" cols="40"
                              id="description"></textarea>
                </div>

                <br/>

                <button class="btn btn-primary" name="submit_review" value="submit_review" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['submit_review'])) {

    $school_id = mysqli_real_escape_string($conn,$_GET['school_id']);
    $desc = mysqli_real_escape_string($conn,$_POST['description']);

    $query = sprintf("CALL ReviewSchools('%s','%s','%s')", $parent_username,$school_id,$desc);
    $q_ins = mysqli_query($conn, $query);
    if ($q_ins == false) {
        echo formattedMessage("Oops! You have already reviewed this school!",1);
        redirect_to("reviewschools.php", 500);
    } else {
        echo formattedMessage("Review Posted!", 2);
        redirect_to("reviewschools.php", 1000);
    }
}
?>

