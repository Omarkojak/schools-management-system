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


    $query = callProcedure("Accept_Or_Reject_Child", array($_SESSION['admin_username'],$_GET['ssn'],$_GET['parent'], $_GET['response']));

    $updateMyInfo = mysqli_query($conn, $query);
    if ($updateMyInfo == false) {
        die(formattedMessage("Oops! An error occured!" . mysqli_error($conn), 1));
    } else {
        if ($_GET['response']==1) {
            echo formattedMessage("Child Has Been Accepted. Redirecting You to Unverified Applications!", 2);
        }
        else {
            echo formattedMessage("Child Has Been Rejected. Redirecting You to Unverified Applications!", 2);
            }
        redirect_to('viewunverifiedstudents.php');
    }

?>