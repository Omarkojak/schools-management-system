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
            Create an Activity
        </div>

        <div class="panel-body">
            <form action="" method="post">

                <br/>
                <div class="input-group">
                    <label for="adate">Date :</label>
                    <input type="text" name="adate" class="form-control" placeholder="" data-provide="datepicker"
                           data-date-format="yyyy/mm/dd">
                </div>

                <br/>
                <div class="input-group">
                    <label for="teacher">Supervising Teacher :</label>
                    <select class="form-control" name="teacher" id="teacher" required>
                        <?php
                        foreach ($all_teachers as $teacher)
                            echo sprintf("<option value='%s'>%s %s %s</option>", $teacher['username'],$teacher['first_name'] , $teacher['middle_name'], $teacher['last_name']);
                        ?>
                    </select>
                </div>

                <div class="input-group">
                    <label for="location">Location :</label>
                    <input type="text" name="location" class="form-control" id="location"
                           value= "">
                </div>
                <br/>


                <br/>
                <div class="input-group">
                    <label for="equipment">Equipment :</label>
                    <textarea class="form-control" name="equipment" rows="5" cols="40"
                              id="equipment"></textarea>
                </div>
                <br/>

                <div class="input-group">
                    <label for="type">Type :</label>
                    <input type="text" name="type" class="form-control" id="type"
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

                <button class="btn btn-primary" name="create_activity" value="create_activity" type="submit">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>


<?php
if (isset($_POST['create_activity'])) {

    $query = sprintf(" INSERT INTO Activities (date, administrator_username, teacher_username,
                                              location, equipment, type, description)
              VALUES  ('%s','%s','%s','%s','%s','%s','%s')",
        $_POST['adate'], $_SESSION['admin_username'],$teacher['username'], $_POST['location'], $_POST['equipment'], $_POST['type'], $_POST['description']);
    $q_ins = mysqli_query($conn, $query);
    if ($q_ins == false) {
        die(formattedMessage(DIE_MSG . mysqli_error($conn) ,1));
    } else {
        $query = sprintf("CALL Create_School_Activity('%s','%s','%s','%s','%s','%s','%s')",
            $_SESSION['admin_username'],$teacher['username'],$_POST['adate'], $_POST['location'], $_POST['equipment'], $_POST['type'],$_POST['description']);
        $q_ins = mysqli_query($conn, $query);
        if ($q_ins == false) {
            die(formattedMessage(DIE_MSG. mysqli_error($conn),1));
        } else {
            echo formattedMessage("Activity Created!", 2);
        }

    }
}
?>
