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
$raw_results = mysqli_query($conn, sprintf("CALL ViewSchools('%s')", $parent_username));
if ($raw_results == false)
    die(formattedMessage(DIE_MSG, 1));
$all_schools = mysqli_fetch_all($raw_results, MYSQL_ASSOC);
$children = array();
foreach ($all_schools as $school) {
    $children[$school['child_ssn']][] = $school;
}
?>
<div class="container">
    <?php
    if (count($children) == 0)
        die(formattedMessage("No Children to enroll!"));

    ?>
    <div class="panel panel-primary">

        <div class="panel-heading">
            Enroll Your Child
        </div>

        <div class="panel-body">
            <?php
            foreach ($children as $child) {
                ?>
                <table class="table table-bordered">
                    <h4><?php echo ucfirst($child[0]['first_name']) . " " . ucfirst($child[0]['last_name']); ?></h4>
                    <thead>
                    <th>School Name</th>
                    <th>Enroll</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($child as $c) {
                        echo "<tr>";
                        echo "<td class='col-md-2'>" . $c['name'] . "</td>";
                        echo "<td class='col-md-2'><a href='enroll.php?school_id=" . $c['id']."&child_ssn=".$c['child_ssn'].
                            "' class='btn btn-success' role='button'>Enroll Now</a></td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            <?php } ?>

        </div>
    </div>
</div>

<?php
    if (isset($_GET['school_id']) && isset($_GET['child_ssn'])){
        mysqli_free_result($raw_results);
        mysqli_next_result($conn);
        $school_id = mysqli_real_escape_string($conn,$_GET['school_id']);
        $child_ssn = mysqli_real_escape_string($conn,$_GET['child_ssn']);
        $query = sprintf("CALL ChooseSchool(%s,%s)",$child_ssn,$school_id);
        $res = mysqli_query($conn,$query);
        if ($res == false){
            die(formattedMessage(DIE_MSG.mysqli_error($conn),1));
        }else{
            echo formattedMessage("Successfully Applied!",2);
            redirect_to("enroll.php",1000);
        }
    }
?>