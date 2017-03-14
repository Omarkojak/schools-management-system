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
$raw_results = mysqli_query($conn, "SELECT * FROM Schools");
if ($raw_results == false)
    die(formattedMessage(DIE_MSG, 1));
$all_schools = mysqli_fetch_all($raw_results, MYSQL_ASSOC);

mysqli_free_result($raw_results);
mysqli_next_result($conn);
?>


<div class="container">
    <div class="panel panel-primary">

        <div class="panel-heading">
            Apply For Your Child
        </div>

        <div class="panel-body">
            <div id="parent" class="tab-pane fade in active">
                <form action="applyforchild.php" method="post">
                    <br/>
                    <div class="input-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" class="form-control">
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" class="form-control">
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="ssn">SSN</label>
                        <input type="number" name="ssn" class="form-control">
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="gender">Gender</label>
                        <input type="text" name="gender" class="form-control">
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="bdate">Birth Date</label>
                        <input type="text" name="bdate" class="form-control" placeholder="" data-provide="datepicker"
                               data-date-format="yyyy/mm/dd">
                    </div>

                    <br/>

                    <div class="input-group">
                        <label for="School">School</label>
                        <select class="form-control" name="school_id" id="school_id" required>
                            <?php
                            foreach ($all_schools as $school)
                                echo sprintf("<option value='%s'>%s</option>", $school['id'], $school['name']);
                            ?>
                        </select>
                    </div>

                    <hr/>
                    <button class="btn btn-primary" name="parent_apply" type="submit">Apply</button>
                    <hr/>

                </form>
            </div>
        </div>
    </div>
</div>

<?php
    if(isset($_POST['parent_apply'])){
        $bdate = mysqli_real_escape_string($conn,$_POST['bdate']);
        $gender = mysqli_real_escape_string($conn,$_POST['gender']);
        $ssn = mysqli_real_escape_string($conn,$_POST['ssn']);
        $fname = mysqli_real_escape_string($conn,$_POST['first_name']);
        $lname = mysqli_real_escape_string($conn,$_POST['last_name']);
        $sid = mysqli_real_escape_string($conn,$_POST['school_id']);

        $query = sprintf("CALL ParentApplyForChildren('%s',%s,'%s','%s',%s,'%s','%s')",$parent_username,
            $ssn,$bdate,$gender,$sid,$fname,$lname);
        $raw_results = mysqli_query($conn,$query);
        if ($raw_results == false){
            die(formattedMessage(DIE_MSG,1));
        }else{
            echo formattedMessage("Applied Succesfully!",2);
            redirect_to("index.php",1000);
        }
    }
?>
