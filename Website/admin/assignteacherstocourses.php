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
$raw_results = mysqli_query($conn, "SELECT * FROM Employees");
$all_teachers = mysqli_fetch_all($raw_results, MYSQL_ASSOC);

?>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Choose A teacher For The Course
            </div>

            <div class="panel-body">
                <form action="" method="post">


                    <br/>
                    <div class="input-group">
                        <label for="teacher">Teacher :</label>
                        <select class="form-control" name="teacher" id="teacher" required>
                            <?php
                            foreach ($all_teachers as $teacher)
                                echo sprintf("<option value='%s'>%s %s %s</option>", $teacher['username'],$teacher['first_name'] , $teacher['middle_name'], $teacher['last_name']);
                            ?>
                        </select>
                    </div>

                    <button class="btn btn-primary" name="choose_teacher" value="choose_teacher" type="submit">
                        Submit
                    </button>

                </form>
            </div>
        </div>
    </div>
<?php
if (isset($_POST['choose_teacher'])) {

        $query = sprintf("CALL Assign_Teacher_To_Courses('%s','%d','%s')",
            $_SESSION['admin_username'],$_GET['code'],$teacher['username']);
        $q_ins = mysqli_query($conn, $query);
        if ($q_ins == false) {
            die(formattedMessage(DIE_MSG. mysqli_error($conn),1));
        } else {
            echo formattedMessage("Teacher Assigned To The Course! Redirecting You To Your School's Courses", 2);
            redirect_to('viewcourses.php');
        }
}
?>