<?php
session_start();
include 'includes/header.php';
include 'includes/footer.php';
?>

<?php
if (isset($_SESSION['logged_in'])) {
    $loc = 'Location: '.$path.'/index.php';
    header($loc);
}
?>



<?php
$raw_results = mysqli_query($conn, "SELECT * FROM Schools");
if ($raw_results == false)
    die(formattedMessage(DIE_MSG,1));
$all_schools = mysqli_fetch_all($raw_results, MYSQL_ASSOC);
?>

<div class="container">
    <div class="page-header">
        <h1>Register</h1>
    </div>

    <?php
    if (isset($_POST['parent_register'])) {
        $username = mysqli_real_escape_string($conn,$_POST['username']);
        $password = mysqli_real_escape_string($conn,md5($_POST['password']));
        $first_name = mysqli_real_escape_string($conn,$_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn,$_POST['last_name']);
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $address = mysqli_real_escape_string($conn,$_POST['address']);
        $hnumber = mysqli_real_escape_string($conn,$_POST['hnumber']);
        $mnumbers = explode(',', $_POST['mnumbers']);

        if (check_username_exists_parent($conn, $username)) {
            echo(formattedMessage("Username Already Exists!", 1));
        } else if (check_email_exists_parent($conn, $email)) {
            echo(formattedMessage("Email Already Exists!", 1));
        } else {
            $query = sprintf("CALL Parent_Signup('%s','%s','%s','%s','%s','%s','%s');",
                $first_name, $last_name, $email, $address, $hnumber, $username, $password);
            $res = mysqli_query($conn, $query);
            if ($res == false) {
                die(formattedMessage(DIE_MSG,1));
            } else {
                foreach ($mnumbers as $num) {
                    $query = sprintf("CALL ParentAddPhoneNumber('%s','%s')", $username, $num);
                    $res = mysqli_query($conn, $query);
                    if ($res == false) {
                        die(formattedMessage(DIE_MSG,1));
                    }
                }

                echo formattedMessage("You successfully registered!", 2);
            }
        }
    } else if (isset($_POST['teacher_register'])) {

        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $gender = $_POST['gender'];
        $bdate = $_POST['bdate'];
        $sid = $_POST['school_id'];

        $username = '#UnverifiedTeacher' . rand(10, 10000000) . '#';
        while (check_username_exists($conn, $username))
            $username = '#UnverifiedTeacher' . rand(10, 10000000) . '#';
        if (check_email_exists($conn, $email)) {
            echo(formattedMessage("Email Already Exists!", 1));
        } else {

            $query = sprintf("CALL TeacherSignUp('%s','%s','%s','%s','%s','%s','%s','%s',%d)", $username, $first_name, $middle_name,
                $last_name, $bdate, $address, $email, $gender, $sid);
            $res = mysqli_query($conn, $query);
            if ($res == false) {
                die(formattedMessage("Oops!" . mysqli_error($conn), 1));
            } else {
                echo formattedMessage("You successfully registered!", 2);

            }
        }
    }
    ?>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#parent">Parent</a></li>
        <li><a data-toggle="tab" href="#teacher">Teacher</a></li>
    </ul>

    <div class="tab-content">

        <div id="parent" class="tab-pane fade in active">
            <form action="" method="post">

                <br/>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <br/>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

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
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <br/>
                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" class="form-control">
                </div>

                <br/>
                <div class="input-group">
                    <label for="hnumber">Home Number</label>
                    <input type="text" name="hnumber" class="form-control">
                </div>

                <br/>
                <div class="input-group">
                    <label for="mnumbers">Mobile Number(s)</label>
                    <input type="text" name="mnumbers" class="form-control" placeholder="Comma seperated ..">
                </div>

                <hr/>
                <button class="btn btn-primary" name="parent_register" type="submit">Register</button>
                <hr/>

            </form>


        </div>

        <div id="teacher" class="tab-pane fade">
            <table class="table table-user-information">
                <form action="" method="post">

                    <br/>
                    <div class="input-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" class="form-control">
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control">
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" class="form-control">
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" class="form-control">
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
                    <button class="btn btn-primary" name="teacher_register" type="submit">Register</button>
                    <hr/>

                </form>

        </div>

    </div>
</div>


<?php
function check_username_exists($conn, $username)
{
    $query = sprintf("SELECT * FROM Employees WHERE username = '%s'", $username);
    $res = mysqli_query($conn, $query);
    if ($res == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        return mysqli_num_rows($res) > 0;
    }
}

function check_email_exists($conn, $email)
{
    $query = sprintf("SELECT * FROM Employees WHERE email = '%s'", $email);
    $res = mysqli_query($conn, $query);
    if ($res == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        return mysqli_num_rows($res) > 0;
    }
}


function check_username_exists_parent($conn, $username)
{
    $query = sprintf("SELECT * FROM Parents WHERE username = '%s'", $username);
    $res = mysqli_query($conn, $query);
    if ($res == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        return mysqli_num_rows($res) > 0;
    }
}

function check_email_exists_parent($conn, $email)
{
    $query = sprintf("SELECT * FROM Parents WHERE email = '%s'", $email);
    $res = mysqli_query($conn, $query);
    if ($res == false) {
        die(formattedMessage(DIE_MSG,1));
    } else {
        return mysqli_num_rows($res) > 0;
    }
}

?>

